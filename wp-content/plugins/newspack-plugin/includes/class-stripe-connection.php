<?php
/**
 * All things Stripe.
 *
 * @package Newspack
 */

namespace Newspack;

use Stripe\Stripe;

defined( 'ABSPATH' ) || exit;

/**
 * All things Stripe.
 */
class Stripe_Connection {
	const STRIPE_DATA_OPTION_NAME        = 'newspack_stripe_data';
	const STRIPE_WEBHOOK_OPTION_NAME     = 'newspack_stripe_webhook';
	const STRIPE_DONATION_PRICE_METADATA = 'newspack_donation_price';

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		add_action( 'rest_api_init', [ __CLASS__, 'register_api_endpoints' ] );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public static function register_api_endpoints() {
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/stripe/create-webhooks/',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'create_webhooks' ],
				'permission_callback' => [ __CLASS__, 'api_permissions_check' ],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/stripe/webhook',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'receive_webhook' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * Check capabilities for using API.
	 *
	 * @codeCoverageIgnore
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public static function api_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}

	/**
	 * Get Stripe data blueprint.
	 */
	public static function get_default_stripe_data() {
		return [
			'enabled'            => false,
			'testMode'           => false,
			'publishableKey'     => '',
			'secretKey'          => '',
			'testPublishableKey' => '',
			'testSecretKey'      => '',
			'currency'           => 'USD',
			'newsletter_list_id' => '',
		];
	}

	/**
	 * Get Stripe data, either from WC, or saved in options table.
	 */
	public static function get_stripe_data() {
		$stripe_data = self::get_saved_stripe_data();
		return $stripe_data;
	}

	/**
	 * Update Stripe data. If WC is the donations platform, update is there too.
	 *
	 * @param object $updated_stripe_data Updated Stripe data to be saved.
	 */
	public static function update_stripe_data( $updated_stripe_data ) {
		if ( Donations::is_platform_wc() ) {
			// If WC is configured, set Stripe data in WC.
			$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
			$wc_configuration_manager->update_wc_stripe_settings( $updated_stripe_data );
		}
		// Save it in options table.
		return update_option( self::STRIPE_DATA_OPTION_NAME, $updated_stripe_data );
	}

