<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin {

    private $settings;
    private $fields;

    public function __construct() {
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-settings.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-fields.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-review.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-dashboard.php';
        
        $this->settings = new Admin_Settings();
        $this->fields = new Admin_Fields();
        new Review_Notice();
        
        add_action( 'admin_menu', array( $this, 'menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'admin_post_tap_chat_reset_analytics', array( $this, 'handle_reset_analytics' ) );
        add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );
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
        $series       = is_array( $data['series'] ) ? $data['series'] : array( 0, 0 );
        // Scoped styles so the widget looks right on the main dashboard (admin.css isn't loaded there).
        ?>
        <style>
            .tapchat-dw{--tc-accent:#2271b1;--tc-accent2:#3ca0e7;margin:-12px;padding:0;font-family:inherit}
            .tapchat-dw__stats{display:flex;align-items:stretch;border-bottom:1px solid #f0f0f1}
            .tapchat-dw__stat{flex:1;padding:16px 8px;text-align:center;position:relative}
            .tapchat-dw__stat+.tapchat-dw__stat:before{content:"";position:absolute;left:0;top:22px;bottom:22px;width:1px;background:#f0f0f1}
            .tapchat-dw__num{font-size:26px;font-weight:700;line-height:1;color:#1d2327;letter-spacing:-.5px}
            .tapchat-dw__lbl{margin-top:6px;font-size:11px;text-transform:uppercase;letter-spacing:.4px;color:#8c8f94}
            .tapchat-dw__spark{padding:14px 16px 8px}
            .tapchat-dw__spark-top{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px}
            .tapchat-dw__spark-cap{font-size:11px;text-transform:uppercase;letter-spacing:.4px;color:#8c8f94}
            .tapchat-dw__spark-peak{font-size:11px;color:#8c8f94}
            .tapchat-dw__foot{display:flex;justify-content:space-between;align-items:center;padding:12px 16px 16px}
            .tapchat-dw__brand{display:inline-flex;align-items:center;gap:7px;color:#646970;font-size:12px}
            .tapchat-dw__dot{width:8px;height:8px;border-radius:50%;background:linear-gradient(135deg,var(--tc-accent2),var(--tc-accent));box-shadow:0 0 0 3px rgba(34,113,177,.12)}
            .tapchat-dw__cta{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,var(--tc-accent) 0%,#135e96 100%);color:#fff;border:none;border-radius:6px;padding:7px 14px;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 1px 2px rgba(19,94,150,.35);transition:transform .12s ease,box-shadow .12s ease}
            .tapchat-dw__cta:hover{color:#fff;transform:translateY(-1px);box-shadow:0 3px 8px rgba(19,94,150,.4)}
            .tapchat-dw__cta:focus{color:#fff;box-shadow:0 0 0 2px #fff,0 0 0 4px var(--tc-accent)}
            .tapchat-dw__empty{padding:26px 20px;text-align:center;color:#646970}
            .tapchat-dw__empty-title{font-size:14px;font-weight:600;color:#1d2327;margin:0 0 4px}
            .tapchat-dw__empty-text{font-size:12px;margin:0 0 14px}
        </style>
        <div class="tapchat-dw">
        <?php if ( $total_clicks <= 0 ) : ?>
            <div class="tapchat-dw__empty">
                <p class="tapchat-dw__empty-title"><?php esc_html_e( 'No chat clicks yet', 'tap-chat' ); ?></p>
                <p class="tapchat-dw__empty-text"><?php esc_html_e( 'Your button is live — clicks will appear here as visitors tap it.', 'tap-chat' ); ?></p>
                <a class="tapchat-dw__cta" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Open analytics', 'tap-chat' ); ?></a>
            </div>
        <?php else : ?>
            <div class="tapchat-dw__stats">
                <div class="tapchat-dw__stat">
                    <div class="tapchat-dw__num"><?php echo esc_html( number_format_i18n( $today_clicks ) ); ?></div>
                    <div class="tapchat-dw__lbl"><?php esc_html_e( 'Today', 'tap-chat' ); ?></div>
                </div>
                <div class="tapchat-dw__stat">
                    <div class="tapchat-dw__num"><?php echo esc_html( number_format_i18n( $week_clicks ) ); ?></div>
                    <div class="tapchat-dw__lbl"><?php esc_html_e( '7 days', 'tap-chat' ); ?></div>
                </div>
                <div class="tapchat-dw__stat">
                    <div class="tapchat-dw__num"><?php echo esc_html( number_format_i18n( $total_clicks ) ); ?></div>
                    <div class="tapchat-dw__lbl"><?php esc_html_e( 'All time', 'tap-chat' ); ?></div>
                </div>
            </div>

            <div class="tapchat-dw__spark">
                <div class="tapchat-dw__spark-top">
                    <span class="tapchat-dw__spark-cap"><?php esc_html_e( 'Last 14 days', 'tap-chat' ); ?></span>
                    <span class="tapchat-dw__spark-peak"><?php echo esc_html( sprintf( /* translators: %s: peak clicks in a day */ __( 'peak %s/day', 'tap-chat' ), number_format_i18n( max( $series ) ) ) ); ?></span>
                </div>
                <?php echo $this->sparkline_svg( $series ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG built internally from integers ?>
            </div>

            <div class="tapchat-dw__foot">
                <span class="tapchat-dw__brand"><span class="tapchat-dw__dot"></span><?php esc_html_e( 'Tap Chat analytics', 'tap-chat' ); ?></span>
                <a class="tapchat-dw__cta" href="<?php echo esc_url( $url ); ?>">
                    <?php esc_html_e( 'View full report', 'tap-chat' ); ?>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Build a small inline SVG sparkline (area + line) from a series of integers.
     */
    private function sparkline_svg( $values ) {
        $n = count( $values );
        if ( $n < 2 ) {
            $values = array_pad( $values, 2, 0 );
            $n = 2;
        }

        $w = 300;
        $h = 48;
        $pad = 4;
        $max = max( 1, max( $values ) );
        $step = ( $w - ( 2 * $pad ) ) / ( $n - 1 );

        $points = array();
        foreach ( $values as $i => $v ) {
            $x = $pad + ( $i * $step );
            $y = $h - $pad - ( ( $v / $max ) * ( $h - ( 2 * $pad ) ) );
            $points[] = array( round( $x, 2 ), round( $y, 2 ) );
        }

        $line = '';
        foreach ( $points as $i => $p ) {
            $line .= ( 0 === $i ? 'M' : 'L' ) . $p[0] . ' ' . $p[1] . ' ';
        }
        $line = trim( $line );

        $first = $points[0];
        $last  = $points[ $n - 1 ];
        $area  = 'M' . $first[0] . ' ' . ( $h - $pad ) . ' ' . str_replace( 'M', 'L', $line ) . ' L' . $last[0] . ' ' . ( $h - $pad ) . ' Z';

        $last_pt = $last;

        $svg  = '<svg viewBox="0 0 ' . $w . ' ' . $h . '" preserveAspectRatio="none" width="100%" height="48" role="img" aria-hidden="true" style="display:block">';
        $svg .= '<defs><linearGradient id="tcSparkFill" x1="0" y1="0" x2="0" y2="1">'
            . '<stop offset="0%" stop-color="#2271b1" stop-opacity="0.28"/>'
            . '<stop offset="100%" stop-color="#2271b1" stop-opacity="0"/></linearGradient></defs>';
        $svg .= '<path d="' . esc_attr( $area ) . '" fill="url(#tcSparkFill)"/>';
        $svg .= '<path d="' . esc_attr( $line ) . '" fill="none" stroke="#2271b1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>';
        $svg .= '<circle cx="' . $last_pt[0] . '" cy="' . $last_pt[1] . '" r="2.6" fill="#135e96" stroke="#fff" stroke-width="1.5"/>';
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
        if ( 'settings_page_tap-chat' !== $hook ) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        
        wp_enqueue_style( 
            'tap-chat-admin', 
            TAP_CHAT_PLUGIN_URL . 'assets/css/admin.css', 
            array(), 
            TAP_CHAT_VERSION 
        );
        
        wp_enqueue_script( 
            'tap-chat-admin', 
            TAP_CHAT_PLUGIN_URL . 'assets/js/admin.js', 
            array('jquery', 'wp-color-picker'), 
            TAP_CHAT_VERSION, 
            true 
        );
    }

    public function settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'tap-chat' ) );
        }
        
        $tabs = array(
            'general' => __( 'General', 'tap-chat' ),
            'bubble' => __( 'Welcome Bubble', 'tap-chat' ),
            'hours' => __( 'Working Hours', 'tap-chat' ),
            'visibility' => __( 'Visibility', 'tap-chat' ),
            'advanced' => __( 'Advanced', 'tap-chat' ),
            'analytics' => __( 'Analytics', 'tap-chat' ),
        );
        ?>
        <div class="wrap tap-chat-admin-wrap">
            <h1><?php esc_html_e('Tap Chat Settings','tap-chat'); ?></h1>
            
            <nav class="nav-tab-wrapper wp-clearfix tap-chat-tabs">
                <?php foreach ( $tabs as $tab_id => $tab_name ) : ?>
                    <a href="#<?php echo esc_attr( $tab_id ); ?>" 
                       class="nav-tab <?php echo $tab_id === 'general' ? 'nav-tab-active' : ''; ?>"
                       data-tab="<?php echo esc_attr( $tab_id ); ?>">
                        <?php echo esc_html( $tab_name ); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            
            <form method="post" action="options.php" class="tap-chat-settings-form">
                <?php settings_fields( 'tap_chat_settings_group' ); ?>
                
                <div class="tap-chat-tab-content" id="tab-general">
                    <?php do_settings_sections( 'tap-chat-general' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-bubble" style="display: none;">
                    <?php do_settings_sections( 'tap-chat-bubble' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-hours" style="display: none;">
                    <?php do_settings_sections( 'tap-chat-hours' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-visibility" style="display: none;">
                    <?php do_settings_sections( 'tap-chat-visibility' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-advanced" style="display: none;">
                    <?php do_settings_sections( 'tap-chat-advanced' ); ?>
                </div>
                
                <div class="tap-chat-tab-content" id="tab-analytics" style="display: none;">
                    <?php if ( isset( $_GET['tap_chat_reset'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
                        <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Analytics data has been reset.', 'tap-chat' ); ?></p></div>
                    <?php endif; ?>

                    <nav class="tap-chat-subtabs">
                        <a href="#analytics/overview" class="tap-chat-subtab nav-tab-active" data-subtab="overview">
                            <?php esc_html_e( 'Overview', 'tap-chat' ); ?>
                        </a>
                        <a href="#analytics/settings" class="tap-chat-subtab" data-subtab="settings">
                            <?php esc_html_e( 'Settings', 'tap-chat' ); ?>
                        </a>
                    </nav>

                    <div class="tap-chat-subtab-content" data-subtab-content="overview">
                        <?php ( new \Tap_Chat\Analytics_Dashboard() )->render(); ?>
                    </div>

                    <div class="tap-chat-subtab-content" data-subtab-content="settings" style="display: none;">
                        <?php do_settings_sections( 'tap-chat-analytics-collect' ); ?>
                        <?php do_settings_sections( 'tap-chat-analytics' ); ?>

                        <?php
                        // Reset is a nonce-protected link (not a nested form), so it can live inside the settings sub-tab.
                        $reset_url = wp_nonce_url(
                            admin_url( 'admin-post.php?action=tap_chat_reset_analytics' ),
                            'tap_chat_reset_analytics'
                        );
                        ?>
                        <div class="tap-chat-reset-block">
                            <h2><?php esc_html_e( 'Reset analytics data', 'tap-chat' ); ?></h2>
                            <p class="description"><?php esc_html_e( 'Permanently delete all recorded clicks, views and visitors. This cannot be undone. Your settings are kept.', 'tap-chat' ); ?></p>
                            <a href="<?php echo esc_url( $reset_url ); ?>"
                               class="button button-secondary tap-chat-reset-button"
                               onclick="return confirm('<?php echo esc_js( __( 'Delete all Tap Chat analytics data? This cannot be undone.', 'tap-chat' ) ); ?>');">
                                <?php esc_html_e( 'Reset analytics data', 'tap-chat' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}