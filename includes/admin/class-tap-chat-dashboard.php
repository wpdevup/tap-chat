<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Renders the first-party analytics report (the "Analytics" view).
 *
 * Read-only report built entirely from Analytics_Store aggregate queries.
 * Server-rendered so it works without JavaScript; the only interactivity is a
 * date-range selector implemented as plain links.
 */
class Analytics_Dashboard {

    /**
     * Allowed range presets, in days.
     */
    private function ranges() {
        return array( 7, 30, 90 );
    }

    private function current_range() {
        $range = isset( $_GET['tc_range'] ) ? absint( $_GET['tc_range'] ) : 30; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return in_array( $range, $this->ranges(), true ) ? $range : 30;
    }

    private function fmt( $n ) {
        return number_format_i18n( (int) $n );
    }

    /**
     * URL of a range preset, keeping the analytics view in the hash.
     */
    private function range_url( $days ) {
        $url = add_query_arg( 'tc_range', (int) $days, admin_url( 'options-general.php?page=tap-chat' ) );
        return $url . '#analytics/overview';
    }

    public function render() {
        $settings        = get_option( 'tap_chat_settings', array() );
        $click_enabled   = isset( $settings['enable_click_analytics'] ) ? ( 'yes' === $settings['enable_click_analytics'] ) : true;
        $traffic_enabled = isset( $settings['enable_traffic_analytics'] ) ? ( 'yes' === $settings['enable_traffic_analytics'] ) : false;
        $has_data        = Analytics_Store::has_data();
        $range_days      = $this->current_range();

        Admin_UI::page_header(
            __( 'Analytics', 'tap-chat' ),
            __( 'Chat button activity recorded on this site. No cookies, and the data never leaves your server.', 'tap-chat' ),
            $has_data ? $this->range_selector_html( $range_days ) : ''
        );

        // First-run empty state: nothing recorded yet.
        if ( ! $has_data ) {
            $this->render_empty_state( $click_enabled );
            return;
        }

        // Site-timezone "today" so ranges line up with how events are bucketed.
        $today      = current_time( 'Y-m-d' );
        $range_from = gmdate( 'Y-m-d', strtotime( $today ) - ( ( $range_days - 1 ) * DAY_IN_SECONDS ) );

        // Previous equal-length period, immediately before the current one.
        $prev_to   = gmdate( 'Y-m-d', strtotime( $range_from ) - DAY_IN_SECONDS );
        $prev_from = gmdate( 'Y-m-d', strtotime( $prev_to ) - ( ( $range_days - 1 ) * DAY_IN_SECONDS ) );

        // Fixed calendar buckets for the small cards.
        $week_start  = gmdate( 'Y-m-d', strtotime( 'monday this week', strtotime( $today ) ) );
        $month_start = gmdate( 'Y-m-01', strtotime( $today ) );

        $clicks_range = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $range_from, $today );
        $clicks_prev  = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $prev_from, $prev_to );
        $clicks_total = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK );
        $clicks_today = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $today, $today );
        $clicks_week  = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $week_start, $today );
        $clicks_month = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $month_start, $today );

        if ( ! $click_enabled ) {
            Admin_UI::alert( 'warning', esc_html__( 'Click tracking is currently turned off. Turn it on under Tracking to keep collecting data.', 'tap-chat' ) );
        }

        echo '<div class="tap-chat-stats">';
        $this->render_hero_card( $clicks_range, $clicks_prev, $range_days );
        echo '<div class="tap-chat-stats__grid">';
        $this->render_stat_card( __( 'Total Clicks', 'tap-chat' ), $clicks_total, __( 'all time', 'tap-chat' ) );
        $this->render_stat_card( __( 'Clicks Today', 'tap-chat' ), $clicks_today, date_i18n( get_option( 'date_format' ), strtotime( $today ) ) );
        $this->render_stat_card( __( 'Clicks This Week', 'tap-chat' ), $clicks_week, __( 'since Monday', 'tap-chat' ) );
        $this->render_stat_card( __( 'Clicks This Month', 'tap-chat' ), $clicks_month, date_i18n( 'F Y', strtotime( $today ) ) );
        echo '</div></div>';

        $this->render_funnel( $traffic_enabled, $range_from, $today, $range_days );
        $this->render_trend( $range_from, $today );

        echo '<div class="tap-chat-report-grid">';
        $this->render_top_pages( $range_from, $today );
        $this->render_by_device( $range_from, $today );
        echo '</div>';
    }

    private function render_empty_state( $click_enabled ) {
        echo '<div class="tap-chat-card tap-chat-empty"><span class="tap-chat-empty__icon">';
        Admin_Icons::render( 'chart' );
        echo '</span>';
        echo '<h2 class="tap-chat-empty__title">' . esc_html__( 'No clicks recorded yet', 'tap-chat' ) . '</h2>';
        if ( $click_enabled ) {
            echo '<p class="tap-chat-empty__text">' . esc_html__( 'Your chat button is live and tracking is on. As soon as a visitor taps it, their click will show up here — check back shortly.', 'tap-chat' ) . '</p>';
        } else {
            echo '<p class="tap-chat-empty__text">' . esc_html__( 'Click tracking is currently turned off, so nothing is being recorded. Turn it on under Tracking to start collecting data.', 'tap-chat' ) . '</p>';
            printf( '<a class="tap-chat-btn tap-chat-btn--ghost" href="#analytics/settings" data-tap-chat-goto="tracking">%s</a>', esc_html__( 'Open tracking settings', 'tap-chat' ) );
        }
        echo '</div>';
    }

    private function range_selector_html( $active ) {
        $html = '<span class="tap-chat-seg tap-chat-seg--text" role="group" aria-label="' . esc_attr__( 'Date range:', 'tap-chat' ) . '">';
        foreach ( $this->ranges() as $days ) {
            $html .= sprintf(
                '<a href="%1$s" class="tap-chat-seg__btn"%2$s>%3$s</a>',
                esc_url( $this->range_url( $days ) ),
                $days === $active ? ' aria-current="true"' : '',
                /* translators: %d: number of days */
                esc_html( sprintf( __( 'Last %d days', 'tap-chat' ), (int) $days ) )
            );
        }
        return $html . '</span>';
    }

    private function render_hero_card( $current, $previous, $range_days ) {
        echo '<div class="tap-chat-stat tap-chat-stat--hero">';
        printf(
            '<span class="tap-chat-stat__label">%s</span>',
            /* translators: %d: number of days */
            esc_html( sprintf( __( 'Chat Clicks (last %d days)', 'tap-chat' ), (int) $range_days ) )
        );
        echo '<span class="tap-chat-stat__value">' . esc_html( $this->fmt( $current ) ) . '</span>';
        $this->render_change_badge( $current, $previous );
        echo '</div>';
    }

    private function render_stat_card( $label, $value, $sub ) {
        echo '<div class="tap-chat-stat">';
        echo '<span class="tap-chat-stat__label">' . esc_html( $label ) . '</span>';
        echo '<span class="tap-chat-stat__value">' . esc_html( $this->fmt( $value ) ) . '</span>';
        if ( $sub ) {
            echo '<span class="tap-chat-stat__sub">' . esc_html( $sub ) . '</span>';
        }
        echo '</div>';
    }

    /**
     * Percentage-change badge vs the previous period.
     */
    private function render_change_badge( $current, $previous ) {
        if ( $previous <= 0 ) {
            if ( $current <= 0 ) {
                echo '<span class="tap-chat-change">' . esc_html__( 'No data yet', 'tap-chat' ) . '</span>';
                return;
            }
            echo '<span class="tap-chat-change tap-chat-change--up">' . esc_html__( 'New activity', 'tap-chat' ) . '</span>';
            return;
        }

        $pct     = ( ( $current - $previous ) / $previous ) * 100;
        $rounded = round( abs( $pct ) );

        if ( 0 === (int) $rounded ) {
            echo '<span class="tap-chat-change">' . esc_html__( 'No change vs previous period', 'tap-chat' ) . '</span>';
            return;
        }

        $up = $pct > 0;
        printf( '<span class="tap-chat-change tap-chat-change--%s">', $up ? 'up' : 'down' );
        Admin_Icons::render( $up ? 'arrow-up' : 'arrow-down' );
        echo esc_html( number_format_i18n( $rounded ) . '% ' . __( 'compared to previous period', 'tap-chat' ) ) . '</span>';
    }

    private function render_funnel( $traffic_enabled, $from, $to, $range_days ) {
        echo '<section class="tap-chat-card tap-chat-panel">';
        echo '<h2 class="tap-chat-card__title">' . esc_html__( 'Conversion funnel', 'tap-chat' ) . '</h2>';

        if ( ! $traffic_enabled ) {
            Admin_UI::alert( 'info', esc_html__( 'Turn on "Traffic & CTR tracking" under Tracking to measure button views, unique visitors and click-through rate. Click totals keep working without it.', 'tap-chat' ) );
            echo '</section>';
            return;
        }

        $visitors = Analytics_Store::get_total( Analytics_Store::EVENT_VISITOR, $from, $to );
        $views    = Analytics_Store::get_total( Analytics_Store::EVENT_VIEW, $from, $to );
        $clicks   = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $from, $to );
        $ctr      = ( $views > 0 ) ? ( $clicks / $views ) * 100 : 0;

        echo '<ol class="tap-chat-funnel">';
        $this->render_funnel_item( 'users', __( 'Visitors', 'tap-chat' ), $this->fmt( $visitors ) );
        $this->render_funnel_item( 'eye', __( 'Button Views', 'tap-chat' ), $this->fmt( $views ) );
        $this->render_funnel_item( 'chat', __( 'Clicks', 'tap-chat' ), $this->fmt( $clicks ) );
        $this->render_funnel_item( 'activity', __( 'CTR', 'tap-chat' ), number_format_i18n( $ctr, 2 ) . '%', true );
        echo '</ol>';

        echo '<p class="tap-chat-card__note">' . esc_html(
            sprintf(
                /* translators: %d: number of days */
                __( 'Based on the last %d days. CTR = Clicks ÷ Button Views.', 'tap-chat' ),
                (int) $range_days
            )
        ) . '</p>';
        echo '</section>';
    }

    private function render_funnel_item( $icon, $label, $value, $highlight = false ) {
        echo '<li class="tap-chat-funnel__item' . ( $highlight ? ' is-result' : '' ) . '"><span class="tap-chat-funnel__label">';
        Admin_Icons::render( $icon );
        echo esc_html( $label ) . '</span>';
        echo '<span class="tap-chat-funnel__value">' . esc_html( $value ) . '</span></li>';
    }

    private function render_trend( $from, $to ) {
        $series = Analytics_Store::get_daily_series( Analytics_Store::EVENT_CLICK, $from, $to );
        if ( empty( $series ) ) {
            return;
        }

        $max = max( 1, max( $series ) );

        echo '<section class="tap-chat-card tap-chat-panel">';
        echo '<div class="tap-chat-panel__head"><h2 class="tap-chat-card__title">' . esc_html__( 'Clicks over time', 'tap-chat' ) . '</h2>';
        /* translators: %s: peak clicks in a day */
        echo '<span class="tap-chat-panel__meta">' . esc_html( sprintf( __( 'peak %s/day', 'tap-chat' ), $this->fmt( max( $series ) ) ) ) . '</span></div>';
        echo '<div class="tap-chat-bars">';
        foreach ( $series as $date => $hits ) {
            $height = max( 2, (int) round( ( $hits / $max ) * 100 ) );
            $title  = sprintf(
                /* translators: 1: date, 2: click count */
                __( '%1$s: %2$s clicks', 'tap-chat' ),
                date_i18n( get_option( 'date_format' ), strtotime( $date ) ),
                $this->fmt( $hits )
            );
            printf(
                '<span class="tap-chat-bars__bar" title="%1$s"><span class="tap-chat-bars__fill" style="height:%2$d%%"></span></span>',
                esc_attr( $title ),
                (int) $height
            );
        }
        echo '</div>';
        $dates = array_keys( $series );
        printf(
            '<div class="tap-chat-bars__axis"><span>%1$s</span><span>%2$s</span></div>',
            esc_html( date_i18n( get_option( 'date_format' ), strtotime( reset( $dates ) ) ) ),
            esc_html( date_i18n( get_option( 'date_format' ), strtotime( end( $dates ) ) ) )
        );
        echo '</section>';
    }

    private function render_top_pages( $from, $to ) {
        $rows = Analytics_Store::get_top_pages( Analytics_Store::EVENT_CLICK, $from, $to, 10 );

        echo '<section class="tap-chat-card tap-chat-panel">';
        echo '<h2 class="tap-chat-card__title">' . esc_html__( 'Top Pages by Clicks', 'tap-chat' ) . '</h2>';

        if ( empty( $rows ) ) {
            echo '<p class="tap-chat-panel__empty">' . esc_html__( 'No clicks recorded in this range yet.', 'tap-chat' ) . '</p>';
            echo '</section>';
            return;
        }

        echo '<div class="tap-chat-table-wrap"><table class="tap-chat-table"><thead><tr>';
        echo '<th scope="col">' . esc_html__( 'Page', 'tap-chat' ) . '</th>';
        echo '<th scope="col" class="tap-chat-num">' . esc_html__( 'Clicks', 'tap-chat' ) . '</th>';
        echo '</tr></thead><tbody>';

        foreach ( $rows as $row ) {
            echo '<tr><td>';
            if ( ! empty( $row['page_title'] ) ) {
                echo '<span class="tap-chat-table__title">' . esc_html( $row['page_title'] ) . '</span>';
            }
            echo '<span class="tap-chat-table__path">' . esc_html( $row['page_key'] ) . '</span>';
            echo '</td><td class="tap-chat-num">' . esc_html( $this->fmt( $row['hits'] ) ) . '</td></tr>';
        }

        echo '</tbody></table></div>';
        echo '</section>';
    }

    private function render_by_device( $from, $to ) {
        $data = Analytics_Store::get_by_device( Analytics_Store::EVENT_CLICK, $from, $to );

        echo '<section class="tap-chat-card tap-chat-panel">';
        echo '<h2 class="tap-chat-card__title">' . esc_html__( 'Clicks by Device', 'tap-chat' ) . '</h2>';

        if ( empty( $data ) ) {
            echo '<p class="tap-chat-panel__empty">' . esc_html__( 'No clicks recorded in this range yet.', 'tap-chat' ) . '</p>';
            echo '</section>';
            return;
        }

        $total  = array_sum( $data );
        $labels = array(
            'desktop' => array( __( 'Desktop', 'tap-chat' ), 'monitor' ),
            'mobile'  => array( __( 'Mobile', 'tap-chat' ), 'phone' ),
            'tablet'  => array( __( 'Tablet', 'tap-chat' ), 'tablet' ),
            'unknown' => array( __( 'Unknown', 'tap-chat' ), 'help' ),
        );

        echo '<ul class="tap-chat-devices">';
        foreach ( $data as $device => $hits ) {
            $meta = isset( $labels[ $device ] ) ? $labels[ $device ] : array( ucfirst( $device ), 'help' );
            $pct  = ( $total > 0 ) ? round( ( $hits / $total ) * 100 ) : 0;
            echo '<li class="tap-chat-devices__row"><span class="tap-chat-devices__name">';
            Admin_Icons::render( $meta[1] );
            echo esc_html( $meta[0] ) . '</span>';
            echo '<span class="tap-chat-devices__val">' . esc_html( $this->fmt( $hits ) ) . ' <span class="tap-chat-devices__pct">' . esc_html( number_format_i18n( $pct ) . '%' ) . '</span></span>';
            echo '<span class="tap-chat-devices__bar"><span class="tap-chat-devices__fill" style="width:' . (int) $pct . '%"></span></span>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</section>';
    }
}
