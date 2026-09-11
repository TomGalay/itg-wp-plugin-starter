<?php
/**
 * Main plugin coordinator.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Boots the plugin and wires the sub-controllers together.
 *
 * @since 0.1.0
 */
final class ITG_Plugin_Setup {

	/**
	 * Singleton instance.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup|null
	 */
	private static $instance = null;

	/**
	 * Integration manager.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Integration_Manager|null
	 */
	private $integrations = null;

	/**
	 * Retrieve the singleton instance.
	 *
	 * @since 0.1.0
	 *
	 * @return ITG_Plugin_Setup
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register hooks and load dependencies.
	 *
	 * @since 0.1.0
	 */
	private function __construct() {
		$this->load_dependencies();
		$this->register_hooks();
	}

	/**
	 * Load the plugin class files.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function load_dependencies() {
		$files = array(
			'integrations/interface-itg-plugin-setup-integration.php',
			'integrations/class-itg-plugin-setup-integration-abstract.php',
			'integrations/class-itg-plugin-setup-integration-manager.php',
			'integrations/firebase/class-itg-plugin-setup-firebase-client.php',
			'integrations/firebase/class-itg-plugin-setup-firebase-auth.php',
			'integrations/firebase/class-itg-plugin-setup-firebase-storage.php',
			'integrations/firebase/class-itg-plugin-setup-firebase-messaging.php',
			'integrations/firebase/class-itg-plugin-setup-firebase-functions.php',
			'integrations/firebase/class-itg-plugin-setup-firebase-remote-config.php',
			'integrations/firebase/class-itg-plugin-setup-integration-firebase.php',
			'integrations/email/class-itg-plugin-setup-email-provider.php',
			'integrations/email/providers/class-itg-plugin-setup-email-sendgrid.php',
			'integrations/email/providers/class-itg-plugin-setup-email-mailgun.php',
			'integrations/email/class-itg-plugin-setup-integration-email.php',
			'admin/class-itg-plugin-setup-admin.php',
			'public/class-itg-plugin-setup-public.php',
			'rest/class-itg-plugin-setup-rest-controller.php',
			'rest/class-itg-plugin-setup-rest-integrations-controller.php',
		);

		foreach ( $files as $file ) {
			require_once ITG_PLUGIN_SETUP_DIR . $file;
		}
	}

	/**
	 * Instantiate the sub-controllers and register core hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function register_hooks() {
		$this->integrations = new ITG_Plugin_Setup_Integration_Manager();

		$i18n = new ITG_Plugin_Setup_I18n();
		$i18n->register();

		$public = new ITG_Plugin_Setup_Public();
		$public->register();

		if ( is_admin() ) {
			$admin = new ITG_Plugin_Setup_Admin();
			$admin->register();
		}

		$cron = new ITG_Plugin_Setup_Cron();
		$cron->register();

		add_action( 'init', array( $this, 'boot_integrations' ), 5 );
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once ITG_PLUGIN_SETUP_DIR . 'cli/class-itg-plugin-setup-cli.php';
			new ITG_Plugin_Setup_CLI();
		}
	}

	/**
	 * Register and boot the configured integrations.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function boot_integrations() {
		$this->integrations->register();
	}

	/**
	 * Register the REST API routes.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		$controller = new ITG_Plugin_Setup_REST_Integrations_Controller( $this->integrations );
		$controller->register_routes();
	}

	/**
	 * Retrieve the integration manager.
	 *
	 * @since 0.1.0
	 *
	 * @return ITG_Plugin_Setup_Integration_Manager|null
	 */
	public function get_integrations() {
		return $this->integrations;
	}
}
