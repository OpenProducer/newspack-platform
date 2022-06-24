<?php
/**
 * Front-end render functions for the Price block.
 *
 * @package Newspack_Listings
 */

namespace Newspack_Listings\Price_Block;

use \Newspack_Listings\Core;
use \Newspack_Listings\Utils;

/**
 * Dynamic block registration.
 */
function register_block() {
	// Block attributes.
	$block_json = json_decode(
		file_get_contents( __DIR__ . '/block.json' ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		true
	);

	// Register Price block.
	register_block_type(
		$block_json['name'],
		[
			'attributes'      => $block_json['attributes'],
			'render_callback' => __NAMESPACE__ . '\render_block',
		]
	);
}

/**
 * Block render callback.
 *
 * @param array $attributes Block attributes.
 * @return string $content content.
 */
function render_block( $attributes ) {
	return '<p class="newspack-listings__price has-large-font-size"><strong>' . esc_html( $attributes['formattedPrice'] ) . '</strong></p>';
}

register_block();
