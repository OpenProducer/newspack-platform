/**
 * Extra zazz for the WP Admin Plugins page.
 * @see Admin_Plugins_Screen::enqueue_scripts_and_styles().
 */
( function( $ ){
	// Add a 'newspack_plugin' class to managed plugins.
	newspack_plugin_info['plugins'].forEach( function( plugin_slug ) {
		var $row = $( 'tr[data-slug="' + plugin_slug + '"]' );
		if ( $row.length ) {
			$row.addClass( 'newspack-plugin' );
		}

		if ( ! newspack_plugin_info['installed_plugins'].includes( plugin_slug ) ) {
			$row.addClass( 'uninstalled' );
		}
	} );
} )( jQuery );