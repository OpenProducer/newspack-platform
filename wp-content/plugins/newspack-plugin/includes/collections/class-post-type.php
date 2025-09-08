<?php
/**
 * Collections Post Type handler.
 *
 * @package Newspack
 */

namespace Newspack\Collections;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/class-collection-meta.php';

/**
 * Handles the Collections custom post type and related operations.
 */
class Post_Type {
	use Traits\Hook_Manager;
	use Traits\Registration_Manager;

	/**
	 * Post type for Collections.
	 *
	 * @var string
	 */
	private const POST_TYPE = 'newspack_collection';

	/**
	 * Order column name (using default WP menu order column).
	 *
	 * @var string
	 */
	private const ORDER_COLUMN_NAME = 'menu_order';

	/**
	 * Get the hooks for collection custom post type operations.
	 * Same structure as the add_action() parameters.
	 *
	 * @return array {
	 *     Array of hooks with the same structure as the add_action() parameters.
	 *
	 *     @type string   $hook
	 *     @type callable $callback
	 *     @type int      $priority
	 *     @type int      $accepted_args
	 * }
	 */
	protected static function get_hooks() {
		return [
			[ 'save_post_' . self::get_post_type(), [ Sync::class, 'handle_post_save' ], 10, 3 ],
			[ 'before_delete_post', [ Sync::class, 'handle_post_deleted' ] ],
			[ 'wp_trash_post', [ Sync::class, 'handle_post_trashed' ] ],
			[ 'untrashed_post', [ Sync::class, 'handle_post_untrashed' ] ],
		];
	}

	/**
	 * Get the post type for the Collections.
	 *
	 * @return string The post type.
	 */
	public static function get_post_type() {
		return self::POST_TYPE;
	}

	/**
	 * Get the translated column heading.
	 *
	 * @return string The translated column heading.
	 */
	public static function get_order_column_heading() {
		return __( 'Order', 'newspack-plugin' );
	}

