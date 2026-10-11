<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin_Settings {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Register the option. The screens are rendered by Admin_Fields, so no
     * Settings API sections or fields are registered here.
     */
    public function register_settings() {
        register_setting( 'tap_chat_settings_group', 'tap_chat_settings', array(
            'type' => 'array',
            'sanitize_callback' => array( $this, 'sanitize' ),
            'default' => $this->get_defaults()
        ) );
    }

    private function get_defaults() {
        return array(
            'enable_floating' => 'yes',
            'button_mode' => 'single',
            'agents_panel_title' => __( 'Start a conversation', 'tap-chat' ),
            'agents_panel_subtitle' => __( 'Choose someone to chat with', 'tap-chat' ),
            'agents' => array(),
            'link_type' => 'phone',
            'country_code' => $this->get_default_country_code(),
            'phone' => '',
            'custom_url' => '',
            'message' => '',
            'label' => __( 'Chat with us', 'tap-chat' ),
            'custom_icon' => '',
            'position' => 'right',
            'size' => 40,
            'mobile_size' => 40,
            'color' => '#25D366',
            'button_animation' => 'none',
            'hide_label_mobile' => 'yes',
            'hide_label_desktop' => 'yes',
            'append_page_context' => 'no',
            'enable_show_on' => 'no',
            'enable_hide_on' => 'no',
            'show_on_pages' => array(),
            'hide_on_pages' => array(),
            'enable_working_hours' => 'no',
            'timezone' => wp_timezone_string(),
            'offline_message' => '',
            'working_hours' => $this->get_default_working_hours(),
            'enable_welcome_bubble' => 'no',
            'bubble_style' => 'modern',
            'bubble_position' => 'top',
            'welcome_bubble_message' => __('Need help? Let\'s chat! 💬', 'tap-chat'),
            'welcome_bubble_name' => __('Support Team', 'tap-chat'),
            'welcome_bubble_avatar' => '',
            'trigger_scroll_enabled' => 'no',
            'trigger_scroll_depth' => 50,
            'trigger_exit_enabled' => 'no',
            'trigger_time_enabled' => 'yes',
            'trigger_time_delay' => 3,
            'trigger_idle_enabled' => 'no',
            'trigger_idle_time' => 60,
            'enable_analytics' => 'no',
            'analytics_method' => 'auto',
            'analytics_event_name' => 'tapchat_click',
            'analytics_track_triggers' => 'yes',
            'enable_click_analytics' => 'yes',
            'enable_traffic_analytics' => 'no',
            'analytics_exclude_logged_in' => 'no',
            'analytics_retention_days' => 365,
        );
    }

    private function get_default_country_code() {
        $locale = get_locale();
        
        $locale_map = array(
            'de_DE' => '49', 'de_AT' => '43', 'de_CH' => '41',
            'en_US' => '1', 'en_GB' => '44', 'en_CA' => '1', 'en_AU' => '61', 'en_NZ' => '64',
            'fr_FR' => '33', 'fr_BE' => '32', 'fr_CA' => '1', 'fr_CH' => '41',
            'es_ES' => '34', 'es_MX' => '52', 'es_AR' => '54', 'es_CO' => '57',
            'it_IT' => '39', 'nl_NL' => '31', 'pt_PT' => '351', 'pt_BR' => '55',
        );
        
        return isset( $locale_map[ $locale ] ) ? $locale_map[ $locale ] : '49';
    }

    private function get_default_working_hours() {
        return array(
            'monday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'tuesday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'wednesday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'thursday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'friday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'saturday' => array('enabled' => 'no', 'start' => '09:00', 'end' => '17:00'),
            'sunday' => array('enabled' => 'no', 'start' => '09:00', 'end' => '17:00'),
        );
    }

    public function sanitize( $input ) {
        $out = array();
        
        // General tab
        $out['enable_floating'] = ( isset( $input['enable_floating'] ) && $input['enable_floating'] === 'yes' ) ? 'yes' : 'no';

        // Agents tab
        $out['button_mode'] = ( isset( $input['button_mode'] ) && $input['button_mode'] === 'agents' ) ? 'agents' : 'single';
        $out['agents_panel_title'] = isset( $input['agents_panel_title'] ) ? sanitize_text_field( $input['agents_panel_title'] ) : '';
        $out['agents_panel_subtitle'] = isset( $input['agents_panel_subtitle'] ) ? sanitize_text_field( $input['agents_panel_subtitle'] ) : '';
        $out['agents'] = $this->sanitize_agents( isset( $input['agents'] ) ? $input['agents'] : array() );

        $out['link_type'] = ( isset( $input['link_type'] ) && $input['link_type'] === 'custom' ) ? 'custom' : 'phone';
        $out['country_code'] = isset( $input['country_code'] ) ? sanitize_text_field( $input['country_code'] ) : $this->get_default_country_code();
        $out['custom_url'] = isset( $input['custom_url'] ) ? esc_url_raw( trim( $input['custom_url'] ) ) : '';
        
        if ( isset( $input['phone'] ) ) {
            $phone = sanitize_text_field( $input['phone'] );
            $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
            $phone = ltrim($phone, '0');
            $out['phone'] = $phone;
        } else {
            $out['phone'] = '';
        }
        
        $out['message'] = isset( $input['message'] ) ? sanitize_textarea_field( $input['message'] ) : '';
        $out['label'] = isset( $input['label'] ) ? sanitize_text_field( $input['label'] ) : '';
        $out['custom_icon'] = isset( $input['custom_icon'] ) ? esc_url_raw( $input['custom_icon'] ) : '';
        $out['position'] = ( isset( $input['position'] ) && in_array( $input['position'], array( 'left','right' ), true ) ) ? $input['position'] : 'right';
        $out['size'] = isset( $input['size'] ) ? absint( $input['size'] ) : 40;
        $out['mobile_size'] = isset( $input['mobile_size'] ) ? absint( $input['mobile_size'] ) : 40;
        $out['color'] = isset( $input['color'] ) ? sanitize_hex_color( $input['color'] ) : '#25D366';
        $allowed_anim = array( 'none', 'fade', 'slide', 'bounce', 'ring', 'attention' );
        $out['button_animation'] = ( isset( $input['button_animation'] ) && in_array( $input['button_animation'], $allowed_anim, true ) ) ? $input['button_animation'] : 'none';
        $out['hide_label_mobile'] = ( isset( $input['hide_label_mobile'] ) && $input['hide_label_mobile'] === 'yes' ) ? 'yes' : 'no';
        $out['hide_label_desktop'] = ( isset( $input['hide_label_desktop'] ) && $input['hide_label_desktop'] === 'yes' ) ? 'yes' : 'no';
        
        // Bubble tab
        $out['enable_welcome_bubble'] = ( isset( $input['enable_welcome_bubble'] ) && $input['enable_welcome_bubble'] === 'yes' ) ? 'yes' : 'no';
        $out['bubble_style'] = ( isset( $input['bubble_style'] ) && in_array( $input['bubble_style'], array( 'modern', 'simple' ), true ) ) ? $input['bubble_style'] : 'modern';
        $out['bubble_position'] = ( isset( $input['bubble_position'] ) && in_array( $input['bubble_position'], array( 'top', 'side' ), true ) ) ? $input['bubble_position'] : 'top';
        
        $out['welcome_bubble_message'] = isset( $input['welcome_bubble_message'] ) ? sanitize_textarea_field( $input['welcome_bubble_message'] ) : __('Need help? Let\'s chat! 💬', 'tap-chat');
        $out['welcome_bubble_name'] = isset( $input['welcome_bubble_name'] ) ? sanitize_text_field( $input['welcome_bubble_name'] ) : __('Support Team', 'tap-chat');
        $out['welcome_bubble_avatar'] = isset( $input['welcome_bubble_avatar'] ) ? esc_url_raw( $input['welcome_bubble_avatar'] ) : '';
        
        $out['trigger_scroll_enabled'] = ( isset( $input['trigger_scroll_enabled'] ) && $input['trigger_scroll_enabled'] === 'yes' ) ? 'yes' : 'no';
        $out['trigger_scroll_depth'] = isset( $input['trigger_scroll_depth'] ) ? absint( $input['trigger_scroll_depth'] ) : 50;
        $out['trigger_exit_enabled'] = ( isset( $input['trigger_exit_enabled'] ) && $input['trigger_exit_enabled'] === 'yes' ) ? 'yes' : 'no';
        $out['trigger_time_enabled'] = ( isset( $input['trigger_time_enabled'] ) && $input['trigger_time_enabled'] === 'yes' ) ? 'yes' : 'no';
        $out['trigger_time_delay'] = isset( $input['trigger_time_delay'] ) ? absint( $input['trigger_time_delay'] ) : 3;
        $out['trigger_idle_enabled'] = ( isset( $input['trigger_idle_enabled'] ) && $input['trigger_idle_enabled'] === 'yes' ) ? 'yes' : 'no';
        $out['trigger_idle_time'] = isset( $input['trigger_idle_time'] ) ? absint( $input['trigger_idle_time'] ) : 60;
        
        // Hours tab
        $out['enable_working_hours'] = ( isset( $input['enable_working_hours'] ) && $input['enable_working_hours'] === 'yes' ) ? 'yes' : 'no';
        $out['timezone'] = isset( $input['timezone'] ) ? sanitize_text_field( $input['timezone'] ) : wp_timezone_string();
        $out['offline_message'] = isset( $input['offline_message'] ) ? sanitize_textarea_field( $input['offline_message'] ) : '';
        
        if ( isset( $input['working_hours'] ) && is_array( $input['working_hours'] ) ) {
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            $out['working_hours'] = array();
            
            foreach ( $days as $day ) {
                if ( isset( $input['working_hours'][$day] ) ) {
                    $out['working_hours'][$day] = array(
                        'enabled' => ( isset( $input['working_hours'][$day]['enabled'] ) && $input['working_hours'][$day]['enabled'] === 'yes' ) ? 'yes' : 'no',
                        'start' => isset( $input['working_hours'][$day]['start'] ) ? sanitize_text_field( $input['working_hours'][$day]['start'] ) : '09:00',
                        'end' => isset( $input['working_hours'][$day]['end'] ) ? sanitize_text_field( $input['working_hours'][$day]['end'] ) : '17:00',
                    );
                } else {
                    $out['working_hours'][$day] = array('enabled' => 'no', 'start' => '09:00', 'end' => '17:00');
                }
            }
        } else {
            $out['working_hours'] = $this->get_default_working_hours();
        }
        
        // Visibility tab
        $out['enable_show_on'] = ( isset( $input['enable_show_on'] ) && $input['enable_show_on'] === 'yes' ) ? 'yes' : 'no';
        $out['enable_hide_on'] = ( isset( $input['enable_hide_on'] ) && $input['enable_hide_on'] === 'yes' ) ? 'yes' : 'no';
        $out['show_on_pages'] = isset( $input['show_on_pages'] ) && is_array( $input['show_on_pages'] ) ? array_map( 'absint', $input['show_on_pages'] ) : array();
        $out['hide_on_pages'] = isset( $input['hide_on_pages'] ) && is_array( $input['hide_on_pages'] ) ? array_map( 'absint', $input['hide_on_pages'] ) : array();
        
        // Advanced tab
        $out['append_page_context'] = ( isset( $input['append_page_context'] ) && $input['append_page_context'] === 'yes' ) ? 'yes' : 'no';
        
        // Analytics tab
        $out['enable_analytics'] = ( isset( $input['enable_analytics'] ) && $input['enable_analytics'] === 'yes' ) ? 'yes' : 'no';
        $allowed_method = array( 'auto', 'gtag', 'datalayer' );
        $out['analytics_method'] = ( isset( $input['analytics_method'] ) && in_array( $input['analytics_method'], $allowed_method, true ) ) ? $input['analytics_method'] : 'auto';
        $out['analytics_event_name'] = isset( $input['analytics_event_name'] ) ? $this->sanitize_event_name( $input['analytics_event_name'] ) : 'tapchat_click';
        $out['analytics_track_triggers'] = ( isset( $input['analytics_track_triggers'] ) && $input['analytics_track_triggers'] === 'yes' ) ? 'yes' : 'no';

        // First-party analytics
        $out['enable_click_analytics'] = ( isset( $input['enable_click_analytics'] ) && $input['enable_click_analytics'] === 'yes' ) ? 'yes' : 'no';
        $out['enable_traffic_analytics'] = ( isset( $input['enable_traffic_analytics'] ) && $input['enable_traffic_analytics'] === 'yes' ) ? 'yes' : 'no';
        $out['analytics_exclude_logged_in'] = ( isset( $input['analytics_exclude_logged_in'] ) && $input['analytics_exclude_logged_in'] === 'yes' ) ? 'yes' : 'no';
        $retention = isset( $input['analytics_retention_days'] ) ? absint( $input['analytics_retention_days'] ) : 365;
        $out['analytics_retention_days'] = max( 7, min( 3650, $retention ) );

        return $out;
    }

    /**
     * Sanitize the agents repeater: cap at 3, drop empty rows, and normalize
     * every field. Preserves each row's stable id (or generates a new one).
     */
    private function sanitize_agents( $agents ) {
        if ( ! is_array( $agents ) ) {
            return array();
        }

        $out = array();

        foreach ( $agents as $agent ) {
            if ( ! is_array( $agent ) ) {
                continue;
            }

            $name         = isset( $agent['name'] ) ? sanitize_text_field( $agent['name'] ) : '';
            $channel_type = ( isset( $agent['channel_type'] ) && $agent['channel_type'] === 'custom' ) ? 'custom' : 'phone';

            $phone = isset( $agent['phone'] ) ? sanitize_text_field( $agent['phone'] ) : '';
            $phone = preg_replace( '/[\s\-\(\)]/', '', $phone );
            $phone = ltrim( $phone, '0' );

            $custom_url = isset( $agent['custom_url'] ) ? esc_url_raw( trim( $agent['custom_url'] ) ) : '';

            // Skip rows the user left completely blank.
            $has_contact = ( 'custom' === $channel_type ) ? ( '' !== $custom_url ) : ( '' !== $phone );
            if ( '' === $name && ! $has_contact ) {
                continue;
            }

            $id = ( isset( $agent['id'] ) && '' !== $agent['id'] )
                ? sanitize_key( $agent['id'] )
                : 'agent_' . substr( md5( uniqid( '', true ) ), 0, 8 );

            $out[] = array(
                'id'           => $id,
                'name'         => $name,
                'role'         => isset( $agent['role'] ) ? sanitize_text_field( $agent['role'] ) : '',
                'avatar'       => isset( $agent['avatar'] ) ? esc_url_raw( $agent['avatar'] ) : '',
                'channel_type' => $channel_type,
                'country_code' => isset( $agent['country_code'] ) ? preg_replace( '/\D+/', '', sanitize_text_field( $agent['country_code'] ) ) : '',
                'phone'        => $phone,
                'custom_url'   => $custom_url,
                'message'      => isset( $agent['message'] ) ? sanitize_textarea_field( $agent['message'] ) : '',
                'online'       => ( isset( $agent['online'] ) && $agent['online'] === 'yes' ) ? 'yes' : 'no',
                'enabled'      => ( isset( $agent['enabled'] ) && $agent['enabled'] === 'no' ) ? 'no' : 'yes',
            );

            if ( count( $out ) >= 3 ) {
                break;
            }
        }

        return $out;
    }

    /**
     * Sanitize a GA4 event name: lowercase, letters/numbers/underscores only,
     * must start with a letter, max 40 chars. Falls back to 'tapchat_click'.
     */
    private function sanitize_event_name( $name ) {
        $name = strtolower( trim( (string) $name ) );
        $name = preg_replace( '/[^a-z0-9_]/', '_', $name );
        $name = preg_replace( '/_+/', '_', $name );
        $name = ltrim( $name, '0123456789_' );
        if ( '' === $name ) {
            $name = 'tapchat_click';
        }
        return substr( $name, 0, 40 );
    }
}