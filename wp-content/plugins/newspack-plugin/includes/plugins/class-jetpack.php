<?php
/**
 * Jetpack integration class.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Jetpack {

	/**
	 * Modules scripts handles.
	 *
	 * @var string[]
	 */
	private static $scripts_handles = [
		'jp-tracks',              // Tracks analytics library.
		'jetpack-instant-search', // Jetpack Instant Search.
		'jetpack-search-widget',  // Jetpack Search widget.
	];

	/**
	 * Default modules
	 *
	 * @var string[]
	 */
	public static $default_active_modules = [
		/** 
		 * Assets CDN
		 *
		 * @link https://jetpack.com/support/site-accelerator/
		 */
		'photon-cdn',
		/** 
		 * Image CDN
		 *
		 * @link https://jetpack.com/support/site-accelerator/
		 */
		'photon',
		/** 
		 * Contact Form
		 *
		 * @link https://jetpack.com/support/contact-form/
		 */
		'contact-form',
		/** 
		 * Brute Force Protection. 
		 *
		 * @link https://jetpack.com/support/protect/
		 */
		'protect',
		/** 
		 * JSON API 
		 *
		 * @link https://jetpack.com/support/json-api/
		 */
		'json-api',
		/** 
		 * Notifications
		 *
		 * @link https://jetpack.com/support/notifications/
		 */
		'notes',
		/**
		 * Stats
		 *
		 * @link https://jetpack.com/support/jetpack-stats/
		 */
		'stats',
		/**
		 * Site Verification
		 *
		 * @link https://jetpack.com/support/site-verification-tools/
		 */
		'verification-tools',
		/**
		 * Carousel
		 *
		 * @link https://jetpack.com/support/carousel/
		 */
		'carousel',
		/**
		 * Copy Post
		 *
		 * @link https://jetpack.com/support/copy-post/
		 */
		'copy-post',
		/**
		 * Extra Sidebar Widgets
		 *
		 * @link https://jetpack.com/support/extra-sidebar-widgets/
		 */
		'widgets',
		/**
		 * Gravatar Hovercards
		 *
		 * @link https://jetpack.com/support/gravatar-hovercards
		 */
		'gravatar-hovercards',
		/**
		 * Social
		 *
		 * @link https://jetpack.com/support/jetpack-social/
		 */
		'publicize',
		/**
		 * Related Posts
		 *
		 * @link https://jetpack.com/support/related-posts/
		 */
		'related-posts',
		/**
		 * Sharing
		 *
		 * @link https://jetpack.com/support/sharing/
		 */
		'sharedaddy',
		/**
		 * Sitemaps
		 *
		 * @link https://jetpack.com/support/sitemaps
		 */
		'sitemaps',
		/**
		 * Tiled Gallery
		 *
		 * @link https://jetpack.com/support/jetpack-blocks/tiled-galleries/
		 */
		'tiled-gallery',
		/**
		 * Widget Visibility
		 *
		 * @link https://jetpack.com/support/widget-visibility/
		 */
		'widget-visibility',
	];

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'jetpack_async_scripts' ], 20 );
		add_filter( 'newspack_amp_plus_sanitized', [ __CLASS__, 'jetpack_modules_amp_plus' ], 10, 2 );
		add_action( 'wp_head', [ __CLASS__, 'fix_instant_search_sidebar_display' ], 10 );
		add_filter( 'jetpack_lazy_images_skip_image_with_attributes', [ __CLASS__, 'skip_lazy_loading_on_feeds' ], 10 );
		add_filter( 'wp_calculate_image_srcset', [ __CLASS__, 'filter_srcset_array' ], 100, 5 );

		// Disables Google Analytics.
		add_filter( 'jetpack_active_modules', [ __CLASS__, 'remove_google_analytics_from_active' ], 10, 2 );
		add_filter( 'jetpack_get_available_modules', [ __CLASS__, 'remove_google_analytics_from_available' ] );

		// Set Jetpack default modules on Newspack setup.
		add_action( 'add_option_newspack_setup_complete', [ __CLASS__, 'set_default_modules' ], 10, 2 );
		add_action( 'update_option_newspack_setup_complete', [ __CLASS__, 'set_default_modules' ], 10, 2 );
	}

	/**
	 * Filters an array of image `srcset` values, adding Photon urls for additional sizes.
	 *
	 * @param array  $sources       An array of image urls and widths.
	 * @param int[]  $size_array    The size array for srcset.
	 * @param string $image_src     The 'src' of the image.
	 * @param array  $image_meta    The image meta.
	 * @param int    $attachment_id The image attachment ID.
	 *
	 * @return array An array of Photon image urls and widths.
	 */
	public static function filter_srcset_array( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		if ( ! class_exists( 'Jetpack' ) || ! \Jetpack::is_module_active( 'photon' ) ) {
			return $sources;
		}
		if ( ! function_exists( 'jetpack_photon_url' ) ) {
			return $sources;
		}

		/**
		 * Filter the additional sizes to add to the srcset.
		 *
		 * @param array $additional_sizes An array of additional sizes to add to the srcset.
		 */
		$additional_sizes = apply_filters( 'newspack_photon_srcset_additional_sizes', [ 370, 400 ] );

		foreach ( $additional_sizes as $w ) {
			if ( isset( $sources[ $w ] ) ) {
				continue;
			}
			$sources[ $w ] = [
				'url'        => \jetpack_photon_url( $image_src, [ 'w' => $w ] ),
				'descriptor' => 'w',
				'value'      => $w,
			];
		}

		return $sources;
	}

	/**
	 * Skip image lazy-loading on RSS feeds.
	 *
	 * @param bool $skip_lazy_loading Whether to skip lazy-loading.
	 * @return @bool Whether to skip lazy-loading.
	 */
	public static function skip_lazy_loading_on_feeds( $skip_lazy_loading ) {
		if ( is_feed() ) {
			return true;
		}
		return $skip_lazy_loading;
	}

	/**
	 * Whether Jetpack modules scripts should be rendered in AMP Plus.
	 *
	 * @return @bool Whether to render scripts.
	 */
	private static function should_amp_plus_modules() {
		if ( defined( 'NEWSPACK_AMP_PLUS_JETPACK_MODULES' ) ) {
			return true === NEWSPACK_AMP_PLUS_JETPACK_MODULES;
		}
		return false;
	}

	/**
	 * Make Jetpack scripts async.
	 */
	public static function jetpack_async_scripts() {
		foreach ( self::$scripts_handles as $handle ) {
			wp_script_add_data( $handle, 'async', true );
		}
	}

	/**
	 * Allow Jetpack modules scripts to be loaded in AMP Plus mode.
	 *
	 * @param bool|null $is_sanitized If null, the error will be handled. If false, rejected.
	 * @param object    $error        The AMP sanitisation error.
	 *
	 * @return bool Whether the error should be rejected.
	 */
	public static function jetpack_modules_amp_plus( $is_sanitized, $error ) {
		if ( ! self::should_amp_plus_modules() ) {
			return $is_sanitized;
		}
		if ( AMP_Enhancements::is_script_attribute_matching_strings( self::$scripts_handles, $error ) ) {
			$is_sanitized = false;
		}

		// Match inline scripts by script text since they don't have IDs.
		if ( AMP_Enhancements::is_script_body_matching_strings(
			[
				'jetpackSearchModuleSorting',  // Jetpack Search module sorting.
				'JetpackInstantSearchOptions', // Jetpack Instant Search options.
			],
			$error
		) ) {
			$is_sanitized = false;
		}
		return $is_sanitized;
	}

	/**
	 * Fix Instant Search Sidebar Display for AMP Plus
	 */
	public static function fix_instant_search_sidebar_display() {
		if ( ! self::should_amp_plus_modules() ) {
			return;
		}
		?>
		<style>
			.jetpack-instant-search__widget-area {
				display: block !important;
			}
		</style>
		<?php
	}

	/**
	 * Disables Google Analytics module. Users will not be able to activate it.
	 *
	 * @param array $modules Array with modules slugs.
	 * @return array
	 */
	public static function remove_google_analytics_from_active( $modules ) {
		return array_diff( $modules, array( 'google-analytics' ) );
	}

	/**
	 * Remove Google Analytics from available modules
	 *
	 * @param array $modules The array of available modules.
	 * @return array
	 */
	public static function remove_google_analytics_from_available( $modules ) {
		if ( isset( $modules['google-analytics'] ) ) {
			unset( $modules['google-analytics'] );
		}
		return $modules;
	}

	/**
	 * Set Jetpack Default Modules on newspack setup complete.
	 *
	 * @param string $old_val_or_opt_name If adding will be opt name. If updating will be old value.
	 * @param string $new_val Value correlated to update/add.
	 * @return void 
	 */
	public static function set_default_modules( string $old_val_or_opt_name, string $new_val ) {
		if ( $new_val === '1' ) {
			update_option( 'jetpack_active_modules', static::$default_active_modules );
		}
	}
}
Jetpack::init();
