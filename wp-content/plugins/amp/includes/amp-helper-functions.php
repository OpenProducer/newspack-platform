<?php
/**
 * AMP Helper Functions
 *
 * @package AMP
 */

use AmpProject\AmpWP\Services;

/**
 * Handle activation of plugin.
 *
 * @since 0.2
 *
 * @param bool $network_wide Whether the activation was done network-wide.
 */
function amp_activate( $network_wide = false ) {
	Services::activate( $network_wide );
	amp_after_setup_theme();
	if ( ! did_action( 'amp_init' ) ) {
		amp_init();
	}
	flush_rewrite_rules();
}

/**
 * Handle deactivation of plugin.
 *
 * @since 0.2
 *
 * @param bool $network_wide Whether the activation was done network-wide.
 */
function amp_deactivate( $network_wide = false ) {
	Services::deactivate( $network_wide );
	// We need to manually remove the amp endpoint.
	global $wp_rewrite;
	foreach ( $wp_rewrite->endpoints as $index => $endpoint ) {
		if ( amp_get_slug() === $endpoint[1] ) {
			unset( $wp_rewrite->endpoints[ $index ] );
			break;
		}
	}

	flush_rewrite_rules( false );
}

/**
 * Bootstrap plugin.
 *
 * @since 1.5
 */
function amp_bootstrap_plugin() {
	Services::register();

	// The plugins_loaded action is the earliest we can run this since that is when pluggable.php has been required and wp_hash() is available.
	add_action( 'plugins_loaded', [ 'AMP_Validation_Manager', 'init_validate_request' ], ~PHP_INT_MAX );

	/*
	 * Register AMP scripts regardless of whether AMP is enabled or it is the AMP endpoint
	 * for the sake of being able to use AMP components on non-AMP documents ("dirty AMP").
	 */
	add_action( 'wp_default_scripts', 'amp_register_default_scripts' );

	// Ensure async and custom-element/custom-template attributes are present on script tags.
	add_filter( 'script_loader_tag', 'amp_filter_script_loader_tag', PHP_INT_MAX, 2 );

	// Ensure crossorigin=anonymous is added to font links.
	add_filter( 'style_loader_tag', 'amp_filter_font_style_loader_tag_with_crossorigin_anonymous', 10, 4 );

	add_action( 'after_setup_theme', 'amp_after_setup_theme', 5 );

	add_action( 'plugins_loaded', '_amp_bootstrap_customizer', 9 ); // Should be hooked before priority 10 on 'plugins_loaded' to properly unhook core panels.
}

/**
 * Init AMP.
 *
 * @since 0.1
 */
function amp_init() {

	/**
	 * Triggers on init when AMP plugin is active.
	 *
	 * @since 0.3
	 */
	do_action( 'amp_init' );

	add_filter( 'allowed_redirect_hosts', [ 'AMP_HTTP', 'filter_allowed_redirect_hosts' ] );
	AMP_HTTP::purge_amp_query_vars();
	AMP_HTTP::send_cors_headers();
	AMP_HTTP::handle_xhr_request();
	AMP_Theme_Support::init();
	AMP_Validation_Manager::init();
	AMP_Service_Worker::init();
	add_action( 'admin_init', 'AMP_Options_Manager::register_settings' );
	add_action( 'wp_loaded', 'amp_add_options_menu' );
	add_action( 'wp_loaded', 'amp_bootstrap_admin' );

	add_rewrite_endpoint( amp_get_slug(), EP_PERMALINK );
	AMP_Post_Type_Support::add_post_type_support();
	add_action( 'init', [ 'AMP_Post_Type_Support', 'add_post_type_support' ], 1000 ); // After post types have been defined.
	add_action( 'parse_query', 'amp_correct_query_when_is_front_page' );
	add_action( 'admin_bar_menu', 'amp_add_admin_bar_view_link', 100 );
	add_action( 'wp_loaded', 'amp_editor_core_blocks' );
	add_filter( 'request', 'amp_force_query_var_value' );

	// Redirect the old url of amp page to the updated url.
	add_filter( 'old_slug_redirect_url', 'amp_redirect_old_slug_to_new_url' );

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		if ( class_exists( 'WP_CLI\Dispatcher\CommandNamespace' ) ) {
			WP_CLI::add_command( 'amp', 'AMP_CLI_Namespace' );
		}

		WP_CLI::add_command( 'amp validation', 'AMP_CLI_Validation_Command' );
	}

	/*
	 * Broadcast plugin updates.
	 * Note that AMP_Options_Manager::get_option( 'version', '0.0' ) cannot be used because
	 * version was new option added, and in that case default would never be used for a site
	 * upgrading from a version prior to 1.0. So this is why get_option() is currently used.
	 */
	$options     = get_option( AMP_Options_Manager::OPTION_NAME, [] );
	$old_version = isset( $options['version'] ) ? $options['version'] : '0.0';
	if ( AMP__VERSION !== $old_version && is_admin() && current_user_can( 'manage_options' ) ) {
		/**
		 * Triggers when after amp_init when the plugin version has updated.
		 *
		 * @param string $old_version Old version.
		 */
		do_action( 'amp_plugin_update', $old_version );
		AMP_Options_Manager::update_option( 'version', AMP__VERSION );
	}
}

/**
 * Set up AMP.
 *
 * This function must be invoked through the 'after_setup_theme' action to allow
 * the AMP setting to declare the post types support earlier than plugins/theme.
 *
 * @since 0.6
 */
function amp_after_setup_theme() {
	amp_get_slug(); // Ensure AMP_QUERY_VAR is set.

	/**
	 * Filters whether AMP is enabled on the current site.
	 *
	 * Useful if the plugin is network activated and you want to turn it off on select sites.
	 *
	 * @since 0.2
	 */
	if ( false === apply_filters( 'amp_is_enabled', true ) ) {
		return;
	}

	add_action( 'init', 'amp_init', 0 ); // Must be 0 because widgets_init happens at init priority 1.
}

/**
 * Make sure the `amp` query var has an explicit value.
 *
 * This avoids issues when filtering the deprecated `query_string` hook.
 *
 * @since 0.3.3
 *
 * @param array $query_vars Query vars.
 * @return array Query vars.
 */
function amp_force_query_var_value( $query_vars ) {
	if ( isset( $query_vars[ amp_get_slug() ] ) && '' === $query_vars[ amp_get_slug() ] ) {
		$query_vars[ amp_get_slug() ] = 1;
	}
	return $query_vars;
}

/**
 * Fix up WP_Query for front page when amp query var is present.
 *
 * Normally the front page would not get served if a query var is present other than preview, page, paged, and cpage.
 *
 * @since 0.6
 * @see WP_Query::parse_query()
 * @link https://github.com/WordPress/wordpress-develop/blob/0baa8ae85c670d338e78e408f8d6e301c6410c86/src/wp-includes/class-wp-query.php#L951-L971
 *
 * @param WP_Query $query Query.
 */
function amp_correct_query_when_is_front_page( WP_Query $query ) {
	$is_front_page_query = (
		$query->is_main_query()
		&&
		$query->is_home()
		&&
		// Is AMP endpoint.
		false !== $query->get( amp_get_slug(), false )
		&&
		// Is query not yet fixed uo up to be front page.
		! $query->is_front_page()
		&&
		// Is showing pages on front.
		'page' === get_option( 'show_on_front' )
		&&
		// Has page on front set.
		get_option( 'page_on_front' )
		&&
		// See line in WP_Query::parse_query() at <https://github.com/WordPress/wordpress-develop/blob/0baa8ae/src/wp-includes/class-wp-query.php#L961>.
		0 === count( array_diff( array_keys( wp_parse_args( $query->query ) ), [ amp_get_slug(), 'preview', 'page', 'paged', 'cpage' ] ) )
	);
	if ( $is_front_page_query ) {
		$query->is_home     = false;
		$query->is_page     = true;
		$query->is_singular = true;
		$query->set( 'page_id', get_option( 'page_on_front' ) );
	}
}

