<?php
/**
 * Mailgun email provider.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sends email through the Mailgun API.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Email_Mailgun {

	/**
	 * Mailgun API key.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $api_key;

	/**
	 * Mailgun sending domain.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	private $domain;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param string $api_key Mailgun private API key.
	 * @param string $domain  Sending domain.
	 */
	public function __construct( $api_key, $domain ) {
		$this->api_key = (string) $api_key;
		$this->domain  = (string) $domain;
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
		$endpoint = sprintf( 'https://api.mailgun.net/v3/%s/messages', rawurlencode( $this->domain ) );

		$body = array(
			'from'    => sprintf( '%s <%s>', isset( $from['name'] ) ? $from['name'] : '', isset( $from['email'] ) ? $from['email'] : '' ),
			'to'      => $to,
			'subject' => $subject,
			'html'    => $message,
		);

		$response = wp_remote_post(
			$endpoint,
			array(
				'timeout' => 15,
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( 'api:' . $this->api_key ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
				),
				'body'    => $body,
			)
		);

		return ITG_Plugin_Setup_Email_Provider::check_response( $response );
	}
}
