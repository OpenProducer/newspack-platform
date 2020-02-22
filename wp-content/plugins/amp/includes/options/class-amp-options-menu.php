<?php
/**
 * AMP Options.
 *
 * @package AMP
 */

/**
 * AMP_Options_Menu class.
 */
class AMP_Options_Menu {

	/**
	 * The AMP svg menu icon.
	 *
	 * @var string
	 */
	const ICON_BASE64_SVG = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjIiIGhlaWdodD0iNjIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTQxLjYyODg2NjcgMjguMTYxNDMzM2wtMTMuMDA0NSAyMS42NDIxMzM0aC0yLjM1NmwyLjMyOTEzMzMtMTQuMTAxOS03LjIxMzcuMDA5M3MtLjA2ODIuMDAyMDY2Ni0uMTAwMjMzMy4wMDIwNjY2Yy0uNjQ5OTY2NyAwLTEuMTc1OTMzNC0uNTI1OTY2Ni0xLjE3NTkzMzQtMS4xNzU5MzMzIDAtLjI3OS4yNTkzNjY3LS43NTEyMzMzLjI1OTM2NjctLjc1MTIzMzNsMTIuOTYyMTMzMy0yMS42MTYzTDM1LjcyNDQgMTIuMTc5OWwtMi4zODgwMzMzIDE0LjEyMzYgNy4yNTA5LS4wMDkzcy4wNzc1LS4wMDEwMzMzLjExNDctLjAwMTAzMzNjLjY0OTk2NjYgMCAxLjE3NTkzMzMuNTI1OTY2NiAxLjE3NTkzMzMgMS4xNzU5MzMzIDAgLjI2MzUtLjEwMzMzMzMuNDk0OTY2Ny0uMjUwMDY2Ny42OTEzbC4wMDEwMzM0LjAwMTAzMzN6TTMxIDBDMTMuODc4NyAwIDAgMTMuODc5NzMzMyAwIDMxYzAgMTcuMTIxMyAxMy44Nzg3IDMxIDMxIDMxIDE3LjEyMDI2NjcgMCAzMS0xMy44Nzg3IDMxLTMxQzYyIDEzLjg3OTczMzMgNDguMTIwMjY2NyAwIDMxIDB6IiBmaWxsPSIjYTBhNWFhIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiLz48L3N2Zz4=';

	/**
	 * Initialize.
	 */
	public function init() {
		add_action( 'admin_post_amp_analytics_options', 'AMP_Options_Manager::handle_analytics_submit' );
		add_action( 'admin_menu', [ $this, 'add_menu_items' ], 9 );

		$plugin_file = preg_replace( '#.+/(?=.+?/.+?)#', '', AMP__FILE__ );
		add_filter( "plugin_action_links_{$plugin_file}", [ $this, 'add_plugin_action_links' ] );
	}

