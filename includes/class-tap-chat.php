<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Plugin {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/class-tap-chat-analytics-store.php';
        require_once TAP_CHAT_PLUGIN_DIR . 'includes/class-tap-chat-rest.php';

        // Make sure the stats table exists even after a silent update.
        \Tap_Chat\Analytics_Store::maybe_upgrade();

        // Bring older (pre-2.1.0) settings up to the agents-aware schema.
        $this->maybe_migrate_settings();

        new \Tap_Chat\Rest_Collector();

        if ( is_admin() ) {
            require_once TAP_CHAT_PLUGIN_DIR . 'includes/admin/class-tap-chat-admin.php';
            new \Tap_Chat\Admin();
        }

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front' ] );
        add_action( 'wp_footer', [ $this, 'render_floating_button' ] );

        add_action( 'tap_chat_prune_stats', [ $this, 'prune_stats' ] );

        add_shortcode('tapchat', [ $this, 'shortcode' ] );
    }

    /**
     * Whether first-party analytics collection should be suppressed for the
     * current request (e.g. logged-in users when exclusion is enabled).
     */
    private function analytics_suppressed() {
        if ( 'yes' === $this->get_option( 'analytics_exclude_logged_in', 'no' ) && is_user_logged_in() ) {
            return true;
        }
        return false;
    }

    /**
     * Daily maintenance: drop stats rows older than the retention window.
     */
    public function prune_stats() {
        $days = absint( $this->get_option( 'analytics_retention_days', 365 ) );
        if ( $days < 7 ) {
            $days = 7;
        }
        \Tap_Chat\Analytics_Store::prune( $days );
        delete_transient( 'tap_chat_dw_summary' );
    }

    /**
     * Lazily upgrade settings saved by versions before 2.1.0 to the
     * agents-aware schema. Runs once: after the first write, button_mode
     * is set and the early return short-circuits every later request.
     */
    private function maybe_migrate_settings() {
        $settings = get_option( 'tap_chat_settings', array() );

        if ( ! is_array( $settings ) || isset( $settings['button_mode'] ) ) {
            return;
        }

        $settings['button_mode'] = 'single';

        if ( ! isset( $settings['agents_panel_title'] ) ) {
            $settings['agents_panel_title'] = __( 'Start a conversation', 'tap-chat' );
        }
        if ( ! isset( $settings['agents_panel_subtitle'] ) ) {
            $settings['agents_panel_subtitle'] = __( 'Choose someone to chat with', 'tap-chat' );
        }

        // Seed the first agent from the existing single-button config.
        if ( empty( $settings['agents'] ) || ! is_array( $settings['agents'] ) ) {
            $settings['agents'] = array(
                array(
                    'id'           => 'agent_' . substr( md5( uniqid( '', true ) ), 0, 8 ),
                    'name'         => ! empty( $settings['welcome_bubble_name'] ) ? $settings['welcome_bubble_name'] : __( 'Support', 'tap-chat' ),
                    'role'         => '',
                    'avatar'       => isset( $settings['welcome_bubble_avatar'] ) ? $settings['welcome_bubble_avatar'] : '',
                    'channel_type' => ( isset( $settings['link_type'] ) && 'custom' === $settings['link_type'] ) ? 'custom' : 'phone',
                    'country_code' => isset( $settings['country_code'] ) ? $settings['country_code'] : '',
                    'phone'        => isset( $settings['phone'] ) ? $settings['phone'] : '',
                    'custom_url'   => isset( $settings['custom_url'] ) ? $settings['custom_url'] : '',
                    'message'      => isset( $settings['message'] ) ? $settings['message'] : '',
                    'online'       => 'yes',
                    'enabled'      => 'yes',
                ),
            );
        }

        update_option( 'tap_chat_settings', $settings );
    }

    /**
     * Default WhatsApp glyph, shared by the single button and the agents panel.
     */
    private function whatsapp_svg( $class = 'tapchat-icon' ) {
        return '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false" role="img"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.273-.1-.472-.149-.672.15-.197.297-.768.966-.94 1.164-.173.199-.347.224-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.672-1.611-.921-2.206-.242-.58-.487-.502-.672-.512l-.573-.01c-.198 0-.521.074-.793.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.37l-.361-.214-3.741.982.999-3.648-.236-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.89-9.884 2.64 0 5.122 1.027 6.988 2.893a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.439 9.884-9.887 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .154 5.338.157 11.892c0 2.096.549 4.142 1.595 5.945L.057 24l6.3-1.654a11.89 11.89 0 0 0 5.683 1.448h.005c6.554 0 11.895-5.338 11.898-11.893a11.82 11.82 0 0 0-3.479-8.413"/></svg>';
    }

    public function enqueue_front() {
        wp_enqueue_style( 'tap-chat', TAP_CHAT_PLUGIN_URL . 'assets/css/tapchat.css', [], TAP_CHAT_VERSION );
        wp_enqueue_script( 'tap-chat', TAP_CHAT_PLUGIN_URL . 'assets/js/tapchat.js', [ 'jquery' ], TAP_CHAT_VERSION, true );
        
        $size = absint( $this->get_option( 'size', 40 ) );
        $mobile_size = absint( $this->get_option( 'mobile_size', 40 ) );
        
        $inline_css = sprintf(
            ':root { --tapchat-size: %dpx; --tapchat-mobile-size: %dpx; }',
            $size,
            $mobile_size
        );
        wp_add_inline_style( 'tap-chat', $inline_css );
        
        $trigger_data = array(
            'scrollEnabled' => $this->get_option('trigger_scroll_enabled', 'no') === 'yes',
            'scrollDepth' => absint($this->get_option('trigger_scroll_depth', 50)),
            'exitEnabled' => $this->get_option('trigger_exit_enabled', 'no') === 'yes',
            'timeEnabled' => $this->get_option('trigger_time_enabled', 'yes') === 'yes',
            'timeDelay' => absint($this->get_option('trigger_time_delay', 3)),
            'idleEnabled' => $this->get_option('trigger_idle_enabled', 'no') === 'yes',
            'idleTime' => absint($this->get_option('trigger_idle_time', 60)),
        );
        
        wp_localize_script( 'tap-chat', 'TapChatData', array(
            'whatsAppBase' => 'https://wa.me/',
            'triggers' => $trigger_data,
            'analytics' => array(
                'enabled'       => ( 'yes' === $this->get_option( 'enable_analytics', 'no' ) ),
                'method'        => $this->get_option( 'analytics_method', 'auto' ),
                'eventName'     => $this->get_option( 'analytics_event_name', 'tapchat_click' ),
                'channel'       => ( 'custom' === $this->get_option( 'link_type', 'phone' ) ) ? 'custom' : 'whatsapp',
                'trackTriggers' => ( 'yes' === $this->get_option( 'analytics_track_triggers', 'yes' ) ),
            ),
            'collect' => array(
                'clickEnabled'   => ( 'yes' === $this->get_option( 'enable_click_analytics', 'yes' ) ) && ! $this->analytics_suppressed(),
                'trafficEnabled' => ( 'yes' === $this->get_option( 'enable_traffic_analytics', 'no' ) ) && ! $this->analytics_suppressed(),
                'endpoint'       => esc_url_raw( rest_url( 'tapchat/v1/collect' ) ),
            ),
        ));
    }

    private function get_option( $key, $default = '' ) {
        $opts = get_option( 'tap_chat_settings', [] );
        return isset( $opts[ $key ] ) ? $opts[ $key ] : $default;
    }

    private function get_full_phone_number() {
        $country_code = $this->get_option( 'country_code', '49' );
        $phone = $this->get_option( 'phone', '' );
        
        $phone = preg_replace('/\D+/', '', $phone);
        $phone = ltrim($phone, '0');
        
        if ( empty( $phone ) ) {
            return '';
        }
        
        return $country_code . $phone;
    }

    private function build_whatsapp_url( $phone, $message = '' ) {
        if ( strpos( $phone, '+' ) === false && ! preg_match('/^\d{10,15}$/', $phone ) ) {
            $phone = $this->get_full_phone_number();
        } else {
            $phone = preg_replace('/\D+/', '', $phone);
        }
        
        if ( empty( $phone ) ) {
            return '';
        }
        
        $url = 'https://wa.me/' . $phone;
        if ( ! empty( $message ) ) {
            $url .= '?text=' . rawurlencode( $message );
        }
        return esc_url( $url );
    }

    private function replace_variables( $text ) {
        if ( '' === $text ) {
            return $text;
        }
        return str_replace(
            array( '{TITLE}', '{TAGLINE}', '{URL}' ),
            array( get_bloginfo( 'name' ), get_bloginfo( 'description' ), home_url( '/' ) ),
            $text
        );
    }

    private function build_button_link() {
        $link_type = $this->get_option( 'link_type', 'phone' );

        if ( 'custom' === $link_type ) {
            $url = trim( $this->get_option( 'custom_url', '' ) );
            return $url ? esc_url( $url ) : '';
        }

        $full_phone = $this->get_full_phone_number();
        if ( empty( $full_phone ) ) {
            return '';
        }

        $message = $this->replace_variables( $this->get_option( 'message', '' ) );
        return $this->build_whatsapp_url( $full_phone, $message );
    }
    
    private function is_within_working_hours() {
        $enable_working_hours = $this->get_option( 'enable_working_hours', 'no' );
        
        if ( $enable_working_hours !== 'yes' ) {
            return true;
        }
        
        $timezone = $this->get_option( 'timezone', wp_timezone_string() );
        $working_hours = $this->get_option( 'working_hours', array() );
        
        try {
            $tz = new \DateTimeZone( $timezone );
            $now = new \DateTime( 'now', $tz );
            
            $current_day = strtolower( $now->format( 'l' ) );
            $current_time = $now->format( 'H:i' );
            
            if ( ! isset( $working_hours[ $current_day ] ) ) {
                return false;
            }
            
            $day_schedule = $working_hours[ $current_day ];
            
            if ( ! isset( $day_schedule['enabled'] ) || $day_schedule['enabled'] !== 'yes' ) {
                return false;
            }
            
            $start_time = isset( $day_schedule['start'] ) ? $day_schedule['start'] : '09:00';
            $end_time = isset( $day_schedule['end'] ) ? $day_schedule['end'] : '17:00';
            
            return ( $current_time >= $start_time && $current_time <= $end_time );
            
        } catch ( \Exception $e ) {
            return true;
        }
    }
    
    private function should_show_button() {
        $enable_show_on = $this->get_option( 'enable_show_on', 'no' );
        $enable_hide_on = $this->get_option( 'enable_hide_on', 'no' );
        
        if ( $enable_show_on === 'no' && $enable_hide_on === 'no' ) {
            return true;
        }
        
        $current_id = get_queried_object_id();
        
        if ( function_exists( 'is_shop' ) && is_shop() ) {
            $current_id = wc_get_page_id( 'shop' );
        }
        
        if ( ! $current_id ) {
            if ( $enable_show_on === 'yes' ) {
                return false;
            }
            return true;
        }
        
        $show_on_pages = $this->get_option( 'show_on_pages', array() );
        $hide_on_pages = $this->get_option( 'hide_on_pages', array() );
        
        if ( $enable_show_on === 'yes' ) {
            $in_show_list = in_array( $current_id, (array) $show_on_pages );
            
            if ( $enable_hide_on === 'yes' && $in_show_list ) {
                $in_hide_list = in_array( $current_id, (array) $hide_on_pages );
                return $in_show_list && ! $in_hide_list;
            }
            
            return $in_show_list;
        }
        
        if ( $enable_hide_on === 'yes' ) {
            $in_hide_list = in_array( $current_id, (array) $hide_on_pages );
            return ! $in_hide_list;
        }
        
        return true;
    }

    public function render_floating_button() {
        $enabled = $this->get_option( 'enable_floating', 'yes' );
        if ( 'yes' !== $enabled ) { return; }

        if ( ! $this->should_show_button() ) { return; }

        // Working-hours gate applies to both single and multiple-agents modes.
        $enable_working_hours = $this->get_option( 'enable_working_hours', 'no' );
        if ( 'yes' === $enable_working_hours && ! $this->is_within_working_hours() ) {
            $offline_message = $this->get_option( 'offline_message', '' );

            if ( empty( $offline_message ) ) {
                return;
            }

            $this->render_offline_button( $offline_message );
            return;
        }

        // Multiple-agents mode: render the picker panel instead of a single link.
        if ( 'agents' === $this->get_option( 'button_mode', 'single' ) ) {
            $agents = $this->get_agents_for_render();
            if ( empty( $agents ) ) {
                return;
            }
            $this->render_agents_widget( $agents );
            $this->render_welcome_bubble();
            return;
        }

        $href = $this->build_button_link();
        if ( '' === $href ) { return; }

        $label   = $this->get_option( 'label', __( 'Chat with us', 'tap-chat' ) );
        
        if ( empty( $label ) ) {
            $label = __( 'Chat with us', 'tap-chat' );
        }
        
        $pos     = $this->get_option( 'position', 'right' );
        $size    = absint( $this->get_option( 'size', 40 ) );
        $mobile_size = absint( $this->get_option( 'mobile_size', 40 ) );
        $color   = sanitize_hex_color( $this->get_option( 'color', '#25D366' ) );
        $hide_mb = $this->get_option( 'hide_label_mobile', 'yes' );
        $hide_dt = $this->get_option( 'hide_label_desktop', 'yes' );
        $append  = ( $this->get_option( 'link_type', 'phone' ) === 'phone' ) ? $this->get_option( 'append_page_context', 'no' ) : 'no';
        $custom_icon = $this->get_option( 'custom_icon', '' );

        $classes = 'tapchat-fab tapchat-pos-' . esc_attr( $pos );
        if ( 'yes' === $hide_mb ) {
            $classes .= ' tapchat-hide-label-mobile';
        }
        if ( 'yes' === $hide_dt ) {
            $classes .= ' tapchat-hide-label-desktop';
        }

        $animation = $this->get_option( 'button_animation', 'none' );
        if ( $animation && 'none' !== $animation ) {
            $classes .= ' tapchat-anim-' . sanitize_html_class( $animation );
        }

        $style = sprintf( 
            '--tapchat-color:%s;', 
            $color ? $color : '#25D366' 
        );

        echo '<a class="' . esc_attr( $classes ) . '" style="' . esc_attr( $style ) . '" href="' . esc_url( $href ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $label ) . '"' . ( 'yes' === $append ? ' data-append-page="1"' : '' ) . '>';
        
        if ( ! empty( $custom_icon ) ) {
            echo '<img src="' . esc_url( $custom_icon ) . '" alt="Chat" class="tapchat-icon" />';
        } else {
            echo '<svg class="tapchat-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false" role="img"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.273-.1-.472-.149-.672.15-.197.297-.768.966-.94 1.164-.173.199-.347.224-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.672-1.611-.921-2.206-.242-.58-.487-.502-.672-.512l-.573-.01c-.198 0-.521.074-.793.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.37l-.361-.214-3.741.982.999-3.648-.236-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.89-9.884 2.64 0 5.122 1.027 6.988 2.893a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.439 9.884-9.887 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .154 5.338.157 11.892c0 2.096.549 4.142 1.595 5.945L.057 24l6.3-1.654a11.89 11.89 0 0 0 5.683 1.448h.005c6.554 0 11.895-5.338 11.898-11.893a11.82 11.82 0 0 0-3.479-8.413"/></svg>';
        }
        
        echo '<span class="tapchat-label">' . esc_html( $label ) . '</span>';
        if ( 'attention' === $animation ) {
            echo '<span class="tapchat-badge" aria-hidden="true">1</span>';
        }
        echo '</a>';
        
        $this->render_welcome_bubble();
    }
    
    private function render_offline_button( $offline_message ) {
        $pos     = $this->get_option( 'position', 'right' );
        $size    = absint( $this->get_option( 'size', 40 ) );
        $mobile_size = absint( $this->get_option( 'mobile_size', 40 ) );
        $color   = '#999999';
        $custom_icon = $this->get_option( 'custom_icon', '' );

        $classes = 'tapchat-fab tapchat-fab-offline tapchat-pos-' . esc_attr( $pos );

        $style = sprintf( 
            '--tapchat-color:%s; cursor: not-allowed; opacity: 0.7;', 
            $color
        );

        echo '<div class="' . esc_attr( $classes ) . '" style="' . esc_attr( $style ) . '" title="' . esc_attr( $offline_message ) . '">';
        
        if ( ! empty( $custom_icon ) ) {
            echo '<img src="' . esc_url( $custom_icon ) . '" alt="Chat" class="tapchat-icon" style="opacity: 0.5;" />';
        } else {
            echo '<svg class="tapchat-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false" role="img"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.273-.1-.472-.149-.672.15-.197.297-.768.966-.94 1.164-.173.199-.347.224-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.672-1.611-.921-2.206-.242-.58-.487-.502-.672-.512l-.573-.01c-.198 0-.521.074-.793.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.37l-.361-.214-3.741.982.999-3.648-.236-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.89-9.884 2.64 0 5.122 1.027 6.988 2.893a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.439 9.884-9.887 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .154 5.338.157 11.892c0 2.096.549 4.142 1.595 5.945L.057 24l6.3-1.654a11.89 11.89 0 0 0 5.683 1.448h.005c6.554 0 11.895-5.338 11.898-11.893a11.82 11.82 0 0 0-3.479-8.413"/></svg>';
        }
        
        echo '<span class="tapchat-label">' . esc_html( $offline_message ) . '</span>';
        echo '</div>';
    }
    
    /**
     * Return the list of enabled agents that have a usable link, each with a
     * computed 'link' key. Capped at 3 (the free-plan limit).
     */
    private function get_agents_for_render() {
        $agents = $this->get_option( 'agents', array() );
        if ( ! is_array( $agents ) ) {
            return array();
        }

        $out = array();
        foreach ( $agents as $agent ) {
            if ( ! is_array( $agent ) ) {
                continue;
            }
            if ( isset( $agent['enabled'] ) && 'yes' !== $agent['enabled'] ) {
                continue;
            }

            $link = $this->build_agent_link( $agent );
            if ( '' === $link ) {
                continue;
            }

            $agent['link'] = $link;
            $out[] = $agent;

            if ( count( $out ) >= 3 ) {
                break;
            }
        }

        return $out;
    }

    /**
     * Build the chat link for a single agent (WhatsApp or custom URL).
     */
    private function build_agent_link( $agent ) {
        $type = isset( $agent['channel_type'] ) ? $agent['channel_type'] : 'phone';

        if ( 'custom' === $type ) {
            $url = isset( $agent['custom_url'] ) ? trim( $agent['custom_url'] ) : '';
            return $url ? esc_url( $url ) : '';
        }

        $country = isset( $agent['country_code'] ) ? preg_replace( '/\D+/', '', $agent['country_code'] ) : '';
        $phone   = isset( $agent['phone'] ) ? preg_replace( '/\D+/', '', $agent['phone'] ) : '';
        $phone   = ltrim( $phone, '0' );

        if ( '' === $phone ) {
            return '';
        }

        $full = $country . $phone;
        $url  = 'https://wa.me/' . $full;

        $message = $this->replace_variables( isset( $agent['message'] ) ? $agent['message'] : '' );
        if ( '' !== $message ) {
            $url .= '?text=' . rawurlencode( $message );
        }

        return esc_url( $url );
    }

    /**
     * Render the floating trigger button plus the agent picker panel.
     *
     * @param array $agents Agents returned by get_agents_for_render().
     */
    private function render_agents_widget( $agents ) {
        $pos         = $this->get_option( 'position', 'right' );
        $color       = sanitize_hex_color( $this->get_option( 'color', '#25D366' ) );
        $custom_icon = $this->get_option( 'custom_icon', '' );
        $animation   = $this->get_option( 'button_animation', 'none' );
        $panel_title = $this->get_option( 'agents_panel_title', __( 'Start a conversation', 'tap-chat' ) );
        $panel_sub   = $this->get_option( 'agents_panel_subtitle', '' );

        $label = $this->get_option( 'label', __( 'Chat with us', 'tap-chat' ) );
        if ( empty( $label ) ) {
            $label = __( 'Chat with us', 'tap-chat' );
        }

        $hide_mb = $this->get_option( 'hide_label_mobile', 'yes' );
        $hide_dt = $this->get_option( 'hide_label_desktop', 'yes' );

        $classes = 'tapchat-fab tapchat-fab-trigger tapchat-pos-' . esc_attr( $pos );
        if ( 'yes' === $hide_mb ) {
            $classes .= ' tapchat-hide-label-mobile';
        }
        if ( 'yes' === $hide_dt ) {
            $classes .= ' tapchat-hide-label-desktop';
        }
        if ( $animation && 'none' !== $animation ) {
            $classes .= ' tapchat-anim-' . sanitize_html_class( $animation );
        }

        $style = sprintf( '--tapchat-color:%s;', $color ? $color : '#25D366' );

        // Trigger (opens the panel). Rendered as an anchor, exactly like the
        // single-button FAB, so themes that restyle <button> can't hide it.
        echo '<a href="#" role="button" class="' . esc_attr( $classes ) . '" style="' . esc_attr( $style ) . '" aria-haspopup="dialog" aria-expanded="false" aria-controls="tapchat-agents-panel" aria-label="' . esc_attr( $label ) . '">';

        if ( ! empty( $custom_icon ) ) {
            echo '<img src="' . esc_url( $custom_icon ) . '" alt="Chat" class="tapchat-icon" />';
        } else {
            echo $this->whatsapp_svg( 'tapchat-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG built internally
        }

        echo '<span class="tapchat-label">' . esc_html( $label ) . '</span>';
        if ( 'attention' === $animation ) {
            echo '<span class="tapchat-badge" aria-hidden="true">1</span>';
        }
        echo '</a>';

        // Picker panel.
        echo '<div class="tapchat-agents-panel tapchat-pos-' . esc_attr( $pos ) . '" id="tapchat-agents-panel" style="' . esc_attr( $style ) . '" role="dialog" aria-modal="false" aria-label="' . esc_attr( $panel_title ) . '">';

        echo '<div class="tapchat-agents-header">';
        echo '<div class="tapchat-agents-heading">';
        if ( '' !== $panel_title ) {
            echo '<span class="tapchat-agents-title">' . esc_html( $panel_title ) . '</span>';
        }
        if ( '' !== $panel_sub ) {
            echo '<span class="tapchat-agents-subtitle">' . esc_html( $panel_sub ) . '</span>';
        }
        echo '</div>';
        echo '<button type="button" class="tapchat-agents-close" aria-label="' . esc_attr__( 'Close', 'tap-chat' ) . '">&times;</button>';
        echo '</div>';

        echo '<div class="tapchat-agents-list">';
        foreach ( $agents as $agent ) {
            $this->render_agent_row( $agent );
        }
        echo '</div>';

        echo '</div>';
    }

    /**
     * Render a single clickable agent row inside the picker panel.
     */
    private function render_agent_row( $agent ) {
        $name   = isset( $agent['name'] ) ? $agent['name'] : '';
        $role   = isset( $agent['role'] ) ? $agent['role'] : '';
        $avatar = isset( $agent['avatar'] ) ? $agent['avatar'] : '';
        $type   = isset( $agent['channel_type'] ) ? $agent['channel_type'] : 'phone';
        $link   = isset( $agent['link'] ) ? $agent['link'] : '';
        $online = ! ( isset( $agent['online'] ) && 'no' === $agent['online'] );

        if ( '' === $link ) {
            return;
        }

        $status_label = $online ? __( 'Online', 'tap-chat' ) : __( 'Offline', 'tap-chat' );
        $row_class    = 'tapchat-agent-link' . ( $online ? '' : ' is-offline' );

        echo '<a class="' . esc_attr( $row_class ) . '" href="' . esc_url( $link ) . '" target="_blank" rel="noopener" data-agent-name="' . esc_attr( $name ) . '">';

        echo '<span class="tapchat-agent-avatar">';
        if ( '' !== $avatar ) {
            echo '<img src="' . esc_url( $avatar ) . '" alt="' . esc_attr( $name ) . '" />';
        } else {
            echo $this->whatsapp_svg( 'tapchat-agent-avatar-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG built internally
        }
        if ( $online ) {
            echo '<span class="tapchat-agent-dot" aria-hidden="true"></span>';
        }
        echo '</span>';

        echo '<span class="tapchat-agent-info">';
        echo '<span class="tapchat-agent-name">' . esc_html( $name ) . '</span>';
        if ( '' !== $role ) {
            echo '<span class="tapchat-agent-role">' . esc_html( $role ) . '</span>';
        }
        echo '<span class="tapchat-agent-status ' . ( $online ? 'is-online' : 'is-offline' ) . '">' . esc_html( $status_label ) . '</span>';
        echo '</span>';

        // Channel glyph on the trailing edge.
        echo '<span class="tapchat-agent-channel" aria-hidden="true">';
        if ( 'custom' === $type ) {
            echo '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>';
        } else {
            echo $this->whatsapp_svg( 'tapchat-agent-channel-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG built internally
        }
        echo '</span>';

        echo '</a>';
    }

    private function render_welcome_bubble() {
        $enable = $this->get_option('enable_welcome_bubble', 'no');
        
        if ($enable !== 'yes') {
            return;
        }
        
        $bubble_style = $this->get_option('bubble_style', 'modern');
        $bubble_position = $this->get_option('bubble_position', 'top');
        $message = $this->get_option('welcome_bubble_message', __('Need help? Let\'s chat! 💬', 'tap-chat'));
        $name = $this->get_option('welcome_bubble_name', __('Support Team', 'tap-chat'));
        $avatar = $this->get_option('welcome_bubble_avatar', '');
        $custom_icon = $this->get_option('custom_icon', '');
        
        if ( empty( $message ) ) {
            $message = __('Need help? Let\'s chat! 💬', 'tap-chat');
        }
        
        if ( empty( $name ) && $bubble_style === 'modern' ) {
            $name = __('Support Team', 'tap-chat');
        }
        
        $bubble_class = 'tapchat-welcome-bubble';
        if ($bubble_style === 'simple') {
            $bubble_class .= ' style-simple';
            if ($bubble_position === 'side') {
                $bubble_class .= ' position-side';
            }
        }
        ?>
        <div class="<?php echo esc_attr($bubble_class); ?>">
            <?php if ($bubble_style === 'modern') : ?>
                <div class="tapchat-bubble-avatar">
                    <?php if (!empty($avatar)) : ?>
                        <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($name); ?>" />
                    <?php elseif (!empty($custom_icon)) : ?>
                        <img src="<?php echo esc_url($custom_icon); ?>" alt="<?php echo esc_attr($name); ?>" />
                    <?php else : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.273-.1-.472-.149-.672.15-.197.297-.768.966-.94 1.164-.173.199-.347.224-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.672-1.611-.921-2.206-.242-.58-.487-.502-.672-.512l-.573-.01c-.198 0-.521.074-.793.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.37l-.361-.214-3.741.982.999-3.648-.236-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.89-9.884 2.64 0 5.122 1.027 6.988 2.893a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.439 9.884-9.887 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .154 5.338.157 11.892c0 2.096.549 4.142 1.595 5.945L.057 24l6.3-1.654a11.89 11.89 0 0 0 5.683 1.448h.005c6.554 0 11.895-5.338 11.898-11.893a11.82 11.82 0 0 0-3.479-8.413"/>
                        </svg>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="tapchat-bubble-content">
                <?php if ($bubble_style === 'modern') : ?>
                    <div class="tapchat-bubble-name">
                        <?php echo esc_html($name); ?>
                        <span class="tapchat-bubble-online"></span>
                    </div>
                <?php endif; ?>
                <div class="tapchat-bubble-message">
                    <?php echo esc_html($message); ?>
                </div>
            </div>
            
            <button class="tapchat-bubble-close" aria-label="<?php esc_attr_e('Close', 'tap-chat'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        </div>
        <?php
    }

    public function shortcode( $atts ) {
        $atts = shortcode_atts( [
            'phone'   => '',
            'message' => $this->get_option( 'message', '' ),
            'label'   => $this->get_option( 'label', __( 'Chat with us', 'tap-chat' ) ),
        ], $atts, 'tapchat' );

        if ( empty( $atts['phone'] ) && $this->get_option( 'link_type', 'phone' ) === 'custom' ) {
            $custom = trim( $this->get_option( 'custom_url', '' ) );
            if ( empty( $custom ) ) { return ''; }
            return sprintf(
                '<a class="tap-chat-inline" href="%s" target="_blank" rel="noopener">%s</a>',
                esc_url( $custom ),
                esc_html( $atts['label'] )
            );
        }

        if ( empty( $atts['phone'] ) ) {
            $atts['phone'] = $this->get_full_phone_number();
        }

        if ( empty( $atts['phone'] ) ) { return ''; }

        $href = $this->build_whatsapp_url( $atts['phone'], $this->replace_variables( $atts['message'] ) );
        return sprintf(
            '<a class="tap-chat-inline" href="%s" target="_blank" rel="noopener">%s</a>',
            esc_url( $href ),
            esc_html( $atts['label'] )
        );
    }
}