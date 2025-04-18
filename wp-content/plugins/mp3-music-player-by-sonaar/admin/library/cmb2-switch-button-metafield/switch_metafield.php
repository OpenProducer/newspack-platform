<?php

function abs_cmb2_render_switch($field, $escaped_value, $object_id, $object_type, $field_type_object) {
    $escaped_value = ($escaped_value == 'true') ? true : false;

    $switch = '<div class="cmb2-switch">';

    // Prepare conditional attributes
    $conditional_attrs = '';
    if (isset($field->args['attributes']['data-conditional'])) {
        $conditional_attrs .= ' data-conditional="' . esc_attr($field->args['attributes']['data-conditional']) . '"';
    } else {
        // Support for older conditional attributes
        if (isset($field->args['attributes']['data-conditional-id'])) {
            $conditional_attrs .= ' data-conditional-id="' . esc_attr($field->args['attributes']['data-conditional-id']) . '"';
        }
        if (isset($field->args['attributes']['data-conditional-value'])) {
            $conditional_attrs .= ' data-conditional-value="' . esc_attr($field->args['attributes']['data-conditional-value']) . '"';
        }
    }

    // Check for data-target-selector attribute
    if (isset($field->args['attributes']['data-target-selector'])) {
        $target_selector = ' data-target-selector="' . esc_attr($field->args['attributes']['data-target-selector']) . '"';
    } else {
        $target_selector = '';
    }

    $label_on = isset($field->args['label']) ? esc_attr($field->args['label']['on']) : 'On';
    $label_off = isset($field->args['label']) ? esc_attr($field->args['label']['off']) : 'Off';

    $switch .= '<input ' . $conditional_attrs . $target_selector . ' type="radio" id="' . esc_attr($field->args['_id']) . '1" value="true" ' . ($escaped_value ? 'checked="checked"' : '') . ' name="' . esc_attr($field->args['_name']) . '" />';
    $switch .= '<input ' . $conditional_attrs . ' type="radio" id="' . esc_attr($field->args['_id']) . '2" value="false" ' . (!$escaped_value ? 'checked="checked"' : '') . ' name="' . esc_attr($field->args['_name']) . '" />';
    $switch .= '<label for="' . esc_attr($field->args['_id']) . '1" class="cmb2-enable ' . ($escaped_value ? 'selected' : '') . '"><span>' . esc_html($label_on) . '</span></label>';
    $switch .= '<label for="' . esc_attr($field->args['_id']) . '2" class="cmb2-disable ' . (!$escaped_value ? 'selected' : '') . '"><span>' . esc_html($label_off) . '</span></label>';

    $switch .= '</div>';
    $switch .= $field_type_object->_desc(true);  // Assuming this method correctly escapes HTML if necessary
    echo $switch;
}
add_action('cmb2_render_switch', 'abs_cmb2_render_switch', 10, 5);

