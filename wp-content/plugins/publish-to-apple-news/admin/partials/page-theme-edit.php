<?php
/**
 * Publish to Apple News partials: Theme Edit page template
 *
 * phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
 *
 * @global Apple_Exporter\Theme $theme
 * @global array                $theme_options
 * @global string               $theme_admin_url
 * @global string               $theme_name
 *
 * @package Apple_News
 */

?>
<div class="wrap apple-news-theme-edit">
	<h1><?php esc_html_e( 'Edit Theme', 'apple-news' ); ?></h1>
	<form method="post" action="<?php echo esc_url( $theme_admin_url ); ?>" id="apple-news-theme-edit-form">
		<?php wp_nonce_field( 'apple_news_save_edit_theme' ); ?>
		<input type="hidden" name="action" value="apple_news_save_edit_theme" />
		<p>
			<label id="apple_news_theme_name_label" for="apple_news_theme_name"><?php esc_html_e( 'Theme Name', 'apple-news' ); ?></label>
			<br />
			<input type="text" id="apple_news_theme_name" name="apple_news_theme_name" value="<?php echo esc_attr( $theme_name ); ?>" />
			<input type="hidden" id="apple_news_theme_name_previous" name="apple_news_theme_name_previous" value="<?php echo esc_attr( $theme_name ); ?>" />
		</p>
		<div id="apple-news-formatting">
			<div class="apple-news-settings-left">
				<h3><?php esc_html_e( 'Theme Settings', 'apple-news' ); ?></h3>
				<p><?php esc_html_e( 'Configuration for the visual appearance of the theme. Updates to these settings will not change the appearance of any articles previously published to your channel in Apple News using this theme unless you republish them.', 'apple-news' ); ?></p>
				<p><?php esc_html_e( 'Apple News supports Dark Mode. Each applicable component is now given the ability to set its own dark mode colors. This will override the intelligent defaults that Apple News will attempt to use.', 'apple-news' ); ?></p>
				<p>
				<?php
					printf(
						/* translators: first token is an opening a tag, second is closing a tag */
						esc_html__( 'You may further customize the theme using the %1$sCustomize JSON%2$s feature. Note that customizations made through Customize JSON will not be reflected in the live preview here.', 'apple-news' ),
						'<a href="' . esc_url( admin_url( 'admin.php?page=apple-news-json&theme=' . $theme_name ) ) . '">',
						'</a>'
					);
					?>
				</p>
				<table class="form-table apple-news">
					<?php foreach ( $theme->get_groups() as $apple_group ) : ?>
						<?php do_action( 'apple_news_before_setting_group', $apple_group, false ); ?>
						<tr>
							<th scope="row"><?php echo esc_html( $apple_group['label'] ); ?></th>
							<td>
								<fieldset>
									<?php foreach ( $apple_group['settings'] as $apple_setting_name ) : ?>
										<?php do_action( 'apple_news_before_setting', $apple_setting_name, $theme_options[ $apple_setting_name ] ); ?>
										<label class="setting-container">
											<?php if ( ! empty( $theme_options[ $apple_setting_name ]['label'] ) ) : ?>
												<span class="label-name">
													<?php if ( 'group_heading' === $theme_options[ $apple_setting_name ]['type'] ) : ?>
														<strong>
													<?php endif; ?>
													<?php echo esc_html( $theme_options[ $apple_setting_name ]['label'] ); ?>
													<?php if ( 'group_heading' === $theme_options[ $apple_setting_name ]['type'] ) : ?>
														</strong>
													<?php endif; ?>
												</span>
											<?php endif; ?>
											<?php
											echo wp_kses(
												Admin_Apple_Themes::render_field( $theme, $apple_setting_name ),
												Admin_Apple_Settings_Section::$allowed_html
											);
											?>
										</label>
										<?php do_action( 'apple_news_after_setting', $apple_setting_name, $theme_options[ $apple_setting_name ] ); ?>
										<br />
									<?php endforeach; ?>

									<?php if ( ! empty( $apple_group['description'] ) ) : ?>
										<p class="description"><?php echo '(' . wp_kses_post( $apple_group['description'] ) . ')'; ?></p>
									<?php endif; ?>
								</fieldset>
							</td>
						</tr>
						<?php do_action( 'apple_news_after_setting_group', $apple_group, false ); ?>
					<?php endforeach; ?>
				</table>
			</div>
			<?php
				$apple_preview = new Admin_Apple_Preview();
				$apple_preview->get_preview_html( $theme->get_name() );
			?>
		</div>
		<p class="apple-news-theme-edit-buttons">
			<?php
				submit_button(
					__( 'Save Theme Settings', 'apple-news' ),
					'primary',
					'submit',
					false
				);
				?>
			<a class="button" href="<?php echo esc_url( $theme_admin_url ); ?>"><?php esc_html_e( 'Cancel', 'apple-news' ); ?></a>
		</p>
	</form>
</div>
