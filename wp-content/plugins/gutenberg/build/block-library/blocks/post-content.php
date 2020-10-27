<?php
/**
 * Server-side rendering of the `core/post-content` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/post-content` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 * @return string Returns the filtered post content of the current post.
 */
function gutenberg_render_block_core_post_content( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}

	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'entry-content' ) );

	return (
		'<div ' . $wrapper_attributes . '>' .
			apply_filters( 'the_content', str_replace( ']]>', ']]&gt;', get_the_content( null, false, $block->context['postId'] ) ) ) .
		'</div>'
	);
}

/**
 * Registers the `core/post-content` block on the server.
 */
function gutenberg_register_block_core_post_content() {
	register_block_type_from_metadata(
		__DIR__ . '/post-content',
		array(
			'render_callback' => 'gutenberg_render_block_core_post_content',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_post_content', 20 );
