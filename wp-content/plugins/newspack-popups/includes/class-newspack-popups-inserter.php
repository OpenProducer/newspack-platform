<?php
/**
 * Newspack Popups Inserters
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

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
	 * Whether we've already inserted campaigns into the content.
	 * If we've already inserted campaigns into the content, don't try to do it again.
	 *
	 * @var boolean
	 */
	protected static $the_content_has_rendered = false;

	/**
	 * Retrieve the appropriate popups for the current post.
	 *
	 * @return array Popup objects.
	 */
	public static function popups_for_post() {
		if ( ! empty( self::$popups ) ) {
			return self::$popups;
		}

		// Get the previewed popup and return early if there's one.
		if ( Newspack_Popups::previewed_popup_id() ) {
			return [ Newspack_Popups_Model::retrieve_preview_popup( Newspack_Popups::previewed_popup_id() ) ];
		}

		// 1. Get all inline popups in there first.
		$popups_to_maybe_display = Newspack_Popups_Model::retrieve_inline_popups();

		// 2. Get the overlay popup.

		// Check if there's an overlay popup with matching category.
		$category_overlay_popup = Newspack_Popups_Model::retrieve_category_overlay_popup();
		if ( $category_overlay_popup && self::should_display( $category_overlay_popup ) ) {
			array_push(
				$popups_to_maybe_display,
				$category_overlay_popup
			);
		} else {
			// If there's no category-matching popup, get the sitewide pop-up.
			$sitewide_default = get_option( Newspack_Popups::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, null );
			if ( $sitewide_default ) {
				$found_popup = Newspack_Popups_Model::retrieve_popup_by_id( $sitewide_default );
				if (
					$found_popup &&
					// Prevent inline sitewide default from being added - all inline popups are there.
					'inline' !== $found_popup['options']['placement']
				) {
					array_push(
						$popups_to_maybe_display,
						$found_popup
					);
				}
			}
		}

		$popups_to_display = array_filter(
			$popups_to_maybe_display,
			[ __CLASS__, 'should_display' ]
		);
		if ( ! empty( $popups_to_display ) ) {
			return $popups_to_display;
		}

		return [];
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

		add_filter(
			'newspack_newsletters_assess_has_disabled_popups',
			function () {
				return get_post_meta( get_the_ID(), 'newspack_popups_has_disabled_popups', true );
			}
		);

		// Suppress popups on product pages.
		// Until the popups non-AMP refactoring happens, they will break Add to Cart buttons.
		add_filter(
			'newspack_newsletters_assess_has_disabled_popups',
			function( $disabled ) {
				if ( function_exists( 'is_product' ) && is_product() ) {
					return true;
				}
				return $disabled;
			}
		);

		// These hooks are fired before and after rendering posts in the Homepage Posts block.
		// By removing the the_content filter before rendering, we avoid incorrectly injecting campaign content into excerpts in the block.
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
	 * @param bool   $enqueue_assets Whether assets should be enqueued.
	 */
	public static function insert_popups_in_content( $content = '', $enqueue_assets = true ) {
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

		// No campaign insertion in archive pages.
		if ( ! is_singular() ) {
			return $content;
		}

		// If not in the loop, ignore.
		if ( ! in_the_loop() ) {
			return $content;
		}

		// Campaigns disabled for this page.
		if ( self::assess_has_disabled_popups() ) {
			return $content;
		}

		// If the current post is a Campaign, ignore.
		if ( Newspack_Popups::NEWSPACK_PLUGINS_CPT == get_post_type() ) {
			return $content;
		}

		// Don't inject inline popups on paywalled posts.
		// It doesn't make sense with a paywall message and also causes an infinite loop.
		if ( function_exists( 'wc_memberships_is_post_content_restricted' ) && wc_memberships_is_post_content_restricted() ) {
			return $content;
		}

		$popups = self::popups_for_post();

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

		$total_length = strlen( $content );

		// 1. Separate campaigns into inline and overlay.
		$inline_popups  = [];
		$overlay_popups = [];
		foreach ( $popups as $popup ) {
			if ( 'inline' === $popup['options']['placement'] ) {
				$percentage                = intval( $popup['options']['trigger_scroll_progress'] ) / 100;
				$popup['precise_position'] = $total_length * $percentage;
				$popup['is_inserted']      = false;
				$inline_popups[]           = $popup;
			} else {
				$overlay_popups[] = $popup;
			}
		}

		// 2. Iterate overall blocks and insert inline campaigns.
		$pos    = 0;
		$output = '';
		foreach ( parse_blocks( $content ) as $block ) {
			$block_content = render_block( $block );
			$pos          += strlen( $block_content );
			foreach ( $inline_popups as &$inline_popup ) {
				if ( ! $inline_popup['is_inserted'] && $pos > $inline_popup['precise_position'] ) {
					$output .= '<!-- wp:shortcode -->[newspack-popup id="' . $inline_popup['id'] . '"]<!-- /wp:shortcode -->';

					$inline_popup['is_inserted'] = true;
				}
			}
			$output .= $block_content;
		}

		// 3. Insert any remaining inline campaigns at the end.
		foreach ( $inline_popups as $inline_popup ) {
			if ( ! $inline_popup['is_inserted'] ) {
				$output .= '<!-- wp:shortcode -->[newspack-popup id="' . $inline_popup['id'] . '"]<!-- /wp:shortcode -->';

				$inline_popup['is_inserted'] = true;
			}
		}

		// 4. Insert overlay campaigns at the top of content.
		foreach ( $overlay_popups as $overlay_popup ) {
			$output = '<!-- wp:html -->' . Newspack_Popups_Model::generate_popup( $overlay_popup ) . '<!-- /wp:html -->' . $output;
		}

		if ( $enqueue_assets ) {
			self::enqueue_popup_assets();
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

		$popups = self::popups_for_post();

		if ( ! empty( $popups ) ) {
			foreach ( $popups as $popup ) {
				echo Newspack_Popups_Model::generate_popup( $popup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			self::enqueue_popup_assets();
		}
	}

	/**
	 * Enqueue the assets needed to display the popups.
	 */
	public static function enqueue_popup_assets() {
		$is_amp = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
		if ( ! $is_amp ) {
			wp_register_script(
				'newspack-popups-view',
				plugins_url( '../dist/view.js', __FILE__ ),
				[ 'wp-dom-ready' ],
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
	 *
	 * @param array $atts Shortcode attributes.
	 * @return HTML
	 */
	public static function popup_shortcode( $atts = array() ) {
		$previewed_popup_id = Newspack_Popups::previewed_popup_id();
		if ( $previewed_popup_id ) {
			$found_popup = Newspack_Popups_Model::retrieve_preview_popup( $previewed_popup_id );
		} elseif ( isset( $atts['id'] ) ) {
			$found_popup = Newspack_Popups_Model::retrieve_popup_by_id( $atts['id'] );
		}
		return Newspack_Popups_Model::generate_popup( $found_popup );
	}

	/**
	 * Create the popup definition for sending to the API.
	 *
	 * @param object $popup A popup.
	 */
	public static function create_single_popup_access_payload( $popup ) {
		$popup_id_string = Newspack_Popups_Model::canonize_popup_id( esc_attr( $popup['id'] ) );
		$frequency       = $popup['options']['frequency'];
		if ( 'inline' !== $popup['options']['placement'] && 'always' === $frequency ) {
			$frequency = 'once';
		}
		return [
			'id'  => $popup_id_string,
			'f'   => $frequency,
			'utm' => $popup['options']['utm_suppression'],
			'n'   => \Newspack_Popups_Model::has_newsletter_prompt( $popup ),
		];
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

		$popups = self::popups_for_post();
		// "Escape hatch" if there's a need to block adding amp-access for pages that have no campaigns.
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

		$popups_access_provider['authorization'] .= '&popups=' . wp_json_encode( $popups_configs );
		$popups_access_provider['authorization'] .= '&settings=' . wp_json_encode( \Newspack_Popups_Settings::get_settings() );
		$popups_access_provider['authorization'] .= '&visit=' . wp_json_encode(
			[
				'post_id'    => esc_attr( get_the_ID() ),
				'categories' => esc_attr( $category_ids ),
				'is_post'    => is_single(),
			]
		);
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
		return apply_filters( 'newspack_newsletters_assess_has_disabled_popups', [] );
	}

	/**
	 * Register and enqueue all required AMP scripts, if needed.
	 */
	public static function register_amp_scripts() {
		if ( ! Newspack_Popups_Segmentation::is_tracking() ) {
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
		$scripts = [ 'amp-access', 'amp-analytics', 'amp-animation', 'amp-bind', 'amp-position-observer' ];
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
	 * If Pop-up Frequency is "Test Mode," assess whether it should be shown.
	 *
	 * @param object $popup The popup to assess.
	 * @return bool Should popup be shown based on Test Mode assessment.
	 */
	public static function assess_test_mode( $popup ) {
		if ( 'test' === $popup['options']['frequency'] ) {
			return is_user_logged_in() && ( current_user_can( 'edit_others_pages' ) || Newspack_Popups::previewed_popup_id() );
		}
		return true;
	}

	/**
	 * If Pop-up has categories, it should only be shown on posts/pages with those.
	 *
	 * @param object $popup The popup to assess.
	 * @return bool Should popup be shown based on categories it has.
	 */
	public static function assess_categories_filter( $popup ) {
		$post_categories  = get_the_category();
		$popup_categories = get_the_category( $popup['id'] );
		if ( $popup_categories && count( $popup_categories ) ) {
			return array_intersect(
				array_column( $post_categories, 'term_id' ),
				array_column( $popup_categories, 'term_id' )
			);
		}
		return true;
	}

	/**
	 * Should Popup be rendered, based on universal conditions.
	 *
	 * @param object $popup The popup to assess.
	 * @return bool Should popup be shown.
	 */
	public static function should_display( $popup ) {
		// Hide non-test mode campaigns for logged-in users.
		if ( is_user_logged_in() && 'test' !== $popup['options']['frequency'] ) {
			return false;
		}
		return self::assess_is_post( $popup ) &&
			self::assess_test_mode( $popup ) &&
			self::assess_categories_filter( $popup );
	}
}
$newspack_popups_inserter = new Newspack_Popups_Inserter();