/**
 * Whether this is in 'canonical mode'.
 *
 * Themes can register support for this with `add_theme_support( AMP_Theme_Support::SLUG )`:
 *
 *      add_theme_support( AMP_Theme_Support::SLUG );
 *
 * This will serve templates in AMP-first, allowing you to use AMP components in your theme templates.
 * If you want to make available in transitional mode, where templates are served in AMP or non-AMP documents, do:
 *
 *      add_theme_support( AMP_Theme_Support::SLUG, array(
 *          'paired' => true,
 *      ) );
 *
 * Transitional mode is also implied if you define a template_dir:
 *
 *      add_theme_support( AMP_Theme_Support::SLUG, array(
 *          'template_dir' => 'amp',
 *      ) );
 *
 * If you want to have AMP-specific templates in addition to serving AMP-first, do:
 *
 *      add_theme_support( AMP_Theme_Support::SLUG, array(
 *          'paired'       => false,
 *          'template_dir' => 'amp',
 *      ) );
 *
 * If you want to force AMP to always be served on a given template, you can use the templates_supported arg,
 * for example to always serve the Category template in AMP:
 *
 *      add_theme_support( AMP_Theme_Support::SLUG, array(
 *          'templates_supported' => array(
 *              'is_category' => true,
 *          ),
 *      ) );
 *
 * Or if you want to force AMP to be used on all templates:
 *
 *      add_theme_support( AMP_Theme_Support::SLUG, array(
 *          'templates_supported' => 'all',
 *      ) );
 *
 * @see AMP_Theme_Support::read_theme_support()
 * @return boolean Whether this is in AMP 'canonical' mode, that is whether it is AMP-first and there is not a separate (paired) AMP URL.
 */
function amp_is_canonical() {
	if ( ! current_theme_supports( AMP_Theme_Support::SLUG ) ) {
		return false;
	}

	$args = AMP_Theme_Support::get_theme_support_args();
	if ( isset( $args[ AMP_Theme_Support::PAIRED_FLAG ] ) ) {
		return empty( $args[ AMP_Theme_Support::PAIRED_FLAG ] );
	}

	// If there is a template_dir, then transitional mode is implied.
	return empty( $args['template_dir'] );
}

/**
 * Add frontend actions.
 *
 * @since 0.2
 */
function amp_add_frontend_actions() {
	add_action( 'wp_head', 'amp_add_amphtml_link' );
}

/**
 * Bootstraps the AMP customizer.
 *
 * Uses the priority of 12 for the 'after_setup_theme' action.
 * Many themes run `add_theme_support()` on the 'after_setup_theme' hook, at the default priority of 10.
 * And that function's documentation suggests adding it to that action.
 * So this enables themes to `add_theme_support( AMP_Theme_Support::SLUG )`.
 * And `amp_init_customizer()` will be able to recognize theme support by calling `amp_is_canonical()`.
 *
 * @since 0.4
 */
function _amp_bootstrap_customizer() {
	add_action( 'after_setup_theme', 'amp_init_customizer', 12 );
}

/**
 * Redirects the old AMP URL to the new AMP URL.
 *
 * If post slug is updated the amp page with old post slug will be redirected to the updated url.
 *
 * @since 0.5
 *
 * @param string $link New URL of the post.
 * @return string URL to be redirected.
 */
function amp_redirect_old_slug_to_new_url( $link ) {

	if ( is_amp_endpoint() && ! amp_is_canonical() ) {
		if ( current_theme_supports( AMP_Theme_Support::SLUG ) ) {
			$link = add_query_arg( amp_get_slug(), '', $link );
		} else {
			$link = trailingslashit( trailingslashit( $link ) . amp_get_slug() );
		}
	}

	return $link;
}

/**
 * Get the slug used in AMP for the query var, endpoint, and post type support.
 *
 * The return value can be overridden by previously defining a AMP_QUERY_VAR
 * constant or by adding a 'amp_query_var' filter, but *warning* this ability
 * may be deprecated in the future. Normally the slug should be just 'amp'.
 *
 * @since 0.7
 *
 * @return string Slug used for query var, endpoint, and post type support.
 */
function amp_get_slug() {
	if ( defined( 'AMP_QUERY_VAR' ) ) {
		return AMP_QUERY_VAR;
	}

	/**
	 * Filter the AMP query variable.
	 *
	 * Warning: This filter may become deprecated.
	 *
	 * @since 0.3.2
	 *
	 * @param string $query_var The AMP query variable.
	 */
	$query_var = apply_filters( 'amp_query_var', 'amp' );

	define( 'AMP_QUERY_VAR', $query_var );

	return $query_var;
}

/**
 * Get the URL for the current request.
 *
 * This is essentially the REQUEST_URI prefixed by the scheme and host for the home URL.
 * This is needed in particular due to subdirectory installs.
 *
 * @since 1.0
 *
 * @return string Current URL.
 */
function amp_get_current_url() {
	$url = preg_replace( '#(^https?://[^/]+)/.*#', '$1', home_url( '/' ) );
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$url = esc_url_raw( $url . wp_unslash( $_SERVER['REQUEST_URI'] ) );
	} else {
		$url .= '/';
	}
	return $url;
}

/**
 * Retrieves the full AMP-specific permalink for the given post ID.
 *
 * @since 0.1
 *
 * @param int $post_id Post ID.
 * @return string AMP permalink.
 */
function amp_get_permalink( $post_id ) {

	// When theme support is present, the plain query var should always be used.
	if ( current_theme_supports( AMP_Theme_Support::SLUG ) ) {
		$permalink = get_permalink( $post_id );
		if ( ! amp_is_canonical() ) {
			$permalink = add_query_arg( amp_get_slug(), '', $permalink );
		}
		return $permalink;
	}

	/**
	 * Filters the AMP permalink to short-circuit normal generation.
	 *
	 * Returning a non-false value in this filter will cause the `get_permalink()` to get called and the `amp_get_permalink` filter to not apply.
	 *
	 * @since 0.4
	 * @since 1.0 This filter does not apply when 'amp' theme support is present.
	 *
	 * @param false $url     Short-circuited URL.
	 * @param int   $post_id Post ID.
	 */
	$pre_url = apply_filters( 'amp_pre_get_permalink', false, $post_id );

	if ( false !== $pre_url ) {
		return $pre_url;
	}

	$permalink = get_permalink( $post_id );

	if ( amp_is_canonical() ) {
		$amp_url = $permalink;
	} else {
		$parsed_url    = wp_parse_url( get_permalink( $post_id ) );
		$structure     = get_option( 'permalink_structure' );
		$use_query_var = (
			// If pretty permalinks aren't available, then query var must be used.
			empty( $structure )
			||
			// If there are existing query vars, then always use the amp query var as well.
			! empty( $parsed_url['query'] )
			||
			// If the post type is hierarchical then the /amp/ endpoint isn't available.
			is_post_type_hierarchical( get_post_type( $post_id ) )
			||
			// Attachment pages don't accept the /amp/ endpoint.
			'attachment' === get_post_type( $post_id )
		);
		if ( $use_query_var ) {
			$amp_url = add_query_arg( amp_get_slug(), '', $permalink );
		} else {
			$amp_url = preg_replace( '/#.*/', '', $permalink );
			$amp_url = trailingslashit( $amp_url ) . user_trailingslashit( amp_get_slug(), 'single_amp' );
			if ( ! empty( $parsed_url['fragment'] ) ) {
				$amp_url .= '#' . $parsed_url['fragment'];
			}
		}
	}

	/**
	 * Filters AMP permalink.
	 *
	 * @since 0.2
	 * @since 1.0 This filter does not apply when 'amp' theme support is present.
	 *
	 * @param false $amp_url AMP URL.
	 * @param int   $post_id Post ID.
	 */
	return apply_filters( 'amp_get_permalink', $amp_url, $post_id );
}

