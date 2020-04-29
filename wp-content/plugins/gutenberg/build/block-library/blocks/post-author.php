<?php
/**
 * Server-side rendering of the `core/post-author` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/post-author` block on the server.
 *
 * @return string Returns the filtered post author for the current post wrapped inside "h6" tags.
 */
function gutenberg_render_block_core_post_author() {
	$post = gutenberg_get_post_from_context();
	if ( ! $post ) {
		return '';
	}
	// translators: %s: The author.
	return '<address>' . sprintf( __( 'By %s' ), get_the_author() ) . '</address>';
}

/**
 * Registers the `core/post-author` block on the server.
 */
function gutenberg_register_block_core_post_author() {
	register_block_type_from_metadata(
		__DIR__ . '/post-author',
		array(
			'render_callback' => 'gutenberg_render_block_core_post_author',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_post_author', 20 );
