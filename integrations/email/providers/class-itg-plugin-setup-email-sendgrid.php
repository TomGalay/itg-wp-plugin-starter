<?php
/**
 * SendGrid email provider.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sends email through the SendGrid v3 API.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Email_SendGrid {

	/**
	 * SendGrid API key.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $api_key;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param string $api_key SendGrid API key.
	 */
	public function __construct( $api_key ) {
		$this->api_key = (string) $api_key;
	}

	/**
	 * Send an email.
	 *
	 * @since 0.1.0
	 *
	 * @param string               $to      Recipient address.
	 * @param string               $subject Subject line.
	 * @param string               $message HTML message body.
	 * @param array<string, mixed> $from    From name and address.
	 * @return true|WP_Error True on success, WP_Error otherwise.
	 */
	public function send( $to, $subject, $message, $from ) {
		$body = array(
			'personalizations' => array(
				array(
					'to' => array(
						array( 'email' => $to ),
					),
				),
			),
			'from'             => array(
				'email' => isset( $from['email'] ) ? $from['email'] : '',
				'name'  => isset( $from['name'] ) ? $from['name'] : '',
			),
			'subject'          => $subject,
			'content'          => array(
				array(
					'type'  => 'text/html',
					'value' => $message,
				),
			),
		);

		$response = wp_remote_post(
			'https://api.sendgrid.com/v3/mail/send',
			array(
				'timeout' => 15,
				'headers' => array(
					'Authorization' => 'Bearer ' . $this->api_key,
					'Content-Type'  => 'application/json',
				),
				'body'    => wp_json_encode( $body ),
			)
		);

		return ITG_Plugin_Setup_Email_Provider::check_response( $response );
	}
}
