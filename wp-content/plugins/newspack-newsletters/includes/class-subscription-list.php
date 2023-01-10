<?php
/**
 * Newspack Newsletters Subscription List
 *
 * @package Newspack
 */

namespace Newspack\Newsletters;

use Newspack_Newsletters;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Class used to represent one Subscription List
 */
class Subscription_List {

	/**
	 * Hold the WP_Post object associated with this List.
	 *
	 * @var WP_Post
	 */
	protected $post;

	/**
	 * The meta key where the settings are stored.
	 */
	const META_KEY = 'newspack_nl_provider_settings';

	/**
	 * The prefix used to build the form iD
	 */
	const FORM_ID_PREFIX = 'newspack-';

	/**
	 * Checks if a string $id is in the format of a Subscription List Form ID
	 *
	 * @see self::get_form_id
	 * @param string $id The ID to be checked.
	 * @return boolean
	 */
	public static function is_form_id( $id ) {
		return (bool) self::get_id_from_form_id( $id );
	}

	/**
	 * Extracts the numeric ID from a properly formatted Form ID
	 *
	 * @see self::get_form_id
	 * @param string $form_id The Form id.
	 * @return ?int The ID on success, NULL on failure
	 */
	public static function get_id_from_form_id( $form_id ) {
		if ( ! is_string( $form_id ) ) {
			return;
		}
		$search = preg_match(
			'/^' . self::FORM_ID_PREFIX . '([0-9]+)$/',
			$form_id,
			$matches
		);
		if ( $search && ! empty( $matches[1] ) ) {
			return (int) $matches[1];
		}
	}

	/**
	 * Initializes a new Subscription List
	 *
	 * @param WP_Post|int|string $post_or_id The post object, post ID or Subscription List form ID.
	 * @throws \InvalidArgumentException In case the post is not found.
	 */
	public function __construct( $post_or_id ) {
		if ( ! $post_or_id instanceof WP_Post ) {
			if ( self::is_form_id( $post_or_id ) ) {
				$post_or_id = self::get_id_from_form_id( $post_or_id );
			}
			$post_or_id = get_post( (int) $post_or_id );
			if ( ! $post_or_id instanceof WP_Post ) {
				throw new \InvalidArgumentException( 'Post not found' );
			}
		}
		$this->post = $post_or_id;
	}

	/**
	 * Gets the List ID
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->post->ID;
	}

	/**
	 * Returns the Form ID for this List
	 *
	 * Form ID is the ID that will represent this list in all UI forms across the application
	 *
	 * @return string
	 */
	public function get_form_id() {
		return self::FORM_ID_PREFIX . $this->get_id();
	}

	/**
	 * Gets the List title
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->post->post_title;
	}

	/**
	 * Gets the List description
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->post->post_content;
	}

	/**
	 * Gets the link to edit this list
	 *
	 * In some rest requests, the post type is not registered, so we can't use get_edit_post_link
	 *
	 * @return string
	 */
	public function get_edit_link() {
		return sprintf( admin_url( 'post.php?post=%d&action=edit' ), $this->get_id() );
	}

	/**
	 * Generate the tag name that will be added to the ESP based on the post title
	 *
	 * @return string
	 */
	public function generate_tag_name() {
		return 'Newspack: ' . $this->get_title();
	}

	/**
	 * Returns the settings stored for a provider
	 *
	 * @param string $provider_slug The provider slug.
	 * @return ?array
	 */
	public function get_provider_settings( $provider_slug ) {
		$meta = $this->get_all_providers_settings();
		if ( ! empty( $provider_slug ) && is_array( $meta ) && ! empty( $meta[ $provider_slug ] ) ) {
			return $meta[ $provider_slug ];
		}
		return null;
	}

	/**
	 * Returns the settings stored for the current service provicer
	 *
	 * @return ?array
	 */
	public function get_current_provider_settings() {
		return $this->get_provider_settings( Newspack_Newsletters::service_provider() );
	}

