<?php
/**
 * Fired during plugin activation.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Activation routines.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Activator {

	/**
	 * Run tasks on plugin activation.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function activate() {
		if ( false === get_option( 'itg_plugin_setup_integrations', false ) ) {
			add_option( 'itg_plugin_setup_integrations', array() );
		}

		ITG_Plugin_Setup_Cron::schedule();
		flush_rewrite_rules();
	}
}
