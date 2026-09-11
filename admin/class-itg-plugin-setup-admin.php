<?php
/**
 * Admin controller.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the admin settings screen.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Admin {

	/**
	 * Admin page slug.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	const MENU_SLUG = 'itg-plugin-setup';

	/**
	 * Option name.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	const OPTION = 'itg_plugin_setup_integrations';

	/**
	 * Register hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register the settings page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_menu() {
		add_options_page(
			__( 'ITG Plugin Setup', 'itg-plugin-setup' ),
			__( 'ITG Plugin Setup', 'itg-plugin-setup' ),
			'manage_options',
			self::MENU_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register the settings option.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'itg_plugin_setup',
			self::OPTION,
			array(
				'type'              => 'array',
				'default'           => array(),
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);
	}

	/**
	 * Sanitize the submitted settings.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $input Raw option value.
	 * @return array<string, array<string, mixed>> Sanitized settings.
	 */
	public function sanitize_settings( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$manager = ITG_Plugin_Setup::init()->get_integrations();
		$clean   = array();

		if ( ! $manager instanceof ITG_Plugin_Setup_Integration_Manager ) {
			return $clean;
		}

		foreach ( $manager->get_integrations() as $id => $integration ) {
			if ( ! isset( $input[ $id ] ) || ! is_array( $input[ $id ] ) ) {
				continue;
			}

			foreach ( $integration->get_settings_fields() as $key => $field ) {
				if ( ! isset( $input[ $id ][ $key ] ) ) {
					continue;
				}

				$value = $input[ $id ][ $key ];
				$type  = isset( $field['type'] ) ? $field['type'] : 'text';

				if ( 'select' === $type ) {
					$options = isset( $field['options'] ) ? array_keys( (array) $field['options'] ) : array();

					$clean[ $id ][ $key ] = in_array( $value, $options, true ) ? $value : '';
				} elseif ( 'email' === $type ) {
					$clean[ $id ][ $key ] = sanitize_email( $value );
				} else {
					$clean[ $id ][ $key ] = sanitize_text_field( $value );
				}
			}
		}

		return $clean;
	}

	/**
	 * Render the settings page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$manager                       = ITG_Plugin_Setup::init()->get_integrations();
		$itg_plugin_setup_integrations = $manager instanceof ITG_Plugin_Setup_Integration_Manager ? $manager->get_integrations() : array();

		require ITG_PLUGIN_SETUP_DIR . 'admin/views/settings-page.php';
	}

	/**
	 * Enqueue the admin assets on the settings screen only.
	 *
	 * @since 0.1.0
	 *
	 * @param string $hook_suffix Current admin page hook.
	 * @return void
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( 'settings_page_' . self::MENU_SLUG !== $hook_suffix ) {
			return;
		}

		$asset_file = ITG_PLUGIN_SETUP_DIR . 'build/admin.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = require $asset_file;

		wp_enqueue_script(
			'itg-plugin-setup-admin',
			ITG_PLUGIN_SETUP_URL . 'build/admin.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		$style_file = ITG_PLUGIN_SETUP_DIR . 'build/admin.css';

		if ( file_exists( $style_file ) ) {
			wp_enqueue_style(
				'itg-plugin-setup-admin',
				ITG_PLUGIN_SETUP_URL . 'build/admin.css',
				array(),
				$asset['version']
			);
		}

		wp_localize_script(
			'itg-plugin-setup-admin',
			'itgPluginSetup',
			array(
				'restUrl' => esc_url_raw( rest_url() ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
			)
		);
	}
}
