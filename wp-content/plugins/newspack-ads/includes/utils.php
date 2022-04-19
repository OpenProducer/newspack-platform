<?php
/**
 * Newspack Ads Utility Functions.
 *
 * @package Newspack
 */

namespace Newspack_Ads;

/**
 * Return a string from a size array.
 *
 * @param array $size Size array.
 *
 * @return string Size string.
 */
function get_size_string( $size ) {
	return $size[0] . 'x' . $size[1];
}

/**
 * Return an array from a size string.
 *
 * @param string $size Size string.
 *
 * @return array Size array.
 */
function get_size_array( $size ) {
	return array_map( 'intval', explode( 'x', $size ) );
}
