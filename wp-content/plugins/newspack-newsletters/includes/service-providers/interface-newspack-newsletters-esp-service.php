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
	public function add_contact( $contact, $list_id );

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
}
