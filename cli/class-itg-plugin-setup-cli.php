<?php
/**
 * WP-CLI commands.
 *
 * @package ITG_Plugin_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage the plugin from the command line.
 *
 * @since 0.1.0
 */
class ITG_Plugin_Setup_CLI {

	/**
	 * Register the command.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		WP_CLI::add_command( 'itg-plugin-setup', $this );
	}

	/**
	 * List the integrations and their status.
	 *
	 * ## EXAMPLES
	 *
	 *     wp itg-plugin-setup status
	 *
	 * @since 0.1.0
	 *
	 * @param array<int, string>    $args       Positional arguments.
	 * @param array<string, string> $assoc_args Associative arguments.
	 * @return void
	 */
	public function status( $args, $assoc_args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$manager = ITG_Plugin_Setup::init()->get_integrations();
		$rows    = array();

		if ( $manager instanceof ITG_Plugin_Setup_Integration_Manager ) {
			foreach ( $manager->get_integrations() as $id => $integration ) {
				$rows[] = array(
					'id'         => $id,
					'name'       => $integration->get_name(),
					'configured' => $integration->is_configured() ? 'yes' : 'no',
				);
			}
		}

		WP_CLI\Utils\format_items( 'table', $rows, array( 'id', 'name', 'configured' ) );
	}
}
