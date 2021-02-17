<?php
/**
 * Newspack Ads Custom Post  Type
 *
 * @package Newspack
 */

/**
 * Newspack Ads Blocks Management
 */
class Newspack_Ads_Model {

	const AD_SERVICE = 'ad_service';
	const SIZES      = 'sizes';
	const CODE       = 'code';

	const NEWSPACK_ADS_SERVICE_PREFIX      = '_newspack_ads_service_';
	const NEWSPACK_ADS_NETWORK_CODE_SUFFIX = '_network_code';

	/**
	 * Custom post type
	 *
	 * @var string
	 */

	public static $custom_post_type = 'newspack_ad_codes';

	/**
	 * Array of all unique div IDs used for ads.
	 *
	 * @var array
	 */
	public static $ad_ids = [];

	/**
	 * Initialize Google Ads Model
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_ad_post_type' ) );
	}

	/**
	 * Register ad unit post type
	 */
	public static function register_ad_post_type() {
		register_post_type(
			self::$custom_post_type,
			array(
				'public'             => false,
				'publicly_queryable' => true,
				'show_in_rest'       => true,
			)
		);
	}

	/**
	 * Get a single ad unit.
	 *
	 * @param number $id The id of the ad unit to retrieve.
	 * @param string $placement The id of the placement region.
	 * @param string $context An optional parameter to describe the context of the ad. For example, in the Widget, the widget ID.
	 */
	public static function get_ad_unit( $id, $placement = null, $context = null ) {
		$ad_unit               = \get_post( $id );
		$responsive_placements = [ 'global_above_header', 'global_below_header', 'global_above_footer' ];
		if ( is_a( $ad_unit, 'WP_Post' ) ) {
			$responsive = apply_filters(
				'newspack_ads_maybe_use_responsive_placement',
				in_array( $placement, $responsive_placements ),
				$placement,
				$context
			);

			$prepared_ad_unit = [
				'id'             => $ad_unit->ID,
				'name'           => $ad_unit->post_title,
				self::SIZES      => self::sanitize_sizes( \get_post_meta( $ad_unit->ID, self::SIZES, true ) ),
				self::CODE       => \get_post_meta( $ad_unit->ID, self::CODE, true ),
				self::AD_SERVICE => self::sanitize_ad_service( \get_post_meta( $ad_unit->ID, self::AD_SERVICE, true ) ),
				'responsive'     => $responsive,
				'placement'      => $placement,
				'context'        => $context,
			];

			$prepared_ad_unit['ad_code']     = self::code_for_ad_unit( $prepared_ad_unit );
			$prepared_ad_unit['amp_ad_code'] = self::amp_code_for_ad_unit( $prepared_ad_unit );
			return $prepared_ad_unit;
		} else {
			return new WP_Error(
				'newspack_no_adspot_found',
				\esc_html__( 'No such ad spot.', 'newspack' ),
				array(
					'status' => '400',
				)
			);
		}
	}

