<?php
/**
 * Newspack Popups Inserter
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/../api/segmentation/class-segmentation.php';

/**
 * API endpoints
 */
final class Newspack_Popups_Inserter {
	/**
	 * The popup objects to display.
	 *
	 * @var array
	 */
	protected static $popups = [];

	/**
	 * Whether we've already inserted prompts into the content.
	 * If we've already inserted popups into the content, don't try to do it again.
	 *
	 * @var boolean
	 */
	public static $the_content_has_rendered = false;

	/**
	 * Retrieve the appropriate popups for the current post.
	 *
	 * @return array Popup objects.
	 */
	public static function popups_for_post() {
		// Inject prompts only in posts, pages, and CPTs that explicitly opt in.
		if ( ! in_array(
			get_post_type(),
			apply_filters(
				'newspack_campaigns_post_types_for_campaigns',
				[ 'post', 'page' ]
			)
		) ) {
			return [];
		}

		if ( ! empty( self::$popups ) ) {
			return self::$popups;
		}

		// Get the previewed popup and return early if there's one.
		if ( Newspack_Popups::previewed_popup_id() ) {
			return [ Newspack_Popups_Model::retrieve_preview_popup( Newspack_Popups::previewed_popup_id() ) ];
		}

		// Popups disabled for this page.
		if ( self::assess_has_disabled_popups() ) {
			return [];
		}

		$view_as_spec        = Segmentation::parse_view_as( Newspack_Popups_View_As::viewing_as_spec() );
		$campaign_id         = isset( $view_as_spec['campaign'] ) ? $view_as_spec['campaign'] : false;
		$include_unpublished = isset( $view_as_spec['show_unpublished'] ) && 'true' === $view_as_spec['show_unpublished'] ? true : false;

		// Retrieve all prompts eligible for display.
		$popups_to_maybe_display = Newspack_Popups_Model::retrieve_eligible_popups( $include_unpublished, $campaign_id );

		return array_filter(
			$popups_to_maybe_display,
			[ __CLASS__, 'should_display' ]
		);
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'the_content', [ $this, 'insert_popups_in_content' ], 1 );
		add_shortcode( 'newspack-popup', [ $this, 'popup_shortcode' ] );
		add_action( 'after_header', [ $this, 'insert_popups_after_header' ] ); // This is a Newspack theme hook. When used with other themes, popups won't be inserted on archive pages.
		add_action( 'wp_head', [ $this, 'insert_popups_amp_access' ] );
		add_action( 'wp_head', [ $this, 'register_amp_scripts' ] );
		add_action( 'before_header', [ $this, 'insert_before_header' ] );

