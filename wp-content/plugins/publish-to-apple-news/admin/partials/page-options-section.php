<?php
/**
 * Publish to Apple News partials: Options Section page template
 *
 * phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
 *
 * @global Admin_Apple_Settings_Section $apple_section
 *
 * @package Apple_News
 */

?>
<h3><?php echo esc_html( $apple_section->name() ); ?></h3>
<?php echo wp_kses_post( $apple_section->get_section_info() ); ?>
<table class="form-table apple-news">
	<?php foreach ( $apple_section->groups() as $apple_group ) : ?>
		<?php do_action( 'apple_news_before_setting_group', $apple_group, false ); ?>
	<tr>
		<th scope="row"><?php echo esc_html( $apple_group['label'] ); ?></th>
		<td>
			<fieldset>
				<?php foreach ( $apple_group['settings'] as $apple_setting_name => $apple_setting_meta ) : ?>
					<?php do_action( 'apple_news_before_setting', $apple_setting_name, $apple_setting_meta ); ?>
				<label class="setting-container">
					<?php if ( ! empty( $apple_setting_meta['label'] ) ) : ?>
						<span class="label-name"><?php echo esc_html( $apple_setting_meta['label'] ); ?></span>
					<?php endif; ?>
					<?php
						echo wp_kses(
							$apple_section->render_field(
								array(
									$apple_setting_name,
									$apple_setting_meta['default'],
									$apple_setting_meta['callback'],
								)
							),
							Admin_Apple_Settings_Section::$allowed_html
						);
					?>
				</label>
					<?php do_action( 'apple_news_after_setting', $apple_setting_name, $apple_setting_meta ); ?>
				<br />
				<?php endforeach; ?>

				<?php if ( $apple_group['description'] ) : ?>
					<p class="description"><?php echo '(' . wp_kses_post( $apple_group['description'] ) . ')'; ?></p>
				<?php endif; ?>
			</fieldset>
		</td>
	</tr>
		<?php do_action( 'apple_news_after_setting_group', $apple_group, false ); ?>
	<?php endforeach; ?>
</table>
