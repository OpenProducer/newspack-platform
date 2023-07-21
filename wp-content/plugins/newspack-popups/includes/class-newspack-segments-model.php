<?php
/**
 * Newspack Segments
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Segments Plugin Class.
 */
final class Newspack_Segments_Model {

	/**
	 * The taxonomy slug
	 *
	 * @var string
	 */
	const TAX_SLUG = 'popup_segment';

	/**
	 * The current DB version, used to perform updates in the data in case of change in the structure
	 *
	 * @var int
	 */
	const DB_VERSION = 1;

	/**
	 * The DB version option name. Where the current option is stored.
	 *
	 * @var string
	 */
	const DB_VERSION_OPTION_NAME = 'newspack_segments_db_version';

	/**
	 * Initializes the class and registers the taxonomy
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_segments_taxonomy' ) );
		add_action( 'init', array( __CLASS__, 'maybe_update_db_version' ) );
	}

	/**
	 * Checks if the DB version has changed and updates the data if needed
	 *
	 * @return void
	 */
	public static function maybe_update_db_version() {
		$current_db_version = (int) get_option( self::DB_VERSION_OPTION_NAME, 0 );
		if ( $current_db_version < self::DB_VERSION ) {
			self::update_db_version( $current_db_version );
		}
	}

	/**
	 * Updates the DB version option and performs the needed updates
	 *
	 * @param int $current_db_version The current DB version.
	 * @return void
	 */
	public static function update_db_version( $current_db_version ) {
		if ( $current_db_version < 1 ) {
			self::update_db_version_to_1();
		}
		update_option( self::DB_VERSION_OPTION_NAME, self::DB_VERSION );
	}

	/**
	 * Updates the DB version to 1, when the segments were migrated from a single option entry into terms of a taxonomy
	 *
	 * @return void
	 */
	public static function update_db_version_to_1() {
		$old_segments = get_option( Newspack_Popups_Segmentation::SEGMENTS_OPTION_NAME );
		$id_mapping   = [];

		if ( ! is_array( $old_segments ) || empty( $old_segments ) ) {
			return;
		}

		foreach ( $old_segments as $old_segment ) {
			$insert                           = self::create_segment( $old_segment );
			$new_segment                      = end( $insert );
			$id_mapping[ $old_segment['id'] ] = $new_segment['id'];
		}

		$popups = Newspack_Popups_Model::retrieve_popups( true, true );
		foreach ( $popups as $popup ) {
			$meta_value = get_post_meta( $popup['id'], 'selected_segment_id', true );
			if ( $meta_value ) {
				// Create a backup of the old value.
				update_post_meta( $popup['id'], 'selected_segment_id_bkp', $meta_value );
				foreach ( $id_mapping as $old_id => $new_id ) {
					$meta_value = str_replace( $old_id, $new_id, $meta_value );
				}
				update_post_meta( $popup['id'], 'selected_segment_id', $meta_value );
			}
		}
	}

