<?php
/**
 * Server-side rendering of the `core/post-comments-count` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/post-comments-count` block on the server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns the filtered post comments count for the current post.
 */
function gutenberg_render_block_core_post_comments_count( $attributes ) {
	$post = gutenberg_get_post_from_context();
	if ( ! $post ) {
		return '';
	}
	$class = 'wp-block-post-comments-count';
	if ( isset( $attributes['className'] ) ) {
		$class .= ' ' . $attributes['className'];
	}
	return sprintf(
		'<span class="%1$s">%2$s</span>',
		esc_attr( $class ),
		get_comments_number( $post )
	);
}

/**
 * Registers the `core/post-comments-count` block on the server.
 */
function gutenberg_register_block_core_post_comments_count() {
	$path     = __DIR__ . '/post-comments-count/block.json';
	$metadata = json_decode( file_get_contents( $path ), true );

	register_block_type(
		$metadata['name'],
		array_merge(
			$metadata,
			array(
				'attributes'      => array(
					'className' => array(
						'type' => 'string',
					),
				),
				'render_callback' => 'gutenberg_render_block_core_post_comments_count',
			)
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_post_comments_count', 20 );