	/**
	 * Add plugin action links.
	 *
	 * @param array $links Links.
	 * @return array Modified links.
	 */
	public function add_plugin_action_links( $links ) {
		return array_merge(
			[
				'settings' => sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( add_query_arg( 'page', AMP_Options_Manager::OPTION_NAME, admin_url( 'admin.php' ) ) ),
					__( 'Settings', 'amp' )
				),
			],
			$links
		);
	}

	/**
	 * Add menu.
	 */
	public function add_menu_items() {
		/*
		 * Note that the admin items for Validated URLs and Validation Errors will also be placed under this admin menu
		 * page when the current user can manage_options.
		 */
		add_menu_page(
			__( 'AMP Options', 'amp' ),
			__( 'AMP', 'amp' ),
			'manage_options',
			AMP_Options_Manager::OPTION_NAME,
			[ $this, 'render_screen' ],
			self::ICON_BASE64_SVG
		);

		add_submenu_page(
			AMP_Options_Manager::OPTION_NAME,
			__( 'AMP Settings', 'amp' ),
			__( 'General', 'amp' ),
			'manage_options',
			AMP_Options_Manager::OPTION_NAME
		);

		add_settings_section(
			'general',
			false,
			'__return_false',
			AMP_Options_Manager::OPTION_NAME
		);

		if ( AMP_Story_Post_Type::has_posts() ) {
			add_settings_field(
				'experiences',
				__( 'Experiences', 'amp' ),
				[ $this, 'render_experiences' ],
				AMP_Options_Manager::OPTION_NAME,
				'general',
				[
					'class' => 'experiences',
				]
			);
		}

		add_settings_field(
			'theme_support',
			__( 'Template Mode', 'amp' ),
			[ $this, 'render_theme_support' ],
			AMP_Options_Manager::OPTION_NAME,
			'general',
			[
				'class' => 'amp-website-mode',
			]
		);

		add_settings_field(
			'supported_templates',
			__( 'Supported Templates', 'amp' ),
			[ $this, 'render_supported_templates' ],
			AMP_Options_Manager::OPTION_NAME,
			'general',
			[
				'class' => 'amp-template-support-field',
			]
		);

		if ( AMP_Story_Post_Type::has_posts() ) {
			add_settings_field(
				'stories_export',
				__( 'Stories Export', 'amp' ),
				[ $this, 'render_stories_export' ],
				AMP_Options_Manager::OPTION_NAME,
				'general',
				[
					'class' => 'amp-stories-export-field',
				]
			);

			add_settings_field(
				'stories_settings',
				__( 'Stories Settings', 'amp' ),
				[ $this, 'render_stories_settings' ],
				AMP_Options_Manager::OPTION_NAME,
				'general',
				[
					'class' => 'amp-stories-settings-field',
				]
			);
		}

		add_action(
			'admin_print_styles',
			static function() {
				if ( ! AMP_Story_Post_Type::has_posts() ) {
					return;
				}
				?>
				<style>
					body:not(.amp-experience-website) .amp-website-mode,
					body:not(.amp-experience-website) .amp-template-support-field {
						display: none;
					}
					body:not(.amp-experience-stories) .amp-stories-export-field,
					body:not(.amp-experience-stories) .amp-stories-settings-field {
						display: none;
					}
				</style>
				<?php
			}
		);

		if ( wp_using_ext_object_cache() ) {
			add_settings_field(
				'caching',
				__( 'Caching', 'amp' ),
				[ $this, 'render_caching' ],
				AMP_Options_Manager::OPTION_NAME,
				'general',
				[
					'class' => 'amp-caching-field',
				]
			);
		}

		$submenus = [
			new AMP_Analytics_Options_Submenu( AMP_Options_Manager::OPTION_NAME ),
		];

		// Create submenu items and calls on the Submenu Page object to render the actual contents of the page.
		foreach ( $submenus as $submenu ) {
			$submenu->init();
		}
	}

	/**
	 * Render experiences.
	 *
	 * @since 1.2
	 */
	public function render_experiences() {
		$experiences = AMP_Options_Manager::get_option( 'experiences' );

		$has_required_block_capabilities = AMP_Story_Post_Type::has_required_block_capabilities();
		?>
		<style>
			label[for="stories_experience"] span {
				text-transform: uppercase;
				font-size: 0.7em;
				border: 1px solid;
				border-radius: 2px;
				padding: 2px;
				margin: -15px 0  0 3px;
				position: relative;
				top: -2px;
				font-weight: 400;
				line-height: 1;
			}
		</style>
		<fieldset>
			<dl>
				<dt>
					<input type="checkbox" name="<?php echo esc_attr( AMP_Options_Manager::OPTION_NAME . '[experiences][]' ); ?>" id="website_experience" value="<?php echo esc_attr( AMP_Options_Manager::WEBSITE_EXPERIENCE ); ?>" <?php checked( in_array( AMP_Options_Manager::WEBSITE_EXPERIENCE, $experiences, true ) ); ?>>
					<label for="website_experience">
						<strong><?php esc_html_e( 'Website', 'amp' ); ?></strong>
					</label>
				</dt>
				<dd>
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: %s: Stories documentation URL. */
							__( 'AMP is a powerful web components framework that helps you build fast, user-first websites that monetize well. AMP puts tons of advanced capabilities at your fingertips, effectively reducing the operating and development costs of your sites. Read more about <a href="%s" target="_blank">AMP Websites</a>.', 'amp' ),
							esc_url( 'https://amp.dev/about/websites' )
						)
					);
					?>
				</dd>
				<?php if ( AMP_Story_Post_Type::has_posts() ) : ?>
					<dt>
						<input type="checkbox" name="<?php echo esc_attr( AMP_Options_Manager::OPTION_NAME . '[experiences][]' ); ?>" id="stories_experience" value="<?php echo esc_attr( AMP_Options_Manager::STORIES_EXPERIENCE ); ?>" <?php disabled( ! $has_required_block_capabilities ); ?> <?php checked( in_array( AMP_Options_Manager::STORIES_EXPERIENCE, $experiences, true ) ); ?>>
						<label for="stories_experience">
							<strong><?php echo wp_kses_post( __( 'Stories <span>Beta</span>', 'amp' ) ); ?></strong>
						</label>
					</dt>
					<dd>
						<?php if ( ! $has_required_block_capabilities ) : ?>
							<div class="notice notice-info notice-alt inline">
								<p>
									<?php
									$gutenberg = 'Gutenberg';
									// Link to Gutenberg plugin installation if eligible.
									if ( current_user_can( 'install_plugins' ) ) {
										$gutenberg = '<a href="' . esc_url( add_query_arg( 'tab', 'featured', admin_url( 'plugin-install.php' ) ) ) . '">' . $gutenberg . '</a>';
									}
									printf(
										/* translators: 1: WordPress version number. 2: Gutenberg plugin name,  */
										esc_html__( 'To use Stories, you must be running WordPress %1$s, or have a version between %2$s and %3$s of the %4$s plugin activated.', 'amp' ),
										'5.3',
										'6.6.0',
										'7.1.0',
										$gutenberg // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									);
									?>
								</p>
							</div>
						<?php endif; ?>
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: %s: Stories documentation URL. */
								__( 'Stories is a visual storytelling format for the open web which immerses your readers in fast-loading, full-screen, and visually rich experiences. Stories can be a great addition to your overall content strategy. Read more about <a href="%s" target="_blank">AMP Stories</a>.', 'amp' ),
								esc_url( 'https://amp.dev/about/stories' )
							)
						);
						?>
					</dd>
				<?php endif; ?>
			</dl>
			<script>
				/*
				 * Toggle visibility of setting sections based on whether or not their respective experiences are enabled.
				 * Ensure that at least one experience is selected, either Website, Stories, or both.
				 */
				( function( $, optionInputName, mustSelectMessage ) {
					const websiteExperienceInput = $( '#website_experience' )[0];
					const checkboxInputs = $( 'input[name="' + optionInputName + '"]' );

					const handleExperiencesUpdate = () => {
						const checkedCount = checkboxInputs.filter( ':checked' ).length;
						if ( 0 === checkedCount ) {
							websiteExperienceInput.setCustomValidity( mustSelectMessage );
						} else {
							websiteExperienceInput.setCustomValidity( '' );
						}

						checkboxInputs.each( function() {
							document.body.classList.toggle( 'amp-experience-' + this.value, this.checked );
						} );
					};

					checkboxInputs.on( 'change', handleExperiencesUpdate );
					handleExperiencesUpdate();
				})(
					jQuery,
					<?php echo wp_json_encode( AMP_Options_Manager::OPTION_NAME . '[experiences][]' ); ?>,
					<?php echo wp_json_encode( __( 'You must select at least one experience.', 'amp' ) ); ?>
				);
			</script>
		</fieldset>
		<?php
	}

	/**
	 * Render theme support.
	 *
	 * @since 1.0
	 */
	public function render_theme_support() {
		$theme_support = AMP_Theme_Support::get_support_mode();

		/* translators: %s: URL to the documentation. */
		$standard_description = sprintf( __( 'The active theme integrates AMP as the framework for your site by using its templates and styles to render webpages. This means your site is <b>AMP-first</b> and your canonical URLs are AMP! Depending on your theme/plugins, a varying level of <a href="%s">development work</a> may be required.', 'amp' ), esc_url( 'https://amp-wp.org/documentation/developing-wordpress-amp-sites/' ) );
		/* translators: %s: URL to the documentation. */
		$transitional_description = sprintf( __( 'The active theme’s templates are used to generate non-AMP and AMP versions of your content, allowing for each canonical URL to have a corresponding (paired) AMP URL. This mode is useful to progressively transition towards a fully AMP-first site. Depending on your theme/plugins, a varying level of <a href="%s">development work</a> may be required.', 'amp' ), esc_url( 'https://amp-wp.org/documentation/developing-wordpress-amp-sites/' ) );
		$reader_description       = __( 'Formerly called the <b>classic mode</b>, this mode generates paired AMP content using simplified templates which may not match the look-and-feel of your site. Only posts/pages can be served as AMP in Reader mode. No redirection is performed for mobile visitors; AMP pages are served by AMP consumption platforms.', 'amp' );
		/* translators: %s: URL to the ecosystem page. */
		$ecosystem_description = sprintf( __( 'For a list of themes and plugins that are known to be AMP compatible, please see the <a href="%s">ecosystem page</a>.', 'amp' ), esc_url( 'https://amp-wp.org/ecosystem/' ) );

		$builtin_support     = in_array( get_template(), AMP_Core_Theme_Sanitizer::get_supported_themes(), true );
		$reader_mode_support = __( 'Your theme indicates it works best in <strong>Reader mode.</strong>', 'amp' );
		?>

		<fieldset>
			<?php if ( AMP_Theme_Support::READER_MODE_SLUG === AMP_Theme_Support::get_support_mode() ) : ?>
				<?php if ( AMP_Theme_Support::STANDARD_MODE_SLUG === AMP_Theme_Support::get_support_mode_added_via_theme() ) : ?>
					<div class="notice notice-success notice-alt inline">
						<p><?php esc_html_e( 'Your active theme is known to work well in standard mode.', 'amp' ); ?></p>
					</div>
				<?php elseif ( $builtin_support || AMP_Theme_Support::TRANSITIONAL_MODE_SLUG === AMP_Theme_Support::get_support_mode_added_via_theme() ) : ?>
					<div class="notice notice-success notice-alt inline">
						<p><?php esc_html_e( 'Your active theme is known to work well in standard or transitional mode.', 'amp' ); ?></p>
					</div>
				<?php endif; ?>
			<?php elseif ( AMP_Theme_Support::supports_reader_mode() ) : ?>
				<div class="notice notice-success notice-alt inline">
					<p><?php echo wp_kses( $reader_mode_support, [ 'strong' => [] ] ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( ! AMP_Theme_Support::get_support_mode_added_via_theme() && ! AMP_Theme_Support::supports_reader_mode() && ! $builtin_support ) : ?>
				<p>
					<?php echo wp_kses_post( $ecosystem_description ); ?>
				</p>
			<?php endif; ?>

			<dl>
				<dt>
					<input type="radio" id="theme_support_standard" name="<?php echo esc_attr( AMP_Options_Manager::OPTION_NAME . '[theme_support]' ); ?>" value="<?php echo esc_attr( AMP_Theme_Support::STANDARD_MODE_SLUG ); ?>" <?php checked( $theme_support, AMP_Theme_Support::STANDARD_MODE_SLUG ); ?>>
					<label for="theme_support_standard">
						<strong><?php esc_html_e( 'Standard', 'amp' ); ?></strong>
					</label>
				</dt>
				<dd>
					<?php echo wp_kses_post( $standard_description ); ?>
				</dd>
				<dt>
					<input type="radio" id="theme_support_transitional" name="<?php echo esc_attr( AMP_Options_Manager::OPTION_NAME . '[theme_support]' ); ?>" value="<?php echo esc_attr( AMP_Theme_Support::TRANSITIONAL_MODE_SLUG ); ?>" <?php checked( $theme_support, AMP_Theme_Support::TRANSITIONAL_MODE_SLUG ); ?>>
					<label for="theme_support_transitional">
						<strong><?php esc_html_e( 'Transitional', 'amp' ); ?></strong>
					</label>
				</dt>
				<dd>
					<?php echo wp_kses_post( $transitional_description ); ?>
				</dd>
				<dt>
					<input type="radio" id="theme_support_disabled" name="<?php echo esc_attr( AMP_Options_Manager::OPTION_NAME . '[theme_support]' ); ?>" value="<?php echo esc_attr( AMP_Theme_Support::READER_MODE_SLUG ); ?>" <?php checked( $theme_support, AMP_Theme_Support::READER_MODE_SLUG ); ?>>
					<label for="theme_support_disabled">
						<strong><?php esc_html_e( 'Reader', 'amp' ); ?></strong>
					</label>
				</dt>
				<dd>
					<?php echo wp_kses_post( $reader_description ); ?>

					<?php if ( ! current_theme_supports( AMP_Theme_Support::SLUG ) && wp_count_posts( AMP_Validated_URL_Post_Type::POST_TYPE_SLUG )->publish > 0 ) : ?>
						<div class="notice notice-info inline notice-alt">
							<p>
								<?php
								echo wp_kses_post(
									sprintf(
										/* translators: %1: link to invalid URLs. 2: link to validation errors. */
										__( 'View current site compatibility results for standard and transitional modes: %1$s and %2$s.', 'amp' ),
										sprintf(
											'<a href="%s">%s</a>',
											esc_url( add_query_arg( 'post_type', AMP_Validated_URL_Post_Type::POST_TYPE_SLUG, admin_url( 'edit.php' ) ) ),
											esc_html( get_post_type_object( AMP_Validated_URL_Post_Type::POST_TYPE_SLUG )->labels->name )
										),
										sprintf(
											'<a href="%s">%s</a>',
											esc_url(
												add_query_arg(
													[
														'taxonomy' => AMP_Validation_Error_Taxonomy::TAXONOMY_SLUG,
														'post_type' => AMP_Validated_URL_Post_Type::POST_TYPE_SLUG,
													],
													admin_url( 'edit-tags.php' )
												)
											),
											esc_html( get_taxonomy( AMP_Validation_Error_Taxonomy::TAXONOMY_SLUG )->labels->name )
										)
									)
								);
								?>
							</p>
						</div>
					<?php endif; ?>
				</dd>
			</dl>

			<?php if ( AMP_Theme_Support::get_support_mode_added_via_theme() ) : ?>
				<p>
					<?php echo wp_kses_post( $ecosystem_description ); ?>
				</p>
			<?php endif; ?>
		</fieldset>
		<?php
	}

	/**
	 * Supported templates section renderer.
	 *
	 * @since 1.0
	 */
	public function render_supported_templates() {
		$theme_support_args = AMP_Theme_Support::get_theme_support_args();
		?>

		<?php if ( ! isset( $theme_support_args['available_callback'] ) ) : ?>
			<fieldset id="all_templates_supported_fieldset">
				<?php if ( isset( $theme_support_args['templates_supported'] ) && 'all' === $theme_support_args['templates_supported'] ) : ?>
					<div class="notice notice-info notice-alt inline">
						<p>
							<?php esc_html_e( 'The current theme requires all templates to support AMP.', 'amp' ); ?>
						</p>
					</div>
				<?php else : ?>
					<p>
						<label for="all_templates_supported">
							<input id="all_templates_supported" type="checkbox" name="<?php echo esc_attr( AMP_Options_Manager::OPTION_NAME . '[all_templates_supported]' ); ?>" <?php checked( AMP_Options_Manager::get_option( 'all_templates_supported' ) ); ?>>
							<?php esc_html_e( 'Serve all templates as AMP regardless of what is being queried.', 'amp' ); ?>
						</label>
					</p>
					<p class="description">
						<?php esc_html_e( 'This will allow all of the URLs on your site to be served as AMP by default.', 'amp' ); ?>
					</p>
				<?php endif; ?>
			</fieldset>
		<?php else : ?>
			<div class="notice notice-warning notice-alt inline">
				<p>
					<?php
					printf(
						/* translators: %s: available_callback */
						esc_html__( 'Your theme is using the deprecated %s argument for AMP theme support.', 'amp' ),
						'available_callback'
					);
					?>
				</p>
			</div>
		<?php endif; ?>

		<fieldset id="supported_post_types_fieldset">
			<?php
			$element_name         = AMP_Options_Manager::OPTION_NAME . '[supported_post_types][]';
			$supported_post_types = AMP_Options_Manager::get_option( 'supported_post_types' );
			?>
			<h4 class="title"><?php esc_html_e( 'Content Types', 'amp' ); ?></h4>
			<p>
				<?php esc_html_e( 'The following content types will be available as AMP:', 'amp' ); ?>
			</p>
			<ul>
			<?php foreach ( array_map( 'get_post_type_object', AMP_Post_Type_Support::get_eligible_post_types() ) as $post_type ) : ?>
				<?php
				$checked = (
					post_type_supports( $post_type->name, AMP_Post_Type_Support::SLUG )
					||
					( ! AMP_Options_Manager::is_website_experience_enabled() && in_array( $post_type->name, $supported_post_types, true ) )
				);
				?>
				<li>
					<?php $element_id = AMP_Options_Manager::OPTION_NAME . "-supported_post_types-{$post_type->name}"; ?>
					<input
						type="checkbox"
						id="<?php echo esc_attr( $element_id ); ?>"
						name="<?php echo esc_attr( $element_name ); ?>"
						value="<?php echo esc_attr( $post_type->name ); ?>"
						<?php checked( $checked ); ?>
						>
					<label for="<?php echo esc_attr( $element_id ); ?>">
						<?php echo esc_html( $post_type->label ); ?>
					</label>
				</li>
			<?php endforeach; ?>
			</ul>
		</fieldset>

		<?php if ( ! isset( $theme_support_args['available_callback'] ) ) : ?>
			<fieldset id="supported_templates_fieldset">
				<style>
					#supported_templates_fieldset ul ul {
						margin-left: 40px;
					}
				</style>
				<h4 class="title"><?php esc_html_e( 'Templates', 'amp' ); ?></h4>
				<?php
				$this->list_template_conditional_options( AMP_Theme_Support::get_supportable_templates() );
				?>
				<script>
					// Let clicks on parent items automatically cause the children checkboxes to have same checked state applied.
					(function ( $ ) {
						$( '#supported_templates_fieldset input[type=checkbox]' ).on( 'click', function() {
							$( this ).siblings( 'ul' ).find( 'input[type=checkbox]' ).prop( 'checked', this.checked );
						} );
					})( jQuery );
				</script>
			</fieldset>

			<script>
				// Update the visibility of the fieldsets based on the selected template mode and then whether all templates are indicated to be supported.
				(function ( $ ) {
					const templateModeInputs = $( 'input[type=radio][name="amp-options[theme_support]"]' );
					const themeSupportDisabledInput = $( '#theme_support_disabled' );
					const allTemplatesSupportedInput = $( '#all_templates_supported' );

					function isThemeSupportDisabled() {
						return Boolean( themeSupportDisabledInput.length && themeSupportDisabledInput.prop( 'checked' ) );
					}

					function updateFieldsetVisibility() {
						const allTemplatesSupported = 0 === allTemplatesSupportedInput.length || allTemplatesSupportedInput.prop( 'checked' );
						$( '#all_templates_supported_fieldset, #supported_post_types_fieldset > .title' ).toggleClass(
							'hidden',
							isThemeSupportDisabled()
						);
						$( '#supported_post_types_fieldset' ).toggleClass(
							'hidden',
							allTemplatesSupported && ! isThemeSupportDisabled()
						);
						$( '#supported_templates_fieldset' ).toggleClass(
							'hidden',
							allTemplatesSupported || isThemeSupportDisabled()
						);
					}

					templateModeInputs.on( 'change', updateFieldsetVisibility );
					allTemplatesSupportedInput.on( 'click', updateFieldsetVisibility );
					updateFieldsetVisibility();
				})( jQuery );
			</script>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render the caching settings section.
	 *
	 * @since 1.0
	 *
	 * @todo Change the messaging and description to be user-friendly and helpful.
	 */
	public function render_caching() {
		?>
		<fieldset>
			<?php if ( AMP_Options_Manager::show_response_cache_disabled_notice() ) : ?>
				<div class="notice notice-info notice-alt inline">
					<p><?php esc_html_e( 'The post-processor cache was disabled due to detecting randomly generated content found on', 'amp' ); ?> <a href="<?php echo esc_url( get_option( AMP_Theme_Support::CACHE_MISS_URL_OPTION, '' ) ); ?>"><?php esc_html_e( 'on this web page.', 'amp' ); ?></a></p>
					<p><?php esc_html_e( 'Randomly generated content was detected on this web page.  To avoid filling up the cache with unusable content, the AMP plugin\'s post-processor cache was automatically disabled.', 'amp' ); ?>
						<a href="<?php echo esc_url( 'https://github.com/ampproject/amp-wp/wiki/Post-Processor-Cache' ); ?>"><?php esc_html_e( 'Read more', 'amp' ); ?></a>.</p>
				</div>
			<?php endif; ?>
			<p>
				<label for="enable_response_caching">
					<input id="enable_response_caching" type="checkbox" name="<?php echo esc_attr( AMP_Options_Manager::OPTION_NAME . '[enable_response_caching]' ); ?>" <?php checked( AMP_Options_Manager::get_option( 'enable_response_caching' ) ); ?>>
					<?php esc_html_e( 'Enable post-processor caching.', 'amp' ); ?>
				</label>
			</p>
			<p class="description"><?php esc_html_e( 'This will enable post-processor caching to speed up processing an AMP response after WordPress renders a template.', 'amp' ); ?></p>
		</fieldset>
		<?php
	}

	/**
	 * Render the stories export settings section.
	 *
	 * @since 1.2
	 */
	public function render_stories_export() {
		?>
		<fieldset <?php disabled( ! current_user_can( 'publish_posts' ) ); ?>>
			<p>
				<label for="story_export_base_url">
					<strong><?php echo esc_html__( 'Base URL for exported stories.', 'amp' ); ?></strong>
				</label>
				<br />
				<input id="story_export_base_url" type="text" placeholder="https://" class="regular-text code" name="<?php echo esc_attr( AMP_Options_Manager::OPTION_NAME . '[story_export_base_url]' ); ?>" value="<?php echo esc_url( AMP_Options_Manager::get_option( 'story_export_base_url' ) ); ?>" />
			</p>
			<p class="description"><?php esc_html_e( 'AMP requires most asset URLs to be absolute as opposed to relative. In order to export stories with the desired absolute URLs, you can provide the required URL base here. This base URL will be used for the uploaded files, as well as, links to other stories. If left empty, the default URLs will be used. Meaning, uploaded images and videos will be referenced from the WordPress install. Remember that the provided URL should be HTTPS.', 'amp' ); ?></p>
		</fieldset>
		<?php
	}

	/**
	 * Render the stories settings section.
	 *
	 * @since 1.3
	 */
	public function render_stories_settings() {
		?>
		<p><?php esc_html_e( 'These settings are applied to new stories only.', 'amp' ); ?></p>
		<?php
		$this->render_stories_settings_page_advance();
	}

	/**
	 * Render the page advancement settings section.
	 *
	 * @since 1.3
	 */
	private function render_stories_settings_page_advance() {
		$definitions                = AMP_Story_Post_Type::get_stories_settings_definitions();
		$auto_advance_after_options = $definitions['auto_advance_after']['data']['options'];

		$story_settings            = AMP_Options_Manager::get_option( 'story_settings' );
		$story_settings_field_name = sprintf( '%s[%s]', AMP_Options_Manager::OPTION_NAME, AMP_Story_Post_Type::STORY_SETTINGS_OPTION );
		?>
		<fieldset>
			<p>
				<label for="stories_settings_auto_advance_after">
					<strong><?php echo esc_html__( 'Advance to next page', 'amp' ); ?></strong>
				</label>
				<br />
				<select id="stories_settings_auto_advance_after" name="<?php echo esc_attr( $story_settings_field_name . '[auto_advance_after]' ); ?>">
				<?php foreach ( $auto_advance_after_options as $option ) : ?>
					<option
						value="<?php echo esc_attr( $option['value'] ); ?>"
						data-description="<?php echo esc_attr( $option['description'] ); ?>"
						<?php selected( $story_settings['auto_advance_after'], $option['value'] ); ?>
					>
						<?php echo esc_html( $option['label'] ); ?>
					</option>
				<?php endforeach; ?>
				</select>
			</p>
			<p class="hidden">
				<label for="stories_settings_auto_advance_after_duration">
					<strong><?php echo esc_html__( 'Time in seconds', 'amp' ); ?></strong>
				</label>
				<br />
				<input
					type="number"
					class="small-text"
					id="stories_settings_auto_advance_after_duration"
					min="0"
					max="100"
					name="<?php echo esc_attr( $story_settings_field_name . '[auto_advance_after_duration]' ); ?>"
					value="<?php echo (int) $story_settings['auto_advance_after_duration']; ?>"
				>
			</p>
			<p class="description"></p>
		</fieldset>
		<script>
			(function( $ ) {
				const $autoAdvanceAfter = $('#stories_settings_auto_advance_after');
				const $description = $autoAdvanceAfter.closest('fieldset').find('.description');
				const $durationSection = $('#stories_settings_auto_advance_after_duration').closest('p');

				$autoAdvanceAfter
					.on('change', function() {
						const $selectedOption = $('option:selected', this);

						$description.text( $selectedOption.data('description') );
						$durationSection.toggleClass( 'hidden', 'time' !== $selectedOption.prop('value') );
					})
					.trigger('change');
			})( jQuery );
		</script>
		<?php
	}

	/**
	 * List template conditional options.
	 *
	 * @param array       $options Options.
	 * @param string|null $parent  ID of the parent option.
	 */
	private function list_template_conditional_options( $options, $parent = null ) {
		$element_name = AMP_Options_Manager::OPTION_NAME . '[supported_templates][]';
		?>
		<ul>
			<?php foreach ( $options as $id => $option ) : ?>
				<?php
				$element_id = AMP_Options_Manager::OPTION_NAME . '-supported-templates-' . $id;
				if ( $parent ? empty( $option['parent'] ) || $parent !== $option['parent'] : ! empty( $option['parent'] ) ) {
					continue;
				}

				// Skip showing an option if it doesn't have a label.
				if ( empty( $option['label'] ) ) {
					continue;
				}

				?>
				<li>
					<?php if ( empty( $option['immutable'] ) ) : ?>
						<input
							type="checkbox"
							id="<?php echo esc_attr( $element_id ); ?>"
							name="<?php echo esc_attr( $element_name ); ?>"
							value="<?php echo esc_attr( $id ); ?>"
							<?php checked( ! empty( $option['user_supported'] ) ); ?>
						>
					<?php else : // Persist user selection even when checkbox disabled, when selection forced by theme/filter. ?>
						<input
							type="checkbox"
							id="<?php echo esc_attr( $element_id ); ?>"
							<?php checked( ! empty( $option['supported'] ) ); ?>
							<?php disabled( true ); ?>
						>
						<?php if ( ! empty( $option['user_supported'] ) ) : ?>
							<input type="hidden" name="<?php echo esc_attr( $element_name ); ?>" value="<?php echo esc_attr( $id ); ?>">
						<?php endif; ?>
					<?php endif; ?>
					<label for="<?php echo esc_attr( $element_id ); ?>">
						<?php echo esc_html( $option['label'] ); ?>
					</label>

					<?php if ( ! empty( $option['description'] ) ) : ?>
						<span class="description">
							&mdash; <?php echo wp_kses_post( $option['description'] ); ?>
						</span>
					<?php endif; ?>

					<?php self::list_template_conditional_options( $options, $id ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Display Settings.
	 *
	 * @since 0.6
	 */
	public function render_screen() {
		if ( ! empty( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			AMP_Options_Manager::check_supported_post_type_update_errors();
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php settings_errors(); ?>
			<form id="amp-settings" action="options.php" method="post">
				<?php
				settings_fields( AMP_Options_Manager::OPTION_NAME );
				do_settings_sections( AMP_Options_Manager::OPTION_NAME );
				if ( current_user_can( 'manage_options' ) ) {
					submit_button();
				}
				?>
			</form>
		</div>
		<?php
	}
}
