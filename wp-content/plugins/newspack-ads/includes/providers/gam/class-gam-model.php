<?php
/**
 * Newspack Ads Google Ad Manager Provider Model.
 *
 * @package Newspack
 */

namespace Newspack_Ads\Providers;

use Newspack_Ads\Providers\GAM_API;

/**
 * Newspack Ads GAM Model Class.
 */
final class GAM_Model {
	const SIZES = 'sizes';
	const CODE  = 'code';
	const FLUID = 'fluid';

	// Legacy network code manually inserted.
	const OPTION_NAME_LEGACY_NETWORK_CODE = '_newspack_ads_service_google_ad_manager_network_code';

	// GAM network code pulled from user credentials.
	const OPTION_NAME_GAM_NETWORK_CODE = '_newspack_ads_gam_network_code';

	const OPTION_NAME_GAM_ITEMS             = '_newspack_ads_gam_items';
	const OPTION_NAME_GLOBAL_AD_SUPPRESSION = '_newspack_global_ad_suppression';

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
	 * Array of all ad units configurations.
	 *
	 * @var array|null Array or null if not yet initialized.
	 */
	public static $ad_units = null;

	/**
	 * Initialize Google Ads Model
	 *
	 * @codeCoverageIgnore
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_ad_post_type' ) );
		GAM_API::set_network_code( get_option( self::OPTION_NAME_GAM_NETWORK_CODE, null ) );
	}

	/**
	 * Register ad unit post type
	 *
	 * @codeCoverageIgnore
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
	 * Initial GAM setup.
	 *
	 * @return object|\WP_Error Setup results or error if setup fails.
	 */
	public static function setup_gam() {
		$setup_results = array();
		try {
			$setup_results['created_targeting_keys'] = GAM_API::update_custom_targeting_keys();
		} catch ( \Exception $e ) {
			return new \WP_Error( 'newspack_ads_setup_gam', $e->getMessage() );
		}
		return $setup_results;
	}

	/**
	 * Get a single ad unit to display on the page.
	 *
	 * @param number $id     The id of the ad unit to retrieve.
	 * @param array  $config {
	 *   Optional additional configuration parameters for the ad unit.
	 *
	 *   @type string $unique_id The unique ID for this ad displayment.
	 *   @type string $placement The id of the placement region.
	 *   @type string $context   An optional parameter to describe the context of the ad. For example, in the Widget, the widget ID.
	 * }
	 *
	 * @return object Prepared ad unit, with markup for injecting on a page.
	 */
	public static function get_ad_unit_for_display( $id, $config = array() ) {
		if ( 0 === (int) $id ) {
			return new \WP_Error(
				'newspack_no_adspot_found',
				\esc_html__( 'No such ad spot.', 'newspack' ),
				array(
					'status' => '400',
				)
			);
		}

		$unique_id = $config['unique_id'] ?? uniqid();
		$placement = $config['placement'] ?? '';
		$context   = $config['context'] ?? '';

		$ad_unit = \get_post( $id );

		$prepared_ad_unit = [];

		if ( is_a( $ad_unit, 'WP_Post' ) ) {
			// Legacy ad units, saved as CPT. Ad unit ID is the post ID.
			$prepared_ad_unit = [
				'id'    => $ad_unit->ID,
				'name'  => $ad_unit->post_title,
				'code'  => \get_post_meta( $ad_unit->ID, self::CODE, true ),
				'sizes' => self::sanitize_sizes( \get_post_meta( $ad_unit->ID, self::SIZES, true ) ),
				'fluid' => (bool) \get_post_meta( $ad_unit->ID, self::FLUID, true ),
			];
		} else {
			// Ad units saved in options table. Ad unit ID is the GAM Ad Unit ID.
			$ad_units = self::get_synced_gam_ad_units();

			foreach ( $ad_units as $unit ) {
				if ( intval( $id ) === intval( $unit['id'] ) && 'ACTIVE' === $unit['status'] ) {
					$ad_unit = $unit;
					break;
				}
			}
			if ( $ad_unit ) {
				$prepared_ad_unit = [
					'id'    => $ad_unit['id'],
					'name'  => $ad_unit['name'],
					'code'  => $ad_unit['code'],
					'sizes' => self::sanitize_sizes( $ad_unit['sizes'] ),
					'fluid' => isset( $ad_unit['fluid'] ) ? (bool) $ad_unit['fluid'] : false,
				];
			}
		}

		// Ad unit not found neither as the CPT nor in options table.
		if ( ! isset( $prepared_ad_unit['id'] ) ) {
			return new \WP_Error(
				'newspack_no_adspot_found',
				\esc_html__( 'No such ad spot.', 'newspack' ),
				array(
					'status' => '400',
				)
			);
		}

		$prepared_ad_unit['placement'] = $placement;
		$prepared_ad_unit['context']   = $context;

		$prepared_ad_unit['ad_code']     = self::get_ad_unit_code( $prepared_ad_unit, $unique_id );
		$prepared_ad_unit['amp_ad_code'] = self::get_ad_unit_amp_code( $prepared_ad_unit, $unique_id );
		return $prepared_ad_unit;
	}

