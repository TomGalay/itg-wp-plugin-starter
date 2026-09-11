<?php
/**
 * Firebase REST transport.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Thin REST client for the Firebase / Google APIs.
 *
 * This intentionally avoids a hard dependency on an SDK. Provide an OAuth2
 * access token through the `itg_plugin_setup_firebase_access_token` filter,
 * for example from `google/auth` or `kreait/firebase-php`, and this class
 * takes care of the HTTP transport through the WordPress HTTP API.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Firebase_Client {

	/**
	 * Firebase project ID.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $project_id;

	/**
	 * Service account credentials.
	 *
	 * @since 0.1.0
	 * @var array<string, mixed>
	 */
	private $credentials;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param string               $project_id  Firebase project ID.
	 * @param array<string, mixed> $credentials Service account credentials.
	 */
	public function __construct( $project_id, $credentials = array() ) {
		$this->project_id  = (string) $project_id;
		$this->credentials = is_array( $credentials ) ? $credentials : array();
	}

	/**
	 * Whether the client is configured.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_configured() {
		return '' !== $this->project_id;
	}

	/**
	 * Retrieve the configured project ID.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_project_id() {
		return $this->project_id;
	}

	/**
	 * Retrieve the service account credentials.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string, mixed>
	 */
	public function get_credentials() {
		return $this->credentials;
	}

	/**
	 * Resolve an OAuth2 access token.
	 *
	 * TODO: Mint a token from the service account (google/auth or a signed JWT)
	 * and cache it in a transient until it expires.
	 *
	 * @since 0.1.0
	 *
	 * @return string|WP_Error Access token or an error when unavailable.
	 */
	public function get_access_token() {
		/**
		 * Filter the Firebase OAuth2 access token.
		 *
		 * @since 0.1.0
		 *
		 * @param string|WP_Error     $token  Access token, or a WP_Error.
		 * @param ITG_Plugin_Setup_Firebase_Client $client This client instance.
		 */
		$token = apply_filters( 'itg_plugin_setup_firebase_access_token', '', $this );

		if ( is_string( $token ) && '' !== $token ) {
			return $token;
		}

		return new WP_Error(
			'itg_firebase_no_token',
			__( 'No Firebase access token is available. Supply credentials or use the itg_plugin_setup_firebase_access_token filter.', 'itg-plugin-setup' )
		);
	}

	/**
	 * Perform an authenticated JSON request.
	 *
	 * @since 0.1.0
	 *
	 * @param string                   $method HTTP method.
	 * @param string                   $url    Absolute request URL.
	 * @param array<string, mixed>|null $body  Optional JSON body.
	 * @return array<string, mixed>|WP_Error Decoded response or an error.
	 */
	public function request( $method, $url, $body = null ) {
		$token = $this->get_access_token();

		if ( is_wp_error( $token ) ) {
			return $token;
		}

		$args = array(
			'method'  => strtoupper( $method ),
			'timeout' => 15,
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
				'Content-Type'  => 'application/json',
			),
		);

		if ( null !== $body ) {
			$args['body'] = wp_json_encode( $body );
		}

		$response = wp_remote_request( $url, $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$status = (int) wp_remote_retrieve_response_code( $response );
		$data   = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $status < 200 || $status >= 300 ) {
			return new WP_Error(
				'itg_firebase_request_failed',
				sprintf(
					/* translators: %d: HTTP status code. */
					__( 'Firebase request failed with status %d.', 'itg-plugin-setup' ),
					$status
				),
				array(
					'status'   => $status,
					'response' => $data,
				)
			);
		}

		return $data;
	}
}
