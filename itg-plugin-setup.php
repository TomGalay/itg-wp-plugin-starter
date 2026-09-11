<?php
/**
 * Plugin Name:       ITG Plugin Setup
 * Plugin URI:        https://example.com/itg-plugin-setup
 * Description:       A WordPress plugin foundation with a pluggable integrations layer (Firebase, Email) and WPCS tooling.
 * Version:           0.1.0
 * Requires at least: 6.6
 * Requires PHP:      8.0
 * Author:            ITG
 * Author URI:        https://example.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       itg-plugin-setup
 * Domain Path:       /languages
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ITG_PLUGIN_SETUP_VERSION', '0.1.0' );
define( 'ITG_PLUGIN_SETUP_FILE', __FILE__ );
define( 'ITG_PLUGIN_SETUP_DIR', plugin_dir_path( __FILE__ ) );
define( 'ITG_PLUGIN_SETUP_URL', plugin_dir_url( __FILE__ ) );
define( 'ITG_PLUGIN_SETUP_BASENAME', plugin_basename( __FILE__ ) );

require_once ITG_PLUGIN_SETUP_DIR . 'includes/itg-plugin-setup-functions.php';
require_once ITG_PLUGIN_SETUP_DIR . 'includes/class-itg-plugin-setup-activator.php';
require_once ITG_PLUGIN_SETUP_DIR . 'includes/class-itg-plugin-setup-deactivator.php';
require_once ITG_PLUGIN_SETUP_DIR . 'includes/class-itg-plugin-setup-i18n.php';
require_once ITG_PLUGIN_SETUP_DIR . 'includes/class-itg-plugin-setup-cron.php';
require_once ITG_PLUGIN_SETUP_DIR . 'includes/class-itg-plugin-setup.php';

register_activation_hook( __FILE__, array( 'ITG_Plugin_Setup_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ITG_Plugin_Setup_Deactivator', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'ITG_Plugin_Setup', 'init' ) );
