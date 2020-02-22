<?php
/**
 * Class Google\Site_Kit\Core\Admin_Bar\Admin_Bar
 *
 * @package   Google\Site_Kit
 * @copyright 2019 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Core\Admin_Bar;

use Google\Site_Kit\Context;
use Google\Site_Kit\Core\Permissions\Permissions;
use Google\Site_Kit\Core\Assets\Assets;

/**
 * Class handling the plugin's admin bar menu.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Admin_Bar {

	/**
	 * Plugin context.
	 *
	 * @since 1.0.0
	 * @var Context
	 */
	private $context;

	/**
	 * Assets Instance.
	 *
	 * @since 1.0.0
	 * @var Assets
	 */
	private $assets;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Context $context Plugin context.
	 * @param Assets  $assets  Optional. Assets API instance. Default is a new instance.
	 */
	public function __construct( Context $context, Assets $assets = null ) {
		$this->context = $context;

		if ( ! $assets ) {
			$assets = new Assets( $this->context );
		}
		$this->assets = $assets;
	}

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_action(
			'admin_bar_menu',
			function( $wp_admin_bar ) {
				$this->add_menu_button( $wp_admin_bar );
			},
			99
		);

		$admin_bar_callback = function() {
			if ( ! $this->is_active() ) {
				return;
			}

			// Enqueue fonts.
			$this->assets->enqueue_fonts();

			// Enqueue styles.
			$this->assets->enqueue_asset( 'googlesitekit_adminbar_css' );

			if ( $this->context->is_amp() ) {
				if ( ! $this->is_amp_dev_mode() ) {
					// AMP Dev Mode support was added in v1.4, and if it is not enabled then short-circuit since scripts will be invalid.
					return;
				}
				add_filter( 'amp_dev_mode_element_xpaths', array( $this, 'add_amp_dev_mode' ) );
			}

			// Enqueue scripts.
			$this->assets->enqueue_asset( 'googlesitekit_adminbar_loader' );
		};
		add_action( 'admin_enqueue_scripts', $admin_bar_callback, 40 );
		add_action( 'wp_enqueue_scripts', $admin_bar_callback, 40 );
	}

	/**
	 * Add data-ampdevmode attributes to the elements that need it.
	 *
	 * @see \Google\Site_Kit\Core\Assets\Assets::get_assets() The 'googlesitekit' string is added to all inline scripts.
	 * @see \Google\Site_Kit\Core\Assets\Assets::add_amp_dev_mode_attributes() The data-ampdevmode attribute is added to registered scripts/styles here.
	 *
	 * @param string[] $xpath_queries XPath queries for elements that should get the data-ampdevmode attribute.
	 * @return string[] XPath queries.
	 */
	public function add_amp_dev_mode( $xpath_queries ) {
		$xpath_queries[] = '//script[ contains( text(), "googlesitekit" ) ]';
		return $xpath_queries;
	}

	/**
	 * Render the Adminbar button.
	 *
	 * @since 1.0.0
	 *
	 * @param object $wp_admin_bar The WP AdminBar object.
	 */
	private function add_menu_button( $wp_admin_bar ) {
		if ( ! $this->is_active() ) {
			return;
		}

		$args = array(
			'id'    => 'google-site-kit',
			'title' => '<span class="googlesitekit-wp-adminbar__icon"></span> <span class="googlesitekit-wp-adminbar__label">Site Kit</span>',
			'href'  => '#',
			'meta'  => array(
				'class' => 'menupop googlesitekit-wp-adminbar',
			),
		);

		if ( $this->context->is_amp() && ! $this->is_amp_dev_mode() ) {
			$post = get_post();
			if ( ! $post || ! current_user_can( 'edit_post', $post->ID ) ) {
				return;
			}
			$args['href'] = add_query_arg( 'googlesitekit_adminbar_open', 'true', get_edit_post_link( $post->ID ) );
		} else {
			$args['meta']['html'] = $this->menu_markup();
		}

		$wp_admin_bar->add_node( $args );
	}

	/**
	 * Checks if admin bar menu is active and displaying.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if Admin bar should display, False when it's not.
	 */
	public function is_active() {

		// Only active if the admin bar is showing.
		if ( ! is_admin_bar_showing() ) {
			return false;
		}

		// Gets post object. On front area we need to use get_queried_object to get the current post object.
		if ( $this->is_admin_post_screen() ) {
			$post        = get_post();
			$current_url = $this->context->get_reference_permalink( $post );
		} else {
			$post        = get_queried_object();
			$current_url = $this->context->get_reference_canonical();
		}

		// No URL was identified - don't display the admin bar menu.
		if ( ! $current_url ) {
			return false;
		}

		// Checks for post objects.
		if ( $post instanceof \WP_Post ) {

			// Ensure the user can view post insights for this post.
			if ( ! current_user_can( Permissions::VIEW_POST_INSIGHTS, $post->ID ) ) {
				return false;
			}

			// Only published posts show the menu.
			if ( 'publish' !== $post->post_status ) {
				return false;
			}
		} else {

			// Only admins can see non-post admin bar data.
			if ( ! current_user_can( Permissions::VIEW_DASHBOARD ) ) {
				return false;
			}
		}

		/**
		 * Filters whether the Site Kit admin bar menu should be displayed.
		 *
		 * The admin bar menu is only shown when there is data for the current URL and the current
		 * user has the correct capability to view the data. Modules use this filter to indicate the
		 * presence of valid data.
		 *
		 * @since 1.0.0
		 *
		 * @param bool   $display     Whether to display the admin bar menu.
		 * @param string $current_url The URL of the current request.
		 */
		return apply_filters( 'googlesitekit_show_admin_bar_menu', true, $current_url );
	}

	/**
	 * Checks if current screen is an admin edit post screen.
	 *
	 * @since 1.0.0
	 */
	private function is_admin_post_screen() {
		$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;

		// No screen context available.
		if ( ! $current_screen instanceof \WP_Screen ) {
			return false;
		}

		// Only show for post screens.
		if ( 'post' !== $current_screen->base ) {
			return false;
		}

		// Don't show for new post screen.
		if ( 'add' === $current_screen->action ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks whether AMP dev mode is enabled.
	 *
	 * This is only relevant if the current context is AMP.
	 *
	 * @since 1.1.0
	 *
	 * @return bool True if AMP dev mode is enabled, false otherwise.
	 */
	private function is_amp_dev_mode() {
		return function_exists( 'amp_is_dev_mode' ) && amp_is_dev_mode();
	}

	/**
	 * Return the Adminbar content markup.
	 *
	 * @since 1.0.0
	 */
	private function menu_markup() {
		// Start buffer output.
		ob_start();

		?>
		<div class="googlesitekit-plugin">
			<div id="js-googlesitekit-adminbar" class="ab-sub-wrapper googlesitekit-adminbar googlesitekit-adminbar--loading">
				<div class="googlesitekit-adminbar__loading">
					<div role="progressbar" class="mdc-linear-progress mdc-linear-progress--indeterminate">
						<div class="mdc-linear-progress__buffering-dots"></div>
						<div class="mdc-linear-progress__buffer"></div>
						<div class="mdc-linear-progress__bar mdc-linear-progress__primary-bar">
							<span class="mdc-linear-progress__bar-inner"></span>
						</div>
						<div class="mdc-linear-progress__bar mdc-linear-progress__secondary-bar">
							<span class="mdc-linear-progress__bar-inner"></span>
						</div>
					</div>
				</div>

				<?php
				/**
				 * Display server rendered content before JS-based adminbar modules.
				 *
				 * @since 1.0.0
				 */
				do_action( 'googlesitekit_adminbar_modules_before' );
				?>

				<section id="js-googlesitekit-adminbar-modules" class="googlesitekit-adminbar-modules"></section>

				<?php
				/**
				 * Display server rendered content after JS-based adminbar modules.
				 *
				 * @since 1.0.0
				 */
				do_action( 'googlesitekit_adminbar_modules_after' );
				?>
			</div>
		</div>
		<?php

		// Get the buffer output.
		$markup = ob_get_clean();

		return $markup;
	}
}
