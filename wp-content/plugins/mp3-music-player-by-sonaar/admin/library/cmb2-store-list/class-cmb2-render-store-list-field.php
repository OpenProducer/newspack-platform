<?php

/**
 * Handles 'store list' custom field type.
 */
class CMB2_Render_Store_list_Field extends CMB2_Type_Base {


	
	/**
	 * List of stores. To translate, pass array of states in the 'state_list' field param.
	 *
	 * @var array
	 */
	protected static $store_target = array (
		'' => 'Yes',
		'_self' => 'No',									
	);
	protected static $link_option = array (
		'' => 'Link Url',
		'popup' => 'Pop-up',									
	);
	protected static $show_label = array (
		'' => 'Default (See General Settings)',
		'no' => 'No',
		'true' => 'Yes',
	);
	public static function init() {
		add_filter( 'cmb2_render_class_store_list', array( __CLASS__, 'class_name' ) );
		add_filter( 'cmb2_sanitize_store_list', array( __CLASS__, 'maybe_save_split_values' ), 12, 4 );
		/**
		 * The following snippets are required for allowing the store_list field
		 * to work as a repeatable field, or in a repeatable group
		 */
		add_filter( 'cmb2_sanitize_store_list', array( __CLASS__, 'sanitize' ), 10, 5 );
		add_filter( 'cmb2_types_esc_store_list', array( __CLASS__, 'escape' ), 10, 4 );
	}

	public static function class_name() { return __CLASS__; }

	/**
	 * Handles outputting the address field.
	 */
	public function render() {
		global $render_store_icon;
		self::setup_scripts();
		
		// make sure we assign each part of the value we need.
		$value = wp_parse_args( $this->field->escaped_value(), array(
			'store-icon' => '',
			'store-name' => '',
			'store-link' => '',
			'store-target' => '',
			'show-label' => '',
			'store-content' => '',
			'link-option' => ''
		) );

		if ( $this->field->args( 'icon' ) ) {
			$store_icon = $this->field->args( 'store_icon', array() ); 
			 
			$store_icon = array( '' => esc_html( $this->_text( 'store_select_store_icon_text', 'Select Icon' ) ) ); 
			$store_icon = $render_store_icon;

			$store_icon_options = '';
			foreach ( $store_icon as $icon => $store ) {
				$store_icon_options .= '<option class="' . $icon . '" value="'. $icon .'" '. selected( $value['store-icon'], $icon, false ) .'>'. $store .'</option>';
			}
		}

		$store_target = $this->field->args( 'store_target', array() );
		if ( empty( $store_target ) ) {
			$store_target = self::$store_target;
		}
		$store_target_options = '';
		foreach ( $store_target as $target => $targetstore ) {
			$store_target_options .= '<option class="' . $target . '" value="'. $target .'" '. selected( $value['store-target'], $target, false ) .'>'. $targetstore .'</option>';
		}

		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$show_label = $this->field->args( 'show_label', array() );
			if ( empty( $show_label ) ) {
				$show_label = self::$show_label;
			}
			$show_label_options = '';
			foreach ( $show_label as $option => $ShowLabelOption ) {
				$show_label_options .= '<option class="' . $option . '" value="'. $option .'" '. selected( $value['show-label'], $option, false ) .'>'. $ShowLabelOption .'</option>';
			}

			$link_option = $this->field->args( 'link_option', array() );
			if ( empty( $link_option ) ) {
				$link_option = self::$link_option;
			}
			$link_option_options = '';
			foreach ( $link_option as $option => $linkOption ) {
				$link_option_options .= '<option class="' . $option . '" value="'. $option .'" '. selected( $value['link-option'], $option, false ) .'>'. $linkOption .'</option>';
			}
		}

		ob_start();
		// Do html
		?>
		<?php if( $this->field->args( 'icon' ) ) :?>
		<div class="store-icon"><p><label for="<?php echo esc_attr($this->_id( '_store_icon' )); ?>"><?php echo esc_html( $this->_text( 'store_icon_text', 'Select Icon' ) ); ?></label></p>
			<?php echo $this->types->select( array(
				'name'  => $this->_name( '[store-icon]' ),
				'id'    => $this->_id( '_store_icon' ),
				'show_option_none' => true,
				'options' => $store_icon_options,
				'desc'  => '',
				'class' => 'fab fas iconselectfa'
			) ); ?>
		</div>
		<?php endif ?>

		<div class="store-name"><p><label for="<?php echo esc_attr($this->_id( '_store_name' )); ?>"><?php echo esc_html( $this->_text( 'store_name_text', 'Label' ) ); ?></label></p>
			<?php echo $this->types->input( array(
				'name'  => $this->_name( '[store-name]' ),
				'id'    => $this->_id( '_store_name' ),
				'value' => $value['store-name'],
				'class' => 'cmb2-text-medium',
				'desc'  => '',
			) ); ?>
		<p class="cmb2-metabox-description"><?php echo esc_html( $this->_text( 'store_name_desc') ); ?></p>	
		</div>

