<?php
/**
 * Firebase Cloud Functions helper.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calls HTTPS Cloud Functions.
 *
 * Callable functions are usually invoked from the browser with the Firebase
 * JS SDK. For server-to-server calls, pass the deployed function URL.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Firebase_Functions {

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
	 * Invoke an HTTPS function by URL.
	 *
	 * @since 0.1.0
	 *
	 * @param string               $url  Deployed function URL.
	 * @param array<string, mixed> $data Request payload.
	 * @return array<string, mixed>|WP_Error Response or an error.
	 */
	public function call( $url, $data = array() ) {
		if ( ! wp_http_validate_url( $url ) ) {
			return new WP_Error(
				'itg_firebase_invalid_url',
				__( 'The Cloud Function URL is not valid.', 'itg-plugin-setup' )
			);
		}

		return $this->client->request( 'POST', $url, $data );
	}
}
