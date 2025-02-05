<?php
/**
 * Radio Block Class
 *
 * @since 1.6.0
 * @todo  - Add Block
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sonaar Block Class
 */

class Sonaar_Block {
	/**
	 * Contruction
	 */
	public $version;

	private $block_swiper_condition;
	
	public function __construct() {
		$this->version = SRMP3_VERSION;
		add_action( 'init', array( $this, 'sonaar_block_editor_style_script' ),12 );

        add_action( 'enqueue_block_editor_assets', array($this, 'sonaar_block_editor_scripts') );
	}
    
    function sonaar_block_editor_scripts() {

		$sonaar_mp3player = 'sonaar-music-mp3player';

        // Register Script for elementor
		// other scripts
		wp_register_script( 'sonaar-music', plugin_dir_url( dirname( __FILE__ ) ) . 'public/js/sonaar-music-public.js', array( 'jquery' ), $this->version, true );		
		wp_register_script( 'moments', plugin_dir_url( dirname( __FILE__ ) ) . 'public/js/iron-audioplayer/00.moments.min.js', array(), $this->version, true );
		wp_register_script( 'sonaar-music-mp3player', plugin_dir_url( dirname( __FILE__ ) ) . 'public/js/iron-audioplayer/iron-audioplayer.js', array( 'jquery', 'sonaar-music' ,'moments'), $this->version, true );

		/* Enqueue Sonaar Music related CSS and Js file */
		wp_enqueue_style( 'sonaar-music' );
		wp_enqueue_script( 'sonaar-music-mp3player' );
		wp_localize_script( 'sonaar-music-mp3player', 'sonaar_music', array(
			'plugin_dir_url'=> plugin_dir_url( dirname( __FILE__ ) ),
			'option' => Sonaar_Music::get_option( 'allOptions' )
		));

		if ($this->block_swiper_condition) {
			wp_enqueue_script( 'srp-swiper' );
			wp_enqueue_style( 'srp-swiper-style' );
		}

		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$sonaar_music_pro = new Sonaar_Music_Pro_Public( 'sonaar-music-pro', '2.0.2' );
			$sonaar_music_pro->enqueue_styles();
	    	$sonaar_music_pro->enqueue_scripts();

			
			wp_enqueue_style( 'sonaar-music-pro' );
			wp_enqueue_script( 'sonaar-music-pro-mp3player' );
			wp_enqueue_script( 'sonaar_player' );

			$sonaar_mp3player = 'sonaar-music-pro-mp3player';
	   }

		if ( function_exists('sonaar_player') ) {
			add_action('wp_footer','sonaar_player', 12);
        }
        
		wp_enqueue_style( 'select2', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/select2.min.css' );
		wp_enqueue_script( 'select2', plugin_dir_url( __DIR__ ) . 'admin/js/select2.min.js','4.1.0', true );
		        
        wp_enqueue_script(
            'sonaar-admin',
            plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/sonaar-admin.js',
            array(
                $sonaar_mp3player,
                )
        );
        
    }

	/**
	 * Regester Block Scripts
	 *
	 * @return void
	 */
	function sonaar_block_editor_style_script() {
		$sonaar_mp3player = 'sonaar-music-mp3player';

		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$sonaar_mp3player = 'sonaar-music-pro-mp3player';
	   }

		// Register required js and css files
		wp_register_style( 'sonaar-music', plugin_dir_url( dirname( __FILE__ ) ) . 'public/css/sonaar-music-public.css', array(), $this->version, 'all' );
	
		// Register the tb1 block
		wp_register_script( 'sonaar-block-script', plugin_dir_url( dirname( __FILE__ ) ) . 'build/index.js', array( 'jquery', $sonaar_mp3player,'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-data', 'wp-editor'), $this->version );


