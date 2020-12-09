<?php
/**
 * Server-side rendering of the `core/site-logo` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/site-logo` block on the server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string The render.
 */
function gutenberg_render_block_core_site_logo( $attributes ) {
	$adjust_width_height_filter = function ( $image ) use ( $attributes ) {
		if ( empty( $attributes['width'] ) ) {
			return $image;
		}
		$height = floatval( $attributes['width'] ) / ( floatval( $image[1] ) / floatval( $image[2] ) );
		return array( $image[0], intval( $attributes['width'] ), intval( $height ) );
	};

	add_filter( 'wp_get_attachment_image_src', $adjust_width_height_filter );
	$custom_logo = get_custom_logo();
	$classnames  = array();
	if ( ! empty( $attributes['className'] ) ) {
		$classnames[] = $attributes['className'];
	}

	if ( ! empty( $attributes['align'] ) && in_array( $attributes['align'], array( 'center', 'left', 'right' ), true ) ) {
		$classnames[] = "align{$attributes['align']}";
	}

	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => implode( ' ', $classnames ) ) );
	$html               = sprintf( '<div %s><a href="' . get_bloginfo( 'url' ) . '" rel="home" title="' . get_bloginfo( 'name' ) . '">%s</a></div>', $wrapper_attributes, $custom_logo );
	remove_filter( 'wp_get_attachment_image_src', $adjust_width_height_filter );
	return $html;
}


/**
 * Registers the `core/site-logo` block on the server.
 */
function gutenberg_register_block_core_site_logo() {
	register_block_type_from_metadata(
		__DIR__ . '/site-logo',
		array(
			'render_callback' => 'gutenberg_render_block_core_site_logo',
		)
	);
	add_filter( 'pre_set_theme_mod_custom_logo', 'gutenberg_sync_site_logo_to_theme_mod' );
	add_filter( 'theme_mod_custom_logo', 'gutenberg_override_custom_logo_theme_mod' );
}
add_action( 'init', 'gutenberg_register_block_core_site_logo', 20 );

/**
 * Overrides the custom logo with a site logo, if the option is set.
 *
 * @param string $custom_logo The custom logo set by a theme.
 *
 * @return string The site logo if set.
 */
function gutenberg_override_custom_logo_theme_mod( $custom_logo ) {
	$sitelogo = get_option( 'sitelogo' );
	return false === $sitelogo ? $custom_logo : $sitelogo;
}

/**
 * Syncs the site logo with the theme modified logo.
 *
 * @param string $custom_logo The custom logo set by a theme.
 *
 * @return string The custom logo.
 */
function gutenberg_sync_site_logo_to_theme_mod( $custom_logo ) {
	if ( $custom_logo ) {
		update_option( 'sitelogo', $custom_logo );
	}
	return $custom_logo;
}

/**
 * Register a core site setting for a site logo
 */
function gutenberg_gutenberg_register_block_core_site_logo_setting() {
	register_setting(
		'general',
		'sitelogo',
		array(
			'show_in_rest' => array(
				'name' => 'sitelogo',
			),
			'type'         => 'string',
			'description'  => __( 'Site logo.' ),
		)
	);
}

add_action( 'rest_api_init', 'gutenberg_gutenberg_register_block_core_site_logo_setting', 10 );
