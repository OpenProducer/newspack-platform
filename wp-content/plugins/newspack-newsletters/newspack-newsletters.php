<?php
/**
 * Plugin Name:     Newspack Newsletters
 * Plugin URI:      https://newspack.pub
 * Description:     Newsletter authoring using the Gutenberg editor.
 * Author:          Automattic
 * Text Domain:     newspack-newsletters
 * Domain Path:     /languages
 * Version:         1.45.0
 *
 * @package         Newspack_Newsletters
 */

defined( 'ABSPATH' ) || exit;

// Define NEWSPACK_NEWSLETTERS_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_NEWSLETTERS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_NEWSLETTERS_PLUGIN_FILE', plugin_dir_path( __FILE__ ) );
}

/**
 * If a Letterhead endpoint hasn't been added, for instance for development or to point at
 * a separate instance, we'll set a default.
 */
if ( ! defined( 'NEWSPACK_NEWSLETTERS_LETTERHEAD_ENDPOINT' ) ) {
	define( 'NEWSPACK_NEWSLETTERS_LETTERHEAD_ENDPOINT', 'https://api.tryletterhead.com' );
}
// Include main plugin resources.
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/vendor/autoload.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/interface-newspack-newsletters-esp-service.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/interface-newspack-newsletters-wp-hookable.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/class-newspack-newsletters-service-provider.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/class-newspack-newsletters-service-provider-controller.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/mailchimp/class-newspack-newsletters-mailchimp.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/mailchimp/class-newspack-newsletters-mailchimp-controller.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/constant_contact/class-newspack-newsletters-constant-contact.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/constant_contact/class-newspack-newsletters-constant-contact-controller.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/constant_contact/class-newspack-newsletters-constant-contact-sdk.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/campaign_monitor/class-newspack-newsletters-campaign-monitor.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/campaign_monitor/class-newspack-newsletters-campaign-monitor-controller.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/active_campaign/class-newspack-newsletters-active-campaign.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/active_campaign/class-newspack-newsletters-active-campaign-controller.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/letterhead/class-newspack-newsletters-letterhead.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/letterhead/dtos/class-newspack-newsletters-letterhead-promotion-dto.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/service-providers/letterhead/models/class-newspack-newsletters-letterhead-promotion.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters-editor.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters-layouts.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters-settings.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters-renderer.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters-ads.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters-bulk-actions.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters-quick-edit.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters-embed.php';
require_once NEWSPACK_NEWSLETTERS_PLUGIN_FILE . '/includes/class-newspack-newsletters.php';
