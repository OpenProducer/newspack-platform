<?php
/**
 * Newspack's Engagement Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error, \WP_Query;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Easy interface for setting up general store info.
 */
class Engagement_Wizard extends Wizard {

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-engagement-wizard';

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
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return \esc_html__( 'Engagement', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return \esc_html__( 'Newsletters, social, commenting, UGC', 'newspack' );
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
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/engagement',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_engagement_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/popup/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => [ $this, 'api_delete_popup' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'id' => [
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/sitewide-popup/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_set_sitewide_popup' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'id' => [
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/sitewide-popup/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => [ $this, 'api_unset_sitewide_popup' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'id' => [
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/popup-categories/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_set_popup_categories' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'id'         => [
						'sanitize_callback' => 'absint',
					],
					'categories' => [
						'sanitize_callback' => [ $this, 'sanitize_categories' ],
					],
				],
			]
		);
	}

	/**
	 * Get the Jetpack-Mailchimp connection settings.
	 *
	 * @see jetpack/_inc/lib/core-api/wpcom-endpoints/class-wpcom-rest-api-v2-endpoint-mailchimp.php
	 * @return WP_REST_Response with the info.
	 */
	public function api_get_engagement_settings() {
		$jetpack_configuration_manager         = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'jetpack' );
		$wc_configuration_manager              = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
		$newspack_popups_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-popups' );

		$response = array(
			'connected'   => false,
			'connectURL'  => null,
			'wcConnected' => false,
			'popups'      => array(),
		);

		$jetpack_status = $jetpack_configuration_manager->get_mailchimp_connection_status();
		if ( ! is_wp_error( $jetpack_status ) ) {
			$response['connected']   = $jetpack_status['connected'];
			$response['connectURL']  = $jetpack_status['connectURL'];
			$response['wcConnected'] = $wc_configuration_manager->is_active();
		}
		if ( $newspack_popups_configuration_manager->is_configured() ) {
			$response['popups'] = array_map(
				function( $popup ) {
					$popup['edit_link'] = get_edit_post_link( $popup['id'] );
					return $popup;
				},
				$newspack_popups_configuration_manager->get_popups()
			);
		}
		return rest_ensure_response( $response );
	}

	/**
	 * Delete a Pop-up.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response with complete info to render the Engagement Wizard.
	 */
	public function api_delete_popup( $request ) {
		$id = $request['id'];

		$popup = get_post( $id );
		if ( is_a( $popup, 'WP_Post' ) && 'newspack_popups_cpt' === $popup->post_type ) {
			wp_delete_post( $id );
		}

		return $this->api_get_engagement_settings();
	}

	/**
	 * Set the sitewide default Popup
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response with the info.
	 */
	public function api_set_sitewide_popup( $request ) {
		$sitewide_default = $request['id'];

		$newspack_popups_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-popups' );

		$response = $newspack_popups_configuration_manager->set_sitewide_popup( $sitewide_default );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $this->api_get_engagement_settings();
	}

	/**
	 * Unset the sitewide default Popup
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response with the info.
	 */
	public function api_unset_sitewide_popup( $request ) {
		$sitewide_default = $request['id'];

		$newspack_popups_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-popups' );

		$response = $newspack_popups_configuration_manager->unset_sitewide_popup( $sitewide_default );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $this->api_get_engagement_settings();
	}

	/**
	 * Set categories for one Popup.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response with the info.
	 */
	public function api_set_popup_categories( $request ) {
		$id         = $request['id'];
		$categories = $request['categories'];

		$newspack_popups_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-popups' );

		$response = $newspack_popups_configuration_manager->set_popup_categories( $id, $categories );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $this->api_get_engagement_settings();
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
			'newspack-engagement-wizard',
			Newspack::plugin_url() . '/dist/engagement.js',
			$this->get_script_dependencies( array( 'wp-html-entities' ) ),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/engagement.js' ),
			true
		);

		\wp_register_style(
			'newspack-engagement-wizard',
			Newspack::plugin_url() . '/dist/engagement.css',
			$this->get_style_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/engagement.css' )
		);
		\wp_style_add_data( 'newspack-engagement-wizard', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-engagement-wizard' );
	}

	/**
	 * Sanitize array of categories.
	 *
	 * @param array $categories Array of categories to sanitize.
	 * @return array Sanitized array.
	 */
	public static function sanitize_categories( $categories ) {
		$categories = is_array( $categories ) ? $categories : [];
		$sanitized  = [];
		foreach ( $categories as $category ) {
			$category['id']   = isset( $category['id'] ) ? absint( $category['id'] ) : null;
			$category['name'] = isset( $category['name'] ) ? sanitize_title( $category['name'] ) : null;
			$sanitized[]      = $category;
		}
		return $sanitized;
	}
}
