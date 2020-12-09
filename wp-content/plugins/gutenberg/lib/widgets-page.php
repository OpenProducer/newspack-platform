<?php
/**
 * Bootstraping the Gutenberg widgets page.
 *
 * @package gutenberg
 */

/**
 * The main entry point for the Gutenberg widgets page.
 *
 * @since 5.2.0
 */
function the_gutenberg_widgets() {
	?>
	<div
		id="widgets-editor"
		class="blocks-widgets-container"
	>
	</div>
	<?php
}

/**
 * Initialize the Gutenberg widgets page.
 *
 * @since 5.2.0
 *
 * @param string $hook Page.
 */
function gutenberg_widgets_init( $hook ) {
	if ( 'widgets.php' === $hook ) {
		wp_enqueue_style( 'wp-block-library' );
		wp_enqueue_style( 'wp-block-library-theme' );
		wp_add_inline_style(
			'wp-block-library-theme',
			'.block-widget-form .widget-control-save { display: none; }'
		);
		return;
	}
	if ( ! in_array( $hook, array( 'appearance_page_gutenberg-widgets' ), true ) ) {
		return;
	}

	$initializer_name = 'initialize';

	$settings = array_merge(
		gutenberg_get_common_block_editor_settings(),
		gutenberg_get_legacy_widget_settings()
	);

	// This purposefully does not rely on apply_filters( 'block_editor_settings', $settings, null );
	// Applying that filter would bring over multitude of features from the post editor, some of which
	// may be undesirable. Instead of using that filter, we simply pick just the settings that are needed.
	$settings = gutenberg_experimental_global_styles_settings( $settings );
	$settings = gutenberg_extend_block_editor_styles( $settings );

	$preload_paths = array(
		array( '/wp/v2/media', 'OPTIONS' ),
		'/wp/v2/sidebars?context=edit&per_page=-1',
		'/wp/v2/widgets?context=edit&per_page=-1',
	);
	$preload_data  = array_reduce(
		$preload_paths,
		'rest_preload_api_request',
		array()
	);
	wp_add_inline_script(
		'wp-api-fetch',
		sprintf(
			'wp.apiFetch.use( wp.apiFetch.createPreloadingMiddleware( %s ) );',
			wp_json_encode( $preload_data )
		),
		'after'
	);

	wp_add_inline_script(
		'wp-edit-widgets',
		sprintf(
			'wp.domReady( function() {
				wp.editWidgets.%s( "widgets-editor", %s );
			} );',
			$initializer_name,
			wp_json_encode( $settings )
		)
	);

	// Preload server-registered block schemas.
	wp_add_inline_script(
		'wp-blocks',
		'wp.blocks.unstable__bootstrapServerSideBlockDefinitions(' . wp_json_encode( get_block_editor_server_block_settings() ) . ');'
	);

	wp_enqueue_script( 'wp-edit-widgets' );
	wp_enqueue_script( 'admin-widgets' );
	wp_enqueue_script( 'wp-format-library' );
	wp_enqueue_style( 'wp-edit-widgets' );
	wp_enqueue_style( 'wp-format-library' );
}
add_action( 'admin_enqueue_scripts', 'gutenberg_widgets_init' );

/**
 * Tells the script loader to load the scripts and styles of custom block on widgets editor screen.
 *
 * @param bool $is_block_editor_screen Current decision about loading block assets.
 * @return bool Filtered decision about loading block assets.
 */
function gutenberg_widgets_editor_load_block_editor_scripts_and_styles( $is_block_editor_screen ) {
	if ( is_callable( 'get_current_screen' ) && 'appearance_page_gutenberg-widgets' === get_current_screen()->base ) {
		return true;
	}

	return $is_block_editor_screen;
}

add_filter( 'should_load_block_editor_scripts_and_styles', 'gutenberg_widgets_editor_load_block_editor_scripts_and_styles' );

/**
 * Show responsive embeds correctly on the widgets screen by adding the wp-embed-responsive class.
 *
 * @param string $classes existing admin body classes.
 *
 * @return string admin body classes including the wp-embed-responsive class.
 */
function gutenberg_widgets_editor_add_responsive_embed_body_class( $classes ) {
	return "$classes wp-embed-responsive";
}

add_filter( 'admin_body_class', 'gutenberg_widgets_editor_add_responsive_embed_body_class' );
