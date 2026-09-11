<?php
/**
 * Integration tests that run against a real WordPress install.
 *
 * @package ITG_Plugin_Setup
 */

/**
 * Smoke tests for plugin loading.
 */
class PluginActivationTest extends WP_UnitTestCase {

	/**
	 * The plugin defines its version constant on load.
	 */
	public function test_plugin_constant_is_defined() {
		$this->assertTrue( defined( 'ITG_PLUGIN_SETUP_VERSION' ) );
	}

	/**
	 * The integration manager is available after init.
	 */
	public function test_integration_manager_is_available() {
		$manager = ITG_Plugin_Setup::init()->get_integrations();

		$this->assertInstanceOf( ITG_Plugin_Setup_Integration_Manager::class, $manager );
		$this->assertArrayHasKey( 'firebase', $manager->get_integrations() );
		$this->assertArrayHasKey( 'email', $manager->get_integrations() );
	}
}
