<?php
/**
 * PHP and WordPress configuration compatibility functions for the Gutenberg
 * editor plugin changes related to REST API.
 *
 * @package gutenberg
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

if ( ! function_exists( 'gutenberg_add_post_type_rendering_mode' ) ) {
	/**
	 * Add Block Editor default rendering mode to the post type response.
	 */
	function gutenberg_add_post_type_rendering_mode() {
		$controller = new Gutenberg_REST_Post_Types_Controller_6_8();
		$controller->register_routes();
	}
}
add_action( 'rest_api_init', 'gutenberg_add_post_type_rendering_mode' );

// When querying terms for a given taxonomy in the REST API, respect the default
// query arguments set for that taxonomy upon registration.
function gutenberg_respect_taxonomy_default_args_in_rest_api( $args ) {
	// If a `post` argument is provided, the Terms controller will use
	// `wp_get_object_terms`, which respects the default query arguments,
	// so we don't need to do anything.
	if ( ! empty( $args['post'] ) ) {
		return $args;
	}

	$t = get_taxonomy( $args['taxonomy'] );
	if ( isset( $t->args ) && is_array( $t->args ) ) {
		$args = array_merge( $args, $t->args );
	}
	return $args;
}
add_action(
	'registered_taxonomy',
	function ( $taxonomy ) {
		add_filter( "rest_{$taxonomy}_query", 'gutenberg_respect_taxonomy_default_args_in_rest_api' );
	}
);
add_action(
	'unregistered_taxonomy',
	function ( $taxonomy ) {
		remove_filter( "rest_{$taxonomy}_query", 'gutenberg_respect_taxonomy_default_args_in_rest_api' );
	}
);

/**
 * Adds the default template part areas to the REST API index.
 *
 * This function exposes the default template part areas through the WordPress REST API.
 * Note: This function backports into the wp-includes/rest-api/class-wp-rest-server.php file.
 *
 * @param WP_REST_Response $response REST API response.
 * @return WP_REST_Response Modified REST API response with default template part areas.
 */
function gutenberg_add_default_template_part_areas_to_index( WP_REST_Response $response ) {
	$response->data['default_template_part_areas'] = get_allowed_block_template_part_areas();
	return $response;
}

add_filter( 'rest_index', 'gutenberg_add_default_template_part_areas_to_index' );

/**
 * Adds the default template types to the REST API index.
 *
 * This function exposes the default template types through the WordPress REST API.
 * Note: This function backports into the wp-includes/rest-api/class-wp-rest-server.php file.
 *
 * @param WP_REST_Response $response REST API response.
 * @return WP_REST_Response Modified REST API response with default template part areas.
 */
function gutenberg_add_default_template_types_to_index( WP_REST_Response $response ) {
	$indexed_template_types = array();
	foreach ( get_default_block_template_types() as $slug => $template_type ) {
		$template_type['slug']    = (string) $slug;
		$indexed_template_types[] = $template_type;
	}

	$response->data['default_template_types'] = $indexed_template_types;
	return $response;
}

add_filter( 'rest_index', 'gutenberg_add_default_template_types_to_index' );
