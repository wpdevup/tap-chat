<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Renders the first-party analytics dashboard (the "Dashboard" sub-tab).
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
     * Build the URL for a range preset while preserving the analytics tab hash.
     */
    private function range_url( $days ) {
        $url = add_query_arg( 'tc_range', (int) $days, admin_url( 'options-general.php?page=tap-chat' ) );
        return $url . '#analytics/overview';
    }

    public function render() {
        $settings = get_option( 'tap_chat_settings', array() );
        $click_enabled   = isset( $settings['enable_click_analytics'] ) ? ( 'yes' === $settings['enable_click_analytics'] ) : true;
        $traffic_enabled = isset( $settings['enable_traffic_analytics'] ) ? ( 'yes' === $settings['enable_traffic_analytics'] ) : false;

        $range_days = $this->current_range();

        // Site-timezone "today" so ranges line up with how events are bucketed.
        $today      = current_time( 'Y-m-d' );
        $range_from = gmdate( 'Y-m-d', strtotime( $today ) - ( ( $range_days - 1 ) * DAY_IN_SECONDS ) );

        // Previous equal-length period, immediately before the current one.
        $prev_to    = gmdate( 'Y-m-d', strtotime( $range_from ) - DAY_IN_SECONDS );
        $prev_from  = gmdate( 'Y-m-d', strtotime( $prev_to ) - ( ( $range_days - 1 ) * DAY_IN_SECONDS ) );

        // Fixed calendar buckets for the small cards.
        $week_start  = gmdate( 'Y-m-d', strtotime( 'monday this week', strtotime( $today ) ) );
        $month_start = gmdate( 'Y-m-01', strtotime( $today ) );

        $clicks_range   = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $range_from, $today );
        $clicks_prev    = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $prev_from, $prev_to );
        $clicks_total   = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK );
        $clicks_today   = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $today, $today );
        $clicks_week    = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $week_start, $today );
        $clicks_month   = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $month_start, $today );

        echo '<div class="tap-chat-analytics-dashboard">';

        // First-run empty state: nothing recorded yet.
        if ( ! Analytics_Store::has_data() ) {
            $this->render_empty_state( $click_enabled );
            echo '</div>';
            return;
        }

        $this->render_range_selector( $range_days );

        if ( ! $click_enabled ) {
            $this->render_notice(
                __( 'Click tracking is currently turned off. Turn it on in the Settings sub-tab to keep collecting data.', 'tap-chat' )
            );
        }

        // Hero + small cards.
        echo '<div class="tc-cards">';
        $this->render_hero_card( $clicks_range, $clicks_prev, $range_days );
        $this->render_stat_card( __( 'Total Clicks', 'tap-chat' ), $clicks_total, __( 'all time', 'tap-chat' ) );
        $this->render_stat_card( __( 'Clicks Today', 'tap-chat' ), $clicks_today, $today );
        $this->render_stat_card( __( 'Clicks This Week', 'tap-chat' ), $clicks_week, __( 'since Monday', 'tap-chat' ) );
        $this->render_stat_card( __( 'Clicks This Month', 'tap-chat' ), $clicks_month, gmdate( 'F Y', strtotime( $today ) ) );
        echo '</div>';

        // CTR funnel.
        $this->render_funnel( $traffic_enabled, $range_from, $today, $range_days );

        // Trend.
        $this->render_trend( $range_from, $today );

        echo '<div class="tc-two-col">';
        $this->render_top_pages( $range_from, $today );
        $this->render_by_device( $range_from, $today );
        echo '</div>';

        echo '</div>';
    }

    private function render_empty_state( $click_enabled ) {
        echo '<div class="tc-empty-state">';
        echo '<div class="tc-empty-icon" aria-hidden="true"><span class="dashicons dashicons-chart-bar"></span></div>';
        echo '<h3 class="tc-empty-title">' . esc_html__( 'No clicks recorded yet', 'tap-chat' ) . '</h3>';

        if ( $click_enabled ) {
            echo '<p class="tc-empty-text">' . esc_html__( 'Your chat button is live and tracking is on. As soon as a visitor taps it, their click will show up here — check back shortly.', 'tap-chat' ) . '</p>';
        } else {
            echo '<p class="tc-empty-text">' . esc_html__( 'Click tracking is currently turned off, so nothing is being recorded. Turn it on in the Settings sub-tab to start collecting data.', 'tap-chat' ) . '</p>';
        }

        echo '</div>';
    }

    private function render_range_selector( $active ) {
        echo '<div class="tc-range-selector">';
        echo '<span class="tc-range-label">' . esc_html__( 'Date range:', 'tap-chat' ) . '</span>';
        foreach ( $this->ranges() as $days ) {
            $class = ( $days === $active ) ? 'tc-range active' : 'tc-range';
            printf(
                '<a href="%s" class="%s">%s</a>',
                esc_url( $this->range_url( $days ) ),
                esc_attr( $class ),
                sprintf(
                    /* translators: %d: number of days */
                    esc_html__( 'Last %d days', 'tap-chat' ),
                    (int) $days
                )
            );
        }
        echo '</div>';
    }

    private function render_notice( $message ) {
        echo '<div class="tc-inline-notice">' . esc_html( $message ) . '</div>';
    }

    private function render_hero_card( $current, $previous, $range_days ) {
        echo '<div class="tc-card tc-card-hero">';
        echo '<div class="tc-card-value">' . esc_html( $this->fmt( $current ) ) . '</div>';
        echo '<div class="tc-card-label">' . sprintf(
            /* translators: %d: number of days */
            esc_html__( 'Chat Clicks (last %d days)', 'tap-chat' ),
            (int) $range_days
        ) . '</div>';
        echo $this->change_badge_html( $current, $previous ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_html internally
        echo '</div>';
    }

    private function render_stat_card( $label, $value, $sub ) {
        echo '<div class="tc-card">';
        echo '<div class="tc-card-value">' . esc_html( $this->fmt( $value ) ) . '</div>';
        echo '<div class="tc-card-label">' . esc_html( $label ) . '</div>';
        if ( $sub ) {
            echo '<div class="tc-card-sub">' . esc_html( $sub ) . '</div>';
        }
        echo '</div>';
    }

    /**
     * Percentage-change badge vs the previous period.
     */
    private function change_badge_html( $current, $previous ) {
        if ( $previous <= 0 ) {
            if ( $current <= 0 ) {
                return '<div class="tc-change tc-change-flat">' . esc_html__( 'No data yet', 'tap-chat' ) . '</div>';
            }
            return '<div class="tc-change tc-change-up">' . esc_html__( 'New activity', 'tap-chat' ) . '</div>';
        }

        $pct = ( ( $current - $previous ) / $previous ) * 100;
        $rounded = round( abs( $pct ) );

        if ( 0 === (int) $rounded ) {
            return '<div class="tc-change tc-change-flat">' . esc_html__( 'No change vs previous period', 'tap-chat' ) . '</div>';
        }

        $up = $pct > 0;
        $class = $up ? 'tc-change tc-change-up' : 'tc-change tc-change-down';
        $arrow = $up ? '&#8593;' : '&#8595;';

        return '<div class="' . esc_attr( $class ) . '">'
            . $arrow . ' ' . esc_html( $rounded ) . '% '
            . esc_html__( 'compared to previous period', 'tap-chat' )
            . '</div>';
    }

    private function render_funnel( $traffic_enabled, $from, $to, $range_days ) {
        echo '<div class="tc-panel">';
        echo '<h3 class="tc-panel-title">' . esc_html__( 'Conversion funnel', 'tap-chat' ) . '</h3>';

        if ( ! $traffic_enabled ) {
            echo '<div class="tc-inline-notice">'
                . esc_html__( 'Turn on "Traffic & CTR tracking" in the Settings sub-tab to measure button views, unique visitors and click-through rate. Click totals keep working without it.', 'tap-chat' )
                . '</div>';
            echo '</div>';
            return;
        }

        $visitors = Analytics_Store::get_total( Analytics_Store::EVENT_VISITOR, $from, $to );
        $views    = Analytics_Store::get_total( Analytics_Store::EVENT_VIEW, $from, $to );
        $clicks   = Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $from, $to );
        $ctr      = ( $views > 0 ) ? ( $clicks / $views ) * 100 : 0;

        echo '<div class="tc-funnel">';
        $this->render_funnel_item( __( 'Visitors', 'tap-chat' ), $this->fmt( $visitors ) );
        $this->render_funnel_item( __( 'Button Views', 'tap-chat' ), $this->fmt( $views ) );
        $this->render_funnel_item( __( 'Clicks', 'tap-chat' ), $this->fmt( $clicks ) );
        $this->render_funnel_item( __( 'CTR', 'tap-chat' ), number_format_i18n( $ctr, 2 ) . '%' );
        echo '</div>';

        echo '<p class="tc-panel-note">' . sprintf(
            /* translators: %d: number of days */
            esc_html__( 'Based on the last %d days. CTR = Clicks ÷ Button Views.', 'tap-chat' ),
            (int) $range_days
        ) . '</p>';

        echo '</div>';
    }

    private function render_funnel_item( $label, $value ) {
        echo '<div class="tc-funnel-item">';
        echo '<div class="tc-funnel-value">' . esc_html( $value ) . '</div>';
        echo '<div class="tc-funnel-label">' . esc_html( $label ) . '</div>';
        echo '</div>';
    }

    private function render_trend( $from, $to ) {
        $series = Analytics_Store::get_daily_series( Analytics_Store::EVENT_CLICK, $from, $to );
        if ( empty( $series ) ) {
            return;
        }

        $max = max( 1, max( $series ) );

        echo '<div class="tc-panel">';
        echo '<h3 class="tc-panel-title">' . esc_html__( 'Clicks over time', 'tap-chat' ) . '</h3>';
        echo '<div class="tc-bars">';
        foreach ( $series as $date => $hits ) {
            $height = max( 2, (int) round( ( $hits / $max ) * 100 ) );
            $title = sprintf(
                /* translators: 1: date, 2: click count */
                esc_attr__( '%1$s: %2$s clicks', 'tap-chat' ),
                esc_attr( $date ),
                esc_attr( $this->fmt( $hits ) )
            );
            printf(
                '<span class="tc-bar" style="height:%d%%" title="%s"><span class="tc-bar-fill"></span></span>',
                (int) $height,
                esc_attr( $title )
            );
        }
        echo '</div>';
        echo '</div>';
    }

    private function render_top_pages( $from, $to ) {
        $rows = Analytics_Store::get_top_pages( Analytics_Store::EVENT_CLICK, $from, $to, 10 );

        echo '<div class="tc-panel">';
        echo '<h3 class="tc-panel-title">' . esc_html__( 'Top Pages by Clicks', 'tap-chat' ) . '</h3>';

        if ( empty( $rows ) ) {
            echo '<p class="tc-empty">' . esc_html__( 'No clicks recorded in this range yet.', 'tap-chat' ) . '</p>';
            echo '</div>';
            return;
        }

        echo '<table class="tc-table"><thead><tr>';
        echo '<th>' . esc_html__( 'Page', 'tap-chat' ) . '</th>';
        echo '<th class="tc-num">' . esc_html__( 'Clicks', 'tap-chat' ) . '</th>';
        echo '</tr></thead><tbody>';

        foreach ( $rows as $row ) {
            $label = $row['page_key'];
            echo '<tr>';
            echo '<td><span class="tc-page-path">' . esc_html( $label ) . '</span>';
            if ( ! empty( $row['page_title'] ) ) {
                echo '<span class="tc-page-title">' . esc_html( $row['page_title'] ) . '</span>';
            }
            echo '</td>';
            echo '<td class="tc-num">' . esc_html( $this->fmt( $row['hits'] ) ) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    }

    private function render_by_device( $from, $to ) {
        $data = Analytics_Store::get_by_device( Analytics_Store::EVENT_CLICK, $from, $to );

        echo '<div class="tc-panel">';
        echo '<h3 class="tc-panel-title">' . esc_html__( 'Clicks by Device', 'tap-chat' ) . '</h3>';

        if ( empty( $data ) ) {
            echo '<p class="tc-empty">' . esc_html__( 'No clicks recorded in this range yet.', 'tap-chat' ) . '</p>';
            echo '</div>';
            return;
        }

        $total = array_sum( $data );
        $labels = array(
            'desktop' => __( 'Desktop', 'tap-chat' ),
            'mobile'  => __( 'Mobile', 'tap-chat' ),
            'tablet'  => __( 'Tablet', 'tap-chat' ),
            'unknown' => __( 'Unknown', 'tap-chat' ),
        );

        echo '<ul class="tc-device-list">';
        foreach ( $data as $device => $hits ) {
            $label = isset( $labels[ $device ] ) ? $labels[ $device ] : ucfirst( $device );
            $pct = ( $total > 0 ) ? round( ( $hits / $total ) * 100 ) : 0;
            echo '<li class="tc-device-row">';
            echo '<span class="tc-device-name">' . esc_html( $label ) . '</span>';
            echo '<span class="tc-device-bar"><span class="tc-device-fill" style="width:' . (int) $pct . '%"></span></span>';
            echo '<span class="tc-device-val">' . esc_html( $this->fmt( $hits ) ) . ' (' . (int) $pct . '%)</span>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}
