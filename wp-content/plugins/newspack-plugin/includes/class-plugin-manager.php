<?php
/**
 * Newspack setup
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * General purpose class for managing installation/activation of plugins.
 */
class Plugin_Manager {

	/**
	 * Array of Newspack required plugins.
	 *
	 * @var array
	 */
	public static $required_plugins = [
		'jetpack',
		'amp',
		'pwa',
		'wordpress-seo',
		'google-site-kit',
		'newspack-blocks',
	];

	/**
	 * Get info about all the managed plugins and their status.
	 *
	 * @todo Define what the structure of this looks like better and load it up from a config or something.
	 *
	 * @return array of plugins info.
	 */
	public static function get_managed_plugins() {
		$managed_plugins = [
			'akismet'                       => [
				'Name'        => 'Akismet Anti-Spam',
				'Description' => 'Used by millions, Akismet is quite possibly the best way in the world to protect your blog from spam. It keeps your site protected even while you sleep.',
				'Author'      => 'Automattic',
				'AuthorURI'   => 'https://automattic.com/wordpress-plugins/',
				'PluginURI'   => 'https://akismet.com/',
				'Download'    => 'wporg',
			],
			'amp'                           => [
				'Name'        => __( 'AMP', 'newspack' ),
				'Description' => esc_html__( 'Enable AMP on your WordPress site, the WordPress way.', 'newspack' ),
				'Author'      => 'WordPress.com VIP, XWP, Google, and contributors',
				'PluginURI'   => 'https://amp-wp.org/',
				'AuthorURI'   => 'https://github.com/ampproject/amp-wp/graphs/contributors',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=amp-options',
			],
			'amp-tablepress'                => [
				'Name'        => 'AMP TablePress',
				'Description' => 'Adding AMP compatibility on top of the TablePress plugin.',
				'Author'      => 'Weston Ruter, Google',
				'AuthorURI'   => 'https://weston.ruter.net/',
				'PluginURI'   => 'https://github.com/westonruter/amp-tablepress',
				'Download'    => 'https://github.com/westonruter/amp-tablepress/releases/latest/download/amp-tablepress.zip',
			],
			'bing-webmaster-tools'          => [
				'Name'        => 'Bing URL Submissions',
				'Description' => 'Detects page creation/update in WordPress and automatically submits the URL behind the scenes to Bing, ensuring that the site pages are always fresh in the Bing index.',
				'Author'      => 'Bing Webmaster',
				'AuthorURI'   => 'https://www.bing.com/webmasters/homepage',
				'PluginURI'   => 'https://wordpress.org/plugins/bing-webmaster-tools/',
				'Download'    => 'wporg',
			],
			'broadstreet'                   => [
				'Name'        => 'Broadstreet',
				'Description' => 'Integrate Broadstreet business directory and adserving power into your site.',
				'Author'      => 'Broadstreet',
				'AuthorURI'   => 'https://broadstreetads.com',
				'PluginURI'   => 'https://broadstreetads.com',
				'Download'    => 'wporg',
			],
			'co-authors-plus'               => [
				'Name'        => 'Co-Authors Plus',
				'Description' => 'Allows multiple authors and guest authors to be assigned to a post.',
				'Author'      => 'Mohammad Jangda, Daniel Bachhuber, Automattic, Weston Ruter',
				'AuthorURI'   => 'https://wordpress.org/extend/plugins/co-authors-plus/',
				'PluginURI'   => 'https://wordpress.org/extend/plugins/co-authors-plus/',
				'Download'    => 'wporg',
			],
			'constant-contact-forms'        => [
				'Name'        => 'Constant Contact Forms',
				'Description' => 'Be a better marketer. All it takes is Constant Contact email marketing.',
				'Author'      => 'Constant Contact',
				'AuthorURI'   => 'https://www.constantcontact.com/index?pn=miwordpress',
				'PluginURI'   => 'https://www.constantcontact.com',
				'Download'    => 'wporg',
			],
			'disqus-comment-system'         => [
				'Name'        => 'Disqus for WordPress',
				'Description' => 'Disqus helps publishers increase engagement and build loyal audiences.',
				'Author'      => 'Disqus',
				'AuthorURI'   => 'https://disqus.com/',
				'PluginURI'   => 'https://wordpress.org/plugins/disqus-comment-system/',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=disqus#install',
			],
			'documentcloud'                 => [
				'Name'        => 'DocumentCloud',
				'Description' => 'Embed DocumentCloud resources in WordPress content.',
				'Author'      => 'Chris Amico, Justin Reese',
				'AuthorURI'   => 'https://www.documentcloud.org/',
				'PluginURI'   => 'https://www.documentcloud.org/',
				'Download'    => 'wporg',
			],
			'fb-instant-articles'           => [
				'Name'        => 'Instant Articles for WP',
				'Description' => 'Add support for Instant Articles for Facebook to your WordPress site.',
				'Author'      => 'Automattic, Dekode, Facebook',
				'AuthorURI'   => 'https://vip.wordpress.com/plugins/instant-articles/',
				'PluginURI'   => 'https://vip.wordpress.com/plugins/instant-articles/',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=instant-articles-wizard',
			],
			'distributor'                   => [
				'Name'        => 'Distributor',
				'Description' => 'Makes it easy to distribute and reuse content across your websites, whether inside of a multisite or across the web.',
				'Author'      => '10up Inc.',
				'AuthorURI'   => 'https://distributorplugin.com/',
				'PluginURI'   => 'https://distributorplugin.com/',
				'Download'    => 'https://github.com/10up/distributor/releases/latest/download/distributor.zip',
				'EditPath'    => 'admin.php?page=pull',
			],
			'google-site-kit'               => [
				'Name'        => 'Google Site Kit',
				'Description' => 'Site Kit is is a one-stop solution for WordPress users to use everything Google has to offer to make them successful on the web.',
				'Author'      => 'Google',
				'AuthorURI'   => 'https://opensource.google.com',
				'PluginURI'   => 'https://sitekit.withgoogle.com/',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=googlesitekit-splash',
				'Configurer'  => [
					'filename'   => 'class-site-kit-configuration-manager.php',
					'class_name' => 'Site_Kit_Configuration_Manager',
				],
			],
			'jetpack'                       => [
				'Name'        => __( 'Jetpack', 'newspack' ),
				'Description' => esc_html__( 'Bring the power of the WordPress.com cloud to your self-hosted WordPress. Jetpack enables you to connect your blog to a WordPress.com account to use the powerful features normally only available to WordPress.com users.', 'newspack' ),
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://jetpack.com/',
				'AuthorURI'   => 'https://automattic.com/',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=jetpack',
			],
			'mailchimp-for-woocommerce'     => [
				'Name'        => 'Mailchimp for WooCommerce',
				'Description' => esc_html__( 'Connects WooCommerce to Mailchimp to sync your store data, send targeted campaigns to your customers, and sell more stuff.', 'newspack' ),
				'Author'      => 'Mailchimp',
				'AuthorURI'   => 'https://mailchimp.com',
				'PluginURI'   => 'https://mailchimp.com/connect-your-store/',
				'Download'    => 'wporg',
			],
			'mailchimp-for-wp'              => [
				'Name'        => 'MC4WP: Mailchimp for WordPress',
				'Description' => 'Mailchimp for WordPress by ibericode. Adds various highly effective sign-up methods to your site.',
				'Author'      => 'ibericode',
				'PluginURI'   => 'https://mc4wp.com',
				'AuthorURI'   => 'https://ibericode.com',
				'Download'    => 'wporg',
			],
			'newspack-ads'                  => [
				'Name'        => esc_html__( 'Newspack Ads', 'newspack' ),
				'Description' => esc_html__( 'Ads integration.', 'newspack' ),
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-ads/releases/latest/download/newspack-ads.zip',
			],
			'newspack-blocks'               => [
				'Name'        => esc_html__( 'Newspack Blocks', 'newspack' ),
				'Description' => esc_html__( 'A collection of blocks for news publishers.', 'newspack' ),
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-blocks/releases/latest/download/newspack-blocks.zip',
			],
			'newspack-content-converter'    => [
				'Name'        => esc_html__( 'Newspack Content Converter', 'newspack' ),
				'Description' => esc_html__( 'Batch conversion of Classic->Gutenberg post conversion.', 'newspack' ),
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-content-converter/releases/latest/download/newspack-content-converter.zip',
				'Quiet'       => true,
			],
			'newspack-disqus-amp'           => [
				'Name'        => 'Newspack Disqus AMP',
				'Description' => 'Adds AMP-compatibility to the Disqus plugin.',
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-disqus-amp/releases/latest/download/newspack-disqus-amp.zip',
			],
			'newspack-image-credits'        => [
				'Name'        => 'Newspack Image Credits',
				'Description' => 'Add photo credit info to images.',
				'Author'      => 'Automattic, INN Labs, Project Argo',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-image-credits/releases/latest/download/newspack-image-credits.zip',
			],
			'newspack-media-partners'       => [
				'Name'        => 'Newspack Media Partners',
				'Description' => 'Add media partners and their logos to posts. Intended for posts published in conjunction with other outlets.',
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-media-partners/releases/latest/download/newspack-media-partners.zip',
			],
			'newspack-newsletters'          => [
				'Name'        => 'Newspack Newsletters',
				'Description' => 'Newsletter authoring using the Gutenberg editor.',
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-newsletters/releases/latest/download/newspack-newsletters.zip',
			],
			'newspack-popups'               => [
				'Name'        => 'Newspack Campaigns',
				'Description' => 'AMP-compatible overlay and inline Campaigns.',
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-popups/releases/latest/download/newspack-popups.zip',
			],
			'newspack-sponsors'             => [
				'Name'        => 'Newspack Sponsors',
				'Description' => 'Sponsored and underwritten content for Newspack sites.',
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-sponsors/releases/latest/download/newspack-sponsors.zip',
			],
			'newspack-listings'             => [
				'Name'        => 'Newspack Listings',
				'Description' => 'Create reusable content in list form using the Gutenberg editor.',
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-listings/releases/latest/download/newspack-listings.zip',
			],
			'newspack-rename-comments'      => [
				'Name'        => 'Newspack Rename Comments',
				'Description' => 'Provides the Newspack theme with the ability to allow users to rename comments.',
				'Author'      => 'Philip John, Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-rename-comments/releases/latest/download/newspack-rename-comments.zip',
			],
			'newspack-supporters'           => [
				'Name'        => 'Newspack Supporters',
				'Description' => 'Manage and display your site\'s supporters.',
				'Author'      => 'Automattic',
				'PluginURI'   => 'https://newspack.blog',
				'AuthorURI'   => 'https://automattic.com',
				'Download'    => 'https://github.com/Automattic/newspack-supporters/releases/latest/download/newspack-supporters.zip',
			],
			'newspack-theme'                => [
				'Name'        => 'Newspack Theme',
				'Description' => 'The Newspack theme.',
				'Author'      => 'Newspack',
			],
			'organic-profile-block'         => [
				'Name'        => 'Organic Profile Block',
				'Description' => "The Profile Block is created for the Gutenberg content editor. It displays a profile section with an image, name, subtitle, bio and personal social media links. It's perfect for author biographies, personal profiles, or staff pages.",
				'Author'      => 'Organic Themes',
				'PluginURI'   => 'https://organicthemes.com/',
				'AuthorURI'   => 'https://organicthemes.com/',
				'Download'    => 'wporg',
			],
			'wp-parsely'                    => [
				'Name'        => 'Parse.ly',
				'Description' => 'This plugin makes it a snap to add Parse.ly tracking code to your WordPress blog.',
				'Author'      => 'Mike Sukmanowsky ( mike@parsely.com )',
				'PluginURI'   => 'https://www.parsely.com/',
				'AuthorURI'   => 'https://www.parsely.com/',
				'Download'    => 'wporg',
			],
			'password-protected'            => [
				'Name'        => 'Password Protected',
				'Description' => 'A very simple way to quickly password protect your WordPress site with a single password. Please note: This plugin does not restrict access to uploaded files and images and does not work with some caching setups.',
				'Author'      => 'Ben Huson',
				'PluginURI'   => 'https://github.com/benhuson/password-protected/',
				'AuthorURI'   => 'https://github.com/benhuson/password-protected/',
				'Download'    => 'wporg',
				'EditPath'    => 'options-general.php?page=password-protected',
			],
			'publish-to-apple-news'         => [
				'Name'        => 'Publish to Apple News',
				'Description' => 'Export and sync posts to Apple format.',
				'Author'      => 'Alley Interactive',
				'AuthorURI'   => 'https://www.alleyinteractive.com',
				'PluginURI'   => 'https://github.com/alleyinteractive/apple-news',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=apple_news_index',
				'Configurer'  => [
					'filename'   => 'class-publish-to-apple-news-configuration-manager.php',
					'class_name' => 'Publish_To_Apple_News_Configuration_Manager',
				],
			],
			'pwa'                           => [
				'Name'        => 'PWA',
				'Description' => 'Feature plugin to bring Progressive Web App (PWA) capabilities to Core.',
				'Author'      => 'PWA Plugin Contributors',
				'AuthorURI'   => 'https://github.com/xwp/pwa-wp/graphs/contributors',
				'PluginURI'   => 'https://github.com/xwp/pwa-wp',
				'Download'    => 'wporg',
			],
			'pym-shortcode'                 => [
				'Name'        => 'Pym.js Embeds',
				'Description' => 'A WordPress solution to embed iframes that are responsive horizontally and vertically using the NPR Visuals Team\'s Pym.js.',
				'Author'      => 'INN Labs',
				'AuthorURI'   => 'https://labs.inn.org/',
				'PluginURI'   => 'https://github.com/INN/pym-shortcode',
				'Download'    => 'wporg',
			],
			'redirection'                   => [
				'Name'        => 'Redirection',
				'Description' => 'Manage all your 301 redirects and monitor 404 errors.',
				'Author'      => 'John Godley',
				'AuthorURI'   => 'https://redirection.me/',
				'PluginURI'   => 'https://redirection.me/',
				'Download'    => 'wporg',
			],
			'regenerate-thumbnails'         => [
				'Name'        => 'Regenerate Thumbnails',
				'Description' => 'Regenerate the thumbnails for one or more of your image uploads. Useful when changing their sizes or your theme.',
				'Author'      => 'Alex Mills (Viper007Bond)',
				'AuthorURI'   => 'https://alex.blog/',
				'PluginURI'   => 'https://alex.blog/wordpress-plugins/regenerate-thumbnails/',
				'Download'    => 'wporg',
			],
			'super-cool-ad-inserter'        => [
				'Name'        => __( 'Super Cool Ad Inserter Plugin', 'newspack' ),
				'Description' => __( 'This WordPress plugin gives site administrators a way to insert widgets such as ads, newsletter signups, or calls to action into posts at set intervals.', 'newspack' ),
				'Author'      => 'INN Labs',
				'AuthorURI'   => 'https://labs.inn.org/',
				'PluginURI'   => 'https://wordpress.org/plugins/super-cool-ad-inserter/',
				'Download'    => 'wporg',
			],
			'tablepress'                    => [
				'Name'        => 'TablePress',
				'Description' => 'Embed beautiful and feature-rich tables into your posts and pages, without having to write code.',
				'Author'      => 'Tobias Bäthge',
				'AuthorURI'   => 'https://tobias.baethge.com/',
				'PluginURI'   => 'https://tablepress.org/',
				'Download'    => 'wporg',
			],
			'talk-wp-plugin'                => [
				'Name'        => 'Coral Project',
				'Description' => 'A plugin to replace stock WP commenting with Coral Project comments.',
				'Author'      => 'Alley Interactive, The Coral Project',
				'AuthorURI'   => 'https://www.alleyinteractive.com',
				'PluginURI'   => 'hhttps://coralproject.net',
				'Download'    => 'https://github.com/coralproject/talk-wp-plugin/archive/v0.2.0.zip',
				'EditPath'    => 'options-general.php?page=talk-settings',
			],
			'woocommerce'                   => [
				'Name'        => __( 'WooCommerce', 'newspack' ),
				'Description' => esc_html__( 'An eCommerce toolkit that helps you sell anything. Beautifully.', 'newspack' ),
				'Author'      => 'WordPress.com VIP, XWP, Google, and contributors',
				'PluginURI'   => 'https://woocommerce.com/',
				'AuthorURI'   => 'https://woocommerce.com',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=wc-settings',
			],
			'woocommerce-gateway-stripe'    => [
				'Name'        => __( 'WooCommerce Stripe Gateway', 'newspack' ),
				'Description' => esc_html__( 'Take credit card payments on your store using Stripe.', 'newspack' ),
				'Author'      => 'WooCommerce',
				'PluginURI'   => 'https://woocommerce.com/',
				'AuthorURI'   => 'https://woocommerce.com/',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=wc-settings&tab=checkout&section=stripe',
			],
			'woocommerce-name-your-price'   => [
				'Name'        => __( 'WooCommerce Name Your Price', 'newspack' ),
				'Description' => esc_html__( 'WooCommerce Name Your Price allows customers to set their own price for products or donations.', 'newspack' ),
				'Author'      => 'Kathy Darling',
				'PluginURI'   => 'https://www.woocommerce.com/products/name-your-price/',
				'AuthorURI'   => 'https://kathyisawesome.com',
			],
			'woocommerce-subscriptions'     => [
				'Name'        => __( 'WooCommerce Subscriptions', 'newspack' ),
				'Description' => esc_html__( 'An eCommerce toolkit that helps you sell anything. Beautifully.', 'newspack' ),
				'Author'      => 'WooCommerce',
				'PluginURI'   => 'https://woocommerce.com/products/woocommerce-subscriptions/',
				'AuthorURI'   => 'https://woocommerce.com/',
			],
			'automatewoo'                   => [
				'Name'        => 'AutomateWoo',
				'Description' => esc_html__( 'Convert and retain customers with automated marketing that does the hard work for you.', 'newspack' ),
				'Author'      => 'WooCommerce',
				'PluginURI'   => 'https://woocommerce.com/products/woocommerce-subscriptions/',
				'AuthorURI'   => 'https://woocommerce.com/',
			],
			'automatewoo-refer-a-friend'    => [
				'Name'        => __( 'AutomateWoo - Refer A Friend', 'newspack' ),
				'Description' => esc_html__( 'Boost your organic sales by adding a customer referral program to your WooCommerce store.', 'newspack' ),
				'Author'      => 'WooCommerce',
				'PluginURI'   => 'https://woocommerce.com/products/automatewoo-refer-a-friend/',
				'AuthorURI'   => 'https://woocommerce.com/',
			],
			'wordpress-seo'                 => [
				'Name'        => 'Yoast SEO',
				'Description' => 'The first true all-in-one SEO solution for WordPress, including on-page content analysis, XML sitemaps and much more.',
				'Author'      => 'Team Yoast',
				'AuthorURI'   => 'https://yoa.st/1uk',
				'Download'    => 'wporg',
				'EditPath'    => 'admin.php?page=wpseo_dashboard',
			],
			'wordpress-seo-premium'         => [
				'Name'  => 'Yoast SEO Premium',
				'Quiet' => true,
			],
			'wpseo-news'                    => [
				'Name'  => 'Yoast SEO: News',
				'Quiet' => true,
			],
			'wordpress-settings-discussion' => [
				'Name'     => 'Wordpress Commenting',
				'WPCore'   => true,
				'EditPath' => 'options-discussion.php',
			],
			'wp-gdpr-cookie-notice'         => [
				'Name'        => 'WP GDPR Cookie Notice',
				'Description' => esc_html__( 'Simple performant cookie consent notice that supports AMP, granular cookie control and live preview customization.', 'newspack' ),
				'Author'      => 'Felix Arntz',
				'AuthorURI'   => 'https://felix-arntz.me/',
				'PluginURI'   => 'https://wordpress.org/plugins/wp-gdpr-cookie-notice/',
				'Download'    => 'wporg',
			],
			'wp-user-avatar'                => [
				'Name'        => 'WP User Avatar',
				'Description' => 'Use any image from your WordPress Media Library as a custom user avatar. Add your own Default Avatar.',
				'Author'      => 'flippercode',
				'AuthorURI'   => 'https://www.flippercode.com/',
				'PluginURI'   => 'https://wordpress.org/plugins/wp-user-avatar/',
				'Download'    => 'wporg',
			],
			'web-stories'                   => [
				'Name'        => 'Web Stories',
				'Description' => 'Visual storytelling for WordPress.',
				'Author'      => 'Google',
				'AuthorURI'   => 'https://opensource.google.com/',
				'PluginURI'   => 'https://wp.stories.google/',
				'Download'    => 'wporg',
			],
		];

		$default_info = [
			'Name'        => '',
			'Description' => '',
			'Author'      => '',
			'Version'     => '',
			'PluginURI'   => '',
			'AuthorURI'   => '',
			'TextDomain'  => '',
			'DomainPath'  => '',
			'Download'    => '',
			'Status'      => '',
		];

		// Add plugin status info and fill in defaults.
		$installed_plugins = self::get_installed_plugins();
		foreach ( $managed_plugins as $plugin_slug => $managed_plugin ) {
			$status = 'uninstalled';
			if ( isset( $installed_plugins[ $plugin_slug ] ) ) {
				if ( is_plugin_active( $installed_plugins[ $plugin_slug ] ) ) {
					$status = 'active';
				} else {
					$status = 'inactive';
				}
			}
			if ( 'newspack-theme' === $plugin_slug ) {
				if ( 'newspack-theme' === get_stylesheet() ) {
					$status = 'active';
				}
			}
			if ( isset( $managed_plugin['WPCore'] ) ) {
				$status = 'active';
			}
			if ( Newspack::is_debug_mode() ) {
				$status = 'active';
			}
			$managed_plugins[ $plugin_slug ]['Status']      = $status;
			$managed_plugins[ $plugin_slug ]['Slug']        = $plugin_slug;
			$managed_plugins[ $plugin_slug ]['HandoffLink'] = isset( $managed_plugins[ $plugin_slug ]['EditPath'] ) ? admin_url( $managed_plugins[ $plugin_slug ]['EditPath'] ) : null;
			$managed_plugins[ $plugin_slug ]                = wp_parse_args( $managed_plugins[ $plugin_slug ], $default_info );
		}
		return $managed_plugins;
	}

