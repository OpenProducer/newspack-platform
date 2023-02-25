<?php
/**
 * Performance management.
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Manages settings for Performance.
 */
class Performance {
	/**
	 * Whether page content is rendered now.
	 *
	 * @var bool
	 */
	public static $is_rendering_page_content = false;

	/**
	 * Index of currently rendered Homepage Post Block.
	 *
	 * @var int
	 */
	public static $current_homepage_posts_block = 0;

	/**
	 * Add hooks.
	 */
	public static function init() {
		add_filter( 'newspack_blocks_homepage_posts_block_attributes', [ __CLASS__, 'optimise_homepage_posts_block' ] );
		add_action( 'newspack_theme_before_page_content', [ __CLASS__, 'mark_page_content_rendering_start' ] );
		add_filter( 'newspack_analytics_event_js', [ __CLASS__, 'optimize_js' ] );
		add_filter( 'newspack_ads_frontend_js', [ __CLASS__, 'optimize_js' ] );
		add_filter( 'newspack_ads_lazy_loading_js', [ __CLASS__, 'optimize_js' ] );
	}

	/**
	 * Optimise JS code.
	 *
	 * @param string $js_code JS code.
	 */
	public static function optimize_js( $js_code ) {
		return preg_replace(
			[ '/(<script>|,|\(|\)|\{|\}|;|\&\&)\s+/', '/\s+(<script>|,|\(|\)|\{|\}|;|\&\&)/' ],
			'\1',
			$js_code
		);
	}

	/**
	 * Mark page content rendering start.
	 */
	public static function mark_page_content_rendering_start() {
		self::$is_rendering_page_content = true;
	}

	/**
	 * Turn of image lazy loading for the first two homepage posts blocks rendered on a page.
	 * These first blocks are likely to contain the Largest Contentful Paint image, which should
	 * not be deprioritised by lazy-loading, and should be prioritized with the fetchpriority hint.
	 *
	 * @param array $attributes Block attributes.
	 */
	public static function optimise_homepage_posts_block( $attributes ) {
		if ( self::$is_rendering_page_content ) {
			if ( 0 === self::$current_homepage_posts_block ) {
				$attributes['disableImageLazyLoad'] = true;
				$attributes['fetchPriority']        = 'high';
			}
			self::$current_homepage_posts_block++;
		}
		return $attributes;
	}
}
Performance::init();
