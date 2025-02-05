<?php
/**
 * Main Elementor Sonaar Class
 *
 * The init class that runs the Elementor for Sonaar plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.2.0
 */
final class Elementor_Sonaar_Plugin {

	public function __construct() {

		// Load translation
		//add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}
	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	
	public function init() {
		// Once we get here, We have passed all validation checks so we can safely include our plugin
		// Register Script for elementor
		add_action( 'elementor/frontend/before_enqueue_scripts', function() { 
			wp_enqueue_script( 'sr-scripts' ); 
		} );
		add_action( 'elementor/editor/before_enqueue_scripts',[ $this, 'srmp3_elementor_register_scripts_editor' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'srmp3_elementor_register_scripts_editor' ] );
		require_once( 'plugin.php' );
	}
	public function srmp3_elementor_register_scripts_editor() {
		wp_register_script( 'sr-scripts', plugin_dir_url( __DIR__ ) . 'public/js/sr-scripts.js', [ 'jquery' ], '2.0', true );
	}

}
// if elementor exist
if ( did_action( 'elementor/loaded' ) ) {
	new Elementor_Sonaar_Plugin();
}