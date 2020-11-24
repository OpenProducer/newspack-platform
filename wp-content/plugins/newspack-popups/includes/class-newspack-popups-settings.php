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
	 * Update settings.
	 *
	 * @param object $options options.
	 */
	public static function set_settings( $options ) {
		if ( update_option( $options['option_name'], $options['option_value'] ) ) {
			return self::get_settings();
		} else {
			return new \WP_Error(
				'newspack_popups_settings_error',
				esc_html__( 'Error updating the settings.', 'newspack' )
			);
		}
	}

	/**
	 * Return a single setting value.
	 *
	 * @param string $key Key name.
	 */
	public static function get_setting( $key ) {
		return get_option( $key, true );
	}

	/**
	 * Return all settings.
	 */
	public static function get_settings() {
		return [
			[
				'key'   => 'suppress_newsletter_campaigns',
				'value' => get_option( 'suppress_newsletter_campaigns', true ),
				'label' => __(
					'Suppress Newsletter campaigns if visitor is coming from email.',
					'newspack-popups'
				),
			],
			[
				'key'   => 'suppress_all_newsletter_campaigns_if_one_dismissed',
				'value' => get_option( 'suppress_all_newsletter_campaigns_if_one_dismissed', true ),
				'label' => __(
					'Suppress all Newsletter campaigns if at least one Newsletter campaign was permanently dismissed.',
					'newspack-popups'
				),
			],
			[
				'key'   => 'suppress_donation_campaigns_if_donor',
				'value' => get_option( 'suppress_donation_campaigns_if_donor', false ),
				'label' => __(
					'Suppress all donation campaigns if the reader has donated.',
					'newspack-popups'
				),
			],
			[
				'key'   => 'newspack_newsletters_non_interative_mode',
				'value' => self::is_non_interactive(),
				'label' => __(
					'Enable non-interactive mode.',
					'newspack-popups'
				),
				'help'  => __(
					'Use this setting in high traffic scenarios. No API requests will be made, reducing server load. Inline campaigns will be shown to all users without dismissal buttons, and overlay campaigns will be suppressed.',
					'newspack-popups'
				),
			],
			[
				'key'   => 'all_segments',
				'value' => array_reduce(
					Newspack_Popups_Segmentation::get_segments(),
					function( $acc, $item ) {
						$acc[ $item['id'] ] = $item['configuration'];
						return $acc;
					},
					[]
				),
			],
		];
	}

	/**
	 * Is the non-interactive setting on?
	 */
	public static function is_non_interactive() {
		return get_option( 'newspack_newsletters_non_interative_mode', false );
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
