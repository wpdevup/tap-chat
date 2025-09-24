<?php
/**
 * Uninstall Chatly – remove plugin options.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }
delete_option( 'chatly_settings' );
