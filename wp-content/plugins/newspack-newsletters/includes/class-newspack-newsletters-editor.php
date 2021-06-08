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
	 * Closure for excerpt filtering that can be added and removed.
	 *
	 * @var newspack_newsletters_excerpt_length_filter
	 */
	public static $newspack_newsletters_excerpt_length_filter = null;

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
		add_action( 'rest_post_query', [ __CLASS__, 'maybe_filter_excerpt_length' ], 10, 2 );
		add_action( 'rest_api_init', [ __CLASS__, 'add_newspack_author_info' ] );
		add_filter( 'the_posts', [ __CLASS__, 'maybe_reset_excerpt_length' ] );
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

		// If it's a reusable block, register this plugin's blocks.
		if ( 'wp_block' === get_post_type() ) {
			\wp_enqueue_script(
				'newspack-newsletters-blocks',
				plugins_url( '../dist/blocks.js', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/blocks.js' ),
				true
			);
			wp_register_style(
				'newspack-newsletters-blocks',
				plugins_url( '../dist/blocks.css', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/blocks.css' )
			);
			wp_style_add_data( 'newspack-newsletters-blocks', 'rtl', 'replace' );
			wp_enqueue_style( 'newspack-newsletters-blocks' );
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
				'email_html_meta'                => Newspack_Newsletters::EMAIL_HTML_META,
				'newsletter_cpt'                 => Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
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

	/**
	 * If excerpt length is set in Post Inserter block attributes, override the site's excerpt length using the setting.
	 *
	 * @param array           $args Request arguments.
	 * @param WP_REST_Request $request The original REST request params.
	 *
	 * @return array Unmodified request args.
	 */
	public static function maybe_filter_excerpt_length( $args, $request ) {
		$params = $request->get_params();

		if ( isset( $params['excerpt_length'] ) ) {
			self::filter_excerpt_length( intval( $params['excerpt_length'] ) );
		}

		return $args;
	}

	/**
	 * Append author info to the posts REST response so we can append Coauthors, if they exist.
	 */
	public static function add_newspack_author_info() {
		/* Add author info source */
		register_rest_field(
			'post',
			'newspack_author_info',
			[
				'get_callback' => [ __CLASS__, 'newspack_get_author_info' ],
				'schema'       => [
					'context' => [
						'edit',
					],
					'type'    => 'array',
				],
			]
		);
	}

	/**
	 * After fetching posts, reset the excerpt length.
	 *
	 * @param array $posts Array of posts.
	 *
	 * @return array Unmodified array of posts.
	 */
	public static function maybe_reset_excerpt_length( $posts ) {
		if ( self::$newspack_newsletters_excerpt_length_filter ) {
			self::remove_excerpt_length_filter();
		}

		return $posts;
	}

	/**
	 * Filter for excerpt length.
	 *
	 * @param int $excerpt_length Excerpt length to set.
	 */
	public static function filter_excerpt_length( $excerpt_length ) {
		// If showing excerpt, filter the length using the block attribute.
		if ( is_int( $excerpt_length ) ) {
			self::$newspack_newsletters_excerpt_length_filter = add_filter(
				'excerpt_length',
				function() use ( $excerpt_length ) {
					return $excerpt_length;
				},
				999
			);
			add_filter( 'wc_memberships_trimmed_restricted_excerpt', [ __CLASS__, 'remove_wc_memberships_excerpt_limit' ], 999 );
		}
	}

	/**
	 * Remove excerpt length filter after newsletters post loop.
	 */
	public static function remove_excerpt_length_filter() {
		remove_filter(
			'excerpt_length',
			self::$newspack_newsletters_excerpt_length_filter,
			999
		);
		remove_filter( 'wc_memberships_trimmed_restricted_excerpt', [ __CLASS__, 'remove_wc_memberships_excerpt_limit' ] );
	}

	/**
	 * Function to override WooCommerce Membership's Excerpt Length filter.
	 *
	 * @return string Current post's original excerpt.
	 */
	public static function remove_wc_memberships_excerpt_limit() {
		$excerpt = get_the_excerpt( get_the_id() );
		return $excerpt;
	}

	/**
	 * Append author data to the REST /posts response, so we can include Coauthors, link and display names.
	 *
	 * @param object $post Post object for the post being returned.
	 * @return object Formatted data for all authors associated with the post.
	 */
	public static function newspack_get_author_info( $post ) {
		$author_data = [];

		if ( function_exists( 'get_coauthors' ) ) {
			$authors = get_coauthors();

			foreach ( $authors as $author ) {
				$author_link = null;
				if ( function_exists( 'coauthors_posts_links' ) ) {
					$author_link = get_author_posts_url( $author->ID, $author->user_nicename );
				}
				$author_data[] = [
					/* Get the author name */
					'display_name' => esc_html( $author->display_name ),
					/* Get the author ID */
					'id'           => $author->ID,
					/* Get the author Link */
					'author_link'  => $author_link,
				];
			}
		} else {
			$author_data[] = [
				/* Get the author name */
				'display_name' => get_the_author_meta( 'display_name', $post['author'] ),
				/* Get the author ID */
				'id'           => $post['author'],
				/* Get the author Link */
				'author_link'  => get_author_posts_url( $post['author'] ),
			];
		}

		/* Return the author data */
		return $author_data;
	}
}
Newspack_Newsletters_Editor::instance();
