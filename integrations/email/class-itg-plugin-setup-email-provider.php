<?php
/**
 * Shared email provider helpers.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utility helpers shared by email providers.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Email_Provider {

	/**
	 * Validate an HTTP response from a provider.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string, mixed>|WP_Error $response Response from wp_remote_post().
	 * @return true|WP_Error True on success, WP_Error otherwise.
	 */
	public static function check_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$status = (int) wp_remote_retrieve_response_code( $response );

		if ( $status < 200 || $status >= 300 ) {
			return new WP_Error(
				'itg_email_send_failed',
				sprintf(
					/* translators: %d: HTTP status code. */
					__( 'The email provider returned status %d.', 'itg-plugin-setup' ),
					$status
				),
				array(
					'status'   => $status,
					'response' => json_decode( wp_remote_retrieve_body( $response ), true ),
				)
			);
		}

		return true;
	}
}
