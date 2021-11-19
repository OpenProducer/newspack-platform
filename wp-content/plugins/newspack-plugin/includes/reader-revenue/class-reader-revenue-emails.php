<?php
/**
 * Reader-revenue related emails.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Reader-revenue related emails.
 */
class Reader_Revenue_Emails {
	const POST_TYPE          = 'newspack_rr_email';
	const POST_TYPE_META     = 'newspack_email_type';
	const EMAIL_TYPE_RECEIPT = 'receipt';

	const DYNAMIC_CONTENT_PLACEHOLDERS = [
		'AMOUNT'         => '*AMOUNT*',
		'DATE'           => '*DATE*',
		'PAYMENT_METHOD' => '*PAYMENT_METHOD*',
		'CONTACT_EMAIL'  => '*CONTACT_EMAIL*',
		'RECEIPT_URL'    => '*RECEIPT_URL*',
	];

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'rest_api_init', [ __CLASS__, 'register_api_endpoints' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
		add_filter( 'newspack_newsletters_email_editor_cpts', [ __CLASS__, 'register_email_cpt_with_email_editor' ] );
		add_filter( 'newspack_newsletters_allowed_editor_actions', [ __CLASS__, 'register_scripts_enqueue_with_email_editor' ] );
	}

	/**
	 * Register the custom post type as edited-as-email.
	 *
	 * @param array $email_editor_cpts Post type which should be edited as emails.
	 * @codeCoverageIgnore
	 */
	public static function register_email_cpt_with_email_editor( $email_editor_cpts ) {
		$email_editor_cpts[] = self::POST_TYPE;
		return $email_editor_cpts;
	}

	/**
	 * Register the editor scripts as allowed when editing email.
	 *
	 * @param array $allowed_actions Actions allowed when enqueuing assets for the block editor.
	 * @codeCoverageIgnore
	 */
	public static function register_scripts_enqueue_with_email_editor( $allowed_actions ) {
		$allowed_actions[] = __CLASS__ . '::enqueue_block_editor_assets';
		return $allowed_actions;
	}

	/**
	 * Register the custom post type for emails.
	 *
	 * @codeCoverageIgnore
	 */
	public static function register_cpt() {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}

		$labels = [
			'name'                     => _x( 'Reader Revenue Emails', 'post type general name', 'newspack' ),
			'singular_name'            => _x( 'Reader Revenue Email', 'post type singular name', 'newspack' ),
			'menu_name'                => _x( 'Reader Revenue Emails', 'admin menu', 'newspack' ),
			'name_admin_bar'           => _x( 'Reader Revenue Email', 'add new on admin bar', 'newspack' ),
			'add_new'                  => _x( 'Add New', 'popup', 'newspack' ),
			'add_new_item'             => __( 'Add New Reader Revenue Email', 'newspack' ),
			'new_item'                 => __( 'New Reader Revenue Email', 'newspack' ),
			'edit_item'                => __( 'Edit Reader Revenue Email', 'newspack' ),
			'view_item'                => __( 'View Reader Revenue Email', 'newspack' ),
			'all_items'                => __( 'All Reader Revenue Emails', 'newspack' ),
			'search_items'             => __( 'Search Reader Revenue Emails', 'newspack' ),
			'parent_item_colon'        => __( 'Parent Reader Revenue Emails:', 'newspack' ),
			'not_found'                => __( 'No Reader Revenue Emails found.', 'newspack' ),
			'not_found_in_trash'       => __( 'No Reader Revenue Emails found in Trash.', 'newspack' ),
			'items_list'               => __( 'Reader Revenue Emails list', 'newspack' ),
			'item_published'           => __( 'Reader Revenue Email published', 'newspack' ),
			'item_published_privately' => __( 'Reader Revenue Email published privately', 'newspack' ),
			'item_reverted_to_draft'   => __( 'Reader Revenue Email reverted to draft', 'newspack' ),
			'item_scheduled'           => __( 'Reader Revenue Email scheduled', 'newspack' ),
			'item_updated'             => __( 'Reader Revenue Email updated', 'newspack' ),
		];

