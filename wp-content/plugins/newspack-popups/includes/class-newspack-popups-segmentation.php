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

		add_filter( 'newspack_custom_dimensions', [ __CLASS__, 'register_custom_dimensions' ] );

		// Sending pageviews with segmentation-related custom dimensions.
		// 1. Disable pageview sending from Site Kit's GTAG implementation. The custom events sent using Site Kit's
		// GTAG will not contain the segmentation-related custom dimensions.
		add_filter( 'googlesitekit_gtag_opt', [ __CLASS__, 'remove_pageview_reporting' ] );
		add_filter( 'googlesitekit_amp_gtag_opt', [ __CLASS__, 'remove_pageview_reporting_amp' ] );
		// 2. Add an amp-analytics tag which will send the PV with custom dimensions attached.
		add_action( 'wp_footer', [ __CLASS__, 'insert_gtag_amp_analytics' ] );

		add_action( 'newspack_popups_segmentation_data_prune', [ __CLASS__, 'prune_data' ] );
		if ( ! wp_next_scheduled( 'newspack_popups_segmentation_data_prune' ) ) {
			wp_schedule_event( time(), 'daily', 'newspack_popups_segmentation_data_prune' );
		}
	}

	/**
	 * Remove pageview reporting from non-AMP Analytics GTAG config.
	 *
	 * @param array $gtag_amp GTAG Analytics config.
	 */
	public static function remove_pageview_reporting( $gtag_amp ) {
		$gtag_opt['send_page_view'] = false;
		return $gtag_opt;
	}

	/**
	 * Remove pageview reporting from AMP Analytics GTAG config.
	 *
	 * @param array $gtag_opt AMP Analytics GTAG config.
	 */
	public static function remove_pageview_reporting_amp( $gtag_opt ) {
		$tracking_id = $gtag_opt['vars']['gtag_id'];
		$gtag_opt['vars']['config'][ $tracking_id ]['send_page_view'] = false;
		return $gtag_opt;
	}

	/**
	 * Add custom custom dimensions to Newspack Plugin's Analytics Wizard.
	 *
	 * @param array $default_dimensions Default custom dimensions.
	 */
	public static function register_custom_dimensions( $default_dimensions ) {
		$default_dimensions = array_merge(
			$default_dimensions,
			[
				[
					'role'   => Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_READER_FREQUENCY,
					'option' => [
						'value' => Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_READER_FREQUENCY,
						'label' => __( 'Reader frequency', 'newspack' ),
					],
				],
				[
					'role'   => Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_IS_SUBSCRIBER,
					'option' => [
						'value' => Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_IS_SUBSCRIBER,
						'label' => __( 'Is a subcriber', 'newspack' ),
					],
				],
				[
					'role'   => Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_IS_DONOR,
					'option' => [
						'value' => Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_IS_DONOR,
						'label' => __( 'Is a donor', 'newspack' ),
					],
				],
			]
		);
		return $default_dimensions;
	}


	/**
	 * Get GA property ID from Site Kit's options.
	 */
	public static function get_ga_property_id() {
		$ga_settings = get_option( 'googlesitekit_analytics_settings' );
		if ( $ga_settings && isset( $ga_settings['propertyID'] ) ) {
			return $ga_settings['propertyID'];
		}
	}

	/**
	 * Inset GTAG amp-analytics with a remote config, which will insert segmentation-related custom dimensions.
	 */
	public static function insert_gtag_amp_analytics() {

		$custom_dimensions = [];
		if ( class_exists( 'Newspack\Analytics_Wizard' ) ) {
			$custom_dimensions = Newspack\Analytics_Wizard::list_configured_custom_dimensions();
		}
		$custom_dimensions_existing_values = [];
		if ( class_exists( 'Newspack\Analytics' ) ) {
			$custom_dimensions_existing_values = Newspack\Analytics::get_custom_dimensions_values( get_the_ID() );
		}

		$remote_config_url = add_query_arg(
			[
				'client_id'                         => 'CLIENT_ID(' . esc_attr( self::NEWSPACK_SEGMENTATION_CID_NAME ) . ')',
				'post_id'                           => esc_attr( get_the_ID() ),
				'custom_dimensions'                 => wp_json_encode( $custom_dimensions ),
				'custom_dimensions_existing_values' => wp_json_encode( $custom_dimensions_existing_values ),
			],
			plugins_url( '../api/segmentation/index.php', __FILE__ )
		);

		?>
			<amp-analytics
				type="gtag"
				config="<?php echo esc_attr( $remote_config_url ); ?>"
			></amp-analytics>
		<?php
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
		if ( Newspack_Popups_View_As::viewing_as_spec() ) {
			return true;
		}
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

		$client_data_endpoint             = self::get_client_data_endpoint();
		$amp_analytics_config['requests'] = [
			'event' => esc_url( $client_data_endpoint ),
		];

		// Handle Mailchimp URL parameters.
		if ( isset( $_GET['mc_cid'], $_GET['mc_eid'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
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

		$donor_landing_page = Newspack_Popups_Settings::donor_landing_page();
		if ( $donor_landing_page && intval( $donor_landing_page ) === get_queried_object_id() ) {
			$amp_analytics_config['triggers']['reportDonor'] = [
				'on'             => 'ini-load',
				'request'        => 'event',
				'extraUrlParams' => [
					'donation'  => wp_json_encode(
						[
							'offsite_has_donated' => true,
						]
					),
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
				created_at datetime NOT NULL,
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
		} elseif ( 'date' === $wpdb->get_var( $wpdb->prepare( "SHOW COLUMNS FROM {$events_table_name} LIKE %s", 'created_at' ), 1 ) ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->query( "ALTER TABLE {$events_table_name} CHANGE `created_at` `created_at` DATETIME NOT NULL" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
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

	/**
	 * Get segment reach, based on recorded client data.
	 *
	 * @param object $segment_config Segment configuration.
	 * @return object Total clients amount and the amount covered by the segment.
	 */
	public static function get_segment_reach( $segment_config ) {
		require_once dirname( __FILE__ ) . '/../api/campaigns/class-campaign-data-utils.php';
		require_once dirname( __FILE__ ) . '/../api/classes/class-lightweight-api.php';

		$all_client_data = wp_cache_get( 'newspack_popups_all_clients_data', 'newspack-popups' );
		if ( false === $all_client_data ) {
			$api             = new Lightweight_API();
			$all_client_data = $api->get_all_clients_data();
			wp_cache_set( 'newspack_popups_all_clients_data', $all_client_data, 'newspack-popups' );
		}

		$client_in_segment = array_filter(
			$all_client_data,
			function ( $client_data ) use ( $segment_config ) {
				return Campaign_Data_Utils::should_display_campaign(
					$segment_config,
					$client_data
				);
			}
		);

		return [
			'total'      => count( $all_client_data ),
			'in_segment' => count( $client_in_segment ),
		];
	}

	/**
	 * Only last month's worth of posts-read data is needed for segmentation features.
	 */
	public static function prune_data() {
		global $wpdb;
		$events_table_name         = Segmentation::get_events_table_name();
		$removed_rows_count_events = $wpdb->query( $wpdb->prepare( "DELETE FROM $events_table_name WHERE type = %s AND created_at < now() - interval 30 DAY", 'post_read' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
		error_log( 'Newspack Campaigns: Data pruning – removed ' . $removed_rows_count_events . ' rows from ' . $events_table_name . ' table.' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}
Newspack_Popups_Segmentation::instance();
