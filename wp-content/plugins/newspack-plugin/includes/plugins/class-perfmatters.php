<?php
/**
 * Perfmatters integration class.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Perfmatters {
	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_filter( 'default_option_perfmatters_options', [ __CLASS__, 'set_defaults' ] );
		add_filter( 'option_perfmatters_options', [ __CLASS__, 'set_defaults' ] );
	}

	/**
	 * Scripts to delay spec.
	 */
	private static function scripts_to_delay() {
		return [
			// Newspack.
			'newspack-plugin',
			'newspack-popups',
			'newspack-blocks',
			'newspack-newsletters',
			'newspack-sponsors',
			'newspack-listings',
			'newspack-theme',
			'window.newspack',
			// WordPress.
			'videopress',
			'related-posts',
			'jp-search.js',
			'jetpack-comment',
			'photon.min.js',
			'comment-reply',
			'stats.wp.com',
			// Google Analytics.
			"ga( '",
			"ga('",
			'google-analytics.com/analytics.js',
			'googletag.pubads',
			// Google Tag Manager.
			'/gtm.js',
			'/gtag/js',
			'gtag(',
			'/gtm-',
			'/gtm.',
			// Advertising.
			'adsbygoogle.js',
			'ai_insert_code',
			'doubleclick.net',
			// Facebook.
			'fbevents.js',
			'fbq(',
			'/busting/facebook-tracking/',
			// Twitter.
			'ads-twitter.com',
			// Etc.
			'disqus',
			'stripe.com',
		];
	}

	/**
	 * Stylesheets to exclude from the "Unused CSS" feature.
	 */
	private static function unused_css_excluded_stylesheets() {
		return [
			'donateStreamlined.css',
		];
	}

	/**
	 * URLs to preconnect to.
	 *
	 * @param array $existing_urls Existing URLs to filter out.
	 */
	private static function preconnect_urls( $existing_urls = [] ) {
		return array_filter(
			[
				[
					'url'         => 'https://i0.wp.com',
					'crossorigin' => false,
				],
			],
			function( $url ) use ( $existing_urls ) {
				return ! in_array( $url['url'], $existing_urls );
			}
		);
	}

	/**
	 * Set default options for Perfmatters.
	 *
	 * @param array $options Perfmatters options.
	 */
	public static function set_defaults( $options = [] ) {
		if ( ! isset( $_GET['newspack-perfmatters-defaults'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return $options;
		}
		if ( defined( 'NEWSPACK_IGNORE_PERFMATTERS_DEFAULTS' ) && NEWSPACK_IGNORE_PERFMATTERS_DEFAULTS ) {
			return $options;
		}

		// Basic options.
		$options['disable_emojis']              = true;
		$options['disable_dashicons']           = true;
		$options['remove_global_styles']        = true;
		$options['disable_woocommerce_scripts'] = true;
		// "The cart fragments feature and or AJAX request in WooCommerce is used to update the cart
		// total without refreshing the page."
		// https://perfmatters.io/docs/disable-woocommerce-cart-fragments-ajax/
		$options['disable_woocommerce_cart_fragmentation'] = true;

		// JS deferral.
		if ( ! isset( $options['assets'] ) ) {
			$options['assets'] = [];
		}
		$defer_js_exclusions           = [ 'wp-includes' ];
		$options['assets']['defer_js'] = true;
		if ( isset( $options['assets']['js_exclusions'] ) && is_array( $options['assets']['js_exclusions'] ) ) {
			$options['assets']['js_exclusions'] = array_unique(
				array_merge(
					$options['assets']['js_exclusions'],
					$defer_js_exclusions
				)
			);
		} else {
			$options['assets']['js_exclusions'] = $defer_js_exclusions;
		}
		$options['assets']['defer_jquery'] = true;

		// JS delay.
		$options['assets']['delay_js'] = true;
		if ( isset( $options['assets']['delay_js_inclusions'] ) && is_array( $options['assets']['delay_js_inclusions'] ) ) {
			$options['assets']['delay_js_inclusions'] = array_unique(
				array_merge(
					$options['assets']['delay_js_inclusions'],
					self::scripts_to_delay()
				)
			);
		} else {
			$options['assets']['delay_js_inclusions'] = self::scripts_to_delay();
		}
		$options['assets']['delay_timeout'] = true;

		// Unused CSS.
		$options['assets']['remove_unused_css'] = true;
		if ( isset( $options['assets']['rucss_excluded_stylesheets'] ) && is_array( $options['assets']['rucss_excluded_stylesheets'] ) ) {
			$options['assets']['rucss_excluded_stylesheets'] = array_unique(
				array_merge(
					$options['assets']['rucss_excluded_stylesheets'],
					self::unused_css_excluded_stylesheets()
				)
			);
		} else {
			$options['assets']['rucss_excluded_stylesheets'] = self::unused_css_excluded_stylesheets();
		}

		// Preload.
		if ( ! isset( $options['preload'] ) ) {
			$options['preload'] = [];
		}
		$options['preload']['critical_images'] = '2';
		if ( isset( $options['preload']['preconnect'] ) && is_array( $options['preload']['preconnect'] ) ) {
			$options['preload']['preconnect'] = array_merge(
				$options['preload']['preconnect'],
				self::preconnect_urls( array_column( $options['preload']['preconnect'], 'url' ) )
			);
		} else {
			$options['preload']['preconnect'] = self::preconnect_urls();
		}

		// Lazyload.
		if ( ! isset( $options['lazyload'] ) ) {
			$options['lazyload'] = [];
		}
		$options['lazyload']['lazy_loading_iframes']       = true;
		$options['lazyload']['youtube_preview_thumbnails'] = true;
		$options['lazyload']['image_dimensions']           = true;

		return $options;
	}
}
Perfmatters::init();
