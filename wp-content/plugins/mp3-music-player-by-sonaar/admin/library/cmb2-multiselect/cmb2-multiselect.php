<?php


/**
 * CMB2 Select Multiple Custom Field Type
 * @package CMB2 Select Multiple Field Type
 */
/**
 * Adds a custom field type for select multiples.
 * @param  object $field             The CMB2_Field type object.
 * @param  string $value             The saved (and escaped) value.
 * @param  int    $object_id         The current post ID.
 * @param  string $object_type       The current object type.
 * @param  object $field_type_object The CMB2_Types object.
 * @return void
 */
function sr_cmb2_render_select_multiple_field_type( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	$select_multiple = '<select class="widefat" multiple name="' . $field->args['_name'] . '[]" id="' . $field->args['_id'] . '"';
	foreach ( $field->args['attributes'] as $attribute => $value ) {
		$select_multiple .= " $attribute=\"$value\"";
	}
	$select_multiple .= ' />';
	foreach ( $field->options() as $value => $name ) {
		$selected = ( $escaped_value && in_array( $value, $escaped_value ) ) ? 'selected="selected"' : '';
		$select_multiple .= '<option class="cmb2-option" value="' . esc_attr( $value ) . '" ' . $selected . '>' . esc_html( $name ) . '</option>';
	}
	$select_multiple .= '</select>';
	$select_multiple .= $field_type_object->_desc( true );
	echo $select_multiple;
}
add_action( 'cmb2_render_select_multiple', 'sr_cmb2_render_select_multiple_field_type', 10, 5 );
/**
 * Sanitize the selected value.
 */
function cmb2_sanitize_select_multiple_callback( $override_value, $value ) {
	if ( is_array( $value ) ) {
		
		$arraySize = count($value) - 1;
		$returnedValue = '';
		foreach ( $value as $key => $saved_value ) {
			$returnedValue .= ( $key == $arraySize )? $saved_value : $saved_value . ',';	
		}

		return $returnedValue;
		
	}
	return;
}
add_filter( 'cmb2_sanitize_select_multiple', 'cmb2_sanitize_select_multiple_callback', 10, 2 );