/**
 * Remove the AMP endpoint (and query var) from a given URL.
 *
 * @since 0.7
 *
 * @param string $url URL.
 * @return string URL with AMP stripped.
 */
function amp_remove_endpoint( $url ) {

	// Strip endpoint.
	$url = preg_replace( ':/' . preg_quote( amp_get_slug(), ':' ) . '(?=/?(\?|#|$)):', '', $url );

	// Strip query var.
	$url = remove_query_arg( amp_get_slug(), $url );

	return $url;
}

/**
 * Add amphtml link.
 *
 * If there are known validation errors for the current URL then do not output anything.
 *
 * @since 1.0
 */
function amp_add_amphtml_link() {

	/**
	 * Filters whether to show the amphtml link on the frontend.
	 *
	 * @todo This filter's name is incorrect. It's not about adding a canonical link but adding the amphtml link.
	 * @since 0.2
	 */
	if ( false === apply_filters( 'amp_frontend_show_canonical', true ) ) {
		return;
	}

	$current_url = amp_get_current_url();

	$amp_url = null;
	if ( current_theme_supports( AMP_Theme_Support::SLUG ) ) {
		if ( AMP_Theme_Support::is_paired_available() ) {
			$amp_url = add_query_arg( amp_get_slug(), '', $current_url );
		}
	} elseif ( is_singular() && post_supports_amp( get_post( get_queried_object_id() ) ) ) {
		$amp_url = amp_get_permalink( get_queried_object_id() );
	}

	if ( ! $amp_url ) {
		printf( '<!-- %s -->', esc_html__( 'There is no amphtml version available for this URL.', 'amp' ) );
		return;
	}

	// Check to see if there are known unaccepted validation errors for this URL.
	if ( current_theme_supports( AMP_Theme_Support::SLUG ) ) {
		$validation_errors = AMP_Validated_URL_Post_Type::get_invalid_url_validation_errors( $current_url, [ 'ignore_accepted' => true ] );
		$error_count       = count( $validation_errors );
		if ( $error_count > 0 ) {
			echo "<!--\n";
			echo esc_html(
				sprintf(
					/* translators: %s: error count */
					_n(
						'There is %s validation error that is blocking the amphtml version from being available.',
						'There are %s validation errors that are blocking the amphtml version from being available.',
						$error_count,
						'amp'
					),
					number_format_i18n( $error_count )
				)
			);
			echo "\n-->";
			return;
		}
	}

	if ( $amp_url ) {
		printf( '<link rel="amphtml" href="%s">', esc_url( $amp_url ) );
	}
}

/**
 * Determine whether a given post supports AMP.
 *
 * @since 0.1
 * @since 0.6 Returns false when post has meta to disable AMP.
 * @see   AMP_Post_Type_Support::get_support_errors()
 *
 * @param WP_Post $post Post.
 * @return bool Whether the post supports AMP.
 */
function post_supports_amp( $post ) {
	return 0 === count( AMP_Post_Type_Support::get_support_errors( $post ) );
}

/**
 * Determine whether the current response being served as AMP.
 *
 * This function cannot be called before the parse_query action because it needs to be able
 * to determine the queried object is able to be served as AMP. If 'amp' theme support is not
 * present, this function returns true just if the query var is present. If theme support is
 * present, then it returns true in transitional mode if an AMP template is available and the query
 * var is present, or else in standard mode if just the template is available.
 *
 * @return bool Whether it is the AMP endpoint.
 * @global string $pagenow
 * @global WP_Query $wp_query
 */
function is_amp_endpoint() {
	global $pagenow, $wp_query;

	if ( is_admin() || is_embed() || is_feed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || in_array( $pagenow, [ 'wp-login.php', 'wp-signup.php', 'wp-activate.php' ], true ) ) {
		return false;
	}

	// Always return false when requesting service worker.
	if ( class_exists( 'WP_Service_Workers' ) && ! empty( $wp_query ) && defined( 'WP_Service_Workers::QUERY_VAR' ) && $wp_query->get( WP_Service_Workers::QUERY_VAR ) ) {
		return false;
	}

	$did_parse_query = did_action( 'parse_query' );

	if ( ! $did_parse_query ) {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				/* translators: 1: is_amp_endpoint(), 2: parse_query */
				esc_html__( '%1$s was called before the %2$s hook was called.', 'amp' ),
				'is_amp_endpoint()',
				'parse_query'
			),
			'0.4.2'
		);
	}

	if ( empty( $wp_query ) || ! ( $wp_query instanceof WP_Query ) ) {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				/* translators: 1: is_amp_endpoint(), 2: WP_Query */
				esc_html__( '%1$s was called before the %2$s was instantiated.', 'amp' ),
				'is_amp_endpoint()',
				'WP_Query'
			),
			'1.1'
		);
	}

	/*
	 * If this is a URL for validation, and validation is forced for all URLs, return true.
	 * Normally, this would be false if the user has deselected a template,
	 * like by unchecking 'Categories' in 'AMP Settings' > 'Supported Templates'.
	 * But there's a flag for the WP-CLI command that sets this query var to validate all URLs.
	 */
	if ( AMP_Validation_Manager::is_theme_support_forced() ) {
		return true;
	}

	$has_amp_query_var = (
		isset( $_GET[ amp_get_slug() ] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		||
		(
			$wp_query instanceof WP_Query
			&&
			false !== $wp_query->get( amp_get_slug(), false )
		)
	);

	if ( ! current_theme_supports( AMP_Theme_Support::SLUG ) ) {
		return $has_amp_query_var;
	}

	// When there is no query var and AMP is not canonical (AMP-first), then this is definitely not an AMP endpoint.
	if ( ! $has_amp_query_var && ! amp_is_canonical() ) {
		return false;
	}

	if ( ! did_action( 'wp' ) ) {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				/* translators: 1: is_amp_endpoint(). 2: wp. 3: amp_skip_post */
				esc_html__( '%1$s was called before the %2$s action which means it will not have access to the queried object to determine if it is an AMP response, thus neither the %3$s filter nor the AMP enabled publish metabox toggle will be considered.', 'amp' ),
				'is_amp_endpoint()',
				'wp',
				'amp_skip_post'
			),
			'1.0.2'
		);
		$supported = true;
	} else {
		$availability = AMP_Theme_Support::get_template_availability();
		$supported    = $availability['supported'];
	}

	return amp_is_canonical() ? $supported : ( $has_amp_query_var && $supported );
}

/**
 * Get AMP asset URL.
 *
 * @param string $file Relative path to file in assets directory.
 * @return string URL.
 */
function amp_get_asset_url( $file ) {
	return plugins_url( sprintf( 'assets/%s', $file ), AMP__FILE__ );
}

/**
 * Get AMP boilerplate code.
 *
 * @since 0.7
 * @link https://www.ampproject.org/docs/reference/spec#boilerplate
 *
 * @return string Boilerplate code.
 */
function amp_get_boilerplate_code() {
	$stylesheets = amp_get_boilerplate_stylesheets();
	return sprintf( '<style amp-boilerplate>%s</style><noscript><style amp-boilerplate>%s</style></noscript>', $stylesheets[0], $stylesheets[1] );
}

/**
 * Get AMP boilerplate stylesheets.
 *
 * @since 1.3
 * @link https://www.ampproject.org/docs/reference/spec#boilerplate
 *
 * @return string[] Stylesheets, where first is contained in style[amp-boilerplate] and the second in noscript>style[amp-boilerplate].
 */
