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
	\Newspack_Blocks\Modal_Checkout::enqueue_modal();
	\Newspack_Blocks::enqueue_view_assets( 'checkout-button' );
	return $content;
}
