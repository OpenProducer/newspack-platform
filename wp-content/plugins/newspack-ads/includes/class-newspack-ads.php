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
	protected static $_instance = null;

	/**
	 * Main Newspack Ads Instance.
	 * Ensures only one instance of Newspack Ads is loaded or can be loaded.
	 *
	 * @return Newspack Ads - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
	}

	/**
	 * Define Constants.
	 */
	private function define_constants() {
		define( 'NEWSPACK_ADS_VERSION', '0.0.1' );
		define( 'NEWSPACK_ADS_ABSPATH', dirname( NEWSPACK_ADS_PLUGIN_FILE ) . '/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 * e.g. include_once NEWSPACK_ADS_ABSPATH . 'includes/foo.php';
	 */
	private function includes() {
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-blocks.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-model.php';
		include_once NEWSPACK_ADS_ABSPATH . '/includes/class-newspack-ads-widget.php';
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
}
Newspack_Ads::instance();
