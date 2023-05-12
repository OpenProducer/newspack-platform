<?php
/**
 * WooCommerce Memberships Block Patterns.
 *
 * @package Newspack
 */

namespace Newspack\WC_Memberships;

/**
 * WooCommerce Memberships Block Patterns class.
 */
class Block_Patterns {
	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_action( 'admin_init', [ __CLASS__, 'register_block_patterns' ] );
	}

	/**
	 * Get block patterns.
	 *
	 * Each pattern content should be a PHP file in the block-patterns directory
	 * named after the pattern slug.
	 *
	 * @return array
	 */
	public static function get_block_patterns() {
		return [
			'registration-wall' => __( 'Registration Wall', 'newspack' ),
			'donation-wall'     => __( 'Donation Wall', 'newspack' ),
			'pay-wall-one-tier' => __( 'Pay Wall with One Tier', 'newspack' ),
		];
	}

	/**
	 * Register block patterns.
	 */
	public static function register_block_patterns() {
		// Bail if Woo Memberships is not active.
		if ( ! class_exists( 'WC_Memberships' ) ) {
			return false;
		}
		\register_block_pattern_category( 'newspack-memberships', [ 'label' => __( 'Memberships', 'newspack' ) ] );
		$patterns = self::get_block_patterns();
		foreach ( $patterns as $slug => $title ) {
			$path = __DIR__ . '/block-patterns/' . $slug . '.php';
			if ( ! file_exists( $path ) ) {
				continue;
			}
			ob_start();
			require $path;
			$content = ob_get_clean();
			if ( empty( $content ) ) {
				continue;
			}
			\register_block_pattern(
				'newspack-memberships/' . $slug,
				[
					'categories'  => [ 'newspack-memberships' ],
					'title'       => $title,
					'description' => _x( 'Invite your reader to become a member before continuing reading the article', 'Block pattern description', 'newspack' ),
					'content'     => $content,
				]
			);
		}
	}
}
Block_Patterns::init();
