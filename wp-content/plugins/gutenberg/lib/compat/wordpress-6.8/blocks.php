<?php
/**
 * Temporary compatibility shims for block APIs present in Gutenberg.
 *
 * @package gutenberg
 */

function gutenberg_apply_block_hooks_to_post_content( $content ) {
	// The `the_content` filter does not provide the post that the content is coming from.
	// However, we can infer it by calling `get_post()`, which will return the current post
	// if no post ID is provided.
	return apply_block_hooks_to_content( $content, get_post(), 'insert_hooked_blocks' );
}
// We need to apply this filter before `do_blocks` (which is hooked to `the_content` at priority 9).
add_filter( 'the_content', 'gutenberg_apply_block_hooks_to_post_content', 8 );

/**
 * Hooks into the REST API response for the Posts endpoint and adds the first and last inner blocks.
 *
 * @since 6.6.0
 * @since 6.8.0 Support non-`wp_navigation` post types.
 *
 * @param WP_REST_Response $response The response object.
 * @param WP_Post          $post     Post object.
 * @return WP_REST_Response The response object.
 */
function gutenberg_insert_hooked_blocks_into_rest_response( $response, $post ) {
	if ( empty( $response->data['content']['raw'] ) ) {
		return $response;
	}

	$attributes            = array();
	$ignored_hooked_blocks = get_post_meta( $post->ID, '_wp_ignored_hooked_blocks', true );
	if ( ! empty( $ignored_hooked_blocks ) ) {
		$ignored_hooked_blocks  = json_decode( $ignored_hooked_blocks, true );
		$attributes['metadata'] = array(
			'ignoredHookedBlocks' => $ignored_hooked_blocks,
		);
	}

	if ( 'wp_navigation' === $post->post_type ) {
		$wrapper_block_type = 'core/navigation';
	} elseif ( 'wp_block' === $post->post_type ) {
		$wrapper_block_type = 'core/block';
	} else {
		$wrapper_block_type = 'core/post-content';
	}

	$content = get_comment_delimited_block_content(
		$wrapper_block_type,
		$attributes,
		$response->data['content']['raw']
	);

	$content = apply_block_hooks_to_content(
		$content,
		$post,
		'insert_hooked_blocks_and_set_ignored_hooked_blocks_metadata'
	);

	// Remove mock block wrapper.
	$content = remove_serialized_parent_block( $content );

	$response->data['content']['raw'] = $content;

	// If the rendered content was previously empty, we leave it like that.
	if ( empty( $response->data['content']['rendered'] ) ) {
		return $response;
	}

	// No need to inject hooked blocks twice.
	$priority = has_filter( 'the_content', 'apply_block_hooks_to_content' );
	if ( false !== $priority ) {
		remove_filter( 'the_content', 'apply_block_hooks_to_content', $priority );
	}

	/** This filter is documented in wp-includes/post-template.php */
	$response->data['content']['rendered'] = apply_filters( 'the_content', $content );

	// Add back the filter.
	if ( false !== $priority ) {
		add_filter( 'the_content', 'apply_block_hooks_to_content', $priority );
	}

	return $response;
}
add_filter( 'rest_prepare_page', 'gutenberg_insert_hooked_blocks_into_rest_response', 10, 2 );
add_filter( 'rest_prepare_post', 'gutenberg_insert_hooked_blocks_into_rest_response', 10, 2 );
add_filter( 'rest_prepare_wp_block', 'gutenberg_insert_hooked_blocks_into_rest_response', 10, 2 );

/**
 * Updates the wp_postmeta with the list of ignored hooked blocks
 * where the inner blocks are stored as post content.
 *
 * @since 6.6.0
 * @since 6.8.0 Support other post types. (Previously, it was limited to `wp_navigation` only.)
 * @access private
 *
 * @param stdClass $post Post object.
 * @return stdClass The updated post object.
 */
function gutenberg_update_ignored_hooked_blocks_postmeta( $post ) {
	/*
	 * In this scenario the user has likely tried to create a new post object via the REST API.
	 * In which case we won't have a post ID to work with and store meta against.
	 */
	if ( empty( $post->ID ) ) {
		return $post;
	}

	/*
	 * Skip meta generation when consumers intentionally update specific fields
	 * and omit the content update.
	 */
	if ( ! isset( $post->post_content ) ) {
		return $post;
	}

	/*
	 * Skip meta generation if post type is not set.
	 */
	if ( ! isset( $post->post_type ) ) {
		return $post;
	}

	$attributes = array();

	$ignored_hooked_blocks = get_post_meta( $post->ID, '_wp_ignored_hooked_blocks', true );
	if ( ! empty( $ignored_hooked_blocks ) ) {
		$ignored_hooked_blocks  = json_decode( $ignored_hooked_blocks, true );
		$attributes['metadata'] = array(
			'ignoredHookedBlocks' => $ignored_hooked_blocks,
		);
	}

	if ( 'wp_navigation' === $post->post_type ) {
		$wrapper_block_type = 'core/navigation';
	} elseif ( 'wp_block' === $post->post_type ) {
		$wrapper_block_type = 'core/block';
	} else {
		$wrapper_block_type = 'core/post-content';
	}

	$markup = get_comment_delimited_block_content(
		$wrapper_block_type,
		$attributes,
		$post->post_content
	);

	$existing_post = get_post( $post->ID );
	// Merge the existing post object with the updated post object to pass to the block hooks algorithm for context.
	$context          = (object) array_merge( (array) $existing_post, (array) $post );
	$context          = new WP_Post( $context ); // Convert to WP_Post object.
	$serialized_block = apply_block_hooks_to_content( $markup, $context, 'set_ignored_hooked_blocks_metadata' );
	$root_block       = parse_blocks( $serialized_block )[0];

	$ignored_hooked_blocks = isset( $root_block['attrs']['metadata']['ignoredHookedBlocks'] )
		? $root_block['attrs']['metadata']['ignoredHookedBlocks']
		: array();

	if ( ! empty( $ignored_hooked_blocks ) ) {
		$existing_ignored_hooked_blocks = get_post_meta( $post->ID, '_wp_ignored_hooked_blocks', true );
		if ( ! empty( $existing_ignored_hooked_blocks ) ) {
			$existing_ignored_hooked_blocks = json_decode( $existing_ignored_hooked_blocks, true );
			$ignored_hooked_blocks          = array_unique( array_merge( $ignored_hooked_blocks, $existing_ignored_hooked_blocks ) );
		}

		if ( ! isset( $post->meta_input ) ) {
			$post->meta_input = array();
		}
		$post->meta_input['_wp_ignored_hooked_blocks'] = json_encode( $ignored_hooked_blocks );
	}

	$post->post_content = remove_serialized_parent_block( $serialized_block );
	return $post;
}
add_filter( 'rest_pre_insert_page', 'gutenberg_update_ignored_hooked_blocks_postmeta' );
add_filter( 'rest_pre_insert_post', 'gutenberg_update_ignored_hooked_blocks_postmeta' );
add_filter( 'rest_pre_insert_wp_block', 'gutenberg_update_ignored_hooked_blocks_postmeta' );
