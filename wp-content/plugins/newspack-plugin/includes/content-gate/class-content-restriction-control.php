<?php
/**
 * Newspack Content Restriction Control
 *
 * @package Newspack
 */

namespace Newspack;

use Newspack\Access_Rules;

/**
 * Main class.
 */
class Content_Restriction_Control {
	/**
	 * Map of post IDs to gate IDs.
	 *
	 * @var array
	 */
	private static $post_gate_id_map = [];

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_filter( 'newspack_is_post_restricted', [ __CLASS__, 'is_post_restricted' ], 10, 2 );
	}

	/**
	 * Get the post types that can be restricted.
	 */
	public static function get_available_post_types() {
		$available_post_types = array_values(
			array_map(
				function( $post_type ) {
					return [
						'value' => $post_type->name,
						'label' => $post_type->label,
					];
				},
				get_post_types(
					[
						'public'       => true,
						'show_in_rest' => true,
						'_builtin'     => false,
					],
					'objects'
				)
			)
		);

		return apply_filters(
			'newspack_content_gate_supported_post_types',
			array_merge(
				[
					[
						'value' => 'post',
						'label' => 'Posts',
					],
					[
						'value' => 'page',
						'label' => 'Pages',
					],
				],
				$available_post_types
			)
		);
	}

	/**
	 * Get the taxonomies that can be restricted.
	 * By default, this includes all public taxonomies that apply to available post types.
	 *
	 * @return array Array of taxonomies.
	 */
	public static function get_available_taxonomies() {
		$available_taxonomies = [
			[
				'slug'  => 'category',
				'label' => 'Categories',
			],
			[
				'slug'  => 'post_tag',
				'label' => 'Tags',
			],
		];

		return apply_filters(
			'newspack_content_gate_supported_taxonomies',
			$available_taxonomies
		);
	}

	/**
	 * Get post gates.
	 *
	 * @param int $post_id Optional post ID.
	 *
	 * @return array Array of post gates.
	 */
	public static function get_post_gates( $post_id = null ) {
		$post_id = $post_id ?? \get_the_ID();
		if ( ! $post_id ) {
			return [];
		}

		$gates = Content_Gate::get_gates( Content_Gate::GATE_CPT, 'publish' );
		if ( empty( $gates ) ) {
			return [];
		}

		$post_gates = [];
		foreach ( $gates as $gate ) {
			if ( 'publish' !== $gate['status'] ) {
				continue;
			}
			$content_rules = $gate['content_rules'];
			if ( empty( $content_rules ) ) {
				continue;
			}

			foreach ( $content_rules as $content_rule ) {
				$is_exclusion = isset( $content_rule['exclusion'] ) && $content_rule['exclusion'];
				if ( $content_rule['slug'] === 'post_types' ) {
					$post_type = get_post_type( $post_id );
					if ( $is_exclusion ? in_array( $post_type, $content_rule['value'], true ) : ! in_array( $post_type, $content_rule['value'], true ) ) {
						continue 2;
					}
				} else {
					$taxonomy = get_taxonomy( $content_rule['slug'] );
					if ( ! $taxonomy ) {
						continue 2;
					}
					$terms = wp_get_post_terms( $post_id, $content_rule['slug'], [ 'fields' => 'ids' ] );
					if ( ( ! $is_exclusion && ! $terms ) || is_wp_error( $terms ) ) {
						continue 2;
					}
					if ( $is_exclusion ? ! empty( array_intersect( $terms, $content_rule['value'] ) ) : empty( array_intersect( $terms, $content_rule['value'] ) ) ) {
						continue 2;
					}
				}
			}
			$post_gates[] = $gate;
		}
		return $post_gates;
	}

	/**
	 * Whether the post is restricted for the current user.
	 *
	 * @param bool $is_post_restricted Whether the post is restricted for the current user.
	 * @param int  $post_id            Post ID.
	 *
	 * @return bool
	 */
	public static function is_post_restricted( $is_post_restricted, $post_id = null ) {
		// Don't apply our restriction strategy if Woo Memberships is active.
		if ( Memberships::is_active() ) {
			return $is_post_restricted;
		}

		// Return early if the post is already restricted for the current user.
		if ( $is_post_restricted ) {
			return $is_post_restricted;
		}

		$post_gates = self::get_post_gates( $post_id );
		if ( empty( $post_gates ) ) {
			return false;
		}

		// Return if the post gate has already been determined.
		if ( ! empty( self::$post_gate_id_map[ $post_id ] ) ) {
			return true;
		}

		foreach ( $post_gates as $gate ) {
			$access_rules = $gate['access_rules'];
			if ( empty( $access_rules ) ) {
				continue;
			}
			foreach ( $access_rules as $rule ) {
				if ( ! Access_Rules::evaluate_rule( $rule['slug'], $rule['value'] ?? null ) ) {
					self::$post_gate_id_map[ $post_id ] = $gate['id'];
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Get the current gate post ID.
	 *
	 * @param int $post_id Post ID. If not given, uses the current post ID.
	 *
	 * @return int|false
	 */
	public static function get_gate_post_id( $post_id = null ) {
		if ( ! Content_Gate::is_newspack_feature_enabled() ) {
			return false;
		}
		if ( is_singular() ) {
			$post_id = $post_id ? $post_id : get_queried_object_id();
		}
		if ( ! $post_id ) {
			return false;
		}
		if ( ! empty( self::$post_gate_id_map[ $post_id ] ) ) {
			return self::$post_gate_id_map[ $post_id ];
		}
		return false;
	}
}
Content_Restriction_Control::init();