		\register_post_type(
			self::POST_TYPE,
			[
				'public'       => false,
				'labels'       => $labels,
				'show_ui'      => true,
				'show_in_menu' => false,
				'show_in_rest' => true,
				'supports'     => [ 'editor', 'title', 'custom-fields' ],
				'taxonomies'   => [],
			]
		);
	}

	/**
	 * Load up common JS/CSS for newsletter editor.
	 *
	 * @codeCoverageIgnore
	 */
	public static function enqueue_block_editor_assets() {
		if ( get_post_type() !== self::POST_TYPE ) {
			return;
		}
		Newspack::load_common_assets();
		$handle = 'revenue-email-editor';
		\wp_register_script(
			$handle,
			Newspack::plugin_url() . '/dist/other-scripts/reader-revenue-emails.js',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/other-scripts/reader-revenue-emails.js' ),
			true
		);
		\wp_localize_script(
			$handle,
			'newspack_emails',
			[
				'current_user_email' => wp_get_current_user()->user_email,
			]
		);
		wp_enqueue_script( $handle );

		\wp_register_style(
			$handle,
			Newspack::plugin_url() . '/dist/other-scripts/reader-revenue-emails.css',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/other-scripts/reader-revenue-emails.css' )
		);
		\wp_style_add_data( $handle, 'rtl', 'replace' );
		\wp_enqueue_style( $handle );
	}

	/**
	 * Register custom fields.
	 *
	 * @codeCoverageIgnore
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			self::POST_TYPE_META,
			[
				'object_subtype' => self::POST_TYPE,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'from_name',
			[
				'object_subtype' => self::POST_TYPE,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
				'default'        => get_bloginfo( 'name' ),
			]
		);
		\register_meta(
			'post',
			'from_email',
			[
				'object_subtype' => self::POST_TYPE,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
				'default'        => get_bloginfo( 'admin_email' ),
			]
		);
	}

	/**
	 * Send an HTML email.
	 *
	 * @param string|int $type_or_post_id Email type or email post ID.
	 * @param string     $to Recipient's email addesss.
	 * @param array      $placeholders Dynamic content substitutions.
	 */
	public static function send_email( $type_or_post_id, $to, $placeholders = [] ) {
		if ( ! self::supports_emails() ) {
			return false;
		}
		if ( 'string' === gettype( $type_or_post_id ) ) {
			$email_config = self::get_email_config_by_type( $type_or_post_id );
		} elseif ( 'integer' === gettype( $type_or_post_id ) ) {
			$email_config = self::serialize_email( null, $type_or_post_id );
		} else {
			return false;
		}
		if ( ! $to || false === $email_config || 'publish' !== $email_config['status'] ) {
			return false;
		}
		$html           = $email_config['html_payload'];
		$from_email     = $email_config['from_email'];
		$placeholders[] = [
			'template' => self::DYNAMIC_CONTENT_PLACEHOLDERS['CONTACT_EMAIL'],
			'value'    => sprintf( '<a href="mailto:%s">%s</a>', $from_email, $from_email ),
		];
		foreach ( $placeholders as $value ) {
			$html = str_replace(
				$value['template'],
				$value['value'],
				$html
			);
		}
		$email_content_type = function() {
			return 'text/html';
		};
		add_filter( 'wp_mail_content_type', $email_content_type );
		$email_send_result = wp_mail( // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_wp_mail
			$to,
			$email_config['subject'],
			$html,
			[
				sprintf( 'From: %s <%s>', $email_config['from_name'], $from_email ),
			]
		);
		remove_filter( 'wp_mail_content_type', $email_content_type );
		return $email_send_result;
	}

	/**
	 * Load a template of an email.
	 *
	 * @param string $type Email type.
	 */
	private static function load_email_template( $type ) {
		$templates_directory = dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/templates/reader-revenue-emails/';
		return require $templates_directory . $type . '.php';
	}

	/**
	 * Does this instance support emails?
	 */
	public static function supports_emails() {
		if ( ! class_exists( 'Newspack_Newsletters' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Can email of a particular type be sent?
	 *
	 * @param string $type Type of the email.
	 */
	public static function can_send_email( $type ) {
		if ( ! self::supports_emails() ) {
			return false;
		}
		$email_config = self::get_email_config_by_type( $type );
		if ( ! $email_config || 'publish' !== $email_config['status'] ) {
			return false;
		}
		return true;
	}

	/**
	 * Serialize an email config.
	 *
	 * @param string $type Type of the email.
	 * @param int    $post_id Email post id.
	 */
	private static function serialize_email( $type = null, $post_id = 0 ) {
		if ( ! self::supports_emails() ) {
			return false;
		}
		switch ( $type ) {
			case 'receipt':
				$label       = __( 'Receipt', 'newspack' );
				$description = __( "Email sent to the donor after they've donated.", 'newspack' );
				break;
			default:
				$label       = '';
				$description = '';
				break;
		}
		$html_payload = get_post_meta( $post_id, \Newspack_Newsletters::EMAIL_HTML_META, true );
		if ( ! $html_payload || empty( $html_payload ) ) {
			return false;
		}
		$serialized_email = [
			'label'        => $label,
			'description'  => $description,
			'post_id'      => $post_id,
			// Make the edit link relative.
			'edit_link'    => str_replace( site_url(), '', get_edit_post_link( $post_id, '' ) ),
			'subject'      => get_the_title( $post_id ),
			'from_name'    => get_post_meta( $post_id, 'from_name', true ),
			'from_email'   => get_post_meta( $post_id, 'from_email', true ),
			'status'       => get_post_status( $post_id ),
			'html_payload' => $html_payload,
		];
		return $serialized_email;
	}

	/**
	 * Get the email for a specific type.
	 * If the email does not exist, it will be created based on default template.
	 *
	 * @param string $type Type of the email.
	 */
	private static function get_email_config_by_type( $type ) {
		$emails_query = new \WP_Query(
			[
				'post_type'      => self::POST_TYPE,
				'posts_per_page' => 1,
				'post_status'    => 'any',
				'meta_key'       => self::POST_TYPE_META,
				'meta_value'     => $type, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			]
		);
		if ( $emails_query->post ) {
			return self::serialize_email( $type, $emails_query->post->ID );
		} else {
			$email_post_data                = self::load_email_template( self::EMAIL_TYPE_RECEIPT );
			$email_post_data['post_status'] = 'publish';
			$email_post_data['post_type']   = self::POST_TYPE;
			$email_post_data['meta_input']  = [
				self::POST_TYPE_META                   => self::EMAIL_TYPE_RECEIPT,
				\Newspack_Newsletters::EMAIL_HTML_META => $email_post_data['email_html'],
			];
			$post_id                        = wp_insert_post( $email_post_data );
			error_log( sprintf( 'Newspack: creating reader revenue email of type %s (id: %s).', $type, $post_id ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			return self::serialize_email(
				self::EMAIL_TYPE_RECEIPT,
				$post_id
			);
		}
	}

	/**
	 * Get the emails per-purpose.
	 */
	public static function get_emails() {
		$emails = [];
		if ( ! self::supports_emails() ) {
			return $emails;
		}
		$receipt_email = self::get_email_config_by_type( self::EMAIL_TYPE_RECEIPT );
		if ( $receipt_email ) {
			$emails[ self::EMAIL_TYPE_RECEIPT ] = $receipt_email;
		}
		return $emails;
	}

	/**
	 * Send a test email.
	 *
	 * @param WP_REST_Request $request Request.
	 */
	public static function api_send_test_email( $request ) {
		$was_sent = self::send_email(
			$request->get_param( 'post_id' ),
			$request->get_param( 'recipient' )
		);
		if ( $was_sent ) {
			return \rest_ensure_response( [] );
		} else {
			return new WP_Error(
				'newspack_test_email_not_sent',
				__( 'Test email was not sent.', 'newspack' )
			);
		}
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public static function register_api_endpoints() {
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/reader-revenue-emails/test',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_send_test_email' ],
				'permission_callback' => [ __CLASS__, 'api_permissions_check' ],
				'args'                => [
					'recipient' => [
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
					'post_id'   => [
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
	}

	/**
	 * Check capabilities for using API.
	 *
	 * @codeCoverageIgnore
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public static function api_permissions_check( $request ) {
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
}
Reader_Revenue_Emails::init();
