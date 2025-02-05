<?php

class SRMP3_ShortcodeBuilder {
    public function __construct() {
        add_filter( 'admin_body_class', array($this, 'srmp3_admin_dark_mode_body_class'));
        add_action( 'srmp3_register_shortcodebuilder_options', array($this, 'srmp3_register_shortcodebuilder_options'));
        add_action( 'wp_ajax_srmp3_toggle_dark_mode', array($this, 'srmp3_toggle_dark_mode'));
        add_action( 'wp_ajax_update_shortcode', array($this, 'update_shortcodeBuilder_callback') );
        add_action( 'wp_ajax_reset_shortcode', array($this, 'reset_shortcodeBuilder_callback') );
        add_action( 'wp_ajax_load_srmp3_template',  array($this, 'load_shortcodebuilder_template'));
        add_action( 'wp_ajax_delete_srmp3_template',  array($this, 'delete_shortcodebuilder_template'));
        add_action( 'wp_ajax_import_shortcode_template',  array($this, 'import_shortcodebuilder_template'));
        add_action( 'wp_ajax_export_srmp3_template',  array($this, 'export_shortcodebuilder_template'));
        add_action( 'cmb2_save_options-page_fields', array($this, 'save_shortcodebuilder_template'),10, 3);
        add_action( 'current_screen', array( $this, 'srmp3_disable_admin_notices_on_shortcode_builder') );
        //add_action( 'admin_menu', array( $this, 'srmp3_remove_footer_on_shortcode_builder') );
        require_once SRMP3_DIR_PATH . '/includes/queries.php';
    
    }
    public function srmp3_admin_dark_mode_body_class($classes) {
        $user_id = get_current_user_id();
        $dark_mode = get_user_meta($user_id, 'sonaar_mp3_darkmode', true);
    
        if ($dark_mode === 'true') {
            $classes .= ' dark-mode';
        }
    
        return $classes;
    }
    function srmp3_toggle_dark_mode() {
        check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');
        $user_id = get_current_user_id();
        $dark_mode = sanitize_text_field($_POST['dark_mode']);
    
        if ($user_id > 0 && ($dark_mode === 'true' || $dark_mode === 'false')) {
            update_user_meta($user_id, 'sonaar_mp3_darkmode', $dark_mode);
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    /*public function srmp3_remove_footer_on_shortcode_builder() {
        add_filter('admin_footer_text', '__return_empty_string');
        add_filter('update_footer', '__return_empty_string', 11);
    }*/
    public function srmp3_disable_admin_notices_on_shortcode_builder() {
        $screen = get_current_screen();
        
        if ($screen->id === 'sr_playlist_page_srmp3_settings_shortcodebuilder') {
            remove_all_actions('admin_notices');
            // Re-add the specific admin notice you want to keep
            add_action('admin_notices', array($this, 'shortcode_builder_with_elementor'));
            add_action('admin_print_styles', array($this, 'srmp3_hide_admin_notices_with_css'));
        }
    }
    
    public function srmp3_hide_admin_notices_with_css() {
        echo '<style>
            .notice:not(.srmp3-shortcode-builder-notice):not(.notice-success) {
                display: none !important;
            }
        </style>';
    }
    public function shortcode_builder_with_elementor() {
        if (did_action('elementor/loaded')) {
            $screen = get_current_screen();
            if ($screen->id == 'sr_playlist_page_srmp3_settings_shortcodebuilder') {
                echo '<div class="srmp3-shortcode-builder-notice notice notice-warning">
                <p>' . __('You are using Elementor. For seamless integration, we recommend using the Elementor Page Builder instead of this Shortcode Builder. However, you can still use the Shortcode Builder to generate shortcodes and then paste them into an Elementor widget.', 'sonaar-music') . '</p>
                </div>';
            }
        }
    }
    public function export_shortcodebuilder_template(){
        check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');

        if (isset($_POST['template_name']) && !empty($_POST['template_name'])) {
            $template_name = sanitize_text_field($_POST['template_name']);
            $templates = get_option('srmp3_shortcode_templates', []);
           
            if (isset($templates[$template_name]) && is_array($templates[$template_name])) {
                //We assume that the template is saved and is available in the database
                $template_values = $templates[$template_name];

                wp_send_json_success(['message' => 'Template Copied Successfully.', 'template_values' => $templates[$template_name]]);
            } else {
                wp_send_json_error(['message' => 'Template is not saved. Make sure to save the template first.']);
            }
            
        } else {
            wp_send_json_error(['message' => 'Template name is required.']);
        }
    }
    public function delete_shortcodebuilder_template(){
        if (!current_user_can('manage_options')) {
            wp_die('Access Denied');
        }
        check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');

        if (isset($_POST['template_name']) && !empty($_POST['template_name'])) {
            $template_name = sanitize_text_field($_POST['template_name']);
            $templates = get_option('srmp3_shortcode_templates', []);
    
            if (isset($templates[$template_name]) && is_array($templates[$template_name])) {
                unset($templates[$template_name]);
                update_option('srmp3_shortcode_templates', $templates);
                wp_send_json_success(['message' => 'Template deleted successfully.']);
            } else {
                wp_send_json_error(['message' => 'Template not found or invalid format.']);
            }
        } else {
            wp_send_json_error(['message' => 'Template name is required.']);
        }
    }
    public function save_shortcodebuilder_template($object_id, $updated, $cmb) {
        //CMB2 HOOK

        // Check if the correct page and data are being saved
        if (!function_exists('run_sonaar_music_pro') || empty(get_site_option('sonaar_music_licence'))) {
            return;
        }
        if (get_site_option('SRMP3_License_Status') != 'active') {
            return;
        }
        if ('srmp3_settings_shortcodebuilder' == $object_id && isset($_POST['save_template_name']) && !empty($_POST['save_template_name'])) {
            // Sanitize the template name
            $templateName = sanitize_text_field($_POST['save_template_name']);
    
            // Recursive sanitization directly inside the method
            function sanitize_data($data) {
                if (is_array($data)) {
                    return array_map('sanitize_data', $data);
                }
                return sanitize_text_field($data);
            }
    
            // Prepare and sanitize settings, including nested arrays
            $settings = sanitize_data($_POST);
    
            // Remove any data not needed or not to be saved
            unset($settings['load_template'], $settings['submit']);
    
            // Your custom function to save the template
            $saveResult = SRMP3_ShortcodeBuilder::saveTemplate($templateName, $settings);
        }
    }
    public static function saveTemplate($templateName, $settings, $is_import = false) {
        // Assuming that $settings is already an array of sanitized data
        if (empty($templateName) || empty($settings)) {
            return false;
        }
    
        // Fetch the existing templates
        $templates = get_option('srmp3_shortcode_templates', []);

        if ($is_import) {
            // Check if the template name already exists
            if (isset($templates[$templateName])) {
                $originalTemplateName = $templateName;
                $counter = 1;  // Start the counter
            
                // Continue looping until a unique name is found
                while (isset($templates[$templateName])) {
                    $templateName = $originalTemplateName . ' - Copy ' . $counter;
                    $counter++;
                }
            
                // Once a unique name is found, update the settings
                $settings['save_template_name'] = $templateName;
            }
          
        }
        
    
    
        // Assign the current settings to the specified template name
        $templates[$templateName] = $settings;

        // Update the option with the new set of templates
        $updated = update_option('srmp3_shortcode_templates', $templates);
    
        // Provide feedback based on whether the option was successfully updated
        if ($updated) {
            // Option was updated
            return $templateName;
        } else {
            // Option update failed, possibly because the new value is the same as the old one
            // or due to a database error
            return false;
        }
    }
    public static function import_shortcodebuilder_template($templateSettings = null) {
        // This function is an ajax callback from shortcode builder AND also used in the big template importer
        check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');
    
        if (!function_exists('run_sonaar_music_pro') || empty(get_site_option('sonaar_music_licence'))) {
            return;
        }
        if (get_site_option('SRMP3_License_Status') != 'active') {
            return;
        }
        function sanitize_recursive($value) {
            if (is_array($value)) {
                // Recursively apply to each element if it's an array
                return array_map('sanitize_recursive', $value);
            } else {
                // Apply sanitization if it's not an array
                return sanitize_text_field($value);
            }
        }
    
        $settings = $templateSettings ?: $_POST['template_settings'] ?? null;
    
        if (is_array($settings)) {
            $sanitized_settings = sanitize_recursive($settings);
            $settings = $sanitized_settings;
    
                // Remove any data not needed or not to be saved
            unset($settings['load_template'], $settings['submit-cmb'], $settings['nonce_CMB2phpsrmp3_settings_shortcodebuilder']);
    
            $templateName = $settings['save_template_name'] ?? null;
            $saveResult = SRMP3_ShortcodeBuilder::saveTemplate($templateName, $settings, true);
    
            if ($saveResult !== false) {
                wp_send_json_success(['message' => 'Template Imported Successfully.', 'template_name' => $saveResult]);
            } else {
                wp_send_json_error(['message' => 'Failed to import template.']);
            }
        } else {
            wp_send_json_error('Invalid data format.');
        }
    }
    
    public function load_shortcodebuilder_template() {
        check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');

        if (isset($_POST['template_name']) && !empty($_POST['template_name'])) {
            $template_name = sanitize_text_field($_POST['template_name']);
            $templates = get_option('srmp3_shortcode_templates', []);
    
            if (isset($templates[$template_name]) && is_array($templates[$template_name])) {
                $settings_array = $templates[$template_name];
                update_option('srmp3_settings_shortcodebuilder', $settings_array); // Update srmp3_settings_shortcodebuilder option
                wp_send_json_success(['message' => 'Template loaded successfully.', 'settings' => $settings_array]);
            } else {
                wp_send_json_error(['message' => 'Template not found or invalid format.']);
            }
        } else {
            wp_send_json_error(['message' => 'Template name is required.']);
        }
    }
    public function srmp3_save_template_callback() {
        check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');

        if (isset($_POST['template_name']) && !empty($_POST['template_name'])) {
            $template_name = sanitize_text_field($_POST['template_name']);
            
            // Fetch the current settings
            $current_settings = get_option('srmp3_settings_shortcodebuilder', []);

            // Save the current settings in the templates option
            $templates = get_option('srmp3_shortcode_templates', []);
            $templates[$template_name] = $current_settings;
            update_option('srmp3_shortcode_templates', $templates);

            wp_send_json_success(['message' => 'Template saved successfully.']);
        } else {
            wp_send_json_error(['message' => 'Template name is required.']);
        }
    }
    public function reset_shortcodeBuilder_callback(){
        if (!current_user_can('manage_options')) {
            wp_die('Access Denied');
        }
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sonaar_music_admin_ajax_nonce')) {
            wp_die('Nonce validation failed');
        }
        delete_option('srmp3_settings_shortcodebuilder');

        wp_send_json_success();
        wp_die();
    }
    public function update_shortcodeBuilder_callback() {
        // Check for the nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sonaar_music_admin_ajax_nonce')) {
            wp_die('Nonce validation failed');
        }
    
        $fields = [
            'source',
            'albums',
            'audio_meta_field',
            'repeater_meta_field',
            'category',
            'post_id_to_test',            
           
            'rss_feed',
            'rss_item_title',
            'rss_items',
            'feed',
            'feed_title',
            'feed_img',

            //Order
            'posts_per_page',
            'tracks_per_page',
            'pagination', //true or false
            'lazy_load',
            'order',
            'orderby',
            'searchbar',
            'searchbar_placeholder',

            // General
            'hide_artwork',
            'hide_metas',
            'display_control_artwork',
            'show_control_on_hover',
            'artwork_background',
            'adaptive_colors',
            'adaptive_colors_freeze',
            'scbuilder_show_playlist',
            'hide_timeline',
            'show_mini_player',
            'use_play_label_with_icon',
            'hide_play_icon',
            'play_text',
            'pause_text',
            'play_bt_text_color',
            'play_bt_bg_color',
            'hide_progressbar',
            'hide_control_under',
            'hide_times',
            'progressbar_inline',
            'show_meta_duration',
            'show_publish_date',
            'show_tracks_count',
            'show_skip_bt',
            'show_shuffle_bt',
            'show_repeat_bt',
            'show_speed_bt',
            'show_volume_bt',
            'show_miniplayer_note_bt',
            
            'progress_bar_style',
            'wave_bar_width',
            'wave_bar_gap',
            'wave_color',
            'wave_progress_color',

            'sticky_player',
            'track_memory',
            'player_layout',
            'tracklist_layout',
            'hide_track_number',
            'grid_column_number',
            'artist_wrap',


            // Call to actions
            'show_track_market',
            'track_market_inline',
            'cta_track_show_label',
            'force_cta_dl',
            'force_cta_share',
            'force_cta_favorite',
            'force_cta_singlepost',

            'show_album_market',
            'store_title_text',

            'track_artwork',
            'track_artwork_play_button',
            'track_artwork_play_on_hover',
            'track_artwork_format',
            'scrollbar',
            'show_track_publish_date',
            'hide_trackdesc',
            'track_desc_lenght',
            'strip_html_track_desc',
            // Tracklist Soundwave
            'tracklist_soundwave_show',
            'tracklist_soundwave_cursor',
            'tracklist_soundwave_style',
            'tracklist_soundwave_color',
            'tracklist_soundwave_progress_color',
            'tracklist_soundwave_bar_width',
            'tracklist_soundwave_bar_gap',
            'tracklist_soundwave_line_cap',

            //advanced
            'shuffle',
            'notrackskip',
            'no_loop_tracklist',
            'id',
            'player_class',
            'custom_css',


            // Slider fields
            'slider',
            'slide_source',
            'slider_play_on_hover',
            'slidesPerView',
            'effect',
            'loop',
            'navigation',
            'centeredSlides',
            'spaceBetween',
            'coverflowEffect',

             // Spectro fields
            'spectro_enabled',
            'spectroStyle',
            'spectro_color1',
            'spectro_color2',
            'spectro_shadow',
            'spectro_reflectFx',
            'spectro_sharpFx',
            'spectro_barCount',
            'spectro_barWidth',
            'spectro_barGap',
            'spectro_blockGap',
            'spectro_blockHeight',
            'spectro_canvasHeight',
            'spectro_halign',
            'spectro_valign',
            'spectro_enableOnTracklist',
            'spectro_bounceClass',
            'spectro_bounceVibrance',
            'spectro_bounceBlur',

            //Custom Fields
            'custom_fields_heading',
            'cc_group',


        ];
        if (!function_exists('run_sonaar_music_pro') || empty(get_site_option('sonaar_music_licence'))) {
            unset($_POST['track_memory']);
            unset($_POST['sticky_player']);
            unset($_POST['slider']);
            unset($_POST['category']);
            unset($_POST['order']);
            unset($_POST['orderby']);
            unset($_POST['show_meta_duration']);
            unset($_POST['show_publish_date']);
            unset($_POST['show_tracks_count']);
            unset($_POST['show_skip_bt']);
            unset($_POST['show_shuffle_bt']);
            unset($_POST['show_repeat_bt']);
            unset($_POST['show_speed_bt']);
            unset($_POST['show_volume_bt']);
            unset($_POST['show_miniplayer_note_bt']);
            unset($_POST['hide_progressbar']);
            unset($_POST['hide_times']);
            unset($_POST['hide_times_typo']);
            unset($_POST['display_control_artwork']);
            unset($_POST['show_control_on_hover']);
            
            unset($_POST['artwork_background']);
            unset($_POST['artwork_background_size']);
            unset($_POST['artwork_set_background_hideMainImage']);
            unset($_POST['artwork_background_blur']);
            unset($_POST['artwork_set_background_overflow']);
            unset($_POST['artwork_background_pos']);
            unset($_POST['artwork_background_posx']);
            unset($_POST['artwork_background_posy']);

            unset($_POST['playpause_size']);
            unset($_POST['playpause_circle_size']);
            unset($_POST['playpause_circle_border_width']);
            unset($_POST['progressbar_inline']);
            unset($_POST['track_artwork']);
            unset($_POST['track_artwork_format']);
            unset($_POST['track_artwork_play_button']);
            unset($_POST['tracklist_padding']);
            unset($_POST['tracklist_soundwave_line_cap']);
            unset($_POST['tracklist_soundwave_bar_gap']);
            unset($_POST['tracklist_soundwave_bar_width']);
            unset($_POST['tracklist_soundwave_progress_color']);
            unset($_POST['tracklist_soundwave_color']);
            unset($_POST['tracklist_soundwave_style']);
            unset($_POST['tracklist_soundwave_cursor']);
            unset($_POST['tracklist_soundwave_show']);
            unset($_POST['hide_track_number']);
            unset($_POST['show_track_publish_date']);
            unset($_POST['artist_wrap']);
            unset($_POST['track_item_padding']);
            unset($_POST['hide_trackdesc']);
            unset($_POST['hide_info_icon']);
            unset($_POST['force_cta_dl']);
            unset($_POST['force_cta_share']);
            unset($_POST['force_cta_singlepost']);
            unset($_POST['posts_per_page']);
            unset($_POST['scrollbar']);
            unset($_POST['track_market_inline']);
            unset($_POST['hide_play_icon']);
            unset($_POST['play_text']);
            unset($_POST['pause_text']);
            unset($_POST['use_play_label_with_icon']);
            unset($_POST['cta_track_show_label']);
            unset($_POST['spectro_enabled']);
            unset($_POST['custom_css']);
        }
        if(!function_exists( 'run_sonaar_music_pro' ) || get_site_option('SRMP3_ecommerce') != '1' || empty(get_site_option('sonaar_music_licence'))){
            unset($_POST['searchbar']);
            unset($_POST['force_cta_favorite']);
            unset($_POST['lazy_load']);
            unset($_POST['pagination']);
            unset($_POST['tracks_per_page']);
            unset($_POST['player_metas_acf_field']);
            unset($_POST['player_metas_metakey']);
            unset($_POST['cc_group']);
            //unset custom fields   
        }
        $shortcode_attrs = [];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                // Sanitize text input before using it
                $shortcode_attrs[$field] = sanitize_text_field($_POST[$field]);
            } else {
                $shortcode_attrs[$field] = '';
            }
        }
        // Initialize an associative array to hold CSS properties grouped by selectors
        $cssRules = [];

        function process_post_data($array, &$cssRules, $currentSelector = '', $inheritedProperty = null) {
            foreach ($array as $key => $value) {

                //if key = custom_css, skip
                if($key == 'custom_css'){
                    continue;
                }
                if (is_array($value)) {
                  
                    // Potentially update currentSelector or pass the last known property
                    if ($key === 'selector' && !empty($value)) {
                        $newSelector = is_string($value) ? trim($value) : $currentSelector;
                        process_post_data($value, $cssRules, $newSelector, $inheritedProperty);
                    } else {
                      
                        // Recurse into the array with the potential property context
                        $propertyCandidate = (strpos($key, '-') !== false || in_array($key, ['font-size', 'color', 'background-color', 'font-family', 'text-transform'])) ? $key : $inheritedProperty;
                        
                        process_post_data($value, $cssRules, $currentSelector, $propertyCandidate);
                    }
                } else {
                    // Check if the value contains the expected CSS delimiter
                    if (strpos($value, '|||') !== false) {
                        list($cssValue, $cssSelectorPart) = explode('|||', $value, 2);
                        $cssSelectorPart = trim($cssSelectorPart);
                        $cssSelector = $currentSelector ?: $cssSelectorPart;
                        $cssProperty = $inheritedProperty ?: $key;
        
                        // Use the last known CSS property if the current key is numeric
                        if (is_numeric($key) && $inheritedProperty) {
                            $cssProperty = $inheritedProperty;
                        }
        
                        // Ensure that a CSS property is determined before adding to rules
                        if ($cssProperty && !is_numeric($cssProperty)) {
                            if (($cssProperty == 'font-size' || $cssProperty == 'line-height' || $cssProperty == 'width') && !preg_match('/(px|em|%|pt|cm|mm|in|ex|ch|rem|vh|vw|vmin|vmax)$/i', $cssValue)) {
                                $cssValue .= 'px';  // Append 'px' if no unit is present
                            }
                            $cssRules[$cssSelector][$cssProperty] = $cssValue;
        
                        }else{
                           // For groups and when they have full CSS string
                            if ($cssSelector) {
                                 // Assume $cssString is your combined selector and properties string
                                $cssString = trim($cssSelector, " \t\n\r\0\x0B{}");

                                // Split the string into selector and declarations
                                [$cssSelector, $declarations] = explode('{', $cssString, 2);
                                $cssSelector = trim($cssSelector);
                                $declarations = rtrim(trim($declarations), '; }');

                                // Split and process declarations into properties
                                $properties = array_filter(explode(';', $declarations));
                                foreach ($properties as $property) {
                                    [$key, $value] = array_map('trim', explode(':', $property, 2));
                                    if ($key && isset($value)) {
                                        $cssRules[$cssSelector][$key] = $value;
                                    }
                                }
                            }

                        }
                    }
                }
               
            }
        }
        
        $cssRules = [];
        process_post_data($_POST, $cssRules);
        
        // Building the final CSS string
        $final_css = "";

        foreach ($cssRules as $selector => $props) {
            
            if(strpos($selector, '{') !== false){
                $final_css .= "$selector "; 
            } else {
                $final_css .= "$selector { ";
                foreach ($props as $prop => $value) {
                    $final_css .= "$prop: $value; ";
                }
                $final_css .= "}";
            }

        }
        if(isset($shortcode_attrs['custom_css']) && !empty($shortcode_attrs['custom_css'])){
            $final_css .= $shortcode_attrs['custom_css'];
            unset($shortcode_attrs['custom_css']);
        }
        //strip all spaces of $final_css before and after '{' and before and after '}' and before and after ';' and also after ':'
        function strip_spaces_from_css($css) {
            // Strip spaces before and after '{'
            $css = preg_replace('/\s*{\s*/', '{', $css);
            // Strip spaces before and after '}'
            $css = preg_replace('/\s*}\s*/', '}', $css);
            // Strip spaces before and after ';'
            $css = preg_replace('/\s*;\s*/', ';', $css);
            // Strip spaces after ':'
            $css = preg_replace('/\s*:\s*/', ':', $css);
            return $css;
        }

        $final_css = strip_spaces_from_css($final_css);

        // Process player_meta_group to create a formatted player_metas string
        $player_metas_entries = [];
        if (isset($_POST['player_meta_group']) && is_array($_POST['player_meta_group'])) {
            $metas = $_POST['player_meta_group']['player_metas'] ?? [];
            $customheadings = $_POST['player_meta_group']['player_metas_customheading'] ?? [];
            $prefixes = $_POST['player_meta_group']['player_metas_prefix'] ?? [];
            $htmltags = $_POST['player_meta_group']['player_metas_htmltag'] ?? [];
            $acf_field = $_POST['player_meta_group']['player_metas_acf_field'] ?? [];
            $metakey = $_POST['player_meta_group']['player_metas_metakey'] ?? [];
            foreach ($metas as $index => $meta_type) {
                $meta_type_sanitized = sanitize_text_field($meta_type);
                $customheading_sanitized = !empty($customheadings[$index]) ? sanitize_text_field($customheadings[$index]) : '';
                $prefix_sanitized = sanitize_text_field($prefixes[$index] ?? '');
                $htmltag_sanitized = sanitize_text_field($htmltags[$index] ?? '');
                $acf_field_sanitized = sanitize_text_field($acf_field[$index] ?? '');
                $metakey_sanitized = sanitize_text_field($metakey[$index] ?? '');

                // Construct the meta entry
                $entry = "meta_{$meta_type_sanitized}";
                if (!empty($acf_field_sanitized)) {
                    $entry .= "::{$acf_field_sanitized}";
                }
                if (!empty($metakey_sanitized)) {
                    $entry .= "::{$metakey_sanitized}";
                }
                // Add custom heading if present, else skip entry if it's a special case for custom_heading
                if (!empty($customheading_sanitized) || $meta_type_sanitized !== 'custom_heading') {
                    if (!empty($customheading_sanitized)) {
                        $entry .= "::{$customheading_sanitized}";
                    }

                    // Add prefix if present
                    if (!empty($prefix_sanitized)) {
                        //if last character is : add an empty space at the end because it will be used as a separator... glitch.
                        if (substr($prefix_sanitized, -1) == ':') {
                            $prefix_sanitized .= ' ';
                        }
                        $entry .= "::prefix_{$prefix_sanitized}";
                    }

                    // Add HTML tag if present
                    if (!empty($htmltag_sanitized)) {
                        $entry .= "::{$htmltag_sanitized}";
                    }

                    // Only add the entry if there's more than the initial "meta_{type}"
                    if ($entry != "meta_{$meta_type_sanitized}") {
                        $player_metas_entries[] = $entry;
                    }
                }
            }
        }

        // Join the entries with '||' to form the value of the player_metas attribute
        if (!empty($player_metas_entries)) {
            $shortcode_attrs['player_metas'] = implode('||', $player_metas_entries);
        }else{
            unset($shortcode_attrs['player_metas']);
        }
        if ( $shortcode_attrs['hide_metas'] == "true") {
            $shortcode_attrs['player_metas'] = 'hide';
            unset($shortcode_attrs['hide_metas']);
        }


        // custom fields
        // Process player_meta_group to create a formatted player_metas string
        $cc_entries = [];
        if( isset($_POST['cc_enable']) && $_POST['cc_enable'] == "true" ){
            if (isset($_POST['cc_group']) && is_array($_POST['cc_group'])) {
                $cc_labels = $_POST['cc_group']['cc_label'] ?? [];
                $cc_source = $_POST['cc_group']['cc_source'] ?? [];
                $postmeta = $_POST['cc_group']['cc_postmeta'] ?? [];
                $acf_field = $_POST['cc_group']['cc_acf_field'] ?? [];
                $metakey = $_POST['cc_group']['cc_custom_key'] ?? [];
                $jetengine_field = $_POST['cc_group']['cc_jetengine_field'] ?? [];
                $cc_width = $_POST['cc_group']['cc_width'] ?? [];
                foreach ($cc_labels as $index => $label) {

                    $cc_label_sanitized = sanitize_text_field($label);
                    $cc_source_santized = sanitize_text_field($cc_source[$index] ?? '');
                    $postmeta_sanitized = sanitize_text_field($postmeta[$index] ?? '');
                    $acf_field_sanitized = sanitize_text_field($acf_field[$index] ?? '');
                    $metakey_sanitized = sanitize_text_field($metakey[$index] ?? '');
                    $jetengine_field_sanitized = sanitize_text_field($jetengine_field[$index] ?? '');
                    $cc_width_field_sanitized = sanitize_text_field($cc_width[$index] ?? '');
                    // Construct the meta entry
                    $entry = "{$cc_label_sanitized}";
                    if (!empty($postmeta_sanitized)) {
                        $entry .= "::{$postmeta_sanitized}";
                    }
                    if (!empty($acf_field_sanitized)) {
                        $entry .= "::{$acf_field_sanitized}";
                    }
                    if (!empty($metakey_sanitized)) {
                        $entry .= "::{$metakey_sanitized}";
                    }
                    if (!empty($jetengine_field_sanitized)) {
                        $entry .= "::{$jetengine_field_sanitized}";
                    }
                     // Add prefix if present
                     if (!empty($cc_width_field_sanitized)) {
                        $entry .= "::{$cc_width_field_sanitized}";
                    }
                    $cc_entries[] = $entry;
                }
            }
            // Join the entries with '||' to form the value of the player_metas attribute
            if (!empty($cc_entries)) {
                $shortcode_attrs['custom_fields_columns'] = implode(';', $cc_entries);
            }else{
                unset($shortcode_attrs['custom_fields_columns']);
            }
        }



        // Conditional logic based on the 'source' field
        switch ($shortcode_attrs['source']) {
            case 'from_cpt':
                //if albums is empty set it to "latest"
                if(empty($shortcode_attrs['albums'])){
                    $shortcode_attrs['albums'] = 'latest';
                }
                unset($shortcode_attrs['category']);
                unset($shortcode_attrs['rss_feed']);
                unset($shortcode_attrs['feed']);
                unset($shortcode_attrs['audio_meta_field']);
                unset($shortcode_attrs['post_id_to_test']);
                break;
            case 'from_cat_all':
                $shortcode_attrs['category'] = 'all';
                unset($shortcode_attrs['albums']);
                unset($shortcode_attrs['rss_feed']);
                unset($shortcode_attrs['feed']);
                unset($shortcode_attrs['audio_meta_field']);
                unset($shortcode_attrs['post_id_to_test']);
                break;
            case 'from_cat':
                unset($shortcode_attrs['albums']);
                unset($shortcode_attrs['rss_feed']);
                unset($shortcode_attrs['feed']);
                unset($shortcode_attrs['audio_meta_field']);
                unset($shortcode_attrs['post_id_to_test']);
                break;
            case 'from_current_post':
                unset($shortcode_attrs['albums']);
                unset($shortcode_attrs['category']);
                unset($shortcode_attrs['rss_feed']);
                unset($shortcode_attrs['feed']);
                break;
            case 'from_favorites':
                $shortcode_attrs['albums'] = 'favorites';
                unset($shortcode_attrs['category']);
                unset($shortcode_attrs['rss_feed']);
                unset($shortcode_attrs['feed']);
                unset($shortcode_attrs['audio_meta_field']);
                unset($shortcode_attrs['post_id_to_test']);
                break;
            case 'recently_played':
                $shortcode_attrs['albums'] = 'recentlyplayed';
                unset($shortcode_attrs['category']);
                unset($shortcode_attrs['rss_feed']);
                unset($shortcode_attrs['feed']);
                unset($shortcode_attrs['audio_meta_field']);
                unset($shortcode_attrs['post_id_to_test']);
                break;
            case 'from_feed':
                unset($shortcode_attrs['albums']);
                unset($shortcode_attrs['category']);
                unset($shortcode_attrs['rss_feed']);
                unset($shortcode_attrs['audio_meta_field']);
                unset($shortcode_attrs['post_id_to_test']);
                break;
            case 'from_current_term':
                unset($shortcode_attrs['albums']);
                unset($shortcode_attrs['rss_feed']);
                unset($shortcode_attrs['feed']);
                unset($shortcode_attrs['audio_meta_field']);
                unset($shortcode_attrs['post_id_to_test']);
                $shortcode_attrs['category'] = 'current';
                break;
            case 'from_rss':
                unset($shortcode_attrs['albums']);
                unset($shortcode_attrs['category']);
                unset($shortcode_attrs['feed']);
                unset($shortcode_attrs['audio_meta_field']);
                unset($shortcode_attrs['post_id_to_test']);
                break;
        }
        if (isset($shortcode_attrs['show_mini_player']) && $shortcode_attrs['show_mini_player'] !== "true") {
            $shortcode_attrs['hide_timeline'] = "true";
            unset($shortcode_attrs['wave_bar_width']);
            unset($shortcode_attrs['wave_bar_gap']);
            unset($shortcode_attrs['wave_color']);
            unset($shortcode_attrs['wave_progress_color']);
        }
        if ($_POST['show_mini_player'] === "false") {
            $shortcode_attrs['hide_timeline'] = "true";
        }
        if ($shortcode_attrs['show_track_market'] !== "true") {
            unset($shortcode_attrs['track_market_inline']);
            unset($shortcode_attrs['force_cta_dl']);
            unset($shortcode_attrs['force_cta_share']);
            unset($shortcode_attrs['force_cta_favorite']);
            unset($shortcode_attrs['force_cta_singlepost']);
        }
        if ($shortcode_attrs['tracklist_soundwave_show'] !== "true") {
            unset($shortcode_attrs['tracklist_soundwave_show']);
            unset($shortcode_attrs['tracklist_soundwave_cursor']);
            unset($shortcode_attrs['tracklist_soundwave_style']);
            unset($shortcode_attrs['tracklist_soundwave_color']);
            unset($shortcode_attrs['tracklist_soundwave_progress_color']);
            unset($shortcode_attrs['tracklist_soundwave_bar_width']);
            unset($shortcode_attrs['tracklist_soundwave_bar_gap']);
            unset($shortcode_attrs['tracklist_soundwave_line_cap']);
        }
        if ($shortcode_attrs['slider'] == "true") {
            // Assemble the slider parameters into a JSON-like string
            $slider_param = "{";
            $slider_param .= "effect:'" . (isset($shortcode_attrs['effect']) ? $shortcode_attrs['effect'] : 'coverflow') . "',";
            $slider_param .= "slidesPerView:" . (isset($shortcode_attrs['slidesPerView']) ? $shortcode_attrs['slidesPerView'] : 3) . ",";
            $slider_param .= "loop:" . (isset($shortcode_attrs['loop']) ? ($shortcode_attrs['loop'] == 'true' ? 'true' : 'false') : 'true') . ",";
            $slider_param .= "spaceBetween:" . (isset($shortcode_attrs['spaceBetween']) ? $shortcode_attrs['spaceBetween'] : 5) . ",";
            $slider_param .= "coverflowEffect:{" .  $shortcode_attrs['coverflowEffect'] . "},";
            $slider_param .= "navigation:" . (isset($shortcode_attrs['navigation']) ? ($shortcode_attrs['navigation'] == 'true' ? 'true' : 'false') : 'true') . ",";
            $slider_param .= "centeredSlides:" . (isset($shortcode_attrs['centeredSlides']) ? ($shortcode_attrs['centeredSlides'] == 'true' ? 'true' : 'false') : 'true');
            $slider_param .= "}";
        
            $shortcode_attrs['slider_param'] = $slider_param;
        }else{
            unset($shortcode_attrs['slider']);
            unset($shortcode_attrs['slide_source']);
            unset($shortcode_attrs['slider_play_on_hover']);
        }
        if ($shortcode_attrs['spectro_enabled'] == "true") {
            $sharpFx_value = ($shortcode_attrs['spectro_sharpFx'] == "true") ? "yes" : "";
            //$spectro_param = "spectro=\"";
            $spectro_param = "color1:" . $shortcode_attrs['spectro_color1'] . "|";
            $spectro_param .= "color2:" . $shortcode_attrs['spectro_color2'] . "|";
            $spectro_param .= "shadow:" . $shortcode_attrs['spectro_shadow'] . "|";
            $spectro_param .= "reflectFx:" . $shortcode_attrs['spectro_reflectFx'] . "|";
            $spectro_param .= "sharpFx:" . $sharpFx_value . "|";
            $spectro_param .= "barCount:" . $shortcode_attrs['spectro_barCount'] . "|";
            $spectro_param .= "barWidth:" . $shortcode_attrs['spectro_barWidth'] . "|";
            $spectro_param .= "barGap:" . $shortcode_attrs['spectro_barGap'] . "|";
            $spectro_param .= "blockGap:" . $shortcode_attrs['spectro_blockGap'] . "|";
            $spectro_param .= "blockHeight:" . $shortcode_attrs['spectro_blockHeight'] . "|";
            $spectro_param .= "canvasHeight:" . $shortcode_attrs['spectro_canvasHeight'] . "|";
            $spectro_param .= "halign:" . $shortcode_attrs['spectro_halign'] . "|";
            $spectro_param .= "valign:" . $shortcode_attrs['spectro_valign'] . "|";
            $spectro_param .= "enableOnTracklist:" . $shortcode_attrs['spectro_enableOnTracklist'] . "|";
            $spectro_param .= "bounceClass:" . $shortcode_attrs['spectro_bounceClass'] . "|";
            $spectro_param .= "bounceVibrance:" . $shortcode_attrs['spectro_bounceVibrance'] . "|";
            $spectro_param .= "bounceBlur:" . $shortcode_attrs['spectro_bounceBlur'] . "|";
            $spectro_param .= "spectroStyle:" . $shortcode_attrs['spectroStyle'] . "|";
            $spectro_param .= ""; // Closing the parameter string
    
            $shortcode_attrs['spectro'] = $spectro_param;

        }
     
        if($shortcode_attrs['scbuilder_show_playlist']){
            // special condition because we cannot use show_playlist since its already taken by another attribute.
            $shortcode_attrs['show_playlist'] = $shortcode_attrs['scbuilder_show_playlist'];
            unset($shortcode_attrs['scbuilder_show_playlist']);
        }

        if($shortcode_attrs['show_playlist'] === 'true' && $shortcode_attrs['tracklist_layout'] !== 'grid'){
                unset($shortcode_attrs['grid_column_number']);
        }
        if($shortcode_attrs['show_playlist'] !== 'true'){
            unset($shortcode_attrs['grid_column_number']);
            unset($shortcode_attrs['tracklist_layout']);
            unset($shortcode_attrs['lazy_load']);
            unset($shortcode_attrs['order']);
            unset($shortcode_attrs['orderby']);
            unset($shortcode_attrs['searchbar']);
            unset($shortcode_attrs['scrollbar']);
            unset($shortcode_attrs['tracklist_soundwave_show']);
            unset($shortcode_attrs['tracklist_soundwave_cursor']);
            unset($shortcode_attrs['tracklist_soundwave_style']);
            unset($shortcode_attrs['tracklist_soundwave_color']);
            unset($shortcode_attrs['tracklist_soundwave_progress_color']);
            unset($shortcode_attrs['tracklist_soundwave_bar_width']);
            unset($shortcode_attrs['tracklist_soundwave_bar_gap']);
            unset($shortcode_attrs['tracklist_soundwave_line_cap']);
        }
        if($shortcode_attrs['show_album_market'] !== 'true'){
            unset($shortcode_attrs['store_title_text']);
        }
        if(isset($shortcode_attrs['lazy_load']) && $shortcode_attrs['lazy_load'] !== 'true'){
            unset($shortcode_attrs['lazy_load']);
        }else{
           
            unset($shortcode_attrs['posts_per_page']);
        }
        if($shortcode_attrs['pagination'] === 'true'){
            if(empty($shortcode_attrs['tracks_per_page'])){
                //set default tracks per page if pagination is true but tracks per page is not set
                $shortcode_attrs['tracks_per_page'] = '10';
            }
        }
        $tempId = '';
        if(!empty($shortcode_attrs['post_id_to_test'])){
            // temporary set an album ID for the player to render when using current_post.
            $shortcode_attrs['albums'] = $shortcode_attrs['post_id_to_test'];
            $tempId = $shortcode_attrs['post_id_to_test'];
        }

        if(!$shortcode_attrs['adaptive_colors']){
            unset($shortcode_attrs['adaptive_colors_freeze']);
            
        }

        if( isset($_POST['save_template_name']) && !empty($_POST['save_template_name']) ){

            $shortcodeTemplate = sanitize_text_field($_POST['save_template_name']);
            $shortcodeTemplate = preg_replace('/\s+/', '-', $shortcodeTemplate);
            $shortcodeTemplate = preg_replace('/-+/', '-', $shortcodeTemplate);
            $shortcodeTemplate = strtolower($shortcodeTemplate);
            $shortcodeTemplate = $shortcodeTemplate;
            $shortcode_attrs['class'] = 'sr-tmpl-' . $shortcodeTemplate;
        }
        if(isset($shortcode_attrs['player_class']) && !empty($shortcode_attrs['player_class'])){
            $shortcode_attrs['class'] = $shortcode_attrs['class'] . ' ' . sanitize_text_field($shortcode_attrs['player_class']);
        }
        
        unset($shortcode_attrs['show_mini_player']);
        unset($shortcode_attrs['source']);
        unset($shortcode_attrs['spectro_enabled']);
        unset($shortcode_attrs['effect']);
        unset($shortcode_attrs['slidesPerView']);
        unset($shortcode_attrs['coverflowEffect']);
        unset($shortcode_attrs['loop']);
        unset($shortcode_attrs['spaceBetween']);
        unset($shortcode_attrs['navigation']);
        unset($shortcode_attrs['centeredSlides']);
        unset($shortcode_attrs['spectroStyle']);
        unset($shortcode_attrs['spectro_color1']);
        unset($shortcode_attrs['spectro_color2']);
        unset($shortcode_attrs['spectro_shadow']);
        unset($shortcode_attrs['spectro_reflectFx']);
        unset($shortcode_attrs['spectro_sharpFx']);
        unset($shortcode_attrs['spectro_barCount']);
        unset($shortcode_attrs['spectro_barWidth']);
        unset($shortcode_attrs['spectro_barGap']);
        unset($shortcode_attrs['spectro_blockGap']);
        unset($shortcode_attrs['spectro_blockHeight']);
        unset($shortcode_attrs['spectro_canvasHeight']);
        unset($shortcode_attrs['spectro_halign']);
        unset($shortcode_attrs['spectro_valign']);
        unset($shortcode_attrs['spectro_enableOnTracklist']);
        unset($shortcode_attrs['spectro_bounceClass']);
        unset($shortcode_attrs['spectro_bounceVibrance']);
        unset($shortcode_attrs['spectro_bounceBlur']);
        unset($shortcode_attrs['post_id_to_test']);
        unset($shortcode_attrs['player_class']);

        $shortcode_attrs['css'] = $final_css;

        $shortcode = '[sonaar_audioplayer';
        foreach ($shortcode_attrs as $key => $attr) {
            if (is_array($attr)) {
                // Handle nested arrays for group attributes
                foreach ($attr as $index => $groupFields) {
                    foreach ($groupFields as $groupKey => $groupValue) {
                        $groupAttributeName = $key . '_' . $groupKey . '[' . $index . ']'; // Format for grouped attribute
                        if (!empty($groupValue) || $groupValue === 'false') {
                            // Check if group attribute should be included even if 'false'
                            $shortcode .= ' ' . $groupAttributeName . '="' . esc_attr($groupValue) . '"';
                        }
                    }
                }
            } else {
                // Regular attribute handling
                if (!empty($attr) || $attr === 'false') {
                    if ($attr === 'false') {
                        // Handle special false cases
                        if (strpos($key, 'force_cta_') !== false ||
                            strpos($key, 'cta_track_show_label') !== false ||
                            strpos($key, 'hide_track_number') !== false ||
                            strpos($key, 'show_skip_bt') !== false ||
                            strpos($key, 'show_shuffle_bt') !== false ||
                            strpos($key, 'show_repeat_bt') !== false ||
                            strpos($key, 'show_speed_bt') !== false ||
                            strpos($key, 'show_volume_bt') !== false ||
                            strpos($key, 'show_miniplayer_note_bt') !== false ||
                            strpos($key, 'show_track_publish_date') !== false
                            ) {
                            $shortcode .= ' ' . $key . '="false"';
                        }
                    } else {
                        // Include non-empty attributes
                        $shortcode .= ' ' . $key . '="' . esc_attr($attr) . '"';
                    }
                }
            }
        }
      
        $shortcode .= '][/sonaar_audioplayer]';
    
        $rendered_html = do_shortcode($shortcode);

        if(!empty($tempId)){
            //prevent to add albums to the shortcode markup with the test ID Preview
            $shortcode = str_replace('albums="' . $tempId . '"', '', $shortcode);
        }

        $response = [
            'html' => $rendered_html,
            'shortcode' => $shortcode
        ];
    
        // Send JSON response
        wp_send_json_success($response);
        wp_die();
    }


    public function srmp3_register_shortcodebuilder_options(){
        /**
         * Register SHORTCODE BUILDER options
         */
        $options_name = array();
        $args = array(
            'id'           => 'srmp3_settings_shortcodebuilder',
            'menu_title'   => esc_html__( 'Shortcode Player Builder', 'sonaar-music' ),
            'title'        => esc_html__( 'Shortcode Builder', 'sonaar-music' ),
            'object_types' => array( 'options-page' ),
            'option_key'   => 'srmp3_settings_shortcodebuilder', // The option key and admin menu page slug. 'yourprefix_tertiary_options',
            'parent_slug'  => 'edit.php?post_type=' . SR_PLAYLIST_CPT, // Make options page a submenu item of the themes menu,
            'tab_group'    => 'yourprefix_main_options',
            'tab_title'    => esc_html__( 'Shortcode Player Builder', 'sonaar-music' ),
        );

        // 'tab_group' property is supported in > 2.4.0.
        if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
            $args['display_cb'] = 'yourprefix_options_display_with_tabs';
        }

        $shortcode_options = new_cmb2_box( $args );
        array_push($options_name, $shortcode_options);
        
    
        $shortcode_options->add_field( array(
            'classes'       => 'admin-player-preview',
            'name'          => esc_html__('Player HTML', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_preview_markup',
            'render_row_cb' => 'shortcodebuilder_render',
        ) );
    

        //check if we are in the ?page=sonaar-music-settings page
        if ( isset( $_GET['page'] ) && $_GET['page'] == 'srmp3_settings_shortcodebuilder' ) {
            wp_enqueue_style( 'srp-swiper-style' );
            wp_enqueue_style( 'sonaar-music' );
            wp_enqueue_script( 'srp-swiper' );
            wp_enqueue_script( 'color-thief' );
            wp_enqueue_script( 'sonaar-music-mp3player' );
            wp_enqueue_script( 'sonaar-music-scrollbar' );

            if(function_exists('run_sonaar_music_pro')){
                wp_enqueue_style( 'sonaar-music-pro' );
                wp_enqueue_script( 'sonaar-list' );
                wp_enqueue_script( 'sonaar-music-pro-mp3player' );
                wp_enqueue_script( 'sonaar_player' );
            }
            if ( function_exists('sonaar_player') ) {
                add_action('admin_footer','sonaar_player', 12); //this make the sticky player works with IRON.sonaar.player.setPlayerAndPlay({ id:"3240", trackid:"0"});
            }
        }else{
            //return if we are not in the page builder.
            return;
        }
      

        /**
        *  TEMPLATES
        */
        $shortcode_options->add_field(array(
            'name' => esc_html__('Save as Template', 'sonaar-music'),
            'desc' => '<div class="description">Use only letters, numbers and spaces. No special characters allowed.</div>
                <div id="shortcode_preloader_templates" style="display: none;">
                    <div class="shortcode_spinner"></div>
                </div>
                <div id="shortcode-import-from-library-bt" class="srmp3-bt-shortcode-ui">
                <span class="dashicons dashicons-format-gallery"></span><a href="' . admin_url( 'edit.php?post_type=' . SR_PLAYLIST_CPT . '&page=srmp3-import-shortcode-templates' ) . '" target="_self">
                '  . esc_html__('Browse Library', 'sonaar-music') . '</a></div>
                <div id="shortcode-import-open-textarea-bt" class="srmp3-bt-shortcode-ui">
                    <span class="dashicons dashicons-download"></span>
                    '  . esc_html__('Import', 'sonaar-music') . '</div>
                <div id="shortcode-export-template-bt" class="srmp3-bt-shortcode-ui">
                    <span class="dashicons dashicons-upload"></span>
                    <span class="export-text">'  . esc_html__('Export', 'sonaar-music'). '</span>
                </div>
                <div id="shortcode-delete-template-bt" class="srmp3-bt-shortcode-ui">
                    <span class="dashicons dashicons-trash"></span>
                    '  .  esc_html__('Delete template', 'sonaar-music'). '
                </div>
        
                <div id="shortcode-import-container" style="display:none;">
                    <textarea id="shortcode-import-textarea" placeholder="' . esc_html__('Paste your template to import here', 'sonaar-music') . '"></textarea><br>
                    <button id="shortcode-import-template-bt">' . esc_html__('Import', 'sonaar-music') . '</button>
                </div>',
            'id'   => 'save_template_name',
            'type' => 'text_medium',
            'attributes' => array(
                'placeholder' => 'Add Template Name...',
            ),
            'classes_to_hook' => 'prolabel--nohide',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        =>  array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ));
        $templates = get_option('srmp3_shortcode_templates', []);
        $template_options = ['' => 'Load a template...'];
        foreach ($templates as $key => $value) {
            $template_options[$key] = $key;
        }

        $shortcode_options->add_field(array(
            'name' => esc_html__('Load Template', 'sonaar-music'),
            'id'   => 'load_template',
            'type' => 'select',
            'options' => $template_options,
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ));
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Preview Page Background', 'sonaar-music'),
            'id'            => 'preview_bg_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                //'data-target-selector' => '.control div, .control i, .srp-play-button .sricon-play, .srp_noteButton{color:{{VALUE}}; } .control .play, .srp-play-circle, .sr_speedRate div{ border-color:{{VALUE}}; }',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ) );
        $shortcode_options->add_field( array(
            'before_row'    => '<br>',
            'name'          => esc_html__('Show Player Widget', 'sonaar-music'),
            'id'            => 'show_mini_player',
            'type'          => 'switch',
            'default'       => 'true',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Tracklist', 'sonaar-music'),
            'id'            => 'scbuilder_show_playlist',
            'type'          => 'switch',
            'default'       => 'true',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Slider Carousel', 'sonaar-music'),
            'id'            => 'slider',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Enable Sticky Footer Player', 'sonaar-music'),
            'id'            => 'sticky_player',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Remember Track Progress', 'sonaar-music'),
            'id'            => 'track_memory',
            'type'          => 'select',
            'options' 		=> [
                'default'           => __( 'Default', 'sonaar-music' ),
                'true' 		        => __( 'Yes', 'sonaar-music' ),
                'false' 			=> __( 'No', 'sonaar-music' ),
            ],
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        /**
         * SOURCES
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Audio Source', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_source_title',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Source', 'sonaar-music'),
            'id'            => 'source',
            'type'          => 'select',
            'options' 			=> sr_plugin_playlist_source(true),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__( 'Select Playlist', 'sonaar-music'),
            'id'            => 'albums',
            'type'          => 'post_search_text', // This field type
            'post_type'     => Sonaar_Music_Admin::get_cpt($all = true),
            'desc'          => sprintf(__('Enter a comma separated list of playlist IDs. Enter <i>latest</i> to always load the latest published %1$s playlist. Click Select Button to search for playlist','sonaar-music'), Sonaar_Music_Admin::sr_GetString('playlist') ),
            'select_type'   => 'checkbox',
            'select_behavior' => 'add',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_cpt' ) ),
                
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Select Categories', 'sonaar-music'),
            'id'            => 'category',
            'type'          => 'multicheck',
            'options'       => srp_elementor_select_category(),
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_cat' ) ), 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            
        ) );
        /**
         *  CURRENT POST
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Preview Post ID', 'sonaar-music'),
            'id'            => 'post_id_to_test',
            'type'          => 'post_search_text', // This field type
            'post_type'     => Sonaar_Music_Admin::get_cpt($all = true),
            'desc'          => sprintf(__('Enter a comma separated list of playlist IDs. Enter <i>latest</i> to always load the latest published %1$s playlist. Click Select Button to search for playlist','sonaar-music'), Sonaar_Music_Admin::sr_GetString('playlist') ),
            'select_type'   => 'checkbox',
            'select_behavior' => 'add',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_current_post' ) ),
                
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('(Optional) Custom Field Metakey ID of the audio URL', 'sonaar-music'),
            'id'            => 'audio_meta_field',
            'type'          => 'text',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_current_post' ) ), 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('(Optional) Repeater Group Metakey ID', 'sonaar-music'),
            'id'            => 'repeater_meta_field',
            'type'          => 'text',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_current_post' ) ), 
            ),
        ) );
        /**
         *  FEED
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Audio URLs', 'sonaar-music'),
            'desc'          => esc_html__('Set audio URLs delimited by || characters. Eg: https://yourdomain.com/01.mp3 || https://yourdomain.com/02.mp3', 'sonaar-music'),
            'id'            => 'feed',
            'type'          => 'text',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_feed' ) ), 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Track Titles', 'sonaar-music'),
            'desc'          => esc_html__('Set track titles delimited by || character. Eg: Title 01 || Title 02', 'sonaar-music'),
            'id'            => 'feed_title',
            'type'          => 'text',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_feed' ) ), 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Track Image URLs', 'sonaar-music'),
            'desc'          => esc_html__('Set image URLs delimited by || character. Eg: https://yourdomain.com/img1.jpg || https://yourdomain.com/img2.jpg', 'sonaar-music'),
            'id'            => 'feed_img',
            'type'          => 'text',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_feed' ) ), 
            ),
        ) );
        /**
         *  RSS
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('RSS Feed', 'sonaar-music'),
            'id'            => 'rss_feed',
            'type'          => 'text_medium',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_rss' ) ), 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Fetch only the following episode names', 'sonaar-music'),
            'id'            => 'rss_item_title',
            'type'          => 'text_medium',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_rss' ) ), 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Max Number of Episodes', 'sonaar-music'),
            'id'            => 'rss_items',
            'type'          => 'text_small',
            'attributes'  => array(
                'data-conditional-id'    => 'source',
                'data-conditional-value' => wp_json_encode( array( 'from_rss' ) ), 
            ),
        ) );
        // orderby can be: “ID”, “author”, “title”, “name”, “date”, “modified”, “menu_order”, “srmp3_track_length”, “_price”, “_sale_price”, “_sku”, “total_sales”, “_wc_average_rating”, “_stock_status”.

    

        /**
         *  GENERAL LAYOUT
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Player Settings', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_layout_title',
        ) );
        $shortcode_options->add_field( array(
            'name' => esc_html__('Player Layout', 'sonaar-music'),
            'id'   =>   'player_layout',
            'type' => 'image_select',
            'width' => '100%',
            'options' => array(
                'skin_boxed_tracklist' => array('title' => 'Boxed', 'alt' => 'Boxed', 'img' => plugin_dir_url( __FILE__ ) . '../../img/player_type_boxed.svg'),
                'skin_float_tracklist' => array('title' => 'Floated', 'alt' => 'Floated', 'img' => plugin_dir_url( __FILE__ ) . '../../img/player_type_floated.svg'),
            ),
            'default' => 'skin_boxed_tracklist',
        ));

        $shortcode_options->add_field( array(
            'name'          => esc_html__('Player Radius', 'sonaar-music'),
            'id'            => 'player_radius',
            'type'       	=> 'text_small',
            'attributes'  => array(
                'data-target-selector' => '.iron-audioplayer{ border-radius:{{VALUE}}; overflow:hidden; }',
                'data-target-unit'     => 'px',
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Adaptive Color', 'sonaar-music'),
            'id'            => 'adaptive_colors',
            'type'          => 'select',
            'options' 						=> [
                '' 					        => __( 'Inactive', 'sonaar-music' ),
                '1' 						=> __( 'Color Match 01', 'sonaar-music' ),
                '2' 						=> __( 'Color Match 02', 'sonaar-music' ),
                '3' 						=> __( 'Color Match 03', 'sonaar-music' ),
                '4' 						=> __( 'Color Match 04', 'sonaar-music' ),
                'random' 					=> __( 'Randomize Everytime', 'sonaar-music' ),
            ],
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Freeze Adaptive Color', 'sonaar-music'),
            'id'            => 'adaptive_colors_freeze',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'adaptive_colors',
                'data-conditional-value' => wp_json_encode( array( '1,2,3,4,random' ) ), 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Shuffle On Next', 'sonaar-music'),
            'id'            => 'shuffle',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add notrackskip
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Stop when track ends', 'sonaar-music'),
            'id'            => 'notrackskip',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add no_loop_tracklist
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Stop when tracklist ends', 'sonaar-music'),
            'id'            => 'no_loop_tracklist',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
    
        $shortcode_options->add_field( array(
            'name' => esc_html__('Order by', 'sonaar-music'),
            'desc' => __('Works only if you have 1 track per "post"', 'sonaar-music'),
            'id'   =>   'orderby',
            'type' => 'select',
            'options' => array(
                'date' => esc_html__('Date', 'sonaar-music'),
                'ID' => esc_html__('ID', 'sonaar-music'),
                'author' => esc_html__('Author', 'sonaar-music'),
                'title' => esc_html__('Title', 'sonaar-music'),
                'name' => esc_html__('Name', 'sonaar-music'),
                'modified' => esc_html__('Modified', 'sonaar-music'),
                'menu_order' => esc_html__('Menu Order', 'sonaar-music'),
                'srmp3_track_length' => esc_html__('Track Length', 'sonaar-music'),
                '_price' => esc_html__('Price', 'sonaar-music'),
                '_sale_price' => esc_html__('Sale Price', 'sonaar-music'),
                '_sku' => esc_html__('SKU', 'sonaar-music'),
                'total_sales' => esc_html__('Total Sales', 'sonaar-music'),
                '_wc_average_rating' => esc_html__('Average Rating', 'sonaar-music'),
                '_stock_status' => esc_html__('Stock Status', 'sonaar-music'),
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ));
        $shortcode_options->add_field( array(
            'name' => esc_html__('Order', 'sonaar-music'),
            'desc' => __('Works only if you have 1 track per "post"', 'sonaar-music'),
            'id'   =>   'order',
            'type' => 'select',
            'options' => array(
                'desc' => esc_html__('Desc', 'sonaar-music'),
                'asc' => esc_html__('Asc', 'sonaar-music'),
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ));
       


       



        
    

        /**
         *  Artwork
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Player Widget: Image Cover', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_artwork_title',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'OR', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_float_tracklist'
                        ),
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Artwork', 'sonaar-music'),
            'id'            => 'hide_artwork',
            'type'          => 'switch',
            
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Image Size (px)', 'sonaar-music'),
            'id'            => 'artwork_size',
            'type'       	=> 'text_small',
            'default'       => '',
            'attributes'  => array(
                'data-target-unit'     => 'px',
                'data-target-selector' => ':not(.sonaar-no-artwork) .srp_player_grid{ grid-template-columns: {{VALUE}} 1fr; } .album-art{ width:{{VALUE}}; max-width: {{VALUE}}px; } .srp_player_boxed .sonaar-Artwort-box{ min-width: {{VALUE}}; }',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Display play button in the artwork', 'sonaar-music'),
            'id'            => 'display_control_artwork',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Control Buttons when Hover', 'sonaar-music'),
            'id'            => 'show_control_on_hover',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'display_control_artwork',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
        ) );
       
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Display Artwork Image in Background', 'sonaar-music'),
            'id'            => 'artwork_background',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Background Image Size', 'sonaar-music'),
            'id'            => 'artwork_background_size',
            'type'       	=> 'text_small',
            'default'       => '100',
            'attributes'  => array(
                'data-target-unit'     => '%',
                'data-target-selector' => '.iron-audioplayer .srp-artworkbg{ background-size: {{VALUE}};}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'artwork_background',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );

        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Cover Image in Front', 'sonaar-music'),
            'id'            => 'artwork_set_background_hideMainImage',
            'type'          => 'switch',
            'attributes'  => array(
                'data-target-selector' => '.iron-audioplayer .sonaar-Artwort-box{display:none;},  .iron-audioplayer .srp_player_boxed{display:block;}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'artwork_background',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Blur Image (px)', 'sonaar-music'),
            'id'            => 'artwork_background_blur',
            'type'       	=> 'text_small',
            'default'       => '',
            'attributes'  => array(
                'data-target-unit'     => 'px',
                'data-target-selector' => '.iron-audioplayer .srp-artworkbg{ filter: blur({{VALUE}});}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'artwork_background',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );

        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Blur Overflow', 'sonaar-music'),
            'id'            => 'artwork_set_background_overflow',
            'type'          => 'switch',
            'attributes'  => array(
                'data-target-selector' => '.iron-audioplayer {overflow:hidden;},  .iron-audioplayer .srp_player_boxed{display:block;}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'artwork_background',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Background Position', 'sonaar-music'),
            'id'            => 'artwork_background_pos',
            'type'              => 'select',
            'show_option_none'  => false,
            'options'           => array(
                '' => esc_html_x( 'Default', 'Background Control', 'sonaar-music' ),
                'center center' => esc_html_x( 'Center Center', 'Background Control', 'sonaar-music' ),
                'center left' => esc_html_x( 'Center Left', 'Background Control', 'sonaar-music' ),
                'center right' => esc_html_x( 'Center Right', 'Background Control', 'sonaar-music' ),
                'top center' => esc_html_x( 'Top Center', 'Background Control', 'sonaar-music' ),
                'top left' => esc_html_x( 'Top Left', 'Background Control', 'sonaar-music' ),
                'top right' => esc_html_x( 'Top Right', 'Background Control', 'sonaar-music' ),
                'bottom center' => esc_html_x( 'Bottom Center', 'Background Control', 'sonaar-music' ),
                'bottom left' => esc_html_x( 'Bottom Left', 'Background Control', 'sonaar-music' ),
                'bottom right' => esc_html_x( 'Bottom Right', 'Background Control', 'sonaar-music' ),
                'initial' => esc_html_x( 'Custom', 'Background Control', 'sonaar-music' ),
            ),
            'default'           => '',
            'attributes'  => array(
                'data-target-selector' => '.iron-audioplayer .srp-artworkbg{ background-position:{{VALUE}};}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'artwork_background',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );

        $shortcode_options->add_field( array(
            'name'          => esc_html__('X Position', 'sonaar-music'),
            'id'            => 'artwork_background_posx',
            'type'       	=> 'text_small',
            'default'       => '',
            'attributes'  => array(
                'data-target-unit'     => 'px',
                'data-target-selector' => '.iron-audioplayer .srp-artworkbg{ background-position-x: {{VALUE}};}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'artwork_background',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'artwork_background_pos',
                            'value' => 'initial'
                        ),
                    ),
                )),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Y Position', 'sonaar-music'),
            'id'            => 'artwork_background_posy',
            'type'       	=> 'text_small',
            'default'       => '',
            'attributes'  => array(
                'data-target-unit'     => 'px',
                'data-target-selector' => '.iron-audioplayer .srp-artworkbg{ background-position-y: {{VALUE}};}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'artwork_background',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'artwork_background_pos',
                            'value' => 'initial'
                        ),
                    ),
                )),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );

        /**
         *  META
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Player Widget: Titles & Metas', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_meta_title',
            'attributes'  => array(
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Titles & Metas', 'sonaar-music'),
            'id'            => 'hide_metas',
            'type'          => 'switch',
        ) );
        $shortcode_options->add_field( array(
            'id'            => 'css_player_metas',
            'type'          => 'typography',
            'name'          => esc_html__('Heading Style', 'sonaar-music'),
            'fields'        => array(
                'font-family'        => false,
                'font-weight'        => false,
                'background' 		=> false,
                //'text-align' 		=> false,
                
            ),
            'attributes'  => array(
                'data-target-selector' => '.srp_meta, .srp_meta.album-title',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_metas',
                            'value' => 'false'
                        ),
                    
                    ),
                )),
            ),
        ) );
        $player_meta_group = $shortcode_options->add_field( array(
            'id'            => 'player_meta_group',         
            'type'          => 'group',
            'repeatable'    => true, // use false if you want non-repeatable group
            'options'       => array(
                'group_title'   => esc_html__( 'Heading', 'sonaar-music' ),
                'add_button'    => esc_html__( 'Add Another Heading', 'sonaar-music' ),
                'remove_button' => esc_html__( 'Remove Heading', 'sonaar-music' ),
                'sortable'      => true, // beta
                'closed'        => true, // true to have the groups closed by default
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'hide_metas',
                'data-conditional-value' => 'false', 
            ),
        ) );
            $shortcode_options->add_group_field( $player_meta_group ,array(
                'name'              => esc_html__('Meta Type', 'sonaar-music'),
                'id'                => 'player_metas',
                'type'              => 'select',
                'show_option_none'  => false,
                'options'           => Sonaar_Music_Admin::metaOptions(),
            ));
            $shortcode_options->add_group_field( $player_meta_group ,array(
                'name'              => esc_html__('Custom Heading', 'sonaar-music'),
                'id'                => 'player_metas_customheading',
                'type'              => 'text_medium',
                'attributes'    => array(
                    'data-conditional-id'    => wp_json_encode( array( $player_meta_group, 'player_metas' )),
                    'data-conditional-value' => 'custom_heading',
                )
            ));
            $shortcode_options->add_group_field( $player_meta_group ,array(
                'name'              => esc_html__('ACF Field ID', 'sonaar-music'),
                'id'                => 'player_metas_acf_field',
                'type'              => 'text_medium',
                'attributes'    => array(
                    'data-conditional-id'    => wp_json_encode( array( $player_meta_group, 'player_metas' )),
                    'data-conditional-value' => 'acf_field',
                )
            ));
            $shortcode_options->add_group_field( $player_meta_group ,array(
                'name'              => esc_html__('Post/Track Meta', 'sonaar-music'),
                'id'                => 'player_metas_metakey',
                'type'              => 'text_medium',
                'attributes'    => array(
                    'data-conditional-id'    => wp_json_encode( array( $player_meta_group, 'player_metas' )),
                    'data-conditional-value' => 'key',
                )
            ));
            $shortcode_options->add_group_field( $player_meta_group ,array(
                'name'              => esc_html__('Prefix Label', 'sonaar-music'),
                'id'                => 'player_metas_prefix',
                'type'              => 'text_small',
                'attributes'    => array(
                    'data-conditional-id'    => wp_json_encode( array( $player_meta_group, 'player_metas' )),
                    'data-conditional-value' => wp_json_encode( array( 'playlist_title' , 'track_title' , 'artist_name', 'description', 'duration', 'categories', 'tags', 'podcast_show', 'acf_field', 'key' ) ),
                )
            ));
            $shortcode_options->add_group_field( $player_meta_group ,array(
                'name'          => esc_html__('Display Inline', 'sonaar-music'),
                'id'            => 'player_metas_inline',
                'type'          => 'checkbox',
                'attributes'  => array(
                    'data-target-selector' => '.srp_miniplayer_metas .srp_meta_{#} { display:inline-block; margin-right: 0.3em; }',
                ),
            ) );
            $shortcode_options->add_group_field( $player_meta_group ,array(
                'id'            => 'css_player_meta_font_group',
                'type'          => 'typography',
                'name'          => esc_html__('Heading Style', 'sonaar-music'),
                'attributes'  => array(
                    'data-target-selector' => '.srp_miniplayer_metas .srp_meta_{#}, .srp_miniplayer_metas .srp_meta.album-title.srp_meta_{#}',
                ),
                'fields'        => array(
                    'font-family'       => false,
                    'font-weight' 		=> false,
                    'background' 		=> false,
                    'text-align' 		=> false,
                    //'text-transform' 	=> false,
                    
                )
            ) );
            $shortcode_options->add_group_field( $player_meta_group ,array(
                'name'              => esc_html__('HTML Tag', 'sonaar-music'),
                'id'                => 'player_metas_htmltag',
                'type'              => 'select',
                'show_option_none'  => false,
                'options'           => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p'  => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ),
                'default'           => 'div',
            ));
    

            $shortcode_options->add_field( array(
                'name'          => esc_html__('Show Meta Duration', 'sonaar-music'),
                'id'            => 'show_meta_duration',
                'type'          => 'switch',
                'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
                'plan_required' => 'starter',
                'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            ) );
        
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Show Publish Date', 'sonaar-music'),
                'id'            => 'show_publish_date',
                'type'          => 'switch',
                'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
                'plan_required' => 'starter',
                'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            ) );
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Show Tracks Count', 'sonaar-music'),
                'id'            => 'show_tracks_count',
                'type'          => 'switch',
                'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
                'plan_required' => 'starter',
                'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            ) );
            $shortcode_options->add_field( array(
                'id'            => 'css_extra_meta',
                'type'          => 'typography',
                'name'          => esc_html__('Duration, Publish Data and Track Counts Typography', 'sonaar-music'),
                'fields'        => array(
                // 'selector'          => '.srp_miniplayer_metas, .srp_miniplayer_metas .srp_meta.album-title',
                    'font-family'       => false,
                    'font-weight'       => false,
                    'background' 		=> false,
                    'text-align' 		=> false,
                    'text-transform' 	=> false,
                    'line-height'       => false,
                    
                ),
                'attributes'  => array(
                    'data-target-selector' => '.sr_it-playlist-publish-date, .srp_trackCount, .srp_playlist_duration',
                    'data-conditional' => wp_json_encode(array(
                        'logic' => 'OR', // Could be 'OR'
                        'conditions' => array(
                            array(
                                'id'    => 'show_meta_duration',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'show_publish_date',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'show_tracks_count',
                                'value' => 'true'
                            ),
                        ),
                    ),
                ),
                ),
            ) );

        /**
         *  Mini Player
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Player Widget: Layout & Progress Bar', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_mini_title',
            'attributes'  => array(
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Controls', 'sonaar-music'),
            'id'            => 'hide_control_under',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_float_tracklist'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Player Control Color', 'sonaar-music'),
            'id'            => 'control_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.control div, .control i, .srp-play-button .sricon-play, .album-player .srp_noteButton{color:{{VALUE}}; } .control .play, .srp-play-circle, .sr_speedRate div{ border-color:{{VALUE}}; }',
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ) );
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Player Background', 'sonaar-music'),
            'id'      => 'player_background_color',
            'type'    => 'colorpicker',
            'default' => '',
            'options'       => array(
                'alpha'         => true,
            ),
            'attributes'  => array(
                'data-target-selector' => '.srp_player_boxed, .skin_floated .album-player, .srp-artworkbg{background-color:{{VALUE}};}',
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ));
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Player Padding', 'sonaar-music'),
            'id'            => 'player_padding',
            'desc'          => '<br>' . esc_html__('Eg: 10px 10px 10px 10px', 'sonaar-music'),
            'type'          => 'text_medium',
            'attributes'  => array(
                'data-target-selector' => '.srp_player_boxed{ padding: {{VALUE}}; } .skin_floated .album-player{ padding: {{VALUE}}; }',
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Progress Bar', 'sonaar-music'),
            'id'            => 'hide_progressbar',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Style', 'sonaar-music'),
            'id'            => 'progress_bar_style',
            'type'          => 'select',
            'options' 		=> [
                'default'               => __( 'Default', 'sonaar-music' ),
                'mediaElement' 		    => __( 'Waveform', 'sonaar-music' ),
                'simplebar' 			=> __( 'Simple Bar', 'sonaar-music' ),
            ],
            'default'    => 'default',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_progressbar',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Wave Progress Color', 'sonaar-music'),
            'id'            => 'wave_progress_color',
            'classes'       => 'srmp3-settings--subitem',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_progressbar',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Wave Color', 'sonaar-music'),
            'id'            => 'wave_color',
            'classes'       => 'srmp3-settings--subitem',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_progressbar',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Wave Bar Width', 'sonaar-music'),
            'id'            => 'wave_bar_width',
            'classes'       => 'srmp3-settings--subitem',
            'type'       	=> 'own_slider',
            'min'           => '0.25',
            'max'           => '10',
            'step'          => '0.25',
            'default'       => '1',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes'    => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_progressbar',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Wave Bar Gap', 'sonaar-music'),
            'id'            => 'wave_bar_gap',
            'classes'       => 'srmp3-settings--subitem',
            'type'       	=> 'own_slider',
            'min'           => '0.25',
            'max'           => '10',
            'step'          => '0.25',
            'default'       => '1',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_progressbar',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Wave Bar Height', 'sonaar-music'),
            'id'            => 'wave_bar_height',
            'classes'       => 'srmp3-settings--subitem',
            'type'       	=> 'text_small',
            'attributes'  => array(
                'data-target-selector' => '.sr_progressbar .sonaar_fake_wave, .sr_progressbar .sonaar_wave_base, .sr_progressbar .sonaar_wave_cut{ height:{{VALUE}}!important; }',
                'data-target-unit'     => 'px',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_progressbar',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
        ) );
    
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Times', 'sonaar-music'),
            'id'            => 'hide_times',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_progressbar',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'id'            => 'hide_times_typo',
            'type'          => 'typography',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'name'          => esc_html__('Times Duration Style', 'sonaar-music'),
            'fields'        => array(
                'font-family'        => false,
                'line-height'        => false,
                'font-weight'        => false,
                'background' 		=> false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
                
            ),
            'attributes'  => array(
                'data-target-selector' => '.sr_progressbar',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_progressbar',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Play/Pause Size (px)', 'sonaar-music'),
            'id'            => 'playpause_size',
            'type'       	=> 'text_small',
            'attributes'  => array(
                'data-target-selector' => '.album-player .sricon-play{ font-size:{{VALUE}}; }',
                'data-target-unit'     => 'px',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_boxed_tracklist'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Play/Pause Circle Size (px)', 'sonaar-music'),
            'id'            => 'playpause_circle_size',
            'type'       	=> 'text_small',
            'attributes'  => array(
                'data-target-selector' => '.srp-play-circle{ height:{{VALUE}}; width:{{VALUE}}; border-radius:{{VALUE}}; }',
                'data-target-unit'     => 'px',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_boxed_tracklist'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Play/Pause Circle Border Width (px)', 'sonaar-music'),
            'id'            => 'playpause_circle_border_width',
            'type'       	=> 'text_small',
            'attributes'  => array(
                'data-target-selector' => '.srp-play-circle{ border-width:{{VALUE}}; }',
                'data-target-unit'     => 'px',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_boxed_tracklist'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
        ) );
         $shortcode_options->add_field( array(
            'name'          => esc_html__('Use Play Label with Icon', 'sonaar-music'),
            'id'            => 'use_play_label_with_icon',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_boxed_tracklist'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Play Label Text', 'sonaar-music'),
            'id'            => 'play_text',
            'type'          => 'text',
            'attributes'  => array(
                'data-conditional-id'    => 'use_play_label_with_icon',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add pause_text
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Pause Label Text', 'sonaar-music'),
            'id'            => 'pause_text',
            'type'          => 'text',
            'attributes'  => array(
                'data-conditional-id'    => 'use_play_label_with_icon',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add play_bt_bg_color
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Play Button Background Color', 'sonaar-music'),
            'id'            => 'play_bt_bg_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'use_play_label_with_icon',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add play_bt_text_color
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Play Button Text Color', 'sonaar-music'),
            'id'            => 'play_bt_text_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'use_play_label_with_icon',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add hide_play_icon
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Play Icon', 'sonaar-music'),
            'id'            => 'hide_play_icon',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'use_play_label_with_icon',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        

        
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Skip Button', 'sonaar-music'),
            'id'            => 'show_skip_bt',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Shuffle Button', 'sonaar-music'),
            'id'            => 'show_shuffle_bt',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Repeat Button', 'sonaar-music'),
            'id'            => 'show_repeat_bt',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', 
                    'conditions' => array(
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'notrackskip',
                            'value' => 'false'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Speed Button', 'sonaar-music'),
            'id'            => 'show_speed_bt',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Volume Button', 'sonaar-music'),
            'id'            => 'show_volume_bt',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional-id'    => 'show_mini_player',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Info Icon', 'sonaar-music'),
            'id'            => 'show_miniplayer_note_bt',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_boxed_tracklist'
                        ),
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        )
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Progress Bar Inline', 'sonaar-music'),
            'id'            => 'progressbar_inline',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'show_mini_player',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
    

        
        /**
         *  Tracklist: Layout
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Tracklist: Layout', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_tracklist_title',
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Tracklist Layout', 'sonaar-music'),
            'id'            => 'tracklist_layout',
            'type'          => 'select',
            'options' 						=> [
                'list' 						=> __( 'List', 'sonaar-music' ),
                'grid' 						=> __( 'Grid', 'sonaar-music' ),
            ],
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            
        ) );
        //add switch slider_move_content_below_image
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Move Tracklist Below Image', 'sonaar-music'),
            'id'            => 'move_playlist_below_artwork',
            'type'          => 'switch',
            'attributes'  => array(
                'data-target-selector' => '.sonaar-grid{ display: grid; grid-template-columns: auto;} .skin_floated{margin: auto; width: min-content;}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_float_tracklist'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'hide_artwork',
                            'value' => 'false'
                        ),
                    
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Grid Columns', 'sonaar-music'),
            'id'            => 'grid_column_number',
            'desc'          => __('Set the number of columns for desktop, tablet and mobile', 'sonaar-music'),
            'type'          => 'text_small',
            'default'       => '4,3,2',
            'attributes'  => array(
                'data-conditional-id'    => 'tracklist_layout',
                'data-conditional-value' => wp_json_encode( array( 'grid' ) ), 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
    
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Track Thumbnail', 'sonaar-music'),
            'id'            => 'track_artwork',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Play Button in Track Artwork', 'sonaar-music'),
            'id'            => 'track_artwork_play_button',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'track_artwork',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Play Icon overlay Thumbnail', 'sonaar-music'),
            'id'            => 'track_artwork_play_on_hover',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'track_artwork',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'track_artwork_play_button',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Thumbnail Width (px)', 'sonaar-music'),
            'id'            => 'track_artwork_width',
            'classes'       => 'srmp3-settings--subitem',
            'type'       	=> 'text_small',
            'attributes'  => array(
                'data-target-selector' => '.playlist li .sr_track_cover{ width:{{VALUE}}; min-width:{{VALUE}}; }',
                'data-target-unit'     => 'px',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'track_artwork',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    ),
                )),
            ),
        ) );

        $shortcode_options->add_field( array(
            'name'          => esc_html__('Thumbnail Resolution', 'sonaar-music'),
            'id'            => 'track_artwork_format',
            'type'       	=> 'select',
            'options_cb'    => 'music_player_coverSize',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'id'            => 'tracklist_album_title_typography',
            'type'          => 'typography',
            'name'          => esc_html__('Album Title Typography', 'sonaar-music'),
            'fields'        => array(
                'font-family'       => false,
                'font-weight' 		=> false,
                'background' 		=> false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.sr_it-playlist-title',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'player_layout',
                            'value' => 'skin_float_tracklist'
                        ),
                    
                    ),
                )),
            ),
        ) );
        $shortcode_options->add_field( array(
            'id'            => 'css_tracklist_typography',
            'type'          => 'typography',
            'name'          => esc_html__('Tracklist Typography', 'sonaar-music'),
            'fields'        => array(
                'font-family'       => false,
                'color'        => false,
                'font-weight' 		=> false,
                'background' 		=> false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.audio-track, .playlist .track-number',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
        ) );


        /**
         *  Tracklist: COLORS
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Play/Pause Color', 'sonaar-music'),
            'id'            => 'tracklist_control_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.playlist .sricon-play{color:{{VALUE}};} .sr_track_cover .srp_play{border-color:{{VALUE}};}',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Font Color', 'sonaar-music'),
            'id'            => 'tracklist_type_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.audio-track, .playlist .track-number{color:{{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Background Color', 'sonaar-music'),
            'id'            => 'tracklist_type_bg_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => true,
            ),
            'attributes'  => array(
                'data-target-selector' => '.sr-playlist-item{ background:{{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hover Font Color', 'sonaar-music'),
            'id'            => 'tracklist_type_color_hover',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.audio-track:hover, .audio-track:hover .track-number {color:{{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hover Background Color', 'sonaar-music'),
            'id'            => 'tracklist_type_bg_color_hover',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => true,
            ),
            'attributes'  => array(
                'data-target-selector' => '.sr-playlist-item:hover{ background:{{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Active Font Color', 'sonaar-music'),
            'id'            => 'tracklist_type_color_active',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.audio-playing .current .audio-track, .audio-playing .current .audio-track .track-number {color:{{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Active Background Color', 'sonaar-music'),
            'id'            => 'tracklist_type_bg_color_active',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => true,
            ),
            'attributes'  => array(
                'data-target-selector' => '.audio-playing .current.sr-playlist-item{ background:{{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Tracklist Container Background', 'sonaar-music'),
            'id'            => 'tracklist_bg',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => true,
            ),
            'attributes'  => array(
                'data-target-selector' => '.playlist, .skin_floated:not(.sonaar-no-artwork) .sonaar-grid{ background: {{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );


        $shortcode_options->add_field( array(
            'name'          => esc_html__('Wrap title on multiple lines', 'sonaar-music'),
            'id'            => 'tracklist_wrap_titles',
            'type'          => 'switch',
            'attributes'  => array(
                'data-target-selector' => '.playlist .tracklist-item-title{ white-space: normal;overflow:visible;text-overflow:initial; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add hide_track_number
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Track Number', 'sonaar-music'),
            'id'            => 'hide_track_number',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add Hide Duration
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Time Duration', 'sonaar-music'),
            'id'            => 'track_hide_duration',
            'type'          => 'switch',
            'attributes'  => array(
                'data-target-selector' => '.tracklist-item-time{ display:none;}',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );

        
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Artist Names', 'sonaar-music'),
            'id'            => 'tracklist_hide_artist',
            'type'          => 'switch',
            'attributes'  => array(
                'data-target-selector' => '.srp_trackartist{ display: none; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Artist wrap under the track title', 'sonaar-music'),
            'id'            => 'artist_wrap',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_hide_artist',
                            'value' => 'false'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Publish Date', 'sonaar-music'),
            'id'            => 'show_track_publish_date',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );


        /**
         *  Tracklist: Description
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Track Description', 'sonaar-music'),
            'id'            => 'hide_trackdesc',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        
        $shortcode_options->add_field( array(
            'id'            => 'trackdesc_typo',
            'type'          => 'typography',
            'name'          => esc_html__('Description Typography', 'sonaar-music'),
            'fields'        => array(
                'font-family'        => false,
                'font-weight'        => false,
                'background' 		=> false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
                
            ),
            'attributes'  => array(
                'data-target-selector' => '.srp_track_description',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_trackdesc',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Description Length', 'sonaar-music'),
            'id'            => 'track_desc_lenght',
            'desc'          => '<br>' . esc_html__('Max Number of words to show', 'sonaar-music'),
            'type'          => 'text_small',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_trackdesc',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Strip HTML', 'sonaar-music'),
            'id'            => 'strip_html_track_desc',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Hide Info Icon', 'sonaar-music'),
            'id'            => 'hide_info_icon',
            'type'          => 'switch',
            'attributes'  => array(
                'data-target-selector' => '.srp_tracklist .srp_noteButton, .srp_info_spacer{ display:none; }',
                'data-target-unit'     => 'px',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'id'            => 'trackdesc_info_typo',
            'type'          => 'typography',
            'name'          => esc_html__('Info Title Typography', 'sonaar-music'),
            'fields'        => array(
                'font-family'        => false,
                'font-weight'        => false,
                'background' 		=> false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
                
            ),
            'attributes'  => array(
                'data-target-selector' => '.playlist .srp_note_title',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_info_icon',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'id'            => 'trackdesc_info_text_typo',
            'type'          => 'typography',
            'name'          => esc_html__('Info Text Typography', 'sonaar-music'),
            'fields'        => array(
                'font-family'        => false,
                'font-weight'        => false,
                'background' 		=> false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
                
            ),
            'attributes'  => array(
                'data-target-selector' => '.playlist .srp_note',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_info_icon',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Info Background Color', 'sonaar-music'),
            'id'            => 'trackdesc_info_bg',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.playlist .srp_note{background-color:{{VALUE}}; }',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'hide_info_icon',
                            'value' => 'false'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem, srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Track Padding', 'sonaar-music'),
            'id'            => 'tracklist_padding',
            'desc'          => '<br>' . esc_html__('Eg: 10px 10px 10px 10px', 'sonaar-music'),
            'type'          => 'text_medium',
            'attributes'  => array(
                'data-target-selector' => '.sr-playlist-item{ padding: {{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Tracklist Container Padding', 'sonaar-music'),
            'id'            => 'track_item_padding',
            'desc'          => '<br>' . esc_html__('Eg: 10px 10px 10px 10px', 'sonaar-music'),
            'type'          => 'text_medium',
            'attributes'  => array(
                'data-target-selector' => '.playlist{ padding: {{VALUE}}; }',
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );

        
        /**
         *  Tracklist Soundwave
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Tracklist: Waveform', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_tracksw_title',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'options' => array(
                'textpromo' => esc_html__('Pro', 'sonaar-music'),
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Enable Soundwaves in the Tracklist', 'sonaar-music'),
            'id'            => 'tracklist_soundwave_show',
            'type'          => 'switch',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Enable Cursor duration on hover', 'sonaar-music'),
            'id'            => 'tracklist_soundwave_cursor',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'tracklist_soundwave_show',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Style', 'sonaar-music'),
            'id'            => 'tracklist_soundwave_style',
            'type'          => 'select',
            'options' 		=> [
                'mediaElement' 		    => __( 'Waveform', 'sonaar-music' ),
                'simplebar' 			=> __( 'Simple Bar', 'sonaar-music' ),
            ],
            'default'    => 'mediaElement',
            'attributes'  => array(
                'data-conditional-id'    => 'tracklist_soundwave_show',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Soundwave Background Color', 'sonaar-music'),
            'id'      => 'tracklist_soundwave_color',
            'type'    => 'colorpicker',
            'default' => '#cccccc',
            'attributes'  => array(
                'data-conditional-id'    => 'tracklist_soundwave_show',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Soundwave Progress Color', 'sonaar-music'),
            'id'      => 'tracklist_soundwave_progress_color',
            'type'    => 'colorpicker',
            'default' => '#000000',
            'attributes'  => array(
                'data-conditional-id'    => 'tracklist_soundwave_show',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Bar Width', 'sonaar-music'),
            'id'      => 'tracklist_soundwave_bar_width',
            'type'       	=> 'own_slider',
            'min'           => '0.25',
            'max'           => '10',
            'step'          => '0.25',
            'default'       => '1',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes'  => array(
                'data-conditional-id'    => 'tracklist_soundwave_show',
                'data-conditional-value' => 'true', 
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Bar Gap', 'sonaar-music'),
            'id'      => 'tracklist_soundwave_bar_gap',
            'type'       	=> 'own_slider',
            'min'           => '0.25',
            'max'           => '10',
            'step'          => '0.25',
            'default'       => '1',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes'  => array(
                'data-conditional-id'    => 'tracklist_soundwave_show',
                'data-conditional-value' => 'true', 
            ),
        ));
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Line Cap', 'sonaar-music'),
            'id'            => 'tracklist_soundwave_line_cap',
            'type'          => 'select',
            'default' => 'square',
            'options' 		=> [
                'square' 	    => __( 'Square', 'sonaar-music' ),
                'round' 		=> __( 'Round', 'sonaar-music' ),
                'butt'          => __( 'Butt', 'sonaar-music' ),
            ],
            'attributes'  => array(
                'data-conditional-id'    => 'tracklist_soundwave_show',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Wave Bar Height', 'sonaar-music'),
            'id'            => 'tracklist_soundwave_bar_height',
            'type'       	=> 'text_small',
            'attributes'  => array(
                'data-target-selector' => '.playlist .sonaar_fake_wave, .sr_waveform_simplebar .sr-playlist-item .sonaar_wave_base, .sr_waveform_simplebar .sr-playlist-item .sonaar_wave_cut{ height:{{VALUE}}!important; }',
                'data-target-unit'     => 'px',
                'data-conditional-id'    => 'tracklist_soundwave_show',
                'data-conditional-value' => 'true', 
            ),
        ) );

        /**
         *  Tracklist Searchbar
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Tracklist: Searchbar', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'tracklist_searchbar_title',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'business',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'options' => array(
                'textpromo' => esc_html__('Pro [Business]', 'sonaar-music'),
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Enable Searchbar', 'sonaar-music'),
            'id'            => 'searchbar',
            'type'          => 'switch',
            'attributes' => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true',
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'business',
            'options' => array(
                'textpromo' => esc_html__('PREMIUM FEATURE | UPGRADE TO PRO [Business]', 'sonaar-music'),
            ),
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Placeholder Text', 'sonaar-music'),
            'id'            => 'searchbar_placeholder',
            'type'          => 'text_medium',
            'attributes' => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'searchbar',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'id'            => 'searchbar_typo',
            'type'          => 'typography',
            'name'          => esc_html__('Search Bar Typography', 'sonaar-music'),
            'fields'        => array(
                'line-height'        => false,
                'font-family'        => false,
                'font-weight'        => false,
                'background' 		=> false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
                
            ),
            'attributes'  => array(
                'data-target-selector' => '.srp_search, .srp_search_container .fa-search',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'searchbar',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )), 
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Searchbar Reset Icon Color', 'sonaar-music'),
            'id'            => 'searchbar_reset_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.srp_reset_search{ color:{{VALUE}}; }',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'searchbar',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Searchbar Background', 'sonaar-music'),
            'id'            => 'searchbar_bg',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.srp_search{ background-color:{{VALUE}}; }',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'searchbar',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes'       => 'srmp3-settings--subitem',
        ) );





         /**
         *  COLUMNS
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Tracklist: Custom Fields Columns', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_columns_title',
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'business',
            'options' => array(
                'textpromo' => esc_html__('Pro [Business]', 'sonaar-music'),
            ),
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add Enable Custom Fields Columns switch
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Enable Custom Fields Columns', 'sonaar-music'),
            'id'            => 'cc_enable',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'business',
            'options' => array(
                'textpromo' => esc_html__('PREMIUM FEATURE | UPGRADE TO PRO [Business]', 'sonaar-music'),
            ),
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
         // Column Repeater
         $cc_group = $shortcode_options->add_field( array(
            'id'          => 'cc_group',
            'type'        => 'group',
            'description' => esc_html__( 'Manage Columns', 'sonaar-music' ),
            'options'     => array(
                'group_title'   => esc_html__( 'Column {#}', 'sonaar-music' ),
                'add_button'    => esc_html__( 'Add Another Column', 'sonaar-music' ),
                'remove_button' => esc_html__( 'Remove Column', 'sonaar-music' ),
                'sortable'      => true,
                'closed'        => true,
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'cc_enable',
                'data-conditional-value' => 'true', 
            ),
        ));

        // Sub-fields within the repeater
        $shortcode_options->add_group_field( $cc_group, array(
            'name' => esc_html__( 'Column Name', 'sonaar-music' ),
            'id'   => 'cc_label',
            'type' => 'text',
        ));
        // Sub-fields within the repeater for additional options
        $shortcode_options->add_group_field( $cc_group, array(
            'name' => esc_html__( 'Source', 'sonaar-music' ),
            'id'   => 'cc_source',
            'type' => 'select',
            'options' => array(
                'object'    => 'Post Metas',  // Example, you might need to define these based on actual use
                'acf'       => 'ACF',
                'jetengine' => 'JetEngine',
                'customkey' => 'Custom Meta Key',
            ),
        ));

        $shortcode_options->add_group_field( $cc_group, array(
            'name' => esc_html__( 'Meta Field', 'sonaar-music' ),
            'id'   => 'cc_postmeta',
            'type' => 'select',
            'options' => array(
                'srmp3_cf_length' => 'Track Length',
                'srmp3_cf_audio_title' => 'Track Title',
                'srmp3_cf_description' => 'Description',
                'post_title' => 'Post Title',
                'post_id' => 'Post ID',
                'post_date' => 'Post Date',
                'post_modified' => 'Post Modified',
                'playlist-cat' => 'Playlist Category',
                'playlist-tag' => 'Playlist Tag',
                'podcast-show' => 'Podcast Show',
                'product_cat' => 'Product Category',
                'product_tag' => 'Product Tag',
                'post_tags' => 'Post Tags',
            ),
            'attributes'    => array(
                'data-conditional-id'    => wp_json_encode( array( $cc_group, 'cc_source' )),
                'data-conditional-value' => 'object',
            )
        ));

        $shortcode_options->add_group_field( $cc_group, array(
            'name' => esc_html__( 'ACF Field', 'sonaar-music' ),
            'id'   => 'cc_acf_field',
            'type' => 'text',
            'attributes'    => array(
                'data-conditional-id'    => wp_json_encode( array( $cc_group, 'cc_source' )),
                'data-conditional-value' => 'acf',
            )
        ));
        // add jetengine field
        $shortcode_options->add_group_field( $cc_group, array(
            'name' => esc_html__( 'JetEngine Field', 'sonaar-music' ),
            'id'   => 'cc_jetengine_field',
            'type' => 'text',
            'attributes'    => array(
                'data-conditional-id'    => wp_json_encode( array( $cc_group, 'cc_source' )),
                'data-conditional-value' => 'jetengine',
            )
        ));
        //add custom key
        $shortcode_options->add_group_field( $cc_group, array(
            'name' => esc_html__( 'Custom Meta Key', 'sonaar-music' ),
            'id'   => 'cc_custom_key',
            'type' => 'text',
            'attributes'    => array(
                'data-conditional-id'    => wp_json_encode( array( $cc_group, 'cc_source' )),
                'data-conditional-value' => 'customkey',
            )
        ));

        $shortcode_options->add_group_field( $cc_group, array(
            'name' => esc_html__( 'Column Width', 'sonaar-music' ),
            'id'   => 'cc_width',
            'type' => 'text_small',
            'description' => '<br>' . esc_html__('Specify the width for the column.', 'sonaar-music'),
            'attributes' => array(
                'data-target-unit'     => 'px',
                
            ),
        ));

         
        // Track Title Column Width
        $shortcode_options->add_field( array(
            'name' => esc_html__( 'Track Title Column Width (px)', 'sonaar-music' ),
            'id'   => 'track_title_width',
            'desc' => '<br>' . esc_html__('Make sure tracklist is wide enough to contains all your columns. We automatically hide columns (starting with the last one) if tracklist has not enough space.', 'sonaar-music' ),
            'default' => '300px',
            'type' => 'text_small',
            'attributes'  => array(
                'data-target-unit'     => 'px',
                'data-target-selector' => '.srp_has_customfields .audio-track{ flex:1 1 {{VALUE}}; } .srp_has_customfields .sr-playlist-cf-container{ flex:0 1 calc(100% - {{VALUE}}); } .srp_has_customfields .tracklist-item-title{ width: unset; }',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    ),
                )),
            ),
        ));

        // CTA Column Width
        $shortcode_options->add_field( array(
            'name' => esc_html__( 'CTA Column Width (px)', 'sonaar-music' ),
            'id'   => 'cta_column_width',
            'type' => 'text_small',
            'attributes'  => array(
                'data-target-unit'     => 'px',
                'data-target-selector' => '.srp_has_customfields .sr-playlist-item .store-list{ flex: 0 0 {{VALUE}}; } .srp_has_customfields .playlist .store-list .song-store-list-menu{ width: {{VALUE}}; } .srp_has_customfields .sr-playlist-item:not(.srp_extended) .song-store-list-menu{ max-width: {{VALUE}}; } .srp_has_customfields .srp_responsive .sr-playlist-item .store-list{ flex: 0 0 {{VALUE}}; } .srp_has_customfields .srp_responsive .playlist .store-list .song-store-list-menu{ width: {{VALUE}}; }',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    ),
                )),
            ),
        ));
        

        // Justify Content
        $shortcode_options->add_field( array(
            'name'    => esc_html__( 'Justify Content', 'sonaar-music' ),
            'id'      => 'justify_content',
            'type'    => 'select',
            'options' => array(
                ''              => esc_html__( 'Default', 'sonaar-music' ),
                'flex-start'    => esc_html__( 'Start', 'sonaar-music' ),
                'center'        => esc_html__( 'Center', 'sonaar-music' ),
                'flex-end'      => esc_html__( 'End', 'sonaar-music' ),
                'space-between' => esc_html__( 'Space Between', 'sonaar-music' ),
                'space-around'  => esc_html__( 'Space Around', 'sonaar-music' ),
                'space-evenly'  => esc_html__( 'Space Evenly', 'sonaar-music' ),
            ),
            'attributes'  => array(
                'data-target-selector' => '.sr-playlist-cf-container{justify-content: {{VALUE}};}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
        ));
        $shortcode_options->add_field( array(
            'name'    => esc_html__( 'Text Alignment', 'sonaar-music' ),
            'id'      => 'text_alignment',
            'type'    => 'select',
            'options' => array(
                ''              => esc_html__( 'Default', 'sonaar-music' ),
                'left'   => esc_html__( 'Left', 'sonaar-music' ),
                'center' => esc_html__( 'Center', 'sonaar-music' ),
                'right'  => esc_html__( 'Right', 'sonaar-music' ),
            ),
            'attributes'  => array(
                'data-target-selector' => '.sr-playlist-cf-container .sr-playlist-cf-child{ text-align: {{VALUE}}; justify-content: {{VALUE}}; } .sr-playlist-heading-child:not(.sr-playlist-cf--title){text-align: {{VALUE}}!important;}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
        ));
        
        // Column Text Color
        $shortcode_options->add_field( array(
            'name'    => esc_html__( 'Column Text Typography', 'sonaar-music' ),
            'id'      => 'column_text_color',
            'type'    => 'typography',
            'fields'        => array(
                'font-family'       => false,
                'font-weight' 		=> false,
                'background' 		=> false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.sr-playlist-cf-container, .sr-playlist-heading-child',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
        ));
        
        // Typography and font style for column - CMB2 does not have a direct equivalent for Elementor's typography control.
        // You might need to define separate fields for font size, family, style, etc., or handle it with custom CSS.
        
        // White Space Control
        $shortcode_options->add_field( array(
            'name'    => esc_html__( 'White Space No Wrap', 'sonaar-music' ),
            'id'      => 'white_space_no_wrap',
            'type'    => 'switch',
            'attributes'  => array(
                'data-target-selector' => '.sr-playlist-cf-container .sr-playlist-cf-child{ white-space: nowrap; }',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                    ),
                )),
            ),
        ));
        
   
        
        $shortcode_options->add_field( array(
            'name'    => esc_html__( 'Show Column Headings', 'sonaar-music' ),
            'id'      => 'custom_fields_heading',
            'type'    => 'switch',
            'attributes'  => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    ),
                )),
            ),
        ));
        
        // Heading Separator Color
        $shortcode_options->add_field( array(
            'name'    => esc_html__( 'Heading Separator Color', 'sonaar-music' ),
            'id'      => 'heading_separator_color',
            'type'    => 'colorpicker',
            'attributes'  => array(
                'data-target-selector' => '.sr-cf-heading{ border-color: {{VALUE}}; }',
                'data-colorpicker' => setDefaultColorPalettes(),
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'custom_fields_heading',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    ),
                )),
            ),
        ));
        
        // Background for Heading - Use custom fields for gradient or classic types.
       
        
        //add heading typography
        $shortcode_options->add_field( array(
            'id'            => 'cc_heading_typography',
            'type'          => 'typography',
            'name'          => esc_html__('Heading Typography', 'sonaar-music'),
            'fields'        => array(
                'font-family'        => false,
                'font-weight'        => false,
                'letter-spacing'     => false,
                'text-transform'     => false,
                'font-style'         => false,
                'text-decoration'    => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.sr-cf-heading, .sr-playlist-heading-child',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'custom_fields_heading',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    ),
                )),
            ),
        ) );

        // Heading Padding - CMB2 does not support responsive controls directly.
        $shortcode_options->add_field( array(
            'name'    => esc_html__( 'Heading Padding', 'sonaar-music' ),
            'id'      => 'heading_padding',
            'type'    => 'text',
            'desc'    => '<br>' . esc_html__('Eg: 10px 10px 10px 10px', 'sonaar-music'),
            'attributes'  => array(
                'data-target-selector' => '.sr-cf-heading{ padding:{{VALUE}}; }',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'cc_enable',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'custom_fields_heading',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'tracklist_layout',
                            'value' => 'list'
                        ),
                    ),
                )),
            ),
        ));








        /**
         *  Tracklist Pagination
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Tracklist: Pagination', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_pagination_title',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'options' => array(
                'textpromo' => esc_html__('Pro [Business]', 'sonaar-music'),
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Max Number of Post to load', 'sonaar-music'),
            'id'            => 'posts_per_page',
            'type'          => 'text_small',
            'attributes'  => array(
                'data-conditional-id'    => 'lazy_load',
                'data-conditional-value' => 'false', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add scrollbar
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Scrollbar', 'sonaar-music'),
            'id'            => 'scrollbar',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add scrollbar height small_text
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Scrollbar Height (px)', 'sonaar-music'),
            'id'            => 'scrollbar_height',
            'type'          => 'text_small',
            'attributes'  => array(
                'data-target-unit'     => 'px',
                'data-target-selector' => '.srp_list{ height:{{VALUE}}; overflow-y:hidden; overflow-x:hidden;}',
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'scrollbar',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings--subitem',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add enable pagination switch
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Enable Pagination', 'sonaar-music'),
            'id'            => 'pagination',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'scbuilder_show_playlist',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'business',
            'options' => array(
                'textpromo' => esc_html__('PREMIUM FEATURE | UPGRADE TO PRO [Business]', 'sonaar-music'),
            ),
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Lazy Load', 'sonaar-music'),
            'desc' => __('Works only if audio source is set to "All Post" and if you have set 1 track per "post"', 'sonaar-music'),
            'id'            => 'lazy_load',
            'type'          => 'switch',
            'attributes' => array(
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'pagination',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'scbuilder_show_playlist',
                            'value' => 'true'
                        ),
                        array(
                            'id'    => 'source',
                            'value' => 'from_cat_all', // WIP we should support from_cat and from_term
                        ),
                    
                    ),
                )),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'business',
            'options' => array(
                'textpromo' => esc_html__('PREMIUM FEATURE | UPGRADE TO PRO [Business]', 'sonaar-music'),
            ),
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        if ( get_site_option('SRMP3_ecommerce') == '1'){
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Tracks per Page', 'sonaar-music'),
                'id'            => 'tracks_per_page',
                'type'          => 'text_small',
                'default'       => '10',
                'attributes'  => array(
                    'data-conditional' => wp_json_encode(array(
                        'logic' => 'AND', // Could be 'OR'
                        'conditions' => array(
                            array(
                                'id'    => 'pagination',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'scbuilder_show_playlist',
                                'value' => 'true'
                            ),
                        
                        ),
                    )),
                ),
            ) );

            //add Hide Numbers, Keep Arrows switch
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Hide Numbers, Keep Arrows', 'sonaar-music'),
                'id'            => 'pagination_hide_numbers',
                'type'          => 'switch',
                'attributes'  => array(
                    'data-target-selector' => '.srp_pagination{ display:none;}',
                    'data-conditional' => wp_json_encode(array(
                        'logic' => 'AND', // Could be 'OR'
                        'conditions' => array(
                            array(
                                'id'    => 'pagination',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'scbuilder_show_playlist',
                                'value' => 'true'
                            ),
                        
                        ),
                    )),
                ),
            ) );
            //add Pagination color
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Pagination Color', 'sonaar-music'),
                'id'            => 'pagination_color',
                'type'          => 'colorpicker',
                'options'       => array(
                    'alpha'         => false,
                ),
                'attributes'  => array(
                    'data-target-selector' => '.srp_pagination span{ color:{{VALUE}}; }',
                    'data-conditional' => wp_json_encode(array(
                        'logic' => 'AND', // Could be 'OR'
                        'conditions' => array(
                            array(
                                'id'    => 'pagination',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'scbuilder_show_playlist',
                                'value' => 'true'
                            ),
                        
                        ),
                    )),
                    'data-colorpicker' => setDefaultColorPalettes(),
                ),
            ) );
            //add background color
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Pagination Background Color', 'sonaar-music'),
                'id'            => 'pagination_bg_color',
                'type'          => 'colorpicker',
                'options'       => array(
                    'alpha'         => false,
                ),
                'attributes'  => array(
                    'data-target-selector' => '.srp_pagination span{ background-color:{{VALUE}}; }',
                    'data-conditional' => wp_json_encode(array(
                        'logic' => 'AND', // Could be 'OR'
                        'conditions' => array(
                            array(
                                'id'    => 'pagination',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'scbuilder_show_playlist',
                                'value' => 'true'
                            ),
                        
                        ),
                    )),
                    'data-colorpicker' => setDefaultColorPalettes(),
                ),
            ) );
            //add pagination arrow color
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Pagination Arrow Color', 'sonaar-music'),
                'id'            => 'pagination_arrow_color',
                'type'          => 'colorpicker',
                'options'       => array(
                    'alpha'         => false,
                ),
                'attributes'  => array(
                    'data-target-selector' => '.srp_pagination_arrows{ color:{{VALUE}}; border-color:{{VALUE}}; }',
                    'data-conditional' => wp_json_encode(array(
                        'logic' => 'AND', // Could be 'OR'
                        'conditions' => array(
                            array(
                                'id'    => 'pagination',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'scbuilder_show_playlist',
                                'value' => 'true'
                            ),
                        
                        ),
                    )),
                    'data-colorpicker' => setDefaultColorPalettes(),
                ),
            ) );
            //add pagination color active
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Pagination Active Color', 'sonaar-music'),
                'id'            => 'pagination_active_color',
                'type'          => 'colorpicker',
                'options'       => array(
                    'alpha'         => false,
                ),
                'attributes'  => array(
                    'data-target-selector' => '.srp_pagination .active span{ color:{{VALUE}}; }',
                    'data-conditional' => wp_json_encode(array(
                        'logic' => 'AND', // Could be 'OR'
                        'conditions' => array(
                            array(
                                'id'    => 'pagination',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'scbuilder_show_playlist',
                                'value' => 'true'
                            ),
                        
                        ),
                    )),
                    'data-colorpicker' => setDefaultColorPalettes(),
                ),
            ) );
            //add pagination background active color
            $shortcode_options->add_field( array(
                'name'          => esc_html__('Pagination Active Background Color', 'sonaar-music'),
                'id'            => 'pagination_active_bg_color',
                'type'          => 'colorpicker',
                'options'       => array(
                    'alpha'         => false,
                ),
                'attributes'  => array(
                    'data-target-selector' => '.srp_pagination .active span{ background-color:{{VALUE}}; }',
                    'data-conditional' => wp_json_encode(array(
                        'logic' => 'AND', // Could be 'OR'
                        'conditions' => array(
                            array(
                                'id'    => 'pagination',
                                'value' => 'true'
                            ),
                            array(
                                'id'    => 'scbuilder_show_playlist',
                                'value' => 'true'
                            ),
                        
                        ),
                    )),
                    'data-colorpicker' => setDefaultColorPalettes(),
                ),
            ) );
        }
    



        /**
         *  SLIDER CAROUSEL
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Carousel Slider', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'slider_title',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'options' => array(
                'textpromo' => esc_html__('Pro', 'sonaar-music'),
            ), 
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Slide Source', 'sonaar-music'),
            'id'            => 'slide_source',
            'type'          => 'select',
            'options' 		=> [
                'track'     => __( 'One for each track', 'sonaar-music' ),
                'post' 		=> __( 'One for each playlist', 'sonaar-music' ),
            ],
            'default'    => 'track',
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Play Icon on Hover', 'sonaar-music'),
            'id'            => 'slider_play_on_hover',
            'type'          => 'switch',
            'default'       => 'true',
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Effect Type', 'sonaar-music'),
            'id'            => 'effect',
            'type'          => 'select',
            'options' 		=> [
                'coverflow' 		=> __( 'Coverflow', 'sonaar-music' ),
                'slide' 			=> __( 'Slide', 'sonaar-music' ),
            ],
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Slides Per View', 'sonaar-music'),
            'id'            => 'slidesPerView',
            'type'          => 'select',
            'options' 		=> [
                '1' 		    => __( '1', 'sonaar-music' ),
                '2' 			=> __( '2', 'sonaar-music' ),
                '3' 			=> __( '3', 'sonaar-music' ),
                '4' 			=> __( '4', 'sonaar-music' ),
                '5' 			=> __( '5', 'sonaar-music' ),
                '6' 			=> __( '6', 'sonaar-music' ),
                '7' 			=> __( '7', 'sonaar-music' ),
                '8' 			=> __( '8', 'sonaar-music' ),
                '9' 			=> __( '9', 'sonaar-music' ),
                '10' 			=> __( '10', 'sonaar-music' ),
            ],
            'default'    => '3',
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Loop', 'sonaar-music'),
            'id'            => 'loop',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Navigation', 'sonaar-music'),
            'id'            => 'navigation',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Center Slides', 'sonaar-music'),
            'id'            => 'centeredSlides',
            'type'          => 'switch',
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Slide Spacing', 'sonaar-music'),
            'desc'          => esc_html__('For Slide Only. Does not apply if you are using CoverFlow', 'sonaar-music'),
            'id'            => 'spaceBetween',
            'type'       	=> 'own_slider',
            'min'           => '0',
            'max'           => '50',
            'step'          => '1',
            'default'       => '5',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes'  => array(
                'data-conditional-id'    => 'slider',
                'data-conditional-value' => 'true', 
            ),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Coverflow Effect', 'sonaar-music'),
            'desc'          => esc_html__('For CoverFlow Only. Does not apply if you are using Slide', 'sonaar-music'),
            'id'            => 'coverflowEffect',
            'type'          => 'text',
            'default'       => 'rotate: 15, slideShadows: true,depth:200, stretch:100',
            'attributes'  => array(
            // 'data-conditional-id'    => 'slider',
                //'data-conditional-value' => 'true', 
                'data-conditional' => wp_json_encode(array(
                    'logic' => 'AND', // Could be 'OR'
                    'conditions' => array(
                        array(
                            'id'    => 'effect',
                            'value' => 'coverflow'
                        ),
                        array(
                            'id'    => 'slider',
                            'value' => 'true'
                        ),
                    
                    ),
                )),
            ),
        ) );
        
        /**
         *  SPECTRO
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Animated Audio Spectrum', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'spectro_title',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'options' => array(
                'textpromo' => esc_html__('Pro', 'sonaar-music'),
            ), 
        ) );
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Enable Spectro', 'sonaar-music'),
            'id'      => 'spectro_enabled',
            'type'    => 'switch',
            'default' => 'off',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'options' => array(
                'textpromo' => esc_html__('Pro Feature', 'sonaar-music'),
            ), 
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Enable on Tracklist', 'sonaar-music'),
            'id'      => 'spectro_enableOnTracklist',
            'type'    => 'switch',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Spectro Style', 'sonaar-music'),
            'id'      => 'spectroStyle',
            'type'    => 'select',
            'options' => [
                'bars'       => __('Bars', 'sonaar-music'),
                'bricks'     => __('Bricks', 'sonaar-music'),
                'shockwave'  => __('Shockwave', 'sonaar-music'),
                'string'     => __('String', 'sonaar-music'),
            ],
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Spectro Color 1', 'sonaar-music'),
            'id'      => 'spectro_color1',
            'type'    => 'colorpicker',
            'default' => '#FF0000',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Spectro Color 2', 'sonaar-music'),
            'id'      => 'spectro_color2',
            'type'    => 'colorpicker',
            'default' => '#0000FF',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Gradient Direction', 'sonaar-music'),
            'id'      => 'spectro_gradientDirection',
            'type'    => 'radio_inline',
            'options' => [
                'vertical'   => __('Vertical', 'sonaar-music'),
                'horizontal' => __('Horizontal', 'sonaar-music'),
            ],
            'default' => 'vertical',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Shadow Effect', 'sonaar-music'),
            'id'      => 'spectro_shadow',
            'type'    => 'switch',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Reflection Effect', 'sonaar-music'),
            'id'      => 'spectro_reflectFx',
            'type'    => 'switch',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Sharp Effect', 'sonaar-music'),
            'id'      => 'spectro_sharpFx',
            'type'    => 'switch',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Bar Count', 'sonaar-music'),
            'id'      => 'spectro_barCount',
            'type'    => 'text_small',
            'default' => '100',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Bar Width', 'sonaar-music'),
            'id'      => 'spectro_barWidth',
            'type'       	=> 'own_slider',
            'min'           => '1',
            'max'           => '50',
            'step'          => '1',
            'default'       => '4',
            'value_label'        => esc_html__('Default 4px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Bar Gap', 'sonaar-music'),
            'id'      => 'spectro_barGap',
            'type'       	=> 'own_slider',
            'min'           => '0',
            'max'           => '10',
            'step'          => '1',
            'default'       => '1',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Brick Block Gap', 'sonaar-music'),
            'id'      => 'spectro_blockGap',
            'type'       	=> 'own_slider',
            'min'           => '1',
            'max'           => '10',
            'step'          => '1',
            'default'       => '1',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Brick Block Height', 'sonaar-music'),
            'id'      => 'spectro_blockHeight',
            'type'       	=> 'own_slider',
            'min'           => '1',
            'max'           => '10',
            'step'          => '1',
            'default'       => '1',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Canvas Height', 'sonaar-music'),
            'id'      => 'spectro_canvasHeight',
            'type'       	=> 'own_slider',
            'min'           => '10',
            'max'           => '200',
            'step'          => '1',
            'default'       => '50',
            'value_label'        => esc_html__('Default 1px', 'sonaar-music'),
            'value_suffix_label' => esc_html__('px', 'sonaar-music'),
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Horizontal Alignment', 'sonaar-music'),
            'id'      => 'spectro_halign',
            'type'    => 'radio_inline',
            'options' => [
                'left'   => __('Left', 'sonaar-music'),
                'center' => __('Center', 'sonaar-music'),
                'right'  => __('Right', 'sonaar-music'),
            ],
            'default' => 'center',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Vertical Alignment', 'sonaar-music'),
            'id'      => 'spectro_valign',
            'type'    => 'radio_inline',
            'options' => [
                'top'    => __('Top', 'sonaar-music'),
                'middle' => __('Middle', 'sonaar-music'),
                'bottom' => __('Bottom', 'sonaar-music'),
            ],
            'default' => 'bottom',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));

        $shortcode_options->add_field(array(
            'name'    => esc_html__('Bounce Class', 'sonaar-music'),
            'id'      => 'spectro_bounceClass',
            'type'    => 'text',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Bounce Vibrance', 'sonaar-music'),
            'id'      => 'spectro_bounceVibrance',
            'type'    => 'text_small',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));
        $shortcode_options->add_field(array(
            'name'    => esc_html__('Bounce Blur Effect', 'sonaar-music'),
            'id'      => 'spectro_bounceBlur',
            'type'    => 'switch',
            'attributes' => array(
                'data-conditional-id'    => 'spectro_enabled',
                'data-conditional-value' => 'true',
            ),
        ));




        /**
         *  Call to actions buttons
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Call to actions buttons', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_cta_title',
        ) );
        //add show_track_market
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Track Market', 'sonaar-music'),
            'id'            => 'show_track_market',
            'type'          => 'switch',
            'default'       => 'true',
        ) );
        //add track_market_inline
        $default_value = (function_exists('run_sonaar_music_pro')) ? 'true' : 'false'; // check a random option to make sure settings have not be saved a first time
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Track Market Inline', 'sonaar-music'),
            'id'            => 'track_market_inline',
            'type'          => 'switch',
            'default'       => $default_value,
            'attributes'  => array(
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true',
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
    
        //add force_cta_dl
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Display Download Button', 'sonaar-music'),
            'id'            => 'force_cta_dl',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add force_cta_share
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Display Share Button', 'sonaar-music'),
            'id'            => 'force_cta_share',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add force_cta_favorite
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Display Favorite Button', 'sonaar-music'),
            'id'            => 'force_cta_favorite',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'business',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add force_cta_singlepost
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Display Single Post Button', 'sonaar-music'),
            'id'            => 'force_cta_singlepost',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'default'       => 'default',
            'attributes'  => array(
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
    
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Text Label', 'sonaar-music'),
            'id'            => 'cta_track_show_label',
            'type'          => 'select',
            'options'       => array(
                'default'   => esc_html__('Default', 'sonaar-music'),
                'true'      => esc_html__('Yes', 'sonaar-music'),
                'false'     => esc_html__('No', 'sonaar-music'),
            ),
            'attributes'  => array(
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'id'            => 'cta_typography',
            'type'          => 'typography',
            'name'          => esc_html__('Button Typography', 'sonaar-music'),
            'fields'        => array(
                'color'             => false,
                'line-height'       => false,
                'font-family'       => false,
                'font-weight'       => false,
                'background' 	    => false,
                'text-align' 		=> false,
                'text-transform' 	=> false,
                
            ),
            'attributes'  => array(
                'data-target-selector' => '.song-store-list-container a.song-store',
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Button Padding', 'sonaar-music'),
            'id'            => 'cta_padding',
            'desc'          => '<br>' . esc_html__('Eg: 10px 10px 10px 10px', 'sonaar-music'),
            'type'          => 'text_medium',
            'attributes'  => array(
                'data-target-selector' => '.sr_store_wc_round_bt{ padding: {{VALUE}}; }',
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true', 
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'classes_to_hook' => 'srmp3-settings-hide',
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //ad Label Button Color
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Label Button Color', 'sonaar-music'),
            'id'            => 'label_button_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.song-store-list-container a.song-store.sr_store_wc_round_bt{ color:{{VALUE}}; }',
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add label button background color
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Label Button Background Color', 'sonaar-music'),
            'id'            => 'label_button_bg_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.song-store-list-container a.song-store.sr_store_wc_round_bt{ background-color:{{VALUE}}; }',
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
        ) );
        //add popover icon colorpicker
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Popover Icon Color', 'sonaar-music'),
            'id'            => 'popover_icon_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.playlist .song-store-list-menu .fa-ellipsis-v, .store-list .srp_ellipsis{ color:{{VALUE}}; }',
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
            
        ) );
        //add popover background color
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Popover Background Color', 'sonaar-music'),
            'id'            => 'popover_bg_color',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => '.song-store-list-menu .song-store-list-container{ background-color:{{VALUE}}; }',
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ) );
        //add Icons Color When No Label Present
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Icon Color when NO Label displayed', 'sonaar-music'),
            'id'            => 'icons_color_no_label',
            'type'          => 'colorpicker',
            'options'       => array(
                'alpha'         => false,
            ),
            'attributes'  => array(
                'data-target-selector' => 'a.song-store:not(.sr_store_wc_round_bt){ color:{{VALUE}}; }',
                'data-conditional-id'    => 'show_track_market',
                'data-conditional-value' => 'true',
                'data-colorpicker' => setDefaultColorPalettes(),
            ),
        ) );



        // show_album_market
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Show Album Market', 'sonaar-music'),
            'id'            => 'show_album_market',
            'type'          => 'switch',
        ) );
        //add store_title_text
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Store Title Text', 'sonaar-music'),
            'id'            => 'store_title_text',
            'type'          => 'text',
            'default'       => 'Available on',
            'attributes'  => array(
                'data-conditional-id'    => 'show_album_market',
                'data-conditional-value' => 'true', 
            ),
        ) );





        /**
         *  Advanced
         */
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Advanced / Custom CSS', 'sonaar-music'),
            'type'          => 'title',
            'id'            => 'shortcode_advanced_title',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Custom CSS', 'sonaar-music'),
            'id'            => 'custom_css',
            'type'          => 'textarea_code',
            'classes_cb'    => array('Sonaar_Music_Admin', 'pro_feature_class_cb'),
            'plan_required' => 'starter',
            'before'        => array('Sonaar_Music_Admin', 'promo_ad_text_cb'),
            'attributes' => array(
                'data-codeeditor' => json_encode( array(
                    'codemirror' => array(
                        'autoRefresh' => true,
                        'autoCloseBrackets' => true,
                        'mode' => 'css',
                        'tabSize' => 3,
                        'lineWrapping' => true,
                        'lineNumbers' => true,
                    ),
                ) ),
            ),
        ) );
        //add id text
        $shortcode_options->add_field( array(
            'name'          => esc_html__('ID CSS Selector', 'sonaar-music'),
            'id'            => 'id',
            'type'          => 'text_medium',
        ) );
        $shortcode_options->add_field( array(
            'name'          => esc_html__('Class CSS Selector', 'sonaar-music'),
            'id'            => 'player_class',
            'type'          => 'text_medium',
        ) );

        function shortcodebuilder_render($field_args, $field) {

            $id    = $field->args('id');
            $options = get_option('srmp3_settings_shortcodebuilder');
        
            echo '<div class="srmp3-player-preview-container">';
                echo '<div id="srmp3-player-preview"></div>';
            echo '</div>';
        }      



    }


}

new SRMP3_ShortcodeBuilder();