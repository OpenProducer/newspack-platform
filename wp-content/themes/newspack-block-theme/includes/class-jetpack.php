<?php
/**
 * Jetpack compatibility file.
 * See: http://jetpack.com/
 *
 * @package Newspack_Block_Theme
 */

namespace Newspack_Block_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Main Jetpack class.
 */
final class Jetpack {
	/**
	 * The single instance of the class.
	 *
	 * @var Jetpack
	 */
	protected static $instance = null;

	/**
	 * Main Jetpack instance.
	 * Ensures only one instance of Jetpack is loaded or can be loaded.
	 *
	 * @return Jetpack - Main instance.
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
		\add_action( 'after_setup_theme', [ __CLASS__, 'theme_support' ] );
		\add_filter( 'sharing_show', '__return_false' );
		\add_filter( 'sharing_enqueue_scripts', '__return_false' );
		\add_filter( 'post_flair_disable', '__return_true' );
	}

	/**
	 * Registers support for various Jetpack features.
	 *
	 * @since Newspack Block Theme 1.0
	 *
	 * @return void
	 */
	public static function theme_support() {
		// Add theme support for geo-location.
		\add_theme_support( 'jetpack-geo-location' );
	}
}

Jetpack::instance();
