<?php
/**
 * Newspack Newsletter Author
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/vendor/autoload.php';

use \DrewM\MailChimp\MailChimp;

/**
 * Main Newspack Newsletters Class.
 */
final class Newspack_Newsletters {

	const NEWSPACK_NEWSLETTERS_CPT = 'newspack_nl_cpt';

	/**
	 * Supported fonts.
	 *
	 * @var array
	 */
	public static $supported_fonts = [
		'Arial, Helvetica, sans-serif',
		'Tahoma, sans-serif',
		'Trebuchet MS, sans-serif',
		'Verdana, sans-serif',
		'Georgia, serif',
		'Palatino, serif',
		'Times New Roman, serif',
		'Courier, monospace',
	];

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Newsletters
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Newsletter Author Instance.
	 * Ensures only one instance of Newspack Author Instance is loaded or can be loaded.
	 *
	 * @return Newspack Author Instance - Main instance.
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
		add_action( 'init', [ __CLASS__, 'register_cpt' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'rest_api_init', [ __CLASS__, 'rest_api_init' ] );
		add_action( 'default_title', [ __CLASS__, 'default_title' ], 10, 2 );
		add_action( 'save_post_' . self::NEWSPACK_NEWSLETTERS_CPT, [ __CLASS__, 'save_post' ], 10, 3 );
		add_action( 'publish_' . self::NEWSPACK_NEWSLETTERS_CPT, [ __CLASS__, 'send_campaign' ], 10, 2 );
		add_action( 'wp_trash_post', [ __CLASS__, 'trash_post' ], 10, 1 );

		$needs_nag =
			is_admin() &&
			( ! self::mailchimp_api_key() || ! get_option( 'newspack_newsletters_mjml_api_key', false ) || ! get_option( 'newspack_newsletters_mjml_api_secret', false ) ) &&
			! get_option( 'newspack_newsletters_activation_nag_viewed', false );

		if ( $needs_nag ) {
			add_action( 'admin_notices', [ __CLASS__, 'activation_nag' ] );
			add_action( 'admin_enqueue_scripts', [ __CLASS__, 'activation_nag_dismissal_script' ] );
			add_action( 'wp_ajax_newspack_newsletters_activation_nag_dismissal', [ __CLASS__, 'activation_nag_dismissal_ajax' ] );
		}
		include_once dirname( __FILE__ ) . '/class-newspack-newsletters-editor.php';
		include_once dirname( __FILE__ ) . '/class-newspack-newsletters-layouts.php';
		include_once dirname( __FILE__ ) . '/class-newspack-newsletters-settings.php';
		include_once dirname( __FILE__ ) . '/class-newspack-newsletters-renderer.php';
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'mc_campaign_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'mc_list_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'template_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => true,
				'type'           => 'integer',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'font_header',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'font_body',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'background_color',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		/**
		 * The default color palette lives in the editor frontend and is not
		 * retrievable on the backend. The workaround is to set it as post meta
		 * so that it's available to the email renderer.
		 */
		\register_meta(
			'post',
			'color_palette',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => array(
					'schema' => array(
						'type'                 => 'object',
						'properties'           => array(),
						'additionalProperties' => array(
							'type' => 'string',
						),
					),
				),
				'type'           => 'object',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
	}

	/**
	 * Register the custom post type.
	 */
	public static function register_cpt() {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}
		$labels = [
			'name'               => _x( 'Newsletters', 'post type general name', 'newspack-newsletters' ),
			'singular_name'      => _x( 'Newsletter', 'post type singular name', 'newspack-newsletters' ),
			'menu_name'          => _x( 'Newsletters', 'admin menu', 'newspack-newsletters' ),
			'name_admin_bar'     => _x( 'Newsletter', 'add new on admin bar', 'newspack-newsletters' ),
			'add_new'            => _x( 'Add New', 'popup', 'newspack-newsletters' ),
			'add_new_item'       => __( 'Add New Newsletter', 'newspack-newsletters' ),
			'new_item'           => __( 'New Newsletter', 'newspack-newsletters' ),
			'edit_item'          => __( 'Edit Newsletter', 'newspack-newsletters' ),
			'view_item'          => __( 'View Newsletter', 'newspack-newsletters' ),
			'all_items'          => __( 'All Newsletters', 'newspack-newsletters' ),
			'search_items'       => __( 'Search Newsletters', 'newspack-newsletters' ),
			'parent_item_colon'  => __( 'Parent Newsletters:', 'newspack-newsletters' ),
			'not_found'          => __( 'No newsletters found.', 'newspack-newsletters' ),
			'not_found_in_trash' => __( 'No newsletters found in Trash.', 'newspack-newsletters' ),
		];

		$cpt_args = [
			'labels'       => $labels,
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'supports'     => [ 'editor', 'title', 'custom-fields' ],
			'taxonomies'   => [],
			'menu_icon'    => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0Ij48cGF0aCBkPSJNMTIgMkM2LjQ4IDIgMiA2LjQ4IDIgMTJzNC40OCAxMCAxMCAxMGg1di0yaC01Yy00LjM0IDAtOC0zLjY2LTgtOHMzLjY2LTggOC04IDggMy42NiA4IDh2MS40M2MwIC43OS0uNzEgMS41Ny0xLjUgMS41N3MtMS41LS43OC0xLjUtMS41N1YxMmMwLTIuNzYtMi4yNC01LTUtNXMtNSAyLjI0LTUgNSAyLjI0IDUgNSA1YzEuMzggMCAyLjY0LS41NiAzLjU0LTEuNDcuNjUuODkgMS43NyAxLjQ3IDIuOTYgMS40NyAxLjk3IDAgMy41LTEuNiAzLjUtMy41N1YxMmMwLTUuNTItNC40OC0xMC0xMC0xMHptMCAxM2MtMS42NiAwLTMtMS4zNC0zLTNzMS4zNC0zIDMtMyAzIDEuMzQgMyAzLTEuMzQgMy0zIDN6IiBmaWxsPSJ3aGl0ZSIvPjwvc3ZnPgo=',
		];
		\register_post_type( self::NEWSPACK_NEWSLETTERS_CPT, $cpt_args );
	}

