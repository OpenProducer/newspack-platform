<?php
/**
 * Newspack Ads Google Ad Manager Provider Scripts.
 *
 * @package Newspack
 */

namespace Newspack_Ads\Providers;

use Newspack_Ads\Core;
use Newspack_Ads\Settings;
use Newspack_Ads\Providers;
use Newspack_Ads\Providers\GAM_Model;

defined( 'ABSPATH' ) || exit;

/**
 * Google Ad Manager Scripts.
 */
final class GAM_Scripts {

	/**
	 * Initialize scripts
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_head', [ __CLASS__, 'insert_gpt_header_script' ], 1 );
		add_action( 'wp_footer', [ __CLASS__, 'insert_gpt_footer_script' ] );
		add_action( 'newspack_ads_before_placement_ad', [ __CLASS__, 'print_fixed_height_css' ], 10, 3 );
	}

	/**
	 * Google Publisher Tag header script.
	 */
	public static function insert_gpt_header_script() {
		if ( ! \newspack_ads_should_show_ads() ) {
			return;
		}
		if ( ! Providers::is_provider_active( 'gam' ) ) {
			return;
		}
		if ( Core::is_amp() ) {
			return;
		}
		?>
		<script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js" data-amp-plus-allowed></script>
		<script data-amp-plus-allowed>
			window.googletag = window.googletag || { cmd: [] };
		</script>
		<?php
	}

