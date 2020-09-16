<?php
/**
 * Enhancements and improvements to third-party plugins for AMP compatibility.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Tweaks third-party plugins for better AMP compatibility.
 * When possible it's preferred to contribute AMP fixes downstream to the actual plugin.
 */
class AMP_Enhancements {
	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		// Use local storage instead of an ajax endpoint with WP GDPR Cookie Notice.
		add_filter( 'wp_gdpr_cookie_notice_amp_use_endpoint', '__return_false' );
		add_filter(
			'googlesitekit_amp_gtag_opt',
			function ( $gtag_opt ) {
				foreach ( $gtag_opt['vars']['config'] as &$config ) {
					unset( $config['linker']['domains'] );
				}
				return $gtag_opt;
			}
		);
	}
}
AMP_Enhancements::init();
