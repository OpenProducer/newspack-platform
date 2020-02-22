<?php
/**
 * Class Google\Site_Kit\Core\Storage\Encrypted_User_Options
 *
 * @package   Google\Site_Kit
 * @copyright 2019 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Core\Storage;

/**
 * Class providing access to encrypted per-user options.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Encrypted_User_Options {

	/**
	 * Data Encryption API instance.
	 *
	 * @since 1.0.0
	 * @var Data_Encryption
	 */
	private $encryption;

	/**
	 * User Option API instance.
	 *
	 * @since 1.0.0
	 * @var User_Options
	 */
	private $user_options;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param User_Options $user_options User Option API instance.
	 */
	public function __construct( User_Options $user_options ) {
		$this->encryption   = new Data_Encryption();
		$this->user_options = $user_options;
	}

	/**
	 * Gets the value of the given user option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option User option name.
	 * @return mixed Value set for the user option, or false if not set.
	 */
	public function get( $option ) {
		$raw_value = $this->user_options->get( $option );
		if ( ! $raw_value ) {
			return false;
		}

		$data = $this->encryption->decrypt( $raw_value );

		return maybe_unserialize( $data );
	}

	/**
	 * Sets the value for a user option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option User option name.
	 * @param mixed  $value  User option value. Must be serializable if non-scalar.
	 * @return bool True on success, false on failure.
	 */
	public function set( $option, $value ) {
		if ( ! is_scalar( $value ) ) {
			$value = maybe_serialize( $value );
		}
		$raw_value = $this->encryption->encrypt( $value );
		if ( ! $raw_value ) {
			return false;
		}

		return $this->user_options->set( $option, $raw_value );
	}

	/**
	 * Deletes the given user option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option User option name.
	 * @return bool True on success, false on failure.
	 */
	public function delete( $option ) {
		return $this->user_options->delete( $option );
	}
}
