<?php
/**
 * Newspack Listings - Sets up shadow taxonomies to associate different post types with each other.
 *
 * @package Newspack_Listings
 */

namespace Newspack_Listings;

use \Newspack_Listings\Newspack_Listings_Core as Core;
use \Newspack_Listings\Utils as Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomies class.
 * Sets up shadow taxonomies for listings.
 */
final class Newspack_Listings_Taxonomies {
	const NEWSPACK_LISTINGS_TAXONOMIES = [
		'event'       => 'newspack_lstngs_evt',
		'generic'     => 'newspack_lstngs_gen',
		'marketplace' => 'newspack_lstngs_mkt',
		'place'       => 'newspack_lstngs_plc',
	];

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Listings_Taxonomies
	 */
	protected static $instance = null;

	/**
	 * Main Newspack_Listings_Taxonomies instance.
	 * Ensures only one instance of Newspack_Listings_Taxonomies is loaded or can be loaded.
	 *
	 * @return Newspack_Listings_Taxonomies - Main instance.
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
		add_action( 'init', [ __CLASS__, 'init' ] );
		add_filter( 'rest_prepare_taxonomy', [ __CLASS__, 'hide_taxonomy_sidebar' ], 10, 2 );
		register_activation_hook( NEWSPACK_LISTINGS_FILE, [ __CLASS__, 'activation_hook' ] );
	}

	/**
	 * After WP init.
	 */
	public static function init() {
		self::register_tax();
		self::create_shadow_relationship();
	}

