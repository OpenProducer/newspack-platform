<?php
/**
 * Class Google\Site_Kit\Core\Admin\Screens
 *
 * @package   Google\Site_Kit
 * @copyright 2019 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Core\Admin;

use Google\Site_Kit\Context;
use Google\Site_Kit\Core\Authentication\Authentication;
use Google\Site_Kit\Core\Permissions\Permissions;
use Google\Site_Kit\Core\Assets\Assets;

/**
 * Class managing admin screens.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Screens {

	const PREFIX = 'googlesitekit-';

	/**
	 * Plugin context.
	 *
	 * @since 1.0.0
	 * @var Context
	 */
	private $context;

	/**
	 * Assets API instance.
	 *
	 * @since 1.0.0
	 * @var Assets
	 */
	private $assets;

	/**
	 * Associative array of $hook_suffix => $screen pairs.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $screens = array();

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
		if ( $this->context->is_network_mode() ) {
			add_action(
				'network_admin_menu',
				function() {
					$this->add_screens();
				}
			);
		}

		add_action(
			'admin_menu',
			function() {
				$this->add_screens();
			}
		);

		add_action(
			'admin_enqueue_scripts',
			function( $hook_suffix ) {
				$this->enqueue_screen_assets( $hook_suffix );
			}
		);

		// Ensure the menu icon always is rendered correctly, without enqueueing a global CSS file.
		add_action(
			'admin_head',
			function() {
				?>
				<style type="text/css">
					#adminmenu .toplevel_page_googlesitekit-dashboard img {
						width: 16px;
					}
					#adminmenu .toplevel_page_googlesitekit-dashboard.current img,
					#adminmenu .toplevel_page_googlesitekit-dashboard.wp-has-current-submenu img {
						opacity: 1;
					}
				</style>
				<?php
			}
		);

		$remove_notices_callback = function() {
			global $hook_suffix;

			if ( empty( $hook_suffix ) ) {
				return;
			}

			if ( isset( $this->screens[ $hook_suffix ] ) ) {
				remove_all_actions( current_action() );
			}
		};
		add_action( 'admin_notices', $remove_notices_callback, -9999 );
		add_action( 'network_admin_notices', $remove_notices_callback, -9999 );
		add_action( 'all_admin_notices', $remove_notices_callback, -9999 );

		add_filter( 'custom_menu_order', '__return_true' );
		add_filter(
			'menu_order',
			function( array $menu_order ) {
				$new_order = array();
				foreach ( $menu_order as $index => $item ) {
					if ( 'index.php' === $item || 0 === strpos( $item, self::PREFIX ) ) {
						$new_order[] = $item;
						unset( $menu_order[ $index ] );
					}
				}
				return array_values( array_merge( $new_order, $menu_order ) );
			}
		);
	}

	/**
	 * Adds all screens to the admin.
	 *
	 * @since 1.0.0
	 */
	private function add_screens() {
		$screens = $this->get_screens();

		array_walk( $screens, array( $this, 'add_screen' ) );
	}

	/**
	 * Adds the given screen to the admin.
	 *
	 * @since 1.0.0
	 *
	 * @param Screen $screen Screen to add.
	 */
	private function add_screen( Screen $screen ) {
		$hook_suffix = $screen->add( $this->context );
		if ( empty( $hook_suffix ) ) {
			return;
		}

		add_action(
			"load-{$hook_suffix}",
			function() use ( $screen ) {
				$screen->initialize( $this->context );
			}
		);

		$this->screens[ $hook_suffix ] = $screen;
	}

	/**
	 * Enqueues assets if a plugin screen matches the given hook suffix.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix Hook suffix for the current admin screen.
	 */
	private function enqueue_screen_assets( $hook_suffix ) {
		if ( ! isset( $this->screens[ $hook_suffix ] ) ) {
			return;
		}

		$this->screens[ $hook_suffix ]->enqueue_assets( $this->assets );

		/**
		 * Fires when assets are enqueued for a Site Kit admin screen.
		 *
		 * @since 1.0.0
		 */
		do_action( 'googlesitekit_enqueue_screen_assets' );
	}

	/**
	 * Gets available admin screens.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of Screen instances.
	 */
	private function get_screens() {
		$screens = array(
			new Screen(
				self::PREFIX . 'dashboard',
				array(
					'title'            => __( 'Dashboard', 'google-site-kit' ),
					'capability'       => Permissions::VIEW_DASHBOARD,
					'enqueue_callback' => function( Assets $assets ) {
						if ( $this->context->input()->filter( INPUT_GET, 'permaLink' ) ) {
							$assets->enqueue_asset( 'googlesitekit_dashboard_details' );
						} else {
							$assets->enqueue_asset( 'googlesitekit_dashboard' );
						}
					},
					'render_callback'  => function( Context $context ) {
						?>
						<div class="googlesitekit-plugin">
							<?php
							if ( $context->input()->filter( INPUT_GET, 'permaLink' ) ) {
								/**
								 * Fires before the Dashboard Details App wrapper is rendered.
								 *
								 * @since 1.0.0
								 */
								do_action( 'googlesitekit_above_dashboard_details_app' );
								?>
								<div id="js-googlesitekit-dashboard-details" class="googlesitekit-page"></div>
								<?php
							} else {
								/**
								 * Fires before the Dashboard App wrapper is rendered.
								 *
								 * @since 1.0.0
								 */
								do_action( 'googlesitekit_above_dashboard_app' );
								?>
								<div id="js-googlesitekit-dashboard" class="googlesitekit-page"></div>
								<?php
							}
							?>
						</div>
						<?php
					},
				)
			),
		);

		// Wrap this simply to save some unnecessary filter firing and screen instantiation.
		if ( current_user_can( Permissions::VIEW_MODULE_DETAILS ) ) {
			/**
			 * Filters the admin screens for modules.
			 *
			 * By default this is an empty array, but can be expanded.
			 *
			 * @since 1.0.0
			 *
			 * @param array $module_screens List of Screen instances.
			 */
			$module_screens = apply_filters( 'googlesitekit_module_screens', array() );

			$screens = array_merge( $screens, $module_screens );
		}

		$screens[] = new Screen(
			self::PREFIX . 'settings',
			array(
				'title'            => __( 'Settings', 'google-site-kit' ),
				'capability'       => Permissions::MANAGE_OPTIONS,
				'enqueue_callback' => function( Assets $assets ) {
					$assets->enqueue_asset( 'googlesitekit_settings' );
				},
				'render_callback'  => function( Context $context ) {
					?>
					<div class="googlesitekit-plugin">
						<?php
						/**
						 * Fires before the Settings App wrapper is rendered.
						 *
						 * @since 1.0.0
						 */
						do_action( 'googlesitekit_above_settings_app' );
						?>
						<div id="googlesitekit-settings-wrapper" class="googlesitekit-page"></div>
					</div>
					<?php
				},
			)
		);

		$show_splash_in_menu = ! current_user_can( Permissions::VIEW_DASHBOARD ) && ! current_user_can( Permissions::VIEW_MODULE_DETAILS ) && ! current_user_can( Permissions::MANAGE_OPTIONS );

		$screens[] = new Screen(
			self::PREFIX . 'splash',
			array(
				'title'               => __( 'Dashboard', 'google-site-kit' ),
				'capability'          => Permissions::AUTHENTICATE,
				'parent_slug'         => $show_splash_in_menu ? Screen::MENU_SLUG : null,

				// This callback will redirect to the dashboard on successful authentication.
				'initialize_callback' => function( Context $context ) {
					$splash_context = $context->input()->filter( INPUT_GET, 'googlesitekit_context' );
					$reset_session  = $context->input()->filter( INPUT_GET, 'googlesitekit_reset_session', FILTER_VALIDATE_BOOLEAN );
					$authentication = new Authentication( $context );

					// If the user is authenticated, redirect them to the disconnect URL and then send them back here.
					if ( ! $reset_session && 'revoked' === $splash_context && $authentication->is_authenticated() ) {
						$authentication->disconnect();

						wp_safe_redirect( add_query_arg( array( 'googlesitekit_reset_session' => 1 ) ) );
						exit;
					}

					$notification = $context->input()->filter( INPUT_GET, 'notification' );
					$error        = $context->input()->filter( INPUT_GET, 'error' );

					// Bail if no success parameter indicator.
					if ( 'authentication_success' !== $notification || ! empty( $error ) ) {
						return;
					}

					// Bail if the current user cannot access the dashboard.
					if ( ! current_user_can( Permissions::VIEW_DASHBOARD ) ) {
						return;
					}

					wp_safe_redirect(
						$context->admin_url(
							'dashboard',
							array(
								'notification' => 'authentication_success',
							)
						)
					);
					exit;
				},
				'enqueue_callback'    => function( Assets $assets ) {
					$assets->enqueue_asset( 'googlesitekit_dashboard_splash' );
				},
				'render_callback'     => function( Context $context ) {
					?>
					<div class="googlesitekit-plugin">
						<?php
						/**
						 * Fires before the Dashboard Splash App wrapper is rendered.
						 *
						 * @since 1.0.0
						 */
						do_action( 'googlesitekit_above_dashboard_splash_app' );
						?>
						<div id="js-googlesitekit-dashboard-splash" class="googlesitekit-page"></div>
					</div>
					<?php
				},
			)
		);

		return $screens;
	}
}
