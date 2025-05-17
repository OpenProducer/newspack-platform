<?php
/**
 * SVG Icons class
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Returns HTML markup for SVG icons, based on name.
 */
class Newspack_UI_Icons {

	/**
	 * Gets the SVG code for a given icon.
	 *
	 * @param string $icon Name of the icon to render.
	 */
	public static function get_svg( $icon ) {
		$arr = self::$ui_icons;
		if ( array_key_exists( $icon, $arr ) ) {
			return $arr[ $icon ];
		}
		return null;
	}

	/**
	 * Sanitizes the SVG code for a given icon
	 *
	 * @param string $icon Name of the icon to render.
	 */
	public static function print_svg( $icon ) {
		echo wp_kses( self::get_svg( $icon ), self::sanitize_svgs() );
	}

	/**
	 * Returns an array of 'acceptable' SVG tags to use with wp_kses().
	 */
	public static function sanitize_svgs() {
		$svg_args = array(
			'svg'      => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
			),
			'g'        => array(
				'fill'      => true,
				'fill-rule' => true,
			),
			'title'    => array(
				'title' => true,
			),
			'path'     => array(
				'd'         => true,
				'fill'      => true,
				'fill-rule' => true,
			),
			'defs'     => true,
			'clipPath' => true,
			'polygon'  => array(
				'points' => true,
			),
		);

		return $svg_args;
	}

	/**
	 * User Interface icons – svg sources.
	 *
	 * @var array
	 */
	public static $ui_icons = array(
		'account'     =>
			'<svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M7.25 16.4371C6.16445 15.2755 5.5 13.7153 5.5 12C5.5 8.41015 8.41015 5.5 12 5.5C15.5899 5.5 18.5 8.41015 18.5 12C18.5 13.7153 17.8356 15.2755 16.75 16.4371V16C16.75 14.4812 15.5188 13.25 14 13.25L10 13.25C8.48122 13.25 7.25 14.4812 7.25 16V16.4371ZM8.75 17.6304C9.70606 18.1835 10.8161 18.5 12 18.5C13.1839 18.5 14.2939 18.1835 15.25 17.6304V16C15.25 15.3096 14.6904 14.75 14 14.75L10 14.75C9.30964 14.75 8.75 15.3096 8.75 16V17.6304ZM4 12C4 7.58172 7.58172 4 12 4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12ZM14 10C14 11.1046 13.1046 12 12 12C10.8954 12 10 11.1046 10 10C10 8.89543 10.8954 8 12 8C13.1046 8 14 8.89543 14 10Z" />
			</svg>',
		'check'       =>
			'<svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z" />
			</svg>',
		'close'       =>
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
				<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
			</svg>',
		'error'       =>
			'<svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M12 20.8C16.8 20.8 20.8 16.9 20.8 12C20.8 7.2 16.9 3.2 12 3.2C7.2 3.2 3.2 7.1 3.2 12C3.2 16.8 7.2 20.8 12 20.8V20.8ZM12 4.8C16 4.8 19.2 8.1 19.2 12C19.2 16 16 19.2 12 19.2C8 19.2 4.8 15.9 4.8 12C4.8 8 8 4.8 12 4.8ZM13 7H11V13H13L13 7ZM13 15H11V17H13V15Z" />
			</svg>',
		'google'      =>
			'<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M19.6 10.227C19.6 9.51801 19.536 8.83701 19.418 8.18201H10V12.05H15.382C15.2706 12.6619 15.0363 13.2448 14.6932 13.7635C14.3501 14.2822 13.9054 14.726 13.386 15.068V17.578H16.618C18.509 15.836 19.6 13.273 19.6 10.228V10.227Z" fill="#4285F4"></path>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M9.99996 20C12.7 20 14.964 19.105 16.618 17.577L13.386 15.068C12.491 15.668 11.346 16.023 9.99996 16.023C7.39496 16.023 5.18996 14.263 4.40496 11.9H1.06396V14.49C1.89597 16.1468 3.17234 17.5395 4.7504 18.5126C6.32846 19.4856 8.14603 20.0006 9.99996 20Z" fill="#34A853"></path>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M4.405 11.9C4.205 11.3 4.091 10.66 4.091 10C4.091 9.34001 4.205 8.70001 4.405 8.10001V5.51001H1.064C0.364015 6.90321 -0.000359433 8.44084 2.66054e-07 10C2.66054e-07 11.614 0.386 13.14 1.064 14.49L4.404 11.9H4.405Z" fill="#FBBC05"></path>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M9.99996 3.977C11.468 3.977 12.786 4.482 13.823 5.473L16.691 2.605C14.959 0.99 12.695 0 9.99996 0C6.08996 0 2.70996 2.24 1.06396 5.51L4.40396 8.1C5.19196 5.736 7.39596 3.977 9.99996 3.977Z" fill="#EA4335"></path>
			</svg>',
		'menu'        =>
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
				<path d="M5 5v1.5h14V5H5zm0 7.8h14v-1.5H5v1.5zM5 19h14v-1.5H5V19z"/>
			</svg>',
		'info'        =>
			'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
				<path d="M12 3.2c-4.8 0-8.8 3.9-8.8 8.8 0 4.8 3.9 8.8 8.8 8.8 4.8 0 8.8-3.9 8.8-8.8 0-4.8-4-8.8-8.8-8.8zm0 16c-4 0-7.2-3.3-7.2-7.2C4.8 8 8 4.8 12 4.8s7.2 3.3 7.2 7.2c0 4-3.2 7.2-7.2 7.2zM11 17h2v-6h-2v6zm0-8h2V7h-2v2z"/>
			</svg>',
		'arrow-right' =>
			'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
				<path d="m14.5 6.5-1 1 3.7 3.7H4v1.6h13.2l-3.7 3.7 1 1 5.6-5.5z"/>
			</svg>',
	);
}
