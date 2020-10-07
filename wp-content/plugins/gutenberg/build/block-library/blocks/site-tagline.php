<?php
/**
 * Server-side rendering of the `core/site-tagline` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/site-tagline` block on the server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string The render.
 */
function gutenberg_render_block_core_site_tagline( $attributes ) {
	$align_class_name = empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";

	return sprintf(
		'<p class="%1$s">%2$s</p>',
		esc_attr( $align_class_name ),
		get_bloginfo( 'description' )
	);
}

/**
 * Registers the `core/site-tagline` block on the server.
 */
function gutenberg_register_block_core_site_tagline() {
	register_block_type_from_metadata(
		__DIR__ . '/site-tagline',
		array(
			'render_callback' => 'gutenberg_render_block_core_site_tagline',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_site_tagline', 20 );
