<?php
/**
 * PHPUnit bootstrap.
 *
 * Provides a minimal WordPress function environment so the plugin classes can
 * be unit tested without a full WordPress install. Swap this for the
 * wp-phpunit bootstrap to run integration tests against a real WordPress
 * test suite.
 *
 * @package ITG_Plugin_Setup
 */

require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

$itg_plugin_dir = dirname( __DIR__, 2 );

if ( ! defined( 'ITG_PLUGIN_SETUP_VERSION' ) ) {
	define( 'ITG_PLUGIN_SETUP_VERSION', '0.1.0' );
}
if ( ! defined( 'ITG_PLUGIN_SETUP_DIR' ) ) {
	define( 'ITG_PLUGIN_SETUP_DIR', $itg_plugin_dir . '/' );
}
if ( ! defined( 'ITG_PLUGIN_SETUP_FILE' ) ) {
	define( 'ITG_PLUGIN_SETUP_FILE', $itg_plugin_dir . '/itg-plugin-setup.php' );
}
if ( ! defined( 'ITG_PLUGIN_SETUP_URL' ) ) {
	define( 'ITG_PLUGIN_SETUP_URL', 'https://example.com/wp-content/plugins/itg-plugin-setup/' );
}
if ( ! defined( 'ITG_PLUGIN_SETUP_BASENAME' ) ) {
	define( 'ITG_PLUGIN_SETUP_BASENAME', 'itg-plugin-setup/itg-plugin-setup.php' );
}
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', $itg_plugin_dir . '/' );
}

if ( ! class_exists( 'WP_Error' ) ) {
	/**
	 * Minimal WP_Error stand-in for unit tests.
	 */
	class WP_Error {

		/**
		 * Error code.
		 *
		 * @var string
		 */
		private $code;

		/**
		 * Error message.
		 *
		 * @var string
		 */
		private $message;

		/**
		 * Error data.
		 *
		 * @var mixed
		 */
		private $data;

		/**
		 * Constructor.
		 *
		 * @param string $code    Error code.
		 * @param string $message Error message.
		 * @param mixed  $data    Error data.
		 */
		public function __construct( $code = '', $message = '', $data = '' ) {
			$this->code    = $code;
			$this->message = $message;
			$this->data    = $data;
		}

		/**
		 * Retrieve the error code.
		 *
		 * @return string
		 */
		public function get_error_code() {
			return $this->code;
		}

		/**
		 * Retrieve the error message.
		 *
		 * @return string
		 */
		public function get_error_message() {
			return $this->message;
		}

		/**
		 * Retrieve the error data.
		 *
		 * @return mixed
		 */
		public function get_error_data() {
			return $this->data;
		}
	}
}

if ( ! function_exists( '__' ) ) {
	/**
	 * Translation stub.
	 *
	 * @param string $text   Text.
	 * @param string $domain Text domain.
	 * @return string
	 */
	function __( $text, $domain = 'default' ) { // phpcs:ignore
		return $text;
	}
}

if ( ! function_exists( 'add_action' ) ) {
	/**
	 * Action stub.
	 *
	 * @return bool
	 */
	function add_action() { // phpcs:ignore
		return true;
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	/**
	 * Filter stub.
	 *
	 * @return bool
	 */
	function add_filter() { // phpcs:ignore
		return true;
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	/**
	 * Apply filters stub.
	 *
	 * @param string $hook  Hook name.
	 * @param mixed  $value Value.
	 * @return mixed
	 */
	function apply_filters( $hook, $value = null ) { // phpcs:ignore
		return $value;
	}
}

if ( ! function_exists( 'get_option' ) ) {
	/**
	 * Option stub.
	 *
	 * @param string $option  Option name.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	function get_option( $option, $default = false ) { // phpcs:ignore
		return $default;
	}
}

if ( ! function_exists( 'is_wp_error' ) ) {
	/**
	 * Error check stub.
	 *
	 * @param mixed $thing Value to check.
	 * @return bool
	 */
	function is_wp_error( $thing ) { // phpcs:ignore
		return $thing instanceof WP_Error;
	}
}

if ( ! function_exists( 'wp_json_encode' ) ) {
	/**
	 * JSON encode stub.
	 *
	 * @param mixed $data    Data.
	 * @param int   $options Options.
	 * @param int   $depth   Depth.
	 * @return string|false
	 */
	function wp_json_encode( $data, $options = 0, $depth = 512 ) { // phpcs:ignore
		return json_encode( $data, $options, $depth ); // phpcs:ignore
	}
}

require_once ITG_PLUGIN_SETUP_DIR . 'includes/itg-plugin-setup-functions.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/interface-itg-plugin-setup-integration.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/class-itg-plugin-setup-integration-abstract.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/firebase/class-itg-plugin-setup-firebase-client.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/firebase/class-itg-plugin-setup-firebase-auth.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/firebase/class-itg-plugin-setup-firebase-storage.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/firebase/class-itg-plugin-setup-firebase-messaging.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/firebase/class-itg-plugin-setup-firebase-functions.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/firebase/class-itg-plugin-setup-firebase-remote-config.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/firebase/class-itg-plugin-setup-integration-firebase.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/email/class-itg-plugin-setup-email-provider.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/email/providers/class-itg-plugin-setup-email-sendgrid.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/email/providers/class-itg-plugin-setup-email-mailgun.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/email/class-itg-plugin-setup-integration-email.php';
require_once ITG_PLUGIN_SETUP_DIR . 'integrations/class-itg-plugin-setup-integration-manager.php';
require_once ITG_PLUGIN_SETUP_DIR . 'rest/class-itg-plugin-setup-rest-controller.php';
require_once ITG_PLUGIN_SETUP_DIR . 'rest/class-itg-plugin-setup-rest-integrations-controller.php';
