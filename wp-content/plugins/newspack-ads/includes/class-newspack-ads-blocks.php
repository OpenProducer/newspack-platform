<?php
/**
 * Newspack Ads Block Management
 *
 * @package Newspack
 */

/**
 * Newspack Ads Blocks Management
 */
class Newspack_Ads_Blocks {

	/**
	 * Initialize blocks
	 *
	 * @return void
	 */
	public static function init() {
		require_once NEWSPACK_ADS_ABSPATH . 'src/blocks/ad-unit/view.php';
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_block_editor_assets' ) );
		add_action( 'wp_head', array( __CLASS__, 'insert_google_ad_manager_header_code' ), 30 );
		add_action( 'wp_footer', array( __CLASS__, 'insert_google_ad_manager_footer_code' ), 30 );
	}

	/**
	 * Enqueue block scripts and styles for editor.
	 */
	public static function enqueue_block_editor_assets() {
		$editor_script = Newspack_Ads::plugin_url( 'dist/editor.js' );
		$editor_style  = Newspack_Ads::plugin_url( 'dist/editor.css' );
		$dependencies  = self::dependencies_from_path( NEWSPACK_ADS_ABSPATH . 'dist/editor.deps.json' );
		wp_enqueue_script(
			'newspack-ads-editor',
			$editor_script,
			$dependencies,
			NEWSPACK_ADS_VERSION,
			true
		);
		wp_enqueue_style(
			'newspack-ads-editor',
			$editor_style,
			array(),
			NEWSPACK_ADS_VERSION
		);
	}

	/**
	 * Parse generated .deps.json file and return array of dependencies to be enqueued.
	 *
	 * @param string $path Path to the generated dependencies file.
	 *
	 * @return array Array of dependencides.
	 */
	public static function dependencies_from_path( $path ) {
		// TODO: use this better approach: https://github.com/Automattic/newspack-blocks/blob/master/class-newspack-blocks.php#L27-L44.
		$dependencies = file_exists( $path )
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
			? json_decode( file_get_contents( $path ) )
			: array();
		$dependencies[] = 'wp-polyfill';
		return $dependencies;
	}

	/**
	 * Utility to assemble the class for a server-side rendered bloc
	 *
	 * @param string $type The block type.
	 * @param array  $attributes Block attributes.
	 *
	 * @return string Class list separated by spaces.
	 */
	public static function block_classes( $type, $attributes = array() ) {
		$align   = isset( $attributes['align'] ) ? $attributes['align'] : 'none';
		$classes = array(
			"wp-block-newspack-blocks-{$type}",
			"align{$align}",
		);
		if ( isset( $attributes['className'] ) ) {
			array_push( $classes, $attributes['className'] );
		}
		return implode( ' ', $classes );
	}

