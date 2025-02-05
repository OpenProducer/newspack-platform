<?php

class SRMP3_ShortcodeImporter {
    public function __construct() {
      add_action('cmb2_admin_init', array($this, 'register_shortcode_metabox'), 10);
      add_action('wp_ajax_import_srmp3_shortcode_template', array($this, 'import_srmp3_shortcode_template'));
    }
    public function register_shortcode_metabox(){
      new_cmb2_box( array(
        'id'           		=> 'sonaar_music_shortcode_import_metabox',
        'title'        		=> esc_html__( 'Sonaar Music', 'sonaar-music-pro' ),
        'object_types' 		=> array( 'options-page' ),
        'option_key'      	=> 'srmp3-import-shortcode-templates', // The option key and admin menu page slug.
        'icon_url'        	=> 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        'menu_title'      	=> esc_html__( 'Import Shortcode Templates', 'sonaar-music-pro' ), // Falls back to 'title' (above).
        'parent_slug'     	=> 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu.
        'capability'      	=> 'manage_options', // Cap required to view options-page.
        'enqueue_js' 		=> false,
        'cmb_styles' 		=> false,
        'display_cb'		=> array('SRMP3_ShortcodeImporter', 'srmp3_get_json_url'),
        'position' 			=> 997,
        ) );
    }
    public static function srmp3_get_json_url() {
      if ( ! defined( 'SR_PLAYLIST_CPT' ) ) {
        return;
      }
      if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'srmp3-import-shortcode-templates' ) {
        return;
      }
    
      $SRMP3_plan = get_site_option('SRMP3_purchased_plan');
      
      ?>
      <div class="srmp3_wrap_templates">
        <h1 class="srmp3_import_head">Import Shortcode Templates<div class="srmp3_pro_badge"><i class="sricon-Sonaar-symbol">&nbsp;</i>Pro feature</div></h1>
        <h2 class="srmp3_import_subtitle">
          Save time with our pre-designed Player Shortcode Templates!
        </h2>
        <div class="srmp3_import_subtitle">
          To access them, you need one of the following:
        <ul>
          <li>MP3 Audio Player Pro [Starter Plan] + Player Templates Access or</li>
          <li>MP3 Audio Player Pro [Unlimited Plan] or</li>
          <li>MP3 Audio Player Pro [Lifetime Plan]</li>
        </ul>
          <a href="https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin" target="_blank">View Plan Features Comparison Here</a>
        </div>
        <div class="srmp3_import_subtitle">
          Imported templates will be available in the Shortcode Player Builder and will be editable right away.
        </div>
    </div>
        <?php if ( function_exists( 'printPurchasedPlan' ) ){ ?>
          <div class="srmp3_import_license-msg">You are currently on the <span class="srmp3_import_license-msg--plan"><?php echo esc_html( printPurchasedPlan() ) ?> plan. </span><?php echo wp_kses_post(SRMP3_ShortcodeImporter::printImportCTA('heading')) ?></div>
        <?php }else{ ?>
          <div class="srmp3_import_license-msg">You are currently on the <span class="srmp3_import_license-msg--plan">free version. </span><?php echo wp_kses_post(SRMP3_ShortcodeImporter::printImportCTA('heading')) ?></div>
        <?php } 
    
      $licence = get_site_option('sonaar_music_licence');
      $transient_key = 'SRMP3_shortcode_templates_json_url';
      $templates_json_url = get_transient($transient_key);
    
      if (false === $templates_json_url) {
        $api_url = 'https://sonaar.io/wp-json/wp/v2/sonaar-api/srmp3-shortcode-templates/?licence=' . $licence;
        $response = wp_remote_get($api_url);
    
        if (is_wp_error($response) || $response['response']['code'] !== 200 || $response['headers']['content-type'] !== 'application/json; charset=UTF-8') {
          echo '<br>';
          echo '<div class="notice notice-error is-dismissible"><p>Please contact Sonaar.io support team with Error Code 6723</p></div>';
          echo '</div>'; // close srmp3_wrap_templates div
          return;
        }
    
        $body = wp_remote_retrieve_body($response);
        $templates_json_url = json_decode($body, true);
    
        if (is_wp_error($templates_json_url) || empty($templates_json_url)) {
          echo '<br>';
          echo '<div class="notice notice-error is-dismissible"><p>Please contact Sonaar.io support team with Error Code 6723</p></div>';
          echo '</div>'; // close srmp3_wrap_templates div
          return;
        }
    
        set_transient($transient_key, $templates_json_url, WEEK_IN_SECONDS);
      }
    
