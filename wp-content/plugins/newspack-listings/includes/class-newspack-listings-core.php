<?php
/**
 * Newspack Listings Core.
 *
 * Registers custom post types and metadata.
 *
 * @package Newspack_Listings
 */

namespace Newspack_Listings;

use \Newspack_Listings\Newspack_Listings_Settings as Settings;
use \Newspack_Listings\Utils as Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Main Core class.
 * Sets up CPTs and taxonomies for listings.
 */
final class Newspack_Listings_Core {

	/**
	 * Custom post type slugs for Newspack Listings.
	 */
	const NEWSPACK_LISTINGS_POST_TYPES = [
		'event'       => 'newspack_lst_event',
		'generic'     => 'newspack_lst_generic',
		'marketplace' => 'newspack_lst_mktplce',
		'place'       => 'newspack_lst_place',

	];

	/**
	 * Permalink slugs for Newspack Listings CPTs.
	 */
	const NEWSPACK_LISTINGS_PERMALINK_SLUGS = [
		'event'       => 'events',
		'generic'     => 'items',
		'marketplace' => 'marketplace',
		'place'       => 'places',

	];

	/**
	 * Custom taxonomy slugs for Newspack Listings.
	 */
	const NEWSPACK_LISTINGS_CAT = 'newspack_lst_cat';
	const NEWSPACK_LISTINGS_TAG = 'newspack_lst_tag';

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Listings_Core
	 */
	protected static $instance = null;

	/**
	 * Main Newspack_Listings instance.
	 * Ensures only one instance of Newspack_Listings is loaded or can be loaded.
	 *
	 * @return Newspack_Listings_Core - Main instance.
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
		add_action( 'admin_menu', [ __CLASS__, 'add_plugin_page' ] );
		add_filter( 'parent_file', [ __CLASS__, 'highlight_taxonomy_menu_items' ] );
		add_action( 'init', [ __CLASS__, 'register_post_types' ] );
		add_action( 'init', [ __CLASS__, 'register_taxonomies' ] );
		add_filter( 'single_template', [ __CLASS__, 'set_default_template' ] );
		add_action( 'save_post', [ __CLASS__, 'sync_post_meta' ], 10, 2 );
		add_filter( 'newspack_listings_hide_author', [ __CLASS__, 'hide_author' ] );
		register_activation_hook( NEWSPACK_LISTINGS_FILE, [ __CLASS__, 'activation_hook' ] );
	}

	/**
	 * Add options page.
	 */
	public static function add_plugin_page() {
		// Top-level menu item.
		add_menu_page(
			'Newspack Listings',
			'Listings',
			'edit_posts',
			'newspack-listings',
			'',
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0Ij48cGF0aCBkPSJNMTkgNXYxNEg1VjVoMTRtMS4xLTJIMy45Yy0uNSAwLS45LjQtLjkuOXYxNi4yYzAgLjQuNC45LjkuOWgxNi4yYy40IDAgLjktLjUuOS0uOVYzLjljMC0uNS0uNS0uOS0uOS0uOXpNMTEgN2g2djJoLTZWN3ptMCA0aDZ2MmgtNnYtMnptMCA0aDZ2MmgtNnpNNyA3aDJ2Mkg3em0wIDRoMnYySDd6bTAgNGgydjJIN3oiIGZpbGw9IndoaXRlIi8+PC9zdmc+Cg==',
			35
		);

		// Custom taxonomy menu links.
		add_submenu_page(
			'newspack-listings',
			__( 'Newspack Listings: Categories', 'newspack-listings' ),
			__( 'Listing Categories', 'newspack-listings' ),
			'manage_categories',
			'edit-tags.php?taxonomy=' . self::NEWSPACK_LISTINGS_CAT
		);
		add_submenu_page(
			'newspack-listings',
			__( 'Newspack Listings: Tags', 'newspack-listings' ),
			__( 'Listing Tags', 'newspack-listings' ),
			'manage_categories',
			'edit-tags.php?taxonomy=' . self::NEWSPACK_LISTINGS_TAG
		);

		// Settings menu link.
		add_submenu_page(
			'newspack-listings',
			__( 'Newspack Listings: Site-Wide Settings', 'newspack-listings' ),
			__( 'Settings', 'newspack-listings' ),
			'manage_options',
			'newspack-listings-settings-admin',
			[ '\Newspack_Listings\Newspack_Listings_Settings', 'create_admin_page' ]
		);
	}

