<?php
/**
* Radio Widget Class
*
* @since 1.6.0
* @todo  - Add options
*/

class Sonaar_Music_Widget extends WP_Widget{
    /**
    * Widget Defaults
    */
    
    public static $widget_defaults;

    private $sr_playlist_cpt;
    private $cta_download_visibility;
    private $cta_share_visibility;
    private $cta_favorite_visibility;
    private $shortcodeParams;
    private $cf_dataSort;
    /**
    * Register widget with WordPress.
    */
    
    function __construct (){        
        $widget_ops = array(
        'classname'   => 'sonaar_music_widget',
        'description' => esc_html_x('A simple radio that plays a list of songs from selected albums.', 'Widget', 'sonaar-music')
        );
        
        self::$widget_defaults = array(
            'title'        => '',
            'store_title_text' => '',
            'albums'     	 => array(),
            'hide_artwork' => false,
            'sticky_player' => 0,
            'show_album_market' => 0,
            'show_track_market' => 0,
            //'remove_player' => 0, // deprecated and replaced by hide_timeline
            'hide_timeline' =>0,
        
            
        );
        add_filter( 'srmp3_track_title', array($this, 'srmp3_track_title' ), 10, 3);
        add_action('srmp3player_after_register_post_type', function () {
            $this->sr_playlist_cpt =  (defined( 'SR_PLAYLIST_CPT' )) ? SR_PLAYLIST_CPT : 'sr_playlist';
            if ( isset($_GET['load']) && $_GET['load'] == 'playlist.json' ) {     
                $this->print_playlist_json();
            }
        });
        parent::__construct('sonaar-music', esc_html_x('Sonaar: Music Player', 'Widget', 'sonaar-music'), $widget_ops);
        
    }
    public function srmp3_track_title($track_title, $mp3_id, $audioSrc){
        if (Sonaar_Music::get_option('use_filenames', 'srmp3_settings_general') === 'true') {
            if (Sonaar_Music::get_option('hide_extension', 'srmp3_settings_general') === 'true') {
                $track_title = pathinfo($audioSrc, PATHINFO_FILENAME);
            } else {
                $track_title = basename($audioSrc);
            }
        }
        //add filter to filter the track_title
        return $track_title;
    }
    /**
    * Front-end display of widget.
    */
    public function widget ( $args, $instance ){
            $instance = apply_filters( 'srmp3_add_shortcode_params', $instance );
            $this->shortcodeParams = wp_parse_args( (array) $instance, self::$widget_defaults );
            $this->sr_playlist_cpt =  (defined( 'SR_PLAYLIST_CPT' )) ? SR_PLAYLIST_CPT : 'sr_playlist';
            $widget_id = (isset($this->shortcodeParams['id']))? $this->shortcodeParams['id']: $args["widget_id"];
            $widget_id = sanitize_key($widget_id);
            $elementor_widget = (bool)( isset( $this->shortcodeParams['elementor'] ) )? true: false; //Return true if the widget is set in the elementor editor 
            $args['before_title'] = "<span class='heading-t3'></span>".$args['before_title'];
            $args['before_title'] = str_replace('h2','h3',$args['before_title']);
            $args['after_title'] = str_replace('h2','h3',$args['after_title']);
            $import_file = ( isset( $this->shortcodeParams['import_file'] ) )? $this->shortcodeParams['import_file']: false;
            $rss_feed = ( isset( $this->shortcodeParams['rss_feed'] ) )? $this->shortcodeParams['rss_feed']: false;
            $rss_items = (isset($this->shortcodeParams['rss_items']) && $this->shortcodeParams['rss_items'] !== '') ? (int)$this->shortcodeParams['rss_items'] : -1;
            $rss_item_title = (isset($this->shortcodeParams['rss_item_title']) && $this->shortcodeParams['rss_item_title'] !== '') ? $this->shortcodeParams['rss_item_title'] : null;
            $import_file = ($rss_feed) ? $rss_feed : $import_file; // add rss feed shortcode attribute to be more UX friendly. And we assign it to import_file because its the same behavior.
            $feed = ( isset( $this->shortcodeParams['feed'] ) )? $this->shortcodeParams['feed']: '';
            $feed_title =  ( isset( $this->shortcodeParams['feed_title'] ) )? $this->shortcodeParams['feed_title']: '';
            $feed_img =  ( isset( $this->shortcodeParams['feed_img'] ) )? $this->shortcodeParams['feed_img']: '';
            $el_widget_id = ( isset( $this->shortcodeParams['el_widget_id'] ) )? $this->shortcodeParams['el_widget_id']: '';
            $css = ( isset( $this->shortcodeParams['css'] ) )? $this->shortcodeParams['css']: '';
            $single_playlist = (is_single()) ? true : false;
            $playlatestalbum = ( isset( $this->shortcodeParams['play-latest'] ) ) ? true : false;
            $title = apply_filters( 'widget_title', $this->shortcodeParams['title'], $this->shortcodeParams, $this->id_base );
            $albums = $this->shortcodeParams['albums'];
            $albums = apply_filters( 'srmp3_album_param', $albums );
            $show_playlist = (bool)( isset( $this->shortcodeParams['show_playlist'] ) )? $this->shortcodeParams['show_playlist']: false;
            if($show_playlist){
                $show_playlist = ($this->shortcodeParams['show_playlist']=="true" || $this->shortcodeParams['show_playlist']==1) ? : false;      
            }
            $lazy_load = ( isset( $this->shortcodeParams['lazy_load'] ) && $this->shortcodeParams['lazy_load'] === 'true' && ( isset($this->shortcodeParams['show_playlist']) && $this->shortcodeParams['show_playlist'] === "true" ) )? true : false;
            $posts_per_pages = (isset($this->shortcodeParams['posts_per_page']) && $this->shortcodeParams['posts_per_page'] !== '') ? (int)$this->shortcodeParams['posts_per_page'] : -1;
            $audio_meta_field =  ( function_exists( 'run_sonaar_music_pro' ) &&  isset( $this->shortcodeParams['audio_meta_field'] ) ) ? $this->shortcodeParams['audio_meta_field'] : '';
            $repeater_meta_field =  ( function_exists( 'run_sonaar_music_pro' ) && isset( $this->shortcodeParams['repeater_meta_field'] ) ) ? $this->shortcodeParams['repeater_meta_field'] : '';
            $adaptiveColors = ( isset( $this->shortcodeParams['adaptive_colors'] ) )? $this->shortcodeParams['adaptive_colors'] : false;
            $adaptiveColorsFreeze = ( isset( $this->shortcodeParams['adaptive_colors_freeze'] )) ? $this->shortcodeParams['adaptive_colors_freeze'] : false;
            $isPlayer_Favorite = false;
            $isPlayer_recentlyPlayed = false;
            $fav_label_notfound = (Sonaar_Music::get_option('fav_label_notfound', 'srmp3_settings_favorites') !== null ) ? Sonaar_Music::get_option('fav_label_notfound', 'srmp3_settings_favorites') : esc_html__( 'You haven\'t liked any tracks yet.', 'sonaar-music' );
            $fav_icon_add = (Sonaar_Music::get_option('srp_fav_add_icon', 'srmp3_settings_favorites')) ? Sonaar_Music::get_option('srp_fav_add_icon', 'srmp3_settings_favorites') : 'sricon-heart-fill';
            $fav_icon_remove = (Sonaar_Music::get_option('srp_fav_remove_icon', 'srmp3_settings_favorites')) ? Sonaar_Music::get_option('srp_fav_remove_icon', 'srmp3_settings_favorites') : 'sricon-heart';
            $fav_label_add = (Sonaar_Music::get_option('fav_label_add', 'srmp3_settings_favorites')) ? Sonaar_Music::get_option('fav_label_add', 'srmp3_settings_favorites') : esc_html__( 'Add to your Favorite', 'sonaar-music' );
            $fav_label_remove = (Sonaar_Music::get_option('fav_label_remove', 'srmp3_settings_favorites')) ? Sonaar_Music::get_option('fav_label_remove', 'srmp3_settings_favorites') : esc_html__( 'Remove from your Favorite', 'sonaar-music' );
            $fav_label_removeall = (Sonaar_Music::get_option('fav_label_removeall', 'srmp3_settings_favorites')) ? Sonaar_Music::get_option('fav_label_removeall', 'srmp3_settings_favorites') : esc_html__( 'Remove All Favorites', 'sonaar-music' );
            $tracklistGrid = ( isset( $this->shortcodeParams['tracklist_layout'] ) && $this->shortcodeParams['tracklist_layout'] == 'grid') ? true : false;
            $player_datas = '';
            $reverse_tracklist = $this->getOptionValue('reverse_tracklist');
            $this->shortcodeParams['orderby'] = ( function_exists('run_sonaar_music_pro') && isset( $this->shortcodeParams['orderby'] ) && $this->shortcodeParams['orderby'] !== '' ) ? $this->shortcodeParams['orderby'] : 'date'; //should be date
            $this->shortcodeParams['order'] = ( isset( $this->shortcodeParams['order'] ) && $this->shortcodeParams['order'] !== '' ) ? $this->shortcodeParams['order'] : 'DESC'; //should be date
            

            if(
                (isset($this->shortcodeParams['use_play_label']) && $this->shortcodeParams['use_play_label'] != 'false' || !isset($this->shortcodeParams['use_play_label'])) && 
                isset($this->shortcodeParams['use_play_label_with_icon']) && $this->shortcodeParams['use_play_label_with_icon'] == 'true' && 
                isset($this->shortcodeParams['hide_play_icon']) && $this->shortcodeParams['hide_play_icon'] == 'true'
            ){ //New Param to hide play icon when use_play_label_with_icon is true
                $this->shortcodeParams['use_play_label'] = 'true';
                $this->shortcodeParams['use_play_label_with_icon'] = 'false';
            }

            $this->cf_dataSort = [];

            if($tracklistGrid ){
                $tracklistGrid_col = ( isset( $this->shortcodeParams['grid_column_number'] ) ) ? $this->shortcodeParams['grid_column_number']: '4,3,2';
                $tracklistGrid_col = explode(',', $tracklistGrid_col);
                $tracklistGrid_col_desktop = $tracklistGrid_col[0];
                $tracklistGrid_col_tablet = ( isset( $tracklistGrid_col[1] ) && isset( $tracklistGrid_col[2] ) && $tracklistGrid_col[0] != $tracklistGrid_col[1] )? $tracklistGrid_col[1] : false;
                if( isset( $tracklistGrid_col[1] ) && isset( $tracklistGrid_col[2] ) && $tracklistGrid_col[1] != $tracklistGrid_col[2]  ){
                    $tracklistGrid_col_mobile = $tracklistGrid_col[2];
                }else if( isset( $tracklistGrid_col[1] ) && ! isset( $tracklistGrid_col[2] ) && $tracklistGrid_col[0] != $tracklistGrid_col[1] ){
                    $tracklistGrid_col_mobile = $tracklistGrid_col[1];
                }else{
                    $tracklistGrid_col_mobile = false;
                }
                $player_datas .= ' data-col="' . $tracklistGrid_col_desktop . '"';
                $player_datas .= ($tracklistGrid_col_tablet !== false )?' data-col-tablet="' . $tracklistGrid_col_tablet . '"' : '';
                $player_datas .= ($tracklistGrid_col_mobile !== false )?' data-col-mobile="' . $tracklistGrid_col_mobile . '"' : '';
            }
            $player_datas .= (isset($this->shortcodeParams['tracklist_soundwave_color']) && $this->shortcodeParams['tracklist_soundwave_color'] != '') ? ' data-tracklist-wave-color="' . $this->shortcodeParams['tracklist_soundwave_color'] . '"' : '';
            $player_datas .= (isset($this->shortcodeParams['tracklist_soundwave_progress_color']) && $this->shortcodeParams['tracklist_soundwave_progress_color'] != '') ? ' data-tracklist-wave-progress-color="' . $this->shortcodeParams['tracklist_soundwave_progress_color'] . '"' : '';
            $player_datas .= (isset($this->shortcodeParams['tracklist_soundwave_style']) && $this->shortcodeParams['tracklist_soundwave_style'] != 'default') ? ' data-tracklist-soundwave-style="' . $this->shortcodeParams['tracklist_soundwave_style'] . '"' : '';
            $all_category = ( isset($this->shortcodeParams['category']) && $this->shortcodeParams['category'] == 'all' ) ? true : false;
            $category = ( isset( $this->shortcodeParams['category'] ) ) ? $this->shortcodeParams['category'] : false;
            $posts_not_in = ( function_exists( 'run_sonaar_music_pro' ) &&  isset( $this->shortcodeParams['posts_not_in'] ) ) ? $this->shortcodeParams['posts_not_in'] : null;
            $category_not_in = ( function_exists( 'run_sonaar_music_pro' ) &&  isset( $this->shortcodeParams['category_not_in'] ) ) ? $this->shortcodeParams['category_not_in'] : null;
            $author = ( function_exists( 'run_sonaar_music_pro' ) &&  isset( $this->shortcodeParams['author'] ) ) ? $this->shortcodeParams['author'] : null;

            if($category){
                $terms = $category;
                if($category != 'all'){
                    if ($category == 'current') { // show posts in the current category (archive product page by example) 
                        $current_term = get_queried_object(); 
                        if (isset($current_term->term_id)) { 
                            $category = $current_term->term_id;
                            $terms = array($current_term->term_id); // we convert $terms in array for later use
                        } else { 
                            $terms = array(); // we convert $terms in array for later use
                        } 
                    } else { 
                        $terms = explode(", ", $terms);  // we convert $terms in array for later use
                    } 
                }
            }else{
                $terms = false;
            }

            if ($lazy_load && (!isset($this->shortcodeParams['srp_callFromAjax']) || $this->shortcodeParams['srp_callFromAjax'] !== 'true')) {
                // This is a LazyLoad Player but it has not been called from Ajax yet. We need to display an empty player.
                // We need to load  a SPinner!!!
                //$terms = ''; 
                $category = '';
                $ajaxFirstLoad = true;
                $outputNoResultDom = false; //should we output the No Result Found HTML in the player ?
             }else{
                $ajaxFirstLoad = null;
                $outputNoResultDom = true;
             }
       
            if( $albums == 'all' ){
                $albums = array();
                $query_args = array(
                    'post_status' => 'publish',
                    'posts_per_page' => (int)$posts_per_pages,
                    'post_type' =>  $this->sr_playlist_cpt,
                    'orderby' => $this->shortcodeParams['orderby'],
                    'order' => $this->shortcodeParams['order'],

                );
                $i = 0;
                $r = new WP_Query( $query_args );
                if ( $r->have_posts() ){
                  
                    while ( $r->have_posts() ) : $r->the_post();
                        array_push($albums, $r->posts[$i]->ID);
                        $i++;
                    endwhile;
                    $albums = implode(",", $albums);
                    wp_reset_query();
                }else{
                    echo '<div>' . esc_html__("No Playlist Post found ", 'sonaar-music') . '</div>';
                    return;
                }
            }

           // if (isset($terms) && $terms !=='' && $terms != false){
            if ($category){
                $returned_data = $this->getAlbumsFromTerms($category, $posts_not_in, $category_not_in, $author, $posts_per_pages, false); 
                $albums = $returned_data['albums'];// true means get post objects. false means get Ids only  
            }
           
            if(function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1'){
                if( $albums == 'favorites' ){
                    $isPlayer_Favorite = true;
                    if(Sonaar_Music::get_option('fav_removeall_bt', 'srmp3_settings_favorites') === "true"){
                        echo '<div class="srp-fav-removeall-wrapper"><div class="srp-fav-removeall-bt" style="display:none;">' . esc_html($fav_label_removeall) . '</div></div>';
                    }
                    $albums = [];
                    $favoriteTracks = $this->loadUserPlaylists_fromCookies('Favorites');
                    if($favoriteTracks){
                        $albums = array_column($favoriteTracks, 'postId');
                    }
                }
            }
            if(function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1'){
                if( $albums == 'recentlyplayed' ){
                    $isPlayer_recentlyPlayed = true;
                    $albums = [];
                    $mostRecentTracks = $this->loadUserPlaylists_fromCookies('RecentlyPlayed');
                    if($mostRecentTracks){

                        $albums = array_column($mostRecentTracks, 'postId');
                    }
                }
            }
           
            if ( is_array($albums)) {
                $albums = implode(',', $albums);
            }
           
            $hasImportFile = false;
            $albumsArray = explode(',', $albums);
            foreach ($albumsArray as $value) {
                $hasImportFile = (get_post_meta( $value, 'playlist_csv_file', true)) ? get_post_meta( $value, 'playlist_csv_file', true) : $hasImportFile;
                $hasImportFile = (get_post_meta( $value, 'playlist_rss', true)) ? get_post_meta( $value, 'playlist_rss', true) : $hasImportFile;
                if($hasImportFile){
                    break;
                }
            }
            if ( FALSE === get_post_status( $albums ) || get_post_status ( $albums ) == 'trash') {
                // if album is set by is deleted afterward, let fallback on the latest album post.
                $playlatestalbum = true;
            }

            if($playlatestalbum && $category == false && !$isPlayer_Favorite && !$isPlayer_recentlyPlayed){
                $recent_posts = wp_get_recent_posts(array('post_type'=>$this->sr_playlist_cpt, 'post_status' => 'publish', 'numberposts' => 1));
                if (!empty($recent_posts)){
                    $albums = $recent_posts[0]["ID"];
                }
            }

            $import_file = (get_post_meta( $albums, 'playlist_csv_file', true)) ? get_post_meta( $albums, 'playlist_csv_file', true) : $import_file;
            $import_file = (get_post_meta( $albums, 'playlist_rss', true)) ? get_post_meta( $albums, 'playlist_rss', true) : $import_file;
            if($isPlayer_Favorite) {
                echo '<div class="srp-fav-notfound" style="display:none;" data-label="' . $fav_label_notfound . '"><i class="' . esc_html($fav_icon_add) . '"></i>' . $fav_label_notfound . '</div>';
            }

            if( empty($albums) || $import_file && $isPlayer_Favorite !== true) {

                // SHORTCODE IS DISPLAYED BUT NO ALBUMS ID ARE SET. EITHER GET INFO FROM CURRENT POST OR RETURN NO PLAYLIST SELECTED
                $trackSet = '';
                $albums = get_the_ID();
           
                $album_tracks =  get_post_meta( $albums, 'alb_tracklist', true);

                if (is_array($album_tracks)){
                    $fileOrStream = $album_tracks[0]['FileOrStream'] ?? null;
                       
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
                            break;
                        case 'icecast':
                            if ( isset( $album_tracks[0]["icecast_link"] ) ) {
                                $trackSet = true;
                            }
                            break;
                    }
                }                
                if (isset($feed) && strlen($feed) > 1 ){
                     $trackSet = true;
                }
                if (isset($import_file) && strlen($import_file) > 1 ){
                    $trackSet = true;
               }
                if(isset($audio_meta_field) && $audio_meta_field !==''){
                    $trackSet = true;
                }
                if ( ($album_tracks == 0 || !$trackSet) && (!isset($feed) && strlen($feed) < 1 )){
                    echo esc_html__("No playlist selected", 'sonaar-music');
                    return;
                }
                
                if (!$feed && !$trackSet && !$isPlayer_Favorite && !$lazy_load && !$isPlayer_recentlyPlayed){
                    return;
                }
            }
            $iron_widget_newClass = ''; 
            /* TRACKLIST GRID LAYOUT: Default value */
            if ( isset( $this->shortcodeParams['tracklist_layout'] ) && $this->shortcodeParams['tracklist_layout'] == 'grid' && !$elementor_widget){
                $this->shortcodeParams['player_layout'] =( isset( $this->shortcodeParams['player_layout'] ) )? $this->shortcodeParams['player_layout'] : 'skin_boxed_tracklist';
                $this->shortcodeParams['show_playlist'] =( isset( $this->shortcodeParams['show_playlist'] ) )? $this->shortcodeParams['show_playlist'] : 'true';
                $this->shortcodeParams['track_artwork'] =( isset( $this->shortcodeParams['track_artwork'] ) )? $this->shortcodeParams['track_artwork'] : 'true';
                $this->shortcodeParams['track_artwork_play_button'] = ( isset( $this->shortcodeParams['track_artwork_play_button'] ) )? $this->shortcodeParams['track_artwork_play_button'] : 'true';
                $this->shortcodeParams['track_artwork_play_on_hover'] = ( isset( $this->shortcodeParams['track_artwork_play_on_hover'] ) )? $this->shortcodeParams['track_artwork_play_on_hover'] : 'true';
            }

            /* SKIN BUTTON LAYOUT */
            if( isset($this->shortcodeParams['player_layout'] ) && $this->shortcodeParams['player_layout'] == 'skin_button'){
                $iron_widget_newClass .= ' srp_player_button'; 
                $ironAudioClass = ' srp_player_button';
                $this->shortcodeParams['player_layout'] = 'skin_boxed_tracklist';
                $this->shortcodeParams['hide_artwork'] ='true'; 
                $this->shortcodeParams['hide_album_title'] = 'true'; 
                $this->shortcodeParams['hide_album_subtitle'] = 'true';
                $this->shortcodeParams['hide_player_title'] ='true'; 
                $this->shortcodeParams['hide_track_title'] ='true';  
                $this->shortcodeParams['show_publish_date'] = 'false';
                $this->shortcodeParams['show_skip_bt'] = (isset($this->shortcodeParams['show_skip_bt']))? $this->shortcodeParams['show_skip_bt']:'false';
                $this->shortcodeParams['show_volume_bt'] = (isset($this->shortcodeParams['show_volume_bt']))? $this->shortcodeParams['show_volume_bt']:'false';
                $this->shortcodeParams['show_speed_bt'] = (isset($this->shortcodeParams['show_speed_bt']))? $this->shortcodeParams['show_speed_bt']:'false';
                $this->shortcodeParams['show_shuffle_bt'] = (isset($this->shortcodeParams['show_shuffle_bt']))? $this->shortcodeParams['show_shuffle_bt']:'false';
                $this->shortcodeParams['use_play_label'] = (isset($this->shortcodeParams['use_play_label']))? $this->shortcodeParams['use_play_label']:'true';
                $this->shortcodeParams['use_play_label_with_icon'] = (isset($this->shortcodeParams['use_play_label_with_icon']) && function_exists( 'run_sonaar_music_pro' ) )? $this->shortcodeParams['use_play_label_with_icon']:'true';
                $this->shortcodeParams['progressbar_inline'] = 'true';
                $this->shortcodeParams['spectro'] = '';
                if( !isset($this->shortcodeParams['hide_progressbar']) ){
                    $this->shortcodeParams['hide_progressbar'] = 'true';
                }
                if($this->shortcodeParams['hide_progressbar'] == 'false'){
                    $this->shortcodeParams['inline'] = 'false'; // Always disable inline when progressbar is shown
                }
            }else{
                if( !function_exists( 'run_sonaar_music_pro' ) ){
                    $this->shortcodeParams['use_play_label_with_icon'] = 'false';
                }
            }

            /* SLIDER  */
            if( isset($this->shortcodeParams['slider_param'] ) && $this->shortcodeParams['slider_param'] != 'false'){
                $slider = true;
                $sliderSource = ( isset($this->shortcodeParams['slide_source']) && $this->shortcodeParams['slide_source'] == 'track' || $import_file || $feed )? 'track': 'post'; //track or post
                $dataSwiperSource = 'data-swiper-source="' . $sliderSource . '" ';
                $sliderParams = (isset($this->shortcodeParams['slider_param']) && $this->shortcodeParams['slider_param'] != 'true')? $this->shortcodeParams['slider_param']: "{loop:true,spaceBetween:5,slidesPerView:3,effect:'coverflow',centeredSlides:true}"; 
                $sliderParams = wp_kses( $sliderParams, array() );
                $sliderPagination = ($this->getSliderParams('pagination', $sliderParams) != null && $this->getSliderParams('pagination', $sliderParams) !== 'false')? true : false ;
                $sliderNavigation = ($this->getSliderParams('navigation', $sliderParams) != null && $this->getSliderParams('navigation', $sliderParams) !== 'false')? true : false ;
                $sliderScrollbar = ($this->getSliderParams('scrollbar', $sliderParams) != null  && $this->getSliderParams('scrollbar', $sliderParams) !== 'false')? true : false ;
            }else{
                $slider = false;
            }

            
            

