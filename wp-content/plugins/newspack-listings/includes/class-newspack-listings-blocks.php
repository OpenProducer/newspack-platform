<?php
/**
 * Newspack Listings Blocks.
 *
 * Custom Gutenberg Blocks for Newspack Listings.
 *
 * @package Newspack_Listings
 */

namespace Newspack_Listings;

use \Newspack_Listings\Newspack_Listings_Core as Core;

defined( 'ABSPATH' ) || exit;

/**
 * Blocks class.
 * Sets up custom blocks for listings.
 */
final class Newspack_Listings_Blocks {
	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Listings_Blocks
	 */
	protected static $instance = null;

	/**
	 * Main Newspack_Listings_Blocks instance.
	 * Ensures only one instance of Newspack_Listings_Blocks is loaded or can be loaded.
	 *
	 * @return Newspack_Listings_Blocks - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'manage_editor_assets' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'custom_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'custom_styles' ] );
		add_action( 'init', [ __CLASS__, 'manage_view_assets' ] );
	}

	/**
	 * Enqueue editor assets.
	 */
	public static function manage_editor_assets() {
		wp_enqueue_script(
			'newspack-listings-editor',
			NEWSPACK_LISTINGS_URL . 'dist/editor.js',
			[],
			NEWSPACK_LISTINGS_VERSION,
			true
		);

		$total_count = 0;
		$post_type   = get_post_type();
		$post_types  = [];

		foreach ( Core::NEWSPACK_LISTINGS_POST_TYPES as $label => $name ) {
			$post_count           = wp_count_posts( $name )->publish;
			$total_count          = $total_count + $post_count;
			$post_types[ $label ] = [
				'name'             => $name,
				'show_in_inserter' => 0 < $post_count,
			];
		}

		wp_localize_script(
			'newspack-listings-editor',
			'newspack_listings_data',
			[
				'post_type_label' => get_post_type_object( $post_type )->labels->singular_name,
				'post_type'       => $post_type,
				'post_types'      => $post_types,
				'taxonomies'      => [
					'category' => Core::NEWSPACK_LISTINGS_CAT,
					'tag'      => Core::NEWSPACK_LISTINGS_TAG,
				],

				// If we don't have ANY listings that can be added to a list yet, alert the editor so we can show messaging.
				'no_listings'     => 0 === $total_count,
				'date_format'     => get_option( 'date_format' ),
				'time_format'     => get_option( 'time_format' ),
			]
		);

		wp_register_style(
			'newspack-listings-editor',
			plugins_url( '../dist/editor.css', __FILE__ ),
			[],
			NEWSPACK_LISTINGS_VERSION
		);
		wp_style_add_data( 'newspack-listings-editor', 'rtl', 'replace' );
		wp_enqueue_style( 'newspack-listings-editor' );
	}

	/**
	 * Enqueue front-end assets.
	 */
	public static function manage_view_assets() {
		// Do nothing in editor environment.
		if ( is_admin() ) {
			return;
		}

		$src_directory  = NEWSPACK_LISTINGS_PLUGIN_FILE . 'src/blocks/';
		$dist_directory = NEWSPACK_LISTINGS_PLUGIN_FILE . 'dist/';
		$iterator       = new \DirectoryIterator( $src_directory );

		foreach ( $iterator as $block_directory ) {
			if ( ! $block_directory->isDir() || $block_directory->isDot() ) {
				continue;
			}
			$type = $block_directory->getFilename();

			/* If view.php is found, include it and use for block rendering. */
			$view_php_path = $src_directory . $type . '/view.php';
			if ( file_exists( $view_php_path ) ) {
				include_once $view_php_path;
				continue;
			}

			/* If view.php is missing but view Javascript file is found, do generic view asset loading. */
			$view_js_path = $dist_directory . $type . '/view.js';
			if ( file_exists( $view_js_path ) ) {
				register_block_type(
					"newspack-listings/{$type}",
					array(
						'render_callback' => function( $attributes, $content ) use ( $type ) {
							Newspack_Blocks::enqueue_view_assets( $type );
							return $content;
						},
					)
				);
			}
		}
	}

	/**
	 * Enqueue custom scripts for Newspack Listings front-end components.
	 */
	public static function custom_scripts() {
		if ( ! Utils\is_amp() ) {
			wp_register_script(
				'newspack-listings',
				NEWSPACK_LISTINGS_URL . 'dist/assets.js',
				[],
				NEWSPACK_LISTINGS_VERSION,
				true
			);

			wp_enqueue_script( 'newspack-listings' );
		}
	}

	/**
	 * Enqueue custom styles for Newspack Listings front-end components.
	 */
	public static function custom_styles() {
		if ( ! is_admin() ) {
			wp_register_style(
				'newspack-listings-styles',
				NEWSPACK_LISTINGS_URL . 'dist/assets.css',
				[],
				NEWSPACK_LISTINGS_VERSION
			);

			wp_enqueue_style( 'newspack-listings-styles' );
		}
	}
}

Newspack_Listings_Blocks::instance();
