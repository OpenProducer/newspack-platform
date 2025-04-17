<?php
/**
 * Handles Default Image functionality.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Handles Default Image functionality.
 */
class Default_Image {
	/**
	 * Form field name.
	 */
	const FIELD_NAME = 'is_default_image';

	/**
	 * Option name.
	 */
	const OPTION_NAME = 'newspack_default_image_url';

	/**
	 * Add hooks.
	 */
	public static function init() {
		add_filter( 'attachment_fields_to_save', [ __CLASS__, 'save_attachement_settings' ], 10, 2 );
		add_filter( 'attachment_fields_to_edit', [ __CLASS__, 'add_attachment_field' ], 10, 2 );
		add_action( 'template_redirect', [ __CLASS__, 'handle_not_found_image' ] );
		add_action( 'wp_head', [ __CLASS__, 'add_404_image_handler_function' ] );
		add_filter( 'the_content', [ __CLASS__, 'add_onerror_to_images' ] );
	}

	/**
	 * Handle not found image.
	 */
	public static function handle_not_found_image() {
		if ( ! is_404() || empty( $_SERVER['REQUEST_URI'] ) ) {
			return;
		}
		$requested_url = $_SERVER['REQUEST_URI']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( preg_match( '/\.(jpg|jpeg|png|gif|webp)$/i', $requested_url ) ) {
			$default_image_url = get_option( self::OPTION_NAME );
			if ( ! empty( $default_image_url ) ) {
				wp_safe_redirect( $default_image_url, 301 );
				exit;
			}
		}
	}

	/**
	 * Add the relevant setting in the media editing screen.
	 *
	 * @param array   $fields Array of media editor field info.
	 * @param WP_Post $post Post object for current attachment.
	 * @return array Modified $fields.
	 */
	public static function add_attachment_field( $fields, $post ) {
		$field_name = 'attachments[' . $post->ID . '][' . self::FIELD_NAME . ']';
		$value = ( wp_get_attachment_url( $post->ID ) === get_option( self::OPTION_NAME ) );
		$fields[ self::FIELD_NAME ] = [
			'label' => __( 'Is default image?', 'newspack-image-credits' ),
			'input' => 'html',
			'html'  => '<input id="' . $field_name . '" name="' . $field_name . '" type="hidden" value="0" /><input id="' . $field_name . '" name="' . $field_name . '" type="checkbox" value="1" ' . checked( $value, true, false ) . ' />',
			'helps' => __( 'Use this image as a default image, if the requested one is not found (404 status).', 'newspack-plugin' ),
		];
		return $fields;
	}

	/**
	 * Save the relevant setting.
	 *
	 * @param array $post Array of post info.
	 * @param array $attachment Array of media field input info.
	 * @return array $post Unmodified post info.
	 */
	public static function save_attachement_settings( $post, $attachment ) {
		if ( isset( $attachment[ self::FIELD_NAME ] ) ) {
			if ( '1' === $attachment[ self::FIELD_NAME ] ) {
				update_option( self::OPTION_NAME, wp_get_attachment_url( $post['ID'] ) );
			} else {
				delete_option( self::OPTION_NAME );
			}
		}

		return $post;
	}

	/**
	 * Add JavaScript function to handle 404 images.
	 * If an image is served from a different domain, the `template_redirect` action won't catch a 404.
	 * Adding a JS fallback ensures all images will have the default applied.
	 *
	 * Note that a better approach would be to use `img.addEventListener` here, instead of
	 * adding the `onerror` attribute in `add_onerror_to_images` method, but
	 * the event listener way for some reason did not work in testing. This solution
	 * is less elegant, but more robust.
	 */
	public static function add_404_image_handler_function() {
		$default_image_url = get_option( self::OPTION_NAME );
		if ( ! empty( $default_image_url ) ) {
			echo '<script>
				function newspackHandleImageError(img) {
					if (!img.dataset.defaultImageHandled) {
						img.dataset.defaultImageHandled = true;
						img.src = "' . esc_js( $default_image_url ) . '";
					}
				}
			</script>';
		}
	}

	/**
	 * Add the onerror attribute to all img elements in the content.
	 *
	 * @param string $content The post content.
	 * @return string Modified content with onerror attributes.
	 */
	public static function add_onerror_to_images( $content ) {
		$content = preg_replace_callback(
			'/<img[^>]+>/i',
			function ( $matches ) {
				if ( strpos( $matches[0], 'onerror=' ) === false ) {
					return preg_replace(
						'/<img/',
						'<img onerror="if (typeof newspackHandleImageError === \'function\') newspackHandleImageError(this);"',
						$matches[0],
						1
					);
				}
				return $matches[0];
			},
			$content
		);
		return $content;
	}
}
Default_Image::init();
