<?php
/**
 * Newspack Custom Bylines.
 *
 * @package Newspack
 */

namespace Newspack;

/**
 * Class to handle custom bylines.
 */
class Bylines {
	/**
	 * Meta key for the active flag.
	 *
	 * @var string
	 */
	const META_KEY_ACTIVE = '_newspack_byline_active';

	/**
	 * Meta key for the byline.
	 *
	 * @var string
	 */
	const META_KEY_BYLINE = '_newspack_byline';

	/**
	 * Initializes the class.
	 */
	public static function init() {
		if ( ! self::is_enabled() ) {
			return;
		}
		add_action( 'init', [ __CLASS__, 'register_post_meta' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
		add_filter( 'the_content', [ __CLASS__, 'output_byline_on_post' ] );
	}

	/**
	 * Checks if the feature is enabled.
	 *
	 * True when:
	 * - NEWSPACK_BYLINES_ENABLED is defined and true.
	 *
	 * @return bool True if the feature is enabled, false otherwise.
	 */
	public static function is_enabled() {
		return defined( 'NEWSPACK_BYLINES_ENABLED' ) && NEWSPACK_BYLINES_ENABLED;
	}


	/**
	 * Enqueue block editor scripts and styles.
	 */
	public static function enqueue_block_editor_assets() {
		if ( ! is_admin() || \get_current_screen()->id !== 'post' ) {
			return;
		}
		\wp_enqueue_script(
			'newspack-bylines',
			Newspack::plugin_url() . '/dist/bylines.js',
			[],
			NEWSPACK_PLUGIN_VERSION,
			true
		);
		\wp_localize_script(
			'newspack-bylines',
			'newspackBylines',
			[
				'metaKeyActive' => self::META_KEY_ACTIVE,
				'metaKeyByline' => self::META_KEY_BYLINE,
				'siteUrl'       => \get_site_url(),
			]
		);
		\wp_enqueue_style(
			'newspack-bylines',
			Newspack::plugin_url() . '/dist/bylines.css',
			[],
			NEWSPACK_PLUGIN_VERSION
		);
	}

	/**
	 * Registers custom byline post meta.
	 */
	public static function register_post_meta() {
		\register_post_meta(
			'post',
			self::META_KEY_ACTIVE,
			[
				'default'       => false,
				'description'   => 'Whether custom bylines is enabled for the post.',
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'boolean',
				'auth_callback' => [ __CLASS__, 'auth_callback' ],
			]
		);
		\register_post_meta(
			'post',
			self::META_KEY_BYLINE,
			[
				'default'       => '',
				'description'   => 'A custom byline for the post',
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => [ __CLASS__, 'auth_callback' ],
			]
		);
	}

	/**
	 * Auth callback for custom post meta.
	 *
	 * @return bool True if current user can access, false otherwise.
	 */
	public static function auth_callback() {
		return \current_user_can( 'edit_posts' );
	}

	/**
	 * Outputs the byline on the post.
	 *
	 * @param string $content The post content.
	 *
	 * @return string The post content with the byline prepended.
	 */
	public static function output_byline_on_post( $content ) {
		if ( ! \is_single() || ! \get_post_meta( \get_the_ID(), self::META_KEY_ACTIVE, true ) ) {
			return $content;
		}
		$byline = \get_post_meta( \get_the_ID(), self::META_KEY_BYLINE, true );
		if ( ! $byline ) {
			return $content;
		}
		$byline      = preg_replace( '/<Author id=(\d*)>(\D*)<\/Author>/', '<a href="' . \get_site_url() . '/?author=$1">$2</a>', $byline );
		$byline_html = '<div class="newspack-byline">' . \wp_kses_post( $byline ) . '</div>';
		return $byline_html . $content;
	}
}
Bylines::init();
