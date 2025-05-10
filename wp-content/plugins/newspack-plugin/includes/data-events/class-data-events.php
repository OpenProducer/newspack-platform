<?php
/**
 * Newspack Data Events.
 *
 * @package Newspack
 */

namespace Newspack;

use WP_Error;

/**
 * Main class.
 */
final class Data_Events {
	/**
	 * Asynchronous action name.
	 */
	const ACTION = 'newspack_data_event';

	/**
	 * Header to be used while logging.
	 */
	const LOGGER_HEADER = 'NEWSPACK-DATA-EVENTS';

	/**
	 * Option name for storing the nonce.
	 */
	const NONCE_OPTION = 'newspack_data_events_nonce';

	/**
	 * Option name for storing the nonce expiration.
	 */
	const NONCE_EXPIRATION_OPTION = 'newspack_data_events_nonce_expiration';

	/**
	 * Nonce lifetime in seconds (1 hour).
	 */
	const NONCE_LIFETIME = 3600; // 1 hour in seconds

	/**
	 * Grace period in seconds for accepting old nonces.
	 */
	const NONCE_GRACE_PERIOD = 10; // 10 seconds

	/**
	 * Option name for storing the previous nonce.
	 */
	const PREVIOUS_NONCE_OPTION = 'newspack_data_events_previous_nonce';

	/**
	 * Option name for storing the previous nonce expiration.
	 */
	const PREVIOUS_NONCE_EXPIRATION_OPTION = 'newspack_data_events_previous_nonce_expiration';

	/**
	 * Registered callable handlers, keyed by their action name.
	 *
	 * @var callable[]
	 */
	private static $actions = [];

	/**
	 * Registered global callable handlers to be executed on all actions.
	 *
	 * @var callable[]
	 */
	private static $global_handlers = [];

	/**
	 * Dispatches queued for execution on shutdown.
	 *
	 * @var array[]
	 */
	private static $queued_dispatches = [];

