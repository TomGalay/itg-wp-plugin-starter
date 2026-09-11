<?php
/**
 * Integrations REST controller.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Exposes the registered integrations over REST.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_REST_Integrations_Controller extends ITG_Plugin_Setup_REST_Controller {

	/**
	 * Integration manager.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Integration_Manager|null
	 */
	private $manager;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param ITG_Plugin_Setup_Integration_Manager|null $manager Integration manager.
	 */
	public function __construct( $manager = null ) {
		$this->manager = $manager;
	}

	/**
	 * Register the routes.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/integrations',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_integrations' ),
				'permission_callback' => array( $this, 'permissions_check' ),
			)
		);
	}

	/**
	 * Return the list of integrations and their status.
	 *
	 * @since 0.1.0
	 *
	 * @return WP_REST_Response
	 */
	public function get_integrations() {
		$items = array();

		if ( $this->manager instanceof ITG_Plugin_Setup_Integration_Manager ) {
			$booted = $this->manager->get_booted();

			foreach ( $this->manager->get_integrations() as $id => $integration ) {
				$items[] = array(
					'id'         => $id,
					'name'       => $integration->get_name(),
					'configured' => (bool) $integration->is_configured(),
					'booted'     => array_key_exists( $id, $booted ),
				);
			}
		}

		return rest_ensure_response( $items );
	}
}
