<?php
/**
 * REST controller base.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for REST controllers.
 *
 * @since 0.1.0
 */
abstract class ITG_Plugin_Setup_REST_Controller {

	/**
	 * REST namespace.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $namespace = 'itg-plugin-setup/v1';

	/**
	 * Register the routes for this controller.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	abstract public function register_routes();

	/**
	 * Default permission callback.
	 *
	 * Must be public: WordPress invokes registered permission callbacks via
	 * call_user_func() from outside the class scope, so a protected method
	 * triggers a TypeError on every request.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function permissions_check() {
		return current_user_can( 'manage_options' );
	}
}
