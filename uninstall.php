<?php
/**
 * Uninstall Tap Chat – remove plugin options.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { 
    exit; 
}

// Remove plugin settings
delete_option( 'tap_chat_settings' );

// Remove review-nudge bookkeeping
delete_option( 'tap_chat_installed_at' );
delete_option( 'tap_chat_review_variant' );
delete_option( 'tap_chat_review_done' );
delete_option( 'tap_chat_review_snooze' );

// Remove old legacy settings if exists
delete_option( 'chatly_settings' );