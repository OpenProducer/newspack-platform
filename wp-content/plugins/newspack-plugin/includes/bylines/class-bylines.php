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

	const AVATAR_ARGS = array(
		'img'      => array(
			'class'  => true,
			'src'    => true,
			'alt'    => true,
			'width'  => true,
			'height' => true,
			'data-*' => true,
			'srcset' => true,
		),
		'noscript' => array(),
	);

	/**
	 * Initializes the class.
	 */
	public static function init() {
		if ( ! self::is_enabled() ) {
			return;
		}
		add_action( 'init', [ __CLASS__, 'register_post_meta' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
		add_filter( 'pre_newspack_posted_by', [ __CLASS__, 'output_byline_on_post' ] );
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
				'metaKeyActive'             => self::META_KEY_ACTIVE,
				'metaKeyByline'             => self::META_KEY_BYLINE,
				'siteUrl'                   => \get_site_url(),
				'is_co_authors_plus_active' => is_plugin_active( 'co-authors-plus/co-authors-plus.php' ),
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
	 * Return the author in use into the custom byline.
	 *
	 * @return array $authors  Array of authors.
	 */
	public static function authors_on_byline() {
		global $coauthors_plus;
		$authors = [];
		$byline_is_active = \get_post_meta( \get_the_ID(), self::META_KEY_ACTIVE, true );
		$byline = \get_post_meta( \get_the_ID(), self::META_KEY_BYLINE, true );

		if ( ! $byline_is_active || ! $byline ) {
			return [];
		}

		$author_ids = self::extract_author_ids_from_shortcode( $byline );

		foreach ( $author_ids as $author_id ) {
			$authors[] = $coauthors_plus->get_coauthor_by( 'user_nicename', get_the_author_meta( 'user_nicename', $author_id ) );
		}

		return $authors;
	}

	/**
	 * Outputs the byline on the post.
	 *
	 * @return false|string The post content with the byline prepended.
	 */
	public static function output_byline_on_post() {
		$byline_is_active = \get_post_meta( \get_the_ID(), self::META_KEY_ACTIVE, true );
		$byline = \get_post_meta( \get_the_ID(), self::META_KEY_BYLINE, true );

		if ( ! $byline_is_active || ! $byline ) {
			return false;
		}

		$byline      = self::get_authors_avatars( $byline ) . self::replace_author_shortcodes( $byline );
		$byline_html = \wp_kses_post( $byline );

		return $byline_html;
	}

	/**
	 * Replace author shortcodes on byline by HTML output.
	 *
	 * @param string $byline  Byline with author shortcodes on it.
	 */
	public static function replace_author_shortcodes( $byline ) {
		return '<span class="byline">' . preg_replace_callback(
			'/\[Author id=(\d+)\](.*?)\[\/Author\]/',
			function( $matches ) {
				$author_id = $matches[1];

				return sprintf(
					/* translators: 1: Author avatar. 2: author link. */
					'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
					esc_url( get_author_posts_url( $author_id ) ),
					esc_html( get_the_author_meta( 'display_name', $author_id ) )
				);
			},
			$byline
		) . '</span>';
	}

	/**
	 * Return author avatars for authors present in the byline.
	 *
	 * @param string $byline  Byline with author shortcodes on it.
	 */
	public static function get_authors_avatars( $byline ) {
		global $coauthors_plus;

		$author_ids = self::extract_author_ids_from_shortcode( $byline );
		$avatars = '';

		foreach ( $author_ids as $author_id ) {
			$author = $coauthors_plus->get_coauthor_by( 'user_nicename', get_the_author_meta( 'user_nicename', $author_id ) );

			// avatar_img_tag is a property added by Newspack Network plugin to distributed posts.
			$author_avatar = $author->avatar_img_tag ?? coauthors_get_avatar( $author, 80 );

			$avatars .= '<span class="author-avatar">' . wp_kses( $author_avatar, self::AVATAR_ARGS ) . '</span>';
		}

		return $avatars;
	}

	/**
	 * Return all author ID's from shortcode.
	 *
	 * @param string $byline  Byline with author shortcodes on it.
	 */
	public static function extract_author_ids_from_shortcode( $byline ) {
		preg_match_all( '/\[Author id=(\d+)\]/', $byline, $matches );
		return array_map( 'intval', $matches[1] );
	}
}
Bylines::init();
