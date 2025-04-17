<?php
/**
 * Audience Subscriptions Wizard
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Audience Subscriptions Wizard.
 */
class Audience_Subscriptions extends Wizard {
	/**
	 * Admin page slug.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-audience-subscriptions';

	/**
	 * Parent slug.
	 *
	 * @var string
	 */
	protected $parent_slug = 'newspack-audience';


	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return esc_html__( 'Audience Management / Subscriptions', 'newspack-plugin' );
	}

	/**
	 * Add Subscriptions page.
	 */
	public function add_page() {
		add_submenu_page(
			$this->parent_slug,
			$this->get_name(),
			esc_html__( 'Subscriptions', 'newspack-plugin' ),
			$this->capability,
			$this->slug,
			[ $this, 'render_wizard' ]
		);
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		if ( ! $this->is_wizard_page() ) {
			return;
		}

		parent::enqueue_scripts_and_styles();
		wp_enqueue_script( 'newspack-wizards' );
		wp_localize_script(
			'newspack-wizards',
			'newspackAudienceSubscriptions',
			[
				'tabs' => [
					[
						'path'        => '/configuration',
						'title'       => esc_html__( 'Configuration', 'newspack-plugin' ),
						'header'      => esc_html__( 'Manage Subscriptions settings in Woo Memberships', 'newspack-plugin' ),
						'description' => esc_html__( 'You can manage the details of your subscription offerings in the Woo Memberships plugin.', 'newspack-plugin' ),
						'href'        => admin_url( 'edit.php?post_type=wc_membership_plan' ),
						'btn_text'    => esc_html__( 'Manage Subscriptions', 'newspack-plugin' ),
					],
					/**
					 * TODO: Add revenue tab when `custom revenue report` is completed, [see related comment](https://github.com/Automattic/newspack-plugin/pull/3565#discussion_r1891884248).
					 */

					// phpcs:disable Squiz.PHP.CommentedOutCode.Found

					/*
					[
						'path'        => '/revenue',
						'title'       => esc_html__( 'Revenue', 'newspack-plugin' ),
						'header'      => esc_html__( 'View Subscription Revenue in WooCommerce', 'newspack-plugin' ),
						'description' => esc_html__( 'You can view revenue data from Donations and Subscriptions in the WooCommerce Plugin.', 'newspack-plugin' ),
						'href'        => admin_url( 'admin.php?page=wc-reports' ),
						'btn_text'    => esc_html__( 'View Subscription Revenue', 'newspack-plugin' ),
					],
					*/

					// phpcs:enable Squiz.PHP.CommentedOutCode.Found
				],
			]
		);
	}
}