function amp_get_boilerplate_stylesheets() {
	return [
		'body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}',
		'body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}',
	];
}

/**
 * Add generator metadata.
 *
 * @since 6.0
 * @since 1.0 Add template mode.
 */
function amp_add_generator_metadata() {
	$content = sprintf( 'AMP Plugin v%s', AMP__VERSION );

	if ( amp_is_canonical() ) {
		$mode = 'standard';
	} elseif ( current_theme_supports( AMP_Theme_Support::SLUG ) ) {
		$mode = 'transitional';
	} else {
		$mode = 'reader';
	}
	$content .= sprintf( '; mode=%s', $mode );

	printf( '<meta name="generator" content="%s">', esc_attr( $content ) );
}

/**
 * Register default scripts for AMP components.
 *
 * @param WP_Scripts $wp_scripts Scripts.
 */
function amp_register_default_scripts( $wp_scripts ) {
	/*
	 * Polyfill dependencies that are registered in Gutenberg and WordPress 5.0.
	 * Note that Gutenberg will override these at wp_enqueue_scripts if it is active.
	 */
	$handles = [ 'wp-i18n', 'wp-dom-ready', 'wp-polyfill', 'wp-url' ];
	foreach ( $handles as $handle ) {
		if ( ! isset( $wp_scripts->registered[ $handle ] ) ) {
			$asset_file   = AMP__DIR__ . '/assets/js/' . $handle . '.asset.php';
			$asset        = require $asset_file;
			$dependencies = $asset['dependencies'];
			$version      = $asset['version'];

			$wp_scripts->add(
				$handle,
				amp_get_asset_url( sprintf( 'js/%s.js', $handle ) ),
				$dependencies,
				$version
			);
		}
	}

	// AMP Runtime.
	$handle = 'amp-runtime';
	$wp_scripts->add(
		$handle,
		'https://cdn.ampproject.org/v0.js',
		[],
		null
	);
	$wp_scripts->add_data(
		$handle,
		'amp_script_attributes',
		[
			'async' => true,
		]
	);

	// Shadow AMP API.
	$handle = 'amp-shadow';
	$wp_scripts->add(
		$handle,
		'https://cdn.ampproject.org/shadow-v0.js',
		[],
		null
	);
	$wp_scripts->add_data(
		$handle,
		'amp_script_attributes',
		[
			'async' => true,
		]
	);

	// Register all AMP components as defined in the spec.
	foreach ( AMP_Allowed_Tags_Generated::get_extension_specs() as $extension_name => $extension_spec ) {
		$src = sprintf(
			'https://cdn.ampproject.org/v0/%s-%s.js',
			$extension_name,
			end( $extension_spec['version'] )
		);

		$wp_scripts->add(
			$extension_name,
			$src,
			[ 'amp-runtime' ],
			null
		);
	}
}

/**
 * Generate HTML for AMP scripts that have not yet been printed.
 *
 * This is adapted from `wp_scripts()->do_items()`, but it runs only the bare minimum required to output
 * the missing scripts, without allowing other filters to apply which may cause an invalid AMP response.
 * The HTML for the scripts is returned instead of being printed.
 *
 * @since 0.7.2
 * @see WP_Scripts::do_items()
 * @see AMP_Base_Embed_Handler::get_scripts()
 * @see AMP_Base_Sanitizer::get_scripts()
 *
 * @param array $scripts Script handles mapped to URLs or true.
 * @return string HTML for scripts tags that have not yet been done.
 */
function amp_render_scripts( $scripts ) {
	$script_tags = '';

	/*
	 * Make sure the src is up to date. This allows for embed handlers to override the
	 * default extension version by defining a different URL.
	 */
	foreach ( $scripts as $handle => $src ) {
		if ( is_string( $src ) && wp_script_is( $handle, 'registered' ) ) {
			wp_scripts()->registered[ $handle ]->src = $src;
		}
	}

	foreach ( array_diff( array_keys( $scripts ), wp_scripts()->done ) as $handle ) {
		if ( ! wp_script_is( $handle, 'registered' ) ) {
			continue;
		}

		$script_dep   = wp_scripts()->registered[ $handle ];
		$script_tags .= amp_filter_script_loader_tag(
			sprintf(
				"<script type='text/javascript' src='%s'></script>\n", // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
				esc_url( $script_dep->src )
			),
			$handle
		);

		wp_scripts()->done[] = $handle;
	}
	return $script_tags;
}

/**
 * Add AMP script attributes to enqueued scripts.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @since 0.7
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Script loader tag.
 */
function amp_filter_script_loader_tag( $tag, $handle ) {
	$prefix = 'https://cdn.ampproject.org/';
	$src    = wp_scripts()->registered[ $handle ]->src;
	if ( 0 !== strpos( $src, $prefix ) ) {
		return $tag;
	}

	/*
	 * All scripts from AMP CDN should be loaded async.
	 * See <https://www.ampproject.org/docs/integration/pwa-amp/amp-in-pwa#include-"shadow-amp"-in-your-progressive-web-app>.
	 */
	$attributes = [
		'async' => true,
	];

	// Add custom-template and custom-element attributes. All component scripts look like https://cdn.ampproject.org/v0/:name-:version.js.
	if ( 'v0' === strtok( substr( $src, strlen( $prefix ) ), '/' ) ) {
		/*
		 * Per the spec, "Most extensions are custom-elements." In fact, there is only one custom template. So we hard-code it here.
		 *
		 * This could also be derived by looking at the extension_type in the extension_spec.
		 *
		 * @link https://github.com/ampproject/amphtml/blob/cd685d4e62153557519553ffa2183aedf8c93d62/validator/validator.proto#L326-L328
		 * @link https://github.com/ampproject/amphtml/blob/cd685d4e62153557519553ffa2183aedf8c93d62/extensions/amp-mustache/validator-amp-mustache.protoascii#L27
		 */
		if ( 'amp-mustache' === $handle ) {
			$attributes['custom-template'] = $handle;
		} else {
			$attributes['custom-element'] = $handle;
		}
	}

	// Add each attribute (if it hasn't already been added).
	foreach ( $attributes as $key => $value ) {
		if ( ! preg_match( ":\s$key(=|>|\s):", $tag ) ) {
			if ( true === $value ) {
				$attribute_string = sprintf( ' %s', esc_attr( $key ) );
			} else {
				$attribute_string = sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}
			$tag = preg_replace(
				':(?=></script>):',
				$attribute_string,
				$tag,
				1
			);
		}
	}

	return $tag;
}

/**
 * Explicitly opt-in to CORS mode by adding the crossorigin attribute to font stylesheet links.
 *
 * This explicitly triggers a CORS request, and gets back a non-opaque response, ensuring that a service
 * worker caching the external stylesheet will not inflate the storage quota. This must be done in AMP
 * and non-AMP alike because in transitional mode the service worker could cache the font stylesheets in a
 * non-AMP document without CORS (crossorigin="anonymous") in which case the service worker could then
 * fail to serve the cached font resources in an AMP document with the warning:
 *
 * > The FetchEvent resulted in a network error response: an "opaque" response was used for a request whose type is not no-cors
 *
 * @since 1.0
 * @link https://developers.google.com/web/tools/workbox/guides/storage-quota#beware_of_opaque_responses
 * @link https://developers.google.com/web/tools/workbox/guides/handle-third-party-requests#cross-origin_requests_and_opaque_responses
 * @todo This should be proposed for WordPress core.
 *
 * @param string $tag    Link tag HTML.
 * @param string $handle Dependency handle.
 * @param string $href   Link URL.
 * @return string Link tag HTML.
 */
