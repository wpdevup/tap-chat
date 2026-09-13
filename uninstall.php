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

// Remove setup-notice bookkeeping
delete_option( 'tap_chat_setup_notice_dismissed' );

// Remove old legacy settings if exists
delete_option( 'chatly_settings' );

// Remove first-party analytics table, schema version and scheduled maintenance
global $wpdb;
$tap_chat_table = $wpdb->prefix . 'tapchat_stats';
// Dropping the plugin's own custom table on uninstall is intentional; the table
// name is a trusted identifier built from $wpdb->prefix (no user input).
$wpdb->query( "DROP TABLE IF EXISTS {$tap_chat_table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange, PluginCheck.Security.DirectDB.UnescapedDBParameter

delete_option( 'tap_chat_db_version' );

$tap_chat_timestamp = wp_next_scheduled( 'tap_chat_prune_stats' );
if ( $tap_chat_timestamp ) {
    wp_unschedule_event( $tap_chat_timestamp, 'tap_chat_prune_stats' );
}
wp_clear_scheduled_hook( 'tap_chat_prune_stats' );