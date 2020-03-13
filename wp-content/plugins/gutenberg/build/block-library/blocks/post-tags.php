<?php
/**
 * Server-side rendering of the `core/post-tags` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/post-tags` block on the server.
 *
 * @return string Returns the filtered post tags for the current post wrapped inside "a" tags.
 */
function gutenberg_render_block_core_post_tags() {
	$post = gutenberg_get_post_from_context();
	if ( ! $post ) {
		return '';
	}
	$post_tags = get_the_tags();
	if ( ! empty( $post_tags ) ) {
		$output = '';
		foreach ( $post_tags as $tag ) {
			$output .= '<a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a>' . ' | ';
		}
		return trim( $output, ' | ' );
	}
}

/**
 * Registers the `core/post-tags` block on the server.
 */
function gutenberg_register_block_core_post_tags() {
	$path     = __DIR__ . '/post-tags/block.json';
	$metadata = json_decode( file_get_contents( $path ), true );

	register_block_type(
		$metadata['name'],
		array_merge(
			$metadata,
			array(
				'render_callback' => 'gutenberg_render_block_core_post_tags',
			)
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_post_tags', 20 );
