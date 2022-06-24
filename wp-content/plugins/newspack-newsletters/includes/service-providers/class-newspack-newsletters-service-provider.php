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
	 * Post statuses controlled by the service provider.
	 *
	 * @var string[]
	 */
	protected static $controlled_statuses = [ 'publish', 'private' ];

	/**
	 * Class constructor.
	 */
	public function __construct() {
		if ( $this->controller && $this->controller instanceof \WP_REST_Controller ) {
			add_action( 'rest_api_init', [ $this->controller, 'register_routes' ] );
		}
		add_action( 'pre_post_update', [ $this, 'pre_post_update' ], 10, 2 );
		add_action( 'transition_post_status', [ $this, 'transition_post_status' ], 10, 3 );
		add_action( 'wp_insert_post', [ $this, 'insert_post' ], 10, 3 );
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

		// Prevent status change from the controlled status if newsletter has been sent.
		if ( ! in_array( $new_status, self::$controlled_statuses, true ) && $old_status !== $new_status && $sent ) {
			$error = new WP_Error( 'newspack_newsletters_error', __( 'You cannot change a sent newsletter status.', 'newspack-newsletters' ) );
			wp_die( $error ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// Send if changing from any status to controlled statuses - 'publish' or 'private'.
		if (
			! $sent &&
			$old_status !== $new_status &&
			in_array( $new_status, self::$controlled_statuses, true ) && 
			! in_array( $old_status, self::$controlled_statuses, true )
		) {
			$result = $this->send_newsletter( $post );
			if ( is_wp_error( $result ) ) {
				$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
				set_transient( $transient, $result->get_error_message(), 45 );
				wp_die( $result ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	/**
	 * Handle post status transition for scheduled newsletters.
	 *
	 * This is executed after the post is updated.
	 *
	 * Scheduling a post (future -> publish) does not trigger the
	 * `pre_post_update` action hook because it uses the `wp_publish_post()`
	 * function. Unfortunately, this function does not fire any action hook prior
	 * to updating the post, so, for this case, we need to handle sending after
	 * the post is published.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Post object.
	 */
	public function transition_post_status( $new_status, $old_status, $post ) {
		// Only run if it's a newsletter post.
		if ( ! Newspack_Newsletters::validate_newsletter_id( $post->ID ) ) {
			return;
		}

		// Only run if this is the active provider.
		if ( Newspack_Newsletters::service_provider() !== $this->service ) {
			return;
		}

		if ( in_array( $new_status, self::$controlled_statuses, true ) && 'future' === $old_status ) {
			update_post_meta( $post->ID, 'sending_scheduled', true );
			$result              = $this->send_newsletter( $post );
			$error_transient_key = sprintf( 'newspack_newsletters_scheduling_error_%s', $post->ID );
			if ( is_wp_error( $result ) ) {
				set_transient( $error_transient_key, $result->get_error_message() );
				wp_update_post(
					[
						'ID'          => $post->ID,
						'post_status' => 'draft',
					] 
				);
				wp_die( $result ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				delete_transient( $error_transient_key );
			}
			delete_post_meta( $post->ID, 'sending_scheduled' );
		}
	}

	/**
	 * Fix a newsletter controlled status after update.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an existing post being updated.
	 */
	public function insert_post( $post_id, $post, $update ) {
		// Only run if it's a newsletter post.
		if ( ! Newspack_Newsletters::validate_newsletter_id( $post_id ) ) {
			return;
		}
		
		// Only run if this is the active provider.
		if ( Newspack_Newsletters::service_provider() !== $this->service ) {
			return;
		}

		// Only run if the post already exists.
		if ( ! $update ) {
			return;
		}

		$is_public = (bool) get_post_meta( $post_id, 'is_public', true );

		/**
		 * Control 'publish' and 'private' statuses using the 'is_public' meta.
		 */
		$target_status = 'private';
		if ( $is_public ) {
			$target_status = 'publish';
		}

		if ( in_array( $post->post_status, self::$controlled_statuses, true ) && $target_status !== $post->post_status ) {
			wp_update_post(
				[
					'ID'          => $post_id,
					'post_status' => $target_status,
				] 
			);
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
		$is_public  = (bool) get_post_meta( $post->ID, 'is_public', true );

		/**
		 * Control 'publish' and 'private' statuses using the 'is_public' meta.
		 */
		$target_status = 'private';
		if ( $is_public ) {
			$target_status = 'publish';
		}
		if ( in_array( $new_status, self::$controlled_statuses, true ) ) {
			$data['post_status'] = $target_status;
		}

		/**
		 * Ensure sent newsletter will not be set to draft.
		 */
		if ( $sent && 'draft' === $new_status ) {
			$data['post_status'] = $target_status;
		}

		/**
		 * If the newsletter is being restored from trash and has been sent,
		 * use controlled status.
		 */
		if ( 'trash' === $old_status && 'trash' !== $new_status && $sent ) {
			$data['post_status'] = $target_status;
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