	/**
	 * Enqueue view scripts and styles for a single block.
	 *
	 * @param string $type The block's type.
	 */
	public static function enqueue_view_assets( $type ) {
		$style_path  = Newspack_Ads::plugin_url( 'dist/{$type}/view' . ( is_rtl() ? '.rtl' : '' ) . '.css' );
		$script_path = Newspack_Ads::plugin_url( 'dist/{$type}/view.js' );
		if ( file_exists( NEWSPACK_ADS_ABSPATH . $style_path ) ) {
			wp_enqueue_style(
				"newspack-blocks-{$type}",
				plugins_url( $style_path, __FILE__ ),
				array(),
				NEWSPACK_ADS_VERSION
			);
		}
		if ( file_exists( NEWSPACK_ADS_ABSPATH . $script_path ) ) {
			$dependencies = self::dependencies_from_path( Newspack_Ads::plugin_url( 'dist/{$type}/view.deps.json' ) );
			wp_enqueue_script(
				"newspack-blocks-{$type}",
				plugins_url( $script_path, __FILE__ ),
				$dependencies,
				array(),
				NEWSPACK_ADS_VERSION
			);
		}

		wp_register_style( "newspack-blocks-{$type}", false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_add_inline_style( "newspack-blocks-{$type}", '.wp-block-newspack-blocks-wp-block-newspack-ads-blocks-ad-unit.aligncenter > div { margin-left: auto; margin-right: auto; }' );
		wp_enqueue_style( "newspack-blocks-{$type}" );
	}

	/**
	 * Google Ad Manager header code
	 */
	public static function insert_google_ad_manager_header_code() {
		if ( ! newspack_ads_should_show_ads() ) {
			return;
		}

		if ( Newspack_Ads::is_amp() ) {
			return;
		}
		ob_start();
		?>
		<script async src="https://securepubads.g.doubleclick.net/tag/js/gpt.js" data-amp-plus-allowed></script>
		<script data-amp-plus-allowed>
			window.googletag = window.googletag || {cmd: []};
		</script>
		<?php
		$code = ob_get_clean();
		echo $code; //phpcs:ignore
	}

	/**
	 * Google Ad Manager footer code
	 */
	public static function insert_google_ad_manager_footer_code() {
		if ( ! newspack_ads_should_show_ads() ) {
			return;
		}

		if ( Newspack_Ads::is_amp() ) {
			return;
		}

		$network_code = Newspack_Ads_Model::get_active_network_code();

		$prepared_unit_data = [];
		foreach ( Newspack_Ads_Model::$ad_ids as $unique_id => $ad_unit ) {
			$ad_targeting = Newspack_Ads_Model::get_ad_targeting( $ad_unit );

			$container_id = esc_attr( 'div-gpt-ad-' . $unique_id . '-0' );
			$sizes        = $ad_unit['sizes'];

			if ( ! is_array( $sizes ) ) {
				$sizes = [];
			}

			// Remove all ad sizes greater than 600px wide for sticky ads.
			if ( Newspack_Ads_Model::is_sticky( $ad_unit ) ) {
				$sizes = array_filter(
					$sizes,
					function( $size ) {
						return $size[0] < 600;
					}
				);
			}

			$prepared_unit_data[ $container_id ] = [
				'name'      => esc_attr( $ad_unit['name'] ),
				'code'      => esc_attr( $ad_unit['code'] ),
				'sizes'     => array_values( $sizes ),
				'fluid'     => (bool) $ad_unit['fluid'],
				'targeting' => $ad_targeting,
				'sticky'    => Newspack_Ads_Model::is_sticky( $ad_unit ),
			];
		}

		$ad_config = [
			'network_code'         => esc_attr( $network_code ),
			'disable_initial_load' => (bool) apply_filters( 'newspack_ads_disable_gtag_initial_load', false ),
		];

		ob_start();
		?>
		<script data-amp-plus-allowed>
			googletag.cmd.push(function() {
				var ad_config        = <?php echo wp_json_encode( $ad_config ); ?>;
				var all_ad_units     = <?php echo wp_json_encode( $prepared_unit_data ); ?>;
				var lazy_load        = <?php echo wp_json_encode( Newspack_Ads_Settings::get_settings( 'lazy_load', true ), JSON_FORCE_OBJECT ); ?>;
				var defined_ad_units = {};

				for ( var container_id in all_ad_units ) {
					var ad_unit = all_ad_units[ container_id ];

					// Only set up ad units that are present on the page.
					if ( ! document.querySelector( '#' + container_id ) ) {
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

					for ( var target_key in ad_unit['targeting'] ) {
						defined_ad_units[ container_id ].setTargeting( target_key, ad_unit['targeting'][ target_key ] );
					}

					/**
					 * Configure responsive ads.
					 * Ads wider than the viewport should not show.
					 */

					// Get all of the unique ad widths.
					var unique_widths = {};
					ad_unit['sizes'].forEach( function( size ) {
						unique_widths[ size[0] ] = [];
					} );

					// For each width, get all of the sizes equal-to-or-smaller than it.
					for ( width in unique_widths ) {
						ad_unit['sizes'].forEach( function( size ) {
							if ( size[0] <= width ) {
								unique_widths[ width ].push( size );
							}
						} );
					}

					// Build and set the responsive mapping.
					// @see https://developers.google.com/doubleclick-gpt/guides/ad-sizes#responsive_ads
					var mapping = googletag.sizeMapping();

					var mobile_cutoff = 500; // As a rule of thumb, let's say mobile ads are <500px wide and desktop ads are >=500px wide.

					var smallest_ad_width = Math.min.apply( Math, Object.keys( unique_widths ).map( Number ) );
					var largest_ad_width  = Math.max.apply( Math, Object.keys( unique_widths ).map( Number ) );

					// Default base is to not show ads.
					var baseSizes = [];
					// If the ad unit is fluid, base includes fluid.
					if( ad_unit['fluid'] ) {
						baseSizes = baseSizes.concat( 'fluid' );
					}

					// If the smallest width is mobile size and the largest width is desktop size,
					// we want to use some logic to prevent displaying mobile ads on desktop.
					if ( smallest_ad_width < mobile_cutoff && largest_ad_width >= mobile_cutoff ) {
						for ( width in unique_widths ) {
							// On viewports < 500px wide, include all ads smaller than the viewport.
							if ( parseInt( width ) < mobile_cutoff ) {
								mapping.addSize( [ parseInt( width ), 0 ], baseSizes.concat( unique_widths[ width ] ) );

								// On viewports >= 500px wide, only include ads with widths >= 500px.
							} else {
								var desktopAds = [];
								for ( array_index in unique_widths[ width ] ) {
									ad_size = unique_widths[ width ][ array_index ];
									if ( ad_size[0] >= mobile_cutoff ) {
										desktopAds.push( ad_size );
									}
								}
								mapping.addSize( [ parseInt( width ), 0 ], baseSizes.concat( desktopAds ) );
							}
						}

						// If the sizes don't contain both mobile and desktop ad sizes,
						// we can just display any ad that is smaller than the viewport.
					} else {
						for ( width in unique_widths ) {
							mapping.addSize( [ parseInt( width ), 0 ], baseSizes.concat( unique_widths[ width ] ) );
						}
					}

					// Sticky ads should only be shown on mobile (screen width <=600px).
					if ( ad_unit['sticky'] ) {
						mapping.addSize( [600, 0], baseSizes );
					}

					// On viewports smaller than the smallest ad size, don't show any ads.
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
				// Identify fluid rendered ad and fix iframe width.
				// GPT currently sets the iframe with `min-width` set to 100% and property `width` set to 0.
				// This causes the iframe to be rendered with 0 width.
				googletag.pubads().addEventListener( 'slotRenderEnded', function(event) {
					var sizes = event.slot.getSizes();
					if (
						( event.size === null || event.size[0] === 0 ) &&
						Array.isArray( sizes ) && sizes.indexOf( 'fluid' ) !== -1
					) {
						var container = document.getElementById( event.slot.getSlotElementId() );
						if ( container ) {
							var iframe = container.querySelector( 'iframe' );
							if ( iframe ) {
								iframe.style.width = '100%';
							}
						}
					}
				} );
			} );
		</script>
		<?php
		$code = ob_get_clean();
		echo $code; // phpcs:ignore
	}
}
Newspack_Ads_Blocks::init();
