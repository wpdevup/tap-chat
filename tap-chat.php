<?php
/**
 * Plugin Name: Tap Chat
 * Description: Lightweight click-to-chat button for WhatsApp (MVP). Adds a floating button and a [tapchat] shortcode. Fully translatable & GDPR-friendly.
 * Version: 0.9.0
 * Author: iruserwp9
 * Author URI: https://profiles.wordpress.org/iruserwp9/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tap-chat
 * Domain Path: /languages
 
 * Requires at least: 5.8
 * Requires PHP: 7.4*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

define('TAP_CHAT_VERSION', '0.9.0');
define( 'TAP_CHAT_PLUGIN_FILE', __FILE__ );
define( 'TAP_CHAT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TAP_CHAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once TAP_CHAT_PLUGIN_DIR . 'includes/class-tap-chat.php';

// Note: For plugins hosted on WordPress.org, translations are auto-loaded since WP 4.6.

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
            '1' => 1,
            '7' => 1,
            '20' => 2,
            '27' => 2,
            '30' => 2,
            '31' => 2,
            '32' => 2,
            '33' => 2,
            '34' => 2,
            '36' => 2,
            '39' => 2,
            '40' => 2,
            '41' => 2,
            '43' => 2,
            '44' => 2,
            '45' => 2,
            '46' => 2,
            '47' => 2,
            '48' => 2,
            '49' => 2,
            '51' => 2,
            '52' => 2,
            '53' => 2,
            '54' => 2,
            '55' => 2,
            '56' => 2,
            '57' => 2,
            '58' => 2,
            '60' => 2,
            '61' => 2,
            '62' => 2,
            '63' => 2,
            '64' => 2,
            '65' => 2,
            '66' => 2,
            '81' => 2,
            '82' => 2,
            '84' => 2,
            '86' => 2,
            '90' => 2,
            '91' => 2,
            '92' => 2,
            '93' => 2,
            '94' => 2,
            '95' => 2,
            '98' => 2,
            '212' => 3,
            '213' => 3,
            '216' => 3,
            '218' => 3,
            '220' => 3,
            '221' => 3,
            '222' => 3,
            '223' => 3,
            '224' => 3,
            '225' => 3,
            '226' => 3,
            '227' => 3,
            '228' => 3,
            '229' => 3,
            '230' => 3,
            '231' => 3,
            '233' => 3,
            '234' => 3,
            '235' => 3,
            '236' => 3,
            '237' => 3,
            '238' => 3,
            '240' => 3,
            '241' => 3,
            '242' => 3,
            '243' => 3,
            '244' => 3,
            '245' => 3,
            '249' => 3,
            '250' => 3,
            '251' => 3,
            '252' => 3,
            '253' => 3,
            '254' => 3,
            '255' => 3,
            '256' => 3,
            '257' => 3,
            '258' => 3,
            '260' => 3,
            '261' => 3,
            '263' => 3,
            '264' => 3,
            '265' => 3,
            '266' => 3,
            '267' => 3,
            '268' => 3,
            '269' => 3,
            '291' => 3,
            '350' => 3,
            '351' => 3,
            '352' => 3,
            '353' => 3,
            '354' => 3,
            '355' => 3,
            '356' => 3,
            '357' => 3,
            '358' => 3,
            '359' => 3,
            '370' => 3,
            '371' => 3,
            '372' => 3,
            '373' => 3,
            '374' => 3,
            '375' => 3,
            '376' => 3,
            '377' => 3,
            '380' => 3,
            '381' => 3,
            '382' => 3,
            '385' => 3,
            '386' => 3,
            '387' => 3,
            '389' => 3,
            '420' => 3,
            '421' => 3,
            '423' => 3,
            '501' => 3,
            '502' => 3,
            '503' => 3,
            '504' => 3,
            '505' => 3,
            '506' => 3,
            '507' => 3,
            '509' => 3,
            '591' => 3,
            '592' => 3,
            '593' => 3,
            '595' => 3,
            '597' => 3,
            '598' => 3,
            '673' => 3,
            '852' => 3,
            '855' => 3,
            '856' => 3,
            '880' => 3,
            '886' => 3,
            '960' => 3,
            '961' => 3,
            '962' => 3,
            '963' => 3,
            '964' => 3,
            '965' => 3,
            '966' => 3,
            '967' => 3,
            '968' => 3,
            '970' => 3,
            '971' => 3,
            '972' => 3,
            '973' => 3,
            '974' => 3,
            '975' => 3,
            '976' => 3,
            '977' => 3,
            '992' => 3,
            '993' => 3,
            '994' => 3,
            '995' => 3,
            '996' => 3,
            '998' => 3
        );
        
        // Get default country code based on locale
        $locale = get_locale();
        $locale_map = array(
            'de_DE' => '49',
            'de_AT' => '43',
            'de_CH' => '41',
            'en_US' => '1',
            'en_GB' => '44',
            'en_CA' => '1',
            'en_AU' => '61',
            'en_NZ' => '64',
            'fr_FR' => '33',
            'fr_BE' => '32',
            'fr_CA' => '1',
            'fr_CH' => '41',
            'es_ES' => '34',
            'es_MX' => '52',
            'es_AR' => '54',
            'es_CO' => '57',
            'it_IT' => '39',
            'nl_NL' => '31',
            'pt_PT' => '351',
            'pt_BR' => '55',
            'ru_RU' => '7',
            'pl_PL' => '48',
            'tr_TR' => '90'
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
});

add_action( 'plugins_loaded', function() {
    \Tap_Chat\Plugin::instance();
} );