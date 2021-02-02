<?php
/**
 * Publish to Apple News partials: Sections page template
 *
 * phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
 *
 * @global array       $priority_mappings
 * @global array       $sections
 * @global WP_Taxonomy $taxonomy
 * @global array       $taxonomy_mappings
 * @global string      $theme_admin_url
 * @global array       $theme_mappings
 * @global array       $themes
 *
 * @package Apple_News
 */

?>
<div class="wrap apple-news-sections">
	<h1 id="apple_news_sections_title"><?php esc_html_e( 'Manage Sections', 'apple-news' ); ?></h1>
	<h2><?php esc_html_e( 'Section Mappings', 'apple-news' ); ?></h2>
	<p>
	<?php
	echo esc_html(
		sprintf(
			// translators: placeholder is a taxonomy label, plural form, e.g., "categories".
			__( 'To enable automatic section assignment, choose the %s that you would like to be associated with each section.', 'apple-news' ),
			strtolower( $taxonomy->label )
		)
	);
	?>
	</p>
	<p>
	<?php
	echo wp_kses_post(
		sprintf(
			// translators: first argument is an opening <a> tag, second argument is </a>.
			__( 'You can also map a theme to automatically be used for posts with a specific Apple News section, if you want to use something other than the %1$sactive theme%2$s.', 'apple-news' ),
			'<a href="' . esc_url( $theme_admin_url ) . '">',
			'</a>'
		)
	);
	?>
	</p>
	<p>
		<?php
			esc_html_e(
				'Additionally, you can assign a priority to each section. If a post will be published to more than one section, the priority determines which theme is used for the post. The section with the higher priority number will take precedence. The theme assigned to the section with the highest priority that is assigned to the post will be used as the selected theme for the post.',
				'apple-news'
			);
			?>
	</p>
	<form method="post" action="" id="apple-news-section-form" enctype="multipart/form-data">
		<?php wp_nonce_field( 'apple_news_sections' ); ?>
		<input name="action" type="hidden" value="apple_news_set_section_mappings" />
		<div id="apple-news-section-taxonomy-mapping-template">
			<label class="screen-reader-text"><?php echo esc_html( $taxonomy->labels->singular_name ); ?></label>
			<input type="text" class="apple-news-section-taxonomy-autocomplete" />
			<button type="button" class="apple-news-section-taxonomy-remove"><span class="apple-news-section-taxonomy-remove-icon" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Remove mapping', 'apple-news' ); ?></span></button>
		</div>
		<table class="wp-list-table widefat fixed striped">
			<thead>
			<tr>
				<th scope="col" id="apple_news_section_name" class="manage-column column-apple-news-section-name column-primary"><?php esc_html_e( 'Section', 'apple-news' ); ?></th>
				<th scope="col" id="apple_news_section_priority" class="manage-column column-apple-news-section-priority"><?php esc_html_e( 'Priority', 'apple-news' ); ?></th>
				<th scope="col" id="apple_news_section_taxonomy_mapping" class="manage-column column-apple-news-section-taxonomy-mapping"><?php echo esc_html( $taxonomy->label ); ?></th>
				<th scope="col" id="apple_news_section_theme_mapping" class="manage-column column-apple-news-section-theme-mapping column-primary"><?php esc_html_e( 'Theme', 'apple-news' ); ?></th>
			</tr>
			</thead>
			<tbody id="apple-news-sections-list">
			<?php $apple_count = 0; ?>
			<?php foreach ( $sections as $apple_section_id => $apple_section_name ) : ?>
				<tr id="apple-news-section-<?php echo esc_attr( $apple_section_id ); ?>">
					<td><?php echo esc_html( $apple_section_name ); ?></td>
					<td>
						<input
							aria-labelledby="apple_news_section_priority"
							name="priority-mapping-<?php echo esc_attr( $apple_section_id ); ?>"
							type="number"
							step="1"
							value="<?php echo esc_attr( isset( $priority_mappings[ $apple_section_id ] ) ? (int) $priority_mappings[ $apple_section_id ] : 1 ); ?>"
						/>
					</td>
					<td>
						<ul class="apple-news-section-taxonomy-mapping-list">
						<?php if ( ! empty( $taxonomy_mappings[ $apple_section_id ] ) ) : ?>
							<?php foreach ( $taxonomy_mappings[ $apple_section_id ] as $apple_taxonomy_term ) : ?>
								<?php $apple_taxonomy_id = 'apple-news-section-mapping-' . ( ++ $apple_count ); ?>
								<li>
									<label for="<?php echo esc_attr( $apple_taxonomy_id ); ?>" class="screen-reader-text"><?php echo esc_html( $taxonomy->labels->singular_name ); ?></label>
									<input name="taxonomy-mapping-<?php echo esc_attr( $apple_section_id ); ?>[]" id="<?php echo esc_attr( $apple_taxonomy_id ); ?>" type="text" class="apple-news-section-taxonomy-autocomplete" value="<?php echo esc_attr( $apple_taxonomy_term ); ?>" />
									<button type="button" class="apple-news-section-taxonomy-remove"><span class="apple-news-section-taxonomy-remove-icon" aria-hidden="true"></span><span class="screen-reader-text"><?php esc_html_e( 'Remove mapping', 'apple-news' ); ?></span></button>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
						</ul>
						<button type="button" class="apple-news-add-section-taxonomy-mapping" data-section-id="<?php echo esc_attr( $apple_section_id ); ?>"><?php esc_html_e( 'Add', 'apple-news' ); ?> <?php echo esc_html( $taxonomy->labels->singular_name ); ?></button>
					</td>
					<td>
						<?php
							$apple_theme_id       = 'apple-news-theme-mapping-' . ( ++ $apple_count );
							$apple_selected_theme = ( isset( $theme_mappings[ $apple_section_id ] ) ) ? $theme_mappings[ $apple_section_id ] : '';
						?>
						<select name="theme-mapping-<?php echo esc_attr( $apple_section_id ); ?>" id="<?php echo esc_attr( $apple_theme_id ); ?>">
							<option value=""></option>
							<?php
							foreach ( $themes as $apple_theme ) :
								?>
								<option value="<?php echo esc_attr( $apple_theme ); ?>" <?php selected( $apple_theme, $apple_selected_theme ); ?>><?php echo esc_html( $apple_theme ); ?></option>
									<?php
								endforeach;
							?>
						</select>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p>
			<?php
				submit_button(
					__( 'Save Changes', 'apple-news' ),
					'primary',
					'apple_news_set_section_mappings',
					false
				);
				?>
			<?php
				submit_button(
					__( 'Refresh Section List', 'apple-news' ),
					'secondary',
					'apple_news_refresh_section_list',
					false
				);
				?>
		</p>
	</form>
</div>
