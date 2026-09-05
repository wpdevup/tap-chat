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

// Remove first-party analytics table, schema version and scheduled maintenance
global $wpdb;
$table = $wpdb->prefix . 'tapchat_stats';
$wpdb->query( "DROP TABLE IF EXISTS {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

delete_option( 'tap_chat_db_version' );

$timestamp = wp_next_scheduled( 'tap_chat_prune_stats' );
if ( $timestamp ) {
    wp_unschedule_event( $timestamp, 'tap_chat_prune_stats' );
}
wp_clear_scheduled_hook( 'tap_chat_prune_stats' );