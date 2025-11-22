<?php
/**
 * WooCommerce Memberships Block Patterns.
 *
 * @package Newspack
 */

namespace Newspack\Content_Gate;

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
		/**
		 * Filters whether to enqueue the reader auth scripts.
		 *
		 * @param bool $should_enqueue_styles Whether to enqueue the reader auth scripts.
		 */
		if ( ! apply_filters( 'newspack_enqueue_content_gate_block_patterns', true ) ) {
			return false;
		}
		wp_enqueue_style(
			'newspack-content_gate-block-patterns',
			\Newspack\Newspack::plugin_url() . '/dist/content-gate-block-patterns.css',
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
			'registration-card'          => __( 'Registration Card', 'newspack' ),
			'registration-card-compact'  => __( 'Registration Card (Compact)', 'newspack' ),
			'registration-wall'          => __( 'Registration Wall', 'newspack' ),
			'donation-wall'              => __( 'Donation Wall', 'newspack' ),
			'pay-wall-one-tier'          => __( 'Paywall with One Tier', 'newspack' ),
			'pay-wall-one-tier-metering' => __( 'Paywall with One Tier and Metering', 'newspack' ),
			'pay-wall-two-tiers'         => __( 'Paywall with Two Tiers', 'newspack' ),
			'pay-wall-two-tiers-alt'     => __( 'Paywall with Two Tiers (Alt)', 'newspack' ),
			'pay-wall-three-tiers'       => __( 'Paywall with Three Tiers', 'newspack' ),
			'pay-wall-three-tiers-alt'   => __( 'Paywall with Three Tiers (Alt)', 'newspack' ),
		];
	}

	/**
	 * Register block patterns.
	 */
	public static function register_block_patterns() {
		\register_block_pattern_category( 'newspack-content-gate', [ 'label' => __( 'Newspack Content Gate', 'newspack' ) ] );
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
				'newspack-content-gate/' . $slug,
				[
					'categories'  => [ 'newspack-content-gate' ],
					'title'       => $title,
					'description' => _x( 'Invite your reader to become a member before continuing reading the article', 'Block pattern description', 'newspack' ),
					'content'     => $content,
				]
			);
		}
	}
}
Block_Patterns::init();
