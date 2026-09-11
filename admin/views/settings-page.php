<?php
/**
 * Settings page template.
 *
 * @package ITG_Plugin_Setup
 *
 * @var array<string, ITG_Plugin_Setup_Integration> $itg_plugin_setup_integrations Available integrations.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap itg-plugin-setup">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<p><?php esc_html_e( 'Configure the integrations available to this site.', 'itg-plugin-setup' ); ?></p>

	<div id="itg-plugin-setup-admin-root"></div>

	<form action="options.php" method="post">
		<?php settings_fields( 'itg_plugin_setup' ); ?>

		<?php foreach ( $itg_plugin_setup_integrations as $itg_plugin_setup_integration ) : ?>
			<?php
			$itg_plugin_setup_fields = $itg_plugin_setup_integration->get_settings_fields();

			if ( empty( $itg_plugin_setup_fields ) ) {
				continue;
			}

			$itg_plugin_setup_id = $itg_plugin_setup_integration->get_id();
			?>
			<h2><?php echo esc_html( $itg_plugin_setup_integration->get_name() ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
				<?php foreach ( $itg_plugin_setup_fields as $itg_plugin_setup_key => $itg_plugin_setup_field ) : ?>
					<?php
					$itg_plugin_setup_option_name = sprintf( 'itg_plugin_setup_integrations[%s][%s]', $itg_plugin_setup_id, $itg_plugin_setup_key );
					$itg_plugin_setup_value       = itg_plugin_setup_get_integration_setting( $itg_plugin_setup_id, $itg_plugin_setup_key, '' );
					$itg_plugin_setup_type        = isset( $itg_plugin_setup_field['type'] ) ? $itg_plugin_setup_field['type'] : 'text';
					$itg_plugin_setup_input_type  = 'text';

					if ( 'password' === $itg_plugin_setup_type ) {
						$itg_plugin_setup_input_type = 'password';
					} elseif ( 'email' === $itg_plugin_setup_type ) {
						$itg_plugin_setup_input_type = 'email';
					}
					?>
					<tr>
						<th scope="row">
							<label for="itg-<?php echo esc_attr( $itg_plugin_setup_id . '-' . $itg_plugin_setup_key ); ?>">
								<?php echo esc_html( $itg_plugin_setup_field['label'] ); ?>
							</label>
						</th>
						<td>
							<?php if ( 'select' === $itg_plugin_setup_type ) : ?>
								<select
									id="itg-<?php echo esc_attr( $itg_plugin_setup_id . '-' . $itg_plugin_setup_key ); ?>"
									name="<?php echo esc_attr( $itg_plugin_setup_option_name ); ?>"
								>
									<?php foreach ( (array) $itg_plugin_setup_field['options'] as $itg_plugin_setup_option_value => $itg_plugin_setup_option_label ) : ?>
										<option
											value="<?php echo esc_attr( $itg_plugin_setup_option_value ); ?>"
											<?php selected( $itg_plugin_setup_value, $itg_plugin_setup_option_value ); ?>
										>
											<?php echo esc_html( $itg_plugin_setup_option_label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							<?php else : ?>
								<input
									type="<?php echo esc_attr( $itg_plugin_setup_input_type ); ?>"
									id="itg-<?php echo esc_attr( $itg_plugin_setup_id . '-' . $itg_plugin_setup_key ); ?>"
									name="<?php echo esc_attr( $itg_plugin_setup_option_name ); ?>"
									value="<?php echo esc_attr( (string) $itg_plugin_setup_value ); ?>"
									class="regular-text"
								/>
							<?php endif; ?>

							<?php if ( ! empty( $itg_plugin_setup_field['description'] ) ) : ?>
								<p class="description"><?php echo esc_html( $itg_plugin_setup_field['description'] ); ?></p>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endforeach; ?>

		<?php submit_button(); ?>
	</form>
</div>
