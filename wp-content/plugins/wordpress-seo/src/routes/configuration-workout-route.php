<?php

namespace Yoast\WP\SEO\Routes;

use Yoast\WP\SEO\Actions\Configuration\Configuration_Workout_Action;
use Yoast\WP\SEO\Conditionals\No_Conditionals;
use Yoast\WP\SEO\Main;

/**
 * Configuration_Workout_Route class.
 */
class Configuration_Workout_Route implements Route_Interface {

	use No_Conditionals;

	/**
	 * Represents a site representation route.
	 *
	 * @var string
	 */
	const SITE_REPRESENTATION_ROUTE = '/site_representation';

	/**
	 * Represents a social profiles route.
	 *
	 * @var string
	 */
	const SOCIAL_PROFILES_ROUTE = '/social_profiles';

	/**
	 * Represents a route to enable/disable tracking.
	 *
	 * @var string
	 */
	const ENABLE_TRACKING_ROUTE = '/enable_tracking';

	/**
	 *  The configuration workout action.
	 *
	 * @var Configuration_Workout_Action;
	 */
	private $configuration_workout_action;

	/**
	 * Configuration_Workout_Route constructor.
	 *
	 * @param Configuration_Workout_Action $configuration_workout_action The configuration workout action.
	 */
	public function __construct(
		Configuration_Workout_Action $configuration_workout_action
	) {
		$this->configuration_workout_action = $configuration_workout_action;
	}

	/**
	 * Registers routes with WordPress.
	 *
	 * @return void
	 */
	public function register_routes() {
		$site_representation_route = [
			'methods'             => 'POST',
			'callback'            => [ $this, 'set_site_representation' ],
			'permission_callback' => [ $this, 'can_manage_options' ],
			'args'                => [
				'company_or_person' => [
					'type'     => 'string',
					'enum'     => [
						'company',
						'person',
					],
					'required' => true,
				],
				'company_name' => [
					'type'     => 'string',
				],
				'company_logo' => [
					'type'     => 'string',
				],
				'company_logo_id' => [
					'type'     => 'integer',
				],
				'person_logo' => [
					'type'     => 'string',
				],
				'person_logo_id' => [
					'type'     => 'integer',
				],
				'company_or_person_user_id' => [
					'type'     => 'integer',
				],
				'description' => [
					'type'     => 'string',
				],
			],
		];

		\register_rest_route( Main::API_V1_NAMESPACE, Workouts_Route::WORKOUTS_ROUTE . self::SITE_REPRESENTATION_ROUTE, $site_representation_route );

		$social_profiles_route = [
			'methods'             => 'POST',
			'callback'            => [ $this, 'set_social_profiles' ],
			'permission_callback' => [ $this, 'can_manage_options' ],
			'args'                => [
				'facebook_site' => [
					'type'     => 'string',
				],
				'twitter_site' => [
					'type'     => 'string',
				],
				'instagram_url' => [
					'type'     => 'string',
				],
				'linkedin_url' => [
					'type'     => 'string',
				],
				'myspace_url' => [
					'type'     => 'string',
				],
				'pinterest_url' => [
					'type'     => 'string',
				],
				'youtube_url' => [
					'type'     => 'string',
				],
				'wikipedia_url' => [
					'type'     => 'string',
				],
			],
		];

		\register_rest_route( Main::API_V1_NAMESPACE, Workouts_Route::WORKOUTS_ROUTE . self::SOCIAL_PROFILES_ROUTE, $social_profiles_route );

		$enable_tracking_route = [
			'methods'             => 'POST',
			'callback'            => [ $this, 'set_enable_tracking' ],
			'permission_callback' => [ $this, 'can_manage_options' ],
			'args'                => [
				'tracking' => [
					'type'     => 'boolean',
					'required' => true,
				],
			],
		];

		\register_rest_route( Main::API_V1_NAMESPACE, Workouts_Route::WORKOUTS_ROUTE . self::ENABLE_TRACKING_ROUTE, $enable_tracking_route );
	}

	/**
	 * Sets the site representation values.
	 *
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return \WP_REST_Response
	 */
	public function set_site_representation( \WP_REST_Request $request ) {
		$data = $this
			->configuration_workout_action
			->set_site_representation( $request->get_json_params() );

		return new \WP_REST_Response( $data, $data->status );
	}

	/**
	 * Sets the social profiles values.
	 *
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return \WP_REST_Response
	 */
	public function set_social_profiles( \WP_REST_Request $request ) {
		$data = $this
			->configuration_workout_action
			->set_social_profiles( $request->get_json_params() );

		return new \WP_REST_Response( $data, $data->status );
	}

	/**
	 * Enables or disables tracking.
	 *
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return \WP_REST_Response
	 */
	public function set_enable_tracking( \WP_REST_Request $request ) {
		$data = $this
			->configuration_workout_action
			->set_enable_tracking( $request->get_json_params() );

		return new \WP_REST_Response( $data, $data->status );
	}

	/**
	 * Checks if the current user has the right capability.
	 *
	 * @return bool
	 */
	public function can_manage_options() {
		return \current_user_can( 'wpseo_manage_options' );
	}
}
