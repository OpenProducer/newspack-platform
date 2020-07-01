<?php
/**
 * Start: Include for phase 2
 * REST API: WP_REST_Menus_Controller class
 *
 * @package    WordPress
 * @subpackage REST_API
 */

/**
 * Controller which provides REST API endpoints for image editing.
 *
 * @since 7.x ?
 *
 * @see WP_REST_Controller
 */
class WP_REST_Image_Editor_Controller extends WP_REST_Controller {

	/**
	 * Constructs the controller.
	 *
	 * @since 7.x ?
	 * @access public
	 */
	public function __construct() {
		$this->namespace = '__experimental';
		$this->rest_base = '/richimage/(?P<media_id>[\d]+)';
	}

	/**
	 * Registers the necessary REST API routes.
	 *
	 * @since 7.x ?
	 * @access public
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/apply',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'apply_edits' ),
					'permission_callback' => array( $this, 'permission_callback' ),
					'args'                => array(
						'x'        => array(
							'type'     => 'float',
							'minimum'  => 0,
							'required' => true,
						),
						'y'        => array(
							'type'     => 'float',
							'minimum'  => 0,
							'required' => true,
						),
						'width'    => array(
							'type'     => 'float',
							'minimum'  => 1,
							'required' => true,
						),
						'height'   => array(
							'type'     => 'float',
							'minimum'  => 1,
							'required' => true,
						),
						'rotation' => array(
							'type' => 'integer',
						),
					),
				),
			)
		);
	}

	/**
	 * Checks if the user has permissions to make the request.
	 *
	 * @since 7.x ?
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function permission_callback( $request ) {
		if ( ! current_user_can( 'edit_post', $request['media_id'] ) ) {
			return new WP_Error( 'rest_cannot_edit_image', __( 'Sorry, you are not allowed to edit images.', 'gutenberg' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Applies all edits in one go.
	 *
	 * @since 7.x ?
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return array|WP_Error If successful image JSON for the modified image, otherwise a WP_Error.
	 */
	public function apply_edits( $request ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$params = $request->get_params();

		$media_id = $params['media_id'];

		// Get image information.
		$attachment_info = wp_get_attachment_metadata( $media_id );
		$media_url       = wp_get_attachment_image_url( $media_id, 'original' );

		if ( ! $attachment_info || ! $media_url ) {
			return new WP_Error( 'unknown', 'Unable to get meta information for file' );
		}

		$meta = array( 'original_name' => basename( $media_url ) );

		if ( isset( $attachment_info['richimage'] ) ) {
			$meta = array_merge( $meta, $attachment_info['richimage'] );
		}

		// Try and load the image itself.
		$image_path = get_attached_file( $media_id );
		if ( empty( $image_path ) ) {
			return new WP_Error( 'fileunknown', 'Unable to find original media file' );
		}

		$image_editor = wp_get_image_editor( $image_path );
		if ( ! $image_editor->load() ) {
			return new WP_Error( 'fileload', 'Unable to load original media file' );
		}

		if ( isset( $params['rotation'] ) ) {
			$image_editor->rotate( 0 - $params['rotation'] );
		}

		$size = $image_editor->get_size();

		// Finally apply the modifications.
		$crop_x = round( ( $size['width'] * floatval( $params['x'] ) ) / 100.0 );
		$crop_y = round( ( $size['height'] * floatval( $params['y'] ) ) / 100.0 );
		$width  = round( ( $size['width'] * floatval( $params['width'] ) ) / 100.0 );
		$height = round( ( $size['height'] * floatval( $params['height'] ) ) / 100.0 );
		$image_editor->crop( $crop_x, $crop_y, $width, $height );

		// TODO: Generate filename based on edits.
		$target_file = 'edited-' . $meta['original_name'];

		$filename = rtrim( dirname( $image_path ), '/' ) . '/' . $target_file;

		// Save to disk.
		$saved = $image_editor->save( $filename );

		if ( is_wp_error( $saved ) ) {
			return $saved;
		}

		// Update attachment details.
		$attachment_post = array(
			'guid'           => $saved['path'],
			'post_mime_type' => $saved['mime-type'],
			'post_title'     => pathinfo( $target_file, PATHINFO_FILENAME ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Add this as an attachment.
		$attachment_id = wp_insert_attachment( $attachment_post, $saved['path'], 0 );
		if ( 0 === $attachment_id ) {
			return new WP_Error( 'attachment', 'Unable to add image as attachment' );
		}

		// Generate thumbnails.
		$metadata = wp_generate_attachment_metadata( $attachment_id, $saved['path'] );

		$metadata['richimage'] = $meta;

		wp_update_attachment_metadata( $attachment_id, $metadata );

		return array(
			'media_id' => $attachment_id,
			'url'      => wp_get_attachment_image_url( $attachment_id, 'original' ),
		);
	}
}
