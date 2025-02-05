<?php
/**
 * Plugin Name: CMB2 Field Type: Typography
 * Description: Typography field type for CMB2.
 * Version: 0.0.1
 * Author: Edouard Duplessis
 * Author URI: eduplessis.com
 * License: GPLv2+
 */


function cmb2_init_typography_field() {
	require_once dirname( __FILE__ ) . '/class-cmb2-render-typography-field.php';
	CMB2_Render_Typography_Field::init();
}
add_action( 'cmb2_init', 'cmb2_init_typography_field' );
