<?php
/**
 * Patches for known issues affecting Newspack sites.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Patches {
	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_filter( 'redirect_canonical', [ __CLASS__, 'patch_redirect_canonical' ], 10, 2 );
		add_filter( 'wpseo_enhanced_slack_data', [ __CLASS__, 'use_cap_for_slack_preview' ] );
		add_action( 'admin_menu', [ __CLASS__, 'add_reusable_blocks_menu_link' ] );
		add_filter( 'wpseo_opengraph_url', [ __CLASS__, 'http_ogurls' ] );
		add_filter( 'map_meta_cap', [ __CLASS__, 'prevent_accidental_page_deletion' ], 10, 4 );
	}

	/**
	 * Patches an issue where links to the site's homepage containing a query param with plus characters can cause
	 * a redirect loop with certain types of cacheing. The most common example of this ocurs when a
	 * newsletter uses a utm_campaign parameter that contains a space, e.g. utm_campaign=my+campaign
	 * The causes a redirect loop on the homepage, which often causes the page to fail to load on mobile.
	 *
	 * Related: https://core.trac.wordpress.org/ticket/51733#ticket.
	 *
	 * @param string $redirect_url The redirect URL.
	 * @param string $requested_url The requested URL.
	 *
	 * @return bool Should redirect be allowed.
	 */
	public static function patch_redirect_canonical( $redirect_url, $requested_url ) {
		$parsed_redirect  = wp_parse_url( $redirect_url );
		$parsed_requested = wp_parse_url( $requested_url );

		// Scheme changed, do redirect.
		if ( $parsed_requested['scheme'] !== $parsed_redirect['scheme'] ) {
			return $redirect_url;
		}

		// Host changed, do redirect.
		if ( $parsed_requested['host'] !== $parsed_redirect['host'] ) {
			return $redirect_url;
		}

		// Path changed, do redirect.
		if ( $parsed_requested['path'] !== $parsed_redirect['path'] ) {
			return $redirect_url;
		}

		// Parse query args.
		parse_str( $parsed_redirect['query'], $query_redirect );
		parse_str( $parsed_requested['query'], $query_request );

		// Sort by keys, if the order changed.
		ksort( $query_redirect );
		ksort( $query_request );

		// If parsed query args are the same, skip redirect.
		if ( $query_redirect === $query_request ) {
			return false;
		}

		// Otherwise, do redirect.
		return $redirect_url;
	}

	/**
	 * Use the Co-Author in Slack preview metadata instead of the regular post author if needed.
	 *
	 * @param array $slack_data Array of data which will be output in twitter:data tags.
	 * @return array Modified $slack_data
	 */
	public static function use_cap_for_slack_preview( $slack_data ) {
		if ( function_exists( 'coauthors' ) && is_single() && isset( $slack_data[ __( 'Written by', 'wordpress-seo' ) ] ) ) {
			$slack_data[ __( 'Written by', 'wordpress-seo' ) ] = coauthors( null, null, null, null, false );
		}

		return $slack_data;
	}

	/**
	 * Add a menu link in WP Admin to easily edit and manage reusable blocks.
	 */
	public static function add_reusable_blocks_menu_link() {
		add_submenu_page( 'edit.php', 'manage_reusable_blocks', __( 'Reusable Blocks' ), 'read', 'edit.php?post_type=wp_block', '', 2 );
	}

	/**
	 * On Atomic infrastructure, URLs are `http` for Facebook requests.
	 * This forces the `og:url` to `http` for consistency, to prevent 301 redirect loop issues.
	 *
	 * @param string $og_url The opengraph URL.
	 * @return string modified $og_url
	 */
	public static function http_ogurls( $og_url ) {
		if ( defined( 'ATOMIC_SITE_ID' ) && ATOMIC_SITE_ID ) {
			$og_url = str_replace( 'https:', 'http:', $og_url );
		}
		return $og_url;
	}

	/**
	 * Prevent deletion of essential pages such as the homepage, blog posts page, and WooCommerce pages.
	 *
	 * @param array  $caps Primitive capabilities required of the user.
	 * @param string $cap Capability being checked.
	 * @param int    $user_id The user ID.
	 * @param array  $args Adds context to the capability check, typically starting with an object ID.
	 *
	 * @return array Filtered array of capabilities.
	 */
	public static function prevent_accidental_page_deletion( $caps, $cap, $user_id, $args ) {
		// If no $args to check, bail early.
		if ( empty( $args ) ) {
			return $caps;
		}

		$protected_post_ids = [];
		$post_id            = $args[0]; // First item is usually the post ID.

		// If $post_id isn't a valid post, bail early.
		if ( false === get_post_status( $post_id ) ) {
			return $caps;
		}

		// Dynamically look up post IDs for protected pages.
		// Homepage.
		$front_page = intval( get_option( 'page_on_front', -1 ) );
		if ( 0 < $front_page ) {
			$protected_post_ids[] = $front_page;
		}

		// Blog posts page.
		$posts_page = intval( get_option( 'page_for_posts', -1 ) );
		if ( 0 < $posts_page ) {
			$protected_post_ids[] = $posts_page;
		}

		// Privacy policy page.
		$privacy_policy = intval( get_option( 'wp_page_for_privacy_policy', -1 ) );
		if ( 0 < $privacy_policy ) {
			$protected_post_ids[] = $privacy_policy;
		}

		// WooCommerce pages.
		if ( function_exists( 'wc_get_page_id' ) && function_exists( 'wc_privacy_policy_page_id' ) ) {
			// WooCommerce myaccount page.
			$account_page = wc_get_page_id( 'myaccount' );
			if ( 0 < $account_page ) {
				$protected_post_ids[] = $account_page;
			}

			// WooCommerce shop page.
			$shop = wc_get_page_id( 'shop' );
			if ( 0 < $shop ) {
				$protected_post_ids[] = $shop;
			}

			// WooCommerce cart page.
			$cart = wc_get_page_id( 'cart' );
			if ( 0 < $cart ) {
				$protected_post_ids[] = $cart;
			}

			// WooCommerce checkout page.
			$checkout = wc_get_page_id( 'checkout' );
			if ( 0 < $checkout ) {
				$protected_post_ids[] = $checkout;
			}

			// WooCommerce view_order page.
			$view_order = wc_get_page_id( 'view_order' );
			if ( 0 < $view_order ) {
				$protected_post_ids[] = $view_order;
			}

			// WooCommerce terms page.
			$terms = wc_get_page_id( 'terms' );
			if ( 0 < $terms ) {
				$protected_post_ids[] = $terms;
			}

			// WooCommerce privacy policy page.
			$wc_privacy_policy = wc_privacy_policy_page_id();
			if ( 0 < $wc_privacy_policy ) {
				$protected_post_ids[] = $wc_privacy_policy;
			}
		}

		// If the current page ID is protected, and the capability being checked is for deletion, do not allow.
		if ( 'delete_post' === $cap && in_array( $post_id, $protected_post_ids, true ) ) {
			$caps[] = 'do_not_allow';
		}

		return $caps;
	}
}
Patches::init();
