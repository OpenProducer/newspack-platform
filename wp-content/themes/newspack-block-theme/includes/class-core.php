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
	 * The single instance of the class.
	 *
	 * @var Core
	 */
	protected static $instance = null;

	/**
	 * Main Core instance.
	 * Ensures only one instance of Core is loaded or can be loaded.
	 *
	 * @return Core - Main instance.
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
		\add_action( 'after_setup_theme', [ __CLASS__, 'theme_support' ] );
		\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'theme_styles' ] );
		\add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'editor_scripts' ] );
		\add_filter( 'block_type_metadata', [ __CLASS__, 'block_variations' ] );
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
			$metadata['attributes']['placeholder']['default']    = esc_html__( 'Search...', 'newspack-block-theme' );
			$metadata['attributes']['showLabel']['default']      = false;
		}
		if ( $metadata['name'] == 'core/navigation' ) {
			$metadata['attributes']['overlayMenu']['default'] = 'never';
		}
		return $metadata;
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
			'newspack-block-theme-post-meta',
			array(
				'label'       => __( 'Newspack Theme - Post Meta', 'newspack-block-theme' ),
				'description' => __( 'Patterns bundled with the Newspack Block Theme, specifically built for the post meta.', 'newspack-block-theme' ),
			)
		);
	}
}

Core::instance();
