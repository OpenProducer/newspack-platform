<?php
/**
 * Newspack Block Theme core.
 *
 * @package Newspack_Block_Theme
 */

namespace Newspack_Block_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Main Core class.
 * Primary theme functionaltiy.
 */
final class Core {
	/**
	 * Initializer.
	 */
	public static function init() {
		\add_action( 'after_setup_theme', [ __CLASS__, 'theme_support' ] );
		\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'theme_styles' ] );
		\add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'editor_scripts' ] );
		\add_filter( 'block_type_metadata', [ __CLASS__, 'block_variations' ] );
		\add_filter( 'rest_dispatch_request', [ __CLASS__, 'restrict_patterns' ], 12, 3 );
		\add_filter( 'should_load_remote_block_patterns', '__return_false' );
		\add_action( 'init', [ __CLASS__, 'block_pattern_categories' ] );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Newspack Block Theme 1.0
	 *
	 * @return void
	 */
	public static function theme_support() {
		// Enqueue editor styles.
		\add_editor_style( 'style.css' );

		// Make theme available for translation.
		\load_theme_textdomain( 'newspack-block-theme' );

		\add_theme_support( 'post-subtitle' );
	}

	/**
	 * Enqueue styles.
	 *
	 * @since Newspack Block Theme 1.0
	 *
	 * @return void
	 */
	public static function theme_styles() {
		// Register theme stylesheet.
		\wp_register_style(
			'newspack_block_theme-style',
			\get_stylesheet_directory_uri() . '/style.css',
			array(),
			\wp_get_theme()->get( 'Version' )
		);

		// Enqueue theme stylesheet.
		\wp_enqueue_style( 'newspack_block_theme-style' );

		// Strings for translation.
		$newspack_l10n = array(
			'close_search' => esc_html__( 'Close Search', 'newspack-block-theme' ),
			'open_search'  => esc_html__( 'Open Search', 'newspack-block-theme' ),
		);
		if ( wp_script_is( 'jetpack-instant-search', 'enqueued' ) ) {
			$newspack_l10n['jetpack_instant_search'] = 'true';
		}

		// Enqueue front-end JavaScript.
		wp_enqueue_script( 'newspack-main', get_theme_file_uri( '/dist/main.js' ), array(), wp_get_theme()->get( 'Version' ), true );
		wp_localize_script( 'newspack-main', 'newspackScreenReaderText', $newspack_l10n );
	}

	/**
	 * Enqueue editor scripts.
	 */
	public static function editor_scripts() {
		// Enqueue editor JavaScript.
		wp_enqueue_script( 'editor-script', get_theme_file_uri( '/dist/editor.js' ), array( 'wp-blocks', 'wp-dom' ), wp_get_theme()->get( 'Version' ), true );
	}

	/**
	 * Add block variations.
	 *
	 * @since Newspack Block Theme 1.0
	 *
	 * We may be able to replace this with JavaScript; I'm unclear whether isDefault isn't working, or just not working as I expect it to.
	 * See: https://github.com/WordPress/gutenberg/issues/28119
	 *
	 * @param array $metadata Block metadata.
	 * @return array Block metadata.
	 */
	public static function block_variations( $metadata ) {
		if ( $metadata['name'] == 'core/search' ) {
			$metadata['attributes']['buttonPosition']['default'] = 'button-inside';
			$metadata['attributes']['buttonUseIcon']['default']  = true;
			$metadata['attributes']['placeholder']['default']    = esc_html__( 'Search posts, categories, authors, ...', 'newspack-block-theme' );
			$metadata['attributes']['showLabel']['default']      = false;
		}
		if ( $metadata['name'] == 'core/navigation' ) {
			$metadata['attributes']['overlayMenu']['default'] = 'never';
		}
		return $metadata;
	}

	/**
	 * Restricts block editor patterns in the editor by removing support for all patterns from:
	 *   - Dotcom and plugins like Jetpack or WooCommerce
	 *   - Dotorg pattern directory except for theme patterns
	 *
	 * @link https://developer.wordpress.com/docs/developer-tools/block-patterns/disable-all-patterns/
	 *
	 * @since Newspack Block Theme 1.0
	 *
	 * @param mixed           $dispatch_result Dispatch result, will be used if not empty.
	 * @param WP_REST_Request $request Request used to generate the response.
	 * @param string          $route Route matched for the request.
	 * @return mixed Dispatch result.
	 */
	public static function restrict_patterns( $dispatch_result, $request, $route ) {
		// Define the slugs for patterns to whitelist.
		$whitelisted_patterns = [
			'newspack',
			'publisher-media-kit',
		];

		if ( strpos( $route, '/wp/v2/block-patterns/patterns' ) === 0 ) {
			$patterns = \WP_Block_Patterns_Registry::get_instance()->get_all_registered();

			if ( ! empty( $patterns ) ) {
				// Remove theme support for all patterns, except for the whitelisted ones.
				foreach ( $patterns as $pattern ) {
					$is_whitelisted = false;

					// Check if the pattern's name starts with any of the whitelisted slugs.
					foreach ( $whitelisted_patterns as $slug ) {
						if ( strpos( $pattern['name'], $slug ) === 0 ) {
							$is_whitelisted = true;
							break;
						}
					}

					// Unregister the pattern if it is not whitelisted.
					if ( ! $is_whitelisted ) {
						\unregister_block_pattern( $pattern['name'] );
					}
				}

				// Remove theme support for Core patterns from the Dotorg pattern directory.
				\remove_theme_support( 'core-block-patterns' );
			}
		}

		return $dispatch_result;
	}

	/**
	 * Add block pattern categories.
	 *
	 * @since Newspack Block Theme 1.0
	 */
	public static function block_pattern_categories() {
		register_block_pattern_category(
			'newspack-block-theme',
			array(
				'label'       => __( 'Newspack Theme', 'newspack-block-theme' ),
				'description' => __( 'Patterns bundled with the Newspack Block Theme.', 'newspack-block-theme' ),
			)
		);

		register_block_pattern_category(
			'newspack-block-theme-author-bio',
			array(
				'label'       => __( 'Newspack Theme - Author Bio', 'newspack-block-theme' ),
				'description' => __( 'Patterns bundled with the Newspack Block Theme, specifically built for the author biography.', 'newspack-block-theme' ),
			)
		);

		register_block_pattern_category(
			'newspack-block-theme-columns',
			array(
				'label'       => __( 'Newspack Theme - Columns', 'newspack-block-theme' ),
				'description' => __( 'Patterns bundled with the Newspack Block Theme, specifically built for the columns to perfectly fit the grid.', 'newspack-block-theme' ),
			)
		);

		register_block_pattern_category(
			'newspack-block-theme-post-header',
			array(
				'label'       => __( 'Newspack Theme - Post Header', 'newspack-block-theme' ),
				'description' => __( 'Patterns bundled with the Newspack Block Theme, specifically built for the post header.', 'newspack-block-theme' ),
			)
		);

		register_block_pattern_category(
			'newspack-block-theme-post-meta',
			array(
				'label'       => __( 'Newspack Theme - Post Meta', 'newspack-block-theme' ),
				'description' => __( 'Patterns bundled with the Newspack Block Theme, specifically built for the post meta.', 'newspack-block-theme' ),
			)
		);
	}
}

Core::init();
