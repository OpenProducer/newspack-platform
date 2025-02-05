<?php
/**
 * Plugin Name: CMB2 Field Slider
 * Plugin URI:  https://github.com/mattkrupnik/cmb2-field-slider
 * Description: Slider field type for Custom Metaboxes and Fields for WordPress
 * Version:     1.1.2
 * Author:      Matt Krupnik
 * Author URI:  http://mattkrupnik.com
 * License:     GPLv2+
 */


class OWN_Field_Slider {

	const VERSION = '1.1.2';

	public function hooks() {
		add_filter( 'cmb2_render_own_slider',  array( $this, 'own_slider_field' ), 10, 5 );
	}
	
	public function own_slider_field($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
		// Only enqueue scripts if field is used.
		$this->setup_admin_scripts();
		$slider_width = $field->slider_width() ? $field->slider_width() : '';
	
		// Prepare conditional attributes
		$conditional_attrs = '';
		if (isset($field->args['attributes']['data-conditional'])) {
			$conditional_attrs = 'data-conditional="' . esc_attr($field->args['attributes']['data-conditional']) . '"';
		} else {
			if (isset($field->args['attributes']['data-conditional-id'])) {
				$conditional_attrs .= ' data-conditional-id="' . esc_attr($field->args['attributes']['data-conditional-id']) . '"';
			}
			if (isset($field->args['attributes']['data-conditional-value'])) {
				$conditional_attrs .= ' data-conditional-value="' . esc_attr($field->args['attributes']['data-conditional-value']) . '"';
			}
		}
	
		echo '<div class="own-slider-field" style="width:' . esc_html($slider_width) .  ';"></div>';
		
		// Store the output of input() method in a variable
		$input_html = $field_type_object->input(array(
			'type'       => 'hidden',
			'class'      => 'own-slider-field-value',
			'name'       => esc_attr($field_type_object->_name()),
			'id'         => esc_attr($field_type_object->_id()),
			'readonly'   => 'readonly',
			'data-start' => esc_attr($field_escaped_value),
			'data-min'   => esc_attr($field->min()),
			'data-max'   => esc_attr($field->max()),
			'data-step'  => esc_attr($field->step()),
			'desc'       => '',
			'extra_attributes' => $conditional_attrs
		));
	
		// Use wp_kses() or another suitable escaping function to ensure safe output
		echo wp_kses($input_html, array(
			'input' => array(
				'type' => array(),
				'class' => array(),
				'name' => array(),
				'id' => array(),
				'readonly' => array(),
				'data-start' => array(),
				'data-min' => array(),
				'data-max' => array(),
				'data-step' => array(),
				'desc' => array(),
				'data-conditional' => array(),
				'data-conditional-id' => array(),
				'data-conditional-value' => array(),
			)
		));
	
		// Escaping for HTML content
		echo wp_kses_post('<span class="own-slider-field-value-display"  style="width:' . esc_html($slider_width) .  ';"><span class="own-slider-field-default-value-display">'. esc_html($field->value_label()) .'</span><span class="own-slider-field-value-text"></span><span class="own-slider-field-value-suffix-text">'. esc_html($field->value_suffix_label()) .'</span> </span>');
		// Properly escape description if needed
		if ($field_type_object->_desc(true, true)) {
		}
	}
	
	

	public function setup_admin_scripts( ) {

		wp_enqueue_script( 'cmb2_field_slider_js',  plugins_url( 'js/cmb2_field_slider.js', __FILE__ ), array( 'jquery', 'jquery-ui-slider' ), self::VERSION, true);

		wp_register_style( 'slider_ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css', array(), '1.0' );
		wp_enqueue_style( 'cmb2_field_slider_css', plugins_url( 'css/cmb2_field_slider.css', __FILE__ ), array( 'slider_ui' ), self::VERSION );

	}
}
$own_field_slider = new OWN_Field_Slider();
$own_field_slider->hooks();
