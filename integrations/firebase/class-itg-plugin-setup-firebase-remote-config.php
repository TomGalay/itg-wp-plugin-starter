<?php
/**
 * Firebase Remote Config helper.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wraps the Firebase Remote Config REST API.
 *
 * Analytics itself is client-side; server-side management is handled here.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Firebase_Remote_Config {

	/**
	 * Firebase client.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Firebase_Client
	 */
	private $client;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param ITG_Plugin_Setup_Firebase_Client $client Firebase client.
	 */
	public function __construct( ITG_Plugin_Setup_Firebase_Client $client ) {
		$this->client = $client;
	}

	/**
	 * Retrieve the current Remote Config template.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, mixed>|WP_Error Template or an error.
	 */
	public function get_template() {
		return $this->client->request( 'GET', $this->template_url() );
	}

	/**
	 * Publish a Remote Config template.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string, mixed> $template Template payload.
	 * @return array<string, mixed>|WP_Error Response or an error.
	 */
	public function publish( $template ) {
		$url = add_query_arg( 'validate_only', 'false', $this->template_url() );

		return $this->client->request( 'PUT', $url, $template );
	}

	/**
	 * Build the Remote Config template endpoint URL.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	private function template_url() {
		return sprintf(
			'https://firebaseremoteconfig.googleapis.com/v1/projects/%s/remoteConfig',
			rawurlencode( $this->client->get_project_id() )
		);
	}
}
