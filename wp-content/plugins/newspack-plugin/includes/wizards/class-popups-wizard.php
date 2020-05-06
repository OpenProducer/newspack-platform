<?php
/**
 * Newspack Pop-ups Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error, \WP_Query;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Interface for managing Pop-ups.
 */
class Popups_Wizard extends Wizard {

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-popups-wizard';

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
		return \esc_html__( 'Campaigns', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return \esc_html__( 'Reach your readers with configurable campaigns.', 'newspack' );
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
			'/wizard/' . $this->slug,
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_popup' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'id'      => [
						'sanitize_callback' => 'absint',
					],
					'options' => [
						'validate_callback' => [ $this, 'api_validate_options' ],
					],
				],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/(?P<id>\d+)',
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
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/(?P<id>\d+)/publish',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_publish_popup' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'id'      => [
						'sanitize_callback' => 'absint',
					],
					'options' => [
						'validate_callback' => [ $this, 'api_validate_options' ],
					],
				],
			]
		);
	}

	/**
	 * Enqueue Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}

		\wp_enqueue_script(
			'newspack-popups-wizard',
			Newspack::plugin_url() . '/dist/popups.js',
			$this->get_script_dependencies( [ 'wp-html-entities' ] ),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/popups.js' ),
			true
		);

		$recent_posts = wp_get_recent_posts(
			[
				'numberposts' => 1,
				'post_status' => 'publish',
			],
			OBJECT
		);

		$preview_post = count( $recent_posts ) > 0 ? get_the_permalink( $recent_posts[0] ) : '';

		\wp_localize_script(
			'newspack-popups-wizard',
			'newspack_popups_wizard_data',
			[
				'preview_post' => $preview_post,
			]
		);

		\wp_register_style(
			'newspack-popups-wizard',
			Newspack::plugin_url() . '/dist/popups.css',
			$this->get_style_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/popups.css' )
		);
		\wp_style_add_data( 'newspack-popups-wizard', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-popups-wizard' );
	}

	/**
	 * API endpoint callbacks
	 */

	/**
	 * Get data to render Wizard.
	 *
	 * @return WP_REST_Response
	 */
	public function api_get_settings() {
		$newspack_popups_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-popups' );

		$response = [
			'popups' => [],
		];

		if ( $newspack_popups_configuration_manager->is_configured() ) {
			$response['popups'] = array_map(
				function( $popup ) {
					$popup['edit_link'] = get_edit_post_link( $popup['id'] );
					return $popup;
				},
				$newspack_popups_configuration_manager->get_popups( true )
			);
		}
		return rest_ensure_response( $response );
	}

	/**
	 * Set the sitewide default Pop-up
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

		return $this->api_get_settings();
	}

	/**
	 * Unset the sitewide default Pop-up
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

		return $this->api_get_settings();
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

		return $this->api_get_settings();
	}

	/**
	 * Update settings for a Pop-up.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response with the info.
	 */
	public function api_update_popup( $request ) {
		$id      = $request['id'];
		$options = $request['options'];

		$newspack_popups_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-popups' );

		$response = $newspack_popups_configuration_manager->set_popup_options( $id, $options );
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return $this->api_get_settings();
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

		return $this->api_get_settings();
	}

	/**
	 * Publish a Pop-up.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response with complete info to render the Engagement Wizard.
	 */
	public function api_publish_popup( $request ) {
		$id = $request['id'];

		$popup = get_post( $id );
		if ( is_a( $popup, 'WP_Post' ) && 'newspack_popups_cpt' === $popup->post_type ) {
			wp_publish_post( $id );
		}
		return $this->api_get_settings();
	}

	/**
	 * API sanitization and validation functions
	 */

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

	/**
	 * Validate Pop-up option updates.
	 *
	 * @param array $options Array of options to validate.
	 */
	public static function api_validate_options( $options ) {
		foreach ( $options as $key => $value ) {
			switch ( $key ) {
				case 'frequency':
					if ( ! in_array( $value, [ 'test', 'never', 'once', 'daily', 'always' ] ) ) {
						return false;
					}
					break;
				case 'placement':
					if ( ! in_array( $value, [ 'center', 'top', 'bottom', 'inline' ] ) ) {
						return false;
					}
					break;
				default:
					return false;
			}
		}
		return true;
	}
}
