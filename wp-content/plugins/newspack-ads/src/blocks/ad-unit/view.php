<?php
/**
 * Server-side rendering of the `newspack-ads/ad-unit` block.
 *
 * @package WordPress
 */

/**
 * Renders the `newspack-ads/ad-unit` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns the post content with latest posts added.
 */
function newspack_ads_render_block_ad_unit( $attributes ) {
	if ( ! newspack_ads_should_show_ads() ) {
		return '';
	}

	$active_ad = isset( $attributes['activeAd'] ) ? (int) $attributes['activeAd'] : 0;

	if ( 1 > $active_ad ) {
		return '';
	}

	$classes = Newspack_Ads_Blocks::block_classes( 'wp-block-newspack-ads-blocks-ad-unit', $attributes );

	$align = 'inherit';

	if ( strpos( $classes, 'aligncenter' ) == true ) {
		$align = 'center';
	}

	$ad_unit = Newspack_Ads_Model::get_ad_unit( $active_ad );

	if ( is_wp_error( $ad_unit ) ) {
		return '';
	}

	$is_amp = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();

	$code = $is_amp ? $ad_unit['amp_ad_code'] : $ad_unit['ad_code'];

	if ( empty( $code ) ) {
		return '';
	}

	$content = sprintf(
		'<div class="%1$s" style="text-align:%2$s">%3$s</div>',
		esc_attr( $classes ),
		esc_attr( $align ),
		$code /* TODO: escape with wp_kses() */
	);

	Newspack_Ads_Blocks::enqueue_view_assets( 'ad-unit' );

	return $content;
}

/**
 * Registers the `newspack-ads/ad-unit` block on server.
 */
function newspack_ads_register_ad_unit() {
	register_block_type(
		'newspack-ads/ad-unit',
		array(
			'attributes'      => array(
				'activeAd' => array(
					'type' => 'integer',
				),
			),
			'render_callback' => 'newspack_ads_render_block_ad_unit',
			'supports'        => [],
		)
	);
}
add_action( 'init', 'newspack_ads_register_ad_unit' );