	/**
	 * Get the legacy ad units (defined as CPTs).
	 */
	private static function get_legacy_ad_units() {
		$legacy_ad_units = [];
		$query           = new \WP_Query(
			[
				'post_type'      => self::$custom_post_type,
				'posts_per_page' => -1,
				'post_status'    => [ 'publish' ],
			]
		);
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post ) {
				if ( self::$custom_post_type === $post->post_type ) {
					$legacy_ad_units[] = [
						'id'        => $post->ID,
						'name'      => html_entity_decode( $post->post_title, ENT_QUOTES ),
						'sizes'     => self::sanitize_sizes( \get_post_meta( $post->ID, self::SIZES, true ) ),
						'code'      => esc_html( \get_post_meta( $post->ID, self::CODE, true ) ),
						'fluid'     => (bool) \get_post_meta( $post->ID, self::FLUID, true ),
						'status'    => 'ACTIVE',
						'is_legacy' => true,
					];
				}
			}
		}
		return $legacy_ad_units;
	}

	/**
	 * Get the ad units.
	 */
	public static function get_ad_units() {
		if ( null !== self::$ad_units ) {
			return self::$ad_units;
		}
		$ad_units = self::get_legacy_ad_units();
		if ( self::is_gam_connected() ) {
			$gam_ad_units = GAM_API::get_serialised_gam_ad_units();
			if ( \is_wp_error( $gam_ad_units ) ) {
				return $gam_ad_units;
			}
			$sync_result = self::sync_gam_settings( $gam_ad_units );
			if ( \is_wp_error( $sync_result ) ) {
				return $sync_result;
			}
			$ad_units = array_merge( $ad_units, $gam_ad_units );
		}
		self::$ad_units = $ad_units;
		return self::$ad_units;
	}

	/**
	 * Add a new ad unit.
	 *
	 * @param array $ad_unit The new ad unit info to add.
	 */
	public static function add_ad_unit( $ad_unit ) {
		if ( self::is_gam_connected() ) {
			$result = GAM_API::create_ad_unit( $ad_unit );
			self::sync_gam_settings();
		} else {
			$result = self::legacy_add_ad_unit( $ad_unit );
		}
		return $result;
	}

	/**
	 * Add a new legacy ad unit.
	 *
	 * @param array $ad_unit The new ad unit info to add.
	 */
	private static function legacy_add_ad_unit( $ad_unit ) {
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
			return new \WP_Error(
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
		\add_post_meta( $ad_unit_post, self::FLUID, (bool) $ad_unit[ self::FLUID ] );

		return array(
			'id'        => $ad_unit_post,
			'name'      => $ad_unit['name'],
			self::SIZES => $ad_unit[ self::SIZES ],
			self::CODE  => $ad_unit[ self::CODE ],
			self::FLUID => $ad_unit[ self::FLUID ],
		);
	}

	/**
	 * Update a legacy ad unit.
	 *
	 * @param array $ad_unit The updated ad unit.
	 */
	private static function legacy_update_ad_unit( $ad_unit ) {
		$ad_unit_post = \get_post( $ad_unit['id'] );
		if ( ! is_a( $ad_unit_post, 'WP_Post' ) ) {
			return new \WP_Error(
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
		\update_post_meta( $ad_unit['id'], self::FLUID, (bool) $ad_unit[ self::FLUID ] );
		return array(
			'id'        => $ad_unit['id'],
			'name'      => $ad_unit['name'],
			self::SIZES => $ad_unit[ self::SIZES ],
			self::CODE  => $ad_unit[ self::CODE ],
			self::FLUID => $ad_unit[ self::FLUID ],
		);
	}

	/**
	 * Update an ad unit. Updating the code is not possible, it's set at ad unit creation.
	 *
	 * @param array $ad_unit The updated ad unit.
	 */
	public static function update_ad_unit( $ad_unit ) {
		if ( isset( $ad_unit['is_legacy'] ) && true === $ad_unit['is_legacy'] ) {
			$result = self::legacy_update_ad_unit( $ad_unit );
		} else {
			$result = GAM_API::update_ad_unit( $ad_unit );
			self::sync_gam_settings();
		}
		return $result;
	}

	/**
	 * Delete an ad unit
	 *
	 * @param integer $id The id of the ad unit to delete.
	 */
	public static function delete_ad_unit( $id ) {
		$ad_unit_cpt = \get_post( $id );
		if ( is_a( $ad_unit_cpt, 'WP_Post' ) ) {
			if ( $ad_unit_cpt->post_type === self::$custom_post_type ) {
				\wp_delete_post( $id );
				return true;
			}
		} else {
			$result = GAM_API::change_ad_unit_status( $id, 'ARCHIVE' );
			self::sync_gam_settings();
			return $result;
		}
	}

	/**
	 * Retrieve the active network code.
	 * This might be updateable in the future to enable handling users with
	 * multiple GAM networks - for now simply the first available GAM network
	 * is retrieved.
	 *
	 * @return string The network code.
	 */
	public static function get_active_network_code() {
		$gam_network_code    = get_option( self::OPTION_NAME_GAM_NETWORK_CODE, '' );
		$legacy_network_code = get_option( self::OPTION_NAME_LEGACY_NETWORK_CODE, '' );
		return sanitize_text_field( ! empty( $gam_network_code ) ? $gam_network_code : $legacy_network_code );
	}

	/**
	 * Save GAM configuration locally.
	 * First reason is so the GAM API is not queried on frontend requests - information
	 * about ad units & GAM settings will be read from the DB.
	 * Second reason is backwards compatibility - in a previous version of the plugin,
	 * the sync was done manually.
	 *
	 * @param object[] $serialised_ad_units An array of ad units configurations.
	 * @param object[] $settings Settings to use.
	 */
	public static function sync_gam_settings( $serialised_ad_units = null, $settings = null ) {
		if ( null === $serialised_ad_units ) {
			$serialised_ad_units = GAM_API::get_serialised_gam_ad_units();
		}
		if ( null === $settings ) {
			try {
				$settings = GAM_API::get_gam_settings();
			} catch ( \Exception $e ) {
				return new \WP_Error(
					'newspack_ads_failed_gam_sync',
					__( 'Unable to synchronize with GAM', 'newspack-ads' )
				);
			}
		}

		if ( isset( $settings['network_code'] ) && $serialised_ad_units && ! empty( $serialised_ad_units ) ) {
			$synced_gam_items                              = get_option( self::OPTION_NAME_GAM_ITEMS, [] );
			$network_code                                  = sanitize_text_field( $settings['network_code'] );
			$synced_gam_items[ $network_code ]['ad_units'] = $serialised_ad_units;
			update_option( self::OPTION_NAME_LEGACY_NETWORK_CODE, $network_code );
			update_option( self::OPTION_NAME_GAM_ITEMS, $synced_gam_items );
		}
	}

	/**
	 * The user on whose behalf GAM is authorised might be using
	 * a different GAM account than the one already configured on the site.
	 *
	 * @return boolean True if there is a network code mismatch.
	 */
	private static function is_network_code_matched() {
		$legacy_network_code = get_option( self::OPTION_NAME_LEGACY_NETWORK_CODE, '' );
		if ( empty( $legacy_network_code ) ) {
			// No network code set yet.
			return true;
		}
		try {
			$gam_network_code = get_option( self::OPTION_NAME_GAM_NETWORK_CODE, '' );

			// Active Network Code might be a comma-delimited list of codes.
			return array_reduce(
				explode( ',', $legacy_network_code ),
				function( $valid_code, $code ) use ( $gam_network_code ) {
					if ( absint( $code ) === absint( $gam_network_code ) ) {
						$valid_code = true;
					}
					return $valid_code;
				},
				false
			);
		} catch ( \Exception $e ) {
			// Can't get user's network code.
			return false;
		}
	}

	/**
	 * Retrieve the synced GAM items.
	 *
	 * @return array[] GAM items.
	 */
	private static function get_synced_gam_items() {
		$network_code     = self::get_active_network_code();
		$synced_gam_items = get_option( self::OPTION_NAME_GAM_ITEMS, null );
		if ( $network_code && $synced_gam_items && isset( $synced_gam_items[ $network_code ] ) ) {
			return $synced_gam_items[ $network_code ];
		}
		return null;
	}

	/**
	 * Retrieve the synced GAM items.
	 *
	 * @return array[] GAM items.
	 */
	private static function get_synced_gam_ad_units() {
		$gam_items = self::get_synced_gam_items();
		if ( $gam_items ) {
			return $gam_items['ad_units'];
		}
		return [];
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
	 * Code for ad unit.
	 *
	 * @param array  $ad_unit   The ad unit to generate code for.
	 * @param string $unique_id The unique ID for this ad displayment.
	 */
	public static function get_ad_unit_code( $ad_unit, $unique_id = '' ) {
		$sizes        = $ad_unit['sizes'];
		$code         = $ad_unit['code'];
		$network_code = self::get_active_network_code();
		$unique_id    = $unique_id ?? uniqid();
		if ( ! is_array( $sizes ) ) {
			$sizes = [];
		}

		// Remove all ad sizes greater than 600px wide for sticky ads.
		if ( self::is_sticky( $ad_unit ) ) {
			$sizes = array_filter(
				$sizes,
				function( $size ) {
					return $size[0] < 600;
				}
			);
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
	 * @param array  $ad_unit   The ad unit to generate AMP code for.
	 * @param string $unique_id Optional pre-defined unique ID for this ad displayment.
	 */
	public static function get_ad_unit_amp_code( $ad_unit, $unique_id = '' ) {
		$sizes        = $ad_unit['sizes'];
		$code         = $ad_unit['code'];
		$network_code = self::get_active_network_code();
		$targeting    = self::get_ad_targeting( $ad_unit );
		$unique_id    = $unique_id ?? uniqid();

		if ( ! is_array( $sizes ) ) {
			$sizes = [];
		}

		// Remove all ad sizes greater than 600px wide for sticky ads.
		if ( self::is_sticky( $ad_unit ) ) {
			$sizes = array_filter(
				$sizes,
				function( $size ) {
					return $size[0] < 600;
				}
			);
		}

		$size_map = self::get_ad_unit_size_map( $ad_unit, $sizes );

		/**
		 * Legacy filter for custom size map.
		 */
		$size_map = apply_filters( 'newspack_ads_multisize_ad_sizes', $size_map, $ad_unit );

		// Do not use responsive strategy if the size map only results in one viewport or the ad unit is fluid.
		if ( 1 < count( $size_map ) && false === $ad_unit['fluid'] ) {
			return self::get_ad_unit_responsive_amp_code( $unique_id, $ad_unit, $size_map );
		}

		$attrs      = [];
		$multisizes = [];

		if ( true === $ad_unit['fluid'] ) {
			$attrs['height'] = 'fluid';
			$attrs['layout'] = 'fluid';
			$multisizes[]    = 'fluid';
		}

		if ( count( $sizes ) ) {
			// Sort sizes by squareness.
			usort(
				$sizes,
				function( $a, $b ) {
					return $a[0] * $a[1] < $b[0] * $b[1];
				}
			);
			if ( ! isset( $attrs['layout'] ) ) {
				$attrs['width']  = max( array_column( $sizes, 0 ) );
				$attrs['height'] = max( array_column( $sizes, 1 ) );
				$attrs['layout'] = 'fixed';
			}
			foreach ( $sizes as $size ) {
				$multisizes[] = $size[0] . 'x' . $size[1];
			}
		}

		if ( 1 < count( $multisizes ) ) {
			$attrs['data-multi-size']            = implode( ',', $multisizes );
			$attrs['data-multi-size-validation'] = 'false';
		}

		$attrs['type']                  = 'doubleclick';
		$attrs['data-slot']             = sprintf( '/%s/%s', $network_code, $code );
		$attrs['data-loading-strategy'] = 'prefer-viewability-over-views';
		$attrs['json']                  = sprintf( '{"targeting": %s}', wp_json_encode( $targeting ) );

		$code = sprintf(
			'<amp-ad %s></amp-ad>',
			implode(
				' ',
				array_map(
					function( $key, $value ) {
						return sprintf( "%s='%s'", $key, $value );
					},
					array_keys( $attrs ),
					array_values( $attrs )
				)
			)
		);

		return $code;
	}

	/**
	 * Get size map for responsive ads.
	 * 
	 * Gather up all of the ad sizes which should be displayed on the same
	 * viewports. As a heuristic, each ad slot can safely display ads with a 30%
	 * difference from slot's width. e.g. for the following setup: [[300,200],
	 * [350,200]], we can display [[300,200], [350,200]] on viewports >= 350px
	 * and [[300,200]] on viewports <= 300px.
	 *
	 * Sizes above the determined width threshold (default to 600) will not have
	 * their ratio difference considered and will always share viewport with the
	 * next largest size. e.g. for the following setup: [[640,320], [960,540]],
	 * we can display [[640,320], [960,540]] on viewports >= 960px even though
	 * the ratio difference is higher than the default 30%.
	 *
	 * @param array[]   $sizes            Array of sizes.
	 * @param float     $width_diff_ratio Minimum width ratio difference for sizes to share same viewport.
	 * @param int|false $width_threshold  Width threshold to ignore ratio difference. False disables threshold.
	 *
	 * @return array[] Size map keyed by the viewport width.
	 */
	public static function get_responsive_size_map( $sizes, $width_diff_ratio = 0.3, $width_threshold = 600 ) {

		array_multisort( $sizes );
	
		// Each existing size's width is size map viewport.
		$viewports = array_unique( array_column( $sizes, 0 ) );

		$size_map = [];
		foreach ( $viewports as $viewport_width ) {
			foreach ( $sizes as $size ) {
				$is_in_viewport     = $size[0] <= $viewport_width;
				$is_above_threshold = false !== $width_threshold && $width_threshold <= $size[0];
				$diff               = min( $viewport_width, $size[0] ) / max( $viewport_width, $size[0] );
				$is_within_ratio    = ( 1 - $width_diff_ratio ) <= $diff;
				if ( $is_in_viewport && ( $is_within_ratio || $is_above_threshold ) ) {
					$size_map[ $viewport_width ][] = $size;
				}
			}
		}
		return $size_map;
	}

	/**
	 * Get the size map for an ad unit.
	 *
	 * @param array $ad_unit Ad unit.
	 * @param array $sizes   Optional array of sizes to use.
	 *
	 * @return array Size map keyed by the viewport width.
	 */
	public static function get_ad_unit_size_map( $ad_unit, $sizes = [] ) {

		if ( empty( $sizes ) ) {
			$sizes = $ad_unit['sizes'];
		}

		/**
		 * Filters the ad unit size map difference ratio.
		 *
		 * @param float   $width_diff_ratio The width diff ratio.
		 * @param array   $ad_unit          The ad unit config.
		 * @param array[] $sizes            The sizes being used.
		 */
		$width_diff_ratio = apply_filters( 'newspack_ads_gam_size_map_diff_ratio', 0.3, $ad_unit, $sizes );

		/**
		 * Filters the ad unit size map width threshold.
		 *
		 * @param int     $width_threshold The width threshold.
		 * @param array   $ad_unit         The ad unit config.
		 * @param array[] $sizes           The sizes being used.
		 */
		$width_threshold = apply_filters( 'newspack_ads_gam_size_map_width_threshold', 600, $ad_unit, $sizes );

		/**
		 * Filters the ad unit size map rules.
		 *
		 * @param array[] $size_map The size map array.
		 * @param array   $ad_unit  The ad unit config.
		 * @param array[] $sizes    The sizes being used.
		 */
		$size_map = apply_filters(
			'newspack_ads_gam_size_map',
			self::get_responsive_size_map( $sizes, $width_diff_ratio, $width_threshold ),
			$ad_unit,
			$sizes
		);

		return $size_map;
	}

	/**
	 * Generate responsive AMP ads for a series of ad sizes.
	 *
	 * @param string $unique_id Unique ID for this ad unit instance.
	 * @param array  $ad_unit   The ad unit to generate code for.
	 * @param array  $size_map  The size map.
	 */
	public static function get_ad_unit_responsive_amp_code( $unique_id, $ad_unit, $size_map ) {
		$network_code = self::get_active_network_code();
		$code         = $ad_unit['code'];
		$sizes        = $ad_unit['sizes'];
		$targeting    = self::get_ad_targeting( $ad_unit );

		$markup = [];
		$styles = [];

		// Build the amp-ad units according to size map.
		foreach ( $size_map as $viewport_width => $ad_sizes ) {

			// The size of the ad container should be equal to the largest width and height among all the sizes available.
			$width  = $viewport_width;
			$height = absint( max( array_column( $ad_sizes, 1 ) ) );

			$multisizes = array_map( '\Newspack_Ads\get_size_string', $ad_sizes );

			// If there is a multisize that's equal to the width and height of the container, remove it from the multisizes.
			// The container size is included by default, and should not also be included in the multisize.
			$container_multisize           = $width . 'x' . $height;
			$container_multisize_locations = array_keys( $multisizes, $container_multisize );
			foreach ( $container_multisize_locations as $container_multisize_location ) {
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
			$media_query_elements   = [];
			$media_query_elements[] = sprintf( '(min-width:%dpx)', $viewport_width );
			$index                  = array_search( $viewport_width, array_keys( $size_map ) );
			// If there are ad sizes larger than the current size, the max_width is 1 less than the next ad's size.
			// If it's the largest ad size, there is no max width.
			if ( count( $size_map ) > $index + 1 ) {
				$max_width              = absint( array_keys( $size_map )[ $index + 1 ] ) - 1;
				$media_query_elements[] = sprintf( '(max-width:%dpx)', $max_width );
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

			$template_slug = get_page_template_slug();
			if ( ! empty( $template_slug ) ) {
				$targeting['template'] = sanitize_title( basename( $template_slug, '.php' ) );
			}

			// Add the category slugs to targeting.
			$categories = wp_get_post_categories( get_the_ID(), [ 'fields' => 'slugs' ] );
			if ( ! empty( $categories ) ) {
				$targeting['category'] = array_map( 'sanitize_text_field', $categories );
			}

			// Add post type to targeting.
			$targeting['post_type'] = get_post_type();

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

	/**
	 * Is GAM connected?
	 *
	 * @return boolean True if GAM is connected.
	 */
	public static function is_gam_connected() {
		$status = GAM_API::connection_status();
		return $status['connected'];
	}

	/**
	 * Get GAM connection status.
	 *
	 * @return object Object with status information.
	 */
	public static function get_gam_connection_status() {
		$status = GAM_API::connection_status();
		if ( isset( $status['network_code'] ) ) {
			update_option( self::OPTION_NAME_GAM_NETWORK_CODE, $status['network_code'] );
		} else {
			$status['network_code'] = self::get_active_network_code();
		}
		if ( true === $status['connected'] ) {
			$status['is_network_code_matched'] = self::is_network_code_matched();
		}
		return $status;
	}

	/**
	 * Get GAM available networks.
	 *
	 * @return array[] Array of available networks. Empty array if no networks found or unable to fetch.
	 */
	public static function get_gam_available_networks() {
		try {
			$networks = GAM_API::get_serialized_gam_networks();
		} catch ( \Exception $e ) {
			$networks = [];
		}
		return $networks;
	}

	/**
	 * Get global ad suppresion config.
	 */
	public static function get_suppression_config() {
		return get_option(
			self::OPTION_NAME_GLOBAL_AD_SUPPRESSION,
			[
				'tag_archive_pages'               => false,
				'specific_tag_archive_pages'      => [],
				'category_archive_pages'          => false,
				'specific_category_archive_pages' => [],
				'author_archive_pages'            => false,
			]
		);
	}

	/**
	 * Update global ad suppresion config.
	 *
	 * @param array $config Updated config.
	 */
	public static function update_suppression_config( $config ) {
		update_option( self::OPTION_NAME_GLOBAL_AD_SUPPRESSION, $config );
	}
}
GAM_Model::init();
