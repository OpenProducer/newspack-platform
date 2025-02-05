<?php
/*
  Plugin Name: Options Importer and Exporter
  Plugin URI: https://github.com/rrikesh/WordPress-Export-Options
  Description: Plugin to import and export hooked data saved in the options table.
  Author: Rikesh Ramlochund
  Version: 1.0
  Author URI: http://rrikesh.com

  Last updated: 11 June 2013
  Most recent version tested: 3.6 beta
 */


/**
 * Do not call outside of WordPress
 */
function_exists( 'add_filter' ) || exit;
/**
 * @todo: Add github update engine
 */

ob_start();

define( 'SRMP3_EXPORT_FILTER_NAME', 'srmp3_export_options' );
define( 'SRMP3_EXPORT_IMPORT_CAPABILITY', 'export' );
define( 'SRMP3_EXPORT_EXPORT_CAPABILITY', 'export' );
/**
 * Register the import and export pages
 */
function srmp3_export_register_pages() {
  add_submenu_page('edit.php?post_type=' . SR_PLAYLIST_CPT, __( 'Import/Export MP3 Player Settings', 'sonaar-music' ), 'Import Settings', 'manage_options', 'srmp3-import-page','srmp3_import_page_callback');
}
/**
 * Callback function for the import options page
 */
