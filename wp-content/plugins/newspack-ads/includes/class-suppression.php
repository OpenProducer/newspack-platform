<?php
/**
 * Newspack Ads Ad Suppression
 *
 * @package Newspack
 */

namespace Newspack_Ads;

use Newspack_Ads\Core;
use Newspack_Ads\Providers\GAM_Model;

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Ads Ad Suppression Class.
 */
final class Suppression {

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_suppression_meta' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
	}

	/**
	 * Register suppression meta field for post.
	 */
	public static function register_suppression_meta() {
		\register_post_meta(
			'',
			'newspack_ads_suppress_ads',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'boolean',
			]
		);
	}

	/**
	 * Enqueue block editor ad suppression assets.
	 */
	public static function enqueue_block_editor_assets() {
		if ( 'post' === get_current_screen()->post_type || 'page' === get_current_screen()->post_type ) {
			wp_enqueue_script( 'newspack-ads-suppress-ads', Core::plugin_url( 'dist/suppress-ads.js' ), [], NEWSPACK_ADS_VERSION, true );
		}
	}

	/**
	 * Get whether ads should be displayed on a screen.
	 *
	 * @param int $post_id Post ID to check (optional, default: current post).
	 *
	 * @return bool
	 */
	public static function should_show_ads( $post_id = null ) {
		$should_show = true;

		if ( is_404() ) {
			$should_show = false;
		}
  
		if ( is_singular() ) {
			if ( null === $post_id ) {
				$post_id = get_the_ID();
			}
  
			if ( get_post_meta( $post_id, 'newspack_ads_suppress_ads', true ) ) {
				$should_show = false;
			}
		}
  
		$global_suppression_config = GAM_Model::get_suppression_config();
		if ( true === $global_suppression_config['tag_archive_pages'] ) {
			if ( is_tag() ) {
				$should_show = false;
			}
		} elseif ( ! empty( $global_suppression_config['specific_tag_archive_pages'] ) ) {
			$suppressed_tags = $global_suppression_config['specific_tag_archive_pages'];
			foreach ( $suppressed_tags as $tag_id ) {
				if ( is_tag( $tag_id ) ) {
					$should_show = false;
				}
			}
		}
  
		if ( true === $global_suppression_config['category_archive_pages'] ) {
			if ( is_category() ) {
				$should_show = false;
			}
		} elseif ( ! empty( $global_suppression_config['specific_category_archive_pages'] ) ) {
			$suppressed_categories = $global_suppression_config['specific_category_archive_pages'];
			foreach ( $suppressed_categories as $category_id ) {
				if ( is_category( $category_id ) ) {
					$should_show = false;
				}
			}
		}
		if ( is_author() && true === $global_suppression_config['author_archive_pages'] ) {
			$should_show = false;
		}
  
		return apply_filters( 'newspack_ads_should_show_ads', $should_show, $post_id );
	}
}
Suppression::init();
