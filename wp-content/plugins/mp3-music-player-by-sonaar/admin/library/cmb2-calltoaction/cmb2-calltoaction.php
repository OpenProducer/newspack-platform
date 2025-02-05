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
function cmb2_render_calltoaction_field_type( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	$calltoaction = '<a href="' . $field->args['href'] . '" target="_blank">';
	
	if (isset($field->args['img']))
		$calltoaction .= '<img style="width:100%; height:auto;" src="' . $field->args['img'] . '">';
	
	if (isset($field->args['txt']))
		$calltoaction .= $field->args['txt'];

	$calltoaction .= '</a>';
	echo $calltoaction;
}
add_action( 'cmb2_render_calltoaction', 'cmb2_render_calltoaction_field_type', 10, 5 );
