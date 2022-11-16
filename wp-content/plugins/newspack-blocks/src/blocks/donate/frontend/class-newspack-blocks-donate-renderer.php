<?php
/**
 * Server-side rendering of the `newspack-blocks/donate` block.
 *
 * @package WordPress
 */

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/blocks/donate/frontend/class-newspack-blocks-donate-renderer-frequency-based.php';

/**
 * Server-side rendering of the `newspack-blocks/donate` block.
 */
class Newspack_Blocks_Donate_Renderer {
	/**
	 * Enqueue frontend scripts and styles.
	 *
	 * @param string $handle_slug The slug of the script to enqueue.
	 */
	private static function enqueue_scripts( $handle_slug ) {
		$dependencies = [ 'wp-i18n' ];

		if ( 'streamlined' === $handle_slug ) {
			if ( method_exists( '\Newspack\Recaptcha', 'can_use_captcha' ) && \Newspack\Recaptcha::can_use_captcha() ) {
				$dependencies[] = \Newspack\Recaptcha::SCRIPT_HANDLE;
			}
		}

		switch ( $handle_slug ) {
			case 'streamlined':
				$filename = 'donateStreamlined';
				break;
			case 'frequency-based':
				$filename = 'frequencyBased';
				break;
			default:
				$filename = false;
				break;
		}

		if ( false === $filename ) {
			return;
		}

		$script_data = Newspack_Blocks::script_enqueue_helper( NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . '/' . $filename . '.js' );
		wp_enqueue_script(
			Newspack_Blocks::SCRIPT_HANDLES[ $handle_slug ],
			$script_data['script_path'],
			$dependencies,
			$script_data['version'],
			true
		);

		$style_path = NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . $filename . ( is_rtl() ? '.rtl' : '' ) . '.css';
		wp_enqueue_style(
			Newspack_Blocks::SCRIPT_HANDLES[ $handle_slug ],
			plugins_url( $style_path, NEWSPACK_BLOCKS__PLUGIN_FILE ),
			[],
			NEWSPACK_BLOCKS__VERSION
		);
	}

	/**
	 * Renders the `newspack-blocks/donate` block on server.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string
	 */
	public static function render( $attributes ) {
		if ( ! class_exists( 'Newspack\Donations' ) ) {
			return '';
		}

		$configuration = Newspack_Blocks_Donate_Renderer_Frequency_Based::get_configuration( $attributes );
		if ( \is_wp_error( $configuration ) ) {
			return '';
		}

		if ( $configuration['is_rendering_streamlined_block'] ) {
			self::enqueue_scripts( 'streamlined' );
		}

		Newspack_Blocks::enqueue_view_assets( 'donate' );

		self::enqueue_scripts( 'frequency-based' );
		return Newspack_Blocks_Donate_Renderer_Frequency_Based::render( $attributes );
	}
}
