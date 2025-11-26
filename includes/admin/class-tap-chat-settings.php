<?php
namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin_Settings {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function register_settings() {
        register_setting( 'tap_chat_settings_group', 'tap_chat_settings', array(
            'type' => 'array',
            'sanitize_callback' => array( $this, 'sanitize' ),
            'default' => $this->get_defaults()
        ) );

        $this->register_general_settings();
        $this->register_bubble_settings();
        $this->register_hours_settings();
        $this->register_visibility_settings();
        $this->register_advanced_settings();
    }

    private function register_general_settings() {
        add_settings_section( 
            'tapchat_general', 
            __( 'Basic Configuration', 'tap-chat' ), 
            array( $this, 'general_section_callback' ),
            'tap-chat-general' 
        );

        $fields = new Admin_Fields();
        
        add_settings_field( 'enable_floating', __( 'Enable Floating Button', 'tap-chat' ), 
            array( $fields, 'field_enable_floating' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'phone', __( 'WhatsApp Phone Number', 'tap-chat' ), 
            array( $fields, 'field_phone' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'message', __( 'Default Message', 'tap-chat' ), 
            array( $fields, 'field_message' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'label', __( 'Button Label', 'tap-chat' ), 
            array( $fields, 'field_label' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'custom_icon', __( 'Custom Icon', 'tap-chat' ), 
            array( $fields, 'field_custom_icon' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'position', __( 'Button Position', 'tap-chat' ), 
            array( $fields, 'field_position' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'color', __( 'Button Color', 'tap-chat' ), 
            array( $fields, 'field_color' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'size', __( 'Desktop Size (px)', 'tap-chat' ), 
            array( $fields, 'field_size' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'mobile_size', __( 'Mobile Size (px)', 'tap-chat' ), 
            array( $fields, 'field_mobile_size' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'hide_label_mobile', __( 'Mobile Label', 'tap-chat' ), 
            array( $fields, 'field_hide_label_mobile' ), 'tap-chat-general', 'tapchat_general' );
        
        add_settings_field( 'hide_label_desktop', __( 'Desktop Label', 'tap-chat' ), 
            array( $fields, 'field_hide_label_desktop' ), 'tap-chat-general', 'tapchat_general' );
    }

    private function register_bubble_settings() {
        add_settings_section( 
            'tapchat_bubble', 
            __( 'Welcome Bubble Configuration', 'tap-chat' ), 
            array( $this, 'bubble_section_callback' ),
            'tap-chat-bubble' 
        );

        $fields = new Admin_Fields();
        
        add_settings_field( 'welcome_bubble_controls', __( 'Bubble Settings', 'tap-chat' ), 
            array( $fields, 'field_welcome_bubble_controls' ), 'tap-chat-bubble', 'tapchat_bubble' );
        
        add_settings_field( 'smart_triggers', __( 'Smart Triggers', 'tap-chat' ), 
            array( $fields, 'field_smart_triggers' ), 'tap-chat-bubble', 'tapchat_bubble' );
    }

    private function register_hours_settings() {
        add_settings_section( 
            'tapchat_hours', 
            __( 'Business Hours Configuration', 'tap-chat' ), 
            array( $this, 'hours_section_callback' ),
            'tap-chat-hours' 
        );

        $fields = new Admin_Fields();
        
        add_settings_field( 'working_hours_controls', __( 'Schedule Settings', 'tap-chat' ), 
            array( $fields, 'field_working_hours_controls' ), 'tap-chat-hours', 'tapchat_hours' );
    }

    private function register_visibility_settings() {
        add_settings_section( 
            'tapchat_visibility', 
            __( 'Page Visibility Configuration', 'tap-chat' ), 
            array( $this, 'visibility_section_callback' ),
            'tap-chat-visibility' 
        );

        $fields = new Admin_Fields();
        
        add_settings_field( 'visibility_controls', __( 'Display Rules', 'tap-chat' ), 
            array( $fields, 'field_visibility_controls' ), 'tap-chat-visibility', 'tapchat_visibility' );
    }

    private function register_advanced_settings() {
        add_settings_section( 
            'tapchat_advanced', 
            __( 'Advanced Options', 'tap-chat' ), 
            array( $this, 'advanced_section_callback' ),
            'tap-chat-advanced' 
        );

        $fields = new Admin_Fields();
        
        add_settings_field( 'append_page_context', __( 'Page Context', 'tap-chat' ), 
            array( $fields, 'field_append_page_context' ), 'tap-chat-advanced', 'tapchat_advanced' );
    }

    public function general_section_callback() {
        echo '<p>' . esc_html__( 'Configure your WhatsApp button appearance and basic settings.', 'tap-chat' ) . '</p>';
    }

    public function bubble_section_callback() {
        echo '<p>' . esc_html__( 'Display a friendly welcome message to encourage visitors to start a conversation. Use smart triggers to show the bubble at the perfect moment.', 'tap-chat' ) . '</p>';
    }

    public function hours_section_callback() {
        echo '<p>' . esc_html__( 'Set your business hours. The button will only show during these hours.', 'tap-chat' ) . '</p>';
    }

    public function visibility_section_callback() {
        echo '<p>' . esc_html__( 'Control where the WhatsApp button appears on your site.', 'tap-chat' ) . '</p>';
    }

    public function advanced_section_callback() {
        echo '<p>' . esc_html__( 'Advanced configuration options for power users.', 'tap-chat' ) . '</p>';
    }

    private function get_defaults() {
        return array(
            'enable_floating' => 'yes',
            'country_code' => $this->get_default_country_code(),
            'phone' => '',
            'message' => '',
            'label' => __( 'Chat with us', 'tap-chat' ),
            'custom_icon' => '',
            'position' => 'right',
            'size' => 40,
            'mobile_size' => 40,
            'color' => '#25D366',
            'hide_label_mobile' => 'yes',
            'hide_label_desktop' => 'no',
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
        $out['country_code'] = isset( $input['country_code'] ) ? sanitize_text_field( $input['country_code'] ) : $this->get_default_country_code();
        
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
        
        return $out;
    }
}