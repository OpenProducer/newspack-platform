<?php

/**
 * Handles 'store list' custom field type.
 */
class CMB2_Render_Typography_Field extends CMB2_Type_Base {


  
  /**
   * List of stores. To translate, pass array of states in the 'state_list' field param.
   *
   * @var array
   */
  const VERSION = '0.0.1';

  protected static $text_align = array (
    '' => 'Default',
    'left' => 'left',
    'center' => 'center',
    'right' => 'right'
  );
  protected static $fields = array(
    'font-family' 		=> true,
    'font-size' 			=> true,
    'font-weight' 		=> true,
    'color' 					=> true,
    'background' 			=> true,
    'text-align' 			=> true,
    'text-transform' 	=> true,
    'line-height' 		=> true,
    'selector' 				=> true,
  );

  protected static $transform = array (
    '' => 'Default',
    'none' => 'None',
    'capitalize' => 'Capitalize',
    'uppercase' => 'Uppercase',
    'lowercase' => 'Lowercase'
  );

  protected static $font_weight = array (
    '' => 'Default',
    'none' => 'None',
    'bold' => 'Bold',
    'bolder' => 'Bolder',
    'lighter' => 'Lighter',
    '100' => '100',
    '200' => '200',
    '300' => '300',
    '400' => '400',
    '500' => '500',
    '600' => '600',
    '700' => '700',
    '800' => '800',
    '900' => '900',
    'inherit' => 'Inherit'
  );

  public static function init() {
    add_filter( 'cmb2_render_class_typography', array( __CLASS__, 'class_name' ) );
    add_filter( 'cmb2_sanitize_typography', array( __CLASS__, 'maybe_save_split_values' ), 12, 4 );
    /**
     * The following snippets are required for allowing the typography field
     * to work as a repeatable field, or in a repeatable group
     */
    add_filter( 'cmb2_sanitize_typography', array( __CLASS__, 'sanitize' ), 10, 5 );
    add_filter( 'cmb2_types_esc_typography', array( __CLASS__, 'escape' ), 10, 4 );
  }

  public static function class_name() { return __CLASS__; }