	/**
	 * Current action event.
	 *
	 * @var string|null
	 */
	private static $current_event = null;

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		\add_action( 'wp_ajax_' . self::ACTION, [ __CLASS__, 'maybe_handle' ] );
		\add_action( 'wp_ajax_nopriv_' . self::ACTION, [ __CLASS__, 'maybe_handle' ] );
		\add_action( 'shutdown', [ __CLASS__, 'execute_queued_dispatches' ] );
	}

	/**
	 * Get the current nonce for data events.
	 *
	 * We use a custom nonce implementation to avoid issues with user authentication.
	 * In some cases, and in some sites, the nonce is created for a user ID but the dispatched request does not have a user ID.
	 * This implementation generates a nonce that is not tied to a user ID, making it more reliable.
	 *
	 * @return string The nonce.
	 */
	public static function get_nonce() {
		$nonce = \get_option( self::NONCE_OPTION, '' );
		$expiration = \get_option( self::NONCE_EXPIRATION_OPTION, 0 );
		$current_time = time();

		// If nonce is empty or expired, generate a new one.
		if ( empty( $nonce ) || $current_time > $expiration ) {
			// Store the current nonce as previous before generating a new one.
			if ( ! empty( $nonce ) ) {
				\update_option( self::PREVIOUS_NONCE_OPTION, $nonce );
				\update_option( self::PREVIOUS_NONCE_EXPIRATION_OPTION, $current_time + self::NONCE_GRACE_PERIOD );
			}

			$nonce = self::generate_nonce();
			$expiration = $current_time + self::NONCE_LIFETIME;

			\update_option( self::NONCE_OPTION, $nonce );
			\update_option( self::NONCE_EXPIRATION_OPTION, $expiration );
		}

		return $nonce;
	}

	/**
	 * Generate a random nonce that's safe for use in URLs.
	 *
	 * @return string URL-safe random nonce.
	 */
	private static function generate_nonce() {
		// Generate password with only alphanumeric characters.
		return \wp_generate_password( 32, false, false );
	}

	/**
	 * Verify the provided nonce.
	 *
	 * @param string $nonce The nonce to verify.
	 * @return bool Whether the nonce is valid.
	 */
	public static function verify_nonce( $nonce ) {
		// Check against current nonce.
		$current_nonce = \get_option( self::NONCE_OPTION, '' );
		if ( $current_nonce === $nonce ) {
			return true;
		}

		// If current nonce doesn't match, check against previous nonce if within grace period.
		$previous_nonce = \get_option( self::PREVIOUS_NONCE_OPTION, '' );
		$previous_expiration = \get_option( self::PREVIOUS_NONCE_EXPIRATION_OPTION, 0 );

		if ( $previous_nonce === $nonce && time() <= $previous_expiration ) {
			return true;
		}

		return false;
	}

	/**
	 * Maybe handle an event.
	 */
	public static function maybe_handle() {
		// Don't lock up other requests while processing.
		session_write_close(); // phpcs:ignore

		if ( ! isset( $_REQUEST['nonce'] ) || ! self::verify_nonce( \sanitize_text_field( $_REQUEST['nonce'] ) ) ) { // phpcs:ignore
			\wp_die();
		}

		$dispatches = isset( $_POST['dispatches'] ) ? $_POST['dispatches'] : null; // phpcs:ignore

		if ( empty( $dispatches ) || ! is_array( $dispatches ) ) {
			\wp_die();
		}

		foreach ( $dispatches as $dispatch ) {
			$action_name = isset( $dispatch['action_name'] ) ? \sanitize_text_field( $dispatch['action_name'] ) : null;
			if ( empty( $action_name ) || ! isset( self::$actions[ $action_name ] ) ) {
				continue;
			}

			$timestamp = isset( $dispatch['timestamp'] ) ? \sanitize_text_field( $dispatch['timestamp'] ) : null;
			$data      = isset( $dispatch['data'] ) ? $dispatch['data'] : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$client_id = isset( $dispatch['client_id'] ) ? \sanitize_text_field( $dispatch['client_id'] ) : null;

			self::handle( $action_name, $timestamp, $data, $client_id );
		}

		\wp_die();
	}

	/**
	 * Handle an event.
	 *
	 * @param string $action_name Action name.
	 * @param int    $timestamp   Timestamp.
	 * @param array  $data        Data.
	 * @param string $client_id   Client ID.
	 */
	public static function handle( $action_name, $timestamp, $data, $client_id ) {
		// Set current event.
		self::set_current_event( $action_name );

		// Execute global handlers.
		Logger::log(
			sprintf( 'Executing global action handlers for "%s".', $action_name ),
			self::LOGGER_HEADER
		);
		foreach ( self::$global_handlers as $handler ) {
			try {
				call_user_func( $handler, $action_name, $timestamp, $data, $client_id );
			} catch ( \Throwable $e ) {
				// Catch fatal errors so it doesn't disrupt other handlers.
				Logger::error( $e->getMessage(), self::LOGGER_HEADER );
			}
		}

		// Execute action handlers.
		Logger::log(
			sprintf( 'Executing action handlers for "%s".', $action_name ),
			self::LOGGER_HEADER
		);
		$handlers = self::get_action_handlers( $action_name );
		foreach ( $handlers as $handler ) {
			try {
				call_user_func( $handler, $timestamp, $data, $client_id );
			} catch ( \Throwable $e ) {
				// Catch fatal errors so it doesn't disrupt other handlers.
				Logger::error( $e->getMessage(), self::LOGGER_HEADER );
			}
		}

		/**
		 * Fires after all global and action-specific handlers have been executed.
		 *
		 * The dynamic portion of the hook name, `$action_name`, refers to the name
		 * of the action being fired.
		 *
		 * @param int    $timestamp   Timestamp.
		 * @param array  $data        Data.
		 * @param string $client_id   Client ID.
		 */
		\do_action( "newspack_data_event_{$action_name}", $timestamp, $data, $client_id );

		/**
		 * Fires after all global and action-specific handlers have been executed.
		 *
		 * @param string $action_name Action name.
		 * @param int    $timestamp   Timestamp.
		 * @param array  $data        Data.
		 * @param string $client_id   Client ID.
		 */
		\do_action( 'newspack_data_event', $action_name, $timestamp, $data, $client_id );

		// Unset current event.
		self::set_current_event( null );
	}

	/**
	 * Get the current event being handled.
	 *
	 * @return string|null Current event.
	 */
	public static function current_event() {
		return self::$current_event;
	}

	/**
	 * Set the current event being handled.
	 *
	 * @param string|null $name Event name.
	 */
	private static function set_current_event( $name ) {
		self::$current_event = $name;
	}

	/**
	 * Register a triggerable action.
	 *
	 * @param string $action_name Action name.
	 *
	 * @return void|WP_Error Error if action already registered.
	 */
	public static function register_action( $action_name ) {
		if ( isset( self::$actions[ $action_name ] ) ) {
			return new WP_Error( 'action_already_registered', __( 'Action already registered.', 'newspack' ) );
		}
		self::$actions[ $action_name ] = [];
	}

	/**
	 * Register a handler for a triggerable action.
	 *
	 * @param callable $handler     Action handler.
	 * @param string   $action_name Action name.
	 *
	 * @return void|WP_Error Error if action not registered, handler already registered or is not callable.
	 */
	public static function register_handler( $handler, $action_name = null ) {
		/** If there's no action name, treat as a global handler. */
		if ( empty( $action_name ) ) {
			self::$global_handlers[] = $handler;
			return;
		}

		$error = false;

		if ( ! isset( self::$actions[ $action_name ] ) ) {
			$error = new WP_Error( 'action_not_registered', __( 'Action not registered.', 'newspack' ) );
		}
		if ( ! is_callable( $handler ) ) {
			$error = new WP_Error( 'handler_not_callable', __( 'Handler is not callable.', 'newspack' ) );
		}

		if ( $error ) {

			Logger::log(
				sprintf(
					'ATTENTION: Data Event handler for action "%s" was not properly registered: %s',
					$action_name,
					$error->get_error_message()
				),
				'DATA EVENTS'
			);

			return $error;
		}

		if ( in_array( $handler, self::$actions[ $action_name ], true ) ) {
			return new WP_Error( 'handler_already_registered', __( 'Handler already registered.', 'newspack' ) );
		}

		self::$actions[ $action_name ][] = $handler;
	}

	/**
	 * Register a listener so it dispatches an action when a WordPress hook is
	 * fired.
	 *
	 * @param string         $hook_name   WordPress hook name.
	 * @param string         $action_name Action name.
	 * @param callable|array $callable    Optional callable to filter the data
	 *                                    passed to dispatch or an array of
	 *                                    strings to map argument names.
	 */
	public static function register_listener( $hook_name, $action_name, $callable = null ) {
		self::register_action( $action_name );
		\add_action(
			$hook_name,
			function() use ( $action_name, $callable ) {
				$args = func_get_args();
				if ( is_callable( $callable ) ) {
					$data = call_user_func_array( $callable, $args );
				} elseif ( is_array( $callable ) ) {
					$data = [];
					foreach ( $callable as $i => $key ) {
						if ( isset( $args[ $i ] ) ) {
							$data[ $key ] = $args[ $i ];
						}
					}
				} else {
					$data = $args;
				}
				if ( ! empty( $data ) ) {
					self::dispatch( $action_name, $data );
				}
				return $args[0];
			},
			PHP_INT_MAX, // We want dispatches to be executed last so that any modified data is available.
			PHP_INT_MAX // The handler should receive all arguments of a hook.
		);
	}

	/**
	 * Get a list of all registered actions.
	 *
	 * @return string[] Registered actions.
	 */
	public static function get_actions() {
		return array_keys( self::$actions );
	}

	/**
	 * Get a list of all registered action handlers.
	 *
	 * @param string $action_name Action name.
	 *
	 * @return callable[] Registered action handlers.
	 */
	public static function get_action_handlers( $action_name ) {
		if ( ! isset( self::$actions[ $action_name ] ) ) {
			return [];
		}
		return self::$actions[ $action_name ];
	}

	/**
	 * Whether an action is registered.
	 *
	 * @param string $action_name The action name.
	 *
	 * @return bool
	 */
	public static function is_action_registered( $action_name ) {
		return isset( self::$actions[ $action_name ] );
	}

	/**
	 * Dispatch an action event.
	 *
	 * @param string  $action_name   Action name.
	 * @param array   $data          Data to pass to the action.
	 * @param boolean $use_client_id Whether to use the session's client ID. Default is true.
	 *
	 * @return void|WP_Error Error if action not registered.
	 */
	public static function dispatch( $action_name, $data, $use_client_id = true ) {
		if ( ! self::is_action_registered( $action_name ) ) {
			return new WP_Error( 'newspack_data_events_action_not_registered', __( 'Action not registered.', 'newspack' ) );
		}

		$timestamp = time();
		$client_id = null;
		if ( $use_client_id ) {
			$client_id = Reader_Activation::get_client_id();
		}

		/**
		 * Fires when an action is dispatched. This occurs before any handlers are
		 * executed.
		 *
		 * The dynamic portion of the hook name, `$action_name`, refers to the name
		 * of the action being fired.
		 *
		 * @param string $action_name Action name.
		 * @param int    $timestamp   Timestamp.
		 * @param array  $data        Data.
		 * @param string $client_id   Client ID.
		 */
		\do_action( "newspack_data_event_dispatch_{$action_name}", $timestamp, $data, $client_id );

		/**
		 * Fires when an action is dispatched. This occurs before any handlers are
		 * executed.
		 *
		 * @param string $action_name Action name.
		 * @param int    $timestamp   Timestamp.
		 * @param array  $data        Data.
		 * @param string $client_id   Client ID.
		 */
		\do_action( 'newspack_data_event_dispatch', $action_name, $timestamp, $data, $client_id );

		$body = [
			'action_name' => $action_name,
			'timestamp'   => $timestamp,
			'data'        => $data,
			'client_id'   => $client_id,
		];

		/**
		 * Filters the body of the action dispatch request. Return a WP_Error if you want to cancel the dispatch.
		 *
		 * @param array  $body        Body.
		 * @param string $action_name The action name.
		 */
		$body = apply_filters( 'newspack_data_events_dispatch_body', $body, $action_name );

		if ( is_wp_error( $body ) ) {
			Logger::log(
				sprintf( 'Error dispatching action "%s": %s', $action_name, $body->get_error_message() ),
				self::LOGGER_HEADER
			);
			return $body;
		}

		self::$queued_dispatches[] = $body;

		// If we're in shutdown, execute the dispatches immediately.
		if ( did_action( 'shutdown' ) ) {
			self::execute_queued_dispatches();
		}
	}

	/**
	 * Execute queued dispatches.
	 */
	public static function execute_queued_dispatches() {
		if ( empty( self::$queued_dispatches ) ) {
			return;
		}

		$actions = array_column( self::$queued_dispatches, 'action_name' );

		Logger::log(
			sprintf( 'Dispatching actions: "%s".', implode( ', ', $actions ) ),
			self::LOGGER_HEADER
		);

		$url = \add_query_arg(
			[
				'action' => self::ACTION,
				'nonce'  => self::get_nonce(),
			],
			\admin_url( 'admin-ajax.php' )
		);

		$request = \wp_remote_post(
			$url,
			[
				'timeout'   => 0.01,
				'blocking'  => false,
				'body'      => [ 'dispatches' => self::$queued_dispatches ],
				'cookies'   => $_COOKIE, // phpcs:ignore
				'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
			]
		);

		/**
		 * Fires after dispatching queued actions.
		 *
		 * @param WP_Error|WP_HTTP_Response $request           The request object.
		 * @param array                     $queued_dispatches The queued dispatches.
		 */
		\do_action( 'newspack_data_events_dispatched', $request, self::$queued_dispatches );

		// Clear the queue in case of a retry.
		self::$queued_dispatches = [];
	}
}
Data_Events::init();
