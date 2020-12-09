<?php
/**
 * Newspack setup
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Class.
 */
final class Newspack {

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack
	 */
	protected static $_instance = null; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

	/**
	 * Main Newspack Instance.
	 * Ensures only one instance of Newspack is loaded or can be loaded.
	 *
	 * @return Newspack - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Newspack Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		add_action( 'admin_init', [ $this, 'admin_redirects' ] );
		add_action( 'admin_menu', [ $this, 'remove_all_newspack_options' ], 1 );
		add_action( 'admin_menu', [ $this, 'remove_newspack_suite_plugin_links' ], 1 );
		add_action( 'admin_notices', [ $this, 'remove_notifications' ], -9999 );
		add_action( 'network_admin_notices', [ $this, 'remove_notifications' ], -9999 );
		add_action( 'all_admin_notices', [ $this, 'remove_notifications' ], -9999 );
		register_activation_hook( NEWSPACK_PLUGIN_FILE, [ $this, 'activation_hook' ] );
	}

	/**
	 * Define WC Constants.
	 */
	private function define_constants() {
		define( 'NEWSPACK_VERSION', '0.0.1' );
		define( 'NEWSPACK_ABSPATH', dirname( NEWSPACK_PLUGIN_FILE ) . '/' );
		if ( ! defined( 'NEWSPACK_COMPOSER_ABSPATH' ) ) {
			define( 'NEWSPACK_COMPOSER_ABSPATH', dirname( NEWSPACK_PLUGIN_FILE ) . '/vendor/' );
		}
		define( 'NEWSPACK_ACTIVATION_TRANSIENT', '_newspack_activation_redirect' );
		define( 'NEWSPACK_READER_REVENUE_PLATFORM', 'newspack_reader_revenue_platform' );
		define( 'NEWSPACK_NRH_CONFIG', 'newspack_nrh_config' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 * e.g. include_once NEWSPACK_ABSPATH . 'includes/foo.php';
	 */
	private function includes() {
		include_once NEWSPACK_ABSPATH . 'includes/util.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-plugin-manager.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-theme-manager.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-admin-plugins-screen.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-api.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-profile.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-analytics.php';

		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-setup-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-dashboard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-components-demo.php';

		/* Unified Wizards */
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-advertising-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-analytics-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-engagement-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-reader-revenue-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-seo-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-site-design-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-syndication-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-health-check-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-support-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-popups-wizard.php';
		include_once NEWSPACK_ABSPATH . 'includes/wizards/class-updates-wizard.php';

		include_once NEWSPACK_ABSPATH . 'includes/class-wizards.php';

		include_once NEWSPACK_ABSPATH . 'includes/class-handoff-banner.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-donations.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-salesforce.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-pwa.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-starter-content.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-amp-enhancements.php';
		include_once NEWSPACK_ABSPATH . 'includes/class-webhooks.php';

		if ( 'nrh' === get_option( NEWSPACK_READER_REVENUE_PLATFORM ) ) {
			include_once NEWSPACK_ABSPATH . 'includes/class-nrh.php';
		}

		include_once NEWSPACK_ABSPATH . 'includes/class-settings.php';

		include_once NEWSPACK_ABSPATH . 'includes/configuration_managers/class-configuration-managers.php';
	}

	/**
	 * Get the URL for the Newspack plugin directory.
	 *
	 * @return string URL
	 */
	public static function plugin_url() {
		return untrailingslashit( plugins_url( '/', NEWSPACK_PLUGIN_FILE ) );
	}

	/**
	 * Remove links to Newspack suite's plugins – they have wizards in this plugin.
	 */
	public static function remove_newspack_suite_plugin_links() {
		global $menu;
		foreach ( $menu as $key => $value ) {
			if (
				class_exists( 'Newspack_Popups' ) && 'edit.php?post_type=' . \Newspack_Popups::NEWSPACK_PLUGINS_CPT === $value[2]
			) {
				unset( $menu[ $key ] );
			}
		}
	}

	/**
	 * Reset Newspack by removing all newspack prefixed options. Triggered by the query param reset_newspack_settings=1
	 */
	public function remove_all_newspack_options() {
		if ( filter_input( INPUT_POST, 'newspack_reset', FILTER_SANITIZE_STRING ) === 'on' ) {
			$all_options = wp_load_alloptions();
			foreach ( $all_options as $key => $value ) {
				if ( strpos( $key, 'newspack' ) === 0 || strpos( $key, '_newspack' ) === 0 ) {
					delete_option( $key );
				}
			}
			wp_safe_redirect( admin_url( 'admin.php?page=newspack-setup-wizard' ) );
			exit;
		}
		if ( Support_Wizard::get_wpcom_access_token() && filter_input( INPUT_POST, 'newspack_remove_wpcom_token', FILTER_SANITIZE_STRING ) === 'on' ) {
			delete_user_meta( get_current_user_id(), Support_Wizard::NEWSPACK_WPCOM_ACCESS_TOKEN );
		}
	}

	/**
	 * Remove notifications.
	 */
	public function remove_notifications() {
		$screen = get_current_screen();

		$is_newspack_screen = ( 'newspack' === $screen->parent_base ) || ( 'admin_page_newspack-' === substr( $screen->base, 0, 20 ) );
		if ( ! $screen || ! $is_newspack_screen ) {
			return;
		}
		remove_all_actions( current_action() );
	}

	/**
	 * Activation Hook
	 */
	public function activation_hook() {
		set_transient( NEWSPACK_ACTIVATION_TRANSIENT, 1, 30 );
	}

	/**
	 * Handle initial redirect after activation
	 */
	public function admin_redirects() {
		if ( ! get_transient( NEWSPACK_ACTIVATION_TRANSIENT ) || wp_doing_ajax() || is_network_admin() || ! current_user_can( 'install_plugins' ) ) {
			return;
		}
		delete_transient( NEWSPACK_ACTIVATION_TRANSIENT );
		wp_safe_redirect( admin_url( 'admin.php?page=newspack-setup-wizard' ) );
		exit;
	}
}
Newspack::instance();
