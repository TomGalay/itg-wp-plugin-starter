<?php
/**
 * Uninstall routines.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'itg_plugin_setup_integrations' );

$itg_plugin_setup_timestamp = wp_next_scheduled( 'itg_plugin_setup_cron_tick' );

while ( $itg_plugin_setup_timestamp ) {
	wp_unschedule_event( $itg_plugin_setup_timestamp, 'itg_plugin_setup_cron_tick' );
	$itg_plugin_setup_timestamp = wp_next_scheduled( 'itg_plugin_setup_cron_tick' );
}
