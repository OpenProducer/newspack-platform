<?php
/**
 * Newspack Newsletter Editor
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Newsletters Editor Class.
 * Everything needed to turn the Post editor into a Newsletter editor.
 */
final class Newspack_Newsletters_Editor {

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Newsletters_Editor
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Newsletter Editor Instance.
	 * Ensures only one instance of Newspack Editor Instance is loaded or can be loaded.
	 *
	 * @return Newspack Editor Instance - Main instance.
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
		add_action( 'the_post', [ __CLASS__, 'strip_editor_modifications' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
		add_filter( 'allowed_block_types', [ __CLASS__, 'newsletters_allowed_block_types' ], 10, 2 );
	}

	/**
	 * Remove all editor enqueued assets besides this plugins' and disable some editor features.
	 * This is to prevent theme styles being loaded in the editor.
	 */
	public static function strip_editor_modifications() {
		if ( ! self::is_editing_newsletter() && ! self::is_editing_newsletter_ad() ) {
			return;
		}

		$enqueue_block_editor_assets_filters = $GLOBALS['wp_filter']['enqueue_block_editor_assets']->callbacks;
		foreach ( $enqueue_block_editor_assets_filters as $index => $filter ) {
			$action_handlers = array_keys( $filter );
			foreach ( $action_handlers as $handler ) {
				if ( __CLASS__ . '::enqueue_block_editor_assets' != $handler && 'newspack_enqueue_scripts' !== $handler ) {
					remove_action( 'enqueue_block_editor_assets', $handler, $index );
				}
			}
		}

		remove_editor_styles();
		add_theme_support( 'editor-gradient-presets', array() );
		add_theme_support( 'disable-custom-gradients' );
	}

	/**
	 * Restrict block types for Newsletter CPT.
	 *
	 * @param array   $allowed_block_types default block types.
	 * @param WP_Post $post the post to consider.
	 */
	public static function newsletters_allowed_block_types( $allowed_block_types, $post ) {
		if ( ! self::is_editing_newsletter() && ! self::is_editing_newsletter_ad() ) {
			return $allowed_block_types;
		}
		return array(
			'core/spacer',
			'core/block',
			'core/group',
			'core/paragraph',
			'core/heading',
			'core/column',
			'core/columns',
			'core/buttons',
			'core/button',
			'core/image',
			'core/separator',
			'core/list',
			'core/quote',
			'core/social-links',
			'newspack-newsletters/posts-inserter',
		);
	}

	/**
	 * Load up common JS/CSS for wizards.
	 */
	public static function enqueue_block_editor_assets() {
		if ( self::is_editing_newsletter() || self::is_editing_newsletter_ad() ) {
			wp_register_style(
				'newspack-newsletters',
				plugins_url( '../dist/editor.css', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/editor.css' )
			);
			wp_style_add_data( 'newspack-newsletters', 'rtl', 'replace' );
			wp_enqueue_style( 'newspack-newsletters' );
		}

		if ( self::is_editing_newsletter_ad() ) {
			\wp_enqueue_script(
				'newspack-newsletters-ads-page',
				plugins_url( '../dist/adsEditor.js', __FILE__ ),
				[ 'wp-components', 'wp-api-fetch' ],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/adsEditor.js' ),
				true
			);
			wp_register_style(
				'newspack-newsletters-ads-page',
				plugins_url( '../dist/adsEditor.css', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/adsEditor.css' )
			);
			wp_style_add_data( 'newspack-newsletters-ads-page', 'rtl', 'replace' );
			wp_enqueue_style( 'newspack-newsletters-ads-page' );
		}

		if ( ! self::is_editing_newsletter() ) {
			return;
		}
		\wp_enqueue_script(
			'newspack-newsletters',
			plugins_url( '../dist/editor.js', __FILE__ ),
			[],
			filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/editor.js' ),
			true
		);
		wp_localize_script(
			'newspack-newsletters',
			'newspack_newsletters_data',
			[
				'is_service_provider_configured' => Newspack_Newsletters::is_service_provider_configured(),
				'service_provider'               => Newspack_Newsletters::service_provider(),
			]
		);
	}

	/**
	 * Is editing a newsletter?
	 */
	public static function is_editing_newsletter() {
		return Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT === get_post_type();
	}

	/**
	 * Is editing a newsletter ad?
	 */
	public static function is_editing_newsletter_ad() {
		return Newspack_Newsletters_Ads::NEWSPACK_NEWSLETTERS_ADS_CPT === get_post_type();
	}
}
Newspack_Newsletters_Editor::instance();