	/**
	 * Google Publisher Tag configuration script.
	 */
	public static function insert_gpt_footer_script() {
		if ( ! \newspack_ads_should_show_ads() ) {
			return;
		}
		if ( ! Providers::is_provider_active( 'gam' ) ) {
			return;
		}
		if ( Core::is_amp() ) {
			return;
		}

		$network_code = GAM_Model::get_active_network_code();

		$prepared_unit_data = [];
		foreach ( GAM_Model::$slots as $unique_id => $ad_unit ) {
			$ad_targeting = GAM_Model::get_ad_targeting( $ad_unit );

			$container_id = esc_attr( 'div-gpt-ad-' . $unique_id . '-0' );
			$sizes        = $ad_unit['sizes'];

			/**
			 * Filters which container elements should restrict the bounds of its
			 * inner ads.
			 *
			 * @param string[] $bounds_selectors The selectors to restrict bounds.
			 * @param array    $ad_unit          Ad unit data.
			 * @param array    $sizes            Ad unit sizes.
			 */
			$bounds_selectors = apply_filters(
				'newspack_ads_gam_bounds_selectors',
				[
					'.wp-block-column',
					'.entry-content',
					'.sidebar',
					'.widget-area',
				],
				$ad_unit,
				$sizes
			);

			/**
			 * Filters the bleed allowed to extrapolate the ad container bounds in
			 * case the bounds are strict.
			 *
			 * @param int   $bounds_bleed The amount of bleed allowed.
			 * @param array $ad_unit      Ad unit data.
			 * @param array $sizes        Ad unit sizes.
			 */
			$bounds_bleed = apply_filters( 'newspack_ads_gam_bounds_bleed', 40, $ad_unit, $sizes );

			$prepared_unit_data[ $container_id ] = [
				'unique_id'        => $unique_id,
				'name'             => esc_attr( $ad_unit['name'] ),
				'code'             => esc_attr( $ad_unit['code'] ),
				'sizes'            => $sizes,
				'fluid'            => (bool) $ad_unit['fluid'],
				'fixed_height'     => (bool) $ad_unit['fixed_height'],
				'targeting'        => $ad_targeting,
				'sticky'           => GAM_Model::is_sticky( $ad_unit ),
				'size_map'         => GAM_Model::get_ad_unit_size_map( $ad_unit ),
				'bounds_selectors' => $bounds_selectors,
				'bounds_bleed'     => (int) $bounds_bleed ?? 0,
			];
		}

		// Gather common targeting data and remove from ad unit targeting.
		$common_targeting = [];
		if ( 1 < count( $prepared_unit_data ) ) {
			$common_targeting = array_uintersect_assoc(
				...array_values( array_column( $prepared_unit_data, 'targeting' ) ),
				...[
					function( $a, $b ) {
							return $a === $b ? 0 : 1;
					},
				]
			);
			foreach ( $prepared_unit_data as $container_id => $ad_unit ) {
				$prepared_unit_data[ $container_id ]['targeting'] = array_diff_key( $ad_unit['targeting'], $common_targeting );
			}
		}

		$ad_config = [
			'network_code'         => esc_attr( $network_code ),
			'disable_initial_load' => (bool) apply_filters( 'newspack_ads_disable_gtag_initial_load', false ),
		];

		/**
		 * Filters the ads data parsed for gtag.
		 *
		 * @param array $data {
		 *   Ads data parsed for gtag inline script.
		 *
		 *   @type string $unique_id Unique ID for the ad unit.
		 *   @type string $name      Ad name.
		 *   @type string $code      Ad code.
		 *   @type array  $sizes     Ad sizes.
		 *   @type bool   $fluid     Whether the ad is fluid.
		 *   @type array  $targeting Ad targeting.
		 *   @type bool   $sticky    Whether the ad is sticky.
		 * }
		 */
		$prepared_unit_data = apply_filters( 'newspack_ads_gtag_ads_data', $prepared_unit_data );

		do_action( 'newspack_ads_gtag_before_script', $ad_config, $prepared_unit_data );
		?>
		<script data-amp-plus-allowed>
			( function() {
				var ad_config        = <?php echo wp_json_encode( $ad_config ); ?>;
				var all_ad_units     = <?php echo wp_json_encode( $prepared_unit_data ); ?>;
				var lazy_load        = <?php echo wp_json_encode( Settings::get_settings( 'lazy_load', true ), JSON_FORCE_OBJECT ); ?>;
				var common_targeting = <?php echo wp_json_encode( $common_targeting ); ?>;
				var defined_ad_units = {};

				var boundsContainers = {};

				function inOrPastViewport( element ) {
					var bounding = element.getBoundingClientRect();
					return (
						bounding.right <= ( window.innerWidth || document.documentElement.clientWidth ) &&
						bounding.bottom <= ( window.innerHeight || document.documentElement.clientHeight )
					);
				}

				for ( var container_id in all_ad_units ) {
					var ad_unit = all_ad_units[ container_id ];
					var container = document.querySelector( '#' + container_id );
					if ( ! container ) {
						continue;
					}
					ad_unit.in_viewport = inOrPastViewport( container );
					container.ad_unit = ad_unit;
					<?php
					/**
					 * Identify the bounds container for this slot and use its offset
					 * width as bounds width.
					 */
					?>
					var boundsWidth = 0;
					findContainer:
					for ( var i = 0; i < ad_unit['bounds_selectors'].length; i++ ) {
						var selector = ad_unit['bounds_selectors'][ i ];
						if ( typeof boundsContainers[ selector ] === 'undefined' ) {
							boundsContainers[ selector ] = document.querySelectorAll( selector );
						}
						if ( boundsContainers[ selector ].length ) {
							for( var j = 0; j < boundsContainers[ selector ].length; j++ ) {
								var boundsContainer = boundsContainers[ selector ][ j ];
								if ( boundsContainer.contains( container ) ) {
									boundsWidth = boundsContainer.offsetWidth;
									break findContainer;
								}
							}
						}
					}
					<?php
					/**
					 * Iterate and remove size map rules with viewport larger than the
					 * container width, if a bounds container is identified.
					 *
					 * The available width is the bigger of the bounds container width or
					 * the direct parent offset width.
					 */
					?>
					var shouldUseBounds = !! boundsWidth;
					var availableWidth = window.innerWidth;
					if ( shouldUseBounds ) {
						var containerWidth = container.parentNode.offsetWidth;
						availableWidth = Math.max( boundsWidth, containerWidth ) + parseInt( ad_unit['bounds_bleed'] );
						for ( viewportWidth in ad_unit['size_map'] ) {
							var width = parseInt( viewportWidth );
							if ( shouldUseBounds && width > availableWidth ) {
								delete ad_unit['size_map'][ viewportWidth ];
							}
						}
					}
					<?php
					/**
					 * Set fixed height on parentNode for slots within viewport. Slots
					 * outside of the viewport will have 'auto' height.
					 */
					?>
					if ( ad_unit.fixed_height ) {
						var height = 'auto';
						if ( ad_unit.in_viewport ) {
							for ( viewportWidth in ad_unit.size_map ) {
								if ( viewportWidth < availableWidth ) {
									height = 0;
									for ( size in ad_unit.size_map[ viewportWidth ] ) {
										height = Math.max( height, ad_unit.size_map[ viewportWidth ][ size ][1] );
									}
								}
							}
							height = height + 'px';
						}
						container.parentNode.style.height = height;
					}
				}
				googletag.cmd.push(function() {
					for ( var container_id in all_ad_units ) {
						var ad_unit = all_ad_units[ container_id ];
						var container = document.querySelector( '#' + container_id );
						if ( ! container ) {
							continue;
						}

						var slotSizes = ad_unit['sizes'];
						if ( ad_unit['fluid'] ) {
							slotSizes = slotSizes.concat( 'fluid' );
						}

						defined_ad_units[ container_id ] = googletag.defineSlot(
							'/' + ad_config['network_code'] + '/' + ad_unit['code'],
							slotSizes,
							container_id
						).addService( googletag.pubads() );

						for ( var target_key in common_targeting ) {
							defined_ad_units[ container_id ].setTargeting( target_key, common_targeting[ target_key ] );
						}
						for ( var target_key in ad_unit['targeting'] ) {
							defined_ad_units[ container_id ].setTargeting( target_key, ad_unit['targeting'][ target_key ] );
						}

						<?php
						/**
						 * Build and set the responsive mapping.
						 *
						 * @see https://developers.google.com/doubleclick-gpt/guides/ad-sizes#responsive_ads
						 */
						?>
						var mapping = googletag.sizeMapping();
						<?php
						// Default base is to not show ads.
						?>
						var baseSizes = [];
						<?php
						// If the ad unit is fluid, base includes fluid.
						?>
						if( ad_unit['fluid'] ) {
							baseSizes = baseSizes.concat( 'fluid' );
						}
						<?php
						// Apply size map rules.
						?>
						for ( viewportWidth in ad_unit['size_map'] ) {
							var width = parseInt( viewportWidth );
							var mappedSizes = ad_unit['size_map'][ viewportWidth ];
							mapping.addSize( [ width, 0 ], baseSizes.concat( mappedSizes ) );
						}
						<?php
						// Sticky ads should only be shown on mobile (screen width <=600px).
						?>
						if ( ad_unit['sticky'] ) {
							mapping.addSize( [600, 0], baseSizes );
						}
						<?php
						// On viewports smaller than the smallest ad size, don't show any ads.
						?>
						mapping.addSize( [0, 0], baseSizes );
						defined_ad_units[ container_id ].defineSizeMapping( mapping.build() );
					}

					if ( ad_config['disable_initial_load'] ) {
						googletag.pubads().disableInitialLoad();
					}
					googletag.pubads().collapseEmptyDivs();
					googletag.pubads().enableSingleRequest();
					if ( lazy_load && lazy_load.active ) {
						googletag.pubads().enableLazyLoad( {
							fetchMarginPercent: lazy_load.fetch_margin_percent,
							renderMarginPercent: lazy_load.render_margin_percent,
							mobileScaling: lazy_load.mobile_scaling
						} );
					}
					googletag.enableServices();

					for ( var container_id in defined_ad_units ) {
						googletag.display( container_id );
					}

					googletag.pubads().addEventListener( 'slotRenderEnded', function( event ) {
						var container = document.getElementById( event.slot.getSlotElementId() );
						if ( ! container ) {
							return;
						}
						var ad_unit = container.ad_unit;
						if ( ! ad_unit ) {
							return;
						}
						<?php
						/**
						 * Lock fixed height ad unit size mapping to prevent larger
						 * creatives to render on refresh control.
						 */
						?>
						if ( ad_unit.fixed_height && container.parentNode.style.height === 'auto' && event.size ) {
							container.parentNode.style.height = event.size[1] + 'px';
							event.slot.defineSizeMapping( googletag.sizeMapping().addSize( [ 0, 0 ], event.size ).build() );
						}
						<?php
						/**
						 * Handle slot visibility.
						 */
						?>
						if ( event.isEmpty && ( ! ad_unit.fixed_height || ( ad_unit.fixed_height && ! ad_unit.in_viewport ) ) ) {
							container.parentNode.style.display = 'none';
						} else {
							container.parentNode.style.display = 'flex';
						}
						<?php
						/**
						 * Identify fluid rendered ad and fix iframe width.
						 * GPT currently sets the iframe with `min-width` set to 100% and property `width` set to 0.
						 * This causes the iframe to be rendered with 0 width.
						 */
						?>
						var sizes = event.slot.getSizes();
						if (
							( event.size === null || event.size[0] === 0 ) &&
							Array.isArray( sizes ) && sizes.indexOf( 'fluid' ) !== -1
						) {
							if ( container ) {
								var iframe = container.querySelector( 'iframe' );
								if ( iframe ) {
									iframe.style.width = '100%';
								}
							}
						}
					} );
				} );
			} )();
		</script>
		<?php
		do_action( 'newspack_ads_gtag_after_script', $ad_config, $prepared_unit_data );
	}

