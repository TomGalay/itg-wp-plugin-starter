<?php
/**
 * Internationalization.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads the plugin text domain.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_I18n {

	/**
	 * Register hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load the plugin translations.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'itg-plugin-setup',
			false,
			dirname( ITG_PLUGIN_SETUP_BASENAME ) . '/languages'
		);
	}
}
