<?php
/**
 * Newspack setup wizard. Required plugins, introduction, and data collection.
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error, WP_REST_Server;
defined( 'ABSPATH' ) || exit;
require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

define( 'NEWSPACK_SETUP_COMPLETE', 'newspack_setup_complete' );

/**
 * Setup Newspack.
 */
class Setup_Wizard extends Wizard {
	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-setup-wizard';
	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'install_plugins';

	/**
	 * An array of theme mods that are media library IDs.
	 *
	 * @var array
	 */
	protected $media_theme_mods = [ 'newspack_footer_logo', 'custom_logo' ];

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
		if ( ! get_option( NEWSPACK_SETUP_COMPLETE ) ) {
			add_action( 'current_screen', [ $this, 'redirect_to_setup' ] );
			add_action( 'admin_menu', [ $this, 'hide_non_setup_menu_items' ], 1000 );
		}
		add_filter( 'show_admin_bar', [ $this, 'show_admin_bar' ], 10, 2 ); // phpcs:ignore WordPressVIPMinimum.UserExperience.AdminBarRemoval.RemovalDetected
		$this->hidden = get_option( NEWSPACK_SETUP_COMPLETE, false );
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return esc_html__( 'Setup', 'newspack' );
	}
	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return esc_html__( 'Setup Newspack.', 'newspack' );
	}
	/**
	 * Get the expected duration of this wizard.
	 *
	 * @return string The wizard length.
	 */
	public function get_length() {
		return esc_html__( '10 minutes', 'newspack' );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		// Update option when setup is complete.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/complete',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_complete' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/theme',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_retrieve_theme_and_set_defaults' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/theme',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_theme_with_mods' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'theme_mods' => [
						'sanitize_callback' => [ $this, 'sanitize_theme_mods' ],
					],
					'theme'      => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/starter-content/categories',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_starter_content_categories' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/starter-content/post/(?P<id>[\a-z]+)',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_starter_content_post' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/starter-content/theme',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_starter_content_theme' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/starter-content/homepage',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_starter_content_homepage' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/services',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_services' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/services',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_services' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
	}

	/**
	 * Update option when setup is complete.
	 *
	 * @return WP_REST_Response containing info.
	 */
	public function api_complete() {
		update_option( NEWSPACK_SETUP_COMPLETE, true );
		return rest_ensure_response( [ 'complete' => true ] );
	}

	/**
	 * Install starter content categories
	 *
	 * @return WP_REST_Response containing info.
	 */
	public function api_starter_content_categories() {
		$status = Starter_Content::create_categories();
		return rest_ensure_response( [ 'status' => $status ] );
	}

	/**
	 * Install one starter content post
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing info.
	 */
	public function api_starter_content_post( $request ) {
		$id     = $request['id'];
		$status = Starter_Content::create_post( $id );
		return rest_ensure_response( [ 'status' => $status ] );
	}

	/**
	 * Set up initial theme mods
	 *
	 * @return WP_REST_Response containing info.
	 */
	public function api_starter_content_theme() {
		$status = Starter_Content::initialize_theme();
		return rest_ensure_response( [ 'status' => $status ] );
	}

	/**
	 * Set up Homepage
	 *
	 * @return WP_REST_Response containing info.
	 */
	public function api_starter_content_homepage() {
		$status = Starter_Content::create_homepage();
		return rest_ensure_response( [ 'status' => $status ] );
	}

	/**
	 * Get current theme & mods.
	 *
	 * @return WP_REST_Response containing info.
	 */
	public function api_retrieve_theme_and_set_defaults() {
		$theme_mods = get_theme_mods();

		$theme_mods['header_color'] = get_theme_mod( 'header_color', 'default' );
		// Force custom header color, since the default depends on the theme.
		if ( 'default' === $theme_mods['header_color'] ) {
			set_theme_mod( 'header_color', 'custom' );
			$theme_mods['header_color'] = get_theme_mod( 'header_color' );
		}

		foreach ( $theme_mods as $key => &$theme_mod ) {
			if ( in_array( $key, $this->media_theme_mods ) ) {
				$attachment = wp_get_attachment_image_src( $theme_mod, 'full' );
				if ( $attachment ) {
					$theme_mod = [
						'id'  => $theme_mod,
						'url' => is_array( $attachment ) ? $attachment[0] : null,
					];
				}
			}
		}
		$theme_mods['theme_colors'] = get_theme_mod( 'theme_colors', 'default' );
		if ( 'default' === $theme_mods['theme_colors'] ) {
			$theme_mods['primary_color_hex']   = newspack_get_primary_color();
			$theme_mods['secondary_color_hex'] = newspack_get_secondary_color();
		} else {
			$theme_mods['primary_color_hex']   = get_theme_mod( 'primary_color_hex', newspack_get_primary_color() );
			$theme_mods['secondary_color_hex'] = get_theme_mod( 'secondary_color_hex', newspack_get_secondary_color() );
		}

		if ( ! isset( $theme_mods['header_color_hex'] ) ) {
			set_theme_mod( 'header_color_hex', $theme_mods['primary_color_hex'] );
			$theme_mods['header_color_hex'] = get_theme_mod( 'header_color_hex' );
		}
		// Set custom header color to primary, if not set.

		$theme_mods['accent_allcaps'] = get_theme_mod( 'accent_allcaps', true );

		// Footer.
		$theme_mods['footer_color']     = get_theme_mod( 'footer_color', 'default' );
		$theme_mods['footer_logo_size'] = get_theme_mod( 'footer_logo_size', 'medium' );
		$theme_mods['footer_copyright'] = get_theme_mod( 'footer_copyright', false );
		if ( false === $theme_mods['footer_copyright'] ) {
			set_theme_mod( 'footer_copyright', get_option( 'blogdescription', '' ) );
			$theme_mods['footer_copyright'] = get_theme_mod( 'footer_copyright' );
		}

		$theme_mods['header_text']            = get_theme_mod( 'header_text', '' );
		$theme_mods['header_display_tagline'] = get_theme_mod( 'header_display_tagline', '' );

		return rest_ensure_response(
			[
				'theme'             => Starter_Content::get_theme(),
				'theme_mods'        => $theme_mods,
				'homepage_patterns' => $this->get_homepage_patterns(),
			]
		);
	}

	/**
	 * Load homepage pattern.
	 *
	 * @param string $file_path File path of the pattern.
	 * @return string The pattern markup.
	 */
	private function load_homepage_pattern( $file_path ) {
		$has_donations   = $this->check_service_enabled( 'reader-revenue' );
		$has_newsletters = $this->check_service_enabled( 'newsletters' );
		$donate_block    = $has_donations ? '
		<!-- wp:group {"align":"full","backgroundColor":"primary","className":"has-white-color has-text-color"} -->
		<div class="wp-block-group alignfull has-white-color has-text-color has-primary-background-color has-background"><div class="wp-block-group__inner-container"><!-- wp:spacer {"height":20} -->
		<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:columns -->
		<div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:heading -->
		<h2>' . __( 'Support our publication', 'newspack' ) . '</h2>
		<!-- /wp:heading --></div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"75%"} -->
		<div class="wp-block-column" style="flex-basis:75%"><!-- wp:newspack-blocks/donate /--></div>
		<!-- /wp:column --></div>

		<!-- wp:spacer {"height":20} -->
		<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer --></div></div>
		<!-- /wp:group -->
		' : '';
		$image_url       = 'https://picsum.photos/id/424/600/400	';
		$subscribe_block = $has_newsletters ? '<!-- wp:group {"className":"newspack-pattern subscribe__style-1"} -->
		<div class="wp-block-group newspack-pattern subscribe__style-1"><div class="wp-block-group__inner-container"><!-- wp:media-text {"align":"","mediaPosition":"right","mediaId":131,"mediaType":"image","verticalAlignment":"center","imageFill":true,"backgroundColor":"light-gray"} -->
		<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center is-image-fill has-light-gray-background-color has-background"><figure class="wp-block-media-text__media" style="background-image:url(' . $image_url . ');background-position:50% 50%"><img src="' . $image_url . '" alt="" class="wp-image-131 size-full"/></figure><div class="wp-block-media-text__content"><!-- wp:spacer {"height":32} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:heading -->
		<h2>' . __( 'Subscribe', 'newspack' ) . '</h2>
		<!-- /wp:heading -->

		<!-- wp:jetpack/mailchimp {"interests":[]} -->
		<!-- wp:jetpack/button {"element":"button","uniqueId":"mailchimp-widget-id","text":"Join my email list"} /-->
		<!-- /wp:jetpack/mailchimp -->

		<!-- wp:spacer {"height":32} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer --></div></div>
		<!-- /wp:media-text --></div></div>
		<!-- /wp:group -->' : '';
		return require $file_path;
	}

	/**
	 * Get homepage patterns registered by the Newspack Blocks plugin.
	 *
	 * @param int $index Pattern ID.
	 * @return array|object|bool Homepage pattern (false if not found) or all patterns.
	 */
	private function get_homepage_patterns( $index = null ) {
		$patterns_directory = dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/templates/block-patterns/homepage/';

		// Load a single homepage pattern.
		if ( null !== $index ) {
			$index_padded = str_pad( $index + 1, 2, '0', STR_PAD_LEFT );
			$file_path    = $patterns_directory . $index_padded . '.php';
			if ( file_exists( $file_path ) ) {
				return $this->load_homepage_pattern( $file_path );
			} else {
				return false;
			}
		}

		// Load all homepage patterns.
		foreach ( scandir( $patterns_directory ) as $file_name ) {
			if ( '.' !== $file_name && '..' !== $file_name ) {
				$file_path           = $patterns_directory . $file_name;
				$homepage_patterns[] = $this->load_homepage_pattern( $file_path );
			}
		}

		return $homepage_patterns;
	}

	/**
	 * Update theme
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing info.
	 */
	public function api_update_theme_with_mods( $request ) {
		// Set theme before updating theme mods, since a theme might be setting theme mod defaults.
		$theme = $request['theme'];
		Starter_Content::set_theme( $theme );

		// Set homepage pattern.
		if ( isset( $request['theme_mods']['homepage_pattern_index'] ) ) {
			$homepage_pattern_index = (int) $request['theme_mods']['homepage_pattern_index'];
			$homepage_pattern       = $this->get_homepage_patterns( $homepage_pattern_index );
			if ( false !== $homepage_pattern ) {
				$homepage_id = get_option( 'page_on_front', false );
				if ( $homepage_id ) {
					wp_update_post(
						[
							'ID'           => $homepage_id,
							'post_content' => $homepage_pattern['content'],
						]
					);
				}
			}
		}

		$theme_mods = $request['theme_mods'];
		foreach ( $theme_mods as $key => $value ) {
			if ( null !== $value && in_array( $key, $this->media_theme_mods ) ) {
				$value = $value['id'];
			}
			set_theme_mod( $key, $value );
		}
		return self::api_retrieve_theme_and_set_defaults();
	}

	/**
	 * Check if a particular service is enabled.
	 *
	 * @param string $service_name Name of the service.
	 * @return bool True if the service is enabled.
	 */
	private function check_service_enabled( $service_name ) {
		switch ( $service_name ) {
			case 'reader-revenue':
				$rr_wizard = new Reader_Revenue_Wizard();
				return isset( $rr_wizard->fetch_all_data()['platform_data']['platform'] );
			case 'newsletters':
				$newsletters_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-newsletters' );
				return $newsletters_configuration_manager->is_esp_set_up();
			case 'google-ad-sense':
				$ads_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );
				return $ads_configuration_manager->is_service_enabled( 'google_adsense' );
			case 'google-ad-manager':
				$ads_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );
				return $ads_configuration_manager->is_service_enabled( 'google_ad_manager' );
			default:
				return false;
		}
	}

	/**
	 * Get Services step initial data.
	 *
	 * @return WP_REST_Response containing info.
	 */
	public function api_get_services() {
		$response = [
			'reader-revenue'    => [ 'configuration' => [ 'is_service_enabled' => $this->check_service_enabled( 'reader-revenue' ) ] ],
			'newsletters'       => [ 'configuration' => [ 'is_service_enabled' => $this->check_service_enabled( 'newsletters' ) ] ],
			'google-ad-sense'   => [ 'configuration' => [ 'is_service_enabled' => $this->check_service_enabled( 'google-ad-sense' ) ] ],
			'google-ad-manager' => [ 'configuration' => [ 'is_service_enabled' => $this->check_service_enabled( 'google-ad-manager' ) ] ],
		];
		return rest_ensure_response( $response );
	}

	/**
	 * Update Services step.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing info.
	 */
	public function api_update_services( $request ) {
		if ( isset( $request['newsletters']['is_service_enabled'] ) ) {
			$newsletters_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-newsletters' );
			$newsletters_configuration_manager->update_settings( $request['newsletters'] );
		}
		if ( isset( $request['reader-revenue']['is_service_enabled'] ) ) {
			$rr_wizard = new Reader_Revenue_Wizard();
			if ( $request['reader-revenue']['donation_data'] ) {
				$rr_wizard->update_donation_settings( $request['reader-revenue']['donation_data'] );
			}
			if ( $request['reader-revenue']['stripe_data'] ) {
				$stripe_settings            = $request['reader-revenue']['stripe_data'];
				$stripe_settings['enabled'] = true;
				$rr_wizard->update_stripe_settings( $stripe_settings );
			}
		}
		if ( isset( $request['google-ad-manager']['is_service_enabled'], $request['google-ad-manager']['network_code'] ) ) {
			$ads_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );
			$ads_configuration_manager->set_network_code( 'google_ad_manager', $request['google-ad-manager']['network_code'] );
		}
		if ( isset( $request['google-ad-sense']['is_service_enabled'] ) ) {
			$sitekit_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'google-site-kit' );
			if ( $request['google-ad-sense']['is_service_enabled'] ) {
				$sitekit_configuration_manager->activate_module( 'adsense' );
			}
		}

		return rest_ensure_response( [] );
	}

	/**
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();
		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}
		wp_enqueue_script(
			'newspack-setup-wizard',
			Newspack::plugin_url() . '/dist/setup.js',
			$this->get_script_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/setup.js' ),
			true
		);
		wp_register_style(
			'newspack-setup-wizard',
			Newspack::plugin_url() . '/dist/setup.css',
			$this->get_style_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/setup.css' )
		);
		wp_style_add_data( 'newspack-setup-wizard', 'rtl', 'replace' );
		wp_enqueue_style( 'newspack-setup-wizard' );
	}

	/**
	 * Hide all menu items besides setup if setup is incomplete.
	 */
	public function hide_non_setup_menu_items() {
		global $submenu;
		if ( ! current_user_can( $this->capability ) ) {
			return;
		}
		foreach ( $submenu['newspack'] as $key => $value ) {
			if ( 'newspack-setup-wizard' !== $value[2] ) {
				unset( $submenu['newspack'][ $key ] );
			}
		}
	}

	/**
	 * If initial setup is incomplete, redirect to Setup Wizard.
	 */
	public function redirect_to_setup() {
		$screen = get_current_screen();
		if ( $screen && 'toplevel_page_newspack' === $screen->id ) {
			$setup_url = Wizards::get_url( 'setup' );
			wp_safe_redirect( esc_url( $setup_url ) );
			exit;
		}
	}

	/**
	 * Should admin bar be shown.
	 *
	 * @param bool $show Whether to show admin bar.
	 * @return boolean Whether admin bar should be shown.
	 */
	public static function show_admin_bar( $show ) {
		if ( $show && isset( $_GET['newspack_design_preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return false;
		}
		return $show;
	}

	/**
	 * Sanitize theme mods.
	 *
	 * @param array $theme_mods An array of theme mods.
	 */
	public function sanitize_theme_mods( $theme_mods ) {
		return $theme_mods;
	}
}
