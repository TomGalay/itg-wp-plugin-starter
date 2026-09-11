<?php
/**
 * Email integration.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Example integration sending email through an external provider.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Integration_Email extends ITG_Plugin_Setup_Integration_Abstract {

	/**
	 * Integration identifier.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	const ID = 'email';

	/**
	 * Active provider instance.
	 *
	 * @since 0.1.0
	 * @var ITG_Plugin_Setup_Email_SendGrid|ITG_Plugin_Setup_Email_Mailgun|null
	 */
	private $provider = null;

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
		return __( 'Email', 'itg-plugin-setup' );
	}

	/**
	 * Whether the integration is configured.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function is_configured() {
		return '' !== (string) $this->get_setting( 'provider', '' )
			&& '' !== (string) $this->get_setting( 'api_key', '' );
	}

	/**
	 * Instantiate the selected provider.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		$provider = (string) $this->get_setting( 'provider', '' );
		$api_key  = (string) $this->get_setting( 'api_key', '' );

		if ( 'sendgrid' === $provider ) {
			$this->provider = new ITG_Plugin_Setup_Email_SendGrid( $api_key );
		} elseif ( 'mailgun' === $provider ) {
			$this->provider = new ITG_Plugin_Setup_Email_Mailgun( $api_key, (string) $this->get_setting( 'domain', '' ) );
		}
	}

	/**
	 * Send an email through the configured provider.
	 *
	 * @since 0.1.0
	 *
	 * @param string $to      Recipient address.
	 * @param string $subject Subject line.
	 * @param string $message HTML message body.
	 * @return true|WP_Error True on success, WP_Error otherwise.
	 */
	public function send( $to, $subject, $message ) {
		if ( null === $this->provider ) {
			return new WP_Error(
				'itg_email_not_configured',
				__( 'The email integration is not configured.', 'itg-plugin-setup' )
			);
		}

		$from = array(
			'email' => (string) $this->get_setting( 'from_email', get_option( 'admin_email' ) ),
			'name'  => (string) $this->get_setting( 'from_name', get_bloginfo( 'name' ) ),
		);

		return $this->provider->send( $to, $subject, $message, $from );
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
			'provider'   => array(
				'label'   => __( 'Provider', 'itg-plugin-setup' ),
				'type'    => 'select',
				'options' => array(
					'sendgrid' => 'SendGrid',
					'mailgun'  => 'Mailgun',
				),
			),
			'api_key'    => array(
				'label' => __( 'API key', 'itg-plugin-setup' ),
				'type'  => 'password',
			),
			'domain'     => array(
				'label'       => __( 'Sending domain', 'itg-plugin-setup' ),
				'type'        => 'text',
				'description' => __( 'Required for Mailgun.', 'itg-plugin-setup' ),
			),
			'from_email' => array(
				'label' => __( 'From email', 'itg-plugin-setup' ),
				'type'  => 'email',
			),
			'from_name'  => array(
				'label' => __( 'From name', 'itg-plugin-setup' ),
				'type'  => 'text',
			),
		);
	}
}