	/**
	 * Checks whether the List is properly configured for the current provider
	 *
	 * @return boolean
	 */
	public function is_configured_for_current_provider() {
		return $this->is_configured_for_provider( Newspack_Newsletters::service_provider() );
	}

	/**
	 * Checks whether the List is properly configured for a provider
	 *
	 * @param string $provider_slug The provider slug.
	 * @return boolean
	 */
	public function is_configured_for_provider( $provider_slug ) {
		$settings = $this->get_provider_settings( $provider_slug );
		if ( ! is_array( $settings ) ) {
			return false;
		}
		return empty( $settings['error'] ) && ! empty( $settings['tag_id'] ) && ! empty( $settings['list'] );
	}

	/**
	 * Gets all the settings stored for all providers
	 *
	 * @return array
	 */
	public function get_all_providers_settings() {
		$settings = get_post_meta( $this->get_id(), self::META_KEY, true );
		return is_array( $settings ) ? $settings : [];
	}

	/**
	 * Gets a list of all providers slugs this List has configuration for
	 *
	 * @return array
	 */
	public function get_configured_providers() {
		$providers  = array_keys( $this->get_all_providers_settings() );
		$configured = [];
		foreach ( $providers as $provider ) {
			if ( $this->is_configured_for_provider( $provider ) ) {
				$configured[] = $provider;
			}
		}
		return $configured;
	}

	/**
	 * Gets a list of all providers slugs this List has configuration for, excluding the current active provider
	 *
	 * @return array
	 */
	public function get_other_configured_providers() {
		$providers = $this->get_configured_providers();
		return array_values( array_diff( $providers, [ Newspack_Newsletters::service_provider() ] ) );
	}

	/**
	 * Gets a list of all providers names this List has configuration for
	 *
	 * @param boolean $ignore_current Whether to ignore the current provider or not.
	 * @return array
	 */
	public function get_configured_providers_names( $ignore_current = false ) {
		$providers = $ignore_current ? $this->get_other_configured_providers() : $this->get_configured_providers();
		$names     = [];
		foreach ( $providers as $provider_slug ) {
			$provider = Newspack_Newsletters::get_service_provider_instance( $provider_slug );
			if ( is_object( $provider ) ) {
				$names[] = $provider::label( 'name' );
			}
		}
		return $names;
	}

	/**
	 * Gets a list of all providers names this List has configuration for, excluding the current active provider
	 *
	 * @return array
	 */
	public function get_other_configured_providers_names() {
		return $this->get_configured_providers_names( true );
	}

	/**
	 * Checks if this List has any other providers configured other than the current active provider
	 *
	 * @return boolean
	 */
	public function has_other_configured_providers() {
		return ! empty( $this->get_other_configured_providers() );

	}

	/**
	 * Updates the settings of a provider
	 *
	 * @param string $list The list ID readers will be added to when they signup for this List.
	 * @param int    $tag_id The ID of the tag that will be added readers who signup to this List.
	 * @param string $tag_name The name of the tag that will be added readers who signup to this List.
	 * @param string $error The error message in case there was an error creating the tag on the ESP.
	 * @return int|bool Meta ID if the key didn't exist, true on successful update, false on failure or if the value passed to the function is the same as the one that is already in the database.
	 */
	public function update_current_provider_settings( $list, $tag_id, $tag_name, $error = '' ) {
		$settings = $this->get_all_providers_settings();
		if ( ! empty( $error ) ) {
			$settings[ Newspack_Newsletters::service_provider() ] = [
				'error' => $error,
			];
		} elseif ( empty( $list ) || empty( $tag_id ) || empty( $tag_name ) ) {
			$settings[ Newspack_Newsletters::service_provider() ]['error'] = __( 'Error: Missing information, try updating this list', 'newspack-newsletters' );
		} else {
			$settings[ Newspack_Newsletters::service_provider() ] = [
				'list'     => $list,
				'tag_id'   => $tag_id,
				'tag_name' => $tag_name,
			];
		}
		
		return update_post_meta( $this->get_id(), self::META_KEY, $settings );
	}

}
