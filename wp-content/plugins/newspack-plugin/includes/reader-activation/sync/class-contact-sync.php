<?php
/**
 * Reader contact data syncing with the active integrations.
 *
 * @package Newspack
 */

namespace Newspack\Reader_Activation;

use Newspack\Reader_Activation;
use Newspack\Reader_Activation\Integrations;
use Newspack\Data_Events;
use Newspack\Logger;

defined( 'ABSPATH' ) || exit;

/**
 * Contact Sync Class.
 */
class Contact_Sync extends Sync {
	/**
	 * Context of the sync.
	 *
	 * @var string
	 */
	protected static $context = 'Contact Sync';

	/**
	 * Queued syncs containing their contexts keyed by email address.
	 *
	 * @var array[]
	 */
	protected static $queued_syncs = [];

	/**
	 * ActionScheduler hook for retrying a failed integration sync.
	 */
	const RETRY_HOOK = 'newspack_contact_sync_retry';

	/**
	 * Maximum number of retries for a failed integration sync.
	 */
	const MAX_RETRIES = 5;

	/**
	 * Backoff schedule in seconds for integration sync retries.
	 * 30s, 2min, 8min, 30min, 2h.
	 */
	const RETRY_BACKOFF = [ 30, 120, 480, 1800, 7200 ];

	/**
	 * Initialize hooks.
	 */
	public static function init_hooks() {
		add_action( 'newspack_scheduled_esp_sync', [ __CLASS__, 'scheduled_sync' ], 10, 2 );
		add_action( 'shutdown', [ __CLASS__, 'run_queued_syncs' ] );
		add_action( self::RETRY_HOOK, [ __CLASS__, 'execute_integration_retry' ] );
	}

	/**
	 * Sync contact to the ESP.
	 *
	 * @param array  $contact          The contact data to sync.
	 * @param string $context          The context of the sync. Defaults to static::$context.
	 * @param array  $existing_contact Optional. Existing contact data to merge with. Defaults to null.
	 *
	 * @return true|\WP_Error True if succeeded or WP_Error.
	 */
	public static function sync( $contact, $context = '', $existing_contact = null ) {
		$can_sync = static::can_sync( true );
		if ( $can_sync->has_errors() ) {
			return $can_sync;
		}

		if ( empty( $context ) ) {
			$context = static::$context;
		}

		// If we're running in a data event, queue the sync to run on shutdown.
		if ( Data_Events::current_event() ) {
			if ( ! isset( self::$queued_syncs[ $contact['email'] ] ) ) {
				self::$queued_syncs[ $contact['email'] ] = [
					'contexts' => [],
					'contact'  => [],
				];
			}
			if ( ! empty( self::$queued_syncs[ $contact['email'] ]['contact']['metadata'] ) ) {
				$contact['metadata'] = array_merge( self::$queued_syncs[ $contact['email'] ]['contact']['metadata'], $contact['metadata'] );
			}
			self::$queued_syncs[ $contact['email'] ]['contexts'][] = $context;
			self::$queued_syncs[ $contact['email'] ]['contact']    = $contact;
			if ( ! did_action( 'shutdown' ) ) {
				return true;
			}
		}

		return self::push_to_integrations( $contact, $context, $existing_contact );
	}

	/**
	 * Push contact data to all active integrations.
	 *
	 * Failed integrations are scheduled for retry via ActionScheduler
	 * with exponential backoff.
	 *
	 * @param array  $contact          The contact data to sync.
	 * @param string $context          The context of the sync.
	 * @param array  $existing_contact Optional. Existing contact data to merge with.
	 *
	 * @return true|\WP_Error True if all succeeded, or WP_Error with combined messages.
	 */
	private static function push_to_integrations( $contact, $context, $existing_contact = null ) {
		/** This filter is documented in includes/reader-activation/sync/class-contact-sync.php */
		$contact = \apply_filters( 'newspack_esp_sync_contact', $contact, $context );
		$contact = Sync\Metadata::normalize_contact_data( $contact );

		$integrations = Integrations::get_active_integrations();
		$errors       = [];

		foreach ( $integrations as $integration_id => $integration ) {
			$result = $integration->push_contact_data( $contact, $context, $existing_contact );
			if ( \is_wp_error( $result ) ) {
				self::schedule_integration_retry( $integration_id, $contact, $context, $existing_contact, 0, $result->get_error_message() );
				$errors[] = sprintf( '[%s] %s', $integration_id, $result->get_error_message() );
			}
		}

		if ( ! empty( $errors ) ) {
			return new \WP_Error( 'newspack_esp_sync_failed', implode( '; ', $errors ) );
		}

		return true;
	}