	/**
	 * List Stripe webhooks.
	 */
	public static function list_webhooks() {
		$stripe = self::get_stripe_client();
		try {
			return $stripe->webhookEndpoints->all()['data']; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_webhooks', __( 'Could not fetch webhooks.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Get Stripe customer.
	 *
	 * @param string $customer_id Customer ID.
	 */
	private static function get_customer( $customer_id ) {
		$stripe = self::get_stripe_client();
		try {
			return $stripe->customers->retrieve( $customer_id, [] ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_webhooks', __( 'Could not fetch customer.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Get Stripe invoice.
	 *
	 * @param string $invoice_id Customer ID.
	 */
	private static function get_invoice( $invoice_id ) {
		$stripe = self::get_stripe_client();
		try {
			return $stripe->invoices->retrieve( $invoice_id, [] ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_webhooks', __( 'Could not fetch invoice.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Receive Stripe webhook.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public static function receive_webhook( $request ) {
		// Verify the webhook signature (https://stripe.com/docs/webhooks/signatures).
		$webhook = get_option( self::STRIPE_WEBHOOK_OPTION_NAME, false );
		if ( false === $webhook || ! isset( $webhook['secret'], $_SERVER['HTTP_STRIPE_SIGNATURE'] ) ) {
			return new \WP_Error( 'newspack_webhook_missing_data' );
		}
		try {
			$sig_header = sanitize_text_field( $_SERVER['HTTP_STRIPE_SIGNATURE'] );
			$payload    = @file_get_contents( 'php://input' ); // phpcs:disable WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsRemoteFile, WordPress.PHP.NoSilencedErrors.Discouraged
			$event      = \Stripe\Webhook::constructEvent(
				$payload,
				$sig_header,
				$webhook['secret']
			);
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'newspack_webhook_error' );
		}

		switch ( $request['type'] ) {
			case 'charge.succeeded':
				$payment           = $request['data']['object'];
				$metadata          = $payment['metadata'];
				$customer          = self::get_customer( $payment['customer'] );
				$amount_normalised = self::normalise_amount( $payment['amount'], $payment['currency'] );

				$referer = '';
				if ( isset( $metadata['referer'] ) ) {
					$referer = $metadata['referer'];
				}

				$frequency = 'once';
				if ( $payment['invoice'] ) {
					// A subscription payment will have an invoice.
					$invoice   = self::get_invoice( $payment['invoice'] );
					$recurring = $invoice['lines']['data'][0]['price']['recurring'];
					if ( isset( $recurring['interval'] ) ) {
						$frequency = $recurring['interval'];
					}
					if ( isset( $invoice['metadata']['referer'] ) ) {
						$referer = $invoice['metadata']['referer'];
					}
				}

				// Update data in Newsletters provider.
				$was_customer_added_to_mailing_list = false;
				$stripe_data                        = self::get_stripe_data();
				if ( ! empty( $stripe_data['newsletter_list_id'] ) && isset( $customer['metadata']['newsletterOptIn'] ) && 'true' === $customer['metadata']['newsletterOptIn'] ) {
					$newsletters_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-newsletters' );
					// Note: With Mailchimp, this is adding the contact as 'pending' - the subscriber has to confirm.
					$newsletters_configuration_manager->add_contact(
						[
							'email'    => $customer['email'],
							'name'     => $customer['name'],
							'metadata' => [
								'donation_date'      => gmdate( 'Y-m-d', $payment['created'] ),
								'donation_amount'    => $amount_normalised,
								'donation_frequency' => $frequency,
								'donation_recurring' => 'once' !== $frequency,
							],
						],
						$stripe_data['newsletter_list_id']
					);
					$was_customer_added_to_mailing_list = true;
				}

				// Update data in Campaigns plugin.
				if ( isset( $customer['metadata']['clientId'] ) && class_exists( 'Newspack_Popups_Segmentation' ) ) {
					$client_id = $customer['metadata']['clientId'];
					if ( ! empty( $client_id ) ) {
						$donation_data = [
							'stripe_id'          => $payment['id'],
							'stripe_customer_id' => $customer['id'],
							'date'               => $payment['created'],
							'amount'             => $amount_normalised,
							'frequency'          => $frequency,
						];
						$client_update = [
							'donation' => $donation_data,
						];
						if ( $was_customer_added_to_mailing_list ) {
							$client_update['email_subscription'] = [
								'email' => $customer['email'],
							];
						}
						\Newspack_Popups_Segmentation::update_client_data(
							$client_id,
							$client_update
						);
					}
				}

				// Send custom event to GA.
				$analytics = \Newspack\Google_Services_Connection::get_site_kit_analytics_module();
				if ( $analytics->is_connected() ) {
					$tracking_id        = $analytics->get_settings()->get()['propertyID'];
					$analytics_ping_url = 'https://www.google-analytics.com/collect?v=1';

					// Params docs: https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters.
					$analytics_ping_params = array(
						'tid' => $tracking_id, // Tracking ID/ Web Property ID.
						'cid' => '555', // Client ID.
						't'   => 'event', // Hit type.
						'an'  => 'Newspack', // Application Name.
						'ec'  => __( 'Newspack Donation', 'newspack' ), // Event Category.
						'ea'  => __( 'Stripe', 'newspack' ), // Event Action.
						'el'  => $frequency, // Event Label.
						'ev'  => $amount_normalised, // Event Value.
						'dr'  => $referer, // Document Referrer.
					);

					wp_remote_post( $analytics_ping_url . '&' . http_build_query( $analytics_ping_params ) );
				}

				break;
			case 'charge.failed':
				break;
			default:
				return new \WP_Error( 'newspack_unsupported_webhook' );
		}
	}

	/**
	 * Create Stripe webhooks.
	 */
	public static function create_webhooks() {
		$stripe = self::get_stripe_client();
		try {
			$webhook = $stripe->webhookEndpoints->create( // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				[
					'url'            => get_rest_url( null, NEWSPACK_API_NAMESPACE . '/stripe/webhook' ),
					'enabled_events' => [
						'charge.failed',
						'charge.succeeded',
					],
				]
			);
			update_option(
				self::STRIPE_WEBHOOK_OPTION_NAME,
				[
					'id'     => $webhook->id,
					'secret' => $webhook->secret,
				]
			);
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'newspack_plugin_stripe', __( 'Webhook creation failed.', 'newspack' ), $e->getMessage() );
		}
		return self::list_webhooks();
	}

	/**
	 * Get saved Stripe data.
	 */
	private static function get_saved_stripe_data() {
		$stripe_data = self::get_default_stripe_data();
		if ( Donations::is_platform_wc() ) {
			// If WC is configured, get Stripe data from WC.
			$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
			$stripe_data              = $wc_configuration_manager->stripe_data();
		} else {
			$stripe_data = get_option( self::STRIPE_DATA_OPTION_NAME, self::get_default_stripe_data() );
		}
		$stripe_data['usedPublishableKey'] = $stripe_data['testMode'] ? $stripe_data['testPublishableKey'] : $stripe_data['publishableKey'];
		$stripe_data['usedSecretKey']      = $stripe_data['testMode'] ? $stripe_data['testSecretKey'] : $stripe_data['secretKey'];
		return $stripe_data;
	}

	/**
	 * Get Stripe secret key.
	 */
	private static function get_stripe_secret_key() {
		$stripe_data = self::get_saved_stripe_data();
		return $stripe_data['usedSecretKey'];
	}

	/**
	 * Create a recurring donation product in Stripe.
	 *
	 * @param string $name Name.
	 * @param string $interval Interval.
	 */
	private static function create_donation_product( $name, $interval ) {
		$payment_data = self::get_stripe_data();
		$currency     = $payment_data['currency'];

		$stripe = self::get_stripe_client();
		// A price has to be assigned to a product.
		$product = $stripe->products->create( [ 'name' => $name ] );
		// Tiered volume pricing allows the subscription prices to be arbitrary.
		$price = $stripe->prices->create(
			[
				'product'        => $product['id'],
				'currency'       => $currency,
				'billing_scheme' => 'tiered',
				'tiers'          => [
					[
						'up_to'               => 1,
						'unit_amount_decimal' => 1,
					],
					[
						'up_to'               => 'inf',
						'unit_amount_decimal' => 1,
					],
				],
				'tiers_mode'     => 'volume',
				'recurring'      => [
					'interval' => $interval,
				],
				'metadata'       => [
					self::STRIPE_DONATION_PRICE_METADATA => $interval,
				],
			]
		);
		return $price;
	}

	/**
	 * List Stripe donation-related products. Products will be created if not found.
	 */
	public static function get_donation_prices() {
		try {
			$stripe        = self::get_stripe_client();
			$all_prices    = $stripe->prices->all()['data'];
			$prices_mapped = [];
			foreach ( $all_prices as $price ) {
				$has_metadata_field = isset( $price['metadata'][ self::STRIPE_DONATION_PRICE_METADATA ] );
				if ( $has_metadata_field ) {
					$metadata_field_value = $price['metadata'][ self::STRIPE_DONATION_PRICE_METADATA ];
					if ( 'month' === $metadata_field_value && 'month' === $price['recurring']['interval'] ) {
						$prices_mapped['month'] = $price;
					}
					if ( 'year' === $metadata_field_value && 'year' === $price['recurring']['interval'] ) {
						$prices_mapped['year'] = $price;
					}
				}
			}
			if ( ! isset( $prices_mapped['month'] ) ) {
				$prices_mapped['month'] = self::create_donation_product( __( 'Newspack Monthly Donation', 'newspack' ), 'month' );
			}
			if ( ! isset( $prices_mapped['year'] ) ) {
				$prices_mapped['year'] = self::create_donation_product( __( 'Newspack Annual Donation', 'newspack' ), 'year' );
			}
			return $prices_mapped;
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'newspack_plugin_stripe', __( 'Products retrieval failed.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Get Stripe client.
	 */
	public static function get_stripe_client() {
		$secret_key = self::get_stripe_secret_key();
		if ( $secret_key ) {
			return new \Stripe\StripeClient( $secret_key );
		}
	}

	/**
	 * Check connection to Stripe.
	 */
	public static function get_connection_error() {
		if ( ! self::get_stripe_secret_key() ) {
			return __( 'Stripe secret key not provided.', 'newspack' );
		}
		$stripe = self::get_stripe_client();
		try {
			$products = $stripe->products->all( [ 'limit' => 1 ] );
			return false;
		} catch ( \Throwable $e ) {
			return $e->getMessage();
		}
	}

	/**
	 * Is the currency zero-decimal?
	 *
	 * @param strin $currency Currency code.
	 * @return number Amount.
	 */
	private static function is_currency_zero_decimal( $currency ) {
		$zero_decimal_currencies = [ 'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF' ];
		return in_array( strtoupper( $currency ), $zero_decimal_currencies, true );
	}

	/**
	 * Normalise the amount.
	 *
	 * @see get_amount
	 *
	 * @param number $amount Amount to process.
	 * @param strin  $currency Currency code.
	 * @return number Amount.
	 */
	private static function normalise_amount( $amount, $currency ) {
		if ( self::is_currency_zero_decimal( $currency ) ) {
			return $amount;
		}
		return (float) $amount / 100;
	}

	/**
	 * Process the amount for Stripe. Some currencies are zero-decimal, but for others,
	 * the amount should be multiplied by 100 (100 USD is 100*100 currency units, aka cents).
	 * https://stripe.com/docs/currencies#zero-decimal
	 *
	 * @param number $amount Amount to process.
	 * @param strin  $currency Currency code.
	 * @return number Amount.
	 */
	private static function get_amount( $amount, $currency ) {
		if ( self::is_currency_zero_decimal( $currency ) ) {
			return $amount;
		}
		return $amount * 100;
	}

	/**
	 * Handle a donation in Stripe.
	 * If it's a recurring donation, a subscription will be created. Otherwise,
	 * a single charge.
	 *
	 * @param object $config Data about the donation.
	 */
	public static function handle_donation( $config ) {
		$response = [
			'error'  => null,
			'status' => null,
		];
		try {
			$stripe = self::get_stripe_client();

			$amount_raw       = $config['amount'];
			$frequency        = $config['frequency'];
			$email_address    = $config['email_address'];
			$full_name        = $config['full_name'];
			$token_data       = $config['token_data'];
			$client_metadata  = $config['client_metadata'];
			$payment_metadata = $config['payment_metadata'];

			// Find or create the customer by email.
			$found_customers = $stripe->customers->all( [ 'email' => $email_address ] )['data'];
			$customer        = count( $found_customers ) ? $found_customers[0] : null;
			if ( null === $customer ) {
				$customer = $stripe->customers->create(
					[
						'email'       => $email_address,
						'name'        => $full_name,
						'description' => __( 'Newspack Donor', 'newspack-blocks' ),
						'source'      => $token_data['id'],
						'metadata'    => $client_metadata,
					]
				);
			}
			if ( $customer['default_source'] !== $token_data['card']['id'] ) {
				$stripe->customers->update(
					$customer['id'],
					[
						'source'   => $token_data['id'],
						'metadata' => $client_metadata,
						'name'     => $full_name,
					]
				);
			}

			if ( 'once' === $frequency ) {
				// Create a Payment Intent on Stripe.
				$payment_data              = self::get_stripe_data();
				$intent                    = $stripe->paymentIntents->create( // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					[
						'amount'               => self::get_amount( $amount_raw, $payment_data['currency'] ),
						'currency'             => $payment_data['currency'],
						'receipt_email'        => $email_address,
						'customer'             => $customer['id'],
						'metadata'             => $payment_metadata,
						'description'          => __( 'Newspack One-Time Donation', 'newspack-blocks' ),
						'payment_method_types' => [ 'card' ],
					]
				);
				$response['client_secret'] = $intent['client_secret'];
			} else {
				// Create a Subscription on Stripe.
				$prices = self::get_donation_prices();
				$price  = $prices[ $frequency ];
				$amount = self::get_amount( $amount_raw, $price['currency'] );

				$subscription = $stripe->subscriptions->create( // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					[
						'customer'         => $customer['id'],
						'items'            => [
							[
								'price'    => $price['id'],
								'quantity' => $amount,
							],
						],
						'payment_behavior' => 'allow_incomplete',
						'metadata'         => $payment_metadata,
						'expand'           => [ 'latest_invoice.payment_intent' ],
					]
				);

				// Update invoice metadata.
				$stripe->invoices->update(
					$subscription->latest_invoice['id'],
					[
						'metadata' => $payment_metadata,
					]
				);

				if ( 'incomplete' === $subscription->status ) {
					// The card may require additional authentication.
					$response['client_secret'] = $subscription->latest_invoice->payment_intent->client_secret;
				} elseif ( 'active' === $subscription->status ) {
					$response['status'] = 'success';
				}
			}
		} catch ( \Throwable $e ) {
			$response['error'] = $e->getMessage();
		}
		return $response;
	}
}

Stripe_Connection::init();
