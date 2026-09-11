<?php
/**
 * Global helper functions.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieve all stored integration settings.
 *
 * @since 0.1.0
 *
 * @return array<string, array<string, mixed>> Integration settings keyed by integration ID.
 */
function itg_plugin_setup_get_settings() {
	$settings = get_option( 'itg_plugin_setup_integrations', array() );

	return is_array( $settings ) ? $settings : array();
}

/**
 * Retrieve the settings for a single integration.
 *
 * @since 0.1.0
 *
 * @param string $integration_id Integration identifier.
 * @return array<string, mixed> Integration settings.
 */
function itg_plugin_setup_get_integration_settings( $integration_id ) {
	$settings = itg_plugin_setup_get_settings();

	if ( isset( $settings[ $integration_id ] ) && is_array( $settings[ $integration_id ] ) ) {
		return $settings[ $integration_id ];
	}

	return array();
}

/**
 * Retrieve a single setting for an integration.
 *
 * @since 0.1.0
 *
 * @param string $integration_id Integration identifier.
 * @param string $key            Setting key.
 * @param mixed  $fallback        Default value when the setting is missing.
 * @return mixed Setting value.
 */
function itg_plugin_setup_get_integration_setting( $integration_id, $key, $fallback = '' ) {
	$settings = itg_plugin_setup_get_integration_settings( $integration_id );

	if ( array_key_exists( $key, $settings ) ) {
		return $settings[ $key ];
	}

	return $fallback;
}