	/**
	 * Schedule a retry for a failed integration sync via ActionScheduler.
	 *
	 * @param string $integration_id   The integration ID.
	 * @param array  $contact          The contact data.
	 * @param string $context          The sync context.
	 * @param array  $existing_contact Optional. Existing contact data.
	 * @param int    $retry_count      Current retry count (0 = first failure).
	 * @param string $error_message    The error message from the failure.
	 */
	private static function schedule_integration_retry( $integration_id, $contact, $context, $existing_contact, $retry_count, $error_message ) {
		if ( ! function_exists( 'as_schedule_single_action' ) ) {
			return;
		}

		$next_retry = $retry_count + 1;
		if ( $next_retry > self::MAX_RETRIES ) {
			static::log(
				sprintf(
					'Max retries (%d) reached for integration "%s" sync of %s. Giving up. Last error: %s',
					self::MAX_RETRIES,
					$integration_id,
					$contact['email'] ?? 'unknown',
					$error_message
				)
			);
			return;
		}

		$backoff_index   = min( $retry_count, count( self::RETRY_BACKOFF ) - 1 );
		$backoff_seconds = self::RETRY_BACKOFF[ $backoff_index ];

		$retry_data = [
			'integration_id'   => $integration_id,
			'contact'          => $contact,
			'context'          => $context,
			'existing_contact' => $existing_contact,
			'retry_count'      => $next_retry,
		];

		\as_schedule_single_action(
			time() + $backoff_seconds,
			self::RETRY_HOOK,
			[ $retry_data ],
			'newspack'
		);

		static::log(
			sprintf(
				'Scheduled retry %d/%d for integration "%s" sync of %s in %ds. Error: %s',
				$next_retry,
				self::MAX_RETRIES,
				$integration_id,
				$contact['email'] ?? 'unknown',
				$backoff_seconds,
				$error_message
			)
		);
	}

	/**
	 * Execute an integration sync retry from ActionScheduler.
	 *
	 * @param array $retry_data The retry data containing integration_id, contact, context, and retry_count.
	 */
	public static function execute_integration_retry( $retry_data ) {
		if ( ! is_array( $retry_data ) || empty( $retry_data['integration_id'] ) || empty( $retry_data['contact'] ) ) {
			Logger::log( 'Invalid integration retry data received from Action Scheduler.', 'NEWSPACK-SYNC', 'error' );
			return;
		}

		$integration_id   = $retry_data['integration_id'];
		$stored_contact   = $retry_data['contact'];
		$context          = $retry_data['context'] ?? static::$context;
		$existing_contact = $retry_data['existing_contact'] ?? null;
		$retry_count      = $retry_data['retry_count'] ?? 1;

		// Fetch fresh contact data to avoid pushing stale state (e.g. outdated subscription status).
		$email = $stored_contact['email'] ?? '';
		$user  = $email ? \get_user_by( 'email', $email ) : false;
		if ( $user ) {
			$contact = self::get_contact_data( $user->ID );
		} else {
			// User deleted — fall back to the stored contact data.
			$contact = $stored_contact;
		}

		$integration = Integrations::get_integration( $integration_id );
		if ( ! $integration ) {
			Logger::log( sprintf( 'Integration "%s" not found on retry %d.', $integration_id, $retry_count ), 'NEWSPACK-SYNC', 'error' );
			return;
		}

		static::log( sprintf( 'Executing retry %d/%d for integration "%s" sync of %s.', $retry_count, self::MAX_RETRIES, $integration_id, $contact['email'] ?? 'unknown' ) );

		$result = $integration->push_contact_data( $contact, $context, $existing_contact );
		if ( \is_wp_error( $result ) ) {
			static::log(
				sprintf(
					'Retry %d failed for integration "%s" sync of %s: %s',
					$retry_count,
					$integration_id,
					$contact['email'] ?? 'unknown',
					$result->get_error_message()
				)
			);
			self::schedule_integration_retry( $integration_id, $contact, $context, $existing_contact, $retry_count, $result->get_error_message() );
			return;
		}

		static::log( sprintf( 'Retry %d succeeded for integration "%s" sync of %s.', $retry_count, $integration_id, $contact['email'] ?? 'unknown' ) );
	}

	/**
	 * Schedule a future sync.
	 *
	 * @param int    $user_id The user ID for the contact to sync.
	 * @param string $context The context of the sync.
	 * @param int    $delay   The delay in seconds.
	 */
	public static function schedule_sync( $user_id, $context, $delay ) {
		// Schedule another sync in $delay number of seconds.
		if ( ! is_int( $delay ) ) {
			return;
		}

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return;
		}

