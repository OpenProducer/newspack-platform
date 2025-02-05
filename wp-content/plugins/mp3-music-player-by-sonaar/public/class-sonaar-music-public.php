<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       sonaar.io
 * @since      1.0.0
 *
 * @package    Sonaar_Music
 * @subpackage Sonaar_Music/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sonaar_Music
 * @subpackage Sonaar_Music/public
 * @author     Edouard Duplessis <eduplessis@gmail.com>
 */


class Sonaar_Music_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */

	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function sr_generateTypoStyle($typoSettings, $selector) { //convert typography setting to css line style
		$cssLines = '';
		$cssLines .= ( isset($typoSettings['font-family']) && $typoSettings['font-family'] !== '' )? $this->sr_addFontStyle( $typoSettings['font-family'], $selector ) :'';
		$cssLines .= ( isset($typoSettings['font-size']) && $typoSettings['font-size'] !== '' )? $selector . '{ font-size:' . $typoSettings['font-size'] . ';}' :'';
		$cssLines .= ( isset($typoSettings['color']) && $typoSettings['color'] !== '' )? $selector . '{ color:' . $typoSettings['color'] . ';}' :'';
		return $cssLines;
	}
	public function sr_addFontStyle($formatedFontfamily, $selector) {
		$fontItalic = false;
		$formatedFontfamily = str_replace('_safe_', '',$formatedFontfamily);
		$formatedFontfamily = str_replace('+', ' ',$formatedFontfamily);
		if( strstr( $formatedFontfamily, ':' ) ){
			
			$fontWeightAndStyle = substr($formatedFontfamily, strpos($formatedFontfamily, ":") + 1); 
			
			if( strstr( $fontWeightAndStyle, 'italic' ) ){
				$fontItalic = true;
				$fontWeight = strstr( $fontWeightAndStyle, 'italic', true); 
			}else{
				$fontWeight = $fontWeightAndStyle;
			}
			if($fontWeight == 'regular'){
				$fontWeight = '400';
			}

			$formatedFont= array(
				'family' => strstr( $formatedFontfamily, ':', true), 
				'weight' => $fontWeight,
				'italic' => $fontItalic
			);
			
			$result = $selector . '{ font-family:' . $formatedFont['family'] . '; font-weight:' . $formatedFont['weight'].';';
			$result .=  ( $formatedFont['italic'] )?'font-style:italic;':'';
			$result .=  '}';
			
		}else{
			$result = $selector . '{ font-family:' . $formatedFontfamily . '}';
		}	
		return  $result;
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $post;


		wp_register_style( 'sonaar-music', plugin_dir_url( __FILE__ ) . 'css/sonaar-music-public.css', array(), $this->version, 'all' );
		$data = "";
		
	
		if ( 
		(is_single() && get_post_type() == SR_PLAYLIST_CPT ) || // Enqueue Sonaar Music css file on single Album Page
		class_exists('Iron_sonaar') //If Sonaar Theme is activated
		){
			wp_enqueue_style( 'sonaar-music' );
			wp_enqueue_style( 'sonaar-music-pro' );
		}
		$font = Sonaar_Music::get_option('music_player_playlist', 'srmp3_settings_widget_player');
		$fontTitle = Sonaar_Music::get_option('music_player_album_title', 'srmp3_settings_widget_player');
		$metaTitle = Sonaar_Music::get_option('music_player_metas', 'srmp3_settings_widget_player');
		$fontdate = Sonaar_Music::get_option('music_player_date', 'srmp3_settings_widget_player');
		$fontExcerpt = Sonaar_Music::get_option('player_track_desc_style', 'srmp3_settings_widget_player');

		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$fontStickyPlayer = Sonaar_Music::get_option('sticky_player_typo', 'srmp3_settings_sticky_player');
			$data .= ( isset($fontStickyPlayer['font-family']) && $fontStickyPlayer['font-family'] != NULL && !strpos($fontStickyPlayer['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $fontStickyPlayer['font-family'] .');':'';
		}
		$data .= ( isset($font['font-family']) && $font['font-family'] != NULL && !strpos($font['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $font['font-family'] .');':'';
		$data .= ( isset($fontTitle['font-family']) && $fontTitle['font-family'] != NULL && !strpos($fontTitle['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $fontTitle['font-family'] .');':'';
		$data .= ( isset($fontdate['font-family']) && $fontdate['font-family'] != NULL && !strpos($fontdate['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $fontdate['font-family'] .');':'';
		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$data .= ( isset($fontExcerpt['font-family']) && $fontExcerpt['font-family'] != NULL && !strpos($fontExcerpt['font-family'], '_safe_') )?'@import url(//fonts.googleapis.com/css?family='. $fontExcerpt['font-family'] .');':'';
		}
			
		if( isset($font['font-family']) && $font['font-family'] !== ''){
			$data .= $this->sr_addFontStyle( $font['font-family'], '.iron-audioplayer .playlist .audio-track, .iron-audioplayer .srp_notfound, .iron-audioplayer.srp_has_customfields .sr-cf-heading, .iron-audioplayer .sr-playlist-cf-container, .iron-audioplayer .track-title, .iron-audioplayer .album-store, .iron-audioplayer  .playlist .track-number, .iron-audioplayer .sr_it-playlist-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .track-title');
		}
		$data .= (  isset($font['font-size']) && $font['font-size'] !== '' )? '.iron-audioplayer  .playlist .audio-track, .iron-audioplayer .srp_notfound, .iron-audioplayer .track-title, .iron-audioplayer .album-store, .iron-audioplayer  .playlist .track-number, .iron-audioplayer .sr_it-playlist-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .track-title{ font-size:' . $font['font-size'] . ';}' :'';
		$data .= ( isset($font['color']) && $font['color'] !== '' )? '.iron-audioplayer .playlist .audio-track, .iron-audioplayer .srp_notfound, .iron-audioplayer .playlist .srp_pagination, .iron-audioplayer.srp_has_customfields .sr-cf-heading, .iron-audioplayer .sr-playlist-cf-container, .iron-audioplayer .track-title, .iron-audioplayer .album-store, .iron-audioplayer  .playlist .track-number, .iron-audioplayer .sr_it-playlist-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .track-title, .sr-playlist-item .srp_noteButton{ color:' . $font['color'] . ';}' :'';
		
		$data .= ( isset($font['color']) && $font['color'] !== '' )? ':root {--srp-global-tracklist-color: ' . $font['color'] . ';}':':root {--srp-global-tracklist-color: #000000;}';
		$data .= ( isset($font['font-size']) && $font['font-size'] !== '' )? ':root {--srp-global-tracklist-font-size: ' . $font['font-size'] . ';}':'';
		
		if( isset($fontTitle['font-family']) && $fontTitle['font-family'] !== ''){
			$data .= $this->sr_addFontStyle( $fontTitle['font-family'], '.iron-audioplayer .sr_it-playlist-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .album-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .track-title, .srp-play-button-label-container' );
		}

		$data .= ( isset($fontTitle['font-size']) && $fontTitle['font-size'] !== '' )? '.iron-audioplayer .sr_it-playlist-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .album-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .track-title, .srp_miniplayer_metas .srp_meta.track-title, .srp_miniplayer_metas .srp_meta.album-title{ font-size:' . $fontTitle['font-size'] . ';}' :'';
		$data .= ( isset($fontTitle['color']) && $fontTitle['color'] !== '' )? ' .iron-audioplayer .sr_it-playlist-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .album-title, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .track-title, .iron-audioplayer .srp_player_meta, .srp_miniplayer_metas, .srp_miniplayer_metas .srp_meta.track-title, .srp_miniplayer_metas .srp_meta.album-title { color:' . $fontTitle['color'] . ';}' :'';	
		
		if( isset($metaTitle['font-family']) && $metaTitle['font-family'] !== ''){
			$data .= $this->sr_addFontStyle( $metaTitle['font-family'], '.srp_miniplayer_metas' );
		}
		$data .= ( isset($metaTitle['font-size']) && $metaTitle['font-size'] !== '' )? ' .srp_miniplayer_metas .srp_meta{ font-size:' . $metaTitle['font-size'] . ';}' :'';
		$data .= ( isset($metaTitle['color']) && $metaTitle['color'] !== '' )? ' .srp_miniplayer_metas { color:' . $metaTitle['color'] . ';}' :'';
		
		if( isset($fontdate['font-family']) && $fontdate['font-family'] !== ''){
			$data .= $this->sr_addFontStyle( $fontdate['font-family'], '.iron-audioplayer .srp_subtitle' );
		}	
		$data .= ( isset($fontdate['font-size']) && $fontdate['font-size'] !== '' )? '.iron-audioplayer .srp_subtitle{ font-size:' . $fontdate['font-size'] . ';}' :'';
		$data .= ( isset($fontdate['color']) && $fontdate['color'] !== '' )? '.iron-audioplayer .srp_subtitle{ color:' . $fontdate['color'] . ';}' :'';
		
		if ( function_exists( 'run_sonaar_music_pro' ) ){
			//set color typography styles		
			if( isset($fontExcerpt['font-family']) && $fontExcerpt['font-family'] !== ''){
				$data .= $this->sr_addFontStyle( $fontExcerpt['font-family'], '.srp_track_description' );
			}	
			$data .= ( isset($fontExcerpt['font-size']) && $fontExcerpt['font-size'] !== '' )? '.srp_track_description{ font-size:' . $fontExcerpt['font-size'] . ';}' :'';
			$data .= ( isset($fontExcerpt['color']) && $fontExcerpt['color'] !== '' )? '.srp_track_description{ color:' . $fontExcerpt['color'] . ';}' :'';

			if( isset($fontStickyPlayer['font-family']) && $fontStickyPlayer['font-family'] !== ''){
				$data .= $this->sr_addFontStyle( $fontStickyPlayer['font-family'], '#sonaar-player' );
			}
			$data .= ( isset($fontStickyPlayer['font-size']) && $fontStickyPlayer['font-size'] !== '' )? '#sonaar-player{ font-size:' . $fontStickyPlayer['font-size'] . ';}' :'';
			$data .= ( isset($fontStickyPlayer['color']) && $fontStickyPlayer['color'] !== '' )? 'div#sonaar-player{ color:' . $fontStickyPlayer['color'] . ';}' :'';
			$data .= ( isset($fontStickyPlayer['color']) && $fontStickyPlayer['color'] !== '' )? '#sonaar-player.sr-float .close.btn-player.enable:after, #sonaar-player.sr-float .close.btn-player.enable:before{ border-color:' . $fontStickyPlayer['color'] . '!important;}' :'';
			$data .= ( isset($fontStickyPlayer['color']) && $fontStickyPlayer['color'] !== '' )? '#sonaar-player.sr-float .close.btn-player rect{ fill:' . $fontStickyPlayer['color'] . ';}' :'';
			
			$sticky_player_featured_color = Sonaar_Music::get_option('sticky_player_featured_color', 'srmp3_settings_sticky_player');
			//set Featured Color styles
			$data .= ( $sticky_player_featured_color !== '' )? '#sonaar-player .player, #sonaar-player .player .volume .slider-container, #sonaar-player .close.btn_playlist:before, #sonaar-player .close.btn_playlist:after{border-color:' . $sticky_player_featured_color . ';}' : '';
			$data .= ( $sticky_player_featured_color !== '' )? '#sonaar-player .player .volume .slider-container:before{border-top-color:' . $sticky_player_featured_color . ';}' : '';
			$data .= ( $sticky_player_featured_color !== '' )? '#sonaar-player .playlist button.play, #sonaar-player .close.btn-player, #sonaar-player .mobileProgress, #sonaar-player .ui-slider-handle, .ui-slider-range{background-color:' . $sticky_player_featured_color . ';}' : '';
			$data .= ( $sticky_player_featured_color !== '' )? '#sonaar-player .playlist .tracklist li.active, #sonaar-player .playlist .tracklist li.active span, #sonaar-player .playlist .title, .srmp3_singning p[begin]:not(.srmp3_lyrics_read ~ p){color:' . $sticky_player_featured_color . ';}' : '';
			
			$sticky_player_labelsandbuttons = Sonaar_Music::get_option('sticky_player_labelsandbuttons', 'srmp3_settings_sticky_player');
			// set Labels and Buttons styles
			$data .= ( $sticky_player_labelsandbuttons !== '' )? '#sonaar-player .player .timing, #sonaar-player .album-title, #sonaar-player .playlist .tracklist li, #sonaar-player .playlist .tracklist li a, #sonaar-player .player .store .track-store li a, #sonaar-player .track-store li, #sonaar-player .sonaar-extend-button, #sonaar-player .sr_skip_number{color:' . $sticky_player_labelsandbuttons . ';}' : '';
			$data .= ( $sticky_player_labelsandbuttons !== '' )? '#sonaar-player .player .store .track-store li .sr_store_round_bt, #sonaar-player .ui-slider-handle, #sonaar-player .ui-slider-range{background-color:' . $sticky_player_labelsandbuttons . ';}' : '';
			$data .= ( $sticky_player_labelsandbuttons !== '' )? '#sonaar-player .control, #sonaar-player .sricon-volume {color:' . $sticky_player_labelsandbuttons . ';}' : '';
			$data .= ( $sticky_player_labelsandbuttons !== '' )? '#sonaar-player div.sr_speedRate div{background:' . $sticky_player_labelsandbuttons . ';}' : '';
			
			$sticky_player_background = Sonaar_Music::get_option('sticky_player_background', 'srmp3_settings_sticky_player');
			// set sticky background color
			$data .= ( $sticky_player_background !== '' )? 'div#sonaar-player, #sonaar-player .player, #sonaar-player .player .volume .slider-container, #sonaar-player.sr-float div.playlist, #sonaar-player.sr-float .close.btn-player, #sonaar-player.sr-float .player.sr-show_controls_hover .playerNowPlaying, .srp_extendedPlayer{background-color:' . $sticky_player_background . ';}' : '';
			$data .= ( $sticky_player_background !== '' )? '@media only screen and (max-width: 1025px){#sonaar-player .store{background-color:' . $sticky_player_background . ';}}' : '';
			$data .= ( $sticky_player_background !== '' )? '#sonaar-player .player .volume .slider-container:after{border-top-color:' . $sticky_player_background . ';}' : '';
			$data .= ( $sticky_player_background !== '' )? '#sonaar-player .playlist button.play, #sonaar-player .player .store .track-store li .sr_store_round_bt{color:' . $sticky_player_background . ';}' : '';
			$data .= ( $sticky_player_background !== '' )? '#sonaar-player .close.btn-player rect{fill:' . $sticky_player_background . ';}' : '';
			$data .= ( $sticky_player_background !== '' )? '#sonaar-player .close.btn-player.enable:after, #sonaar-player .close.btn-player.enable:before{border-color:' . $sticky_player_background . '!important;}' : '';
			$data .= ( $sticky_player_background !== '' )? '#sonaar-player.sr-float .close.btn-player.enable:after, #sonaar-player.sr-float .close.btn-player.enable:before{border-color:' . Sonaar_Music::get_option('sticky_player_labelsandbuttons') . '!important;}' : '';
			$data .= ( $sticky_player_background !== '' )? '#sonaar-player div.sr_speedRate div{color:' . $sticky_player_background . ';}' : '';
			$data .= ( $sticky_player_background !== '' )? '#sonaar-player .mobilePanel, #sonaar-player .player .player-row:before{background-color:' . $sticky_player_background . ';}' : '';
			$data .= ( $sticky_player_background !== '' )? '#sonaar-player .player div.mobilePanel{border-color:' . $sticky_player_background . ';}' : '';

			// set sticky Mobile Progress bar color
			$data .= ( $sticky_player_featured_color !== '' )? '#sonaar-player .mobileProgressing, #sonaar-player .progressDot{background-color:' . Sonaar_Music::get_option('sticky_player_soundwave_progress_bars', 'srmp3_settings_sticky_player') . ';}' : '';
			if( Sonaar_Music::get_option('mobile_progress_bars', 'srmp3_settings_sticky_player' ) != '' ){
				$data .= ( $sticky_player_featured_color !== '' )? '#sonaar-player div.mobileProgressing, #sonaar-player div.progressDot{background-color:' . Sonaar_Music::get_option('mobile_progress_bars', 'srmp3_settings_sticky_player') . ';}' : '';
			}
			
			if( Sonaar_Music::get_option('sticky_preset', 'srmp3_settings_sticky_player') == 'float' ){
				$sticky_float_radius = Sonaar_Music::get_option('float_radius', 'srmp3_settings_sticky_player');
				$data .= ( $sticky_float_radius !== '' )? 'div#sonaar-player.sr-float .player, #sonaar-player.sr-float .player.sr-show_controls_hover .playerNowPlaying{border-radius:' . $sticky_float_radius . 'px;}' : '';
				$data .= ( $sticky_float_radius !== '' )? 'div#sonaar-player.sr-float .album-art img:last-child{border-radius:' . $sticky_float_radius . 'px 0px 0px ' . $sticky_float_radius . 'px;}' : '';
			}

			// CTA Pop-up
			$ctaPopupFont = Sonaar_Music::get_option('cta-popup-typography', 'srmp3_settings_popup' );
			$data .= $this->sr_generateTypoStyle($ctaPopupFont, 'div#sonaar-modal, article.srp_note, div#sonaar-modal h1, div#sonaar-modal h2, div#sonaar-modal h3, div#sonaar-modal h4, div#sonaar-modal h5, div#sonaar-modal label');
			$data .= ( Sonaar_Music::get_option('cta-popup-background', 'srmp3_settings_popup') !== '' )? 'div#sonaar-modal .sr_popup-content, .iron-audioplayer .srp_note{background-color:' . Sonaar_Music::get_option('cta-popup-background', 'srmp3_settings_popup') . ';}' : '';
			$data .= ( Sonaar_Music::get_option('cta-popup-close-btn-color', 'srmp3_settings_popup') !== '' )? '.sr_close svg{fill:' . Sonaar_Music::get_option('cta-popup-close-btn-color', 'srmp3_settings_popup') . ';}' : '';
		}
		


		$data .= ( Sonaar_Music::get_option('music_player_bgcolor', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .srp_player_boxed, .single-album .iron-audioplayer[data-playertemplate="skin_float_tracklist"] .album-player{background:' . Sonaar_Music::get_option('music_player_bgcolor', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_playlist_bgcolor', 'srmp3_settings_widget_player') !== '' )? '.iron_widget_radio:not(.srp_player_button) .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .playlist, .single-album .iron-audioplayer[data-playertemplate="skin_float_tracklist"] .sonaar-grid{background:' . Sonaar_Music::get_option('music_player_playlist_bgcolor', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('labelPlayColor', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .srp-play-button-label-container{color:' . Sonaar_Music::get_option('labelPlayColor', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_featured_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .playlist .current .audio-track, .playlist .current .track-number{color:' . Sonaar_Music::get_option('music_player_featured_color', 'srmp3_settings_widget_player') . ';}' : '';
		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$data .= ( Sonaar_Music::get_option('music_player_wc_bt_bgcolor', 'srmp3_settings_widget_player') !== '') ? '.iron-audioplayer .playlist a.song-store:not(.sr_store_wc_round_bt){color:' . Sonaar_Music::get_option('music_player_wc_bt_bgcolor', 'srmp3_settings_widget_player') . ';}' : '';
		}
		$data .= ( Sonaar_Music::get_option('music_player_store_drawer', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer  .playlist .song-store-list-menu .fa-ellipsis-v{color:' . Sonaar_Music::get_option('music_player_store_drawer', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_featured_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer  .playlist .audio-track path, .iron-audioplayer  .playlist .sricon-play{color:' . Sonaar_Music::get_option('music_player_featured_color', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .control .sricon-play, .srp-play-button .sricon-play, .srp_pagination .active{color:' . Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .srp-play-circle{border-color:' . Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .control, .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .sr_progressbar, .srp_player_boxed .srp_noteButton{color:' . Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .sr_speedRate div{border-color:' . Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .srp-play-button-label-container, .iron-audioplayer .ui-slider-handle, .iron-audioplayer .ui-slider-range{background:' . Sonaar_Music::get_option('music_player_icon_color', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_artwork_icon_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer.sr_player_on_artwork .sonaar-Artwort-box .control [class*="sricon-"]{color:' . Sonaar_Music::get_option('music_player_artwork_icon_color', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_artwork_icon_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer.sr_player_on_artwork .sonaar-Artwort-box .control .play{border-color:' . Sonaar_Music::get_option('music_player_artwork_icon_color', 'srmp3_settings_widget_player') . ';}' : '';
		$music_player_timeline_color = Sonaar_Music::get_option('music_player_timeline_color', 'srmp3_settings_widget_player');
		$music_player_progress_color = Sonaar_Music::get_option('music_player_progress_color', 'srmp3_settings_widget_player');
		$sticky_music_player_soundwave_bars = Sonaar_Music::get_option('sticky_player_soundwave_bars', 'srmp3_settings_sticky_player');
		$sticky_music_player_soundwave_progress_bars = Sonaar_Music::get_option('sticky_player_soundwave_progress_bars', 'srmp3_settings_sticky_player');
		$data .= ( Sonaar_Music::get_option('music_player_wc_bt_color', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .song-store.sr_store_wc_round_bt{color:' . Sonaar_Music::get_option('music_player_wc_bt_color', 'srmp3_settings_widget_player') . ';}' : '';
		$data .= ( Sonaar_Music::get_option('music_player_wc_bt_bgcolor', 'srmp3_settings_widget_player') !== '' )? '.iron-audioplayer .song-store.sr_store_wc_round_bt{background-color:' . Sonaar_Music::get_option('music_player_wc_bt_bgcolor', 'srmp3_settings_widget_player') . ';}' : '';

		if( Sonaar_Music::get_option('player_hide_track_number', 'srmp3_settings_widget_player') == 'true' ){
			$data .= ' #sonaar-player .playlist .tracklist .track-status{ visibility: hidden; }';
		}else{
			$data .= ' @media screen and (max-width: 540px){ #sonaar-player .playlist .tracklist span.track-title, #sonaar-player .playlist .tracklist span.track-artist, #sonaar-player .playlist .tracklist span.track-album{ padding-left: 35px; } }';
		}

        //WC VARIATION
        $data .= '.srp-modal-variation-list .srp-modal-variant-selector {background-color:' . Sonaar_Music::get_option('cta-popup-variant-bg-color', 'srmp3_settings_popup') . ';}';
        $data .= '.srp-modal-variation-list .srp-modal-variant-selector:hover, .srp-modal-variation-list .srp-modal-variant-selector.srp_selected {background-color:' . Sonaar_Music::get_option('cta-popup-variant-ac-color', 'srmp3_settings_popup') . ';}';
        $data .= '#sonaar-modal .srp_button {background-color:' . Sonaar_Music::get_option('cta-popup-btn-bg-color', 'srmp3_settings_popup') . ';}';
        $data .= '#sonaar-modal .srp_button {color:' . Sonaar_Music::get_option('cta-popup-btn-txt-color', 'srmp3_settings_popup') . ';}';
		$data .= ':root {
			--srp-global-sticky_player_featured_color: ' . Sonaar_Music::get_option('sticky_player_featured_color', 'srmp3_settings_sticky_player') . ';
			--srp-global-sticky_player_waveform_progress_color: ' . Sonaar_Music::get_option('sticky_player_soundwave_progress_bars', 'srmp3_settings_sticky_player') . ';
			--srp-global-sticky_player_waveform_background_color: ' . Sonaar_Music::get_option('sticky_player_soundwave_bars', 'srmp3_settings_sticky_player') . ';
			--srp-global-sticky_player_labelsandbuttons: ' . Sonaar_Music::get_option('sticky_player_labelsandbuttons', 'srmp3_settings_sticky_player') . ';
			--srp-global-sticky_player_background: ' . Sonaar_Music::get_option('sticky_player_background', 'srmp3_settings_sticky_player') . ';
			--srp-global-music_player_wc_bt_color: ' . Sonaar_Music::get_option('music_player_wc_bt_color', 'srmp3_settings_widget_player') . ';
			--srp-global-music_player_wc_bt_bgcolor: ' . Sonaar_Music::get_option('music_player_wc_bt_bgcolor', 'srmp3_settings_widget_player') . ';
			--srp-global-modal-btn-txt-color: ' . Sonaar_Music::get_option('cta-popup-btn-txt-color', 'srmp3_settings_popup') . ';
			--srp-global-modal-btn-bg-color: ' . Sonaar_Music::get_option('cta-popup-btn-bg-color', 'srmp3_settings_popup') . ';
			--srp-global-modal-form-input-bg-color: ' . Sonaar_Music::get_option('cta_popup_form_input_background', 'srmp3_settings_popup') . ';
			--srp-global-modal-form-input-border-color: ' . Sonaar_Music::get_option('cta_popup_form_input_border', 'srmp3_settings_popup') . ';
			--srp-global-modal-form-input-color: ' . Sonaar_Music::get_option('cta_popup_form_input_color', 'srmp3_settings_popup') . ';
			
		  }';
		if( $music_player_progress_color !== '' ){
			$data .= '.iron-audioplayer .sonaar_fake_wave .sonaar_wave_cut rect{fill:' . $music_player_progress_color . ';}';
			$data .= '#sonaar-player .sonaar_fake_wave .sonaar_wave_base rect{fill:' . $sticky_music_player_soundwave_bars . ';}';
			$data .= '#sonaar-player .mobileProgress{background-color:' . $sticky_music_player_soundwave_bars . ';}';
			$data .= '#sonaar-player .sonaar_fake_wave .sonaar_wave_cut rect{fill:' . $sticky_music_player_soundwave_progress_bars . ';}';
			if (Sonaar_Music::get_option('waveformType', 'srmp3_settings_general') === 'simplebar'){
				$data .= '.iron-audioplayer .sr_waveform_simplebar .sonaar_wave_base{background-color:' . $music_player_timeline_color . ';}';
				$data .= '.iron-audioplayer .sr_waveform_simplebar .sonaar_wave_cut{background-color:' . $music_player_progress_color . ';}';
				$data .= '#sonaar-player .sonaar_fake_wave .sonaar_wave_base {background-color:' . $sticky_music_player_soundwave_bars . ';}';
				$data .= '#sonaar-player .sonaar_fake_wave .sonaar_wave_cut {background-color:' . $sticky_music_player_soundwave_progress_bars . ';}';
			}
		}
		wp_add_inline_style( $this->plugin_name, $data );
		
	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// other scripts
		wp_register_script( 'sonaar-music', plugin_dir_url( __FILE__ ) . 'js/sonaar-music-public.js', array( 'jquery' ), $this->version, true );		
		wp_register_script( 'moments', plugin_dir_url( __FILE__ ) . 'js/iron-audioplayer/00.moments.min.js', array(), $this->version, true );
		wp_register_script( 'sonaar-music-mp3player', plugin_dir_url( __FILE__ ) . 'js/iron-audioplayer/iron-audioplayer.js', array( 'jquery', 'sonaar-music' ,'moments'), $this->version, true );
		wp_register_script( 'sonaar-music-scrollbar', plugin_dir_url( __FILE__ ) . 'js/perfect-scrollbar.min.js', array( 'jquery' ), $this->version, false );
		
		//check if constant 'SRMP3PRO_VERSION' is < 5
		if( function_exists('run_sonaar_music_pro') && defined('SRMP3PRO_VERSION') && version_compare(SRMP3PRO_VERSION, '5.0.0', '<') ){
			wp_register_script( 'wave', plugins_url( 'sonaar-music-pro/public/js/iron-audioplayer/00.wavesurfer.min.js' ), array(), $this->version, true );
		}
		
		/* Enqueue Sonaar Music mp3player js file on single Album Page */
		if ( is_single() && get_post_type() == SR_PLAYLIST_CPT ) {
			wp_enqueue_script( 'sonaar-music-mp3player' );			
		}
		
		wp_localize_script( $this->plugin_name . '-mp3player', 'sonaar_music', array(
			'plugin_version_free'	=> $this->version,
			'plugin_dir_url_free'	=> SRMP3_DIR_PATH,
			'plugin_version_pro'	=> ( defined( 'SRMP3PRO_VERSION' ) ? SRMP3PRO_VERSION : 'Not Installed' ),
			'plugin_dir_url'		=> plugin_dir_url( __FILE__ ),
			'option' 				=> Sonaar_Music::get_option( 'allOptions' ),
			'ajax' => array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'sonaar_music_ajax_nonce' ),
				'ajax_nonce_peaks' => wp_create_nonce( 'sonaar_music_ajax_peaks_nonce' ),
			),
		));

	}
	
	public function srbodyclass(){ // add special class if onair2 theme is used
		return ['qt-html5audio-disable'];
	}
	public function editor_enqueue_scripts() {
		/* Enqueue Sonaar Music related CSS and Js file */
		global $pagenow;
		if ( !is_admin()  && !isset($_REQUEST['elementor-preview']) ) {			
			return;
		}
		
		wp_enqueue_style( 'sonaar-music' );
		wp_enqueue_style( 'sonaar-music-pro' );
		wp_enqueue_script( 'sonaar-music-mp3player' );
		wp_enqueue_script( 'sonaar-music-pro-mp3player' );
		wp_enqueue_script( 'sonaar_player' );

		if(function_exists('run_sonaar_music_pro')){
			wp_enqueue_script( 'sonaar-list' );
		}

		if ( function_exists('sonaar_player') ) {
			add_action('wp_footer','sonaar_player', 12);
		}
	}
	public function srp_rsscron($arg){
		if ( !function_exists('run_sonaar_music_pro') || !get_site_option( 'sonaar_music_licence', '' )){
			return;
		}
		if (get_site_option('SRMP3_License_Status') != 'active') {
            return;
        }
		//Hook name: sonaar_podcast_import

		// Debugging purpose :
		/* $file = plugin_dir_path( __DIR__ ).'/somefile.txt';
 		file_put_contents($file, $arg, FILE_TEXT ); */

		// Set up the environment
		if ( ! defined('ABSPATH') ) {
		    require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );
		}
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		define( 'WP_LOAD_IMPORTERS', true );

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sonaar-podcast-rss-import.php';
		// run function
		Sonaar_RSS_Import::run_import( esc_html($arg['feed_url']), $import_category = esc_attr($arg['cat_id']), $import_attachments = true, $import_settings = false );
	}

}
