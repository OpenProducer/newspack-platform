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
	 * Get API key for service provider.
	 *
	 * @return String Stored API key for the service provider.
	 */
	public function api_key();

	/**
	 * Set the API key for the service provider.
	 *
	 * @param string $key API key.
	 */
	public function set_api_key( $key );

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
}
