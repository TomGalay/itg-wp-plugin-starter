<?php
/**
 * Integration registry.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Collects integrations and boots the ones that are configured.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Integration_Manager {

	/**
	 * All registered integrations keyed by ID.
	 *
	 * @since 0.1.0
	 * @var array<string, ITG_Plugin_Setup_Integration>
	 */
	private $integrations = array();

	/**
	 * Integrations that have been booted.
	 *
	 * @since 0.1.0
	 * @var array<string, ITG_Plugin_Setup_Integration>
	 */
	private $booted = array();

	/**
	 * Register the default integrations and apply the filter.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$defaults = array(
			new ITG_Plugin_Setup_Integration_Firebase(),
			new ITG_Plugin_Setup_Integration_Email(),
		);

		/**
		 * Filter the list of available integrations.
		 *
		 * @since 0.1.0
		 *
		 * @param ITG_Plugin_Setup_Integration[] $integrations Integration instances.
		 */
		$integrations = apply_filters( 'itg_plugin_setup_integrations', $defaults );

		foreach ( $integrations as $integration ) {
			if ( $integration instanceof ITG_Plugin_Setup_Integration ) {
				$this->integrations[ $integration->get_id() ] = $integration;
			}
		}
	}

	/**
	 * Boot every configured integration.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		foreach ( $this->integrations as $id => $integration ) {
			if ( $integration->is_configured() ) {
				$integration->register();
				$this->booted[ $id ] = $integration;
			}
		}
	}

	/**
	 * Retrieve every registered integration.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, ITG_Plugin_Setup_Integration>
	 */
	public function get_integrations() {
		return $this->integrations;
	}

	/**
	 * Retrieve the booted integrations.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, ITG_Plugin_Setup_Integration>
	 */
	public function get_booted() {
		return $this->booted;
	}

	/**
	 * Retrieve a single integration by ID.
	 *
	 * @since 0.1.0
	 *
	 * @param string $id Integration identifier.
	 * @return ITG_Plugin_Setup_Integration|null
	 */
	public function get( $id ) {
		return isset( $this->integrations[ $id ] ) ? $this->integrations[ $id ] : null;
	}

	/**
	 * Whether an integration is configured.
	 *
	 * @since 0.1.0
	 *
	 * @param string $id Integration identifier.
	 * @return bool
	 */
	public function is_configured( $id ) {
		$integration = $this->get( $id );

		return $integration instanceof ITG_Plugin_Setup_Integration ? $integration->is_configured() : false;
	}
}
