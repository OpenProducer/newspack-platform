<?php
/**
 * Newspack Blocks.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Blocks Class.
 */
final class Blocks {
	/**
	 * Initialize Hooks.
	 */
	public static function init() {
		require_once NEWSPACK_ABSPATH . 'src/blocks/reader-registration/index.php';

		if ( wp_is_block_theme() && class_exists( 'Newspack\Corrections' ) ) {
			require_once NEWSPACK_ABSPATH . 'src/blocks/correction-box/class-correction-box-block.php';
			require_once NEWSPACK_ABSPATH . 'src/blocks/correction-item/class-correction-item-block.php';
		}

		\add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
	}

	/**
	 * Enqueue blocks scripts and styles for editor.
	 */
	public static function enqueue_block_editor_assets() {
		Newspack::load_common_assets();

		\wp_enqueue_script(
			'newspack-blocks',
			Newspack::plugin_url() . '/dist/blocks.js',
			[],
			NEWSPACK_PLUGIN_VERSION,
			true
		);
		\wp_localize_script(
			'newspack-blocks',
			'newspack_blocks',
			[
				'has_newsletters'         => class_exists( 'Newspack_Newsletters_Subscription' ),
				'has_reader_activation'   => Reader_Activation::is_enabled(),
				'newsletters_url'         => Wizards::get_wizard( 'newsletters' )->newsletters_settings_url(),
				'has_google_oauth'        => Google_OAuth::is_oauth_configured(),
				'google_logo_svg'         => \Newspack\Newspack_UI_Icons::get_svg( 'google' ),
				'reader_activation_terms' => Reader_Activation::get_setting( 'terms_text' ),
				'reader_activation_url'   => Reader_Activation::get_setting( 'terms_url' ),
				'has_recaptcha'           => Recaptcha::can_use_captcha(),
				'recaptcha_url'           => admin_url( 'admin.php?page=newspack-settings' ),
				'corrections_enabled'     => wp_is_block_theme() && class_exists( 'Newspack\Corrections' ),
			]
		);
		\wp_enqueue_style(
			'newspack-blocks',
			Newspack::plugin_url() . '/dist/blocks.css',
			[],
			NEWSPACK_PLUGIN_VERSION
		);
	}
}
Blocks::init();
