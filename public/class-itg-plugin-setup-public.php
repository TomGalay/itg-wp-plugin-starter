<?php
/**
 * Public controller.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the front-end facing behaviour.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_Public {

	/**
	 * Register hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue the front-end assets.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$asset_file = ITG_PLUGIN_SETUP_DIR . 'build/public.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = require $asset_file;

		wp_enqueue_script(
			'itg-plugin-setup-public',
			ITG_PLUGIN_SETUP_URL . 'build/public.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		$style_file = ITG_PLUGIN_SETUP_DIR . 'build/public.css';

		if ( file_exists( $style_file ) ) {
			wp_enqueue_style(
				'itg-plugin-setup-public',
				ITG_PLUGIN_SETUP_URL . 'build/public.css',
				array(),
				$asset['version']
			);
		}
	}
}
