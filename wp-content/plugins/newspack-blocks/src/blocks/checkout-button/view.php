<?php
/**
 * Checkout Button Block Front-End Functions
 *
 * @package Newspack_Blocks
 */

namespace Newspack_Blocks\Checkout_Button;

/**
 * Register the block.
 */
function register_block() {
	register_block_type_from_metadata(
		__DIR__ . '/block.json',
		[
			'render_callback' => __NAMESPACE__ . '\\render_callback',
		]
	);
}
add_action( 'init', __NAMESPACE__ . '\\register_block' );

/**
 * Render the block.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Block content.
 *
 * @return string
 */
function render_callback( $attributes, $content ) {
	if ( empty( $attributes['product'] ) ) {
		return '';
	}
	$product_id = $attributes['product'];
	if ( $attributes['is_variable'] && ! empty( $attributes['variation'] ) ) {
		$product_id = $attributes['variation'];
	}
	\Newspack_Blocks\Modal_Checkout::enqueue_modal( $product_id );
	\Newspack_Blocks::enqueue_view_assets( 'checkout-button' );
	return $content;
}
