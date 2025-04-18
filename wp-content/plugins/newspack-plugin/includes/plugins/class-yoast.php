<?php
/**
 * Yoast integration class.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Yoast {
	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_filter( 'wpseo_image_image_weight_limit', [ __CLASS__, 'ignore_yoast_weight_limit' ] );
	}

	/**
	 * We don't want Yoast to exclude large images from og:image tags for 2 reasons:
	 * 1. Yoast cannot calculate the image size for images served via Jetpack CDN, so any calculations will be incorrect.
	 * 2. It increases support burden since Yoast doesn't provide the user any explanation for why the image was excluded.
	 *
	 * @param int $limit Max image size in bytes.
	 * @return int Modified $limit.
	 */
	public static function ignore_yoast_weight_limit( $limit ) {
		return PHP_INT_MAX;
	}
}
Yoast::init();
