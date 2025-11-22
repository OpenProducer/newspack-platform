<?php
/**
 * Audience Content Gates Wizard
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Audience Campaigns Wizard.
 */
class Audience_Content_Gates extends Wizard {

	/**
	 * Admin page slug.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-audience-content-gates';

	/**
	 * Parent slug.
	 *
	 * @var string
	 */
	protected $parent_slug = 'newspack-audience';

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
		return esc_html__( 'Audience Management / Content Gates', 'newspack-plugin' );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		if ( ! $this->is_wizard_page() || ! $this->is_feature_enabled() ) {
			return;
		}

		parent::enqueue_scripts_and_styles();

		wp_enqueue_script( 'newspack-wizards' );

		\wp_localize_script(
			'newspack-wizards',
			'newspackAudienceContentGates',
			[
				'api'             => '/' . NEWSPACK_API_NAMESPACE . '/wizard/' . $this->slug,
				'available_rules' => Access_Rules::get_access_rules(),
			]
		);
	}

	/**
	 * Add Audience top-level and Content Gate subpage to the /wp-admin menu.
	 */
	public function add_page() {
		if ( ! $this->is_feature_enabled() ) {
			return;
		}

		add_submenu_page(
			$this->parent_slug,
			$this->get_name(),
			esc_html__( 'Content Gates', 'newspack-plugin' ),
			$this->capability,
			$this->slug,
			[ $this, 'render_wizard' ]
		);
	}

	/**
	 * Check feature flag status.
	 *
	 * @return bool
	 */
	public function is_feature_enabled() {
		return defined( 'NEWSPACK_CONTENT_GATES' ) && NEWSPACK_CONTENT_GATES;
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		if ( ! $this->is_feature_enabled() ) {
			return;
		}

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/content-gate',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_gates' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/content-gate',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'create_gate' ],
				'args'                => [
					'title' => [
						'type'     => 'string',
						'required' => true,
						'messages' => [
							'required' => __( 'Title is required.', 'newspack-plugin' ),
						],
					],
				],
				'required'            => [ 'title' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/content-gate/(?P<id>\d+)',
			[
				'methods'             => 'DELETE',
				'callback'            => [ $this, 'delete_gate' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/content-gate/priority',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_gate_priorities' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'sanitize_callback'   => [ $this, 'sanitize_gates' ],
				'args'                => [
					'gates' => [
						'type' => 'array',
					],
				],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/content-gate/(?P<id>\d+)',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_gate' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'sanitize_callback'   => [ $this, 'sanitize_gate' ],
				'args'                => [
					'gate' => [
						'type'       => 'object',
						'properties' => [
							'title'         => [ 'type' => 'string' ],
							'description'   => [ 'type' => 'string' ],
							'metering'      => [
								'type'       => 'object',
								'properties' => [
									'enabled'          => [ 'type' => 'boolean' ],
									'anonymous_count'  => [ 'type' => 'integer' ],
									'registered_count' => [ 'type' => 'integer' ],
									'period'           => [ 'type' => 'string' ],
								],
							],
							'access_rules'  => [
								'type'  => 'array',
								'items' => [
									'type'       => 'object',
									'properties' => [
										'slug'  => [ 'type' => 'string' ],
										'value' => [ 'type' => 'mixed' ],
									],
								],
							],
							// TODO: Fix content rules schema.
							'content_rules' => [
								'type'  => 'array',
								'items' => [
									'type'       => 'object',
									'properties' => [
										'type' => [ 'type' => 'string' ],
									],
								],
							],
						],
					],
				],
			]
		);
	}

	/**
	 * Sanitize the gate.
	 *
	 * @param array $gate The gate.
	 *
	 * @return array The sanitized gate.
	 */
	public function sanitize_gate( $gate ) {
		return [
			'title'         => sanitize_text_field( $gate['title'] ),
			'description'   => sanitize_text_field( $gate['description'] ),
			'metering'      => $this->sanitize_metering( $gate['metering'] ),
			'access_rules'  => $this->sanitize_access_rules( $gate['access_rules'] ),
			'content_rules' => $gate['content_rules'], // TODO: Sanitize content rules.
			'priority'      => intval( $gate['priority'] ),
		];
	}

	/**
	 * Sanitize multiple gates.
	 *
	 * @param array $gates An array of gates.
	 *
	 * @return array The sanitized array of gates.
	 */
	public function sanitize_gates( $gates ) {
		$sanitized_gates = [];
		foreach ( $gates as &$gate ) {
			$sanitized_gates[] = $this->sanitize_gate( $gate );
		}
		return $sanitized_gates;
	}

	/**
	 * Sanitize the metering.
	 *
	 * @param array $metering The metering.
	 *
	 * @return array The sanitized metering.
	 */
	public function sanitize_metering( $metering ) {
		return [
			'enabled'          => boolval( $metering['enabled'] ),
			'anonymous_count'  => intval( $metering['anonymous_count'] ),
			'registered_count' => intval( $metering['registered_count'] ),
			'period'           => sanitize_text_field( $metering['period'] ),
		];
	}

	/**
	 * Sanitize access rules.
	 *
	 * @param array $access_rules The access rules.
	 *
	 * @return array The sanitized access rules.
	 */
	public function sanitize_access_rules( $access_rules ) {
		return array_map( [ $this, 'sanitize_access_rule' ], $access_rules );
	}

	/**
	 * Sanitize rule.
	 *
	 * @param array $access_rule The access rule.
	 *
	 * @return mixed|\WP_Error The sanitized access rule or error if invalid.
	 */
	public function sanitize_access_rule( $access_rule ) {
		$rules = Access_Rules::get_access_rules();

		if ( ! isset( $rules[ $access_rule['slug'] ] ) ) {
			return new \WP_Error( 'invalid_access_rule_slug', __( 'Invalid access rule slug.', 'newspack-plugin' ), [ 'status' => 400 ] );
		}
		$rule = $rules[ $access_rule['slug'] ];
		if ( $rule['is_boolean'] ) {
			return boolval( $access_rule['value'] );
		}
		if ( ! empty( $rule['options'] ) ) {
			if ( ! is_array( $access_rule['value'] ) ) {
				return new \WP_Error( 'invalid_access_rule_value', __( 'Invalid access rule value.', 'newspack-plugin' ), [ 'status' => 400 ] );
			}
			return array_filter( array_map( 'sanitize_text_field', $access_rule['value'] ) );
		}
		return sanitize_text_field( $access_rule['value'] );
	}

	/**
	 * Get the gates.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_gates() {
		return rest_ensure_response( Content_Gate::get_gates() );
	}

	/**
	 * Create a gate.
	 *
	 * @param \WP_REST_Request $request The request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function create_gate( $request ) {
		$gate = Content_Gate::create_gate( $request->get_param( 'title' ) );
		if ( is_wp_error( $gate ) ) {
			return $gate;
		}
		return rest_ensure_response( Content_Gate::get_gate( $gate ) );
	}

	/**
	 * Delete a gate.
	 *
	 * @param \WP_REST_Request $request The request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function delete_gate( $request ) {
		$id = $request->get_param( 'id' );
		$gate = get_post( $id );
		if ( ! $gate ) {
			return new \WP_Error( 'invalid_gate_id', __( 'Invalid gate ID.', 'newspack-plugin' ), [ 'status' => 400 ] );
		}
		if ( Content_Gate::GATE_CPT !== $gate->post_type ) {
			return new \WP_Error( 'invalid_gate_type', __( 'Invalid gate type.', 'newspack-plugin' ), [ 'status' => 400 ] );
		}
		wp_delete_post( $id, true );
		return rest_ensure_response( true );
	}

	/**
	 * Update a gate.
	 *
	 * @param \WP_REST_Request $request The request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function update_gate( $request ) {
		$gate = Content_Gate::update_gate_settings( $request->get_param( 'id' ), $request->get_param( 'gate' ) );
		if ( is_wp_error( $gate ) ) {
			return $gate;
		}
		return rest_ensure_response( $gate );
	}

	/**
	 * Update multiple gates.
	 *
	 * @param \WP_REST_Request $request The request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function update_gate_priorities( $request ) {
		$gates = $request->get_param( 'gates' );
		$updated_gates = [];
		foreach ( $gates as $gate ) {
			$updated_gate = Content_Gate::update_gate_setting( $gate['id'], 'gate_priority', $gate['priority'] );
			if ( is_wp_error( $updated_gate ) ) {
				return $updated_gate;
			}
			$updated_gates[] = $updated_gate;
		}
		return rest_ensure_response( $updated_gates );
	}
}
