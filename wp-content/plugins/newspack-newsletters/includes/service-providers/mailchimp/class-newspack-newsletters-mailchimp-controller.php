<?php
/**
 * Mailchimp ESP Service Controller.
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

use \DrewM\MailChimp\MailChimp;

/**
 * API Controller for Newspack Mailchimp ESP service.
 */
class Newspack_Newsletters_Mailchimp_Controller extends Newspack_Newsletters_Service_Provider_Controller {
	/**
	 * Newspack_Newsletters_Mailchimp_Controller constructor.
	 *
	 * @param \Newspack_Newsletters_Mailchimp $mailchimp The service provider class.
	 */
	public function __construct( $mailchimp ) {
		$this->service_provider = $mailchimp;
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
		parent::__construct( $mailchimp );
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'mc_campaign_id',
			[
				'object_subtype' => Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => false,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'mc_list_id',
			[
				'object_subtype' => Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
	}

	/**
	 * Register API endpoints unique to Mailchimp.
	 */
	public function register_routes() {

		// Register common ESP routes from \Newspack_Newsletters_Service_Provider_Controller::register_routes.
		parent::register_routes();

		\register_rest_route(
			$this->service_provider::BASE_NAMESPACE . $this->service_provider->service,
			'(?P<id>[\a-z]+)',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_retrieve' ],
				'permission_callback' => [ $this->service_provider, 'api_authoring_permissions_check' ],
				'args'                => [
					'id' => [
						'sanitize_callback' => 'absint',
						'validate_callback' => [ 'Newspack_Newsletters', 'validate_newsletter_id' ],
					],
				],
			]
		);
		\register_rest_route(
			$this->service_provider::BASE_NAMESPACE . $this->service_provider->service,
			'(?P<id>[\a-z]+)/test',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_test' ],
				'permission_callback' => [ $this->service_provider, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'         => [
						'sanitize_callback' => 'absint',
						'validate_callback' => [ 'Newspack_Newsletters', 'validate_newsletter_id' ],
					],
					'test_email' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
		\register_rest_route(
			$this->service_provider::BASE_NAMESPACE . $this->service_provider->service,
			'(?P<id>[\a-z]+)/sender',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_sender' ],
				'permission_callback' => [ $this->service_provider, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'        => [
						'sanitize_callback' => 'absint',
						'validate_callback' => [ 'Newspack_Newsletters', 'validate_newsletter_id' ],
					],
					'from_name' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'reply_to'  => [
						'sanitize_callback' => 'sanitize_email',
					],
				],
			]
		);
		\register_rest_route(
			$this->service_provider::BASE_NAMESPACE . $this->service_provider->service,
			'(?P<id>[\a-z]+)/folder',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_folder' ],
				'permission_callback' => [ $this->service_provider, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'        => [
						'sanitize_callback' => 'absint',
						'validate_callback' => [ 'Newspack_Newsletters', 'validate_newsletter_id' ],
					],
					'list_id'   => [
						'sanitize_callback' => 'esc_attr',
					],
					'folder_id' => [
						'sanitize_callback' => 'esc_attr',
					],
				],
			]
		);
		\register_rest_route(
			$this->service_provider::BASE_NAMESPACE . $this->service_provider->service,
			'(?P<id>[\a-z]+)/list/(?P<list_id>[\a-z]+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_list' ],
				'permission_callback' => [ $this->service_provider, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'      => [
						'sanitize_callback' => 'absint',
						'validate_callback' => [ 'Newspack_Newsletters', 'validate_newsletter_id' ],
					],
					'list_id' => [
						'sanitize_callback' => 'esc_attr',
					],
				],
			]
		);
		\register_rest_route(
			$this->service_provider::BASE_NAMESPACE . $this->service_provider->service,
			'(?P<id>[\a-z]+)/segments',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_segments' ],
				'permission_callback' => [ $this->service_provider, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'        => [
						'sanitize_callback' => 'absint',
						'validate_callback' => [ 'Newspack_Newsletters', 'validate_newsletter_id' ],
					],
					'target_id' => [
						'sanitize_callback' => 'esc_attr',
					],
				],
			]
		);
	}

	/**
	 * Get campaign data.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return WP_REST_Response|mixed API response or error.
	 */
	public function api_retrieve( $request ) {
		$response = $this->service_provider->retrieve( $request['id'] );
		return self::get_api_response( $response );
	}

	/**
	 * Test campaign.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return WP_REST_Response|mixed API response or error.
	 */
	public function api_test( $request ) {
		$emails = explode( ',', $request['test_email'] );
		foreach ( $emails as &$email ) {
			$email = sanitize_email( trim( $email ) );
		}
		$this->update_user_test_emails( $emails );
		$response = $this->service_provider->test(
			$request['id'],
			$emails
		);
		return self::get_api_response( $response );
	}

	/**
	 * Set the sender name and email for the campaign.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return WP_REST_Response|mixed API response or error.
	 */
	public function api_sender( $request ) {
		$response = $this->service_provider->sender(
			$request['id'],
			$request['from_name'],
			$request['reply_to']
		);
		return self::get_api_response( $response );
	}

	/**
	 * Set folder for a campaign.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return WP_REST_Response|mixed API response or error.
	 */
	public function api_folder( $request ) {
		$response = $this->service_provider->folder(
			$request['id'],
			$request['folder_id']
		);
		return self::get_api_response( $response );
	}

	/**
	 * Set list for a campaign.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return WP_REST_Response|mixed API response or error.
	 */
	public function api_list( $request ) {
		$response = $this->service_provider->list(
			$request['id'],
			$request['list_id']
		);
		return self::get_api_response( $response );
	}

	/**
	 * Set Mailchimp audience segments for a campaign.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return WP_REST_Response|mixed API response or error.
	 */
	public function api_segments( $request ) {
		$response = $this->service_provider->audience_segments(
			$request['id'],
			$request['target_id']
		);
		return self::get_api_response( $response );
	}
}
