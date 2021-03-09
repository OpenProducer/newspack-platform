<?php
/**
 * Utility functions for Newspack Listings.
 *
 * @package Newspack_Listings
 */

namespace Newspack_Listings\Utils;

/**
 * Sanitize an array of text values.
 *
 * @param array $array Array of text or float values to be sanitized.
 * @return array Sanitized array.
 */
function sanitize_array( $array ) {
	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$value = sanitize_array( $value );
		} else {
			if ( is_string( $value ) ) {
				$value = sanitize_text_field( $value );
			} else {
				$value = floatval( $value );
			}
		}
	}

	return $array;
}


/**
 * Loads a template with given data in scope.
 *
 * @param string $template Name of the template to be included.
 * @param array  $data     Data to be passed into the template to be included.
 * @param string $path     (Optional) Path to the folder containing the template.
 * @return string
 */
function template_include( $template, $data = [], $path = NEWSPACK_LISTINGS_PLUGIN_FILE . 'src/templates/' ) {
	if ( ! strpos( $template, '.php' ) ) {
		$template = $template . '.php';
	}
	$path .= $template;
	if ( ! is_file( $path ) ) {
		return '';
	}
	ob_start();
	include $path;
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents;
}

/**
 * Given a block name, get all blocks (and recursively, all inner blocks) matching the given type.
 *
 * @param string $block_name Block name to match.
 * @param array  $blocks Array of block objects to search.
 *
 * @return array Array of matching blocks.
 */
function get_blocks_by_type( $block_name, $blocks ) {
	$matching_blocks = [];

	if ( empty( $block_name ) ) {
		return $matching_blocks;
	}

	foreach ( $blocks as $block ) {
		if ( $block['blockName'] === $block_name ) {
			$matching_blocks[] = $block;
		}

		// Recursively check inner blocks, too.
		if ( 0 < count( $block['innerBlocks'] ) ) {
			$matching_blocks = array_merge( $matching_blocks, get_blocks_by_type( $block_name, $block['innerBlocks'] ) );
		}
	}

	return $matching_blocks;
}

/**
 * Get data from content blocks within the given $post_id.
 * Searches the content of the post for instances of the source block,
 * then returns the given attributes for all block instances.
 *
 * @param array $blocks Array of block objects to get data from.
 * @param array $source Info for the block to source the data from.
 *              ['blockName'] Name of the block to search for.
 *              ['attrs']     (Optional) Specific block attributes to get.
 *                            If not provided, all attributes will be returned.
 *
 * @return array|Boolean Array of block data, or false if there are no blocks matching the given source (or no data to return).
 */
function get_data_from_blocks( $blocks, $source ) {
	$data = [];

	if ( ! empty( $source ) && ! empty( $source['blockName'] ) ) {
		$matching_blocks = get_blocks_by_type( $source['blockName'], $blocks );

		// Return false if there are no matching blocks of the given source type.
		if ( empty( $matching_blocks ) ) {
			return false;
		}

		// If the source has 'single' specified, only get data from the first found block instance.
		if ( ! empty( $source['single'] ) ) {
			$matching_blocks = array_slice( $matching_blocks, 0, 1 );
		}

		// Gather data from all matching block instances.
		foreach ( $matching_blocks as $matching_block ) {
			$block_data = false;

			// If we have a source `attr` key, sync only that attribute, otherwise sync all attributes.
			if ( ! empty( $source['attr'] ) ) {
				if ( ! empty( $matching_block['attrs'][ $source['attr'] ] ) ) {
					$block_data = $matching_block['attrs'][ $source['attr'] ];
				}
			} else {
				$block_data = [ $matching_block['attrs'] ];
			}

			if ( is_array( $block_data ) ) {
				$data = array_merge( $data, $block_data );
			} elseif ( ! empty( $block_data ) ) {
				$data[] = $block_data;
			}
		}
	}

	// Return false instead of an empty array, if there's no data to return.
	if ( empty( $data ) ) {
		return false;
	}

	// If the source has 'single' specified, only return data from the first found block instance.
	if ( ! empty( $source['single'] ) ) {
		return array_shift( $data );
	}

	return $data;
}

/**
 * Modified excerpt generator that allows some HTML.
 * Will use the excerpt if it exists, otherwise will generate from post content.
 *
 * @param array    $post Post object to create excerpt from.
 * @param int|null $excerpt_length (Optional) Max length of excerpt to generate.
 *
 * @return string|boolean Post excerpt or generated excerpt, or false if $post is invalid.
 */
