<?php
/**
 * Newspack Listings Block Patterns.
 *
 * Custom Gutenberg Block Patterns for Newspack Listings.
 *
 * @package Newspack_Listings
 */

namespace Newspack_Listings;

use \Newspack_Listings\Newspack_Listings_Core as Core;

defined( 'ABSPATH' ) || exit;

/**
 * Blocks class.
 * Sets up custom blocks for listings.
 */
final class Newspack_Listings_Block_Patterns {
	/**
	 * Slug for the block pattern category.
	 */
	const NEWSPACK_LISTINGS_BLOCK_PATTERN_CATEGORY = 'newspack-listings__business-patterns';

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Listings_Block_Patterns
	 */
	protected static $instance = null;

	/**
	 * Main Newspack_Listings_Block_Patterns instance.
	 * Ensures only one instance of Newspack_Listings_Block_Patterns is loaded or can be loaded.
	 *
	 * @return Newspack_Listings_Block_Patterns - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ __CLASS__, 'register_block_pattern_category' ], 10 );
		add_action( 'admin_init', [ __CLASS__, 'register_block_patterns' ], 11 );
	}

	/**
	 * Register custom block pattern category for Newspack Listings.
	 */
	public static function register_block_pattern_category() {
		return register_block_pattern_category(
			self::NEWSPACK_LISTINGS_BLOCK_PATTERN_CATEGORY,
			[ 'label' => __( 'Newspack Listings', 'newspack-listings' ) ]
		);
	}

