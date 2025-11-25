<?php
/**
 * Uninstall Tap Chat – remove plugin options.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { 
    exit; 
}

// Remove plugin settings
delete_option( 'tap_chat_settings' );

// Remove old legacy settings if exists
delete_option( 'chatly_settings' );