	/**
	 * Hack to highlight category/tag menu items.
	 * https://deluxeblogtips.com/move-taxonomy-admin-menu/
	 *
	 * @param string $parent_file The parent file.
	 */
	public static function highlight_taxonomy_menu_items( $parent_file ) {
		if ( get_current_screen()->taxonomy == self::NEWSPACK_LISTINGS_CAT || get_current_screen()->taxonomy == self::NEWSPACK_LISTINGS_TAG ) {
			$parent_file = 'newspack-listings';
		}

		return $parent_file;
	}

	/**
	 * Is the current post a listings post type?
	 *
	 * @param string|null $post_type (Optional) Post type to check. If not given, will use the current global post object.
	 *
	 * @returns Boolean Whether or not the current post type matches one of the listings CPTs.
	 */
	public static function is_listing( $post_type = null ) {
		if ( null === $post_type ) {
			$post_type = get_post_type();
		}

		if ( in_array( $post_type, self::NEWSPACK_LISTINGS_POST_TYPES ) ) {
			return true;
		}

		return false;
	}

	/**
	 * After WP init, register all the necessary post types and blocks.
	 */
	public static function register_post_types() {
		$prefix            = Settings::get_settings( 'permalink_prefix' );
		$default_config    = [
			'has_archive'  => false,
			'public'       => true,
			'show_in_menu' => 'newspack-listings',
			'show_in_rest' => true,
			'show_ui'      => true,
			'supports'     => [ 'editor', 'excerpt', 'title', 'author', 'custom-fields', 'thumbnail' ],
		];
		$post_types_config = [
			'event'       => [
				'labels'   => [
					'name'               => _x( 'Events', 'post type general name', 'newspack-listings' ),
					'singular_name'      => _x( 'Event', 'post type singular name', 'newspack-listings' ),
					'menu_name'          => _x( 'Events', 'admin menu', 'newspack-listings' ),
					'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'newspack-listings' ),
					'add_new'            => _x( 'Add New', 'popup', 'newspack-listings' ),
					'add_new_item'       => __( 'Add New Event', 'newspack-listings' ),
					'new_item'           => __( 'New Event', 'newspack-listings' ),
					'edit_item'          => __( 'Edit Event', 'newspack-listings' ),
					'view_item'          => __( 'View Event', 'newspack-listings' ),
					'all_items'          => __( 'Events', 'newspack-listings' ),
					'search_items'       => __( 'Search Events', 'newspack-listings' ),
					'parent_item_colon'  => __( 'Parent Event:', 'newspack-listings' ),
					'not_found'          => __( 'No events found.', 'newspack-listings' ),
					'not_found_in_trash' => __( 'No events found in Trash.', 'newspack-listings' ),
				],
				'rewrite'  => [ 'slug' => $prefix . '/' . self::NEWSPACK_LISTINGS_PERMALINK_SLUGS['event'] ],
				'template' => [ [ 'newspack-listings/event-dates' ] ],
			],
			'generic'     => [
				'labels'  => [
					'name'               => _x( 'Generic Listings', 'post type general name', 'newspack-listings' ),
					'singular_name'      => _x( 'Listing', 'post type singular name', 'newspack-listings' ),
					'menu_name'          => _x( 'Generic Listings', 'admin menu', 'newspack-listings' ),
					'name_admin_bar'     => _x( 'Listing', 'add new on admin bar', 'newspack-listings' ),
					'add_new'            => _x( 'Add New', 'popup', 'newspack-listings' ),
					'add_new_item'       => __( 'Add New Listing', 'newspack-listings' ),
					'new_item'           => __( 'New Listing', 'newspack-listings' ),
					'edit_item'          => __( 'Edit Listing', 'newspack-listings' ),
					'view_item'          => __( 'View Listing', 'newspack-listings' ),
					'all_items'          => __( 'Generic Listings', 'newspack-listings' ),
					'search_items'       => __( 'Search Listings', 'newspack-listings' ),
					'parent_item_colon'  => __( 'Parent Listing:', 'newspack-listings' ),
					'not_found'          => __( 'No listings found.', 'newspack-listings' ),
					'not_found_in_trash' => __( 'No listings found in Trash.', 'newspack-listings' ),
				],
				'rewrite' => [ 'slug' => $prefix . '/' . self::NEWSPACK_LISTINGS_PERMALINK_SLUGS['generic'] ],
			],
			'marketplace' => [
				'labels'  => [
					'name'               => _x( 'Marketplace', 'post type general name', 'newspack-listings' ),
					'singular_name'      => _x( 'Marketplace Listing', 'post type singular name', 'newspack-listings' ),
					'menu_name'          => _x( 'Marketplace Listings', 'admin menu', 'newspack-listings' ),
					'name_admin_bar'     => _x( 'Marketplace Listing', 'add new on admin bar', 'newspack-listings' ),
					'add_new'            => _x( 'Add New', 'popup', 'newspack-listings' ),
					'add_new_item'       => __( 'Add New Marketplace Listing', 'newspack-listings' ),
					'new_item'           => __( 'New Marketplace Listing', 'newspack-listings' ),
					'edit_item'          => __( 'Edit Marketplace Listing', 'newspack-listings' ),
					'view_item'          => __( 'View Marketplace Listing', 'newspack-listings' ),
					'all_items'          => __( 'Marketplace Listings', 'newspack-listings' ),
					'search_items'       => __( 'Search Marketplace Listings', 'newspack-listings' ),
					'parent_item_colon'  => __( 'Parent Marketplace Listing:', 'newspack-listings' ),
					'not_found'          => __( 'No Marketplace listings found.', 'newspack-listings' ),
					'not_found_in_trash' => __( 'No Marketplace listings found in Trash.', 'newspack-listings' ),
				],
				'rewrite' => [ 'slug' => $prefix . '/' . self::NEWSPACK_LISTINGS_PERMALINK_SLUGS['marketplace'] ],
			],
			'place'       => [
				'labels'  => [
					'name'               => _x( 'Places', 'post type general name', 'newspack-listings' ),
					'singular_name'      => _x( 'Place', 'post type singular name', 'newspack-listings' ),
					'menu_name'          => _x( 'Places', 'admin menu', 'newspack-listings' ),
					'name_admin_bar'     => _x( 'Place', 'add new on admin bar', 'newspack-listings' ),
					'add_new'            => _x( 'Add New', 'popup', 'newspack-listings' ),
					'add_new_item'       => __( 'Add New Place', 'newspack-listings' ),
					'new_item'           => __( 'New Place', 'newspack-listings' ),
					'edit_item'          => __( 'Edit Place', 'newspack-listings' ),
					'view_item'          => __( 'View Place', 'newspack-listings' ),
					'all_items'          => __( 'Places', 'newspack-listings' ),
					'search_items'       => __( 'Search Places', 'newspack-listings' ),
					'parent_item_colon'  => __( 'Parent Place:', 'newspack-listings' ),
					'not_found'          => __( 'No places found.', 'newspack-listings' ),
					'not_found_in_trash' => __( 'No places found in Trash.', 'newspack-listings' ),
				],
				'rewrite' => [ 'slug' => $prefix . '/' . self::NEWSPACK_LISTINGS_PERMALINK_SLUGS['place'] ],
			],
		];

		foreach ( $post_types_config as $post_type_slug => $post_type_config ) {
			$post_type = self::NEWSPACK_LISTINGS_POST_TYPES[ $post_type_slug ];
			$permalink = self::NEWSPACK_LISTINGS_PERMALINK_SLUGS[ $post_type_slug ];

			// Register the post type.
			register_post_type( $post_type, wp_parse_args( $post_type_config, $default_config ) );

			// Register meta fields for this post type.
			$meta_fields = self::get_meta_fields( $post_type );
			foreach ( $meta_fields as $field_name => $meta_field ) {
				register_meta(
					'post',
					$field_name,
					$meta_field['settings']
				);
			}

			// Create a rewrite rule to handle the prefixed permalink.
			add_rewrite_rule( '^' . $prefix . '/' . $permalink . '/([^/]+)/?$', 'index.php?name=$matches[1]&post_type=' . $post_type, 'top' );
		}
	}

