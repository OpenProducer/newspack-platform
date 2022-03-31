<?php
/**
 * Newspack Ads set up
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Ads Class.
 */
final class Newspack_Ads {

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Ads
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Ads Instance.
	 * Ensures only one instance of Newspack Ads is loaded or can be loaded.
	 *
	 * @return Newspack Ads - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();

		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue front-end styles.
	 */
	public static function enqueue_scripts() {
		\wp_register_style(
			'newspack-ads-frontend',
			plugins_url( '../dist/frontend.css', __FILE__ ),
			null,
			filemtime( dirname( NEWSPACK_ADS_PLUGIN_FILE ) . '/dist/frontend.css' )
		);
		\wp_style_add_data( 'newspack-ads-frontend', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-ads-frontend' );
	}

	/**
	 * Define Constants.
	 */
	private function define_constants() {
		define( 'NEWSPACK_ADS_VERSION', '1.0.0' );
		define( 'NEWSPACK_ADS_ABSPATH', dirname( NEWSPACK_ADS_PLUGIN_FILE ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 * e.g. include_once NEWSPACK_ADS_ABSPATH . 'includes/foo.php';
	 */
	private function includes() {
		include_once NEWSPACK_ADS_ABSPATH . '/includes/providers/interface-newspack-ads-provider.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/providers/class-newspack-ads-provider.php';
		require_once NEWSPACK_ADS_ABSPATH . '/includes/providers/broadstreet/class-newspack-ads-broadstreet-provider.php';
		require_once NEWSPACK_ADS_ABSPATH . '/includes/providers/gam/class-newspack-ads-gam-provider.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/providers/gam/class-newspack-ads-gam.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-settings.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-custom-label.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-providers.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-bidding.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/bidders/class-newspack-ads-bidder-medianet.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/bidders/class-newspack-ads-bidder-openx.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/bidders/class-newspack-ads-bidder-pubmatic.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/bidders/class-newspack-ads-bidder-sovrn.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-bidding-gam.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-placements.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-sidebar-placements.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-scaip.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-blocks.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-model.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-widget.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/suppress-ads.php';
	}

	/**
	 * Get the URL for the Newspack Ads plugin directory.
	 *
	 * @param string $path Optional path to append.
	 * @return string URL
	 */
	public static function plugin_url( $path ) {
		$path = $path ? trim( $path ) : '';
		if ( $path && strpos( '/', $path ) !== 1 ) {
			$path = '/' . $path;
		}
		return untrailingslashit( plugins_url( '/', NEWSPACK_ADS_PLUGIN_FILE ) ) . $path;
	}

	/**
	 * Should current request be treated as an AMP endpoint.
	 *
	 * @return bool AMP or not
	 */
	public static function is_amp() {
		if ( class_exists( 'Newspack\AMP_Enhancements' ) && method_exists( 'Newspack\AMP_Enhancements', 'should_use_amp_plus' ) && Newspack\AMP_Enhancements::should_use_amp_plus() ) {
			return false;
		}
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return true;
		}
		return false;
	}
	/**
	 * Can the site use AMP Plus features?
	 *
	 * @return bool Configured or not.
	 */
	public static function is_amp_plus_configured() {
		return class_exists( 'Newspack\AMP_Enhancements' ) && method_exists( 'Newspack\AMP_Enhancements', 'is_amp_plus_configured' ) && Newspack\AMP_Enhancements::is_amp_plus_configured();
	}
}
Newspack_Ads::instance();
