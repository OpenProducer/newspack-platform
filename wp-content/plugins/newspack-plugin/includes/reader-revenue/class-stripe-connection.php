<?php
/**
 * All things Stripe.
 *
 * @package Newspack
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

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
	const STRIPE_CUSTOMER_ID_USER_META   = '_newspack_stripe_customer_id';

	const ESP_METADATA_VALUES = [
		'once_donor'    => 'Donor',
		'monthly_donor' => 'Monthly Donor',
		'yearly_donor'  => 'Yearly Donor',
	];

	/**
	 * Ensures the customer ID lookup is run only once per request.
	 *
	 * @var bool
	 */
	private static $is_looking_up_customer_id = false;

	/**
	 * Cache.
	 *
	 * @var array
	 */
	private static $cache = [
		'invoices'      => [],
		'subscriptions' => [],
	];

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		add_action( 'rest_api_init', [ __CLASS__, 'register_api_endpoints' ] );
		add_action( 'init', [ __CLASS__, 'handle_merchant_id_file_request' ] );
		add_action( 'init', [ __CLASS__, 'register_apple_pay_domain' ] );
		add_filter( 'woocommerce_email_enabled_customer_completed_order', [ __CLASS__, 'is_wc_complete_order_email_enabled' ] );
		add_action( 'newspack_reader_verified', [ __CLASS__, 'newspack_reader_verified' ] );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 *
	 * @codeCoverageIgnore
	 */
	public static function register_api_endpoints() {
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
		$location_code = 'US';
		$currency      = 'USD';

		// The instance might've used WooCommerce. Read WC's saved settings to
		// provide more sensible defaults.
		$wc_country = get_option( 'woocommerce_default_country', false );
		if ( $wc_country ) {
			$wc_country = explode( ':', $wc_country )[0];
		}
		$valid_country_codes = wp_list_pluck( newspack_get_countries(), 'value' );
		if ( $wc_country && in_array( $wc_country, $valid_country_codes ) ) {
			$location_code = $wc_country;
		}
		$wc_currency      = get_option( 'woocommerce_currency', false );
		$valid_currencies = wp_list_pluck( newspack_get_currencies_options(), 'value' );
		if ( $wc_currency && in_array( $wc_currency, $valid_currencies ) ) {
			$currency = $wc_currency;
		}

		return [
			'enabled'            => false,
			'testMode'           => false,
			'publishableKey'     => '',
			'secretKey'          => '',
			'testPublishableKey' => '',
			'testSecretKey'      => '',
			'currency'           => $currency,
			'location_code'      => $location_code,
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
		if ( isset( $updated_stripe_data['fee_multiplier'] ) ) {
			update_option( 'newspack_blocks_donate_fee_multiplier', $updated_stripe_data['fee_multiplier'] );
			unset( $updated_stripe_data['fee_multiplier'] );
		}
		if ( isset( $updated_stripe_data['fee_static'] ) ) {
			update_option( 'newspack_blocks_donate_fee_static', $updated_stripe_data['fee_static'] );
			unset( $updated_stripe_data['fee_static'] );
		}
		// Save it in options table.
		return update_option( self::STRIPE_DATA_OPTION_NAME, $updated_stripe_data );
	}

	/**
	 * Get Stripe customer.
	 *
	 * @param string $customer_id Customer ID.
	 */
	private static function get_customer_by_id( $customer_id ) {
		$stripe = self::get_stripe_client();
		try {
			return $stripe->customers->retrieve( $customer_id, [] );
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_newspack', __( 'Could not fetch customer.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Get customer's charges.
	 *
	 * @param string $customer_id Customer ID.
	 * @param int    $page Page of results.
	 * @param int    $limit Limit of results.
	 */
	public static function get_customer_charges( $customer_id, $page = false, $limit = 10 ) {
		$stripe = self::get_stripe_client();
		try {
			$all_charges = [];
			$params      = [
				'query' => 'customer:"' . $customer_id . '"',
				'limit' => $limit,
			];
			if ( $page ) {
				$params['page'] = $page;
			}
			$response    = $stripe->charges->search( $params );
			$all_charges = $response['data'];
			if ( $response['has_more'] ) {
				$all_charges = array_merge( $all_charges, self::get_customer_charges( $customer_id, $response['next_page'] ) );
			}
			return $all_charges;
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_newspack', __( 'Could not fetch customer\'s charges.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Get the sum of all customer's charges (Lifetime Value).
	 *
	 * @param string $customer_id Customer ID.
	 */
	public static function get_customer_ltv( $customer_id ) {
		$all_charges = self::get_customer_charges( $customer_id );
		if ( \is_wp_error( $all_charges ) ) {
			return $all_charges;
		}
		return array_reduce(
			$all_charges,
			function( $total, $charge ) {
				return $total + self::normalise_amount( $charge['amount'], $charge['currency'] );
			},
			0
		);
	}

	/**
	 * Get Stripe billing portal configuration used for this integration.
	 * The configuration will disallow the customer to update their email,
	 * because it has to stay in sync with WP.
	 */
	private static function get_billing_portal_configuration_id() {
		$config_meta_key = 'newspack_config_v2';
		$stripe          = self::get_stripe_client();
		try {
			$all_configs = $stripe->billingPortal->configurations->all( [ 'active' => true ] );
			$config_id   = false;
			foreach ( $all_configs['data'] as $config ) {
				if ( $config['metadata'][ $config_meta_key ] ) {
					$config_id = $config['id'];
				}
			}
			if ( ! $config_id ) {
				$new_config = $stripe->billingPortal->configurations->create(
					[
						'features'         => [
							'customer_update'       => [
								'allowed_updates' => [ 'tax_id' ],
								'enabled'         => true,
							],
							'invoice_history'       => [
								'enabled' => true,
							],
							'payment_method_update' => [
								'enabled' => true,
							],
							'subscription_pause'    => [
								'enabled' => false,
							],
							'subscription_cancel'   => [
								'cancellation_reason' => [
									'enabled' => true,
									'options' => [ 'too_expensive', 'unused', 'other' ],
								],
								'enabled'             => true,
								'mode'                => 'at_period_end',
								'proration_behavior'  => 'none',
							],
						],
						'business_profile' => [ 'headline' => '' ],
						'metadata'         => [
							$config_meta_key => true,
						],
					]
				);
				$config_id  = $new_config['id'];
				Logger::log( 'Created a new Stripe billing portal configuration, id is: ' . $config_id );
			}
			return $config_id;
		} catch ( \Throwable $e ) {
			Logger::log( 'Failed at creating Stripe billing portal configuration: ' . $e->getMessage() );
			return new \WP_Error( 'stripe_newspack', __( 'Could not retrieve or create billing portal configuration.', 'newspack' ), $e->getMessage() );
		}
	}
	/**
	 * Get Stripe customer.
	 *
	 * @param string $customer_id Customer ID.
	 * @param string $return_url Return URL.
	 */
	public static function get_billing_portal_url( $customer_id, $return_url = false ) {
		$stripe = self::get_stripe_client();
		if ( false === $return_url ) {
			global $wp;
			$return_url = home_url( add_query_arg( array(), $wp->request ) );
		}
		try {
			$portal_data = $stripe->billingPortal->sessions->create(
				[
					'customer'      => $customer_id,
					'return_url'    => $return_url,
					'configuration' => self::get_billing_portal_configuration_id(),
				]
			);
			return $portal_data['url'];
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_newspack', __( 'Could not create billing portal session.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Retrieve a customer by email address.
	 *
	 * @param string $email_address Email address.
	 */
	private static function get_customer_by_email( $email_address ) {
		try {
			$stripe          = self::get_stripe_client();
			$found_customers = $stripe->customers->all( [ 'email' => $email_address ] )['data'];
			return count( $found_customers ) ? $found_customers[0] : null;
		} catch ( \Throwable $e ) {
			return null;
		}
	}

	/**
	 * Get Stripe invoice.
	 *
	 * @param string $invoice_id Invoice ID.
	 */
	public static function get_invoice( $invoice_id ) {
		if ( empty( $invoice_id ) ) {
			return new \WP_Error( 'stripe_newspack', __( 'Invoice ID is missing.', 'newspack' ) );
		}
		if ( isset( self::$cache['invoices'][ $invoice_id ] ) ) {
			return self::$cache['invoices'][ $invoice_id ];
		}
		$stripe = self::get_stripe_client();
		try {
			$result                                 = $stripe->invoices->retrieve( $invoice_id, [] );
			self::$cache['invoices'][ $invoice_id ] = $result;
			return $result;
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_newspack', __( 'Could not fetch invoice.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Get Stripe subscription.
	 *
	 * @param string $subscription_id Invoice ID.
	 */
	public static function get_subscription( $subscription_id ) {
		if ( isset( self::$cache['subscriptions'][ $subscription_id ] ) ) {
			return self::$cache['subscriptions'][ $subscription_id ];
		}
		$stripe = self::get_stripe_client();
		try {
			$result = $stripe->subscriptions->retrieve( $subscription_id, [] );
			self::$cache['subscriptions'][ $subscription_id ] = $result;
			return $result;
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_newspack', __( 'Could not fetch subscription.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Get Subscription from payment, if one exists.
	 *
	 * @param array $payment Payment object.
	 */
	public static function get_subscription_from_payment( $payment ) {
		if ( $payment['invoice'] ) {
			$invoice = self::get_invoice( $payment['invoice'] );
			if ( ! \is_wp_error( $invoice ) && $invoice['subscription'] ) {
				return self::get_subscription( $invoice['subscription'] );
			}
		}
	}

	/**
	 * Get balance transaction.
	 *
	 * @param string $transaction_id Transaction ID.
	 */
	private static function get_balance_transaction( $transaction_id ) {
		$stripe = self::get_stripe_client();
		try {
			return $stripe->balanceTransactions->retrieve( $transaction_id, [] );
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_newspack', __( 'Could not fetch balance transaction.', 'newspack' ), $e->getMessage() );
		}
	}

	/**
	 * Format an amount with currency symbol.
	 *
	 * @param number $amount Amount.
	 * @param string $currency Currency code.
	 */
	private static function format_amount( $amount, $currency ) {
		$symbol = newspack_get_currency_symbol( strtoupper( $currency ) );
		if ( 'USD' === strtoupper( $currency ) ) {
			return $symbol . $amount;
		} else {
			return $amount . $symbol;
		}
	}

	/**
	 * Send an email to customer.
	 *
	 * @param object $customer Stripe customer.
	 * @param object $payment Stripe payment.
	 */
	private static function send_email_to_customer( $customer, $payment ) {
		$amount_normalised = self::normalise_amount( $payment['amount'], $payment['currency'] );

		// Replace content placeholders.
		$placeholders = [
			[
				'template' => '*AMOUNT*',
				'value'    => self::format_amount( $amount_normalised, $payment['currency'] ),
			],
			[
				'template' => '*DATE*',
				'value'    => gmdate( 'Y-m-d', $payment['created'] ),
			],
			[
				'template' => '*PAYMENT_METHOD*',
				'value'    => __( 'Card', 'newspack' ) . ' – ' . $payment['payment_method_details']['card']['last4'],
			],
			[
				'template' => '*RECEIPT_URL*',
				'value'    => sprintf( '<a href="%s">%s</a>', $payment['receipt_url'], 'stripe.com' ),
			],
		];

		Emails::send_email(
			Reader_Revenue_Emails::EMAIL_TYPES['RECEIPT'],
			$customer['email'],
			$placeholders
		);
	}

	/**
	 * Determine the memberhip status metadata field value.
	 *
	 * @param string $frequency Frequency of payment.
	 */
	public static function get_membership_status_field_value( $frequency ) {
		switch ( $frequency ) {
			case 'once':
				return self::ESP_METADATA_VALUES['once_donor'];
			case 'year':
				return self::ESP_METADATA_VALUES['yearly_donor'];
			case 'month':
				return self::ESP_METADATA_VALUES['monthly_donor'];
		}
	}

	/**
	 * Create metadata for a recurring payment.
	 *
	 * @param string $frequency Frequency.
	 * @param string $amount Amount.
	 * @param string $currency Currency.
	 * @param int    $date Date.
	 */
	public static function create_recurring_payment_metadata( $frequency, $amount, $currency, $date ) {
		$metadata          = [];
		$amount_normalised = self::normalise_amount( $amount, $currency );
		$payment_date      = gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $date );
		$metadata[ Newspack_Newsletters::$metadata_keys['billing_cycle'] ]     = $frequency;
		$metadata[ Newspack_Newsletters::$metadata_keys['recurring_payment'] ] = $amount_normalised;
		$metadata[ Newspack_Newsletters::$metadata_keys['membership_status'] ] = self::get_membership_status_field_value( $frequency );
		$next_payment_date = date_format( date_add( date_create( 'now' ), date_interval_create_from_date_string( '1 ' . $frequency ) ), Newspack_Newsletters::METADATA_DATE_FORMAT );
		$metadata[ Newspack_Newsletters::$metadata_keys['next_payment_date'] ] = $next_payment_date;
		$metadata[ Newspack_Newsletters::$metadata_keys['sub_start_date'] ]    = $payment_date;
		// In case this was previously set after a previous cancelled subscription, clear it.
		$metadata[ Newspack_Newsletters::$metadata_keys['sub_end_date'] ] = '';
		return $metadata;
	}

	/**
	 * Receive Stripe webhook.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public static function receive_webhook( $request ) {
		if ( ! Donations::is_platform_stripe() ) {
			return;
		}
		// Verify the webhook signature (https://stripe.com/docs/webhooks/signatures).
		$webhook = get_option( self::STRIPE_WEBHOOK_OPTION_NAME, false );
		if ( false === $webhook || ! isset( $webhook['secret'], $_SERVER['HTTP_STRIPE_SIGNATURE'] ) ) {
			return new \WP_Error( 'newspack_webhook_missing_verification_data' );
		}
		try {
			$sig_header = sanitize_text_field( $_SERVER['HTTP_STRIPE_SIGNATURE'] );
			$payload    = @file_get_contents( 'php://input' ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsRemoteFile, WordPress.PHP.NoSilencedErrors.Discouraged
			$event      = \Stripe\Webhook::constructEvent(
				$payload,
				$sig_header,
				$webhook['secret']
			);
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'newspack_webhook_verification_error' );
		}

		$payload = $request['data']['object'];

		// If order_id is set in the metadata, this was not created by this integration.
		// This can happen when WC Subscriptions w/ Stripe Gateway was active before using this integration.
		// In such a situation, WCS continues to charge subscribers, but since the platform is set to this integration,
		// the webhook will still be exectuted for these payments, resulting in duplicate WC orders.
		if ( isset( $payload['metadata']['order_id'] ) ) {
			return;
		}

		switch ( $request['type'] ) {
			case 'charge.succeeded':
				$payment  = $payload;
				$metadata = $payment['metadata'];
				$customer = self::get_customer_by_id( $payment['customer'] );
				if ( \is_wp_error( $customer ) ) {
					return $customer;
				}
				$amount_normalised = self::normalise_amount( $payment['amount'], $payment['currency'] );
				$client_id         = isset( $customer['metadata']['clientId'] ) ? $customer['metadata']['clientId'] : null;
				$origin            = isset( $customer['metadata']['origin'] ) ? $customer['metadata']['origin'] : null;

				$referer = '';
				if ( isset( $metadata['referer'] ) ) {
					$referer = $metadata['referer'];
				}

				$frequency = self::get_frequency_of_payment( $payment );

				if ( $payment['invoice'] ) {
					$invoice = self::get_invoice( $payment['invoice'] );
					if ( ! \is_wp_error( $invoice ) && isset( $invoice['metadata']['referer'] ) ) {
						$referer = $invoice['metadata']['referer'];
					}
				}

				// Update data in Newsletters provider.
				$was_customer_added_to_mailing_list = false;
				$stripe_data                        = self::get_stripe_data();
				$has_opted_in_to_newsletters        = self::has_customer_opted_in_to_newsletters( $customer );
				if (
					method_exists( '\Newspack_Newsletters_Subscription', 'add_contact' )
					&& (
						$has_opted_in_to_newsletters
						|| Reader_Activation::is_enabled()
					)
				) {
					$contact = [
						'email'    => $customer['email'],
						'name'     => $customer['name'],
						'metadata' => [],
					];

					if ( Reader_Activation::is_enabled() ) {
						$payment_date = gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $payment['created'] );
						$customer_ltv = self::get_customer_ltv( $customer['id'] );
						if ( ! \is_wp_error( $customer_ltv ) ) {
							$total_paid = $customer_ltv + $amount_normalised;
							$contact['metadata'][ Newspack_Newsletters::$metadata_keys['total_paid'] ] = $total_paid;
						}

						$contact['metadata'] = array_merge(
							$contact['metadata'],
							[
								Newspack_Newsletters::$metadata_keys['last_payment_date']   => $payment_date,
								Newspack_Newsletters::$metadata_keys['last_payment_amount'] => $amount_normalised,
							]
						);

						$metadata[ Newspack_Newsletters::$metadata_keys['membership_status'] ] = self::get_membership_status_field_value( $frequency );
						if ( 'once' !== $frequency ) {
							$contact['metadata'] = array_merge(
								self::create_recurring_payment_metadata( $frequency, $payment['amount'], $payment['currency'], $payment['created'] ),
								$contact['metadata']
							);
						}
						if ( isset( $customer['metadata']['userId'] ) ) {
							$contact['metadata'][ Newspack_Newsletters::$metadata_keys['account'] ] = $customer['metadata']['userId'];
						}
						if ( isset( $customer['metadata']['current_page_url'] ) ) {
							$contact['metadata']['current_page_url'] = $customer['metadata']['current_page_url'];
						}

						if ( Donations::is_woocommerce_suite_active() ) {
							$wc_product_id = Donations::get_donation_product( $frequency );
							try {
								$wc_product = \wc_get_product( $wc_product_id );
								$contact['metadata'][ Newspack_Newsletters::$metadata_keys['product_name'] ] = $wc_product->get_name();
							} catch ( \Throwable $th ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
								// Fail silently.
							}
						}
					}

					if ( ! empty( $client_id ) ) {
						$contact['client_id'] = $client_id;
					}

					// Note: With Mailchimp, this is adding the contact as 'pending' - the subscriber has to confirm.
					if ( ! empty( $stripe_data['newsletter_list_id'] ) && $has_opted_in_to_newsletters ) {
						\Newspack_Newsletters_Subscription::add_contact( $contact, $stripe_data['newsletter_list_id'] );
					} else {
						\Newspack_Newsletters_Subscription::add_contact( $contact );
					}
					$was_customer_added_to_mailing_list = true;
				}

				// Update data in Campaigns plugin.
				if ( ! empty( $client_id ) ) {
					/**
					 * When a new Stripe transaction occurs that can be associated with a client ID,
					 * fire an action with the client ID and the relevant donation info.
					 *
					 * @param string      $client_id Client ID.
					 * @param array       $donation_data Info about the transaction.
					 * @param string|null $newsletter_email If the user signed up for a newsletter as part of the transaction, the subscribed email address. Otherwise, null.
					 */
					do_action(
						'newspack_stripe_new_donation',
						$client_id,
						[
							'stripe_id'          => $payment['id'],
							'stripe_customer_id' => $customer['id'],
							'amount'             => $amount_normalised,
							'frequency'          => $frequency,
						],
						$was_customer_added_to_mailing_list ? $customer['email'] : null
					);
				}

				$label = $frequency;
				if ( ! empty( $origin ) ) {
					$label .= ' - ' . $origin;
				}

				// Send custom event to GA.
				\Newspack\Google_Services_Connection::send_custom_event(
					[
						'category' => __( 'Newspack Donation', 'newspack' ),
						'action'   => __( 'Stripe', 'newspack' ),
						'label'    => $label,
						'value'    => $amount_normalised,
						'referer'  => $referer,
					]
				);

				// Add a transaction to WooCommerce.
				if ( Donations::is_woocommerce_suite_active() ) {
					WooCommerce_Connection::create_transaction( self::create_wc_transaction_payload( $customer, $payment ) );
				}

				// Send email to the donor.
				self::send_email_to_customer( $customer, $payment );

				break;
			case 'charge.failed':
				break;
			case 'customer.subscription.deleted':
				$customer = self::get_customer_by_id( $payload['customer'] );
				if ( \is_wp_error( $customer ) ) {
					return $customer;
				}

				$active_subs = 0;
				if ( Donations::is_woocommerce_suite_active() ) {
					if ( $payload['ended_at'] ) {
						$active_subs = WooCommerce_Connection::end_subscription(
							$payload['id'],
							$payload['ended_at']
						);
					}
				}

				if ( Reader_Activation::is_enabled() && method_exists( '\Newspack_Newsletters_Subscription', 'add_contact' ) ) {
					$sub_end_date = gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $payload['ended_at'] );
					$contact      = [
						'email'    => $customer['email'],
						'metadata' => [
							Newspack_Newsletters::$metadata_keys['sub_end_date']   => $sub_end_date,
						],
					];
					if ( 0 === $active_subs && in_array( $payload['plan']['interval'], [ 'month', 'year' ] ) ) {
						$membership_status = 'Ex-' . self::get_membership_status_field_value( $payload['plan']['interval'] );
						$contact['metadata'][ Newspack_Newsletters::$metadata_keys['membership_status'] ] = $membership_status;
					}
					\Newspack_Newsletters_Subscription::add_contact( $contact );
				}

				// Update data in Campaigns plugin.
				$client_id = isset( $customer['metadata']['clientId'] ) ? $customer['metadata']['clientId'] : null;
				if ( ! empty( $client_id ) ) {
					/**
					 * When a Stripe subscription is cancelled that can be associated with a client ID,
					 * fire an action with the client ID and the relevant info.
					 *
					 * @param string      $client_id Client ID.
					 * @param array       $cancellation_data Info about the event.
					 */
					do_action(
						'newspack_stripe_donation_cancellation',
						$client_id,
						[
							'stripe_id'          => $payload['id'],
							'stripe_customer_id' => $customer['id'],
							'frequency'          => $payload['plan']['interval'],
						]
					);
				}

				break;
			case 'customer.subscription.updated':
				if ( Donations::is_woocommerce_suite_active() ) {
					if ( $payload['cancel_at'] ) {
						WooCommerce_Connection::set_pending_cancellation_subscription( $payload['id'], $payload['canceled_at'], $payload['cancel_at'] );
					} elseif ( ! empty( $payload['pause_collection'] ) ) {
						$reactivation_date = $payload['pause_collection']['resumes_at'];
						WooCommerce_Connection::put_subscription_on_hold(
							$payload['id'],
							$payload['pause_collection']['resumes_at']
						);
					} elseif ( 'active' === $payload['status'] ) {
						// An un-canceled subscription, or resumed after pausing.
						WooCommerce_Connection::reactivate_subscription( $payload['id'] );
					}
				}

				if ( Reader_Activation::is_enabled() && method_exists( '\Newspack_Newsletters_Subscription', 'add_contact' ) ) {
					$customer = self::get_customer_by_id( $payload['customer'] );
					if ( \is_wp_error( $customer ) ) {
						return $customer;
					}

					$sub_end_date = gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $payload['ended_at'] );
					$contact      = [
						'email'    => $customer['email'],
						'metadata' => [],
					];
					if ( $payload['cancel_at'] ) {
						// Cancellation was scheduled.
						$sub_end_date = gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $payload['cancel_at'] );
						$contact['metadata'][ Newspack_Newsletters::$metadata_keys['sub_end_date'] ] = $sub_end_date;
					} elseif ( 'active' === $payload['status'] ) {
						// An update to an active subscription (or activation of it).
						$plan                = $payload['plan'];
						$contact['metadata'] = array_merge(
							self::create_recurring_payment_metadata( $plan['interval'], $payload['quantity'], $payload['currency'], $payload['start_date'] ),
							$contact['metadata']
						);
					}
					if ( count( $contact['metadata'] ) ) {
						\Newspack_Newsletters_Subscription::add_contact( $contact );
					}
				}
				break;
			default:
				return new \WP_Error( 'newspack_unsupported_webhook' );
		}
	}

	/**
	 * Get URL of the webhook for this site.
	 */
	public static function get_webhook_url() {
		return get_rest_url( null, NEWSPACK_API_NAMESPACE . '/stripe/webhook' );
	}

	/**
	 * Reset Stripe webhooks.
	 */
	private static function reset_webhooks() {
		Logger::log( 'Resetting Stripe webhooks…' );
		delete_option( self::STRIPE_WEBHOOK_OPTION_NAME );
		try {
			$stripe   = self::get_stripe_client();
			$webhooks = $stripe->webhookEndpoints->all( [ 'limit' => 100 ] );
			foreach ( $webhooks as $webhook ) {
				if ( self::get_webhook_url() === $webhook->url ) {
					$stripe->webhookEndpoints->delete( $webhook->id );
				}
			}
			return true;
		} catch ( \Throwable $th ) {
			Logger::log( 'Could not reset Stripe webhooks: ' . $th->getMessage() );
			return false;
		}
	}

	/**
	 * Create Stripe webhooks if they are missing. Otherwise, validate the webhhooks.
	 *
	 * @param bool $validate_existence_only If true, only validate the existence of the webhooks.
	 */
	public static function validate_or_create_webhooks( $validate_existence_only = false ) {
		$is_valid        = true;
		$created_webhook = get_option( self::STRIPE_WEBHOOK_OPTION_NAME );

		if ( true === $validate_existence_only ) {
			if ( false === $created_webhook ) {
				// If the webhook does not exist, do the full validation & creation.
				self::validate_or_create_webhooks( false );
			}
			return;
		}

		$webhook_events = [
			'charge.failed',
			'charge.succeeded',
			'customer.subscription.deleted',
			'customer.subscription.updated',
		];
		$stripe         = self::get_stripe_client();
		if ( ! $created_webhook ) {
			Logger::log( 'Creating Stripe webhooks…' );
			try {
				$webhook = $stripe->webhookEndpoints->create(
					[
						'url'            => self::get_webhook_url(),
						'enabled_events' => $webhook_events,
					]
				);
				update_option(
					self::STRIPE_WEBHOOK_OPTION_NAME,
					[
						'id'     => $webhook->id,
						'secret' => $webhook->secret,
					]
				);
				return true;
			} catch ( \Throwable $e ) {
				return new \WP_Error( 'newspack_plugin_stripe_webhooks', __( 'Webhook creation failed: ', 'newspack' ) . $e->getMessage() );
			}
		} elseif ( isset( $created_webhook['id'] ) ) {
			try {
				$webhook = $stripe->webhookEndpoints->retrieve( $created_webhook['id'] );
				if ( $webhook->enabled_events !== $webhook_events ) {
					$is_valid = false;
				}
				if ( 'enabled' !== $webhook['status'] ) {
					$is_valid = false;
				}
				if ( self::get_webhook_url() !== $webhook['url'] ) {
					$is_valid = false;
				}
			} catch ( \Throwable $e ) {
				return new \WP_Error( 'newspack_plugin_stripe_webhooks', __( 'Webhook validation failed: ', 'newspack' ) . $e->getMessage() );
			}
		} else {
			$is_valid = false;
		}
		if ( ! $is_valid ) {
			self::reset_webhooks();
		}
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
			// Filter out empty values, which are not booleans.
			$saved_stripe_data = array_filter(
				get_option( self::STRIPE_DATA_OPTION_NAME, [] ),
				function ( $value ) {
					if ( 'boolean' === gettype( $value ) ) {
						return true;
					} else {
						return ! empty( $value );
					}
				}
			);
			$stripe_data       = array_merge( $stripe_data, $saved_stripe_data );
		}
		$stripe_data['usedPublishableKey'] = $stripe_data['testMode'] ? $stripe_data['testPublishableKey'] : $stripe_data['publishableKey'];
		$stripe_data['usedSecretKey']      = $stripe_data['testMode'] ? $stripe_data['testSecretKey'] : $stripe_data['secretKey'];
		$stripe_data['fee_multiplier']     = get_option( 'newspack_blocks_donate_fee_multiplier', '2.9' );
		$stripe_data['fee_static']         = get_option( 'newspack_blocks_donate_fee_static', '0.3' );

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
			$payment_data  = self::get_stripe_data();

			foreach ( $all_prices as $price ) {
				$has_metadata_field = isset( $price['metadata'][ self::STRIPE_DONATION_PRICE_METADATA ] );
				if ( $has_metadata_field ) {
					$metadata_field_value = $price['metadata'][ self::STRIPE_DONATION_PRICE_METADATA ];
					$currency_matches     = strtolower( $payment_data['currency'] ) === strtolower( $price['currency'] );
					if ( $currency_matches && 'month' === $metadata_field_value && 'month' === $price['recurring']['interval'] ) {
						$prices_mapped['month'] = $price;
					}
					if ( $currency_matches && 'year' === $metadata_field_value && 'year' === $price['recurring']['interval'] ) {
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
	public static function is_currency_zero_decimal( $currency ) {
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
	public static function normalise_amount( $amount, $currency ) {
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
	 * Get (by email) or create customer.
	 *
	 * @param object $data Customer data.
	 */
	private static function upsert_customer( $data ) {
		try {
			$stripe   = self::get_stripe_client();
			$customer = self::get_customer_by_email( $data['email'] );

			$customer_data_payload = [
				'email'       => $data['email'],
				'name'        => $data['name'],
				'description' => __( 'Newspack Donor', 'newspack-blocks' ),
			];
			if ( isset( $data['metadata'] ) ) {
				$customer_data_payload['metadata'] = $data['metadata'];
			}

			if ( null === $customer ) {
				$customer = $stripe->customers->create( $customer_data_payload );
			} else {
				$customer = $stripe->customers->update(
					$customer['id'],
					$customer_data_payload
				);
			}
			return $customer;
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'newspack_plugin_stripe', $e->getMessage() ?? __( 'Customer creation failed.', 'newspack' ) );
		}
	}

	/**
	 * Handle a donation in Stripe.
	 * If it's a recurring donation, a subscription will be created. Otherwise,
	 * a single charge.
	 *
	 * @param object $config Data about the donation.
	 */
	public static function handle_donation( $config ) {
		self::validate_or_create_webhooks( true );

		$response = [
			'error'  => null,
			'status' => null,
		];
		try {
			$stripe = self::get_stripe_client();

			$amount_raw        = $config['amount'];
			$frequency         = $config['frequency'];
			$email_address     = $config['email_address'];
			$full_name         = $config['full_name'];
			$token_data        = $config['token_data'];
			$client_metadata   = $config['client_metadata'];
			$payment_metadata  = $config['payment_metadata'];
			$payment_method_id = $config['payment_method_id'];

			if ( ! isset( $client_metadata['userId'] ) && Reader_Activation::is_enabled() ) {
				$reader_metadata                        = $client_metadata;
				$reader_metadata['registration_method'] = 'stripe-donation';
				$user_id                                = Reader_Activation::register_reader( $email_address, $full_name, true, $reader_metadata );
				if ( ! \is_wp_error( $user_id ) && false !== $user_id ) {
					$client_metadata['userId'] = $user_id;
				}
			}

			$customer = self::upsert_customer(
				[
					'email'    => $email_address,
					'name'     => $full_name,
					'metadata' => $client_metadata,
				]
			);
			if ( \is_wp_error( $customer ) ) {
				$response['error'] = $customer->get_error_message();
				return $response;
			}

			// Attach the Payment Method ID to the customer.
			// A new payment method is created for each one-time or first-in-recurring
			// transaction, because the payment methods are not stored on WP.
			$stripe->paymentMethods->attach( // phpcs:ignore
				$payment_method_id,
				[ 'customer' => $customer['id'] ]
			);
			// Set the payment method as the default for customer's transactions.
			$stripe->customers->update(
				$customer['id'],
				[
					'invoice_settings' => [
						'default_payment_method' => $payment_method_id,
					],
				]
			);

			if ( 'once' === $frequency ) {
				// Create a Payment Intent on Stripe.
				$payment_data = self::get_stripe_data();
				$intent       = self::create_payment_intent(
					[
						'amount'   => $amount_raw,
						'customer' => $customer['id'],
						'metadata' => $payment_metadata,
					]
				);
				if ( ! Emails::can_send_email( Reader_Revenue_Emails::EMAIL_TYPES['RECEIPT'] ) ) {
					// If this instance can't send the receipt email, make Stripe send the email.
					$intent['receipt_email'] = $email_address;
				}
				$response['client_secret'] = $intent['client_secret'];
			} else {
				// Create a Subscription on Stripe.
				$prices = self::get_donation_prices();
				$price  = $prices[ $frequency ];
				$amount = self::get_amount( $amount_raw, $price['currency'] );

				$subscription = $stripe->subscriptions->create(
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

	/**
	 * Create a Stripe payment intent.
	 *
	 * @param object $config Data about the payment intent.
	 */
	private static function create_payment_intent( $config ) {
		$stripe       = self::get_stripe_client();
		$payment_data = self::get_stripe_data();
		$intent_data  = [
			'amount'               => self::get_amount( $config['amount'], $payment_data['currency'] ),
			'metadata'             => $config['metadata'],
			'currency'             => $payment_data['currency'],
			'payment_method_types' => [ 'card' ],
			'description'          => __( 'Newspack One-Time Donation', 'newspack-blocks' ),
			'customer'             => $config['customer'],
		];
		return $stripe->paymentIntents->create( $intent_data );
	}

	/**
	 * Handle Stripe's Apple Pay verification.
	 */
	public static function handle_merchant_id_file_request() {
		if ( isset( $_SERVER['REQUEST_URI'] ) ) { // WPCS: Input var okay.
			$raw_uri = sanitize_text_field(
				wp_unslash( $_SERVER['REQUEST_URI'] ) // WPCS: Input var okay.
			);
			if ( '/.well-known/apple-developer-merchantid-domain-association' === $raw_uri ) {
				$path = dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/reader-revenue/apple-developer-merchantid-domain-association';
				header( 'content-type: application/octet-stream' );
				echo file_get_contents( $path ); // phpcs:ignore
				exit;
			}
		}
	}

	/**
	 * Get site's domain.
	 */
	private static function get_site_domain() {
		return wp_parse_url( site_url() )['host'];
	}

	/**
	 * Does the connected Stripe instance have the site's domain
	 * registerd with Apple Pay?
	 */
	private static function is_apple_pay_domain_registered() {
		try {
			$stripe       = self::get_stripe_client();
			$site_domain  = self::get_site_domain();
			$found_domain = array_filter(
				$stripe->applePayDomains->all()['data'],
				function( $item ) use ( $site_domain ) {
					return $site_domain === $item->domain_name && true === $item->livemode;
				}
			);
			return 0 !== count( $found_domain );
		} catch ( \Throwable $e ) {
			return false;
		}
	}

	/**
	 * Ensure the site's domain is registered with Stripe for Apple Pay.
	 */
	public static function register_apple_pay_domain() {
		if ( get_transient( '_newspack_is_registering_apple_pay_domain' ) ) {
			return;
		}
		try {
			$secret_key = self::get_stripe_secret_key();
			if ( 0 === stripos( $secret_key, 'sk_test' ) ) {
				// Apple Pay domains can only be added using live keys.
				return;
			}
			set_transient( '_newspack_is_registering_apple_pay_domain', true, 10 );
			$stripe = self::get_stripe_client();
			if ( $stripe ) {
				$site_domain = self::get_site_domain();
				if ( ! self::is_apple_pay_domain_registered() ) {
					$stripe->applePayDomains->create( [ 'domain_name' => $site_domain ] );
				}
			}
		} catch ( \Exception $e ) {
			return;
		}
	}

	/**
	 * Disable WC's order complete email, if the email will be sent by this integration.
	 *
	 * @param bool $is_enabled True if enabled.
	 */
	public static function is_wc_complete_order_email_enabled( $is_enabled ) {
		if ( Donations::is_platform_stripe() && Emails::can_send_email( Reader_Revenue_Emails::EMAIL_TYPES['RECEIPT'] ) ) {
			$is_enabled = false;
		}
		return $is_enabled;
	}

	/**
	 * Handle the newspack_reader_verified hook.
	 *
	 * @param \WP_User $user   The user object.
	 */
	public static function newspack_reader_verified( $user ) {
		self::sync_customer_id( $user->user_email );
	}

	/**
	 * Lookup the customer ID for a given email address.
	 *
	 * @param string $email_address   Email address.
	 */
	private static function sync_customer_id( $email_address ) {
		if ( self::$is_looking_up_customer_id ) {
			return;
		}
		self::$is_looking_up_customer_id = true;
		if ( ! $email_address ) {
			return;
		}
		$customer = self::get_customer_by_email( $email_address );
		if ( $customer ) {
			update_user_meta( get_current_user_id(), self::STRIPE_CUSTOMER_ID_USER_META, $customer['id'] );
		}
	}

	/**
	 * Get frequency of a payment.
	 *
	 * @param array $payment Stripe payment.
	 */
	public static function get_frequency_of_payment( $payment ) {
		$frequency = 'once';
		if ( $payment['invoice'] ) {
			// A subscription payment will have an invoice.
			$invoice = self::get_invoice( $payment['invoice'] );
			if ( \is_wp_error( $invoice ) ) {
				return $frequency;
			}
			$recurring = $invoice['lines']['data'][0]['price']['recurring'];
			if ( isset( $recurring['interval'] ) ) {
				$frequency = $recurring['interval'];
			}
		}
		return $frequency;
	}

	/**
	 * Has this customer opted in to receiving the newsletter?
	 *
	 * @param array $customer Stripe customer.
	 */
	private static function has_customer_opted_in_to_newsletters( $customer ) {
		return isset( $customer['metadata']['newsletterOptIn'] ) && 'true' === $customer['metadata']['newsletterOptIn'];
	}

	/**
	 * Create WC transaction payload.
	 *
	 * @param array $customer Stripe customer.
	 * @param array $payment Stripe payment.
	 */
	public static function create_wc_transaction_payload( $customer, $payment ) {
		$balance_transaction    = self::get_balance_transaction( $payment['balance_transaction'] );
		$amount_normalised      = self::normalise_amount( $payment['amount'], $payment['currency'] );
		$stripe_data            = self::get_stripe_data();
		$subscription_id        = null;
		$invoice_billing_reason = null;
		$invoice                = self::get_invoice( $payment['invoice'] );
		if ( $invoice && ! \is_wp_error( $invoice ) ) {
			$invoice_billing_reason = $invoice['billing_reason'];
			if ( isset( $invoice['subscription'] ) && is_string( $invoice['subscription'] ) ) {
				$subscription_id = $invoice['subscription'];
			}
		} elseif ( \is_wp_error( $invoice ) ) {
			Logger::log( 'Invoice error: ' . $invoice->get_error_message() );
		}
		return [
			'email'                         => $customer['email'],
			'name'                          => $customer['name'],
			'stripe_id'                     => $payment['id'],
			'stripe_customer_id'            => $customer['id'],
			'stripe_fee'                    => self::normalise_amount( $balance_transaction['fee'], $payment['currency'] ),
			'stripe_net'                    => self::normalise_amount( $balance_transaction['net'], $payment['currency'] ),
			'stripe_invoice_billing_reason' => $invoice_billing_reason,
			'stripe_subscription_id'        => $subscription_id,
			'date'                          => $payment['created'],
			'amount'                        => $amount_normalised,
			'frequency'                     => self::get_frequency_of_payment( $payment ),
			'currency'                      => $stripe_data['currency'],
			'client_id'                     => $customer['metadata']['clientId'],
			'user_id'                       => $customer['metadata']['userId'],
			'subscribed'                    => self::has_customer_opted_in_to_newsletters( $customer ),
		];
	}
}

Stripe_Connection::init();