	/**
	 * Get shadow taxonomy config object.
	 *
	 * @return object Config options for all shadow taxonomies.
	 */
	public static function get_shadow_taxonomy_config() {
		return [
			'event'       => [
				'post_types' => [
					'post',
					'page',
					Core::NEWSPACK_LISTINGS_POST_TYPES['event'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['generic'],
				],
				'config'     => [
					'hierarchical'  => true,
					'public'        => true,
					'rewrite'       => [ 'slug' => self::NEWSPACK_LISTINGS_TAXONOMIES['event'] ],
					'show_in_menu'  => false, // Set to 'true' to show in WP admin for debugging purposes.
					'show_in_rest'  => true,
					'show_tagcloud' => false,
					'show_ui'       => true,
					'labels'        => get_post_type_object( Core::NEWSPACK_LISTINGS_POST_TYPES['event'] )->labels,
				],
			],
			'generic'     => [
				'post_types' => [
					'post',
					'page',
					Core::NEWSPACK_LISTINGS_POST_TYPES['generic'],
				],
				'config'     => [
					'hierarchical'  => true,
					'public'        => true,
					'rewrite'       => [ 'slug' => self::NEWSPACK_LISTINGS_TAXONOMIES['event'] ],
					'show_in_menu'  => false, // Set to 'true' to show in WP admin for debugging purposes.
					'show_in_rest'  => true,
					'show_tagcloud' => false,
					'show_ui'       => true,
					'labels'        => get_post_type_object( Core::NEWSPACK_LISTINGS_POST_TYPES['generic'] )->labels,
				],
			],
			'marketplace' => [
				'post_types' => [
					'post',
					'page',
					Core::NEWSPACK_LISTINGS_POST_TYPES['generic'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
				],
				'config'     => [
					'hierarchical'  => true,
					'public'        => true,
					'rewrite'       => [ 'slug' => self::NEWSPACK_LISTINGS_TAXONOMIES['marketplace'] ],
					'show_in_menu'  => false, // Set to 'true' to show in WP admin for debugging purposes.
					'show_in_rest'  => true,
					'show_tagcloud' => false,
					'show_ui'       => true,
					'labels'        => get_post_type_object( Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'] )->labels,
				],
			],
			'place'       => [
				'post_types' => [
					'post',
					'page',
					Core::NEWSPACK_LISTINGS_POST_TYPES['event'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['generic'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'config'     => [
					'hierarchical'  => true,
					'public'        => true,
					'rewrite'       => [ 'slug' => self::NEWSPACK_LISTINGS_TAXONOMIES['place'] ],
					'show_in_menu'  => false, // Set to 'true' to show in WP admin for debugging purposes.
					'show_in_rest'  => true,
					'show_tagcloud' => false,
					'show_ui'       => true,
					'labels'        => get_post_type_object( Core::NEWSPACK_LISTINGS_POST_TYPES['place'] )->labels,
				],
			],
		];
	}

	/**
	 * Registers shadow taxonomies which can be applied to other listing CPTs, pages, or posts.
	 * Terms in these taxonomies are not created or edited directly, but are linked to Listing CPT posts.
	 */
	public static function register_tax() {
		$shadow_taxonomies = self::get_shadow_taxonomy_config();

		// Register shadow taxonomies for each source post type.
		foreach ( $shadow_taxonomies as $post_type_to_shadow => $shadow_taxonomy ) {
			$taxonomy_slug = self::NEWSPACK_LISTINGS_TAXONOMIES[ $post_type_to_shadow ];
			register_taxonomy(
				$taxonomy_slug,
				$shadow_taxonomy['post_types'],
				$shadow_taxonomy['config']
			);
		}
	}

	/**
	 * Using the array of shadow taxonomies, get an array of corresponding post types being shadowed.
	 *
	 * @return array Array of post types being shadowed.
	 */
	public static function get_post_types_to_shadow() {
		return array_values(
			array_intersect_key(
				Core::NEWSPACK_LISTINGS_POST_TYPES,
				self::NEWSPACK_LISTINGS_TAXONOMIES
			)
		);
	}

	/**
	 * Given a shadow taxonomy, get the name of the post type the taxonomy shadows.
	 *
	 * @param string $taxonomy Taxonomy slug.
	 * @return string}boolean The post type the taxonomy shadows, or false if none.
	 */
	public static function get_post_type_by_taxonomy( $taxonomy ) {
		$post_type      = false;
		$taxonomy_slugs = array_keys( self::NEWSPACK_LISTINGS_TAXONOMIES, $taxonomy );
		$taxonomy_slug  = reset( $taxonomy_slugs );

		if ( ! empty( $taxonomy_slug ) && ! empty( Core::NEWSPACK_LISTINGS_POST_TYPES[ $taxonomy_slug ] ) ) {
			$post_type = Core::NEWSPACK_LISTINGS_POST_TYPES[ $taxonomy_slug ];
		}

		return $post_type;
	}

	/**
	 * Given a post type, get the name of the shadow taxonomy for that post type.
	 *
	 * @param string $post_type Post type.
	 * @return string}boolean The shadow taxonomy for the given post type, or false if none.
	 */
	public static function get_taxonomy_by_post_type( $post_type ) {
		$shadow_taxonomy = false;
		$post_type_slugs = array_keys( Core::NEWSPACK_LISTINGS_POST_TYPES, $post_type );
		$post_type_slug  = reset( $post_type_slugs );

		if ( ! empty( $post_type_slug ) && ! empty( self::NEWSPACK_LISTINGS_TAXONOMIES[ $post_type_slug ] ) ) {
			$shadow_taxonomy = self::NEWSPACK_LISTINGS_TAXONOMIES[ $post_type_slug ];
		}

		return $shadow_taxonomy;
	}

	/**
	 * Create shadow relationships between taxonomies and their posts.
	 */
	public static function create_shadow_relationship() {
		add_action( 'wp_insert_post', [ __CLASS__, 'update_or_delete_shadow_term' ], 10, 2 );
		add_action( 'before_delete_post', [ __CLASS__, 'delete_shadow_term' ] );
	}

	/**
	 * When a listing post type changes status, add/update its shadow term if the status is `publish`, otherwise delete it.
	 *
	 * @param int   $post_id ID for the post being inserted or saved.
	 * @param array $post Post object for the post being inserted or saved.
	 * @return void
	 */
	public static function update_or_delete_shadow_term( $post_id, $post ) {
		// Get the taxonomy to update or delete.
		$shadow_taxonomy = self::get_taxonomy_by_post_type( $post->post_type );

		// Bail if not a shadowable post.
		if ( ! $shadow_taxonomy ) {
			return;
		}

		// If the post is a valid post, update or create the shadow term. Otherwise, delete it.
		if ( self::should_update_shadow_term( $post ) ) {
			self::update_shadow_term( $post, $shadow_taxonomy );
		} else {
			self::delete_shadow_term( $post, $shadow_taxonomy );
		}
	}

	/**
	 * Check whether a given post object should have a shadow term.
	 *
	 * @param object $post Post object to check.
	 * @return bool True if the post should have a shadow term, otherwise false.
	 */
	public static function should_update_shadow_term( $post ) {
		$should_update_shadow_term = true;

		// If post isn't published.
		if ( 'publish' !== $post->post_status ) {
			$should_update_shadow_term = false;
		}

		// If post lacks a valid title.
		if ( ! $post->post_title || 'Auto Draft' === $post->post_title ) {
			$should_update_shadow_term = false;
		}

		// If post lacks a valid slug.
		if ( ! $post->post_name ) {
			$should_update_shadow_term = false;
		}

		// If post type isn't a shadowable type.
		if ( ! in_array( $post->post_type, self::get_post_types_to_shadow() ) ) {
			$should_update_shadow_term = false;
		}

		return $should_update_shadow_term;
	}

	/**
	 * Creates a new taxonomy term, or updates an existing one.
	 *
	 * @param array       $post Post object for the post being inserted or saved.
	 * @param string|null $taxonomy Name of taxonomy to create or update.
	 * @return bool|void Nothing if successful, or false if not.
	 */
	public static function update_shadow_term( $post, $taxonomy = null ) {
		// Bail if post or taxonomy isn't valid.
		if ( ! self::should_update_shadow_term( $post ) || empty( $taxonomy ) ) {
			return false;
		}

		// Check for a shadow term associated with this post.
		$shadow_term = self::get_shadow_term( $post, $taxonomy );

		// If there isn't already a shadow term, create it.
		if ( empty( $shadow_term ) ) {
			self::create_shadow_term( $post, $taxonomy );
		} else {
			// Otherwise, update the existing term.
			wp_update_term(
				$shadow_term->term_id,
				$taxonomy,
				[
					'name' => $post->post_title,
					'slug' => $post->post_name,
				]
			);
		}
	}

	/**
	 * Deletes an existing shadow taxonomy term when the post is being deleted.
	 *
	 * @param array  $post Post object for the post being deleted.
	 * @param string $taxonomy Name of taxonomy to delete.
	 * @return bool|void Nothing if successful, or false if not.
	 */
	public static function delete_shadow_term( $post, $taxonomy = null ) {
		// Bail if no taxonomy passed.
		if ( empty( $taxonomy ) ) {
			return false;
		}

		// Check for a shadow term associated with this post.
		$shadow_term = self::get_shadow_term( $post, $taxonomy );

		if ( empty( $shadow_term ) ) {
			return false;
		}

		wp_delete_term( $shadow_term->term_id, $taxonomy );
	}

	/**
	 * Looks up a shadow taxonomy term linked to a given post.
	 *
	 * @param array  $post Post object to look up.
	 * @param string $taxonomy Name of taxonomy to get.
	 * @return array|bool Term object of the linked term, if any, or false.
	 */
	public static function get_shadow_term( $post, $taxonomy = null ) {
		if ( empty( $post ) || empty( $post->post_title ) || empty( $taxonomy ) ) {
			return false;
		}

		// Try finding the shadow term by slug first.
		$shadow_term = get_term_by( 'slug', $post->post_name, $taxonomy );

		// If we can't find a term by slug, the post slug may have been updated. Try finding by title instead.
		if ( empty( $shadow_term ) ) {
			$shadow_term = get_term_by( 'name', $post->post_title, $taxonomy );
		}

		// If we can't find a term by either slug or title, it probably doesn't exist.
		if ( empty( $shadow_term ) ) {
			return false;
		}

		return $shadow_term;
	}

	/**
	 * Creates a shadow taxonomy term linked to the given post.
	 *
	 * @param array  $post Post object for which to create a shadow term.
	 * @param string $taxonomy Name of taxonomy to create.
	 * @return array|bool Term object if successful, false if not.
	 */
	public static function create_shadow_term( $post, $taxonomy = null ) {
		// Bail if no taxonomy passed.
		if ( empty( $taxonomy ) ) {
			return false;
		}

		$new_term = wp_insert_term(
			$post->post_title,
			$taxonomy,
			[
				'slug' => $post->post_name,
			]
		);

		if ( is_wp_error( $new_term ) ) {
			return false;
		}

		$post_id     = $post->ID;
		$new_term_id = $new_term['term_id'];

		// Apply the term to the parent post. This lets us check for missing terms.
		wp_set_post_terms( $post_id, $new_term_id, $taxonomy, true );

		return $new_term;
	}

	/**
	 * Workaround to hide the default taxonomy sidebars, since we're registering our own UI for managing shadow terms.
	 * The taxonomies must be available via REST from our custom UI, but hidden to Gutenberg's default taxonomy UI.
	 * This workaround is necessary until the parent issue is resolved and merged to WP Core:
	 * https://github.com/WordPress/gutenberg/issues/6912#issuecomment-428403380
	 *
	 * @param WP_REST_Response $response The response object.
	 * @param WP_Taxonomy      $taxonomy The taxonomy object.
	 *
	 * @return WP_REST_Response The filtered response object.
	 */
	public static function hide_taxonomy_sidebar( $response, $taxonomy ) {
		if ( in_array( $taxonomy->name, array_values( self::NEWSPACK_LISTINGS_TAXONOMIES ) ) ) {
			$response->data['visibility']['show_ui'] = false;
		}
		return $response;
	}

	/**
	 * Get parent listing posts for the given shadow taxonomy terms by term slug.
	 * Parent listings are listings that are assigned to a post, page, or other listing
	 * via their corresponding shadow term.
	 *
	 * @param array $slugs Array of term slug to use for looking up post.
	 * @return array Array of parent listing posts.
	 */
	public static function get_parent_listings( $slugs ) {
		$parent_listings = new \WP_Query(
			[
				'post_type'      => self::get_post_types_to_shadow(),
				'posts_per_page' => 100,
				'post_status'    => 'publish',
				'post_name__in'  => $slugs,
				'no_found_rows'  => true,
				'fields'         => 'ids',
				'order'          => 'ASC',
				'orderby'        => 'type title',
			]
		);

		if ( empty( $parent_listings->posts ) || is_wp_error( $parent_listings ) ) {
			return [];
		}

		return $parent_listings->posts;
	}

	/**
	 * Get parent terms for the given post ID.
	 * Parents are the listing shadow terms that have been assigned to a post, page, or other listing.
	 *
	 * @param object $params Array of WP_Query args, including at least the post ID of the parent post.
	 * @return array Array of parent terms for the given post ID.
	 */
	public static function get_parent_terms( $params ) {
		$post_id      = $params['post_id'];
		$per_page     = ! empty( $params['per_page'] ) ? $params['per_page'] : 10;
		$taxonomy     = ! empty( $params['taxonomy'] ) ? $params['taxonomy'] : array_values( self::NEWSPACK_LISTINGS_TAXONOMIES );
		$parent_terms = wp_get_post_terms( $post_id, $taxonomy );
		$parent_terms = array_filter(
			$parent_terms,
			function( $parent_term ) use ( $post_id ) {
				// Don't show the post on itself.
				return get_post_field( 'post_name', get_post( $post_id ) ) !== $parent_term->slug;
			}
		);

		return $parent_terms;
	}

	/**
	 * Get child listings, posts, and pages for the given post ID.
	 * Children are the posts, pages, and other listings that have been assigned a listing shadow term.
	 *
	 * @param object $params Array of WP_Query args, including at least the post ID of the parent post.
	 * @return array Array of child posts that have the parent's shadow term assigned.
	 */
	public static function get_child_posts( $params ) {
		$post_id         = $params['post_id'];
		$per_page        = ! empty( $params['per_page'] ) ? $params['per_page'] : 10;
		$post            = get_post( $post_id );
		$shadow_taxonomy = self::get_taxonomy_by_post_type( $post->post_type );
		$shadow_term     = self::get_shadow_term( $post, $shadow_taxonomy );
		$post_type       = ! empty( $params['post_type'] ) ? $params['post_type'] : [
			'post',
			'page',
			Core::NEWSPACK_LISTINGS_POST_TYPES['event'],
			Core::NEWSPACK_LISTINGS_POST_TYPES['generic'],
			Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
			Core::NEWSPACK_LISTINGS_POST_TYPES['place'],
		];

		// If no shadow term.
		if ( ! $shadow_term ) {
			return [];
		}

		$child_posts = new \WP_Query(
			[
				'post_type'      => $post_type,
				'posts_per_page' => $per_page,
				'post_status'    => [ 'publish', 'draft', 'pending', 'future' ], // Can still set parent listings on draft posts.
				'no_found_rows'  => true,
				'tax_query'      => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => $shadow_taxonomy,
						'terms'    => $shadow_term->slug,
						'field'    => 'slug',
					],
				],
			]
		);

		if ( empty( $child_posts->posts ) || is_wp_error( $child_posts ) ) {
			return [];
		}

		// Filter out the passed post ID.
		return array_filter(
			$child_posts->posts,
			function( $post ) use ( $post_id ) {
				return $post->ID != $post_id;
			}
		);
	}

	/**
	 * Apply or remove a parent shadow term to the given child post.
	 *
	 * @param object $params Params passed from the REST API request.
	 * @return bool|WP_Error True if parent was updated successfully, false if missing required params, or WP_Error.
	 */
	public static function set_parent_posts( $params ) {
		$child   = ! empty( $params['post_id'] ) ? $params['post_id'] : null;
		$added   = ! empty( $params['added'] ) ? $params['added'] : null;
		$removed = ! empty( $params['removed'] ) ? $params['removed'] : null;

		if ( empty( $child ) ) {
			return false;
		}

		// Apply the added parent term to the given child post ID.
		if ( ! empty( $added ) ) {
			$taxonomies_to_add = [];

			foreach ( $added as $term_to_add ) {
				if ( ! isset( $taxonomies_to_add[ $term_to_add['taxonomy'] ] ) ) {
					$taxonomies_to_add[ $term_to_add['taxonomy'] ] = [];
				}

				$taxonomies_to_add[ $term_to_add['taxonomy'] ][] = intval( $term_to_add['id'] );
			}

			foreach ( $taxonomies_to_add as $taxonomy_to_add => $terms_to_add ) {
				$added_parents = wp_set_post_terms( $child, $terms_to_add, $taxonomy_to_add, true );

				// Bail if error.
				if ( is_wp_error( $added_parents ) ) {
					return $added_parents;
				}
			}
		}

		// Remove the parent's shadow term from the child post ID.
		if ( ! empty( $removed ) ) {
			$taxonomies_to_remove = [];

			foreach ( $removed as $term_to_remove ) {
				if ( ! isset( $taxonomies_to_remove[ $term_to_remove['taxonomy'] ] ) ) {
					$taxonomies_to_remove[ $term_to_remove['taxonomy'] ] = [];
				}

				$taxonomies_to_remove[ $term_to_remove['taxonomy'] ][] = intval( $term_to_remove['id'] );
			};

			foreach ( $taxonomies_to_remove as $taxonomy_to_remove => $terms_to_remove ) {
				$removed_parents = wp_remove_object_terms( $child, $terms_to_remove, $taxonomy_to_remove );

				// Bail if error.
				if ( is_wp_error( $removed_parents ) ) {
					return $removed_parents;
				}
			}
		}

		return true;
	}

	/**
	 * Apply or remove the given parent post's shadow term to or from the given child posts.
	 *
	 * @param object $params Params passed from the REST API request.
	 * @return bool|WP_Error True if children were updated successfully, false if missing required params, or WP_Error.
	 */
	public static function set_child_posts( $params ) {
		$parent  = ! empty( $params['post_id'] ) ? $params['post_id'] : null;
		$added   = ! empty( $params['added'] ) ? $params['added'] : [];
		$removed = ! empty( $params['removed'] ) ? $params['removed'] : [];

		if ( empty( $parent ) ) {
			return false;
		}

		$parent_post = get_post( $parent );
		if ( ! self::should_update_shadow_term( $parent_post ) ) {
			return false;
		}

		$taxonomy    = self::get_taxonomy_by_post_type( $parent_post->post_type );
		$shadow_term = self::get_shadow_term( $parent_post, $taxonomy );

		// Apply the parent's shadow term to the `added` post IDs.
		foreach ( $added as $child_to_add ) {
			$added_child = wp_set_post_terms( $child_to_add, $shadow_term->term_id, $taxonomy, true );

			// Bail if error.
			if ( is_wp_error( $added_child ) ) {
				return $added_child;
			}
		}

		// Remove the parent's shadow term from the `removed` post IDs.
		foreach ( $removed as $child_to_remove ) {
			$removed_child = wp_remove_object_terms( $child_to_remove, $shadow_term->term_id, $taxonomy );

			// Bail if error.
			if ( is_wp_error( $removed_child ) ) {
				return $removed_child;
			}
		}

		return true;
	}

	/**
	 * Register taxonomies on plugin activation.
	 */
	public static function activation_hook() {
		self::register_tax();
	}
}

Newspack_Listings_Taxonomies::instance();