	/**
	 * Initialize the post type handler.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'newspack_collections_before_flush_rewrites', [ __CLASS__, 'update_registration' ] );
		add_action( 'current_screen', [ __CLASS__, 'output_collection_meta_data_for_admin_scripts' ] );
		add_action( 'manage_' . self::get_post_type() . '_posts_columns', [ __CLASS__, 'add_order_column' ] );
		add_action( 'manage_' . self::get_post_type() . '_posts_custom_column', [ __CLASS__, 'display_order_column' ], 10, 2 );
		add_filter( 'manage_edit-' . self::get_post_type() . '_sortable_columns', [ __CLASS__, 'make_order_column_sortable' ] );
		self::register_hooks();
		Collection_Meta::init();
	}

	/**
	 * Register the Collections custom post type.
	 */
	public static function register_post_type() {
		$labels = [
			'name'               => _x( 'Collections', 'collection post type general name', 'newspack-plugin' ),
			'singular_name'      => _x( 'Collection', 'collection post type singular name', 'newspack-plugin' ),
			'add_new'            => _x( 'Add New', 'label for add new collection', 'newspack-plugin' ),
			'add_new_item'       => __( 'Add New Collection', 'newspack-plugin' ),
			'edit_item'          => __( 'Edit Collection', 'newspack-plugin' ),
			'new_item'           => __( 'New Collection', 'newspack-plugin' ),
			'view_item'          => __( 'View Collection', 'newspack-plugin' ),
			'view_items'         => __( 'View Collections', 'newspack-plugin' ),
			'search_items'       => __( 'Search Collections', 'newspack-plugin' ),
			'not_found'          => __( 'No collections found.', 'newspack-plugin' ),
			'not_found_in_trash' => __( 'No collections found in Trash.', 'newspack-plugin' ),
			'all_items'          => __( 'All Collections', 'newspack-plugin' ),
			'item_published'     => __( 'Collection published', 'newspack-plugin' ),
			'item_updated'       => __( 'Collection updated', 'newspack-plugin' ),
		];

		$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5.5 17.0009V18.5009H7.5V17.0009H5.5ZM5.5 20.0009C4.67188 20.0009 4 19.329 4 18.5009V17.0009V16.2509V15.5009V8.50092V7.75092V7.00092V5.50092C4 4.6728 4.67188 4.00092 5.5 4.00092C7.33333 4.00092 9.16667 4.00092 11 4.00092C11.6438 4.00092 12.1906 4.40405 12.4062 4.9728C12.5813 4.78217 12.8094 4.63842 13.075 4.56655L14.9344 4.05092C15.7063 3.83842 16.5 4.3103 16.7063 5.10717L17.2688 7.26967L17.4563 7.99467L19.3875 15.4415L19.575 16.1665L19.9469 17.604C20.1531 18.4009 19.6969 19.2197 18.925 19.4322L17.0625 19.9478C16.2906 20.1603 15.4969 19.6884 15.2906 18.8915L14.7281 16.729L14.5406 16.004L12.6125 8.5603L12.5 8.13217V8.50092V15.5009V16.2509V17.0009V18.5009C12.5 19.329 11.8281 20.0009 11 20.0009C9.08318 20.0009 7.41682 20.0009 5.5 20.0009ZM9 18.5009H11V17.0009H9V18.5009ZM7.5 5.50092H5.5V7.00092H7.5V5.50092ZM7.5 8.50092H5.5V15.5009H7.5V8.50092ZM9 7.00092H11V5.50092H9V7.00092ZM11 15.5009V8.50092H9V15.5009H11ZM17.7531 15.1165L16.0094 8.3978L14.2437 8.8853L15.9875 15.604L17.7531 15.1165ZM16.3656 17.0572L16.7375 18.4853L18.5 17.9978C18.5 17.9947 18.5 17.9915 18.5 17.9884V17.9853L18.1344 16.5728L16.3687 17.0603L16.3656 17.0572ZM13.8687 7.43217L15.6344 6.94467L15.2625 5.51655L13.5 6.00405C13.5 6.00717 13.5 6.0103 13.5 6.01655L13.8656 7.42905L13.8687 7.43217Z" /></svg>';

		$icon = sprintf(
			'data:image/svg+xml;base64,%s',
			base64_encode( $svg )
		);

		$args = [
			'label'         => __( 'Collection', 'newspack-plugin' ),
			'labels'        => $labels,
			'description'   => __( 'Grouped content for custom classification.', 'newspack-plugin' ),
			'public'        => true,
			'show_in_rest'  => true,
			'rewrite'       => [
				'slug' => Settings::get_collection_slug(),
			],
			'menu_icon'     => $icon,
			'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes', 'newspack_blocks' ],
			'has_archive'   => true,
			'menu_position' => 6,
		];

		register_post_type( self::get_post_type(), $args );
	}

	/**
	 * Add menu order column to the admin list view.
	 *
	 * @param array $columns The existing columns.
	 * @return array Modified columns array.
	 */
	public static function add_order_column( $columns ) {
		$columns[ self::ORDER_COLUMN_NAME ] = self::get_order_column_heading();
		return $columns;
	}

	/**
	 * Display the menu order value in the custom column.
	 *
	 * @param string $column_name The name of the column.
	 * @param int    $post_id     The post ID.
	 */
	public static function display_order_column( $column_name, $post_id ) {
		if ( self::ORDER_COLUMN_NAME === $column_name ) {
			echo esc_html( get_post_field( self::ORDER_COLUMN_NAME, $post_id ) );
		}
	}

	/**
	 * Make the menu order column sortable.
	 *
	 * @param array $columns The sortable columns.
	 * @return array Modified sortable columns array.
	 */
	public static function make_order_column_sortable( $columns ) {
		$columns[ self::ORDER_COLUMN_NAME ] = self::ORDER_COLUMN_NAME;
		return $columns;
	}

	/**
	 * Output collection meta data for admin scripts.
	 *
	 * @param WP_Screen $current_screen The current screen object.
	 */
	public static function output_collection_meta_data_for_admin_scripts( $current_screen ) {
		if (
			'post' === $current_screen->base &&
			self::get_post_type() === $current_screen->post_type
		) {
			Enqueuer::add_data(
				'collectionPostType',
				[
					'postType'        => self::get_post_type(),
					'metaDefinitions' => Collection_Meta::get_frontend_meta_definitions(),
					'panelTitle'      => __( 'Collection Details', 'newspack-plugin' ),
				]
			);
		}
	}
}