	/**
	 * Register the segments taxonomy
	 *
	 * @return void
	 */
	public static function register_segments_taxonomy() {
		$labels = array(
			'name'              => _x( 'Segments', 'taxonomy general name', 'newspack-popups' ),
			'singular_name'     => _x( 'Segment', 'taxonomy singular name', 'newspack-popups' ),
			'search_items'      => __( 'Search Segments', 'newspack-popups' ),
			'all_items'         => __( 'All Segments', 'newspack-popups' ),
			'parent_item'       => __( 'Parent Segment', 'newspack-popups' ),
			'parent_item_colon' => __( 'Parent Segment:', 'newspack-popups' ),
			'edit_item'         => __( 'Edit Segment', 'newspack-popups' ),
			'update_item'       => __( 'Update Segment', 'newspack-popups' ),
			'add_new_item'      => __( 'Add New Segment', 'newspack-popups' ),
			'new_item_name'     => __( 'New Segment Name', 'newspack-popups' ),
			'menu_name'         => __( 'Segments', 'newspack-popups' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Segments for popups', 'newspack-popups' ),
			'hierarchical'       => false,
			'public'             => false,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => false,
			'show_admin_column'  => false,
			'rewrite'            => false,
		);

		register_taxonomy( self::TAX_SLUG, array( Newspack_Popups::NEWSPACK_POPUPS_CPT ), $args );

		self::register_meta_fields();
	}

	/**
	 * Gets the Schema for the meta fields
	 *
	 * @return array The schema.
	 */
	public static function get_meta_schema() {
		return [
			'priority'      => [
				'name'     => 'priority',
				'type'     => 'integer',
				'required' => false,
				'default'  => PHP_INT_MAX,
			],
			'created_at'    => [
				'name'      => 'created_at',
				'type'      => 'string',
				'required'  => false,
				'maxLength' => 10,
				'pattern'   => '^\d{4}-\d\d-\d\d$',
			],
			'updated_at'    => [
				'name'      => 'created_at',
				'type'      => 'string',
				'required'  => false,
				'maxLength' => 10,
				'pattern'   => '^\d{4}-\d\d-\d\d$',
			],
			'configuration' => [
				'name'                 => 'configuration',
				'type'                 => 'object',
				'required'             => true,
				'additionalProperties' => false,
				'properties'           => [
					'min_posts'           => [
						'name'     => 'min_posts',
						'type'     => 'integer',
						'required' => false,
						'default'  => 0,
					],
					'max_posts'           => [
						'name'     => 'max_posts',
						'type'     => 'integer',
						'required' => false,
						'default'  => 0,
					],
					'min_session_posts'   => [
						'name'     => 'min_session_posts',
						'type'     => 'integer',
						'required' => false,
						'default'  => 0,
					],
					'max_session_posts'   => [
						'name'     => 'max_session_posts',
						'type'     => 'integer',
						'required' => false,
						'default'  => 0,
					],
					'is_subscribed'       => [
						'name'     => 'is_subscribed',
						'type'     => 'boolean',
						'required' => false,
						'default'  => false,
					],
					'is_not_subscribed'   => [
						'name'     => 'is_not_subscribed',
						'type'     => 'boolean',
						'required' => false,
						'default'  => false,
					],
					'is_donor'            => [
						'name'     => 'is_donor',
						'type'     => 'boolean',
						'required' => false,
						'default'  => false,
					],
					'is_not_donor'        => [
						'name'     => 'is_not_donor',
						'type'     => 'boolean',
						'required' => false,
						'default'  => false,
					],
					'is_former_donor'     => [
						'name'     => 'is_former_donor',
						'type'     => 'boolean',
						'required' => false,
						'default'  => false,
					],
					'is_logged_in'        => [
						'name'     => 'is_logged_in',
						'type'     => 'boolean',
						'required' => false,
						'default'  => false,
					],
					'is_not_logged_in'    => [
						'name'     => 'is_not_logged_in',
						'type'     => 'boolean',
						'required' => false,
						'default'  => false,
					],
					'favorite_categories' => [
						'name'     => 'favorite_categories',
						'type'     => 'array',
						'required' => false,
						'default'  => [],
						'items'    => [
							'type'                 => 'object',
							'additionalProperties' => false,
							'properties'           => [
								'id'   => [
									'type' => 'integer',
								],
								'name' => [
									'type' => 'string',
								],
							],
						],
					],
					'referrers'           => [
						'name'     => 'referrers',
						'type'     => 'string',
						'required' => false,
						'default'  => '',
					],
					'referrers_not'       => [
						'name'     => 'referrers_not',
						'type'     => 'string',
						'required' => false,
						'default'  => '',
					],
					'is_disabled'         => [
						'name'     => 'is_disabled',
						'type'     => 'boolean',
						'required' => false,
						'default'  => false,
					],
				],
			],
		];
	}

	/**
	 * Registers each meta field.
	 *
	 * @return void
	 */
	public static function register_meta_fields() {
		foreach ( self::get_meta_schema() as $meta_key => $schema ) {
			register_meta(
				'term',
				$meta_key,
				[
					'type'           => 'term',
					'object_subtype' => self::TAX_SLUG,
					'description'    => $schema['description'] ?? '',
					'single'         => true,
					'show_in_rest'   => [
						'schema' => $schema,
					],
				]
			);
		}
	}

	/**
	 * Create a segment.
	 *
	 * @param object $segment A segment.
	 * @throws TypeError If the segment is not an array.
	 */
	public static function create_segment( $segment ) {
		if ( ! is_array( $segment ) ) {
			throw new TypeError();
		}
		$existing_segments = self::get_segments();
		$existing_names    = wp_list_pluck( $existing_segments, 'name' );

		if ( empty( $segment['name'] ) ) {
			$segment['name'] = 'Unnamed Segment';
		}

		// address edge case of segments with the same name.
		if ( in_array( $segment['name'], $existing_names, true ) ) {
			$i             = 2;
			$original_name = $segment['name'];
			while ( in_array( $segment['name'], $existing_names, true ) ) {
				$segment['name'] = $original_name . ' ' . $i;
				$i++;
			}
		}

		$term = wp_insert_term(
			$segment['name'],
			self::TAX_SLUG
		);

		if ( ! is_wp_error( $term ) ) {
			update_term_meta( $term['term_id'], 'created_at', gmdate( 'Y-m-d' ) );
			update_term_meta( $term['term_id'], 'updated_at', gmdate( 'Y-m-d' ) );
			foreach ( $segment as $meta_key => $meta_value ) {
				if ( 'name' === $meta_key ) {
					continue;
				}
				update_term_meta( $term['term_id'], $meta_key, $meta_value );
			}
			// Add it to the end of the list.
			update_term_meta( $term['term_id'], 'priority', count( $existing_segments ) );
		}
		return self::get_segments();
	}

	/**
	 * Gets all segments, ordered by priority.
	 *
	 * @param boolean $include_inactive If true, fetch both inactive and active segments. If false, only fetch active segments.
	 *
	 * @return array
	 */
	public static function get_segments( $include_inactive = true ) {
		$terms = get_terms(
			[
				'taxonomy'   => self::TAX_SLUG,
				'hide_empty' => false,
				'orderby'    => 'term_id',
				'order'      => 'ASC',
			]
		);

		$segments = array_map(
			function ( $segment ) {
				return self::get_segment_from_term( $segment );
			},
			$terms
		);

		usort(
			$segments,
			function ( $a, $b ) {
				if ( ! isset( $a['priority'] ) || ! isset( $b['priority'] ) ) {
					return 0;
				}
				return $a['priority'] <=> $b['priority'];
			}
		);

		$segments_without_priority = array_filter(
			$segments,
			function( $segment ) {
				return ! isset( $segment['priority'] );
			}
		);

		// Failsafe to ensure that all segments have an assigned priority.
		if ( 0 < count( $segments_without_priority ) ) {
			$segments = self::reindex_segments( $segments );
		}

		// Filter out inactive segments, if needed.
		if ( ! $include_inactive ) {
			$segments = array_filter(
				$segments,
				function( $segment ) {
					return empty( $segment['configuration']['is_disabled'] );
				}
			);
		}

		// Filter out non-existing categories.
		$existing_categories_ids = get_categories(
			[
				'hide_empty' => false,
				'fields'     => 'ids',
			]
		);
		foreach ( $segments as &$segment ) {
			if ( ! isset( $segment['configuration']['favorite_categories'] ) ) {
				continue;
			}
			$fav_categories = $segment['configuration']['favorite_categories'];
			if ( ! empty( $fav_categories ) ) {
				$segment['configuration']['favorite_categories'] = array_values(
					array_intersect(
						$existing_categories_ids,
						$fav_categories
					)
				);
			}
		}
		return $segments;
	}

	/**
	 * Get a single segment by ID.
	 *
	 * @param string $id A segment id.
	 * @return object|null The single segment object with matching ID, or null.
	 */
	public static function get_segment( $id ) {
		$term = get_term( $id, self::TAX_SLUG );
		if ( $term instanceof WP_Term ) {
			return self::get_segment_from_term( $term );
		}
	}

	/**
	 * Get segment IDs.
	 */
	public static function get_segment_ids() {
		return array_map(
			function( $segment ) {
				return $segment['id'];
			},
			self::get_segments()
		);
	}

	/**
	 * Get the segment array representation for a given segment term
	 *
	 * @param WP_Term $segment The segment term.
	 * @return array
	 */
	public static function get_segment_from_term( WP_Term $segment ) {
		$segment = [
			'id'   => (string) $segment->term_id, // This typecast is very important for the front-end to work, as previously segment IDs were string.
			'name' => $segment->name,
		];
		foreach ( self::get_meta_schema() as $meta_key => $schema ) {
			$stored_value = get_term_meta( $segment['id'], $meta_key, true );
			if ( false === $stored_value ) {
				continue;
			}
			if ( 'integer' === $schema['type'] ) {
				$stored_value = intval( $stored_value );
			}
			$segment[ $meta_key ] = $stored_value;
		}
		return $segment;
	}

	/**
	 * Delete a segment.
	 *
	 * @param string $id A segment id.
	 */
	public static function delete_segment( $id ) {
		wp_delete_term( $id, self::TAX_SLUG );
		return self::get_segments();
	}

	/**
	 * Delete all segments.
	 *
	 * @return bool True.
	 */
	public static function delete_all_segments() {
		$segments = self::get_segments();
		foreach ( $segments as $segment ) {
			wp_delete_term( $segment['id'], self::TAX_SLUG );
		}
		return true;
	}

	/**
	 * Update a segment.
	 *
	 * @param object $segment A segment.
	 * @throws TypeError If the segment is not an array.
	 */
	public static function update_segment( $segment ) {
		if ( ! is_array( $segment ) ) {
			throw new TypeError();
		}
		if ( ! isset( $segment['id'] ) ) {
			return self::get_segments();
		}

		$saved = self::get_segment( $segment['id'] );
		if ( ! $saved ) {
			return self::get_segments();
		}

		if ( $saved['name'] !== $segment['name'] ) {
			wp_update_term( $segment['id'], self::TAX_SLUG, [ 'name' => $segment['name'] ] );
		}

		update_term_meta( $segment['id'], 'updated_at', gmdate( 'Y-m-d' ) );
		update_term_meta( $segment['id'], 'configuration', $segment['configuration'] );

		return self::get_segments();
	}

	/**
	 * Sort all segments by relative priority.
	 *
	 * @param array $segment_ids Array of segment IDs, in order of desired priority.
	 * @return array Array of sorted segments.
	 */
	public static function sort_segments( $segment_ids ) {
		$segments = self::get_segments();
		$is_valid = self::validate_segment_ids( $segment_ids, $segments );

		if ( ! $is_valid ) {
			return new WP_Error(
				'invalid_segment_sort',
				__( 'Failed to sort due to outdated segment data. Please refresh and try again.', 'newspack-popups' )
			);
		}

		$sorted_segments = array_map(
			function( $segment_id ) use ( $segments ) {
				$segment = array_filter(
					$segments,
					function( $segment ) use ( $segment_id ) {
						return $segment['id'] === $segment_id;
					}
				);

				return reset( $segment );
			},
			$segment_ids
		);

		$sorted_segments = self::reindex_segments( $sorted_segments );
		foreach ( $sorted_segments as $sorted ) {
			update_term_meta( $sorted['id'], 'priority', $sorted['priority'] );
		}
		return self::get_segments();
	}

	/**
	 * Validate an array of segment IDs against the existing segment IDs in the options table.
	 * When re-sorting segments, the IDs passed should all exist, albeit in a different order,
	 * so if there are any differences, validation will fail.
	 *
	 * @param array $segment_ids Array of segment IDs to validate.
	 * @param array $segments    Array of existing segments to validate against.
	 * @return boolean Whether $segment_ids is valid.
	 */
	public static function validate_segment_ids( $segment_ids, $segments ) {
		$existing_ids = array_map(
			function( $segment ) {
				return $segment['id'];
			},
			$segments
		);

		return array_diff( $segment_ids, $existing_ids ) === array_diff( $existing_ids, $segment_ids );
	}

	/**
	 * Reindex segment priorities based on current position in array.
	 *
	 * @param object $segments Array of segments.
	 * @throws TypeError If the segments are not an array.
	 */
	public static function reindex_segments( $segments ) {
		$index = 0;

		if ( ! is_array( $segments ) ) {
			throw new TypeError();
		}

		return array_map(
			function( $segment ) use ( &$index ) {
				$segment['priority'] = $index;
				$index++;
				return $segment;
			},
			$segments
		);
	}
}

Newspack_Segments_Model::init();
