<?php
/**
 * Admin screen: menu, assets, the settings shell, notices and the dashboard widget.
 *
 * @package Tap_Chat
 */

namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin {

    const SCREEN_ID = 'settings_page_tap-chat';

    private $settings;
    private $fields;
    private $review;

    public function __construct() {
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-icons.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-ui.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-settings.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-fields.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-review.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-dashboard.php';

        $this->settings = new Admin_Settings();
        $this->fields   = new Admin_Fields();
        $this->review   = new Review_Notice();

        add_action( 'admin_menu', array( $this, 'menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'admin_post_tap_chat_reset_analytics', array( $this, 'handle_reset_analytics' ) );
        add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );
        add_action( 'admin_notices', array( $this, 'maybe_show_setup_notice' ) );
        add_action( 'admin_init', array( $this, 'maybe_dismiss_setup_notice' ) );
        add_action( 'in_admin_header', array( $this, 'quiet_foreign_notices' ), PHP_INT_MAX );
        add_filter( 'admin_body_class', array( $this, 'quiet_body_class' ) );
    }

    /**
     * Keep the settings screen focused: on this screen only, drop notices that other
     * plugins hook into the notice area. WordPress' own "Settings saved" message is
     * printed outside these hooks, and Tap Chat's own notice is added back.
     * Notices printed by other means are hidden by admin.css.
     */
    public function quiet_foreign_notices() {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        if ( ! $screen || self::SCREEN_ID !== $screen->id ) {
            return;
        }
        /**
         * Filters whether notices from other plugins are hidden on the Tap Chat screen.
         *
         * @param bool $quiet True to hide them.
         */
        if ( ! apply_filters( 'tap_chat_quiet_admin_notices', true ) ) {
            return;
        }
        foreach ( array( 'admin_notices', 'all_admin_notices', 'network_admin_notices', 'user_admin_notices' ) as $hook ) {
            remove_all_actions( $hook );
        }
        add_action( 'admin_notices', array( $this->review, 'maybe_render' ) );
    }

    /**
     * Body class that lets admin.css hide notices printed outside the notice hooks.
     *
     * @param string $classes Body classes.
     * @return string
     */
    public function quiet_body_class( $classes ) {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        if ( ! $screen || self::SCREEN_ID !== $screen->id ) {
            return $classes;
        }
        /** This filter is documented in includes/admin/class-tap-chat-admin.php */
        if ( ! apply_filters( 'tap_chat_quiet_admin_notices', true ) ) {
            return $classes;
        }
        return $classes . ' tap-chat-quiet';
    }

    /**
     * URL of the plugin settings page.
     */
    private function settings_url() {
        return admin_url( 'options-general.php?page=tap-chat' );
    }

    /**
     * Whether a chat destination has been configured yet: a single number/link,
     * or at least one enabled agent with a destination.
     */
    private function is_plugin_configured() {
        $s = get_option( 'tap_chat_settings', array() );
        if ( ! is_array( $s ) ) {
            return false;
        }

        $mode = isset( $s['button_mode'] ) ? $s['button_mode'] : 'single';

        if ( 'agents' === $mode ) {
            if ( empty( $s['agents'] ) || ! is_array( $s['agents'] ) ) {
                return false;
            }
            foreach ( $s['agents'] as $agent ) {
                if ( ! is_array( $agent ) ) {
                    continue;
                }
                if ( isset( $agent['enabled'] ) && 'yes' !== $agent['enabled'] ) {
                    continue;
                }
                $type = isset( $agent['channel_type'] ) ? $agent['channel_type'] : 'phone';
                if ( 'custom' === $type ) {
                    if ( ! empty( $agent['custom_url'] ) ) {
                        return true;
                    }
                } elseif ( ! empty( $agent['phone'] ) ) {
                    return true;
                }
            }
            return false;
        }

        // Single-button mode.
        $type = isset( $s['link_type'] ) ? $s['link_type'] : 'phone';
        if ( 'custom' === $type ) {
            return ! empty( $s['custom_url'] );
        }
        return ! empty( $s['phone'] );
    }

    /**
     * Whether the setup reminder should be printed on the current screen.
     */
    private function setup_notice_due() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return false;
        }
        if ( get_option( 'tap_chat_setup_notice_dismissed' ) ) {
            return false;
        }
        // Not on the plugin's own screen (the user is already there).
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
        if ( $screen && self::SCREEN_ID === $screen->id ) {
            return false;
        }
        // Once a destination exists the button renders, so there is nothing to nag about.
        return ! $this->is_plugin_configured();
    }

    /**
     * Show a setup reminder until the button is configured, pointing the user to
     * the settings page so the chat button actually appears on the site.
     */
    public function maybe_show_setup_notice() {
        if ( ! $this->setup_notice_due() ) {
            return;
        }

        $dismiss_url = wp_nonce_url(
            add_query_arg( 'tap_chat_dismiss_setup', '1' ),
            'tap_chat_dismiss_setup'
        );

        echo '<div class="notice tap-chat-notice"><span class="tap-chat-notice__mark">';
        echo Admin_UI::whatsapp_svg( 'tap-chat-notice__glyph' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG.
        echo '</span><div class="tap-chat-notice__body">';
        printf( '<p class="tap-chat-notice__title">%s</p>', esc_html__( 'Tap Chat', 'tap-chat' ) );
        printf( '<p class="tap-chat-notice__text">%s</p>', esc_html__( 'Almost there! Your chat button is not visible yet. Add your WhatsApp number (or set up agents) to display it on your site.', 'tap-chat' ) );
        printf(
            '<p class="tap-chat-notice__actions"><a class="tap-chat-notice__btn" href="%1$s">%2$s</a><a class="tap-chat-notice__link" href="%3$s">%4$s</a></p>',
            esc_url( $this->settings_url() ),
            esc_html__( 'Set up the chat button', 'tap-chat' ),
            esc_url( $dismiss_url ),
            esc_html__( 'Dismiss', 'tap-chat' )
        );
        echo '</div></div>';
    }

    /**
     * Persist dismissal of the setup notice.
     */
    public function maybe_dismiss_setup_notice() {
        if ( ! isset( $_GET['tap_chat_dismiss_setup'] ) ) {
            return;
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'tap_chat_dismiss_setup' ) ) {
            return;
        }
        update_option( 'tap_chat_setup_notice_dismissed', '1' );

        wp_safe_redirect( remove_query_arg( array( 'tap_chat_dismiss_setup', '_wpnonce' ) ) );
        exit;
    }

    /**
     * "At a Glance"-style summary widget on the main WordPress dashboard.
     */
    public function register_dashboard_widget() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        wp_add_dashboard_widget(
            'tap_chat_summary',
            __( 'Tap Chat — chat clicks', 'tap-chat' ),
            array( $this, 'render_dashboard_widget' )
        );
    }

    public function render_dashboard_widget() {
        $url = admin_url( 'options-general.php?page=tap-chat#analytics/overview' );

        // Cache the summary for a few minutes so repeated dashboard loads don't re-query.
        $data = get_transient( 'tap_chat_dw_summary' );
        if ( false === $data ) {
            $today      = current_time( 'Y-m-d' );
            $week_start = gmdate( 'Y-m-d', strtotime( $today ) - ( 6 * DAY_IN_SECONDS ) );
            $spark_from = gmdate( 'Y-m-d', strtotime( $today ) - ( 13 * DAY_IN_SECONDS ) );

            $data = array(
                'today'  => Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $today, $today ),
                'week'   => Analytics_Store::get_total( Analytics_Store::EVENT_CLICK, $week_start, $today ),
                'total'  => Analytics_Store::get_total( Analytics_Store::EVENT_CLICK ),
                'series' => array_values( Analytics_Store::get_daily_series( Analytics_Store::EVENT_CLICK, $spark_from, $today ) ),
            );
            set_transient( 'tap_chat_dw_summary', $data, 5 * MINUTE_IN_SECONDS );
        }

        $today_clicks = (int) $data['today'];
        $week_clicks  = (int) $data['week'];
        $total_clicks = (int) $data['total'];
        $series       = is_array( $data['series'] ) && $data['series'] ? $data['series'] : array( 0, 0 );

        echo '<div class="tap-chat-dw">';
        if ( $total_clicks <= 0 ) {
            echo '<div class="tap-chat-dw__empty"><span class="tap-chat-dw__empty-icon">';
            Admin_Icons::render( 'chart' );
            printf( '</span><p class="tap-chat-dw__empty-title">%s</p>', esc_html__( 'No chat clicks yet', 'tap-chat' ) );
            printf( '<p class="tap-chat-dw__empty-text">%s</p>', esc_html__( 'Your button is live — clicks will appear here as visitors tap it.', 'tap-chat' ) );
            printf( '<a class="tap-chat-dw__cta" href="%1$s">%2$s</a></div>', esc_url( $url ), esc_html__( 'Open analytics', 'tap-chat' ) );
            echo '</div>';
            return;
        }

        $stats = array(
            array( $today_clicks, __( 'Today', 'tap-chat' ) ),
            array( $week_clicks, __( '7 days', 'tap-chat' ) ),
            array( $total_clicks, __( 'All time', 'tap-chat' ) ),
        );
        echo '<div class="tap-chat-dw__stats">';
        foreach ( $stats as $stat ) {
            printf(
                '<div class="tap-chat-dw__stat"><span class="tap-chat-dw__num">%1$s</span><span class="tap-chat-dw__lbl">%2$s</span></div>',
                esc_html( number_format_i18n( $stat[0] ) ),
                esc_html( $stat[1] )
            );
        }
        echo '</div>';

        echo '<div class="tap-chat-dw__spark"><div class="tap-chat-dw__spark-top">';
        printf( '<span>%s</span>', esc_html__( 'Last 14 days', 'tap-chat' ) );
        /* translators: %s: peak clicks in a day */
        printf( '<span>%s</span>', esc_html( sprintf( __( 'peak %s/day', 'tap-chat' ), number_format_i18n( max( $series ) ) ) ) );
        echo '</div>';
        echo $this->sparkline_svg( $series ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG built internally from integers.
        echo '</div>';

        printf(
            '<div class="tap-chat-dw__foot"><a class="tap-chat-dw__cta" href="%1$s">%2$s<span aria-hidden="true" class="tap-chat-dw__arrow">&rarr;</span></a></div>',
            esc_url( $url ),
            esc_html__( 'View full report', 'tap-chat' )
        );
        echo '</div>';
    }

    /**
     * Small inline SVG sparkline (area + line). Colours come from CSS (currentColor).
     */
    private function sparkline_svg( $values ) {
        $values = array_map( 'intval', array_values( $values ) );
        $n      = count( $values );
        if ( $n < 2 ) {
            $values = array_pad( $values, 2, 0 );
            $n      = 2;
        }

        $w    = 300;
        $h    = 56;
        $pad  = 4;
        $max  = max( 1, max( $values ) );
        $step = ( $w - ( 2 * $pad ) ) / ( $n - 1 );

        $points = array();
        foreach ( $values as $i => $v ) {
            $points[] = array(
                round( $pad + ( $i * $step ), 2 ),
                round( $h - $pad - ( ( $v / $max ) * ( $h - ( 2 * $pad ) ) ), 2 ),
            );
        }

        $line = '';
        foreach ( $points as $i => $p ) {
            $line .= ( 0 === $i ? 'M' : 'L' ) . $p[0] . ' ' . $p[1] . ' ';
        }
        $line  = trim( $line );
        $first = $points[0];
        $last  = $points[ $n - 1 ];
        $area  = 'M' . $first[0] . ' ' . ( $h - $pad ) . ' ' . str_replace( 'M', 'L', $line ) . ' L' . $last[0] . ' ' . ( $h - $pad ) . ' Z';

        $svg  = '<svg class="tap-chat-dw__svg" viewBox="0 0 ' . $w . ' ' . $h . '" preserveAspectRatio="none" width="100%" height="' . $h . '" aria-hidden="true" focusable="false">';
        $svg .= '<defs><linearGradient id="tapChatSparkFill" x1="0" y1="0" x2="0" y2="1">'
            . '<stop offset="0%" stop-color="currentColor" stop-opacity="0.22"/>'
            . '<stop offset="100%" stop-color="currentColor" stop-opacity="0"/></linearGradient></defs>';
        $svg .= '<path d="' . esc_attr( $area ) . '" fill="url(#tapChatSparkFill)"/>';
        $svg .= '<path d="' . esc_attr( $line ) . '" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>';
        $svg .= '<circle cx="' . $last[0] . '" cy="' . $last[1] . '" r="3" fill="currentColor" stroke="#fff" stroke-width="1.5"/>';
        $svg .= '</svg>';

        return $svg;
    }

    /**
     * Handle the "Reset analytics data" action (separate from the settings form).
     */
    public function handle_reset_analytics() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to do this.', 'tap-chat' ) );
        }
        check_admin_referer( 'tap_chat_reset_analytics' );

        Analytics_Store::reset();
        delete_transient( 'tap_chat_dw_summary' );

        wp_safe_redirect(
            add_query_arg(
                'tap_chat_reset',
                '1',
                admin_url( 'options-general.php?page=tap-chat' )
            ) . '#analytics/settings'
        );
        exit;
    }

    public function menu() {
        add_options_page(
            __( 'Tap Chat', 'tap-chat' ),
            __( 'Tap Chat', 'tap-chat' ),
            'manage_options',
            'tap-chat',
            array( $this, 'settings_page' )
        );
    }

    public function admin_scripts( $hook ) {
        $is_ours = ( self::SCREEN_ID === $hook );

        // Small stylesheet for the notices and the dashboard widget, only where they appear.
        if ( $is_ours || 'index.php' === $hook || $this->setup_notice_due() || $this->review->is_due() ) {
            wp_enqueue_style( 'tap-chat-admin-global', TAP_CHAT_PLUGIN_URL . 'assets/css/admin-global.css', array(), TAP_CHAT_VERSION );
        }

        if ( ! $is_ours ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );

        // 'wp-base-styles' defines --wp-admin-theme-color for the user's admin colour scheme where available.
        $deps = array( 'wp-color-picker' );
        if ( wp_style_is( 'wp-base-styles', 'registered' ) ) {
            $deps[] = 'wp-base-styles';
        }
        wp_enqueue_style( 'tap-chat-admin', TAP_CHAT_PLUGIN_URL . 'assets/css/admin.css', $deps, TAP_CHAT_VERSION );

        wp_enqueue_script(
            'tap-chat-admin',
            TAP_CHAT_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ),
            TAP_CHAT_VERSION,
            true
        );
        wp_localize_script(
            'tap-chat-admin',
            'tapChatAdmin',
            array(
                'i18n' => array(
                    'mediaTitle'  => __( 'Select or upload an image', 'tap-chat' ),
                    'mediaButton' => __( 'Use this image', 'tap-chat' ),
                    'defaultLabel' => __( 'Chat with us', 'tap-chat' ),
                    'px'          => __( 'px', 'tap-chat' ),
                    'online'      => __( 'Online', 'tap-chat' ),
                    'offline'     => __( 'Offline', 'tap-chat' ),
                    /* translators: %d: agent number. */
                    'agentName'   => __( 'Agent %d', 'tap-chat' ),
                    'noAgents'    => __( 'Add an agent with a name to see it here.', 'tap-chat' ),
                    'agentsOff'   => __( 'Multiple agents is off, so visitors see a single button. Choose "Multiple agents" to preview the picker.', 'tap-chat' ),
                    'bubbleOff'   => __( 'The welcome bubble is off. Turn it on to preview it.', 'tap-chat' ),
                    'bubbleText'  => __( 'Need help? Let\'s chat! 💬', 'tap-chat' ),
                    'bubbleName'  => __( 'Support Team', 'tap-chat' ),
                ),
            )
        );
    }

    /**
     * Sidebar sections: view => array( label, icon, hash ).
     */
    private function nav_items() {
        return array(
            'general'    => array( __( 'General', 'tap-chat' ), 'chat', 'general' ),
            'agents'     => array( __( 'Agents', 'tap-chat' ), 'users', 'agents' ),
            'bubble'     => array( __( 'Welcome Bubble', 'tap-chat' ), 'message', 'bubble' ),
            'hours'      => array( __( 'Working Hours', 'tap-chat' ), 'clock', 'hours' ),
            'visibility' => array( __( 'Visibility', 'tap-chat' ), 'eye', 'visibility' ),
            'advanced'   => array( __( 'Advanced', 'tap-chat' ), 'sliders', 'advanced' ),
            '-'          => null,
            'analytics'  => array( __( 'Analytics', 'tap-chat' ), 'chart', 'analytics/overview' ),
            'tracking'   => array( __( 'Tracking', 'tap-chat' ), 'activity', 'analytics/settings' ),
        );
    }

    private function render_sidebar() {
        echo '<aside class="tap-chat-sidebar">';
        printf( '<p class="tap-chat-sidebar__title">%s</p>', esc_html__( 'Tap Chat', 'tap-chat' ) );
        printf( '<nav class="tap-chat-nav" aria-label="%s">', esc_attr__( 'Tap Chat sections', 'tap-chat' ) );
        foreach ( $this->nav_items() as $view => $item ) {
            if ( null === $item ) {
                echo '<hr class="tap-chat-nav__sep">';
                continue;
            }
            printf(
                '<a class="tap-chat-nav__link" href="#%1$s" data-tap-chat-nav="%2$s"%3$s>',
                esc_attr( $item[2] ),
                esc_attr( $view ),
                ''
            );
            Admin_Icons::render( $item[1] );
            printf( '<span>%s</span></a>', esc_html( $item[0] ) );
        }
        echo '</nav>';
        /* translators: %s: plugin version. */
        printf( '<p class="tap-chat-sidebar__foot">%s</p>', esc_html( sprintf( __( 'Version %s', 'tap-chat' ), TAP_CHAT_VERSION ) ) );
        echo '</aside>';
    }

    /**
     * Opening tag of one view. Which view shows on the first paint is decided by
     * the boot script (see boot_script()) and admin.css, then admin.js takes over.
     */
    private function view_open( $view, $saves = true, $scene = '' ) {
        printf(
            '<section class="tap-chat-view" id="tap-chat-view-%1$s" data-tap-chat-view="%1$s"%2$s%3$s%4$s>',
            esc_attr( $view ),
            $saves ? '' : ' data-tap-chat-no-save',
            '' !== $scene ? ' data-tap-chat-scene="' . esc_attr( $scene ) . '"' : '',
            ''
        );
    }

    /**
     * Picks the view from the URL hash before the page is painted.
     *
     * The hash never reaches the server, and admin.js runs only after every footer
     * script has loaded, so without this the General view would flash first. The
     * script marks the app with data-tap-chat-boot; admin.css shows the matching
     * view, menu item and preview from that, and admin.js removes the mark once it
     * has taken over.
     */
    private function boot_script() {
        $views = array();
        foreach ( $this->nav_items() as $view => $item ) {
            if ( null !== $item ) {
                $views[] = $view;
            }
        }
        $script = sprintf(
            '(function(s){var h=String(window.location.hash||"").replace(/^#/,""),a={"analytics":"analytics","analytics/overview":"analytics","analytics/settings":"tracking"},v=a[h]||h.split("/")[0];if(%1$s.indexOf(v)<0){v="general";}s.parentNode.setAttribute("data-tap-chat-boot",v);}(document.currentScript));',
            wp_json_encode( $views )
        );
        if ( function_exists( 'wp_print_inline_script_tag' ) ) {
            wp_print_inline_script_tag( $script );
        } else {
            echo '<script>' . $script . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static script built from a fixed list.
        }
    }

    public function settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'tap-chat' ) );
        }

        $reset_url = wp_nonce_url(
            admin_url( 'admin-post.php?action=tap_chat_reset_analytics' ),
            'tap_chat_reset_analytics'
        );
        $was_reset = isset( $_GET['tap_chat_reset'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only flag set by our own redirect.

        echo '<div class="wrap tap-chat-app" data-tap-chat-boot="general">';
        $this->boot_script();
        echo '<div class="tap-chat-shell">';
        $this->render_sidebar();
        echo '<main class="tap-chat-main"><hr class="wp-header-end">';
        echo '<form method="post" action="options.php" class="tap-chat-form" data-tap-chat-form>';
        settings_fields( 'tap_chat_settings_group' );
        echo '<div class="tap-chat-workspace has-preview" data-tap-chat-workspace><div class="tap-chat-workspace__views">';

        $this->view_open( 'general', true, 'button' );
        $this->fields->render_general();
        echo '</section>';

        $this->view_open( 'agents', true, 'agents' );
        $this->fields->render_agents();
        echo '</section>';

        $this->view_open( 'bubble', true, 'bubble' );
        $this->fields->render_bubble();
        echo '</section>';

        $this->view_open( 'hours' );
        $this->fields->render_hours();
        echo '</section>';

        $this->view_open( 'visibility' );
        $this->fields->render_visibility();
        echo '</section>';

        $this->view_open( 'advanced' );
        $this->fields->render_advanced();
        echo '</section>';

        $this->view_open( 'analytics', false );
        ( new Analytics_Dashboard() )->render();
        echo '</section>';

        $this->view_open( 'tracking' );
        $this->fields->render_tracking( $reset_url, $was_reset );
        echo '</section>';

        echo '</div><aside class="tap-chat-workspace__aside" data-tap-chat-preview-aside>';
        $this->fields->render_preview();
        echo '</aside></div>';

        printf(
            '<div class="tap-chat-savebar" data-tap-chat-savebar><button type="submit" name="submit" class="tap-chat-btn">%s</button></div>',
            esc_html__( 'Save changes', 'tap-chat' )
        );
        echo '</form></main></div></div>';
    }
}
