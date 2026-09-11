<?php
/**
 * Firebase Cloud Messaging helper.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wraps the Firebase Cloud Messaging HTTP v1 API.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Firebase_Messaging {

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
	 * Send a message to a device registration token.
	 *
	 * @since 0.1.0
	 *
	 * @param string               $token        Device registration token.
	 * @param array<string, mixed> $notification Notification payload.
	 * @param array<string, mixed> $data         Data payload.
	 * @return array<string, mixed>|WP_Error Response or an error.
	 */
	public function send( $token, $notification = array(), $data = array() ) {
		$url = sprintf(
			'https://fcm.googleapis.com/v1/projects/%s/messages:send',
			rawurlencode( $this->client->get_project_id() )
		);

		$message = array( 'token' => $token );

		if ( ! empty( $notification ) ) {
			$message['notification'] = $notification;
		}

		if ( ! empty( $data ) ) {
			$message['data'] = $data;
		}

		return $this->client->request( 'POST', $url, array( 'message' => $message ) );
	}
}
