<?php // phpcs:disable Squiz.Commenting.FileComment.Missing
/**
 * WP_REST_Newspack_Donate_Controller file.
 *
 * @package WordPress
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound

/**
 * Class WP_REST_Newspack_Donate_Controller.
 */
class WP_REST_Newspack_Donate_Controller extends WP_REST_Controller {
// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound

	private static $current_user_id = 0; // phpcs:ignore Squiz.Commenting.VariableComment.Missing

	/**
	 * Constructs the controller.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->namespace = 'newspack-blocks/v1';
		$this->rest_base = 'donate';
	}

	/**
	 * Registers the necessary REST API routes.
	 *
	 * @access public
	 */
	public function register_routes() {
		self::$current_user_id = get_current_user_id();

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'api_process_donation' ],
					'args'                => [
						'tokenData'         => [
							'type'       => 'object',
							'properties' => [
								'id' => [
									'type'              => 'string',
									'sanitize_callback' => 'sanitize_text_field',
									'required'          => true,
								],
							],
						],
						'amount'            => [
							'sanitize_callback' => function ( $amount ) {
								return (float) abs( $amount );
							},
							'required'          => true,
						],
						'frequency'         => [
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						],
						'email'             => [
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						],
						'full_name'         => [
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						],
						'clientId'          => [
							'sanitize_callback' => 'sanitize_text_field',
						],
						'newsletter_opt_in' => [
							'sanitize_callback' => 'rest_sanitize_boolean',
						],
						'agree_to_pay_fees' => [
							'sanitize_callback' => 'rest_sanitize_boolean',
						],
						'payment_method_id' => [
							'sanitize_callback' => 'sanitize_text_field',
						],
					],
					'permission_callback' => '__return_true',
				],
			]
		);
	}

	/**
	 * Handle a donation.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function api_process_donation( $request ) {
		$payment_metadata = [
			'referer' => wp_get_referer(),
		];
		if ( class_exists( 'Newspack\NRH' ) && method_exists( 'Newspack\NRH', 'get_nrh_config' ) ) {
			$nrh_config = \Newspack\NRH::get_nrh_config();
			if ( isset( $nrh_config['nrh_salesforce_campaign_id'] ) ) {
				$payment_metadata['sf_campaign_id'] = $nrh_config['nrh_salesforce_campaign_id'];
			}
		}

		$response = \Newspack\Stripe_Connection::handle_donation(
			[
				'frequency'         => $request->get_param( 'frequency' ),
				'token_data'        => $request->get_param( 'tokenData' ),
				'email_address'     => $request->get_param( 'email' ),
				'full_name'         => $request->get_param( 'full_name' ),
				'amount'            => $request->get_param( 'amount' ),
				'client_metadata'   => [
					'clientId'        => $request->get_param( 'clientId' ),
					'newsletterOptIn' => $request->get_param( 'newsletter_opt_in' ),
					'userId'          => self::$current_user_id,
				],
				'payment_metadata'  => $payment_metadata,
				'payment_method_id' => $request->get_param( 'payment_method_id' ),
			]
		);

		return rest_ensure_response( $response );
	}
}
