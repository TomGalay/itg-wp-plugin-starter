<?php
/**
 * Integration manager tests.
 *
 * @package ITG_Plugin_Setup
 */

use PHPUnit\Framework\TestCase;

/**
 * Covers the integration registry and the Firebase client guard rails.
 */
class IntegrationManagerTest extends TestCase {

	/**
	 * The manager exposes the bundled example integrations.
	 */
	public function test_manager_registers_default_integrations() {
		$manager      = new ITG_Plugin_Setup_Integration_Manager();
		$integrations = $manager->get_integrations();

		$this->assertArrayHasKey( 'firebase', $integrations );
		$this->assertArrayHasKey( 'email', $integrations );
	}

	/**
	 * Unconfigured integrations are not booted.
	 */
	public function test_unconfigured_integrations_are_not_booted() {
		$manager = new ITG_Plugin_Setup_Integration_Manager();
		$manager->register();

		$this->assertSame( array(), $manager->get_booted() );
	}

	/**
	 * The Firebase client reports configuration based on the project ID.
	 */
	public function test_firebase_client_requires_project_id() {
		$this->assertFalse( ( new ITG_Plugin_Setup_Firebase_Client( '' ) )->is_configured() );
		$this->assertTrue( ( new ITG_Plugin_Setup_Firebase_Client( 'demo-project' ) )->is_configured() );
	}

	/**
	 * The Firebase client returns an error when no token can be resolved.
	 */
	public function test_firebase_client_returns_error_without_token() {
		$client = new ITG_Plugin_Setup_Firebase_Client( 'demo-project' );

		$this->assertTrue( is_wp_error( $client->get_access_token() ) );
	}
}
