<?php
/**
 * Newspack Newsletter Author
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

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
	 * Instance of the service provider class.
	 *
	 * @var Newspack_Newsletters_Service_Provider
	 */
	protected static $provider = null;

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
		add_filter( 'display_post_states', [ __CLASS__, 'display_post_states' ], 10, 2 );

		switch ( self::service_provider() ) {
			case 'mailchimp':
				self::$provider = Newspack_Newsletters_Mailchimp::instance();
				break;
			case 'constant_contact':
				self::$provider = Newspack_Newsletters_Constant_Contact::instance();
				break;
		}

		$needs_nag = is_admin() &&
			( ! self::$provider->api_key() || ! get_option( 'newspack_newsletters_mjml_api_key', false ) || ! get_option( 'newspack_newsletters_mjml_api_secret', false ) ) &&
			! get_option( 'newspack_newsletters_activation_nag_viewed', false );

		if ( $needs_nag ) {
			add_action( 'admin_notices', [ __CLASS__, 'activation_nag' ] );
			add_action( 'admin_enqueue_scripts', [ __CLASS__, 'activation_nag_dismissal_script' ] );
			add_action( 'wp_ajax_newspack_newsletters_activation_nag_dismissal', [ __CLASS__, 'activation_nag_dismissal_ajax' ] );
		}
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
			'cc_campaign_id',
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
		\register_meta(
			'post',
			'preview_text',
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
			'diable_ads',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => true,
				'type'           => 'boolean',
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
	 * Filter post states in admin posts list.
	 *
	 * @param array   $post_states An array of post display states.
	 * @param WP_Post $post        The current post object.
	 * @return array The filtered $post_states array.
	 */
	public static function display_post_states( $post_states, $post ) {
		if ( self::NEWSPACK_NEWSLETTERS_CPT !== $post->post_type ) {
			return $post_states;
		}

		$post_status = get_post_status_object( $post->post_status );
		$is_sent     = 'publish' === $post_status->name;

		if ( $is_sent ) {
			$sent_date = get_the_time( 'U', $post );
			$time_diff = time() - $sent_date;
			$sent_date = human_time_diff( $sent_date, time() );

			// Show relative date if sent within the past 24 hours.
			if ( $time_diff < 86400 ) {
				/* translators: Relative time stamp of sent/published date */
				$post_states[ $post_status->name ] = sprintf( __( 'Sent %1$s ago', 'newspack-newsletters' ), $sent_date );
			} else {
				/* translators:  Absolute time stamp of sent/published date */
				$post_states[ $post_status->name ] = sprintf( __( 'Sent %1$s', 'newspack-newsletters' ), get_the_time( get_option( 'date_format' ), $post ) );
			}
		}

		return $post_states;
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
			'post-meta/(?P<id>[\a-z]+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_post_meta' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
				'args'                => [
					'id'    => [
						'validate_callback' => [ __CLASS__, 'validate_newsletter_id' ],
						'sanitize_callback' => 'absint',
					],
					'key'   => [
						'validate_callback' => [ __CLASS__, 'validate_newsletter_post_meta_key' ],
						'sanitize_callback' => 'sanitize_text_field',
					],
					'value' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'color-palette',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_color_palette' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
			]
		);
	}

	/**
	 * The default color palette lives in the editor frontend and is not
	 * retrievable on the backend. The workaround is to set it as an option
	 * so that it's available to the email renderer.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_color_palette( $request ) {
		update_option( 'newspack_newsletters_color_palette', $request->get_body() );
		return \rest_ensure_response( [] );
	}

	/**
	 * Set post meta.
	 * The save_post action fires before post meta is updated.
	 * This causes newsletters to be synced to the ESP before recent changes to custom fields have been recorded,
	 * which leads to incorrect rendering. This is addressed through custom endpoints to update the  fields
	 * as soon as they are changed in the editor, so that the changes are available the next time sync to ESP occurs.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_post_meta( $request ) {
		$id    = $request['id'];
		$key   = $request['key'];
		$value = $request['value'];
		update_post_meta( $id, $key, $value );
		return [];
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
	 * Validate meta key.
	 *
	 * @param String $key Meta key.
	 */
	public static function validate_newsletter_post_meta_key( $key ) {
		return in_array(
			$key,
			[
				'font_header',
				'font_body',
				'background_color',
				'preview_text',
				'diable_ads',
			]
		);
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
		$user_layouts  = array_map(
			function ( $post ) {
				$post->meta = [
					'background_color' => get_post_meta( $post->ID, 'background_color', true ),
					'font_body'        => get_post_meta( $post->ID, 'font_body', true ),
					'font_header'      => get_post_meta( $post->ID, 'font_header', true ),
				];
				return $post;
			},
			$layouts_query->get_posts()
		);
		$layouts       = array_merge(
			$user_layouts,
			Newspack_Newsletters_Layouts::get_default_layouts(),
			apply_filters( 'newspack_newsletters_templates', [] )
		);

		return \rest_ensure_response( $layouts );
	}

	/**
	 * Retrieve service API keys for API endpoints.
	 */
	public static function api_get_keys() {
		return \rest_ensure_response( self::api_keys() );
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
			$status = self::$provider->set_api_key( $mailchimp_api_key );
			if ( is_wp_error( $status ) ) {
				foreach ( $status->errors as $code => $message ) {
					$wp_error->add( $code, implode( ' ', $message ) );
				}
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
	 * Retrieve service API keys.
	 */
	public static function api_keys() {
		$mailchimp_api_key = self::$provider->api_key(); // For now, it will only be Mailchimp.
		$mjml_api_key      = get_option( 'newspack_newsletters_mjml_api_key', false );
		$mjml_api_secret   = get_option( 'newspack_newsletters_mjml_api_secret', false );

		return [
			'mailchimp_api_key' => $mailchimp_api_key ? $mailchimp_api_key : '',
			'mjml_api_key'      => $mjml_api_key ? $mjml_api_key : '',
			'mjml_api_secret'   => $mjml_api_secret ? $mjml_api_secret : '',
			'status'            => ! empty( $mailchimp_api_key ) && ! empty( $mjml_api_key ) && ! empty( $mjml_api_secret ),
		];
	}

	/**
	 * Are all the needed API keys available?
	 *
	 * @return bool Whether all API keys are set.
	 */
	public static function has_keys() {
		$keys = self::api_keys();
		return $keys['status'];
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
	 * Is wp-config debug flag set.
	 *
	 * @return boolean Is debug mode on?
	 */
	public static function debug_mode() {
		return defined( 'NEWSPACK_NEWSLETTERS_DEBUG_MODE' ) ? NEWSPACK_NEWSLETTERS_DEBUG_MODE : false;
	}

	/**
	 * Which Email Service Provider should be used.
	 *
	 * @return string Name of the Email Service Provider.
	 */
	public static function service_provider() {
		// TODO: UI for user input of API key in keys modal and settings page.
		if (
			defined( 'NEWSPACK_NEWSLETTERS_CONSTANT_CONTACT_API_KEY' ) &&
			defined( 'NEWSPACK_NEWSLETTERS_CONSTANT_CONTACT_ACCESS_TOKEN' ) &&
			NEWSPACK_NEWSLETTERS_CONSTANT_CONTACT_API_KEY &&
			NEWSPACK_NEWSLETTERS_CONSTANT_CONTACT_ACCESS_TOKEN
		) {
			return 'constant_contact';
		}
		return 'mailchimp'; // For now, Mailchimp is the only choice.
	}
}
Newspack_Newsletters::instance();