function amp_filter_font_style_loader_tag_with_crossorigin_anonymous( $tag, $handle, $href ) {
	static $allowed_font_src_regex = null;
	if ( ! $allowed_font_src_regex ) {
		$spec_name = 'link rel=stylesheet for fonts'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
		foreach ( AMP_Allowed_Tags_Generated::get_allowed_tag( 'link' ) as $spec_rule ) {
			if ( isset( $spec_rule[ AMP_Rule_Spec::TAG_SPEC ]['spec_name'] ) && $spec_name === $spec_rule[ AMP_Rule_Spec::TAG_SPEC ]['spec_name'] ) {
				$allowed_font_src_regex = '@^(' . $spec_rule[ AMP_Rule_Spec::ATTR_SPEC_LIST ]['href']['value_regex'] . ')$@';
				break;
			}
		}
	}

	$href = preg_replace( '#^(http:)?(?=//)#', 'https:', $href );

	if ( preg_match( $allowed_font_src_regex, $href ) && false === strpos( $tag, 'crossorigin=' ) ) {
		$tag = preg_replace( '/(?<=<link\s)/', 'crossorigin="anonymous" ', $tag );
	}

	return $tag;
}

/**
 * Retrieve analytics data added in backend.
 *
 * @since 0.7
 *
 * @param array $analytics Analytics entries.
 * @return array Analytics.
 */
function amp_get_analytics( $analytics = [] ) {
	$analytics_entries = AMP_Options_Manager::get_option( 'analytics', [] );

	/**
	 * Add amp-analytics tags.
	 *
	 * This filter allows you to easily insert any amp-analytics tags without needing much heavy lifting.
	 * This filter should be used to alter entries for transitional mode.
	 *
	 * @since 0.7
	 *
	 * @param array $analytics_entries An associative array of the analytics entries we want to output. Each array entry must have a unique key, and the value should be an array with the following keys: `type`, `attributes`, `script_data`. See readme for more details.
	 */
	$analytics_entries = apply_filters( 'amp_analytics_entries', $analytics_entries );

	if ( ! $analytics_entries ) {
		return $analytics;
	}

	foreach ( $analytics_entries as $entry_id => $entry ) {
		$analytics[ $entry_id ] = [
			'type'        => $entry['type'],
			'attributes'  => isset( $entry['attributes'] ) ? $entry['attributes'] : [],
			'config_data' => json_decode( $entry['config'] ),
		];
	}

	return $analytics;
}

/**
 * Print analytics data.
 *
 * @since 0.7
 *
 * @param array|string $analytics Analytics entries, or empty string when called via wp_footer action.
 */
