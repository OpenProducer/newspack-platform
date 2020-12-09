<?php
/**
 * Newspack webhooks management.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Define, deliver, and handle webhooks.
 */
class Webhooks {
	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->salesforce_settings = Salesforce::get_salesforce_settings();

		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
	}

	/**
	 * Register the endpoints needed to handle webhooks.
	 */
	public function register_api_endpoints() {

		// Handle WooCommerce webhook to sync with Salesforce.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/salesforce/sync',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_sync_salesforce' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * Webhook callback handler for syncing data to Salesforce.
	 *
	 * @param WP_REST_Request $request Request containing webhook.
	 * @return WP_REST_Response|WP_Error The response from Salesforce, or WP_Error.
	 */
	public function api_sync_salesforce( $request ) {
		$args          = $request->get_params();
		$order_details = Salesforce::parse_wc_order_data( $args );

		if ( empty( $order_details ) ) {
			return new \WP_Error(
				'newspack_salesforce_invalid_order',
				__( 'No valid WooCommerce order data.', 'newspack' )
			);
		}

		$contact       = $order_details['contact'];
		$orders        = $order_details['orders'];
		$opportunities = [];

		if ( empty( $contact ) || empty( $orders ) ) {
			return new \WP_Error(
				'newspack_salesforce_invalid_contact_or_transaction',
				__( 'No valid transaction or contact data.', 'newspack' )
			);
		}

		if ( empty( $contact['Email'] ) ) {
			return new \WP_Error(
				'newspack_salesforce_invalid_contact_email',
				__( 'No valid contact email address.', 'newspack' )
			);
		}

		$contacts = Salesforce::get_contacts_by_email( $contact['Email'] );

		if ( empty( $contacts ) ) {
			return new \WP_Error(
				'newspack_salesforce_invalid_salesforce_lookup',
				__( 'Could not communicate with Salesforce.', 'newspack' )
			);
		}

		if ( $contacts->totalSize > 0 ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$contact_id       = $contacts->records[0]->Id;
			$contact_response = Salesforce::update_contact( $contact_id, $contact );
		} else {
			// Create new contact.
			$contact_response = Salesforce::create_contact( $contact );

			if ( is_wp_error( $contact_response ) ) {
				return new \WP_Error(
					'newspack_salesforce_contact_failure',
					$contact_response->get_error_message()
				);
			}

			$contact_id = $contact_response->id;
		}

		if ( empty( $contact_id ) ) {
			return new \WP_Error(
				'newspack_salesforce_sync_failure',
				__( 'Could not create or update Salesforce data.', 'newspack' )
			);
		}

		// Sync WooCommerce orders to Salesforce opportunities.
		if ( is_array( $orders ) ) {
			$is_npsp = Salesforce::has_field( 'npsp__Primary_Contact__c', 'Opportunity' );

			foreach ( $orders as $order ) {
				// Add the NPSP "Primary Contact" field if the Salesforce instance supports it.
				if ( $is_npsp ) {
					$order['npsp__Primary_Contact__c'] = $contact_id;
				}

				$opportunity_response = Salesforce::create_opportunity( $order );

				if ( is_wp_error( $opportunity_response ) ) {
					return new \WP_Error(
						'newspack_salesforce_opportunity_failure',
						$opportunity_response->get_error_message()
					);
				}

				$opportunity_id = $opportunity_response->id;

				if ( ! empty( $opportunity_id ) ) {
					$opportunities[] = $opportunity_response;
					Salesforce::create_opportunity_contact_role( $opportunity_id, $contact_id );
				}
			}
		}

		return \rest_ensure_response(
			[
				'contact'       => $contact_response,
				'opportunities' => $opportunities,
			]
		);
	}
}

new Webhooks();
