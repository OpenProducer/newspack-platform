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
		add_filter( 'pre_newspack_posted_by', [ __CLASS__, 'pre_newspack_posted_by' ] );
		add_filter( 'newspack_blocks_post_authors', [ __CLASS__, 'newspack_blocks_post_authors' ] );
		add_filter( 'newspack_blocks_post_byline', [ __CLASS__, 'newspack_blocks_post_byline' ] );

		// Newspack Network compatibility.
		add_filter( 'newspack_network_distributed_post_meta', [ __CLASS__, 'newspack_network_distributed_post_meta' ], 10, 2 );
		add_action( 'newspack_network_incoming_post_inserted', [ __CLASS__, 'newspack_network_incoming_post_inserted' ], 10, 3 );
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
	 * Get the post custom byline HTML markup.
	 *
	 * @param bool $include_avatars Whether to include avatars in the markup.
	 *
	 * @return false|string The post custom byline HTML markup or false if not available.
	 */
	public static function get_post_byline_html( $include_avatars = true ) {
		$byline_is_active = \get_post_meta( \get_the_ID(), self::META_KEY_ACTIVE, true );
		if ( ! $byline_is_active ) {
			return false;
		}

		$byline = \get_post_meta( \get_the_ID(), self::META_KEY_BYLINE, true );
		if ( ! $byline ) {
			return false;
		}

		$byline_html = self::replace_author_shortcodes( $byline );
		if ( $include_avatars ) {
			$byline_html = self::get_authors_avatars( $byline ) . $byline_html;
		}
		return $byline_html;
	}

	/**
	 * Short-circuit the "posted by" text to render the custom byline.
	 *
	 * @return string
	 */
	public static function pre_newspack_posted_by() {
		$byline = self::get_post_byline_html();
		if ( ! $byline ) {
			return false;
		}
		return '<span class="byline">' . wp_kses_post( $byline ) . '</span>';
	}

	/**
	 * Replace author shortcodes on byline for HTML markup.
	 *
	 * @param string $byline Byline with author shortcodes on it.
	 *
	 * @return string
	 */
	public static function replace_author_shortcodes( $byline ) {
		return preg_replace_callback(
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
		);
	}

	/**
	 * Return author avatars for authors present in the byline.
	 *
	 * @param string $byline Byline with author shortcodes on it.
	 *
	 * @return string
	 */
	public static function get_authors_avatars( $byline ) {
		$author_ids = self::extract_author_ids_from_shortcode( $byline );
		$avatars = '';

		foreach ( $author_ids as $author_id ) {
			$avatars .= '<span class="author-avatar">' . get_avatar( $author_id ) . '</span>';
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

	/**
	 * Return post authors according to the byline.
	 *
	 * @param int $post_id The post ID.
	 *
	 * @return array $authors The authors.
	 */
	public static function get_post_byline_authors( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = \get_the_ID();
		}

		$byline_is_active = \get_post_meta( $post_id, self::META_KEY_ACTIVE, true );
		if ( ! $byline_is_active ) {
			return [];
		}

		$byline = \get_post_meta( $post_id, self::META_KEY_BYLINE, true );
		if ( ! $byline ) {
			return [];
		}

		$author_ids = self::extract_author_ids_from_shortcode( $byline );
		return array_map(
			function( $author_id ) {
				return get_user_by( 'id', $author_id );
			},
			$author_ids
		);
	}

	/**
	 * Filter Newspack Blocks Authors.
	 *
	 * @param object[] $authors The authors.
	 *
	 * @return object[] $authors The authors.
	 */
	public static function newspack_blocks_post_authors( $authors ) {
		if ( ! self::is_enabled() ) {
			return $authors;
		}

		$byline_authors = self::get_post_byline_authors();
		if ( empty( $byline_authors ) ) {
			return $authors;
		}

		$authors = [];
		foreach ( $byline_authors as $author ) {
			$authors[] = (object) [
				'ID'            => $author->ID,
				'avatar'        => get_avatar( $author->ID, 48 ),
				'url'           => get_author_posts_url( $author->ID ),
				'user_nicename' => $author->user_nicename,
				'display_name'  => $author->display_name,
			];
		}

		return $authors;
	}

	/**
	 * Filter Newspack Blocks Byline.
	 *
	 * @param string $byline The byline.
	 *
	 * @return string $byline The byline.
	 */
	public static function newspack_blocks_post_byline( $byline ) {
		if ( ! self::is_enabled() ) {
			return $byline;
		}

		$custom_byline = self::get_post_byline_html( false );

		if ( ! $custom_byline ) {
			return $byline;
		}

		return $custom_byline;
	}

	/**
	 * Filters the post meta data for distribution and add a new method with a mapping of author IDs to author emails.
	 *
	 * @param array   $meta The post meta data.
	 * @param WP_Post $post The post object.
	 */
	public static function newspack_network_distributed_post_meta( $meta, $post ) {
		$byline_is_active = \get_post_meta( \get_the_ID(), self::META_KEY_ACTIVE, true );
		if ( ! $byline_is_active ) {
			return $meta;
		}

		$byline = \get_post_meta( $post->ID, self::META_KEY_BYLINE, true );

		$author_ids = self::extract_author_ids_from_shortcode( $byline );

		if ( empty( $author_ids ) ) {
			return $meta;
		}

		$mapping = [];
		// create a mapping of author IDs to author emails.
		foreach ( $author_ids as $author_id ) {
			$mapping[ $author_id ] = get_the_author_meta( 'user_email', $author_id );
		}

		$meta['_newspack_byline_network_authors'] = [ wp_json_encode( $mapping ) ];

		return $meta;
	}

	/**
	 * After an incoming post is inserted, update the IDs with the IDs in the target site, based on the mapping that was sent.
	 *
	 * @param int   $post_id   The post ID.
	 * @param bool  $is_linked Whether the post is linked.
	 * @param array $payload   The post payload.
	 */
	public static function newspack_network_incoming_post_inserted( $post_id, $is_linked, $payload ) {
		if ( ! $is_linked ) {
			return;
		}

		$byline = get_post_meta( $post_id, self::META_KEY_BYLINE, true );

		$mapping = get_post_meta( $post_id, '_newspack_byline_network_authors', true );

		$mapping = json_decode( $mapping, true );

		if ( empty( $mapping ) ) {
			return;
		}

		foreach ( $mapping as $author_id => $author_email ) {
			$local_user = get_user_by( 'email', $author_email );
			if ( ! $local_user ) {
				continue;
			}
			$byline = str_replace( 'id=' . $author_id, 'id=' . $local_user->ID, $byline );
		}

		update_post_meta( $post_id, self::META_KEY_BYLINE, $byline );
	}
}
Bylines::init();
