<?php
/**
 * Loads and prepares everything for unit testing.
 *
 * @package Newspack\Tests
 */

/**
 * Newspack Unit Tests Bootstrap.
 */
class Newspack_Unit_Tests_Bootstrap {

	/**
	 * The unit tests bootstrap instance.
	 *
	 * @var Newspack_Unit_Tests_Bootstrap
	 */
	protected static $instance = null;

	/**
	 * The directory of this plugin.
	 *
	 * @var string
	 */
	public $plugin_dir;

	/**
	 * Setup the unit testing environment.
	 */
	public function __construct() {

		// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions, WordPress.PHP.DevelopmentFunctions, WordPress.PHP.IniSet.display_errors_Blacklisted
		ini_set( 'display_errors', 'on' );
		error_reporting( E_ALL );
		// phpcs:enable WordPress.PHP.DiscouragedPHPFunctions, WordPress.PHP.DevelopmentFunctions, WordPress.PHP.IniSet.display_errors_Blacklisted

		// Ensure server variable is set for WP email functions.
		// phpcs:disable WordPress.VIP.SuperGlobalInputUsage.AccessDetected
		if ( ! isset( $_SERVER['SERVER_NAME'] ) ) {
			$_SERVER['SERVER_NAME'] = 'localhost';
		}
		// phpcs:enable WordPress.VIP.SuperGlobalInputUsage.AccessDetected

		$_tests_dir = getenv( 'WP_TESTS_DIR' );

		if ( ! $_tests_dir ) {
			$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
		}

		if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
			echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore
			exit( 1 );
		}

		$this->plugin_dir = dirname( dirname( __FILE__ ) );

		// Load test function so tests_add_filter() is available.
		require_once $_tests_dir . '/includes/functions.php';

		// Load Newspack.
		tests_add_filter( 'muplugins_loaded', array( $this, 'load_newspack' ) );

		// Install Newspack.
		tests_add_filter( 'setup_theme', array( $this, 'install_newspack' ) );

		// Load the WP testing environment.
		require_once $_tests_dir . '/includes/bootstrap.php';
	}

	/**
	 * Load Newspack.
	 */
	public function load_newspack() {
		require_once $this->plugin_dir . '/newspack.php';
	}

	/**
	 * Install Newspack after the test environment and Newspack have been loaded.
	 */
	public function install_newspack() {

		// Clean existing install first.
		// define( 'WP_UNINSTALL_PLUGIN', true );
		// @todo Create uninstaller if needed.
		// include $this->plugin_dir . '/uninstall.php';
		// Install the plugin here if needed.
		// Reload capabilities after install, see https://core.trac.wordpress.org/ticket/28374.
		// phpcs:disable WordPress.WP.GlobalVariablesOverride.DeprecatedWhitelistCommentFound
		$GLOBALS['wp_roles'] = null; // WPCS: override ok.
		// phpcs:enable WordPress.WP.GlobalVariablesOverride.DeprecatedWhitelistCommentFound
		wp_roles();

		echo esc_html( 'Installing Newspack...' . PHP_EOL );
	}

	/**
	 * Get the single class instance.
	 *
	 * @return Newspack_Unit_Tests_Bootstrap
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Newspack_Unit_Tests_Bootstrap::instance();
