<?php
/**
 * Newspack Popups Settings Page
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manages Settings page.
 */
class Newspack_Popups_Settings {
	const NEWSPACK_POPUPS_SETTINGS_PAGE = 'newspack-popups-settings-admin';

	/**
	 * Set up hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'add_plugin_page' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ] );
	}

	/**
	 * Add settings page.
	 */
	public static function add_plugin_page() {
		add_submenu_page(
			'edit.php?post_type=' . Newspack_Popups::NEWSPACK_PLUGINS_CPT,
			__( 'Campaigns Settings', 'newspack-popups' ),
			__( 'Settings', 'newspack-popups' ),
			'manage_options',
			self::NEWSPACK_POPUPS_SETTINGS_PAGE,
			[ __CLASS__, 'create_admin_page' ]
		);
	}

	/**
	 * Settings page callback.
	 */
	public static function create_admin_page() {
		?>
		<div id="newspack-popups-settings-root"></div>
		<?php
	}

	/**
	 * Return all settings.
	 */
	public static function get_settings() {
		return [
			'suppress_newsletter_campaigns' => get_option( 'suppress_newsletter_campaigns', true ),
			'suppress_all_newsletter_campaigns_if_one_dismissed' => get_option( 'suppress_all_newsletter_campaigns_if_one_dismissed', true ),
		];
	}

	/**
	 * Load up common JS/CSS for settings.
	 */
	public static function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( Newspack_Popups::NEWSPACK_PLUGINS_CPT . '_page_' . self::NEWSPACK_POPUPS_SETTINGS_PAGE !== $screen->base ) {
			return;
		}

		\wp_register_script(
			'newspack-popups-settings',
			plugins_url( '../dist/settings.js', __FILE__ ),
			[ 'wp-components', 'wp-api-fetch' ],
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/settings.js' ),
			true
		);
		wp_localize_script(
			'newspack-popups-settings',
			'newspack_popups_settings',
			self::get_settings()
		);
		\wp_enqueue_script( 'newspack-popups-settings' );
		\wp_register_style(
			'newspack-popups-settings',
			plugins_url( '../dist/settings.css', __FILE__ ),
			null,
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/settings.css' )
		);
		\wp_style_add_data( 'newspack-popups-settings', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-popups-settings' );
	}
}

if ( is_admin() ) {
	Newspack_Popups_Settings::init();
}
