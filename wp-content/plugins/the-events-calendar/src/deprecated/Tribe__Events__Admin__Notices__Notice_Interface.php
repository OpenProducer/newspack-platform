<?php
_deprecated_file( __FILE__, '4.3', 'Tribe__Admin__Notices' );

interface Tribe__Events__Admin__Notices__Notice_Interface {

	/**
	 * Echoes the notice.
	 *
	 * @param string $message
	 * @param string $class
	 *
	 * @return void
	 */
	public function render( $message, $class = 'updated' );

	/**
	 * Return the notice content.
	 *
	 * @param string $message
	 * @param string $class
	 *
	 * @return string
	 */
	public function get( $message, $class );
}