function srmp3_import_page_callback() {
  $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
  ?>
  <div class="wrap">
    <div id="icon-tools" class="icon32"><br /></div>
    <h2><?php _e( 'Global Settings Import/Export', 'sonaar-music' ); ?></h2>
  </div>
  <div class="wrap">
    <h2><?php _e( 'Import Settings', 'sonaar-music' ); ?></h2>
    

  <?php
  if ( isset( $_FILES[ 'import' ] ) && check_admin_referer( 'srmp3_options_importer' ) ) {
    $filename = $_FILES[ 'import' ][ 'name' ];
    $file_extension = pathinfo( $filename, PATHINFO_EXTENSION );
    $file_size = $_FILES[ 'import' ][ 'size' ];
    if ( ($file_extension === 'json') && ($file_size < $bytes) && $_FILES[ 'import' ][ 'error' ] === 0 ) {
      $file_contents = file_get_contents( $_FILES[ 'import' ][ 'tmp_name' ] );
      $options = json_decode( $file_contents, true );
      foreach ( $options as $option_name => $option_value ) {
        update_option( $option_name, $option_value );
      }
      echo "<div class='updated'><p>" . __( 'The import was successful.', 'sonaar-music' ) . "</p></div>";
    }
    else {
      echo "<div class='error'><p>" . __( 'An error happened.', 'sonaar-music' ) . "</p></div>";
      if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        echo '<pre>';
        print_r( $_FILES );
        echo '</pre>';
      }
    }
  }
  ?>
  </div>
    <?php
    #invoke wp_import_upload_form()
    $size = size_format( $bytes );
    $upload_dir = wp_upload_dir();
    if ( !empty( $upload_dir[ 'error' ] ) ) :
      ?><div class="error"><p><?php _e( 'Before you can upload your import file, you will need to fix the following error:', 'sonaar-music' ); ?></p>
      <p><strong><?php echo $upload_dir[ 'error' ]; ?></strong></p></div><?php
  else :
    ?>
    <form enctype="multipart/form-data" id="import-upload-form" method="post" class="wp-upload-form" action="<?php echo esc_url( wp_nonce_url( 'edit.php?post_type=' . SR_PLAYLIST_CPT . '&page=srmp3-import-page', 'srmp3_options_importer' ) ); ?>">
      <p>
        <label for="upload"><?php _e( 'Choose a compatible JSON file from your computer:', 'sonaar-music' ); ?></label> (<?php printf( __( 'Maximum size: %s', 'sonaar-music' ), $size ); ?>)
        <input type="file" id="upload" name="import" size="25" />
        <input type="hidden" name="action" value="save" />
        <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
    <?php wp_nonce_field( 'srmp3_options_importer' ); ?>
      </p>
        <?php submit_button( __( 'Upload file and import', 'sonaar-music' ), 'button' ); ?>
    </form>
    <?php
    endif;
    srmp3_export_page_callback();
  }

  /**
   * Callback function for the export options page
   */
  function srmp3_export_page_callback() {

    if ( !empty( $_POST ) && check_admin_referer( 'srmp3_options_importer' ) ) {

      $options = array ( );
      if ( isset( $_POST[ 'rroptionsexport' ] ) && is_array( $_POST[ 'rroptionsexport' ] ) ) {
       
        foreach ( $_POST[ 'rroptionsexport' ] as $option ) {
          $options[ $option ] = get_option( $option );
        }
        foreach ( $options as $key => $value ) {
          $value = maybe_unserialize( $value );
          $need_options[ $key ] = $value;
          
    
        }
        $json_file = json_encode( $need_options );
        ob_clean();
        echo $json_file;
         /**
         * Generate the JSON file and trigger download
         * Taken from wp-admin/includes/export.php
         */
        $sitename = sanitize_key( get_bloginfo( 'name' ) );
        $filename = $sitename . '_settings_' . date( 'Y-m-d' ) . '.json';
        nocache_headers();
        header( "Content-Type: text/json; charset=" . get_option( 'blog_charset') );
        header( "Content-Disposition: attachment; filename=$filename" );
        header( "Expires: 0" );
        exit();
      } else {
        # No options selected and submitted the form
        echo 'Finished';
      }
    } else {
      ?>
    <div class="wrap">
      <div id="icon-tools" class="icon32"><br /></div>
      <h2>Export Settings</h2>
      <h2><?php _e( 'Choose which options tab you want to export:', 'sonaar-music' ); ?></h2>

      <form method="POST">
    <?php
    $val = array ( );
    $options_array = apply_filters( SRMP3_EXPORT_FILTER_NAME, $val );

    /**
     * @todo: check if array first for both cases
     */
    if ( is_array( $options_array ) && count( $options_array ) > 0 ) {
      ?>
          <!-- code for plugin lists from update-core.php -->
          <table class="widefat updates-table" id="update-plugins-table">
	<thead>
	<tr>
		<td class="manage-column check-column"><input type="checkbox" id="plugins-select-all" /></td>
		<td class="manage-column"><label for="plugins-select-all"><?php _e( 'Select All' ); ?></label></td>
	</tr>
	</thead>

	<tbody class="plugins">
  <tfoot>
	<tr>
		<td class="manage-column check-column"><input type="checkbox" id="plugins-select-all-2" /></td>
		<td class="manage-column"><label for="plugins-select-all-2"><?php _e( 'Select All' ); ?></label></td>
	</tr>
	</tfoot>
      <?php
      foreach ( $options_array as $option_per_filter ) {
        if ( is_array( $option_per_filter ) && count( $option_per_filter ) > 0 ) {
          foreach ( $option_per_filter as $option_pretty_name => $option_name ) {
            # @todo: sanitize attributes
            printf( "<tr class='active'>
            <th scope='row' class='check-column'><input type='checkbox' name='rroptionsexport[]' value='%s' /></th>
            <td><p><strong>%s</strong><br />%s</p></td>
          </tr>", $option_name, $option_pretty_name, $option_name );
          }
        }
      }
      ?>



            </tbody>
          </table>
      <?php wp_nonce_field( 'srmp3_options_importer' ); ?>
          <p> <input type='submit' class="button" name='export-options' value='Export selected options'/></p>
          <?php
        } else {
          _e( 'There are actually no options specified for backup. Create one.', 'sonaar-music' );
        }
        ?>


      </form>




    </div>
    <script>
      jQuery(document).ready(function($){
        var $tableCheckbox = $('#update-plugins-table');
        var $optionCheckbox = $('tbody').find('input');
        var $exportButton = $('input[name=export-options]');

        function srmp3_checkCheckbox(){
          if( $optionCheckbox.filter(':checked').length === 0 && $('input[id=plugins-select-all]').filter(':checked').length === 0 ){
            $exportButton.attr('disabled', 'disabled');
          }else{
            $exportButton.removeAttr('disabled');
          }
        }

        srmp3_checkCheckbox(); //firefox keeps checkboxes checked after a refresh
        $tableCheckbox.click(function(){
          srmp3_checkCheckbox();
        });
      });
    </script>
    <?php
  }
}
add_action( 'admin_menu', 'srmp3_export_register_pages' );