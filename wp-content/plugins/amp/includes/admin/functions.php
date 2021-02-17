<?php
/**
 * Callbacks for adding AMP-related things to the admin.
 *
 * @package AMP
 */

use AmpProject\AmpWP\Option;

/**
 * Sets up the AMP template editor for the Customizer.
 *
 * @internal
 */
function amp_init_customizer() {

	// Fire up the AMP Customizer.
	add_action( 'customize_register', [ 'AMP_Template_Customizer', 'init' ], 500 );

	if ( amp_is_legacy() ) {
		// Add some basic design settings + controls to the Customizer.
		add_action( 'amp_init', [ 'AMP_Customizer_Design_Settings', 'init' ] );
	}

	// Add a link to the AMP Customizer in Reader mode.
	if ( AMP_Theme_Support::READER_MODE_SLUG === AMP_Options_Manager::get_option( Option::THEME_SUPPORT ) ) {
		add_action( 'admin_menu', 'amp_add_customizer_link' );
	}
}

/**
 * Get permalink for the first AMP-eligible post.
 *
 * @internal
 * @return string|null URL on success, null if none found.
 */
function amp_admin_get_preview_permalink() {
	/**
	 * Filter the post type to retrieve the latest for use in the AMP template customizer.
	 *
	 * @param string $post_type Post type slug. Default 'post'.
	 */
	$post_type = (string) apply_filters( 'amp_customizer_post_type', 'post' );

	// Make sure the desired post type is actually supported, and if so, prefer it.
	$supported_post_types = AMP_Post_Type_Support::get_supported_post_types();
	if ( in_array( $post_type, $supported_post_types, true ) ) {
		$supported_post_types = array_unique( array_merge( [ $post_type ], $supported_post_types ) );
	}

	// Bail if there are no supported post types.
	if ( empty( $supported_post_types ) ) {
		return null;
	}

	// If theme support is present, then bail if the singular template is not supported.
	if ( ! amp_is_legacy() ) {
		$supported_templates = AMP_Theme_Support::get_supportable_templates();
		if ( empty( $supported_templates['is_singular']['supported'] ) ) {
			return null;
		}
	}

	$post_ids = get_posts(
		[
			'no_found_rows'    => true,
			'suppress_filters' => false,
			'post_status'      => 'publish',
			'post_type'        => $supported_post_types,
			'posts_per_page'   => 1,
			'fields'           => 'ids',
			// @todo This should eventually do a meta_query to make sure there are none that have AMP_Post_Meta_Box::STATUS_POST_META_KEY = DISABLED_STATUS.
		]
	);

	if ( empty( $post_ids ) ) {
		return null;
	}

	$post_id = $post_ids[0];

	return amp_get_permalink( $post_id );
}

/**
 * Provides a URL to the customizer.
 *
 * @internal
 * @return string
 */
function amp_get_customizer_url() {
	$is_legacy = amp_is_legacy();
	$mode      = AMP_Options_Manager::get_option( Option::THEME_SUPPORT );

	/** This filter is documented in includes/settings/class-amp-customizer-design-settings.php */
	if ( 'reader' !== $mode || ( $is_legacy && ! apply_filters( 'amp_customizer_is_enabled', true ) ) ) {
		return '';
	}

	$args = [
		'url' => amp_admin_get_preview_permalink(),
	];
	if ( $is_legacy ) {
		$args['autofocus[panel]'] = AMP_Template_Customizer::PANEL_ID;
	} else {
		$args[ amp_get_slug() ] = '1';
	}

	return add_query_arg( urlencode_deep( $args ), 'customize.php' );
}

/**
 * Registers a submenu page to access the AMP template editor panel in the Customizer.
 *
 * @internal
 */
function amp_add_customizer_link() {
	$customizer_url = amp_get_customizer_url();

	if ( ! $customizer_url ) {
		return;
	}

	// Add the theme page.
	add_theme_page(
		__( 'AMP', 'amp' ),
		__( 'AMP', 'amp' ),
		'edit_theme_options',
		$customizer_url
	);
}

/**
 * Add custom analytics.
 *
 * This is currently only used for legacy AMP post templates.
 *
 * @since 0.5
 * @see amp_get_analytics()
 * @internal
 *
 * @param array $analytics Analytics.
 * @return array Analytics.
 */
function amp_add_custom_analytics( $analytics = [] ) {
	$analytics = amp_get_analytics( $analytics );

	/**
	 * Add amp-analytics tags.
	 *
	 * This filter allows you to easily insert any amp-analytics tags without needing much heavy lifting.
	 * This filter should be used to alter entries for legacy AMP templates.
	 *
	 * @since 0.4
	 *
	 * @param array   $analytics An associative array of the analytics entries we want to output. Each array entry must have a unique key, and the value should be an array with the following keys: `type`, `attributes`, `script_data`. See readme for more details.
	 * @param WP_Post $post      The current post.
	 */
	$analytics = apply_filters( 'amp_post_template_analytics', $analytics, get_queried_object() );

	return $analytics;
}

/**
 * Bootstrap AMP Editor core blocks.
 *
 * @internal
 */
function amp_editor_core_blocks() {
	$editor_blocks = new AMP_Editor_Blocks();
	$editor_blocks->init();
}

/**
 * Bootstraps AMP admin classes.
 *
 * @since 1.5.0
 * @internal
 */
function amp_bootstrap_admin() {
	$admin_pointers = new AMP_Admin_Pointers();
	$admin_pointers->init();

	$post_meta_box = new AMP_Post_Meta_Box();
	$post_meta_box->init();
}

/**
 * Whether to activate the new onboarding feature.
 *
 * @internal
 * @return bool
 */
function amp_should_use_new_onboarding() {
	if ( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ) {
		return false;
	}

	return true;
}
