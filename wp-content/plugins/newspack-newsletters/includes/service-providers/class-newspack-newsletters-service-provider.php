<?php
/**
 * Service Provider: Mailchimp Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Newsletters Class.
 */
abstract class Newspack_Newsletters_Service_Provider implements Newspack_Newsletters_ESP_API_Interface, Newspack_Newsletters_WP_Hookable_Interface {

	const BASE_NAMESPACE = 'newspack-newsletters/v1/';

	/**
	 * The controller.
	 *
	 * @var \WP_REST_Controller.
	 */
	private $controller;

	/**
	 * Name of the service.
	 *
	 * @var string
	 */
	public $service;

	/**
	 * Instances of descendant service provider classes.
	 *
	 * @var array
	 */
	protected static $instances = [];

	/**
	 * Class constructor.
	 */
	public function __construct() {
		if ( $this->controller && $this->controller instanceof \WP_REST_Controller ) {
			add_action( 'rest_api_init', [ $this->controller, 'register_routes' ] );
		}
		add_action( 'pre_post_update', [ $this, 'pre_post_update' ], 10, 2 );
		add_filter( 'wp_insert_post_data', [ $this, 'insert_post_data' ], 10, 2 );
	}

	/**
	 * Manage singleton instances of all descendant service provider classes.
	 */
	public static function instance() {
		if ( empty( self::$instances[ static::class ] ) ) {
			self::$instances[ static::class ] = new static();
		}
		return self::$instances[ static::class ];
	}

	/**
	 * Check capabilities for using the API for authoring tasks.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public function api_authoring_permissions_check( $request ) {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack-newsletters' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}

	/**
	 * Handle newsletter post status changes.
	 *
	 * @param int   $post_id The post ID.
	 * @param array $data    Unslashed post data.
	 */
	public function pre_post_update( $post_id, $data ) {

		// Only run if it's a newsletter post.
		if ( ! Newspack_Newsletters::validate_newsletter_id( $post_id ) ) {
			return;
		}

		// Only run if this is the active provider.
		if ( Newspack_Newsletters::service_provider() !== $this->service ) {
			return;
		}

		$post       = get_post( $post_id );
		$old_status = $post->post_status;
		$new_status = $data['post_status'];
		$sent       = Newspack_Newsletters::is_newsletter_sent( $post_id );

		// Don't run if moving to/from trash.
		if ( 'trash' === $new_status || 'trash' === $old_status ) {
			return;
		}

		// Prevent status change from 'publish' if newsletter has been sent.
		if ( 'publish' === $old_status && 'publish' !== $new_status && $sent ) {
			$error = new WP_Error( 'newspack_newsletters_error', __( 'You cannot change a sent newsletter status.', 'newspack-newsletters' ) );
			wp_die( $error ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// Send if changing from any status to publish.
		if ( 'publish' === $new_status && 'publish' !== $old_status ) {
			$result = $this->send_newsletter( $post );
			if ( is_wp_error( $result ) ) {
				$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
				set_transient( $transient, $result->get_error_message(), 45 );
				wp_die( $result ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * Handle newsletter post status changes.
	 *
	 * @param array $data An array of slashed, sanitized, and processed post data.
	 * @param array $postarr An array of sanitized (and slashed) but otherwise unmodified post data.
	 *
	 * @return array An array of slashed, sanitized, and processed post data.
	 */
	public function insert_post_data( $data, $postarr ) {
		$post_id = $postarr['ID'];

		// Only run if it's a newsletter post.
		if ( ! Newspack_Newsletters::validate_newsletter_id( $post_id ) ) {
			return $data;
		}

		// Only run if this is the active provider.
		if ( Newspack_Newsletters::service_provider() !== $this->service ) {
			return $data;
		}

		$post       = get_post( $post_id );
		$old_status = $post->post_status;
		$new_status = $data['post_status'];
		$sent       = Newspack_Newsletters::is_newsletter_sent( $post_id );

		// If the newsletter is being restored from trash and has been sent,
		// set the status to 'publish'.
		if ( 'trash' === $old_status && 'trash' !== $new_status && $sent ) {
			$data['post_status'] = 'publish';
		}

		return $data;
	}

	/**
	 * Send a newsletter.
	 *
	 * @param WP_Post $post The newsletter post.
	 *
	 * @return true|WP_Error True if successful, WP_Error if not.
	 */
	public function send_newsletter( $post ) {
		$post_id = $post->ID;

		if ( Newspack_Newsletters::is_newsletter_sent( $post_id ) ) {
			return;
		}

		try {
			$result = $this->send( $post );
		} catch ( Exception $e ) {
			$result = new WP_Error( 'newspack_newsletter_error', $e->getMessage() );
		}

		if ( true === $result ) {
			Newspack_Newsletters::set_newsletter_sent( $post_id );
		}

		return $result;
	}
}
