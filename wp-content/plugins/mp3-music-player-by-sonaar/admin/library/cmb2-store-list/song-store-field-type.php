<?php


function cmb2_init_store_list_field() {
	global $render_store_icon;
	
	require_once dirname( __FILE__ ) . '/class-cmb2-render-store-list-field.php';
	require_once SRMP3_DIR_PATH . 'admin/library/cmb2-field-faiconselect/predefined-array-fontawesome.php';	
	$store_icon = array( '' => esc_html__( 'Select Icon' )  ); 
	if( Sonaar_Music::get_option('cta_load_all_icons', 'srmp3_settings_widget_player') === 'true' ){
		$render_store_icon = array_merge($store_icon, $sr_cta_default_icons, $fa5all);
	}else{
		$render_store_icon = array_merge($store_icon, $sr_cta_default_icons);
	}
	
	
	CMB2_Render_Store_list_Field::init();
}
add_action( 'cmb2_init', 'cmb2_init_store_list_field' );
