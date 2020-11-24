<?php
/**
 * Newspack Segmentation Plugin
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/../api/segmentation/class-segmentation.php';

/**
 * Main Newspack Segmentation Plugin Class.
 */
final class Newspack_Popups_Segmentation {
	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Popups_Segmentation
	 */
	protected static $instance = null;

	/**
	 * Name of the Client ID, to be used by amp-analytics.
	 */
	const NEWSPACK_SEGMENTATION_CID_NAME = 'newspack-cid';

	/**
	 * Query param that will overwrite the cookie value.
	 */
	const NEWSPACK_SEGMENTATION_CID_LINKER_PARAM = 'ref_newspack_cid';

	/**
	 * Name of the option to store segments under.
	 */
	const SEGMENTS_OPTION_NAME = 'newspack_popups_segments';

	/**
	 * Main Newspack Segmentation Plugin Instance.
	 * Ensures only one instance of Newspack Segmentation Plugin Instance is loaded or can be loaded.
	 *
	 * @return Newspack Segmentation Plugin Instance - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'create_database_table' ] );
		add_action( 'wp_footer', [ __CLASS__, 'insert_amp_analytics' ], 20 );
	}

	/**
	 * Permission callback for the API calls.
	 */
	public static function is_admin_user() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Should tracking code be inserted?
	 */
	public static function is_tracking() {
		if ( is_admin() || self::is_admin_user() || Newspack_Popups_Settings::is_non_interactive() ) {
			return false;
		}
		return true;
	}

	/**
	 * Insert amp-analytics tracking code.
	 * Has to be included on every page to set the cookie.
	 *
	 * This amp-analytics tag will not report any analytics, it's only responsible for settings the cookie
	 * bearing the client ID, as well as handling the linker paramerer when navigating from a proxy site.
	 *
	 * There is a known issue with amp-analytics & amp-access interoperation – more on that at
	 * https://github.com/Automattic/newspack-popups/pull/224#discussion_r496655085.
	 */
	public static function insert_amp_analytics() {
		if ( ! self::is_tracking() ) {
			return;
		}

		$linker_id            = 'cid';
		$amp_analytics_config = [
			// Linker will append a query param to all internal links.
			// This will only be performed on a proxy site (like AMP cache) by default.
			// https://amp.dev/documentation/components/amp-analytics/?format=websites#linkers.
			'linkers' => [
				'enabled' => true,
				self::NEWSPACK_SEGMENTATION_CID_LINKER_PARAM => [
					'ids' => [
						$linker_id => 'CLIENT_ID(' . self::NEWSPACK_SEGMENTATION_CID_NAME . ')',
					],
				],
			],
			// If the linker parameter is found, the cookie value will be overwritten by it.
			// https://amp.dev/documentation/components/amp-analytics/?format=websites#cookies.
			'cookies' => [
				'enabled'                            => true,
				self::NEWSPACK_SEGMENTATION_CID_NAME => [
					'value' => 'LINKER_PARAM(' . self::NEWSPACK_SEGMENTATION_CID_LINKER_PARAM . ', ' . $linker_id . ')',
				],
			],
		];

		// Handle Mailchimp URL parameters.
		if ( isset( $_GET['mc_cid'], $_GET['mc_eid'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$endpoint                         = self::get_client_data_endpoint();
			$amp_analytics_config['requests'] = [
				'event' => esc_url( $endpoint ),
			];
			$amp_analytics_config['triggers']['trackMailchimpData'] = [
				'on'             => 'ini-load',
				'request'        => 'event',
				'extraUrlParams' => [
					'mc_cid'    => sanitize_text_field( $_GET['mc_cid'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					'mc_eid'    => sanitize_text_field( $_GET['mc_eid'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					'client_id' => 'CLIENT_ID(' . esc_attr( self::NEWSPACK_SEGMENTATION_CID_NAME ) . ')',
				],
			];
		}

		?>
			<amp-analytics>
				<script type="application/json">
					<?php echo wp_json_encode( $amp_analytics_config ); ?>
				</script>
			</amp-analytics>
		<?php
	}

	/**
	 * Create the clients and events tables.
	 */
	public static function create_database_table() {
		global $wpdb;
		$events_table_name = Segmentation::get_events_table_name();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $events_table_name ) ) != $events_table_name ) {
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $events_table_name (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				created_at date NOT NULL,
				-- type of event
				type varchar(20) NOT NULL,
				-- Unique id of a device/browser pair
				client_id varchar(100) NOT NULL,
				-- Article ID
				post_id bigint(20),
				-- Article categories IDs
				category_ids varchar(100),
				UNIQUE KEY client_id_post_id (client_id, post_id),
				KEY client_id_type (client_id, type),
				PRIMARY KEY  (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.dbDelta_dbdelta
		}
	}

	/**
	 * Get all configured segments.
	 */
	public static function get_segments() {
		return get_option( self::SEGMENTS_OPTION_NAME, [] );
	}

	/**
	 * Create a segment.
	 *
	 * @param object $segment A segment.
	 */
	public static function create_segment( $segment ) {
		$segments              = self::get_segments();
		$segment['id']         = uniqid();
		$segment['created_at'] = gmdate( 'Y-m-d' );
		$segment['updated_at'] = gmdate( 'Y-m-d' );
		$segments[]            = $segment;

		update_option( self::SEGMENTS_OPTION_NAME, $segments );
		return self::get_segments();
	}

	/**
	 * Delete a segment.
	 *
	 * @param string $id A segment id.
	 */
	public static function delete_segment( $id ) {
		$segments = array_values(
			array_filter(
				self::get_segments(),
				function( $segment ) use ( $id ) {
					return $segment['id'] !== $id;
				}
			)
		);
		update_option( self::SEGMENTS_OPTION_NAME, $segments );
		return self::get_segments();
	}

	/**
	 * Update a segment.
	 *
	 * @param object $segment A segment.
	 */
	public static function update_segment( $segment ) {
		$segments              = self::get_segments();
		$segment['updated_at'] = gmdate( 'Y-m-d' );
		foreach ( $segments as &$_segment ) {
			if ( $_segment['id'] === $segment['id'] ) {
				$_segment['name']          = $segment['name'];
				$_segment['configuration'] = $segment['configuration'];
			}
		}

		update_option( self::SEGMENTS_OPTION_NAME, $segments );
		return self::get_segments();
	}

	/**
	 * Get current client's id.
	 */
	public static function get_client_id() {
		return isset( $_COOKIE[ self::NEWSPACK_SEGMENTATION_CID_NAME ] ) ? esc_attr( $_COOKIE[ self::NEWSPACK_SEGMENTATION_CID_NAME ] ) : false; // phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	}

	/**
	 * Get API client data endpoint.
	 */
	public static function get_client_data_endpoint() {
		return plugins_url( '../api/segmentation/index.php', __FILE__ );
	}

	/**
	 * Update client data.
	 *
	 * @param string $client_id Client ID.
	 * @param string $payload Client data.
	 */
	public static function update_client_data( $client_id, $payload ) {
		if ( isset( $client_id ) ) {
			wp_safe_remote_post(
				self::get_client_data_endpoint(),
				[
					'body' => array_merge(
						[
							'client_id' => $client_id,
						],
						$payload
					),
				]
			);
		}
	}
}
Newspack_Popups_Segmentation::instance();