	/**
	 * Register custom taxonomies for Listings CPTs.
	 */
	public static function register_taxonomies() {
		$prefix        = Settings::get_settings( 'permalink_prefix' );
		$category_args = [
			'hierarchical'  => true,
			'public'        => true,
			'rewrite'       => false, // phpcs:ignore Squiz.PHP.CommentedOutCode.Found [ 'hierarchical' => true, 'slug' => $prefix . '/category' ]
			'show_in_menu'  => true,
			'show_in_rest'  => true,
			'show_tagcloud' => false,
			'show_ui'       => true,
		];
		$tag_args      = [
			'hierarchical'  => false,
			'public'        => true,
			'rewrite'       => false, // phpcs:ignore Squiz.PHP.CommentedOutCode.Found [ 'slug' => $prefix . '/tag' ],
			'show_in_menu'  => true,
			'show_in_rest'  => true,
			'show_tagcloud' => false,
			'show_ui'       => true,
		];

		// Register the taxonomies for all Listing CPTs.
		$post_types = array_values( self::NEWSPACK_LISTINGS_POST_TYPES );
		register_taxonomy( self::NEWSPACK_LISTINGS_CAT, $post_types, $category_args );
		register_taxonomy( self::NEWSPACK_LISTINGS_TAG, $post_types, $tag_args );

		// Better safe than sorry: https://developer.wordpress.org/reference/functions/register_taxonomy/#more-information.
		foreach ( $post_types as $post_type ) {
			register_taxonomy_for_object_type( self::NEWSPACK_LISTINGS_CAT, $post_type );
			register_taxonomy_for_object_type( self::NEWSPACK_LISTINGS_TAG, $post_type );
		}

		// phpcs:ignore Squiz.PHP.CommentedOutCode.Found self::rewrite_taxonomy_urls();
	}

