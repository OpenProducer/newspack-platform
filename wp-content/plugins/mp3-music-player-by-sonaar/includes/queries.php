<?php
/**
 * Get Artist
 */
    function sr_plugin_elementor_select_artist(){
        $sr_artist_list = get_posts(array(
            'post_type' => 'artist',
            'posts_per_page' => -1,
        ));
        $options = array();

        if ( ! empty( $sr_artist_list ) && ! is_wp_error( $sr_artist_list ) ){
            foreach ( $sr_artist_list as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        } else {
            $options[0] = esc_html__( 'Create an Artist First', 'sonaar-music' );
        }
        return $options;
    }

/**
 * Get Category
 */

 function srp_elementor_select_category() {
    // Retrieve the post types from the options
    $sr_postypes = Sonaar_Music_Admin::get_cpt($all = true);
    // Initialize the options array
    $options = array();

    // Loop through each post type and retrieve its terms
    foreach ($sr_postypes as $post_type) {
        $taxonomies = get_object_taxonomies($post_type, 'names');

        // Get the post type label
        $post_type_object = get_post_type_object($post_type);
        $post_type_label = isset($post_type_object->label) ? $post_type_object->label : $post_type;

        // If it's a WooCommerce product, only use the product_cat taxonomy
        if ($post_type == 'product' && defined('WC_VERSION')) {
            $taxonomies = array('product_cat', 'product_tag');
        }

        // Check if SR_PLAYLIST_CPT is defined, otherwise set 'sr_playlist'
        $defaultPostType = defined('SR_PLAYLIST_CPT') ? SR_PLAYLIST_CPT : 'sr_playlist';
        // If it's an SR Playlist, only use the playlist-category taxonomy
        if ($post_type == $defaultPostType) {
            $taxonomies[] = 'playlist-category';
            $taxonomies[] = 'playlist-tag';

            if (Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast') {
                $taxonomies[] = 'podcast-show';
            }
        }

        // Get the terms for each taxonomy
        foreach ($taxonomies as $taxonomy) {
            $args = array(
                'taxonomy' => $taxonomy,
                'hide_empty' => apply_filters('sonaar/hide_empty_terms', true),
            );
            $terms = get_terms($args);

            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    // Include the post type label in the option key
                    $option_key = $term->name . ' ( ' . $term->count . ' ) [' . $post_type_label . ']';
                    $options[$term->term_id] = $option_key;
                }
            }
        }
    }

    // Return the options array with the terms
    return $options;
}


function srp_elementor_select_authors() {
    // Retrieve all users with roles including Dokan vendors
    $args = array(
        'role__in' => array('Author', 'Administrator', 'Editor', 'Shop Manager', 'Seller'), // Include 'Seller' for Dokan vendors
        'orderby' => 'display_name',
        'order' => 'ASC',
    );

    $authors = get_users($args);

    // Initialize the options array
    $options = array();

    // Loop through each user and format their display
    foreach ($authors as $author) {
        $options[$author->ID] = $author->display_name;
    }

    return $options;
}



/**
 * Get Music Playlist
 */
    function sr_plugin_playlist_source($shortcodebuilder = false){
        $options = array(
            'from_cpt' => 'Selected Post(s)',
            'from_cat' => 'All Posts/Categories',
            'from_elementor' => 'This Widget',
            'from_current_post' => 'Current Post',
            'from_current_term' => 'Current Term',
            'from_rss' => 'RSS Feed',
            'from_text_file' => 'CSV File'
        );
        if(function_exists( 'run_sonaar_music_generator' )){
            //this function is unique to the sonaar-music-generate plugin
            $options['from_user_meta'] = 'From User Meta';
        }
        if(function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1'){
            $options['from_favorites'] = 'User Favorites';
            $options['recently_played'] = 'User Recently Played Tracks';
        }

        if($shortcodebuilder){    
            if(!function_exists( 'run_sonaar_music_pro' ) || empty(get_site_option('sonaar_music_licence'))){
                $options = array(
                    'from_cpt'          => 'Selected Post(s)',
                    'from_cat_all'      => 'All Posts',
                    'from_feed'         => 'Audio URL(s)',
                    'from_rss'          => 'RSS Feed',
                    'from_cat'          => '[Pro Feature - Starter Plan] From Specific Categories', //from_cat is intentional to see a list of cat, even not pro
                    'pro3'              => '[Pro Feature - Starter Plan] Current Post',
                    'pro4'              => '[Pro Feature - Starter Plan] Current Term',
                    'pro5'              => '[Pro Feature - Business Plan] User Favorites',
                    'pro6'              => '[Pro Feature - Business Plan] User Recently Played Tracks',
                );
            }else{
                $options = array(
                    'from_cpt'          => 'Selected Post(s)',
                    'from_cat_all'      => 'All Posts',
                    'from_cat'          => 'From Specific Categories',
                    'from_current_post' => 'Current Post',
                    'from_current_term' => 'Current Term',
                    'from_feed'         => 'Audio URL(s)',
                    'from_rss'          => 'RSS Feed',
                    'pro1'              => '[Pro Feature - Business Plan] User Favorites',
                    'pro2'              => '[Pro Feature - Business Plan] User Recently Played Tracks',
                );
            }
            if(function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1' && !empty(get_site_option('sonaar_music_licence'))){
                $options = array(
                    'from_cpt'          => 'Selected Post(s)',
                    'from_cat_all'      => 'All Posts',
                    'from_cat'          => 'From Specific Categories',
                    'from_current_post' => 'Current Post',
                    'from_current_term' => 'Current Term',
                    'from_feed'         => 'Audio URL(s)',
                    'from_rss'          => 'RSS Feed',
                    'from_favorites'    => 'User Favorites',
                    'recently_played'   => 'User Recently Played Tracks',
                );
            }
           
        }
        

        return $options;
    }
    function sr_plugin_elementor_select_playlist(){

        $sr_postypes = Sonaar_Music_Admin::get_cpt($all = true);

        $sr_playlist_list = get_posts(array(
            'post_type' => $sr_postypes,//array(SR_PLAYLIST_CPT, 'post', 'product'),
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));
        $options = array();

        if ( ! empty( $sr_playlist_list ) && ! is_wp_error( $sr_playlist_list ) ){
            foreach ( $sr_playlist_list as $post ) {
                if (Sonaar_Music::srmp3_check_if_audio($post)){
                    $options[ $post->ID ] = '['.$post->post_type .'] ' . $post->post_title;     
                }
            }
        } else {
            $options[0] = esc_html__( 'Create a Playlist First', 'sonaar-music' );
        }
        return $options;
    }

/**
 * Get Latest Published Post
 */
    function sr_plugin_elementor_getLatestPost($posttype){
        $arg = wp_get_recent_posts(array('post_type'=>$posttype, 'post_status' => 'publish', 'numberposts' => 1));
        if (!empty($arg)){
            return $arg[0]["ID"];
        }
    }