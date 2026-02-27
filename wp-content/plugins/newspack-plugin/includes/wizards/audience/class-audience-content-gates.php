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

		// Determine active menu items.
		add_filter( 'parent_file', [ $this, 'parent_file' ] );
		add_filter( 'submenu_file', [ $this, 'submenu_file' ] );
	}

	/**
	 * Parent file filter. Used to determine active menu items.
	 *
	 * @param string $parent_file Parent file to be overridden.
	 * @return string
	 */
	public function parent_file( $parent_file ) {
		global $pagenow, $typenow;
		if ( in_array( $pagenow, [ 'post.php', 'post-new.php' ] ) && $typenow === Content_Gate::GATE_CPT ) {
			return $this->parent_slug;
		}
		return $parent_file;
	}

	/**
	 * Submenu file filter. Used to determine active submenu items.
	 *
	 * @param string $submenu_file Submenu file to be overridden.
	 * @return string
	 */
	public function submenu_file( $submenu_file ) {
		global $pagenow, $typenow;
		if ( in_array( $pagenow, [ 'post.php', 'post-new.php' ] ) && $typenow === Content_Gate::GATE_CPT ) {
			return $this->slug;
		}
		return $submenu_file;
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
				'api'                     => '/' . NEWSPACK_API_NAMESPACE . '/wizard/' . $this->slug,
				'available_access_rules'  => Access_Rules::get_access_rules(),
				'available_content_rules' => Content_Gate::get_content_rules(),
				'edit_gate_layout_url'    => Content_Gate::get_edit_gate_layout_url(),
			]
		);

		\wp_localize_script(
			'newspack-wizards',
			'newspackAudience',
			[
				'available_products' => Content_Gate::get_purchasable_product_options(),
				'content_gifting'    => [
					'can_use_gifting' => Content_Gifting::can_use_gifting(),
					'has_metering'    => Content_Gate::is_metering_enabled(),
				],
			]
		);

		// Enqueue content banner CSS for previews.
		wp_enqueue_style( 'newspack-content-banner', Newspack::plugin_url() . '/dist/content-banner.css', [], NEWSPACK_PLUGIN_VERSION );
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
		return Content_Gate::is_newspack_feature_enabled();
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
			'/wizard/' . $this->slug,
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_config' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/content-gifting',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_content_gifting' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'button_label'         => [
						'type' => 'string',
					],
					'cta_label'            => [
						'type' => 'string',
					],
					'cta_product_id'       => [
						'type' => 'integer',
					],
					'cta_type'             => [
						'type' => 'string',
					],
					'cta_url'              => [
						'type' => 'string',
					],
					'enabled'              => [
						'type' => 'boolean',
					],
					'expiration_time'      => [
						'type' => 'integer',
					],
					'expiration_time_unit' => [
						'type' => 'string',
					],
					'interval'             => [
						'type' => 'string',
					],
					'limit'                => [
						'type' => 'integer',
					],
					'style'                => [
						'type' => 'string',
					],
				],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/countdown-banner',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_countdown_banner' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'button_label'   => [
						'type' => 'string',
					],
					'cta_label'      => [
						'type' => 'string',
					],
					'cta_product_id' => [
						'type' => 'integer',
					],
					'cta_type'       => [
						'type' => 'string',
					],
					'cta_url'        => [
						'type' => 'string',
					],
					'enabled'        => [
						'type' => 'boolean',
					],
					'style'          => [
						'type' => 'string',
					],
				],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug,
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
			'/wizard/' . $this->slug . '/(?P<id>\d+)',
			[
				'methods'             => 'DELETE',
				'callback'            => [ $this, 'delete_gate' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/priority',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_gate_priorities' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'gates' => [
						'type'  => 'array',
						'items' => [
							'type'       => 'object',
							'properties' => [
								'id'       => [
									'type'              => 'integer',
									'sanitize_callback' => 'absint',
								],
								'priority' => [
									'type'              => 'integer',
									'sanitize_callback' => 'absint',
								],
							],
						],
					],
				],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/(?P<id>\d+)',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_gate' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'gate' => [
						'type'              => 'object',
						'sanitize_callback' => [ $this, 'sanitize_gate' ],
						'properties'        => [
							'title'         => [ 'type' => 'string' ],
							'status'        => [ 'type' => 'string' ],
							'metering'      => [
								'type'       => 'object',
								'properties' => [
									'enabled'          => [ 'type' => 'boolean' ],
									'anonymous_count'  => [ 'type' => 'integer' ],
									'registered_count' => [ 'type' => 'integer' ],
									'period'           => [ 'type' => 'string' ],
								],
							],
							'content_rules' => [
								'type'  => 'array',
								'items' => [
									'type'       => 'object',
									'properties' => [
										'slug'      => [ 'type' => 'string' ],
										'value'     => [ 'type' => 'mixed' ],
										'exclusion' => [ 'type' => 'boolean' ],
									],
								],
							],
							'registration'  => [
								'type'       => 'object',
								'properties' => [
									'active'               => [ 'type' => 'boolean' ],
									'require_verification' => [ 'type' => 'boolean' ],
									'gate_layout_id'       => [
										'type'     => 'integer',
										'required' => false,
									],
									'metering'             => [
										'type'       => 'object',
										'properties' => [
											'enabled' => [ 'type' => 'boolean' ],
											'count'   => [ 'type' => 'integer' ],
											'period'  => [ 'type' => 'string' ],
										],
									],
								],
							],
							'custom_access' => [
								'type'       => 'object',
								'properties' => [
									'active'         => [ 'type' => 'boolean' ],
									'metering'       => [
										'type'       => 'object',
										'properties' => [
											'enabled' => [ 'type' => 'boolean' ],
											'count'   => [ 'type' => 'integer' ],
											'period'  => [ 'type' => 'string' ],
										],
									],
									'gate_layout_id' => [
										'type'     => 'integer',
										'required' => false,
									],
									'access_rules'   => [
										'type'  => 'array',
										'items' => [
											'type'  => 'array',
											'items' => [
												'type' => 'object',
												'properties' => [
													'slug' => [ 'type' => 'string' ],
													'value' => [ 'type' => 'mixed' ],
												],
											],
										],
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
	 * TODO: Handle errors from each sanitization method.
	 *
	 * @param array $gate The gate.
	 *
	 * @return array The sanitized gate.
	 */
	public function sanitize_gate( $gate ) {
		return [
			'title'         => isset( $gate['title'] ) ? sanitize_text_field( $gate['title'] ) : __( 'Untitled Content Gate', 'newspack-plugin' ),
			'priority'      => intval( $gate['priority'] ),
			'status'        => $this->sanitize_status( $gate['status'], $gate['id'] ),
			'content_rules' => $this->sanitize_rules( $gate['content_rules'], 'content' ),
			'registration'  => $this->sanitize_registration( $gate['registration'] ),
			'custom_access' => $this->sanitize_custom_access( $gate['custom_access'] ),
		];
	}

	/**
	 * Sanitize registration settings.
	 *
	 * @param array $registration The registration settings.
	 *
	 * @return array The sanitized registration.
	 */
	public function sanitize_registration( $registration ) {
		$registration = [
			'active'               => boolval( $registration['active'] ),
			'metering'             => $this->sanitize_metering( $registration['metering'] ),
			'require_verification' => boolval( $registration['require_verification'] ),
		];
		if ( isset( $registration['gate_layout_id'] ) ) {
			$registration['gate_layout_id'] = absint( $registration['gate_layout_id'] );
		}
		return $registration;
	}

	/**
	 * Sanitize custom access settings.
	 *
	 * @param array $custom_access The custom access settings.
	 *
	 * @return array The sanitized custom access.
	 */
	public function sanitize_custom_access( $custom_access ) {
		$custom_access = [
			'active'       => boolval( $custom_access['active'] ),
			'metering'     => $this->sanitize_metering( $custom_access['metering'] ),
			'access_rules' => $this->sanitize_rules( $custom_access['access_rules'], 'access' ),
		];
		if ( isset( $custom_access['gate_layout_id'] ) ) {
			$custom_access['gate_layout_id'] = absint( $custom_access['gate_layout_id'] );
		}
		return $custom_access;
	}

	/**
	 * Sanitize the metering.
	 *
	 * @param array $metering The metering.
	 *
	 * @return array The sanitized metering.
	 */
	public function sanitize_metering( $metering ) {
		$metering = wp_parse_args(
			$metering,
			[
				'enabled' => false,
				'count'   => 0,
				'period'  => 'month',
			]
		);
		return [
			'enabled' => boolval( $metering['enabled'] ),
			'count'   => intval( $metering['count'] ),
			'period'  => sanitize_text_field( $metering['period'] ),
		];
	}

	/**
	 * Sanitize rules.
	 *
	 * @param array  $rules The rules.
	 * @param string $type The type of rules to sanitize.
	 *
	 * @return array The sanitized rules.
	 */
	public function sanitize_rules( $rules, $type = 'access' ) {
		if ( ! is_array( $rules ) ) {
			return [];
		}

		// For access rules, handle grouped format.
		if ( 'access' === $type ) {
			return $this->sanitize_access_rules_grouped( $rules );
		}

		// For content rules, use flat format.
		$sanitized_rules = [];
		foreach ( $rules as $rule ) {
			$sanitized = $this->sanitize_content_rule( $rule );
			if ( ! is_wp_error( $sanitized ) ) {
				$sanitized_rules[] = $sanitized;
			}
		}
		return $sanitized_rules;
	}

	/**
	 * Sanitize access rules in grouped format.
	 *
	 * Accepts both flat format [ rule1, rule2 ] and grouped format [ [ rule1, rule2 ], [ rule3 ] ].
	 * Always returns grouped format [ [ rule1, rule2 ], [ rule3 ] ].
	 *
	 * @param array $rules The access rules.
	 *
	 * @return array The sanitized access rules in grouped format.
	 */
	public function sanitize_access_rules_grouped( $rules ) {
		if ( empty( $rules ) ) {
			return [];
		}

		// Normalize rules (flat or grouped) to a consistent grouped format.
		$rules = Access_Rules::normalize_rules( $rules );

		// Sanitize each group.
		$sanitized_groups = [];
		foreach ( $rules as $group ) {
			$sanitized_group = $this->sanitize_access_rules_group( $group );
			if ( ! empty( $sanitized_group ) ) {
				$sanitized_groups[] = $sanitized_group;
			}
		}

		return $sanitized_groups;
	}

	/**
	 * Sanitize a single group of access rules.
	 *
	 * @param array $group The group of access rules.
	 *
	 * @return array The sanitized group.
	 */
	public function sanitize_access_rules_group( $group ) {
		if ( ! is_array( $group ) ) {
			return [];
		}

		$sanitized_group = [];
		foreach ( $group as $rule ) {
			$sanitized = $this->sanitize_access_rule( $rule );
			if ( ! is_wp_error( $sanitized ) ) {
				$sanitized_group[] = $sanitized;
			}
		}
		return $sanitized_group;
	}

	/**
	 * Sanitize access rule.
	 *
	 * @param array $access_rule The access rule.
	 *
	 * @return mixed|\WP_Error The sanitized access rule or error if invalid.
	 */
	public function sanitize_access_rule( $access_rule ) {
		$rules = Access_Rules::get_access_rules();
		$slug  = sanitize_text_field( $access_rule['slug'] );

		if ( empty( $slug ) || ! isset( $rules[ $slug ] ) ) {
			return new \WP_Error( 'invalid_access_rule_slug', __( 'Invalid access rule slug.', 'newspack-plugin' ), [ 'status' => 400 ] );
		}

		$value = null;
		$rule  = $rules[ $slug ];
		if ( $rule['is_boolean'] ) {
			$value = true; // Boolean rules are always true.
		} elseif ( ! empty( $rule['options'] ) ) {
			if ( ! is_array( $access_rule['value'] ) ) {
				return new \WP_Error( 'invalid_access_rule_value', __( 'Invalid access rule value.', 'newspack-plugin' ), [ 'status' => 400 ] );
			}
			$value = array_values(
				array_filter(
					array_map(
						function( $value ) {
							return is_numeric( $value ) ? intval( $value ) : sanitize_text_field( $value );
						},
						$access_rule['value']
					)
				)
			);
		} else {
			$value = sanitize_text_field( $access_rule['value'] );
		}

		return [
			'slug'  => $slug,
			'value' => $value,
		];
	}

	/**
	 * Sanitize content rule.
	 *
	 * @param array $content_rule The content rule.
	 *
	 * @return mixed|\WP_Error The sanitized content rule or error if invalid.
	 */
	public function sanitize_content_rule( $content_rule ) {
		$rules = Content_Gate::get_content_rules();
		$slug  = sanitize_text_field( $content_rule['slug'] );

		if ( empty( $slug ) || ! isset( $rules[ $slug ] ) ) {
			return new \WP_Error( 'invalid_content_rule_slug', __( 'Invalid content rule slug.', 'newspack-plugin' ), [ 'status' => 400 ] );
		}

		$rule = $rules[ $slug ];
		if ( ! empty( $rule['options'] ) ) {
			$allowed = array_column( $rule['options'], 'value' );
			$invalid = array_diff( $content_rule['value'], $allowed );
			if ( ! empty( $invalid ) ) {
				return new \WP_Error( 'invalid_content_rule_value', __( 'Invalid content rule value.', 'newspack-plugin' ), [ 'status' => 400 ] );
			}
		}

		$value     = array_values( array_filter( array_map( 'sanitize_text_field', $content_rule['value'] ) ) );
		$exclusion = isset( $content_rule['exclusion'] ) ? boolval( $content_rule['exclusion'] ) : false;

		$sanitized_rule = [
			'slug'  => $slug,
			'value' => $value,
		];
		if ( $exclusion ) {
			$sanitized_rule['exclusion'] = $exclusion;
		}

		return $sanitized_rule;
	}

	/**
	 * Sanitize the gate post status.
	 *
	 * @param string $status Post status.
	 * @param int    $gate_id Gate ID.
	 *
	 * @return string The sanitized post status.
	 */
	public function sanitize_status( $status, $gate_id ) {
		$sanitized = sanitize_text_field( $status );
		$valid = in_array( $sanitized, Content_Gate::get_post_statuses(), true );
		if ( ! $valid ) {
			$sanitized = $gate_id ? get_post_status( $gate_id ) : 'draft';
		}
		return $sanitized;
	}

	/**
	 * Get the gates.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_config() {
		$config = [
			'gates'  => Content_Gate::get_gates(),
			'config' => [
				'countdown_banner' => Metering_Countdown::get_settings(),
				'content_gifting'  => Content_Gifting::get_settings(),
			],
		];
		return rest_ensure_response( $config );
	}

	/**
	 * Update content gifting settings.
	 *
	 * @param \WP_REST_Request $request The request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function update_content_gifting( $request ) {
		$args = $request->get_params();

		if ( isset( $args['enabled'] ) ) {
			Content_Gifting::set_enabled( (bool) $args['enabled'] );
		}
		if ( isset( $args['limit'] ) ) {
			Content_Gifting::set_gifting_limit( (int) $args['limit'] );
		}
		if ( isset( $args['expiration_time'] ) ) {
			Content_Gifting::set_expiration_time( (int) $args['expiration_time'] );
		}
		if ( isset( $args['expiration_time_unit'] ) ) {
			Content_Gifting::set_expiration_time_unit( sanitize_text_field( $args['expiration_time_unit'] ) );
		}
		if ( isset( $args['interval'] ) ) {
			Content_Gifting::set_gifting_reset_interval( sanitize_text_field( $args['interval'] ) );
		}
		if ( isset( $args['cta_label'] ) ) {
			Content_Gifting_CTA::set_cta_label( sanitize_text_field( $args['cta_label'] ) );
		}
		if ( isset( $args['button_label'] ) ) {
			Content_Gifting_CTA::set_button_label( sanitize_text_field( $args['button_label'] ) );
		}
		if ( isset( $args['cta_type'] ) ) {
			Content_Gifting_CTA::set_cta_type( sanitize_text_field( $args['cta_type'] ) );
		}
		if ( isset( $args['cta_product_id'] ) ) {
			Content_Gifting_CTA::set_cta_product_id( (int) $args['cta_product_id'] );
		}
		if ( isset( $args['cta_url'] ) ) {
			Content_Gifting_CTA::set_cta_url( sanitize_text_field( $args['cta_url'] ) );
		}
		if ( isset( $args['style'] ) ) {
			Content_Gifting_CTA::set_style( sanitize_text_field( $args['style'] ) );
		}
		return rest_ensure_response( Content_Gifting::get_settings() );
	}

	/**
	 * Update countdown banner settings.
	 *
	 * @param \WP_REST_Request $request The request object.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function update_countdown_banner( $request ) {
		$args = $request->get_params();
		return rest_ensure_response( Metering_Countdown::update_settings( $args ) );
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
		$force = $gate->post_status === 'trash';
		if ( $force ) {
			wp_delete_post( $id, $force );
		} else {
			wp_trash_post( $id );
		}
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
