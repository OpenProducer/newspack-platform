<?php
/**
 * Newspack's Analytics Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use Google\Site_Kit\Modules\Analytics as SiteKitAnalytics;
use Google\Site_Kit\Context;
use Google\Site_Kit\Core\Storage\Options;
use Google\Site_Kit\Core\Storage\User_Options;
use Google\Site_Kit\Core\Authentication\Authentication;
use Google\Site_Kit_Dependencies\Google_Service_Analytics;
use Google\Site_Kit_Dependencies\Google_Service_Analytics_CustomDimension;

use \WP_Error, \WP_Query;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Easy interface for setting up general store info.
 */
class Analytics_Wizard extends Wizard {

	/**
	 * Name of the option storing the article category custom dimension id.
	 *
	 * @var string
	 */
	public static $category_dimension_option_name = 'newspack_analytics_category_custom_dimension_id';

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-analytics-wizard';

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
		parent::__construct();
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
		add_action( 'admin_init', [ $this, 'insert_custom_dimensions' ] );

		// Ensure Site Kit asks for sufficient scopes to add custom dimensions.
		add_filter(
			'googlesitekit_auth_scopes',
			function( array $scopes ) {
				return array_merge( $scopes, [ 'https://www.googleapis.com/auth/analytics.edit' ] );
			},
			1
		);
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return \esc_html__( 'Analytics', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return \esc_html__( 'Track traffic and activity.', 'newspack' );
	}

	/**
	 * Get the duration of this wizard.
	 *
	 * @return string A description of the expected duration (e.g. '10 minutes').
	 */
	public function get_length() {
		return esc_html__( '10 minutes', 'newspack' );
	}