      $url = $templates_json_url; // The URL is now stored in the transient directly
    
      $relativeurl = $url;
      $filename = basename($relativeurl);
      $dirname = str_replace($filename, '', $relativeurl) . '/shortcode_templates/';
    
      $templatesList = SRMP3_ShortcodeImporter::srmp3_getTemplatesList($url);
      if (!$templatesList) {
        echo '</div>'; // close srmp3_wrap_templates div
        return;
      }
      $canImport = true;
      SRMP3_ShortcodeImporter::srmp3_outputTemplates($templatesList, $dirname, $canImport);
    }
      
      

    public static function printImportCTA($block){

      $SRMP3_plan = get_site_option('SRMP3_purchased_plan');
      $licenseKey = get_site_option('sonaar_music_licence');

      if($block == 'heading'){
        if ( !function_exists( 'run_sonaar_music_pro' ) ){
          return  sprintf(__('<a href="%1$s" target="_blank">Get MP3 Audio Player Pro</a>', 'sonaar-music'), 'https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin');
        }
        switch ($SRMP3_plan) {
          case '1':
            $SRMP3_plan = sprintf(__('<a href="%1$s" target="_blank">Upgrade to get access</a>', 'sonaar-music'), 'https://sonaar.io/cart/?nocache=true&edd_action=sl_license_upgrade&license_id='. $licenseKey .'&upgrade_id=5');
            break;

          case '6':
            $SRMP3_plan = __('Enjoy!', 'sonaar-music');
            break;

          case '5':
            $SRMP3_plan = sprintf(__('<a href="%1$s" target="_blank">Upgrade to get access</a>', 'sonaar-music'), 'https://sonaar.io/cart/?nocache=true&edd_action=sl_license_upgrade&license_id='. $licenseKey .'&upgrade_id=6');
            break;

          case '7':
            $SRMP3_plan = __('Enjoy!', 'sonaar-music');
            break;

          case '3':
            $SRMP3_plan = __('Enjoy!', 'sonaar-music');
            break;

          case '4':
            $SRMP3_plan = __('Enjoy!', 'sonaar-music');
            break;

          default:
            $SRMP3_plan = __('Enter your License Key to get access.', 'sonaar-music');
            break;
        }
        return $SRMP3_plan;

      }
      if($block == 'after_import'){
        if ( !function_exists( 'run_sonaar_music_pro' ) ){
          return __('You are using the free version of MP3 Audio Player. The templates are only compatible with the PRO version. <br><a href="https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin">Get MP3 Audio Player Pro</a>', 'sonaar-music');
        }
        switch ($SRMP3_plan) {
          case '1':
            $SRMP3_plan = sprintf(__('You need the Player Templates Addon access with your current Starter plan.<br><a href="%1$s">Upgrade here</a>', 'sonaar-music'), 'https://sonaar.io/cart/?nocache=true&edd_action=sl_license_upgrade&license_id='. $licenseKey .'&upgrade_id=5');
            break;
      
          case '6':
            $SRMP3_plan = true;
            break;
      
          case '5':
            $SRMP3_plan = sprintf(__('You need the Player Templates access with your current Business plan<br><a href="%1$s">Upgrade here</a>', 'sonaar-music'), 'https://sonaar.io/cart/?nocache=true&edd_action=sl_license_upgrade&license_id='. $licenseKey .'&upgrade_id=6');
            break;
      
          case '7':
            $SRMP3_plan = true;
            break;
      
          case '3':
            $SRMP3_plan = true;
            break;
      
          case '4':
            $SRMP3_plan = true;
            break;
      
          default:
            $SRMP3_plan = sprintf( __( 'We must validate your license first. Make sure to <strong>remove</strong> and re-activate your license key <a href="%1$s" >here</a> ', 'sonaar-music' ), admin_url( 'edit.php?post_type=' . SR_PLAYLIST_CPT . '&page=sonaar_music_pro_license' ) , 'sonaar-music');
            break;
        }
        return $SRMP3_plan;
      }
    }

   

    public static function srmp3_getTemplatesList($url){
      $data = @file_get_contents($url);
    
      if ($data === false) {
        // Handle error - unable to fetch JSON data
        echo '<div class="notice notice-error is-dismissible"><p>Error: Unable to fetch JSON data. The URL may be incorrect or the server may be down. Please try again later or contact support.</p></div>';
        return false;
      }
    
      $jsonData = json_decode($data);
    
      if ($jsonData === null) {
        // Handle error - unable to parse JSON data
        echo '<div class="notice notice-error is-dismissible"><p>Error: Unable to parse JSON data. Please check the JSON format and try again.</p></div>';
        return false;
      }
    
      return $jsonData;
    }
    

    public static function srmp3_outputTemplates($templatesList, $dirname) {
      
      $url = admin_url('edit.php?post_type=sr_playlist&page=srmp3_settings_shortcodebuilder');
      ?>

        <div class="srmp3_import_messages">
          <div class="srmp3_import_notice srmp3_import_success">
            <?php echo sprintf(__('<strong>Yepi! Your template <span class="srmp3-template-name"></span> has been successfully imported!</strong> You can access it in MP3 Player > <a href="%1$s">Shortcode Player Builder</a>.', 'sonaar-music' ), esc_html($url)) ;?>
          </div>
          <div class="srmp3_import_notice srmp3_import_failed"><strong>Oops! Template can't been imported.</strong></div>
        </div>

        <div id="srp_templates_container">
          <div class="srp_search_main"><div class="srp_search_container"><i class="fas fa-search"></i><input class="srp_search" enterkeyhint="done" placeholder="Search for a template keyword, eg: spectrum, Example 005, Grid, etc.." \></div></div>

          <ul class="template-list">
            <?php foreach ($templatesList->items as $template): ?>
              <?php
              $tmplt_filename = basename($template->filename);
              $tmplt_img = str_replace('.json', '.jpg', $tmplt_filename);
              $tmplt_title = str_replace('.json', '', $tmplt_filename);
              $tmplt_title = str_replace('-', ' ', $tmplt_title);
              $tmplt_title = ucwords($tmplt_title);
              ?>
              
                <li class="template-thumbnail">
                  <img src="<?php echo esc_html($dirname . $tmplt_img) ?>">
                  <div class="srmp3_importing"><?php echo __( 'Please Wait', 'sonaar-music' );?></div>
                  <div class="srmp3_import_overlay" data-title="<?php echo esc_html($tmplt_title); ?>" data-filename="<?php echo esc_html($dirname . $tmplt_filename); ?>">
                  <div class="srp_elementor_import"><?php echo __( 'Import Template', 'sonaar-music' );?></div>
                  </div>
                  <div class="srp-tmpl-title"><?php echo esc_html($tmplt_title) ?></div>
                </li>
            
            <?php endforeach; ?>
          </ul>
      </div>
      <?php
    }

    public function import_srmp3_shortcode_template() {
      
      check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');
      $ret = array(); 

      // Check if the current user has the required capability
      if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => esc_html__('You do not have sufficient permissions to perform this action.', 'sonaar-music')]);
        exit;
      }

      $checkLicenseType = $this->printImportCTA('after_import');

      if ($checkLicenseType !== true){
        wp_send_json_error(['message' => $checkLicenseType]);
        exit;
      }

      $filename = strip_tags($_POST['filename']);

      if (substr($filename, -5) !== '.json') {
        wp_send_json_error(['message' => esc_html__('Error reading file. Not a JSON. Could not load the template file. Please contact Sonaar.io support team!', 'sonaar-music')]);
        exit;
      }

      $fileContent = file_get_contents($filename);
      $templateSettings = json_decode($fileContent, true); // Assuming JSON represents an array

      if ($templateSettings) {
        $result = SRMP3_ShortcodeBuilder::import_shortcodebuilder_template($templateSettings);
        exit;
      } else {
        wp_send_json_error(['message' => esc_html__('Could not load the template file. Error 6452. Please contact Sonaar.io support team!', 'sonaar-music')]);
        exit;
      }
    }
    
}

new SRMP3_ShortcodeImporter();