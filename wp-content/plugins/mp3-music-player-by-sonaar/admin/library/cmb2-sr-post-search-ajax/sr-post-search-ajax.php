<?php

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

class SR_post_Search_Ajax {

    private static $instance = null;

    /**
     * Singleton pattern to prevent multiple instances.
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('cmb2_render_sr_post_search_ajax', [$this, 'render_field'], 10, 5);
        add_action('wp_ajax_sr_post_search', [$this, 'ajax_search']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    
    }

    /**
     * Enqueue necessary scripts and styles.
     */
    public function enqueue_scripts($hook) {
        // Check if we're editing a post of type 'sr_advanced_trigger'
        global $post;
    
        if ($hook === 'post.php' || $hook === 'post-new.php') {

            // Ensure the post is loaded
            /*$post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
            $post = $post_id ? get_post($post_id) : null;*/
    
            if ($post->post_type === 'sr_advanced_triggers') {

                wp_enqueue_script( 'select2',  plugin_dir_url( __DIR__ ) . '../js/select2.min.js', array( 'jquery' ), '4.1.0', true);
                wp_enqueue_style( 'select2', plugin_dir_url( __DIR__ ) . '../css/select2.min.css' );
                wp_enqueue_script('sr-post-search-ajax-field', plugin_dir_url(__FILE__) . 'sr-post-search-ajax.js', ['jquery', 'select2'], SRMP3_VERSION, true);
    
                wp_localize_script('sr-post-search-ajax-field', 'SR_Select2_Ajax', [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce'    => wp_create_nonce('sr_post_search'),
                ]);
            }
        }
    }
    

    /**
     * Render the custom CMB2 field.
     */
    public function render_field($field, $escaped_value, $object_id, $object_type, $field_type) {
        $post_type = $field->args('post_type');
        $select_behavior = $field->args('select_behavior'); // 'replace' or 'add'
        $multiple = $select_behavior === 'add' ? 'multiple' : '';
        $required = '';
        if (isset($field->args['attributes']['required']) && $field->args['attributes']['required'] === 'required') {
            $required = 'required';
        }
        // Retrieve the meta query, if available
        $meta_query = $field->args('meta_query') ?? [];
        
        // Retrieve the search type and taxonomy
        $search_type = $field->args('search_type') ?? 'post';
        $taxonomy = $field->args('taxonomy') ?? '';
    
        $conditional_attrs = '';
        if (isset($field->args['attributes']['data-conditional'])) {
            $conditional_attrs .= ' data-conditional="' . esc_attr($field->args['attributes']['data-conditional']) . '"';
        } else {
            // Support for older conditional attributes
            if (isset($field->args['attributes']['data-conditional-id'])) {
                $conditional_attrs .= ' data-conditional-id="' . esc_attr($field->args['attributes']['data-conditional-id']) . '"';
            }
            if (isset($field->args['attributes']['data-conditional-value'])) {
                $conditional_attrs .= ' data-conditional-value="' . esc_attr($field->args['attributes']['data-conditional-value']) . '"';
            }
        }
        
        echo '<select 
                class="sr-post-search-ajax" 
                name="' . esc_attr($field_type->_name()) . ($multiple ? '[]' : '') . '" 
                ' . $multiple . ' 
                style="width:100%;" 
                data-post-type="' . esc_attr(json_encode($post_type)) . '" 
                data-search-type="' . esc_attr($search_type) . '" 
                data-taxonomy="' . esc_attr($taxonomy) . '" 
                data-select-behavior="' . esc_attr($select_behavior) . '" 
                data-meta-query="' . esc_attr(json_encode($meta_query)) . '" 
                ' . $conditional_attrs . ' 
                ' . $required . '>';
        
        // Pre-populate the selected value(s)
        if (!empty($escaped_value)) {
            if (is_array($escaped_value)) {
                foreach ($escaped_value as $value) {
                    $option_label = $search_type === 'taxonomy' 
                        ? get_term($value)->name 
                        : get_the_title($value);
                    echo '<option value="' . esc_attr($value) . '" selected>' . esc_html($option_label) . '</option>';
                }
            } else {
                $option_label = $search_type === 'taxonomy' 
                    ? get_term($escaped_value)->name 
                    : get_the_title($escaped_value);
                echo '<option value="' . esc_attr($escaped_value) . '" selected>' . esc_html($option_label) . '</option>';
            }
        }
    
        echo '</select>';
        $field_type->_desc(true); // Display field description if available
    }
    
    
    
    
    
    

    /**
     * Handle the AJAX request to search for posts.
     */
    public function ajax_search() {
        check_ajax_referer('sr_post_search', 'nonce');
    
        $search_type = isset($_GET['search_type']) ? sanitize_text_field($_GET['search_type']) : 'post';
        $search_term = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
        $results = [];
    
        if ($search_type === 'post') {
            $post_types = isset($_GET['post_type']) ? (array) $_GET['post_type'] : ['post'];
            $post_types = array_map('sanitize_text_field', $post_types);
    
            $query = new WP_Query([
                'post_type'      => $post_types,
                'posts_per_page' => 10,
                's'              => $search_term,
                'post_status'    => 'publish',
            ]);
    
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $results[] = [
                        'id'   => get_the_ID(),
                        'text' => get_the_title(),
                    ];
                }
            }
            wp_reset_postdata();
        } elseif ($search_type === 'taxonomy') {
               // Call the existing function to get terms
                $options = srp_elementor_select_category();

                // Filter results based on the search term
                foreach ($options as $term_id => $label) {
                    if (stripos($label, $search_term) !== false) { // Case-insensitive match
                        $results[] = [
                            'id'   => $term_id,
                            'text' => $label,
                        ];
                    }
                }
            /*$sr_postypes = Sonaar_Music_Admin::get_cpt(true); // Retrieve custom post types
    
            foreach ($sr_postypes as $post_type) {
                $taxonomies = get_object_taxonomies($post_type, 'names');
    
                // Customize taxonomy inclusion logic
                if ($post_type === 'product' && defined('WC_VERSION')) {
                    $taxonomies = ['product_cat', 'product_tag'];
                }
    
                foreach ($taxonomies as $taxonomy) {
                    $terms = get_terms([
                        'taxonomy'   => $taxonomy,
                        'search'     => $search_term,
                        'hide_empty' => apply_filters('sonaar/hide_empty_terms', true),
                    ]);
    
                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            $results[] = [
                                'id'   => $term->term_id,
                                'text' => $term->name . ' (' . $term->count . ') [' . $taxonomy . ']',
                            ];
                        }
                    }
                }
            }*/
        }
    
        wp_send_json($results);
    }
    
    
    
    
    
    
    
    
}

// Initialize the class.
SR_post_Search_Ajax::instance();