	/**
	 * Get info about all the unmanaged plugins that are installed.
	 *
	 * @return array of plugin info.
	 */
	public static function get_unmanaged_plugins() {
		$plugins_info      = self::get_installed_plugins_info();
		$managed_plugins   = self::get_managed_plugins();
		$ignore            = [ 'newspack-plugin' ];
		$unmanaged_plugins = [];
		foreach ( $plugins_info as $slug => $info ) {
			if ( ! isset( $managed_plugins[ $slug ] ) && ! in_array( $slug, $ignore ) && is_plugin_active( $info['Path'] ) ) {
				$unmanaged_plugins[ $slug ] = $info;
			}
		}
		return $unmanaged_plugins;
	}

	/**
	 * Get info about all the required plugins that aren't installed and active.
	 *
	 * @return array of plugin info.
	 */
	public static function get_missing_plugins() {
		$managed_plugins = self::get_managed_plugins();
		$plugins_info    = self::get_installed_plugins_info();
		$missing_plugins = array();
		foreach ( self::$required_plugins as $slug ) {
			if ( ! isset( $plugins_info[ $slug ] ) || ! is_plugin_active( $plugins_info[ $slug ]['Path'] ) ) {
				$missing_plugins[ $slug ] = $managed_plugins[ $slug ];
			}
		}
		return $missing_plugins;
	}

