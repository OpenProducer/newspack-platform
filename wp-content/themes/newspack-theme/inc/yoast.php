<?php
/**
 * Newspack Theme: Yoast customizations.
 *
 * @package Newspack
 */

add_action( 'after_setup_theme', 'newspack_theme_yoast_init', 20 );

/**
 * Add support for the Bluesky contact method while Yoast doesn't.
 *
 * @return void
 */
function newspack_theme_yoast_init() {

	if ( class_exists( 'Yoast\WP\SEO\User_Meta\Framework\Additional_Contactmethods\Facebook' ) ) {
		require_once get_template_directory() . '/inc/yoast-bluesky-contact-method.php';
		add_filter(
			'wpseo_additional_contactmethods',
			function( $contact_methods ) {

				// Bail if the Bluesky contact method is already registered.
				foreach ( $contact_methods as $contact_method ) {
					if ( 'bluesky' === $contact_method->get_key() ) {
						return $contact_methods;
					}
				}
				$contact_methods[] = new Newspack_Theme_Bluesky();
				return $contact_methods;
			}
		);
	}
}
