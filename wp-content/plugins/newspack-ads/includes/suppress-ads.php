<?php
/**
 * Controls for suppressing ads on certain singles.
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register meta for suppressing ads on singles.
 */
function newspack_ads_register_suppress_ad_meta() {
	register_post_meta(
		'',
		'newspack_ads_suppress_ads',
		array(
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'boolean',
		)
	);
}
add_action( 'init', 'newspack_ads_register_suppress_ad_meta' );

/**
 * Enqueue editor assets for suppressing ads.
 */
function newspack_ads_enqueue_suppress_ad_assets() {
	if ( 'post' === get_current_screen()->post_type || 'page' === get_current_screen()->post_type ) {
		wp_enqueue_script( 'newspack-ads-suppress-ads', Newspack_Ads::plugin_url( 'dist/suppress-ads.js' ), [], NEWSPACK_ADS_VERSION, true );
	}
}
add_action( 'enqueue_block_editor_assets', 'newspack_ads_enqueue_suppress_ad_assets' );

/**
 * Get whether ads should be displayed on a screen.
 *
 * @param int $post_id Post ID to check (optional, default: current post).
 * @return bool
 */
function newspack_ads_should_show_ads( $post_id = null ) {
	$should_show = true;

	if ( is_singular() ) {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		if ( get_post_meta( $post_id, 'newspack_ads_suppress_ads', true ) ) {
			$should_show = false;
		}
	}

	return apply_filters( 'newspack_ads_should_show_ads', $should_show, $post_id );
}
