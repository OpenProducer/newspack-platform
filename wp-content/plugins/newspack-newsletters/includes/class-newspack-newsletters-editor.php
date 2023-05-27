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
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'the_post', [ __CLASS__, 'strip_editor_modifications' ] );
		add_action( 'after_setup_theme', [ __CLASS__, 'newspack_font_sizes' ], 11 );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
		add_filter( 'allowed_block_types_all', [ __CLASS__, 'newsletters_allowed_block_types' ], 10, 2 );
		add_action( 'rest_post_query', [ __CLASS__, 'maybe_filter_excerpt_length' ], 10, 2 );
		add_action( 'rest_api_init', [ __CLASS__, 'add_newspack_author_info' ] );
		add_filter( 'the_posts', [ __CLASS__, 'maybe_reset_excerpt_length' ] );
		add_filter( 'should_load_remote_block_patterns', [ __CLASS__, 'strip_block_patterns' ] );
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		foreach ( self::get_email_editor_cpts() as $cpt ) {
			\register_meta(
				'post',
				Newspack_Newsletters::EMAIL_HTML_META,
				[
					'object_subtype' => $cpt,
					'show_in_rest'   => [
						'schema' => [
							'context' => [ 'edit' ],
						],
					],
					'type'           => 'string',
					'single'         => true,
					'auth_callback'  => '__return_true',
				]
			);
		}
	}

	/**
	 * Get post types which should be edited using the email editor.
	 */
	private static function get_email_editor_cpts() {
		$email_cpts = [
			Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
			Newspack_Newsletters_Ads::NEWSPACK_NEWSLETTERS_ADS_CPT,
		];
		return apply_filters( 'newspack_newsletters_email_editor_cpts', $email_cpts );
	}

	/**
	 * Is the editor editing an email?
	 */
	private static function is_editing_email() {
		return in_array( get_post_type(), self::get_email_editor_cpts() );
	}

	/**
	 * Get CSS rules for color palette.
	 *
	 * @param string $container_selector Optional selector to prefix as a container to every rule.
	 *
	 * @return string CSS rules.
	 */
	public static function get_color_palette_css( $container_selector = '' ) {
		$rules = [];
		// Add `.has-{color-name}-color` rules for each palette color.
		$color_palette = json_decode( get_option( 'newspack_newsletters_color_palette', false ), true );
		if ( ! empty( $color_palette ) ) {
			foreach ( $color_palette as $color_name => $color_value ) {
				$rules[] = '.has-' . esc_html( $color_name ) . '-color { color: ' . esc_html( $color_value ) . '; }';
			}
		}
		if ( $container_selector ) {
			$container_selector = esc_html( $container_selector );
			$rules              = array_map(
				function( $rule ) use ( $container_selector ) {
					return $container_selector . ' ' . $rule;
				},
				$rules
			);
		}
		return implode( "\n", $rules );
	}

	/**
	 * Remove all editor enqueued assets besides this plugins' and disable some editor features.
	 * This is to prevent theme styles being loaded in the editor.
	 */
	public static function strip_editor_modifications() {
		if ( ! self::is_editing_email() ) {
			return;
		}

		$allowed_actions = [
			__CLASS__ . '::enqueue_block_editor_assets',
			'newspack_enqueue_scripts',
			'wp_enqueue_editor_format_library_assets',
		];

		if ( isset( $GLOBALS['coauthors_plus'] ) ) {
			$hash              = spl_object_hash( $GLOBALS['coauthors_plus'] );
			$allowed_actions[] = $hash . 'enqueue_sidebar_plugin_assets';
		}

		/**
		 * Filters allowed 'enqueue_block_editor_assets' actions inside a newsletter editor.
		 *
		 * @param string[] $allowed_actions Array of allowed actions.
		 */
		$allowed_actions = apply_filters(
			'newspack_newsletters_allowed_editor_actions',
			$allowed_actions
		);

		$enqueue_block_editor_assets_filters = $GLOBALS['wp_filter']['enqueue_block_editor_assets']->callbacks;
		foreach ( $enqueue_block_editor_assets_filters as $index => $filter ) {
			$action_handlers = array_keys( $filter );
			foreach ( $action_handlers as $handler ) {
				if ( ! in_array( $handler, $allowed_actions, true ) ) {
					remove_action( 'enqueue_block_editor_assets', $handler, $index );
				}
			}
		}

		remove_editor_styles();
		add_theme_support( 'editor-gradient-presets', array() );
		add_theme_support( 'disable-custom-gradients' );

		$block_patterns_registry = \WP_Block_Patterns_Registry::get_instance();
		if ( $block_patterns_registry->is_registered( 'core/social-links-shared-background-color' ) ) {
			unregister_block_pattern( 'core/social-links-shared-background-color' );
		}
	}

	/**
	 * Remove Core's Remote Block patterns.
	 *
	 * @param boolean $should_load_remote Whether to load remote block patterns.
	 *
	 * @return boolean Whether to load remote block patterns.
	 */
	public static function strip_block_patterns( $should_load_remote ) {
		if ( self::is_editing_email() ) {
			return false;
		}

		return $should_load_remote;
	}

	/**
	 * Define Editor Font Sizes.
	 */
	public static function newspack_font_sizes() {
		if ( ! self::is_editing_email() ) {
			return;
		}
		add_theme_support(
			'editor-font-sizes',
			[
				[
					'name' => _x( 'Small', 'font size name', 'newspack-newsletters' ),
					'size' => 12,
					'slug' => 'small',
				],
				[
					'name' => _x( 'Normal', 'font size name', 'newspack-newsletters' ),
					'size' => 16,
					'slug' => 'normal',
				],
				[
					'name' => _x( 'Medium', 'font size name', 'newspack-newsletters' ),
					'size' => 16,
					'slug' => 'medium',
				],
				[
					'name' => _x( 'Large', 'font size name', 'newspack-newsletters' ),
					'size' => 24,
					'slug' => 'large',
				],
				[
					'name' => _x( 'Extra Large', 'font size name', 'newspack-newsletters' ),
					'size' => 36,
					'slug' => 'x-large',
				],
				[
					'name' => _x( 'Huge', 'font size name', 'newspack-newsletters' ),
					'size' => 36,
					'slug' => 'huge',
				],
			]
		);
	}

	/**
	 * Restrict block types for Newsletter CPT.
	 *
	 * @param array   $allowed_block_types default block types.
	 * @param WP_Post $post the post to consider.
	 */
	public static function newsletters_allowed_block_types( $allowed_block_types, $post ) {
		if ( ! self::is_editing_email() ) {
			return $allowed_block_types;
		}
		return array(
			'core/spacer',
			'core/block',
			'core/group',
			'core/paragraph',
			'core/embed',
			'core/heading',
			'core/column',
			'core/columns',
			'core/buttons',
			'core/button',
			'core/image',
			'core/separator',
			'core/list',
			'core/list-item',
			'core/quote',
			'core/site-logo',
			'core/site-tagline',
			'core/site-title',
			'core/social-links',
			'core/social-link',
			'newspack-newsletters/posts-inserter',
			'newspack-newsletters/share',
		);
	}

	/**
	 * Load up common JS/CSS for newsletter editor.
	 */
	public static function enqueue_block_editor_assets() {
		if ( self::is_editing_email() ) {
			wp_register_style(
				'newspack-newsletters',
				plugins_url( '../dist/editor.css', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/editor.css' )
			);
			wp_style_add_data( 'newspack-newsletters', 'rtl', 'replace' );
			wp_enqueue_style( 'newspack-newsletters' );

			wp_add_inline_style( 'newspack-newsletters', self::get_color_palette_css( '.editor-styles-wrapper' ) );

			\wp_enqueue_script(
				'newspack-newsletters-editor',
				plugins_url( '../dist/editor.js', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/editor.js' ),
				true
			);

			// Remove the Ads CPT - it does not need MJML handling since ads
			// will be injected into email content before it's converted to MJML.
			$mjml_handling_post_types = array_values( array_diff( self::get_email_editor_cpts(), [ Newspack_Newsletters_Ads::NEWSPACK_NEWSLETTERS_ADS_CPT ] ) );
			$provider                 = Newspack_Newsletters::get_service_provider();
			$conditional_tag_support  = false;
			if ( $provider ) {
				$conditional_tag_support = $provider::get_conditional_tag_support();
			}
			wp_localize_script(
				'newspack-newsletters-editor',
				'newspack_email_editor_data',
				[
					'email_html_meta'          => Newspack_Newsletters::EMAIL_HTML_META,
					'mjml_handling_post_types' => $mjml_handling_post_types,
					'conditional_tag_support'  => $conditional_tag_support,
				]
			);

			do_action( 'newspack_newsletters_enqueue_block_editor_assets' );
		}

		if ( self::is_editing_newsletter_ad() ) {
			\wp_enqueue_script(
				'newspack-newsletters-ads-page',
				plugins_url( '../dist/adsEditor.js', __FILE__ ),
				[ 'wp-components', 'wp-api-fetch' ],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/adsEditor.js' ),
				true
			);
		}

		if ( self::is_editing_newsletter() ) {
			wp_localize_script(
				'newspack-newsletters-editor',
				'newspack_newsletters_data',
				[
					'is_service_provider_configured' => Newspack_Newsletters::is_service_provider_configured(),
					'service_provider'               => Newspack_Newsletters::service_provider(),
					'user_test_emails'               => self::get_current_user_test_emails(),
				]
			);
			wp_register_style(
				'newspack-newsletters-newsletter-editor',
				plugins_url( '../dist/newsletterEditor.css', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/newsletterEditor.css' )
			);
			wp_style_add_data( 'newspack-newsletters-newsletter-editor', 'rtl', 'replace' );
			wp_enqueue_style( 'newspack-newsletters-newsletter-editor' );
			\wp_enqueue_script(
				'newspack-newsletters-newsletter-editor',
				plugins_url( '../dist/newsletterEditor.js', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/newsletterEditor.js' ),
				true
			);
		}

		// If it's a reusable block, register this plugin's blocks.
		if ( 'wp_block' === get_post_type() ) {
			\wp_enqueue_script(
				'newspack-newsletters-editor-blocks',
				plugins_url( '../dist/editorBlocks.js', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/editorBlocks.js' ),
				true
			);
			wp_register_style(
				'newspack-newsletters-editor-blocks',
				plugins_url( '../dist/editorBlocks.css', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/editorBlocks.css' )
			);
			wp_style_add_data( 'newspack-newsletters-editor-blocks', 'rtl', 'replace' );
			wp_enqueue_style( 'newspack-newsletters-editor-blocks' );
		}
	}

	/**
	 * Is editing a newsletter?
	 */
	private static function is_editing_newsletter() {
		return Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT === get_post_type();
	}

	/**
	 * Is editing a newsletter ad?
	 */
	private static function is_editing_newsletter_ad() {
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
	 * Get current user test emails
	 *
	 * @return array List of user defined emails.
	 */
	public static function get_current_user_test_emails() {
		$user_id = get_current_user_id();
		$emails  = get_user_meta( $user_id, 'newspack_nl_test_emails', true );
		if ( ! is_array( $emails ) ) {
			$user_info = get_userdata( $user_id );
			return array( $user_info->user_email );
		}
		return $emails;
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
