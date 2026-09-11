<?php
/**
 * PHPUnit bootstrap for the WordPress integration test suite.
 *
 * Requires the WordPress test library, installed with:
 *
 *     bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
 *
 * Set WP_TESTS_DIR if the test library lives outside the default location.
 *
 * @package ITG_Plugin_Setup
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	// Prefer the WordPress test library bundled with wp-phpunit, so the suite
	// runs on a plain `composer install` with no SVN / install-wp-tests.sh.
	$_itg_vendored_tests = dirname( __DIR__, 2 ) . '/vendor/wp-phpunit/wp-phpunit';

	if ( file_exists( $_itg_vendored_tests . '/includes/functions.php' ) ) {
		$_tests_dir = $_itg_vendored_tests;
	} else {
		$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
	}
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo 'Could not find ' . $_tests_dir . '/includes/functions.php, have you run bin/install-wp-tests.sh ?' . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// A local tests/wp-tests-config.php (git-ignored) overrides the config lookup.
if ( ! defined( 'WP_TESTS_CONFIG_FILE_PATH' ) ) {
	$_itg_tests_config = dirname( __DIR__ ) . '/wp-tests-config.php';

	if ( file_exists( $_itg_tests_config ) ) {
		define( 'WP_TESTS_CONFIG_FILE_PATH', $_itg_tests_config );
	}
}

require_once $_tests_dir . '/includes/functions.php';

/**
 * Load the plugin before WordPress finishes bootstrapping.
 */
function itg_plugin_setup_manually_load_plugin() {
	require dirname( __DIR__, 2 ) . '/itg-plugin-setup.php';
}

tests_add_filter( 'muplugins_loaded', 'itg_plugin_setup_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';
