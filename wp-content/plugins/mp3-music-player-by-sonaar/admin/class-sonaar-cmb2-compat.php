<?php
/**
 * Sonaar extensions to the unmodified, bundled CMB2 library.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Sonaar_CMB2_Compat {
    public static function group_attributes( $attributes, $field ) {
        $field_attributes = (array) $field->args( 'attributes' );
        foreach ( array( 'data-conditional-id', 'data-conditional-value' ) as $key ) {
            if ( isset( $field_attributes[ $key ] ) ) {
                $attributes[ $key ] = esc_attr( $field_attributes[ $key ] );
            }
        }
        return $attributes;
    }

    public static function enqueue() {
        wp_enqueue_script(
            'sonaar-cmb2-compat',
            plugin_dir_url( __FILE__ ) . 'js/sonaar-cmb2-compat.js',
            array( 'cmb2-scripts', 'jquery-ui-sortable' ),
            SRMP3_VERSION . '-cmb2-2.13.5.1',
            true
        );
    }
}

add_filter( 'cmb2_group_wrap_attributes', array( 'Sonaar_CMB2_Compat', 'group_attributes' ), 10, 2 );
add_action( 'cmb2_footer_enqueue', array( 'Sonaar_CMB2_Compat', 'enqueue' ) );
