<?php
/**
 * Integration contract.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contract implemented by every integration.
 *
 * @since 0.1.0
 */
interface ITG_Plugin_Setup_Integration {

	/**
	 * Unique, machine-readable identifier.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_id();

	/**
	 * Human-readable name.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Whether the integration has enough configuration to run.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_configured();

	/**
	 * Register the integration hooks. Only called when configured.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register();

	/**
	 * Describe the settings fields rendered on the admin screen.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, array<string, mixed>> Fields keyed by setting name.
	 */
	public function get_settings_fields();
}