		// Always enqueue scripts, since this plugin's scripts are handling pageview sending via GTAG.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_filter(
			'newspack_popups_assess_has_disabled_popups',
			function ( $disabled ) {
				if ( get_post_meta( get_the_ID(), 'newspack_popups_has_disabled_popups', true ) ) {
					return true;
				}

				return $disabled;
			}
		);

		// Suppress popups on product pages.
		// Until the popups non-AMP refactoring happens, they will break Add to Cart buttons.
		add_filter(
			'newspack_popups_assess_has_disabled_popups',
			function( $disabled ) {
				if ( function_exists( 'is_product' ) && is_product() ) {
					return true;
				}
				return $disabled;
			}
		);

		// The suppress filter used to be named 'newspack_newsletters_assess_has_disabled_popups'.
		// Maintain that filter for backwards compatibility.
		add_filter(
			'newspack_popups_assess_has_disabled_popups',
			function( $disabled ) {
				if ( apply_filters( 'newspack_newsletters_assess_has_disabled_popups', false ) ) {
					return true;
				}

				return $disabled;
			}
		);

		// These hooks are fired before and after rendering posts in the Homepage Posts block.
		// By removing the the_content filter before rendering, we avoid incorrectly injecting popup content into excerpts in the block.
		add_action(
			'newspack_blocks_homepage_posts_before_render',
			function() {
				remove_filter( 'the_content', [ $this, 'insert_popups_in_content' ], 1 );
			}
		);

		add_action(
			'newspack_blocks_homepage_posts_after_render',
			function() {
				add_filter( 'the_content', [ $this, 'insert_popups_in_content' ], 1 );
			}
		);
	}

	/**
	 * Process popups and insert into post and page content if needed.
	 *
	 * @param string $content The content of the post.
	 */
	public static function insert_popups_in_content( $content = '' ) {
		// Avoid duplicate execution.
		if ( true === self::$the_content_has_rendered ) {
			return $content;
		}

		// Not Frontend.
		if ( is_admin() ) {
			return $content;
		}

		// Content is empty.
		if ( empty( trim( $content ) ) ) {
			return $content;
		}

		// No popup insertion in archive pages.
		if ( ! is_singular() ) {
			return $content;
		}

		// If not in the loop, ignore.
		if ( ! in_the_loop() ) {
			return $content;
		}

		// If the current post isn't an allowed post type, ignore.
		if ( ! in_array(
			get_post_type(),
			apply_filters(
				'newspack_campaigns_post_types_for_campaigns',
				[ 'post', 'page' ]
			)
		) ) {
			return $content;
		}

		// Don't inject inline popups on paywalled posts.
		// It doesn't make sense with a paywall message and also causes an infinite loop.
		if ( function_exists( 'wc_memberships_is_post_content_restricted' ) && wc_memberships_is_post_content_restricted() ) {
			return $content;
		}

		// If any popups are inserted using a shortcode, skip them.
		$shortcoded_popups_ids = self::get_shortcoded_popups_ids( get_the_content() );
		$popups                = array_filter(
			self::popups_for_post(),
			function ( $popup ) use ( $shortcoded_popups_ids ) {
				return ! in_array( $popup['id'], $shortcoded_popups_ids ) && Newspack_Popups_Model::should_be_inserted_in_page_content( $popup );
			}
		);

		if ( empty( $popups ) ) {
			return $content;
		}

		if ( function_exists( 'scaip_maybe_insert_shortcode' ) ) {
			// Prevent default SCAIP insertion.
			remove_filter( 'the_content', 'scaip_maybe_insert_shortcode', 10 );

			// In order to prevent the SCAIP ad being inserted mid-popup, let's insert the ads
			// manually. SCAI begins by checking if there are any ads already inserted and bails
			// if there are, to allow for manual ads placement.
			$content = scaip_maybe_insert_shortcode( $content );
		}

		// For certain types of blocks, their innerHTML is not a good representation of the length of their content.
		// For example, slideshows may have an arbitrary amount of slide content, but only show one slide at a time.
		// For these blocks, let's ignore their length for purposes of inserting prompts.
		$blacklisted_blocks = [ 'jetpack/slideshow', 'newspack-blocks/carousel', 'newspack-popups/single-prompt' ];
		$parsed_blocks      = parse_blocks( $content );
		$total_length       = 0;

		foreach ( $parsed_blocks as $block ) {
			if ( ! in_array( $block['blockName'], $blacklisted_blocks ) ) {
				$is_classic_block = null === $block['blockName'] || 'core/freeform' === $block['blockName']; // Classic block doesn't have a block name.
				$block_content    = $is_classic_block ? force_balance_tags( wpautop( $block['innerHTML'] ) ) : $block['innerHTML'];
				$total_length    += strlen( wp_strip_all_tags( $block_content ) );
			} else {
				// Give blacklisted blocks a length so that prompts at 0% can still be inserted before them.
				$total_length++;
			}
		}

		// 1. Separate prompts into inline and overlay.
		$inline_popups  = [];
		$overlay_popups = [];
		foreach ( $popups as $popup ) {
			if ( Newspack_Popups_Model::is_inline( $popup ) ) {
				$percentage                = intval( $popup['options']['trigger_scroll_progress'] ) / 100;
				$popup['precise_position'] = $total_length * $percentage;
				$popup['is_inserted']      = false;
				$inline_popups[]           = $popup;
			} elseif ( Newspack_Popups_Model::is_overlay( $popup ) ) {
				$overlay_popups[] = $popup;
			}
		}

		// Return early if there are no popups to insert. This can happen if e.g. the only popup is an above header one.
		if ( empty( $inline_popups ) && empty( $overlay_popups ) ) {
			return $content;
		}

		// 2. Iterate over all blocks and insert inline prompts.
		$pos    = 0;
		$output = '';

		foreach ( $parsed_blocks as $block ) {
			$is_classic_block = null === $block['blockName']; // Classic block doesn't have a block name.

			// Classic block content: insert prompts between block-level HTML elements.
			if ( $is_classic_block ) {
				$classic_content = force_balance_tags( wpautop( $block['innerHTML'] ) ); // Ensure we have paragraph tags and valid HTML.
				if ( 0 === strlen( wp_strip_all_tags( $classic_content ) ) ) {
					continue;
				}
				$positions     = [];
				$last_position = -1;
				$block_endings = [ // Block-level elements eligble for prompt insertion.
					'</p>',
					'</ol>',
					'</ul>',
					'</h1>',
					'</h2>',
					'</h3>',
					'</h4>',
					'</h5>',
					'</h6>',
					'</div>',
					'</figure>',
					'</aside>',
					'</dl>',
					'</pre>',
					'</section>',
					'</table>',
				];

				// Parse the classic content string by block endings.
				foreach ( $block_endings as $block_ending ) {
					$last_position = -1;
					while ( stripos( $classic_content, $block_ending, $last_position + 1 ) ) {
						// Get the position of the end of the next $block_ending.
						$last_position = stripos( $classic_content, $block_ending, $last_position + 1 ) + strlen( $block_ending );
						$positions[]   = $last_position;
					}
				}

				sort( $positions, SORT_NUMERIC );
				$last_position = 0;

				// Insert prompts between block-level elements.
				foreach ( $positions as $position ) {
					foreach ( $inline_popups as &$inline_popup ) {
						if (
							! $inline_popup['is_inserted'] &&
							$position > $inline_popup['precise_position']
						) {
							$output                     .= '<!-- wp:shortcode -->[newspack-popup id="' . $inline_popup['id'] . '"]<!-- /wp:shortcode -->';
							$inline_popup['is_inserted'] = true;
						}
					}
					$output       .= substr( $classic_content, $last_position, $position - $last_position );
					$last_position = $position;
				}

				$pos += strlen( $classic_content );
				continue;
			}

			// Regular block content: insert prompts between blocks.
			if ( ! in_array( $block['blockName'], $blacklisted_blocks ) ) {
				$pos += strlen( wp_strip_all_tags( $block['innerHTML'] ) );
			} else {
				$pos++;
			}
			foreach ( $inline_popups as &$inline_popup ) {
				if (
					! $inline_popup['is_inserted'] &&
					$pos > $inline_popup['precise_position']
				) {
					$output                     .= '<!-- wp:shortcode -->[newspack-popup id="' . $inline_popup['id'] . '"]<!-- /wp:shortcode -->';
					$inline_popup['is_inserted'] = true;
				}
			}
			$block_content = render_block( $block );
			$output       .= $block_content;
		}

		// 3. Insert any remaining inline prompts at the end.
		foreach ( $inline_popups as &$inline_popup ) {
			if ( ! $inline_popup['is_inserted'] ) {
				$output .= '<!-- wp:shortcode -->[newspack-popup id="' . $inline_popup['id'] . '"]<!-- /wp:shortcode -->';

				$inline_popup['is_inserted'] = true;
			}
		}

		// 4. Insert overlay prompts at the top of content.
		foreach ( $overlay_popups as $overlay_popup ) {
			$output = '<!-- wp:html -->' . Newspack_Popups_Model::generate_popup( $overlay_popup ) . '<!-- /wp:html -->' . $output;
		}

		self::$the_content_has_rendered = true;
		return $output;
	}

	/**
	 * Process popups and insert into archive pages if needed. Applies to Newspack Theme only.
	 */
	public static function insert_popups_after_header() {
		/* Posts and pages are covered by the_content hook */
		if ( is_singular() ) {
			return;
		}
		$popups = array_filter( self::popups_for_post(), [ 'Newspack_Popups_Model', 'should_be_inserted_in_page_content' ] );
		foreach ( $popups as $popup ) {
			echo Newspack_Popups_Model::generate_popup( $popup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Insert popups markup before header.
	 */
	public static function insert_before_header() {
		$before_header_popups = array_filter( self::popups_for_post(), [ 'Newspack_Popups_Model', 'should_be_inserted_above_page_header' ] );
		foreach ( $before_header_popups as $popup ) {
			echo Newspack_Popups_Model::generate_popup( $popup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Enqueue the assets needed to display the popups.
	 */
	public static function enqueue_scripts() {
		if ( defined( 'IS_TEST_ENV' ) && IS_TEST_ENV ) {
			return;
		}
		$is_amp = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
		if ( ! $is_amp ) {
			wp_register_script(
				'newspack-popups-view',
				plugins_url( '../dist/view.js', __FILE__ ),
				[ 'wp-dom-ready', 'wp-url' ],
				filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/view.js' ),
				true
			);
			wp_enqueue_script( 'newspack-popups-view' );
		}

		\wp_register_style(
			'newspack-popups-view',
			plugins_url( '../dist/view.css', __FILE__ ),
			null,
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/view.css' )
		);
		\wp_style_add_data( 'newspack-popups-view', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-popups-view' );
	}

	/**
	 * The popup shortcode function.
	 * Primarily, the shortcode is inserted by the plugin, but it may also be inserted manually to
	 * display a specific popup anywhere on the site.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return HTML
	 */
	public static function popup_shortcode( $atts = array() ) {
		if ( isset( $atts['id'] ) ) {
			$include_unpublished = Newspack_Popups::is_preview_request();
			$found_popup         = Newspack_Popups_Model::retrieve_popup_by_id( $atts['id'], $include_unpublished );
		}
		if ( ! $found_popup ) {
			return;
		}
		$is_overlay = Newspack_Popups_Model::is_overlay( $found_popup );
		if ( $is_overlay ) {
			// Only inline popups may be placed using shortcodes.
			return;
		}

		if (
			// Bail if it's a non-preview popup which should not be displayed.
			( ! self::should_display( $found_popup, true ) && ! Newspack_Popups::previewed_popup_id() ) ||
			// Only inline or manual-only popups can be inserted via the shortcode.
			( ! Newspack_Popups_Model::is_inline( $found_popup ) && ! Newspack_Popups_Model::is_manual_only( $found_popup ) )
		) {
			return;
		}

		// Wrapping the inline popup in an aside element prevents the markup from being mangled
		// if the shortcode is the first block.
		return '<aside>' . Newspack_Popups_Model::generate_popup( $found_popup ) . '</aside>';
	}

	/**
	 * Create the popup definition for sending to the API.
	 *
	 * @param object $popup A popup.
	 */
	public static function create_single_popup_access_payload( $popup ) {
		$popup_id_string = Newspack_Popups_Model::canonize_popup_id( esc_attr( $popup['id'] ) );
		$frequency       = $popup['options']['frequency'];
		$is_overlay      = Newspack_Popups_Model::is_overlay( $popup );
		$is_above_header = Newspack_Popups_Model::is_above_header( $popup );
		$type            = 'i';

		if ( $is_overlay ) {
			$type = 'o';

			if ( 'always' === $frequency ) {
				$frequency = 'once';
			}
		}

		if ( $is_above_header ) {
			$type = 'a';
		}

		$popup_payload = [
			'id'  => $popup_id_string,
			'f'   => $frequency,
			'utm' => $popup['options']['utm_suppression'],
			's'   => $popup['options']['selected_segment_id'],
			'n'   => \Newspack_Popups_Model::has_newsletter_prompt( $popup ),
			'd'   => \Newspack_Popups_Model::has_donation_block( $popup ),
			't'   => $type,
		];

		if ( Newspack_Popups_Custom_Placements::is_custom_placement( $popup ) ) {
			$popup_payload['c'] = $popup['options']['placement'];
		}

		return $popup_payload;
	}

	/**
	 * Add amp-access header code.
	 *
	 * The amp-access endpoint is also responsible for reporting visits, in order to minimise
	 * the number of requests. For this reason it is placed on every page, not only those
	 * with popups.
	 */
	public static function insert_popups_amp_access() {
		if ( ! Newspack_Popups_Segmentation::is_tracking() ) {
			return;
		}
		$shortcoded_popup_ids = array_unique(
			array_merge(
				self::get_shortcoded_popups_ids( get_the_content() ),
				self::get_all_widget_shortcoded_popups_ids()
			)
		);

		// Get shortcoded prompts.
		$shortcoded_popups = array_reduce(
			$shortcoded_popup_ids,
			function ( $acc, $id ) {
				$popup_post = get_post( $id );
				if ( $popup_post ) {
					$popup_object = Newspack_Popups_Model::create_popup_object( $popup_post );
					// Shortcoded overlay popups will not be rendered on the page, but still the shortcode might be present.
					// This condition is just to remove the unnecessary part of the payload in such a case.
					$is_overlay = Newspack_Popups_Model::is_overlay( $popup_object );
					if ( $popup_object && ! $is_overlay && 'publish' === $popup_object['status'] ) {
						$acc[] = $popup_object;
					}
				}
				return $acc;
			},
			[]
		);

		// Get prompts for custom placements.
		$custom_placement_ids    = self::get_custom_placement_ids( get_the_content() );
		$custom_placement_popups = array_reduce(
			Newspack_Popups_Custom_Placements::get_prompts_for_custom_placement( $custom_placement_ids ),
			function ( $acc, $custom_placement_popup ) {
				if ( $custom_placement_popup ) {
					$popup_object = Newspack_Popups_Model::create_popup_object( $custom_placement_popup );

					if ( $popup_object ) {
						$acc[] = $popup_object;
					}
				}
				return $acc;
			},
			[]
		);

		$popups = array_merge(
			self::popups_for_post(),
			$shortcoded_popups,
			$custom_placement_popups
		);
		// Prevent duplicates - a popup might be duplicated in a shortcode.
		$unique_ids = [];
		$popups     = array_filter(
			$popups,
			function ( $item ) use ( &$unique_ids ) {
				$id = $item['id'];
				if ( in_array( $id, $unique_ids ) ) {
					return false;
				}
				$unique_ids[] = $id;
				return true;
			}
		);
		// Sort the array, so the segmented popups come first. This is necessary for proper
		// prioritisation of single-popup placements (e.g. above header).
		uasort(
			$popups,
			function( $popup_a, $popup_b ) {
				$a_has_segments = ! empty( $popup_a['options']['selected_segment_id'] );
				$b_has_segments = ! empty( $popup_b['options']['selected_segment_id'] );
				if ( $a_has_segments && $b_has_segments ) {
					return 0;
				}
				return $a_has_segments && false === $b_has_segments ? -1 : 1;
			}
		);

		// "Escape hatch" if there's a need to block adding amp-access for pages that have no prompts.
		if ( apply_filters( 'newspack_popups_suppress_insert_amp_access', false, $popups ) ) {
			return;
		}

		$popups_access_provider = [
			'namespace'     => 'popups',
			'authorization' => esc_url( Newspack_Popups_Model::get_reader_endpoint() ) . '?cid=CLIENT_ID(' . Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME . ')',
			'noPingback'    => true,
		];

		$popups_configs = [];
		foreach ( $popups as $popup ) {
			$popups_configs[] = self::create_single_popup_access_payload( $popup );
		}

		$categories   = get_the_category();
		$category_ids = '';
		if ( ! empty( $categories ) ) {
			$category_ids = implode(
				',',
				array_map(
					function( $cat ) {
						return $cat->term_id;
					},
					$categories
				)
			);
		}

		$settings                                 = array_reduce(
			\Newspack_Popups_Settings::get_settings(),
			function ( $acc, $item ) {
				$key       = $item['key'];
				$acc->$key = $item['value'];
				return $acc;
			},
			(object) []
		);
		$popups_access_provider['authorization'] .= '&ref=DOCUMENT_REFERRER';
		$popups_access_provider['authorization'] .= '&popups=' . wp_json_encode( $popups_configs );
		$popups_access_provider['authorization'] .= '&settings=' . wp_json_encode( $settings );
		$popups_access_provider['authorization'] .= '&visit=' . wp_json_encode(
			[
				'post_id'    => esc_attr( get_the_ID() ),
				'categories' => esc_attr( $category_ids ),
				'is_post'    => is_single(),
			]
		);
		$view_as_spec                             = Newspack_Popups_View_As::viewing_as_spec();
		if ( $view_as_spec ) {
			$popups_access_provider['authorization'] .= '&view_as=' . wp_json_encode( $view_as_spec );
		}
		if ( isset( $_GET['newspack-campaigns-debug'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$popups_access_provider['authorization'] .= '&debug';
		}

		?>
		<script id="amp-access" type="application/json">
			<?php echo wp_json_encode( $popups_access_provider ); ?>
		</script>
		<?php
	}

	/**
	 * Disable popups on posts and pages which have newspack_popups_has_disabled_popups.
	 *
	 * @return bool True if popups should be disabled for current page.
	 */
	public static function assess_has_disabled_popups() {
		return apply_filters( 'newspack_popups_assess_has_disabled_popups', false );
	}

	/**
	 * Register and enqueue all required AMP scripts, if needed.
	 */
	public static function register_amp_scripts() {
		if ( self::assess_has_disabled_popups() ) {
			return;
		}
		if ( ! is_admin() && ! wp_script_is( 'amp-runtime', 'registered' ) ) {
		// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_register_script(
				'amp-runtime',
				'https://cdn.ampproject.org/v0.js',
				null,
				null,
				true
			);
		}
		$scripts = [ 'amp-access', 'amp-animation', 'amp-bind', 'amp-position-observer' ];
		foreach ( $scripts as $script ) {
			if ( ! wp_script_is( $script, 'registered' ) ) {
				$path = "https://cdn.ampproject.org/v0/{$script}-latest.js";
				// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
				wp_register_script(
					$script,
					$path,
					array( 'amp-runtime' ),
					null,
					true
				);
			}
			wp_enqueue_script( $script );
		}
	}

	/**
	 * Look for popup shortcodes in a string and return their IDs.
	 *
	 * @param string $string String to assess.
	 * @return array Found shortcoded popups IDs.
	 */
	public static function get_shortcoded_popups_ids( $string ) {
		$parsed_blocks = parse_blocks( $string );

		$single_prompt_blocks    = array_filter(
			$parsed_blocks,
			function( $block ) {
				return 'newspack-popups/single-prompt' === $block['blockName'];
			}
		);
		$single_prompt_block_ids = array_reduce(
			$single_prompt_blocks,
			function( $acc, $popup ) {
				if ( ! empty( $popup['attrs']['promptId'] ) ) {
					$acc[] = intval( $popup['attrs']['promptId'] );
				}
				return $acc;
			},
			[]
		);

		preg_match_all( '/\[newspack-popup .*\]/', $string, $popup_shortcodes_in_content );

		if ( empty( $popup_shortcodes_in_content ) ) {
			return [];
		} else {
			return array_unique(
				array_merge(
					$single_prompt_block_ids,
					array_map(
						function ( $item ) {
							preg_match( '/id=["|\'](\d*)/', $item, $matches );
							if ( empty( $matches ) ) {
								return null;
							} else {
								return $matches[1];
							}
						},
						$popup_shortcodes_in_content[0]
					)
				)
			);
		}
	}

	/**
	 * Get custom placement IDs from a string.
	 *
	 * @param string $string String to assess.
	 * @return array Found custom placement IDs.
	 */
	public static function get_custom_placement_ids( $string ) {
		preg_match_all( '/<!-- wp:newspack-popups\/custom-placement {"customPlacement":".*"} \/-->/', $string, $custom_placement_ids );
		if ( empty( $custom_placement_ids ) ) {
			return [];
		} else {
			return array_unique(
				array_map(
					function ( $item ) {
						preg_match( '/"customPlacement":"(.*)"/', $item, $matches );
						if ( empty( $matches ) ) {
							return null;
						} else {
							return $matches[1];
						}
					},
					$custom_placement_ids[0]
				)
			);
		}

		return [];
	}

	/**
	 * Some popups can only appear on Posts.
	 *
	 * @param object $popup The popup to assess.
	 * @return bool Should popup be shown.
	 */
	public static function assess_is_post( $popup ) {
		if (
			// Inline Pop-ups can only appear in Posts.
			'inline' === $popup['options']['placement']
		) {
			return is_single();
		}
		return true;
	}

	/**
	 * If a prompt is assigned the given taxonomy, it should only be shown on posts/pages with at least one matching term.
	 * If the prompt has no terms, it should be shown regardless of the post's terms.
	 *
	 * @param object $popup The prompt to assess.
	 * @param string $taxonomy The type of taxonomy to match.
	 *
	 * @return bool Whether the prompt should be shown based on matching terms.
	 */
	public static function assess_taxonomy_filter( $popup, $taxonomy = 'category' ) {
		$popup_terms = get_the_terms( $popup['id'], $taxonomy );
		if ( false === $popup_terms ) {
			return true; // No terms on the popup, no need to compare.
		}
		$post_terms = get_the_terms( get_the_ID(), $taxonomy );
		return array_intersect(
			array_column( $post_terms ? $post_terms : [], 'term_id' ),
			array_column( $popup_terms, 'term_id' )
		);
	}

	/**
	 * Should Popup be rendered, based on universal conditions.
	 *
	 * @param object $popup The popup to assess.
	 * @param bool   $skip_context_checks Skip checking context, like if the popup is rendered in a post, and if category/tags are matching.
	 * @return bool Should popup be shown.
	 */
	public static function should_display( $popup, $skip_context_checks = false ) {
		if ( Newspack_Popups_Custom_Placements::is_custom_placement( $popup ) ) {
			return true;
		}

		// When using "view as" feature, disregard most conditions.
		if ( Newspack_Popups_View_As::viewing_as_spec() ) {
			return $skip_context_checks ? true : self::assess_is_post( $popup );
		}
		// Hide prompts for admin users.
		if ( Newspack_Popups::is_user_admin() ) {
			return false;
		}
		// Hide overlay prompts in non-interactive mode, for non-admin users.
		if ( ! Newspack_Popups::is_user_admin() && Newspack_Popups_Settings::is_non_interactive() && ! Newspack_Popups_Model::is_inline( $popup ) ) {
			return false;
		}

		if ( $skip_context_checks ) {
			return true;
		}
		return self::assess_is_post( $popup ) &&
			self::assess_taxonomy_filter( $popup, 'category' ) &&
			self::assess_taxonomy_filter( $popup, 'post_tag' );
	}

	/**
	 * Get all widget shortcoded popups IDs.
	 *
	 * @return array IDs of popups shortcoded in widgets.
	 */
	public static function get_all_widget_shortcoded_popups_ids() {
		$text_widget_option = get_option( 'widget_text' );
		return array_reduce(
			$text_widget_option,
			function ( $acc, $text_widget ) {
				if ( isset( $text_widget['text'] ) ) {
					$popup_ids = self::get_shortcoded_popups_ids( $text_widget['text'] );
					if ( ! empty( $popup_ids ) ) {
						$acc = array_merge( $acc, $popup_ids );
					}
				}
				return $acc;
			},
			[]
		);
	}
}
$newspack_popups_inserter = new Newspack_Popups_Inserter();
