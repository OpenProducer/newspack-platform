<?php
/**
 * Service Provider: Mailchimp Groups Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

use \DrewM\MailChimp\MailChimp;

/**
 * This trait adds the Mailchimp Groups implementation to the Mailchimp Service Provider.
 *
 * In Mailchimp, Groups are also called Interests. Interests are categorized in Interest Categories.
 *
 * In this implementation, we will always add groups/interests to a category called "Newspack Newsletters". If this category
 * doesn't exist, it will be created.
 *
 * From that point on, groups will always be added to this category.
 */
trait Newspack_Newsletters_Mailchimp_Groups {

	/**
	 * Gets the Newspack Newsletter group category ID, and creates it if it doesn't exist.
	 *
	 * @param string $list_id The list ID.
	 * @return string|WP_Error The group category ID on success . WP_Error on failure .
	 */
	public function get_groups_category_id( $list_id ) {
		$option_name        = 'newspack_newsletters_mailchimp_group_category_id';
		$group_category_ids = get_option( $option_name );
		if ( ! array( $group_category_ids ) ) {
			$group_category_ids = [];
		}
		$group_category_id = $group_category_ids[ $list_id ] ?? null;
		if ( $group_category_id ) {
			return $group_category_id;
		}
		$mc     = new Mailchimp( $this->api_key() );
		$create = $mc->post(
			sprintf( 'lists/%s/interest-categories', $list_id ),
			[
				'title' => 'Newspack Newsletters',
				'type'  => 'checkboxes',
			]
		);
		
		// If there was an error creating the category, let's check if it already exists.
		if ( ! empty( $create['status'] ) && 400 === $create['status'] ) {
			$search = $mc->get(
				sprintf( 'lists/%s/interest-categories', $list_id ),
				[
					'count' => 100,
				]
			);
			if ( ! empty( $search['total_items'] ) ) {
				foreach ( $search['categories'] as $found_category ) {
					if ( 'Newspack Newsletters' === $found_category['title'] ) {
						$group_category_ids[ $list_id ] = $found_category['id'];
						update_option( $option_name, $group_category_ids );
						return $found_category['id'];
					}
				}
			}
		}
			
		if ( ! empty( $create['id'] ) ) {
			$group_category_ids[ $list_id ] = $create['id'];
			update_option( $option_name, $group_category_ids );
			return $create['id'];
		} else {
			return new WP_Error(
				'newspack_newsletter_unable_to_create_group_category'
			);
		}
	}

	/**
	 * Retrieve the Mailchimp's group ID from its name
	 *
	 * @param string  $group_name The group .
	 * @param boolean $create_if_not_found Whether to create a new group() if not found . default to true .
	 * @param string  $list_id The list ID .
	 * @return string | WP_Error The group ID on success . WP_Error on failure .
	 */
	public function get_group_id( $group_name, $create_if_not_found = true, $list_id = null ) {
		$mc     = new Mailchimp( $this->api_key() );
		$search = $mc->get(
			sprintf( '/lists/%s/interest-categories/%s/interests', $list_id, $this->get_groups_category_id( $list_id ) ),
			[
				'count' => 100,
			]
		);
		if ( ! empty( $search['total_items'] ) ) {
			foreach ( $search['interests'] as $found_group ) {
				if ( strtolower( $group_name ) === strtolower( $found_group['name'] ) ) {
					return $found_group['id'];
				}
			}
		}

		// Group was not found.
		if ( ! $create_if_not_found ) {
			return new WP_Error(
				'newspack_newsletter_group_not_found'
			);
		}

		$created = $this->create_group( $group_name, $list_id );

		if ( is_wp_error( $created ) ) {
			return $created;
		}

		return $created['id'];
	}

	/**
	 * Retrieve the ESP's group name from its ID
	 *
	 * @param int    $group_id The group ID.
	 * @param string $list_id The List ID.
	 * @return string|WP_Error The group name on success. WP_Error on failure.
	 */
	public function get_group_by_id( $group_id, $list_id = null ) {
		$mc     = new Mailchimp( $this->api_key() );
		$search = $mc->get(
			sprintf( '/lists/%s/interest-categories/%s/interests/%s', $list_id, $this->get_groups_category_id( $list_id ), $group_id )
		);
		if ( ! empty( $search['name'] ) ) {
			return $search['name'];
		}
		return new WP_Error(
			'newspack_newsletter_group_not_found'
		);
	}

	/**
	 * Create a Group on Mailchimp
	 *
	 * @param string $group The Group name.
	 * @param string $list_id The List ID.
	 * @return array|WP_Error The group representation sent from the server on succes. WP_Error on failure.
	 */
	public function create_group( $group, $list_id = null ) {
		
		$mc      = new Mailchimp( $this->api_key() );
		$created = $mc->post(
			sprintf( '/lists/%s/interest-categories/%s/interests', $list_id, $this->get_groups_category_id( $list_id ) ),
			[
				'name' => $group,
			]
		);

		if ( is_array( $created ) && ! empty( $created['id'] ) && ! empty( $created['name'] ) ) {
			return $created;
		}
		return new WP_Error(
			'newspack_newsletters_error_creating_group',
			! empty( $created['detail'] ) ? $created['detail'] : ''
		);
	}

	/**
	 * Add a group to a contact
	 *
	 * @param string $email The contact email.
	 * @param string $group_id The group ID.
	 * @param string $list_id The List ID.
	 * @return true|WP_Error
	 */
	public function add_group_to_contact( $email, $group_id, $list_id = null ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			return $existing_contact;
		}

		$mc    = new Mailchimp( $this->api_key() );
		$added = $mc->put(
			sprintf( 'lists/%s/members/%s', $list_id, $existing_contact['id'] ),
			[
				'interests' => [ $group_id => true ],
			]
		);

		if ( is_array( $added ) && ! empty( $added['status'] ) ) {
			return true;
		}

		return new WP_Error(
			'newspack_newsletter_error_adding_group_to_contact',
			! empty( $added['errors'] ) && ! empty( $added['errors'][0]['error'] ) ? $added['errors'][0]['error'] : ''
		);

	}

	/**
	 * Remove a group from a contact
	 *
	 * @param string $email The contact email.
	 * @param string $group_id The group ID.
	 * @param string $list_id The List ID.
	 * @return true|WP_Error
	 */
	public function remove_group_from_contact( $email, $group_id, $list_id = null ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			return $existing_contact;
		}

		$mc    = new Mailchimp( $this->api_key() );
		$added = $mc->put(
			sprintf( 'lists/%s/members/%s', $list_id, $existing_contact['id'] ),
			[
				'interests' => [ $group_id => false ],
			]
		);

		if ( is_array( $added ) && ! empty( $added['status'] ) ) {
			return true;
		}

		return new WP_Error(
			'newspack_newsletter_error_adding_group_to_contact',
			! empty( $added['errors'] ) && ! empty( $added['errors'][0]['error'] ) ? $added['errors'][0]['error'] : ''
		);

	}

	/**
	 * Get the IDs of the groups associated with a contact.
	 *
	 * @param string $email The contact email.
	 * @return array|WP_Error The groups IDs on success. WP_Error on failure.
	 */
	public function get_contact_groups_ids( $email ) {
		$contact_data = $this->get_contact_data( $email );
		if ( is_wp_error( $contact_data ) ) {
			return $contact_data;
		}

		$groups = [];

		foreach ( $contact_data['interests'] as $group_id => $is_subscribed ) {
			if ( $is_subscribed ) {
				$groups[] = $group_id;
			}
		}
		
		return $groups;
	}       
}