	/**
	 * Determine whether plugin installation is allowed in the current environment.
	 *
	 * @return bool
	 */
	public static function can_install_plugins() {
		if ( ( defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT ) ||
			( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Activate a plugin, installing it first if necessary.
	 *
	 * @param string $plugin The plugin slug or URL to the plugin.
	 * @return bool True on success. False on failure or if plugin was already activated.
	 */
	public static function activate( $plugin ) {
		if ( 'newspack-theme' === $plugin ) {
			return Theme_Manager::install_activate_theme();
		}
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_slug = self::get_plugin_slug( $plugin );
		if ( ! $plugin_slug ) {
			return new WP_Error( 'newspack_invalid_plugin', __( 'Invalid plugin.', 'newspack' ) );
		}

		$installed_plugins = self::get_installed_plugins();

		// Install the plugin if it's not installed already.
		$plugin_installed = isset( $installed_plugins[ $plugin_slug ] );
		if ( ! $plugin_installed ) {
			$plugin_installed = self::install( $plugin );
		}
		if ( is_wp_error( $plugin_installed ) ) {
			return $plugin_installed;
		}

		// Refresh the installed plugin list if the plugin isn't present because we just installed it.
		if ( ! isset( $installed_plugins[ $plugin_slug ] ) ) {
			$installed_plugins = self::get_installed_plugins();
		}

		if ( is_plugin_active( $installed_plugins[ $plugin_slug ] ) ) {
			return new WP_Error( 'newspack_plugin_already_active', __( 'The plugin is already active.', 'newspack' ) );
		}

		$activated = activate_plugin( $installed_plugins[ $plugin_slug ] );
		if ( is_wp_error( $activated ) ) {
			return new WP_Error( 'newspack_plugin_failed_activation', $activated->get_error_message() );
		}

		return true;
	}

	/**
	 * Deactivate a plugin.
	 *
	 * @param string $plugin The plugin slug (e.g. 'newspack') or path to the plugin file. e.g. ('newspack/newspack.php').
	 * @return bool True on success. False on failure.
	 */
	public static function deactivate( $plugin ) {
		$installed_plugins = self::get_installed_plugins();
		if ( ! in_array( $plugin, $installed_plugins ) && ! isset( $installed_plugins[ $plugin ] ) ) {
			return new WP_Error( 'newspack_plugin_not_installed', __( 'The plugin is not installed.', 'newspack' ) );
		}

		if ( isset( $installed_plugins[ $plugin ] ) ) {
			$plugin_file = $installed_plugins[ $plugin ];
		} else {
			$plugin_file = $plugin;
		}

		if ( ! is_plugin_active( $plugin_file ) ) {
			return new WP_Error( 'newspack_plugin_not_active', __( 'The plugin is not active.', 'newspack' ) );
		}

		deactivate_plugins( $plugin_file );
		if ( is_plugin_active( $plugin_file ) ) {
			return new WP_Error( 'newspack_plugin_failed_deactivation', __( 'Failed to deactivate plugin.', 'newspack' ) );
		}
		return true;
	}

	/**
	 * Get a simple list of all installed plugins.
	 *
	 * @return array of 'plugin_slug => plugin_file_path' entries for all installed plugins.
	 */
	public static function get_installed_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = array_reduce( array_keys( get_plugins() ), array( __CLASS__, 'reduce_plugin_info' ) );
		$themes  = array_reduce( array_keys( wp_get_themes() ), array( __CLASS__, 'reduce_plugin_info' ) );
		return array_merge( $plugins, $themes );
	}

	/**
	 * Get a list of all installed plugins, with complete info for each.
	 *
	 * @return array of 'plugin_slug => []' entries for all installed plugins.
	 */
	public static function get_installed_plugins_info() {
		$plugins = array_merge( get_plugins(), wp_get_themes() );

		$installed_plugins_info = [];
		foreach ( self::get_installed_plugins() as $key => $path ) {
			$installed_plugins_info[ $key ]         = $plugins[ $path ];
			$installed_plugins_info[ $key ]['Path'] = $path;
		}
		return $installed_plugins_info;
	}

	/**
	 * Parse a plugin slug from the slug or URL to download a plugin.
	 *
	 * @param string $plugin A plugin slug or the URL to a plugin zip file.
	 * @return string|bool Parsed slug on success. False on failure.
	 */
	public static function get_plugin_slug( $plugin ) {
		if ( ! is_string( $plugin ) || empty( $plugin ) ) {
			return false;
		}

		$url = wp_http_validate_url( $plugin );

		// A plugin slug was passed in, so just return it.
		if ( ! $url ) {
			return $plugin;
		}

		if ( ! stripos( $url, '.zip' ) ) {
			return false;
		}

		$result = preg_match_all( '/\/([^\.\/*]+)/', $url, $matches );
		if ( ! $result ) {
			return false;
		}

		$group = end( $matches );
		$slug  = end( $group );
		return $slug;
	}

	/**
	 * Installs a plugin.
	 *
	 * @param string $plugin Plugin slug or URL to plugin zip file.
	 * @return bool True on success. False on failure.
	 */
	public static function install( $plugin ) {
		if ( ! self::can_install_plugins() ) {
			return new WP_Error( 'newspack_plugin_failed_install', __( 'Plugins cannot be installed.', 'newspack' ) );
		}

		if ( wp_http_validate_url( $plugin ) ) {
			return self::install_from_url( $plugin );
		} else {
			return self::install_from_slug( $plugin );
		}
	}

	/**
	 * Uninstall a plugin.
	 *
	 * @param string $plugin The plugin slug (e.g. 'newspack') or path to the plugin file. e.g. ('newspack/newspack.php').
	 * @return bool True on success. False on failure.
	 */
	public static function uninstall( $plugin ) {
		if ( ! self::can_install_plugins() ) {
			return new WP_Error( 'newspack_plugin_failed_uninstall', __( 'Plugins cannot be uninstalled.', 'newspack' ) );
		}

		$installed_plugins = self::get_installed_plugins();
		if ( ! in_array( $plugin, $installed_plugins ) && ! isset( $installed_plugins[ $plugin ] ) ) {
			return new WP_Error( 'newspack_plugin_failed_uninstall', __( 'The plugin is not installed.', 'newspack' ) );
		}

		if ( isset( $installed_plugins[ $plugin ] ) ) {
			$plugin_file = $installed_plugins[ $plugin ];
		} else {
			$plugin_file = $plugin;
		}

		// Deactivate plugin before uninstalling.
		self::deactivate( $plugin_file );

		if ( ! function_exists( 'request_filesystem_credentials' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$success = (bool) delete_plugins( [ $plugin_file ] );
		if ( $success ) {
			wp_clean_plugins_cache();
			return true;
		}
		return new WP_Error( 'newspack_plugin_failed_uninstall', __( 'The plugin could not be uninstalled.', 'newspack' ) );
	}

	/**
	 * Install a plugin by slug.
	 *
	 * @param string $plugin_slug The slug for the plugin.
	 * @return Mixed True on success. WP_Error on failure.
	 */
	protected static function install_from_slug( $plugin_slug ) {
		// Quick check to make sure plugin directory doesn't already exist.
		$plugin_directory = WP_PLUGIN_DIR . '/' . $plugin_slug;
		if ( is_dir( $plugin_directory ) ) {
			return new WP_Error( 'newspack_plugin_already_installed', __( 'The plugin directory already exists.', 'newspack' ) );
		}

		$managed_plugins = self::get_managed_plugins();
		if ( ! isset( $managed_plugins[ $plugin_slug ] ) ) {
			return new WP_Error(
				'newspack_plugin_failed_install',
				__( 'Plugin not found.', 'newspack' )
			);
		}

		// Return a useful error if we are unable to get download info for the plugin.
		if ( empty( $managed_plugins[ $plugin_slug ]['Download'] ) ) {
			$error_message = __( 'Newspack cannot install this plugin. You will need to get it from the plugin\'s site and install it manually.', 'newspack' );
			if ( ! empty( $managed_plugins[ $plugin_slug ]['PluginURI'] ) ) {
				/* translators: %s: plugin URL */
				$error_message = sprintf( __( 'Newspack cannot install this plugin. You will need to get it from <a href="%s">the plugin\'s site</a> and install it manually.', 'newspack' ), esc_url( $managed_plugins[ $plugin_slug ]['PluginURI'] ) );
			}

			return new WP_Error(
				'newspack_plugin_failed_install',
				$error_message
			);
		}

		// If the plugin has a URL as its Download, install it from there.
		if ( wp_http_validate_url( $managed_plugins[ $plugin_slug ]['Download'] ) ) {
			return self::install_from_url( $managed_plugins[ $plugin_slug ]['Download'] );
		}

		if ( ! function_exists( 'plugins_api' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		}

		// Check WP.org for a download link, and install it from WP.org.
		$plugin_info = plugins_api(
			'plugin_information',
			[
				'slug'   => $plugin_slug,
				'fields' => [
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				],
			]
		);

		if ( is_wp_error( $plugin_info ) ) {
			return new WP_Error( 'newspack_plugin_failed_install', $plugin_info->get_error_message() );
		}

		return self::install_from_url( $plugin_info->download_link );
	}

	/**
	 * Install a plugin from an arbitrary URL.
	 *
	 * @param string $plugin_url The URL to the plugin zip file.
	 * @return bool True on success. False on failure.
	 */
	protected static function install_from_url( $plugin_url ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		WP_Filesystem();

		$skin     = new \Automatic_Upgrader_Skin();
		$upgrader = new \WP_Upgrader( $skin );
		$upgrader->init();

		$download = $upgrader->download_package( $plugin_url );
		if ( is_wp_error( $download ) ) {
			return new WP_Error( 'newspack_plugin_failed_install', $download->get_error_message() );
		}

		// GitHub appends random strings to the end of its downloads.
		// If we asked for foo.zip, make sure the downloaded file is called foo.tmp.
		if ( stripos( $plugin_url, 'github' ) ) {
			$plugin_url_parts  = explode( '/', $plugin_url );
			$desired_file_name = str_replace( '.zip', '', end( $plugin_url_parts ) );
			$new_file_name     = preg_replace( '#(' . $desired_file_name . '.*).tmp#', $desired_file_name . '.tmp', $download );
			rename( $download, $new_file_name ); // phpcs:ignore
			$download = $new_file_name;
		}

		$working_dir = $upgrader->unpack_package( $download );
		if ( is_wp_error( $working_dir ) ) {
			return new WP_Error( 'newspack_plugin_failed_install', $working_dir->get_error_message() );
		}

		$result = $upgrader->install_package(
			[
				'source'        => $working_dir,
				'destination'   => WP_PLUGIN_DIR,
				'clear_working' => true,
				'hook_extra'    => [
					'type'   => 'plugin',
					'action' => 'install',
				],
			]
		);
		if ( is_wp_error( $result ) ) {
			return new WP_Error( 'newspack_plugin_failed_install', $result->get_error_message() );
		}

		wp_clean_plugins_cache();
		return true;
	}

	/**
	 * Reduce get_plugins() info to form 'folder => file'.
	 *
	 * @param array  $plugins Associative array of plugin files to paths.
	 * @param string $key Plugin relative path. Example: newspack/newspack.php.
	 * @return array
	 */
	private static function reduce_plugin_info( $plugins, $key ) {
		$path   = explode( '/', $key );
		$folder = current( $path );

		// Strip version info from key. (e.g. 'woocommerce-stripe-gateway-4.1.2' should just be 'woocommerce-stripe-gateway').
		$folder = preg_replace( '/[\-0-9\.]+$/', '', $folder );

		$plugins[ $folder ] = $key;
		return $plugins;
	}
}
