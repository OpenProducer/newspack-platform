<?php
/**
 * Service Provider: Mailchimp Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

use Newspack\Newsletters\Subscription_List;
use Newspack\Newsletters\Subscription_Lists;

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
	 * Whether the provider has support to tags and tags based Subscription Lists.
	 *
	 * @var boolean
	 */
	public static $support_tags = false;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		if ( $this->controller && $this->controller instanceof \WP_REST_Controller ) {
			add_action( 'rest_api_init', [ $this->controller, 'register_routes' ] );
		}
		add_action( 'pre_post_update', [ $this, 'pre_post_update' ], 10, 2 );
		add_action( 'transition_post_status', [ $this, 'transition_post_status' ], 10, 3 );
		add_action( 'updated_post_meta', [ $this, 'updated_post_meta' ], 10, 4 );
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
			$error = new WP_Error( 'newspack_newsletters_error', __( 'You cannot change a sent newsletter status.', 'newspack-newsletters' ), [ 'status' => 403 ] );
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
	 * Updated post meta
	 *
	 * @param int    $meta_id    ID of updated metadata entry.
	 * @param int    $post_id    Post ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 */
	public function updated_post_meta( $meta_id, $post_id, $meta_key, $meta_value ) {
		// Only run if it's a newsletter post.
		if ( ! Newspack_Newsletters::validate_newsletter_id( $post_id ) ) {
			return;
		}

		// Only run if this is the active provider.
		if ( Newspack_Newsletters::service_provider() !== $this->service ) {
			return;
		}

		// Only run if the meta key is the one we're interested in.
		if ( 'is_public' !== $meta_key ) {
			return;
		}

		$is_public = $meta_value;

		$post = get_post( $post_id );
		if ( in_array( $post->post_status, self::$controlled_statuses, true ) ) {
			wp_update_post(
				[
					'ID'          => $post_id,
					'post_status' => $is_public ? 'publish' : 'private',
				]
			);
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
			$result = new WP_Error( 'newspack_newsletter_error', $e->getMessage(), [ 'status' => 400 ] );
		}

		if ( true === $result ) {
			Newspack_Newsletters::set_newsletter_sent( $post_id );
		}

		return $result;
	}

	/**
	 * Update a contact lists subscription.
	 *
	 * @param string   $email           Contact email address.
	 * @param string[] $lists_to_add    Array of list IDs to subscribe the contact to.
	 * @param string[] $lists_to_remove Array of list IDs to remove the contact from.
	 *
	 * @return true|WP_Error True if the contact was updated or error.
	 */
	public function update_contact_lists( $email, $lists_to_add = [], $lists_to_remove = [] ) {
		return new WP_Error( 'newspack_newsletters_not_implemented', __( 'Not implemented', 'newspack-newsletters' ), [ 'status' => 400 ] );
	}

	/**
	 * Get the provider specific labels
	 *
	 * This allows us to make reference to provider specific features in the way the user is used to see them in the provider's UI
	 *
	 * @return array
	 */
	public static function get_labels() {
		return [
			'name'  => '', // The provider name.
			'list'  => __( 'list', 'newspack-newsletters' ), // "list" in lower case singular format.
			'lists' => __( 'lists', 'newspack-newsletters' ), // "list" in lower case plural format.
			'List'  => __( 'List', 'newspack-newsletters' ), // "list" in uppercase case singular format.
			'Lists' => __( 'Lists', 'newspack-newsletters' ), // "list" in uppercase case plural format.
		];
	}

	/**
	 * Get one specific label for the current provider
	 *
	 * @param string $key The label key.
	 * @return string Empty string in case the label is not found.
	 */
	public static function label( $key ) {
		$labels = static::get_labels();
		return $labels[ $key ] ?? '';
	}

	/**
	 * Add or update contact to a list, but handling local Subscription Lists
	 *
	 * The difference between this method and add_contact is that this method will identify and handle local lists
	 *
	 * If the $list_id informed is a local list, it will read its settings and call add_contact with the list associated and also add the tag to the contact
	 *
	 * @param array  $contact      {
	 *    Contact data.
	 *
	 *    @type string   $email    Contact email address.
	 *    @type string   $name     Contact name. Optional.
	 *    @type string[] $metadata Contact additional metadata. Optional.
	 * }
	 * @param string $list_id      List to add the contact to.
	 *
	 * @return array|WP_Error Contact data if it was added, or error otherwise.
	 */
	public function add_contact_handling_local_lists( $contact, $list_id ) {
		if ( Subscription_List::is_form_id( $list_id ) ) {
			try {
				$list = new Subscription_List( $list_id );
				
				if ( ! $list->is_configured_for_provider( $this->service ) ) {
					return new WP_Error( 'List not properly configured for the provider' );
				}
				$list_settings = $list->get_provider_settings( $this->service );

				$added_contact = $this->add_contact( $contact, $list_settings['list'] );

				if ( is_wp_error( $added_contact ) ) {
					return $added_contact;
				}

				if ( static::$support_tags ) {
					$this->add_tag_to_contact( $contact['email'], (int) $list_settings['tag_id'], $list_settings['list'] );
				}

				return $added_contact;

			} catch ( \InvalidArgumentException $e ) {
				return new WP_Error( 'List not found' );
			}
		}
		return $this->add_contact( $contact, $list_id );
	}

	/**
	 * Update a contact lists subscription, but handling local Subscription Lists
	 *
	 * The difference between this method and update_contact_lists is that this method will identify and handle local lists
	 *
	 * @param string   $email           Contact email address.
	 * @param string[] $lists_to_add    Array of list IDs to subscribe the contact to.
	 * @param string[] $lists_to_remove Array of list IDs to remove the contact from.
	 *
	 * @return true|WP_Error True if the contact was updated or error.
	 */
	public function update_contact_lists_handling_local( $email, $lists_to_add = [], $lists_to_remove = [] ) {
		$contact = $this->get_contact_data( $email );
		if ( is_wp_error( $contact ) ) {
			// Create contact.
			// Use  Newspack_Newsletters_Subscription::add_contact to trigger hooks and call add_contact_handling_local_lists if needed.
			$result = Newspack_Newsletters_Subscription::add_contact( [ 'email' => $email ], $lists_to_add );
			if ( is_wp_error( $result ) ) {
				return $result;
			}
			return true;
		}
		if ( static::$support_tags ) {
			$lists_to_add    = $this->update_contact_local_lists( $email, $lists_to_add, 'add' );
			$lists_to_remove = $this->update_contact_local_lists( $email, $lists_to_remove, 'remove' );
			if ( is_wp_error( $lists_to_add ) ) {
				return $lists_to_add;
			}
			if ( is_wp_error( $lists_to_remove ) ) {
				return $lists_to_remove;
			}
		}
		return $this->update_contact_lists( $email, $lists_to_add, $lists_to_remove );

	}

	/**
	 * Bulk update a contact local lists, by adding or removing tags
	 *
	 * @param string $email The contact email.
	 * @param array  $lists An array with List IDs, mixing local and providers lists. Only local lists will be handled.
	 * @param string $action The action to be performed. add or remove.
	 * @return array|WP_Error The remaining lists that were not handled by this method, because they are not local lists.
	 */
	public function update_contact_local_lists( $email, $lists = [], $action = 'add' ) {
		foreach ( $lists as $key => $list_id ) {
			if ( Subscription_List::is_form_id( $list_id ) ) {
				try {
					$list = new Subscription_List( $list_id );
					
					if ( ! $list->is_configured_for_provider( $this->service ) ) {
						return new WP_Error( 'List not properly configured for the provider' );
					}
					$list_settings = $list->get_provider_settings( $this->service );

					if ( 'add' === $action ) {
						$this->add_tag_to_contact( $email, (int) $list_settings['tag_id'], $list_settings['list'] );
					} elseif ( 'remove' === $action ) {
						$this->remove_tag_from_contact( $email, (int) $list_settings['tag_id'], $list_settings['list'] );
					}
					
					unset( $lists[ $key ] );

				} catch ( \InvalidArgumentException $e ) {
					return new WP_Error( 'List not found' );
				}
			}
		}
		return $lists;
	}

	/**
	 * Get the contact local lists IDs
	 *
	 * @param string $email The contact email.
	 * @return string[] Array of local lists IDs or error.
	 */
	public function get_contact_local_lists( $email ) {
		$tags = $this->get_contact_tags_ids( $email );
		if ( is_wp_error( $tags ) ) {
			return [];
		}
		$lists = Subscription_Lists::get_configured_for_provider( $this->service );
		$ids   = [];
		foreach ( $lists as $list ) {
			$list_settings = $list->get_provider_settings( $this->service );
			if ( in_array( $list_settings['tag_id'], $tags, false ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.FoundNonStrictFalse
				$ids[] = $list->get_form_id();
			}
		}
		return $ids;
	}

	/**
	 * Get contact lists combining local lists and provider lists
	 *
	 * @param string $email The contact email.
	 * @return WP_Error|array
	 */
	public function get_contact_combined_lists( $email ) {
		$lists = $this->get_contact_lists( $email );
		if ( is_wp_error( $lists ) ) {
			return $lists;
		}
		$local_lists = [];
		if ( static::$support_tags ) {
			$local_lists = $this->get_contact_local_lists( $email );
			if ( is_wp_error( $local_lists ) ) {
				return $local_lists;
			}
		}
		return array_merge( $lists, $local_lists );
	}

	/**
	 * Retrieve the ESP's tag ID from its name
	 *
	 * @param string  $tag_name The tag.
	 * @param boolean $create_if_not_found Whether to create a new tag if not found. Default to true.
	 * @param string  $list_id The List ID.
	 * @return int|WP_Error The tag ID on success. WP_Error on failure.
	 */
	public function get_tag_id( $tag_name, $create_if_not_found = true, $list_id = null ) {
		return new WP_Error( 'newspack_newsletters_not_implemented', __( 'Not implemented', 'newspack-newsletters' ), [ 'status' => 400 ] );
	}

	/**
	 * Retrieve the ESP's tag name from its ID
	 *
	 * @param int    $tag_id The tag ID.
	 * @param string $list_id The List ID.
	 * @return string|WP_Error The tag name on success. WP_Error on failure.
	 */
	public function get_tag_by_id( $tag_id, $list_id = null ) {
		return new WP_Error( 'newspack_newsletters_not_implemented', __( 'Not implemented', 'newspack-newsletters' ), [ 'status' => 400 ] );
	}

	/**
	 * Create a Tag on the provider
	 *
	 * @param string $tag The Tag name.
	 * @param string $list_id The List ID.
	 * @return array|WP_Error The tag representation with at least 'id' and 'name' keys on succes. WP_Error on failure.
	 */
	public function create_tag( $tag, $list_id = null ) {
		return new WP_Error( 'newspack_newsletters_not_implemented', __( 'Not implemented', 'newspack-newsletters' ), [ 'status' => 400 ] );
	}

	/**
	 * Add a tag to a contact
	 *
	 * @param string     $email The contact email.
	 * @param string|int $tag The tag ID retrieved with get_tag_id() or the the tag string.
	 * @param string     $list_id The List ID.
	 * @return true|WP_Error
	 */
	public function add_tag_to_contact( $email, $tag, $list_id = null ) {
		return new WP_Error( 'newspack_newsletters_not_implemented', __( 'Not implemented', 'newspack-newsletters' ), [ 'status' => 400 ] );
	}

	/**
	 * Remove a tag from a contact
	 *
	 * @param string     $email The contact email.
	 * @param string|int $tag The tag ID retrieved with get_tag_id() or the the tag string.
	 * @param string     $list_id The List ID.
	 * @return true|WP_Error
	 */
	public function remove_tag_from_contact( $email, $tag, $list_id = null ) {
		return new WP_Error( 'newspack_newsletters_not_implemented', __( 'Not implemented', 'newspack-newsletters' ), [ 'status' => 400 ] );
	}

	/**
	 * Get the IDs of the tags associated with a contact.
	 *
	 * @param string $email The contact email.
	 * @return array|WP_Error The tag IDs on success. WP_Error on failure.
	 */
	public function get_contact_tags_ids( $email ) {
		return new WP_Error( 'newspack_newsletters_not_implemented', __( 'Not implemented', 'newspack-newsletters' ), [ 'status' => 400 ] );
	}
}
