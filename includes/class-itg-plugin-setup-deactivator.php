<?php
/**
 * Fired during plugin deactivation.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Deactivation routines.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Deactivator {

	/**
	 * Run tasks on plugin deactivation.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function deactivate() {
		ITG_Plugin_Setup_Cron::unschedule();
		flush_rewrite_rules();
	}
}
