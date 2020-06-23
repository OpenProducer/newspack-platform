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
		if ( $category_overlay_popup ) {
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
	 */
	public static function insert_popups_in_content( $content = '' ) {
		if ( is_admin() || ! is_singular() || self::assess_has_disabled_popups( $content ) ) {
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

		// First needs to check if there any inline popups, to handle SCAIP.
		$has_an_inline_popup = count(
			array_filter(
				$popups,
				function( $p ) {
					return 'inline' === $p['options']['placement'];
				}
			)
		);

		if ( $has_an_inline_popup && function_exists( 'scaip_maybe_insert_shortcode' ) ) {
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
			$output = '<!-- wp:html -->' . $overlay_popup['markup'] . '<!-- /wp:html -->' . $output;
		}

		self::enqueue_popup_assets();
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
				echo $popup['markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
				null,
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
		if ( isset( $found_popup['markup'] ) ) {
			return $found_popup['markup'];
		}
	}

	/**
	 * Add amp-access header code.
	 *
	 * @param object $popups The popup objects to handle.
	 */
	public static function insert_popups_amp_access( $popups ) {
		if ( is_admin() || self::assess_has_disabled_popups() ) {
			return;
		}

		$popups                  = self::popups_for_post();
		$endpoint                = str_replace( 'http://', '//', get_rest_url( null, 'newspack-popups/v1/reader' ) );
		$popups_access_providers = [];

		foreach ( $popups as $popup ) {
			// In test frequency cases (logged in site editor) and when previewing,
			// fallback to authorization of true to avoid possible amp-access timeouts.
			$authorization_fallback_response = (
				( 'test' === $popup['options']['frequency'] || Newspack_Popups::previewed_popup_id() ) &&
				is_user_logged_in() &&
				current_user_can( 'edit_others_pages' )
			);

			$amp_access_provider = array(
				'namespace'                     => 'popup_' . $popup['id'],
				'authorization'                 => esc_url( $endpoint ) . '?popup_id=' . esc_attr( $popup['id'] ) . '&rid=READER_ID&url=CANONICAL_URL&RANDOM',
				'noPingback'                    => true,
				'authorizationFallbackResponse' => array(
					'displayPopup' => $authorization_fallback_response,
				),
			);

			array_push(
				$popups_access_providers,
				$amp_access_provider
			);
		}

		if ( ! empty( $popups_access_providers ) ) {
			?>
			<script id="amp-access" type="application/json">
				<?php echo wp_json_encode( $popups_access_providers ); ?>
			</script>
			<?php
		}
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
			'inline' === $popup['options']['placement'] ||
			// Pop-ups triggered by scroll position can only appear on Posts.
			'scroll' === $popup['options']['trigger_type']
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
	 * Should Popup be displayed, based on universal conditions.
	 *
	 * @param object $popup The popup to assess.
	 * @return bool Should popup be shown.
	 */
	public static function should_display( $popup ) {
		return self::assess_is_post( $popup ) && self::assess_test_mode( $popup ) && self::assess_categories_filter( $popup );
	}
}
$newspack_popups_inserter = new Newspack_Popups_Inserter();
