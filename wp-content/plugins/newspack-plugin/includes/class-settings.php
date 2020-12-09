<?php
/**
 * Starter content management class.
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Manages Settings page.
 */
class Settings {
	/**
	 * Set up hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'add_plugin_page' ] );
		add_action( 'admin_init', [ __CLASS__, 'page_init' ] );
	}

	/**
	 * Add options page
	 */
	public static function add_plugin_page() {
		add_options_page(
			'Settings Admin',
			'Newspack',
			'manage_options',
			'newspack-settings-admin',
			[ __CLASS__, 'create_admin_page' ]
		);
	}

	/**
	 * Options page callback
	 */
	public static function create_admin_page() {
		$newspack_debug = get_option( 'newspack_debug' );
		?>
		<div class="wrap">
			<h1>Newspack Settings</h1>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'newspack_options_group' );
				do_settings_sections( 'newspack-settings-admin' );
				submit_button();
				printf(
					wp_kses(
						/* translators: %2$s: Set Up Wizard, %4$s: Components Demo */
						'<p><a href="%1$s">%2$s</a> | <a href="%3$s">%4$s</a></p>',
						array(
							'p' => array(),
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( admin_url( 'admin.php?page=newspack-setup-wizard' ) ),
					esc_attr( __( 'Setup Wizard', 'newspack' ) ),
					esc_url( admin_url( 'admin.php?page=newspack-components-demo' ) ),
					esc_attr( __( 'Components Demo', 'newspack' ) )
				);
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public static function page_init() {
		register_setting(
			'newspack_options_group',
			'newspack_debug'
		);
		add_settings_section(
			'newspack_settings',
			'Newspack Custom Settings',
			null,
			'newspack-settings-admin'
		);
		add_settings_field(
			'newspack_debug',
			__( 'Debug Mode', 'newspack' ),
			[ __CLASS__, 'newspack_debug_callback' ],
			'newspack-settings-admin',
			'newspack_settings'
		);
		add_settings_field(
			'newspack_reset',
			__( 'Reset Newspack', 'newspack' ),
			[ __CLASS__, 'newspack_reset_callback' ],
			'newspack-settings-admin',
			'newspack_settings'
		);
		if ( Support_Wizard::get_wpcom_access_token() ) {
			add_settings_field(
				'newspack_remove_wpcom_token',
				__( 'Reset WordPress.com authentication', 'newspack' ),
				[ __CLASS__, 'newspack_remove_wpcom_token_callback' ],
				'newspack-settings-admin',
				'newspack_settings'
			);
		}
	}

	/**
	 * Render Debug checkbox.
	 */
	public static function newspack_debug_callback() {
		$newspack_debug = get_option( 'newspack_debug', false );
		$newspack_value = $newspack_debug ? 'checked' : '';
		printf(
			'<input type="checkbox" id="newspack_debug" name="newspack_debug" %s />',
			esc_attr( $newspack_value )
		);
	}

	/**
	 * Render Reset checkbox.
	 */
	public static function newspack_reset_callback() {
		printf(
			'<input type="checkbox" id="newspack_reset" name="newspack_reset" />'
		);
	}

	/**
	 * Render reset subscription checkbox.
	 */
	public static function newspack_reset_subscription_callback() {
		printf(
			'<input type="checkbox" id="newspack_reset_subscription" name="newspack_reset_subscription" />'
		);
	}

	/**
	 * Render remove WPCOM token checkbox.
	 */
	public static function newspack_remove_wpcom_token_callback() {
		printf(
			'<input type="checkbox" id="newspack_remove_wpcom_token" name="newspack_remove_wpcom_token" />'
		);
	}
}

if ( is_admin() ) {
	Settings::init();
}
