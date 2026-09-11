<?php
/**
 * Scheduled tasks.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the plugin cron events and custom schedules.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Cron {

	/**
	 * Cron hook fired on the daily maintenance tick.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	const HOOK = 'itg_plugin_setup_cron_tick';

	/**
	 * Custom schedule identifier.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	const SCHEDULE = 'itg_plugin_setup_daily';

	/**
	 * Register hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'cron_schedules', array( __CLASS__, 'add_schedules' ) );
		add_action( self::HOOK, array( $this, 'run' ) );
	}

	/**
	 * Schedule the daily event.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function schedule() {
		add_filter( 'cron_schedules', array( __CLASS__, 'add_schedules' ) );

		if ( ! wp_next_scheduled( self::HOOK ) ) {
			wp_schedule_event( time(), self::SCHEDULE, self::HOOK );
		}
	}

	/**
	 * Unschedule the daily event.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function unschedule() {
		$timestamp = wp_next_scheduled( self::HOOK );

		while ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::HOOK );
			$timestamp = wp_next_scheduled( self::HOOK );
		}
	}

	/**
	 * Register the custom cron schedule.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string, array<string, mixed>> $schedules Existing schedules.
	 * @return array<string, array<string, mixed>> Filtered schedules.
	 */
	public static function add_schedules( $schedules ) {
		$schedules[ self::SCHEDULE ] = array(
			'interval' => DAY_IN_SECONDS,
			'display'  => __( 'Once Daily (ITG Plugin Setup)', 'itg-plugin-setup' ),
		);

		return $schedules;
	}

	/**
	 * Execute the daily maintenance task.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function run() {
		/**
		 * Fires on the daily maintenance tick.
		 *
		 * @since 0.1.0
		 */
		do_action( 'itg_plugin_setup_daily_tick' );
	}
}