            $scrollbar = ( isset( $this->shortcodeParams['scrollbar'] ) )? $this->shortcodeParams['scrollbar']: false;
            $show_album_market = (bool) ( isset( $this->shortcodeParams['show_album_market'] ) )? $this->shortcodeParams['show_album_market']: 0;
            $show_track_market = (bool) ( isset( $this->shortcodeParams['show_track_market'] ) )? $this->shortcodeParams['show_track_market']: 0;
            $store_title_text = $this->shortcodeParams['store_title_text'];
            $hide_artwork = (bool)( isset( $this->shortcodeParams['hide_artwork'] ) )? $this->shortcodeParams['hide_artwork']: false;
            $displayControlArtwork = (bool)( isset( $this->shortcodeParams['display_control_artwork'] ) )? $this->shortcodeParams['display_control_artwork']: false;
            $hide_control_under = (bool)( isset( $this->shortcodeParams['hide_control_under'] ) )? $this->shortcodeParams['hide_control_under']: false;
            $hide_track_title = (bool)( isset( $this->shortcodeParams['hide_track_title'] ) )? $this->shortcodeParams['hide_track_title']: false;
            $hide_player_title = (bool)( isset( $this->shortcodeParams['hide_player_title'] ) )? $this->shortcodeParams['hide_player_title']: false;
            $hide_times = (bool)( isset( $this->shortcodeParams['hide_times'] ) )? $this->shortcodeParams['hide_times']: false;
            $artwork= (bool)( isset( $this->shortcodeParams['artwork'] ) )? $this->shortcodeParams['artwork']: false;
            $track_artwork = (bool)( isset( $this->shortcodeParams['track_artwork'] ) )? $this->shortcodeParams['track_artwork']: false;
            $remove_player = (bool) ( isset( $this->shortcodeParams['remove_player'] ) )? $this->shortcodeParams['remove_player']: false; // deprecated and replaced by hide_timeline. keep it for fallbacks
            $hide_timeline = (bool) ( isset( $this->shortcodeParams['hide_timeline'] ) )? $this->shortcodeParams['hide_timeline']: false;
            $noLoopTracklist = (bool) ( isset( $this->shortcodeParams['no_loop_tracklist'] ) && function_exists( 'run_sonaar_music_pro' ))? $this->shortcodeParams['no_loop_tracklist']: false;
            $notrackskip = (bool) ( isset( $this->shortcodeParams['notrackskip'] ) )? $this->shortcodeParams['notrackskip']: false;
            $progressbar_inline = (bool) ( isset( $this->shortcodeParams['progressbar_inline'] ) )? $this->shortcodeParams['progressbar_inline']: false;
            $sticky_player = (bool)( isset( $this->shortcodeParams['sticky_player'] ) )? $this->shortcodeParams['sticky_player']: false;
            $shuffle = (bool)( isset( $this->shortcodeParams['shuffle'] ) && ($this->shortcodeParams['shuffle'] == '1' || strtolower($this->shortcodeParams['shuffle'])  == 'true') )? 'true' : 'false';
            $wave_color = (bool)( isset( $this->shortcodeParams['wave_color'] ) )? $this->shortcodeParams['wave_color']: false;
            $wave_progress_color = (bool)( isset( $this->shortcodeParams['wave_progress_color'] ) )? $this->shortcodeParams['wave_progress_color']: false;
            $spectro = (function_exists('run_sonaar_music_pro') && isset($this->shortcodeParams['spectro']) && $this->shortcodeParams['spectro'] != '') ? $this->shortcodeParams['spectro'] : false;
            $spectro_hide_tablet = (bool)(function_exists('run_sonaar_music_pro') && isset($this->shortcodeParams['spectro_hide_tablet']) && $this->shortcodeParams['spectro_hide_tablet'] === 'true') ? true : false;
            $spectro_hide_mobile = (bool)(function_exists('run_sonaar_music_pro') && isset($this->shortcodeParams['spectro_hide_mobile']) && $this->shortcodeParams['spectro_hide_mobile'] === 'true') ? true : false;
            $artwork_background = (bool)( isset( $this->shortcodeParams['artwork_background'] ) )? $this->shortcodeParams['artwork_background']: false;
            $artwork_background_gradient = (bool)( isset( $this->shortcodeParams['artwork_background_gradient'] ) )? $this->shortcodeParams['artwork_background_gradient']: false;

          
            if( $reverse_tracklist == true ){
                $this->shortcodeParams['order'] = 'DESC'; // bypass the set order and let the magic do the trick
            }
            $title_html_tag_playlist = ( isset( $this->shortcodeParams['titletag_playlist'] ) )? $this->shortcodeParams['titletag_playlist']: 'h3';
            $title_html_tag_soundwave = ( isset( $this->shortcodeParams['titletag_soundwave'] ) )? $this->shortcodeParams['titletag_soundwave']: 'div';
            $track_title_html_tag_soundwave = ( isset( $this->shortcodeParams['track_titletag_soundwave'] ) && $this->shortcodeParams['track_titletag_soundwave'] != '' )? $this->shortcodeParams['track_titletag_soundwave']: $title_html_tag_soundwave;
            $title_html_tag_playlist = ($title_html_tag_playlist == '') ? 'div' : $title_html_tag_playlist;
            $hide_album_title = (bool)( isset( $this->shortcodeParams['hide_album_title'] ) )? $this->shortcodeParams['hide_album_title']: false;
            $hide_album_subtitle = (bool)( isset( $this->shortcodeParams['hide_album_subtitle'] ) )? $this->shortcodeParams['hide_album_subtitle']: false;
            $playlist_title = ( isset( $this->shortcodeParams['playlist_title'] ) )? $this->shortcodeParams['playlist_title']: false;   
            $artistWrap = ( isset( $this->shortcodeParams['artist_wrap'] ) &&  $this->shortcodeParams['artist_wrap'] === "true" )? true : false;   
            $hide_trackdesc = ( isset( $this->shortcodeParams['hide_trackdesc'] ) &&  $this->shortcodeParams['hide_trackdesc'] == true ) ? true : false;
            $track_desc_postcontent = ( isset( $this->shortcodeParams['track_desc_postcontent'] ) &&  $this->shortcodeParams['track_desc_postcontent'] == true ) ? true : false;
            $track_desc_lenght = ( isset( $this->shortcodeParams['track_desc_lenght'] ) )? $this->shortcodeParams['track_desc_lenght']: 55;
            $strip_html_track_desc = ( isset( $this->shortcodeParams['strip_html_track_desc'] ) && ( $this->shortcodeParams['strip_html_track_desc'] == 'false' || $this->shortcodeParams['strip_html_track_desc'] === false || $this->shortcodeParams['strip_html_track_desc'] === '' ) )?  false : true;
            $albumStorePosition = ( isset( $this->shortcodeParams['album_store_position'] ) ) ? $this->shortcodeParams['album_store_position'] : '' ;
            $showPublishDate = ( $this->getOptionValue('show_publish_date') && !$feed)? true : false;
            $dateFormat = (Sonaar_Music::get_option('player_date_format', 'srmp3_settings_widget_player') && Sonaar_Music::get_option('player_date_format', 'srmp3_settings_widget_player') != '' ) ? Sonaar_Music::get_option('player_date_format', 'srmp3_settings_widget_player') : '';
            $labelPlayTxt = (Sonaar_Music::get_option('labelPlayTxt', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('labelPlayTxt', 'srmp3_settings_widget_player') : 'Play';
            $labelPlayTxt = ( function_exists('run_sonaar_music_pro') && isset($this->shortcodeParams['play_text']) && $this->shortcodeParams['play_text'] != '') ? $this->shortcodeParams['play_text'] : $labelPlayTxt; 
            $labelPauseTxt = (Sonaar_Music::get_option('labelPauseTxt', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('labelPauseTxt', 'srmp3_settings_widget_player') : 'Pause'; 
            $labelPauseTxt = (function_exists('run_sonaar_music_pro') && isset($this->shortcodeParams['pause_text']) && $this->shortcodeParams['pause_text'] != '') ? $this->shortcodeParams['pause_text'] : $labelPauseTxt;
            if( 
                $this->getOptionValue( 'use_play_label', false ) || //If parameter "use_play_label" is set to true
                ( $this->getOptionValue( 'use_play_label_with_icon', false ) && (isset($this->shortcodeParams['use_play_label']) && $this->shortcodeParams['use_play_label'] != 'false' || ! isset($this->shortcodeParams['use_play_label']))) || //If parameter "use_play_label_with_icon" is set to true AND "use_play_label" is not set to false
                isset($this->shortcodeParams['play_text']) || isset($this->shortcodeParams['pause_text']) //If parameter "play_text" or "pause_text" is set
            ){
                $usePlayLabel = true;
            }else{
                $usePlayLabel = false;
            }
            $labelTitleColumn = (Sonaar_Music::get_option('tracklist_column_title_label', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('tracklist_column_title_label', 'srmp3_settings_widget_player') : esc_html__('Title', 'sonaar-music'); 
            $labelSearch = (Sonaar_Music::get_option('tracklist_search_label', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('tracklist_search_label', 'srmp3_settings_widget_player') : esc_html__('Search', 'sonaar-music'); 
            $labelSearchPlaceHolder = (Sonaar_Music::get_option('tracklist_search_placeholder', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('tracklist_search_placeholder', 'srmp3_settings_widget_player') : esc_html__('Enter any keyword', 'sonaar-music'); 
            $labelNoResult1 = (Sonaar_Music::get_option('tracklist_no_result_1_label', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('tracklist_no_result_1_label', 'srmp3_settings_widget_player') : esc_html__('Sorry, no results.', 'sonaar-music'); 
            $labelNoResult2 = (Sonaar_Music::get_option('tracklist_no_result_2_label', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('tracklist_no_result_2_label', 'srmp3_settings_widget_player') : esc_html__('Please try another keyword', 'sonaar-music'); 
            $labelNoRecentTrack = (Sonaar_Music::get_option('tracklist_no_recent_track_label', 'srmp3_settings_widget_player')) ? Sonaar_Music::get_option('tracklist_no_recent_track_label', 'srmp3_settings_widget_player') : esc_html__('Play history is empty', 'sonaar-music');

            $show_cf_headings = false;
            $tracks_per_page = ( !empty( $this->shortcodeParams['tracks_per_page'] ) )? $this->shortcodeParams['tracks_per_page']: null;
            $pagination_scroll_offset = ( isset( $this->shortcodeParams['pagination_scroll_offset'] ) )? $this->shortcodeParams['pagination_scroll_offset']: '';
            $sr_cf_heading = '';
            if(!function_exists( 'run_sonaar_music_pro' )){
                $hide_trackdesc = true;
            }else{
                $notrackskip = apply_filters( 'srp_track_skip_attribute', $notrackskip);
            }
            if ( isset($this->shortcodeParams['show_progressbar']) ){
                if ( $this->shortcodeParams['show_progressbar'] == 'true' ){
                    $this->shortcodeParams['hide_progressbar'] = 'false'; // Always set "hide_progressbar" to false when "show_progressbar" is to true. We have created the "show_progressbar" parameter for the "skin_button" layout
                }else if( $this->shortcodeParams['show_progressbar'] == 'false' ){
                    $this->shortcodeParams['hide_progressbar'] = 'true'; // Always set "hide_progressbar" to true when "show_progressbar" is to false. We have created the "show_progressbar" parameter for the "skin_button" layout
                }
            }

            $hide_progressbar = filter_var(( isset( $this->shortcodeParams['hide_progressbar'] ) )? $this->shortcodeParams['hide_progressbar']: false, FILTER_VALIDATE_BOOLEAN);
            $progress_bar_style = ( isset( $this->shortcodeParams['progress_bar_style'] ) && $this->shortcodeParams['progress_bar_style'] != 'default') ? $this->shortcodeParams['progress_bar_style'] : false; 
            $playerSpectrum = ($spectro) ? true : false;

            $showControlOnHover = ( isset( $this->shortcodeParams['show_control_on_hover'] ) && $this->shortcodeParams['show_control_on_hover'] == 'true' ) ?  true : false ;
            
            $hasMetaData = ($this->getOptionValue('show_repeat_bt') || $this->getOptionValue('show_shuffle_bt') || $this->getOptionValue('show_speed_bt') || $this->getOptionValue('show_volume_bt') || $this->getOptionValue('show_skip_bt'));


            $hasFavoriteCta = (Sonaar_Music::get_option('force_cta_favorite', 'srmp3_settings_favorites') == "true" || (isset( $this->shortcodeParams['force_cta_favorite']) && $this->shortcodeParams['force_cta_favorite'] == 'true'))? true : false;
            //Field validation
            $sr_html_allowed_tags = array('h1', 'h2', 'h3', 'h4','h5','h6','div','span', 'p');
            if (!in_array($title_html_tag_playlist, $sr_html_allowed_tags, true)) {
                $title_html_tag_playlist = 'h3';
            }
            if (!in_array($title_html_tag_soundwave, $sr_html_allowed_tags, true)) {
                $title_html_tag_soundwave = 'div';
            }
            if (!in_array($track_title_html_tag_soundwave, $sr_html_allowed_tags, true)) {
                $track_title_html_tag_soundwave = 'div';
            }
      
            if($sticky_player){
                if ( function_exists( 'run_sonaar_music_pro' )){
                    $sticky_player = ($this->shortcodeParams['sticky_player']=="true" || $this->shortcodeParams['sticky_player']==1) ? : false;
                }else{
                    $sticky_player = false;
                }
            }
           
            if($hide_track_title){
                $hide_track_title = ($this->shortcodeParams['hide_track_title']=="true" || $this->shortcodeParams['hide_track_title']==1) ? : false;      
            }
            if($show_track_market){
                $show_track_market = ($this->shortcodeParams['show_track_market']=="true" || $this->shortcodeParams['show_track_market']==1) ? : false;      
            }
            if($show_album_market){
                $show_album_market = ($this->shortcodeParams['show_album_market']=="true" || $this->shortcodeParams['show_album_market']==1) ? : false;      
            }
            if($hide_artwork){
                $hide_artwork = ($this->shortcodeParams['hide_artwork']=="true" || $this->shortcodeParams['hide_artwork']==1) ? : false;   
            }
            if($track_artwork){
                if ( function_exists( 'run_sonaar_music_pro' )){
                    $track_artwork = ($this->shortcodeParams['track_artwork']=="true" || $this->shortcodeParams['track_artwork']==1) ? : false;      
                }else{
                    $track_artwork = false;
                }
            }
            if($displayControlArtwork){
                $displayControlArtwork = ($this->shortcodeParams['display_control_artwork']=="true" || $this->shortcodeParams['display_control_artwork']==1) ? : false;      
            }
            if($hide_control_under){
                $hide_control_under = ($this->shortcodeParams['hide_control_under']=="true") ? true : false;      
            }
            if($hide_player_title){
                $hide_player_title = ($this->shortcodeParams['hide_player_title']=="true") ? true : false;      
            }
            if($hide_album_title){
                $hide_album_title = ($this->shortcodeParams['hide_album_title']=="true") ? true : false;      
            }
            if($hide_album_subtitle){
                $hide_album_subtitle = ($this->shortcodeParams['hide_album_subtitle']=="true") ? true : false;      
            }
            if($progressbar_inline){
                $progressbar_inline = ($this->shortcodeParams['progressbar_inline']=="true" || $this->shortcodeParams['progressbar_inline']==1) ? true : false;      
            }
            if($hide_times){
                $hide_times = ($this->shortcodeParams['hide_times']=="true" || $this->shortcodeParams['hide_times']==1) ? true : false;      
            }
            if($noLoopTracklist && isset($this->shortcodeParams['no_loop_tracklist'])){
                $noLoopTracklist = ($this->shortcodeParams['no_loop_tracklist']=="true" || $this->shortcodeParams['no_loop_tracklist']==1) ? 'on' : false;      
            }
            $noLoopTracklist = ($noLoopTracklist == false) ? get_post_meta($albums, 'no_loop_tracklist', true) : $noLoopTracklist;
            
            if($notrackskip && isset($this->shortcodeParams['notrackskip'])){
                $notrackskip = ($this->shortcodeParams['notrackskip']=="true" || $this->shortcodeParams['notrackskip']==1) ? 'on' : false;      
            }
            if($remove_player){
                $remove_player = ($this->shortcodeParams['remove_player']=="true" || $this->shortcodeParams['remove_player']==1) ? true : false;      
            }

            if($hide_timeline){
                $hide_timeline = ($this->shortcodeParams['hide_timeline']=="true" || $this->shortcodeParams['hide_timeline']==1) ? true : false;      
            }

            $store_buttons = array();
           
            $albumParsed = $albums; // need to create a transitionary variable because $albums is used in difference place

            if ( $category ) {
                $albumParsed = '';
            }
            if($ajaxFirstLoad){
                $albumParsed = '';
            }
            $playlist = $this->get_playlist($albumParsed, $category, $posts_not_in, $category_not_in, $author, $title, $feed_title, $feed, $feed_img, $el_widget_id, $artwork, $posts_per_pages, $all_category, $single_playlist, $this->getOptionValue('reverse_tracklist'), $audio_meta_field, $repeater_meta_field, 'widget', $track_desc_postcontent, $import_file, $rss_items, $rss_item_title, $isPlayer_Favorite, $isPlayer_recentlyPlayed);
            if ( !$playlist ) return;
            
            $playlist = (is_array($playlist)) ? $playlist : json_decode($playlist, true);

            if (isset($this->shortcodeParams['player_layout']) && $this->shortcodeParams['player_layout'] == 'skin_boxed_tracklist' && count($playlist['tracks']) == 1 && is_singular( $this->sr_playlist_cpt )){  // Set hide Playlist on single post if only 1 track and boxed layout is set (otherwise the srp_track_cta will be hidden)
                if(!$hide_timeline){
                    $show_playlist = false;
                }
            }; 

            if(array_key_exists('playlist_image', $playlist)){
                $artwork = $playlist['playlist_image'];
                //$hide_artwork = false;

                }
        
        
            if ( isset($playlist['tracks']) && ! empty($playlist['tracks']) )
                $player_message = esc_html_x('Loading tracks...', 'Widget', 'sonaar-music');
            else
                $player_message = esc_html_x('No tracks founds...', 'Widget', 'sonaar-music');
            
            /***/
            
            
            if($show_playlist) { 
                $iron_widget_newClass .= ' playlist_enabled'; 
            } 

            if($this->getOptionValue('track_market_inline') || ( isset($this->shortcodeParams['custom_fields_columns']) && $this->shortcodeParams['custom_fields_columns'] ) || $tracklistGrid) { 
                $iron_widget_newClass .= ' sr_track_inline_cta_bt__yes'; 
            } 

            if($this->getOptionValue('inline', false)) { 
                $iron_widget_newClass .= ' srp_inline'; 
            } 
            $args['before_widget'] = str_replace('class="iron_widget_radio"', 'id="'. $widget_id .'" class="iron_widget_radio'. $iron_widget_newClass .'"', $args['before_widget'] );    
        
		/* Enqueue Sonaar Music related CSS and Js file */
        //not enqueued with ajax request

		wp_enqueue_style( 'sonaar-music' );
		wp_enqueue_style( 'sonaar-music-pro' );
		wp_enqueue_script( 'sonaar-music-mp3player' );
		wp_enqueue_script( 'sonaar-music-pro-mp3player' );
		wp_enqueue_script( 'sonaar_player' );
        if( $adaptiveColors ){
            wp_enqueue_script( 'color-thief' );
        }

        if( $slider ){
            wp_enqueue_script( 'srp-swiper' );
            wp_enqueue_style( 'srp-swiper-style' );
        }

		if ( function_exists('sonaar_player') ) {
			add_action('wp_footer','sonaar_player', 12);
		}
        
        if ( ( $this->getOptionValue('show_name_filter') || 
            $this->getOptionValue('show_date_filter') ||
            $this->getOptionValue('show_duration_filter')  ) && 
            $this->getOptionValue('searchbar_show_filters') &&
            function_exists( 'run_sonaar_music_pro' ) 
        ){
            $searchbarShowFilters = true;
        }else{
            $searchbarShowFilters = false;
        }
        if( isset( $this->shortcodeParams['custom_fields_columns']) && function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1' ){
        
            $show_cf_headings =   ( isset( $this->shortcodeParams['custom_fields_heading'] ) && $this->shortcodeParams['custom_fields_heading'] == 'true')? true : false;
            $sr_cf_heading_html_ar = array();
            $sr_cf_heading_html_ar[] = '<div class="srp_sort sr-playlist-heading-child sr-playlist-cf--title" data-sort="tracklist-item-title" title="Title">' . esc_html($labelTitleColumn) . '</div>';
            $custom_fields_columns = $this->shortcodeParams['custom_fields_columns'];
            $custom_fields_columns_ar = explode(';', $custom_fields_columns);
            $cf_input_formatted_ar =  array();
            $headingLabel='';
            $headingID ='';
            foreach ($custom_fields_columns_ar as $key => $valueString) {
                $value = explode('::', $valueString);
                $headingLabel = $value[0];
                $headingID= ( isset($value[1]) ) ? $value[1] : '';
                $cf_columnWidth = ( isset($value[2]) ) ? $value[2] :'100px';
                $sr_cf_heading_html = '<div class="srp_sort sr-playlist-heading-child" data-sort="sr-playlist-cf--' . esc_attr($headingID) . '"style="flex: 0 0 ' . esc_attr($cf_columnWidth) . ';" title="' .  esc_html($headingLabel) . '">' .  esc_html($headingLabel) . '</div>';
                $sr_cf_heading_html_ar[] =  $sr_cf_heading_html;
                if( ! in_array( $headingID, $this->cf_dataSort )){
                   array_push($this->cf_dataSort, $headingID);
                }
            }
        }else{
            $custom_fields_columns = false;
        }

        if( ( $searchbarShowFilters || $this->getOptionValue('searchbar') || $custom_fields_columns || $tracks_per_page ) && function_exists( 'run_sonaar_music_pro' ) ){
            wp_enqueue_script( 'sonaar-list' );
        }

        echo $args['before_widget'];
        
        if ( ! empty( $title ) )
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
    
        $firstAlbum = explode(',', $albums);
        $firstAlbum = $firstAlbum[0];
       
        if( isset( $this->shortcodeParams['player_layout'])){  
            $playerWidgetTemplate = ($this->shortcodeParams['player_layout'] == 'skin_boxed_tracklist' )? 'skin_boxed_tracklist' :'skin_float_tracklist'; //if player_layout parameter is set in the shortcode
        }else{  
            if(get_post_meta($firstAlbum, 'post_player_type', true)=='default') {
                $playerWidgetTemplate = ( Sonaar_Music::get_option('player_widget_type', 'srmp3_settings_general')  == 'skin_boxed_tracklist' )? 'skin_boxed_tracklist' :'skin_float_tracklist'; //if player_layout is not set or set to default through the post setting
            }else{
                $playerWidgetTemplate = ( get_post_meta($firstAlbum, 'post_player_type', true)  == 'skin_boxed_tracklist' )? 'skin_boxed_tracklist' :'skin_float_tracklist'; //Get the player_layout from the plugin settings
            };
        }

        /* Miniplayer Meta Order */
        if( isset( $this->shortcodeParams['player_metas']) && function_exists( 'run_sonaar_music_pro' ) ){
            $miniplayer_order =  $this->shortcodeParams['player_metas'] ;  
            $miniplayer_order = explode('||', $miniplayer_order);    
            $miniplayer_order = array_map( function($string) { return ltrim($string); }, $miniplayer_order ); //remove first white space
            $miniPlayer_meta_id = isset( $this->shortcodeParams['miniplayer_meta_id'])? $this->shortcodeParams['miniplayer_meta_id'] :'';  
            $miniPlayer_meta_id = explode(',', $miniPlayer_meta_id); 
        }else{
            // Default order
            $miniplayer_order =  [];
            if( $playerWidgetTemplate == 'skin_float_tracklist'){
                $miniplayer_order =  ['meta_track_title'];
                if(!$show_playlist){
                    array_push($miniplayer_order, 'meta_playlist_title');
                }
            }
            if( $playerWidgetTemplate == 'skin_boxed_tracklist'){
                $miniplayer_order =  ['meta_playlist_title'];
                if(!$show_playlist){
                    $miniplayer_order =  ['meta_track_title'];
                }
            }
        }
        if($hide_player_title){
            $miniplayer_order = array_diff($miniplayer_order, ['meta_playlist_title']);
        }
        if($hide_track_title){
            $miniplayer_order = array_diff($miniplayer_order, ['meta_track_title']);
        }

        
        $ironAudioClass = '';
        $ironAudioClass .= ( $artwork_background )? ' srp_artwork_fullbackground_yes': '' ;
        $ironAudioClass .= ( $artwork_background_gradient )? ' srp_artwork_fullbackground_wgradient_yes': '' ;
        $ironAudioClass .= ( $show_playlist ) ? ' show-playlist' :'';
        $ironAudioClass .= ( $track_artwork ) ? ' show-trackartwork' :'';
        $ironAudioClass .= ( $hide_artwork == "true" ) ? ' sonaar-no-artwork' :'';
        $ironAudioClass .= ($displayControlArtwork) ? ' sr_player_on_artwork' : '';
        $ironAudioClass .= ( $remove_player || $hide_timeline )? ' srp_hide_player': '' ;
        $ironAudioClass .= ( $isPlayer_Favorite )? ' srp_player_is_favorite': '' ;
        $ironAudioClass .= ( $isPlayer_recentlyPlayed )? ' srp_player_is_recentlyPlayed': '' ;
        $ironAudioClass .= ( $hide_progressbar )? ' srp_hide_progressbar': '' ;
        $ironAudioClass .= ( $spectro_hide_tablet ) ? ' srp_hide_spectro_tablet' : '';
        $ironAudioClass .= ( $spectro_hide_mobile ) ? ' srp_hide_spectro_mobile' : '';
        $ironAudioClass .= ( $playerSpectrum )? ' srp_player_spectrum': '' ;
        $ironAudioClass .= ( $hide_times )? ' srp_hide_time': '' ;
        $ironAudioClass .= ( $single_playlist )? ' srp_post_player': '' ;
        $ironAudioClass .= ( $hasMetaData )? ' srp_has_metadata': '' ;
        $ironAudioClass .= ( $this->getOptionValue('hide_track_number') && $show_playlist )? ' srp_hide_tracknumber': '' ;
        $ironAudioClass .= ( $custom_fields_columns ) ? ' srp_has_customfields': '' ;
        $ironAudioClass .= ( $noLoopTracklist == 'on' )? ' srp_noLoopTracklist': '' ;
        $ironAudioClass = apply_filters( 'srmp3_player_class', $ironAudioClass );
        // TODO. Dont show the player if its a text to speech player and it has no tracks set. We dont want to show No Keyword found ! Note: We want to display No Keywords in certain case (eg: Lazyload players) for other player types. TODO.
       //var_dump(strpos($ironAudioClass, 'srp_tts_player'));

        if ( !$isPlayer_recentlyPlayed && !$lazy_load && strpos($ironAudioClass, 'srp_tts_player') && empty($playlist['tracks'])) {
            //its a tts player with no tracks && lazyload is disabled so empty track will be legit
            return;
        }
        $album_ids_with_show_market = ( $show_album_market )? $albums : 0 ;
        $hasTracklistSoundwave = false;
        $hasTracklistCursor = false;
        
        $format_playlist ='';

        if(Sonaar_Music::get_option('show_artist_name', 'srmp3_settings_general') ){
            $artistSeparator = (Sonaar_Music::get_option('artist_separator', 'srmp3_settings_general') && Sonaar_Music::get_option('artist_separator', 'srmp3_settings_general') != '' && Sonaar_Music::get_option('artist_separator', 'srmp3_settings_general') != 'by')?Sonaar_Music::get_option('artist_separator', 'srmp3_settings_general'):  esc_html__('by', 'sonaar-music');
            $artistSeparator = ' ' . $artistSeparator . ' ';
        }else{
            $artistSeparator = '';
        }
       
        $storeButtonPosition = [];//$storeButtonPosition[ {track index} , {store index} ] , so $storeButtonPosition[ 0, 1 ] refers to the second(1) store button from the first(0) track
        $trackIndexRelatedToItsPost = 0; //variable required to set the data-store-id. Data-store-id is used to popup the right content.
        $currentTrackId = ''; //Used to set the $trackIndexRelatedToItsPost
        $trackNumber = 0; // Dont Count Relataded track
        $trackCountFromPlaylist = 0; //Count tracks from same playlist
        $playlistID = '';
        $excerptTrimmed = '[...]';
        $playlist_has_ctas = false;
        $wpkses_arr = array( 'br' => array(), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array()));
        $widgetPart_cat_description =  ( $this->getOptionValue('show_cat_description') && $terms) ? '<div class="srp_podcast_rss_description">' . wp_kses(category_description((int)$terms[0]),$wpkses_arr) . '</div>' : '';
        if (array_key_exists('tracks',$playlist['tracks'])){
            $playlist['tracks'] = $playlist['tracks']['tracks'];
        }
        
        $hasTracklistSoundwave = ( function_exists( 'run_sonaar_music_pro' ) && isset($this->shortcodeParams['tracklist_soundwave_show']) && $this->shortcodeParams['tracklist_soundwave_show'] == 'true' )? true : false ;
        $hasTracklistCursor = ( $hasTracklistSoundwave && isset($this->shortcodeParams['tracklist_soundwave_cursor']) && $this->shortcodeParams['tracklist_soundwave_cursor'] == 'true' )? true : false ;
        $miniPlayer_metas = '';
        
        foreach( $playlist['tracks'] as $key1 => $track){
            $allAlbums = explode(', ', $albums);
            if(! isset( $track['poster'] ) || $track['poster'] === null){
                $track['poster'] = '';
            }
            if( $playlistID == $track['sourcePostID'] ){
                $trackCountFromPlaylist++;
            }else{
                $playlistID = $track['sourcePostID'];
                $trackCountFromPlaylist = 0;
                if( $this->getOptionValue('reverse_tracklist') ){ //If reverse track list order is enable, start to count (the incrementation) from the number of track the playlist post has (in negative) rather than 0
                    $i = $key1 + 1;
                    while (  $i < (count( $playlist['tracks'] )) && $playlist['tracks'][$i]['sourcePostID'] == $playlistID ) {
                    $i++;
                    $trackCountFromPlaylist--;
                    }
                }
            }

            $relatedTrack = ( Sonaar_Music::get_option('sticky_show_related-post', 'srmp3_settings_sticky_player') != 'true' || $terms || in_array($track['sourcePostID'], $allAlbums) || $feed || $this->shortcodeParams['albums'] == 'all' || !$single_playlist)? false : true; //True when the track is related to the selected playlist post as episode podcast from same category           
            $storeButtonPosition[$key1] = [];
            $trackdescEscapedValue = '';
            $trackUrl = $track['mp3'] ;
            $showLoading = $track['loading'] ;
            if($currentTrackId != $track['sourcePostID']){ //Reset $trackIndexRelatedToItsPost counting. It is incremented at the end of the foreach.
                $currentTrackId = $track['sourcePostID'];
                $trackIndexRelatedToItsPost = 0; 
            }

            if( 
                ( get_post_meta( $currentTrackId, 'reverse_post_tracklist', true) || $this->getOptionValue('reverse_tracklist') ) &&  // If Reverse tracklist is set through the shortcode or throught the post settings, reverse the popup CTA odrer 
                !(get_post_meta( $currentTrackId, 'reverse_post_tracklist', true) && $this->getOptionValue('reverse_tracklist') )  //But if Reverse tracklist is set twice, dont reverse the popup CTA odrer
            ){
                $countTrackFromSamePlaylist = array_count_values( array_column($playlist['tracks'], 'sourcePostID') )[$currentTrackId];
                $trackIndex =  $countTrackFromSamePlaylist - 1 - $trackIndexRelatedToItsPost;
            }else{
                $trackIndex =  $trackIndexRelatedToItsPost;
            }
            
            $song_store_list_ar = $this->fetch_song_store_list_html($track, $trackIndex, $show_track_market, $key1);
            $song_store_list = $song_store_list_ar['store_list'];
            $playlist_has_ctas = (isset($playlist_has_ctas) && $playlist_has_ctas == true ) ? $playlist_has_ctas : $song_store_list_ar['playlist_has_ctas'];
            $trackdesc_allowed_html = [
                'a'      => [
                    'href'  => [],
                    'title' => [],
                    'target'=> [],
                ],
                'br'     => [],
                'em'     => [],
                'strong' => [],
                'b' => [],
                'p' => [],
            ];
            if (!$hide_trackdesc && isset($track['description']) && $track['description'] !==false) {
                if( $strip_html_track_desc ){
                        $trackdescEscapedValue =  force_balance_tags( wp_trim_words( strip_shortcodes( $track['description'] ) , esc_attr($track_desc_lenght), $excerptTrimmed )) ;
                }else{
                        $trackdescEscapedValue =  force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( strip_shortcodes( $track['description']   )), esc_attr($track_desc_lenght), $excerptTrimmed ) ));
                }
            }

            $playlistTrackDesc = (isset($trackdescEscapedValue)) ? '</div><div class="srp_track_description">'. wp_kses( $trackdescEscapedValue, $trackdesc_allowed_html ) .'</div>' : '</div>';
            $store_buttons = ( !empty($track["track_store"]) ) ? '<a class="button" target="_blank" href="'. esc_url( $track['track_store'] ) .'">'. esc_textarea( $track['track_buy_label'] ).'</a>' : '' ;
            $artistSeparator_string = ($track['track_artist']) ? $artistSeparator : '';//remove separator if no track doesnt have artist

            if( $artistSeparator_string && $artistWrap ){
                $artistSeparator_string = ( trim(htmlentities($artistSeparator_string)) == '&nbsp;' || trim($artistSeparator_string) == '&nbsp;') ? '<br>': substr_replace($artistSeparator_string, '<br>', 0, 0); //if the separator is a space '&nbsp;' and $artistWrap is enable, remove the space to fix the offset alignment issue.
            }
            
            $imageFormat = ( isset( $this->shortcodeParams['track_artwork_format'] ) )? $this->shortcodeParams['track_artwork_format'] : 'thumbnail' ;

            $track_image_url = (($track_artwork && isset($track['track_image_id'])) && ($track['track_image_id'] != 0)) ? wp_get_attachment_image_src($track['track_image_id'], $imageFormat, true)[0] : $track['poster'] ;
            $coverSpacer = ($custom_fields_columns && $track_artwork)? '<span class="sr_track_cover srp_spacer"></span>': '';

            $track_artwork_container = ( $this->getOptionValue('track_artwork_play_button') ) ? '<div class="sr_track_cover"><div class="srp_play"><i class="sricon-play"></i></div><img src=' . esc_url( $track_image_url ) . ' alt="track-artwork" /></div>' : '<img src=' . esc_url( $track_image_url ) . ' alt="track-artwork" class="sr_track_cover" />' ;
            $track_artwork_value = ($track_artwork && $track_image_url != '') ? $track_artwork_container : $coverSpacer ;
            
            if(isset($track['published_date']) ){
                $date_obj = new DateTime($track['published_date']); 
                $track_date = date_i18n('Y/m/d', $date_obj->getTimestamp());  
                $track_date_formated = date_i18n(get_option('date_format'), $date_obj->getTimestamp());
            }else if (isset($track['sourcePostID']) ){
                $track_date = get_the_date( 'Y/m/d', $track['sourcePostID'] );
                $track_date_formated = get_the_date(get_option('date_format'), $track['sourcePostID']);
            }else{
                $track_date = false;
                $track_date_formated = false;
            }

            $trackLinkedToPost = ( isset( $track['sourcePostID'] ) && $this->getOptionValue('post_link') && ( get_post_type() != 'product' || isset($this->shortcodeParams['post_link']) && filter_var($this->shortcodeParams['post_link'], FILTER_VALIDATE_BOOLEAN) ) ) ? get_permalink($track['sourcePostID']) : false; //Disable post link if the widget is used in a product page, except if the "post_link" option is set to true in the widget settings
            $trackTitle = esc_html($track['track_title']);
            $trackTitle .= ( Sonaar_Music::get_option('show_artist_name', 'srmp3_settings_general') )?  '<span class="srp_trackartist">' . esc_html($artistSeparator_string) . esc_html($track['track_artist']) .'</span>': '';
            $noteButton =  $this->addNoteButton($track['sourcePostID'], abs($trackCountFromPlaylist), $trackTitle, $trackdescEscapedValue, $excerptTrimmed, $track_desc_postcontent ); // We are using abs() here, because when the "reverse order" option is enable, the "$trackCountFromPlaylist" variable has a negative value 
            $playlistItemClass = (isset($trackdescEscapedValue) || $noteButton != null ) ? 'sr-playlist-item' : 'sr-playlist-item sr-playlist-item-flex';
            if($trackLinkedToPost && ! $this->getOptionValue('track_artwork_play_button') && ! $tracklistGrid ){
                $track_artwork_value = '<a href="' . $trackLinkedToPost . '" target="_self">' . $track_artwork_value;
                $track_artwork_value .= '</a>';
            }
            $format_playlist .= '<li 
            class="'. esc_attr($playlistItemClass) .'" 
            data-audiopath="' . esc_url( $trackUrl ) . '"
            data-showloading="' . esc_html($showLoading) .'"
            data-albumTitle="' . esc_attr( $track['album_title'] ) . '"
            data-albumArt="' . esc_url( $track['poster'] ) . '"
            data-releasedate="' . esc_attr( (isset($track['release_date'])) ? $track['release_date'] : '' ) . '"
            data-date="' . esc_attr( $track_date ) . '"
            data-date-formated="' . esc_attr( $track_date_formated ) . '"
            data-show-date="' . esc_attr($this->getOptionValue('show_track_publish_date')) . '"
            data-trackTitle="' . esc_html($trackTitle) . '"
            data-artist="' . esc_html( $track['track_artist'] ) . '"
            data-trackID="' . esc_html($track['id']) . '"
            data-trackTime="' . esc_html($track['length']) . '"
            data-relatedTrack="'. esc_html($relatedTrack) . '"
            data-post-url="'. esc_html($trackLinkedToPost) . '"
            data-post-id="'. esc_html($track['sourcePostID']) . '"
            data-track-pos="'. (isset($track['track_pos']) ? $track['track_pos'] : $trackIndex) . '"
            data-peakFile="'. esc_html((isset($track['peakFile'])) ? $track['peakFile'] : '') . '"
            data-peakFile-allow="'. esc_html((isset($track['peak_allow_frontend'])) ? $track['peak_allow_frontend'] : '') . '"
            data-is-preview="'. esc_html((isset($track['isPreview'])) ? $track['isPreview'] : '') . '"
            data-track-lyric="'. esc_html((isset($track['has_lyric'])) ? $track['has_lyric'] : '') . '"';
            //error_log('track peaks == ' . $track['peaks']);
            $format_playlist .= ( array_key_exists( 'icecast_json', $track) && $track['icecast_json'] !== '')? ' data-icecast_json="' . esc_attr( $track['icecast_json'] ) . '"' : '';
            $format_playlist .= ( array_key_exists( 'icecast_mount', $track) && $track['icecast_mount'] !== '')? ' data-icecast_mount="' . esc_attr( $track['icecast_mount'] ) . '"' : '';
            $format_playlist .= ( array_key_exists( 'icecast_json', $track) && $track['icecast_json'] !== '' && $track['optional_poster'] != false)? ' data-optional_poster="true"' : ''; //if icecast we need to display the optional poster if no image is provided
            $format_playlist .= '>';

            $cf_input_formatted_ar=array();
            $cf_input_styling_ar=array();

            $postid = $track['sourcePostID'];


            /*
            ----------------------------------
            // MINI PLAYER META HEADING
            ----------------------------------
            */ 
            $miniPlayer_metas = '';
            $htmlTagList = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span');
            $metaType = '';
            $metaHtmlTag = $title_html_tag_soundwave; //default
            $metaContent = '';
            $metaPrefix = '';
            $cfData = array();
            foreach ($miniplayer_order as $index => $metaHeading) {
                $metaHeading = explode('::', $metaHeading); 
                $metaHeading = array_map( function($string) { return ltrim($string); }, $metaHeading ); //remove first white space
                $prefix_label = '';
                foreach ($metaHeading as $metaHeading_param) {
                    if (strpos($metaHeading_param, 'prefix_') === 0) {
                        $prefix_label = str_replace('prefix_', '', $metaHeading_param);
                    }else if (strpos($metaHeading_param, 'meta_') === 0){
                        $metaHeading_param = strtolower($metaHeading_param); 
                        $metaHeading_param = str_replace(' ', '', $metaHeading_param); 
                        $metaType = str_replace('meta_', '', $metaHeading_param); 
                    } else if (in_array( str_replace(' ', '', $metaHeading_param), $htmlTagList)) {
                        $metaHeading_param = strtolower($metaHeading_param); 
                        $metaHeading_param = str_replace(' ', '', $metaHeading_param); 
                        $metaHtmlTag = $metaHeading_param;
                    } else {
                            if($metaType == 'custom_heading'){
                                $metaContent = $metaHeading_param;
                            }else{
                                $metaPrefix = $metaHeading_param;
                            }
                    }
                }

                $metaClass = ' srp_meta srp_meta_' . $index;
                $metaData = '';
                $metaAriaLabel = '';
                $validMeta = true;
                if( isset($miniPlayer_meta_id[$index])){
                    $metaClass .= ' elementor-repeater-item-' . $miniPlayer_meta_id[$index];
                }
                switch ( $metaType ) {
                    case 'custom_heading':
                        $metaClass .= ' srp_custom_title';
                        $metaContent = $prefix_label . ' ' . htmlspecialchars_decode($metaContent);
                        $metaAriaLabel = esc_html__(strip_tags($metaContent));
                        break;
                    case 'playlist_title':
                        $metaClass .= ' album-title';
                        $metaAriaLabel = esc_html__('Title', 'sonaar-music');
                        break;
                    case 'podcast_title':
                        //same thig as playlist_title but its for podcast shortcode which is more explicit
                        $metaClass .= ' album-title';
                        $metaAriaLabel = esc_html__('Title', 'sonaar-music');
                        break;
                    case 'track_title':
                        $metaClass .= ' track-title';
                        $metaAriaLabel = esc_html__('Track title', 'sonaar-music');
                        break;
                    case 'episode_title':
                        //same thig as track_title but its for podcast shortcode which is more explicit
                        $metaClass .= ' track-title';
                        $metaAriaLabel = esc_html__('Track title', 'sonaar-music');
                        break;
                    case 'artist_name':
                        $metaClass .= ' srp_artistname';
                        $metaAriaLabel = esc_html__('Artist', 'sonaar-music');
                        break;
                    case 'performer_name':
                        //same thig as artist_name but its for podcast shortcode which is more explicit
                        $metaClass .= ' srp_artistname';
                        $metaAriaLabel = esc_html__('Artist', 'sonaar-music');
                        break;
                    case 'description':
                        $metaClass .= ' srp_description';
                        $metaAriaLabel = esc_html__('Description', 'sonaar-music');
                        $value = wp_kses( $trackdescEscapedValue, $trackdesc_allowed_html ); 
                        if( $value != NULL){
                            array_push($cfData, '<div class="srp_cf_data sr-playlist-cf--description">' . $value . '</div>');
                        }
                        break;
                    case 'duration':
                        $metaClass .= ' srp_duration';
                        $metaAriaLabel = esc_html__('Duration', 'sonaar-music');
                        break;
                    case 'categories':
                        $metaClass .= ' srp_category';
                        $metaAriaLabel = esc_html__('Category', 'sonaar-music');
                        $value = ($this->getTermsForFilters($postid, 'playlist-category'))? $this->getTermsForFilters($postid, 'playlist-category') : $this->getTermsForFilters($postid, 'product_cat');
                        if( $value != NULL){
                            array_push($cfData, '<div class="srp_cf_data sr-playlist-cf--playlist-category">' . sanitize_text_field($value) . '</div>');
                        }
                        break;
                    case 'tags':
                        $metaClass .= ' srp_tag';
                        $metaAriaLabel = esc_html__('Tag', 'sonaar-music');
                        $value = ($this->getTermsForFilters($postid, 'playlist-tag'))? $this->getTermsForFilters($postid, 'playlist-tag') : $this->getTermsForFilters($postid, 'product_tag');
                        if( $value != NULL){
                            array_push($cfData, '<div class="srp_cf_data srp_cf_data.sr-playlist-cf--playlist-tag">' . sanitize_text_field($value) . '</div>');
                        }
                        break;
                    case 'podcast_show':
                        $metaClass .= ' srp_podcast_show';
                        $metaAriaLabel = esc_html__('Podcast Show', 'sonaar-music');
                        $value = $this->getTermsForFilters($postid, 'podcast-show');
                        if( $value != NULL){
                            array_push($cfData, '<div class="srp_cf_data sr-playlist-cf--podcast-show">' . sanitize_text_field($value) . '</div>');
                        }
                        break;
                    case 'acf_field':
                        $metaClass .= ' srp_meta_cf';
                        $metaData = ' data-cf="' . esc_attr($metaPrefix) . '"';
                        $metaAriaLabel =  esc_attr($metaPrefix);
                        if(function_exists('acf')){ 
                            if(is_array(get_fields($postid, true))){ 
                                foreach (get_fields($postid, true) as $key => $value) { 
                                    if(is_array($value) && (isset($value[0]) && is_string($value[0]))){ // Prevent array values 
                                        $value = implode(', ', $value ); 
                                    } 
                                    if(is_string($value) ){ 
                                        if( ! in_array( $key, $this->cf_dataSort ) ){ 
                                            array_push($this->cf_dataSort, $key); 
                                        } 
                                        array_push($cfData, '<div class="srp_cf_data sr-playlist-cf--'. esc_attr($key) .'">' . sanitize_text_field($value) . '</div>');  
                                    } 
                                } 
                            } 
                        } 
                        break;
                    case 'key':
                        $value = get_post_meta( $postid, $metaPrefix, true );
                        $metaData = ' data-cf="' . esc_attr($metaPrefix) . '"';
                        array_push($cfData, '<div class="srp_cf_data sr-playlist-cf--'. esc_attr($metaPrefix) .'">' . sanitize_text_field($value) . '</div>');  
                        $metaClass .= ' srp_meta_cf';
                        break;
                    default:
                        $validMeta = false;
                        break;
                }
                if($validMeta){
                    $miniPlayer_metas .= '<'. esc_attr($metaHtmlTag) .' class="' . esc_attr($metaClass) . '"' . $metaData . ' data-prefix="' . esc_attr($prefix_label) . '" aria-label="' . $metaAriaLabel . '">' . $metaContent . '</'. esc_attr($metaHtmlTag) .'>';
                }
            }
            if($miniPlayer_metas != ''){
                $miniPlayer_metas = '<div class="srp_miniplayer_metas">' . $miniPlayer_metas . '</div>';
            }

            /*
            ----------------------------------
            // INSERT CF INTO DOM FOR FILTERS 
            ----------------------------------
            */
            $cf_data_formatted = array();
            if(function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1' ){
                
                array_push($cf_data_formatted, $this->getTermsForFilters($postid, 'playlist-category'));
                array_push($cf_data_formatted, $this->getTermsForFilters($postid, 'playlist-tag'));
                array_push($cf_data_formatted, $this->getTermsForFilters($postid, 'podcast-show'));


                if (defined( 'WC_VERSION' )){
                    if(wc_get_product($postid)){
                        array_push($cf_data_formatted, $this->getTermsForFilters($postid, 'product_cat'));
                        array_push($cf_data_formatted, $this->getTermsForFilters($postid, 'product_tag'));

                        $product = wc_get_product($postid);
                        $attributes = $product->get_attributes();

                        if ( $attributes ) {
                            foreach ( $attributes as $attribute ) {
                                $display_result = '';
                                $name = $attribute->get_name();
                                if( ! in_array( $name, $this->cf_dataSort ) ){
                                    array_push($this->cf_dataSort, $name);
                                }
                                if ( $attribute->is_taxonomy() ) {
                                    $wooterms = wp_get_post_terms( $product->get_id(), $name, 'all' );

                                    // Check if there are terms before proceeding
                                    if (!empty($wooterms) && is_object($wooterms[0])) {
                                        $cwtax = $wooterms[0]->taxonomy;
                                        $cw_object_taxonomy = get_taxonomy($cwtax);
                                        if ( isset ($cw_object_taxonomy->labels->singular_name) ) {
                                            $tax_label = $cw_object_taxonomy->labels->singular_name;
                                        } elseif ( isset( $cw_object_taxonomy->label ) ) {
                                            $tax_label = $cw_object_taxonomy->label;
                                            if ( 0 === strpos( $tax_label, 'Product ' ) ) {
                                                $tax_label = substr( $tax_label, 8 );
                                            }
                                        }
                                        $tax_terms = array();
                                        foreach ( $wooterms as $term ) {
                                            $single_term = esc_html( $term->name );
                                            array_push( $tax_terms, $single_term );
                                        }
                                        $display_result .= implode(', ', $tax_terms);
                                    } else {
                                        // Handle the case where there are no terms or there's an unexpected data structure
                                        // You can log an error, provide a default value, etc.
                                    }
                                } else {
                                    // If custom attribute are used. but its useless for filtering.
                                    //$display_result .= esc_html( implode( ', ', $attribute->get_options() ) );
                                }
                                array_push($cf_data_formatted, '<div class="srp_cf_data sr-playlist-cf--'. esc_attr($name) .'">' . sanitize_text_field($display_result) . '</div>');  
                            }
                        }
                    }
                }

                if(function_exists('acf')){
                    if(is_array(get_fields($postid, true))){
                        foreach (get_fields($postid, true) as $key => $value) {
                            if(is_array($value) && (isset($value[0]) && is_string($value[0]))){ // Prevent array values
                                $value = implode(', ', $value );
                            }
                            if(is_string($value) ){
                                if( ! in_array( $key, $this->cf_dataSort ) ){
                                    array_push($this->cf_dataSort, $key);
                                }
                                array_push($cf_data_formatted, '<div class="srp_cf_data sr-playlist-cf--'. esc_attr($key) .'">' . sanitize_text_field($value) . '</div>');  
                            }
                        }
                    }
                }
                if ( function_exists('jet_engine') && jet_engine()->meta_boxes ) {
                    $metaboxes = jet_engine()->meta_boxes->get_registered_fields();



                    foreach ($metaboxes as $metabox) {
                        foreach($metabox as $themetabox){
                            if(isset($themetabox["object_type"])){ // make sure the object has a complete metabox structure
                                $metakey = isset($themetabox['name']) ? $themetabox['name'] : '' ;
                                $metakey_value = get_post_meta( $postid,  $metakey, true );
                                if(is_array($metakey_value)){
                                    $metakey_value = $this->recursive_implode(', ', $metakey_value);                   
                                }
                                if( ! in_array( $metakey, $this->cf_dataSort ) ){
                                    array_push($this->cf_dataSort, $metakey);
                                }
                                array_push($cf_data_formatted, '<div class="srp_cf_data sr-playlist-cf--'. esc_attr($metakey) .'">' . sanitize_text_field($metakey_value) . '</div>');  
                            }
                        }
                    }
                }
            }

            if(function_exists( 'run_sonaar_music_pro' )){
                $commentStart = (count($cfData) === 0 )? '<!--START CF DATA-->': ''; //This comment is used by the elementor_remove_cf_data() function; 
                $commentEnd = (count($cfData) === 0  )? '<!--END CF DATA-->': '';

                if(isset($cf_data_formatted) && !( isset($this->shortcodeParams['hide_cf_data']) && $this->shortcodeParams['hide_cf_data'] == 'true') ){
                    $cf_data_formatted = array_unique(array_merge($cf_data_formatted, $cfData)); //Merge required CF from the miniplayer meta heading and those required for the filters 
                }else if(count($cfData) > 0){
                    $cf_data_formatted = $cfData;
                }
                if(isset($cf_data_formatted) && count($cf_data_formatted) > 0){
                    $cf_data_formatted = implode('', $cf_data_formatted);
                    $cf_data_formatted =   $commentStart . '<div class="srp_cf_output" style="display:none;">' . $cf_data_formatted . '</div>' . $commentEnd;
                }else{
                    $cf_data_formatted = '';
                }
            }


            /*
            ----------------------------------
            // END OF CF INTO DOM FOR FILTERS 
            ----------------------------------
            */

            /*
            ----------------------------------
            // START OF CF DISPLAY INTO COLUMNS.
            ----------------------------------
            */
            if($custom_fields_columns != false && function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1' ){
                $cf_object = array();
                $cf_value =''; 

                foreach ($custom_fields_columns_ar as $key => $value) {
                    $value = explode('::', $value);
                    if(!isset($value[1]) || $value[1] == ''){
                        break;
                    }else{
                        $valuekey = $value[1];
                    }
                    
                    $cf_object['name'] = $valuekey;

                    if( $valuekey != '' ){
                        if('pa_' == substr($valuekey, 0, 3)){
                            if (defined( 'WC_VERSION' )){
                                if(wc_get_product($postid)){
                                    $product = wc_get_product($postid);
                                    $cf_value = $product->get_attribute($valuekey);
                                }
                            }
                        }else{
                            $cf_value = get_post_meta( $postid, $valuekey, true );
                        }
                    

                    }else{
                        break;
                    }
                    if(function_exists('acf')){
                       //try to check if field_key present. underscore _postmeta shall contains the field key.
                        $cf_value_temp = get_post_meta( $postid, '_'.$valuekey, true );
                        if($cf_value_temp && !is_array($cf_value_temp) && 'field_' == substr($cf_value_temp, 0, 6)){
                            $cf_obj = get_field_object($cf_value_temp, $postid);
                            if($cf_obj){
                                $cf_value = $cf_obj['value'];
                            }
                        }
                    }
                    
                    // Handle taxonomy terms
                    if (is_string($valuekey)) {
                        if (taxonomy_exists($valuekey)) {
                            $terms = get_terms(array(
                                'taxonomy' => $valuekey,
                                'object_ids' => array($postid),
                                'fields' => 'names'
                            ));
                            
                            if (is_wp_error($terms)) {
                                // Handle the error
                            } elseif (!empty($terms)) {
                                $cf_value = join(', ', $terms);
                            } else {
                                $cf_value = '';
                            }
                        } 
                    }
                    //$cf_value = get_post_meta( $postid, $value[1], true );
                    // timestamps example: $cf_value = "[sonaar_ts post_id='". $postid ."']" . $track['lenght'] . "[/sonaar_ts]";
                    if (!$cf_value){
                        switch ($valuekey) {
                            case 'srmp3_cf_album_img':
                                $cf_value = '<img src="'. esc_html($track['poster']) .'" class="sr_cf_track_cover">';
                                break;
                            case 'srmp3_cf_length':
                                $cf_value = ($track['length']) ? $track['length'] : '';
                                $cfValue_class = ' srp-hide-track-time';
                                break;
                            case 'srmp3_cf_album_title':
                                $cf_value = $track['album_title'];
                                break;
                            case 'srmp3_cf_audio_title':
                                $cf_value = $trackTitle;
                                break;
                            case 'srmp3_cf_artist':
                                $cf_value = $track['track_artist'];
                                break;
                            case 'srmp3_cf_description':
                                $cf_value = force_balance_tags( wp_trim_words( strip_shortcodes( $track['description'] ) , esc_attr($track_desc_lenght), $excerptTrimmed ));
                                break;
                            case 'post_title':
                                $cf_value = get_the_title( $postid );
                                break;
                            case 'post_id':
                                $cf_value = $postid;
                                break;
                            case 'post_date':
                                $cf_value = get_the_date('', $postid);
                                break;
                            case 'post_modified':
                                $cf_value = get_the_modified_date('', $postid);
                                break;
                            case 'playlist-category':
                                $cf_value = (get_the_terms($postid,'playlist-category')) ? get_the_terms($postid,'playlist-category'): '';
                                break;
                            case 'playlist-tag':
                                $cf_value = (get_the_terms($postid,'playlist-tag')) ? get_the_terms($postid,'playlist-tag'): '';
                                break;                               
                            case 'podcast-show':
                                $cf_value = (get_the_terms($postid,'podcast-show')) ? get_the_terms($postid,'podcast-show'):'';
                                break;
                            case 'product_cat':
                                $cf_value = (get_the_terms($postid,'product_cat')) ? get_the_terms($postid,'product_cat'): '';
                                break;
                            case 'product_tag':
                                $cf_value = (get_the_terms($postid,'product_tag')) ? get_the_terms($postid,'product_tag'): '';
                                break;
                            case 'post_tags':
                                // we dont currently support Playlist tags
                                $prod_terms='';
                                $tags=array();
                                $taxonomy = get_post_taxonomies( $postid );
                                foreach ($taxonomy as $key => $tax) {
                                    $taxonomy = ($tax == 'product_tag') ? $tax : $taxonomy; 
                                }
                                if ($taxonomy == 'product_tag'){
                                    $prod_terms = wp_get_post_terms($postid, $taxonomy );
                                    if ( count( $prod_terms ) > 0 ) {
                                        foreach ($prod_terms as $key => $prod_term) {
                                            $term_name = $prod_term->name;
                                            $tags[] = $term_name;
                                        }
                                        $tags = implode( ', ', $tags );
                                        $cf_value = $tags ;
                                    }
                                }

                                break;
                        } 
                    }
                   
                    
                    if ($cf_value == 'true' || $cf_value == 'false'){  
                        $cf_value = filter_var($cf_value, FILTER_VALIDATE_BOOLEAN);
                    }
                    if (is_bool($cf_value) === true) {
                        $cf_value = ($cf_value) ? esc_html__("Yes", 'sonaar-music') : esc_html__("No", 'sonaar-music');
                    }else if(is_array($cf_value)){
                        
                        $cf_value_ar =  array();
                        foreach ($cf_value as $keyx => $valuex[0]) {
                            
                            if (is_object($valuex[0])){
                                $cf_value_ar[]= $valuex[0]->name;
                                //array_push($cf_value_ar, $valuex[0]->name);
                            }else{
                                $cf_value_ar[]= $valuex[0];
                                //array_push($cf_value_ar, $valuex[0]);
                            }
                        }
                        $cf_value = join(', ', $cf_value_ar);
                    }
                    if ( is_wp_error( $cf_value ) ){
                        $cf_value = '';
                    }
                    $column_width = (isset($value[2]) && $value[2] !='' ) ? $value[2] : '100px';
                    $cf_input_styling = (isset( $cf_object['name'] )) ? '[data-id="' . esc_attr($widget_id) . '"] .sr-playlist-cf--' . $cf_object['name'] .'{
                                            flex: 0 0 ' . esc_attr($column_width) . '
                                        }':'';
                    $wpkses_value = array('img' => array('src' => array(), 'class'=>array()), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(), 'target' => array()));
                    
                    $icon_html = '';
                    if(isset($value[3])){
                        if (substr($value[3], -4) === '.svg') {
                            $icon_html = '<img src="' . esc_url($value[3]) . '"/>'; // Use an img tag to display the SVG
                        }else{
                            $icon_html = '<i class="' . esc_attr($value[3]) . '"></i>';
                        }
                    }

                    $cf_input_formatted =  (isset( $cf_object['name'] )) ? '<div class="sr-playlist-cf-child sr-playlist-cf--' . esc_attr($cf_object['name']) . '" data-id="sr-playlist-cf--' . esc_attr($cf_object['name']) . '">' . $icon_html . wp_kses($cf_value, $wpkses_value) . '</div>' : '';
                    $cf_input_styling_ar[] = $cf_input_styling;
                    $cf_input_formatted_ar[] = $cf_input_formatted;
                }
               
            }
            $show_cf_headings_class = ($show_cf_headings) ? '' : 'srmp3-heading--hide';
            $sr_cf_heading = ($custom_fields_columns != false ) ? '<div class="sr-cf-heading ' . $show_cf_headings_class . '"><style>' . join(' ', $cf_input_styling_ar) . '</style>' . join(' ', $sr_cf_heading_html_ar) . '</div>' : '';
            $custom_fields = ($custom_fields_columns != false && (isset($cf_input_formatted_ar[0]) && $cf_input_formatted_ar[0] != '')) ? ' <div class="sr-playlist-cf-container">' . join(' ', $cf_input_formatted_ar) . '</div>' :'';
            
            $format_playlist .= ( isset($trackdescEscapedValue) || $noteButton != null ) ? '<div class="sr-playlist-item-flex">' : '';
            $format_playlist .= ($hasTracklistSoundwave) ? '<div class="srp_soundwave_wrapper">' . $this->fakeWave(true) . '</div>' : '';
            $format_playlist .= $track_artwork_value . $custom_fields . $song_store_list;
            $format_playlist .=  (is_string($cf_data_formatted))? $cf_data_formatted : '';
            $format_playlist .= ($noteButton != null)? $noteButton : '';
    
            $format_playlist .= (isset($trackdescEscapedValue)) ? $playlistTrackDesc : '';
            $format_playlist .= '</li>';

            if(!$relatedTrack){
                $trackNumber++; //Count visible track in the tracklist (All related tracks are hidden)
            }
            $trackIndexRelatedToItsPost++;//$trackIndexRelatedToItsPost is required to set the data-store-id. Data-store-id is used to popup the right content.
        }

        $feedurl = ($feed) ? '1' : '0';

        $hide_times_current = (!$hide_times) ? '
            <div class="currentTime">00:00</div>
        ' : '' ;
        $hide_times_total = (!$hide_times) ? '
            <div class="totalTime"></div>
        ' : '' ;

        $wave_margin = ($hide_times) ? 'style="margin-left:0px;margin-right:0px;"': ''; // remove margin needed for the current/total time

        $progressbar = '';
        $player_style = ($hide_progressbar && $playerWidgetTemplate == 'skin_float_tracklist') ? 'style="height:33px;"': '';
        if (!$hide_progressbar){
            $progressbar = '
                ' . $hide_times_current . ' 
                <div id="'.esc_attr($widget_id). '-' . bin2hex(random_bytes(5)) . '-wave" class="wave" ' . esc_attr($wave_margin) . '>
                ' . $this->fakeWave() . ' 
                </div>
                ' . $hide_times_total . ' 
            ';
         }else{
             // hide the progress bar
             $progressbar = '
                <div id="'.esc_attr($widget_id). '-' . bin2hex(random_bytes(5)) . '-wave" class="wave">
                ' . $this->fakeWave() . '
                </div>
                
            ';
         }
        
         if(
            $playerWidgetTemplate == 'skin_float_tracklist' &&
            !$this->getOptionValue('show_shuffle_bt') &&
            !$this->getOptionValue('show_speed_bt') &&
            !$this->getOptionValue('show_volume_bt')
         ){ 
             $main_control_xtraClass = ' srp_oneColumn';
        }else{
            $main_control_xtraClass = '';
        }

        $widgetPart_control = ($playerWidgetTemplate == 'skin_float_tracklist' || ! $show_playlist )?'<div class="srp_main_control'. $main_control_xtraClass .'">':'';
        $widgetPart_control .= '<div class="control">';
        if ( $this->getOptionValue('show_skip_bt') ){
            $widgetPart_control .=
            '<div class="sr_skipBackward sricon-15s" aria-label="Rewind 15 seconds" title="' . esc_html(Sonaar_Music::get_option('tooltip_rwd_btn', 'srmp3_settings_widget_player')) .'"></div>';
        }
        $prev_play_next_Controls = '';
        if(count($playlist['tracks']) > 1 ){
            $prev_play_next_Controls .= 
            '<div class="previous sricon-back" style="opacity:0;" aria-label="Previous Track" title="' . esc_html(Sonaar_Music::get_option('tooltip_prev_btn', 'srmp3_settings_widget_player')) .'"></div>';
        }
            $prev_play_next_Controls .=
            '<div class="play" style="opacity:0;" aria-label="Play" title="' . esc_html(Sonaar_Music::get_option('tooltip_play_btn', 'srmp3_settings_widget_player')) .'">
                <i class="sricon-play"></i>
            </div>';
        if(count($playlist['tracks']) > 1 ){
            $prev_play_next_Controls .=
            '<div class="next sricon-forward" style="opacity:0;" aria-label="Next Track" title="' . esc_html(Sonaar_Music::get_option('tooltip_next_btn', 'srmp3_settings_widget_player')) .'"></div>';
        };
        $widgetPart_control .= $prev_play_next_Controls;
       
        if ( $this->getOptionValue('show_skip_bt') ){
            $widgetPart_control .= 
            '<div class="sr_skipForward sricon-30s" aria-label="Forward 30 seconds" title="' . esc_html(Sonaar_Music::get_option('tooltip_fwrd_btn', 'srmp3_settings_widget_player')) .'"></div>';
        }
        $widgetPart_control .= ( $playerWidgetTemplate == 'skin_float_tracklist' )?'</div><div class="control">':'';
        if ( $this->getOptionValue('show_shuffle_bt') ){
            $widgetPart_control .= '<div class="sr_shuffle sricon-shuffle" aria-label="Shuffle Track" title="' . esc_html(Sonaar_Music::get_option('tooltip_shuffle_btn', 'srmp3_settings_widget_player')) .'"></div>';
        }
        if ( $this->getOptionValue('show_repeat_bt') && !$notrackskip){
            $widgetPart_control .= '<div class="srp_repeat sricon-repeat " aria-label="Repeat" data-repeat-status="playlist" title="' . esc_html(Sonaar_Music::get_option('tooltip_repeat_track_btn', 'srmp3_settings_widget_player')) .'"></div>';
        }
        if ( $this->getOptionValue('show_speed_bt') ){
                $widgetPart_control .= '<div class="sr_speedRate" aria-label="Speed Rate" title="' . esc_html(Sonaar_Music::get_option('tooltip_speed_btn', 'srmp3_settings_widget_player')) .'"><div>1X</div></div>';
        }
        if ( $this->getOptionValue('show_volume_bt') ){
                $widgetPart_control .= '<div class="volume" aria-label="Volume" title="' . esc_html(Sonaar_Music::get_option('tooltip_volume_btn', 'srmp3_settings_widget_player')) .'">
                <div class="sricon-volume">
                    <div class="slider-container">
                    <div class="slide"></div>
                </div>
                </div>
                </div>';
            }

        $trackTitle = (isset($trackTitle)) ? $trackTitle : '';
        $widgetPart_control .= ($playerWidgetTemplate == 'skin_boxed_tracklist' &&  ! $show_playlist )? '<div class="srp_track_cta"></div>': '';
        $widgetPart_control .= ($playerWidgetTemplate == 'skin_boxed_tracklist' && $this->getOptionValue('show_miniplayer_note_bt') )? $this->addNoteButton() :'';
        $widgetPart_control .= '</div>'; //End DIV .control
        $widgetPart_control .= ($playerWidgetTemplate == 'skin_float_tracklist' ||  ! $show_playlist )?'</div>':''; //End DIV .srp_main_control
        
        $class_player ='player ';
        $class_player .=($progressbar_inline) ? 'sr_player__inline ' : '';
        $controlArtwork = ($displayControlArtwork) ? $prev_play_next_Controls : '';
        $displayControlUnder = ($hide_control_under || $playerWidgetTemplate == 'skin_boxed_tracklist') ? '' : $widgetPart_control;
        $noLoopTracklist = ($noLoopTracklist == false) ? get_post_meta($albums, 'no_loop_tracklist', true) : $noLoopTracklist;
        $notrackskip = ($notrackskip == false) ? get_post_meta($albums, 'no_track_skip', true) : $notrackskip;
        $showControlOnHoverClass = ($showControlOnHover)? 'srp_show_ctr_hover' : '';

        if( $slider ){
            $swiperClass = ( isset($this->shortcodeParams['slider_play_on_hover']) && $this->shortcodeParams['slider_play_on_hover'] == 'true' ) ? ' srp_slider_play_cover_hover' : '' ;
            $swiperClass .= ( isset($this->shortcodeParams['slider_content_on_active']) && $this->shortcodeParams['slider_content_on_active'] == 'true' ) ? ' srp_slider_content_on_active' : '' ;
            $swiperClass .= ( isset($this->shortcodeParams['slider_content_on_hover']) && $this->shortcodeParams['slider_content_on_hover'] == 'true' ) ? ' srp_slider_content_on_hover' : '' ;
            $swiperClass .= ( isset($this->shortcodeParams['slider_move_content_below_image']) && $this->shortcodeParams['slider_move_content_below_image'] == 'true' ) ? ' srp_slider_move_content_below' : '' ;
            $ifNavigationOutside = ($sliderNavigation && isset($this->shortcodeParams['slider_navigation_placement']) && $this->shortcodeParams['slider_navigation_placement'] == 'outside');
            $ifPaginationOutside = ($sliderPagination && isset($this->shortcodeParams['slider_pagination_placement']) && $this->shortcodeParams['slider_pagination_placement'] == 'outside');
            $ifNavigationOutsideCenter = ($ifNavigationOutside && isset($this->shortcodeParams['slider_navigation_vertical_alignment']) && $this->shortcodeParams['slider_navigation_vertical_alignment'] == 'center')? true : false;
            $swiperWrapClass = ($ifNavigationOutsideCenter)?' srp_swiper-nav-v-pos-center' : '';
            $navExtraClass = ( isset($this->shortcodeParams['slider_arrow_style']) && $this->shortcodeParams['slider_arrow_style'] == 'round' )? ' srp_arrow_round' : '' ;
            $navigationTemplate ='<div class="srp_swiper-button-prev' . $navExtraClass . '"></div><div class="srp_swiper-button-next' . $navExtraClass . '"></div>';
            $widgetPart_slider = '';
          
            $widgetPart_slider .= '<div class="srp_swiper-wrap' . $swiperWrapClass . '">';
        
            if($ifNavigationOutside){
                $navigationVerticalAlignment = (isset($this->shortcodeParams['slider_navigation_vertical_alignment']))?  $this->shortcodeParams['slider_navigation_vertical_alignment'] : 'bottom';
                $widgetPart_slider .= '<div class="swiper-box-navigation" data-v-align="' . $navigationVerticalAlignment . '">';
            }
            if($ifNavigationOutsideCenter){
                $widgetPart_slider .= $navigationTemplate;
                $widgetPart_slider .= '</div>';  // swiper-box-navigation
            }
            $widgetPart_slider .= '<div class="srp_swiper swiper' . esc_attr($swiperClass) . '" ' . $dataSwiperSource . '  data-params="' .  esc_attr( $sliderParams ) . '" >';
            $widgetPart_slider .= '<div class="swiper-wrapper">';
            $slideList = $playlist['tracks'];
            if( $sliderSource == 'post' ){
                $newSlideList = array();
                foreach ( $slideList as $trackEl ) {
                    array_push($newSlideList, $trackEl['sourcePostID']);
                }
                $slideList =  array_unique($newSlideList);
            }

            $index = 0;
            $trackIndexRelatedToItsPost = 0; //variable required to set the data-store-id. Data-store-id is used to popup the right content.
            $currentTrackId = ''; //Used to set the $trackIndexRelatedToItsPost

            foreach ( $slideList as $trackIndex => $slide ) {
                if( $sliderSource == 'track' ){
                    $slidePostId = $slide['sourcePostID'];
                    $slideTrackPos = $slide['track_pos'];
                    $slideId = $trackIndex;
                    $slideArtwork = $slide['poster'];
                }else{
                    $slideId = str_replace(' ', '', $slide);
                    $trackIndex = array_search(intval($slideId), array_column($playlist['tracks'], 'sourcePostID'));
                    $slidePostId = $playlist['tracks'][$trackIndex]['sourcePostID'];
                    $slideTrackPos = $playlist['tracks'][$trackIndex]['track_pos'];
                    $slideArtwork = $playlist['tracks'][$trackIndex]['poster'];
                }
                $slideClasses = 'swiper-slide';
                $widgetPart_slider .= '<div class="' . $slideClasses . '" data-post-id="' . $slidePostId . '" data-track-pos="' . $slideTrackPos . '" data-slide-id="' . $slideId . '" data-slide-id="' . $slideId . '" data-slide-index="' . $index . '"><div class="srp_swiper-album-art" style="background-image:url(' . $slideArtwork . ')"><div class="srp_swiper_overlay"></div>'; 

                $widgetPart_slider .= '<div class="srp_swiper-control"><div class="srp_play" aria-label="Play"><i class="sricon-play"></i></div></div>';
                //$widgetPart_slider .= ( $slideArtwork != '')? '<img alt="album-art" src="' .  $slideArtwork . '">' : '';
                $widgetPart_slider_content = '<div class="srp_swiper-titles">';
                $widgetPart_slider_content .= '<div class="srp_index">' .($index + 1) . '</div>';
                $widgetPart_slider_album_title = ( ! isset($this->shortcodeParams['slider_hide_album_title']) || ( isset($this->shortcodeParams['slider_hide_album_title']) && $this->shortcodeParams['slider_hide_album_title'] !== 'true' ) )? '<div class="srp_swiper-title">' . esc_html($playlist['tracks'][$trackIndex]['album_title']) . '</div>' : '';
                $widgetPart_slider_track_title = ( ! isset($this->shortcodeParams['slider_hide_track_title']) || ( isset($this->shortcodeParams['slider_hide_track_title']) && $this->shortcodeParams['slider_hide_track_title'] !== 'true' ) )? '<div class="srp_swiper-track-title">' . esc_html($playlist['tracks'][$trackIndex]['track_title']) . '</div>' : '';
                $widgetPart_slider_track_title .= ((! isset($this->shortcodeParams['slider_hide_artist']) || (isset($this->shortcodeParams['slider_hide_artist']) && $this->shortcodeParams['slider_hide_artist'] != 'true')) && isset($playlist['tracks'][$trackIndex]['track_artist']) && $playlist['tracks'][$trackIndex]['track_artist'] != '' )? '<div class="srp_swiper-track-artist">' . esc_html($artistSeparator . ' ' . $playlist['tracks'][$trackIndex]['track_artist']) . '</div>' : '';
                
                if( isset($this->shortcodeParams['slider_titles_order']) && $this->shortcodeParams['slider_titles_order'] == 'true' ) {
                    $widgetPart_slider_content .= $widgetPart_slider_track_title . $widgetPart_slider_album_title;
                }else{
                    $widgetPart_slider_content .= $widgetPart_slider_album_title . $widgetPart_slider_track_title;
                }

                if( isset($sliderSource) && $sliderSource == 'post' ){
                    $index = $trackIndex;
                }

                if($currentTrackId != $slidePostId){ //Reset $trackIndexRelatedToItsPost counting. It is incremented at the end of the foreach.
                    $currentTrackId = $slidePostId;
                    $trackIndexRelatedToItsPost = 0; 
                }


                if( 
                    ( get_post_meta( $currentTrackId, 'reverse_post_tracklist', true) || $this->getOptionValue('reverse_tracklist') ) &&  // If Reverse tracklist is set through the shortcode or throught the post settings, reverse the popup CTA odrer 
                    !(get_post_meta( $currentTrackId, 'reverse_post_tracklist', true) && $this->getOptionValue('reverse_tracklist') )  //But if Reverse tracklist is set twice, dont reverse the popup CTA odrer
                ){
                    $countTrackFromSamePlaylist = array_count_values( array_column($playlist['tracks'], 'sourcePostID') )[$currentTrackId];
                    $trackIndex =  $countTrackFromSamePlaylist - 1 - $trackIndexRelatedToItsPost;
                }else{
                    $trackIndex =  $trackIndexRelatedToItsPost;
                }

                
                $song_store_list_ar = $this->fetch_song_store_list_html($playlist['tracks'][$index], $trackIndex,  $show_track_market, $index);
                $song_store_list = $song_store_list_ar['store_list'];
                $playlist_has_ctas = (isset($playlist_has_ctas) && $playlist_has_ctas == true ) ? $playlist_has_ctas : $song_store_list_ar['playlist_has_ctas'];

                $widgetPart_slider_content .= $song_store_list;
                $widgetPart_slider_content .= '</div>';
                if( ! isset($this->shortcodeParams['slider_move_content_below_image']) || isset($this->shortcodeParams['slider_move_content_below_image']) && $this->shortcodeParams['slider_move_content_below_image'] != 'true' ){
                    $widgetPart_slider .= $widgetPart_slider_content;
                }
               
                $widgetPart_slider .= '</div>';
                if( isset($this->shortcodeParams['slider_move_content_below_image']) && $this->shortcodeParams['slider_move_content_below_image'] == 'true' ){
                    $widgetPart_slider .= $widgetPart_slider_content;
                }
                $widgetPart_slider .= '</div>';
                $index++;
                $trackIndexRelatedToItsPost++;//$trackIndexRelatedToItsPost is required to set the data-store-id. Data-store-id is used to popup the right content.
            }
            $widgetPart_slider .= '</div>';
            if( $sliderNavigation && ! $ifNavigationOutside ){
                $widgetPart_slider .= $navigationTemplate;
            }
            if($sliderPagination && ! $ifPaginationOutside ){
                $widgetPart_slider .= '<div class="swiper-pagination"></div>';
            }
            if($sliderScrollbar){
                $widgetPart_slider .= '<div class="swiper-scrollbar"></div>';
            }
            $widgetPart_slider .= '</div>';
            if($ifNavigationOutside && !$ifNavigationOutsideCenter){
                $widgetPart_slider .= '<div class="srp_swiper-navigation">' . $navigationTemplate . '</div>';
                $widgetPart_slider .= '</div>'; // swiper-box-navigation
            }
            if( $ifPaginationOutside ){
                $widgetPart_slider .= '<div class="swiper-box-pagination"><div class="swiper-pagination"></div></div>';
            }
        
            $widgetPart_slider .= '</div>'; // srp_swiper-wrap

        }

        if(!$hide_artwork || $hide_artwork != "true"){
            $widgetPart_artwork = '<div class="sonaar-Artwort-box ' . $showControlOnHoverClass . '">
                <div class="control">
                    ' . $controlArtwork . '
                </div>
                <div class="album">
                    <div class="album-art">
                        <img alt="album-art">
                    </div>
                </div>
                </div>';
        }else{
            $widgetPart_artwork = '';
        }   

        $widgetPart_title =  '<'.esc_attr($title_html_tag_playlist).' class="sr_it-playlist-title">'. esc_attr($playlist_title) .'</'.esc_attr($title_html_tag_playlist).'>';

        
        $widgetPart_subtitle =  '<div class="srp_subtitle">'. ( ( get_post_meta( $firstAlbum, 'alb_release_date', true ) )? esc_html(get_post_meta($firstAlbum, 'alb_release_date', true )) : '' ) . '</div>'; //'alb_release_date' field is now used for the subtitle

  

        $widgetPart_meta = '<div class="srp_player_meta">';
        $widgetPart_meta .= ($showPublishDate)?'<div class="sr_it-playlist-publish-date">'. esc_html(get_the_date( $dateFormat, $albums )) .'</div>':'';
        $widgetPart_meta .= ($this->getOptionValue('show_tracks_count')  && $trackNumber > 1 )?'<div class="srp_trackCount">'. esc_attr($trackNumber) . ' ' . esc_html(Sonaar_Music::get_option('player_show_tracks_count_label', 'srmp3_settings_widget_player')) .'</div>':'';
        $widgetPart_meta .= ($this->getOptionValue('show_meta_duration'))?'<div class="srp_playlist_duration" data-hours-label="'. esc_html(Sonaar_Music::get_option('player_hours_label', 'srmp3_settings_widget_player')) .'" data-minutes-label="'. esc_html(Sonaar_Music::get_option('player_minutes_label', 'srmp3_settings_widget_player')) .'"></div>':'';
        $widgetPart_meta .= '</div>';

        $tracklistClass = '';
        if( isset($this->shortcodeParams['tracklist_soundwave_style']) && ($this->shortcodeParams['tracklist_soundwave_style']  == 'simplebar' || $this->shortcodeParams['tracklist_soundwave_style']  == 'mediaElement') ){ // if shortcode param is set to SIMPLEBAR
            $tracklistClass .= ' sr_waveform_'. $this->shortcodeParams['tracklist_soundwave_style'];
        }elseif( isset( $this->shortcodeParams['tracklist_soundwave_style']) && $this->shortcodeParams['tracklist_soundwave_style'] == 'simplebar'  ){
            $tracklistClass .= ' sr_waveform_simplebar';
        }elseif($progress_bar_style){
            $tracklistClass .= ' sr_waveform_'. $progress_bar_style;
        }else{
            if( Sonaar_Music::get_option('waveformType', 'srmp3_settings_general') === 'mediaElement' || Sonaar_Music::get_option('waveformType', 'srmp3_settings_general') === 'wavesurfer'){
                $waveType = 'mediaElement';
            }else{
                $waveType = 'simplebar';
            }
            $tracklistClass .= ' sr_waveform_' . $waveType;
        }

        $tracklistClass .= ( $hasTracklistSoundwave )? ' srp_tracklist_waveform_enabled' : '';
        $tracklistClass .= ( isset( $this->shortcodeParams['artist_hide'] ) &&  $this->shortcodeParams['artist_hide'] === 'true' && Sonaar_Music::get_option('show_artist_name', 'srmp3_settings_general') )? ' srp_tracklist_hide_artist' : '';  
        $tracklist_datas = (isset($this->shortcodeParams['tracklist_soundwave_bar_width'])) ? ' data-wave-bar-width="' . esc_attr($this->shortcodeParams['tracklist_soundwave_bar_width']) . '"' : '';
        $tracklist_datas .= (isset($this->shortcodeParams['tracklist_soundwave_bar_gap'])) ? ' data-wave-bar-gap="' . esc_attr($this->shortcodeParams['tracklist_soundwave_bar_gap']) . '"' : '';
        $tracklist_datas .= (isset($this->shortcodeParams['tracklist_soundwave_line_cap']) ) ? ' data-wave-line-cap="' . esc_attr($this->shortcodeParams['tracklist_soundwave_line_cap']) . '"' : '';
        
        $widgetPart_tracklist = ($playerWidgetTemplate == 'skin_boxed_tracklist' && $trackNumber <= 1 && isset($this->shortcodeParams['one_track_boxed_hide_tracklist']) && $this->shortcodeParams['one_track_boxed_hide_tracklist'] == "true") ? '<div class="playlist' . $tracklistClass . '" ' . $tracklist_datas . ' id="playlist_'. $widget_id .'" style="display:none;">' : '<div class="playlist' . $tracklistClass . '" ' . $tracklist_datas . ' id="playlist_'. $widget_id .'">';
        $widgetPart_tracklist .= (!$hide_album_title && $playerWidgetTemplate == 'skin_float_tracklist') ? $widgetPart_title : '' ;
        $widgetPart_tracklist .= ($hide_album_subtitle || $playerWidgetTemplate == 'skin_boxed_tracklist') ? '' : $widgetPart_subtitle;
        $widgetPart_tracklist .= ( ($showPublishDate || $this->getOptionValue('show_meta_duration') || $this->getOptionValue('show_tracks_count')) && $playerWidgetTemplate == 'skin_float_tracklist') ? $widgetPart_meta : '';
        $widgetPart_tracklist .= ( $playerWidgetTemplate == 'skin_float_tracklist' ) ? $widgetPart_cat_description : '';

        if(function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1'){
            $labelSearchPlaceHolder = (isset( $this->shortcodeParams['searchbar_placeholder'] ) && $this->shortcodeParams['searchbar_placeholder'] != '' ) ? $this->shortcodeParams['searchbar_placeholder'] : $labelSearchPlaceHolder;
            $searchbar_show_keyword_displayClass = ($this->getOptionValue('searchbar') !== false ) ? 'display:flex;' : 'display:none;';
            $searchbar_show_keyword = '<div class="srp_search_container" style="' . $searchbar_show_keyword_displayClass . '" data-metakey="search" data-label="' . esc_html($labelSearch) .'"><i class="fas fa-search"></i><input class="srp_search" enterkeyhint="done" placeholder="' .  esc_html($labelSearchPlaceHolder) . '" \><i class="srp_reset_search sricon-close-circle" style="display:none;"></i></div>';
            $searchbar_container =  ( $this->getOptionValue('searchbar') ) ? '<div class="srp_search_main">' . $searchbar_show_keyword . '</div>' : '';
            
            $pagination = ($tracks_per_page) ? '<div class="srp_pagination_container"><div class="srp_pagination_arrows srp_pagination--prev sricon-back"></div><ul class="srp_pagination"></ul><div class="srp_pagination_arrows srp_pagination--next sricon-forward"></div></div>' : '' ;
            if($isPlayer_recentlyPlayed){
                $noresulthtml = ($outputNoResultDom) ? '<div class="srp_notfound"><div class="srp_notfound--subtitle">'. esc_html($labelNoRecentTrack) .'</div></div>' : '';
            }else{
                $noresulthtml = ($outputNoResultDom) ? '<div class="srp_notfound"><div class="srp_notfound--title">'. esc_html($labelNoResult1) .'</div><div class="srp_notfound--subtitle">'. esc_html($labelNoResult2) .'</div></div>' : '';
            }
            $widgetPart_tracklist .= $searchbar_container . $sr_cf_heading . '<div class="srp_tracklist">' . $noresulthtml . '<ul class="srp_list" data-filters="' . esc_html( implode(',', $this->cf_dataSort) ) . '">' . $format_playlist . '</ul>' . $pagination . '</div></div>';
        }else{
            $widgetPart_tracklist .= '<div class="srp_tracklist"><ul class="srp_list">' . $format_playlist . '</ul></div></div>';
        }
        $widgetPart_albumStore = '<div class="album-store">' . $this->get_market( $store_title_text, $album_ids_with_show_market, $feedurl, $el_widget_id, $terms) . '</div>';
        
        if($displayControlArtwork){
            $widgetPart_playButton = '';
        }else{
            $extraClass = ( isset( $this->shortcodeParams['button_animation'] ) )?' srp-elementor-animation elementor-animation-' . $this->shortcodeParams['button_animation'] :'';
            $extraClassForlabelOnly = ( $this->getOptionValue( 'use_play_label_with_icon', false ) )?' sricon-play':''; 

            $extraStyle = ''; 
            $extraStyle .= ( isset( $this->shortcodeParams['play_bt_bg_color'] ) )?' background:' . $this->shortcodeParams['play_bt_bg_color'] . ';':''; 
            $extraStyle .= ( isset( $this->shortcodeParams['play_bt_text_color'] ) )?' color:' . $this->shortcodeParams['play_bt_text_color'] . ';':''; 
          
    
            $widgetPart_playButton = ( $usePlayLabel ) ? '
            <div class="srp-play-button play srp-play-button-label-container' . $extraClass . $extraClassForlabelOnly . '" href="#" style="' . esc_attr( $extraStyle ) . '">
                <div class="srp-play-button-label" aria-label="Play">' . esc_html($labelPlayTxt) .'</div>
                <div class="srp-pause-button-label" aria-label="Pause">' . esc_html($labelPauseTxt) .'</div>
            </div>'
            :'
            <div class="srp-play-button play' . $extraClass . '" href="#" aria-label="Play">
                <i class="sricon-play"></i>
                <div class="srp-play-circle"></div>
            </div>';
        }

        $track_memory = (Sonaar_Music::get_option('track_memory', 'srmp3_settings_general') === 'true') ? true : false;
        if ( isset($this->shortcodeParams['track_memory']) ){
            if ( $this->shortcodeParams['track_memory'] === 'true' ) {
                $track_memory = true;
            }
            if ( $this->shortcodeParams['track_memory'] === 'false' ) {
                $track_memory = false;
            }
        }
        $extraClass = ( function_exists( 'run_sonaar_music_pro' ) && $progressbar_inline )? ' srp_progressbar_inline':'';
        $ironAudioClass .= ($playlist_has_ctas) ? '' : ' playlist_has_no_ctas';
        $ironAudioClass .= ( isset($cfValue_class) ) ? $cfValue_class : '' ;
        $ironAudioClass .= ( $tracklistGrid ) ? ' srp_tracklist_grid' : '' ;
        $ironAudioClass .= ( $this->getOptionValue('track_artwork_play_button') ) ? ' srp_tracklist_play_cover' : '' ;
        $ironAudioClass .= ( $this->getOptionValue('track_artwork_play_on_hover') ) ? ' srp_tracklist_play_cover_hover' : '' ;
        $ironAudioClass .= ( $slider ) ? ' srp_slider_enable' : '' ;
        $ironAudioClass .= ($hasFavoriteCta) ? ' srp_favorites_loading' : '' ;
        $ironAudioClass .= ($track_memory) ? ' srp_track_memory' : '' ;
        $ironAudioClass .= ($playerWidgetTemplate == 'skin_float_tracklist') ? ' skin_floated' : '' ;
        if(isset($this->shortcodeParams['class'])){
            $ironAudioClass .= ' ' .$this->shortcodeParams['class'];
        }
        $miniPlayerClass = '';
        if($progress_bar_style){
            $miniPlayerClass .= ' sr_waveform_' . $progress_bar_style;
        }else{
            if( Sonaar_Music::get_option('waveformType', 'srmp3_settings_general') === 'mediaElement' || Sonaar_Music::get_option('waveformType', 'srmp3_settings_general') === 'wavesurfer'){
                $waveType = 'mediaElement';
            }else{
                $waveType = 'simplebar';
            }
            $miniPlayerClass .= ' sr_waveform_' . $waveType;
        }
        if( $this->getOptionValue('show_prevnext_bt')  && $playerWidgetTemplate == 'skin_boxed_tracklist' ){
            $miniPlayerClass .= ' srp_show_prevnext_bt';
        }
        if(isset($this->shortcodeParams['control_alignment'])  && isset($this->shortcodeParams['control_alignment']) == 'left' && $progressbar_inline ){
            $miniPlayerClass .= ' srp_control_left';
        }

        $miniPlayer_datas = (isset($this->shortcodeParams['wave_bar_width'])) ? ' data-wave-bar-width="' . esc_attr($this->shortcodeParams['wave_bar_width']) . '"' : '';
        $miniPlayer_datas .= (isset($this->shortcodeParams['wave_bar_gap'])) ? ' data-wave-bar-gap="' . esc_attr($this->shortcodeParams['wave_bar_gap']) . '"' : '';
        $miniPlayer_datas .= (isset($this->shortcodeParams['wave_fadein'])) ? ' data-wave-fadein="' . esc_attr($this->shortcodeParams['wave_fadein']) . '"' : '';
        $miniPlayer_datas .= (isset($this->shortcodeParams['wave_line_cap']) ) ? ' data-wave-line-cap="' . esc_attr($this->shortcodeParams['wave_line_cap']) . '"' : '';
        $widgetPart_main = '<div class="album-player' . $miniPlayerClass . '"' . $miniPlayer_datas . '>';

        $widgetPart_main .= $miniPlayer_metas;
        $widgetPart_main .= ( !$hide_album_subtitle && $playerWidgetTemplate == 'skin_boxed_tracklist') ? $widgetPart_subtitle : '';
        $widgetPart_main .= ( $playerWidgetTemplate == 'skin_boxed_tracklist' )? $widgetPart_meta . '<div class="srp_control_box">'. $widgetPart_playButton .'<div class="srp_wave_box' . $extraClass . '">' : '';
        $widgetPart_main .= ' <div class="' . esc_attr($class_player) . '" ' . esc_attr($player_style) . '><div class="sr_progressbar">' . $progressbar . ' </div>' . $displayControlUnder . '</div>';
        if($playerWidgetTemplate == 'skin_boxed_tracklist'){
            $widgetPart_main .= ( ($usePlayLabel || $this->getOptionValue('play_btn_align_wave') ) && !$progressbar_inline)?  '</div></div>'. $widgetPart_control :   $widgetPart_control . '</div></div>';
        }
        $albums = str_replace(' ', '', $albums);

        $widgetData = ($artwork)?'data-albumart="' . $artwork. '"' : '';

        $feed = str_replace('&', 'amp;', $feed); //replace & with amp; to avoid conflict with json
        $feed_title = str_replace( "'", "apos;", $feed_title ); //replace ' with apos; to avoid conflict with json
        $feed_title_raw = rawurlencode(wp_unslash($feed_title));
        
        //We set the json file in attribute to be used in the sticky.
        //$category = ( isset( $this->shortcodeParams['category'] ) ) ? $this->shortcodeParams['category'] : false;
        if ( $category ) {
          $albums = '';
        }
        $json_file = home_url('?load=playlist.json&amp;title='.$title.'&amp;albums='.$albums.'&amp;category='.$category.'&amp;posts_not_in='.$posts_not_in.'&amp;category_not_in='.$category_not_in.'&amp;author='.$author.'&amp;feed_title='.$feed_title_raw.'&amp;feed='.$feed.'&amp;feed_img='.$feed_img.'&amp;el_widget_id='.$el_widget_id.'&amp;artwork='.$artwork .'&amp;posts_per_pages='.$posts_per_pages .'&amp;all_category='.$all_category .'&amp;single_playlist='.$single_playlist .'&amp;reverse_tracklist='. $this->getOptionValue('reverse_tracklist') .'&amp;audio_meta_field='.$audio_meta_field .'&amp;repeater_meta_field='.$repeater_meta_field .'&amp;import_file='.$import_file .'&amp;rss_items='.$rss_items .'&amp;rss_item_title='.$rss_item_title .'&amp;is_favorite=' . $isPlayer_Favorite .'&amp;is_recentlyplayed=' . $isPlayer_recentlyPlayed );
       
        $jsonExtraParamNames = ['srp_player_id','srp_meta','srp_search','srp_page','srp_order']; //Add params from ajaxInstance in the json file
        foreach ($jsonExtraParamNames as $name) {
            if ( isset($this->shortcodeParams[$name]) && $this->shortcodeParams[$name] != '') {
                $json_file .= "&" . $name . "=" . urlencode($this->shortcodeParams[$name]);
            }
        }
        // set default order if not specified
        if (isset($this->shortcodeParams['srp_order']) && empty($this->shortcodeParams['srp_order']) || !isset($this->shortcodeParams['srp_order'])) {
            
            if( $reverse_tracklist == true ){
                if( $this->shortcodeParams['order'] == 'DESC' ){
                    $ordering = 'ASC';
                }else{
                    $ordering = 'DESC';
                }
            }else{
                $ordering = $this->shortcodeParams['order'];
            }
            $json_file .= "&srp_order=" . $this->shortcodeParams['orderby'] . '_' . $ordering;

        }


        $output = '';
       
        $total_items = (isset($returned_data['total_items'])) ? ' data-total_items="' . $returned_data['total_items'] . '"' : '';
        $total_pages = (isset($returned_data['total_pages'])) ? ' data-total_pages="' . $returned_data['total_pages'] . '"' : '';        

        if(is_array($terms)){ 
            $all_term_ids = $terms; // Start with the provided terms
            
            foreach($terms as $term_id) {
                $term_data = get_term($term_id);

                if (is_wp_error($term_data) || $term_data === null ) {
                    continue; // Skip this iteration of the loop
                }
                $taxonomy_of_term = $term_data->taxonomy;
            
                // Fetch child term IDs for each term in $terms
                $child_term_ids = get_terms(array(
                    'taxonomy' => $taxonomy_of_term,
                    'child_of' => $term_id,
                    'fields' => 'ids',
                    'hide_empty' => false
                ));
            
                // Merge the child term IDs with our list of all term IDs
                $all_term_ids = array_merge($all_term_ids, $child_term_ids);
            }
            
            // Ensure unique term IDs (remove duplicates)
            $all_term_ids = array_unique($all_term_ids);
            
            // Convert the combined list of term IDs into a comma-separated string
            $termsToFetch = implode(',', $all_term_ids);
        }else{
            $termsToFetch = $terms; //probably 'all'
        }

        if (!empty($this->shortcodeParams['css'])) {
            $widget_id = esc_attr($widget_id);
            $clean_css = trim(strip_tags($this->shortcodeParams['css']));
            
            // Initialize import rule variable
            $import_rule = '';
            // WIP
            // Extract font family, font weight, and font style from CSS 
            /*preg_match_all('/font-family\s*:\s*([^;]+);/', $clean_css, $font_matches);
            preg_match_all('/font-weight\s*:\s*([^;]+);/', $clean_css, $weight_matches);
            preg_match_all('/font-style\s*:\s*([^;]+);/', $clean_css, $style_matches);
            
            $font_families = !empty($font_matches[1]) ? array_map('trim', $font_matches[1]) : array('');
            
            $font_weights = !empty($weight_matches[1]) ? array_map('trim', $weight_matches[1]) : array('400');
            $font_styles = !empty($style_matches[1]) ? array_map('trim', $style_matches[1]) : array('normal');
            
            // Construct @import rules for Google Fonts
            foreach ($font_families as $key => $font_family) {
                if (empty($font_family)) {
                    continue;
                }
                $font_weight = isset($font_weights[$key]) ? $font_weights[$key] : '400';
                $font_style = isset($font_styles[$key]) ? $font_styles[$key] : 'normal';
                
                // Construct @import rule for Google Font
                $import_url = "https://fonts.googleapis.com/css?family=" . urlencode($font_family) . "&display=swap";
                $import_rule .= "@import url(" . $import_url . ");\n";
            }*/
        
            // Adjust the regex to handle multiple selectors separated by commas
            $scoped_css = preg_replace_callback(
                '/([^{}]+)\s*(\{[^}]*\})/',
                function ($matches) use ($widget_id) {
                    // Split the selectors by commas to handle them individually
                    $selectors = explode(',', $matches[1]);
                    $selectors = array_map(function ($selector) use ($widget_id) {
                        return trim('#' . $widget_id . ' ' . trim($selector));
                    }, $selectors);
            
                    // Join the selectors back with commas and return the full CSS rule
                    return implode(', ', $selectors) . ' ' . $matches[2];
                },
                $clean_css
            );
         
            
            // Combine import rule and scoped CSS
            $output .= '<style id="srp-widget-player-style">' . htmlspecialchars($import_rule . $scoped_css) . '</style>';
        }
        
        
        
       

        $output .= '<div class="iron-audioplayer ' . esc_attr($ironAudioClass) . '" id="'. esc_attr( $widget_id ) .'-' . bin2hex(random_bytes(5)) . '" data-id="' . esc_attr($widget_id) .'" data-track-sw-cursor="' . esc_attr($hasTracklistCursor) .'" data-lazyload="'. esc_attr( $lazy_load) .'" data-albums="'. esc_attr( $albums) .'" data-category="'. esc_attr($termsToFetch) .'" data-url-playlist="' . esc_url( $json_file ) . '" data-sticky-player="'. esc_attr($sticky_player) . '" data-shuffle="'. esc_attr($shuffle) . '" data-playlist_title="'. esc_html($playlist_title) . '" data-scrollbar="'. esc_attr($scrollbar) . '" data-wave-color="'. esc_attr($wave_color) .'" data-wave-progress-color="'. esc_attr($wave_progress_color) . '" data-spectro="'. esc_attr($spectro) .'" data-no-wave="'. esc_attr($hide_timeline) . '" data-hide-progressbar="'. esc_attr($hide_progressbar) . '" data-progress-bar-style="'. esc_attr($progress_bar_style) . '"data-feedurl="'. esc_attr($feedurl) .'" data-notrackskip="'. esc_attr($notrackskip) .'" data-no-loop-tracklist="'. esc_attr($noLoopTracklist) .'" data-playertemplate ="'. esc_attr($playerWidgetTemplate) .'" data-hide-artwork ="'. esc_attr($hide_artwork) .'" data-speedrate="1" '. $widgetData .' data-tracks-per-page="'. esc_attr($tracks_per_page).'" data-pagination_scroll_offset="'. esc_attr($pagination_scroll_offset).'"' . $total_items . $total_pages .' data-adaptive-colors="'. esc_attr($adaptiveColors) .'" data-adaptive-colors-freeze="'. esc_attr($adaptiveColorsFreeze) .'"' . $player_datas . ' style="opacity:0;">';
        $output .= ($slider)? $widgetPart_slider : '';// Slider
        if($playerWidgetTemplate == 'skin_boxed_tracklist'){ // Boxed skin
            $output .= ($widgetPart_cat_description == '')?'<div class="srp_player_boxed srp_player_grid">':'<div class="srp_player_boxed"><div class="srp_player_grid">';
            $output .= $widgetPart_artwork . $widgetPart_main;// . $widgetPart_albumStore .'</div></div>';
            $output .= ( isset ($albumStorePosition) && $albumStorePosition == 'top') ? $widgetPart_albumStore : '';
            $output .= '</div></div>';
            $output .= ($widgetPart_cat_description == '')?'': $widgetPart_cat_description  . '</div>';
            $output .= $widgetPart_tracklist;
            $output .= ( isset ($albumStorePosition) && $albumStorePosition !== 'top') ? $widgetPart_albumStore : '';
        }else{ // Floated skin
            $spectrumBox = ($playerSpectrum && ($remove_player || $hide_timeline))?'<div class="srp_spectrum_box"></div>':'';
            $inlineSyle = ($widgetPart_artwork == '' &&  !$show_playlist)? 'style="display:none;"':''; //hide sonaar-grid and its background if it is empty
            // $output .= $widgetPart_artwork . $spectrumBox . $widgetPart_main . '<div class="sonaar-grid" '. esc_html($inlineSyle) . '>' . '</div>' . $widgetPart_tracklist . '</div>' . $widgetPart_albumStore; // move the mini player on top of tracklist WIP.
            $output .= '<div class="sonaar-grid" '. esc_html($inlineSyle) . '>'. $widgetPart_artwork . $widgetPart_tracklist . '</div>' . $spectrumBox . $widgetPart_main . '</div>' . $widgetPart_albumStore;
        }
        /*
        // Start Add Widget Parameters to be used to reload the player in ajax
        */
        $explode = explode('-', $widget_id);
        $variableName = end($explode);
        
        $output .= '</div>';

        //this script tag is for variable with json as value only. Used in the reloadAjax function.  
        $output .= '<script id="srp_js_params_' . $variableName . '">
        var srp_player_params_' . $variableName . ' = '. json_encode($this->shortcodeParams) . ' 
        var srp_player_params_args_' . $variableName . ' = '. json_encode($args) . '  
        </script>'; 

        if($isPlayer_Favorite){ 
            $selectorPlayer = ".iron-audioplayer[data-id='" . $widget_id . "']";
            $output .= '<script>
            var srp_player_params_' . $variableName . ' = '. json_encode($this->shortcodeParams) . '
            var srp_player_params_args_' . $variableName . ' = '. json_encode($args) . ' 
            
            if(typeof IRON.favorites !== "undefined" && typeof IRON.favorites.reloadPlayerAjax !== "undefined"){ 
                IRON.favorites.reloadPlayerAjax("' . $selectorPlayer . '" , null, true); 
            }</script>'; // Script required to reload player when the favorite player is inside a popup
        }
       if($isPlayer_recentlyPlayed){ 
            $selectorPlayer = ".iron-audioplayer[data-id='" . $widget_id . "']";
            $output .= '<script>
            var srp_player_params_' . $variableName . ' = '. json_encode($this->shortcodeParams) . '
            var srp_player_params_args_' . $variableName . ' = '. json_encode($args) . ' 
            
            if(typeof IRON.audioPlayer !== "undefined" && typeof IRON.audioPlayer.reloadAjax !== "undefined"){ 
                IRON.audioPlayer.reloadAjax( $(".iron-audioplayer[data-id=' . $widget_id . ']"), null, true );
            }</script>'; // Script required to reload player when the favorite player is inside a popup
        }

        if( $lazy_load ){ 
            $selectorPlayer = "$('.iron-audioplayer[data-id='" . $widget_id . "']')";
            $output .= '<script>
            if(typeof IRON.audioPlayer !== "undefined" && typeof IRON.audioPlayer.reloadAjax !== "undefined"){ 
                IRON.audioPlayer.reloadAjax( $(".iron-audioplayer[data-id=' . $widget_id . ']"), true, true );
            }
            </script>'; // Script required to reload player when the favorite player is inside a popup
        }
        /*
        // End Add Widget Parameters
        */
        $output .= '<script>if(typeof setIronAudioplayers !== "undefined"){ setIronAudioplayers("'.$widget_id.'"); }</script>'; // force to initialize when a player is loaded via third parties (eg ajax)
       
        if ( function_exists( 'wc_print_notices' ) && WC()->session ) {
			wc_print_notices(); // Print Woocommerce message. Eq: Feedback after Add to Cart
		}
        
        echo $output;
        echo $args['after_widget'];
    }
    private function explodeSearchValue($search){
        $search_values = explode(',', $search);
        $new_search_values = array();
        foreach ($search_values as $value) { 
            $new_search_values[] = $value;
            if (strpos($value, "\'") !== false) { 
                $value = str_replace("\'", "'", $value); //Duplicate search value with escaped single quote, without the escaped "\". Eq : Search for "O\'Brien" and "O'Brien"
                $new_search_values[] = $value;
            }
            if (strpos($value, "'") !== false) { 
                $value = str_replace("'", "&#8217;", $value); //Duplicate search value with single quote, with the Right Single Quotation Mark Hexo code. Eq : Search for "O'Brien" and "O’Brien". Audio file Metadata from the medialibrary are saved with the Right Single Quotation Mark Hexo code.
                $new_search_values[] = $value;
            }
        }
        return $new_search_values;
    }
    private function getQueryOrder($player){
       
        if(isset( $this->shortcodeParams['srp_order'])){
            $order_raw = $this->shortcodeParams['srp_order'];
        }else if(isset( $_GET['srp_order']) && $player == 'sticky' ){
            $order_raw = sanitize_text_field($_GET['srp_order']);
        }else{
            if(isset( $this->shortcodeParams['orderby'])){
                $order_raw =  $this->shortcodeParams['orderby'] . '_' . $this->shortcodeParams['order'];
            }else{
                //last resort: probably coming from a overall_sticky_playlist or footer post playlist
                $order_raw = 'date_desc';
            }
        }
        if (strtolower(substr($order_raw, -4)) == '_asc') {
            $order = 'ASC';
            $orderby = substr($order_raw, 0, -4);  // Everything except the last 4 characters
        } elseif (strtolower(substr($order_raw, -5)) == '_desc') {
            $order = 'DESC';
            $orderby = substr($order_raw, 0, -5);  // Everything except the last 5 characters
        } else {
            // Handle the case when there's no recognized order in $order_raw
            // For example, set default values
            $order = $this->shortcodeParams['order'];  // default order
            $orderby =  $this->shortcodeParams['orderby'];  // use the entire $order_raw as orderby
        }
        
        return array('order' => $order, 'orderby' => $orderby);
    }


    private function getAlbumsFromTerms($terms, $posts_not_in, $category_not_in, $author, $posts_per_page, $returnPostObj = false, $player = null, $reverse_tracklist = false) {
        $fields = $returnPostObj ? 'all' : 'ids';
    
        $paged = 1;
        $search = '';
        $custom_query_params = '';
        $ordering['order'] = (isset($this->shortcodeParams['order']) && !empty($this->shortcodeParams['order'])) ? $this->shortcodeParams['order'] :  'DESC';   // default order
        $ordering['orderby'] = (isset($this->shortcodeParams['orderby']) && !empty($this->shortcodeParams['orderby'])) ? $this->shortcodeParams['orderby'] : 'date';  // use the entire $order_raw as orderby
        if(isset($this->shortcodeParams['lazy_load']) && $this->shortcodeParams['lazy_load'] === 'true' || $player == 'sticky'){
    
            if(isset( $this->shortcodeParams['srp_page'])){
                $paged = $this->shortcodeParams['srp_page'];
            }else if(isset( $_GET['srp_page'] ) && $player == 'sticky'){
                $paged = sanitize_text_field($_GET['srp_page']);
            }else{
                $paged = 1;
            }
        
            if(isset( $this->shortcodeParams['srp_search'])){
                $search = $this->shortcodeParams['srp_search'];
            }else if(isset( $_GET['srp_search'] ) && $player == 'sticky' ){
                $search = sanitize_text_field($_GET['srp_search']);
            }else{
                $search = '';
            }

            if(isset( $this->shortcodeParams['srp_meta'])){
                $custom_query_params = $this->shortcodeParams['srp_meta'];
            }else if(isset( $_GET['srp_meta']) && $player == 'sticky' ){
                $custom_query_params = sanitize_text_field($_GET['srp_meta']);
            }else{
                $custom_query_params = '';
            }
            
            $ordering = $this->getQueryOrder($player);
            
            if( $reverse_tracklist == true && $player != 'sticky' && isset($this->shortcodeParams['lazy_load']) && $this->shortcodeParams['lazy_load'] === 'true'){
                if( $ordering['order'] == 'DESC' ){
                    $ordering['order'] = 'ASC';
                }else{
                    $ordering['order'] = 'DESC';
                }
            }
            

        }

        $albums = array();
        $tag_query = array();
        $meta_query = array();
        $or_queries = array();
        $or_tag_queries = array();
        $possible_taxonomies = get_taxonomies();
        $tax_query = array('relation' => 'AND');

        //$possible_taxonomies = ['product_tag', 'product_cat', 'pa_license', 'playlist-tag', 'playlist-category', 'podcast-show'];

        if (!empty($custom_query_params)) {
            $custom_params = explode(';', $custom_query_params);
            
            foreach ($custom_params as $param) {
                $param_parts = explode(':', $param, 2);
                if (count($param_parts) === 2) {
                    list($meta_key, $meta_value) = $param_parts;
                    $meta_values = explode(',', $meta_value);

                    if (in_array($meta_key, $possible_taxonomies) || in_array(substr($meta_key, 0, -3), $possible_taxonomies)) {
                        $taxonomy = (substr($meta_key, -3) === '_or') ? substr($meta_key, 0, -3) : $meta_key;

                        if (substr($meta_key, -3) === '_or') {
                            if (!isset($or_tag_queries[$taxonomy])) {
                                $or_tag_queries[$taxonomy] = array();
                            }
                            foreach ($meta_values as $value) {
                                $or_tag_queries[$taxonomy][] = array(
                                    'taxonomy' => $taxonomy,
                                    'field'    => 'name',
                                    'terms'    => trim($value),
                                );
                            }
                        } else {
                            foreach ($meta_values as $value) {
                                $tag_query[] = array(
                                    'taxonomy' => $taxonomy,
                                    'field'    => 'name',
                                    'terms'    => trim($value),
                                );
                            }
                        }
                    } else {
                        if ($meta_key === 'search') {
                            continue;
                        } elseif (substr($meta_key, -3) === '_or') {
                            $clean_key = substr($meta_key, 0, -3);
                            if (!isset($or_queries[$clean_key])) {
                                $or_queries[$clean_key] = array();
                            }
                            foreach ($meta_values as $value) {
                                $or_queries[$clean_key][] = array(
                                    'key'     => $clean_key,
                                    'value'   => trim($value),
                                    'compare' => 'LIKE',
                                );
                            }
                        } elseif (substr($meta_key, -7) === '_minmax') {
                            $clean_key = substr($meta_key, 0, -7);
                           if ($clean_key == 'track_length'){
                            $clean_key = 'srmp3_track_length';

                           }
                            if (!isset($or_queries[$clean_key])) {
                                $or_queries[$clean_key] = array();
                            }
                            foreach ($meta_values as $value) {
                                //$value = 20_120
                                $minmax = explode('_', $value);
                                $min = $minmax[0];
                                $max = $minmax[1];
                                $or_queries[$clean_key][] = array(
                                    'key'     => $clean_key,
                                    'value'   => array( $min, $max ),
                                    'type'    => 'numeric',
                                    'compare' => 'BETWEEN',
                                );
                            }
                        }else {
                            foreach ($meta_values as $value) {
                                $meta_query[] = array(
                                    'key'     => $meta_key,
                                    'value'   => trim($value),
                                    'compare' => 'LIKE',
                                );
                            }
                        }
                    }
                }
            }

            // Process OR meta queries
            foreach ($or_queries as $or_query) {
                $or_query_group = array('relation' => 'OR');
                foreach ($or_query as $query) {
                    $or_query_group[] = $query;
                }
                $meta_query[] = $or_query_group;
            }

            // Process OR tag queries
            foreach ($or_tag_queries as $or_tag) {
                $or_tag_group = array('relation' => 'OR');
                foreach ($or_tag as $tag) {
                    $or_tag_group[] = $tag;
                }
                $tag_query[] = $or_tag_group;
            }
        }


       // look for the tracklist in the meta
       if (!empty($search)) {
            $search_query = array('relation' => 'OR');
            $search_values = $this->explodeSearchValue($search);
            foreach ($search_values as $value) {
                $search_query[] = array(
                    'key'     => 'srmp3_search_data',
                    'value'   => trim($value),
                    'compare' => 'LIKE',
                );
            }
            if (count($search_query) > 1) {  // Check if there are any artists in the query
                $meta_query[] = $search_query;
            }
        }
        
        //convert posts_not_in in array
        /*if( $posts_not_in != '' ){
            $posts_not_in = explode(',', $posts_not_in);
        }else{
            $posts_not_in = array(); 
        }*/
        $sr_postypes = Sonaar_Music_Admin::get_cpt($all = true);

        $posts_not_in_array = ($posts_not_in) ? explode(',', $posts_not_in) : array();
        $category_not_in_array = ($category_not_in) ? explode(',', $category_not_in) : array();

        if (!empty($category_not_in_array)) {
            // Loop through each post type to get associated taxonomies
            foreach ($sr_postypes as $post_type) {
                $taxonomies = get_object_taxonomies($post_type, 'names');
                
                foreach ($taxonomies as $this_taxonomy) {
                    if (taxonomy_exists($this_taxonomy)) {
                        $tax_query[] = array(
                            'taxonomy' => $this_taxonomy,
                            'field'    => 'term_id',
                            'terms'    => $category_not_in_array,
                            'operator' => 'NOT IN',
                        );
                    }
                }
            }
        }

        // If specific terms are provided, use tax_query to fetch related post IDs
        if ($terms != 'all') {
            // Get the post types

            // Get relevant taxonomies for the post types
            $post_types_taxonomies = get_object_taxonomies($sr_postypes);

            $terms = array_map('trim', explode(",", $terms));
            
            $or_query = array('relation' => 'OR');
            // Loop through possible taxonomies
            foreach ($post_types_taxonomies as $taxonomy) {
                if (taxonomy_exists($taxonomy)) {
                    $or_query[] = array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'term_id',
                        'terms'    => $terms
                    );
                }
            }

            $tax_query[] = $or_query;

            $query_args = array(
                'fields'         => $fields,
                'post_status'    => 'publish',
                'paged'          => $paged,
                'posts_per_page' => $posts_per_page,
                'post_type'      => 'any',
                //'tax_query'      => $tax_queries,
                'meta_query'     => $meta_query, // Add custom meta_query here
                //'s'              => $search,
            );
        } else {
            // Get the option value and ensure it's an array
            // Remove overlapping IDs
            $query_args = array(
                'fields'         => $fields,
                'post_status'    => 'publish',
                'paged'          => $paged,
                'posts_per_page' => $posts_per_page,
                'post_type'      => $sr_postypes,
                'lang'           => '',
                'meta_query'     => $meta_query,
                //'tax_query'      => $tax_query,
            );
            
        }

        $query_args['post__not_in'] = $posts_not_in_array;
        $query_args['tax_query'] = $tax_query;
        $query_args['order'] = $ordering['order'];
       // Define the order-by criteria and their localized strings
        $order_by_criteria = array(
            'ID',
            'author',
            'title',
            'name',
            'type',
            'date',
            'modified',
            'parent',
            'rand',
            'comment_count',
            'relevance',
            'menu_order',
        );
        
        $meta_value_num = array(
            'srmp3_cf_length',
            'srmp3_track_length',
            '_price',
            '_sku',
            '_sale_price',
            'total_sales',
            '_wc_average_rating',
            '_stock_status'
        );
        if (in_array( $ordering['orderby'], $order_by_criteria)) {
            // If $orderby is one of the standard criteria, use it directly
            $query_args['orderby'] =  $ordering['orderby'];
        } else {
            // This is for custom fields
            if (in_array( $ordering['orderby'], $meta_value_num)) {
                $query_args['orderby'] = 'meta_value_num';
                if( $ordering['orderby'] == 'srmp3_cf_length'){
                    $query_args['meta_key'] = 'srmp3_track_length'; 
                }else{
                    $query_args['meta_key'] = $ordering['orderby'];
                }
            } else {
                $query_args['orderby'] = 'meta_value';
                $query_args['meta_key'] = $ordering['orderby'];
            }
        }
       
        if (!is_array($query_args['orderby'])) {
            if($query_args['orderby'] == 'date'){
                $query_args['orderby'] = array(
                    $query_args['orderby'] =>  $ordering['order'],
                    'ID' => 'ASC'
                );
            }else if($query_args['orderby'] == 'meta_value_num' ){ //When orderby is a meta_value_num, we need to add a second orderby to avoid random order when the meta_value is the same
                $query_args['orderby'] = array( 
                    $query_args['orderby'] =>  $ordering['order'], 
                    'title' => 'ASC' 
                ); 
            } 
        }
       
        if (!empty($tag_query)) {
            $query_args['tax_query'][] = $tag_query;
        }

        // Check if $author is present
        if (!empty($author)) {
            // If $author is provided, use it as the author query parameter
            $query_args['author'] = $author;
        }
       
        
        //error_log("Query Args: " . print_r($query_args, true));
        $query = new WP_Query($query_args);
        
        $total_items = $query->found_posts; // 2035
        $total_pages = intval(ceil($query->max_num_pages));
        $albums = $query->posts;
        //error_log("Query Args: " . print_r($albums, true));
        wp_reset_postdata();

        if($reverse_tracklist && $player == 'sticky'){
            $albums = array_reverse($albums);
        }

       return array(
        'albums' => $albums,
        'total_items' => $total_items,
        'total_pages' => $total_pages
        );
    }
    
    private function getTermsForFilters($postid, $termname){
       
        $termObj = get_the_terms($postid, $termname);
           
        
        if(!$termObj || is_wp_error($termObj) ) return;
        
        $term_ar =  array();
        foreach ($termObj as $term) {
            $term_cat_ar[]= $term->name;
        }
        $term_cat_formatted = join(', ', $term_cat_ar);

        if( ! in_array( $termname, $this->cf_dataSort ) ){
            array_push($this->cf_dataSort, $termname);
        }
        return '<div class="srp_cf_data sr-playlist-cf--' .  $termname . '">' . $term_cat_formatted . '</div>';
    }



    private function fakeWave(  $tracklistWave = false ){ 
        $waveBaseStyle = '';
        $waveCutStyle = '';
        $barHeight =(Sonaar_Music::get_option('sr_soundwave_height', 'srmp3_settings_general')) ? Sonaar_Music::get_option('sr_soundwave_height', 'srmp3_settings_general') : 70; 
        $mediaElementStyle = (Sonaar_Music::get_option('waveformType', 'srmp3_settings_general') === 'mediaElement' || Sonaar_Music::get_option('waveformType', 'srmp3_settings_general') === 'wavesurfer') ? 'style="height:'.esc_attr($barHeight).'px"' : ''; 
        $wave_color = Sonaar_Music::get_option('music_player_timeline_color', 'srmp3_settings_widget_player');
		$wave_progress_color = Sonaar_Music::get_option('music_player_progress_color', 'srmp3_settings_widget_player');
        if(!$tracklistWave && isset($this->shortcodeParams['progress_bar_style']) && $this->shortcodeParams['progress_bar_style']  == 'simplebar'){
            $wave_color = (bool)( isset( $this->shortcodeParams['wave_color'] ) )? $this->shortcodeParams['wave_color']: $wave_color;
            $wave_progress_color = (bool)( isset( $this->shortcodeParams['wave_progress_color'] ) )? $this->shortcodeParams['wave_progress_color']: $wave_progress_color;
            $waveBaseStyle = ($this->shortcodeParams['progress_bar_style']  == 'simplebar' && $wave_color)? ' style="background:'.esc_attr($wave_color).'"': '';
            $waveCutStyle = ($this->shortcodeParams['progress_bar_style'] == 'simplebar' && $wave_progress_color)? ' style="background:'.esc_attr($wave_progress_color).'"': '';
        }
        if($tracklistWave && isset($this->shortcodeParams['tracklist_soundwave_style']) && $this->shortcodeParams['tracklist_soundwave_style']  == 'simplebar'){
            $wave_color = (bool)( isset( $this->shortcodeParams['tracklist_soundwave_color'] ) )? $this->shortcodeParams['tracklist_soundwave_color']: $wave_color;
            $wave_progress_color = (bool)( isset( $this->shortcodeParams['tracklist_soundwave_progress_color'] ) )? $this->shortcodeParams['tracklist_soundwave_progress_color']: $wave_progress_color;
            $waveBaseStyle = ($this->shortcodeParams['tracklist_soundwave_style']  == 'simplebar' && $wave_color)? ' style="background:'.esc_attr($wave_color).'"': '';
            $waveCutStyle = ($this->shortcodeParams['tracklist_soundwave_style'] == 'simplebar' && $wave_progress_color)? ' style="background:'.esc_attr($wave_progress_color).'"': '';
        }
       
        return ' 
        <div class="sonaar_fake_wave" '. $mediaElementStyle . '> 
            <audio src="" class="sonaar_media_element"></audio> 
            <div class="sonaar_wave_base"' . $waveBaseStyle . '> 
                <canvas id="sonaar_wave_base_canvas" class="" height="'.esc_attr($barHeight).'" width="2540"></canvas> 
                <svg></svg> 
            </div> 
            <div class="sonaar_wave_cut"' . $waveCutStyle . '> 
                <canvas id="sonaar_wave_cut_canvas" class="" height="'.esc_attr($barHeight).'" width="2540"></canvas> 
                <svg></svg> 
            </div> 
        </div>'; 
    }

    /* Return the notebutton HTML or NULL */
    private function addNoteButton($postID = '', $trackPosition = '', $trackTitle = '', $trackdescEscapedValue = null, $excerptTrimmed = null, $track_desc_postcontent = null){
        /*parameters:
        -$postID: playlist post ID
        -$trackPosition: track position in the playlist post, not in the track list.
        -$trackTitle: The track title: Required to display it in the Note content
        -$trackdescEscapedValue: (OPTIONAL) The Excerpt content. We have to check if the "note" is cuted by the "$excerptTrimmed"("[...]").
        -$excerptTrimmed: (OPTIONAL) [...]
        */
        $returnValue = null;
        if( function_exists( 'run_sonaar_music_pro' ) ){
            if($postID != ''){
                if($track_desc_postcontent){
                    $post = get_post($postID); 
                    $trackFields = $post->post_content;
                }else{
                    $postObject = get_post_meta($postID, 'alb_tracklist', true );
                    $trackFields = (isset($postObject[$trackPosition]['track_description']))?$postObject[$trackPosition]['track_description'] : '';
                }
            }
            if( isset($trackFields) && $trackFields != '' || $postID === ''){
                if ( ($trackdescEscapedValue && substr(strip_tags($trackdescEscapedValue), -1 * (strlen($excerptTrimmed))) == $excerptTrimmed) || $trackdescEscapedValue == null || $postID === '' ){ // Check if the Excerpt display the whole description or if it is cuted/ended by the $excerptTrimmed[...].
                    $returnValue = '<div class="srp_noteButton"><i class="sricon-info"  data-source-post-id="' . esc_attr( $postID ) . '" data-track-position="' . esc_attr( $trackPosition ) . '" data-track-title="' . esc_attr( $trackTitle ) . '" data-track-use-postcontent="' . esc_attr( $track_desc_postcontent ) . '"></i></div>';
                }
            }
        }
        return $returnValue;
    }
    
    private function recursive_implode($glue, $value) {
        if (is_array($value)) {
            $temp_array = array();
            foreach ($value as $item) {
                $temp_array[] = $this->recursive_implode($glue, $item);
            }
            return implode($glue, $temp_array);
        } else {
            return $value;
        }
    }
    /*E.g. Return the value from "show_skip_bt" (shortcode) or "player_show_skip_bt" (plugin settings) */
    private function getOptionValue($optionID, $proRequired = true, $defaultValue = false){
        /*parameters:
        -$optionID: the option id from the plugins settings has to have the prefix "player_" add to the shortcode id (E.g. "player_show_skip_bt" for "show_skip_bt" )
        -$this->shortcodeParams: The $this->shortcodeParams variable
        -$proRequired: (OPTIONAL) We have to set this false if the option is available with the free plugin
        -$defaultValue: (OPTIONAL) If the setting is not saved return this value.
        */
        if($proRequired && !function_exists( 'run_sonaar_music_pro' ) ){ 
            return false;
        }
        if( isset($this->shortcodeParams[$optionID]) && $this->shortcodeParams[$optionID] != 'default') {
            return filter_var($this->shortcodeParams[$optionID], FILTER_VALIDATE_BOOLEAN); //get value from the shortcode
        }else if(Sonaar_Music::get_option('player_' . $optionID, 'srmp3_settings_widget_player') != null){
            return filter_var(Sonaar_Music::get_option('player_' . $optionID, 'srmp3_settings_widget_player'), FILTER_VALIDATE_BOOLEAN); //get value from the plugin settings
        }else{
            return $defaultValue;
        }
    }

    private function getSliderParams($paramName, $sliderParams){  
        $paramName .= ':'; 
        $result = null;
        if( strpos($sliderParams, $paramName)!== false ) {
            $sliderParams = str_replace(' ', '', $sliderParams);
            $start = strpos($sliderParams, $paramName) + strlen($paramName);
            $commaPosition = strpos($sliderParams, ",", $start);
            $bracketPosition = strpos($sliderParams, "}", $start);
            if ($start !== false && ($commaPosition !== false || $bracketPosition !== false)) {
                $end = ($commaPosition !== false && $commaPosition < $bracketPosition) ? $commaPosition : $bracketPosition;
                $result = trim(substr($sliderParams, $start, $end - $start));
                return $result;
            }  
        }
        return $result;
    }
    
    private function wordpress_get_full_path_of_url( $url ) {
        // Make "get_home_path()" function callable on frontend
        if( ! is_admin() ) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        // Get the document root path
        $root_path = get_home_path();

        // Get the path from URL
        $src = parse_url( $url );	
        $url_path = $src['path'];

        // Get only WordPress subdirectory if exist
        $subdirectory = site_url( '', 'relative' );

        $url_part_1 = str_replace( $subdirectory, '', $root_path );
        $url_part_2 = $url_path;

        // Return the full path
        return untrailingslashit( $url_part_1 ) . $url_part_2; 
    }

    private function wordpress_audio_meta( $audio_url ) {
        $meta = '';
        
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        if( function_exists( 'wp_read_audio_metadata' ) ) {
            // Get the base uploads directory
            $uploads = wp_upload_dir(null, false);
            $uploads_dir = $uploads['basedir'];
    
            // Construct the file path
            $file_path = str_replace(home_url('/wp-content/uploads/'), '', $audio_url);
            $file = $uploads_dir . '/' . $file_path;
    
            // Check if the file exists
            if ( file_exists( $file ) ) {
                // Read the audio metadata
                $meta = wp_read_audio_metadata( $file );
            }
       }

        return $meta;
    }
    
    private function wc_add_to_cart($id = null){
       
        if ( $id == null || ( !defined( 'WC_VERSION' ) && get_site_option('SRMP3_ecommerce') != '1' ) ){
            return false;
        }

        return get_post_meta($id, 'wc_add_to_cart', true);
    }
    private function wc_buynow_bt($id = null){
        if ($id == null || ( !defined( 'WC_VERSION' ) && get_site_option('SRMP3_ecommerce') != '1' )){
            return false;
        }

        return get_post_meta($id, 'wc_buynow_bt', true);
    }
           
    private function fetch_song_store_list_html($track, $trackIndex, $show_track_market, $key1){
        $song_store_list = '';
        $song_store_list_content = '';
        $isProductArchive = (isset($this->shortcodeParams['product_archive']) && $this->shortcodeParams['product_archive']=="true")? true : false;

        $song_store_list = '<span class="store-list">';

       // $playlist_has_ctas = $playlist_has_ctas;
       
        if($show_track_market && isset($track['album_store_list'][0])){
            $track['song_store_list'] = ( isset($track['song_store_list'][0]) ) ? array_merge($track['song_store_list'], $track['album_store_list']) : $track['album_store_list'];
            $track['has_song_store'] = true;
        }
        if( ($show_track_market || $isProductArchive ) && isset($track['optional_storelist_cta'][0])){ //Merge Store list CTA when plugin option is enabled
            $track['song_store_list'] = ( isset($track['song_store_list'][0]) ) ? array_merge($track['song_store_list'], $track['optional_storelist_cta']) : $track['optional_storelist_cta'];
            $track['has_song_store'] = true;
        }
        $song_store_list_content = '';
        if( ! is_array($track['song_store_list']) ){
            $track['song_store_list'] = [];
        }
        
        if(!$show_track_market && !$isProductArchive  && count($track['song_store_list']) > 0 ){
            $track['song_store_list'] = [];
        }
        if ( is_array($track['song_store_list']) ){
            if ($track['has_song_store']){
                if(count($track['song_store_list']) > 0 ){
                    foreach( $track['song_store_list'] as $key2 => $store ){
                        $storeButtonPosition[$key1][$key2]=[];
                        if(isset($store['link-option']) && $store['link-option'] == 'popup'){
                            if( array_key_exists('store-content', $store) ){
                                array_push ($storeButtonPosition[$key1][$key2], $store['store-content']);
                            }
                        }
                        if(!isset($store['store-icon'])){
                            $store['store-icon']='';
                        }
                        if(!isset($store['store-name'])){
                            $store['store-name']='';
                        }

                        $classes = 'song-store';
                        $extraAttributes = '';
                        
                        if(!isset($store['store-link'])){
                            $store['store-link']='';
                        }
                        $href = 'href="' . esc_url($store['store-link']) . '"';
                        $download="";
                        $label = '';

                        if(
                            $store['store-icon'] == "fas fa-download" && strpos($store['store-link'], '#') !== 0 && //If download CTA
                            (!isset($store['download-attr']) || isset($store['download-attr']) && $store['download-attr']) //If "condition NOT met" and force download CTA redirection is enabled 
                        ){
                            $download = ' download';
                        }

                        if(!isset($store['store-target'])){
                            $store['store-target'] = '_blank';
                        }else{
                            $store['store-target'] = '_self';
                        }

                        if(isset($store['link-option']) && $store['link-option'] == 'popup'){ //if Popup content
                        $classes .= ' sr-store-popup';
                        $store['store-target'] = '_self';
                        $href = '';
                        }

                       
                        if (isset($store['make-offer-bt'])){
                            $classes .= ' srp-make-offer-bt';
                        }else if( isset($store['has-variation'])  && ! $store['has-variation'] && Sonaar_Music::get_option('wc_enable_ajax_addtocart', 'srmp3_settings_woocommerce') == 'true' ){ 
                            $classes .= ' add_to_cart_button ajax_add_to_cart';
                            $extraAttributes .= ' data-product_id="' . esc_attr($track['sourcePostID']) . '"';
                        }

                        if( isset($store['cta-class'])){ 
                            $classes .= ' ' . $store['cta-class'];
                            
                            if (strpos($store['cta-class'], 'sr_store_force_dl_bt') !== false) { //If download CTA
                                if( isset( $this->shortcodeParams['force_cta_dl'] ) && $this->shortcodeParams['force_cta_dl'] == 'false' ){
                                    $classes .= ' srp_hidden';
                                }
                            }

                            if( $store['cta-class'] == 'sr_store_force_pl_bt'){ //If POST Link CTA
                                if( isset( $this->shortcodeParams['force_cta_singlepost'] ) && $this->shortcodeParams['force_cta_singlepost'] == 'false' ){
                                    $classes .= ' srp_hidden';
                                }
                            }
                            if( $store['cta-class'] == 'sr_store_force_share_bt'){ //If Share Link CTA
                                if( isset( $this->shortcodeParams['force_cta_share'] ) && $this->shortcodeParams['force_cta_share'] == 'false' ){
                                    $classes .= ' srp_hidden';
                                }
                            }
                            if( $store['cta-class'] == 'srp-fav-bt'){ //If Favorite CTA
                                if( isset( $this->shortcodeParams['force_cta_favorite'] ) && $this->shortcodeParams['force_cta_favorite'] == 'false' ){
                                    $classes .= ' srp_hidden';
                                }
                            }
                        }

                        if( function_exists( 'run_sonaar_music_pro' ) ){ 
                            $displayLabel = false;
                            if(Sonaar_Music::get_option('show_label', 'srmp3_settings_widget_player') != null){ //Display CTA Label: plugin settings
                                $displayLabel = filter_var(Sonaar_Music::get_option('show_label', 'srmp3_settings_widget_player'), FILTER_VALIDATE_BOOLEAN);
                            }
                            if( isset($this->shortcodeParams['cta_track_show_label']) && $this->shortcodeParams['cta_track_show_label'] != 'default') { //Display CTA Label: shortcode (second priority)
                                $displayLabel = filter_var($this->shortcodeParams['cta_track_show_label'], FILTER_VALIDATE_BOOLEAN);
                            }
                            if(isset($store['show-label']) && $store['show-label'] != 'default'){
                                $displayLabel = filter_var($store['show-label'], FILTER_VALIDATE_BOOLEAN); //Display CTA Label: post setting (first priority)
                            }
                            if($displayLabel){
                                $classes .= ' sr_store_wc_round_bt';
                                $label = '<span class="srp_cta_label">' . esc_attr($store['store-name']) . '</span>';
                            }
                            if ( isset($store['has-variation']) && array_key_exists('sourcePostID', $track) && $this->ifProductHasVariation($track['sourcePostID']) && Sonaar_Music::get_option('wc_variation_lb', 'srmp3_settings_woocommerce') !='false'){
                                $classes .= ' srp_wc_variation_button';
                            }
                        }
                        $storeTag = ( $isProductArchive ) ? 'div' : 'a'; //If product archive, use div instead of a tag because the <a> tag is not required on the product archive and they broke <a> tag from woocommerce.
                        $song_store_list_content .= '<' . $storeTag . ' ' . $href . esc_html($download) . 
                            ' class="' . esc_attr($classes) . '" ' .
                            'target="' . esc_attr($store['store-target']) . '" ' .
                            'title="' . esc_attr($store['store-name']) . '" ' .
                            'aria-label="' . esc_attr($store['store-name']) . '" ' .
                            'data-source-post-id="' . esc_attr($track['sourcePostID']) . '" ' .
                            'data-store-id="' . esc_attr($trackIndex . '-' . $key2) . '" ' .
                            (strpos($classes, 'sr_store_force_share_bt') !== false || 
                            strpos($classes, 'srp-fav-bt') !== false 
                                ? 'data-barba-prevent="all" ' 
                                : '') . 
                            $extraAttributes . 
                            ' tabindex="1"><i class="' . esc_html($store['store-icon']) . '"></i>' . 
                            $label . 
                            '</' . $storeTag . '>';
                        $playlist_has_ctas = true;
                    }
                }
                $song_store_list_content = ( $song_store_list_content != '' ) ? $song_store_list_content : '';
                $song_store_list .= '<div class="song-store-list-menu"><i class="fas fa-ellipsis-v"></i><div class="song-store-list-container">' . $song_store_list_content;
                $song_store_list .= '</div></div>';
            }
        }

        $song_store_list .= '</span>';
        // create new array
        $song_store_list = array(
            'store_list' => $song_store_list,
            'playlist_has_ctas' => isset($playlist_has_ctas) ? $playlist_has_ctas : false
        );
        return $song_store_list;
        
    }
    private function get_market($store_title_text, $album_id = 0, $feedurl = 0, $el_widget_id = null, $terms = null){
        
        if( $album_id == 0 && !$feedurl)
        return;

        if (!$feedurl){ // source if from albumid
            $firstAlbum = explode(',', $album_id);
            $firstAlbum = $firstAlbum[0];
            $storeList = get_post_meta($firstAlbum, 'alb_store_list', true);

            $wc_add_to_cart =  $this->wc_add_to_cart($firstAlbum);
            $wc_buynow_bt =  $this->wc_buynow_bt($firstAlbum);
            $is_variable_product = ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true' ) ? $this->is_variable_product($firstAlbum) : '';
            
            //check to add woocommerce icons for external links
            $album_store_list = ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true') ? $this->push_woocart_in_storelist(get_post($firstAlbum), $is_variable_product, $wc_add_to_cart, $wc_buynow_bt) : false;
          
            if ( is_singular( $this->sr_playlist_cpt ) && Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ) {
                if ($terms == null) {
                    //no terms variable is passed manually. So check if post has terms 
                    $terms = get_the_terms(  get_the_ID(), 'podcast-show' ); 
                    $terms = ($terms == false) ? null : $terms[0]->term_id;
                }
            }

            //check to add category icons for external links
            $album_cat_store_list = ($terms) ? $this->push_caticons_in_storelist( get_post($firstAlbum), $terms ) : null;
           
            // merge arrays temporary
            $album_store_list = (isset($album_store_list) && is_array($album_store_list) && count($album_store_list) > 0 && is_array($album_cat_store_list)) ? array_merge($album_store_list,  $album_cat_store_list ) : $album_cat_store_list;
        
        } else if($feedurl = 1) {
             // source if from elementor widget
            if (!$el_widget_id)
            return;

            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                //__A. WE ARE IN EDITOR SO USE CURRENT POST META SOURCE TO UPDATE THE WIDGET LIVE OTHERWISE IT WONT UPDATE WITH LIVE DATA
                $storeList =  get_post_meta( $album_id, 'alb_store_list', true);
                if($storeList == ''){
                    return;
                }   
            }else{
                //__B. WE ARE IN FRONT-END SO USE SAVED POST META SOURCE
                $elementorData = get_post_meta( $album_id, '_elementor_data', true);
                $elementorData = json_decode($elementorData, true);
                $id = $el_widget_id;
                $results=[];

                if($elementorData){
                   $this->findData( $elementorData, $id, $results );
                   $storeList = (!empty($results['settings']['storelist_repeater'])) ? $results['settings']['storelist_repeater'] : '';
                }else{
                    return;
                } 
            }
        }
        if(isset($album_store_list) && is_array($album_store_list) && count($album_store_list) > 0){

            $storeList = (is_array($storeList)) ? array_merge($storeList,$album_store_list ): $album_store_list;
        }
            if ( is_array($storeList) && $storeList ){
                $output = '
                <div class="buttons-block">
                    <div class="ctnButton-block">
                        <div class="available-now">';
                            $output .= ( $store_title_text == NULL ) ? esc_html__("Available now on:", 'sonaar-music') : esc_html__($store_title_text);
                            $output .=  '
                        </div>
                        <ul class="store-list">';
                        if ($feedurl){
                            foreach ($storeList as $store ) {
                                if(!isset($store['store_name'])){
                                    $store['store_name']="";
                                }
                                if(!isset($store['store_link'])){
                                    $store['store_link']="";
                                }

                                if(array_key_exists ( 'store_icon' , $store )){
                                    $icon = ( $store['store_icon']['value'] )? '<i class="' . esc_html($store['store_icon']['value']) . '"></i>': '';
                                }else{
                                    $icon ='';
                                }
                                $output .= '<li><a class="button" href="' . esc_url( $store['store_link'] ) . '" target="_blank">'. $icon . $store['store_name'] . '</a></li>';
                            }
                        }else{
                            foreach ($storeList as $key => $store ) {
                                if(!isset($store['store-name'])){
                                    $store['store-name']="";
                                }
                                if(!isset($store['store-link'])){
                                    $store['store-link']="";
                                }
                                if(!isset($store['store-target'])){
                                    $store['store-target']='_blank';
                                }else{
                                    $store['store-target']='_self';
                                }

                                if(array_key_exists ( 'store-icon' , $store )){
                                    $icon = ( $store['store-icon'] )? '<i class="' . esc_html($store['store-icon']) . '"></i>': '';
                                }else{
                                    $icon ='';
                                }
                                $classes = 'button';

                                $href = 'href="' . esc_url($store['store-link']) . '"';
                                if(isset($store['link-option']) && $store['link-option'] == 'popup'){ 
                                    $classes .= ' sr-store-popup';
                                    $store['store-target'] = '_self';
                                    $href = '';
                                }
                                $output .= '<li><a class="'. esc_attr($classes) .'" data-source-post-id="' . esc_attr($firstAlbum) .'" data-store-id="a-'. esc_attr($key) .'" '. $href .' target="' . esc_attr($store['store-target']) . '">'. $icon . $store['store-name'] . '</a></li>';
                            }
                        }

                        $output .= '
                        </ul>
                    </div>
                </div>';
                
                return $output;
            }        
    }

    /**
    * Back-end widget form.
    */
    
    public function form ( $shortcodeParams ){
            $shortcodeParams = wp_parse_args( (array) $shortcodeParams, self::$widget_defaults );
            
            $title = esc_attr( $shortcodeParams['title'] );
            $albums = $shortcodeParams['albums'];
            $show_playlist = ( isset($shortcodeParams['show_playlist']) )? (bool)$shortcodeParams['show_playlist']: false;
            $sticky_player = (bool)$shortcodeParams['sticky_player'];
            $hide_artwork = (bool)$shortcodeParams['hide_artwork'];
            $show_album_market = (bool)$shortcodeParams['show_album_market'];
            $show_track_market = (bool)$shortcodeParams['show_track_market'];
            //$remove_player = (bool)$shortcodeParams['remove_player']; // deprecated and replaced by hide_timeline
            $hide_timeline = (bool)$shortcodeParams['hide_timeline'];
            
            $all_albums = get_posts(array(
            'post_type' =>  $this->sr_playlist_cpt
            , 'posts_per_page' => -1
            , 'no_found_rows'  => true
            ));
            
            if ( !empty( $all_albums ) ) :?>

  <p>
    <label for="<?php echo esc_html($this->get_field_id('title')); ?>">
      <?php _ex('Title:', 'Widget', 'sonaar-music'); ?>
    </label>
    <input type="text" class="widefat" id="<?php echo esc_html($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" placeholder="<?php _e('Popular Songs', 'sonaar-music'); ?>" />
  </p>
  <p>
    <label for="<?php echo esc_html($this->get_field_id('albums')); ?>">
      <?php esc_html_e('Album:', 'Widget', 'sonaar-music'); ?>
    </label>
    <select class="widefat" id="<?php echo esc_attr($this->get_field_id('albums')); ?>" name="<?php echo esc_attr($this->get_field_name('albums')); ?>[]" multiple="multiple">
      <?php foreach($all_albums as $a): ?>

        <option value="<?php echo esc_attr($a->ID); ?>" <?php echo ( is_array($albums) && in_array($a->ID, $albums) ? ' selected="selected"' : ''); ?>>
          <?php echo esc_attr($a->post_title); ?>
        </option>

        <?php endforeach; ?>
    </select>
  </p>
<?php if ( function_exists( 'run_sonaar_music_pro' ) ): ?>
  <p>
    <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('sticky_player')); ?>" name="<?php echo esc_attr($this->get_field_name('sticky_player')); ?>" <?php checked( esc_attr($sticky_player) ); ?> />
    <label for="<?php echo esc_attr($this->get_field_id('sticky_player')); ?>">
      <?php esc_html_e( 'Enable Sticky Audio Player', 'sonaar-music'); ?>
    </label>
    <br />
  </p>
<?php endif ?>
  <p>
    <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('show_playlist')); ?>" name="<?php echo esc_attr($this->get_field_name('show_playlist')); ?>" <?php checked( esc_attr($show_playlist) ); ?> />
    <label for="<?php echo esc_attr($this->get_field_id('show_playlist')); ?>">
      <?php esc_html_e( 'Show Playlist', 'sonaar-music'); ?>
    </label>
    <br />
  </p>

  <p>
    <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('show_album_market')); ?>" name="<?php echo esc_attr($this->get_field_name('show_album_market')); ?>" <?php checked( esc_attr($show_album_market) ); ?> />
    <label for="<?php echo esc_attr($this->get_field_id('show_album_market')); ?>">
      <?php esc_html_e( 'Show Album store', 'sonaar-music'); ?>
    </label>
    <br />
  </p>
  <p>
    <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('hide_artwork')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_artwork')); ?>" <?php checked( esc_attr($hide_artwork )); ?> />
    <label for="<?php echo esc_attr($this->get_field_id('hide_artwork')); ?>">
      <?php esc_html_e( 'Hide Album Cover', 'sonaar-music'); ?>
    </label>
    <br />
  </p>
  <p>
    <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('show_track_market')); ?>" name="<?php echo esc_attr($this->get_field_name('show_track_market')); ?>" <?php checked( esc_attr($show_track_market )); ?> />
    <label for="<?php echo esc_attr($this->get_field_id('show_track_market')); ?>">
      <?php esc_html_e( 'Show Track store', 'sonaar-music'); ?>
    </label>
    <br />
  </p>
  </p>
  <p>
    <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('hide_timeline')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_timeline')); ?>" <?php checked( esc_attr($hide_timeline )); ?> />
    <label for="<?php echo esc_attr($this->get_field_id('hide_timeline')); ?>">
      <?php esc_html_e( 'Remove Visual Timeline', 'sonaar-music'); ?>
    </label>
    <br />
  </p>

  <?php
            else:
                
            echo wp_kses_post( '<p>'. sprintf( _x('No albums have been created yet. <a href="%s">Create some</a>.', 'Widget', 'sonaar-music'), esc_url(admin_url('edit.php?post_type=' . $this->sr_playlist_cpt)) ) .'</p>' );
            
            endif;
    }
    
    
    
    
    
    
    /**
    * Sanitize widget form values as they are saved.
    */
    
    public function update ( $new_instance, $old_instance )
    {
        $this->shortcodeParams = wp_parse_args( $old_instance, self::$widget_defaults );
            
            $this->shortcodeParams['title'] = strip_tags( stripslashes($new_instance['title']) );
            $this->shortcodeParams['albums'] = $new_instance['albums'];
            $this->shortcodeParams['show_playlist']  = (bool)$new_instance['show_playlist'];
            $this->shortcodeParams['hide_artwork']  = (bool)$new_instance['hide_artwork'];
            $this->shortcodeParams['sticky_player']  = (bool)$new_instance['sticky_player'];
            $this->shortcodeParams['show_album_market']  = (bool)$new_instance['show_album_market'];
            $this->shortcodeParams['show_track_market']  = (bool)$new_instance['show_track_market'];
            //$this->shortcodeParams['remove_player']  = (bool)$new_instance['remove_player']; deprecated and replaced by hide_timeline
            $this->shortcodeParams['hide_timeline']  = (bool)$new_instance['hide_timeline'];
            
            return $this->shortcodeParams;
    }
    
    
    private function print_playlist_json() {
        $jsonData = array();

        if ( ! empty($_GET["albums"]) ){
            $re = '/^\d+(?:,\d+)*$/';
            if ( preg_match($re, $_GET["albums"]) )
                $albums = sanitize_text_field($_GET["albums"]);
            else
                $albums = array();
        }else{
            $albums = array();
        }
       
        if(!empty($_GET["el_widget_id"]) && ctype_alnum($_GET["el_widget_id"])){
            $el_widget_id = sanitize_text_field($_GET["el_widget_id"]);
        }else{
            $el_widget_id = null;
        }

        $single_playlist = !empty($_GET["single_playlist"]) ? rest_sanitize_boolean($_GET["single_playlist"]) : false;
        $title = !empty($_GET["title"]) ? sanitize_text_field($_GET["title"]) : null;
        $feed_title = !empty($_GET["feed_title"]) ? sanitize_text_field($_GET["feed_title"]) : null;
        if ($feed_title !== null) {
            $feed_title = str_replace("apos;", "'", $feed_title); //replace ' with apos; to avoid conflict with json
        }
        $feed = !empty($_GET["feed"]) ? sanitize_text_field($_GET["feed"]) : null; 
        if ($feed !== null) {
            $feed = str_replace('amp;', '&', $feed); //replace & with amp; to avoid conflict with json
        }
        $feed_img = !empty($_GET["feed_img"]) ? sanitize_url($_GET["feed_img"]) : null;
        $artwork =  !empty($_GET["artwork"]) ? sanitize_url($_GET["artwork"]) : null;
        $posts_per_pages = !empty($_GET["posts_per_pages"]) ? intval($_GET["posts_per_pages"]) : null;
        $category =  !empty($_GET["category"]) ? sanitize_text_field($_GET["category"]) : null;
        $posts_not_in =  !empty($_GET["posts_not_in"]) ? sanitize_text_field($_GET["posts_not_in"]) : null;
        $category_not_in =  !empty($_GET["category_not_in"]) ? sanitize_text_field($_GET["category_not_in"]) : null;
        $author =  !empty($_GET["author"]) ? sanitize_text_field($_GET["author"]) : null;
        $all_category = !empty($_GET["all_category"]) ? true : null;
        $reverse_tracklist = !empty($_GET["reverse_tracklist"]) ? true : false;
        $audio_meta_field = !empty($_GET["audio_meta_field"]) ? $_GET["audio_meta_field"] : null;
        $repeater_meta_field = !empty($_GET["repeater_meta_field"]) ? $_GET["repeater_meta_field"] : null;
        $track_desc_postcontent = (isset($track_desc_postcontent)) ? $track_desc_postcontent : null;
        $import_file = !empty($_GET["import_file"]) ? sanitize_url($_GET["import_file"]) : null;
        $rss_items = !empty($_GET["rss_items"]) ?  intval($_GET["rss_items"]) : null;
        $rss_item_title = !empty($_GET["rss_item_title"]) ? sanitize_text_field($_GET["rss_item_title"]) : null;
        $isPlayer_Favorite = !empty($_GET["is_favorite"]) ? sanitize_text_field($_GET["is_favorite"]) : null;
        $isPlayer_recentlyPlayed = !empty($_GET["is_recentlyplayed"]) ? sanitize_text_field($_GET["is_recentlyplayed"]) : null;
        $this->shortcodeParams = null;
        $playlist = $this->get_playlist($albums, $category, $posts_not_in, $category_not_in, $author, $title, $feed_title, $feed, $feed_img, $el_widget_id, $artwork, $posts_per_pages, $all_category, $single_playlist, $reverse_tracklist, $audio_meta_field, $repeater_meta_field, 'sticky', $track_desc_postcontent, $import_file, $rss_items, $rss_item_title, $isPlayer_Favorite, $isPlayer_recentlyPlayed);
        if(!is_array($playlist) || empty($playlist['tracks']))
        wp_send_json('');
       
        wp_send_json($playlist);
        
    }
    private function findData($arr, $id, &$results = []){
        foreach ($arr as $data) {           
            if ( is_array($data) ){
                if (array_key_exists('id', $data)) {
                    if($data['id'] == $id){
                        $results = $data;
                    }
                }
                $this->findData( $data, $id, $results);     
            }
        }
        return false ;
    }
    private function get_wc_price($product_id){
        // Show the price in the player tracklist. 
        if ( !defined( 'WC_VERSION' ) ){
            return;
        }
        $product = wc_get_product($product_id);
        if (!is_a($product, 'WC_Product')) {
            return;
        }
        $product_price = $product->get_price();

        return strip_tags(wc_price($product_price));
    }
    private function is_variable_product($id){
        if ( !function_exists('wc_get_product') ){
            return false;
        }

        $product = wc_get_product($id);
        if ($product->is_type('variable')) {
            return true;
        } else {
            return false;
        }
    }
    /*private function loadFavoriteList_Cookies(){
        // Check if user is logged in
        if(is_user_logged_in()) {
            // Get the current user
            $user = wp_get_current_user();
            // Fetch favorite playlists from user meta data
            $user_playlists = get_user_meta($user->ID, 'sonaar_mp3_playlists', true);
            
            if (is_string($user_playlists)) {
                $user_playlists = json_decode($user_playlists, true); // Setting the second parameter as true returns an associative array
            }
            
        }else if(isset($_COOKIE['sonaar_mp3_playlists'])) {
            $user_playlists = json_decode(stripslashes(urldecode($_COOKIE['sonaar_mp3_playlists'])), true);
        }
        if(isset($user_playlists)){
            if(is_array($user_playlists)) {
                $favoriteTracks = [];
                foreach ($user_playlists as $user_playlist) {
                    if($user_playlist['playlistName'] === 'Favorites') {
                        if(array_key_exists('tracks', $user_playlist)){
                            $favoriteTracks = $user_playlist['tracks'];
                        }
                        break;
                    }
                    $favoriteTracks = false;
                    
                }
                return $favoriteTracks;
            }
        }
    }*/
   /* private function loadMostRecent_Cookies(){
        // Check if user is logged in
        if(is_user_logged_in()) {
            // Get the current user
            $user = wp_get_current_user();
            // Fetch favorite playlists from user meta data
            $user_playlists = get_user_meta($user->ID, 'sonaar_mp3_playlists', true);
            
            if (is_string($user_playlists)) {
                $user_playlists = json_decode($user_playlists, true); // Setting the second parameter as true returns an associative array
            }
            
        }else if(isset($_COOKIE['sonaar_mp3_playlists'])) {
            $user_playlists = json_decode(stripslashes(urldecode($_COOKIE['sonaar_mp3_playlists'])), true);
        }
        if(isset($user_playlists)){
            if(is_array($user_playlists)) {
                $mostRecentTracks = [];
                foreach ($user_playlists as $user_playlist) {
                    if($user_playlist['playlistName'] === 'RecentlyPlayed') {
                        if(array_key_exists('tracks', $user_playlist)){
                            $mostRecentTracks = $user_playlist['tracks'];
                        }
                        break;
                    }
                    $mostRecentTracks = false;
                    
                }
                return $mostRecentTracks;
            }
        }
    }*/
    private function loadUserPlaylists_fromCookies($playlistName) {
        //$playlistName should be Favorites or RecentlyPlayed

        // Check if user is logged in
        if (is_user_logged_in()) {
            // Get the current user
            $user = wp_get_current_user();
            // Fetch favorite playlists from user meta data
            $user_playlists = get_user_meta($user->ID, 'sonaar_mp3_playlists', true);
    
            if (is_string($user_playlists)) {
                $user_playlists = json_decode($user_playlists, true); // Decode JSON as associative array
            }
        } elseif (isset($_COOKIE['sonaar_mp3_playlists'])) {
            $user_playlists = json_decode(stripslashes(urldecode($_COOKIE['sonaar_mp3_playlists'])), true);
        }
        if (isset($user_playlists) && is_array($user_playlists)) {
            $tracks = false;  // Default to false if no playlist matches
            foreach ($user_playlists as $user_playlist) {
                if ($user_playlist['playlistName'] === $playlistName) {
                    if (array_key_exists('tracks', $user_playlist)) {
                        $tracks = $user_playlist['tracks'];
                    }
                    break;  // Found the playlist, no need to check further
                }
            }
            return $tracks;
        }
        return false;  // Return false if no playlists are found or condition doesn't match
    }

    /**
     * Checks if a track is marked as a favorite in a given list.
     *
     * @param array $track The track to check.
     * @param int $postid The post ID associated with the track.
     * @param array $list The list of favorite tracks.
     * @return bool Returns true if the track is marked as a favorite, false otherwise.
     */
  
    private function isTrack_PartOfUserPlaylist($track_pos, $postid, $list){
        if(!$list) return false;
      
        foreach($list as $key => $value) {
            if($postid == $value['postId']){
                if($value['trackPos'] == $track_pos){
                    $trackIsFavorite = true;
                    break;
                }
            }
            $trackIsFavorite = false;
        } 
        return $trackIsFavorite;
    }

    private function force_pushOptionalCTA($audioSrc, $postobj = null, $isPlayer_Favorite = false, $cta_download_settings = null, $cta_link_settings = null, $cta_share_settings = null, $cta_favorite_settings = null){

        $optional_storelist_cta = [];
        if(isset($postobj)){
            if($cta_link_settings == "true" || (isset( $this->shortcodeParams['force_cta_singlepost']) && $this->shortcodeParams['force_cta_singlepost'] == 'true')){
                $optional_storelist_cta = array_merge( $optional_storelist_cta, $this->push_postLink_storelist_cta( $postobj->ID ) );
            }
        }
       
        if( $cta_download_settings == "true" || (isset( $this->shortcodeParams['force_cta_dl']) && $this->shortcodeParams['force_cta_dl'] == 'true')){
            $optional_storelist_cta =  array_merge( $optional_storelist_cta, $this->push_download_storelist_cta( $audioSrc ));
        }
        if(isset($postobj)){
            if($cta_share_settings == "true"  || (isset( $this->shortcodeParams['force_cta_share']) && $this->shortcodeParams['force_cta_share'] == 'true')){
                $optional_storelist_cta = array_merge( $optional_storelist_cta, $this->push_shareModal_storelist_cta( $postobj->ID ) );
            }
        }
        if(isset($postobj)){
            if($cta_favorite_settings == "true" || (isset( $this->shortcodeParams['force_cta_favorite']) && $this->shortcodeParams['force_cta_favorite'] == 'true')){
                $optional_storelist_cta = array_merge( $optional_storelist_cta, $this->push_favorite_storelist_cta( $postobj->ID,  $isPlayer_Favorite ) );
            }
        }
        
       

            return $optional_storelist_cta;
    }

    private function push_caticons_in_storelist($post, $terms = null){
        $terms = (is_array($terms)) ? $terms[0] : $terms;
        $store_list =  array();
        $post_id = $post->ID;

        $default_args = array(
            'post_type'           => $this->sr_playlist_cpt,
            'post_status'         => 'publish',
            'orderby'             => 'date',
            'posts_per_page'      => -1,
            'ignore_sticky_posts' => true,
        );   
        
        $default_args['tax_query'] = array(
                array(
                    'taxonomy' => 'podcast-show',
                    'field'    => 'term_id',
                    'terms'    => $terms
                )
        );
        
        $query_args = apply_filters( 'sonaar_podcast_feed_query_args', $default_args );
        $qry = new WP_Query( $query_args );
        $options = Sonaar_Music_Admin::getPodcastPlatforms();
        $stores = get_term_meta($terms, 'podcast_rss_url', true);
        
        if (isset($stores) && is_array($stores)){
        
            foreach ( $stores as $store ) {
                if ( isset($store['srpodcast_url'] )){
                    if ( array_key_exists('srpodcast_name', $store) && $store['srpodcast_name'] !== '' ){
                        $store['name'] = $store['srpodcast_name'];
                    }else if( isset($options[$store['srpodcast_url_icon']] )){
                        $store['name'] = $options[$store['srpodcast_url_icon']];
                    }else{
                        $store['name'] = '';
                    }

                    array_push($store_list, [
                        'store-icon'    => $store['srpodcast_url_icon'],
                        'store-link'    => $store['srpodcast_url'],
                        'store-name'    => $store['name'],
                        'store-target'  => '_blank',
                        'show-label'    => true
                    ]);
                }
            }
        }    
        return $store_list;

    }
    private function push_woocart_in_storelist($post, $is_variable_product = null, $wc_add_to_cart = false, $wc_buynow_bt = false){
        if (  !defined( 'WC_VERSION' ) || ( defined( 'WC_VERSION' ) && !function_exists( 'run_sonaar_music_pro' ) && get_site_option('SRMP3_ecommerce') != '1' ) ){
            return false;
		}

        $wc_bt_type = Sonaar_Music::get_option('wc_bt_type', 'srmp3_settings_woocommerce');
        $store_list =  array();
        
        if ( $wc_bt_type == 'wc_bt_type_inactive' ){
            return $store_list;
        }
        if(!isset($post->ID)){
            $post = get_post($post);
        }
        
        $post_id = $post->ID;
        $slug = $post->post_name;
    
        $homeurl = esc_url( home_url() );
        $product_permalink = get_option('woocommerce_permalinks')['product_base'];
        $product_slug = $slug;
        $checkout_url = ( defined( 'WC_VERSION' ) ) ? wc_get_checkout_url() : '';
        $product_price = ( $wc_bt_type !='wc_bt_type_label' ) ? html_entity_decode($this->get_wc_price($post_id)) : '';
       
        
        if( $wc_add_to_cart == 'true' ){    
            // Set label based on whether "Make an Offer" is enabled
          
            $url_if_variation = get_permalink( $post_id ); //no add to cart since its a variation and user must choose variation from the single page
            $url_if_no_variation = get_permalink(get_the_ID()) . '?add-to-cart=' . $post_id;
            $storeicon = ( Sonaar_Music::get_option('wc_bt_show_icon', 'srmp3_settings_woocommerce') =='true' ) ? 'fas fa-cart-plus' : '';
            $pageUrl = ($is_variable_product == 1) ? $url_if_variation : $url_if_no_variation ;

            $make_offer_enabled = false;
            $make_offer_enabled_hide_price = false;

            if (method_exists('SRMP3_WooCommerce', 'is_make_offer_enabled')) {
                $make_offer_enabled = SRMP3_WooCommerce::is_make_offer_enabled($post_id);
                if($make_offer_enabled === "yes"){
                    if (get_post_meta($post_id, '_make_offer_hide_price', true) === 'yes') {
                        $make_offer_enabled_hide_price = true;
                    }
                }
            }


            
            if ($make_offer_enabled_hide_price) {
                $label = (Sonaar_Music::get_option('makeanoffer_button_label', 'srmp3_settings_woocommerce')) ? Sonaar_Music::get_option('makeanoffer_button_label', 'srmp3_settings_woocommerce') : esc_html__('Make an Offer', 'sonaar-music');
                $product_price = '';
                $pageUrl = '#srpmakeoffer';
            } else {
                $label = (Sonaar_Music::get_option('wc_add_to_cart_text', 'srmp3_settings_woocommerce') && Sonaar_Music::get_option('wc_add_to_cart_text', 'srmp3_settings_woocommerce') != '' && Sonaar_Music::get_option('wc_add_to_cart_text', 'srmp3_settings_woocommerce') != 'Add to Cart') ? Sonaar_Music::get_option('wc_add_to_cart_text', 'srmp3_settings_woocommerce') : esc_html__('Add to Cart', 'sonaar-music');
                $label = ($wc_bt_type == 'wc_bt_type_price') ? '' : $label . ' ';
            }

            $storeListArgs = [
                'store-icon'    => $storeicon,
                'store-link'    => $pageUrl,
                'store-name'    => $label . $product_price,
                'store-target'  => '_self',
                'show-label'    => true,
                'has-variation' => $is_variable_product == 1,
                'product-id'    =>$post_id
            ];
            if ($make_offer_enabled_hide_price) {
                $storeListArgs['make-offer-bt'] = true;
            }

            array_push($store_list, $storeListArgs);
        }
       
        if( $wc_buynow_bt == 'true' ){
            $label = (Sonaar_Music::get_option('wc_buynow_text', 'srmp3_settings_woocommerce') && Sonaar_Music::get_option('wc_buynow_text', 'srmp3_settings_woocommerce') != '' && Sonaar_Music::get_option('wc_buynow_text', 'srmp3_settings_woocommerce') != 'Buy Now' ) ? Sonaar_Music::get_option('wc_buynow_text', 'srmp3_settings_woocommerce') : esc_html__('Buy Now', 'sonaar-music');
            $label = ($wc_bt_type == 'wc_bt_type_price') ? '' : $label . ' '; 
            $url_if_variation = $homeurl . $product_permalink . '/' . $product_slug; //no add to cart since its a variation and user must choose variation from the single page;
            $url_if_no_variation = $checkout_url . '?add-to-cart=' . $post_id;
            $storeicon = ( Sonaar_Music::get_option('wc_bt_show_icon', 'srmp3_settings_woocommerce') == 'true' ) ? 'fas fa-shopping-cart' : '';
            $pageUrl = ($is_variable_product == 1) ? $url_if_variation : $url_if_no_variation ;

            array_push($store_list, [
                'store-icon'    => $storeicon,
                'store-link'    => $pageUrl,
                'store-name'    =>  $label . $product_price,
                'store-target'  => '_self',
                'show-label'    => true
            ]);
        }
        return $store_list;
    }
    private function checkCTA_Condition($shortcode){
       // $cta_visibility_download = 'show|user_logged_in|subscriber,administrator|#popup';
        if($this->shortcodeParams == null){
            return true;
        }

        $state = null;
        $condition = null;
        $value = null;
    
        if (isset($this->shortcodeParams[$shortcode])) {
            $params = explode('|', $this->shortcodeParams[$shortcode]);
            $state = isset($params[0]) ? $params[0] : null;
            $condition = isset($params[1]) ? $params[1] : null;
            $value = isset($params[2]) ? $params[2] : null;
       
        }
        if(!$state || !$condition){
            return true;
        }

        // Retrieve user's logged in status
        $is_user_logged_in = is_user_logged_in();
        
        // Retrieve user's role
        $user_role = null;
        if ($is_user_logged_in) {
            $user = wp_get_current_user();
            $user_role = $user->roles ? $user->roles[0] : null; // Consider first role only
        }

        // Evaluate conditions based on $condition
        $display = false;

        switch ($condition) {
            case 'user_logged_out':
                $display = !$is_user_logged_in;
                break;
            case 'user_logged_in':
                $display = $is_user_logged_in;
                break;
            case 'user_role_is':
                $user_roles = explode(',', $value); // Split value into array
                $user_roles = array_map('trim', $user_roles); // Trim white space from role names
                $display = $is_user_logged_in && in_array($user_role, $user_roles);
                break;
            default:
                // Handle other cases or errors
                break;
        }

        // If visibility state is set to 'hide', reverse the condition
        if ($state === 'hide') {
            $display = !$display;
        }

        // If conditions are not met, return empty array
        if (!$display) {
            return false;
        }else{
            return true;
        }
    }
    private function checkCTA_Visibility($shortcode_name, $bt_type){
       
        $visibility_shortcode_set = $this->shortcodeParams[$shortcode_name] ?? null;
        $response['link'] = false;
        $display = true;
        $askforemail = false;

        if( (isset($visibility_shortcode_set))){
            $redirect_link = $this->shortcodeParams[$shortcode_name . '_redirect_url'] ?? null;

            //check if  $this->shortcodeParams[$shortcode_name] incliudes the string 'askemail'
            if (strpos($this->shortcodeParams[$shortcode_name], 'askemail') !== false) {
                    $askforemail = true;
            }

        }else{        
            /*
            // START for dynamic visibility SETTINGS
            */
            $state = null;
            $condition = null;
            $action_when_not_met = null;
            $value = null;

            switch ($bt_type) {     
                case 'download':
                    $isDynamicEnabled = (get_site_option('SRMP3_ecommerce') == '1' && get_site_option('sonaar_music_licence')&& Sonaar_Music::get_option('cta_dl_dv_enable_main_settings', 'srmp3_settings_download') === "true") ? true : false;
                    if (!$isDynamicEnabled) {
                        break;
                    }
                    $state = Sonaar_Music::get_option('cta_dl_dv_state_main_settings', 'srmp3_settings_download');
                    $condition = Sonaar_Music::get_option('cta_dl_dv_condition_main_settings', 'srmp3_settings_download');
                    $value = Sonaar_Music::get_option('cta_dl_dv_role_main_settings', 'srmp3_settings_download');
                    $action_when_not_met = (Sonaar_Music::get_option('cta_dl_dv_enable_redirect_main_settings', 'srmp3_settings_download') === "true" && Sonaar_Music::get_option('cta_dl_dv_condition_not_met_action', 'srmp3_settings_download') === "") ? "redirect" : Sonaar_Music::get_option('cta_dl_dv_condition_not_met_action', 'srmp3_settings_download');
                    $redirect_link = ($action_when_not_met === 'redirect' && Sonaar_Music::get_option('cta_dl_dv_redirection_url_main_settings', 'srmp3_settings_download') !== '') ? Sonaar_Music::get_option('cta_dl_dv_redirection_url_main_settings', 'srmp3_settings_download') : null;
                    $redirect_link = ($action_when_not_met === 'askemail' ) ? '#srp_ask_email' : $redirect_link;
                    break;
                
                case 'share':
                    if( Sonaar_Music::get_option('cta_share_dv_enable_main_settings', 'srmp3_settings_share') === 'true' ){
                        $state = Sonaar_Music::get_option('cta_share_dv_state_main_settings', 'srmp3_settings_share');
                        $condition = Sonaar_Music::get_option('cta_share_dv_condition_main_settings', 'srmp3_settings_share');
                        $value = Sonaar_Music::get_option('cta_share_dv_role_main_settings', 'srmp3_settings_share');
                    }
                    $redirect_link = (Sonaar_Music::get_option('cta_share_dv_enable_redirect_main_settings', 'srmp3_settings_share') === 'true' && Sonaar_Music::get_option('cta_share_dv_redirection_url_main_settings', 'srmp3_settings_share') !== '') ? Sonaar_Music::get_option('cta_share_dv_redirection_url_main_settings', 'srmp3_settings_share') : null; 
                    break;
                
                case 'favorites':
                    if( Sonaar_Music::get_option('cta_favorites_dv_enable_main_settings', 'srmp3_settings_favorites') === 'true' ){
                        $state = Sonaar_Music::get_option('cta_favorites_dv_state_main_settings', 'srmp3_settings_favorites');
                        $condition = Sonaar_Music::get_option('cta_favorites_dv_condition_main_settings', 'srmp3_settings_favorites');
                        $value = Sonaar_Music::get_option('cta_favorites_dv_role_main_settings', 'srmp3_settings_favorites');
                    }
                    $redirect_link = (Sonaar_Music::get_option('cta_favorites_dv_enable_redirect_main_settings', 'srmp3_settings_favorites') === 'true' && Sonaar_Music::get_option('cta_favorites_dv_redirection_url_main_settings', 'srmp3_settings_favorites') !== '') ? Sonaar_Music::get_option('cta_favorites_dv_redirection_url_main_settings', 'srmp3_settings_favorites') : null; 
                    break;
            }
           
            if (is_array($value)) {
                $value = implode(',', $value);  // convert the array to a string
            }
            $shortcode_name = 'main_settings';
            $this->shortcodeParams['main_settings'] = $state . '|' . $condition . '|' . $value;
        }

        $display = $this->checkCTA_Condition( $shortcode_name ); // Shortcode parameters for visibility are set, check if we should display the CTA.
        if (!$display) {
            // We might hide the button
            if (isset($redirect_link)) {
                // We will show the button but set a redirect link for people who do not meet the condition above.
                $response['link'] = $redirect_link;
                $response['display'] = true;
            } else {
                $response['link'] = false;
                $response['display'] = false;

                if ($askforemail) {
                    $redirect_link = '#srp_ask_email';
                    $response['link'] = $redirect_link;
                    $response['display'] = true;
                }
                // We hide the button
            }
        }else{
            $response['display'] = true;
        }
        $response['link'] = ($response['link'] || !$response['link'] && !is_user_logged_in() && isset($redirect_link)) ? $redirect_link : 'original_link';

        return $response;
       
    }

    private function push_download_storelist_cta($fileUrl){
        if (  !function_exists( 'run_sonaar_music_pro' )){
            return [];
		}

        $response = $this->cta_download_visibility;
        if ($response['link'] == 'original_link'){
            $response['link'] = $fileUrl;
        }
        //$response = $this->checkCTA_Visibility('cta_visibility_download', 'download', $this->shortcodeParams, $fileUrl);

        if ($response['display'] == false){
            return [];
        }

        // Default class
        $ctaClass = 'sr_store_force_dl_bt';
        $storeName = (Sonaar_Music::get_option('force_cta_download_label', 'srmp3_settings_download') && Sonaar_Music::get_option('force_cta_download_label', 'srmp3_settings_download') != '') ? Sonaar_Music::get_option('force_cta_download_label', 'srmp3_settings_download') : __('Download', 'sonaar-music');
         // Add additional class if link is srp_ask_email
         if ($response['link'] === '#srp_ask_email') { 
            $ctaClass .= ' sr_store_ask_email'; 
            $storeName = (Sonaar_Music::get_option('download_settings_afe_button_label', 'srmp3_settings_download') && Sonaar_Music::get_option('download_settings_afe_button_label', 'srmp3_settings_download') != '') ? Sonaar_Music::get_option('download_settings_afe_button_label', 'srmp3_settings_download') : $storeName; 
        } 
        return [
            [
                'store-icon'    => 'fas fa-download',
                'store-target'  => '_self',
                'store-link'    => $response['link'],
                'store-name'    => $storeName,
                'cta-class'     => $ctaClass,
                'show-label'    => true,
                'download-attr' => ($this->cta_download_visibility['link'] === 'original_link')?true:false // dont set the download attribute if "condition NOT met" and force download CTA redirection is enabled  
                ]
        ];
    
    }
    private function push_postLink_storelist_cta($postId){ 
        if (  !function_exists( 'run_sonaar_music_pro' )){
            return [];
		}

        return [
            [
                'store-icon'    => 'sricon-info',
                'store-link'    => get_permalink($postId),
                'store-name'    => (Sonaar_Music::get_option('force_cta_singlepost_label', 'srmp3_settings_general') && Sonaar_Music::get_option('force_cta_singlepost_label', 'srmp3_settings_general') != '') ? Sonaar_Music::get_option('force_cta_singlepost_label', 'srmp3_settings_general') : __('View Details', 'sonaar-music'),
                'store-target'  => '_self',
                'cta-class'  => 'sr_store_force_pl_bt',
                'show-label'    => true
            ]
        ];
    
    }
    private function push_favorite_storelist_cta($postId, $isPlayer_Favorite = false){
        if (  !function_exists( 'run_sonaar_music_pro' )){
            return [];
		}

        $response = $this->cta_favorite_visibility;
        if ($response['link'] == 'original_link'){
            $response['link'] = '#';
        }
        //response = $this->checkCTA_Visibility('cta_visibility_favorites', 'favorites', $this->shortcodeParams, '#');
        
        if ($response['display'] == false){
            return [];
        }

        if(!$isPlayer_Favorite){
            return [
                [
                    'store-icon'    => Sonaar_Music::get_option('srp_fav_add_icon', 'srmp3_settings_favorites'),
                    'store-link'    => $response['link'],
                    'store-name'    => Sonaar_Music::get_option('fav_label_add_action', 'srmp3_settings_favorites'),
                    'store-target'  => '_self',
                    'cta-class'     => 'srp-fav-bt',
                    'show-label'    => false
                ]
            ];
        }else{
            return [
                [
                    'store-icon'    => Sonaar_Music::get_option('srp_fav_remove_icon', 'srmp3_settings_favorites'),
                    'store-link'    => $response['link'],
                    'store-name'    => Sonaar_Music::get_option('fav_label_remove_action', 'srmp3_settings_favorites'),
                    'store-target'  => '_self',
                    'cta-class'     => 'srp-fav-bt',
                    'show-label'    => false
                ]
            ];
        }
    
    }
    private function push_shareModal_storelist_cta($postId){ 
        
        if (  !function_exists( 'run_sonaar_music_pro' )){
            return [];
		}

        $response = $this->cta_share_visibility;
        if ($response['link'] == 'original_link'){
            $response['link'] = get_permalink($postId);
        }
        //$response = $this->checkCTA_Visibility('cta_visibility_share', 'share', $this->shortcodeParams, get_permalink($postId));

        if ($response['display'] == false){
            return [];
        }
        
        
        // Set the link
        //$link = ($link || !$link && !is_user_logged_in() && isset($redirect_link)) ? $redirect_link : get_permalink($postId);*/
        //$preventShare = ($preventShare || !$preventShare && !is_user_logged_in() && isset($redirect_link)) ? true : false;
        
        $shareClass = ($response['link'] !== get_permalink($postId)) ? 'sr_store_force_share_bt_disabled' : 'sr_store_force_share_bt'; // remove class name to prevent the share link popup
       
        /*
        // END for dynamic visibility
        */

        $share_label = Sonaar_Music::get_option('force_cta_share_label', 'srmp3_settings_share');
        $show_share_label = (Sonaar_Music::get_option('cta_share_view_label', 'srmp3_settings_share') == "true") ? true : false;
        return [
            [
                'store-icon'    => 'sricon-share',
                'store-link'    => $response['link'],
                'store-name' => ($share_label && $share_label != '') ? $share_label : __('Share', 'sonaar-music'),
                'store-target'  => '_self',
                'cta-class'     => $shareClass,
                'show-label'    => $show_share_label
            ]
        ];
    
    }
    private function ifProductHasVariation($post_id){ 
        if(get_post_type( $post_id ) == 'product'){
            $product = wc_get_product($post_id);
            if($product->is_type( 'variable' )){
                $variations = $product->get_available_variations();
                $variations_id = wp_list_pluck( $variations, 'variation_id' ); 
                if( count($variations_id) > 0){
                    return true;
                }
            }
        }
        return false;
    }
    private function checkACF($field, $ids, $loop = true){
        if ($field !== null && substr( $field, 0, 3 ) === "acf") { 
            if (!function_exists('get_field')) return $field;
            if (empty($ids[0])){
                // make sure to get current post id if no album id has been specified so we can run the checkACF function.
                $ids[0] = get_post(get_the_ID());
            }
            $strings = '';
            foreach ( $ids as $a ) {
                if (!$loop){
                    $strings .= get_field( $field,  $a->ID );
                    return $strings;
                }
                $separator = ($a != end($ids)) ? " || " : '';
                $strings .= get_field( $field,  $a->ID ) . $separator;
            }
            return $strings;
        }
        return $field;
    }
    private function urlExists($url) {
        $headers = @get_headers($url);
        if (!$headers) {
            return false;
        }
        foreach ($headers as $header) {
            if (strpos($header, '200 OK') !== false) {
                return true;
            }
        }
        return false;
    }
    private function get_playlist($album_ids = array(), $category = null, $posts_not_in = null, $category_not_in = null, $author = null, $title = null, $feed_title = null, $feed = null, $feed_img = null, $el_widget_id = null, $artwork = null, $posts_per_pages = null, $all_category = null, $single_playlist = false, $reverse_tracklist = false, $audio_meta_field = null, $repeater_meta_field = null, $player = 'widget', $track_desc_postcontent  = null, $import_file = null, $rss_items = -1, $rss_item_title = null, $isPlayer_Favorite = null, $isPlayer_recentlyPlayed = null) {
        // Capture the start time
        // $start_time = microtime(true);
        global $post;
        $playlist = array();
        $tracks = array();
        $albums = '';
        $favoriteList = false;
        $userHistoryList = false;
        $feed_desc = false;
        $feed_id = false;
        // Collect all the parameters into an associative array for easier handling with third party
        $params = compact('album_ids', 'category', 'posts_not_in', 'category_not_in', 'author', 'title', 'feed_title', 'feed', 'feed_img', 'feed_desc', 'feed_id', 'el_widget_id', 'artwork', 'posts_per_pages', 'all_category', 'single_playlist', 'reverse_tracklist', 'audio_meta_field', 'repeater_meta_field', 'player', 'track_desc_postcontent', 'import_file', 'rss_items', 'rss_item_title', 'isPlayer_Favorite', 'isPlayer_recentlyPlayed');
        do_action_ref_array('srmp3_pre_get_playlist', array(&$params));
        extract($params);

        if(function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1'){
            $favoriteList = $this->loadUserPlaylists_fromCookies('Favorites');
            $userHistoryList = $this->loadUserPlaylists_fromCookies('RecentlyPlayed');
        }

        $cta_download_settings = Sonaar_Music::get_option('force_cta_download', 'srmp3_settings_download');
        $cta_link_settings = Sonaar_Music::get_option('force_cta_singlepost', 'srmp3_settings_general');
        $cta_share_settings = Sonaar_Music::get_option('force_cta_share', 'srmp3_settings_share');
        $cta_favorite_settings = Sonaar_Music::get_option('force_cta_favorite', 'srmp3_settings_favorites');
        
        $this->cta_download_visibility = $this->checkCTA_Visibility('cta_visibility_download', 'download');
        $this->cta_share_visibility = $this->checkCTA_Visibility('cta_visibility_share', 'share');
        $this->cta_favorite_visibility = $this->checkCTA_Visibility('cta_visibility_favorites', 'favorites');
        
        // Fetching data outside the loop
        $isPreviewEnabled = (function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1' && Sonaar_Music::get_option('force_audio_preview', 'srmp3_settings_audiopreview') === 'true') ? true : false;
        
        $upload_dir = wp_get_upload_dir();
        $site_url = site_url();
        $peaks_dir = Sonaar_Music::get_peak_dir();
        
        if($isPreviewEnabled){
            $isUserLoggedIn = is_user_logged_in();
            $allowed_roles = [];
            $user_roles = [];

            if ($isUserLoggedIn) {
                $user = wp_get_current_user();
                $user_roles = $user->roles;
                $allowed_roles = Sonaar_Music::get_option('audiopreview_access_roles', 'srmp3_settings_audiopreview');
                $allowed_roles = is_array($allowed_roles) ? $allowed_roles : [];
            }
        }
      

        if(!is_array($album_ids)) {
            $album_ids = explode(",", $album_ids);
        }
        if(!$category){
            // Category is not set
            $ordering['order'] = (isset($this->shortcodeParams['order']) && !empty($this->shortcodeParams['order'])) ? $this->shortcodeParams['order'] :  'DESC';   // default order
            $ordering['orderby'] = (isset($this->shortcodeParams['orderby']) && !empty($this->shortcodeParams['orderby'])) ? $this->shortcodeParams['orderby'] : 'date';  // use the entire $order_raw as orderby
           
            if($player == 'sticky'){
              $ordering = $this->getQueryOrder($player);
            }

            if( function_exists( 'run_sonaar_music_pro' ) && Sonaar_Music::get_option('sticky_show_related-post', 'srmp3_settings_sticky_player') == 'true' && !$all_category && $single_playlist){
            // if pro and RELATED TRUE and all categories FALSE and is_single()... 
                $args =  array(
                    'post_status'=> 'publish',
                    'order' => 'DESC',
                    'orderby' => 'date',
                    'post_type'=> Sonaar_Music_Admin::get_cpt($all = true), 
                    'posts_per_page' => $posts_per_pages,
                    'lang' => ''
                ); 
                $get_podcastshow_terms = [];
                $get_playlistcat_terms = [];
                $get_product_terms = [];
            
                foreach ($album_ids as $value) {
                    if( is_array( get_the_terms( $value, 'playlist-category' ) ) && get_the_terms( $value, 'playlist-category') ){
                        if (!in_array(get_the_terms( $value, 'playlist-category')[0]->term_id, $get_playlistcat_terms)){
                            array_push( $get_playlistcat_terms, get_the_terms( $value, 'playlist-category')[0]->term_id);
                        }
                        
                    }

                    if( is_array( get_the_terms( $value, 'podcast-show' ) ) && get_the_terms( $value, 'podcast-show') ){
                        if (!in_array(get_the_terms( $value, 'podcast-show')[0]->term_id, $get_podcastshow_terms)){
                            array_push( $get_podcastshow_terms, get_the_terms( $value, 'podcast-show')[0]->term_id);
                        }
                    }

                    if( is_array( get_the_terms( $value, 'product_cat' ) ) && get_the_terms( $value, 'product_cat') ){
                        if (!in_array(get_the_terms( $value, 'product_cat')[0]->term_id, $get_product_terms)){
                            array_push( $get_product_terms, get_the_terms( $value, 'product_cat')[0]->term_id);
                        }                
                    }
                }
                if($get_podcastshow_terms || $get_playlistcat_terms || $get_product_terms){
                    $args['tax_query']= array();
                    if( ($get_podcastshow_terms && $get_playlistcat_terms) || ($get_podcastshow_terms && $get_product_terms) || ($get_playlistcat_terms && $get_product_terms) ){
                        $args['tax_query'] = array('relation' => 'OR');
                    }
                    if($get_podcastshow_terms){
                        array_push($args['tax_query'] , array(
                            array(
                            'taxonomy' => 'podcast-show',
                            'field'    => 'id',
                            'terms'    =>  $get_podcastshow_terms
                            ),
                        ));
                    }
                    if($get_playlistcat_terms){
                        array_push($args['tax_query'], array(
                            array(
                            'taxonomy' => 'playlist-category',
                            'field'    => 'id',
                            'terms'    =>  $get_playlistcat_terms
                            ),
                        ));
                    }
                    if($get_product_terms){
                        array_push($args['tax_query'], array(
                            array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'id',
                            'terms'    =>  $get_product_terms
                            ),
                        ));
                    }
                }else{
                    $args['post__in'] = $album_ids;
                }
            }else {
                // retrieve albums IDs when post related is false
                $sr_postypes = Sonaar_Music_Admin::get_cpt($all = true);

                if($isPlayer_Favorite || $isPlayer_recentlyPlayed){
                    $ordering['orderby'] = 'post__in'; // for order by post queried
                }

                $args = array(
                    'posts_per_page' => $posts_per_pages,
                    'post_type' => $sr_postypes,
                    'post__in' => $album_ids,
                    'lang' => '',
                    'order' => $ordering['order'],
                    'orderby' => $ordering['orderby']
                );
                if ( isset($audio_meta_field) && $audio_meta_field != ''){ // This allow to retrieve all post types (posts, page, products, etc) even if they are not set in the srmp3_posttypes in the plugin settings.
                    if (!is_array($args['post_type'])) {
                        $args['post_type'] = array($args['post_type']);
                    }
                    if (!in_array('post', $args['post_type'])) {
                        $args['post_type'][] = 'post';
                    }
                    if (!in_array('page', $args['post_type'])) {
                        $args['post_type'][] = 'page';
                    } 
                    if ( function_exists( 'WC' )) {
                        if (!in_array('product', $args['post_type'])) {
                            $args['post_type'][] = 'product';
                        }
                    }

                }
            }
            if ( isset($audio_meta_field) && $audio_meta_field != '' && count($album_ids) == 1){ // If the source is the currentPost add the current post type to the query
                $postTypeFromTheCurrentPost = get_post_type(intval($album_ids[0])); 
                if($postTypeFromTheCurrentPost !== false && !in_array($postTypeFromTheCurrentPost, $args['post_type']) ){
                    $args['post_type'][] = $postTypeFromTheCurrentPost;
                }
            }   
            $albums = get_posts($args);
        }else{
            // retrieve albums from category
            $returned_data = $this->getAlbumsFromTerms($category, $posts_not_in, $category_not_in, $author, $posts_per_pages, true, $player, $reverse_tracklist); 
            $albums = $returned_data['albums'];// true means get post objects. false means get Ids only
    
        }

        if(Sonaar_Music::get_option('show_artist_name', 'srmp3_settings_general') ){
            $artistSeparator = (Sonaar_Music::get_option('artist_separator', 'srmp3_settings_general') && Sonaar_Music::get_option('artist_separator', 'srmp3_settings_general') != '' && Sonaar_Music::get_option('artist_separator', 'srmp3_settings_general') != 'by' )?Sonaar_Music::get_option('artist_separator', 'srmp3_settings_general'): esc_html__('by', 'sonaar-music');
            $artistSeparator = ' ' . $artistSeparator . ' ';
        }else{
            $artistSeparator = '';
        }
       
        if( $feed == '1' ){
            //001. FEED = 1 MEANS ITS A FEED BUILT WITH ELEMENTOR AND USE TRACKS UPLOAD. IF A PREDEFINED PLAYLIST IS SET, GO TO 003. FEED = 1 VALUE IS SET IN THE SR-MUSIC-PLAYER.PHP
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                //__A. WE ARE IN EDITOR SO USE CURRENT POST META SOURCE TO UPDATE THE WIDGET LIVE OTHERWISE IT WONT UPDATE WITH LIVE DATA
                $album_tracks =  get_post_meta( $album_ids[0], 'srmp3_elementor_tracks', true);
                if($album_tracks == ''){
                    return;
                }   
            }else{
                //__B. WE ARE IN FRONT-END SO USE SAVED POST META SOURCE
                $elementorData = get_post_meta( $album_ids[0], '_elementor_data', true);
                $elementorData = (is_string($elementorData)) ? json_decode($elementorData, true) : ''; // make sure json_decode is parsing a string
                if(empty($elementorData)){
                    return;
                }
                
                $id = $el_widget_id;
                $results=[];

                $this->findData( $elementorData, $id, $results );

                $album_tracks = $results['settings']['feed_repeater'];

                $artwork = ( isset($results['settings']['album_img']['id'] ) && !empty($results['settings']['album_img']['id'] )) ? wp_get_attachment_image_src( $results['settings']['album_img']['id'], 'large' )[0] : '';
            }
        
            $num = 1;
            $track_pos = 0; 
            for($i = 0 ; $i < count($album_tracks) ; $i++) {

                $track_title = ( isset($album_tracks[$i]['feed_track_title'] )) ? $album_tracks[$i]['feed_track_title'] : false;
                $track_length = false;
                $album_title = false;
                $mp3_id = false;
                $artworkImageSize = ( $player == 'sticky' )? 'medium' : 'large';
                if ( isset( $album_tracks[$i]['feed_track_img']['id'] ) && $album_tracks[$i]['feed_track_img']['id'] != ''){
                    $thumb_url = wp_get_attachment_image_src( $album_tracks[$i]['feed_track_img']['id'], $artworkImageSize )[0];
                    $thumb_id = $album_tracks[$i]['feed_track_img']['id'];
                }else{
                   $thumb_url = $artwork;
                }
                
                if( isset( $album_tracks[$i]['feed_source_file']['url'] ) && $album_tracks[$i]['feed_source_file']['url'] != '' ){
                    // TRACK SOURCE IS FROM MEDIA LIBRARY
                    $audioSrc = $album_tracks[$i]['feed_source_file']['url'];
                    $mp3_id = $album_tracks[$i]['feed_source_file']['id'];
                    $mp3_metadata = wp_get_attachment_metadata( $mp3_id );
                    $track_length = ( isset( $mp3_metadata['length_formatted'] ) && $mp3_metadata['length_formatted'] !== '' )? $mp3_metadata['length_formatted'] : false;
                    $album_title = ( isset( $mp3_metadata['album'] ) && $mp3_metadata['album'] !== '' )? $mp3_metadata['album'] : false;
                    $track_artist = ( isset( $mp3_metadata['artist'] ) && $mp3_metadata['artist'] !== '' )? $mp3_metadata['artist'] : false;
                    $track_title = ( isset( $mp3_metadata["title"] ) && $mp3_metadata["title"] !== '' )? $mp3_metadata["title"] : false ;
                    //todo description below
                    if ( function_exists( 'run_sonaar_music_pro' ) ){
                        $media_post = get_post( $mp3_id );
                        $track_description = ( isset( $media_post->post_content ) && $media_post->post_content !== '' )? $media_post->post_content : false ;
                    }else{
                        $track_description = '';
                    }
                    $track_title = ( get_the_title( $mp3_id ) !== '' && $track_title !== get_the_title( $mp3_id ) ) ? get_the_title( $mp3_id ) : $track_title;
                    $track_title = html_entity_decode( $track_title, ENT_COMPAT, 'UTF-8' );
                    $track_title = apply_filters('srmp3_track_title', $track_title, $mp3_id, $audioSrc);


                }else if( isset( $album_tracks[$i]['feed_source_external_url']['url'] ) ){
                     // TRACK SOURCE IS AN EXTERNAL LINK
                    $audioSrc = $album_tracks[$i]['feed_source_external_url']['url'];
                }else{
                    $audioSrc = '';
                }
                $showLoading = true;

                ////////
                
                $album_tracks[$i] = array();
                $album_tracks[$i]["id"] = ( $mp3_id )? $mp3_id : '';
                $album_tracks[$i]["mp3"] = $audioSrc;
                $album_tracks[$i]["loading"] = $showLoading;
                $album_tracks[$i]["track_title"] = ( $track_title )? $track_title : "Track ". $num;
                $album_tracks[$i]["track_artist"] = ( isset( $track_artist ) && $track_artist != '' )? $track_artist : '';
                $album_tracks[$i]["length"] = $track_length;

                $album_tracks[$i]["peak_allow_frontend"] = 'name';
                if( $mp3_id ){
                    //ita media in the library
                    $peakFile = $peaks_dir . $mp3_id . '.peak';
                }else{
                    //its a stream
                    $peakFile = basename($audioSrc);
                    $peakFile = $peaks_dir . $peakFile . '.peak';
                }
                
                if (is_string($peakFile) && file_exists($peakFile)){
                    // Replace the server path with the URL
                    $peakFileUrl = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $peakFile);
                    $album_tracks[$i]["peakFile"] = $peakFileUrl;
                }

                $album_tracks[$i]["album_title"] = ( $album_title )? $album_title : '';
                $album_tracks[$i]["poster"] = ( $thumb_url )? urldecode($thumb_url) : null;
                if(isset($thumb_id)){
                    $album_tracks[$i]["track_image_id"] = $thumb_id;    
                } 
                $album_tracks[$i]["release_date"] = false;
                $album_tracks[$i]["song_store_list"] ='';
                $album_tracks[$i]["has_song_store"] = false;
                $album_tracks[$i]['track_pos'] = $track_pos;
                $album_tracks[$i]['sourcePostID'] = null;
                $album_tracks[$i]['description'] = (isset($track_description)) ? $track_description : null;
                if( Sonaar_Music::get_option('force_cta_download', 'srmp3_settings_download') == "true" || (isset( $this->shortcodeParams['force_cta_dl']) && $this->shortcodeParams['force_cta_dl'] == 'true')){
                    $album_tracks[$i]['optional_storelist_cta'] = $this->push_download_storelist_cta( $album_tracks[$i]['mp3'] );
                }
                $track_pos++; 
                $thumb_id = null;
                $num++;
            }
                $tracks = array_merge($tracks, $album_tracks);
        }else if ( $feed && $feed != '1'){    
            // 002. FEED USED DIRECTLY IN THE SHORTCODE ATTRIBUTE
            $feed = $this->checkACF($feed, $albums);
            $feed_title = $this->checkACF($feed_title, $albums);
            $feed_img = $this->checkACF($feed_img, $albums);
            $feed_desc = $this->checkACF($feed_desc, $albums);
            $artwork = $this->checkACF($artwork, $albums, false); 

            $thealbum = array();

            $feed_ar = explode('||', $feed);
            if ($feed_title !== null) {
                $feed_title_ar = explode('||', $feed_title);
            }
            if ($feed_img !== null) {
                $feed_img_ar = explode('||', $feed_img);
            }
            if ($feed_desc !== null) {
                $feed_desc_ar = explode('||', $feed_desc);
            }
            if ($feed_id !== null) {
                $feed_id_ar = explode('||', $feed_id);
            }
            $thealbum = [$feed_ar];
            
            foreach($thealbum as $a) {
                $album_tracks = $feed_ar;
                $num = 1;
                for($i = 0 ; $i < count($feed_ar) ; $i++) {
                    $track_title = ( isset( $feed_title_ar[$i] )) ? $feed_title_ar[$i] : false;
                    $track_desc = ( isset( $feed_desc_ar[$i] )) ? $feed_desc_ar[$i] : ''; // from smg
                    $track_postid = ( isset( $feed_id_ar[$i] )) ? $feed_id_ar[$i] : ''; // from smg
                    if ( isset($feed_img_ar[$i]) ){
                        $thumb_url = $feed_img_ar[$i];
                    }else{
                       $thumb_url = $artwork;
                    }
                    
                    ////////
                    $audioSrc = $feed_ar[$i];
                    // strip space at the end of the url
                    $audioSrc = trim($audioSrc);
                    $showLoading = true;
                    ////////
                    $track_title = apply_filters('srmp3_track_title', $track_title, null, $audioSrc);

                    $album_tracks[$i] = array();
                    $album_tracks[$i]["id"] = $track_postid;
                    $album_tracks[$i]["mp3"] = $audioSrc;
                    $album_tracks[$i]["loading"] = $showLoading;
                    $album_tracks[$i]["track_title"] = ( $track_title )? $track_title : pathinfo($audioSrc, PATHINFO_FILENAME);
                    $album_tracks[$i]["description"] = $track_desc;
                    $album_tracks[$i]["track_artist"] = ( isset( $track_artist ) && $track_artist != '' )? $track_artist : '';
                    $album_tracks[$i]["length"] = false;
                    $album_tracks[$i]["peak_allow_frontend"] = 'name';
                    
                    $peakFile = basename($audioSrc);
                    $peakFile = $peaks_dir . $peakFile . '.peak';
                    if (is_string($peakFile) && file_exists($peakFile)){
                        // Replace the server path with the URL
                        $peakFileUrl = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $peakFile);
                        $album_tracks[$i]["peakFile"] = $peakFileUrl;
                    }
                    $album_tracks[$i]["album_title"] = '';
                    $album_tracks[$i]["poster"] = ( $thumb_url )? urldecode($thumb_url) : $artwork;
                    $album_tracks[$i]["release_date"] = false;
                    $album_tracks[$i]["song_store_list"] ='';
                    $album_tracks[$i]["has_song_store"] = false;
                    $album_tracks[$i]['sourcePostID'] = null;
                    $album_tracks[$i]["optional_storelist_cta"] = $this->force_pushOptionalCTA($audioSrc, null, null, $cta_download_settings, null, null, null);
                    $num++;
                }

                $tracks = array_merge($tracks, $album_tracks);
            }     
        }else if ( isset($audio_meta_field) && $audio_meta_field != ''){
            // 003. FEED SOURCE IS METAKEY (ACF)
            if(is_numeric($audio_meta_field) ){
                $meta_key_type = 'id';
            }else if(strpos($audio_meta_field, "http") === 0){
                $meta_key_type = 'url';
            }else if(is_array($audio_meta_field)){
                $meta_key_type = 'array';
            }else{
                $meta_key_type = 'meta';
            }
           
            foreach ( $albums as $a ) {
                $album_tracks = array();
                
                if($meta_key_type == 'meta' && $repeater_meta_field != '' && is_array(get_post_meta( $a->ID, $repeater_meta_field, true))){
                   //REPEATER IS SET BY JETENGINE
                    foreach ( get_post_meta( $a->ID, $repeater_meta_field, true ) as $audio_track ) {
                        array_push($album_tracks, $audio_track);
                    }
                }else if( $meta_key_type == 'meta' && $repeater_meta_field != '' && is_array(get_post_meta( $a->ID, $repeater_meta_field )) ){
                    //REPEATER IS SET BY ACF
                    $numbers_of_tracks = (isset(get_post_meta( $a->ID, $repeater_meta_field )[0])) ? get_post_meta( $a->ID, $repeater_meta_field )[0] : '';
                    for ($i = 0; $i < $numbers_of_tracks; $i++) {
                        
                        $audio_track = $repeater_meta_field . '_' . $i . '_' . $audio_meta_field;
                        $audio_track = get_post_meta( $a->ID, $audio_track )[0];
                        array_push($album_tracks, $audio_track);
                    }
                }else{
                    array_push($album_tracks, $audio_meta_field);
                }

                $wc_add_to_cart = $this->wc_add_to_cart($a->ID);
                $wc_buynow_bt =  $this->wc_buynow_bt($a->ID);
                $is_variable_product = ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true' ) ? $this->is_variable_product($a->ID) : '';
              
                if ( get_post_meta( $a->ID, 'reverse_post_tracklist', true) ){
                    $album_tracks = array_reverse($album_tracks); //reverse tracklist order POST option
                }
                
                if ($album_tracks!=''){ 
                    for($i = 0 ; $i < count($album_tracks) ; $i++) {
                       
                        $fileOrStream =  'mp3';
                        $thumb_id = get_post_thumbnail_id($a->ID);
                        if(isset($album_tracks[$i]["track_image_id"]) && $album_tracks[$i]["track_image_id"] != ''){
                            $thumb_id = $album_tracks[$i]["track_image_id"];
                        }
                        $artworkImageSize = ( $player == 'sticky' )? 'medium' : Sonaar_Music::get_option('music_player_coverSize', 'srmp3_settings_widget_player');
                        $thumb_url = ( $thumb_id )? wp_get_attachment_image_src($thumb_id, $artworkImageSize , true)[0] : false ;
                        if ($artwork){ //means artwork is set in the shortcode so prioritize this image instead of the the post featured image.
                            $thumb_url = $artwork;
                        }
                        $track_title = false;
                        $album_title = false;
                        $mp3_id = false;
                        $mp3_metadata = '';
                        $track_description = false;
                        $showLoading = false;
                        $track_length = false;
                        $audioSrc = '';
                        $song_store_list = isset($album_tracks[$i]["song_store_list"]) ? $album_tracks[$i]["song_store_list"] : '' ;
                        $album_store_list = ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true') ? $this->push_woocart_in_storelist($a->ID, $is_variable_product, $wc_add_to_cart, $wc_buynow_bt) : false;
                        $has_song_store =false;
                        if (isset($song_store_list[0])){
                            $has_song_store = true; 
                        }

                        switch ($fileOrStream) {
                            
                            case 'mp3':
                                switch($meta_key_type){
                                    case 'id':
                                        $mp3_id = $audio_meta_field;
                                        $mp3_metadata = wp_get_attachment_metadata( $mp3_id );
                                    break;
                                    
                                    case 'url':
                                        $audioSrc = $audio_meta_field;
                                        $mp3_metadata = $this->wordpress_audio_meta( $audioSrc );
                                    break;
                                   case 'meta':
                                        if(is_array(get_post_meta( $a->ID, $audio_meta_field)) && is_numeric( get_post_meta( $a->ID, $audio_meta_field, true) )){
                                            //this is an array that contains an media ID.
                                            $mp3_id = get_post_meta( $a->ID, $audio_meta_field, true);
                                            $mp3_metadata = wp_get_attachment_metadata( $mp3_id );
                                            
                                        }else if( $repeater_meta_field !='' ){
                                            // Repeater SET
                                            if(is_numeric( $album_tracks[$i] )){
                                                // Audio is an ID
                                                $mp3_id = $album_tracks[$i];
                                                $mp3_metadata = wp_get_attachment_metadata( $mp3_id );
                                            }else{
                                                // Full URL is set
                                                $audioSrc = (isset($album_tracks[$i][$audio_meta_field])) ? $album_tracks[$i][$audio_meta_field] : $album_tracks[$i];
                                                $mp3_metadata = $this->wordpress_audio_meta( $audioSrc );
                                            }
                                            
                                            
                                        }else{
                                            $audioSrc = (is_array( get_post_meta( $a->ID, $audio_meta_field, true ) ) ) ? $album_tracks[$i] : get_post_meta( $a->ID, $audio_meta_field, true);
                                            $mp3_metadata = $this->wordpress_audio_meta( $audioSrc );
                                        }
                                        if($mp3_id != false ){
                                            //get featured image of a post ID
                                            $thumb_id = get_post_thumbnail_id( $mp3_id );
                                            $thumb_url = ( $thumb_id )? wp_get_attachment_image_src($thumb_id,'medium' , true)[0] : false ;
                                            if ($artwork){ //means artwork is set in the shortcode so prioritize this image instead of the the post featured image.
                                                $thumb_url = $artwork;
                                            }
                                        }
                                    break;
                                }

                                    $audioSrc = ($audioSrc == '') ? wp_get_attachment_url($mp3_id) : $audioSrc ;
                                    $track_title = ( isset( $mp3_metadata["title"] ) && $mp3_metadata["title"] !== '' )? $mp3_metadata["title"] : '' ;
                                    $track_title = ($track_title == '') ? get_the_title($a) : $track_title;
                                    $track_title = html_entity_decode($track_title, ENT_COMPAT, 'UTF-8');
                                    $track_title = apply_filters('srmp3_track_title', $track_title, $mp3_id, $audioSrc);

                                    $track_artist = ( isset( $mp3_metadata['artist'] ) && $mp3_metadata['artist'] !== '' )? $mp3_metadata['artist'] : false;
                                    $album_title = ( isset( $mp3_metadata['album'] ) && $mp3_metadata['album'] !== '' )? $mp3_metadata['album'] : html_entity_decode(get_the_title($a->ID), ENT_QUOTES, 'UTF-8');
                                    $track_length = ( isset( $mp3_metadata['length_formatted'] ) && $mp3_metadata['length_formatted'] !== '' )? $mp3_metadata['length_formatted'] : false;
                                    $showLoading = true;
                                break;
                        }
                        
                        $num = 1;
                        $album_tracks[$i] = array();
                        $album_tracks[$i]["id"] = ( $mp3_id )? $mp3_id : '' ;
                        $album_tracks[$i]["mp3"] = $audioSrc;
                        $album_tracks[$i]["loading"] = $showLoading;
                        $album_tracks[$i]["track_title"] = ( $track_title ) ? $track_title : "Track ". $num++;
                        $album_tracks[$i]["track_artist"] = ( isset( $track_artist ) && $track_artist != '' )? $track_artist : '';
                        $album_tracks[$i]["length"] = $track_length;

                        $album_tracks[$i]["peak_allow_frontend"] = 'name';
                        if (isset($mp3_id) && $mp3_id !== false){
                            $peakFile = $peaks_dir . $mp3_id . '.peak';
                        }else{
                            $peakFile = basename($audioSrc);
                            $peakFile = $peaks_dir . $peakFile . '.peak';
                        }
                        if (is_string($peakFile) && file_exists($peakFile)){
                            // Replace the server path with the URL
                            $peakFileUrl = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $peakFile);
                            $album_tracks[$i]["peakFile"] = $peakFileUrl;
                        }

                        $album_tracks[$i]["album_title"] = ( $album_title )? $album_title :'';
                        $album_tracks[$i]["poster"] = urldecode($thumb_url);
                        if(isset($thumb_id)){
                            $album_tracks[$i]["track_image_id"] = $thumb_id;    
                        }
                        $album_tracks[$i]["release_date"] = get_post_meta($a->ID, 'alb_release_date', true);
                        $album_tracks[$i]["song_store_list"] = $song_store_list;
                        $album_tracks[$i]["has_song_store"] = $has_song_store;
                        $album_tracks[$i]["optional_storelist_cta"] = $this->force_pushOptionalCTA($audioSrc, $a, null, $cta_download_settings, $cta_link_settings, $cta_share_settings, $cta_favorite_settings);
                        $album_tracks[$i]["album_store_list"] = $album_store_list;
                        $album_tracks[$i]['sourcePostID'] = $a->ID;
                        $thumb_id = null;
                        
                    }
                
                    $tracks = array_merge($tracks, $album_tracks);
                }
            }
        }else if($import_file){
            /*
            //
            //
            //
            // 004. FEED SOURCE IS FROM A TEXT FILE TO IMPORT.
            this can be in a single post, or in a player widget which the first album contains tracks to import ($import_file will be true in a favorite widget player by example), or a elementor widget with a RSS or CSV source set.
            //
            //
            */
            if (is_array($albums) && count($albums) == 0) {
                $playlist = $this->importFile($import_file, null, $combinedtracks = true, $rss_items, $rss_item_title, $isPlayer_Favorite, $favoriteList);
            }
            foreach ( $albums as $a ) {
                $playlist = $this->importFile($import_file, $a, $combinedtracks = true, $rss_items, $rss_item_title, $isPlayer_Favorite, $favoriteList);

                // WIP. Not tested everywhere...
                if ( get_post_meta( $a->ID, 'reverse_post_tracklist', true) ){
                    $playlist['tracks'] = array_reverse($playlist['tracks']); //reverse tracklist order POST option
                }
            
                if (is_array($playlist)) {
            
                    $filtered_tracks = array();
                    $tracksToFilter = array_key_exists('tracks', $playlist) ? $playlist['tracks'] : $playlist;
            
                    foreach ($tracksToFilter as $track) {
                        if (!isset($track['favorite']) || $track['favorite'] !== false) {
                            $filtered_tracks[] = $track;
                        }
                    }
            
                    // Overwrite the 'tracks' key in the $playlist array with the $filtered_tracks
                    $playlist['tracks'] = $filtered_tracks;
                }

            }
        } else {      
            $tracks = [];

            foreach ( $albums as $a ) {

                $hasAccess = $this->wc_memberships_user_has_access($a->ID);
                if( $hasAccess == false ){
                    continue;
                }

                $wc_add_to_cart = $this->wc_add_to_cart($a->ID);
                $wc_buynow_bt =  $this->wc_buynow_bt($a->ID);
                $is_variable_product = ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true' ) ? $this->is_variable_product($a->ID) : '';
                if(get_post_meta($a->ID, 'playlist_csv_file', true)){
                    $trackSource = 'csv';
                }else if(get_post_meta($a->ID, 'playlist_rss', true)){
                    $trackSource = 'rss';
                }else{
                    $trackSource = 'post';
                }
                           
                if ( $trackSource == 'csv' || $trackSource == 'rss' ){
                     /*
                    //
                    //
                    //
                    // 005. FEED SOURCE IS A POSTID -> ALB_TRACKLIST POST META WITH A TEXT FILE TO IMPORT
                    //
                    //
                    */
                   // $album_tracks = false; // to avoid the next loop below
                    $import_file = (get_post_meta($a->ID, 'playlist_csv_file', true)) ? get_post_meta($a->ID, 'playlist_csv_file', true) : get_post_meta($a->ID, 'playlist_rss', true);
                
                    $album_tracks = $this->importFile($import_file, $a, $combinedtracks = true, $rss_items, $rss_item_title, $isPlayer_Favorite, $favoriteList);
                }else{
                    $album_tracks = get_post_meta( $a->ID, 'alb_tracklist', true);
                    $album_tracks = apply_filters( 'srmp3_album_tracks', $album_tracks, $a->ID );
                }

                if ( get_post_meta( $a->ID, 'reverse_post_tracklist', true) && is_array($album_tracks)){
                    $album_tracks = array_reverse($album_tracks); //reverse tracklist order POST option
                }
                
                if ($album_tracks != '' && is_array($album_tracks) && $trackSource == 'post'){ 
                    /*
                    //
                    //
                    //
                    // 006. FEED SOURCE IS A POSTID -> ALB_TRACKLIST POST META
                    //
                    //
                    */
                   

                    for($i = 0 ; $i < count($album_tracks) ; $i++) {
                        
                       
                        $track_artist = ''; // reset artist value.
                        $fileOrStream =  $album_tracks[$i]['FileOrStream'];
                        $thumb_id = get_post_thumbnail_id($a->ID);
                        $ifOptionalImage = false;
                        if(isset($album_tracks[$i]["track_image_id"]) && $album_tracks[$i]["track_image_id"] != ''){
                            $thumb_id = $album_tracks[$i]["track_image_id"];
                            $ifOptionalImage = true; 
                        }
                        
                        $artworkImageSize = ( $player == 'sticky' )? 'medium' : Sonaar_Music::get_option('music_player_coverSize', 'srmp3_settings_widget_player');

                        $thumb_url = ( $thumb_id )? wp_get_attachment_image_src($thumb_id, $artworkImageSize, true)[0] : false ;

                        if ($artwork){ //means artwork is set in the shortcode so prioritize this image instead of the the post featured image.
                           // $thumb_url = $artwork;
                        }

                        //$store_array = array();
                        $track_title = false;
                        $album_title = false;
                        $mp3_id = false;
                        $media_post = false;
                        $track_description = false;
                        $audioSrc = '';
                        $song_store_list = isset($album_tracks[$i]["song_store_list"]) ? $album_tracks[$i]["song_store_list"] : '' ;
                        $album_store_list = ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true') ? $this->push_woocart_in_storelist($a, $is_variable_product, $wc_add_to_cart, $wc_buynow_bt) : false;
                       
                      
                        $has_song_store = false;
                        if (isset($song_store_list[0])){
                            $has_song_store = true; 
                        }
                        $icecast_json = false; 
                        $icecast_mount = false; 
                        $showLoading = false;
                        $track_length = false;
                        $has_lyric = (isset($album_tracks[$i]['track_lyrics']) && $album_tracks[$i]['track_lyrics'] != false)? true : false;

                        switch ($fileOrStream) {
                            case 'mp3':
                                if ( isset( $album_tracks[$i]["track_mp3"] ) ) {
                                    $mp3_id = $album_tracks[$i]["track_mp3_id"];
                                    $audioSrc = wp_get_attachment_url($mp3_id);
                                    $mp3_metadata = wp_get_attachment_metadata( $mp3_id );
                                    $track_title = ( isset( $mp3_metadata["title"] ) && $mp3_metadata["title"] !== '' )? $mp3_metadata["title"] : false ;
                                    $track_title = ( get_the_title($mp3_id) !== '' && $track_title !== get_the_title($mp3_id))? get_the_title($mp3_id): $track_title;
                                    $track_title = html_entity_decode($track_title, ENT_COMPAT, 'UTF-8');
                                    $track_title = apply_filters('srmp3_track_title', $track_title, $mp3_id, $audioSrc);
                                    $track_artist = ( isset( $mp3_metadata['artist'] ) && $mp3_metadata['artist'] !== '' )? $mp3_metadata['artist'] : false;
                                    $album_title = ( isset( $mp3_metadata['album'] ) && $mp3_metadata['album'] !== '' )? $mp3_metadata['album'] : false;
                                    $track_length = ( isset( $mp3_metadata['length_formatted'] ) && $mp3_metadata['length_formatted'] !== '' )? $mp3_metadata['length_formatted'] : false;
                                    $media_post = get_post( $mp3_id );
                                    $track_description = ( isset ($album_tracks[$i]["track_description"]) && $album_tracks[$i]["track_description"] !== '' )? $album_tracks[$i]["track_description"] : false;
                                    $showLoading = true;
                                    
                                    

                                }
                                break;

                            case 'stream':
                                
                                $audioSrc = ( array_key_exists ( "stream_link" , $album_tracks[$i] ) && $album_tracks[$i]["stream_link"] !== '' )? $album_tracks[$i]["stream_link"] : false;
                                $track_title = (  array_key_exists ( 'stream_title' , $album_tracks[$i] ) && $album_tracks[$i]["stream_title"] !== '' )? $album_tracks[$i]["stream_title"] : false;
                                $track_title = apply_filters('srmp3_track_title', $track_title, null, $audioSrc);

                                $album_title = ( isset ($album_tracks[$i]["stream_album"]) && $album_tracks[$i]["stream_album"] !== '' )? $album_tracks[$i]["stream_album"] : false;
                                $track_artist = ( isset ($album_tracks[$i]["artist_name"]) && $album_tracks[$i]["artist_name"] !== '' )? $album_tracks[$i]["artist_name"] : false;
                                $track_description = ( isset ($album_tracks[$i]["track_description"]) && $album_tracks[$i]["track_description"] !== '' )? $album_tracks[$i]["track_description"] : false;
                                $track_length = ( isset( $album_tracks[$i]["stream_lenght"] ) && $album_tracks[$i]["stream_lenght"] !== '' ) ? $album_tracks[$i]["stream_lenght"] : false;
                                $showLoading = true;
                                
                                break;

                            case 'icecast':
                            
                                $audioSrc = ( array_key_exists ( "icecast_link" , $album_tracks[$i] ) && $album_tracks[$i]["icecast_link"] !== '' )? $album_tracks[$i]["icecast_link"] : false;
                                $track_title = (  array_key_exists ( 'icecast_title' , $album_tracks[$i] ) && $album_tracks[$i]["icecast_title"] !== '' )? $album_tracks[$i]["icecast_title"] : false;
                                $album_title = ( isset ($album_tracks[$i]["icecast_album"]) && $album_tracks[$i]["icecast_album"] !== '' )? $album_tracks[$i]["icecast_album"] : false;
                                $feed_status = ( isset ($album_tracks[$i]["feed_status"]) && $album_tracks[$i]["feed_status"] !== '' )? $album_tracks[$i]["feed_status"] : false;
                                $track_artist = ( isset ($album_tracks[$i]["icecast_hostname"]) && $album_tracks[$i]["icecast_hostname"] !== '' )? $album_tracks[$i]["icecast_hostname"] : false;
                                $track_description = ( isset ($album_tracks[$i]["track_description"]) && $album_tracks[$i]["track_description"] !== '' )? $album_tracks[$i]["track_description"] : false;
                                $track_length = false;
                                $icecast_json = ( array_key_exists ( "icecast_json" , $album_tracks[$i] ) && $album_tracks[$i]["icecast_json"] !== '' )? $album_tracks[$i]["icecast_json"] : false; 
                                $icecast_mount = ( array_key_exists ( "icecast_mount" , $album_tracks[$i] ) && $album_tracks[$i]["icecast_mount"] !== '' )? $album_tracks[$i]["icecast_mount"] : false; 
                                $showLoading = true;
                                
                                break;   
                            default:
                                $album_tracks[$i] = array();
                                break;
                        }
                        $isPreview = false;
                        if ($isPreviewEnabled) {
                            if (isset($album_tracks[$i]["audio_preview"]) && $album_tracks[$i]["audio_preview"] != '') {
                                $audio_preview_url = $album_tracks[$i]["audio_preview"];
                                $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $audio_preview_url);
                            
                                // Determine if the URL is external
                                $isExternal = filter_var($audio_preview_url, FILTER_VALIDATE_URL) && strpos($audio_preview_url, $upload_dir['baseurl']) === false;
                            
                                $fileExists = false;
                                if ($isExternal) {
                                    // Check if external file exists using urlExists function
                                    if ($this->urlExists($audio_preview_url)) {
                                        $fileExists = true;
                                    }
                                } else {
                                    // Check if local file exists
                                    if (file_exists($file_path)) {
                                        $fileExists = true;
                                    }
                                }
                            
                                // Check for non-logged-in users or allowed roles
                                if ($fileExists) {
                                    if (!$isUserLoggedIn || array_intersect($user->roles, $allowed_roles)) {
                                        $isPreview = true;
                                        $audioSrc = $audio_preview_url;
                                    }
                                }
                            }
                        }
                        
                        $num = 1;
                        $album_tracks[$i] = array();
                        
                        $album_tracks[$i]["id"] = ( $mp3_id )? $mp3_id : '' ;
                        $album_tracks[$i]["mp3"] = $audioSrc;
                        $album_tracks[$i]["loading"] = $showLoading;
                        $album_tracks[$i]["track_title"] = ( $track_title )? $track_title : "Track ". $num++;
                        $album_tracks[$i]["track_artist"] = ( isset( $track_artist ) && $track_artist != '' )? $track_artist : '';
                        $album_tracks[$i]["length"] = $track_length;
                        $album_tracks[$i]["album_title"] = ( $album_title )? $album_title : $a->post_title;
                        $album_tracks[$i]["poster"] = urldecode($thumb_url);
                        $album_tracks[$i]["optional_poster"] = ( $ifOptionalImage )? urldecode($thumb_url) : false; 
                        if(isset($thumb_id)){
                            $album_tracks[$i]["track_image_id"] = $thumb_id;
                        }
                        $album_tracks[$i]["track_pos"] = ( get_post_meta( $a->ID, 'reverse_post_tracklist', true) )? count($album_tracks) - ($i + 1) : $i ;
                        $album_tracks[$i]["release_date"] = get_post_meta($a->ID, 'alb_release_date', true);
                        $album_tracks[$i]["song_store_list"] = $song_store_list;
                        $album_tracks[$i]["has_song_store"] = $has_song_store;
                        $album_tracks[$i]["album_store_list"] = $album_store_list;
                        $album_tracks[$i]['sourcePostID'] = $a->ID;
                        $album_tracks[$i]['has_lyric'] = $has_lyric;

                        //Filters. See Documentation
                        $album_tracks[$i]["track_title"]        = apply_filters('custom_track_title', $album_tracks[$i]["track_title"], $a);
                        $album_tracks[$i]["album_title"]        = apply_filters('custom_album_title', $album_tracks[$i]["album_title"], $a);
                        $album_tracks[$i]["track_artist"]       = apply_filters('custom_track_artist', $album_tracks[$i]["track_artist"], $a);
                        $album_tracks[$i]["poster"]             = apply_filters('custom_poster_image', $album_tracks[$i]["poster"], $a);
                        if(isset($thumb_id)){
                            $album_tracks[$i]["track_image_id"] = apply_filters('custom_track_image_id', $album_tracks[$i]["track_image_id"], $a);
                        }
                        //check if track_length is less than 45 minutes
                        $album_tracks[$i]["peak_allow_frontend"] = false;
                        if ($track_length) {
                            $parts = explode(':', $track_length);
                            $totalSeconds = 0;
                        
                            // Depending on the number of parts, calculate the total seconds differently
                            switch (count($parts)) {
                                case 3: // HH:MM:SS
                                    $totalSeconds = $parts[0] * 3600 + $parts[1] * 60 + $parts[2];
                                    break;
                                case 2: // MM:SS
                                    $totalSeconds = $parts[0] * 60 + $parts[1];
                                    break;
                                case 1: // SS
                                    $totalSeconds = $parts[0];
                                    break;
                            }
                        
                            if ($totalSeconds < 2700) {
                                $album_tracks[$i]["peak_allow_frontend"] = true;
                            }
                        }
                        $album_tracks[$i]["peakFile"] = '';

                        if ( $isPreview ) {
                            $album_tracks[$i]['isPreview'] = true;
                            // get the preview file.
                            if( $mp3_id ){
                                //ita media in the library
                                $peakFile = $peaks_dir . $mp3_id . '_preview.peak';
                            }else{
                                //its a stream
                                $peakFile = isset(get_post_meta( $a->ID, 'alb_tracklist' )[0][$i]['track_peaks_preview']) ? get_post_meta( $a->ID, 'alb_tracklist' )[0][$i]['track_peaks_preview'] : null;
                            }
                        }else{
                            if( $mp3_id ){
                                //ita media in the library
                                $peakFile = $peaks_dir . $mp3_id . '.peak';
                            }else{
                                //its a stream
                                $peakFile = isset(get_post_meta( $a->ID, 'alb_tracklist' )[0][$i]['track_peaks']) ? get_post_meta( $a->ID, 'alb_tracklist' )[0][$i]['track_peaks'] : null;
                            }
                        }

                        if ( is_string($peakFile) && file_exists($peakFile) ){
                            $peakFileUrl = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $peakFile);
                            $album_tracks[$i]["peakFile"] = $peakFileUrl;
                            // below it works for alias domain but not used for now.
                            //$peakFileUrl = str_replace(wp_normalize_path(ABSPATH), trailingslashit($site_url), $peakFile);
                            //$album_tracks[$i]["peakFile"] = str_replace(wp_normalize_path($upload_dir['basedir']), trailingslashit($site_url), $peakFileUrl);
                        }

                        $track_description = ( $track_desc_postcontent ) ? $a->post_content : $track_description;
                        $track_description = do_shortcode($track_description);
                        $album_tracks[$i]['description'] = (isset($track_description)) ? $track_description : null;
                        $album_tracks[$i]['icecast_json'] =  $icecast_json;
                        $album_tracks[$i]['icecast_mount'] =  $icecast_mount;
                        $thumb_id = null;

                        $trackFavorited = $this->isTrack_PartOfUserPlaylist($album_tracks[$i]["track_pos"], $a->ID, $favoriteList);
                        $trackRecentlyPlayed = $this->isTrack_PartOfUserPlaylist($album_tracks[$i]["track_pos"], $a->ID, $userHistoryList);

                        $album_tracks[$i]["optional_storelist_cta"] = $this->force_pushOptionalCTA($audioSrc, $a, $trackFavorited, $cta_download_settings, $cta_link_settings, $cta_share_settings, $cta_favorite_settings);
                        
                        if ($isPlayer_Favorite){
                            $album_tracks[$i]['favorite'] = $trackFavorited;
                        }
                        if ($isPlayer_recentlyPlayed){
                            $album_tracks[$i]['recently_played'] = $trackRecentlyPlayed;
                        }

                    }
                    
                }

                if (is_array($album_tracks)) {
                    $filtered_tracks = [];
                    $tracksToFilter = array_key_exists('tracks', $album_tracks) ? $album_tracks['tracks'] : $album_tracks;
                    
                    // Filtering based on the flags
                    foreach ($tracksToFilter as $track) {
                        $includeTrack = true;
                        if ($isPlayer_Favorite) {
                            $includeTrack &= isset($track['favorite']) && $track['favorite'] !== false;
                        }
                        if ($isPlayer_recentlyPlayed) {
                            $includeTrack &= isset($track['recently_played']) && $track['recently_played'] !== false;
                        }
                        
                        if ($includeTrack) {
                            $filtered_tracks[] = $track;
                        }
                    }
                    
                    $tracks = array_merge($tracks, $filtered_tracks);
                }
                
                
            }
            
            if($isPlayer_recentlyPlayed && $userHistoryList){
                // We want to reorder the tracks based on the user history
                $orderList = $userHistoryList ;
                $reorderedTracks = [];
                foreach ($orderList as $item) {
                    $matchingTracks = array_filter($tracks, function ($track) use ($item) {
                        return $track['sourcePostID'] == $item['postId'] && $track['track_pos'] == $item['trackPos'];
                    });
                
                    $reorderedTracks = array_merge($reorderedTracks, $matchingTracks);
                }
                
                // Now includes also duplicate from same albums
                $tracks = $reorderedTracks;
                //only keep the first 5 tracks
                $tracks = array_slice($tracks, 0, $posts_per_pages);
            }

            if( $reverse_tracklist && ! (isset( $this->shortcodeParams['lazy_load'] ) && $this->shortcodeParams['lazy_load'] === 'true') ){
                $tracks = array_reverse($tracks); //reverse tracklist order option
            }
        }

        if(!$playlist){
            $playlist['playlist_name'] = $title;
            if ( empty($playlist['playlist_name']) ) $playlist['playlist_name'] = "";
            $playlist['tracks'] = $tracks;
            if ( empty($playlist['tracks']) ) $playlist['tracks'] = array();
        }
       /* $end_time = microtime(true);
        $elapsed_time = $end_time - $start_time;
        echo "$x cta proceeded. ";
        echo "The Function took $elapsed_time seconds to run.";*/
        return $playlist;

    }

    public static function wc_memberships_user_has_access($post_id) {
        if(! function_exists('wc_memberships_is_post_content_restricted') ){
            return true;
        }

        if ( wc_memberships_is_post_content_restricted($post_id) && ! current_user_can( 'wc_memberships_view_restricted_post_content', $post_id ))  {
            return false;
        } else {
            return true;
        }
    }

