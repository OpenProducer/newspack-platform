<?php

/**
 * Set the default editor mode for the page post type to `template-locked`.
 *
 * Note: This backports into `create_initial_post_types` in WordPress Core.
 *
 * @param array $args Array of post type arguments.
 * @return array Updated array of post type arguments.
 */
function gutenberg_update_page_editor_support( $args ) {
	if ( empty( $args['supports'] ) ) {
		return $args;
	}

	$editor_support_key = array_search( 'editor', $args['supports'], true );
	if ( false !== $editor_support_key ) {
		unset( $args['supports'][ $editor_support_key ] );
		$args['supports']['editor'] = array(
			'default-mode' => 'template-locked',
		);
	}

	return $args;
}
add_action( 'register_page_post_type_args', 'gutenberg_update_page_editor_support' );
