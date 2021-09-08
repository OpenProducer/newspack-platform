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
	 * @return object|WP_Error API Response or error.
	 */
	public function list( $post_id, $list_id );

	/**
	 * Retrieve a campaign.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 * @return object|WP_Error API Response or error.
	 */
	public function retrieve( $post_id );

	/**
	 * Set sender data.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @param string $from_name Sender name.
	 * @param string $reply_to Reply to email address.
	 * @return object|WP_Error API Response or error.
	 */
	public function sender( $post_id, $from_name, $reply_to );

	/**
	 * Send test email or emails.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 * @param array   $emails Array of email addresses to send to.
	 * @return object|WP_Error API Response or error.
	 */
	public function test( $post_id, $emails );

	/**
	 * Synchronize post with corresponding ESP campaign.
	 *
	 * @param WP_POST $post Post to synchronize.
	 * @return object|null API Response or error.
	 */
	public function sync( $post );

	/**
	 * List the ESP's contact lists.
	 *
	 * @return object|null API Response or error.
	 */
	public function get_lists();

	/**
	 * Add contact to a list.
	 *
	 * @param array  $contact Contact data.
	 * @param strine $list_id List ID.
	 * @return object|null API Response or error.
	 */
	public function add_contact( $contact, $list_id);
}
