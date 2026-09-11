<?php
/**
 * Sample configuration for the WordPress integration test suite.
 *
 * Copy this file to `tests/wp-tests-config.php` and adjust the paths and
 * database credentials for your machine. The real file is git-ignored.
 *
 * The suite also runs in CI against a test library installed with
 * `bin/install-wp-tests.sh`; this sample is only needed for local runs.
 *
 * @package ITG_Plugin_Setup
 */

// Absolute path to a WordPress core installation (with a trailing slash).
define( 'ABSPATH', 'C:/path/to/wordpress/' );

define( 'WP_DEFAULT_THEME', 'default' );
define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );
define( 'WP_PHP_BINARY', 'php' );

// A dedicated test database; its contents are erased on every run.
define( 'DB_NAME', 'wordpress_test' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

$table_prefix = 'wptests_';

define( 'AUTH_KEY', 'put your unique phrase here' );
define( 'SECURE_AUTH_KEY', 'put your unique phrase here' );
define( 'LOGGED_IN_KEY', 'put your unique phrase here' );
define( 'NONCE_KEY', 'put your unique phrase here' );
define( 'AUTH_SALT', 'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT', 'put your unique phrase here' );
define( 'NONCE_SALT', 'put your unique phrase here' );