		if ( function_exists( 'register_block_type' ) ) {
			
			register_block_type(
				'sonaar/sonaar-block',
				array(
					'attributes'    => $this->sr_plugin_block_attribute(),
					'editor_script' => 'sonaar-block-script',
					'editor_style'  => 'sonaar-block-editor-style',
                    'style'         => 'sonaar-block-frontend-style',
                    'render_callback' => array($this, 'render_sonaar_block'),
				)
			);
		}
    }
    
    function render_sonaar_block( $attributes ) {

		ob_start();
        $album_id = ( isset( $attributes['album_id'] ) && $attributes['album_id'] ) ? $attributes['album_id'] : '';
		$cat_id = ( isset( $attributes['cat_id'] ) && $attributes['cat_id'] ) ? $attributes['cat_id'] : '';
		$player_layout = ( isset( $attributes['player_layout'] ) && $attributes['player_layout'] ) ? $attributes['player_layout'] : '';
		$show_track_publish_date = ( isset( $attributes['show_track_publish_date'] ) && $attributes['show_track_publish_date'] ) ? $attributes['show_track_publish_date'] : 'default';
		$post_link = ( isset( $attributes['post_link'] ) && $attributes['post_link'] ) ? $attributes['post_link'] : 'default';
		$cta_track_show_label = ( isset( $attributes['cta_track_show_label'] ) && $attributes['cta_track_show_label'] ) ? $attributes['cta_track_show_label'] : 'default';
		$show_tracks_count = ( isset( $attributes['show_tracks_count'] ) && $attributes['show_tracks_count'] ) ? $attributes['show_tracks_count'] : 'default';
		$show_meta_duration = ( isset( $attributes['show_meta_duration'] ) && $attributes['show_meta_duration'] ) ? $attributes['show_meta_duration'] : 'default';
		$show_publish_date = ( isset( $attributes['show_publish_date'] ) && $attributes['show_publish_date'] ) ? $attributes['show_publish_date'] : 'default';
		$show_skip_bt = ( isset( $attributes['show_skip_bt'] ) && $attributes['show_skip_bt'] ) ? $attributes['show_skip_bt'] : 'default';
		$show_shuffle_bt = ( isset( $attributes['show_shuffle_bt'] ) && $attributes['show_shuffle_bt'] ) ? $attributes['show_shuffle_bt'] : 'default';
		$show_repeat_bt = ( isset( $attributes['show_repeat_bt'] ) && $attributes['show_repeat_bt'] ) ? $attributes['show_repeat_bt'] : 'default';
		$show_speed_bt = ( isset( $attributes['show_speed_bt'] ) && $attributes['show_speed_bt'] ) ? $attributes['show_speed_bt'] : 'default';
		$show_volume_bt = ( isset( $attributes['show_volume_bt'] ) && $attributes['show_volume_bt'] ) ? $attributes['show_volume_bt'] : 'default';
		$show_miniplayer_note_bt = ( isset( $attributes['show_miniplayer_note_bt'] ) && $attributes['show_miniplayer_note_bt'] ) ? $attributes['show_miniplayer_note_bt'] : 'default';
        $playlist_show_album_market = ( isset( $attributes['playlist_show_album_market'] ) && $attributes['playlist_show_album_market'] ) ? true : false;
		$sr_player_on_artwork = ( isset( $attributes['sr_player_on_artwork'] ) && $attributes['sr_player_on_artwork'] ) ? true : false;
		$hide_trackdesc = ( isset( $attributes['hide_trackdesc'] ) && $attributes['hide_trackdesc'] ) ? true : false;
		$strip_html_track_desc = ( isset( $attributes['strip_html_track_desc'] ) && !$attributes['strip_html_track_desc'] ) ? false : true;
		$notrackskip = ( isset( $attributes['notrackskip'] ) && $attributes['notrackskip'] ) ? true : false;
        $playlist_hide_artwork      = ( isset( $attributes['playlist_hide_artwork'] ) && $attributes['playlist_hide_artwork'] ) ? true : false;
		$hide_player_subheading      = ( isset( $attributes['hide_player_subheading'] ) && $attributes['hide_player_subheading'] ) ? true : false;
        $playlist_show_playlist     = ( isset( $attributes['playlist_show_playlist'] ) && $attributes['playlist_show_playlist'] ) ? true : false;
        $playlist_show_soundwave    = ( isset( $attributes['playlist_show_soundwave'] ) && $attributes['playlist_show_soundwave'] ) ? true : false;
		$play_current_id            = ( isset( $attributes['play_current_id'] ) && $attributes['play_current_id'] ) ? true : false;		
		$sticky_player  = false;
		$shuffle = false;
		$reverse_tracklist = false;
		$scrollbar = false;
		$scrollbar_height = '200px';
		$track_desc_lenght = 55;
		$move_playlist_below_artwork = false;
		$track_artwork_show = false;
		$track_artwork_size = 45;
		$show_control_on_hover = false;
		$title_btshow = false;
		$subtitle_btshow = false;
		$title_html_tag_playlist = 'h3';
		$title_color = '';
		$subtitle_color = '';
		$track_title_color = '';
		$tracklist_hover_color = '';
		$tracklist_active_color = '';
		$track_separator_color = '';
		$tracklist_spacing = 8;
		$duration_color = '';
		$track_publish_date_fontsize = 0;
		$track_publish_date_color = '';
		$tracklist_bg = '';
		$search_color = '';
		$reset_color = '';
		$search_placeholder = '';
		$search_background = '';
		$search_fontsize = 0;
		$player_bg = '';
		$trackdesc_fontsize = 0;
		$trackdesc_color = '';
		$metadata_fontsize = 12;
		$metadata_color = '';
		$title_align = 'flex-start';
		$title_indent = 0;
		$title_fontsize = 0;
		$subtitle_fontsize = 0;
		$track_title_fontsize = 0;
		$duration_fontsize = 0;
		$store_title_fontsize = 0;
		$store_button_fontsize = 0;
		$duration_soundwave_fontsize = 0;
		$title_soundwave_fontsize = 0;  //Deprecated option, keep for retrocompatibility
		$album_title_soundwave_fontsize = 0;
		$player_subheading_fontsize = 0;
		$hide_number_btshow = false;
		$hide_time_duration = false;
		$play_pause_bt_show = false;
		$tracklist_controls_color = '';
		$tracklist_controls_size = 12;
		$hide_track_market = false;
		$wc_bt_show = true;
		$wc_icons_color = '';
		$wc_icons_bg_color = '';
		$view_icons_alltime = true;
		$popover_icons_store = '';
		$tracklist_icons_color = '';
		$audio_player_play_text_color = '';
		$audio_player_play_text_color_hover = '';
		$tracklist_icons_spacing = '';
		$tracklist_icons_size = '';
		$hide_player_title = false;
		$player_inline = false;
		$title_html_tag_soundwave = 'div';
		$title_soundwave_color = '';
		$player_subheading_color = '';
		$soundwave_show = ( isset( $attributes['soundwave_show'] ) && $attributes['soundwave_show'] ) ? true : false;
		$use_play_label = ( isset( $attributes['use_play_label'] ) && $attributes['use_play_label'] ) ? true : false;
		$use_play_label_with_icon = ( isset( $attributes['use_play_label_with_icon'] ) && $attributes['use_play_label_with_icon'] ) ? true : false;
		$soundWave_progress_bar_color = '';
		$soundWave_bg_bar_color = '';
		$progressbar_inline = false;
		$duration_soundwave_show = false;
		$duration_soundwave_color = '';
		$description_color = '';
		$externalLinkButton_bg = '';
		$audio_player_controls_spacebefore = 0;
		$play_size = 19;
		$play_circle_size = 68;
		$play_circle_width = 6;
		$artwork_width = 300;
		$boxed_artwork_width = 160;
		$artwork_padding = 0;
		$artwork_radius = 0;
		$play_padding_h = 7;
		$play_padding_v = 7;
		$search_padding_v = 15;
		$search_padding_h = 15;
		$audio_player_artwork_controls_color = '';
		$image_overlay_on_hover = '';
		$audio_player_artwork_controls_scale = 1;
		$audio_player_controls_color = '';
		$audio_player_controls_color_hover = '';
		$audio_player_play_text_color = '';
		$audio_player_play_text_color_hover = '';
		$playlist_justify = 'center';
		$artwork_align = 'center';
		$playlist_width = 100;
		$playlist_margin = 0;
		$tracklist_margin = 0;
		$store_title_btshow = false;
		$store_title_text = esc_html__('Available now on:', 'sonaar-music');
		$store_title_color = '';
		$store_title_align = 'center';
		$widget_id = '';
		$shortcode_parameters = '';
		$play_text = '';
		$pause_text = '';
		$album_stores_align = 'center';
		$button_text_color = '';
		$background_color = '';
		$button_hover_color = '';
		$button_background_hover_color = '';
		$button_hover_border_color = '';
		$button_border_style = 'none';
		$button_border_width = 3;
		$button_border_color = '#000000';
		$button_border_radius = 0;
		$play_hover_border_color = '';
		$play_border_style = 'none';
		$play_border_width = 0;
		$play_border_color = '#000000';
		$play_border_radius = 25;
		$extended_control_btn_color = '';
		$extended_control_btn_color_hover = '';
		$store_icon_show = false;
		$icon_font_size = 0;
		$icon_indent = 10;
		$album_stores_padding = 22;
		$posts_per_page = ( isset( $attributes['posts_per_page'] ) && $attributes['posts_per_page'] ) ? $attributes['posts_per_page'] : 99;

		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$sticky_player  = ( isset( $attributes['enable_sticky_player'] ) && $attributes['enable_sticky_player'] ) ? true : false;
			$shuffle = ( isset( $attributes['enable_shuffle'] ) && $attributes['enable_shuffle'] ) ? true : false;
			$show_searchbar = ( isset( $attributes['show_searchbar'] ) && $attributes['show_searchbar'] ) ? true : false;
			$reverse_tracklist = ( isset( $attributes['reverse_tracklist'] ) && $attributes['reverse_tracklist'] ) ? true : false;
			$scrollbar = ( isset( $attributes['enable_scrollbar'] ) && $attributes['enable_scrollbar'] ) ? true : false;
			$scrollbar_height = ( isset( $attributes['scrollbar_height'] ) && $attributes['scrollbar_height'] ) ? $attributes['scrollbar_height'] .'px' : '200px';
			$track_desc_lenght = ( isset( $attributes['track_desc_lenght'] ) && $attributes['track_desc_lenght'] ) ? $attributes['track_desc_lenght'] : 55;
			$move_playlist_below_artwork = ( isset( $attributes['move_playlist_below_artwork'] ) && $attributes['move_playlist_below_artwork'] ) ? true : false;
			$track_artwork_show = ( isset( $attributes['track_artwork_show'] ) && $attributes['track_artwork_show'] ) ? true : false;
			$track_artwork_size = ( isset( $attributes['track_artwork_size'] ) && $attributes['track_artwork_size'] ) ? $attributes['track_artwork_size'] : 45;
			$show_control_on_hover = ( isset( $attributes['show_control_on_hover'] ) && $attributes['show_control_on_hover'] ) ? true : false;
			$title_btshow = ( isset( $attributes['title_btshow'] ) && $attributes['title_btshow'] ) ? true : false;
			$title_html_tag_playlist = ( isset( $attributes['title_html_tag_playlist'] ) && $attributes['title_html_tag_playlist'] ) ? $attributes['title_html_tag_playlist'] : 'h3';
			$title_color = ( isset( $attributes['title_color'] ) && $attributes['title_color'] ) ? $attributes['title_color'] : '';
			$subtitle_color = ( isset( $attributes['subtitle_color'] ) && $attributes['subtitle_color'] ) ? $attributes['subtitle_color'] : '';
			$track_title_color = ( isset( $attributes['track_title_color'] ) && $attributes['track_title_color'] ) ? $attributes['track_title_color'] : '';
			$tracklist_hover_color = ( isset( $attributes['tracklist_hover_color'] ) && $attributes['tracklist_hover_color'] ) ? $attributes['tracklist_hover_color'] : '';
			$tracklist_active_color = ( isset( $attributes['tracklist_active_color'] ) && $attributes['tracklist_active_color'] ) ? $attributes['tracklist_active_color'] : '';
			$track_separator_color = ( isset( $attributes['track_separator_color'] ) && $attributes['track_separator_color'] ) ? $attributes['track_separator_color'] : '';
			$tracklist_spacing = ( isset( $attributes['tracklist_spacing'] ) && $attributes['tracklist_spacing'] ) ? $attributes['tracklist_spacing'] : 8;
			$duration_color = ( isset( $attributes['duration_color'] ) && $attributes['duration_color'] ) ? $attributes['duration_color'] : '';
			$track_publish_date_fontsize = ( isset( $attributes['track_publish_date_fontsize'] ) && $attributes['track_publish_date_fontsize'] ) ? $attributes['track_publish_date_fontsize'] : 0;
			$track_publish_date_color = ( isset( $attributes['track_publish_date_color'] ) && $attributes['track_publish_date_color'] ) ? $attributes['track_publish_date_color'] : '';
			$tracklist_bg = ( isset( $attributes['tracklist_bg'] ) && $attributes['tracklist_bg'] ) ? $attributes['tracklist_bg'] : '';
			$search_color = ( isset( $attributes['search_color'] ) && $attributes['search_color'] ) ? $attributes['search_color'] : '';
			$reset_color = ( isset( $attributes['reset_color'] ) && $attributes['reset_color'] ) ? $attributes['reset_color'] : '';
			$search_placeholder = ( isset( $attributes['search_placeholder'] ) && $attributes['search_placeholder'] ) ? $attributes['search_placeholder'] : '';
			$search_background = ( isset( $attributes['search_background'] ) && $attributes['search_background'] ) ? $attributes['search_background'] : '';
			$search_fontsize = ( isset( $attributes['search_fontsize'] ) && $attributes['search_fontsize'] ) ? $attributes['search_fontsize'] : 0;
			$player_bg = ( isset( $attributes['player_bg'] ) && $attributes['player_bg'] ) ? $attributes['player_bg'] : '';
			$trackdesc_fontsize = ( isset( $attributes['trackdesc_fontsize'] ) && $attributes['trackdesc_fontsize'] ) ? $attributes['trackdesc_fontsize'] : 0;
			$trackdesc_color = ( isset( $attributes['trackdesc_color'] ) && $attributes['trackdesc_color'] ) ? $attributes['trackdesc_color'] : '';
			$metadata_fontsize = ( isset( $attributes['metadata_fontsize'] ) && $attributes['metadata_fontsize'] ) ? $attributes['metadata_fontsize'] : 0;
			$metadata_color = ( isset( $attributes['metadata_color'] ) && $attributes['metadata_color'] ) ? $attributes['metadata_color'] : '';
			$title_align = ( isset( $attributes['title_align'] ) && $attributes['title_align'] ) ? $attributes['title_align'] : 'flex-start';
			$button_align = ( isset( $attributes['button_align'] ) && $attributes['button_align'] ) ? $attributes['button_align'] : '';
			$title_indent = ( isset( $attributes['title_indent'] ) && $attributes['title_indent'] ) ? $attributes['title_indent'] : 0;
			$title_fontsize = ( isset( $attributes['title_fontsize'] ) && $attributes['title_fontsize'] ) ? $attributes['title_fontsize'] : 0;
			$subtitle_fontsize = ( isset( $attributes['subtitle_fontsize'] ) && $attributes['subtitle_fontsize'] ) ? $attributes['subtitle_fontsize'] : 0;
			$track_title_fontsize = ( isset( $attributes['track_title_fontsize'] ) && $attributes['track_title_fontsize'] ) ? $attributes['track_title_fontsize'] : 0;
			$duration_fontsize = ( isset( $attributes['duration_fontsize'] ) && $attributes['duration_fontsize'] ) ? $attributes['duration_fontsize'] : 0;
			$store_title_fontsize = ( isset( $attributes['store_title_fontsize'] ) && $attributes['store_title_fontsize'] ) ? $attributes['store_title_fontsize'] : 0;
			$store_button_fontsize = ( isset( $attributes['store_button_fontsize'] ) && $attributes['store_button_fontsize'] ) ? $attributes['store_button_fontsize'] : 0;
			$duration_soundwave_fontsize = ( isset( $attributes['duration_soundwave_fontsize'] ) && $attributes['duration_soundwave_fontsize'] ) ? $attributes['duration_soundwave_fontsize'] : 0;
			$title_soundwave_fontsize = ( isset( $attributes['title_soundwave_fontsize'] ) && $attributes['title_soundwave_fontsize'] ) ? $attributes['title_soundwave_fontsize'] : 0;  //Deprecated option, keep for retrocompatibility
			$album_title_soundwave_fontsize = ( isset( $attributes['album_title_soundwave_fontsize'] ) && $attributes['album_title_soundwave_fontsize'] ) ? $attributes['album_title_soundwave_fontsize'] : 0;
			$player_subheading_fontsize = ( isset( $attributes['player_subheading_fontsize'] ) && $attributes['player_subheading_fontsize'] ) ? $attributes['player_subheading_fontsize'] : 0;
			$subtitle_btshow = ( isset( $attributes['subtitle_btshow'] ) && $attributes['subtitle_btshow'] ) ? true : false;
			$hide_number_btshow = ( isset( $attributes['hide_number_btshow'] ) && $attributes['hide_number_btshow'] ) ? true : false;
			$hide_time_duration = ( isset( $attributes['hide_time_duration'] ) && $attributes['hide_time_duration'] ) ? true : false;
			$play_pause_bt_show = ( isset( $attributes['play_pause_bt_show'] ) && $attributes['play_pause_bt_show'] ) ? true : false;
			$tracklist_controls_color = ( isset( $attributes['tracklist_controls_color'] ) && $attributes['tracklist_controls_color'] ) ? $attributes['tracklist_controls_color'] : '';
			$tracklist_controls_size = ( isset( $attributes['tracklist_controls_size'] ) && $attributes['tracklist_controls_size'] ) ? $attributes['tracklist_controls_size'] : 12;
			$hide_track_market = ( isset( $attributes['hide_track_market'] ) && $attributes['hide_track_market'] ) ? true : false;
			$view_icons_alltime = ( isset( $attributes['view_icons_alltime'] ) && $attributes['view_icons_alltime'] ) ? true : false;
			$popover_icons_store = ( isset( $attributes['popover_icons_store'] ) && $attributes['popover_icons_store'] ) ? $attributes['popover_icons_store'] : '';
			$tracklist_icons_color = ( isset( $attributes['tracklist_icons_color'] ) && $attributes['tracklist_icons_color'] ) ? $attributes['tracklist_icons_color'] : '';
			$audio_player_play_text_color = ( isset( $attributes['audio_player_play_text_color'] ) && $attributes['audio_player_play_text_color'] ) ? $attributes['audio_player_play_text_color'] : '';
			$audio_player_play_text_color_hover = ( isset( $attributes['audio_player_play_text_color_hover'] ) && $attributes['audio_player_play_text_color_hover'] ) ? $attributes['audio_player_play_text_color_hover'] : '';
			$tracklist_icons_spacing = ( isset( $attributes['tracklist_icons_spacing'] ) && $attributes['tracklist_icons_spacing'] ) ? $attributes['tracklist_icons_spacing'] : 0;
			$tracklist_icons_size = ( isset( $attributes['tracklist_icons_size'] ) && $attributes['tracklist_icons_size'] ) ? $attributes['tracklist_icons_size'] : 0;
			$hide_player_title = ( isset( $attributes['hide_player_title'] ) && $attributes['hide_player_title'] ) ? true : false;
			$player_inline = ( isset( $attributes['player_inline'] ) && $attributes['player_inline'] ) ? true : false;
			$title_html_tag_soundwave = ( isset( $attributes['title_html_tag_soundwave'] ) && $attributes['title_html_tag_soundwave'] ) ? $attributes['title_html_tag_soundwave'] : 'div';
			$title_soundwave_color = ( isset( $attributes['title_soundwave_color'] ) && $attributes['title_soundwave_color'] ) ? $attributes['title_soundwave_color'] : '';
			$player_subheading_color = ( isset( $attributes['player_subheading_color'] ) && $attributes['player_subheading_color'] ) ? $attributes['player_subheading_color'] : '';
			$soundWave_progress_bar_color = ( isset( $attributes['soundWave_progress_bar_color'] ) && $attributes['soundWave_progress_bar_color'] ) ? $attributes['soundWave_progress_bar_color'] : '';
			$soundWave_bg_bar_color = ( isset( $attributes['soundWave_bg_bar_color'] ) && $attributes['soundWave_bg_bar_color'] ) ? $attributes['soundWave_bg_bar_color'] : '';
			$progressbar_inline = ( isset( $attributes['progressbar_inline'] ) && $attributes['progressbar_inline'] ) ? true : false;
			$duration_soundwave_show = ( isset( $attributes['duration_soundwave_show'] ) && $attributes['duration_soundwave_show'] ) ? true : false;
			$duration_soundwave_color = ( isset( $attributes['duration_soundwave_color'] ) && $attributes['duration_soundwave_color'] ) ? $attributes['duration_soundwave_color'] : '';
			$description_color = ( isset( $attributes['description_color'] ) && $attributes['description_color'] ) ? $attributes['description_color'] : '';
			$externalLinkButton_bg = ( isset( $attributes['externalLinkButton_bg'] ) && $attributes['externalLinkButton_bg'] ) ? $attributes['externalLinkButton_bg'] : '';
			$audio_player_controls_spacebefore = ( isset( $attributes['audio_player_controls_spacebefore'] ) && $attributes['audio_player_controls_spacebefore'] ) ? $attributes['audio_player_controls_spacebefore'] : 0;
			$play_size = ( isset( $attributes['play_size'] ) && $attributes['play_size'] ) ? $attributes['play_size'] : 19;
			$play_circle_size = ( isset( $attributes['play_circle_size'] ) && $attributes['play_circle_size'] ) ? $attributes['play_circle_size'] : 68;
			$play_circle_width = ( isset( $attributes['play_circle_width'] ) && $attributes['play_circle_width'] ) ? $attributes['play_circle_width'] : 6;
			$artwork_width = ( isset( $attributes['artwork_width'] ) && $attributes['artwork_width'] ) ? $attributes['artwork_width'] : 300;
			$boxed_artwork_width = ( isset( $attributes['boxed_artwork_width'] ) && $attributes['boxed_artwork_width'] ) ? $attributes['boxed_artwork_width'] : 160;
			$artwork_padding = ( isset( $attributes['artwork_padding'] ) && $attributes['artwork_padding'] ) ? $attributes['artwork_padding'] : 0;
			$artwork_radius = ( isset( $attributes['artwork_radius'] ) && $attributes['artwork_radius'] ) ? $attributes['artwork_radius'] : 0;
			$play_padding_h = ( isset( $attributes['play_padding_h'] ) && $attributes['play_padding_h'] ) ? $attributes['play_padding_h'] : 0;
			$play_padding_v = ( isset( $attributes['play_padding_v'] ) && $attributes['play_padding_v'] ) ? $attributes['play_padding_v'] : 0;
			$search_padding_v = ( isset( $attributes['search_padding_v'] ) && $attributes['search_padding_v'] ) ? $attributes['search_padding_v'] : 15;
			$search_padding_h = ( isset( $attributes['search_padding_h'] ) && $attributes['search_padding_h'] ) ? $attributes['search_padding_h'] : 15;
			$audio_player_artwork_controls_color = ( isset( $attributes['audio_player_artwork_controls_color'] ) && $attributes['audio_player_artwork_controls_color'] ) ? $attributes['audio_player_artwork_controls_color'] : '';
			$audio_player_artwork_controls_scale = ( isset( $attributes['audio_player_artwork_controls_scale'] ) && $attributes['audio_player_artwork_controls_scale'] ) ? $attributes['audio_player_artwork_controls_scale'] : 1;
			$audio_player_controls_color = ( isset( $attributes['audio_player_controls_color'] ) && $attributes['audio_player_controls_color'] ) ? $attributes['audio_player_controls_color'] : '';
			$audio_player_controls_color_hover = ( isset( $attributes['audio_player_controls_color_hover'] ) && $attributes['audio_player_controls_color_hover'] ) ? $attributes['audio_player_controls_color_hover'] : '';
			$image_overlay_on_hover = ( isset( $attributes['image_overlay_on_hover'] ) && $attributes['image_overlay_on_hover'] ) ? $attributes['image_overlay_on_hover'] : '';
			$playlist_justify = ( isset( $attributes['playlist_justify'] ) && $attributes['playlist_justify'] ) ? $attributes['playlist_justify'] : 'center';
			$artwork_align = ( isset( $attributes['artwork_align'] ) && $attributes['artwork_align'] ) ? $attributes['artwork_align'] : 'center';
			$playlist_width = ( isset( $attributes['playlist_width'] ) && $attributes['playlist_width'] ) ? $attributes['playlist_width'] : 100;
			$playlist_margin = ( isset( $attributes['playlist_margin'] ) && $attributes['playlist_margin'] ) ? $attributes['playlist_margin'] : 0;
			$tracklist_margin = ( isset( $attributes['tracklist_margin'] ) && $attributes['tracklist_margin'] ) ? $attributes['tracklist_margin'] : 0;
			$store_title_btshow = ( isset( $attributes['store_title_btshow'] ) && $attributes['store_title_btshow'] ) ? true : false;
			$store_title_text = ( isset( $attributes['store_title_text'] ) && $attributes['store_title_text'] ) ? $attributes['store_title_text'] : esc_html__('Available now on:', 'sonaar-music');
			$store_title_color = ( isset( $attributes['store_title_color'] ) && $attributes['store_title_color'] ) ? $attributes['store_title_color'] : '';
			$store_title_align = ( isset( $attributes['store_title_align'] ) && $attributes['store_title_align'] ) ? $attributes['store_title_align'] : 'center';
			$widget_id = ( isset( $attributes['widget_id'] ) && $attributes['widget_id'] ) ? $attributes['widget_id'] : '';
			$shortcode_parameters = ( isset( $attributes['shortcode_parameters'] ) && $attributes['shortcode_parameters'] ) ? $attributes['shortcode_parameters'] : '';
			$play_text = ( isset( $attributes['play_text'] ) && $attributes['play_text'] ) ? $attributes['play_text'] : '';
			$pause_text = ( isset( $attributes['pause_text'] ) && $attributes['pause_text'] ) ? $attributes['pause_text'] : '';
			$album_stores_align = ( isset( $attributes['album_stores_align'] ) && $attributes['album_stores_align'] ) ? $attributes['album_stores_align'] : 'center';
			$button_text_color = ( isset( $attributes['button_text_color'] ) && $attributes['button_text_color'] ) ? $attributes['button_text_color'] : '';
			$background_color = ( isset( $attributes['background_color'] ) && $attributes['background_color'] ) ? $attributes['background_color'] : '';
			$button_hover_color = ( isset( $attributes['button_hover_color'] ) && $attributes['button_hover_color'] ) ? $attributes['button_hover_color'] : '';
			$button_background_hover_color = ( isset( $attributes['button_background_hover_color'] ) && $attributes['button_background_hover_color'] ) ? $attributes['button_background_hover_color'] : '';
			$button_hover_border_color = ( isset( $attributes['button_hover_border_color'] ) && $attributes['button_hover_border_color'] ) ? $attributes['button_hover_border_color'] : '';
			$button_border_style = ( isset( $attributes['button_border_style'] ) && $attributes['button_border_style'] ) ? $attributes['button_border_style'] : '';
			$button_border_width = ( isset( $attributes['button_border_width'] ) && $attributes['button_border_width'] ) ? $attributes['button_border_width'] : 3;
			$button_border_color = ( isset( $attributes['button_border_color'] ) && $attributes['button_border_color'] ) ? $attributes['button_border_color'] : '#000000';
			$button_border_radius = ( isset( $attributes['button_border_radius'] ) && $attributes['button_border_radius'] ) ? $attributes['button_border_radius'] : 0;
			$play_hover_border_color = ( isset( $attributes['play_hover_border_color'] ) && $attributes['play_hover_border_color'] ) ? $attributes['play_hover_border_color'] : '';
			$play_border_style = ( isset( $attributes['play_border_style'] ) && $attributes['play_border_style'] ) ? $attributes['play_border_style'] : '';
			$play_border_width = ( isset( $attributes['play_border_width'] ) && $attributes['play_border_width'] ) ? $attributes['play_border_width'] : 0;
			$play_border_color = ( isset( $attributes['play_border_color'] ) && $attributes['play_border_color'] ) ? $attributes['play_border_color'] : '#000000';
			$play_border_radius = ( isset( $attributes['play_border_radius'] ) && $attributes['play_border_radius'] ) ? $attributes['play_border_radius'] : 25;
			$extended_control_btn_color = ( isset( $attributes['extended_control_btn_color'] ) && $attributes['extended_control_btn_color'] ) ? $attributes['extended_control_btn_color'] : '';
			$extended_control_btn_color_hover = ( isset( $attributes['extended_control_btn_color_hover'] ) && $attributes['extended_control_btn_color_hover'] ) ? $attributes['extended_control_btn_color_hover'] : '';
			$store_icon_show = ( isset( $attributes['store_icon_show'] ) && $attributes['store_icon_show'] ) ? true : false;
			$icon_font_size = ( isset( $attributes['icon_font_size'] ) && $attributes['icon_font_size'] ) ? $attributes['icon_font_size'] : 0;
			$icon_indent = ( isset( $attributes['icon_indent'] ) && $attributes['icon_indent'] ) ? $attributes['icon_indent'] : 10;
			$album_stores_padding = ( isset( $attributes['album_stores_padding'] ) && $attributes['album_stores_padding'] ) ? $attributes['album_stores_padding'] : 22;
		}
		
		$classes = ''; 

		if( function_exists( 'run_sonaar_music_pro' ) ) { 

			if( $move_playlist_below_artwork ) {
				$classes .= ' sr_playlist_below_artwork_auto'; 
			}
			if( $title_btshow ) {
				$classes .= ' sr_player_title_hide'; 
			}
			if( $subtitle_btshow ) {
				$classes .= ' sr_player_subtitle_hide'; 
			}
			if( $hide_number_btshow ) {
				$classes .= ' sr_player_track_num_hide'; 
			}
			if( $hide_time_duration ) {
				$classes .= ' sr_player_time_hide';
			}
			if( $play_pause_bt_show ) {
				$classes .= ' sr_play_pause_bt_hide';
			}
			if( $view_icons_alltime ) {
				$classes .= ' sr_track_inline_cta_bt__yes';
			}
			if( isset($attributes['className']) ) {
				$classes .= ' ' . $attributes['className'];
			}
		}
		
		if( function_exists( 'run_sonaar_music_pro' ) ) {			
			$wave_color = ( $soundWave_bg_bar_color != ''  ) ? $soundWave_bg_bar_color : false;
			$wave_progress_color = ( $soundWave_progress_bar_color != ''  ) ? $soundWave_progress_bar_color : false;
		} else {			
			$wave_color = false;
			$wave_progress_color = false;
		}

		$show_track_market = ( $hide_track_market ) ? false : true;
		$show_track_market = ( function_exists( 'run_sonaar_music_pro' ) ) ? $show_track_market : true;

		$show_searchbar = (isset($show_searchbar)) ? $show_searchbar : 'false';
		
		$shortcode = '[sonaar_audioplayer titletag_soundwave="'. $title_html_tag_soundwave .'" titletag_playlist="'. $title_html_tag_playlist .'" hide_artwork="' . $playlist_hide_artwork .'" show_playlist="' . $playlist_show_playlist .'" show_album_market="' . $playlist_show_album_market .'" hide_timeline="' . $playlist_show_soundwave .'" sticky_player="' . $sticky_player .'" wave_color="'. $wave_color .'" wave_progress_color="'. $wave_progress_color .'" shuffle="' . $shuffle .'" reverse_tracklist="' . $reverse_tracklist .'" show_track_market="'. $show_track_market .'" searchbar="'. $show_searchbar .'" ';

		if( $scrollbar && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'scrollbar="true" ';
		}

		if( isset($attributes['show_cat_description']) && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .='show_cat_description="'. $attributes['show_cat_description']  .'" ';
		}

		if( $sr_player_on_artwork ) {
			$shortcode .= 'display_control_artwork="true" ';
		}

		if( $hide_player_title ) {
			$shortcode .= 'hide_player_title="true" ';
		}

		
		if( $track_desc_lenght ){
			$shortcode .= 'track_desc_lenght="' . $track_desc_lenght . '" ';
		}
		if( $hide_trackdesc) {
			$shortcode .= 'hide_trackdesc="true" ';
		}

		if( !$strip_html_track_desc) {
			$shortcode .= 'strip_html_track_desc="false" ';
		}else{
			$shortcode .= 'strip_html_track_desc="true" ';
		}

		if( $notrackskip) {
			$shortcode .= 'notrackskip="true" ';
		}
		
		if( $player_layout) {
			$shortcode .= 'player_layout="'.$player_layout.'" ';
		}

		if( $show_track_publish_date && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_track_publish_date="'.$show_track_publish_date.'" ';
		}

		if( $show_volume_bt && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_volume_bt="'.$show_volume_bt.'" ';
		}

		if( $show_miniplayer_note_bt && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_miniplayer_note_bt="'.$show_miniplayer_note_bt.'" ';
		}

		if( $show_speed_bt && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_speed_bt="'.$show_speed_bt.'" ';
		}

		if( $show_shuffle_bt && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_shuffle_bt="'.$show_shuffle_bt.'" ';
		}

		if( $show_repeat_bt && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_repeat_bt="'.$show_repeat_bt.'" ';
		}

		if( $show_skip_bt && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_skip_bt="'.$show_skip_bt.'" ';
		}

		if( $post_link && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'post_link="'.$post_link.'" ';
		}

		if( $cta_track_show_label && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'cta_track_show_label="'.$cta_track_show_label.'" ';
		}

		if( $show_publish_date && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_publish_date="'.$show_publish_date.'" ';
		}

		if( $show_tracks_count && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_tracks_count="'.$show_tracks_count.'" ';
		}

		if( $show_meta_duration && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_meta_duration="'.$show_meta_duration.'" ';
		}

		if( $track_artwork_show && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'track_artwork="true" ';
		}

		if( $show_control_on_hover && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'show_control_on_hover="true" ';
		}
		if( $soundwave_show ) {
			$shortcode .= 'hide_progressbar="true" ';
		}else{
			$shortcode .= 'hide_progressbar="false" ';
		}

		if( $use_play_label ) {
			$shortcode .= 'use_play_label="true" ';
		}else{
			$shortcode .= 'use_play_label="false" ';
		}
		if( $use_play_label && $use_play_label_with_icon && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'use_play_label_with_icon="true" ';
		}else{
			$shortcode .= 'use_play_label_with_icon="false" ';
		}

		if( ! $soundwave_show && $progressbar_inline && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'progressbar_inline="true" ';
		}
		if( ! $soundwave_show && $duration_soundwave_show && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'hide_times="true" ';
		}

		if( ! $store_title_btshow && $store_title_text && function_exists( 'run_sonaar_music_pro' ) ) {
			$shortcode .= 'store_title_text="'. $store_title_text .'" ';
		}

		if( $widget_id != '' ) {
			$shortcode .= 'id="'. $widget_id .'" ';
		}
		if( $play_text != '' && $use_play_label) {
			$shortcode .= 'play_text="'. $play_text .'" ';
		}
		if( $pause_text != '' && $use_play_label) {
			$shortcode .= 'pause_text="'. $pause_text .'" ';
		}
		
		
		if ( $play_current_id || $attributes['playlist_source']=='from_current_post' ){ //If "Play its own Post ID track" option is enable
			$postid = get_the_ID();
			$shortcode .= 'albums="' . $postid . '" ';
		} else {
			$display_playlist_ar = $album_id;
			$display_playlist_cat_ar = $cat_id;

			if(is_array($display_playlist_ar)){
                $display_playlist_ar = implode(", ", $display_playlist_ar);
			}

			if(is_array($display_playlist_cat_ar)){
                $display_playlist_cat_ar = implode(", ", $display_playlist_cat_ar);
			}

			if(!$display_playlist_cat_ar && $attributes['playlist_source'] == 'from_cat'){
				$shortcode .= 'category="all" ';
				$shortcode .= ($posts_per_page > 0) ? 'posts_per_page="' . $posts_per_page . '" ' : '';
			}elseif($display_playlist_cat_ar && $attributes['playlist_source'] == 'from_cat'){
				$shortcode .= 'category="'. $display_playlist_cat_ar . '" ';
				$shortcode .= ($posts_per_page > 0) ? 'posts_per_page="' . $posts_per_page . '" ' : '';
			}

			// WIP
			if (!$display_playlist_ar) { //If no playlist is selected, play the latest playlist
				if($attributes['playlist_source'] == 'from_cpt' ) {
					$shortcode .= 'play-latest="yes" ';
				}
				if ( class_exists('Elementor') &&  \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
					if ($attributes['playlist_source'] == 'from_elementor'  && !$attributes['feed_repeater'] ){
						echo esc_html__('Add tracks in the widget settings.<br>', 'sonaar-music');
					}
				}
			} else {
                $shortcode .= 'albums="' . $display_playlist_ar . '" ';
			}
        }
		$shortcode .= ']';


		if( $shortcode_parameters != '' ) {//If there is shortcode parameters add through the shortcode parameters field, we need to update the shortcode with the new parameters
			$shortcode = str_replace('[sonaar_audioplayer', '', $shortcode);
			$shortcode = str_replace(']', '', $shortcode);
			$shortcode_params = explode('" ', $shortcode);
			$newParameters = explode('" ', $shortcode_parameters);
		
			// Create an associative array of parameters from the block editor 
			$params = array();
			foreach ($shortcode_params as $param) {
				if (strpos($param, "=") !== false) {
					list($key, $value) = explode('=', $param);
					$value = str_replace('"', '', $value);
					$params[$key] = '"' . $value . '"';
				}
			}
			
			// Update parameters with the new parameters from the shortcode parameters field. Replace existing parameters with the same name.
			foreach ($newParameters as $param) {
				if (strpos($param, "=") !== false) {
					list($key, $value) = explode('=', $param);
					$value = str_replace('"', '', $value);
					$params[$key] = '"' .$value . '"';
				}
			}

			// Build the new shortcode with the updated parameters
			$shortcode = '[sonaar_audioplayer ';
			foreach ($params as $key => $value) {
				$shortcode .= $key . '=' . $value . ' ';
			}

			$this->block_swiper_condition = (isset($params['slider_param']))? true : false; //Load swiper script

			$shortcode = trim($shortcode) . ']';
			
		}

		$shortcode = '<div class="sonaar_audioplayer_block_cover'. $classes .'">'. $shortcode .'</div>';

		$renadom_number = rand(10,100);
		$block_id = 'sonaar_music_' . $renadom_number;

		if( $scrollbar && function_exists( 'run_sonaar_music_pro' ) ) {
			$scrollbar_css = " #". esc_attr($block_id) ." .iron-audioplayer .playlist ul {
				height: ". esc_attr($scrollbar_height) . ";
				overflow-y: hidden;
				overflow-x: hidden;
			} ";

			if( is_admin() ) {
			} else {
				wp_add_inline_style( 'sonaar-music-pro', $scrollbar_css );
			}

			echo '<style>';
			echo esc_html($scrollbar_css);
			echo '</style>';
			
		}
		$custom_css = ''; //FRONTEND CSS

		if( function_exists( 'run_sonaar_music_pro' ) ) { 
			if( ! $playlist_hide_artwork ) {
				if ($player_layout == 'skin_boxed_tracklist') {
					$custom_css .= ' #'.$block_id .' .iron-audioplayer:not(.sonaar-no-artwork) .srp_player_grid { grid-template-columns: ' . $boxed_artwork_width . 'px 1fr;}';
					$custom_css .= ' #'.$block_id .' .srp_player_boxed .album-art { width: ' . $boxed_artwork_width . 'px; max-width: ' . $boxed_artwork_width . 'px;}';
					$custom_css .= ' #'.$block_id .' .srp_player_boxed .sonaar-Artwort-box { min-width: ' . $boxed_artwork_width . 'px;}';
				} else {
					$custom_css .= ' #'.$block_id .' .iron-audioplayer .album .album-art { max-width: ' . $artwork_width . 'px; width: ' . $artwork_width . 'px;}';
				}
				$custom_css .= ' #'.$block_id .' .album .album-art img { border-radius: '.$artwork_radius.'px;}';
				$custom_css .= ' #'.$block_id .' .sonaar-grid .album { padding: '.$artwork_padding.'px;}';
			}

			if($player_layout == 'skin_button' && $player_inline && $soundwave_show){
				$custom_css .= ' #'.$block_id .'  { display: inline-block;}';
			}
			
			$custom_css .= ' #'.$block_id .' .srp_player_boxed .srp-play-button-label-container { padding: '.$play_padding_v.'px '.$play_padding_h.'px;}';
			$custom_css .= ' #'.$block_id .' .playlist li .sr_track_cover { width: '.$track_artwork_size.'px; min-width: '.$track_artwork_size.'px;}';
			$custom_css .= ' #'.$block_id .' .sonaar-grid { justify-content: '.$playlist_justify.'; }';
			$custom_css .= ' #'.$block_id .' .sr_playlist_below_artwork_auto .sonaar-grid { align-items: '.$playlist_justify.'; }';
			$custom_css .= ' #'.$block_id .' .playlist, #'.$block_id .' .buttons-block { width: '.$playlist_width.'%; }';
			$custom_css .= ' #'.$block_id .' .playlist { margin: '.$playlist_margin.'px; }';
			$custom_css .= ' #'.$block_id .' .srp_tracklist { margin: '.$tracklist_margin.'px; }';
			$custom_css .= ' #'.$block_id .' .sr_it-playlist-title, #'.$block_id .' .sr_it-playlist-artists, #'.$block_id .' .srp_subtitle { text-align: '.$title_align.'; }';
			$custom_css .= ' #'.$block_id .' .sr_it-playlist-title, #'.$block_id .' .sr_it-playlist-artists, #'.$block_id .' .srp_subtitle { margin-left: '.$title_indent.'px; }';
			$custom_css .= ' #'.$block_id .' .playlist li { padding-top: '.$tracklist_spacing.'px; padding-bottom: '.$tracklist_spacing.'px; }';
			$custom_css .= ' #'.$block_id .' .sr-playlist-item .sricon-play:before{ font-size: '.$tracklist_controls_size.'px; }';
			$custom_css .= ' #'.$block_id .' .track-number { padding-left: calc( '.$tracklist_controls_size.'px + 12px ); }';
			$custom_css .= '@media (max-width: 767px){ #'.$block_id .' .iron-audioplayer .srp_tracklist-item-date { padding-left: calc( '.$tracklist_controls_size.'px + 12px ); }}';
			$custom_css .= ' #'.$block_id .' .ctnButton-block { justify-content: '.$store_title_align.'; align-items: '.$store_title_align.'; }';
			$custom_css .= ' #'.$block_id .' .buttons-block { justify-content: '.$album_stores_align.'; align-items: '.$album_stores_align.'; }';
			$custom_css .= ' #'.$block_id .' .buttons-block .store-list li .button { border-style: '.$button_border_style.'; }';
			$custom_css .= ' #'.$block_id .' .show-playlist .ctnButton-block { margin: '.$album_stores_padding.'px; }';
			if($button_align != ''){
				$custom_css .= ' #'.$block_id .' .album-player { display: flex; justify-content: '.$button_align.'; }';
			}
			if($hide_player_subheading){
				$custom_css .= ' #'.$block_id .' .srp_subtitle { display: none !important; }';
			}
			if( $show_track_market ) {
				if( ! $wc_bt_show ) {
					$custom_css .= ' #'.$block_id .' .playlist a.song-store.sr_store_wc_round_bt { display: none; }';
				}
				if( $wc_bt_show  && $wc_icons_color != '' ) {
					$custom_css .= ' #'.$block_id .' .playlist a.song-store.sr_store_wc_round_bt { color: '.$wc_icons_color.'; }';	
				}
				if( $wc_bt_show  && $wc_icons_bg_color != '' ) {
					$custom_css .= ' #'.$block_id .' .playlist a.song-store.sr_store_wc_round_bt { background-color: '.$wc_icons_color.'; }';	
				}
			}

			if( ! $progressbar_inline && $player_layout != 'skin_button' ) {
				$custom_css .= ' #'.$block_id .' .album-player .control { top: '.$audio_player_controls_spacebefore.'px; position: relative; }';
			}
			
			$custom_css .= ' #'.$block_id .' .srp-play-button .sricon-play  { font-size: '.$play_size.'px; }';
			$custom_css .= ' #'.$block_id .' .srp-play-circle  { height: '.$play_circle_size.'px; width: '.$play_circle_size.'px; border-radius: '.$play_circle_size.'px; }';
			$custom_css .= ' #'.$block_id .' .srp-play-circle  { border-width: '.$play_circle_width.'px; }';

			if( $sr_player_on_artwork && $audio_player_artwork_controls_color != ''  ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer.sr_player_on_artwork .sonaar-Artwort-box .control .sricon-play,  #'.$block_id .' .iron-audioplayer.sr_player_on_artwork .sonaar-Artwort-box .control .sricon-back,  #'.$block_id .' .iron-audioplayer.sr_player_on_artwork .sonaar-Artwort-box .control .sricon-forward { color: '.$audio_player_artwork_controls_color.'; }';
				$custom_css .= ' #'.$block_id .' .sr_player_on_artwork .control .play { border-color: '.$audio_player_artwork_controls_color.'; }';
			}

			if( $sr_player_on_artwork && $show_control_on_hover && $image_overlay_on_hover != ''  ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp_show_ctr_hover .album-art:before  { background: '.$image_overlay_on_hover.'; }';
			}
			
			if( $sr_player_on_artwork  ) {
				$custom_css .= ' #'.$block_id .' .sr_player_on_artwork .sonaar-Artwort-box .control { transform:scale('. $audio_player_artwork_controls_scale .'); }';
			}
			

			if( $button_border_style != 'none' ) {
				$custom_css .= ' #'.$block_id .' .buttons-block .store-list li .button { border-width: '.$button_border_width.'px; }';
				$custom_css .= ' #'.$block_id .' .buttons-block .store-list li .button { border-color: '.$button_border_color.'; }';
				$custom_css .= ' #'.$block_id .' .store-list .button { border-radius: '.$button_border_radius.'px; }';
			}

			if( $play_border_style != 'none' ) {
				$custom_css .= ' #'.$block_id .' .srp-play-button-label-container { border-style: '.$play_border_style.'; }';
				$custom_css .= ' #'.$block_id .' .srp-play-button-label-container { border-width: '.$play_border_width.'px; }';
				$custom_css .= ' #'.$block_id .' .srp-play-button-label-container { border-color: '.$play_border_color.'; }';
				$custom_css .= ' #'.$block_id .' .srp-play-button-label-container { border-color: '.$play_hover_border_color.'; }';
				$custom_css .= ' #'.$block_id .' .srp-play-button-label-container { border-radius: '.$play_border_radius.'px; }';
			}
			
			if ($player_layout != 'skin_float_tracklist' && $extended_control_btn_color != '') {
				$custom_css .= ' #'.$block_id .' div.iron-audioplayer .control .sr_speedRate div { color: ' . $extended_control_btn_color . '; border-color: ' . $extended_control_btn_color . ';}';
				$custom_css .= ' #'.$block_id .' div.iron-audioplayer .control, #'.$block_id .' div.iron-audioplayer .control .sr_skipBackward, #'.$block_id .' div.iron-audioplayer .control .sr_skipForward, #'.$block_id .' div.iron-audioplayer .control .sr_shuffle, #'.$block_id .' div.iron-audioplayer .control .sricon-volume { color: ' . $extended_control_btn_color . ';}';
			}

			if ($player_layout != 'skin_float_tracklist' && $extended_control_btn_color_hover != '') {
				$custom_css .= ' #'.$block_id .' div.iron-audioplayer .ui-slider-handle, #'.$block_id .' div.iron-audioplayer .ui-slider-range { background: ' . $extended_control_btn_color_hover . ';}';
				$custom_css .= ' #'.$block_id .' div.iron-audioplayer .control .sr_speedRate:hover div { color: ' . $extended_control_btn_color_hover . '; border-color: ' . $extended_control_btn_color_hover . ';}';
				$custom_css .= ' #'.$block_id .' div.iron-audioplayer .control .sr_skipBackward:hover, #'.$block_id .' div.iron-audioplayer .control .sr_skipForward:hover, #'.$block_id .' div.iron-audioplayer .control .sr_shuffle:hover, #'.$block_id .' div.iron-audioplayer .control .sricon-volume:hover { color: ' . $extended_control_btn_color_hover . ';}';
			}

			if( $sr_player_on_artwork && ! $playlist_hide_artwork && $playlist_show_playlist && $move_playlist_below_artwork  ) {
				$custom_css .= ' #'.$block_id .' .sonaar-Artwort-box { justify-content: '.$artwork_align.'; }';
			}

			if( $title_color != '' ) {
				$custom_css .= ' #'.$block_id .' .playlist .sr_it-playlist-title { color: '.$title_color.'; }';
			}

			if( $title_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .playlist .sr_it-playlist-title { font-size: '.$title_fontsize.'px; }';
			}

			if( $subtitle_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .srp_subtitle { font-size: '.$subtitle_fontsize.'px; }';
			}

			if( $track_title_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .playlist .audio-track, #'.$block_id .' .playlist .track-number, #'.$block_id .' .track-title { font-size: '.$track_title_fontsize.'px; }';
			}

			if( ! $hide_time_duration && $duration_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .tracklist-item-time { font-size: '.$duration_fontsize.'px; }';
			}

			if( ! $store_title_btshow && $store_title_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .available-now { font-size: '.$store_title_fontsize.'px; }';
			}

			if( $store_button_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' a.button { font-size: '.$store_button_fontsize.'px; }';
			}

			if( ! $soundwave_show && ! $duration_soundwave_show && $duration_soundwave_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .player { font-size: '.$duration_soundwave_fontsize.'px; }';
			}

			if( ! $soundwave_show && $audio_player_controls_color != ''  ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .control .sr_speedRate div, #'.$block_id .' .srp-play-button .sricon-play { color: '.$audio_player_controls_color.'; border-color: '.$audio_player_controls_color.';}';
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .control .sr_skipBackward, #'.$block_id .' .iron-audioplayer .control .sr_skipForward, #'.$block_id .' .iron-audioplayer .control .sr_shuffle, #'.$block_id .' .iron-audioplayer .control .play .sricon-play, #'.$block_id .' .iron-audioplayer .control .sricon-volume, #'.$block_id .' .iron-audioplayer .control .next, #'.$block_id .' .iron-audioplayer .control .previous { color: '.$audio_player_controls_color.';}';
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp-play-circle { border-color: '.$audio_player_controls_color.';}';
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp-play-button-label-container { background: '.$audio_player_controls_color.';}';
			
			}

			if( ! $soundwave_show && $audio_player_controls_color_hover != ''  ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .ui-slider-handle, #'.$block_id .' .iron-audioplayer .ui-slider-range { background: '.$audio_player_controls_color_hover.';}';
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .control .sr_speedRate:hover div, #'.$block_id .' .srp-play-button:hover .sricon-play { color: '.$audio_player_controls_color_hover.'; border-color: '.$audio_player_controls_color_hover.';}';
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .control .play:hover .sricon-play, #'.$block_id .' .iron-audioplayer .control .sr_skipBackward:hover, #'.$block_id .' .iron-audioplayer .control .sr_skipForward:hover, #'.$block_id .' .iron-audioplayer .control .sr_shuffle:hover, #'.$block_id .' .iron-audioplayer .control .play:hover .sricon-play, #'.$block_id .' .iron-audioplayer .control .sricon-volume:hover, #'.$block_id .' .iron-audioplayer .control .next:hover, #'.$block_id .' .iron-audioplayer .control .previous:hover{ color: '.$audio_player_controls_color_hover.';}';
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp-play-button:hover .srp-play-circle { border-color: '.$audio_player_controls_color_hover.';}';
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp-play-button-label-container:hover { background: '.$audio_player_controls_color_hover.';}';
			
			}

			if( $subtitle_color != '' ) {
				$custom_css .= ' #'.$block_id .' .srp_subtitle { color: '.$subtitle_color.'; }';
			}

			if( $track_title_color != '' ) {
				$custom_css .= ' #'.$block_id .' .playlist .audio-track, #'.$block_id .' .playlist .track-number, #'.$block_id .' .track-title, #'.$block_id .' .player { color: '.$track_title_color.'; }';
			}

			if( $tracklist_hover_color != '' ) {
				$custom_css .= ' #'.$block_id .' .playlist .audio-track:hover, #'.$block_id .' .playlist .audio-track:hover .track-number, #'.$block_id .' .playlist a.song-store:not(.sr_store_wc_round_bt):hover, #'.$block_id .' .playlist .current a.song-store:not(.sr_store_wc_round_bt):hover { color: '.$tracklist_hover_color.'; }';
			}

			if( $tracklist_active_color != '' ) {
				$custom_css .= ' #'.$block_id .' .playlist .current .audio-track, #'.$block_id .' .playlist .current .audio-track .track-number, #'.$block_id .' .playlist .current a.song-store { color: '.$tracklist_active_color.'; }';
			}

			if( $track_separator_color != '' ) {
				$custom_css .= ' #'.$block_id .' .playlist li { border-bottom: solid 1px '.$track_separator_color.'; }';
			}

			if( $duration_color != '' ) {
				$custom_css .= ' #'.$block_id .' .tracklist-item-time { color: '.$duration_color.'; }';
			}

			if( $track_publish_date_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp_tracklist-item-date { font-size: '.$track_publish_date_fontsize.'px; }';
			}

			if( $track_publish_date_color != '' ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp_tracklist-item-date { color: '.$track_publish_date_color.'; }';
			}

			if( $tracklist_bg != '' ) {
				if($player_layout == 'skin_boxed_tracklist' || $player_layout == "skin_button"){
					$custom_css .= ' #'.$block_id .' .iron-audioplayer .playlist{ background: '.$tracklist_bg.'; }';
				}else{
					$custom_css .= ' #'.$block_id .' .iron-audioplayer .sonaar-grid{ background: '.$tracklist_bg.'; }';
				} 
			}

			if( $search_color != '' ) {
				$custom_css .= ' #'.$block_id .' .srp_search_container .srp_search, #'.$block_id .' .srp_search_container .fa-search { color: '.$search_color.'; }';
			}
			if( $reset_color != '' ) {
				$custom_css .= ' #'.$block_id .' .srp_search_container .srp_reset_search { color: '.$reset_color.'; }';
			}

			if( $search_placeholder != '' ) {
				$custom_css .= ' #'.$block_id .' .srp_search_container .srp_search::placeholder { color: '.$search_placeholder.'; }';
			}	

			if( $search_background != '' ) {
				$custom_css .= ' #'.$block_id .' .srp_search_container .srp_search { background: '.$search_background.'; }';
			}

			if( $search_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .srp_search_container .srp_search, #'.$block_id .' .srp_search_container { font-size: '.$search_fontsize.'px; }';
			}

			$custom_css .= ' #'.$block_id .' .srp_search_container .srp_search { padding: '. $search_padding_v .'px ' . $search_padding_h . 'px; }';
			
			if( $player_layout != 'skin_button' && $player_bg != '' ) {
				if($player_layout == 'skin_boxed_tracklist'){
					$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp_player_boxed{ background: '.$player_bg.'; }';
		
				}else{
					$custom_css .= ' #'.$block_id .' .iron-audioplayer .album-player{ background: '.$player_bg.'; }';
				}
			}

			if( $trackdesc_fontsize > 0 ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp_track_description { font-size: '.$trackdesc_fontsize.'px; }';
			}

			if( $trackdesc_color != '' ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp_track_description { color: '.$trackdesc_color.'; }';
			}
			if( $metadata_fontsize > 0 ) {
				$custom_css .= ' #'. $block_id .' .sr_it-playlist-publish-date, #'. $block_id .' .srp_playlist_duration, #'. $block_id .' .srp_trackCount { font-size: '. $metadata_fontsize .'px; }';
			}

			if( $metadata_color != '' ) {
				$custom_css .= ' #'. $block_id .' .sr_it-playlist-publish-date, #'. $block_id .' .srp_playlist_duration, #'. $block_id .' .srp_trackCount { color: '. $metadata_color .';}';
			}

			if( $tracklist_controls_color != '' ) {
				$custom_css .= ' #'.$block_id .' .sr-playlist-item .sricon-play { color: '.$tracklist_controls_color.'; }';
			}

			if( $store_title_btshow )  {
				$custom_css .= ' #'.$block_id .' .available-now { display: none; }';
			}

			if( $store_title_color != '' ) {
				$custom_css .= ' #'.$block_id .' .available-now { color: '.$store_title_color.'; }';
			}
			
			if( $button_text_color != '' ) {
				$custom_css .= ' #'.$block_id .' a.button { color: '.$button_text_color.'; }';
			}
			if( $background_color != '' ) {
				$custom_css .= ' #'.$block_id .' a.button { background: '.$background_color.'; }';
			}
			if( $button_hover_color != '' ) {
				$custom_css .= ' #'.$block_id .' a.button:hover { color: '.$button_hover_color.'; }';
			}
			if( $button_background_hover_color != '' ) {
				$custom_css .= ' #'.$block_id .' a.button:hover { background: '.$button_background_hover_color.'; }';
			}
			if( $button_hover_border_color != '' && $button_border_style != 'none' ) {
				$custom_css .= ' #'.$block_id .' a.button:hover { border-color: '.$button_hover_border_color.' !important; }';
			}

			if( $store_icon_show ) {
				$custom_css .= ' #'.$block_id .' .store-list .button i { display: none; }';
			}
			if( $icon_font_size > 0 ) {
				$custom_css .= ' #'.$block_id .' .buttons-block .store-list i { font-size: '.$icon_font_size.'px; }';
				$custom_css .= ' #'.$block_id .' .buttons-block .store-list i { margin-right: '.$icon_indent.'px; }';
			}
			if( $title_soundwave_color != '' )  {
				$custom_css .= ' #'.$block_id .' .track-title, #'.$block_id .' .player, #'.$block_id .' .srp_player_boxed .album-title, #'.$block_id .' .iron-audioplayer .album-player .album-title, #'.$block_id .' .srp_subtitle { color: '.$title_soundwave_color.'; }';
			}
			if( $player_subheading_color != '' )  {
				$custom_css .= ' #'.$block_id .' .srp_subtitle { color: '.$player_subheading_color.'; }';
			}
			if( $title_soundwave_fontsize > 0 )  {
                $custom_css .=  ' #'.$block_id .' .iron-audioplayer .track-title, #'.$block_id .' .srp_player_boxed .album-title { font-size: '. $title_soundwave_fontsize .'px; }'; //Deprecated option, keep for retrocompatibility
			}
			if( $album_title_soundwave_fontsize > 0 )  {
                $custom_css .=  ' #'.$block_id .' .iron-audioplayer .album-player .album-title { font-size: '. $album_title_soundwave_fontsize .'px; }';
			}
			if( $player_subheading_fontsize > 0 )  {
                $custom_css .=  ' #'.$block_id .' .srp_player_boxed .srp_subtitle { font-size: '. $player_subheading_fontsize .'px; }';
			}
			if( ! $soundwave_show && $soundWave_progress_bar_color != '' )  {
				$custom_css .= ' #'.$block_id .' .sonaar_wave_cut rect { fill: '.$soundWave_progress_bar_color.'; }';
				$custom_css .= ' #'.$block_id .' .sr_waveform_simplebar .sonaar_wave_cut { background-color: '.$soundWave_progress_bar_color.'; }';
			}
			if( ! $soundwave_show && $soundWave_bg_bar_color != '' )  {
				$custom_css .= ' #'.$block_id .' .sonaar_wave_base rect { fill: '.$soundWave_bg_bar_color.'; }';
				$custom_css .= ' #'.$block_id .' .sr_waveform_simplebar .sonaar_wave_base { background-color: '.$soundWave_bg_bar_color.'; }';
			}

			if( ! $soundwave_show && ! $duration_soundwave_show && $duration_soundwave_color != '' ) {
				$custom_css .= ' #'.$block_id .' .sr_progressbar { color: '.$duration_soundwave_color.'; }';
			}

			if( $description_color != '' ) {
				$custom_css .= ' #'.$block_id .' .srp_podcast_rss_description { color: '.$description_color.'; }';
			}

			if( $externalLinkButton_bg != '' ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .album-store  { background-color: '.$externalLinkButton_bg.'; }';
			}

			if( ! $hide_track_market && ! $view_icons_alltime && $popover_icons_store != '' ) {
				$custom_css .= ' #'.$block_id .' .playlist .song-store-list-menu .fa-ellipsis-v { color: '.$popover_icons_store.'; }';
			}
			if( ! $hide_track_market && $tracklist_icons_color != '' ) {
				$custom_css .= ' #'.$block_id .' .playlist a.song-store:not(.sr_store_wc_round_bt) { color: '.$tracklist_icons_color.'; }';
			}
			
			if( $audio_player_play_text_color != '' ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp-play-button-label-container { color: '.$audio_player_play_text_color.'; }';	
			}
			
			if( $audio_player_play_text_color_hover != '' ) {
				$custom_css .= ' #'.$block_id .' .iron-audioplayer .srp-play-button-label-container:hover { color: '.$audio_player_play_text_color_hover.'; }';
			}
			if( ! $hide_track_market && $tracklist_icons_spacing > 0 ) {
				$custom_css .= ' #'.$block_id .' .playlist .store-list .song-store-list-container { column-gap: '.$tracklist_icons_spacing.'px; }';
			}
			if( ! $hide_track_market && $tracklist_icons_size > 0 ) {
				$custom_css .= ' #'.$block_id .' .playlist .store-list .song-store .fab, #'.$block_id .' .playlist .store-list .song-store .fas, #'.$block_id .' .playlist .store-list .song-store { font-size: '.$tracklist_icons_size.'px; }';
			}
			

			echo '<style>';
			echo esc_html($custom_css);
			echo '</style>';
		}

		echo '<div id="'. esc_attr($block_id) .'">';
	    echo do_shortcode( $shortcode );
	    echo '</div>';
       return ob_get_clean();
    }
	
	private function sr_plugin_block_attribute() {
		$attributes_pro = array();
		
		$attributes = array(
			'run_pro' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'wc_enable' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'album_id' => array(
				'type' => 'array',
				'default' => [],
				'items'   => [
					'type' => 'integer',
				]
			),
			'cat_id' => array(
				'type' => 'array',
				'default' => [],
				'items'   => [
					'type' => 'integer',
				]
			),
			'player_layout' => array(
				'type' => 'string',
				'default' => 'skin_float_tracklist',
			),
			'show_track_publish_date' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_volume_bt' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_miniplayer_note_bt' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_speed_bt' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_shuffle_bt' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_repeat_bt' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'post_link' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'cta_track_show_label' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_tracks_count' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_meta_duration' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_publish_date' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'show_skip_bt' => array(
				'type' => 'string',
				'default' => 'default',
			),
			'playlist_sources' => array(
				'type'    => 'array',
				'default' => array(
					array(
						'label' => 'Selected Post(s)',
						'value' => 'from_cpt',
					),
					array(
						'label' => 'All Posts',
						'value' => 'from_cat',
					),
					array(
						'label' => 'Current Post',
						'value' => 'from_current_post',
					)
				),
			),
			'playlist_source' => array(
				'type' => 'string',
				'default' => 'from_cpt',
			),
			'playlist_list' => array(
				'type'    => 'array',
				'default' => is_admin() ? $this->sr_plugin_block_select_playlist() : [],
			),
			'playlist_list_cat' => array(
				'type'    => 'array',
				'default' => is_admin() ? $this->sr_plugin_block_select_category() : [],
			),
			'show_cat_description' => array(
				'type'    => 'boolean',
				'default' => false,
			),			
			'posts_per_page' => array(
				'type'    => 'integer',
				'default' => 99,
			),
			'enable_sticky_player' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'enable_shuffle' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'show_searchbar' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'reverse_tracklist' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'enable_scrollbar' => array(
				'type'    => 'boolean',
				'default' => false,
			),				
			'scrollbar_height' => array(
				'type'    => 'integer',
				'default' => 200,
			),
			'track_desc_lenght' => array(
				'type'    => 'integer',
				'default' => 55,
			),
			'playlist_show_playlist' => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'playlist_show_album_market' => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'sr_player_on_artwork' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'playlist_hide_artwork' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'hide_trackdesc' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'strip_html_track_desc' => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'notrackskip' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'playlist_show_soundwave' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'play_current_id' => array(
				'type'    => 'boolean',
				'default' => false,
			),
		);		

			$attributes['layout_settings'] = array(
				'type'    => 'array',
				'default' => array()
			);

			$attributes['html_tags'] = array(
				'type'    => 'array',
				'default' => array(
					array(
						'label' => 'H1',
                    	'value' => 'h1',
					),
					array(
						'label' => 'H2',
                    	'value' => 'h2',
					),
					array(
						'label' => 'H3',
                    	'value' => 'h3',
					),
					array(
						'label' => 'H4',
                    	'value' => 'h4',
					),
					array(
						'label' => 'H5',
                    	'value' => 'h5',
					),
					array(
						'label' => 'H6',
                    	'value' => 'h6',
					),					
					array(
						'label' => 'div',
                    	'value' => 'div',
					),
					array(
						'label' => 'span',
                    	'value' => 'span',
					),
					array(
						'label' => 'p',
                    	'value' => 'p',
					),
				)
			);
			$attributes['sr_text_alignments'] = array(
                'type'    => 'array',
                'default' => array(
                    array(
                        'label' => esc_html__( 'Left', 'sonaar-music' ),
                        'value' => 'left',
                    ),
                    array(
                        'label' => esc_html__( 'Center', 'sonaar-music' ),
                        'value' => 'center',
                    ),
                    array(
                        'label' => esc_html__( 'Right', 'sonaar-music' ),
                        'value' => 'right',
                    ),
                )
			);
			$attributes['sr_text_alignments_default'] = array(
                'type'    => 'array',
                'default' => array(
					array(
                        'label' => esc_html__( 'Default', 'sonaar-music' ),
                        'value' => '',
                    ),
                    array(
                        'label' => esc_html__( 'Left', 'sonaar-music' ),
                        'value' => 'flex-start',
                    ),
                    array(
                        'label' => esc_html__( 'Center', 'sonaar-music' ),
                        'value' => 'center',
                    ),
                    array(
                        'label' => esc_html__( 'Right', 'sonaar-music' ),
                        'value' => 'flex-end',
                    ),
                )
			);
			$attributes['player_layout_options'] = array(
                'type'    => 'array',
                'default' => array(
                    array(
                        'label' => esc_html__( 'floated', 'sonaar-music' ),
                        'value' => 'skin_float_tracklist',
                    ),
                    array(
                        'label' => esc_html__( 'boxed', 'sonaar-music' ),
                        'value' => 'skin_boxed_tracklist',
					), array(
						'label' => esc_html__( 'button', 'sonaar-music' ),
						'value' => 'skin_button',
					)
                )
			);
			$attributes['trueFalseDefault'] = array(
                'type'    => 'array',
                'default' => array(
                    array(
                        'label' => esc_html__( 'Default', 'sonaar-music' ),
                        'value' => 'default',
                    ),
                    array(
                        'label' => esc_html__( 'Yes', 'sonaar-music' ),
                        'value' => 'true',
					),
					array(
                        'label' => esc_html__( 'No', 'sonaar-music' ),
                        'value' => 'false',
                    )
                )
			);
			$attributes['sr_alignments'] = array(
                'type'    => 'array',
                'default' => array(
                    array(
                        'label' => esc_html__( 'Left', 'sonaar-music' ),
                        'value' => 'flex-start',
                    ),
                    array(
                        'label' => esc_html__( 'Center', 'sonaar-music' ),
                        'value' => 'center',
                    ),
                    array(
                        'label' => esc_html__( 'Right', 'sonaar-music' ),
                        'value' => 'flex-end',
                    ),
                )
			);
			$attributes['colors'] = array(
				'type'    => 'array',
				'default' => array(
					array(
                        'name' => esc_html__( 'Black', 'sonaar-music' ),
                        'slug' => 'black',
                        'color' => '#000000'
                    ),
                    array(
                        'name' => esc_html__( 'White', 'sonaar-music' ),
                        'slug' => 'white',
                        'color' => '#ffffff'
                    ),
                    array(
                        'name' => esc_html__( 'Blue', 'sonaar-music' ),
                        'slug' => 'blue',
                        'color' => '#0073aa'
                    ),
				)
			);
			$attributes['border_types'] = array(
				'type'    => 'array',
				'default' => array(
					array(
                        'label' => esc_html__( 'None', 'sonaar-music' ),
                        'value' => 'none',
                    ),
                    array(
                        'label' => esc_html__( 'Solid', 'sonaar-music' ),
                        'value' => 'solid',
                    ),
                    array(
                        'label' => esc_html__( 'Double', 'sonaar-music' ),
                        'value' => 'double',
                    ),					
                    array(
                        'label' => esc_html__( 'Dotted', 'sonaar-music' ),
                        'value' => 'dotted',
                    ),
					array(
                        'label' => esc_html__( 'Dashed', 'sonaar-music' ),
                        'value' => 'dashed',
                    ),
					array(
                        'label' => esc_html__( 'Groove', 'sonaar-music' ),
                        'value' => 'groove',
                    ),
				)
			);

			$attributes['move_playlist_below_artwork'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['track_artwork_show'] = array(
				'type'    => 'boolean',
				'default' => false,
			);			
            $attributes['track_artwork_size'] = array(
                'type' => 'integer',
                'default' => 45,
			);
			$attributes['show_control_on_hover'] = array(
				'type'    => 'boolean',
				'default' => false,
			);	
			$attributes['title_btshow'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['subtitle_btshow'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['title_html_tag_playlist'] = array(
				'type'    => 'string',
				'default' => 'h3',
			);
			$attributes['title_color'] = array(
				'type'    => 'string',
				'default' => '',
			);	
			$attributes['subtitle_color'] = array(
				'type'    => 'string',
				'default' => '',
			);			
			$attributes['track_title_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['tracklist_hover_color'] = array(
				'type'    => 'string',
				'default' => '',
			);	
			$attributes['tracklist_active_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['track_separator_color'] = array(
				'type'    => 'string',
				'default' => '',
			);					
            $attributes['tracklist_spacing'] = array(
                'type' => 'integer',
                'default' => 8,
			);			
			$attributes['duration_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['track_publish_date_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);
			$attributes['track_publish_date_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['tracklist_bg'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['search_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['reset_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['search_placeholder'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['search_background'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['search_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);
			$attributes['player_bg'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['trackdesc_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);
			$attributes['trackdesc_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['metadata_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);
			$attributes['metadata_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
            $attributes['title_align'] = array(
                'type' => 'string',
                'default' => 'left',
			);
			$attributes['button_align'] = array(
                'type' => 'string',
                'default' => '',
			);
			$attributes['hide_player_subheading'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
            $attributes['title_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);
            $attributes['subtitle_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);
            $attributes['track_title_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);
            $attributes['duration_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);
            $attributes['store_title_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);			
            $attributes['store_button_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);	
            $attributes['duration_soundwave_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);			
            $attributes['title_soundwave_fontsize'] = array(  //Deprecated option, keep for retrocompatibility
                'type' => 'integer',
                'default' => 0,
			);	
			$attributes['album_title_soundwave_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);	
			$attributes['player_subheading_fontsize'] = array(
                'type' => 'integer',
                'default' => 0,
			);		
            $attributes['title_indent'] = array(
                'type' => 'integer',
                'default' => 0,
			);
			$attributes['hide_number_btshow'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['hide_time_duration'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['play_pause_bt_show'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['tracklist_controls_color'] = array(
				'type'    => 'string',
				'default' => '',
			);	
            $attributes['tracklist_controls_size'] = array(
                'type' => 'integer',
                'default' => 12,
			);			
			$attributes['hide_track_market'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['view_icons_alltime'] = array(
				'type'    => 'boolean',
				'default' => true,
			);
			$attributes['popover_icons_store'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['tracklist_icons_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['audio_player_play_text_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['audio_player_play_text_color_hover'] = array(
				'type'    => 'string',
				'default' => '',
			);
            $attributes['tracklist_icons_spacing'] = array(
                'type' => 'integer',
                'default' => 0,
			);
            $attributes['tracklist_icons_size'] = array(
                'type' => 'integer',
                'default' => 0,
			);
			$attributes['hide_player_title'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['player_inline'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['title_html_tag_soundwave'] = array(
				'type'    => 'string',
				'default' => 'div',
			);
			$attributes['title_soundwave_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['player_subheading_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['soundwave_show'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['use_play_label'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['use_play_label_with_icon'] = array(
				'type'    => 'boolean',
				'default' => true,
			);
			$attributes['soundWave_progress_bar_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['soundWave_bg_bar_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['progressbar_inline'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['duration_soundwave_show'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['duration_soundwave_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['description_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['externalLinkButton_bg'] = array(
				'type'    => 'string',
				'default' => '',
			);
            $attributes['audio_player_controls_spacebefore'] = array(
                'type' => 'integer',
                'default' => 0,
			);		
			$attributes['play_size'] = array(
                'type' => 'integer',
                'default' => 19,
			);
			$attributes['play_circle_size'] = array(
                'type' => 'integer',
                'default' => 68,
			);
			$attributes['play_circle_width'] = array(
                'type' => 'integer',
                'default' => 6,
			);		
            $attributes['artwork_width'] = array(
                'type' => 'integer',
                'default' => 300,
			);
			$attributes['boxed_artwork_width'] = array(
                'type' => 'integer',
                'default' => 160,
			);
			$attributes['artwork_padding'] = array(
                'type' => 'integer',
                'default' => 0,
			);
            $attributes['artwork_radius'] = array(
                'type' => 'integer',
                'default' => 0,
			);
			$attributes['play_padding_h'] = array(
                'type' => 'integer',
                'default' => 7,
			);
			$attributes['play_padding_v'] = array(
                'type' => 'integer',
                'default' => 7,
			);
			$attributes['search_padding_v'] = array(
                'type' => 'integer',
                'default' => 15,
			);
			$attributes['search_padding_h'] = array(
                'type' => 'integer',
                'default' => 15,
			);
            $attributes['audio_player_artwork_controls_color'] = array(
                'type' => 'string',
                'default' => '',
			);
            $attributes['audio_player_artwork_controls_scale'] = array(
                'type' => 'number',
                'default' => 1,
			);
			$attributes['audio_player_controls_color'] = array(
                'type' => 'string',
                'default' => '',
			);
			$attributes['audio_player_controls_color_hover'] = array(
                'type' => 'string',
                'default' => '',
			);
			$attributes['image_overlay_on_hover'] = array(
                'type' => 'string',
                'default' => '',
			);
            $attributes['playlist_justify'] = array(
                'type' => 'string',
                'default' => 'center',
			);
            $attributes['artwork_align'] = array(
                'type' => 'string',
                'default' => 'center',
			);
            $attributes['playlist_width'] = array(
                'type' => 'integer',
                'default' => 100,
			);
            $attributes['playlist_margin'] = array(
                'type' => 'integer',
                'default' => 0,
			);
            $attributes['tracklist_margin'] = array(
                'type' => 'integer',
                'default' => 0,
			);

			$attributes['store_title_btshow'] = array(
				'type'    => 'boolean',
				'default' => false,
			);			
            $attributes['store_title_text'] = array(
                'type' => 'string',
                'default' => esc_html__('Available now on:', 'sonaar-music'),
			);
			$attributes['store_title_color'] = array(
				'type'    => 'string',
				'default' => '',
			);			
			$attributes['store_title_align'] = array(
				'type'    => 'string',
				'default' => 'center',
			);	
			$attributes['play_text'] = array(
                'type' => 'string',
                'default' => (Sonaar_Music::get_option('labelPlayTxt', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('labelPlayTxt', 'srmp3_settings_widget_player') : 'Play',
			);	
			$attributes['pause_text'] = array(
                'type' => 'string',
                'default' => (Sonaar_Music::get_option('labelPauseTxt', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('labelPauseTxt', 'srmp3_settings_widget_player') : 'Pause',
			);	
			$attributes['widget_id'] = array(
                'type' => 'string',
                'default' => '',
			);		
			$attributes['shortcode_parameters'] = array(
                'type' => 'string',
                'default' => '',
			);					
			$attributes['album_stores_align'] = array(
				'type'    => 'string',
				'default' => 'center',
			);					
			$attributes['button_text_color'] = array(
				'type'    => 'string',
				'default' => '',
			);					
			$attributes['background_color'] = array(
				'type'    => 'string',
				'default' => '',
			);					
			$attributes['button_hover_color'] = array(
				'type'    => 'string',
				'default' => '',
			);					
			$attributes['button_background_hover_color'] = array(
				'type'    => 'string',
				'default' => '',
			);				
			$attributes['button_hover_border_color'] = array(
				'type'    => 'string',
				'default' => '',
			);							
			$attributes['button_border_style'] = array(
				'type'    => 'string',
				'default' => 'none',
			);
			$attributes['button_border_width'] = array(
				'type'    => 'integer',
				'default' => 3,
			);			
			$attributes['button_border_color'] = array(
				'type'    => 'string',
				'default' => 'black',
			);
			$attributes['button_border_radius'] = array(
				'type'    => 'integer',
				'default' => 0,
			);
			$attributes['play_hover_border_color'] = array(
				'type'    => 'string',
				'default' => '',
			);							
			$attributes['play_border_style'] = array(
				'type'    => 'string',
				'default' => 'none',
			);
			$attributes['play_border_width'] = array(
				'type'    => 'integer',
				'default' => 0,
			);			
			$attributes['play_border_color'] = array(
				'type'    => 'string',
				'default' => 'black',
			);
			$attributes['play_border_radius'] = array(
				'type'    => 'integer',
				'default' => 25,
			);
			$attributes['extended_control_btn_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['extended_control_btn_color_hover'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['store_icon_show'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['icon_font_size'] = array(
				'type'    => 'integer',
				'default' => 0,
			);
			$attributes['icon_indent'] = array(
				'type'    => 'integer',
				'default' => 10,
			);
			$attributes['album_stores_padding'] = array(
				'type'    => 'integer',
				'default' => 22,
			);

			$attributes['enable_sticky_player'] = array(
				'type'    => 'boolean',
				'default' => true,
			);
			$attributes['enable_shuffle'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['reverse_tracklist'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['enable_scrollbar'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$attributes['scrollbar_height'] = array(
				'type'    => 'integer',
				'default' => 200,
			);
			$attributes['wc_bt_show'] = array(
				'type'    => 'boolean',
				'default' => true,
			);
			$attributes['wc_icons_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$attributes['wc_icons_bg_color'] = array(
				'type'    => 'string',
				'default' => '',
			);
			

		if ( function_exists( 'run_sonaar_music_pro' ) ) {

			$attributes['run_pro'] = array(
				'type'    => 'boolean',
				'default' => true,
			);

			if( Sonaar_Music::get_option('wc_bt_type', 'srmp3_settings_woocommerce') != 'wc_bt_type_inactive' && ( defined( 'WC_VERSION' ) && get_site_option('SRMP3_ecommerce') == '1' ) ){

				$attributes['wc_enable'] = array(
					'type'    => 'boolean',
					'default' => true,
				);
			}
		}
		return $attributes;
	}


    private function sr_plugin_block_select_playlist() {
        $sr_playlist_list = get_posts(array(
            'post_type' => SR_PLAYLIST_CPT,
            'showposts' => 999,
        ));
        $options = array();

        if ( ! empty( $sr_playlist_list ) && ! is_wp_error( $sr_playlist_list ) ){
            
            foreach ( $sr_playlist_list as $post ) {
                $options[] = array(
                    'label' => $post->post_title,
                    'value' => $post->ID,
                );
            }
        } else {
            $options[0] = esc_html__( 'Create a Playlist First', 'sonaar-music' );
        }
        return $options;
	}

	private function sr_plugin_block_select_category() {
		$taxonomies = array('playlist-category');

		if (defined( 'WC_VERSION' )){
			array_push($taxonomies, 'product_cat');
		}
		if ( Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
			array_push($taxonomies, 'podcast-show');
		}
		$args = array(
			'taxonomy'=> $taxonomies,
		);
		$sr_category_list = get_terms( $args );
		$options = array();
		if ( ! empty( $sr_category_list ) && ! is_wp_error( $sr_category_list ) ){
			foreach ( $sr_category_list as $category ) {
				$options[] = array(
                    'label' => $category->name,
                    'value' => $category->term_id,
                );				
			}
		} else {
			$options[0] = esc_html__( 'Create a Category First', 'elementor-sonaar' );
		}
		return $options;
	}

}

new Sonaar_Block();
