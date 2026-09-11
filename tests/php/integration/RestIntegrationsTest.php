<?php
/**
 * Integration tests for the integrations REST route.
 *
 * These exercise a real request/response cycle through the REST server, which
 * is what surfaces permission-callback visibility problems (the unit suite
 * calls methods in-scope and cannot catch them).
 *
 * @package ITG_Plugin_Setup
 */

/**
 * Covers authentication and payload of /itg-plugin-setup/v1/integrations.
 */
class RestIntegrationsTest extends WP_UnitTestCase {

	/**
	 * Route under test.
	 *
	 * @var string
	 */
	private $route = '/itg-plugin-setup/v1/integrations';

	/**
	 * Guests are rejected with 401.
	 */
	public function test_guest_request_is_unauthorized() {
		wp_set_current_user( 0 );

		$request  = new WP_REST_Request( 'GET', $this->route );
		$response = rest_do_request( $request );

		$this->assertSame( 401, $response->get_status() );
	}

	/**
	 * Administrators receive the registered integrations.
	 */
	public function test_admin_request_returns_integrations() {
		$admin_id = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_id );

		$request  = new WP_REST_Request( 'GET', $this->route );
		$response = rest_do_request( $request );

		$this->assertSame( 200, $response->get_status() );

		$ids = wp_list_pluck( $response->get_data(), 'id' );

		$this->assertContains( 'firebase', $ids );
		$this->assertContains( 'email', $ids );
	}
}