		static::log(
			sprintf(
				// Translators: %s is the email address of the contact to synced.
				__( 'Scheduling secondary sync for contact %s.', 'newspack-plugin' ),
				$user->data->user_email
			),
			[
				'user_email' => $user->data->user_email,
				'user_id'    => $user_id,
				'context'    => $context,
			]
		);
		\wp_schedule_single_event( \time() + $delay, 'newspack_scheduled_esp_sync', [ $user_id, $context ] );
	}

	/**
	 * Handle a scheduled sync event.
	 *
	 * @param int    $user_id The user ID for the contact to sync.
	 * @param string $context The context of the sync.
	 */
	public static function scheduled_sync( $user_id, $context ) {
		$contact = Sync\WooCommerce::get_contact_from_customer( new \WC_Customer( $user_id ) );
		if ( ! $contact ) {
			return;
		}
		self::sync( $contact, $context );
	}

	/**
	 * Get contact data for syncing.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array|\WP_Error The contact data or WP_Error.
	 */
	public static function get_contact_data( $user_id ) {
		if ( ! class_exists( '\WC_Customer' ) ) {
			return new \WP_Error( 'newspack_esp_sync_contact', __( 'WC_Customer class unavailable.', 'newspack-plugin' ) );
		}
		$user = \get_userdata( $user_id );

		if ( ! class_exists( '\WC_Customer' ) ) {
			return new \WP_Error( 'newspack_esp_sync_contact', __( 'WC_Customer class unavailable.', 'newspack-plugin' ) );
		}
		$customer = new \WC_Customer( $user_id );
		if ( ! $customer || ! $customer->get_id() ) {
			return new \WP_Error(
				'newspack_esp_sync_contact',
				sprintf(
				// Translators: %d is the user ID.
					__( 'Customer with ID %d does not exist.', 'newspack-plugin' ),
					$user_id
				)
			);
		}

		// Ensure the customer has a billing address.
		if ( ! $customer->get_billing_email() && $customer->get_email() ) {
			$customer->set_billing_email( $customer->get_email() );
			$customer->save();
		}

		$contact = Sync\WooCommerce::get_contact_from_customer( $customer );

		// Include data from queued syncs too.
		if ( ! empty( self::$queued_syncs[ $contact['email'] ]['contact']['metadata'] ) ) {
			$contact['metadata'] = array_merge( self::$queued_syncs[ $contact['email'] ]['contact']['metadata'], $contact['metadata'] );
		}

		return $contact;
	}

	/**
	 * Given a user ID or WooCommerce Order, sync that reader's contact data to
	 * the connected ESP.
	 *
	 * @param int|\WC_order $user_id_or_order User ID or WC_Order object.
	 * @param string        $context          The context of the sync.
	 * @param bool          $is_dry_run       True if a dry run.
	 *
	 * @return true|\WP_Error True if the contact was synced successfully, WP_Error otherwise.
	 */
	public static function sync_contact( $user_id_or_order, $context = '', $is_dry_run = false ) {
		$can_sync = static::can_sync( true );
		if ( ! $is_dry_run && $can_sync->has_errors() ) {
			return $can_sync;
		}

		$is_order = $user_id_or_order instanceof \WC_Order;
		$order    = $is_order ? $user_id_or_order : false;
		$user_id  = $is_order ? $order->get_customer_id() : $user_id_or_order;

		$contact = $is_order ? Sync\WooCommerce::get_contact_from_order( $order ) : self::get_contact_data( $user_id );
		$result  = $is_dry_run ? true : self::sync( $contact, $context );

		if ( $result && ! \is_wp_error( $result ) ) {
			static::log(
				sprintf(
					// Translators: %1$s is the status and %2$s is the contact's email address.
					__( '%1$s contact data for %2$s.', 'newspack-plugin' ),
					$is_dry_run ? __( 'Would sync', 'newspack-plugin' ) : __( 'Synced', 'newspack-plugin' ),
					$contact['email']
				)
			);
		}

		return $result;
	}

	/**
	 * Run queued syncs.
	 *
	 * @return void
	 */
	public static function run_queued_syncs() {
		if ( empty( self::$queued_syncs ) ) {
			return;
		}

		foreach ( self::$queued_syncs as $email => $queued_sync ) {
			$user = get_user_by( 'email', $email );
			$contact = null;
			if ( $user ) {
				// For existing users, get fresh contact data.
				$contact = self::get_contact_data( $user->ID );
			} else {
				// For deleted users, try to use the queued contact data directly; $user will return nothing.
				$contact = $queued_sync['contact'];
			}
			if ( ! $contact ) {
				continue;
			}
			$contexts = $queued_sync['contexts'];
			self::sync( $contact, implode( '; ', $contexts ) );
		}

		self::$queued_syncs = [];
	}
}
Contact_Sync::init_hooks();