public function importFile($import_file, $a = null, $combinedtracks = false, $rss_items = -1, $rss_item_title = null, $isPlayer_Favorite = null, $favoriteList = null ){
      $upload_dir = wp_get_upload_dir();
      $peaks_dir = Sonaar_Music::get_peak_dir();
      // Load file contents into a string variable
      $wc_add_to_cart = (isset($a)) ? $this->wc_add_to_cart($a->ID) : false;
      $wc_buynow_bt   = (isset($a)) ? $this->wc_buynow_bt($a->ID) : false;
      $is_variable_product = ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true' ) ? $this->is_variable_product($a->ID) : '';
                  
      $album_tracks = false; // to avoid the next loop below

      $json_file = $import_file;

      try {
        if (strtolower(substr($json_file, -4)) === '.csv') {
            $fileType = 'csv';
        } else if (strtolower(substr($json_file, -5)) === '.json') {
            $fileType = 'json';
        } else {
            $fileType = 'rss';
        }
        // Read the contents of the JSON file
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        $json_file = file_get_contents($json_file, false, stream_context_create($arrContextOptions));
        if (current_user_can('manage_options') && $json_file === false) {
            $error = "<p style='color:red;'>Notice to admin: Unable to open the stream for URL - <a href='" . esc_url($import_file) . "' target='_blank'>" .  esc_url($import_file) . "</a>";

            if (ini_get('allow_url_fopen') == false) {
                $error .= "<br><strong>allow_url_fopen</strong> is disabled on your server. Contact your hosting provider to enable it in your php setting";
            }
            if (extension_loaded('openssl') == false) {
                $error .= "<br><strong>openssl</strong> extension is not loaded. Make sure your website is secure (HTTPS) before loading an external feed. Contact your hosting provider.";
            }
            $error .="</p>";
            echo $error;
        }
        
        if ($fileType == 'csv') {
            // Process the CSV file data
            $csv_rows = str_getcsv($json_file, "\n"); // Split into rows
        
            // Detect delimiter from the first row
            $header_row = $csv_rows[0];
            $delimiter = (strpos($header_row, ";") !== false) ? ";" : ",";
        
            $header_row = str_getcsv(array_shift($csv_rows), $delimiter); // Parse header with detected delimiter
        
            $playlists = [];
            $track_pos = 0;
            $playlist_image = false;
            $playlist_name = false;
            $combined_playlist_tracks = [];
        
            foreach ($csv_rows as $csv_row) {
                $row_data = str_getcsv($csv_row, $delimiter); // Parse rows with detected delimiter
        
                // Ensure the row matches the header column count
                /*if (count($header_row) > count($row_data)) {
                    $row_data = array_pad($row_data, count($header_row), '');
                } elseif (count($header_row) < count($row_data)) {
                    $row_data = array_slice($row_data, 0, count($header_row));
                }*/
        
                if (count($header_row) != count($row_data)) {
                    if (current_user_can('manage_options')) {
                        echo "<p style='color:red;'>Notice to admin: Mismatch in row: $csv_row\n<br>";
                        echo "Expected columns: " . count($header_row) . ", Actual columns: " . count($row_data) . "\n<br></p>";
                    }
                    continue;
                }
        
                $data_row = array_combine($header_row, $row_data);
        
                $song_store_list = [];
                foreach ($data_row as $key => $value) {
                    if (strpos($key, 'cta_title_') === 0) {
                        $num = substr($key, -1);
                        if ($value != '') {
                            $song_store_list[] = [
                                'store-icon' => $data_row['cta_icon_' . $num],
                                'store-name' => $value,
                                'store-link' => (isset($data_row['cta_link_' . $num])) ? $data_row['cta_link_' . $num] : '',
                                'store-target' => (isset($data_row['cta_target_' . $num])) ? $data_row['cta_target_' . $num] : '_blank',
                                'link-option' => (isset($data_row['cta_is_popup_' . $num]) && $data_row['cta_is_popup_' . $num] !== '') ? 'popup' : '',
                                'store-content' => (isset($data_row['cta_popup_content_' . $num])) ? $data_row['cta_popup_content_' . $num] : '',
                            ];
                        }
                    }
                }
        
                $audioSrc = isset($data_row['track_url']) ? $data_row['track_url'] : '';
                $track_title = isset($data_row['track_title']) ? $data_row['track_title'] : '';
                $track_title = apply_filters('srmp3_track_title', $track_title, null, $audioSrc);
        
                $track = [
                    'id' => '',
                    'playlist_name' => isset($data_row['playlist_name']) ? $data_row['playlist_name'] : '',
                    'playlist_image' => isset($data_row['playlist_image']) ? $data_row['playlist_image'] : '',
                    'mp3' => $audioSrc,
                    'loading' => true,
                    'category_slug' => isset($data_row['playlist_category']) ? $data_row['playlist_category'] : '',
                    'track_title' => $track_title,
                    'track_artist' => isset($data_row['track_artist']) ? $data_row['track_artist'] : '',
                    'length' => isset($data_row['track_length']) ? $data_row['track_length'] : '',
                    'album_title' => isset($data_row['album_title']) ? $data_row['album_title'] : '',
                    'poster' => isset($data_row['track_image']) ? $data_row['track_image'] : '',
                    'track_pos' => (isset($a) && get_post_meta($a->ID, 'reverse_post_tracklist', true)) ? count($csv_rows) - ($track_pos + 1) : $track_pos++,
                    'release_date' => isset($data_row['album_subtitle']) ? $data_row['album_subtitle'] : '',
                    'song_store_list' => isset($song_store_list) ? $song_store_list : '',
                    'album_store_list' => ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true') ? $this->push_woocart_in_storelist($a, $is_variable_product, $wc_add_to_cart, $wc_buynow_bt) : false,
                    'has_song_store' => (isset($song_store_list[0])) ? true : false,
                    'sourcePostID' => (isset($a)) ? $a->ID : '',
                    'has_lyric' => isset($data_row['track_lyrics']) ? true : false,
                    'description' => isset($data_row['description']) ? $data_row['description'] : '',
                    'woocommerce_download_file' => isset($data_row['woocommerce_download_file']) ? $data_row['woocommerce_download_file'] : '',
                    'track_lyrics' => isset($data_row['track_lyrics']) ? $data_row['track_lyrics'] : '',
                ];
                if( isset($a) && isset($cta_download_settings)){
                    $trackFavorited = $this->isTrack_PartOfUserPlaylist($track['track_pos'], $a->ID, $favoriteList);
                    $track['optional_storelist_cta'] =  $this->force_pushOptionalCTA($audioSrc, $a, $trackFavorited, $cta_download_settings, $cta_link_settings, $cta_share_settings, $cta_favorite_settings);
                    if( $isPlayer_Favorite ){
                        $track['favorite'] = $trackFavorited;
                    }
                }

                $track["peak_allow_frontend"] = 'name';
                 
                $peakFile = basename($audioSrc);
                $peakFile = $peaks_dir . $peakFile . '.peak';
                if (is_string($peakFile) && file_exists($peakFile)){
                    // Replace the server path with the URL
                    $peakFileUrl = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $peakFile);
                    $track["peakFile"] = $peakFileUrl;
                }

        
                $playlist_name = isset($data_row['playlist_name']) ? $data_row['playlist_name'] : '';
                $playlist_image = isset($data_row['playlist_image']) ? $data_row['playlist_image'] : '';
        
                if (!isset($playlists[$playlist_name])) {
                    $playlists[$playlist_name] = [
                        'playlist_name' => $playlist_name,
                        'playlist_image' => $playlist_image,
                        'tracks' => []
                    ];
                }
        
                // Add track to the corresponding playlist only if the playlist_name matches
                if ($track['playlist_name'] === $playlist_name) {
                    $playlists[$playlist_name]['tracks'][] = $track;
                }
        
                // Add track to the combined playlist
                $combined_playlist_tracks[] = $track;
            }
        
            if ($combinedtracks) {
                $combined_playlist_name = "Combined Tracks";
                $combined_playlist_image = ""; // Set a default image if you like
                // Add the combined playlist to the $playlists array
                $playlists[$combined_playlist_name] = [
                    'playlist_name' => $combined_playlist_name,
                    'playlist_image' => $combined_playlist_image,
                    'tracks' => $combined_playlist_tracks
                ];
                return $playlists['Combined Tracks'];
            }
        
            return array_values($playlists);
        }else if($fileType == 'json'){
            // Process the JSON file data // NOT USED AT THE MOMENT
            $playlist = json_decode($json_file, true, 512, JSON_THROW_ON_ERROR);
            $json_tracks = $playlist['tracks'];
            $tracks = [];
            $track_pos = 0;
            if (isset($a)){
                foreach ($json_tracks as &$track) { //To modify the original array, you can use the & operator to pass each element in the $json_tracks array by reference, like this:
                    $track['sourcePostID'] = $a->ID;
                    $track['track_pos'] = ( get_post_meta( $a->ID, 'reverse_post_tracklist', true) )? count($json_tracks) - ($track_pos + 1) : $track_pos++ ;
                    $track['album_store_list'] = ($wc_add_to_cart == 'true' || $wc_buynow_bt == 'true') ? $this->push_woocart_in_storelist($a, $is_variable_product, $wc_add_to_cart, $wc_buynow_bt) : false;
                    $track['has_song_store'] = (isset($track['album_store_list'][0])) ? true : false;
                }
            }
            $tracks = array_merge($tracks, $json_tracks);
            return $tracks;
        }else{
            // Process the RSS feed data
            $feed = simplexml_load_string($json_file);
            if (!$feed){
                return;
            }
            $playlist_name = isset($feed->channel->title) ? sanitize_text_field((string) $feed->channel->title) : '';
            $playlist_image = isset($feed->channel->image) ? esc_url_raw((string) $feed->channel->image->url) : '';

            $playlist = [
                'playlist_name' => $playlist_name,
                'playlist_image' => $playlist_image,
                //'tracks' => []
            ];
            $tracks = [];
            $track_pos = 0;
            $itunes_ns = 'http://www.itunes.com/dtds/podcast-1.0.dtd';

            $counter = 0;
            if(isset($rss_item_title)){
                // Use a regular expression to match the exact pattern, e.g., "Podcast 150"
                $pattern = '/' . preg_quote($rss_item_title, '/') . '/i'; // Add 'i' flag for case-insensitive search
            }
            foreach ($feed->channel->item as $item) {
                if ($rss_items != -1 && $counter >= $rss_items) {
                    break;
                }
                $item_title = isset($item->title) ? (string) $item->title : '';
                if (isset($rss_item_title) && !preg_match($pattern, $item_title)) {
                    continue;
                }
                $itunes_data = $item->children($itunes_ns);
                if (isset($itunes_data->image)) {
                    $itunes_image = $itunes_data->image->attributes();
                } else {
                    $itunes_image = null;
                }
                if(isset($itunes_data->duration)){
                    $item_duration = (string) $itunes_data->duration;
                    if (strpos($item_duration,':') !== false) {
                        $episode_audio_file_duration = $item_duration;
                    }else{
                        $file_duration_secs             = intval($item_duration);
                        $hours                          = floor( $file_duration_secs / 3600 ) . ':';
                        $minutes                        = substr( '00' . floor( ( $file_duration_secs % 3600 / 60 ) ), -2 ) . ':';
                        $seconds                        = substr( '00' . $file_duration_secs % 60, -2 );
                        $episode_audio_file_duration    = ltrim( $hours . $minutes . $seconds, '0:0' );
                    }
                }

                $audioSrc = isset($item->enclosure) ? esc_url((string) $item->enclosure['url']) : '';

                $track_title = isset($item->title) ? sanitize_text_field((string) $item->title) : '';
                $track = [
                    'id' => '',
                    'mp3' =>  $audioSrc,
                    'loading' => true,
                    'track_title' => esc_html($track_title),
                    'track_artist' => isset($item->itunes_author) ? sanitize_text_field((string) $item->itunes_author) : '',
                    'length' => isset($episode_audio_file_duration) ? $episode_audio_file_duration : '',
                    'album_title' =>  $playlist_name,
                    'poster' => isset($itunes_image['href']) ? esc_url((string) $itunes_image['href']) : $playlist_image,
                    'published_date' => isset($item->pubDate) ? esc_html((string) $item->pubDate) : '',
                    'track_pos' => $track_pos,
                    'release_date' => '',
                    'song_store_list' => '',
                    'album_store_list' => false,
                    'has_song_store' => false,
                    'sourcePostID' => (isset($a)) ? $a->ID : '',
                    'has_lyric' => false,
                    'description' => isset($item->description) ? (string) $item->description : '',
                    'woocommerce_download_file' => '',
                    'track_lyrics' => '',
                ];
                if(isset($a) && isset($cta_download_settings)){
                    $trackFavorited = $this->isTrack_PartOfUserPlaylist($track['track_pos'], $a->ID, $favoriteList);
                    $track['optional_storelist_cta'] =  $this->force_pushOptionalCTA($audioSrc, $a, $trackFavorited, $cta_download_settings, $cta_link_settings, $cta_share_settings, $cta_favorite_settings);
                    if( $isPlayer_Favorite ){
                            $track['favorite'] = $trackFavorited;
                    }
                }
                $track_pos++;
                $tracks[] = $track;
                $counter++;
            }
            $playlist['tracks'] = $tracks;
            
            return $playlist;

        }

      } catch (JsonException $e) {
          if ( current_user_can( 'manage_options' ) ) {
          // There was an error decoding the JSON data                    
          echo 'Notice to admin: Playlist - Error decoding the Playlist JSON file: ' . $e->getMessage() . '. <br>Validate the JSON file here: https://jsonlint.com/';
              // The user is an admin
          } else {
          // The user is not an admin, dont show error
          }
         
      }
  /*
  //
  //
  // End of JSON Parser
  //
  //
  */

}
}