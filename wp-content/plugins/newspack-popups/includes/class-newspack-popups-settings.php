<?php
/**
 * Newspack Popups Settings Page
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manages Settings page.
 */
class Newspack_Popups_Settings {
	const NEWSPACK_POPUPS_SETTINGS_PAGE = 'newspack-popups-settings-admin';

	/**
	 * Set up hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'add_plugin_page' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ] );
	}

	/**
	 * Add settings page.
	 */
	public static function add_plugin_page() {
		add_submenu_page(
			'edit.php?post_type=' . Newspack_Popups::NEWSPACK_POPUPS_CPT,
			__( 'Campaigns Settings', 'newspack-popups' ),
			__( 'Settings', 'newspack-popups' ),
			'manage_options',
			self::NEWSPACK_POPUPS_SETTINGS_PAGE,
			[ __CLASS__, 'create_admin_page' ]
		);
	}

	/**
	 * Settings page callback.
	 */
	public static function create_admin_page() {
		?>
		<div id="newspack-popups-settings-root"></div>
		<?php
	}

	/**
	 * Update settings.
	 *
	 * @param object $options options.
	 */
	public static function set_settings( $options ) {
		if ( update_option( $options['option_name'], $options['option_value'] ) ) {
			return self::get_settings();
		} else {
			return new \WP_Error(
				'newspack_popups_settings_error',
				esc_html__( 'Error updating the settings.', 'newspack' )
			);
		}
	}

	/**
	 * Return a single setting value.
	 *
	 * @param string $key Key name.
	 */
	public static function get_setting( $key ) {
		return get_option( $key, true );
	}

	/**
	 * Return all settings.
	 */
	public static function get_settings() {
		$donor_landing_options       = [];
		$donor_landing_options_query = new \WP_Query(
			[
				'post_type'      => [ 'page' ],
				'post_status'    => [ 'publish' ],
				'post_parent'    => 0,
				'posts_per_page' => 100,
			]
		);

		if ( $donor_landing_options_query->have_posts() ) {
			foreach ( $donor_landing_options_query->posts as $page ) {
				$donor_landing_options[] = [
					'label' => $page->post_title,
					'value' => $page->ID,
				];
			}
		}

		return [
			[
				'key'   => 'suppress_newsletter_campaigns',
				'value' => get_option( 'suppress_newsletter_campaigns', true ),
				'label' => __(
					'Suppress Newsletter prompts if visitor is coming from email.',
					'newspack-popups'
				),
			],
			[
				'key'   => 'suppress_all_newsletter_campaigns_if_one_dismissed',
				'value' => get_option( 'suppress_all_newsletter_campaigns_if_one_dismissed', true ),
				'label' => __(
					'Suppress all Newsletter prompts if at least one Newsletter campaign was permanently dismissed.',
					'newspack-popups'
				),
			],
			[
				'key'   => 'suppress_donation_campaigns_if_donor',
				'value' => get_option( 'suppress_donation_campaigns_if_donor', false ),
				'label' => __(
					'Suppress all donation prompts if the reader has donated.',
					'newspack-popups'
				),
			],
			[
				'key'   => 'newspack_popups_non_interative_mode',
				'value' => self::is_non_interactive(),
				'label' => __(
					'Enable non-interactive mode.',
					'newspack-popups'
				),
				'help'  => __(
					'Use this setting in high traffic scenarios. No API requests will be made, reducing server load. Inline prompts will be shown to all users without dismissal buttons, and overlay prompts will be suppressed.',
					'newspack-popups'
				),
			],
			[
				'key'            => 'newspack_popups_donor_landing_page',
				'value'          => self::donor_landing_page(),
				'label'          => __(
					'Donor landing page',
					'newspack-popups'
				),
				'help'           => __(
					"Set a page on your site as a donation landing page. Once a reader views this page, they will be considered a donor. This is helpful if you're using an off-site donation platform but still want to target donors as an audience segment.",
					'newspack-popups'
				),
				'type'           => 'select',
				'options'        => $donor_landing_options,
				'no_option_text' => __( '-- None --', 'newspack-popups' ),
			],
			[
				'key'   => 'all_segments',
				'value' => array_reduce(
					Newspack_Popups_Segmentation::get_segments(),
					function( $acc, $item ) {
						$acc[ $item['id'] ]             = $item['configuration'];
						$acc[ $item['id'] ]['priority'] = $item['priority'];
						return $acc;
					},
					[]
				),
			],
			[
				'key'   => Newspack_Popups::NEWSPACK_POPUPS_ACTIVE_CAMPAIGN_GROUP,
				'value' => get_option( Newspack_Popups::NEWSPACK_POPUPS_ACTIVE_CAMPAIGN_GROUP ),
			],
		];
	}