  private function if_fields( $fields, $field = null ){
    if( gettype( $field ) !== 'string' )
      return false;

    if (!isset( $fields[$field] ) || ( isset( $fields[$field] ) && $fields[$field] == true )){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Handles outputting the address field.
   */
  public function render() {
    self::setup_scripts();
    //check if function not exist
    if ( ! function_exists( 'setDefaultColorPalettes' ) ) {
      function setDefaultColorPalettes() {
        $palettes = array(
            '#000000', // Black
            '#FFFFFF', // White
            '#DA5A47', '#2A2B2D', '#F4F7D9', '#6E44FF', '#1CA1A6', '#FFC17D'
        );
        $colorPickerOptions = array(
            'palettes' => $palettes
        );
        return json_encode($colorPickerOptions);
      }
    }
    // make sure we assign each part of the value we need.
    $value = wp_parse_args( $this->field->escaped_value(), array(
      'font-family' => '',
      'font-size' => '',
      'font-weight' => '',
      'color' => '',
      'background' => '',
      'text-align' => '',
      'text-transform' => '',
      'line-height' => '',
      'selector' => '',
      'collector' => '',

    ));


    $fields = $this->field->args( 'fields', array() );
    if ( empty( $fields ) ) {
      $fields = self::$fields;
    }

    $selector = isset($this->field->args['fields']['selector']) ? $this->field->args['fields']['selector'] : '';

    $text_align = $this->field->args( 'text_align', array() );
    if ( empty( $text_align ) ) {
      $text_align = self::$text_align;
    }

    $text_align_options = '';
    foreach ( $text_align as $key => $selected ) {
      $text_align_options .= '<option value="'. $key .'" '. selected( $value['text-align'], $key, false ) .'>'. $selected .'</option>';
    }


    $text_transform = $this->field->args( 'text_transform', array() );
    if ( empty( $text_transform ) ) {
      $text_transform = self::$transform;
    }

    $transform_options = '';
    foreach ( $text_transform as $key => $selected ) {
      $transform_options .= '<option value="'. $key .'" '. selected( $value['text-transform'], $key, false ) .'>'. $selected .'</option>';
    }

    $font_weight = $this->field->args( 'font_weight', array() );
    if ( empty( $font_weight ) ) {
      $font_weight = self::$font_weight;
    }

    $font_weight_options = '';
    foreach ( $font_weight as $key => $selected ) {
      $font_weight_options .= '<option value="'. $key .'" '. selected( $value['font-weight'], $key, false ) .'>'. $selected .'</option>';
    }

    ob_start();
    // Do html
    ?>
    <table class="typography-table">
      <tbody>
      <?php if ( $this->if_fields($fields, 'font-family') )  : ?>
      <tr>
        <td colspan="2">
        <?php echo $this->types->input( array(
          'name'  => $this->_name( '[font-family]' ),
          'id'    => $this->_id( '_font_family' ),
          'value' => $value['font-family'],
          'desc'  => '',
          'class' => 'cmb2-typography-fs'
        ) ); ?>
        </td>
      </tr>
      <?php endif ?>
    <tr>
    <?php if ( $this->if_fields($fields, 'text-align') )  : ?>
      <td>
        <label>Text Align</label>
        <?php echo $this->types->select( array(
        'name'  => $this->_name( '[text-align]' ),
        'id'    => $this->_id( '_text_align' ),
        'show_option_none' => false,
        'options' => $text_align_options,
        'desc'  => '',
      ) ); ?>
        
      </td>
    <?php endif ?>
    <?php if ( $this->if_fields($fields, 'font-size') )  : ?>
      <td>
        <label>Font Size</label>
        <?php echo $this->types->input( array(
          'name'  => $this->_name( '[font-size]' ),
          'id'    => $this->_id( '_font_size' ),
          'value' => $value['font-size'],
          'class' => 'cmb2-text-small',
          'desc'  => '',
        ) ); ?>
        </td>
    <?php endif ?>
    </tr>
    <tr>
    <?php if ( $this->if_fields($fields, 'text-transform') )  : ?>
      <td>
      <label>Transform</label>
      <?php echo $this->types->select( array(
        'name'  => $this->_name( '[text-transform]' ),
        'id'    => $this->_id( '_transform' ),
        'show_option_none' => false,
        'options' => $transform_options,
        'desc'  => '',
      ) ); ?>
      </td>
    <?php endif ?>
    <?php if ( $this->if_fields($fields, 'line-height') )  : ?>
      <td>
      <label>Line Height</label>
      <?php echo $this->types->input( array(
          'name'  => $this->_name( '[line-height]' ),
          'id'    => $this->_id( '_line_height' ),
          'value' => $value['line-height'],
          'class' => 'cmb2-text-small',
          'desc'  => '',
        ) ); ?>
      </td>
    <?php endif ?>
    </tr>
    <tr>
    <?php if ( $this->if_fields($fields, 'font-weight') )  : ?>
      <td>
      <label>Font Weight</label>
      <?php echo $this->types->select( array(
        'name'  => $this->_name( '[font-weight]' ),
        'id'    => $this->_id( '_font_weight' ),
        'show_option_none' => false,
        'options' => $font_weight_options,
        'desc'  => '',
      ) ); ?>
      </td>
      <td></td>
    <?php endif ?>
    </tr>
    <tr>
    <?php if ( $this->if_fields($fields, 'color') )  : ?>
      <td>
      <label>Font Color</label>
      <?php echo $this->types->colorpicker(array(
        'name' => $this->_name('[color]'),
        'id' => $this->_id('_color'),
        'value' => $value['color'],
        'desc' => '',
        'default' => '#444444',
        'data-colorpicker' => setDefaultColorPalettes(),
      ), $value['color'] ) ?>
      </td>
    <?php endif ?>
    <?php if ( $this->if_fields($fields, 'background') )  : ?>
      <td>
      <label>Background</label>
      <?php echo $this->types->colorpicker(array(
        'name' => $this->_name('[background]'),
        'id' => $this->_id('_background'),
        'value' => $value['background'],
        'desc' => '',
        'default' => '',
        'data-colorpicker' => setDefaultColorPalettes(),
      ), $value['background']) ?>
      </td>
    <?php endif ?>
    </tr>
    </tbody>
    </table>
    <?php echo $this->types->input( array(
          'name'  => $this->_name( '[selector]' ),
          'id'    => $this->_id( '_selector' ),
          'value' => $selector,
          'class' => 'cmb2-text-small',
          'type' => 'hidden',
          'desc'  => '',
        ) ); ?>
    <?php echo $this->types->input( array(
          'name'  => $this->_name( '[collector]' ),
          'id'    => $this->_id( '_collector' ),
          'value' => $value['collector'],
          'desc'  => '',
          'type'=> 'hidden',
          'value' => ''
        ) ); ?>


    <?php

    // grab the data from the output buffer.
    return $this->rendered( ob_get_clean() );
  }

  public static function maybe_save_split_values( $override_value, $value, $object_id, $field_args ) {
    if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] ) {
      // Don't do the override
      return $override_value;
    }

    $store_keys = array( 'store-icon', 'store-name', 'store-link', 'store-target');

    foreach ( $store_keys as $key ) {
      if ( ! empty( $value[ $key ] ) ) {
        update_post_meta( $object_id, $field_args['id'] . 'store_'. $key, sanitize_text_field( $value[ $key ] ) );
      }
    }
    
    remove_filter( 'cmb2_sanitize_typography', array( __CLASS__, 'sanitize' ), 10, 5 );

    // Tell CMB2 we already did the update

    return true;

  }

  public static function sanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {

    // if not repeatable, bail out.
    if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
      return $check;
    }

    foreach ( $meta_value as $key => $val ) {
      $meta_value[ $key ] = array_filter( array_map( 'sanitize_text_field', $val ) );
    }
    
    return array_filter($meta_value);
  }

  public static function escape( $check, $meta_value, $field_args, $field_object ) {
    // if not repeatable, bail out.
    if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
      return $check;
    }

    foreach ( $meta_value as $key => $val ) {
      $meta_value[ $key ] = array_filter( array_map( 'esc_attr', $val ) );
    }
    
    return array_filter($meta_value);
  }

  protected static function setup_scripts() {
    wp_enqueue_script( 'cmb2-typography-field', plugins_url( '/lib/cmb2-typography.js', __FILE__ ),array('jquery'), self::VERSION, true );
    wp_enqueue_style( 'cmb2-typography-field',  plugins_url( '/lib/css/cmb2-typography.css', __FILE__ ), array(), self::VERSION );
  }

}