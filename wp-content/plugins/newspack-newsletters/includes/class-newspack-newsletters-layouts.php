<?php
/**
 * Newspack Newsletter Layouts
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Newsletters Class.
 */
final class Newspack_Newsletters_Layouts {
	/**
	 * CPT for Newsletter layouts.
	 * Name is funky because of 20 character restriction.
	 */
	const NEWSPACK_NEWSLETTERS_LAYOUT_CPT = 'newspack_nl_layo_cpt';

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Newsletters
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Newsletter Layout Instance.
	 * Ensures only one instance of Newspack Layout Instance is loaded or can be loaded.
	 *
	 * @return Newspack Layout Instance - Main instance.
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
		add_action( 'init', [ __CLASS__, 'register_layout_cpt' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
	}

	/**
	 * Register the custom post type for layouts.
	 */
	public static function register_layout_cpt() {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}

		$cpt_args = [
			'public'       => false,
			'show_in_rest' => true,
			'supports'     => [ 'editor', 'title', 'custom-fields' ],
			'taxonomies'   => [],
		];
		\register_post_type( self::NEWSPACK_NEWSLETTERS_LAYOUT_CPT, $cpt_args );
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'font_header',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_LAYOUT_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'font_body',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_LAYOUT_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'background_color',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_LAYOUT_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
	}

	/**
	 * Token replacement for newsletter layouts.
	 *
	 * @param string $content Layout content.
	 * @param array  $extra Associative array of additional tokens to replace.
	 * @return string Content.
	 */
	public static function layout_token_replacement( $content, $extra = [] ) {
		$sitename       = get_bloginfo( 'name' );
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo           = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id, 'medium' )[0] : null;

		$sitename_block = sprintf(
			'<!-- wp:heading {"align":"center","level":1} --><h1 class="has-text-align-center">%s</h1><!-- /wp:heading -->',
			$sitename
		);

		$logo_block = $logo ? sprintf(
			'<!-- wp:image {"align":"center","id":%s,"sizeSlug":"medium"} --><figure class="wp-block-image aligncenter size-medium"><img src="%s" alt="%s" class="wp-image-%s" /></figure><!-- /wp:image -->',
			$custom_logo_id,
			$logo,
			$sitename,
			$custom_logo_id
		) : null;

		$search  = array_merge(
			[
				'__SITENAME__',
				'__LOGO__',
				'__LOGO_OR_SITENAME__',
			],
			array_keys( $extra )
		);
		$replace = array_merge(
			[
				$sitename,
				$logo,
				$logo ? $logo_block : $sitename_block,
			],
			array_values( $extra )
		);
		return str_replace( $search, $replace, $content );
	}

	/**
	 * Get default layouts.
	 */
	public static function get_default_layouts() {
		$layouts_base_path = NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'includes/layouts/';
		$layouts           = [];
		// 1-indexed, because 0 denotes a blank layout.
		$layout_id = 1;
		foreach ( scandir( $layouts_base_path ) as $layout ) {
			if ( strpos( $layout, '.json' ) !== false ) {
				$decoded_layout  = json_decode( file_get_contents( $layouts_base_path . $layout, true ) ); //phpcs:ignore
				$layouts[]      = array(
					'ID'           => $layout_id,
					'post_title'   => $decoded_layout->title,
					'post_content' => self::layout_token_replacement( $decoded_layout->content ),
				);
				$layout_id++;
			}
		}
		return $layouts;
	}
}
Newspack_Newsletters_Layouts::instance();