	/**
	 * Create the custom dimension to report category, if:
	 * - the GA property has no custom dimensions set,
	 * - the category dimension option has not been set.
	 */
	public function insert_custom_dimensions() {
		if (
			get_option( 'has_set_up_category_dimension' ) != 'completed' &&
			! get_option( self::$category_dimension_option_name )
		) {
			$custom_dimensions = self::list_custom_dimensions();
			if ( ! is_wp_error( $custom_dimensions ) && count( $custom_dimensions ) === 0 ) {
				$new_custom_dimension = self::create_custom_dimension(
					[
						'name'  => '[Newspack] Article Category',
						'scope' => 'HIT',
					]
				);
				if ( ! is_wp_error( $new_custom_dimension ) ) {
					$new_custom_dimension = self::set_category_dimension(
						[
							'id' => $new_custom_dimension['id'],
						]
					);
					update_option( 'has_set_up_category_dimension', 'completed' );
				}
			}
		}
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		// Create a custom dimension.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/analytics/custom-dimensions',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'create_custom_dimension' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'name'  => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'scope' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);

		// Set the category custom dimension.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/analytics/category-dimension/(?P<id>[\w:]+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'set_category_dimension' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'id' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}

		\wp_enqueue_script(
			'newspack-analytics-wizard',
			Newspack::plugin_url() . '/dist/analytics.js',
			[ 'wp-components', 'wp-api-fetch' ],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/analytics.js' ),
			true
		);
		$custom_dimensions          = self::list_custom_dimensions();
		$analytics_connection_error = null;
		if ( is_wp_error( $custom_dimensions ) ) {
			$analytics_connection_error = $custom_dimensions->get_error_message();
		}
		\wp_localize_script(
			'newspack-analytics-wizard',
			'newspack_analytics_wizard_data',
			[
				'customDimensions'         => $custom_dimensions,
				'analyticsConnectionError' => $analytics_connection_error,
			]
		);

		\wp_register_style(
			'newspack-analytics-wizard',
			Newspack::plugin_url() . '/dist/analytics.css',
			$this->get_style_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/analytics.css' )
		);
		\wp_style_add_data( 'newspack-analytics-wizard', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-analytics-wizard' );

	}

	/**
	 * Get GA utils.
	 *
	 * @return object authenticated Google_Service_Analytics service and Site Kit settings
	 */
	public static function get_ga_utils() {
		if ( defined( 'GOOGLESITEKIT_PLUGIN_MAIN_FILE' ) ) {
			$context            = new Context( GOOGLESITEKIT_PLUGIN_MAIN_FILE );
			$site_kit_analytics = new SiteKitAnalytics( $context );

			if ( $site_kit_analytics->is_connected() ) {
				$ga_options     = new Options( $context );
				$user_options   = new User_Options( $context );
				$authentication = new Authentication( $context, $ga_options, $user_options );

				if ( false === $authentication->is_authenticated() ) {
					return new WP_Error( 'newspack_analytics_sitekit_authentication', __( 'Please authenticate with the Site Kit plugin.', 'newspack' ) );
				}

				// A user might have authenticated with Site Kit before this version of the plugin,
				// which updated authorization scopes, was deployed.
				$unsatisfied_scopes = $authentication->get_oauth_client()->get_unsatisfied_scopes();
				if ( 0 !== count( $unsatisfied_scopes ) ) {
					return new WP_Error(
						'newspack_analytics_sitekit_unsatisfied_scopes',
						__( 'Please re-authorize', 'newspack' ) .
						' <a href="' . get_admin_url() . 'admin.php?page=googlesitekit-dashboard">' .
						__( 'Site Kit plugin', 'newspack' ) .
						'</a> ' .
						__( 'to allow updating Google Analytics settings.', 'newspack' ) 
					);
				}

				$client = $authentication->get_oauth_client()->get_client();

				return [
					'analytics_service' => new Google_Service_Analytics( $client ),
					'settings'          => $site_kit_analytics->get_settings()->get(),
				];
			} else {
				return new WP_Error( 'newspack_analytics_sitekit_disconnected', __( 'Please connect Analytics in the Site Kit plugin.', 'newspack' ) );
			}
		}
		return new WP_Error( 'newspack_analytics_sitekit_undefined', __( 'Please install the Site Kit plugin.', 'newspack' ) );
	}

	/**
	 * List Custom Dimensions.
	 *
	 * @return Array|WP_Error Array of custom dimensions on success, or WP_Error object on failure.
	 */
	public static function list_custom_dimensions() {
		$ga_utils = self::get_ga_utils();
		if ( is_wp_error( $ga_utils ) ) {
			return $ga_utils;
		}
		try {
			$custom_dimensions = $ga_utils['analytics_service']->management_customDimensions->listManagementCustomDimensions(
				$ga_utils['settings']['accountID'],
				$ga_utils['settings']['propertyID']
			);
		} catch ( \Throwable $e ) {
			return new WP_Error( 'newspack_analytics', __( 'Error retrieving custom dimensions.', 'newspack' ) );
		}
		if ( isset( $custom_dimensions['items'] ) ) {
			$option_name = self::$category_dimension_option_name;
			return array_map(
				function ( &$dimension ) use ( $option_name ) {
					if ( get_option( $option_name ) === $dimension['id'] ) {
						$dimension->is_category_dimension = true;
					}
					return $dimension;
				},
				$custom_dimensions['items']
			);
		}
		return new WP_Error( 'newspack_analytics', __( 'Error retrieving custom dimensions.', 'newspack' ) );
	}

	/**
	 * Create a Custom Dimension.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return Object|WP_Error Object on success, or WP_Error object on failure.
	 */
	public static function create_custom_dimension( $request ) {
		$ga_utils = self::get_ga_utils();
		if ( is_wp_error( $ga_utils ) ) {
			return $ga_utils;
		}

		try {
			$custom_dimension_body = new Google_Service_Analytics_CustomDimension();
			$custom_dimension_body->setName( $request['name'] );
			$custom_dimension_body->setScope( $request['scope'] );
			$custom_dimension_body->setActive( true );

			return $ga_utils['analytics_service']->management_customDimensions->insert(
				$ga_utils['settings']['accountID'],
				$ga_utils['settings']['propertyID'],
				$custom_dimension_body
			);
		} catch ( \Throwable $error ) {
			return new WP_Error( 'newspack_analytics', __( 'Error when creating custom dimension.', 'newspack' ) );
		}
	}

	/**
	 * Set custom dimension as the category-reporting dimension.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return Object|WP_Error Object on success, or WP_Error object on failure.
	 */
	public static function set_category_dimension( $request ) {
		$dimension_id          = $request['id'];
		$existing_dimension_id = get_option( self::$category_dimension_option_name );
		if ( $existing_dimension_id === $dimension_id ) {
			$dimension_id = null;
		}
		if ( update_option( self::$category_dimension_option_name, $dimension_id ) ) {
			return [ 'id' => $dimension_id ];
		} else {
			return new WP_Error( 'newspack_analytics', __( 'Error when setting category custom dimension.', 'newspack' ) );
		}
	}
}
