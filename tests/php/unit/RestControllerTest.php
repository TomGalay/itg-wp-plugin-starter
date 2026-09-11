<?php
/**
 * REST controller tests.
 *
 * @package ITG_Plugin_Setup
 */

use PHPUnit\Framework\TestCase;

/**
 * Guards the REST permission callback contract.
 */
class RestControllerTest extends TestCase {

	/**
	 * The permission callback must be invokable from outside class scope.
	 *
	 * WordPress dispatches a registered permission_callback through
	 * call_user_func() from outside the controller, so a protected method
	 * raises a TypeError on every request to the route.
	 */
	public function test_permissions_check_is_publicly_callable() {
		$controller = new ITG_Plugin_Setup_REST_Integrations_Controller();

		$this->assertTrue(
			is_callable( array( $controller, 'permissions_check' ) ),
			'permissions_check() must be public; WordPress calls it via call_user_func() outside class scope.'
		);
	}
}