	/**
	 * Add newspack_popups_is_sitewide_default to Popup object.
	 */
	public static function rest_api_init() {
		\register_rest_route(
			'newspack-newsletters/v1',
			'layouts',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_get_layouts' ],
				'permission_callback' => [ __CLASS__, 'api_authoring_permissions_check' ],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'mailchimp/(?P<id>[\a-z]+)',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_mailchimp_data' ],
				'permission_callback' => [ __CLASS__, 'api_authoring_permissions_check' ],
				'args'                => [
					'id' => [
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'mailchimp/(?P<id>[\a-z]+)/test',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_test_mailchimp_campaign' ],
				'permission_callback' => [ __CLASS__, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'         => [
						'sanitize_callback' => 'absint',
					],
					'test_email' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'mailchimp/(?P<id>[\a-z]+)/list/(?P<list_id>[\a-z]+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_mailchimp_list' ],
				'permission_callback' => [ __CLASS__, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'      => [
						'sanitize_callback' => 'absint',
					],
					'list_id' => [
						'sanitize_callback' => 'esc_attr',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'mailchimp/(?P<id>[\a-z]+)/interest/(?P<interest_id>[\a-z]+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_mailchimp_interest' ],
				'permission_callback' => [ __CLASS__, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'          => [
						'sanitize_callback' => 'absint',
					],
					'interest_id' => [
						'sanitize_callback' => 'esc_attr',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'mailchimp/(?P<id>[\a-z]+)/settings',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_campaign_settings' ],
				'permission_callback' => [ __CLASS__, 'api_authoring_permissions_check' ],
				'args'                => [
					'id'        => [
						'sanitize_callback' => 'absint',
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
			'newspack-newsletters/v1',
			'keys',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_get_keys' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'keys',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_keys' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
				'args'                => [
					'mailchimp_api_key'   => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'mjml_application_id' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'mjml_api_secret'     => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'styling/(?P<id>[\a-z]+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_styling' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
				'args'                => [
					'id'    => [
						'validate_callback' => [ __CLASS__, 'validate_newsletter_id' ],
						'sanitize_callback' => 'absint',
					],
					'key'   => [
						'validate_callback' => [ __CLASS__, 'validate_newsletter_styling_key' ],
						'sanitize_callback' => 'sanitize_text_field',
					],
					'value' => [
						'validate_callback' => [ __CLASS__, 'validate_newsletter_styling_value' ],
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Set styling meta.
	 * The save_post action fires before post meta is updated.
	 * This causes newsletters to be synced to the ESP before recent changes to custom fields have been recorded,
	 * which leads to incorrect rendering. This is addressed through custom endpoints to update the styling fields
	 * as soon as they are changed in the editor, so that the changes are available the next time sync to ESP occurs.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_styling( $request ) {
		$id    = $request['id'];
		$key   = $request['key'];
		$value = $request['value'];
		update_post_meta( $id, $key, $value );
	}

	/**
	 * Validate ID is a Newsletter post type.
	 *
	 * @param int $id Post ID.
	 */
	public static function validate_newsletter_id( $id ) {
		return self::NEWSPACK_NEWSLETTERS_CPT === get_post_type( $id );
	}

	/**
	 * Validate styling key.
	 *
	 * @param String $key Meta key.
	 */
	public static function validate_newsletter_styling_key( $key ) {
		return in_array(
			$key,
			[
				'font_header',
				'font_body',
				'background_color',
			]
		);
	}

	/**
	 * Validate styling value (font name or hex color).
	 *
	 * @param String $key Meta value.
	 */
	public static function validate_newsletter_styling_value( $key ) {
		return in_array(
			$key,
			self::$supported_fonts
		) || preg_match( '/^#[a-f0-9]{6}$/', $key );
	}

	/**
	 * Retrieve Layouts.
	 */
	public static function api_get_layouts() {
		$layouts_query = new WP_Query(
			array(
				'post_type'      => Newspack_Newsletters_Layouts::NEWSPACK_NEWSLETTERS_LAYOUT_CPT,
				'posts_per_page' => -1,
			)
		);
		$layouts       = array_merge(
			$layouts_query->get_posts(),
			Newspack_Newsletters_Layouts::get_default_layouts(),
			apply_filters( 'newspack_newsletters_templates', [] )
		);

		return \rest_ensure_response( $layouts );
	}

	/**
	 * Retrieve API keys.
	 */
	public static function api_get_keys() {
		$mailchimp_api_key = self::mailchimp_api_key();
		$mjml_api_key      = get_option( 'newspack_newsletters_mjml_api_key', false );
		$mjml_api_secret   = get_option( 'newspack_newsletters_mjml_api_secret', false );

		$keys = [
			'mailchimp_api_key' => $mailchimp_api_key ? $mailchimp_api_key : '',
			'mjml_api_key'      => $mjml_api_key ? $mjml_api_key : '',
			'mjml_api_secret'   => $mjml_api_secret ? $mjml_api_secret : '',
			'status'            => ! empty( $mailchimp_api_key ) && ! empty( $mjml_api_key ) && ! empty( $mjml_api_secret ),
		];
		return \rest_ensure_response( $keys );
	}

	/**
	 * Set API keys.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_keys( $request ) {
		$mailchimp_api_key = $request['mailchimp_api_key'];
		$mjml_api_key      = $request['mjml_api_key'];
		$mjml_api_secret   = $request['mjml_api_secret'];
		$wp_error          = new WP_Error();

		if ( empty( $mailchimp_api_key ) ) {
			$wp_error->add(
				'newspack_newsletters_invalid_keys_mailchimp',
				__( 'Please input a Mailchimp API key.', 'newspack-newsletters' )
			);
		} else {
			try {
				$mc   = new Mailchimp( $mailchimp_api_key );
				$ping = $mc->get( 'ping' );
			} catch ( Exception $e ) {
				$ping = null;
			}
			if ( $ping ) {
				update_option( 'newspack_newsletters_mailchimp_api_key', $mailchimp_api_key );
			} else {
				$wp_error->add(
					'newspack_newsletters_invalid_keys_mailchimp',
					__( 'Please input a valid Mailchimp API key.', 'newspack-newsletters' )
				);
			}
		}

		if ( empty( $mjml_api_key ) || empty( $mjml_api_secret ) ) {
			$wp_error->add(
				'newspack_newsletters_invalid_keys_mjml',
				__( 'Please input MJML application ID and secret key.', 'newspack-newsletters' )
			);
		} else {
			$credentials = "$mjml_api_key:$mjml_api_secret";
			$url         = 'https://api.mjml.io/v1/render';
			$mjml_test   = wp_remote_post(
				$url,
				[
					'body'    => wp_json_encode(
						[
							'mjml' => '<h1>test</h1>',
						]
					),
					'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( $credentials ),
					),
					'timeout' => 10, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
				]
			);
			if ( 200 === $mjml_test['response']['code'] ) {
				update_option( 'newspack_newsletters_mjml_api_key', $mjml_api_key );
				update_option( 'newspack_newsletters_mjml_api_secret', $mjml_api_secret );
			} else {
				$wp_error->add(
					'newspack_newsletters_invalid_keys_mjml',
					__( 'Please input valid MJML application ID and secret key.', 'newspack-newsletters' )
				);
			}
		}

		return $wp_error->has_errors() ? $wp_error : self::api_get_keys();
	}

	/**
	 * Update Campaign settings.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_campaign_settings( $request ) {
		$id        = $request['id'];
		$from_name = $request['from_name'];
		$reply_to  = $request['reply_to'];

		if ( self::NEWSPACK_NEWSLETTERS_CPT !== get_post_type( $id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Post is not a Newsletter.', 'newspack-newsletters' )
			);
		}

		$mc_campaign_id = get_post_meta( $id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_no_campaign_id',
				__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
			);
		}
		try {
			$mc = new Mailchimp( self::mailchimp_api_key() );

			$result = self::validate_mailchimp_operation(
				$mc->get( 'verified-domains' ),
				__( 'Error retrieving verified domains from Mailchimp.', 'newspack-newsletters' )
			);

			$verified_domains = array_filter(
				array_map(
					function( $domain ) {
						return $domain['verified'] ? strtolower( trim( $domain['domain'] ) ) : null;
					},
					$result['domains']
				),
				function( $domain ) {
					return ! empty( $domain );
				}
			);

			$explode = explode( '@', $reply_to );
			$domain  = strtolower( trim( array_pop( $explode ) ) );

			if ( ! in_array( $domain, $verified_domains ) ) {
				return new WP_Error(
					'newspack_newsletters_unverified_sender_domain',
					sprintf(
						// Translators: explanation that current domain is not verified, list of verified options.
						__( '%1$s is not a verified domain. Verified domains for the linked Mailchimp account are: %2$s.', 'newspack-newsletters' ),
						$domain,
						implode( ', ', $verified_domains )
					)
				);
			}

			$settings = [];
			if ( $from_name ) {
				$settings['from_name'] = $from_name;
			}
			if ( $reply_to ) {
				$settings['reply_to'] = $reply_to;
			}
			$payload = [
				'settings' => $settings,
			];
			$result  = self::validate_mailchimp_operation(
				$mc->patch( "campaigns/$mc_campaign_id", $payload ),
				__( 'Error setting sender name and email.', 'newspack_newsletters' )
			);

			$data           = self::retrieve_data( $id );
			$data['result'] = $result;

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Set Mailchimp list.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_mailchimp_list( $request ) {
		$id      = $request['id'];
		$list_id = $request['list_id'];

		if ( self::NEWSPACK_NEWSLETTERS_CPT !== get_post_type( $id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Post is not a Newsletter.', 'newspack-newsletters' )
			);
		}


		$mc_campaign_id = get_post_meta( $id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_no_campaign_id',
				__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
			);
		}

		try {
			$mc      = new Mailchimp( self::mailchimp_api_key() );
			$payload = [
				'recipients' => [
					'list_id' => $list_id,
				],
			];
			$result  = self::validate_mailchimp_operation(
				$mc->patch( "campaigns/$mc_campaign_id", $payload ),
				__( 'Error setting Mailchimp list.', 'newspack_newsletters' )
			);

			$data           = self::retrieve_data( $id );
			$data['result'] = $result;

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Set Mailchimp Interest.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_mailchimp_interest( $request ) {
		$id          = $request['id'];
		$exploded    = explode( ':', $request['interest_id'] );
		$field       = count( $exploded ) ? $exploded[0] : null;
		$interest_id = count( $exploded ) > 1 ? $exploded[1] : null;

		if ( self::NEWSPACK_NEWSLETTERS_CPT !== get_post_type( $id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Post is not a Newsletter.', 'newspack-newsletters' )
			);
		}

		if ( 'no_interests' !== $request['interest_id'] && ( ! $field || ! $interest_id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Invalid Mailchimp Interest .', 'newspack-newsletters' )
			);
		}

		$mc_campaign_id = get_post_meta( $id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_no_campaign_id',
				__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
			);
		}
		try {
			$mc       = new Mailchimp( self::mailchimp_api_key() );
			$campaign = self::validate_mailchimp_operation(
				$mc->get( "campaigns/$mc_campaign_id" ),
				__( 'Error retrieving Mailchimp campaign.', 'newspack_newsletters' )
			);
			$list_id  = isset( $campaign, $campaign['recipients'], $campaign['recipients']['list_id'] ) ? $campaign['recipients']['list_id'] : null;

			if ( ! $list_id ) {
				return new WP_Error(
					'newspack_newsletters_no_campaign_id',
					__( 'Mailchimp list ID not found.', 'newspack-newsletters' )
				);
			}

			$segment_opts = ( 'no_interests' === $request['interest_id'] ) ?
				(object) [] :
				[
					'match'      => 'any',
					'conditions' => [
						[
							'condition_type' => 'Interests',
							'field'          => $field,
							'op'             => 'interestcontains',
							'value'          => [
								$interest_id,
							],
						],
					],
				];

			$payload = [
				'recipients' => [
					'list_id'      => $list_id,
					'segment_opts' => $segment_opts,
				],
			];

			$result = self::validate_mailchimp_operation(
				$mc->patch( "campaigns/$mc_campaign_id", $payload ),
				__( 'Error updating Mailchimp groups.', 'newspack_newsletters' )
			);

			$data           = self::retrieve_data( $id );
			$data['result'] = $result;

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Get Mailchimp data.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_mailchimp_data( $request ) {
		return \rest_ensure_response( self::retrieve_data( $request['id'] ) );
	}

	/**
	 * Send a test email via Mailchimp.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_test_mailchimp_campaign( $request ) {
		$id         = $request['id'];
		$test_email = $request['test_email'];
		if ( self::NEWSPACK_NEWSLETTERS_CPT !== get_post_type( $id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Post is not a Newsletter.', 'newspack-newsletters' )
			);
		}
		$mc_campaign_id = get_post_meta( $id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_no_campaign_id',
				__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
			);
		}
		try {
			$mc = new Mailchimp( self::mailchimp_api_key() );

			$test_emails = explode( ',', $test_email );
			foreach ( $test_emails as &$email ) {
				$email = sanitize_email( trim( $email ) );
			}
			$payload = [
				'test_emails' => $test_emails,
				'send_type'   => 'html',
			];
			$result  = self::validate_mailchimp_operation(
				$mc->post(
					"campaigns/$mc_campaign_id/actions/test",
					$payload
				),
				__( 'Error sending test email.', 'newspack_newsletters' )
			);

			$data            = self::retrieve_data( $id );
			$data['result']  = $result;
			$data['message'] = sprintf(
				// translators: Message after successful test email.
				__( 'Mailchimp test sent successfully to %s.', 'newspack-newsletters' ),
				implode( ' ', $test_emails )
			);

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Get Mailchimp data.
	 *
	 * @param string $id post ID.
	 */
	public static function retrieve_data( $id ) {
		$transient       = sprintf( 'newspack_newsletters_error_%s_%s', $id, get_current_user_id() );
		$persisted_error = get_transient( $transient );
		if ( $persisted_error ) {
			delete_transient( $transient );
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$persisted_error
			);
		}

		try {
			$mc_campaign_id = get_post_meta( $id, 'mc_campaign_id', true );
			if ( ! $mc_campaign_id ) {
				return new WP_Error(
					'newspack_newsletters_mailchimp_error',
					__( 'No Mailchimp campaign ID found for this Newsletter', 'newspack-newsletter' )
				);
			}
			$mc                  = new Mailchimp( self::mailchimp_api_key() );
			$campaign            = self::validate_mailchimp_operation(
				$mc->get( "campaigns/$mc_campaign_id" ),
				__( 'Error retrieving Mailchimp campaign.', 'newspack_newsletters' )
			);
			$list_id             = $campaign && isset( $campaign['recipients']['list_id'] ) ? $campaign['recipients']['list_id'] : null;
			$interest_categories = $list_id ? self::validate_mailchimp_operation(
				$mc->get( "lists/$list_id/interest-categories" ),
				__( 'Error retrieving Mailchimp groups.', 'newspack_newsletters' )
			) : null;
			if ( $interest_categories && count( $interest_categories['categories'] ) ) {
				foreach ( $interest_categories['categories'] as &$category ) {
					$category_id           = $category['id'];
					$category['interests'] = self::validate_mailchimp_operation(
						$mc->get( "lists/$list_id/interest-categories/$category_id/interests" ),
						__( 'Error retrieving Mailchimp groups.', 'newspack_newsletters' )
					);
				}
			}

			return [
				'lists'               => self::validate_mailchimp_operation(
					$mc->get(
						'lists',
						[
							'count' => 1000,
						]
					),
					__( 'Error retrieving Mailchimp lists.', 'newspack_newsletters' )
				),
				'campaign'            => $campaign,
				'campaign_id'         => $mc_campaign_id,
				'interest_categories' => $interest_categories,
			];
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Get Mailchimp API key.
	 */
	public static function mailchimp_api_key() {
		return get_option( 'newspack_newsletters_mailchimp_api_key', false );
	}

	/**
	 * Check capabilities for using the API for administration tasks.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public static function api_administration_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}

	/**
	 * Check capabilities for using the API for authoring tasks.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public static function api_authoring_permissions_check( $request ) {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}

	/**
	 * Callback for CPT save. Will sync with Mailchimp.
	 *
	 * @param string  $id post ID.
	 * @param WP_Post $post the post.
	 * @param boolean $update Is this an update of the post.
	 */
	public static function save_post( $id, $post, $update ) {
		if ( ! $update ) {
			update_post_meta( $id, 'template_id', -1 );
		}
		$status = get_post_status( $id );
		if ( 'trash' === $status ) {
			return;
		}
		self::sync_with_mailchimp( $post );
	}

	/**
	 * Callback for CPT trashing. Will delete corresponding campaign on Mailchimp.
	 *
	 * @param string $id post ID.
	 */
	public static function trash_post( $id ) {
		if ( self::NEWSPACK_NEWSLETTERS_CPT !== get_post_type( $id ) ) {
			return;
		}
		$mc_campaign_id = get_post_meta( $id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return;
		}

		$api_key = self::mailchimp_api_key();
		if ( ! $api_key ) {
			return;
		}
		try {
			$mc       = new Mailchimp( $api_key );
			$campaign = $mc->get( "campaigns/$mc_campaign_id" );
			if ( $campaign ) {
				$status = $campaign['status'];
				if ( ! in_array( $status, [ 'sent', 'sending' ] ) ) {
					$result = $mc->delete( "campaigns/$mc_campaign_id" );
					delete_post_meta( $id, 'mc_campaign_id', $mc_campaign_id );
				}
			}
		} catch ( Exception $e ) {
			return; // Fail silently.
		}
	}

	/**
	 * Synchronize CPT with Mailchimp campaign.
	 *
	 * @param WP_Post $post the post.
	 */
	public static function sync_with_mailchimp( $post ) {
		$api_key = self::mailchimp_api_key();
		if ( ! $api_key ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'No Mailchimp API key available.', 'newspack-newsletters' )
			);
		}
		try {
			$mc      = new Mailchimp( $api_key );
			$payload = [
				'type'         => 'regular',
				'content_type' => 'template',
				'settings'     => [
					'subject_line' => $post->post_title,
					'title'        => $post->post_title,
				],
			];

			$mc_campaign_id = get_post_meta( $post->ID, 'mc_campaign_id', true );
			if ( $mc_campaign_id ) {
				$campaign_result = self::validate_mailchimp_operation(
					$mc->patch( "campaigns/$mc_campaign_id", $payload ),
					__( 'Error updating campaign title.', 'newspack_newsletters' )
				);
			} else {
				$campaign_result = self::validate_mailchimp_operation(
					$mc->post( 'campaigns', $payload ),
					__( 'Error setting campaign title.', 'newspack_newsletters' )
				);
				$mc_campaign_id  = $campaign_result['id'];
				update_post_meta( $post->ID, 'mc_campaign_id', $mc_campaign_id );
			}

			// Prevent updating content of a sent campaign.
			if ( in_array( $campaign_result['status'], [ 'sent', 'sending' ] ) ) {
				return;
			}

			$renderer        = new Newspack_Newsletters_Renderer();
			$content_payload = [
				'html' => $renderer->render_html_email( $post ),
			];

			$content_result = self::validate_mailchimp_operation(
				$mc->put( "campaigns/$mc_campaign_id/content", $content_payload ),
				__( 'Error updating campaign content.', 'newspack_newsletters' )
			);
			return [
				'campaign_result' => $campaign_result,
				'content_result'  => $content_result,
			];
		} catch ( Exception $e ) {
			$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
			set_transient( $transient, $e->getMessage(), 45 );
			return;
		}
	}

	/**
	 * Callback for CPT publish. Sends the campaign.
	 *
	 * @param string  $id post ID.
	 * @param WP_Post $post the post.
	 */
	public static function send_campaign( $id, $post ) {
		if ( self::NEWSPACK_NEWSLETTERS_CPT !== get_post_type( $id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Post is not a Newsletter.', 'newspack-newsletters' )
			);
		}

		try {
			$sync_result = self::sync_with_mailchimp( $post );

			if ( is_wp_error( $sync_result ) ) {
				return $sync_result;
			}

			$mc_campaign_id = get_post_meta( $id, 'mc_campaign_id', true );
			if ( ! $mc_campaign_id ) {
				return new WP_Error(
					'newspack_newsletters_no_campaign_id',
					__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
				);
			}

			$mc = new Mailchimp( self::mailchimp_api_key() );

			$payload = [
				'send_type' => 'html',
			];
			$result  = self::validate_mailchimp_operation(
				$mc->post( "campaigns/$mc_campaign_id/actions/send", $payload ),
				__( 'Error sending campaign.', 'newspack_newsletters' )
			);
		} catch ( Exception $e ) {
			$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
			set_transient( $transient, $e->getMessage(), 45 );
			return;
		}
	}

	/**
	 * Set initial title of newsletter.
	 *
	 * @param string  $post_title Post title.
	 * @param WP_Post $post Post.
	 * @return string Title.
	 */
	public static function default_title( $post_title, $post ) {
		if ( self::NEWSPACK_NEWSLETTERS_CPT === get_post_type( $post ) ) {
			$post_title = gmdate( get_option( 'date_format' ) );
		}
		return $post_title;
	}

	/**
	 * Activation Nag
	 */

	/**
	 * Add admin notice if API keys are unset.
	 */
	public static function activation_nag() {
		$screen = get_current_screen();
		if ( 'settings_page_newspack-newsletters-settings-admin' === $screen->base || self::NEWSPACK_NEWSLETTERS_CPT === $screen->post_type ) {
			return;
		}
		$url = admin_url( 'edit.php?post_type=' . self::NEWSPACK_NEWSLETTERS_CPT . '&page=newspack-newsletters-settings-admin' );
		?>
		<div class="notice notice-info is-dismissible newspack-newsletters-notification-nag">
			<p>
				<?php
					echo wp_kses_post(
						sprintf(
							// translators: urge users to input their API keys on settings page.
							__( 'Thank you for activating Newspack Newsletters. Please <a href="%s">head to settings</a> to set up your API keys.', 'newspack-newsletters' ),
							$url
						)
					);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Enqueue script to handle activation nag dismissal.
	 */
	public static function activation_nag_dismissal_script() {
		$script = 'newspack-newsletters-activation_nag_dismissal';
		wp_register_script(
			$script,
			plugins_url( '../dist/admin.js', __FILE__ ),
			[ 'jquery' ],
			'1.0',
			false
		);
		wp_localize_script(
			$script,
			'newspack_newsletters_activation_nag_dismissal_params',
			[
				'ajaxurl' => get_admin_url() . 'admin-ajax.php',
			]
		);
		wp_enqueue_script( $script );
	}

	/**
	 * AJAX callback after nag has been dismissed.
	 */
	public static function activation_nag_dismissal_ajax() {
		update_option( 'newspack_newsletters_activation_nag_viewed', true );
	}

	/**
	 * Throw an Exception if Mailchimp response indicates an error.
	 *
	 * @param object $result Result of the Mailchimp operation.
	 * @param string $preferred_error Preset error to use instead of Mailchimp errors.
	 * @throws Exception Error message.
	 */
	public static function validate_mailchimp_operation( $result, $preferred_error = null ) {
		if ( ! $result ) {
			if ( $preferred_error ) {
				throw new Exception( $preferred_error );
			} else {
				throw new Exception( __( 'A Mailchimp error has occurred.', 'newspack-newsletters' ) );
			}
		}
		if ( ! empty( $result['status'] ) && in_array( $result['status'], [ 400, 404 ] ) ) {
			if ( $preferred_error && ! self::debug_mode() ) {
				throw new Exception( $preferred_error );
			}
			$messages = [];
			if ( ! empty( $result['errors'] ) ) {
				foreach ( $result['errors'] as $error ) {
					if ( ! empty( $error['message'] ) ) {
						$messages[] = $error['message'];
					}
				}
			}
			if ( ! count( $messages ) && ! empty( $result['detail'] ) ) {
				$messages[] = $result['detail'];
			}
			if ( ! count( $messages ) ) {
				$message[] = __( 'A Mailchimp error has occurred.', 'newspack-newsletters' );
			}
			throw new Exception( implode( ' ', $messages ) );
		}
		return $result;
	}

	/**
	 * Is wp-config debug flag set.
	 *
	 * @return boolean Is debug mode on?
	 */
	public static function debug_mode() {
		return defined( 'NEWSPACK_NEWSLETTERS_DEBUG_MODE' ) ? NEWSPACK_NEWSLETTERS_DEBUG_MODE : false;
	}
}
Newspack_Newsletters::instance();
