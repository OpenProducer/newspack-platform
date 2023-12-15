<?php
/**
 * WooCommerce Memberships Block Patterns.
 *
 * @package Newspack
 */

namespace Newspack\Memberships;

/**
 * WooCommerce Memberships Block Patterns class.
 */
class Block_Patterns {
	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_action( 'admin_init', [ __CLASS__, 'register_block_patterns' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_styles' ] );
	}

	/**
	 * Enqueue styles.
	 */
	public static function enqueue_styles() {
		$should_enqueue_styles = class_exists( 'WC_Memberships' );
		/**
		 * Filters whether to enqueue the reader auth scripts.
		 *
		 * @param bool $should_enqueue_styles Whether to enqueue the reader auth scripts.
		 */
		if ( ! apply_filters( 'newspack_enqueue_memberships_block_patterns', $should_enqueue_styles ) ) {
			return false;
		}
		wp_enqueue_style(
			'newspack-memberships-block-patterns',
			\Newspack\Newspack::plugin_url() . '/dist/memberships-gate-block-patterns.css',
			[],
			NEWSPACK_PLUGIN_VERSION
		);
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
			'registration-wall'          => __( 'Registration Wall', 'newspack' ),
			'donation-wall'              => __( 'Donation Wall', 'newspack' ),
			'pay-wall-one-tier'          => __( 'Pay Wall with One Tier', 'newspack' ),
			'pay-wall-one-tier-metering' => __( 'Pay Wall with One Tier and Metering', 'newspack' ),
			'pay-wall-two-tiers'         => __( 'Pay Wall with Two Tiers', 'newspack' ),
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