	/**
	 * Create rewrite rules for prefixed taxonomy URLs.
	 */
	public static function rewrite_taxonomy_urls() {
		$prefix = Settings::get_settings( 'permalink_prefix' );

		add_rewrite_rule( '^' . $prefix . '/category/([^/]+)/?$', 'index.php?' . self::NEWSPACK_LISTINGS_CAT . '=$matches[1]', 'top' );
		add_rewrite_rule( '^' . $prefix . '/tag/([^/]+)/?$', 'index.php?' . self::NEWSPACK_LISTINGS_TAG . '=$matches[1]', 'top' );
	}

	/**
	 * Define and return meta fields for any Newspack Listings CPTs.
	 *
	 * @param string  $post_type Post type to get corresponding meta fields.
	 * @param boolean $field_names_only (Optional) If true, return an array of just the field names without config.
	 * @return array Array of meta fields for the given $post_type.
	 */
	public static function get_meta_fields( $post_type = null, $field_names_only = false ) {
		if ( empty( $post_type ) ) {
			return [];
		}

		$all_meta_fields = [
			'newspack_listings_contact_email'    => [
				'post_types' => [
					self::NEWSPACK_LISTINGS_POST_TYPES['event'],
					self::NEWSPACK_LISTINGS_POST_TYPES['generic'],
					self::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					self::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'label'      => __( 'Contact email address', 'newspack-listings' ),
				'source'     => [
					'blockName' => 'jetpack/email',
					'attr'      => 'email',
				],
				'settings'   => [
					'object_subtype'    => $post_type,
					'default'           => [],
					'description'       => __( 'Email address to contact for this listing.', 'newspack-listings' ),
					'type'              => 'array',
					'sanitize_callback' => 'Utils\sanitize_array',
					'single'            => false,
					'show_in_rest'      => [
						'schema' => [
							'type'  => 'array',
							'items' => [
								'type' => 'string',
							],
						],
					],
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				],
			],
			'newspack_listings_contact_phone'    => [
				'post_types' => [
					self::NEWSPACK_LISTINGS_POST_TYPES['event'],
					self::NEWSPACK_LISTINGS_POST_TYPES['generic'],
					self::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					self::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'label'      => __( 'Contact phone number', 'newspack-listings' ),
				'source'     => [
					'blockName' => 'jetpack/phone',
					'attr'      => 'phone',
				],
				'settings'   => [
					'object_subtype'    => $post_type,
					'default'           => [],
					'description'       => __( 'Phone number to contact for this listing.', 'newspack-listings' ),
					'type'              => 'array',
					'sanitize_callback' => 'Utils\sanitize_array',
					'single'            => false,
					'show_in_rest'      => [
						'schema' => [
							'type'  => 'array',
							'items' => [
								'type' => 'string',
							],
						],
					],
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				],
			],
			'newspack_listings_contact_address'  => [
				'post_types' => [
					self::NEWSPACK_LISTINGS_POST_TYPES['event'],
					self::NEWSPACK_LISTINGS_POST_TYPES['generic'],
					self::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					self::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'label'      => __( 'Contact Address', 'newspack-listings' ),
				'source'     => [ 'blockName' => 'jetpack/address' ],
				'settings'   => [
					'object_subtype'    => $post_type,
					'default'           => [],
					'description'       => __( 'Contact address for this listing.', 'newspack-listings' ),
					'type'              => 'array',
					'sanitize_callback' => 'Utils\sanitize_array',
					'single'            => false,
					'show_in_rest'      => [
						'schema' => [
							'type'  => 'array',
							'items' => [
								'type'       => 'object',
								'properties' => [
									'address'      => [
										'type' => 'string',
									],
									'addressLine2' => [
										'type' => 'string',
									],
									'addressLine3' => [
										'type' => 'string',
									],
									'city'         => [
										'type' => 'string',
									],
									'region'       => [
										'type' => 'string',
									],
									'postal'       => [
										'type' => 'string',
									],
									'country'      => [
										'type' => 'string',
									],
								],
							],
						],
					],
				],
			],
			'newspack_listings_business_hours'   => [
				'post_types' => [
					self::NEWSPACK_LISTINGS_POST_TYPES['event'],
					self::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					self::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'label'      => __( 'Hours of Operation', 'newspack-listings' ),
				'source'     => [
					'blockName' => 'jetpack/business-hours',
					'attr'      => 'days',
				],
				'settings'   => [
					'object_subtype'    => $post_type,
					'default'           => [],
					'description'       => __( 'Hours of operation for this listing.', 'newspack-listings' ),
					'type'              => 'array',
					'sanitize_callback' => 'Utils\sanitize_array',
					'single'            => false,
					'show_in_rest'      => [
						'schema' => [
							'type'  => 'array',
							'items' => [
								'type'       => 'object',
								'properties' => [
									'name'  => [
										'type' => 'string',
									],
									'hours' => [
										'type'  => 'array',
										'items' => [
											'type'       => 'object',
											'properties' => [
												'opening' => [
													'type' => 'string',
												],
												'closing' => [
													'type' => 'string',
												],
											],
										],
									],
								],
							],
						],
					],
				],
			],
			'newspack_listings_locations'        => [
				'post_types' => [
					self::NEWSPACK_LISTINGS_POST_TYPES['event'],
					self::NEWSPACK_LISTINGS_POST_TYPES['generic'],
					self::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					self::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'label'      => __( 'Locations', 'newspack-listings' ),
				'source'     => [
					'blockName' => 'jetpack/map',
					'attr'      => 'points',
				],
				'settings'   => [
					'object_subtype'    => $post_type,
					'default'           => [],
					'description'       => __( 'Geolocation data for this listing.', 'newspack-listings' ),
					'type'              => 'array',
					'sanitize_callback' => 'Utils\sanitize_array',
					'single'            => false,
					'show_in_rest'      => [
						'schema' => [
							'type'  => 'array',
							'items' => [
								'type'       => 'object',
								'properties' => [
									'placeTitle'  => [
										'type' => 'string',
									],
									'title'       => [
										'type' => 'string',
									],
									'caption'     => [
										'type' => 'string',
									],
									'id'          => [
										'type' => 'string',
									],
									'coordinates' => [
										'type'       => 'object',
										'properties' => [
											'latitude'  => [
												'type' => 'number',
											],
											'longitude' => [
												'type' => 'number',
											],
										],
									],
								],
							],
						],
					],
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				],
			],
			'newspack_listings_event_start_date' => [
				'post_types' => [
					self::NEWSPACK_LISTINGS_POST_TYPES['event'],
				],
				'label'      => __( 'Event dates', 'newspack-listings' ),
				'source'     => [
					'blockName' => 'newspack-listings/event-dates',
					'attr'      => 'startDate',
					'single'    => true,
				],
				'settings'   => [
					'object_subtype'    => $post_type,
					'default'           => '',
					'description'       => __( 'Start date for this event.', 'newspack-listings' ),
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'single'            => true,
					'show_in_rest'      => true,
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				],
			],
			'newspack_listings_hide_author'      => [
				'post_types' => [
					self::NEWSPACK_LISTINGS_POST_TYPES['event'],
					self::NEWSPACK_LISTINGS_POST_TYPES['generic'],
					self::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					self::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'label'      => __( 'Hide listing author', 'newspack-listings' ),
				'settings'   => [
					'object_subtype'    => $post_type,
					'default'           => false,
					'description'       => __( 'Hide the author for this listing?', 'newspack-listings' ),
					'type'              => 'boolean',
					'sanitize_callback' => 'rest_sanitize_boolean',
					'single'            => true,
					'show_in_rest'      => true,
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				],
			],
		];

		// Return only the fields that are associated with the given $post_type.
		$matching_fields = array_filter(
			$all_meta_fields,
			function( $meta_field ) use ( $post_type ) {
				return in_array( $post_type, $meta_field['post_types'] );
			}
		);

		if ( false === $field_names_only ) {
			return $matching_fields;
		} else {
			return array_keys( $matching_fields );
		}
	}

	/**
	 * Given a post ID and post type, get values for all corresponding Listings meta fields.
	 *
	 * @param int|null    $post_id (Optional) ID for the listing post.
	 * @param string|null $post_type (Optional) Post type.
	 *
	 * @return array|boolean Post meta data, or false if post given is not a listing.
	 */
	public static function get_meta_values( $post_id = null, $post_type = null ) {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		if ( null === $post_type ) {
			$post_type = get_post_type( $post_id );
		}

		if ( ! self::is_listing( $post_type ) ) {
			return false;
		}

		$meta_fields = self::get_meta_fields( $post_type, true );
		$meta_values = [];

		foreach ( $meta_fields as $meta_field ) {
			$data = get_post_meta( $post_id, $meta_field, true );

			if ( ! empty( $data ) ) {
				$meta_values[ $meta_field ] = $data;
			}
		}

		return $meta_values;
	}

	/**
	 * Sync data from specific content blocks to post meta.
	 * Source blocks for each meta field are set in the meta config above.
	 *
	 * @param int   $post_id ID of the post being created or updated.
	 * @param array $post Post object of the post being created or updated.
	 */
	public static function sync_post_meta( $post_id, $post ) {
		if ( ! self::is_listing( $post->post_type ) ) {
			return;
		}

		$blocks      = parse_blocks( $post->post_content );
		$meta_fields = self::get_meta_fields( $post->post_type );

		foreach ( $meta_fields as $field_name => $meta_field ) {
			$source = isset( $meta_field['source'] ) ? $meta_field['source'] : false;

			if ( $source ) {
				$data_to_sync = Utils\get_data_from_blocks( $blocks, $source );

				/*
				* If there are no blocks matching the source, or only empty, clear all data.
				* This prevents garbage data from persisting if a block is removed
				* after its data has already been saved as post meta.
				*/
				if ( empty( $data_to_sync ) ) {
					delete_post_meta( $post_id, $field_name );
				} else {
					update_post_meta( $post_id, $field_name, $data_to_sync );
				}
			}
		}
	}

	/**
	 * Filter callback to decide whether to show the author for the current singular post.
	 * Can be used from other plugins and/or themes to modify default template behavior.
	 *
	 * @param boolean $hide_author Whether or not to hide the author.
	 * @return boolean If the current post a.) is a listing and b.) has enabled the option to hide author.
	 */
	public static function hide_author( $hide_author = false ) {
		$post_id = get_the_ID();

		if ( self::is_listing() && ! empty( get_post_meta( $post_id, 'newspack_listings_hide_author', true ) ) ) {
			return true;
		}

		return $hide_author;
	}

	/**
	 * If using a Newspack theme, force single listings pages to use the wide template (sans widget sidebar).
	 *
	 * @param string $template File path of the template to use for the current single post.
	 * @return string Filtered template file path.
	 */
	public static function set_default_template( $template ) {
		if ( self::is_listing() ) {
			$wide_template = str_replace( 'single.php', 'single-wide.php', $template );

			if ( file_exists( $wide_template ) ) {
				$template = $wide_template;

				// Add the single-wide CSS class to the body.
				add_filter(
					'body_class',
					function( $classes ) {
						$classes[] = 'post-template-single-wide';

						return $classes;
					}
				);
			}
		}

		return $template;
	}

	/**
	 * Flush permalinks on plugin activation, ensuring that post types and taxonomies are registered first.
	 */
	public static function activation_hook() {
		self::register_post_types();
		self::register_taxonomies();
		flush_rewrite_rules(); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
	}
}

Newspack_Listings_Core::instance();
