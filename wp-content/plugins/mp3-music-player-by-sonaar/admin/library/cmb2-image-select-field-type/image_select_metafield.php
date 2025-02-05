<?php

function cmb2_render_image_select( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {		
    $conditional_value =(isset($field->args['attributes']['data-conditional-value'])?'data-conditional-value="' .esc_attr($field->args['attributes']['data-conditional-value']).'"':'');
    $conditional_id =(isset($field->args['attributes']['data-conditional-id'])?' data-conditional-id="'.esc_attr($field->args['attributes']['data-conditional-id']).'"':'');
	$default_value = esc_html($field->args['default']);    
	

	$image_select_escaped = '<ul id="cmb2-image-select'.esc_attr($field->args['_id']).'" class="cmb2-image-select-list">';
	
	foreach ( $field->options() as $value => $item ) {
		$selected = ($value === ($escaped_value==''?$default_value:$escaped_value )) ? 'checked="checked"' : '';	
		$image_select_escaped .= '<li class="cmb2-image-select ' . ( $selected != '' ? 'cmb2-image-select-selected' : '' ) . '"><label for="' . esc_attr($field->args['_id']) . esc_attr( $value ) . '">
			<input '.esc_html($conditional_value).esc_html($conditional_id).' type="radio" id="'. esc_attr($field->args['_id']) . esc_attr( $value ) . '" name="' . esc_html($field->args['_name']) . '" value="' . esc_attr( $value ) . '" ' . $selected . ' class="cmb2-option"><img class="" style=" width:' . esc_attr($field->args['width']) . '; " alt="' . esc_html($item['alt']) . '" src="' . esc_url($item['img']) . '">
			<br><span>' . esc_html( $item['title'] ) . '</span></label></li>';
	}
	$image_select_escaped .= esc_html($field_type_object->_desc( true ));	
	$image_select_escaped .= '</ul>';
	
	echo $image_select_escaped;
}

add_action( 'cmb2_render_image_select', 'cmb2_render_image_select', 10, 5 );