function amp_print_analytics( $analytics ) {
	if ( '' === $analytics ) {
		$analytics = [];
	}

	$analytics_entries = amp_get_analytics( $analytics );

	/**
	 * Triggers before analytics entries are printed as amp-analytics tags.
	 *
	 * This is useful for printing additional `amp-analytics` tags to the page without having to refactor any existing
	 * markup generation logic to use the data structure mutated by the `amp_analytics_entries` filter. For such cases,
	 * this action should be used for printing `amp-analytics` tags as opposed to using the `wp_footer` and
	 * `amp_post_template_footer` actions.
	 *
	 * @since 1.3
	 * @param array $analytics_entries Analytics entries, already potentially modified by the amp_analytics_entries filter.
	 */
	do_action( 'amp_print_analytics', $analytics_entries );

	if ( empty( $analytics_entries ) ) {
		return;
	}

	// Can enter multiple configs within backend.
	foreach ( $analytics_entries as $id => $analytics_entry ) {
		if ( ! isset( $analytics_entry['type'], $analytics_entry['attributes'], $analytics_entry['config_data'] ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				sprintf(
					/* translators: 1: the analytics entry ID. 2: type. 3: attributes. 4: config_data. 5: comma-separated list of the actual entry keys. */
					esc_html__( 'Analytics entry for %1$s is missing one of the following keys: `%2$s`, `%3$s`, or `%4$s` (array keys: %5$s)', 'amp' ),
					esc_html( $id ),
					'type',
					'attributes',
					'config_data',
					esc_html( implode( ', ', array_keys( $analytics_entry ) ) )
				),
				'0.3.2'
			);
			continue;
		}
		$script_element = AMP_HTML_Utils::build_tag(
			'script',
			[
				'type' => 'application/json',
			],
			wp_json_encode( $analytics_entry['config_data'] )
		);

		$amp_analytics_attr = array_merge(
			[
				'id'   => $id,
				'type' => $analytics_entry['type'],
			],
			$analytics_entry['attributes']
		);

		echo AMP_HTML_Utils::build_tag( 'amp-analytics', $amp_analytics_attr, $script_element ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Get content embed handlers.
 *
 * @since 0.7
 *
 * @param WP_Post $post Post that the content belongs to. Deprecated when theme supports AMP, as embeds may apply
 *                      to non-post data (e.g. Text widget).
 * @return array Embed handlers.
 */
function amp_get_content_embed_handlers( $post = null ) {
	if ( current_theme_supports( AMP_Theme_Support::SLUG ) && $post ) {
		_deprecated_argument(
			__FUNCTION__,
			'0.7',
			sprintf(
				/* translators: %s: $post */
				esc_html__( 'The %s argument is deprecated when theme supports AMP.', 'amp' ),
				'$post'
			)
		);
		$post = null;
	}

	/**
	 * Filters the content embed handlers.
	 *
	 * @since 0.2
	 * @since 0.7 Deprecated $post parameter.
	 *
	 * @param array   $handlers Handlers.
	 * @param WP_Post $post     Post. Deprecated. It will be null when `amp_is_canonical()`.
	 */
	return apply_filters(
		'amp_content_embed_handlers',
		[
			'AMP_Core_Block_Handler'         => [],
			'AMP_Twitter_Embed_Handler'      => [],
			'AMP_YouTube_Embed_Handler'      => [],
			'AMP_Crowdsignal_Embed_Handler'  => [],
			'AMP_DailyMotion_Embed_Handler'  => [],
			'AMP_Vimeo_Embed_Handler'        => [],
			'AMP_SoundCloud_Embed_Handler'   => [],
			'AMP_Instagram_Embed_Handler'    => [],
			'AMP_Issuu_Embed_Handler'        => [],
			'AMP_Meetup_Embed_Handler'       => [],
			'AMP_Vine_Embed_Handler'         => [],
			'AMP_Facebook_Embed_Handler'     => [],
			'AMP_Pinterest_Embed_Handler'    => [],
			'AMP_Playlist_Embed_Handler'     => [],
			'AMP_Reddit_Embed_Handler'       => [],
			'AMP_TikTok_Embed_Handler'       => [],
			'AMP_Tumblr_Embed_Handler'       => [],
			'AMP_Gallery_Embed_Handler'      => [],
			'AMP_Gfycat_Embed_Handler'       => [],
			'AMP_Hulu_Embed_Handler'         => [],
			'AMP_Imgur_Embed_Handler'        => [],
			'AMP_Scribd_Embed_Handler'       => [],
			'AMP_WordPress_TV_Embed_Handler' => [],
		],
		$post
	);
}

/**
 * Determine whether AMP dev mode is enabled.
 *
 * When enabled, the <html> element will get the data-ampdevmode attribute and the plugin will add the same attribute
 * to elements associated with the admin bar and other elements that are provided by the `amp_dev_mode_element_xpaths`
 * filter.
 *
 * @since 1.3
 *
 * @return bool Whether AMP dev mode is enabled.
 */
function amp_is_dev_mode() {

	/**
	 * Filters whether AMP mode is enabled.
	 *
	 * When enabled, the data-ampdevmode attribute will be added to the document element and it will allow the
	 * attributes to be added to the admin bar. It will also add the attribute to all elements which match the
	 * queries for the expressions returned by the 'amp_dev_mode_element_xpaths' filter.
	 *
	 * @since 1.3
	 * @param bool Whether AMP dev mode is enabled.
	 */
	return apply_filters(
		'amp_dev_mode_enabled',
		(
			// For the few sites that forcibly show the admin bar even when the user is logged out, only enable dev
			// mode if the user is actually logged in. This prevents the dev mode from being served to crawlers
			// when they index the AMP version. The theme support check disables dev mode in Reader mode.
			( is_admin_bar_showing() && is_user_logged_in() && current_theme_supports( 'amp' ) )
			||
			is_customize_preview()
		)
	);
}

/**
 * Get content sanitizers.
 *
 * @since 0.7
 * @since 1.1 Added AMP_Nav_Menu_Toggle_Sanitizer and AMP_Nav_Menu_Dropdown_Sanitizer.
 *
 * @param WP_Post $post Post that the content belongs to. Deprecated when theme supports AMP, as sanitizers apply
 *                      to non-post data (e.g. Text widget).
 * @return array Embed handlers.
 */
function amp_get_content_sanitizers( $post = null ) {
	$theme_support_args = AMP_Theme_Support::get_theme_support_args();

	if ( is_array( $theme_support_args ) && $post ) {
		_deprecated_argument(
			__FUNCTION__,
			'0.7',
			sprintf(
				/* translators: %s: $post */
				esc_html__( 'The %s argument is deprecated when theme supports AMP.', 'amp' ),
				'$post'
			)
		);
		$post = null;
	}

	$parsed_home_url = wp_parse_url( get_home_url() );
	$current_origin  = $parsed_home_url['scheme'] . '://' . $parsed_home_url['host'];
	if ( isset( $parsed_home_url['port'] ) ) {
		$current_origin .= ':' . $parsed_home_url['port'];
	}

	/**
	 * Filters whether AMP-to-AMP linking should be enabled.
	 *
	 * @since 1.4.0
	 * @param bool $amp_to_amp_linking_enabled Whether AMP-to-AMP linking should be enabled.
	 */
	$amp_to_amp_linking_enabled = (bool) apply_filters(
		'amp_to_amp_linking_enabled',
		AMP_Theme_Support::TRANSITIONAL_MODE_SLUG === AMP_Theme_Support::get_support_mode()
	);

	$sanitizers = [
		'AMP_Core_Theme_Sanitizer'        => [
			'template'       => get_template(),
			'stylesheet'     => get_stylesheet(),
			'theme_features' => [
				'force_svg_support' => [], // Always replace 'no-svg' class with 'svg' if it exists.
			],
		],
		'AMP_Img_Sanitizer'               => [
			'align_wide_support' => current_theme_supports( 'align-wide' ),
		],
		'AMP_Form_Sanitizer'              => [],
		'AMP_Comments_Sanitizer'          => [
			'comments_live_list' => ! empty( $theme_support_args['comments_live_list'] ),
		],
		'AMP_Video_Sanitizer'             => [],
		'AMP_O2_Player_Sanitizer'         => [],
		'AMP_Audio_Sanitizer'             => [],
		'AMP_Playbuzz_Sanitizer'          => [],
		'AMP_Embed_Sanitizer'             => [],
		'AMP_Iframe_Sanitizer'            => [
			'add_placeholder' => true,
			'current_origin'  => $current_origin,
		],
		'AMP_Gallery_Block_Sanitizer'     => [ // Note: Gallery block sanitizer must come after image sanitizers since itś logic is using the already sanitized images.
			'carousel_required' => ! is_array( $theme_support_args ), // For back-compat.
		],
		'AMP_Block_Sanitizer'             => [], // Note: Block sanitizer must come after embed / media sanitizers since its logic is using the already sanitized content.
		'AMP_Script_Sanitizer'            => [],
		'AMP_Style_Sanitizer'             => [],
		'AMP_Meta_Sanitizer'              => [],
		'AMP_Layout_Sanitizer'            => [],
		'AMP_Accessibility_Sanitizer'     => [],
		'AMP_Tag_And_Attribute_Sanitizer' => [], // Note: This whitelist sanitizer must come at the end to clean up any remaining issues the other sanitizers didn't catch.
	];

	if ( ! empty( $theme_support_args['nav_menu_toggle'] ) ) {
		$sanitizers['AMP_Nav_Menu_Toggle_Sanitizer'] = $theme_support_args['nav_menu_toggle'];
	}

	if ( ! empty( $theme_support_args['nav_menu_dropdown'] ) ) {
		$sanitizers['AMP_Nav_Menu_Dropdown_Sanitizer'] = $theme_support_args['nav_menu_dropdown'];
	}

	if ( $amp_to_amp_linking_enabled && AMP_Theme_Support::STANDARD_MODE_SLUG !== AMP_Theme_Support::get_support_mode() ) {

		/**
		 * Filters the list of URLs which are excluded from being included in AMP-to-AMP linking.
		 *
		 * This only applies when the amp_to_amp_linking_enabled filter returns true,
		 * which it does by default in Transitional mode. This filter can be used to opt-in
		 * when in Reader mode. This does not apply in Standard mode.
		 * Only frontend URLs on the frontend need be excluded, as all other URLs are never made into AMP links.
		 *
		 * @since 1.5.0
		 *
		 * @param string[] $excluded_urls The URLs to exclude from having AMP-to-AMP links.
		 */
		$excluded_urls = apply_filters( 'amp_to_amp_excluded_urls', [] );

		$sanitizers['AMP_Link_Sanitizer'] = array_merge(
			[ 'paired' => ! amp_is_canonical() ],
			compact( 'excluded_urls' )
		);
	}

	/**
	 * Filters the content sanitizers.
	 *
	 * @since 0.2
	 * @since 0.7 Deprecated $post parameter. It will be null when `amp_is_canonical()`.
	 *
	 * @param array   $handlers Handlers.
	 * @param WP_Post $post     Post. Deprecated.
	 */
	$sanitizers = apply_filters( 'amp_content_sanitizers', $sanitizers, $post );

	if ( amp_is_dev_mode() ) {
		/**
		 * Filters the XPath queries for elements that should be enabled for dev mode.
		 *
		 * By supplying XPath queries to this filter, the data-ampdevmode attribute will automatically be added to the
		 * root HTML element as well as to any elements that match the expressions. The attribute is added to the
		 * elements prior to running any of the sanitizers.
		 *
		 * @since 1.3
		 * @param string[] $element_xpaths XPath element queries. Context is the root element.
		 */
		$dev_mode_xpaths = (array) apply_filters( 'amp_dev_mode_element_xpaths', [] );
		if ( is_admin_bar_showing() ) {
			$dev_mode_xpaths[] = '//*[ @id = "wpadminbar" ]';
			$dev_mode_xpaths[] = '//*[ @id = "wpadminbar" ]//*';
			$dev_mode_xpaths[] = '//style[ @id = "admin-bar-inline-css" ]';
		}
		$sanitizers = array_merge(
			[
				'AMP_Dev_Mode_Sanitizer' => [
					'element_xpaths' => $dev_mode_xpaths,
				],
			],
			$sanitizers
		);
	}

	/**
	 * Filters whether parsed CSS is allowed to be cached in transients.
	 *
	 * When this is filtered to be false, parsed CSS will not be stored in transients. This is important when there is
	 * highly-variable CSS content in order to prevent filling up the wp_options table with an endless number of entries.
	 *
	 * @since 1.5.0
	 * @param bool $transient_caching_allowed Transient caching allowed.
	 */
	$sanitizers['AMP_Style_Sanitizer']['allow_transient_caching'] = apply_filters( 'amp_parsed_css_transient_caching_allowed', true );

	// Force style sanitizer and whitelist sanitizer to be at end.
	foreach ( [ 'AMP_Style_Sanitizer', 'AMP_Meta_Sanitizer', 'AMP_Tag_And_Attribute_Sanitizer' ] as $class_name ) {
		if ( isset( $sanitizers[ $class_name ] ) ) {
			$sanitizer = $sanitizers[ $class_name ];
			unset( $sanitizers[ $class_name ] );
			$sanitizers[ $class_name ] = $sanitizer;
		}
	}

	return $sanitizers;
}

/**
 * Grabs featured image or the first attached image for the post.
 *
 * @since 0.7 This originally was located in the private method AMP_Post_Template::get_post_image_metadata().
 *
 * @param WP_Post|int $post Post or post ID.
 * @return array|false $post_image_meta Post image metadata, or false if not found.
 */
function amp_get_post_image_metadata( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return false;
	}

	$post_image_meta = null;
	$post_image_id   = false;

	if ( has_post_thumbnail( $post->ID ) ) {
		$post_image_id = get_post_thumbnail_id( $post->ID );
	} elseif ( ( 'attachment' === $post->post_type ) && wp_attachment_is( 'image', $post ) ) {
		$post_image_id = $post->ID;
	} else {
		$attached_image_ids = get_posts(
			[
				'post_parent'      => $post->ID,
				'post_type'        => 'attachment',
				'post_mime_type'   => 'image',
				'posts_per_page'   => 1,
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'fields'           => 'ids',
				'suppress_filters' => false,
			]
		);

		if ( ! empty( $attached_image_ids ) ) {
			$post_image_id = array_shift( $attached_image_ids );
		}
	}

	if ( ! $post_image_id ) {
		return false;
	}

	$post_image_src = wp_get_attachment_image_src( $post_image_id, 'full' );

	if ( is_array( $post_image_src ) ) {
		$post_image_meta = [
			'@type'  => 'ImageObject',
			'url'    => $post_image_src[0],
			'width'  => $post_image_src[1],
			'height' => $post_image_src[2],
		];
	}

	return $post_image_meta;
}

/**
 * Get the publisher logo.
 *
 * The following guidelines apply to logos used for general AMP pages.
 *
 * "The logo should be a rectangle, not a square. The logo should fit in a 60x600px rectangle.,
 * and either be exactly 60px high (preferred), or exactly 600px wide. For example, 450x45px
 * would not be acceptable, even though it fits in the 600x60px rectangle."
 *
 * @since 1.2.1
 * @link https://developers.google.com/search/docs/data-types/article#logo-guidelines
 *
 * @return string Publisher logo image URL. WordPress logo if no site icon or custom logo defined, and no logo provided via 'amp_site_icon_url' filter.
 */
function amp_get_publisher_logo() {
	$logo_image_url = null;

	/*
	 * This should be 60x600px rectangle. It *can* be larger than this, contrary to the current documentation.
	 * Only minimum size and ratio matters. So height should be at least 60px and width a minimum of 200px.
	 * An aspect ratio between 200/60 (10/3) and 600:60 (10/1) should be used. A square image still be used,
	 * but it is not preferred; a landscape logo should be provided if possible.
	 */
	$logo_width  = 600;
	$logo_height = 60;

	// Use the Custom Logo if set.
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( has_custom_logo() && $custom_logo_id ) {
		$custom_logo_img = wp_get_attachment_image_src( $custom_logo_id, [ $logo_width, $logo_height ], false );
		if ( ! empty( $custom_logo_img[0] ) ) {
			$logo_image_url = $custom_logo_img[0];
		}
	}

	// Try Site Icon if a custom logo is not set.
	$site_icon_id = get_option( 'site_icon' );
	if ( empty( $logo_image_url ) && $site_icon_id ) {
		$site_icon_src = wp_get_attachment_image_src( $site_icon_id, [ $logo_width, $logo_height ], false );
		if ( ! empty( $site_icon_src ) ) {
			$logo_image_url = $site_icon_src[0];
		}
	}

	/**
	 * Filters the publisher logo URL in the schema.org data.
	 *
	 * Previously, this only filtered the Site Icon, as that was the only possible schema.org publisher logo.
	 * But the Custom Logo is now the preferred publisher logo, if it exists and its dimensions aren't too big.
	 *
	 * @since 0.3
	 *
	 * @param string $schema_img_url URL of the publisher logo, either the Custom Logo or the Site Icon.
	 */
	$logo_image_url = apply_filters( 'amp_site_icon_url', $logo_image_url );

	// Fallback to serving the WordPress logo.
	if ( empty( $logo_image_url ) ) {
		$logo_image_url = amp_get_asset_url( 'images/amp-page-fallback-wordpress-publisher-logo.png' );
	}

	return $logo_image_url;
}

/**
 * Get schema.org metadata for the current query.
 *
 * @since 0.7
 * @see AMP_Post_Template::build_post_data() Where the logic in this function originally existed.
 *
 * @return array $metadata All schema.org metadata for the post.
 */
function amp_get_schemaorg_metadata() {
	$metadata = [
		'@context'  => 'http://schema.org',
		'publisher' => [
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		],
	];

	$publisher_logo = amp_get_publisher_logo();
	if ( $publisher_logo ) {
		$metadata['publisher']['logo'] = $publisher_logo;
	}

	$post = get_queried_object();
	if ( $post instanceof WP_Post ) {
		$metadata = array_merge(
			$metadata,
			[
				'@type'            => is_page() ? 'WebPage' : 'BlogPosting',
				'mainEntityOfPage' => get_permalink(),
				'headline'         => get_the_title(),
				'datePublished'    => mysql2date( 'c', $post->post_date_gmt, false ),
				'dateModified'     => mysql2date( 'c', $post->post_modified_gmt, false ),
			]
		);

		$post_author = get_userdata( $post->post_author );
		if ( $post_author ) {
			$metadata['author'] = [
				'@type' => 'Person',
				'name'  => html_entity_decode( $post_author->display_name, ENT_QUOTES, get_bloginfo( 'charset' ) ),
			];
		}

		$image_metadata = amp_get_post_image_metadata( $post );
		if ( $image_metadata ) {
			$metadata['image'] = $image_metadata['url'];
		}

		/**
		 * Filters Schema.org metadata for a post.
		 *
		 * The 'post_template' in the filter name here is due to this filter originally being introduced in `AMP_Post_Template`.
		 * In general the `amp_schemaorg_metadata` filter should be used instead.
		 *
		 * @since 0.3
		 *
		 * @param array   $metadata Metadata.
		 * @param WP_Post $post     Post.
		 */
		$metadata = apply_filters( 'amp_post_template_metadata', $metadata, $post );
	}

	/**
	 * Filters Schema.org metadata for a query.
	 *
	 * Check the the main query for the context for which metadata should be added.
	 *
	 * @since 0.7
	 *
	 * @param array   $metadata Metadata.
	 */
	$metadata = apply_filters( 'amp_schemaorg_metadata', $metadata );

	return $metadata;
}

/**
 * Output schema.org metadata.
 *
 * @since 0.7
 * @since 1.1 we pass `JSON_UNESCAPED_UNICODE` to `wp_json_encode`.
 * @see https://github.com/ampproject/amp-wp/issues/1969
 */
function amp_print_schemaorg_metadata() {
	$metadata = amp_get_schemaorg_metadata();
	if ( empty( $metadata ) ) {
		return;
	}
	?>
	<script type="application/ld+json"><?php echo wp_json_encode( $metadata, JSON_UNESCAPED_UNICODE ); ?></script>
	<?php
}

/**
 * Filters content and keeps only allowable HTML elements by amp-mustache.
 *
 * @see wp_kses()
 * @since 1.0
 *
 * @param string $markup Markup to sanitize.
 * @return string HTML markup with tags allowed by amp-mustache.
 */
function amp_wp_kses_mustache( $markup ) {
	$amp_mustache_allowed_html_tags = [ 'strong', 'b', 'em', 'i', 'u', 's', 'small', 'mark', 'del', 'ins', 'sup', 'sub' ];
	return wp_kses( $markup, array_fill_keys( $amp_mustache_allowed_html_tags, [] ) );
}

/**
 * Add "View AMP" admin bar item for Transitional/Reader mode.
 *
 * Note that when theme support is present (in Native/Transitional modes), the admin bar item will be further amended by
 * the `AMP_Validation_Manager::add_admin_bar_menu_items()` method.
 *
 * @see \AMP_Validation_Manager::add_admin_bar_menu_items()
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar.
 */
function amp_add_admin_bar_view_link( $wp_admin_bar ) {
	if ( is_admin() || amp_is_canonical() ) {
		return;
	}

	if ( current_theme_supports( 'amp' ) ) {
		$available = AMP_Theme_Support::get_template_availability()['supported'];
	} elseif ( is_singular() ) {
		$post      = get_queried_object();
		$available = ( $post instanceof WP_Post ) && post_supports_amp( $post );
	} else {
		$available = false;
	}
	if ( ! $available ) {
		// @todo Add note that AMP is not available?
		return;
	}

	// Show nothing if there are rejected validation errors for this URL.
	if (
		! is_amp_endpoint() &&
		AMP_Theme_Support::READER_MODE_SLUG !== AMP_Theme_Support::get_support_mode() &&
		count( AMP_Validated_URL_Post_Type::get_invalid_url_validation_errors( amp_get_current_url(), [ 'ignore_accepted' => true ] ) ) > 0
	) {
		return;
	}

	if ( is_amp_endpoint() ) {
		$href = amp_remove_endpoint( amp_get_current_url() );
	} elseif ( is_singular() ) {
		$href = amp_get_permalink( get_queried_object_id() ); // For sake of Reader mode.
	} else {
		$href = add_query_arg( amp_get_slug(), '', amp_get_current_url() );
	}

	$icon = '&#x1F517;'; // LINK SYMBOL.

	$parent = [
		'id'    => 'amp',
		'title' => sprintf(
			'<span id="amp-admin-bar-item-status-icon">%s</span> %s',
			$icon,
			esc_html( is_amp_endpoint() ? __( 'Non-AMP', 'amp' ) : __( 'AMP', 'amp' ) )
		),
		'href'  => esc_url( $href ),
	];

	$wp_admin_bar->add_node( $parent );
}

/**
 * Generate hash for inline amp-script.
 *
 * The sha384 hash used by amp-script is represented not as hexadecimal but as base64url, which is defined in RFC 4648
 * under section 5, "Base 64 Encoding with URL and Filename Safe Alphabet". It is sometimes referred to as "web safe".
 *
 * @since 1.4.0
 * @link https://amp.dev/documentation/components/amp-script/#security-features
 * @link https://github.com/ampproject/amphtml/blob/e8707858895c2af25903af25d396e144e64690ba/extensions/amp-script/0.1/amp-script.js#L401-L425
 * @link https://github.com/ampproject/amphtml/blob/27b46b9c8c0fb3711a00376668d808f413d798ed/src/service/crypto-impl.js#L67-L124
 * @link https://github.com/ampproject/amphtml/blob/c4a663d0ba13d0488c6fe73c55dc8c971ac6ec0d/src/utils/base64.js#L52-L61
 * @link https://tools.ietf.org/html/rfc4648#section-5
 *
 * @param string $script Script.
 * @return string|null Script hash or null if the sha384 algorithm is not supported.
 */
function amp_generate_script_hash( $script ) {
	$sha384 = hash( 'sha384', $script, true );
	if ( false === $sha384 ) {
		return null;
	}
	$hash = str_replace(
		[ '+', '/', '=' ],
		[ '-', '_', '.' ],
		base64_encode( $sha384 ) // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	);
	return 'sha384-' . $hash;
}

/*
 * The function below is copied from the ramsey/array_column package.
 *
 * Changes were made to code style to pass PHPCS requirements, but logic is unchanged.
 *
 * This can be removed once the required PHP version moves to PHP 5.5+.
 *
 * @link https://github.com/ramsey/array_column
 *
 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
if ( ! function_exists( 'array_column' ) ) {

	/**
	 * Returns the values from a single column of the input array, identified by
	 * the $columnKey.
	 *
	 * Optionally, you may provide an $indexKey to index the values in the returned
	 * array by the values from the $indexKey column in the input array.
	 *
	 * @param array $input      A multi-dimensional array (record set) from which to pull
	 *                          a column of values.
	 * @param mixed $column_key The column of values to return. This value may be the
	 *                          integer key of the column you wish to retrieve, or it
	 *                          may be the string key name for an associative array.
	 * @param mixed $index_key  (Optional.) The column to use as the index/keys for
	 *                          the returned array. This value may be the integer key
	 *                          of the column, or it may be the string key name.
	 * @return array|bool
	 */
	function array_column( $input = [], $column_key = null, $index_key = null ) {
		// Using func_get_args() in order to check for proper number of
		// parameters and trigger errors exactly as the built-in array_column()
		// does in PHP 5.5.
		$argc   = func_num_args();
		$params = func_get_args();

		if ( $argc < 2 ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error,WordPress.Security.EscapeOutput.OutputNotEscaped
			trigger_error( "array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING );
			return null;
		}

		if ( ! is_array( $params[0] ) ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error,WordPress.Security.EscapeOutput.OutputNotEscaped
			trigger_error( 'array_column() expects parameter 1 to be array, ' . gettype( $params[0] ) . ' given', E_USER_WARNING );
			return null;
		}

		if ( ! is_int( $params[1] )
			&& ! is_float( $params[1] )
			&& ! is_string( $params[1] )
			&& null !== $params[1]
			&& ! ( is_object( $params[1] ) && method_exists( $params[1], '__toString' ) )
		) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
			trigger_error( 'array_column(): The column key should be either a string or an integer', E_USER_WARNING );
			return false;
		}

		if ( isset( $params[2] )
			&& ! is_int( $params[2] )
			&& ! is_float( $params[2] )
			&& ! is_string( $params[2] )
			&& ! ( is_object( $params[2] ) && method_exists( $params[2], '__toString' ) )
		) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
			trigger_error( 'array_column(): The index key should be either a string or an integer', E_USER_WARNING );
			return false;
		}

		$params_input      = $params[0];
		$params_column_key = ( null !== $params[1] ) ? (string) $params[1] : null;

		$params_index_key = null;
		if ( isset( $params[2] ) ) {
			if ( is_float( $params[2] ) || is_int( $params[2] ) ) {
				$params_index_key = (int) $params[2];
			} else {
				$params_index_key = (string) $params[2];
			}
		}

		$result_array = [];

		foreach ( $params_input as $row ) {
			$key       = null;
			$value     = null;
			$key_set   = false;
			$value_set = false;

			if ( null !== $params_index_key && array_key_exists( $params_index_key, $row ) ) {
				$key_set = true;
				$key     = (string) $row[ $params_index_key ];
			}

			if ( null === $params_column_key ) {
				$value_set = true;
				$value     = $row;
			} elseif ( is_array( $row ) && array_key_exists( $params_column_key, $row ) ) {
				$value_set = true;
				$value     = $row[ $params_column_key ];
			}

			if ( $value_set ) {
				if ( $key_set ) {
					$result_array[ $key ] = $value;
				} else {
					$result_array[] = $value;
				}
			}
		}

		return $result_array;
	}
}
