<?php
/**
 * Server-side rendering of the `core/site-title` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/site-title` block on the server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string The render.
 */
function gutenberg_render_block_core_site_title( $attributes ) {
	$tag_name         = 'h1';
	$align_class_name = empty( $attributes['align'] ) ? '' : ' ' . "has-text-align-{$attributes['align']}";

	if ( isset( $attributes['level'] ) ) {
		$tag_name = 0 === $attributes['level'] ? 'p' : 'h' . $attributes['level'];
	}

	return sprintf(
		'<%1$s class="%2$s">%3$s</%1$s>',
		$tag_name,
		'wp-block-site-title' . esc_attr( $align_class_name ),
		get_bloginfo( 'name' )
	);
}

/**
 * Registers the `core/site-title` block on the server.
 */
function gutenberg_register_block_core_site_title() {
	register_block_type_from_metadata(
		__DIR__ . '/site-title',
		array(
			'render_callback' => 'gutenberg_render_block_core_site_title',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_site_title', 20 );
