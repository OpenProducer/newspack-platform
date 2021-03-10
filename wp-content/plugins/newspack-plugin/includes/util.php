<?php
/**
 * Useful functions.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

define( 'NEWSPACK_API_NAMESPACE', 'newspack/v1' );
define( 'NEWSPACK_API_URL', get_site_url() . '/wp-json/' . NEWSPACK_API_NAMESPACE );

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function newspack_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'newspack_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @param string $string String to convert.
 * @return bool
 */
function newspack_string_to_bool( $string ) {
	return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}
