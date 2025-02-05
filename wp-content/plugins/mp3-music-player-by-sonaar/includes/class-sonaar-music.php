<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       sonaar.io
 * @since      1.0.0
 *
 * @package    Sonaar_Music
 * @subpackage Sonaar_Music/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sonaar_Music
 * @subpackage Sonaar_Music/includes
 * @author     Edouard Duplessis <eduplessis@gmail.com>
 */


class Sonaar_Music {
	private static $options_cache = array();
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sonaar_Music_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version = SRMP3_VERSION;
		$this->plugin_name = 'sonaar-music';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sonaar_Music_Loader. Orchestrates the hooks of the plugin.
	 * - Sonaar_Music_i18n. Defines internationalization functionality.
	 * - Sonaar_Music_Admin. Defines all hooks for the admin area.
	 * - Sonaar_Music_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sonaar-music-elementor.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sonaar-music-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sonaar-music-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sonaar-music-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sonaar-music-review.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sonaar-music-public.php';

		/* Add RSS importer */
		if ( is_admin() && ! wp_doing_ajax() ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sonaar-podcast-rss-import.php';
		}

		$this->loader = new Sonaar_Music_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sonaar_Music_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sonaar_Music_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new Sonaar_Music_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_admin, 'initCPT');
		$this->loader->add_action( 'init', $plugin_admin, 'srmp3_create_postType');
		$this->loader->add_action( 'init', $plugin_admin, 'srmp3_add_shortcode' );
		$this->loader->add_action( 'init', $plugin_admin, 'srmp3_clear_cookie' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widget' );

		if ( is_admin() && ! wp_doing_ajax() ) {
			if( wp_get_theme()->template === 'sonaar' ){ 
				$this->loader->add_action( 'admin_menu', $plugin_admin, 'srp_rename_theme_playlists_menu', 999);
				$this->loader->add_action( 'admin_head', $plugin_admin, 'srp_rename_theme_add_custom_menu_styles');
			}
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_filter( 'submenu_file', $plugin_admin, 'srmp3_remove_submenus' );
			$this->loader->add_action( 'manage_sr_playlist_posts_custom_column', $plugin_admin , 'manage_album_custom_column', 10, 2);
			$this->loader->add_filter( 'manage_sr_playlist_posts_columns', $plugin_admin , 'manage_album_columns');
			$this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $plugin_admin, 'editor_scripts' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'srp_add_go_pro_submenu', 9999);
			$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'init_options');
			$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'init_postField' );
			$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'save_cmb2_defaults_on_first_load');
		}
		
		if(is_admin()){
			$this->loader->add_action( 'shortcode_button_load', $plugin_admin, 'init_my_shortcode_button', 9999 );
		}
	}



	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sonaar_Music_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'elementor/frontend/before_enqueue_scripts', $plugin_public, 'editor_enqueue_scripts' );
		
		if(is_admin()){
			// For the Shortcode Builder
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'srmp3_settings_shortcodebuilder' ) {
				$this->loader->add_action( 'admin_enqueue_scripts', $plugin_public, 'enqueue_styles' );
				$this->loader->add_action( 'admin_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			}
		}

		if (get_template()== 'onair2'){
			$this->loader->add_filter( 'body_class', $plugin_public , 'srbodyclass' );	
		}

		add_action('wp_ajax_get_audio_files', array($this, 'get_audio_files'));
		add_action('wp_ajax_removeTempFiles', array($this, 'removeTempFiles'));
		add_action('wp_ajax_count_peak_files', array($this, 'count_peak_files'));
		add_action('wp_ajax_remove_peak_files_and_update_posts', array($this, 'remove_peak_files_and_update_posts'));
		add_action('wp_ajax_update_audio_peaks', array($this, 'update_audio_peaks'));
		add_action('wp_ajax_nopriv_update_audio_peaks', array($this, 'update_audio_peaks'));
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sonaar_Music_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	public static function get_user_roles() {
		$roles = wp_roles();
		$result = array();
	
		foreach ($roles->roles as $slug => $data) {
			$result[$slug] = $data['name'];
		}
	
		return $result;
	}
	
	public static function get_option($id, $option_name = null, $default = null){
		if (!isset(self::$options_cache[$option_name])) {
            self::$options_cache[$option_name] = get_option($option_name);
        }

    $option_name = (!empty($option_name) && self::$options_cache[$option_name] !== false) 
            ? self::$options_cache[$option_name] 
            : get_option('iron_music_player');

		//$option_name = ( !empty( $option_name ) && get_option($option_name) != false ) ? get_option($option_name) : get_option('iron_music_player');
		
		if ($id == 'allOptions') {
			$srmp3_settings_general = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_general') ) ) ?  get_option('srmp3_settings_general') : array());
			$srmp3_settings_widget_player = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_widget_player') ) ) ?  get_option('srmp3_settings_widget_player') : array());
			$srmp3_settings_sticky_player = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_sticky_player') ) ) ?  get_option('srmp3_settings_sticky_player') : array());
			$srmp3_settings_download = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_download') ) ) ?  get_option('srmp3_settings_download') : array());
			$srmp3_settings_woocommerce = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_woocommerce') ) ) ?  get_option('srmp3_settings_woocommerce') : array());
			$srmp3_settings_favorites = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_favorites') ) ) ?  get_option('srmp3_settings_favorites') : array());
			$srmp3_settings_audiopreview = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_audiopreview') ) ) ?  get_option('srmp3_settings_audiopreview') : array());
			$srmp3_settings_share = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_share') ) ) ?  get_option('srmp3_settings_share') : array());
			$srmp3_settings_stats = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_stats') ) ) ?  get_option('srmp3_settings_stats') : array());
			$srmp3_settings_popup = Sonaar_Music::convertSomeOptionValue(( is_array( get_option('srmp3_settings_popup') ) ) ?  get_option('srmp3_settings_popup') : array());

			$result = array_merge($srmp3_settings_general, $srmp3_settings_widget_player, $srmp3_settings_sticky_player, $srmp3_settings_download, $srmp3_settings_woocommerce, $srmp3_settings_favorites, $srmp3_settings_audiopreview, $srmp3_settings_share, $srmp3_settings_stats, $srmp3_settings_popup);

			return ( is_array( $result ) )? $result : array();
		}

		$value = ( ( is_array( $option_name ) && array_key_exists( $id,  $option_name ) ) )? $option_name[$id] : $default;

		return $value;
		
	}

	/*Do some modifications in the get option return*/
	public static function convertSomeOptionValue($value){
		/*covert URL sting from the 'sr_prevent_continuous_url' option To post ID array */
		if( is_array( $value ) && array_key_exists( 'sr_prevent_continuous_url',  $value ) ){
			$pageListToAvoid = explode(PHP_EOL, $value['sr_prevent_continuous_url']);
			$idListToAvoid = [];
			foreach ( $pageListToAvoid as &$url) {
				if( substr($url, -1) == '*' || substr($url, -2, 1) == '*' ){ // We can add a "*" to the url  to avoid all children post
					if( url_to_postid( str_replace('*', '', $url) ) === 0){ //If the page url doesnt load a post (with post ID), that mean it is probably a archive page. 
						$urlBroken = explode('/', $url);
						$slugNameFromUrl =  $urlBroken[ count($urlBroken) - 2] ;
						foreach ( get_post_types(array(), 'objects') as &$postType) {
							if( isset( $postType->rewrite) && isset( $postType->rewrite['slug'] ) ){ 
								if( ltrim($postType->rewrite['slug'], '/') == $slugNameFromUrl){
									$posts = get_posts([
										'fields'    => 'ids', // Only get post IDs
										'post_type' => $postType->name,
										'post_status' => 'publish',
										'numberposts' => -1
									  ]);
									$idListToAvoid =  array_merge( $posts, $idListToAvoid ); 
									break;  
								}
							}
						}
					}else{
						$newIds = array_keys(get_children(url_to_postid( mb_substr($url, 0, -1) )));
						$idListToAvoid =  array_merge( $newIds, $idListToAvoid );
					}
				}else{
					array_push( $idListToAvoid, url_to_postid($url));
				}
			}
			if( $idListToAvoid == null){
				$value['sr_prevent_continuous_url'] = [];
			}else{
				$value['sr_prevent_continuous_url'] = array_map(function($idInt) { return strval($idInt); }, $idListToAvoid);
			}
		}
		return $value;
	}

	public static function get_peak_dir($folder_only = null){
		if($folder_only) return '/audio_peaks/';

		$upload_dir 	= wp_get_upload_dir();
		$peaks_dir 		= $upload_dir['basedir'] . '/audio_peaks/';
		return $peaks_dir;
	}

	/**
	 * Check if tracks are found in the post
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function srmp3_check_if_audio($post = null, $wooclass = false) {
		
		if($wooclass){
			global $product;
			if ( ! $post ) {
				$post = $product;
			}
			if ( is_numeric( $post ) ) {
				$post = wc_get_product( $post );
			}
			$album_tracks = get_post_meta( $post->get_id(), 'alb_tracklist', true );
			$album_tracks = apply_filters('srmp3_album_tracks', $album_tracks, $post->get_id());
			if( get_post_meta( $post->get_id(), 'playlist_csv_file', true ) != ''){
				return true;
			}
			if( get_post_meta( $post->get_id(), 'playlist_rss', true ) != ''){
				return true;
			}
		} else {
			$album_tracks = get_post_meta( $post->ID, 'alb_tracklist', true );
			$album_tracks = apply_filters('srmp3_album_tracks', $album_tracks, $post->ID );
			if( get_post_meta( $post->ID, 'playlist_csv_file', true ) != ''){
				return true;
			}
			if( get_post_meta( $post->ID, 'playlist_rss', true ) != ''){
				return true;
			}
		}
		
		$trackSet = false;
		
		if(!is_array($album_tracks)){
			return false;
		}

		$fileOrStream =  $album_tracks[0]['FileOrStream'];
		
		switch ($fileOrStream) {
			case 'mp3':
				if ( isset( $album_tracks[0]["track_mp3"] ) ) {
					$trackSet = true;
				}
				break;

			case 'stream':
				if ( isset( $album_tracks[0]["stream_link"] ) ) {
					$trackSet = true;
				}
			case 'icecast':
				if ( isset( $album_tracks[0]["icecast_link"] ) ) {
					$trackSet = true;
				}
				break;
		}

		if (!$trackSet)
		return false;

        return true; 
	}

	public static function array_insert ( $array, $pairs, $key, $position = 'after' ){
		$key_pos = array_search( $key, array_keys($array) );

		if ( 'after' == $position )
			$key_pos++;

		if ( false !== $key_pos ) {
			$result = array_slice( $array, 0, $key_pos );
			$result = array_merge( $result, $pairs );
			$result = array_merge( $result, array_slice( $array, $key_pos ) );
		}
		else {
			$result = array_merge( $array, $pairs );
		}

		return $result;
	}
	
	public function get_audio_files(){
		check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');
		if (!current_user_can('manage_options')) {
			$response = array(
				'error' 			=> 'You do not have sufficient permissions to perform this action.',
			);
			echo wp_json_encode($response);
			wp_die();
		  }
	
		$upload_dir 	= wp_get_upload_dir();
		$limit 			= 1; // Process 250 posts at a time. Adjust this value based on your needs.
		$offset 		= isset($_POST['offset']) ? intval($_POST['offset']) : 0;
		$post_id 		= isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
		$track_pos 		= isset($_POST['track_pos']) ? intval($_POST['track_pos']) : null;
		$overwrite    	= filter_input( INPUT_POST, 'overwrite', FILTER_VALIDATE_BOOL ) ?? true;
		$files 			= array();
	
		//$posts_in = isset($_POST['posts_in']) ? $_POST['posts_in'] : null;
		$args = array(
			'post_type' 		=> array('product', 'sr_playlist'),
			'meta_key'  		=> 'alb_tracklist',
			//'post__in' 		=> array( 879, 880, 881, 882, 883, 884, 885, 886, 887, 888, 889, 868,871,872),
			//'post__in' 		=> array( 880 ),
			//'post__in' 		=> array( 10581, 10991, 181, 9686, 886, 10990, 10989, 10988, 10987, 10986, 10985, 10984, 10983, 10982 ),
			'posts_per_page' 	=> $limit,
			'offset' 			=> $offset,
		);

		if ($post_id) { // For frontend generation
			$args['post__in'] = array($post_id);
		}
		
		$product_playlist_query = new WP_Query($args);
		
		$totalPosts = $product_playlist_query->found_posts;
		$merged_posts = array();

		if (!$post_id) {
			// Query for audio attachments
			$audio_args = array(
				'post_type'      => 'attachment',
				'post_mime_type' => 'audio',
				'post_status'    => 'inherit',
				'posts_per_page' => $limit,
				'offset'         => $offset,
			);
		
			$audio_query = new WP_Query($audio_args);
			$totalPosts += $audio_query->found_posts; // Add the count from audio query
			// Filter both arrays to contain only valid WP_Post objects
			$valid_product_posts = array_filter($product_playlist_query->posts, function($post) {
				return $post instanceof WP_Post;
			});
			
			$valid_audio_posts = array_filter($audio_query->posts, function($post) {
				return $post instanceof WP_Post;
			});
		
			$merged_posts = array_merge($valid_product_posts, $valid_audio_posts);

		} else {
			$merged_posts = $product_playlist_query->posts;
		}

		$processedPosts = $offset;
		$progress		= 0;
	
	
		
	
		// Loop through the merged array of posts
			foreach ($merged_posts as $post) {
				if (!$post instanceof WP_Post) {
					// Skip if the current $post is not a WP_Post object
					continue;
				}
				setup_postdata($post);
			
				$post_id = $post->ID; // Directly access the ID
				 //var_dump($post_id);

				// Check if the post is an attachment
				if (get_post_type($post_id) === 'attachment') {
					$jsonFile 	= $post_id . '.peak';
					$jsonFilePath = $this->get_peak_dir() . $jsonFile;
					$file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $jsonFilePath);
					if (!$overwrite && file_exists($jsonFilePath)) {

					}else{
						$file_url = wp_get_attachment_url($post_id);
						if ($file_url) {
							$files[] = array(
								'file' => $file_url,
								'media_id' => $post_id,
								'post_id' => $post_id,
								'index' => ''
							);
						}
					}
					$processedPosts++;
				} else {
				$data 		= get_post_meta($post_id, 'alb_tracklist', true);
				if ($data && is_array($data)) {
					foreach ($data as $index => $item) {
						// execute only if $track_pos is null or if $track_pos is not null and $index is equal to $track_pos
						if ($track_pos !== null && $index !== $track_pos) {
							continue;
						}
	
						
	
						/*if (isset($item['track_mp3']) && !empty($item['track_mp3'])) {
							$jsonFile = $post_id . '_' . $index . '.peak';
							$jsonFilePath = $this->get_peak_dir() . $jsonFile;
							if (!$overwrite && file_exists($jsonFilePath)) {
								// If not overwriting and JSON file exists, do nothing
							} else {
								$files[] = array(
									'file' => $item['track_mp3'],
									'post_id' => $post_id,
									'index' => $index
								);
							}
							
						}*/
	
						if (isset($item['audio_preview']) && !empty($item['audio_preview'])) {
							$jsonFile 	= $post_id . '_' . $index . '_preview.peak';
							$jsonFilePath = $this->get_peak_dir() . $jsonFile;
	
							$file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $item['audio_preview']);
							if(file_exists($file_path)){
								if (!$overwrite && file_exists($jsonFilePath)) {
									// If not overwriting and JSON file exists, do nothing
								} else {
									$files[] = array(
										'file' 		=> $item['audio_preview'],
										'post_id' 	=> $post_id,
										'index' 	=> $index,
										'is_preview'=> 'true'
									);
								}
							}
						}
	
						if (isset($item['stream_link']) && !empty($item['stream_link'])) {
							$jsonFile = $post_id . '_' . $index . '.peak';
							$jsonFilePath = $this->get_peak_dir() . $jsonFile;
							if (!$overwrite && file_exists($jsonFilePath)) {
								// If not overwriting and JSON file exists, do nothing
							} else {
								$file = $this->downloadFile($item['stream_link']);
								if($file){
									$files[] = array(
										'file' 		=> $file,
										'post_id' 	=> $post_id,
										'index' 	=> $index,
										'is_temp' 	=> 'true'
									);
								}
							}
						}
	
					}
					
					$processedPosts++;
				}
			}
			
	
			$progress = ($processedPosts / $totalPosts) * 100;
		}
	
		$response = array(
			'progress' 			=> isset($progress) ? $progress : 0,  // Ensure that $progress is set
			'message' 			=> '',
			'completed' 		=> ($progress >= 100),
			'totalPosts' 		=> $totalPosts,
			'processedPosts' 	=> $processedPosts,
			'files' 			=> $files
		);
	
		// Reset post data.
		wp_reset_postdata();
		echo wp_json_encode($response);
		wp_die();
	
	}
	
	
	
	public function update_audio_peaks() {
		//generate public temp audio peak files
		check_ajax_referer('sonaar_music_ajax_peaks_nonce', 'nonce');
	
		$peaks 		= array_map( 'floatval', explode( ',', filter_input( INPUT_POST, 'peaks' ) ?? '' ) );
		$post_id    = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT ) ?? null;
		$media_id	= filter_input( INPUT_POST, 'media_id', FILTER_SANITIZE_NUMBER_INT ) ?? null;
		$index      = filter_input( INPUT_POST, 'index', FILTER_SANITIZE_NUMBER_INT ) ?? 0;
		$file 		= filter_input(INPUT_POST, 'file', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? null;
		$is_temp    = filter_input( INPUT_POST, 'is_temp', FILTER_VALIDATE_BOOL ) ?? null;
		$is_preview = filter_input( INPUT_POST, 'is_preview', FILTER_VALIDATE_BOOL ) ?? null;
		$peak_file_type = filter_input(INPUT_POST, 'peak_file_type', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;

		$peaks = implode(
			'',
			array_map(
				function($value) {
					// Ensure $value is within the range [0, 1] // looks special characters comes in if values are more than 1.
					$value = max(0, min(1, $value));
					// Convert to digit character
					return chr(round($value * 50) + 48); // * > 50 will result in higher waves
				},
				$peaks
			)
		);
		
		// Define the directory and file path
		$upload_dir 	= wp_get_upload_dir();
		$peaks_dir 		= $this->get_peak_dir();

		if( $media_id ){
			// its a file from the media library
			if($is_preview == 'true'){
				// its a preview file
				$file_name 	= $media_id . '_preview.peak';

			}else{
				// its a file from our custom field
				$file_name 	= $media_id . '.peak';

			}
		}else if ( $peak_file_type === 'name' || !$post_id ){
			// its a file from a 001 Elementor Widget, 002. Feed Shortcode or 003. ACF Field
			$extractedFile = basename($file);
			$file_name 	= $extractedFile . '.peak';
		}else{
			// its external
			if($is_preview == 'true'){
				// its an external from our STREAM preview file
				$file_name 	= $post_id . '_' . $index . '_preview.peak';
			}else{
				// its an external from our STREAM custom field 
				$file_name 	= $post_id . '_' . $index . '.peak';
			}
		}

	
		// Create the directory if it doesn't exist
		if (!file_exists($peaks_dir)) {
			wp_mkdir_p($peaks_dir);
		}
	
		// Delete the file on the server if it's temporary
		if($is_temp == 'true'){
			$this->removeTempFiles(true);
		}

		$file_path 		= $peaks_dir . $file_name;
		$write_result = file_put_contents($file_path, $peaks);
		$file_path = wp_slash($file_path);
		
		if ($write_result !== false) {
			if (!$post_id){
				wp_send_json_success('Peaks updated for feed in the post successfully.');
			}else{

				$alb_tracklist = get_post_meta($post_id, 'alb_tracklist', true);
				if (isset($alb_tracklist[$index])) {
					if($is_preview == 'true'){
						$alb_tracklist[$index]['track_peaks_preview'] = $file_path;
					}else{
						$alb_tracklist[$index]['track_peaks'] = $file_path;
					}
					update_post_meta($post_id, 'alb_tracklist', $alb_tracklist);
					wp_send_json_success('Peaks updated successfully.');
				}
			}
			
			wp_send_json_success('Peaks written to file successfully.');
		} else {
			wp_send_json_error('Failed to write peaks to file.');
		}
	}
	
	public function downloadFile($file){
		$uploads 		= wp_get_upload_dir();
		$site_domain 	= wp_parse_url(get_site_url(), PHP_URL_HOST);
		$file_domain 	= wp_parse_url($file, PHP_URL_HOST);
	
		if ($site_domain !== $file_domain) {
			// The file is external. Download it to a temporary location.
			$temp_file_name = 'temp_' . uniqid() . '.mp3';
			$temp_file_path = $this->get_peak_dir() . $temp_file_name;
	
			// error_log("temp_file: " . $temp_file_path);
			$ch = curl_init($file);
			$fp = fopen($temp_file_path, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // this will follow redirects
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
	
			// Update the input file path to the URL of the downloaded temporary file
			$relative_path 	= str_replace($uploads['basedir'], '', $temp_file_path);
			$file 			= $uploads['baseurl'] . $relative_path;
		}
	
		return $file;
	}
	
	public function removeTempFiles($called_from_internal = false){
		 // Ensure the user has the proper capability
		 if (!current_user_can('manage_options')) {
			return;
		}

		// Verify nonce only when called via AJAX directly from JS
		if (!$called_from_internal) {
			check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');
		}

		$is_temp = filter_input(INPUT_POST, 'is_temp', FILTER_VALIDATE_BOOLEAN);
		$file = filter_input(INPUT_POST, 'file', FILTER_SANITIZE_SPECIAL_CHARS);
		if ($is_temp && $file) {

			$upload_dir = wp_get_upload_dir();
			$peaks_dir = $this->get_peak_dir();
	
			$file_path_temp = str_replace($upload_dir['baseurl'] . $this->get_peak_dir(true), $peaks_dir, $file);

			$file_path_temp = wp_normalize_path($file_path_temp);
			$file_path_temp = preg_replace('~/\.\./~', '/', $file_path_temp); // Removes any '..' traversal attempts
		
			// Ensure the file path is within the allowed directory and file exists
			if (strpos($file_path_temp, wp_normalize_path($peaks_dir)) === 0 && file_exists($file_path_temp)) {
				wp_delete_file($file_path_temp);
			}
		}
		
		
	}
	
	
	public function count_peak_files() {
		check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');

	
		$files = glob($this->get_peak_dir() . '*.*');
		$fileCount = count($files);
		echo wp_json_encode(['count' => $fileCount]);
		wp_die();
	}
	
	public function remove_peak_files_and_update_posts() {
		check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');
		if (!current_user_can('manage_options')) {
			echo wp_json_encode([
				'error' => 'You do not have sufficient permissions to perform this action.'
			]);
			wp_die();
		}
	
		try {
			// 1. Remove all files from your folder
			$peak_dir = $this->get_peak_dir();
			$files = glob($peak_dir . '*'); // get all file names
			foreach($files as $file) { 
				if(is_file($file)) {
					wp_delete_file($file); // delete file
				}
			}
	
			// 2. Update all posts
			$args = array(
				'post_type' => array('product', 'sr_playlist'),
				'meta_key'  => 'alb_tracklist',
				'posts_per_page' => -1 // get all posts
			);
	
			$query = new WP_Query($args);
			if($query->have_posts()) {
				while($query->have_posts()) {
					$query->the_post();
					$post_id = get_the_ID();
					$data = get_post_meta($post_id, 'alb_tracklist', true);
					if($data && is_array($data)) {
						foreach($data as $index => $item) {
							$data[$index]['track_peaks'] = ''; // set 'audio_preview' to empty
							$data[$index]['track_peaks_preview'] = '';
						}
						update_post_meta($post_id, 'alb_tracklist', $data); // update the post meta
					}
				}
			}
	
			// Return success response
			echo wp_json_encode([
				'success' => true,
				'message' => 'All files removed and posts updated successfully!'
			]);
			wp_die();
	
		} catch(Exception $e) {
			echo wp_json_encode([
				'success' => false,
				'message' => $e->getMessage()
			]);
			wp_die();
		}
	}

}
