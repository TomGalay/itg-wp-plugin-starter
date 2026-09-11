<?php
/**
 * Firebase integration.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Example integration exposing the Firebase services.
 *
 * No SDK dependency is required. See ITG_Plugin_Setup_Firebase_Client for how to inject an
 * OAuth2 access token from kreait/firebase-php or google/auth.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Integration_Firebase extends ITG_Plugin_Setup_Integration_Abstract {

	/**
	 * Integration identifier.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	const ID = 'firebase';

	/**
	 * Firebase REST client.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Firebase_Client|null
	 */
	private $client = null;

	/**
	 * Authentication helper.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Firebase_Auth|null
	 */
	private $auth = null;

	/**
	 * Cloud Storage helper.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Firebase_Storage|null
	 */
	private $storage = null;

	/**
	 * Cloud Messaging helper.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Firebase_Messaging|null
	 */
	private $messaging = null;

	/**
	 * Cloud Functions helper.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Firebase_Functions|null
	 */
	private $functions = null;

	/**
	 * Remote Config helper.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Firebase_Remote_Config|null
	 */
	private $remote_config = null;

	/**
	 * Integration identifier.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_id() {
		return self::ID;
	}

	/**
	 * Integration name.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Firebase', 'itg-plugin-setup' );
	}

	/**
	 * Whether the integration is configured.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_configured() {
		return '' !== (string) $this->get_setting( 'project_id', '' );
	}

	/**
	 * Instantiate the service helpers.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		$this->client = new ITG_Plugin_Setup_Firebase_Client(
			$this->get_setting( 'project_id', '' ),
			$this->get_settings()
		);

		$this->auth          = new ITG_Plugin_Setup_Firebase_Auth( $this->client );
		$this->storage       = new ITG_Plugin_Setup_Firebase_Storage( $this->client, $this->get_setting( 'storage_bucket', '' ) );
		$this->messaging     = new ITG_Plugin_Setup_Firebase_Messaging( $this->client );
		$this->functions     = new ITG_Plugin_Setup_Firebase_Functions( $this->client );
		$this->remote_config = new ITG_Plugin_Setup_Firebase_Remote_Config( $this->client );
	}

	/**
	 * Settings fields.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public function get_settings_fields() {
		return array(
			'project_id'     => array(
				'label' => __( 'Project ID', 'itg-plugin-setup' ),
				'type'  => 'text',
			),
			'storage_bucket' => array(
				'label' => __( 'Storage bucket', 'itg-plugin-setup' ),
				'type'  => 'text',
			),
			'credentials'    => array(
				'label'       => __( 'Service account JSON', 'itg-plugin-setup' ),
				'type'        => 'password',
				'description' => __( 'Paste the service account JSON, or store it outside the web root and reference it with a constant.', 'itg-plugin-setup' ),
			),
		);
	}

	/**
	 * Retrieve the REST client.
	 *
	 * @since 0.1.0
	 *
	 * @return ITG_Plugin_Setup_Firebase_Client|null
	 */
	public function get_client() {
		return $this->client;
	}

	/**
	 * Retrieve the authentication helper.
	 *
	 * @since 0.1.0
	 *
	 * @return ITG_Plugin_Setup_Firebase_Auth|null
	 */
	public function get_auth() {
		return $this->auth;
	}

	/**
	 * Retrieve the storage helper.
	 *
	 * @since 0.1.0
	 *
	 * @return ITG_Plugin_Setup_Firebase_Storage|null
	 */
	public function get_storage() {
		return $this->storage;
	}

	/**
	 * Retrieve the messaging helper.
	 *
	 * @since 0.1.0
	 *
	 * @return ITG_Plugin_Setup_Firebase_Messaging|null
	 */
	public function get_messaging() {
		return $this->messaging;
	}

	/**
	 * Retrieve the functions helper.
	 *
	 * @since 0.1.0
	 *
	 * @return ITG_Plugin_Setup_Firebase_Functions|null
	 */
	public function get_functions() {
		return $this->functions;
	}

	/**
	 * Retrieve the Remote Config helper.
	 *
	 * @since 0.1.0
	 *
	 * @return ITG_Plugin_Setup_Firebase_Remote_Config|null
	 */
	public function get_remote_config() {
		return $this->remote_config;
	}
}
