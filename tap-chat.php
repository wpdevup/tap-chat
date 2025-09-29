<?php
/**
 * Plugin Name: Tap Chat
 * Description: Lightweight click-to-chat button for WhatsApp (MVP). Adds a floating button and a [tapchat] shortcode. Fully translatable & GDPR-friendly.
 * Version: 0.7.0
 * Author: iruserwp9
 * Author URI: https://profiles.wordpress.org/iruserwp9/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tap-chat
 * Domain Path: /languages
 
 * Requires at least: 5.8
 * Requires PHP: 7.4*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

define('TAP_CHAT_VERSION', '0.7.0');
define( 'TAP_CHAT_PLUGIN_FILE', __FILE__ );
define( 'TAP_CHAT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TAP_CHAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once TAP_CHAT_PLUGIN_DIR . 'includes/class-tap-chat.php';

// Note: For plugins hosted on WordPress.org, translations are auto-loaded since WP 4.6.

// Migrate old option key on activation
register_activation_hook( __FILE__, function(){
    $old = get_option('chatly_settings');
    if ( is_array($old) && ! get_option('tap_chat_settings') ) {
        update_option('tap_chat_settings', $old);
    }
});

add_action( 'plugins_loaded', function() {
    \Tap_Chat\Plugin::instance();
} );