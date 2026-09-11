<?php
/**
 * Base integration.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared behaviour for integrations.
 *
 * @since 0.1.0
 */
abstract class ITG_Plugin_Setup_Integration_Abstract implements ITG_Plugin_Setup_Integration {

	/**
	 * Retrieve all settings for this integration.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, mixed>
	 */
	protected function get_settings() {
		return itg_plugin_setup_get_integration_settings( $this->get_id() );
	}

	/**
	 * Retrieve a single setting for this integration.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key      Setting key.
	 * @param mixed  $fallback Default value when missing.
	 * @return mixed
	 */
	protected function get_setting( $key, $fallback = '' ) {
		return itg_plugin_setup_get_integration_setting( $this->get_id(), $key, $fallback );
	}

	/**
	 * Describe the settings fields. Override to expose configuration.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public function get_settings_fields() {
		return array();
	}
}
