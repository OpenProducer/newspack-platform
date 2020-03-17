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
	 * The popup object to display.
	 *
	 * @var object
	 */
	protected static $popup = null;

	/**
	 * Retrieve the best popup for the current post.
	 *
	 * @return object Popup object.
	 */
	public static function popup_for_post() {
		if ( self::$popup ) {
			return self::$popup;
		}

		// First try the preview.
		if ( Newspack_Popups::previewed_popup_id() ) {
			return Newspack_Popups_Model::retrieve_preview_popup( Newspack_Popups::previewed_popup_id() );
		}

		// Then try for pop-up with category filtering.
		$categories = get_the_category();
		if ( $categories && count( $categories ) ) {
			self::$popup = Newspack_Popups_Model::retrieve_popup( get_the_category() );
			if ( self::$popup ) {
				return self::$popup;
			}
		}

		// If nothing found, try for sitewide pop-up.
		$sitewide_default = get_option( Newspack_Popups::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, null );
		if ( $sitewide_default ) {
			self::$popup = Newspack_Popups_Model::retrieve_popup_by_id( $sitewide_default );
			if ( self::$popup ) {
				return self::$popup;
			}
		}
		return null;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'the_content', [ $this, 'popup' ] );
		add_action( 'after_header', [ $this, 'popup_after_header' ] ); // This is a Newspack theme hook. When used with other themes, popups won't be inserted on archive pages.
		add_action( 'wp_head', [ __CLASS__, 'popup_access' ] );
	}

	/**
	 * Process popup and insert into post and page content if needed.
	 *
	 * @param string $content The content of the post.
	 * @return string The content with popup inserted.
	 */
	public static function popup( $content = '' ) {
		if ( is_admin() || ! is_singular() ) {
			return $content;
		}
		$popup = self::popup_for_post();

		if ( ! $popup ) {
			return $content;
		}

		if ( ! static::assess_test_mode( $popup ) ) {
			return $content;
		}

		$is_inline = 'inline' === $popup['options']['placement'];

		if ( $is_inline && ! is_single() ) {
			// Inline Pop-ups can only appear in Posts.
			return $content;
		} elseif ( 'scroll' === $popup['options']['trigger_type'] && ! is_single() && ! Newspack_Popups::previewed_popup_id() ) {
			// Pop-ups triggered by scroll position can only appear on Posts.
			return $content;
		}

		$content = $is_inline ? self::insert_inline_popup( $content, $popup ) : self::insert_popup( $content, $popup );
		\wp_register_style(
			'newspack-popups-view',
			plugins_url( '../dist/view.css', __FILE__ ),
			null,
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/view.css' )
		);
		\wp_style_add_data( 'newspack-popups-view', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-popups-view' );
		return $content;
	}

	/**
	 * Process popup and insert into archive pages if needed. Applies to Newspack Theme only.
	 */
	public static function popup_after_header() {
		/* Posts and pages are covered by the_content hook */
		if ( is_singular() ) {
			return;
		}

		$popup = self::popup_for_post();

		if ( ! static::assess_test_mode( $popup ) ) {
			return;
		}

		if ( ! $popup ) {
			return;
		}

		// Pop-ups triggered by scroll position can only appear on Posts.
		if ( 'scroll' === $popup['options']['trigger_type'] ) {
			return;
		}

		\wp_register_style(
			'newspack-popups-view',
			plugins_url( '../dist/view.css', __FILE__ ),
			null,
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/view.css' )
		);
		\wp_style_add_data( 'newspack-popups-view', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-popups-view' );

		// Inline Pop-ups can only appear in Posts.
		if ( 'inline' === $popup['options']['placement'] ) {
			return;
		}

		echo $popup['markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Insert Popup markup into content
	 *
	 * @param string $content The content of the post.
	 * @param object $popup The popup object to insert.
	 * @return string The content with popup inserted.
	 */
	public static function insert_popup( $content = '', $popup = [] ) {
		if ( 0 === $popup['options']['trigger_scroll_progress'] || Newspack_Popups::previewed_popup_id() ) {
			return $popup['markup'] . $content;
		}

		$position  = 0;
		$positions = [];
		$close_tag = '</p>';
		while ( stripos( $content, $close_tag, $position ) !== false ) {
			$position    = stripos( $content, '</p>', $position ) + strlen( $close_tag );
			$positions[] = $position;
		}
		$total_length       = strlen( $content );
		$percentage         = intval( $popup['options']['trigger_scroll_progress'] ) / 100;
		$precise_position   = $total_length * $percentage;
		$insertion_position = $total_length;
		foreach ( $positions as $position ) {
			if ( $position >= $precise_position ) {
				$insertion_position = $position;
				break;
			}
		}
		$before_popup = substr( $content, 0, $insertion_position );
		$after_popup  = substr( $content, $insertion_position );
		return $before_popup . $popup['markup'] . $after_popup;
	}

	/**
	 * Insert inline Popup markup into content
	 *
	 * @param string $content The content of the post.
	 * @param object $popup The popup object to insert.
	 * @return string The content with popup inserted.
	 */
	public static function insert_inline_popup( $content = '', $popup = [] ) {
		$position  = 0;
		$positions = [];
		$close_tag = '</p>';
		while ( stripos( $content, $close_tag, $position ) !== false ) {
			$position    = stripos( $content, '</p>', $position ) + strlen( $close_tag );
			$positions[] = $position;
		}
		$total_length       = strlen( $content );
		$percentage         = intval( $popup['options']['trigger_scroll_progress'] ) / 100;
		$precise_position   = $total_length * $percentage;
		$insertion_position = $total_length;
		foreach ( $positions as $position ) {
			if ( $position >= $precise_position ) {
				$insertion_position = $position;
				break;
			}
		}
		$before_popup = substr( $content, 0, $insertion_position );
		$after_popup  = substr( $content, $insertion_position );
		return $before_popup . $popup['markup'] . $after_popup;
	}

	/**
	 * Add amp-access header code.
	 */
	public static function popup_access() {
		if ( is_admin() ) {
			return;
		}

		$popup = self::popup_for_post();

		if ( ! $popup ) {
			return;
		}

		if ( ! static::assess_test_mode( $popup ) ) {
			return;
		}

		// Pop-ups triggered by scroll position can only appear on Posts.
		if ( 'scroll' === $popup['options']['trigger_type'] && ! is_single() && ! Newspack_Popups::previewed_popup_id() ) {
			return;
		}
		$endpoint = str_replace( 'http://', '//', get_rest_url( null, 'newspack-popups/v1/reader' ) );

		// In test frequency cases (logged in site editor) and when previewing a popup,
		// fallback to authorization of true to avoid possible amp-access timeouts.
		$authorization_fallback_response = (
			( 'test' === $popup['options']['frequency'] || Newspack_Popups::previewed_popup_id() ) &&
			is_user_logged_in() &&
			current_user_can( 'edit_others_pages' )
		) ? 'true' : 'false';
		?>
		<script id="amp-access" type="application/json">
			{
				"authorization": "<?php echo esc_url( $endpoint ); ?>?popup_id=<?php echo ( esc_attr( $popup['id'] ) ); ?>&rid=READER_ID&url=CANONICAL_URL&RANDOM",
				"noPingback": true,
				"authorizationFallbackResponse": {
					"displayPopup": <?php echo( esc_attr( $authorization_fallback_response ) ); ?>
				}
			}
		</script>
		<?php
		static::register_amp_scripts();
	}

	/**
	 * Register and enqueue all required AMP scripts, if needed.
	 */
	public static function register_amp_scripts() {
		if ( ! wp_script_is( 'amp-runtime', 'registered' ) ) {
		// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_register_script(
				'amp-runtime',
				'https://cdn.ampproject.org/v0.js',
				null,
				null,
				true
			);
		}
		$scripts = [ 'amp-access', 'amp-animation', 'amp-form', 'amp-bind', 'amp-position-observer' ];
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
	 * If Pop-up Frequency is "Test Mode," assess whether it should be shown.
	 *
	 * @param object $popup The popup to assess.
	 * @return bool Should popup be shown based on Test Mode assessment.
	 */
	public static function assess_test_mode( $popup ) {
		if ( is_user_logged_in() ) {
			if ( Newspack_Popups::previewed_popup_id() ) {
				return true;
			}
			if ( 'test' !== $popup['options']['frequency'] || ! current_user_can( 'edit_others_pages' ) ) {
				return false;
			}
		} else {
			if ( 'test' === $popup['options']['frequency'] ) {
				return false;
			}
		}
		return true;
	}
}
$newspack_popups_inserter = new Newspack_Popups_Inserter();
