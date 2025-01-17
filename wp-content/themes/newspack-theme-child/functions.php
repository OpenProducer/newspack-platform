<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_add_parent_dep' ) ):
function chld_thm_cfg_add_parent_dep() {
    global $wp_styles;
    array_unshift( $wp_styles->registered[ 'newspack-style' ]->deps, 'newspack-print-style' );
}
endif;
add_action( 'wp_head', 'chld_thm_cfg_add_parent_dep', 2 );

// END ENQUEUE PARENT ACTION

/**
 ** Add support for custom post types so they show up in home page posts and whatnot
*/
add_post_type_support( 'tribe_events', 'newspack_blocks' );
add_post_type_support( 'tribe_events', array(
    'event_date' ));
add_post_type_support( 'show', 'newspack_blocks' );




add_filter(
  'tribe_events_views_v2_view_list_repository_args',
  function ( $args ) {
    $args['orderby'] = 'event_date';
    $args['order'] = 'DESC';
 
    return $args;
  }
);

function add_event_categories_to_carousel( $query_args ) {
    // Check if the query is for the Post Carousel Block and events
    if ( isset( $query_args['post_type'] ) && $query_args['post_type'] === 'tribe_events' ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'tribe_events_cat', // Custom taxonomy for event categories
                'field'    => 'slug', // Filter by slug
                'terms'    => array( 'upcoming-events' ), // Replace with your event category slug(s)
            ),
        );
    }
    return $query_args;
}
add_filter( 'newspack_blocks_post_carousel_query_args', 'add_event_categories_to_carousel' );
