<?php
/**
 * PWA management.
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error, \WP_Service_Worker_Caching_Routes;

defined( 'ABSPATH' ) || exit;

/**
 * Manages settings for PWA.
 */
class PWA {

	/**
	 * Add hooks.
	 */
	public static function init() {
		add_action( 'wp_front_service_worker', [ __CLASS__, 'register_caching_routes' ] );
		add_filter( 'wp_service_worker_navigation_caching_strategy', [ __CLASS__, 'caching_strategy' ] );
		add_filter( 'wp_service_worker_navigation_caching_strategy_args', [ __CLASS__, 'caching_strategy_args' ] );
	}

	/**
	 * Check whether everything is configured correctly.
	 *
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
	public static function check_configured() {
		if ( ! is_ssl() ) {
			return new WP_Error( 'newspack_pwa_error', __( 'Site is not served over https. Progressive web app features will not work.', 'newspack' ) );
		}

		if ( ! self::get_site_icon() ) {
			return new WP_Error( 'newspack_pwa_error', __( 'No site icon specified. Visitors will not be able to install the site as an app.', 'newspack' ) );
		}

		return true;
	}

	/**
	 * Retrieve Site Icon.
	 *
	 * @return null|array
	 */
	public static function get_site_icon() {
		$site_icon = get_option( 'site_icon' );
		if ( ! $site_icon ) {
			return null;
		}
		$url = wp_get_attachment_image_src( $site_icon );
		return [
			'id'  => $site_icon,
			'url' => $url[0],
		];
	}

	/**
	 * Update Site Icon
	 *
	 * @param string $site_icon The site icon.
	 */
	public function update_site_icon( $site_icon ) {
		if ( ! empty( $site_icon['id'] ) ) {
			update_option( 'site_icon', intval( $site_icon['id'] ) );
		} else {
			delete_option( 'site_icon' );
		}
	}

	/**
	 * Cache images for offline use.
	 *
	 * @param object $scripts PWA scripts object.
	 */
	public static function register_caching_routes( $scripts ) {
		// Cache images.
		$scripts->caching_routes()->register(
			'.*\.(?:png|gif|jpg|jpeg|svg|webp)(\?.*)?$', // @todo make sure this isn't caching Photon pixel.
			[
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_NETWORK_FIRST,
				'cacheName' => 'images',
				'plugins'   => [
					'expiration' => [
						'maxEntries'    => 60,
						'maxAgeSeconds' => 60 * 60 * 24,
					],
				],
			]
		);

		// Cache theme assets.
		$theme_directory_uri_patterns = [
			preg_quote( trailingslashit( get_template_directory_uri() ), '/' ),
		];
		if ( get_template() !== get_stylesheet() ) {
			$theme_directory_uri_patterns[] = preg_quote( trailingslashit( get_stylesheet_directory_uri() ), '/' );
		}
		$scripts->caching_routes()->register(
			'^(' . implode( '|', $theme_directory_uri_patterns ) . ').*',
			[
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_NETWORK_FIRST,
				'cacheName' => 'theme-assets',
				'plugins'   => [
					'expiration' => [
						'maxEntries' => 25,
					],
				],
			]
		);
	}

	/**
	 * Use network-first caching strategy.
	 *
	 * @param string $strategy Caching strategy.
	 * @return string caching strategy
	 */
	public static function caching_strategy( $strategy ) {
		return WP_Service_Worker_Caching_Routes::STRATEGY_NETWORK_FIRST;
	}

	/**
	 * Cache pages for offline use.
	 *
	 * @param array $args Caching strategy args.
	 * @return array $args
	 */
	public static function caching_strategy_args( $args ) {
		$args['cacheName']                           = 'pages';
		$args['plugins']['expiration']['maxEntries'] = 50;
		return $args;
	}
}
PWA::init();