function get_listing_excerpt( $post, $excerpt_length = null ) {
	// Bail if we don't have a valid post object.
	if ( empty( $post ) || empty( $post->post_content ) ) {
		return false;
	}

	$the_dates = '';

	// If post contains event dates, prepend them to the excerpt.
	$event_dates_blocks = get_blocks_by_type( 'newspack-listings/event-dates', parse_blocks( $post->post_content ) );

	if ( is_array( $event_dates_blocks ) && 0 < count( $event_dates_blocks ) ) {
		foreach ( $event_dates_blocks as $event_date_block ) {
			$event_dates = template_include(
				'event-dates',
				[ 'attributes' => array_shift( $event_dates_blocks )['attrs'] ]
			);

			$the_dates .= $event_dates;
		}
	}

	// If we have a manually entered excerpt, use that.
	if ( ! empty( $post->post_excerpt ) ) {
		return $the_dates . wpautop( $post->post_excerpt );
	}

	// Recreate logic from wp_trim_excerpt (https://developer.wordpress.org/reference/functions/wp_trim_excerpt/).
	$excerpt = $post->post_content;
	$excerpt = strip_shortcodes( $excerpt );
	$excerpt = excerpt_remove_blocks( $excerpt );
	$excerpt = wpautop( $excerpt );
	$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );

	// Strip HTML tags except for the explicitly allowed tags.
	$allowed_tags = '<em>,<i>,<strong>,<b>,<u>,<ul>,<ol>,<li>,<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<p>,<img>';
	$excerpt      = strip_tags( $excerpt, $allowed_tags ); // phpcs:ignore WordPressVIPMinimum.Functions.StripTags.StripTagsTwoParameters

	// Get excerpt length. If not provided a valid length, use the default excerpt length.
	if ( empty( $excerpt_length ) || ! is_int( $excerpt_length ) ) {
		$excerpt_length = 55;
	}

	// Set excerpt length.
	$excerpt_length = (int) apply_filters( 'excerpt_length', $excerpt_length );

	// Divide string into tokens (HTML vs. words) (https://wordpress.stackexchange.com/questions/141125/allow-html-in-excerpt).
	$tokens = [];
	$output = '';
	$index  = 0;

	preg_match_all( '/(<[^>]+>|[^<>\s]+)\s*/u', $excerpt, $tokens );

	// Add ellipses.
	$excerpt_more = apply_filters( 'excerpt_more', ' [&hellip;]' );

	foreach ( $tokens[0] as $token ) {
		if ( $index >= $excerpt_length && preg_match( '/[\,\;\?\.\!]\s*$/uS', $token ) ) {
			// Limit reached, continue until , ; ? . or ! occur at the end.
			$output .= trim( $token );
			$output .= $excerpt_more; // Add ellipses inside the last HTML tag.
			break;
		}

		// Add words to complete sentence.
		$index++;

		// Append what's left of the token.
		$output .= $token;
	}

	// Balance unclosed HTML tags and trim whitespace.
	$output = trim( force_balance_tags( $output ) );

	return $the_dates . $output;
}

/**
 * Get attributes formatted for REST API requests.
 *
 * @param array $attributes Array of block attributes.
 * @return array Formatted array of the attributes we care about.
 */
function get_request_attributes( $attributes ) {
	$listing_attributes = [ 'textColor', 'showImage', 'showCaption', 'showCategory', 'showTags', 'showAuthor', 'showExcerpt' ];

	return array_map(
		function( $attribute ) {
			return false === $attribute ? '0' : str_replace( '#', '%23', $attribute );
		},
		array_intersect_key( $attributes, array_flip( $listing_attributes ) )
	);
}

/**
 * Are the given dates the same calendar day?
 *
 * @param DateTime $start_date Start date class.
 * @param DateTime $end_date End date class.
 * @return boolean Whether the two dates are the same day.
 */
function is_same_day( $start_date, $end_date ) {
	return $start_date->format( 'F j, Y' ) === $end_date->format( 'F j, Y' );
}

/**
 * Are the given dates in the same calendar month?
 *
 * @param DateTime $start_date Start date class.
 * @param DateTime $end_date End date class.
 * @return boolean Whether the two dates are in the same month.
 */
function is_same_month( $start_date, $end_date ) {
	return $start_date->format( 'F, Y' ) === $end_date->format( 'F, Y' );
}

/**
 * Are the given dates in the same calendar year?
 *
 * @param DateTime $start_date Start date class.
 * @param DateTime $end_date End date class.
 * @return boolean Whether the two dates are in the same year.
 */
function is_same_year( $start_date, $end_date ) {
	return $start_date->format( 'Y' ) === $end_date->format( 'Y' );
}

/**
 * Given a YYYY-MM-DDTHH:MM:SS date/time string, get only the date.
 *
 * @param string $date_string Date/time string in YYYY-MM-DDTHH:MM:SS format.
 * @return string The same date string, but without the timestamp.
 */
function strip_time( $date_string ) {
	return explode( 'T', $date_string )[0];
}

/**
 * Checks whether the current view is served in AMP context.
 *
 * @return bool True if AMP, false otherwise.
 */
function is_amp() {
	return ! is_admin() && function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
}


/**
 * Use AMP Plugin functions to render markup as valid AMP.
 *
 * @param string $html Markup to convert to AMP.
 * @return string
 */
function generate_amp_partial( $html ) {
	$dom = \AMP_DOM_Utils::get_dom_from_content( $html );

	\AMP_Content_Sanitizer::sanitize_document(
		$dom,
		amp_get_content_sanitizers(),
		[
			'use_document_element' => false,
		]
	);
	$xpath = new \DOMXPath( $dom );
	foreach ( iterator_to_array( $xpath->query( '//noscript | //comment()' ) ) as $node ) {
		$node->parentNode->removeChild( $node ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}
	return \AMP_DOM_Utils::get_content_from_dom( $dom );
}
