<?php
/**
 * Mandatory API definition for an ESP Service.
 *
 * @package Newspack
 */

/**
 * ESP API.
 */
interface Newspack_Newsletters_ESP_API_Interface {

	/**
	 * Get API credentials for service provider.
	 *
	 * @return Object Stored API credentials for the service provider.
	 */
	public function api_credentials();

	/**
	 * Set the API credentials for the service provider.
	 *
	 * @param object $credentials API credentials.
	 */
	public function set_api_credentials( $credentials );

	/**
	 * Check if provider has all necessary credentials set.
	 *
	 * @return Boolean Result.
	 */
	public function has_api_credentials();

	/**
	 * Set list for a campaign.
	 *
	 * @param string $post_id Campaign Id.
	 * @param string $list_id ID of the list.
	 *
	 * @return array|WP_Error API Response or error.
	 */
	public function list( $post_id, $list_id );

	/**
	 * Retrieve a campaign.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 *
	 * @return array|WP_Error API Response or error.
	 */
	public function retrieve( $post_id );

	/**
	 * Set sender data.
	 *
	 * @param string $post_id   Numeric ID of the campaign.
	 * @param string $from_name Sender name.
	 * @param string $reply_to  Reply to email address.
	 *
	 * @return array|WP_Error API Response or error.
	 */
	public function sender( $post_id, $from_name, $reply_to );

	/**
	 * Send test email or emails.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 * @param array   $emails  Array of email addresses to send to.
	 *
	 * @return array|WP_Error API Response or error.
	 */
	public function test( $post_id, $emails );

	/**
	 * Synchronize post with corresponding ESP campaign.
	 *
	 * @param WP_Post $post Post to synchronize.
	 *
	 * @return array|WP_Error API Response or error.
	 */
	public function sync( $post );

	/**
	 * List the ESP's contact lists.
	 *
	 * @return array|WP_Error API Response or error.
	 */
	public function get_lists();

	/**
	 * Add contact to a list.
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
	public function add_contact( $contact, $list_id = false );

	/**
	 * Update a contact lists subscription.
	 *
	 * @param string   $email           Contact email address.
	 * @param string[] $lists_to_add    Array of list IDs to subscribe the contact to.
	 * @param string[] $lists_to_remove Array of list IDs to remove the contact from.
	 *
	 * @return true|WP_Error True if the contact was updated or error.
	 */
	public function update_contact_lists( $email, $lists_to_add = [], $lists_to_remove = [] );

	/**
	 * Retrieve the ESP's tag ID from its name
	 *
	 * @param string  $tag_name The tag.
	 * @param boolean $create_if_not_found Whether to create a new tag if not found. Default to true.
	 * @param string  $list_id The List ID.
	 * @return int|WP_Error The tag ID on success. WP_Error on failure.
	 */
	public function get_tag_id( $tag_name, $create_if_not_found = true, $list_id = null );

	/**
	 * Retrieve the ESP's tag name from its ID
	 *
	 * @param int    $tag_id The tag ID.
	 * @param string $list_id The List ID.
	 * @return string|WP_Error The tag name on success. WP_Error on failure.
	 */
	public function get_tag_by_id( $tag_id, $list_id = null );

	/**
	 * Create a Tag on the provider
	 *
	 * @param string $tag The Tag name.
	 * @param string $list_id The List ID.
	 * @return array|WP_Error The tag representation with at least 'id' and 'name' keys on succes. WP_Error on failure.
	 */
	public function create_tag( $tag, $list_id = null );

	/**
	 * Updates a Tag name on the provider
	 *
	 * @param string|int $tag_id The tag ID.
	 * @param string     $tag The Tag new name.
	 * @param string     $list_id The List ID.
	 * @return array|WP_Error The tag representation with at least 'id' and 'name' keys on succes. WP_Error on failure.
	 */
	public function update_tag( $tag_id, $tag, $list_id = null );

	/**
	 * Add a tag to a contact
	 *
	 * @param string     $email The contact email.
	 * @param string|int $tag The tag ID.
	 * @param string     $list_id The List ID.
	 * @return true|WP_Error
	 */
	public function add_tag_to_contact( $email, $tag, $list_id = null );

	/**
	 * Remove a tag from a contact
	 *
	 * @param string     $email The contact email.
	 * @param string|int $tag The tag ID.
	 * @param string     $list_id The List ID.
	 * @return true|WP_Error
	 */
	public function remove_tag_from_contact( $email, $tag, $list_id = null );

	/**
	 * Get usage report for yesterday.
	 *
	 * @return Newspack_Newsletters_Service_Provider_Usage_Report|WP_Error
	 */
	public function get_usage_report();

	/**
	 * Get transient name for async error messages.
	 *
	 * @param int $post_id The post ID.
	 *
	 * @return string The transient name.
	 */
	public function get_transient_name( $post_id );
}