	/**
	 * Register custom block patterns for Newspack Listings.
	 * These patterns should only be available for certain CPTs.
	 */
	public static function register_block_patterns() {
		// Block pattern config.
		$block_patterns = [
			'business_1' => [
				'post_types' => [
					Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'settings'   => [
					'title'       => __( 'Business Listing 1', 'newspack-listings' ),
					'categories'  => [ self::NEWSPACK_LISTINGS_BLOCK_PATTERN_CATEGORY ],
					'description' => _x(
						'Business description, map, contact info, and hours of operation.',
						'Block pattern description',
						'newspack-listings'
					),
					'content'     => '<!-- wp:group {"className":"newspack-listings__business-pattern-1"} --><div class="wp-block-group newspack-listings__business-pattern-1"><div class="wp-block-group__inner-container"><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} --><div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:paragraph --><p>This is the description of your listing. It can be some sort of introduction, a quick overview, or anything you want to tell your visitors about this listing.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"width":"33.33%"} --><div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:jetpack/map {"points":[{"placeTitle":"29th Street","title":"29th Street","caption":"60 29th Street, San Francisco, California 94110, United States","id":"address.8078744744650036","coordinates":{"longitude":-122.421678,"latitude":37.744137}}],"zoom":14.528317036017558,"mapCenter":{"lng":-122.421678,"lat":37.744137}} --><div class="wp-block-jetpack-map" data-map-style="default" data-map-details="true" data-points="[{&quot;placeTitle&quot;:&quot;29th Street&quot;,&quot;title&quot;:&quot;29th Street&quot;,&quot;caption&quot;:&quot;60 29th Street, San Francisco, California 94110, United States&quot;,&quot;id&quot;:&quot;address.8078744744650036&quot;,&quot;coordinates&quot;:{&quot;longitude&quot;:-122.421678,&quot;latitude&quot;:37.744137}}]" data-zoom="14.528317036017558" data-map-center="{&quot;lng&quot;:-122.421678,&quot;lat&quot;:37.744137}" data-marker-color="red" data-show-fullscreen-button="true"><ul><li><a href="https://www.google.com/maps/search/?api=1&amp;query=37.744137,-122.421678">29th Street</a></li></ul></div><!-- /wp:jetpack/map --><!-- wp:jetpack/contact-info --><div class="wp-block-jetpack-contact-info"><!-- wp:jetpack/email {"email":"email@yourgroovydomain.com"} --><div class="wp-block-jetpack-email"><a href="mailto:email@yourgroovydomain.com">email@yourgroovydomain.com</a></div><!-- /wp:jetpack/email --><!-- wp:jetpack/phone {"phone":"1-877 273-3049"} --><div class="wp-block-jetpack-phone"><a href="tel:18772733049">1-877 273-3049</a></div><!-- /wp:jetpack/phone --><!-- wp:jetpack/address {"address":"60 29th Street #343","city":"San Francisco","region":"CA","postal":"94110"} --><div class="wp-block-jetpack-address"><div class="jetpack-address__address jetpack-address__address1">60 29th Street #343</div><div><span class="jetpack-address__city">San Francisco</span>, <span class="jetpack-address__region">CA</span> <span class="jetpack-address__postal">94110</span></div></div><!-- /wp:jetpack/address --></div><!-- /wp:jetpack/contact-info --><!-- wp:separator {"className":"is-style-wide"} --><hr class="wp-block-separator is-style-wide"/><!-- /wp:separator --><!-- wp:jetpack/business-hours /--></div><!-- /wp:column --></div><!-- /wp:columns --></div></div><!-- /wp:group -->',
				],
			],
			'business_2' => [
				'post_types' => [
					Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'settings'   => [
					'title'       => __( 'Business Listing 2', 'newspack-listings' ),
					'categories'  => [ self::NEWSPACK_LISTINGS_BLOCK_PATTERN_CATEGORY ],
					'description' => _x(
						'Business description, map, contact info, and hours of operation.',
						'Block pattern description',
						'newspack-listings'
					),
					'content'     => '<!-- wp:group {"className":"newspack-listings__business-pattern-2"} --><div class="wp-block-group newspack-listings__business-pattern-2"><div class="wp-block-group__inner-container"><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"width":"50%"} --><div class="wp-block-column" style="flex-basis:50%"><!-- wp:paragraph --><p>This is the description of your listing. It can be some sort of introduction, a quick overview, or anything you want to tell your visitors about this listing.</p><!-- /wp:paragraph --><!-- wp:jetpack/contact-info --><div class="wp-block-jetpack-contact-info"><!-- wp:jetpack/email {"email":"email@yourgroovydomain.com"} --><div class="wp-block-jetpack-email"><a href="mailto:email@yourgroovydomain.com">email@yourgroovydomain.com</a></div><!-- /wp:jetpack/email --><!-- wp:jetpack/phone {"phone":"1-877 273-3049"} --><div class="wp-block-jetpack-phone"><a href="tel:18772733049">1-877 273-3049</a></div><!-- /wp:jetpack/phone --><!-- wp:jetpack/address {"address":"60 29th Street #343","city":"San Francisco","region":"CA","postal":"94110"} --><div class="wp-block-jetpack-address"><div class="jetpack-address__address jetpack-address__address1">60 29th Street #343</div><div><span class="jetpack-address__city">San Francisco</span>, <span class="jetpack-address__region">CA</span> <span class="jetpack-address__postal">94110</span></div></div><!-- /wp:jetpack/address --></div><!-- /wp:jetpack/contact-info --><!-- wp:jetpack/business-hours /--></div><!-- /wp:column --><!-- wp:column {"width":"50%"} --><div class="wp-block-column" style="flex-basis:50%"><!-- wp:jetpack/map {"points":[{"placeTitle":"29th Street","title":"29th Street","caption":"60 29th Street, San Francisco, California 94110, United States","id":"address.8078744744650036","coordinates":{"longitude":-122.421678,"latitude":37.744137}}],"zoom":12,"mapCenter":{"lng":-122.421678,"lat":37.744137}} --><div class="wp-block-jetpack-map" data-map-style="default" data-map-details="true" data-points="[{&quot;placeTitle&quot;:&quot;29th Street&quot;,&quot;title&quot;:&quot;29th Street&quot;,&quot;caption&quot;:&quot;60 29th Street, San Francisco, California 94110, United States&quot;,&quot;id&quot;:&quot;address.8078744744650036&quot;,&quot;coordinates&quot;:{&quot;longitude&quot;:-122.421678,&quot;latitude&quot;:37.744137}}]" data-zoom="12" data-map-center="{&quot;lng&quot;:-122.421678,&quot;lat&quot;:37.744137}" data-marker-color="red" data-show-fullscreen-button="true"><ul><li><a href="https://www.google.com/maps/search/?api=1&amp;query=37.744137,-122.421678">29th Street</a></li></ul></div><!-- /wp:jetpack/map --></div><!-- /wp:column --></div><!-- /wp:columns --></div></div><!-- /wp:group -->',
				],
			],
			'business_3' => [
				'post_types' => [
					Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'settings'   => [
					'title'       => __( 'Business Listing 3', 'newspack-listings' ),
					'categories'  => [ self::NEWSPACK_LISTINGS_BLOCK_PATTERN_CATEGORY ],
					'description' => _x(
						'Business description, map, contact info, and hours of operation.',
						'Block pattern description',
						'newspack-listings'
					),
					'content'     => '<!-- wp:group {"className":"newspack-listings__business-pattern-3"} --><div class="wp-block-group newspack-listings__business-pattern-3"><div class="wp-block-group__inner-container"><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} --><div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:jetpack/map {"points":[{"placeTitle":"29th Street","title":"29th Street","caption":"60 29th Street, San Francisco, California 94110, United States","id":"address.8078744744650036","coordinates":{"longitude":-122.421678,"latitude":37.744137}}],"zoom":12,"mapCenter":{"lng":-122.421678,"lat":37.744137}} --><div class="wp-block-jetpack-map" data-map-style="default" data-map-details="true" data-points="[{&quot;placeTitle&quot;:&quot;29th Street&quot;,&quot;title&quot;:&quot;29th Street&quot;,&quot;caption&quot;:&quot;60 29th Street, San Francisco, California 94110, United States&quot;,&quot;id&quot;:&quot;address.8078744744650036&quot;,&quot;coordinates&quot;:{&quot;longitude&quot;:-122.421678,&quot;latitude&quot;:37.744137}}]" data-zoom="12" data-map-center="{&quot;lng&quot;:-122.421678,&quot;lat&quot;:37.744137}" data-marker-color="red" data-show-fullscreen-button="true"><ul><li><a href="https://www.google.com/maps/search/?api=1&amp;query=37.744137,-122.421678">29th Street</a></li></ul></div><!-- /wp:jetpack/map --></div><!-- /wp:column --><!-- wp:column {"width":"33.33%"} --><div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:jetpack/contact-info --><div class="wp-block-jetpack-contact-info"><!-- wp:jetpack/email {"email":"email@yourgroovydomain.com"} --><div class="wp-block-jetpack-email"><a href="mailto:email@yourgroovydomain.com">email@yourgroovydomain.com</a></div><!-- /wp:jetpack/email --><!-- wp:jetpack/phone {"phone":"1-877 273-3049"} --><div class="wp-block-jetpack-phone"><a href="tel:18772733049">1-877 273-3049</a></div><!-- /wp:jetpack/phone --><!-- wp:jetpack/address {"address":"60 29th Street #343","city":"San Francisco","region":"CA","postal":"94110"} --><div class="wp-block-jetpack-address"><div class="jetpack-address__address jetpack-address__address1">60 29th Street #343</div><div><span class="jetpack-address__city">San Francisco</span>, <span class="jetpack-address__region">CA</span> <span class="jetpack-address__postal">94110</span></div></div><!-- /wp:jetpack/address --></div><!-- /wp:jetpack/contact-info --><!-- wp:separator {"className":"is-style-wide"} --><hr class="wp-block-separator is-style-wide"/><!-- /wp:separator --><!-- wp:jetpack/business-hours /--></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:separator {"className":"is-style-wide"} --><hr class="wp-block-separator is-style-wide"/><!-- /wp:separator --><!-- wp:paragraph --><p>This is the description of your listing. It can be some sort of introduction, a quick overview, or anything you want to tell your visitors about this listing.</p><!-- /wp:paragraph --></div></div><!-- /wp:group -->',
				],
			],
			'business_4' => [
				'post_types' => [
					Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'settings'   => [
					'title'       => __( 'Business Listing 4', 'newspack-listings' ),
					'categories'  => [ self::NEWSPACK_LISTINGS_BLOCK_PATTERN_CATEGORY ],
					'description' => _x(
						'Business description, map, contact info, and hours of operation.',
						'Block pattern description',
						'newspack-listings'
					),
					'content'     => '<!-- wp:group {"className":"newspack-listings__business-pattern-4"} --><div class="wp-block-group newspack-listings__business-pattern-4"><div class="wp-block-group__inner-container"><!-- wp:jetpack/map {"points":[{"placeTitle":"29th Street","title":"29th Street","caption":"60 29th Street, San Francisco, California 94110, United States","id":"address.8078744744650036","coordinates":{"longitude":-122.421678,"latitude":37.744137}}],"zoom":12,"mapCenter":{"lng":-122.421678,"lat":37.744137},"mapHeight":400} --><div class="wp-block-jetpack-map" data-map-style="default" data-map-details="true" data-points="[{&quot;placeTitle&quot;:&quot;29th Street&quot;,&quot;title&quot;:&quot;29th Street&quot;,&quot;caption&quot;:&quot;60 29th Street, San Francisco, California 94110, United States&quot;,&quot;id&quot;:&quot;address.8078744744650036&quot;,&quot;coordinates&quot;:{&quot;longitude&quot;:-122.421678,&quot;latitude&quot;:37.744137}}]" data-zoom="12" data-map-center="{&quot;lng&quot;:-122.421678,&quot;lat&quot;:37.744137}" data-marker-color="red" data-map-height="400" data-show-fullscreen-button="true"><ul><li><a href="https://www.google.com/maps/search/?api=1&amp;query=37.744137,-122.421678">29th Street</a></li></ul></div><!-- /wp:jetpack/map --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} --><div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:paragraph --><p>This is the description of your listing. It can be some sort of introduction, a quick overview, or anything you want to tell your visitors about this listing.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"width":"33.33%"} --><div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:group {"style":{"color":{"background":"#fafafa"}}} --><div class="wp-block-group has-background" style="background-color:#fafafa"><div class="wp-block-group__inner-container"><!-- wp:jetpack/contact-info --><div class="wp-block-jetpack-contact-info"><!-- wp:jetpack/email {"email":"email@yourgroovydomain.com"} --><div class="wp-block-jetpack-email"><a href="mailto:email@yourgroovydomain.com">email@yourgroovydomain.com</a></div><!-- /wp:jetpack/email --><!-- wp:jetpack/phone {"phone":"1-877 273-3049"} --><div class="wp-block-jetpack-phone"><a href="tel:18772733049">1-877 273-3049</a></div><!-- /wp:jetpack/phone --><!-- wp:jetpack/address {"address":"60 29th Street #343","city":"San Francisco","region":"CA","postal":"94110"} --><div class="wp-block-jetpack-address"><div class="jetpack-address__address jetpack-address__address1">60 29th Street #343</div><div><span class="jetpack-address__city">San Francisco</span>, <span class="jetpack-address__region">CA</span> <span class="jetpack-address__postal">94110</span></div></div><!-- /wp:jetpack/address --></div><!-- /wp:jetpack/contact-info --><!-- wp:jetpack/business-hours /--></div></div><!-- /wp:group --></div><!-- /wp:column --></div><!-- /wp:columns --></div></div><!-- /wp:group -->', // phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation
				],
			],
			'business_5' => [
				'post_types' => [
					Core::NEWSPACK_LISTINGS_POST_TYPES['marketplace'],
					Core::NEWSPACK_LISTINGS_POST_TYPES['place'],
				],
				'settings'   => [
					'title'       => __( 'Business Listing 5', 'newspack-listings' ),
					'categories'  => [ self::NEWSPACK_LISTINGS_BLOCK_PATTERN_CATEGORY ],
					'description' => _x(
						'Business description, map, contact info, and hours of operation.',
						'Block pattern description',
						'newspack-listings'
					),
					'content'     => '<!-- wp:group {"className":"newspack-listings__business-pattern-5"} --><div class="wp-block-group newspack-listings__business-pattern-5"><div class="wp-block-group__inner-container"><!-- wp:columns {"className":"is-style-default"} --><div class="wp-block-columns is-style-default"><!-- wp:column --><div class="wp-block-column"><!-- wp:jetpack/map {"points":[{"placeTitle":"29th Street","title":"29th Street","caption":"60 29th Street, San Francisco, California 94110, United States","id":"address.8078744744650036","coordinates":{"longitude":-122.421678,"latitude":37.744137}}],"zoom":12,"mapCenter":{"lng":-122.421678,"lat":37.744137}} --><div class="wp-block-jetpack-map" data-map-style="default" data-map-details="true" data-points="[{&quot;placeTitle&quot;:&quot;29th Street&quot;,&quot;title&quot;:&quot;29th Street&quot;,&quot;caption&quot;:&quot;60 29th Street, San Francisco, California 94110, United States&quot;,&quot;id&quot;:&quot;address.8078744744650036&quot;,&quot;coordinates&quot;:{&quot;longitude&quot;:-122.421678,&quot;latitude&quot;:37.744137}}]" data-zoom="12" data-map-center="{&quot;lng&quot;:-122.421678,&quot;lat&quot;:37.744137}" data-marker-color="red" data-show-fullscreen-button="true"><ul><li><a href="https://www.google.com/maps/search/?api=1&amp;query=37.744137,-122.421678">29th Street</a></li></ul></div><!-- /wp:jetpack/map --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph --><p>This is the description of your listing. It can be some sort of introduction, a quick overview, or anything you want to tell your visitors about this listing.</p><!-- /wp:paragraph --><!-- wp:jetpack/contact-info --><div class="wp-block-jetpack-contact-info"><!-- wp:jetpack/email {"email":"email@yourgroovydomain.com"} --><div class="wp-block-jetpack-email"><a href="mailto:email@yourgroovydomain.com">email@yourgroovydomain.com</a></div><!-- /wp:jetpack/email --><!-- wp:jetpack/phone {"phone":"1-877 273-3049"} --><div class="wp-block-jetpack-phone"><a href="tel:18772733049">1-877 273-3049</a></div><!-- /wp:jetpack/phone --><!-- wp:jetpack/address {"address":"60 29th Street #343","city":"San Francisco","region":"CA","postal":"94110"} --><div class="wp-block-jetpack-address"><div class="jetpack-address__address jetpack-address__address1">60 29th Street #343</div><div><span class="jetpack-address__city">San Francisco</span>, <span class="jetpack-address__region">CA</span> <span class="jetpack-address__postal">94110</span></div></div><!-- /wp:jetpack/address --></div><!-- /wp:jetpack/contact-info --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:jetpack/business-hours /--></div><!-- /wp:column --></div><!-- /wp:columns --></div></div><!-- /wp:group -->',
				],
			],
		];

		/**
		 * Register block patterns for particular post types. We need to get the post type using the
		 * post ID from $_REQUEST since the global $post is not available inside the admin_init hook.
		 * If we can't determine the current post type, just register the patterns anyway.
		 */
		$post_id           = isset( $_REQUEST['post'] ) ? sanitize_text_field( $_REQUEST['post'] ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_post_type = ! empty( $post_id ) && function_exists( 'get_post_type' ) ? get_post_type( $post_id ) : null;

		foreach ( $block_patterns as $pattern_name => $config ) {
			if ( empty( $current_post_type ) || in_array( $current_post_type, $config['post_types'] ) ) {
				$pattern = register_block_pattern(
					'newspack-listings/' . $pattern_name,
					$config['settings']
				);
			}
		}
	}
}

Newspack_Listings_Block_Patterns::instance();
