<?php
/**
 * Plugin Name: Tap Chat
 * Description: Lightweight WhatsApp click-to-chat button with working hours, page visibility controls, welcome bubble, and smart country selector. GDPR-friendly with no tracking.
 * Version: 1.1.1
 * Author: iruserwp9
 * Author URI: https://profiles.wordpress.org/iruserwp9/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tap-chat
 * Domain Path: /languages
 
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define('TAP_CHAT_VERSION', '1.1.1');
define( 'TAP_CHAT_PLUGIN_FILE', __FILE__ );
define( 'TAP_CHAT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TAP_CHAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once TAP_CHAT_PLUGIN_DIR . 'includes/class-tap-chat.php';

// Migrate old option key and phone format on activation
register_activation_hook( __FILE__, function(){
    // Migrate from old 'chatly_settings' to 'tap_chat_settings'
    $old = get_option('chatly_settings');
    if ( is_array($old) && ! get_option('tap_chat_settings') ) {
        update_option('tap_chat_settings', $old);
    }
    
    // Migrate old phone format to new country_code + phone format
    $settings = get_option('tap_chat_settings', array());
    
    // If phone exists but country_code doesn't, try to extract country code
    if ( ! empty( $settings['phone'] ) && empty( $settings['country_code'] ) ) {
        $phone = preg_replace('/\D+/', '', $settings['phone']);
        
        // Remove leading zeros first
        $phone = ltrim($phone, '0');
        
        // Common country codes and their lengths
        $country_codes = array(
            '1' => 1, '7' => 1, '20' => 2, '27' => 2, '30' => 2, '31' => 2, '32' => 2, 
            '33' => 2, '34' => 2, '36' => 2, '39' => 2, '40' => 2, '41' => 2, '43' => 2, 
            '44' => 2, '45' => 2, '46' => 2, '47' => 2, '48' => 2, '49' => 2, '51' => 2,
            '52' => 2, '53' => 2, '54' => 2, '55' => 2, '56' => 2, '57' => 2, '58' => 2,
            '60' => 2, '61' => 2, '62' => 2, '63' => 2, '64' => 2, '65' => 2, '66' => 2,
            '81' => 2, '82' => 2, '84' => 2, '86' => 2, '90' => 2, '91' => 2, '92' => 2,
            '93' => 2, '94' => 2, '95' => 2, '98' => 2,
        );
        
        // Get default country code based on locale
        $locale = get_locale();
        $locale_map = array(
            'de_DE' => '49', 'de_AT' => '43', 'de_CH' => '41',
            'en_US' => '1', 'en_GB' => '44', 'en_CA' => '1', 'en_AU' => '61', 'en_NZ' => '64',
            'fr_FR' => '33', 'fr_BE' => '32', 'fr_CA' => '1', 'fr_CH' => '41',
            'es_ES' => '34', 'es_MX' => '52', 'es_AR' => '54', 'es_CO' => '57',
            'it_IT' => '39', 'nl_NL' => '31', 'pt_PT' => '351', 'pt_BR' => '55',
            'ru_RU' => '7', 'pl_PL' => '48', 'tr_TR' => '90'
        );
        
        $country_code = isset($locale_map[$locale]) ? $locale_map[$locale] : '49';
        $phone_number = $phone;
        
        // Try to extract country code from the full number
        foreach ( $country_codes as $code => $length ) {
            if ( substr( $phone, 0, $length ) === $code ) {
                $country_code = $code;
                $phone_number = substr( $phone, $length );
                break;
            }
        }
        
        // Remove any remaining leading zeros from phone number
        $phone_number = ltrim($phone_number, '0');
        
        $settings['country_code'] = $country_code;
        $settings['phone'] = $phone_number;
        
        update_option('tap_chat_settings', $settings);
    }
    
    // Initialize working hours if not set
    if ( ! isset( $settings['working_hours'] ) ) {
        $settings['working_hours'] = array(
            'monday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'tuesday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'wednesday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'thursday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'friday' => array('enabled' => 'yes', 'start' => '09:00', 'end' => '17:00'),
            'saturday' => array('enabled' => 'no', 'start' => '09:00', 'end' => '17:00'),
            'sunday' => array('enabled' => 'no', 'start' => '09:00', 'end' => '17:00'),
        );
        
        $settings['enable_working_hours'] = 'no';
        $settings['timezone'] = wp_timezone_string();
        $settings['offline_message'] = '';
        
        update_option('tap_chat_settings', $settings);
    }
    
    // Initialize welcome bubble settings
    if ( ! isset( $settings['enable_welcome_bubble'] ) ) {
        $settings['enable_welcome_bubble'] = 'no';
        $settings['welcome_bubble_message'] = __('Need help? Let\'s chat! 💬', 'tap-chat');
        $settings['welcome_bubble_name'] = __('Support Team', 'tap-chat');
        $settings['welcome_bubble_avatar'] = '';
        $settings['welcome_bubble_delay'] = 3;
        
        update_option('tap_chat_settings', $settings);
    }
});

add_action( 'plugins_loaded', function() {
    \Tap_Chat\Plugin::instance();
} );