	/**
	 * Print media queries CSS to apply a fixed height to placements.
	 *
	 * @param string $placement_key  The placement key.
	 * @param string $hook_key       The placement hook hey.
	 * @param array  $placement_data The placement data.
	 */
	public static function print_fixed_height_css( $placement_key, $hook_key, $placement_data ) {
		if ( 'gam' !== $placement_data['provider'] ) {
			return;
		}
		if ( ! isset( $placement_data['fixed_height'] ) || ! $placement_data['fixed_height'] ) {
			return;
		}

		$ad_units    = GAM_Model::get_ad_units( false );
		$ad_unit_idx = array_search( $placement_data['ad_unit'], array_column( $ad_units, 'id' ) );
		if ( false === $ad_unit_idx ) {
			return;
		}
		$ad_unit      = $ad_units[ $ad_unit_idx ];
		$container_id = esc_attr( sprintf( '.newspack_global_ad.%s', $placement_key ) );
		$size_map     = GAM_Model::get_ad_unit_size_map( $ad_unit );
		echo '<style>';
		foreach ( $size_map as $viewport_width => $sizes ) {
			$height = max( array_column( $sizes, 1 ) );
			$css    = sprintf( ' @media ( min-width: %1$dpx ) { %2$s { height: %3$dpx; } } ', $viewport_width, $container_id, $height );
			echo esc_html( $css );
		}
		echo '</style>';
	}
}
GAM_Scripts::init();
