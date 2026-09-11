<?php
/**
 * Firebase Authentication helper.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wraps the Firebase Authentication REST endpoints.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Firebase_Auth {

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
	 * Verify a Firebase ID token.
	 *
	 * TODO: Validate the JWT signature against Google's public keys, then check
	 * the audience, issuer and expiry. See README for the recommended flow.
	 *
	 * @since 0.1.0
	 *
	 * @param string $id_token Firebase ID token.
	 * @return array<string, mixed>|WP_Error Decoded claims or an error.
	 */
	public function verify_id_token( $id_token ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		return new WP_Error(
			'itg_firebase_not_implemented',
			__( 'Firebase ID token verification is not implemented yet.', 'itg-plugin-setup' )
		);
	}

	/**
	 * Look up a user by UID.
	 *
	 * @since 0.1.0
	 *
	 * @param string $uid Firebase user ID.
	 * @return array<string, mixed>|WP_Error User record or an error.
	 */
	public function get_user( $uid ) {
		$url = sprintf(
			'https://identitytoolkit.googleapis.com/v1/projects/%s/accounts:lookup',
			rawurlencode( $this->client->get_project_id() )
		);

		return $this->client->request( 'POST', $url, array( 'localId' => array( $uid ) ) );
	}

	/**
	 * Set custom claims for a user.
	 *
	 * @since 0.1.0
	 *
	 * @param string               $uid    Firebase user ID.
	 * @param array<string, mixed> $claims Custom claims.
	 * @return array<string, mixed>|WP_Error Response or an error.
	 */
	public function set_custom_claims( $uid, $claims ) {
		$url = sprintf(
			'https://identitytoolkit.googleapis.com/v1/projects/%s/accounts:update',
			rawurlencode( $this->client->get_project_id() )
		);

		return $this->client->request(
			'POST',
			$url,
			array(
				'localId'          => $uid,
				'customAttributes' => wp_json_encode( $claims ),
			)
		);
	}
}