	/**
	 * Get the ad units from our saved option.
	 */
	public static function get_ad_units() {
		$ad_units = array();
		$args     = array(
			'post_type'      => self::$custom_post_type,
			'posts_per_page' => 100,
			'post_status'    => [ 'publish' ],
		);

		$query = new \WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$ad_units[] = array(
					'id'             => \get_the_ID(),
					'name'           => html_entity_decode( \get_the_title(), ENT_QUOTES ),
					self::SIZES      => self::sanitize_sizes( \get_post_meta( get_the_ID(), self::SIZES, true ) ),
					self::CODE       => esc_html( \get_post_meta( get_the_ID(), self::CODE, true ) ),
					self::AD_SERVICE => \get_post_meta( get_the_ID(), self::AD_SERVICE, true ),
				);
			}
		}

		return $ad_units;
	}

	/**
	 * Add a new ad unit.
	 *
	 * @param array $ad_unit The new ad unit info to add.
	 */
	public static function add_ad_unit( $ad_unit ) {
		// Sanitise the values.
		$ad_unit = self::sanitise_ad_unit( $ad_unit );
		if ( \is_wp_error( $ad_unit ) ) {
			return $ad_unit;
		}

		$name = strlen( trim( $ad_unit['name'] ) ) ? $ad_unit['name'] : $ad_unit[ self::CODE ];

		// Save the ad unit.
		$ad_unit_post = \wp_insert_post(
			array(
				'post_author' => \get_current_user_id(),
				'post_title'  => $name,
				'post_type'   => self::$custom_post_type,
				'post_status' => 'publish',
			)
		);
		if ( \is_wp_error( $ad_unit_post ) ) {
			return new WP_Error(
				'newspack_ad_unit_exists',
				\esc_html__( 'An ad unit with that name already exists', 'newspack' ),
				array(
					'status' => '400',
				)
			);
		}

		// Add the code to our new post.
		\add_post_meta( $ad_unit_post, self::SIZES, $ad_unit[ self::SIZES ] );
		\add_post_meta( $ad_unit_post, self::CODE, $ad_unit[ self::CODE ] );

		return array(
			'id'             => $ad_unit_post,
			'name'           => $ad_unit['name'],
			self::SIZES      => $ad_unit[ self::SIZES ],
			self::CODE       => $ad_unit[ self::CODE ],
			self::AD_SERVICE => $ad_unit[ self::AD_SERVICE ],
		);
	}

	/**
	 * Update an ad unit
	 *
	 * @param array $ad_unit The updated ad unit.
	 */
	public static function update_ad_unit( $ad_unit ) {
		// Sanitise the values.
		$ad_unit = self::sanitise_ad_unit( $ad_unit );
		if ( \is_wp_error( $ad_unit ) ) {
			return $ad_unit;
		}

		$ad_unit_post = \get_post( $ad_unit['id'] );
		if ( ! is_a( $ad_unit_post, 'WP_Post' ) ) {
			return new WP_Error(
				'newspack_ad_unit_not_exists',
				\esc_html__( "Can't update an ad unit that doesn't already exist", 'newspack' ),
				array(
					'status' => '400',
				)
			);
		}

		$name = strlen( trim( $ad_unit['name'] ) ) ? $ad_unit['name'] : $ad_unit[ self::CODE ];

		\wp_update_post(
			array(
				'ID'         => $ad_unit['id'],
				'post_title' => $name,
			)
		);
		\update_post_meta( $ad_unit['id'], self::SIZES, $ad_unit[ self::SIZES ] );
		\update_post_meta( $ad_unit['id'], self::CODE, $ad_unit[ self::CODE ] );
		\update_post_meta( $ad_unit['id'], self::AD_SERVICE, $ad_unit[ self::AD_SERVICE ] );

		return array(
			'id'             => $ad_unit['id'],
			'name'           => $ad_unit['name'],
			self::SIZES      => $ad_unit[ self::SIZES ],
			self::CODE       => $ad_unit[ self::CODE ],
			self::AD_SERVICE => $ad_unit[ self::AD_SERVICE ],
		);
	}

	/**
	 * Delete an ad unit
	 *
	 * @param integer $id The id of the ad unit to delete.
	 */
	public static function delete_ad_unit( $id ) {
		$ad_unit_post = \get_post( $id );
		if ( ! is_a( $ad_unit_post, 'WP_Post' ) ) {
			return new WP_Error(
				'newspack_ad_unit_not_exists',
				\esc_html__( "Can't delete an ad unit that doesn't already exist", 'newspack' ),
				array(
					'status' => '400',
				)
			);
		}
		if ( $ad_unit_post->post_type !== self::$custom_post_type ) {
			return new WP_Error(
				'newspack_ad_unit_incorrect_type',
				\esc_html__( 'Post is not a Newspack Ad Unit. Cannot be deleted.', 'newspack' ),
				array(
					'status' => '400',
				)
			);
		}
		\wp_delete_post( $id );
		return true;
	}

	/**
	 * Update/create the header code for a service.
	 *
	 * @param string $service The service.
	 * @param string $network_code The code.
	 */
	public static function set_network_code( $service, $network_code ) {
		$id = self::NEWSPACK_ADS_SERVICE_PREFIX . $service . self::NEWSPACK_ADS_NETWORK_CODE_SUFFIX;
		update_option( self::NEWSPACK_ADS_SERVICE_PREFIX . $service . self::NEWSPACK_ADS_NETWORK_CODE_SUFFIX, sanitize_text_field( $network_code ) );
		return true;
	}

	/**
	 * Retrieve the header code for a service.
	 *
	 * @param string $service The service.
	 * @return string $network_code The code.
	 */
	public static function get_network_code( $service ) {
		$network_code = get_option( self::NEWSPACK_ADS_SERVICE_PREFIX . $service . self::NEWSPACK_ADS_NETWORK_CODE_SUFFIX, '' );
		return absint( $network_code ); // Google Ad Manager network code is a numeric identifier https://support.google.com/admanager/answer/7674889?hl=en.
	}

	/**
	 * Sanitize an ad unit.
	 *
	 * @param array $ad_unit The ad unit to sanitize.
	 */
	public static function sanitise_ad_unit( $ad_unit ) {
		if (
			! array_key_exists( self::CODE, $ad_unit ) ||
			! array_key_exists( self::SIZES, $ad_unit )
		) {
			return new WP_Error(
				'newspack_invalid_ad_unit_data',
				\esc_html__( 'Ad spot data is invalid - name or code is missing!', 'newspack' ),
				array(
					'status' => '400',
				)
			);
		}

		$sanitised_ad_unit = array(
			'name'           => \esc_html( $ad_unit['name'] ),
			self::CODE       => esc_html( $ad_unit[ self::CODE ] ),
			self::SIZES      => self::sanitize_sizes( $ad_unit[ self::SIZES ] ),
			self::AD_SERVICE => self::sanitize_ad_service( $ad_unit[ self::AD_SERVICE ] ),

		);

		if ( isset( $ad_unit['id'] ) ) {
			$sanitised_ad_unit['id'] = (int) $ad_unit['id'];
		}

		return $sanitised_ad_unit;
	}

	/**
	 * Sanitize array of ad unit sizes.
	 *
	 * @param array $sizes Array of sizes to sanitize.
	 * @return array Sanitized array.
	 */
	public static function sanitize_sizes( $sizes ) {
		$sizes     = is_array( $sizes ) ? $sizes : [];
		$sanitized = [];
		foreach ( $sizes as $size ) {
			$size    = is_array( $size ) && 2 === count( $size ) ? $size : [ 0, 0 ];
			$size[0] = absint( $size[0] );
			$size[1] = absint( $size[1] );

			$sanitized[] = $size;
		}
		return $sanitized;
	}

	/**
	 * Sanitize ad service ID.
	 *
	 * @param string $ad_service Ad service ID.
	 * @return string Sanitized Ad service ID.
	 */
	public static function sanitize_ad_service( $ad_service ) {
		return in_array( $ad_service, [ 'google_ad_manager' ] ) ? $ad_service : null;
	}

	/**
	 * Code for ad unit.
	 *
	 * @param array $ad_unit The ad unit to generate code for.
	 */
	public static function code_for_ad_unit( $ad_unit ) {
		$sizes        = $ad_unit['sizes'];
		$code         = $ad_unit['code'];
		$network_code = self::get_network_code( 'google_ad_manager' );
		$unique_id    = uniqid();
		if ( ! is_array( $sizes ) ) {
			$sizes = [];
		}

		self::$ad_ids[ $unique_id ] = $ad_unit;

		$code = sprintf(
			"<!-- /%s/%s --><div id='div-gpt-ad-%s-0'></div>",
			$network_code,
			$code,
			$unique_id
		);
		return $code;
	}

	/**
	 * AMP code for ad unit.
	 *
	 * @param array $ad_unit The ad unit to generate AMP code for.
	 */
	public static function amp_code_for_ad_unit( $ad_unit ) {
		$sizes        = $ad_unit['sizes'];
		$code         = $ad_unit['code'];
		$network_code = self::get_network_code( 'google_ad_manager' );
		$targeting    = self::get_ad_targeting( $ad_unit );
		$unique_id    = uniqid();

		if ( ! is_array( $sizes ) ) {
			$sizes = [];
		}

		if ( $ad_unit['responsive'] ) {
			return self::ad_elements_for_sizes( $ad_unit, $unique_id );
		}

		$size_to_show = self::is_sticky( $ad_unit ) ? self::smallest_ad_size( $sizes ) : self::largest_ad_size( $sizes );

		$code = sprintf(
			'<amp-ad width=%s height=%s type="doubleclick" data-slot="/%s/%s" data-loading-strategy="prefer-viewability-over-views" json=\'{"targeting":%s}\'></amp-ad>',
			$size_to_show[0],
			$size_to_show[1],
			$network_code,
			$code,
			wp_json_encode( $targeting )
		);

		return $code;
	}

	/**
	 * Generate responsive AMP ads for a series of ad sizes.
	 *
	 * @param array  $ad_unit The ad unit to generate code for.
	 * @param string $unique_id Unique ID for this ad unit instance.
	 */
	public static function ad_elements_for_sizes( $ad_unit, $unique_id ) {
		$network_code = self::get_network_code( 'google_ad_manager' );
		$code         = $ad_unit['code'];
		$sizes        = $ad_unit['sizes'];
		$targeting    = self::get_ad_targeting( $ad_unit );

		array_multisort( $sizes );
		$widths = array_unique( array_column( $sizes, 0 ) );

		$markup = [];
		$styles = [];

		// Gather up all of the ad sizes which should be displayed on the same viewports.
		// As a heuristic, each ad slot can safely display ads 200px narrower or less than the slot's width.
		// e.g. for the following setup: [[900,200], [750,200]],
		// We can display [[900,200], [750,200]] on viewports >= 900px and [[750,200]] on viewports < 900px.
		$width_difference_max = apply_filters( 'newspack_ads_multisize_size_difference_max', 200, $ad_unit );
		$all_ad_sizes         = [];
		foreach ( $widths as $ad_width ) {
			$valid_ad_sizes = [];

			foreach ( $sizes as $size ) {
				if ( $size[0] <= $ad_width && $ad_width - $width_difference_max <= $size[0] ) {
					$valid_ad_sizes[] = $size;
				}
			}

			$all_ad_sizes[] = $valid_ad_sizes;
		}
		$all_ad_sizes = apply_filters( 'newspack_ads_multisize_ad_sizes', $all_ad_sizes, $ad_unit );

		// Generate an array of media query data, with a likely min and max width for each size.
		$media_queries = [];
		foreach ( $all_ad_sizes as $index => $ad_size ) {
			$width = absint( max( array_column( $ad_size, 0 ) ) );

			// If there are ad sizes larger than the current size, the max_width is 1 less than the next ad's size.
			// If it's the largest ad size, there is no max width.
			$max_width = null;
			if ( count( $all_ad_sizes ) > $index + 1 ) {
				$max_width = absint( max( array_column( $all_ad_sizes[ $index + 1 ], 0 ) ) ) - 1;
			}

			$media_queries[] = [
				'width'     => $width,
				'height'    => absint( max( array_column( $ad_size, 1 ) ) ),
				'min_width' => $width,
				'max_width' => $max_width,
			];
		}

		// Allow themes to filter the media query data based on the size, placement, and context of the ad.
		$media_queries = apply_filters(
			'newspack_ads_media_queries',
			$media_queries,
			$ad_unit['placement'],
			$ad_unit['context']
		);

		// Build the amp-ad units.
		foreach ( $all_ad_sizes as $index => $ad_sizes ) {

			// The size of the ad container should be equal to the largest width and height among all the sizes available.
			$width  = absint( max( array_column( $ad_sizes, 0 ) ) );
			$height = absint( max( array_column( $ad_sizes, 1 ) ) );

			$multisizes = array_map(
				function( $size ) {
					return $size[0] . 'x' . $size[1];
				},
				$ad_sizes
			);

			// If there is a multisize that's equal to the width and height of the container, remove it from the multisizes.
			// The container size is included by default, and should not also be included in the multisize.
			$container_multisize          = $width . 'x' . $height;
			$container_multisize_location = array_search( $container_multisize, $multisizes );
			if ( false !== $container_multisize_location ) {
				unset( $multisizes[ $container_multisize_location ] );
			}

			$multisize_attribute = '';
			if ( count( $multisizes ) ) {
				$multisize_attribute = sprintf(
					'data-multi-size=\'%s\' data-multi-size-validation=\'false\'',
					implode( ',', $multisizes )
				);
			}

			$div_prefix = 'div-gpt-amp-';
			$div_id     = sprintf(
				'%s%s-%s-%dx%d',
				$div_prefix,
				sanitize_title( $ad_unit['code'] ),
				$unique_id,
				$width,
				$height
			);

			$markup[] = sprintf(
				'<div id="%s"><amp-ad width="%dpx" height="%dpx" type="doubleclick" data-slot="/%s/%s" data-loading-strategy="prefer-viewability-over-views" json=\'{"targeting":%s}\' %s></amp-ad></div>',
				$div_id,
				$width,
				$height,
				$network_code,
				$code,
				wp_json_encode( $targeting ),
				$multisize_attribute
			);

			// Generate styles for hiding/showing ads at different viewports out of the media queries.
			$media_query          = $media_queries[ $index ];
			$media_query_elements = [];
			if ( $media_query['min_width'] ) {
				$media_query_elements[] = sprintf( '(min-width:%dpx)', $media_query['min_width'] );
			}
			if ( $media_query['max_width'] ) {
				$media_query_elements[] = sprintf( '(max-width:%dpx)', $media_query['max_width'] );
			}
			$styles[] = sprintf(
				'#%s{ display: none; }',
				$div_id
			);
			if ( count( $media_query_elements ) > 0 ) {
				$styles[] = sprintf(
					'@media %s {#%s{ display: block; } }',
					implode( ' and ', $media_query_elements ),
					$div_id
				);
			}
		}
		return sprintf(
			'<style>%s</style>%s',
			implode( ' ', $styles ),
			implode( ' ', $markup )
		);
	}

	/**
	 * Picks the smallest size from an array of width/height pairs.
	 *
	 * @param array $sizes An array of dimension pairs.
	 * @return array The pair with the narrowest width.
	 */
	public static function smallest_ad_size( $sizes ) {
		return array_reduce(
			$sizes,
			function( $carry, $item ) {
				return $item[0] < $carry[0] ? $item : $carry;
			},
			[ PHP_INT_MAX, PHP_INT_MAX ]
		);
	}

	/**
	 * Picks the largest size from an array of width/height pairs.
	 *
	 * @param array $sizes An array of dimension pairs.
	 * @return array The pair with the widest width.
	 */
	public static function largest_ad_size( $sizes ) {
		return array_reduce(
			$sizes,
			function( $carry, $item ) {
				return $item[0] > $carry[0] ? $item : $carry;
			},
			[ 0, 0 ]
		);
	}

	/**
	 * Get ad targeting params for the current post or archive.
	 *
	 * @param array $ad_unit Ad unit to get targeting for.
	 * @return array Associative array of targeting keyvals.
	 */
	public static function get_ad_targeting( $ad_unit ) {
		$targeting = [];

		if ( is_singular() ) {
			// Add the post slug to targeting.
			$slug = get_post_field( 'post_name' );
			if ( $slug ) {
				$targeting['slug'] = sanitize_text_field( $slug );
			}

			// Add the category slugs to targeting.
			$categories = wp_get_post_categories( get_the_ID(), [ 'fields' => 'slugs' ] );
			if ( ! empty( $categories ) ) {
				$targeting['category'] = array_map( 'sanitize_text_field', $categories );
			}

			// Add the post ID to targeting.
			$targeting['ID'] = get_the_ID();

			// Add the category slugs to targeting on category archives.
		} elseif ( get_queried_object() ) {
			$queried_object = get_queried_object();
			if ( 'WP_Term' === get_class( $queried_object ) && 'category' === $queried_object->taxonomy ) {
				$targeting['category'] = [ sanitize_text_field( $queried_object->slug ) ];
			}
		}

		return apply_filters( 'newspack_ads_ad_targeting', $targeting, $ad_unit );
	}

	/**
	 * Is the given ad unit a sticky ad?
	 *
	 * @param array $ad_unit Ad unit to check.
	 * @return boolean True if sticky, otherwise false.
	 */
	public static function is_sticky( $ad_unit ) {
		return 'sticky' === $ad_unit['placement'];
	}
}
Newspack_Ads_Model::init();
