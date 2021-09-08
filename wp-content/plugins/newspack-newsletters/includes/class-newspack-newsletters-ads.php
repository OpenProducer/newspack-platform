<?php
/**
 * Newspack Newsletter Ads
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Newsletters Ads Class.
 */
final class Newspack_Newsletters_Ads {
	/**
	 * CPT for Newsletter ads.
	 */
	const NEWSPACK_NEWSLETTERS_ADS_CPT = 'newspack_nl_ads_cpt';

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Newsletters
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Newsletter Ads Instance.
	 * Ensures only one instance of Newspack Ads Instance is loaded or can be loaded.
	 *
	 * @return Newspack Ads Instance - Main instance.
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
		add_action( 'init', [ __CLASS__, 'register_ads_cpt' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'save_post_' . self::NEWSPACK_NEWSLETTERS_ADS_CPT, [ __CLASS__, 'ad_default_fields' ], 10, 3 );
		add_action( 'admin_menu', [ __CLASS__, 'add_ads_page' ] );
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'expiry_date',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_ADS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'position_in_content',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_ADS_CPT,
				'show_in_rest'   => true,
				'type'           => 'integer',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
	}

	/**
	 * Add ads page link.
	 */
	public static function add_ads_page() {
		add_submenu_page(
			'edit.php?post_type=' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
			__( 'Newsletters Ads', 'newspack-newsletters' ),
			__( 'Ads', 'newspack-newsletters' ),
			'edit_others_posts',
			'/edit.php?post_type=' . self::NEWSPACK_NEWSLETTERS_ADS_CPT,
			null,
			2
		);
	}

	/**
	 * Register the custom post type for layouts.
	 */
	public static function register_ads_cpt() {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}

		$labels = [
			'name'                     => _x( 'Newsletter Ads', 'post type general name', 'newspack-newsletters' ),
			'singular_name'            => _x( 'Newsletter Ad', 'post type singular name', 'newspack-newsletters' ),
			'menu_name'                => _x( 'Newsletter Ads', 'admin menu', 'newspack-newsletters' ),
			'name_admin_bar'           => _x( 'Newsletter Ad', 'add new on admin bar', 'newspack-newsletters' ),
			'add_new'                  => _x( 'Add New', 'popup', 'newspack-newsletters' ),
			'add_new_item'             => __( 'Add New Newsletter Ad', 'newspack-newsletters' ),
			'new_item'                 => __( 'New Newsletter Ad', 'newspack-newsletters' ),
			'edit_item'                => __( 'Edit Newsletter Ad', 'newspack-newsletters' ),
			'view_item'                => __( 'View Newsletter Ad', 'newspack-newsletters' ),
			'all_items'                => __( 'All Newsletter Ads', 'newspack-newsletters' ),
			'search_items'             => __( 'Search Newsletter Ads', 'newspack-newsletters' ),
			'parent_item_colon'        => __( 'Parent Newsletter Ads:', 'newspack-newsletters' ),
			'not_found'                => __( 'No Newsletter Ads found.', 'newspack-newsletters' ),
			'not_found_in_trash'       => __( 'No Newsletter Ads found in Trash.', 'newspack-newsletters' ),
			'items_list'               => __( 'Newsletter Ads list', 'newspack-newsletters' ),
			'item_published'           => __( 'Newsletter Ad published', 'newspack-newsletters' ),
			'item_published_privately' => __( 'Newsletter Ad published privately', 'newspack-newsletters' ),
			'item_reverted_to_draft'   => __( 'Newsletter Ad reverted to draft', 'newspack-newsletters' ),
			'item_scheduled'           => __( 'Newsletter Ad scheduled', 'newspack-newsletters' ),
			'item_updated'             => __( 'Newsletter Ad updated', 'newspack-newsletters' ),
		];

		$cpt_args = [
			'public'       => false,
			'labels'       => $labels,
			'show_ui'      => true,
			'show_in_menu' => false,
			'show_in_rest' => true,
			'supports'     => [ 'editor', 'title', 'custom-fields' ],
			'taxonomies'   => [],
		];
		\register_post_type( self::NEWSPACK_NEWSLETTERS_ADS_CPT, $cpt_args );
	}

	/**
	 * Set default fields when Ad is created.
	 *
	 * @param int     $post_id ID of post being saved.
	 * @param WP_POST $post The post being saved.
	 * @param bool    $update True if this is an update, false if a newly created post.
	 */
	public static function ad_default_fields( $post_id, $post, $update ) {
		// Set meta only if this is a newly created post.
		if ( $update ) {
			return;
		}
		update_post_meta( $post_id, 'position_in_content', 100 );
	}
}
Newspack_Newsletters_Ads::instance();