		<?php if ( function_exists( 'run_sonaar_music_pro' ) ): ?>
		<div class="show-label"><p><label for="<?php echo esc_attr($this->_id( '_show_label')); ?>"><?php echo esc_html( $this->_text( 'show_label', 'Display Label' ) ); ?></label></p>
			<?php echo $this->types->select( array(
				'name'  => $this->_name( '[show-label]' ),
				'id'    => $this->_id( '_show_label' ),
				'show_option_none' => true,
				'options' => $show_label_options,
				'desc'  => '',
				'class' => 'sr-select'
			) ); ?>
			<p class="cmb2-metabox-description"><?php echo esc_html( $this->_text( 'store_showlabel_desc') ); ?></p>	
		</div>
		<div onchange="cmb2LinkOption(this)" class="link-option"><p><label for="<?php echo esc_attr($this->_id( '_link_option')); ?>"><?php echo esc_html( $this->_text( 'link_option', 'Action' ) ); ?></label></p>
			<?php echo $this->types->select( array(
				'name'  => $this->_name( '[link-option]' ),
				'id'    => $this->_id( '_link_option' ),
				'show_option_none' => true,
				'options' => $link_option_options,
				'desc'  => '',
				'class' => 'sr-select'
			) ); ?>
		</div>
		<?php endif; ?>

		<div class="store-link" style="float:left;"><p><label for="<?php echo esc_attr($this->_id( '_store_link')); ?>"><?php echo esc_html( $this->_text( 'store_link_text', 'Link URL' ) ); ?></label></p>
			<?php echo $this->types->input( array(
				'name'  => $this->_name( '[store-link]' ),
				'id'    => $this->_id( '_store_link' ),
				'value' => $value['store-link'],
				'type' => 'text',
				'class' => 'cmb2-text-url cmb2-text-medium regular-text',
				'desc'  => '',
				) ); ?>
				<p class="cmb2-metabox-description"><?php echo esc_html( $this->_text( 'store_link_desc') ); ?></p>	
		</div>
		
		<div class="store-target" style="float:left;"><p><label for="<?php echo esc_attr($this->_id( '_store_target')); ?>"><?php echo esc_html( $this->_text( 'store_target', 'Open in New Window?' ) ); ?></label></p>
			<?php echo $this->types->select( array(
				'name'  => $this->_name( '[store-target]' ),
				'id'    => $this->_id( '_store_target' ),
				'show_option_none' => true,
				'options' => $store_target_options,
				'desc'  => '',
				'class' => 'sr-select'
			) ); ?>
		</div>

		<?php if ( function_exists( 'run_sonaar_music_pro' ) ): ?>
		<div class="store-content" style="float:left;"><p><label for="<?php echo esc_attr($this->_id( '_store_content')); ?>"><?php echo esc_html( 'Pop-up Content' ); ?></label></p>
			<?php echo $this->types->textarea( array(
				'name'  => $this->_name( '[store-content]' ),
				'id'    => $this->_id( '_store_content' ),
				'value' => $value['store-content'],
				'type'    => 'textarea',
				'desc'  => '',
			) ); ?>
			<p class="cmb2-metabox-description"><?php echo esc_html( $this->_text( 'store_content_desc') ); ?></p>	
		</div>
		<?php endif; ?>
		
		<p class="clear">
			<?php echo esc_html($this->_desc());?>
		</p>
		<?php

		// grab the data from the output buffer.
		return $this->rendered( ob_get_clean() );
	}

	public static function maybe_save_split_values( $override_value, $value, $object_id, $field_args ) {
		if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] ) {
			// Don't do the override
			return $override_value;
		}

		$store_keys = array( 'store-icon', 'store-name', 'store-link', 'store-target', 'show-label', 'link-option');

		foreach ( $store_keys as $key ) {
			if ( ! empty( $value[ $key ] ) ) {
				update_post_meta( $object_id, $field_args['id'] . 'store_'. $key, sanitize_text_field( $value[ $key ] ) );
			}
		}

		remove_filter( 'cmb2_sanitize_store_list', array( __CLASS__, 'sanitize' ), 10, 5 );

		// Tell CMB2 we already did the update

		return true;

	}

	public static function sanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {

		// if not repeatable, bail out.
		if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
			return $check;
		}
 		$allowed_targets = array( '', '_self', '_blank' );
		foreach ( $meta_value as $key => $val ) {
			//Sanitaze all field value except "store-link"
			$val['store-icon'] = sanitize_text_field($val['store-icon']);
			$val['store-name'] = sanitize_text_field($val['store-name']);
			if ( in_array( $val['store-target'], $allowed_targets ) ) {
				$val['store-target'] = sanitize_text_field($val['store-target']);
			} else {
				$val['store-target'] = ''; // Set to default or empty if not valid
			}
			$meta_value[ $key ] = array_filter( $val );
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
    wp_enqueue_style( 'cmb2-store-list',  plugins_url( '/css/cmb2-store-list.css', __FILE__ ), array(), NULL );
  }

}