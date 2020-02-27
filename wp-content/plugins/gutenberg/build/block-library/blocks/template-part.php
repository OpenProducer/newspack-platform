<?php
/**
 * Server-side rendering of the `core/template-part` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/template-part` block on the server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string The render.
 */
function gutenberg_render_block_core_template_part( $attributes ) {
	$content = null;

	if ( ! empty( $attributes['postId'] ) ) {
		// If we have a post ID, which means this template part
		// is user-customized, render the corresponding post content.
		$content = get_post( $attributes['postId'] )->post_content;
	} elseif ( wp_get_theme()->get( 'TextDomain' ) === $attributes['theme'] ) {
		// Else, if the template part was provided by the active theme,
		// render the corresponding file content.
		$template_part_file_path =
				get_stylesheet_directory() . '/block-template-parts/' . $attributes['slug'] . '.html';
		if ( file_exists( $template_part_file_path ) ) {
			$content = file_get_contents( $template_part_file_path );
		}
	}

	if ( is_null( $content ) ) {
		return 'Template Part Not Found';
	}

	// Run through the actions that are typically taken on the_content.
	$content = do_blocks( $content );
	$content = wptexturize( $content );
	$content = convert_smilies( $content );
	$content = wpautop( $content );
	$content = shortcode_unautop( $content );
	$content = wp_make_content_images_responsive( $content );
	$content = do_shortcode( $content );

	return str_replace( ']]>', ']]&gt;', $content );
}

/**
 * Registers the `core/template-part` block on the server.
 */
function gutenberg_register_block_core_template_part() {
	register_block_type(
		'core/template-part',
		array(
			'attributes'      => array(
				'postId' => array(
					'type' => 'number',
				),
				'slug'   => array(
					'type' => 'string',
				),
				'theme'  => array(
					'type' => 'string',
				),
			),
			'render_callback' => 'gutenberg_render_block_core_template_part',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_template_part', 20 );
