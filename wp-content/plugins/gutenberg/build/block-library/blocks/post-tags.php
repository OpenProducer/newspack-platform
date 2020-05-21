<?php
/**
 * Server-side rendering of the `core/post-tags` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/post-tags` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 * @return string Returns the filtered post tags for the current post wrapped inside "a" tags.
 */
function gutenberg_render_block_core_post_tags( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}

	$post_tags = get_the_tags( $block->context['postId'] );
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
	register_block_type_from_metadata(
		__DIR__ . '/post-tags',
		array(
			'render_callback' => 'gutenberg_render_block_core_post_tags',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_post_tags', 20 );
