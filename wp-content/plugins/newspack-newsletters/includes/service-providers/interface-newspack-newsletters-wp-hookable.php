<?php
/**
 * An interface defining the mandatory abilities for the ESP Services to hook into WP actions.
 *
 * @package Newspack
 */

/**
 * Integration with WP Hooks.
 */
interface Newspack_Newsletters_WP_Hookable_Interface {

	/**
	 * Update ESP campaign after post save.
	 *
	 * @param string   $post_id Numeric ID of the campaign.
	 * @param \WP_Post $post The complete post object.
	 * @param boolean  $update Whether this is an existing post being updated or not.
	 */
	public function save( $post_id, $post, $update );

	/**
	 * Send a campaign.
	 *
	 * @param \WP_Post $post Post to send.
	 */
	public function send( $post );

	/**
	 * After Newsletter post is deleted, clean up by deleting corresponding ESP campaign.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 */
	public function trash( $post_id );
}
