<?php
/**
 * Updates the Edit Site link in the admin bar to point to the top level of the Site Editor.
 * Removes the use of the $_wp_current_template_id global and the query args.
 * Displays the link while in the admin area.
 * Changes the capitalization of the link text to match WP 6.8: https://core.trac.wordpress.org/ticket/62971
 *
 * Note: Backports into wp-includes\admin-bar.php wp_admin_bar_edit_site_menu()
 *
 * @param WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance.
 */
function gutenberg_wp_admin_bar_edit_site_menu( $wp_admin_bar ) {
	// Don't show if a block theme is not activated.
	if ( ! wp_is_block_theme() ) {
		return;
	}

	// Don't show for users who can't edit theme options.
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$wp_admin_bar->add_node(
		array(
			'id'    => 'site-editor',
			'title' => __( 'Edit Site' ),
			'href'  => admin_url( 'site-editor.php' ),
		)
	);
}

add_action( 'admin_bar_menu', 'gutenberg_wp_admin_bar_edit_site_menu', 41 );