	/**
	 * Is the non-interactive setting on?
	 */
	public static function is_non_interactive() {
		// Handle legacy option name.
		return get_option( 'newspack_newsletters_non_interative_mode', false ) || get_option( 'newspack_popups_non_interative_mode', false );
	}

	/**
	 * Donor landing page.
	 */
	public static function donor_landing_page() {
		return get_option( 'newspack_popups_donor_landing_page', '' );
	}

	/**
	 * Load up common JS/CSS for settings.
	 */
	public static function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( Newspack_Popups::NEWSPACK_POPUPS_CPT . '_page_' . self::NEWSPACK_POPUPS_SETTINGS_PAGE !== $screen->base ) {
			return;
		}

		\wp_register_script(
			'newspack-popups-settings',
			plugins_url( '../dist/settings.js', __FILE__ ),
			[ 'wp-components', 'wp-api-fetch' ],
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/settings.js' ),
			true
		);
		wp_localize_script(
			'newspack-popups-settings',
			'newspack_popups_settings',
			self::get_settings()
		);
		\wp_enqueue_script( 'newspack-popups-settings' );
		\wp_register_style(
			'newspack-popups-settings',
			plugins_url( '../dist/settings.css', __FILE__ ),
			null,
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/settings.css' )
		);
		\wp_style_add_data( 'newspack-popups-settings', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-popups-settings' );
	}

	/**
	 * Activate prompts by campaign.
	 *
	 * @param int $ids Campaign IDs to publish.
	 * @return bool Whether operation was successful.
	 */
	public static function batch_publish( $ids ) {
		if ( empty( $ids ) ) {
			return new \WP_Error(
				'newspack_popups_settings_error',
				esc_html__( 'Invalid campaign IDs.', 'newspack' )
			);
		}

		$all_campaigns = new \WP_Query(
			[
				'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
				'post_status'    => [ 'draft', 'pending', 'future', 'publish' ],
				'posts_per_page' => 100,
			]
		);

		if ( $all_campaigns->have_posts() ) {
			foreach ( $all_campaigns->posts as $campaign ) {
				if ( in_array( $campaign->ID, $ids ) ) {
					if ( 'publish' !== $campaign->post_status ) {
						wp_publish_post( $campaign );
					}
				} else {
					if ( 'publish' === $campaign->post_status ) {
						$campaign->post_status = 'draft';
						wp_update_post( $campaign );
					}
				}
			}
		}

		return true;
	}

	/**
	 * Unpublish prompts by campaign.
	 *
	 * @param int $ids Campaign IDs to unpublish.
	 * @return bool Whether operation was successful.
	 */
	public static function batch_unpublish( $ids ) {
		if ( empty( $ids ) ) {
			return new \WP_Error(
				'newspack_popups_settings_error',
				esc_html__( 'Invalid campaign IDs.', 'newspack' )
			);
		}

		$campaigns_to_unpublish = new \WP_Query(
			[
				'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
				'post_status'    => [ 'publish' ],
				'post__in'       => $ids,
				'posts_per_page' => 100,
			]
		);

		if ( $campaigns_to_unpublish->have_posts() ) {
			foreach ( $campaigns_to_unpublish->posts as $campaign ) {
				$campaign->post_status = 'draft';
				wp_update_post( $campaign );
			}
		}

		return true;
	}
}

if ( is_admin() ) {
	Newspack_Popups_Settings::init();
}
