<?php // phpcs:disable

use Yoast\WP\SEO\User_Meta\Domain\Additional_Contactmethod_Interface;

/**
 * The Facebook contactmethod.
 */
class Newspack_Theme_Bluesky implements Additional_Contactmethod_Interface {

	/**
	 * Returns the key of the Bluesky contactmethod.
	 *
	 * @return string The key of the Bluesky contactmethod.
	 */
	public function get_key(): string {
		return 'bluesky';
	}

	/**
	 * Returns the label of the Bluesky field.
	 *
	 * @return string The label of the Bluesky field.
	 */
	public function get_label(): string {
		return \__( 'Bluesky profile URL', 'newspack-theme' );
	}
}
