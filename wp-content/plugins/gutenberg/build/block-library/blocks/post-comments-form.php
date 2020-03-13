<?php
/**
 * Server-side rendering of the `core/post-comments-form` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/post-comments-form` block on the server.
 *
 * @return string Returns the filtered post comments form for the current post.
 */
function gutenberg_render_block_core_post_comments_form() {
	$post = gutenberg_get_post_from_context();
	if ( ! $post ) {
		return '';
	}
	ob_start();
	comment_form( array(), $post->ID );
	$form = ob_get_clean();

	return $form;
}

/**
 * Registers the `core/post-comments-form` block on the server.
 */
function gutenberg_register_block_core_post_comments_form() {
	$path     = __DIR__ . '/post-comments-form/block.json';
	$metadata = json_decode( file_get_contents( $path ), true );

	register_block_type(
		$metadata['name'],
		array_merge(
			$metadata,
			array(
				'render_callback' => 'gutenberg_render_block_core_post_comments_form',
			)
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_post_comments_form', 20 );
