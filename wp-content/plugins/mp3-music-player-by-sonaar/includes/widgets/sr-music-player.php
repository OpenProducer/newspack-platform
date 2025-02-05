<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Controls_Media;
use Elementor\Group_Control_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

use Sonaar_Music_Admin;
use Sonaar_Music;
/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */

class SR_Audio_Player extends Widget_Base {
	public $fields_groups;
	
	public function get_name() {
		return 'music-player';
	}

	public function get_title() {
		return esc_html__( 'MP3 Audio Player', 'sonaar-music' );
	}

	public function get_icon() {
		return 'sricons-logo sonaar-badge';
	}

	public function get_help_url() {
		return 'https://support.sonaar.io';
	}

	public function get_categories() {
		return [ 'elementor-sonaar' ];
	}

	public function get_defaultLayout() {
		return Sonaar_Music::get_option('player_widget_type', 'srmp3_settings_general') ;
	}
	public function get_user_roles() {
		return \Sonaar_Music::get_user_roles();
	}
	public function get_srmp3_option_label($option_id, $option_tab){
		if( 'true' === Sonaar_Music::get_option( $option_id, $option_tab ) || 'on' === Sonaar_Music::get_option( $option_id, $option_tab ) ){
			return esc_html__( 'Use global setting (Yes)', 'sonaar-music' );
		}else{
			return esc_html__( 'Use global setting (No)', 'sonaar-music' );
		}
	}
	public function srp_promo_message(){
		return '<div class="sr_gopro elementor-nerd-box sonaar-gopro">' .
		'<i class="sricons-logo" aria-hidden="true"></i>
			<div class="elementor-nerd-box-title">' .
				__( 'Business plan is required', 'sonaar-music' ) .
			'</div>
			<div class="elementor-nerd-box-message">' .
				__( 'This feature starts with the Business Plan which includes:', 'sonaar-music' ) .
			'</div>
			<ul>
				<li><i class="eicon-check"></i>Search Bar Widget</li>
				<li><i class="eicon-check"></i>Filter Dropdown Widget</li>
				<li><i class="eicon-check"></i>Chips & Tags Widget</li>
				<li><i class="eicon-check"></i>Add to Favorites Button & Favorite Playlist widget</li>
				<li><i class="eicon-check"></i>Send an Offer / Negotiate Buttons</li>
				<li><i class="eicon-check"></i>Ask for Email to Access Download</li>
				<li><i class="eicon-check"></i>Tracklist Custom Fields</li>
				<li><i class="eicon-check"></i>Tracklist Pagination</li>
				<li><i class="eicon-check"></i>Lazy Load option for Optimal Performance</li>
				<li><i class="eicon-check"></i>Support for ACF, JetEngine, etc</li>
				<li><i class="eicon-check"></i>Full WooCommerce support</li>
			</ul>
			<a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-go-pro" href="https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin" target="_blank">' .
			__( 'Upgrade your plan', 'elementor' ) .
			'</a>
		</div>';
	}
	public function get_keywords() {
		return [ 'mp3', 'player', 'audio', 'sonaar', 'podcast', 'music', 'beat', 'sermon', 'episode', 'radio' ,'stream', 'sonar', 'sonnar', 'sonnaar', 'music player', 'podcast player', 'carousel' ,'slider', 'coverflow', 'icecast'];
	}

	public function get_script_depends() {
		if (function_exists( 'run_sonaar_music_pro' ) && (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode())) {
			return ['elementor-sonaar', 'color-thief','srp-swiper'];
		}else{
			return [ 'elementor-sonaar' ];
		}
	}
	public function get_style_depends() {
		if (function_exists( 'run_sonaar_music_pro' ) && (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode())) {
			return [ 'srp-swiper-style' ];
		}else{
			return [];
		}
	}

	public function get_swiper_responsive_value($paramName, $device = null) {
		$settings = $this->get_settings_for_display();
		if( isset($settings[$paramName . '_mobile']) && $settings[$paramName . '_mobile'] != '' && $device != 'mobile' && $device != 'tablet'){
			$value =  $settings[$paramName . '_mobile'];
		}else if( isset($settings[$paramName . '_tablet']) && $settings[$paramName . '_tablet'] != ''  && $device != 'tablet'){
			$value =  $settings[$paramName . '_tablet'];
		}else{
			$value =  $settings[$paramName];
		}
		return $value;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' 							=> esc_html__( 'Audio Player Settings', 'sonaar-music' ),
				'tab'   							=> Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'album_img',
			[
				'label' => esc_html__( 'Image Cover (Optional)', 'sonaar-music' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'dynamic' => [ 'active' => true,],
				'separator' => 'after',
				'conditions'                    => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'player_layout', 
							'operator' => '==',
							'value' => 'skin_float_tracklist'
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_boxed_tracklist'
								],
								[
									'name' => 'playlist_show_soundwave', 
									'operator' => '!=',
									'value' => 'yes'
								]
							]
						]
					]
				]
			]
		);
		$this->add_control(
			'playlist_source',
			[
				'label'					=> esc_html__( 'Source', 'sonaar-music' ),
				'type' 					=> Controls_Manager::SELECT,
				'label_block'			=> true,
				'options' 				=> sr_plugin_playlist_source(),
				'default' 				=> 'from_cpt',
			]
		);
		$this->add_control(
			'playlist_list',
				[
					'label' => sprintf( esc_html__( 'Select %1$s Post(s)', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('playlist')) ),
					'label_block' => true,
					'description' => sprintf( esc_html__('To create new %1$s %2$sclick here%3$s Leave blank if you want to display your latest published %1$s', 'sonaar-music'), Sonaar_Music_Admin::sr_GetString('playlist'), '<a href="' . esc_url(get_admin_url( null, 'post-new.php?post_type=sr_playlist' )) . '" target="_blank">', '</a><br>'),
					'type' 							=> \Elementor\Controls_Manager::SELECT2,
					'multiple' 						=> true,
					'options'               		=> sr_plugin_elementor_select_playlist(),   
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '==',
					            'value' => 'from_cpt'
					        ],
					    ]
					]   
				]
		);
		$this->add_control(
			'rss_feed',
			[
				'label' => esc_html__( 'RSS Feed URL', 'sonaar-music' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'url',
				'placeholder'       		=> esc_html__( 'Enter RSS feed URL', 'sonaar-music' ),
				'frontend_available' 		=> true,
				'dynamic'           		=> [ 'active' => true ],
				'label_block' 				=> true,
				'conditions'                => [
					'relation' => 'or',
						'terms' => [
							[
								'name' => 'playlist_source', 
								'operator' => '==',
								'value' => 'from_rss'
							]
						]
				]
			]
		);
		$this->add_control(
			'rss_item_title',
			[
				'label' => esc_html__( 'Fetch specific episode', 'sonaar-music' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'url',
				'placeholder'       		=> esc_html__( 'Search episode title', 'sonaar-music' ),
				'frontend_available' 		=> true,
				'dynamic'           		=> [ 'active' => true ],
				'label_block' 				=> true,
				'conditions'                => [
					'relation' => 'or',
						'terms' => [
							[
								'name' => 'playlist_source', 
								'operator' => '==',
								'value' => 'from_rss'
							]
						]
				]
			]
		);
		if ( function_exists( 'run_sonaar_music_pro' ) ){
				$this->add_control(
					'audio_meta_field',
					[
						'label' 						=> esc_html__( 'Audio Source Metakey ID', 'sonaar-music' ),
						'description' => esc_html__( 'Leave blank to fetch your current post tracklist. For dynamic custom fields, enter your custom meta field key or ID.', 'sonaar-music' ),
						'type' 							=> Controls_Manager::TEXT,
						'dynamic' 						=> [
							'active' 					=> true,
						],
						'default' 						=> '',
						'label_block' 					=> true,
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'playlist_source',
									'operator' => '==',
									'value' => 'from_current_post'
								],
							]
						]   
					]
				);
				$this->add_control(
					'repeater_meta_field',
					[
						'label' 						=> esc_html__( 'Repeater Group Metakey ID', 'sonaar-music' ),
						'description' => esc_html__( 'Leave blank if you are not using dynamic repeater or enter metakey ID for your Repeater Group', 'sonaar-music' ),
						'type' 							=> Controls_Manager::TEXT,
						'dynamic' 						=> [
							'active' 					=> true,
						],
						'default' 						=> '',
						'label_block' 					=> true,
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'playlist_source',
									'operator' => '==',
									'value' => 'from_current_post'
								],
							]
						]   
					]
			);
			$this->add_control(
				'playlist_list_cat',
					[
						'label'                 		=> esc_html__( 'From specific category(s)', 'sonaar-music' ),
						'label_block'					=> true,
						'type' 							=> \Elementor\Controls_Manager::SELECT2,
						'multiple' 						=> true,
						'options'               		=> srp_elementor_select_category(),   
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'playlist_source',
									'operator' => '==',
									'value' => 'from_cat'
								],
							]
						]   
					]
			);
			$this->add_control(
				'show_cat_description',
				[
					'label' 						=> esc_html__( 'Display category description', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'playlist_source', 'operator' => 'in', 'value' => ['from_cat','from_current_term']],
									]
								]
						]
					]
				]
			);
			if(Sonaar_Music::get_option('is_user_history_usermeta_enabled', 'srmp3_settings_general') !== 'true'){
				$this->add_control(
					'recentlyplayed_notice',
					[
						'raw' => sprintf( esc_html__( 'User history tracking is currently %1$sDISABLED%2$s. To enable it, activate the %1$sTrack User Listening History%2$s option found in WP-Admin > MP3 Player > Settings > General, and refresh this page.', 'sonaar-music' ), '<strong>', '</strong>' ),
						'type' => Controls_Manager::RAW_HTML,
						'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
						'render_type' => 'ui',
						'conditions'  => [
							'relation' => 'and',
								'terms' => [
									['name' => 'playlist_source', 'operator' => 'in', 'value' => ['recently_played']],
								]
						]
					]
				);
			}
			$this->add_control(
				'posts_per_page',
				[
					'label' => esc_html__( 'Max number of posts to load', 'sonaar-music' ),
					'description' => esc_html__( 'Leave blank for all posts. If you have more than 100 posts, we recommend to enable Lazy Load in Style > Pagination', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 10000,
					'step' => 1,
					'default' => 99,
					'conditions'                    => [
						'relation' => 'and',
							'terms' => [
								['name' => 'playlist_source', 'operator' => 'in', 'value' => ['from_cat','recently_played']],
							]
					]
				]
			);
			$this->add_control(
				'lazyload_notice',
				[
					'raw' => sprintf( esc_html__('%1$sLazy load is activated%2$s Max number of post (see above) has no effect. Go to Style > Pagination to change the number of tracks per page.', 'sonaar-music' ), '<strong>', '</strong>'),
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'render_type' => 'ui',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
									['name' => 'playlist_source', 'operator' => 'in', 'value' => ['from_cat','from_current_term']],
										['name' => 'lazy_load', 'operator' => '==', 'value' => 'true'],
									]
								],
						],
					],
				]
			);
			
			$this->add_control(
				'feed_text_source_file',
				[
					'label' => esc_html__( 'Upload CSV File', 'sonaar-music' ),
					'description' => sprintf(
						esc_html__('Example of CSV File format %1$s.', 'sonaar-music'),
						'<a href="' . plugin_dir_url(SRMP3_DIR_PATH. 'sonaar-music.php') . 'templates/example_of_csv_file_to_import.csv" target="_blank">' . esc_html__('here', 'sonaar-music') . '</a>'
					),
					'type' => Controls_Manager::MEDIA,
					'media_type' => 'text',
					'frontend_available' => true,
					'conditions'                    => [
						'relation' => 'or',
							'terms' => [
								[
									'name' => 'playlist_source', 
									'operator' => '==',
									'value' => 'from_text_file'
								]
							]
					]
				]
			);
			$this->add_control(
				'rss_items',
				[
					'label' => esc_html__( 'How many episodes your want to fetch?', 'sonaar-music' ),
					'label_block'					=> true,
					'description' => esc_html__( 'Leave blank for all episodes', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 10000,
					'step' => 1,
					//'default' => '',
					'conditions'                    => [
						'relation' => 'or',
							'terms' => [
								[
									'name' => 'playlist_source', 
									'operator' => '==',
									'value' => 'from_rss'
								]
							]
					]
				]
			);
		}
		if ( !function_exists( 'run_sonaar_music_pro' ) ){
			$this->add_control(
				'playlist_list_cat_srpro',
					[
						'label'                 		=> esc_html__( 'Choose specific category(s)', 'sonaar-music' ),
						'label_block'					=> true,
						'classes' 						=> 'sr-pro-only sr-pro-heading-only',
						'type' 							=> \Elementor\Controls_Manager::SELECT2,
						'multiple' 						=> true,
						'options'               		=> srp_elementor_select_category(),   
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'playlist_source',
									'operator' => '==',
									'value' => 'from_cat'
								],
							]
						]   
					]
			);
			$this->add_control(
				'feed_text_source_file_srpro',
				[
					'label' => esc_html__( 'Upload CSV File', 'sonaar-music' ),
					'classes' 						=> 'sr-pro-only sr-pro-heading-only',
					'description' => sprintf(
						esc_html__('Example of CSV File format %1$s.', 'sonaar-music'),
						'<a href="' . plugin_dir_url(SRMP3_DIR_PATH. 'sonaar-music.php') . 'templates/example_of_csv_file_to_import.csv" target="_blank">' . esc_html__('here', 'sonaar-music') . '</a>'
					),
					'type' => Controls_Manager::MEDIA,
					'media_type' => 'text',
					'frontend_available' => true,
					'conditions'                    => [
						'relation' => 'or',
							'terms' => [
								[
									'name' => 'playlist_source', 
									'operator' => '==',
									'value' => 'from_text_file'
								]
							]
					]
				]
			);
			$this->add_control(
				'rss_items_srpro',
				[
					'label' 						=> esc_html__( 'How many episodes your want to fetch?', 'sonaar-music' ),
					'label_block'					=> true,
					'classes' 						=> 'sr-pro-only sr-pro-heading-only',
					'description' 					=> esc_html__( 'Leave blank for all episodes', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::NUMBER,
					'min' 							=> 0,
					'max' 							=> 10000,
					'step' 							=> 1,
					'conditions'                    => [
						'relation' => 'or',
							'terms' => [
								[
									'name' => 'playlist_source', 
									'operator' => '==',
									'value' => 'from_rss'
								]
							]
					]
				]
			);
		}
		
		
		$this->add_control(
			'playlist_title', [
				'label' => esc_html__( 'Playlist Title', 'sonaar-music' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [ 'active' => true,],
				'conditions' 					=> [
				    'relation' => 'and',
				    'terms' => [
				        [
				            'name' => 'playlist_source',
				            'operator' => '==',
				            'value' => 'from_elementor'
				        ],
				    ]
				] 
			]
		);
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'feed_source',
			[
				'label' => esc_html__( 'Source', 'sonaar-music' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'media_file',
				'options' => [
					'media_file' => esc_html__( 'Media File', 'sonaar-music' ),
					'external_url' => esc_html__( 'External URL', 'sonaar-music' ),
				],
				'frontend_available' => true,
			]
		);
		$repeater->add_control(
			'feed_source_external_url',
			[
				'label' => esc_html__( 'External URL', 'sonaar-music' ),
				'type' => Controls_Manager::URL,
				'condition' => [
					'feed_source' => 'external_url',
				],
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your URL', 'sonaar-music' ),
				'frontend_available' => true,
			]
		);
		$repeater->add_control(
			'feed_source_file',
			[
				'label' => esc_html__( 'Upload MP3 File', 'sonaar-music' ),
				'type' => Controls_Manager::MEDIA,
				'media_type' => 'audio',
				'frontend_available' => true,
				'condition' => [
					'feed_source' => 'media_file',
				],
			]
		);
		$repeater->add_control(
			'feed_track_title', [
				'label' => sprintf( esc_html__( '%1$s Title', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [ 'active' => true,],
				'condition' => [
					'feed_source' => 'external_url',
				],
			]
		);
		$repeater->add_control(
			'feed_track_img',
			[
				'label' => sprintf( esc_html__( '%1$s Cover (Optional)', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'description' => sprintf( esc_html__(  'Setting a %1$s cover image will override the main cover image. Recommended: JPG file 500x500px', 'sonaar-music' ), Sonaar_Music_Admin::sr_GetString('track') ),
				'dynamic' => [ 'active' => true,],
			]
		);

		$this->add_control(
			'feed_repeater',
			[
				'label' => sprintf( esc_html__( 'Add %1$s(s)', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'prevent_empty' => false,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ feed_source_file["url"] || feed_source_external_url["url"] }}}',
				'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'play_current_id',
					            'operator' => '==',
					            'value' => ''
					        ],
					        [
					            'name' => 'playlist_source',
					            'operator' => '==',
					            'value' => 'from_elementor'
					        ],
					    ]
				] 
			]
		);
		$this->add_control(
			'hr_storelinks',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'conditions' 					=> [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'playlist_source',
							'operator' => '==',
							'value' => 'from_elementor'
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout',
											'operator' => '!=',
											'value' => 'skin_button'
										],
										[	
											'name' => 'playlist_show_album_market',
											'operator' => '==',
											'value' => 'yes'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout',
											'operator' => '==',
											'value' => 'skin_button'
										],
										[	
											'name' => 'playlist_show_album_market_skin_button',
											'operator' => '==',
											'value' => 'yes'
										]
									]
								],
							]
						],
					]
				]
			]
		);
		$store_repeater = new \Elementor\Repeater();
		$store_repeater->add_control(
			'store_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);
		$store_repeater->add_control(
			'store_name', [
				'label' => esc_html__( 'Link Title', 'sonaar-music' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [ 'active' => true,],
			]
		);
		$store_repeater->add_control(
			'store_link', [
				'label' => esc_html__( 'Link URL', 'sonaar-music' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [ 'active' => true,],
			]
		);

		$this->add_control(
			'storelist_repeater',
			[
				'label' => esc_html__( 'External Link Buttons', 'sonaar-music' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'prevent_empty' => false,
				'fields' => $store_repeater->get_controls(),
				'title_field' => '{{{ store_name || store_link["url"] }}}',
				'conditions' 					=> [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'playlist_source',
							'operator' => '==',
							'value' => 'from_elementor'
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout',
											'operator' => '!=',
											'value' => 'skin_button'
										],
										[	
											'name' => 'playlist_show_album_market',
											'operator' => '==',
											'value' => 'yes'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout',
											'operator' => '==',
											'value' => 'skin_button'
										],
										[	
											'name' => 'playlist_show_album_market_skin_button',
											'operator' => '==',
											'value' => 'yes'
										]
									]
								],
							]
						],
					]
				]
			]
		);
		
		$this->add_control(
			'hr_2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'player_layout',
			[
				'label'					=> esc_html__( 'Player Design Layout', 'sonaar-music' ),
				'type' 					=> Controls_Manager::SELECT,
				'label_block'			=> true,
				'options' 				=> [
					'skin_float_tracklist'         =>  esc_html__('Floated', 'sonaar-music'),
					'skin_boxed_tracklist'    =>  esc_html__('Boxed', 'sonaar-music'),
					'skin_button'    =>  esc_html__('Button', 'sonaar-music'),
				],
				'default' 				=> 'skin_float_tracklist',
			]
		);
		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$this->add_control(
				'adaptive_colors',
				[
					'label' 						=> esc_html__( 'Adaptive Colors', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Automatically match the colors of the audio player with the cover artwork using our AI algorithm', 'sonaar-music' ),
					'classes' 						=> 'sr-adaptive-colors',
					'type' 			=> \Elementor\Controls_Manager::SELECT,
					'options' 		=> [
						'0' 		=> esc_html__( 'Disabled', 'sonaar-music' ),
						'1' 		=> esc_html__( 'Color Match 01', 'sonaar-music' ),
						'2' 		=> esc_html__( 'Color Match 02', 'sonaar-music' ),
						'3' 		=> esc_html__( 'Color Match 03', 'sonaar-music' ),
						'4' 		=> esc_html__( 'Color Match 04', 'sonaar-music' ),
						'random' 	=> esc_html__( 'Randomize Everytime', 'sonaar-music' ),
					],
					'label_block'	=> true,
					'default' 		=> '0',
					'condition' => [
						'player_layout!' 	=> 'skin_button'
					],
				]
			);
			$this->add_control(
				'adaptive_colors_freeze',
				[
					'label' 						=> esc_html__( 'Do not swap the colors on track change', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'adaptive_colors', 'operator' => '!==', 'value' => '0'],
										['name' => 'player_layout', 'operator' => '!==', 'value' => 'skin_button']
									]
								],
						],
					],
				]
			);
			$this->add_control(
				'enable_sticky_player',
				[
					'label' 						=> esc_html__( 'Sticky Audio Player', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '1',
					'separator'						=> 'before',

				]
			);
			$this->add_control(
				'slider',
				[
					'label' 						=> esc_html__( 'Show Carousel', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> 'true',
					'default' 						=> '',
					'separator'						=> 'before',
					'condition' 					=> [
						'lazy_load!'				=> 'true',
					],
				]
			);
		}
		
	
		$this->add_control(
			'playlist_show_playlist',
			[
				'label' 							=> esc_html__( 'Show Tracklist', 'sonaar-music' ),
				'type' 								=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 							=> esc_html__( 'Show', 'sonaar-music' ),
				'label_off' 						=> esc_html__( 'Hide', 'sonaar-music' ),
				'return_value' 						=> 'yes',
				'default' 							=> 'yes',
				'separator'							=> 'before',
				'condition' => [
					'player_layout!' 	=> 'skin_button'
				],
			]
		);
		$this->add_control(
			'playlist_show_playlist_skin_button',
			[
				'label' 							=> esc_html__( 'Show Tracklist', 'sonaar-music' ),
				'type' 								=> \Elementor\Controls_Manager::SWITCHER,
				'separator'							=> 'before',
				'label_on' 							=> esc_html__( 'Show', 'sonaar-music' ),
				'label_off' 						=> esc_html__( 'Hide', 'sonaar-music' ),
				'return_value' 						=> 'yes',
				'default' 							=> '',
				'separator'							=> 'before',
				'condition' => [
					'player_layout' 	=> 'skin_button'
				],
			]
		);
		
		$this->add_control(
			'playlist_show_soundwave',
			[
				'label' 							=> esc_html__( 'Hide Mini Player / Progress Bar', 'sonaar-music' ),
				'type' 								=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 							=> esc_html__( 'Hide', 'sonaar-music' ),
				'label_off' 						=> esc_html__( 'Show', 'sonaar-music' ),
				'return_value' 						=> 'yes',
				'default' 							=> '',
				'condition' => [
					'player_layout!' 	=> 'skin_button'
				],
			]
		);
		$this->add_control(
			'playlist_hide_artwork',
			[
				'label' 							=> esc_html__( 'Hide Image Cover', 'sonaar-music' ),
				'type' 								=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 							=> esc_html__( 'Hide', 'sonaar-music' ),
				'label_off' 						=> esc_html__( 'Show', 'sonaar-music' ),
				'return_value' 						=> 'yes',
				'default' 							=> '',
				'conditions'                    => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'player_layout', 
							'operator' => '==',
							'value' => 'skin_float_tracklist'
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_boxed_tracklist'
								],
								[
									'name' => 'playlist_show_soundwave', 
									'operator' => '!=',
									'value' => 'yes'
								]
							]
						]
					]
				]
			]
		);
		$this->add_control(
			'sr_player_on_artwork',
			[
				'label' 						=> esc_html__( 'Display Controls on Top of the image cover', 'sonaar-music' ),
				'type' 							=> Controls_Manager::SWITCHER,
				'default' 						=> '',
				'return_value' 					=> 'yes',
				'conditions'                    => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_float_tracklist'
								],
								[
									'name' => 'playlist_hide_artwork', 
									'operator' => '!=',
									'value' => 'yes'
								]
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_boxed_tracklist'
								],
								[
									'name' => 'playlist_show_soundwave', 
									'operator' => '!=',
									'value' => 'yes'
								],
								[
									'name' => 'playlist_hide_artwork', 
									'operator' => '!=',
									'value' => 'yes'
								]
							]
						]
					]
				]
			]
		);
		$this->add_control(
			'playlist_show_album_market',
			[
				'label' 							=> esc_html__( 'External Links', 'sonaar-music' ),
				'type' 								=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 							=> esc_html__( 'Show', 'sonaar-music' ),
				'label_off' 						=> esc_html__( 'Hide', 'sonaar-music' ),
				'return_value' 						=> 'yes',
				'default' 							=> 'yes',
				'condition' => [
					'player_layout!' 	=> 'skin_button'
				],
			]
		);
		$this->add_control(
			'playlist_show_album_market_skin_button',
			[
				'label' 							=> esc_html__( 'External Links', 'sonaar-music' ),
				'type' 								=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 							=> esc_html__( 'Show', 'sonaar-music' ),
				'label_off' 						=> esc_html__( 'Hide', 'sonaar-music' ),
				'return_value' 						=> 'yes',
				'default' 							=> '',
				'condition' => [
					'player_layout' 	=> 'skin_button'
				],
			]
		);
		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$this->add_control(
				'enable_shuffle',
				[
					'label' 						=> esc_html__( 'Enable Shuffle', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'separator'							=> 'before',
					'return_value' 					=> '1',
					'default' 						=> '', 
				]
			);
			$this->add_control(
				'no_track_skip',
				[
					'label' => sprintf( esc_html__( 'Stop when  %1$s ends', 'sonaar-music' ), Sonaar_Music_Admin::sr_GetString('track') ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> 'yes',
					'default' 						=> '', 
				]
			);
			$this->add_control(
				'no_loop_tracklist',
				[
					'label' => sprintf( esc_html__( 'Do not loop %1$s list', 'sonaar-music' ), Sonaar_Music_Admin::sr_GetString('track') ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> 'yes',
					'default' 						=> '', 
				]
			);
			$this->add_control(
				'track_memory',
				[
					'label' 						=>  esc_html__( 'Remember Track Progress', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SELECT,
					'options' 				=> [
						'default' 					=> esc_html__( $this->get_srmp3_option_label('track_memory', 'srmp3_settings_general') ),
						'true'    					=>  esc_html__('Yes', 'sonaar-music'),
						'false'    					=>  esc_html__('No', 'sonaar-music'),
					],
					'default' 						=> 'default', 
				]
			);
		}
// Deprecated control: play_current_id. It's always hidden except for old installation. This has been replaced by playlist_source = from_current_post
		$this->add_control(
			'play_current_id',
			[
				'label'							 	=> esc_html__( 'Play its own Post ID track', 'sonaar-music' ),
				'description' 						=> esc_html__( 'Check this case if this player is intended to be displayed on its own single post', 'sonaar-music' ),
				'type' 								=> \Elementor\Controls_Manager::SWITCHER,
				'yes' 								=> esc_html__( 'Yes', 'sonaar-music' ),
				'no' 								=> esc_html__( 'No', 'sonaar-music' ),
				'return_value' 						=> 'yes',
				'default' 							=> '',
				'conditions' 					=> [
				    'relation' => 'and',
				    'terms' => [
				        [
				            'name' => 'playlist_source',
				            'operator' => '!=',
				            'value' => 'from_elementor'
				        ],
						[
							'name' => 'play_current_id',
							'operator' => '!=',
							'value' => ''
						],				       
				    ]
				]
			]
		);
		if ( !function_exists( 'run_sonaar_music_pro' ) ){
			$this->add_control(
				'pro_features_heading',
				[
					'label' => esc_html__( 'Pro Features', 'textdomain' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'classes' 						=> 'sr-pro-heading-only',
				]
			);
			$this->add_control(
				'enable_sticky_player_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Sticky Player', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'description' 					=> esc_html__( 'This option allows you to display a sticky footer player bar on this page', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_slider_player_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Carousel & 3D Coverflow', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Display a carousel of your audio covers with a 3D coverflow slider', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'audio_spectrum_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Animated Audio Spectrum', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Live & Animated Audio visualizer - fully customizable through Elementor!', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> 'true',
					'default'						=> '',
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'adaptive_colors_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Adaptive Colors AI', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Match the player colors with the image artwork automatically!', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> 'true',
					'default'						=> '',
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'grid_layout_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Grid Layout', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Transform your playlist in a Grid Layout', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> 'true',
					'default'						=> '',
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_favorite_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Favorites Buttons & Favorite Playlist', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Add to Favorite Buttons', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_searchbox_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Search Box', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Add search box to search any keywords within the tracklist', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_custom_fields_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Custom Field Columns', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Add any custom fields in the tracklist', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_addtocart_pro-only',
				[
					'label' 						=> esc_html__( 'Add Buy Now button', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Add Buy Now and Add-to-cart buttons on each tracks. We also support audio/music licensing.', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_scrollbar_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Scrollbar', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Enable a scrollbar for long tracklist', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_pagination_pro-only',
				[
					'label' 						=> esc_html__( 'Enable Track Pagination', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_thumbnails_pro-only',
				[
					'label' 						=> esc_html__( 'Add Image on each tracks', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'description' 					=> esc_html__( 'Add image covers for each tracks in the tracklist', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_volume_pro-only',
				[
					'label' 						=> esc_html__( 'Display Volume Control', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_playlistduration_pro-only',
				[
					'label' 						=> esc_html__( 'Display Playlist Duration', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			
			$this->add_control(
				'enable_publishdate_pro-only',
				[
					'label' 						=> esc_html__( 'Display Publish Date', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_numbersoftrack_pro-only',
				[
					'label' 						=> sprintf( esc_html__( 'Display Total Numbers of %1$ss', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_skipbt_pro-only',
				[
					'label' 						=> esc_html__( 'Display Skip 15/30 seconds', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			$this->add_control(
				'enable_speedrate_pro-only',
				[
					'label' 						=> esc_html__( 'Display Speed Rate', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
			
			$this->add_control(
				'enable_shuffle_pro-only',
				[
					'label' 						=> esc_html__( 'Display Shuffle/Random', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '0', 
					'classes' 						=> 'sr-pro-only',
				]
			);
		}
		$this->end_controls_section();

		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$this->start_controls_section(
				'query_section',
				[
					'label'                 		=> esc_html__( 'Query & List Order', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_CONTENT,
					'condition' => [
						'playlist_source!' 	=> 'from_elementor'
					]
				]
			);
			
			$this->add_control(
				'order',
				[
					'label'   	=> esc_html__( 'Order', 'sonaar-music' ),
					'type'    	=> Controls_Manager::SELECT,
					'default' 	=> 'DESC',
					'options' 	=> [
						'DESC' 	=> esc_html__( 'DESC', 'sonaar-music' ),
						'ASC'  	=> esc_html__( 'ASC', 'sonaar-music' ),
					],
					'condition' => [
						'reverse_tracklist' 	=> '',
						'playlist_source' 	=> ['from_cpt', 'from_cat', 'from_current_term']
					]
				]
			);
			$this->add_control(
				'orderby',
				[
					'label'   => esc_html__( 'Order by', 'sonaar-music' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'date',
					'options' => $this->get_orderby(),
					'condition' => [
						'playlist_source' 	=> ['from_cpt', 'from_cat', 'from_current_term']
					]
				]
			);
			$this->add_control(
				'posts_not_in',
				[
					'label'       => esc_html__( 'Exclude posts by IDs', 'sonaar-music' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => '',
					'description' => esc_html__( 'Eg. 12, 24, 33', 'sonaar-music' ),
					'dynamic' => array(
						'active' => true,
					),
					'condition' => [
						'playlist_source' 	=> ['from_cat', 'from_current_term']
					]
				]
			);
			$this->add_control(
				'category_not_in',
					[
						'label'                 		=> esc_html__( 'Exclude Terms/Categories', 'sonaar-music' ),
						'label_block'					=> true,
						'type' 							=> \Elementor\Controls_Manager::SELECT2,
						'multiple' 						=> true,
						'options'               		=> srp_elementor_select_category(),   
						'dynamic' => array(
							'active' => true,
						),
						'condition' => [
							'playlist_source' 	=> ['from_cat']
						]
					]
			);
			
			$this->add_control(
				'query_by_author',
					[
						'label'                 		=> esc_html__( 'Include by Author(s)', 'sonaar-music' ),
						'label_block'					=> true,
						'type' 							=> \Elementor\Controls_Manager::SELECT2,
						'multiple' 						=> true,
						'options'               		=> srp_elementor_select_authors(),   
						'dynamic' => array(
							'active' => true,
						),
						'conditions'                    => [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'playlist_source', 
									'operator' => '==',
									'value' => 'from_cat'
								],
								[
									'name' => 'query_by_author_current', 
									'operator' => '!=',
									'value' => 'true'
								],
							]
						]
					]
			);
			$this->add_control(
				'query_by_author_current',
				[
					'label' 							=> esc_html__( 'Include Current Author only', 'sonaar-music' ),
					'type' 								=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 							=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 						=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 						=> 'true',
					'default' 							=> '',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'playlist_source', 
								'operator' => '==',
								'value' => 'from_cat'
							],
						]
					]
				]
			);
			$this->add_control(
				'reverse_tracklist',
				[
					'label' 							=> esc_html__( 'Reverse Tracklist', 'sonaar-music' ),
					'type' 								=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 							=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 						=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 						=> 'yes',
					'default' 							=> '',
					'separator'							=> 'before',
					/*'condition' 					=> [
						'playlist_source!' 	=> 'from_elementor',
					],*/
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'playlist_source', 
								'operator' => '!=',
								'value' => 'from_elementor'
							],
							[
								'name' => 'playlist_show_playlist', 
								'operator' => '==',
								'value' => 'yes'
							],
							[
								'name' => 'order', 
								'operator' => '!=',
								'value' => 'ASC'
							],
						]
					]
				]
			);
			$this->end_controls_section();
		}

		if ( !function_exists( 'run_sonaar_music_pro' ) ){
			$this->start_controls_section(
				'go_pro_content',
				[
					'label' 						=> esc_html__( 'Go Pro', 'sonaar-music' ),
					'tab'   						=> Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'sonaar_go_pro',
				[
					'type' 							=> \Elementor\Controls_Manager::RAW_HTML,
					'raw' 							=> 	'<div class="sr_gopro elementor-nerd-box sonaar-gopro">' .
														'<i class="sricons-logo" aria-hidden="true"></i>
															<div class="elementor-nerd-box-title">' .
																__( 'Meet the MP3 Audio Player PRO', 'sonaar-music' ) .
															'</div>
															<div class="elementor-nerd-box-message">' .
																__( 'Our PRO version lets you use Elementor\'s & Gutenberg Editor to customize the look and feel of the player in real-time! Over 100+ options available!', 'sonaar-music' ) .
															'</div>
															<div class="srp_promo_plan_heading"><i class="sricons-logo" aria-hidden="true"></i>MP3 Player Pro - Starter</div>
															<ul>
																<li><i class="eicon-check"></i>FULL access to Elementor Style Editor</li>
																<li><i class="eicon-check"></i>Sticky Player with Soundwave</li>
																<li><i class="eicon-check"></i>Real-time Animated Audio Visualizer Spectrum</li>
																<li><i class="eicon-check"></i>Display Soundwaves in the tracklist</li>
																<li><i class="eicon-check"></i>Enable Carousel & 3D Coverflow</li>
																<li><i class="eicon-check"></i>Share tracks on Social Media, SMS, Email</li>
																<li><i class="eicon-check"></i>Display thumbnail beside each tracks</li>
																<li><i class="eicon-check"></i>Input feed URL directly in the widget</li>
																<li><i class="eicon-check"></i>Volume Control</li>
																<li><i class="eicon-check"></i>Shuffle Tracks</li>
																<li><i class="eicon-check"></i>Build dynamic playlist</li>
																<li><i class="eicon-check"></i>Tracklist & Grid View</li>
																<li><i class="eicon-check"></i>Karaoke! Add Live Lyrics to each tracks.</li>
																<li><i class="eicon-check"></i>Tool to import/bulk create playlists</li>
																<li><i class="eicon-check"></i>Statistic Reports</li>
																<li><i class="eicon-check"></i>1 year of support via live chat</li>
																<li><i class="eicon-check"></i>1 year of plugin updates</li>
																<li><i class="eicon-check"></i>1 website usage</li>
															</ul>
															<div class="srp_promo_plan_heading"><i class="sricons-logo" aria-hidden="true"></i>MP3 Player Pro - Business</div>
															<ul>
																<li><i class="eicon-check"></i>Everything in the Starter plan, plus:</li>
																<li><i class="eicon-check"></i>Full WooCommerce Support</li>
																<li><i class="eicon-check"></i>Support for ACF, JetEngine, etc</li>
																<li><i class="eicon-check"></i>Add to Favorites Button & Favorite Playlist widget</li>
																<li><i class="eicon-check"></i>Send an Offer / Negotiate Buttons</li>
																<li><i class="eicon-check"></i>Ask for Email to Access Download</li>
																<li><i class="eicon-check"></i>Search Bar Elementor Widget</li>
																<li><i class="eicon-check"></i>Filter Dropdown Elementor Widget</li>
																<li><i class="eicon-check"></i>Chips & Tags Elementor Widget</li>
																<li><i class="eicon-check"></i>Tracklist Custom Fields</li>
																<li><i class="eicon-check"></i>Tracklist Pagination</li>
																<li><i class="eicon-check"></i>Lazy Load option for Optimal Performance</li>
																<li><i class="eicon-check"></i>Audio Preview, Watermarks & Audio Ads</li>
																<li><i class="eicon-check"></i>Dynamic Visibility for Download, Favorite & Share Buttons</li>
															</ul>
															<div class="srp_promo_plan_heading"><i class="sricons-logo" aria-hidden="true"></i>MP3 Player Pro - Unlimited</div>
															<ul>
																<li><i class="eicon-check"></i>Everything in the Business plan, plus:</li>
																<li><i class="eicon-check"></i>Unlimited website usage</li>
																<li><i class="eicon-check"></i>100+ Elementor Templates Addon kit</li>
																<li><i class="eicon-check"></i>Pre-designed Audio Player Elementor Templates</li>
															</ul>
															<div class="srp_promo_plan_heading"><i class="sricons-logo" aria-hidden="true"></i>MP3 Player Pro - Lifetime</div>
															<ul>
																<li><i class="eicon-check"></i>Everything in the Unlimited plan, plus:</li>
																<li><i class="eicon-check"></i>Lifetime plugin updates</li>
																<li><i class="eicon-check"></i>Lifetime support</li>
																<li><i class="eicon-check"></i>Lifetime Fun!</li>
															</ul>
															<a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-go-pro" href="https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin" target="_blank">' .
															__( 'Go Pro', 'elementor' ) .
															'</a>
														</div>',
				]
			);
		$this->end_controls_section();
		}

		if ( function_exists( 'run_sonaar_music_pro' ) ){

			/**
			* STYLE: CAROUSEL
			* -------------------------------------------------
			*/
			$this->start_controls_section(
	            'carousel_parameters_section',
	            [
	                'label'                 		=> esc_html__( 'Carousel: Parameters', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'condition' => [
						'slider!' 	=> ''
					],
	            ]
			);
			$this->add_control(
				'slide_source',
				[
					'label'					=> esc_html__( 'One slide per', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'post'     =>  esc_html__('Post', 'sonaar-music'),
						'track'    =>  esc_html__('Track', 'sonaar-music'),
					],
					'default' 				=> 'post',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
								'relation' => 'or',
								'terms' => [
					            	[
										'name' => 'playlist_source',
					            		'operator' => '==',
					            		'value' => 'from_cpt'
					        		],
									[
										'name' => 'playlist_source',
										'operator' => '==',
										'value' => 'from_cat'
									],
								]
							]
					    ]
					]   
				]
			);
			$this->add_control(
				'slider_effect',
				[
					'label'					=> esc_html__( 'Carousel Type', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'slide'     =>  esc_html__('Slide', 'sonaar-music'),
						'coverflow'   	=>  esc_html__('Coverflow', 'sonaar-music'),
					],
					'default' 				=> 'coverflow',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_responsive_control(
				'slide_to_show',
				[
					'label'					=> esc_html__( 'Slides per View', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'1'     =>  esc_html__('1', 'sonaar-music'),
						'2'   	=>  esc_html__('2', 'sonaar-music'),
						'3'    	=>  esc_html__('3', 'sonaar-music'),
						'4'    	=>  esc_html__('4', 'sonaar-music'),
						'5'    	=>  esc_html__('5', 'sonaar-music'),
						'6'    	=>  esc_html__('6', 'sonaar-music'),
						'7'    	=>  esc_html__('7', 'sonaar-music'),
						'8'    	=>  esc_html__('8', 'sonaar-music'),
						'9'    	=>  esc_html__('9', 'sonaar-music'),
						'10'    =>  esc_html__('10', 'sonaar-music'),
					],
					'default' 				=> '3',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slide_transition_duration',
				[
					
					'label' 						=> esc_html__( 'Transition Duration', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 0,
							'max' 				=> 1000,
						],
					],
					'default' 					=> [
						'size' 					=> 300,
					],
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slider_space_between',
				[
					
					'label' 						=> esc_html__( 'Space Between Slides', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 0,
							'max' 				=> 100,
						],
					],
					'default' 					=> [
						'size' 					=> 5,
					],
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slider_loop',
				[
					
					'label' 					=> esc_html__( 'Infinite Loop', 'sonaar-music' ),
					'description' 				=> esc_html__( 'Because of nature of how the loop mode works (it will rearrange slides), total number of slides must be >= slidesPerView * 2 (eg: if SlidePerView is set to 3, make sure you have at least 6 different slides)', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
				]
			);
			$this->add_control(
				'slide_autoplay',
				[
					
					'label' 						=> esc_html__( 'Autoplay', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'separator'						=> 'before',
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slide_delay',
				[
					'label' => esc_html__( 'Scroll Speed (ms)', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					//'max' => 100000,
					'step' => 1,
					'default' => 3000,
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slide_autoplay',
					            'operator' => '==',
					            'value' => 'yes'
					        ],
					    ]
					]   
				]
			);
			$this->add_control(
				'slide_pause_on_hover',
				[
					'label' 						=> esc_html__( 'Pause on hover', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slide_autoplay',
					            'operator' => '==',
					            'value' => 'yes'
					        ],
					    ]
					]   
				]
			);
			$this->add_control(
				'slide_disable_on_interaction',
				[
					'label' 						=> esc_html__( 'Disable on interaction', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> 'yes',
					'return_value' 				=> 'yes',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slide_autoplay',
					            'operator' => '==',
					            'value' => 'yes'
					        ],
					    ]
					]   
				]
			);
			$this->add_responsive_control(
				'slide_direction',
				[
					'label'					=> esc_html__( 'Direction', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'right'     =>  esc_html__('Right', 'sonaar-music'),
						'left'   	=>  esc_html__('Left', 'sonaar-music'),
					],
					'default' 				=> 'right',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slide_autoplay',
					            'operator' => '==',
					            'value' => 'yes'
					        ],
					    ]
					]   
				]
			);
			$this->add_control(
				'hr13',
				[
					'type' 							=> \Elementor\Controls_Manager::DIVIDER,
					'style' 						=> 'thick',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slide_keyboard_ctrl',
				[
					
					'label' 						=> esc_html__( 'Keyboard Control', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slide_mousewheel_ctrl',
				[
					
					'label' 						=> esc_html__( 'Mousewheel Control', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'invert_mousewheel_ctrl',
				[
					
					'label' 						=> esc_html__( 'Invert Mousewheel Control', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'condition' => [
						'slider!' 	=> '',
						'slide_mousewheel_ctrl' 	=> 'yes'
					],
				]
			);
			$this->add_control(
				'slide_shadows',
				[
					
					'label' 						=> esc_html__( 'Slide Shadows', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> 'yes',
					'return_value' 				=> 'yes',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slider_effect',
					            'operator' => '==',
					            'value' => 'coverflow'
					        ],
					    ]
					]   
				]
			);
			
			$this->add_control(
				'slide_reflect_fx',
				[
					
					'label' 						=> esc_html__( 'Reflection FX', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> 'yes',
					'return_value' 				=> 'yes',
					'selectors' 					=> [
						'{{WRAPPER}} .srp_swiper-album-art' => '-webkit-box-reflect:below {{slide_reflect_fx_distance.SIZE}}px -webkit-linear-gradient(bottom, rgba(0,0,0,0.5) 0px, rgba(0,0,0,0)  {{slide_reflect_fx_height.SIZE}}px);',				
					],
				]
			);
			$this->add_control(
				'slide_reflect_fx_distance',
				[
					
					'label' 						=> esc_html__( 'Reflection Distance', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 0,
							'max' 				=> 30,
						],
					],
					'default' 					=> [
						'size' 					=> 1,
					],
					'condition' => [
						'slide_reflect_fx' 	=> 'yes'
					],
					'selectors' 					=> [
						'{{WRAPPER}} .srp_swiper-album-art' => '',				
					],
				]
			);
			$this->add_control(
				'slide_reflect_fx_height',
				[
					
					'label' 						=> esc_html__( 'Reflection Height', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 5,
							'max' 				=> 300,
						],
					],
					'default' 					=> [
						'size' 					=> 75,
					],
					'selectors' 					=> [
						'{{WRAPPER}} .srp_swiper-album-art' => '',				
					],
					'condition' => [
						'slide_reflect_fx' 	=> 'yes'
					],
				]
			);
			$this->add_control(
				'slide_depth',
				[
					
					'label' 						=> esc_html__( 'Depth Perspective', 'sonaar-music' ),
					'separator'						=> 'before',
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 0,
							'max' 				=> 1000,
						],
					],
					
					'default' 					=> [
						'size' 					=> 200,
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slider_effect',
					            'operator' => '==',
					            'value' => 'coverflow'
					        ],
					    ]
					]   
				]
			);
			$this->add_control(
				'slide_rotate',
				[
					
					'label' 						=> esc_html__( 'Rotate', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 0,
							'max' 				=> 360,
						],
					],
					'default' 					=> [
						'size' 					=> 15,
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slider_effect',
					            'operator' => '==',
					            'value' => 'coverflow'
					        ],
					    ]
					]   
				]
			);
			$this->add_control(
				'slide_stretch',
				[
					
					'label' 						=> esc_html__( 'Stretch', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> -500,
							'max' 				=> 1000,
						],
					],
					'default' 					=> [
						'size' 					=> 80,
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slider_effect',
					            'operator' => '==',
					            'value' => 'coverflow'
					        ],
					    ]
					]   
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
	            'carousel_dimension_section',
	            [
	                'label'                 		=> esc_html__( 'Carousel: Dimensions', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'condition' => [
						'slider!' 	=> ''
					],
	            ]
			);
			$this->add_responsive_control(
				'slide_artwork_bg_size',
				[
					'label'					=> esc_html__( 'Image Size', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'cover'     =>  esc_html__('Cover', 'sonaar-music'),
						'contain'   	=>  esc_html__('Keep aspect ratio', 'sonaar-music'),
					],
					'default' 				=> 'cover',
					'selectors' 					=> [
						'{{WRAPPER}} .srp_swiper-album-art' => 'background-size:{{VALUE}};',			
					],
					
				]
			);
			$this->add_responsive_control(
				'slider_width',
				[
					
					'label' 						=> esc_html__( 'Carousel Width', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 5,
							'max' 				=> 300,
						],
					],
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 2000,
							'step' => 1,
						],
						'%' => [
							'min' => 1,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'default' 					=> [
						'size' 					=> 100,
						'unit' 					=> '%',
					],
					'selectors' 					=> [
						'{{WRAPPER}} .srp_swiper-wrap' => 'width:{{SIZE}}{{UNIT}};',			
					],
				]
			);
			$this->add_responsive_control(
				'slide_artwork_size',
				[
					
					'label' 						=> esc_html__( 'Carousel Height', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 0,
							'max' 				=> 1000,
						],
					],
					'selectors' 				=> [
						'{{WRAPPER}} .srp_swiper-album-art' => 'height: {{SIZE}}{{UNIT}};',
				],
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
	            'carousel_slides_content_section',
	            [
	                'label'                 		=> esc_html__( 'Carousel: Slides Content', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'condition' => [
						'slider!' 	=> ''
					],
	            ]
			);
			$this->add_control(
				'slider_play_on_hover',
				[
					
					'label' 						=> esc_html__( 'Show Play Icon on Hover', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> 'No',
					'return_value' 				=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slider_content_on_hover',
				[
					
					'label' 						=> esc_html__( 'Show Text Content on Hover', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> 'No',
					'return_value' 				=> 'yes',
					'condition' => [
						'slider_move_content_below_image' 	=> ''
					],
				]
			);
			$this->add_control(
				'slider_content_on_active',
				[
					
					'label' 						=> esc_html__( 'Show Text Content on Slide Focus', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> 'No',
					'return_value' 				=> 'yes',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'slider',
					            'operator' => '!=',
					            'value' => ''
					        ],
							[
					            'name' => 'slider_effect',
					            'operator' => '==',
					            'value' => 'coverflow'
					        ],
					    ]
					] 
				]
			);
			$this->add_control(
				'slider_move_content_below_image',
				[
					'label' 						=> esc_html__( 'Move content below artwork', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'separator'						=> 'before',
					'default'						=> '',
					'return_value' 					=> 'yes',
				]
			);
			$this->add_control(
				'slider_titles_order',
				[
					'label' 						=> esc_html__( 'Swap Titles Order', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_hide_album_title', 
								'operator' => '!=',
								'value' => 'yes'
							],
							[
								'name' => 'slider_hide_track_title', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
				]
			);
			$this->add_control(
				'slide_font_color',
				[
					'label' => esc_html__( 'Text Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'separator'						=> 'before',
					'default'               		=> '',
					'condition' => [
						'slider!' 	=> ''
					],
					'selectors'             => [
						'{{WRAPPER}} .srp_swiper' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'slide_text_color',
					'selector' => '{{WRAPPER}} .srp_swiper-titles',
				]
			);
			$this->add_responsive_control(
				'slide_horizontal_position',
				[
					'label' 						=> esc_html__( 'Horizontal Position', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> 'center',
					'selectors_dictionary' => [
						'flex-start' => 'flex-start;text-align:left',
						'center' => 'center;text-align:center',
						'flex-end' => 'flex-end;text-align:right',
					],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper-titles' => 'align-items: {{VALUE}};',

					],
					'condition' => [
						'slider!' 	=> ''
					],
					
				]
			);
			$this->add_responsive_control(
				'slide_vertical_position',
				[
					'label' 						=> esc_html__( 'Vertical Position', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-end'    					=> [
							'title' 				=> esc_html__( 'Bottom', 'elementor' ),
							'icon' 					=> 'eicon-v-align-bottom',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Middle', 'elementor' ),
							'icon' 					=> 'eicon-v-align-middle',
						],
						'flex-start' 					=> [
							'title' 				=> esc_html__( 'Top', 'elementor' ),
							'icon' 					=> 'eicon-v-align-top',
						],
					],
					'default' 						=> 'flex-end',
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper-titles' => 'justify-content: {{VALUE}};',
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'slider_hide_album_title', 
										'operator' => '!=',
										'value' => 'yes'
									],
									[
										'name' => 'slider_hide_track_title', 
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							],
							[
								'name' => 'slider_move_content_below_image', 
								'operator' => '==',
								'value' => ''
							]
						]
					],
				]
			);
			$this->add_control(
				'slide_control_color',
				[
					'label' => esc_html__( 'Control Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'separator'						=> 'before',
					'default'               		=> '',
					'condition' => [
						'slider!' 	=> ''
					],
					'selectors'             => [
						'{{WRAPPER}} .srp_swiper .sricon-play' => 'color: {{VALUE}}',
						'{{WRAPPER}} .srp_swiper .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .iron-audioplayer .srp_swiper-control .srp_play' => 'border-color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'slider_controls_scale',
				[
					'label' => esc_html__( 'Control Size Scale', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::NUMBER,
					'min' 							=> 0,
					'max' 							=> 10,
					'step' 							=> 0.1,
					'default' 						=> 1,
					'selectors' => [
						'{{WRAPPER}} .srp_swiper-album-art .srp_swiper-control' => 'transform:scale({{SIZE}});',
					],
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slider_control_orientation_h',
				[
					'label' => esc_html__( 'Horizontal Orientation', 'elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'toggle' => false,
					'default' => 'start',
					'options' => [
						'start' => [
							'title' => 'left',
							'icon' => 'eicon-h-align-left',
						],
						'end' => [
							'title' => 'right',
							'icon' => 'eicon-h-align-right',
						],
					],
					'classes' => 'elementor-control-start-end',
					'render_type' => 'ui',
				]
			);
			$this->add_responsive_control(
				'slider_control_offset_x',
				[
					'label' 					=> esc_html__( 'Offset', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -600,
							'max' => 600,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' 				=> [
							'{{WRAPPER}} .srp_swiper-album-art .srp_swiper-control' => 'left: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'slider_control_orientation_h!' => 'end',
					],
				]
			);
			$this->add_responsive_control(
				'slider_control_offset_x_end',
				[
					'label' 					=> esc_html__( 'Offset', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -600,
							'max' => 600,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' 				=> [
							'{{WRAPPER}} .srp_swiper-album-art .srp_swiper-control' => 'right: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'slider_control_orientation_h' => 'end',
					],
				]
			);
			$this->add_control(
				'slider_control_orientation_y',
				[
					'label' => esc_html__( 'Vertical Orientation', 'elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'toggle' => false,
					'default' => 'start',
					'options' => [
						'start' => [
							'title' => esc_html__( 'Top', 'elementor' ),
							'icon' => 'eicon-v-align-top',
						],
						'end' => [
							'title' => esc_html__( 'Bottom', 'elementor' ),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'render_type' => 'ui',
				]
			);
			$this->add_responsive_control(
				'slider_control_offset_y',
				[
					'label' 					=> esc_html__( 'Offset', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -600,
							'max' => 600,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' 				=> [
							'{{WRAPPER}} .srp_swiper-album-art .srp_swiper-control' => 'top: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'slider_control_orientation_y!' => 'end',
					],
				]
			);
			$this->add_responsive_control(
				'slider_control_offset_y_end',
				[
					'label' 					=> esc_html__( 'Offset', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -600,
							'max' => 600,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' 				=> [
							'{{WRAPPER}} .srp_swiper-album-art .srp_swiper-control' => 'bottom: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'slider_control_orientation_y' => 'end',
					],
				]
			);
			$this->add_control(
				'slider_hide_album_title',
				[
					'label' 						=> sprintf( esc_html__( 'Hide %1$s Title', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('playlist/podcast')) ),
					'type' 							=> Controls_Manager::SWITCHER,
					'separator'						=> 'before',
					'default'						=> '',
					'return_value' 					=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'slider_album_title_typography',
					'label' => sprintf( esc_html__( '%1$s Title Typography', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('playlist/podcast')) ),
					'global' => [
								'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_hide_album_title', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selector' 	=> '{{WRAPPER}} .srp_swiper-title',
				]
			);
			$this->add_control(
				'slider_album_title_ellipse',
				[
					'label' 						=> sprintf( esc_html__( '%1$s Title No Wrap', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('playlist/podcast')) ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_hide_album_title', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selectors' 				=> [
						'{{WRAPPER}} .srp_swiper-title' => 'text-overflow: ellipsis; overflow: hidden; white-space: nowrap; width: 100%;',
					],
				]
			);
			$this->add_control(
				'slider_hide_track_title',
				[
					'label' 						=> sprintf( esc_html__( 'Hide %1$s Title', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::SWITCHER,
					'separator'						=> 'before',
					'default'						=> '',
					'return_value' 					=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'slider_track_title_typography',
					'label' => sprintf( esc_html__( '%1$s Title Typography', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_hide_track_title', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selector' 	=> '{{WRAPPER}} .srp_swiper-track-title',
				]
			);
			$this->add_control(
				'slider_track_title_ellipse',
				[
					'label' 						=> sprintf( esc_html__( '%1$s Title No Wrap', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_hide_track_title', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selectors' 				=> [
						'{{WRAPPER}} .srp_swiper-track-title' => 'text-overflow: ellipsis; overflow: hidden; white-space: nowrap; width: 100%;',
					],
				]
			);
			$this->add_control(
				'slider_hide_artist',
				[
					'label' 						=> esc_html__( 'Hide Artist Name', 'sonaar-music' ), 
					'type' 							=> Controls_Manager::SWITCHER,
					'separator'						=> 'before',
					'default'						=> '',
					'return_value' 					=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'slider_artist_typography',
					'label' 						=> 	esc_html__( 'Artist Name Typography', 'sonaar-music' ), 
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_hide_artist', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selector' 	=> '{{WRAPPER}} .srp_swiper-track-artist',
				]
			);
			$this->add_responsive_control(
				'slide_text_padding',
				[
					'label' 						=> esc_html__( 'Content Box Padding', 'sonaar-music' ),
					'separator'						=> 'before',
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper-titles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'slider_hide_album_title', 
										'operator' => '!=',
										'value' => 'yes'
									],
									[
										'name' => 'slider_hide_track_title', 
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							]
						]
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 							=> 'slider_artwork_border',
					'selector' 						=> '{{WRAPPER}} .srp_swiper-album-art',
					'separator'						=> 'before',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_responsive_control(
				'slider_artwork_radius',
				[
					'label' 						=> esc_html__( 'Image Radius', 'elementor' ),
					'separator'						=> 'before',
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px' ],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper-album-art' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' 							=> 'slide_background',
					'separator'						=> 'before',
					'types' 						=> [ 'classic', 'gradient'],
					'exclude' 						=> [ 'image' ],
					'selector' 						=> '{{WRAPPER}} .swiper-slide',
					'fields_options' => [
						'background' => [
							'label' => esc_html__('Slide Background Color Type', 'pixel-gallery'),
						],
						'color' => [
							'label' 				=> esc_html__( 'Slide Background Color', 'elementor-sonaar' ),
						],
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' 							=> 'slide_background_overlay',
					'types' 						=> [ 'classic', 'gradient'],
					'exclude' 						=> [ 'image' ],
					'selector' 						=> '{{WRAPPER}} .srp_swiper.swiper-coverflow .swiper-slide:not(.swiper-slide-active) .srp_swiper-album-art .srp_swiper_overlay:before, {{WRAPPER}} .srp_swiper:not(.swiper-coverflow) .swiper-slide .srp_swiper-album-art .srp_swiper_overlay:before',
					'fields_options' => [
						'background' => [
							'label' => esc_html__('Slide Overlay Color Type', 'pixel-gallery'),
						],
						'color' => [
							'label' 				=> esc_html__( 'Slide Overlay Color', 'elementor-sonaar' ),
						],
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' 							=> 'slide_background__overlay_hover',
					'types' 						=> [ 'classic', 'gradient'],
					'exclude' 						=> [ 'image' ],
					'selector' 						=> '{{WRAPPER}} .swiper-slide .srp_swiper-album-art .srp_swiper_overlay:after',
					'fields_options' => [
						'background' => [
							'label' => esc_html__('Slide Overlay Hover Color Type', 'pixel-gallery'),
						],
						'color' => [
							'label' 				=> esc_html__( 'Slide Overlay Hover Color', 'elementor-sonaar' ),
						],
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 							=> 'slide_border',
					'separator'						=> 'before',
					'selector' 						=> '{{WRAPPER}} .swiper-slide',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_responsive_control(
				'slide_radius',
				[
					'label' 						=> esc_html__( 'Slide Radius', 'elementor' ),
					'separator'						=> 'before',
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 300,
						],
					],
					'condition' => [
						'slider!' 	=> ''
					],
					'selectors' 					=> [
													'{{WRAPPER}} .swiper-slide' => 'border-radius: {{SIZE}}px;',
					],
				]
			);
			$this->add_responsive_control(
				'slide_padding',
				[
					'label' 						=> esc_html__( 'Slide Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
	            'carousel_arrows_section',
	            [
	                'label'                 		=> esc_html__( 'Carousel: Arrows Navigation', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'condition' => [
						'slider!' 	=> ''
					],
	            ]
			);
			$this->add_control(
				'hide_slider_navigation',
				[
					'label' 						=> esc_html__( 'Hide Arrows', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slide_navigation_color',
				[
					'label' => esc_html__( 'Arrow Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'separator'						=> 'before',
					'default'               		=> '',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selectors'             => [
						'{{WRAPPER}} .srp_swiper-button-next, {{WRAPPER}} .srp_swiper-button-prev' => 'color: {{VALUE}}',
						'{{WRAPPER}} .srp_arrow_round.srp_swiper-button-prev, {{WRAPPER}} .srp_arrow_round.srp_swiper-button-next' => 'border-color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'slide_navigation_color_hover',
				[
					'label' => esc_html__( 'Color On Hover', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selectors'             => [
						'{{WRAPPER}} .srp_swiper-button-next:hover, {{WRAPPER}} .srp_swiper-button-prev:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .srp_arrow_round.srp_swiper-button-prev:hover, {{WRAPPER}} .srp_arrow_round.srp_swiper-button-next:hover' => 'border-color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'slider_arrow_style',
				[
					'label'					=> esc_html__( 'Arrow Style', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'floated'     =>  esc_html__('Floated', 'sonaar-music'),
						'round'   	=>  esc_html__('Round', 'sonaar-music'),
					],
					'default' 				=> 'floated',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
				]
			);
			$this->add_responsive_control(
				'slider_arrow_size',
				[
					
					'label' 						=> esc_html__( 'Arrow Size', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 5,
							'max' 				=> 100,
						],
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selectors' 					=> [
						'{{WRAPPER}} .srp_swiper-button-next:not(.srp_arrow_round), {{WRAPPER}} .srp_swiper-button-prev:not(.srp_arrow_round)' => 'font-size: {{SIZE}}px; height: {{SIZE}}px; width: calc({{SIZE}}px/ 44 * 27);',
						'{{WRAPPER}} .srp_arrow_round.srp_swiper-button-next, {{WRAPPER}} .srp_arrow_round.srp_swiper-button-prev' => 'font-size: {{SIZE}}px !important;',
					],
				]
			);
			$this->add_responsive_control(
				'slider_circle_arrow_size',
				[
					
					'label' 						=> esc_html__( 'Circle Size', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 10,
							'max' 				=> 100,
						],
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							],
							[
								'name' => 'slider_arrow_style', 
								'operator' => '==',
								'value' => 'round'
							]
						]
					],
					'selectors' 					=> [
						'{{WRAPPER}} .srp_arrow_round.srp_swiper-button-next, {{WRAPPER}} .srp_arrow_round.srp_swiper-button-prev' => 'width: calc({{SIZE}}px + 20px); height: calc({{SIZE}}px + 20px);',
					],
				]
			);

			$this->add_responsive_control(
				'slider_arrow_border_width',
				[
					
					'label' 						=> esc_html__( 'Border Width', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 1,
							'max' 				=> 20,
						],
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							],
							[
								'name' => 'slider_arrow_style', 
								'operator' => '==',
								'value' => 'round'
							]
						]
					],
					'selectors' 					=> [
						'{{WRAPPER}} .srp_arrow_round.srp_swiper-button-next, {{WRAPPER}} .srp_arrow_round.srp_swiper-button-prev' => 'border-width: {{SIZE}}px;',
					],
				]
			);
			$this->add_control(
				'slider_navigation_placement',
				[
					'label'					=> esc_html__( 'Placement', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'separator'				=> 'before',
					'label_block'			=> true,
					'options' 				=> [
						'inside'     =>  esc_html__('Inside', 'sonaar-music'),
						'outside'   	=>  esc_html__('Outside', 'sonaar-music'),
					],
					'default' 				=> 'inside',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
				]
			);
			$this->add_control(
				'slider_navigation_grouped',
				[
					
					'label' 						=> esc_html__( 'Collapse Arrows', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> 'No',
					'return_value' 				=> 'yes',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_navigation_placement', 
								'operator' => '==',
								'value' => 'outside'
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							],
							[
								'name' => 'slider_navigation_vertical_alignment', 
								'operator' => '!=',
								'value' => 'center'
							]
						]
					],
				]
			);
			$this->add_responsive_control(
				'slider_navigation_horizontal_alignment',
				[
					'label' 						=> esc_html__( 'Horizontal Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> 'center',
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper-navigation' => 'justify-content: {{VALUE}};',
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_navigation_placement', 
								'operator' => '==',
								'value' => 'outside'
							],
							[
								'name' => 'slider_navigation_grouped', 
								'operator' => '==',
								'value' => 'yes'
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							],
							[
								'name' => 'slider_navigation_vertical_alignment', 
								'operator' => '!=',
								'value' => 'center'
							]
						]
					],
				]
			);
			$this->add_responsive_control(
				'slider_navigation_vertical_alignment',
				[
					'label' 						=> esc_html__( 'Vertical Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'top' 					=> [
							'title' 				=> esc_html__( 'Top', 'elementor' ),
							'icon' 					=> 'eicon-v-align-top',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-v-align-middle',
						],
						'bottom'    					=> [
							'title' 				=> esc_html__( 'Bottom', 'elementor' ),
							'icon' 					=> 'eicon-v-align-bottom',
						],
					],
					'default' 						=> 'bottom',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'slider_navigation_placement', 
								'operator' => '==',
								'value' => 'outside'
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
				]
			);
			$this->add_responsive_control(
				'slider_navigation_margin',
				[
					'label' 						=> esc_html__( 'Arrow Margin', 'sonaar-music' ) . ' (px)', 
					'separator'						=> 'before',
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper-nav-v-pos-center .srp_swiper-button-prev' => 'left: calc(10px + {{LEFT}}{{UNIT}}); top: calc(50% + {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}});', //Outisde Center Prev Btn
													'{{WRAPPER}} .srp_swiper-nav-v-pos-center .srp_swiper-button-next' => 'right: calc(10px + {{RIGHT}}{{UNIT}}); top: calc(50% + {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}});', //Outisde Center Next Btn
													'{{WRAPPER}} .srp_swiper-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', //Outisde
													'{{WRAPPER}} .srp_swiper .srp_swiper-button-prev' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', //Inside
													'{{WRAPPER}} .srp_swiper .srp_swiper-button-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;', //Inside
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_navigation', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
	            'carousel_bullets_section',
	            [
	                'label'                 		=> esc_html__( 'Carousel: Bullets Navigation', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'condition' => [
						'slider!' 	=> ''
					],
	            ]
			);
			$this->add_control(
				'hide_slider_pagination',
				[
					'label' 						=> esc_html__( 'Hide Bullets', 'sonaar-music' ), 
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> 'yes',
					'return_value' 					=> 'yes',
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_control(
				'slider_active_bullet_color',
				[
					'label' => esc_html__( 'Active Bulltet Color', 'sonaar-music' ),
					'separator'						=> 'before',
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_pagination', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selectors'             => [
						'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'slider_inactive_bullet_color',
				[
					'label' => esc_html__( 'Inactive Bulltet Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_pagination', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selectors'             => [
						'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'slider_bullet_size',
				[
					
					'label' 						=> esc_html__( 'Bullet Size', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 1,
							'max' 				=> 20,
						],
					],
					'default' 					=> [
						'size' 					=> 6,
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_pagination', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
					'selectors' 					=> [
						'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}px; width: {{SIZE}}px;',
						'{{WRAPPER}} .swiper-box-pagination' => 'height: {{SIZE}}px;',
					],
				]
			);
			$this->add_control(
				'slider_pagination_placement',
				[
					'label'					=> esc_html__( 'Placement', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'separator'				=> 'before',
					'label_block'			=> true,
					'options' 				=> [
						'inside'     =>  esc_html__('Inside', 'sonaar-music'),
						'outside'   	=>  esc_html__('Outside', 'sonaar-music'),
					],
					'default' 				=> 'inside',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_pagination', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
				]
			);
			$this->add_responsive_control(
				'slider_pagination_margin',
				[
					'label' 						=> esc_html__( 'Bullet Margin', 'sonaar-music' ) . ' (px)', 
					'separator'						=> 'before',
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
														'{{WRAPPER}} div:not(.swiper-box-pagination)>.swiper-pagination, {{WRAPPER}} .swiper-box-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
														'{{WRAPPER}} .swiper-box-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
													],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'slider', 
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'hide_slider_pagination', 
								'operator' => '!=',
								'value' => 'yes'
							]
						]
					],
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
	            'carousel_wrapper_section',
	            [
	                'label'                 		=> esc_html__( 'Carousel: Wrapper', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'condition' => [
						'slider!' 	=> ''
					],
	            ]
			);
			$this->add_responsive_control(
				'slider_overflow',
				[
					'label'					=> esc_html__( 'Carousel Overflow', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'clip'    		=>  esc_html__('Hidden', 'sonaar-music'),
						'visible'   	=>  esc_html__('Visible', 'sonaar-music'),
					],
					'default' 				=> 'clip',
					'selectors'             => [
						'{{WRAPPER}} .srp_swiper.swiper' => 'overflow-x: {{VALUE}}',
					],
					'condition' => [
						'slider_loop' 	=> ''
					],
				]
			);
			$this->add_control(
				'slider_background',
				[
					'label' => esc_html__( 'Carousel Background Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             => [
						'{{WRAPPER}} .srp_swiper' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'slider_horizontal_position',
				[
					'label' 						=> esc_html__( 'Carousel Position', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'left'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'right' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> 'center',
					'selectors_dictionary' => [
						'left' => 'margin-left: 0; margin-right: auto;',
						'center' => 'margin-left: auto; margin-right: auto;',
						'right' => 'margin-left: auto; margin-right: 0;',
					],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper-wrap' => '{{VALUE}}',

					],
					'condition' => [
						'slider!' 	=> ''
					],
					
				]
			);
			$this->add_responsive_control(
				'slider_padding',
				[
					'label' 						=> esc_html__( 'Carousel Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->add_responsive_control(
				'slider_margin',
				[
					'label' 						=> esc_html__( 'Carousel Margin', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_swiper-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'slider!' 	=> ''
					],
				]
			);
			$this->end_controls_section();

			/**
			* STYLE: ARTWORK
			* -------------------------------------------------
			*/
			$this->start_controls_section(
	            'artwork_style',
	            [
	                'label'                 		=> esc_html__( 'Mini Player: Image Cover', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '==',
										'value' => 'skin_float_tracklist'
									],
									[
										'name' => 'playlist_hide_artwork', 
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '==',
										'value' => 'skin_boxed_tracklist'
									],
									[
										'name' => 'playlist_show_soundwave', 
										'operator' => '!=',
										'value' => 'yes'
									],
									[
										'name' => 'playlist_hide_artwork', 
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							]
						]
					]
	            ]
			);
			$this->add_control(
				'artwork_main_heading',
				[
					'label' 						=> esc_html__( 'Cover Image', 'sonaar-music' ),
					'type' 							=> Controls_Manager::HEADING,
				]
			);
			$this->add_control(
				'artwork_set_background_hideMainImage',
				[
					'label' 					=> esc_html__( 'Hide Cover Image in Front', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'selectors' 					=> [
						'{{WRAPPER}} .iron-audioplayer .sonaar-Artwort-box' => 'display:none;',
						'{{WRAPPER}} .iron-audioplayer .srp_player_boxed' => 'display:block;',			
					],
					'condition' 					=> [
						'artwork_set_background' 	=> 'yes',
					],
				]
			);
			$this->add_responsive_control(
				'artwork_width',
				[
					'label' 						=> esc_html__( 'Image Width', 'sonaar-music' ) . ' (px)',
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'min' 					=> 1,
							'max' 					=> 1024,
						],
						'%' 						=> [
							'min' 					=> 1,
							'max' 					=> 100,
						],
					],
					'size_units' 					=> [ 'px', '%' ],	
					'default' 						=> [
							'unit' => 'px',
							'size' => 300,
							],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_float_tracklist"] .album .album-art' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist',
						'artwork_set_background_hideMainImage!' => 'yes',
					],
				]
			);
			$this->add_responsive_control(
				'boxed_artwork_width',
				[
					'label' 						=> esc_html__( 'Image Width', 'sonaar-music' ) . ' (px)',
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'min' 					=> 1,
							'max' 					=> 450,
						],
					],
					'default' 						=> [
							'unit' => 'px',
							'size' => 160,
							],
					'selectors' 					=> [
						'{{WRAPPER}} .iron-audioplayer:not(.sonaar-no-artwork) .srp_player_grid' => 'grid-template-columns: {{SIZE}}px 1fr;',
						'{{WRAPPER}} .srp_player_boxed .album-art' => 'width: {{SIZE}}px; max-width: {{SIZE}}px;',
						'{{WRAPPER}} .srp_player_boxed .sonaar-Artwort-box' => 'min-width: {{SIZE}}px;'
					],	
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist',
						'artwork_set_background_hideMainImage!' => 'yes',
					],
				]
			);
			$this->add_responsive_control(
				'artwork_padding',
				[
					'label' 						=> esc_html__( 'Image Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .sonaar-grid .album' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist',
						'artwork_set_background_hideMainImage!' => 'yes',
					],
				]
			);
			$this->add_responsive_control(
				'slide_artwork_radius',
				[
					'label' 						=> esc_html__( 'Image Radius', 'elementor' ),
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 300,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .album .album-art img' => 'border-radius: {{SIZE}}px;',
					],
					'condition' 					=> [
						'artwork_set_background_hideMainImage!' => 'yes',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'artwork_shadow',
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .sonaar-Artwort-box  .album-art',
					'condition' 					=> [
						'artwork_set_background_hideMainImage!' => 'yes',
					],
				]
			);
			$this->add_control(
				'artwork_vertical_align',
				[
					'label' 					=> esc_html__( 'Center the Image vertically with the Tracklist', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'condition' 					=> [
						'playlist_show_playlist!' 	=> '',
						'player_layout' 	=> 'skin_float_tracklist',
						'artwork_set_background_hideMainImage!' => 'yes',
					],
					'selectors' 				=> [
												'{{WRAPPER}} .sonaar-grid' => 'align-items: center;',
						 
				 ],
				]
			);
			$this->add_control(
				'audio_player_artwork_controls_color',
				[
					'label'                 		=> esc_html__( 'Audio Player Controls over Image', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'separator'						=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer.sr_player_on_artwork .sonaar-Artwort-box .control [class*="sricon-"]' => 'color: {{VALUE}};',
													'{{WRAPPER}} .iron-audioplayer.sr_player_on_artwork .sonaar-Artwort-box .control .play' => 'border-color:{{VALUE}};'
					],
					'condition' 					=> [
						'sr_player_on_artwork' 	=> 'yes',
						'artwork_set_background_hideMainImage!' => 'yes',
					],
				]
			);
			$this->add_responsive_control(
				'audio_player_artwork_controls_scale',
				[
					
					'label' 						=> esc_html__( 'Control Size Scale', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::NUMBER,
					'min' 							=> 0,
					'max' 							=> 10,
					'step' 							=> 0.1,
					'default' 						=> 1,
					'condition' 					=> [
						'sr_player_on_artwork' 		=> 'yes',
						'artwork_set_background_hideMainImage!' => 'yes',
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer.sr_player_on_artwork .sonaar-Artwort-box .control' => 'transform:scale({{SIZE}});',
					],
				]
			);
			$this->add_control(
				'show_control_on_hover',
				[
					
					'label' 						=> esc_html__( 'Show Control On Hover', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'condition' 					=> [
						'sr_player_on_artwork' 		=> 'yes',
						'artwork_set_background_hideMainImage!' => 'yes',
					]
				]
			);
			$this->add_control(
				'image_overlay_on_hover',
				[
					'label'                		 	=> esc_html__( 'Image Overlay On Hover', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '#6666667a',
					'condition' 					=> [
						'sr_player_on_artwork' 		=> 'yes',
						'show_control_on_hover' 	=> 'yes',
						'artwork_set_background_hideMainImage!' => 'yes',
					],
					'selectors'             		=> [
													'{{WRAPPER}} .srp_show_ctr_hover .album-art:before ' => 'background: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'artwork_bg_heading',
				[
					'label' 						=> esc_html__( 'Artwork in Background', 'sonaar-music' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
				]
			);
			$this->add_control(
				'artwork_set_background',
				[
					'label' 					=> esc_html__( 'Set Artwork Image in Background', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'render_type' 				=> 'template',
					//'prefix_class'				=> 'srp_artwork_fullbackground_',
					
				]
			);
			
			$this->add_responsive_control(
				'artwork_set_background_size',
				[
					'label'					=> esc_html__( 'Image Size', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'cover'     	=> esc_html__( 'Cover', 'sonaar-music'),
						'contain'   	=> esc_html__( 'Contain', 'sonaar-music'),
						'initial' 		=> esc_html__( 'Custom', 'sonaar-music' ),
					],
					'default' 				=> 'cover',
					'selectors' 					=> [
						'{{WRAPPER}} .iron-audioplayer .srp-artworkbg' => 'background-size:{{VALUE}};',			
					],
					'condition' 					=> [
						'artwork_set_background' 	=> 'yes',
					],
					
				]
			);
			$this->add_responsive_control(
				'artwork_set_background_width',
				[
					'label' => esc_html__( 'Width', 'sonaar-music' ),
					'type' => Controls_Manager::SLIDER,
					'responsive' => true,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
					'range' => [
						'px' => [
							'max' => 1000,
						],
					],
					'default' => [
						'size' => 100,
						'unit' => '%',
					],
					'required' => true,
					'selectors' => [
						'{{WRAPPER}} .iron-audioplayer .srp-artworkbg' => 'background-size: {{SIZE}}{{UNIT}} auto',
					],
					'condition' => [
						'artwork_set_background' => 'yes',
						'artwork_set_background_size' => [ 'initial' ],
					],
				]
			);
			$this->add_responsive_control(
				'artwork_set_background_position',
				[
					'label' => esc_html_x( 'Position', 'Background Control', 'elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => '',
					'responsive' => true,
					'options' => [
						'' => esc_html_x( 'Default', 'Background Control', 'elementor' ),
						'center center' => esc_html_x( 'Center Center', 'Background Control', 'elementor' ),
						'center left' => esc_html_x( 'Center Left', 'Background Control', 'elementor' ),
						'center right' => esc_html_x( 'Center Right', 'Background Control', 'elementor' ),
						'top center' => esc_html_x( 'Top Center', 'Background Control', 'elementor' ),
						'top left' => esc_html_x( 'Top Left', 'Background Control', 'elementor' ),
						'top right' => esc_html_x( 'Top Right', 'Background Control', 'elementor' ),
						'bottom center' => esc_html_x( 'Bottom Center', 'Background Control', 'elementor' ),
						'bottom left' => esc_html_x( 'Bottom Left', 'Background Control', 'elementor' ),
						'bottom right' => esc_html_x( 'Bottom Right', 'Background Control', 'elementor' ),
						'initial' => esc_html_x( 'Custom', 'Background Control', 'elementor' ),
		
					],
					'selectors' => [
						'{{WRAPPER}} .iron-audioplayer .srp-artworkbg' => 'background-position: {{VALUE}};',
					],
					'condition' => [
						'artwork_set_background' => 'yes',
					],
				]
			);
			$this->add_responsive_control(
				'artwork_set_background_xpos',
				[
					'label' => esc_html__( 'X Position', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'responsive' => true,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
					'default' => [
						'size' => 0,
					],
					'tablet_default' => [
						'size' => 0,
					],
					'mobile_default' => [
						'size' => 0,
					],
					'range' => [
						'px' => [
							'min' => -800,
							'max' => 800,
						],
						'em' => [
							'min' => -100,
							'max' => 100,
						],
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'vw' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .iron-audioplayer .srp-artworkbg' => 'background-position: {{SIZE}}{{UNIT}} {{artwork_set_background_ypos.SIZE}}{{artwork_set_background_ypos.UNIT}}',
					],
					'condition' => [
						'artwork_set_background' => 'yes',
						'artwork_set_background_position' => 'initial',
					],
					'required' => true,
				]
			);
			$this->add_responsive_control(
				'artwork_set_background_ypos',
				[
					'label' => esc_html__( 'Y Position', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'responsive' => true,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
					'default' => [
						'size' => 0,
					],
					'tablet_default' => [
						'size' => 0,
					],
					'mobile_default' => [
						'size' => 0,
					],
					'range' => [
						'px' => [
							'min' => -800,
							'max' => 800,
						],
						'em' => [
							'min' => -100,
							'max' => 100,
						],
						'%' => [
							'min' => -100,
							'max' => 100,
						],
						'vw' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .iron-audioplayer .srp-artworkbg' => 'background-position: {{artwork_set_background_xpos.SIZE}}{{artwork_set_background_xpos.UNIT}} {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'artwork_set_background' => 'yes',
						'artwork_set_background_position' => 'initial',
					],
					'required' => true,
				]
			);
			
			$this->add_responsive_control(
				'artwork_set_background_blur',
				[
					'label' 						=> esc_html__( 'Blur Image', 'sonaar-music' ) . ' (px)',
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'min' 					=> 0,
							'max' 					=> 100,
						],
					],
					'size_units' 					=> [ 'px'],	
					'default' 						=> [
							'unit' => 'px',
							'size' => 0,
							],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .srp-artworkbg' => 'filter: blur({{SIZE}}{{UNIT}});',
					],
					'condition' 					=> [
						'artwork_set_background' 	=> 'yes',
					],
				]
			);
			$this->add_control(
				'artwork_set_background_overflow',
				[
					'label' 					=> esc_html__( 'Hide Overlow', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'selectors' 					=> [
						'{{WRAPPER}} .iron-audioplayer' => 'overflow:hidden;',			
					],
					'condition' 					=> [
						'artwork_set_background' 	=> 'yes',
					],
				]
			);
			$this->add_control(
				'artwork_gradient_bg_heading',
				[
					'label' 						=> esc_html__( 'Background Overlay', 'sonaar-music' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
					'condition' 					=> [
						'artwork_set_background' 	=> 'yes',
					],
				]
			);
			$this->add_control(
				'artwork_set_background_gradient',
				[
					'label' 					=> esc_html__( 'Enable Background Overlay', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					//'prefix_class'				=> 'srp_artwork_fullbackground_wgradient_',
					'render_type' 				=> 'template',
					'condition' 					=> [
						'artwork_set_background' 	=> 'yes',
					],
					
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'background',
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .iron-audioplayer .srp-artworkbg-gradient',
					'exclude' => [ 'image' ],
					'fields_options' => [
						'background' => [
							'default' => 'gradient',
						],
						'color' => [
							'default' => '#00000000',
						],
						'color_b' => [
							'default' => '#000000',
						],
						'color_b_stop' => [
							'default' => [
								'unit' => '%',
								'size' => 50,
							],
						],
						'gradient_angle' => [
							'default' => [
								'unit' => 'deg',
								'size' => 90,
							],
						],
					],
					'condition' 					=> [
						'artwork_set_background_gradient' 	=> 'yes',
						'artwork_set_background' 			=> 'yes',
					],
				]
			);
			$this->end_controls_section();


			/**
			 * STYLE: METADATA
			 * -------------------------------------------------
			*/
			$this->start_controls_section(
			'metadata_style',
	            [
	                'label'                			=> esc_html__( 'Mini Player: Titles & Metas', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'condition' 					=> [
						'playlist_show_soundwave!' 	=> 'yes',
						'player_layout!' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'metas_heading',
				[
					'label' 						=> esc_html__( 'Titles & Headings', 'sonaar-music' ),
					'type' 							=> Controls_Manager::HEADING,
				]
			);
			/*DEPRECATED OPTION*/
			$this->add_control(
				'title_soundwave_show',
				[
					'label' 						=> sprintf( esc_html__( 'Hide %1$s Title *DEPRECATED', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist',
						'title_soundwave_show' => 'yes',
					],
				]
			);
			$this->add_control(
				'miniplayer_meta_hide',
				[
					'label' 						=> esc_html__('Hide Headings', 'sonaar-music'),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'playlist_title_soundwave_typography',
					'label' 						=> esc_html__('Heading Typography', 'sonaar-music'),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'condition'                    => [
						'miniplayer_meta_hide!' => 'yes',
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .srp_meta',
				]
			);
			$this->add_control(
				'playlist_title_soundwave_color',
				[
					'label' 						=> esc_html__('Heading Colors', 'sonaar-music'),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'condition'                    => [
						'miniplayer_meta_hide!' => 'yes',
					],
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .srp_meta, {{WRAPPER}} .iron-audioplayer .player' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'miniplayer_meta_heading_aligmnent',
				[
					'label' 						=> esc_html__( 'Headings Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> '',
					'selectors_dictionary' => [
						'flex-start' => 'flex-start;text-align:left',
						'center' => 'center;text-align:center',
						'flex-end' => 'flex-end;text-align:right',
					],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_miniplayer_metas, {{WRAPPER}} .srp_subtitle, {{WRAPPER}} .srp_player_meta' => 'justify-content: {{VALUE}};',
					],
					'condition'                    => [
						'miniplayer_meta_hide!' => 'yes',
					],
				]
			);
			$this->add_control(
				'playlist_title_html_tag_soundwave',
				[
					'label' 						=> esc_html__('HTML Tags', 'sonaar-music'),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
						'span' => 'span',
						'p' => 'p',
					],
					'default' => 'div',
					'condition'                    => [
						'miniplayer_meta_hide!' => 'yes',
					],
				]
			);
			/*DEPRECATED OPTION*/
			$this->add_control(
				'playlist_title_soundwave_show',
				[
					'label' 						=> sprintf( esc_html__( 'Hide %1$s Title *DEPRECATED', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist',
						'playlist_title_soundwave_show' => 'yes',
					],
				]
			);
			$miniplayer_meta_repeater = new \Elementor\Repeater();
			$miniplayer_meta_repeater->add_control(
				'miniplayer_order_meta',
				[
					'label' =>  esc_html__( 'Select Meta', 'sonaar-music' ),
					'type' => Controls_Manager::SELECT,
					'options' => $this->metaOptions(),
					'default' => 'custom_heading',
				]
			);
			$miniplayer_meta_repeater->add_control(
				'miniplayer_custom_heading',
				[
					'label'       => esc_html__( 'Text', 'sonaar-music' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'dynamic' => [
						'active' => true,
					],
					'condition'                    => [
						'miniplayer_order_meta' => 'custom_heading',
					],
				]
			);
			$miniplayer_meta_repeater->add_control(
				'custom_field_key', [
					'label'     => esc_html__( 'Custom Meta Key', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'condition' => [
						'miniplayer_order_meta' => 'key',
					],
				]
			);
			$miniplayer_meta_repeater->add_control(
				'miniplayer_meta_acf',
				[
					'label'     => esc_html__( 'ACF Field', 'sonaar-music' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'default'   => '',
					'groups'    => $this->get_fields_goups( 'fields' ),
					'condition'                    => [
						'miniplayer_order_meta' => 'acf_field',
					],
				]
			);
			$miniplayer_meta_repeater->add_control(
				'miniplayer_prefix',
				[
					'label'       => esc_html__( 'Prefix Label', 'sonaar-music' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
				]
			);
			$miniplayer_meta_repeater->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'miniplayer_order_meta_typography',
					'label' 						=> esc_html__( 'Typography', 'sonaar-music' ),
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .album-player {{CURRENT_ITEM}}',
				]
			);
			$miniplayer_meta_repeater->add_control(
				'miniplayer_order_meta_color',
				[
					'label' 						=> esc_html__( 'Font Color', 'sonaar-music' ), 
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .album-player {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
					],
				]
			);
			$miniplayer_meta_repeater->add_control(
				'miniplayer_order_meta_bgcolor',
				[
					'label' 						=> esc_html__( 'Background Color', 'sonaar-music' ), 
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .album-player {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};width:fit-content;',
					],
				]
			);
			$miniplayer_meta_repeater->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 							=> 'miniplayer_order_meta_border',
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .album-player {{CURRENT_ITEM}}',
				]
			);
			$miniplayer_meta_repeater->add_control(
				'miniplayer_order_meta_radius',
				[
					'label' 						=> esc_html__( 'Radius', 'elementor' ),
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 200,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .album-player {{CURRENT_ITEM}}' => 'border-radius: {{SIZE}}px;',
					],
				]
			);
			$miniplayer_meta_repeater->add_responsive_control(
				'miniplayer_order_meta_padding',
				[
					'label' 						=> esc_html__( 'Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .album-player {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$miniplayer_meta_repeater->add_responsive_control(
				'miniplayer_order_meta_margin',
				[
					'label' 						=> esc_html__( 'Margin', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .album-player {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$miniplayer_meta_repeater->add_control(
				'miniplayer_meta_tag',
				[
					'label' => esc_html__( 'HTML Tag', 'sonaar-music' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'default' => 'Default',
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
						'span' => 'span',
						'p' => 'p',
					],
					'default' => 'default',
				]
			);
			$miniplayer_meta_repeater->add_control(
				'meta_heading_inline',
				[
					'label' 						=> esc_html__( 'Display Inline', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'selectors' 					=> [
						'{{WRAPPER}} {{CURRENT_ITEM}}' => 'display:inline-block;',
						'{{WRAPPER}} .srp_meta+{{CURRENT_ITEM}}' => 'margin-right: 0.3em;',
					],
				]
			);
			$metaTitles = $this->metaOptions();
			$this->add_control(
				'miniplayer_meta_repeater',
				[
					'label' 		=> esc_html__( 'Meta(s)', 'sonaar-music' ),
					'type' 			=> \Elementor\Controls_Manager::REPEATER,
					'prevent_empty' => false,
					'fields' 		=> $miniplayer_meta_repeater->get_controls(),
					'title_field' => '
						<# if ( "custom_heading" == miniplayer_order_meta ) { #>' . $metaTitles['custom_heading'] . ': {{{ miniplayer_custom_heading }}} <# } #> 
						<# if ( "playlist_title" == miniplayer_order_meta ) { #>' . $metaTitles['playlist_title'] . '<# } #>
						<# if ( "track_title" == miniplayer_order_meta ) { #>' . $metaTitles['track_title'] . '<# } #>		
						<# if ( "artist_name" == miniplayer_order_meta ) { #>' . $metaTitles['artist_name'] . '<# } #>
						<# if ( "duration" == miniplayer_order_meta ) { #>' . $metaTitles['duration'] . '<# } #>
						<# if ( "tags" == miniplayer_order_meta ) { #>' . $metaTitles['tags'] . '<# } #>
						<# if ( "categories" == miniplayer_order_meta ) { #>' . $metaTitles['categories'] . '<# } #>
						<# if ( "podcast_show" == miniplayer_order_meta ) { #> Podcast Show <# } #>
						<# if ( "acf_field" == miniplayer_order_meta ) { #>{{{ miniplayer_meta_acf }}} <# } #> 
						<# if ( "jetengine_field" == miniplayer_order_meta ) { #>{{{ miniplayer_meta_jetengine }}} <# } #>
						<# if ( "key" == miniplayer_order_meta ) { #>{{{ custom_field_key }}} <# } #>
						',
				
					'default' => [
						[
							'miniplayer_order_meta' => 'track_title',
						],
					],
					'condition'                    => [
						'player_layout!' => 'skin_button',
						'miniplayer_meta_hide' => '',
					],
				]
			);
			$this->add_control(
				'metadata_heading',
				[
					'label' 						=> esc_html__( 'Extra Metas', 'sonaar-music' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'metadata_typography',
					'label' 						=> esc_html__( 'Extra Metas Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} .sr_it-playlist-publish-date, {{WRAPPER}} .srp_playlist_duration, {{WRAPPER}} .srp_trackCount',
				]
			);	
			$this->add_control(
				'metadata_color',
				[
					'label'                		 	=> esc_html__( 'Extra Metas Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} .sr_it-playlist-publish-date, {{WRAPPER}} .srp_playlist_duration, {{WRAPPER}} .srp_trackCount' => 'color: {{VALUE}}',
					],
				]
			);	
			$this->add_control(
				'publishdate_btshow',
				[
					'label' 						=> esc_html__( 'Show Date in the Mini-Player', 'sonaar-music' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_publish_date', 'srmp3_settings_widget_player') ),
						'true' => esc_html__( 'Yes', 'sonaar-music' ),
						'false' => esc_html__( 'No', 'sonaar-music' ),
					],
					'default' => 'default',
					'condition' => [
						'playlist_source!' => 'from_elementor',
					],
				]
			);
			$this->add_control(
				'nb_of_track_btshow',
				[
					'label' 						=> sprintf( esc_html__( 'Show Total Number of %1$ss', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_tracks_count', 'srmp3_settings_widget_player') ),
						'true' => esc_html__( 'Yes', 'sonaar-music' ),
						'false' => esc_html__( 'No', 'sonaar-music' ),
					],
					'default' => 'default',
				]
			);
			$this->add_control(
				'playlist_duration_btshow',
				[
					'label' 						=> esc_html__( 'Show Total Playlist Time Duration', 'sonaar-music' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_meta_duration', 'srmp3_settings_widget_player') ),
						'true' => esc_html__( 'Yes', 'sonaar-music' ),
						'false' => esc_html__( 'No', 'sonaar-music' ),
					],
					'default' => 'default',
				]
			);			
			$this->add_control(
				'subtitle_heading',
				[
					'label' 						=> esc_html__( 'Post Subtitle', 'sonaar-music' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist',
					],
				]
			);
			$this->add_control(
				'player_subtitle_btshow',
				[
					'label' 						=> esc_html__( 'Hide Post Subtitle', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'none',
					'selectors' 					=> [
													'{{WRAPPER}} .srp_player_boxed .srp_subtitle' => 'display:{{VALUE}}!important;',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'player_subtitle_typography',
					'label' 						=> esc_html__( 'Post Subtitle Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'condition' 					=> [
						'player_subtitle_btshow' 			=> '',
						'player_layout' 	=> 'skin_boxed_tracklist',
					],
					'selector' 						=> '{{WRAPPER}} .srp_player_boxed .srp_subtitle',
				]
			);
			$this->add_control(
				'player_subtitle-color',
				[
					'label'                		 	=> esc_html__( 'Post Subtitle Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'condition' 					=> [
						'player_subtitle_btshow' 			=> '',
						'player_layout' 	=> 'skin_boxed_tracklist',
					],
					'selectors'             		=> [
													'{{WRAPPER}} .srp_player_boxed .srp_subtitle' => 'color: {{VALUE}}',
					],
				]
			);
			
			
			$this->end_controls_section();

			
			/**
	         * STYLE: WAVEFORM / SOUNDWAVE 
	         * -------------------------------------------------
	         */
			
			$this->start_controls_section(
	            'player',
	            [
	                'label'							=> esc_html__( 'Mini Player: Layout & Progress Bar', 'sonaar-music' ),
					'tab'							=> Controls_Manager::TAB_STYLE,
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'name' => 'player_layout', 
								'operator' => '==',
								'value' => 'skin_button'
							],
							/*[
								'name' => 'player_layout', 
								'operator' => 'in',
								'value' => ['skin_boxed_tracklist', 'skin_button']
							],*/
							[
								'name' => 'playlist_show_soundwave', 
								'operator' => '!=',
								'value' => 'yes'
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '==',
										'value' => 'skin_float_tracklist'
									],
									[
										'name' => 'playlist_show_soundwave', 
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							]
						]
					]
	            ]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'cat_description_typo',
					'label' 						=> esc_html__( 'Description/About Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'separator'						=> 'before',
					'condition' 					=> [
						'show_cat_description' 	=> '1',
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .srp_podcast_rss_description',
				]
			);
			$this->add_control(
				'cat_description_color',
				[
					'label'                 		=> esc_html__( 'Description/About  Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'condition' 					=> [
						'show_cat_description' 	=> '1',
					],
					'selectors'            			=> [
													'{{WRAPPER}} .iron-audioplayer .srp_podcast_rss_description' => 'color: {{VALUE}}',
					],
				]
			);

			
			$this->add_responsive_control(
				'player_align',
				[
					'label'						 	=> esc_html__( 'Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> '',
					'selectors' 					=> [
													'{{WRAPPER}} .album-player' => 'display: flex; justify-content: {{VALUE}};',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
						'soundwave_show_skin_button' => ''
					],
				]
			);
			$this->add_control(
				'soundwave_show',
				[
					'label' 						=> esc_html__( 'Hide Progress Bar', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'yes',
					'separator'						=> 'before',
					'condition' 					=> [
						'player_layout!' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'soundwave_show_skin_button',
				[
					'label' 						=> esc_html__( 'Show Progress Bar', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'yes',
					'separator'						=> 'before',
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
					],
				]
			);
			
			$this->add_control(
				'progress_bar_style',
				[
					'label' 				=> esc_html__( 'Progress Bar Style', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'separator'						=> 'before',
					'options' 				=> [
						'default' 			=> esc_html__( 'Default (Plugin Settings)', 'sonaar-music' ),
						'mediaElement' 		=> esc_html__( 'Waveform', 'sonaar-music' ),
						'simplebar' 		=> esc_html__( 'Simple Bar', 'sonaar-music' ),
					],
					'default' 				=> 'default',
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'soundwave_show', 
										'operator' => '==',
										'value' => ''
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '==',
										'value' => 'skin_button'
									],
									[
										'name' => 'soundwave_show_skin_button', 
										'operator' => '!=',
										'value' => ''
									]
								]
							]
						]
					],
				]
			);
			
			$this->add_control(
				'soundWave_progress_bar_color',
				[
					'label'                 		=> esc_html__( 'Progress Bar Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
						'{{WRAPPER}} .sonaar_wave_cut rect' => 'fill: {{VALUE}}',
						'{{WRAPPER}} .sr_waveform_simplebar .sonaar_wave_cut' => 'background-color: {{VALUE}}',
					],
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'soundwave_show', 
										'operator' => '==',
										'value' => ''
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '==',
										'value' => 'skin_button'
									],
									[
										'name' => 'soundwave_show_skin_button', 
										'operator' => '!=',
										'value' => ''
									]
								]
							]
						]
					],
					'render_type' => 'template',
					
				]
			);
			$this->add_control(
				'soundWave_bg_bar_color',
				[
					'label'                 		=> esc_html__( 'Progress Bar Background Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
						'{{WRAPPER}} .sonaar_wave_base rect' => 'fill: {{VALUE}}',
						'{{WRAPPER}} .sr_waveform_simplebar .sonaar_wave_base' => 'background-color: {{VALUE}}',
					],		
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'soundwave_show', 
										'operator' => '==',
										'value' => ''
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '==',
										'value' => 'skin_button'
									],
									[
										'name' => 'soundwave_show_skin_button', 
										'operator' => '!=',
										'value' => ''
									]
								]
							]
						]
					],
					'render_type' => 'template',
				]
			);
			//if(Sonaar_Music::get_option('waveformType', 'srmp3_settings_general') === 'simplebar'){
				$this->add_responsive_control(
					'simple_bar_height',
					[
						
						'label' 						=> esc_html__( 'Progress Bar Height', 'sonaar-music' ),
						'type' 							=> Controls_Manager::SLIDER,
						'render_type'					=> 'template',
						'range' 						=> [
						'px' 						=> [
							'min'					=> 1,
							'max' 					=> 150,
						],
						],
						'selectors' 					=> [
							'{{WRAPPER}} .album-player .wave, {{WRAPPER}} .album-player .sonaar_fake_wave, {{WRAPPER}} .album-player.sr_waveform_simplebar .sonaar_fake_wave .sonaar_wave_base, {{WRAPPER}} .album-player.sr_waveform_simplebar .sonaar_fake_wave .sonaar_wave_cut' => 'height: {{SIZE}}px !important;',
							'{{WRAPPER}} .album-player .sonaar_fake_wave' => 'margin-top: initial;margin-bottom:initial;max-height:unset',
							'{{WRAPPER}} .album-player .sr_progressbar' => 'margin-top: 20px;margin-bottom:20px',
						],
						'conditions'                    => [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '!=',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show', 
											'operator' => '==',
											'value' => ''
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show_skin_button', 
											'operator' => '!=',
											'value' => ''
										]
									]
								]
							]
						],
					]
				);
				$this->add_responsive_control(
					'soundwave_bar_width',
					[
						
						'label' 						=> esc_html__( 'Bar Width(px)', 'sonaar-music' ),
						'type' 							=> Controls_Manager::SLIDER,
						'render_type'					=> 'template',
						'range' 						=> [
							'px' 						=> [
								'min'					=> 1,
								'max' 					=> 20,
							]
						],
						'conditions'                    => [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '!=',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show', 
											'operator' => '==',
											'value' => ''
										],
										[
											'name' => 'progress_bar_style', 
											'operator' => '!=',
											'value' => 'simplebar'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show_skin_button', 
											'operator' => '!=',
											'value' => ''
										],
										[
											'name' => 'progress_bar_style', 
											'operator' => '!=',
											'value' => 'simplebar'
										]
									]
								]
							]
						],
					]
				);
				$this->add_responsive_control(
					'soundwave_bar_gap',
					[
						
						'label' 						=> esc_html__( 'Bar Gap(px)', 'sonaar-music' ),
						'type' 							=> Controls_Manager::SLIDER,
						'render_type'					=> 'template',
						'range' 						=> [
							'px' 						=> [
								'min'					=> 0,
								'max' 					=> 20,
							]
						],
						'conditions'                    => [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '!=',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show', 
											'operator' => '==',
											'value' => ''
										],
										[
											'name' => 'progress_bar_style', 
											'operator' => '!=',
											'value' => 'simplebar'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show_skin_button', 
											'operator' => '!=',
											'value' => ''
										],
										[
											'name' => 'progress_bar_style', 
											'operator' => '!=',
											'value' => 'simplebar'
										]
									]
								]
							]
						],
					]
				);
				$this->add_responsive_control(
					'simple_bar_radius',
					[
						'label' 						=> esc_html__( 'SimpleBar Radius', 'elementor' ),
						'type' 							=> Controls_Manager::SLIDER,
						
						'range' 						=> [
							'px' 						=> [
								'max' 					=> 20,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 0,
						],
						'selectors' 					=> [
														'{{WRAPPER}} .album-player.sr_waveform_simplebar .sonaar_fake_wave .sonaar_wave_base, {{WRAPPER}} .album-player.sr_waveform_simplebar .sonaar_fake_wave .sonaar_wave_cut' => 'border-radius: {{SIZE}}px;',
						],
						'conditions'                    => [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '!=',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show', 
											'operator' => '==',
											'value' => ''
										],
										[
											'name' => 'progress_bar_style', 
											'operator' => '!=',
											'value' => 'mediaElement'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show_skin_button', 
											'operator' => '!=',
											'value' => ''
										]
									]
								]
							]
						],
					]
				);
				$this->add_control(
					'soundwave_linecap',
					[
						'label' => esc_html__( 'Bar Line Cap', 'sonaar-music' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'default',
						'options' => [
							'default' => esc_html__( 'Default (Plugin Settings)', 'sonaar-music' ),
							'square' => esc_html__( 'Square', 'sonaar-music' ),
							'round' => esc_html__( 'Round', 'sonaar-music' ),
							'butt' => esc_html__( 'Butt', 'sonaar-music' ),
						],
						'conditions'                    => [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '!=',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show', 
											'operator' => '==',
											'value' => ''
										],
										[
											'name' => 'progress_bar_style', 
											'operator' => '!=',
											'value' => 'simplebar'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show_skin_button', 
											'operator' => '!=',
											'value' => ''
										],
										[
											'name' => 'progress_bar_style', 
											'operator' => '!=',
											'value' => 'simplebar'
										]
									]
								]
							]
						],
					]
				);
				$this->add_control(
					'soundwave_fadein',
					[
						'label' => esc_html__( 'Disable Waveform FadeIn', 'sonaar-music' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'default',
						'options' => [
							'default' => esc_html__( $this->get_srmp3_option_label('music_player_wave_disable_fadein', 'srmp3_settings_general') ),
							'true' => esc_html__( 'Yes', 'sonaar-music' ),
							'false' => esc_html__( 'No', 'sonaar-music' ),
						],
						'conditions'                    => [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '!=',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show', 
											'operator' => '==',
											'value' => ''
										],
										[
											'name' => 'progress_bar_style', 
											'operator' => '!=',
											'value' => 'simplebar'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_button'
										],
										[
											'name' => 'soundwave_show_skin_button', 
											'operator' => '!=',
											'value' => ''
										]
									]
								]
							]
						],
					]
				);
				
			$this->add_control(
				'progressbar_inline',
				[
					'label' 						=> esc_html__( 'Inline Progress Bar', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'yes',
					'separator'						=> 'before',
				]
			);
			$this->add_responsive_control(
				'control_alignment',
				[
					'label' 						=> esc_html__( 'Control Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'left'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'right' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> 'right',
					'condition' 				=> [
						'progressbar_inline'		=> 'yes',
						'player_layout' 	=> 'skin_boxed_tracklist',
					],

				]
			);
			$this->add_control(
				'duration_soundwave_show',
				[
					'label' 						=> esc_html__( 'Hide Time Durations', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'yes',
					'separator'						=> 'before',
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'soundwave_show', 
										'operator' => '==',
										'value' => ''
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '==',
										'value' => 'skin_button'
									],
									[
										'name' => 'soundwave_show_skin_button', 
										'operator' => '!=',
										'value' => ''
									]
								]
							]
						]
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'duration_soundwave_typography',
					'label' 						=> esc_html__( 'Time Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'duration_soundwave_show', 
								'operator' => '=',
								'value' => ''
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'player_layout', 
												'operator' => '!=',
												'value' => 'skin_button'
											],
											[
												'name' => 'soundwave_show', 
												'operator' => '==',
												'value' => ''
											]
										]
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'player_layout', 
												'operator' => '==',
												'value' => 'skin_button'
											],
											[
												'name' => 'soundwave_show_skin_button', 
												'operator' => '!=',
												'value' => ''
											]
										]
									]
								]
							]
						]
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .player',
				]
			);
			$this->add_control(
				'duration_soundwave_color',
				[
					'label'                 		=> esc_html__( 'Time Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'conditions'                    => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'duration_soundwave_show', 
								'operator' => '=',
								'value' => ''
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'player_layout', 
												'operator' => '!=',
												'value' => 'skin_button'
											],
											[
												'name' => 'soundwave_show', 
												'operator' => '==',
												'value' => ''
											]
										]
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'player_layout', 
												'operator' => '==',
												'value' => 'skin_button'
											],
											[
												'name' => 'soundwave_show_skin_button', 
												'operator' => '!=',
												'value' => ''
											]
										]
									]
								]
							]
						]
					],
					'selectors'            			=> [
													'{{WRAPPER}} .iron-audioplayer .currentTime, {{WRAPPER}} .iron-audioplayer .totalTime' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'hr9',
				[
					'type' 							=> \Elementor\Controls_Manager::DIVIDER,
					'style' 						=> 'thick',
				]
			);
			$this->add_control(
				'control_hide',
				[
					'label' 						=> esc_html__( 'Hide Play/Rew/Fwrd Controls', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'yes',
					'selectors'            			=> [
						'{{WRAPPER}} .iron-audioplayer .srp_main_control .control .previous.sricon-back, {{WRAPPER}} .iron-audioplayer .srp_main_control .control .play, {{WRAPPER}} .iron-audioplayer .srp_main_control .control .next.sricon-forward, {{WRAPPER}} .album-player .srp-play-button' => 'display:none!important;',
					],
					'condition' 					=> [
						'player_layout!' 	=> 'skin_button',
					],
				]
			);
			$this->start_controls_tabs( 'tabs_play_button_style' );
			$this->start_controls_tab(
				'tab_play_button_normal',
				[
					'label' 						=> esc_html__( 'Normal', 'elementor' ),
				]
			);
			$this->add_control(
				'audio_player_controls_color',
				[
					'label'                 		=> esc_html__( 'Audio Player Controls Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .control .sricon-play, {{WRAPPER}} .srp-play-button .sricon-play, {{WRAPPER}} .srp_player_boxed .srp_noteButton' => 'color: {{VALUE}}',
													'{{WRAPPER}} .iron-audioplayer .control .sr_speedRate div' => 'color: {{VALUE}}; border-color: {{VALUE}} ',
													'{{WRAPPER}} .iron-audioplayer .control' => 'color: {{VALUE}};',
													'{{WRAPPER}} .iron-audioplayer .srp-play-circle' => 'border-color: {{VALUE}};',
													'{{WRAPPER}} .iron-audioplayer .srp-play-button-label-container' => 'background: {{VALUE}};',	
													'{{WRAPPER}} .iron-audioplayer .sonaar-Artwort-box .control [class*="sricon-"]' => 'color: {{VALUE}};',
													'{{WRAPPER}} .iron-audioplayer .sonaar-Artwort-box .control .play' => 'border-color:{{VALUE}};'			
					],
					
				]
			);
			$this->add_control(
				'audio_player_play_text_color',
				[
					'label'                 		=> esc_html__( 'Play/Pause Text Color ', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .srp-play-button-label-container' => 'color: {{VALUE}};',
					],
					'condition' 					=> [
						'player_layout' 	=> ['skin_boxed_tracklist', 'skin_button'],
						'control_hide!'     => 'yes'
					],
				]
			);
			$this->end_controls_tab();
			$this->start_controls_tab(
				'tab_play_button_hover',
				[
					'label' 						=> esc_html__( 'Hover', 'elementor' ),
				]
			);
			$this->add_control(
				'audio_player_controls_color_hover',
				[
					'label'                 		=> esc_html__( 'Audio Player Controls Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .control .sricon-play:hover, {{WRAPPER}} .srp-play-button:hover .sricon-play' => 'color: {{VALUE}}',
													'{{WRAPPER}} .iron-audioplayer .control .sr_speedRate:hover div' => 'color: {{VALUE}}; border-color: {{VALUE}} ',
													'{{WRAPPER}} .iron-audioplayer .control .sr_skipBackward:hover, {{WRAPPER}} .iron-audioplayer .control .sr_skipForward:hover, {{WRAPPER}} .iron-audioplayer .control .sr_shuffle:hover, {{WRAPPER}} .iron-audioplayer .control .srp_repeat:hover, {{WRAPPER}} .iron-audioplayer .control .previous:hover, {{WRAPPER}} .iron-audioplayer .control .next:hover, {{WRAPPER}} .iron-audioplayer .control .volume:hover .sricon-volume' => 'color: {{VALUE}};',
													'{{WRAPPER}} .iron-audioplayer .srp-play-button:hover .srp-play-circle' => 'border-color: {{VALUE}};',
													'{{WRAPPER}} .iron-audioplayer .srp-play-button-label-container:hover' => 'background: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'audio_player_play_text_color_hover',
				[
					'label'                 		=> esc_html__( 'Play/Pause Text Color ', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .srp-play-button-label-container:hover' => 'color: {{VALUE}};',
					],
					'condition' 					=> [
						'player_layout' 	=> ['skin_boxed_tracklist', 'skin_button']
					],
				]
			);
			$this->add_control(
				'button_border_color_hover',
				[
					'label'                 		=> esc_html__( 'Border Color ', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .srp-play-button-label-container:hover' => 'border-color: {{VALUE}};',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
						'use_play_label_skin_button' => 'yes'
					],
				]
			);
			$this->add_control(
				'button_hover_animation',
				[
					'label' => esc_html__( 'Hover Animation', 'elementor-sonaar' ),
					'type'  => Controls_Manager::HOVER_ANIMATION,
					'condition' 					=> [
						'player_layout' 	=> 'skin_button'
					],

				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 							=> 'button_label_border',
					'selector' 						=> '{{WRAPPER}} .srp-play-button-label-container',
					'separator' 					=> 'after',
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
						'use_play_label_skin_button' => 'yes'
					],
				]
			);
			$this->add_control(
				'use_play_label',
				[
					'label' 		=> esc_html__( 'Show Text instead of Play Icon', 'sonaar-music' ),
					'type' 			=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_use_play_label', 'srmp3_settings_widget_player') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 		=> 'default',
					'condition' 	=> [
						'player_layout' => 'skin_boxed_tracklist',
						'control_hide!'     => 'yes'
					],
				]
			);
			if ( function_exists( 'run_sonaar_music_pro' ) ){
				$this->add_control(
					'play_text',
					[
						'label' 						=> esc_html__( 'Play text', 'sonaar-music' ),
						'type' 							=> Controls_Manager::TEXT,
						'dynamic' 						=> [
							'active' 					=> true,
						],
						'default' 						=> esc_html__( Sonaar_Music::get_option('labelPlayTxt', 'srmp3_settings_widget_player') ),
						'conditions'                    => [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_button'
										],
										[
											'name' => 'use_play_label_skin_button', 
											'operator' => '==',
											'value' => 'yes'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_boxed_tracklist'
										],
										[
											'name' => 'use_play_label', 
											'operator' => '==',
											'value' => 'true'
										],
										[
											'name' => 'control_hide', 
											'operator' => '!=',
											'value' => 'yes'
										]
									]
								]
							]
						],
						'label_block' 					=> false,
					]
				);
				$this->add_control(
					'pause_text',
					[
						'label' 						=> esc_html__( 'Pause text', 'sonaar-music' ),
						'type' 							=> Controls_Manager::TEXT,
						'dynamic' 						=> [
							'active' 					=> true,
						],
						'default' 						=> esc_html__( Sonaar_Music::get_option('labelPauseTxt', 'srmp3_settings_widget_player') ),
						'conditions'                    => [
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_button'
										],
										[
											'name' => 'use_play_label_skin_button', 
											'operator' => '==',
											'value' => 'yes'
										]
									]
								],
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'player_layout', 
											'operator' => '==',
											'value' => 'skin_boxed_tracklist'
										],
										[
											'name' => 'use_play_label', 
											'operator' => '==',
											'value' => 'true'
										],
										[
											'name' => 'control_hide', 
											'operator' => '!=',
											'value' => 'yes'
										]
									]
								]
							]
						],
						'label_block' 					=> false,
					]
				);
			}
			$this->add_control(
				'play_btn_align_wave',
				[
					'label' 		=> esc_html__( 'Align the Play/Pause Button to the Progress Bar', 'sonaar-music' ),
					'type' 								=> \Elementor\Controls_Manager::SWITCHER,
					'return_value' 						=> 'yes',
					'condition' 	=> [
						'player_layout' => 'skin_boxed_tracklist',
						'use_play_label' => 'false',
						'control_hide!'     => 'yes'
						],
				]
			);
			$this->add_control(
				'use_play_label_skin_button',
				[
					'label' 							=> esc_html__( 'Show Play Label', 'sonaar-music' ),
					'type' 								=> \Elementor\Controls_Manager::SWITCHER,
					'return_value' 						=> 'yes',
					'default' 							=> 'yes',
					'condition' 	=> [
						'player_layout' => 'skin_button',
						],
				]
			);
			
		$this->add_control(
			'use_play_label_with_icon',
			[
				'label' 							=> esc_html__( 'Play Icon', 'sonaar-music' ),
				'type' 								=> \Elementor\Controls_Manager::SWITCHER,
				'return_value' 						=> 'yes',
				'default' 							=> 'yes',
				'condition' 	=> [
					'player_layout' => 'skin_button',
					'use_play_label_skin_button' => 'yes'
					],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 							=> 'play_label_typography',
				'label' 						=> esc_html__( 'Play Label Typography', 'sonaar-music' ),
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'conditions'                    => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_button'
								],
								[
									'name' => 'use_play_label_skin_button', 
									'operator' => '==',
									'value' => 'yes'
								]
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_boxed_tracklist'
								],
								[
									'name' => 'use_play_label', 
									'operator' => '==',
									'value' => 'true'
								],
								[
									'name' => 'control_hide', 
									'operator' => '!=',
									'value' => 'yes'
								]
							]
						]
					]
				],
				'selector' 						=> '{{WRAPPER}} .srp-play-button-label-container',
			]
		);
		$this->add_responsive_control(
			'play_label_padding',
			[
				'label' 						=> esc_html__( 'Play Label Padding', 'sonaar-music' ),
				'type' 							=> Controls_Manager::DIMENSIONS,
				'size_units' 					=> [ 'px', 'em', '%' ],
				'selectors' 					=> [
												'{{WRAPPER}} .srp-play-button-label-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions'                    => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_button'
								],
								[
									'name' => 'use_play_label_skin_button', 
									'operator' => '==',
									'value' => 'yes'
								]
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_boxed_tracklist'
								],
								[
									'name' => 'use_play_label', 
									'operator' => '==',
									'value' => 'true'
								],
								[
									'name' => 'control_hide', 
									'operator' => '!=',
									'value' => 'yes'
								]
							]
						]
					]
				],
			]
		);
		$this->add_control(
			'play_button_radius',
			[
				'label' 						=> esc_html__( 'Play Button Radius', 'elementor' ),
				'type' 							=> Controls_Manager::SLIDER,
				'range' 						=> [
					'px' 						=> [
						'max' 					=> 100,
					],
				],
				'conditions'                    => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_button'
								],
								[
									'name' => 'use_play_label_skin_button', 
									'operator' => '==',
									'value' => 'yes'
								]
							]
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'player_layout', 
									'operator' => '==',
									'value' => 'skin_boxed_tracklist'
								],
								[
									'name' => 'use_play_label', 
									'operator' => '==',
									'value' => 'true'
								],
								[
									'name' => 'control_hide', 
									'operator' => '!=',
									'value' => 'yes'
								]
							]
						]
					]
				],
				'selectors' 					=> [
												'{{WRAPPER}} .srp-play-button-label-container' => 'border-radius: {{SIZE}}px;',
				],
			]
		);
		$this->add_responsive_control(
			'play-size',
			[
				'label' 					=> esc_html__( 'Play/Pause size', 'sonaar-music' ) . ' (px)',
				'type' 						=> Controls_Manager::SLIDER,
				'range' 					=> [
					'px' 					=> [
						'min'				=> 0,
						'max' 				=> 100,
					],
				],
				'selectors' 				=> [
							'{{WRAPPER}} .srp-play-button .sricon-play' => 'font-size: {{SIZE}}px;',
				],
				'condition' 				=> [
					'player_layout' => 'skin_boxed_tracklist',
					'use_play_label!' => 'true',
					'control_hide!'     => 'yes'
				],
			]
		);
		$this->add_responsive_control(
			'play-circle-size',
			[
				'label' 					=> esc_html__( 'Play/Pause Circle size', 'sonaar-music' ) . ' (px)',
				'type' 						=> Controls_Manager::SLIDER,
				'range' 					=> [
					'px' 					=> [
						'min'				=> 10,
						'max' 				=> 150,
					],
				],
				'selectors' 				=> [
							'{{WRAPPER}} .srp-play-circle' => 'height: {{SIZE}}px; width: {{SIZE}}px; border-radius: {{SIZE}}px;',
				],
				'condition' 				=> [
					'player_layout' => 'skin_boxed_tracklist',
					'use_play_label!' => 'true',
					'control_hide!'     => 'yes'
				],
			]
		);
		$this->add_responsive_control(
			'play-circle-width',
			[
				'label' 					=> esc_html__( 'Play/Pause Circle width', 'sonaar-music' ) . ' (px)',
				'type' 						=> Controls_Manager::SLIDER,
				'range' 					=> [
					'px' 					=> [
						'min'				=> 0,
						'max' 				=> 30,
					],
				],
				'selectors' 				=> [
							'{{WRAPPER}} .srp-play-circle' => 'border-width: {{SIZE}}px;',
				],
				'condition' 				=> [
					'player_layout' => 'skin_boxed_tracklist',
					'use_play_label!' => 'true',
					'control_hide!'     => 'yes'
				],
			]
		);
			$this->add_control(
				'show_prevnext_bt',
				[
					'label' 		=> esc_html__( 'Show Previous & Next buttons', 'sonaar-music' ),
					'type' 			=> Controls_Manager::SELECT,
					'options' 		=> [
						//'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_shuffle_bt', 'srmp3_settings_widget_player') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					//'default' 		=> 'default',
					'default' 		=> 'false',
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist',
					],
				]
			);
			$this->add_control(
				'show_skip_bt',
				[
					'label' 		=> esc_html__( 'Show Skip 15/30 Seconds button', 'sonaar-music' ),
					'type' 			=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_skip_bt', 'srmp3_settings_widget_player') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 		=> 'default',
					'condition' 					=> [
						'player_layout!' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'show_skip_bt_skin_button',
				[
					'label' 		=> esc_html__( 'Show Skip 15/30 Seconds button', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'separator'		=> 'before',
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'show_shuffle_bt',
				[
					'label' 		=> esc_html__( 'Show Shuffle button', 'sonaar-music' ),
					'type' 			=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_shuffle_bt', 'srmp3_settings_widget_player') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 		=> 'default',
					'condition' 					=> [
						'player_layout!' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'show_repeat_bt',
				[
					'label' 		=> esc_html__( 'Show Repeat button', 'sonaar-music' ),
					'type' 			=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_repeat_bt', 'srmp3_settings_widget_player') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 		=> 'default',
					'condition' 					=> [
						'player_layout!' 	=> 'skin_button',
						'no_track_skip!' 	=> 'yes',
					],
				]
			);
			$this->add_control(
				'show_shuffle_bt_skin_button',
				[
					'label' 		=> esc_html__( 'Show Shuffle button', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'show_repeat_bt_skin_button',
				[
					'label' 		=> esc_html__( 'Show Repeat button', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
						'no_track_skip!' 	=> 'yes',
					],
				]
			);

			$this->add_control(
				'show_speed_bt',
				[
					'label' 		=> esc_html__( 'Show Playback Speed button', 'sonaar-music' ),
					'type' 			=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_speed_bt', 'srmp3_settings_widget_player') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 		=> 'default',
					'condition' 					=> [
						'player_layout!' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'show_speed_bt_skin_button',
				[
					'label' 		=> esc_html__( 'Show Playback Speed button', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
					],
				]
			);

			$this->add_control(
				'show_volume_bt',
				[
					'label' 		=> esc_html__( 'Show Volume Control', 'sonaar-music' ),
					'type' 			=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_volume_bt', 'srmp3_settings_widget_player') ),
						'true'		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 		=> 'default',
					'condition' 					=> [
						'player_layout!' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'show_volume_bt_skin_button',
				[
					'label' 		=> esc_html__( 'Show Volume Control', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'condition' 					=> [
						'player_layout' 	=> 'skin_button',
					],
				]
			);
			$this->add_control(
				'show_miniplayer_note_bt',
				[
					'label' 		=> esc_html__( 'Show Info Icon', 'sonaar-music' ),
					'type' 			=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_miniplayer_note_bt', 'srmp3_settings_widget_player') ),
						'true'		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 		=> 'default',
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist',
					],
				]
			);
			$this->add_responsive_control(
				'audio_player_controls_spacebefore',
				[
					'label' 					=> esc_html__( 'Add Space Before Player Control', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> -500,
							'max' 				=> 100,
						],
					],
					'selectors' 				=> [
								'{{WRAPPER}} .iron-audioplayer .album-player .control' => 'top: {{SIZE}}px;position:relative;',
					],
					'separator'		=> 'before',
					'condition' 				=> [
						'progressbar_inline'		=> '',
						'player_layout!' 			=> 'skin_button',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'player_background',
					'label' => esc_html__( 'Background', 'elementor-sonaar' ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .iron-audioplayer .srp_player_boxed, {{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_float_tracklist"] .album-player, {{WRAPPER}} .srp-artworkbg',
					'separator' 				=> 'before',
					'condition' 					=> [
						'player_layout!' 	=> 'skin_button',
					],
				]
			);
			
			$this->add_responsive_control(
				'artwork_boxed_vertical_align',
				[
					'label' 						=> esc_html__( 'Vertical Alignment with the Image Cover', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Top', 'elementor' ),
							'icon' 					=> 'eicon-v-align-top',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-v-align-middle',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Bottom', 'elementor' ),
							'icon' 					=> 'eicon-v-align-bottom',
						],
					],
					'default' 						=> '',
					'separator'					=> 'after',
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist',
					],
					'selectors' 					=> [
														'{{WRAPPER}} .iron-audioplayer .srp_player_grid' => 'align-items:{{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'player_radius',
				[
					'label' 						=> esc_html__( 'Player Radius', 'sonaar-music' ) . ' (px)', 
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px' ],
					'selectors' 					=> [
						'{{WRAPPER}} .iron-audioplayer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;',
					],
				]
			);
			$this->add_responsive_control(
				'player_padding',
				[
					'label' 						=> esc_html__( 'Container Padding', 'sonaar-music' ) . ' (px)', 
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
						'{{WRAPPER}} .srp_player_boxed, {{WRAPPER}} :not(.srp_player_boxed) > .album-player' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();


			/**
			* STYLE: SPECTRO
			* -------------------------------------------------
			*/
			$this->start_controls_section(
	            'spectro_style',
	            [
	                'label'                 		=> esc_html__( 'Animated Audio Spectrum', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'condition' => [
						'player_layout!' 	=> 'skin_button'
					],
	            ]
			);		
			
			$this->add_responsive_control(
				'spectro_animation',
				[
					'label' 			=> esc_html__( 'Spectrum Animation', 'sonaar-music' ),
					'type' 				=> Controls_Manager::SELECT,
					'separator'						=> 'before',
					'options' 			=> [
						'none'			=> esc_html__( 'None/Disabled', 'sonaar-music' ),
						'bars' 			=> esc_html__( 'Animated Bars', 'sonaar-music' ),
						'bricks' 		=> esc_html__( 'Animated Bricks', 'sonaar-music' ),
						'shockwave' 	=> esc_html__( 'Animated Shockwaves', 'sonaar-music' ),
						'string' 		=> esc_html__( 'Animated String', 'sonaar-music' ),
						'selectors'		=> esc_html__( 'Bounce Specific CSS Class', 'sonaar-music' ),
						
					],
					'default' 			=> 'none',
				]
			);
			
			$this->add_control(
				'spectro_notice',
				[
					'raw' => sprintf( esc_html__('%1$sAudio Not Playing? Spectrum Not Showing?%2$s If you don\'t see the Live Spectrum or Audio does not start when using this feature, %3$sClick here%4$s for possible cause and work-around.', 'sonaar-music' ), '<strong>', '</strong>', '<a href="https://bit.ly/3HtbC9u" target="_blank">', '</a>' ),
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'render_type' => 'ui',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!==', 'value' => 'none'],
									]
								],
						],
					],
				]
			);
			$this->add_control(
				'spectro_tracklist_spectrum',
				[
					'label' 						=> esc_html__( 'For tracklist only', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'true',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
										
									]
								],
						],
					],
				]
			);
			$this->add_control(
				'spectro_shadow',
				[
					'label' 						=> esc_html__( 'Shadow FX', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> 'true',
					'return_value' 					=> 'true',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none', 'bricks', 'selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_control(
				'spectro_reflect',
				[
					'label' 						=> esc_html__( 'Reflection FX', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'yes',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_control(
				'spectro_pointu',
				[
					'label' 						=> esc_html__( 'Sharp Peaks', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'yes',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['bricks']]
									]
								],
								
						],
					],
				]
			);
			$this->add_control(
				'spectro_vertical_aligned',
				[
					'label' 						=> esc_html__( 'Vertical Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'bottom' 					=> [
							'title' 				=> esc_html__( 'Bottom', 'elementor' ),
							'icon' 					=> 'eicon-v-align-bottom',
						],
						'middle' 					=> [
							'title' 				=> esc_html__( 'Middle', 'elementor' ),
							'icon' 					=> 'eicon-v-align-middle',
						],
						'top'    					=> [
							'title' 				=> esc_html__( 'Top', 'elementor' ),
							'icon' 					=> 'eicon-v-align-top',
						],
					],
					'default' 						=> 'bottom',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_reflect', 'operator' => '===', 'value' => ''],
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['bars', 'bricks']]
									]
								],
								[
								'terms' => [
										['name' => 'spectro_reflect', 'operator' => '===', 'value' => ''],
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['bars', 'bricks']]
									]
								],
						],
					],
				]
			);
			
			$this->add_control(
				'spectro_color_1',
				[
					'label' 			=> esc_html__( 'Spectro Color 1', 'elementor-sonaar' ),
					'type' 				=> Controls_Manager::COLOR,
					'selector' => '',
					'render_type' => 'template',
					//'separator' 				=> 'before',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_control(
				'spectro_color_2',
				[
					'label' 			=> esc_html__( 'Spectro Color 2', 'elementor-sonaar' ),
					'type' 				=> Controls_Manager::COLOR,
					'selector' => '',
					'render_type' => 'template',
					//'separator' 				=> 'before',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_control(
				'spectro_gradient_direction',
				[
					'label' 						=> esc_html__( 'Gradient Direction', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'vertical'    					=> [
							'title' 				=> esc_html__( 'Vertical', 'elementor' ),
							'icon' 					=> 'eicon-arrow-down',
						],
						'horizontal' 					=> [
							'title' 				=> esc_html__( 'Horizontal', 'elementor' ),
							'icon' 					=> 'eicon-arrow-right',
						],
					],
					'default' 						=> 'vertical',
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
										['name' => 'spectro_color_2', 'operator' => '!==', 'value' => ''],
										['name' => 'spectro_reflect', 'operator' => '===', 'value' => '']
									]
								],
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
										['name' => 'spectro_color_2', 'operator' => '!==', 'value' => ''],
										['name' => 'spectro_reflect', 'operator' => '===', 'value' => '']
									]
								],
						],
					],
					
				]
			);
			$this->add_control(
				'spectro_barcount',
				[
					
					'label' 						=> esc_html__( 'Number of Bars', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 1,
							'max' 				=> 1000,
						],
					],
					'default' 					=> [
						'size' 					=> 500,
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_control(
				'spectro_barwidth',
				[
					
					'label' 						=> esc_html__( 'Bar Width', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 1,
							'max' 				=> 100,
						],
					],
					'default' 					=> [
						'size' 					=> 3,
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_control(
				'spectro_blockheight',
				[
					
					'label' 						=> esc_html__( 'Brick Height', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 1,
							'max' 				=> 40,
						],
					],
					'default' 					=> [
						'size' 					=> 2,
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['bricks']],
									]
								],
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['bricks']],
									]
								],
						],
					],
				]
			);
			$this->add_control(
				'spectro_blockgap',
				[
					
					'label' 						=> esc_html__( 'Vertical Gap between Bricks', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 1,
							'max' 				=> 20,
						],
					],
					'default' 					=> [
						'size' 					=> 2,
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['bricks']],
									]
								],
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['bricks']],
									]
								],
						],
					],
				]
			);
			$this->add_control(
				'spectro_bargap',
				[
					
					'label' 						=> esc_html__( 'Bar Gap', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 0,
							'max' 				=> 20,
						],
					],
					'default' 					=> [
						'size' 					=> 2,
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['bars', 'bricks']]
									]
								],
						],
					],
				]
			);
			$this->add_control(
				'spectro_shockwavevibrance',
				[
					
					'label' 						=> esc_html__( 'Shockwave Vibrance', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 1,
							'max' 				=> 200,
						],
					],
					'default' 					=> [
						'size' 					=> 40,
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['shockwave']]
									]
								],
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => 'in', 'value' => ['shockwave']]
									]
								],
						],
					],
				]
			);
			$this->add_control(
				'spectro_canvasheight',
				[
					
					'label' 						=> esc_html__( 'Max height of the bars', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'range' 					=> [
						'px' 					=> [
							'min'				=> 1,
							'max' 				=> 800,
						],
					],
					'default' 					=> [
						'size' 					=> 100,
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_responsive_control(
				'spectro_alignment',
				[
					'label' 						=> esc_html__( 'Spectro Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'label_block' 	=> true,
					'default' 		=> '',
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Start', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'End', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .srp_spectrum_container' => 'justify-content: {{VALUE}};',
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
					
				]
			);
			$this->add_responsive_control(
				'position',
				[
					'label' => esc_html__( 'Position', 'elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'relative',
					'options' => [
						'relative' => esc_html__( 'Relative', 'elementor' ),
						'absolute' => esc_html__( 'Absolute', 'elementor' ),
						'fixed' => esc_html__( 'Fixed', 'elementor' ),
					],
					'selectors' => [
						'{{WRAPPER}} .srp_spectrum_container' => 'position: {{VALUE}};',
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_responsive_control(
				'spectro_hoffset',
				[
					'label' 					=> esc_html__( 'Horizontal Offset', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
						'vw' => [
							'min' => -200,
							'max' => 200,
						],
						'vh' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%', 'vw', 'vh' ],
					'selectors' 				=> [
								'{{WRAPPER}} .iron-audioplayer .srp_spectrum_container' => 'left: {{SIZE}}px;',
					],
					
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_responsive_control(
				'spectro_voffset',
				[
					'label' 					=> esc_html__( 'Vertical Offset', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
						'vw' => [
							'min' => -200,
							'max' => 200,
						],
						'vh' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%', 'vw', 'vh' ],
					'selectors' 				=> [
								'{{WRAPPER}} .iron-audioplayer .srp_spectrum_container' => 'top: {{SIZE}}px;',
					],
					
					'conditions' => [
						'relation' => 'or',
						'terms' => [
								[
								'terms' => [
										['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
									]
								]
						],
					],
				]
			);
			$this->add_responsive_control(
					'spectro_index',
					[
						
						'label' 						=> esc_html__( 'Z-index', 'sonaar-music' ),
						'type' 							=> \Elementor\Controls_Manager::NUMBER,
						'min' 							=> -1,
						'max' 							=> 9999999999,
						'step' 							=> 1,
						'default' 						=> 1,
						'conditions' => [
							'relation' => 'or',
							'terms' => [
									[
									'terms' => [
											['name' => 'spectro_animation', 'operator' => '!in', 'value' => ['none','selectors']],
										]
									]
							],
						],
						'selectors' 					=> [
														'{{WRAPPER}} .iron-audioplayer .srp_spectrum' => 'z-index:{{SIZE}};',
						],
					]
				);
				$this->add_control(
					'spectro_selector_heading',
					[
						'label' 						=> esc_html__( 'Add Bounce Effect to any CSS Classes', 'elementor' ),
						'type' 							=> Controls_Manager::HEADING,
						'separator' 					=> 'before',
						'conditions' => [
							'relation' => 'or',
							'terms' => [
									[
									'terms' => [
											['name' => 'spectro_animation', 'operator' => '!==', 'value' => 'none'],
										]
									]
							],
						],
					]
				);
				$this->add_control(
					'spectro_classes',
					[
						'label' 						=> esc_html__( 'CSS Classes', 'sonaar-music' ),
						'title' 						=> esc_html__( 'Add your custom class with the dot or # for ID. Separated by commas for multiple selectors e.g: .my-class, #myid', 'sonaar-music' ),
						'classes' 						=> 'elementor-control-direction-ltr',
						'type' 							=> Controls_Manager::TEXT,
						'default' 						=> '',
						'render_type' 					=> 'template',
						'prefix_class' 					=> '',
						'dynamic' => [
							'active' => true,
						],
						'conditions' => [
							'relation' => 'or',
							'terms' => [
									[
									'terms' => [
											['name' => 'spectro_animation', 'operator' => '!==', 'value' => 'none'],
										]
									]
							],
						],
					]
				);
				$this->add_control(
					'spectro_selector_vibrance',
					[
						
						'label' 						=> esc_html__( 'Vibrance', 'sonaar-music' ),
						'type' 						=> Controls_Manager::SLIDER,
						'range' 					=> [
							'px' 					=> [
								'min'				=> 1,
								'max' 				=> 1000,
							],
						],
						'default' 					=> [
							'size' 					=> 100,
						],
						'conditions' => [
							'relation' => 'or',
							'terms' => [
									[
									'terms' => [
											['name' => 'spectro_animation', 'operator' => '!==', 'value' => 'none'],
											['name' => 'spectro_classes', 'operator' => '!=', 'value' => ''],
										]
									]
							],
						],
					]
				);
				$this->add_control(
					'spectro_selectorblur',
					[
						'label' 						=> esc_html__( 'Add Blur Effect', 'sonaar-music' ),
						'type' 							=> Controls_Manager::SWITCHER,
						'default' 						=> '',
						'render_type' => 'template',
						'return_value' 					=> 'true',
						'conditions' => [
							'relation' => 'or',
							'terms' => [
									[
									'terms' => [
											['name' => 'spectro_animation', 'operator' => '!==', 'value' => 'none'],
											['name' => 'spectro_classes', 'operator' => '!=', 'value' => ''],
										]
									],
							],
						],
					]
				);

			$this->end_controls_section();


	        /**
	         * STYLE: PLAYLIST
	         * -------------------------------------------------
	         */
				
			 $this->start_controls_section(
	            'playlist_style',
	            [
	                'label'                			=> esc_html__( 'Tracklist: Layout', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout',
										'operator' => '==',
										'value' => 'skin_button'
									],
									[
										'name' => 'playlist_show_playlist_skin_button',
										'operator' => '!=',
										'value' => ''
									]
								]	
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout',
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'playlist_show_playlist',
										'operator' => '!=',
										'value' => ''
									]
								]	
							],
						]
					],
				]
			);
			$this->add_control(
				'trackList_layout',
				[
					'label' 						=> esc_html__( 'Tracklist Layout', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SELECT,
					'options' 				=> [
						'list' 				=> 'List',
						'grid'				=> 'Grid',
					],
					'default'						=> 'list',
				]
			);
			$this->add_responsive_control(
					'grid_column_number',
					[
						'label'					=> esc_html__( 'Columns', 'sonaar-music' ),
						'type' 					=> Controls_Manager::SELECT,
						'options' 				=> [
							'1' 				=> '1',
							'2'					=> '2',
							'3' 				=> '3',
							'4' 				=> '4',
							'5' 				=> '5',
							'6' 				=> '6',
						],
						'default' 				=> '4',
						'tablet_default' 		=> '3',
						'mobile_default' 		=> '2',
						'condition' 			=> [
							'trackList_layout' => 'grid',
						],
						'selectors'             => [
							'{{WRAPPER}} .srp_tracklist_grid .srp_tracklist > ul' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
						],
					]
			);
			$this->add_control(
				'hr12',
				[
					'type' 							=> \Elementor\Controls_Manager::DIVIDER,
					'style' 						=> 'thick',
				]
			);
			$this->add_control(
					'move_playlist_below_artwork',
					[
						'label' 					=> esc_html__( 'Move Tracklist Below Artwork', 'sonaar-music' ),
						'type' 						=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 				=> 'auto',
						'separator'					=> 'after',
						'default' 					=> '',
						'prefix_class'				=> 'sr_playlist_below_artwork_',
						'condition' 				=> [
							'playlist_hide_artwork!' => 'yes',
							'player_layout' 	=> 'skin_float_tracklist',
						],
						'selectors' 				=> [
													'{{WRAPPER}} .sonaar-grid' => 'flex-direction: column;',
													
													//'{{WRAPPER}} .sonaar-Artwort-box' => 'justify-self:center;',
													//'{{WRAPPER}} .sonaar-grid' => 'justify-content:center!important;grid-template-columns:{{VALUE}}!important;',
							 
					 ],
					]
			);
			
			$this->add_control(
				'track_artwork_heading',
				[
					'label' 						=> sprintf( esc_html__( '%1$s Image', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::HEADING,
					//'separator' 					=> 'before',
				]
			);
			$this->add_control(
				'track_artwork_show',
				[
					'label' 						=> sprintf( esc_html__( 'Show Thumbnail for Each %1$s', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'condition' 					=> [
						'trackList_layout!' 			=> 'grid',
					],
				]
			);
			$this->add_control(
				'grid_track_artwork_show',
				[
					'label' 						=> sprintf( esc_html__( 'Show Thumbnail for Each %1$s', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> 'yes',
					'return_value' 					=> 'yes',
					'condition' 					=> [
						'trackList_layout' 			=> 'grid',
					],
				]
			);
			$this->add_control(
				'track_artwork_play_button',
				[
					'label' 						=> esc_html__( 'Play Icon overlay Image', 'sonaar-music' ), 
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'condition' 					=> [
						'trackList_layout!' 		=> 'grid',
						'track_artwork_show' 		=> 'yes'
					],
				]
			);
			$this->add_control(
				'grid_track_artwork_play_button',
				[
					'label' 						=> esc_html__( 'Play Icon overlay Image', 'sonaar-music' ), 
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> 'yes',
					'return_value' 					=> 'yes',
					'condition' 					=> [
						'trackList_layout' 			=> 'grid',
						'grid_track_artwork_show' 		=> 'yes'
					],
				]
			);
			$this->add_control(
				'track_artwork_play_on_hover',
				[
					
					'label' 						=> esc_html__( 'Show Play Icon On Hover only', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'condition' 					=> [
						'trackList_layout!' 			=> 'grid',
						'track_artwork_play_button' => 'yes',
						'track_artwork_show' 		=> 'yes'
					],
				]
			);
			$this->add_control(
				'grid_track_artwork_play_on_hover',
				[
					
					'label' 						=> esc_html__( 'Show Play Icon On Hover only', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> 'yes',
					'return_value' 				=> 'yes',
					'condition' 					=> [
						'trackList_layout' 			=> 'grid',
						'grid_track_artwork_play_button' => 'yes',
						'grid_track_artwork_show' 		=> 'yes'
					],
				]
			);
			$this->add_control(
				'track_artwork_overlay',
				[
					'label'                 		=> esc_html__( 'Image Overlay', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'separator'						=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .srp_tracklist_play_cover .sr_track_cover:after' => 'background: {{VALUE}};',
					],
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '!=',
										'value' => 'grid'
									],
									[
										'name' => 'track_artwork_show',
										'operator' => '==',
										'value' => 'yes'
									],
									[
										'name' => 'track_artwork_play_button',
										'operator' => '==',
										'value' => 'yes'
									],
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '==',
										'value' => 'grid'
									],
									[
										'name' => 'grid_track_artwork_show',
										'operator' => '==',
										'value' => 'yes'
									],
									[
										'name' => 'grid_track_artwork_play_button',
										'operator' => '==',
										'value' => 'yes'
									],
								]
							],
						]
					],
				]
			);
			$this->add_control(
				'track_artwork_play_button_color',
				[
					'label'                 		=> esc_html__( 'Play Icon Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'separator'						=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .sr_track_cover .srp_play .sricon-play' => 'color: {{VALUE}};',
													'{{WRAPPER}} .iron-audioplayer .sr_track_cover .srp_play' => 'border-color:{{VALUE}};'
					],
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '!=',
										'value' => 'grid'
									],
									[
										'name' => 'track_artwork_show',
										'operator' => '==',
										'value' => 'yes'
									],
									[
										'name' => 'track_artwork_play_button',
										'operator' => '==',
										'value' => 'yes'
									],
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '==',
										'value' => 'grid'
									],
									[
										'name' => 'grid_track_artwork_show',
										'operator' => '==',
										'value' => 'yes'
									],
									[
										'name' => 'grid_track_artwork_play_button',
										'operator' => '==',
										'value' => 'yes'
									],
								]
							],
						]
					],
				]
			);
			$this->add_responsive_control(
				'track_artwork_play_button_scale',
				[
					
					'label' 						=> esc_html__( 'Control Size Scale', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::NUMBER,
					'min' 							=> 0,
					'max' 							=> 10,
					'step' 							=> 0.1,
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '!=',
										'value' => 'grid'
									],
									[
										'name' => 'track_artwork_show',
										'operator' => '==',
										'value' => 'yes'
									],
									[
										'name' => 'track_artwork_play_button',
										'operator' => '==',
										'value' => 'yes'
									],
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '==',
										'value' => 'grid'
									],
									[
										'name' => 'grid_track_artwork_show',
										'operator' => '==',
										'value' => 'yes'
									],
									[
										'name' => 'grid_track_artwork_play_button',
										'operator' => '==',
										'value' => 'yes'
									],
								]
							],
						]
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .sr_track_cover .srp_play' => 'transform:scale({{SIZE}});',
													'{{WRAPPER}} .srp_tracklist_play_cover_hover .sr-playlist-item:not(:hover):not(.current) .sr_track_cover .srp_play'  => 'transform:scale({{SIZE}}) translateY(30%);',
													'(mobile){{WRAPPER}} .srp_tracklist_play_cover_hover .sr-playlist-item:not(:hover):not(.current) .sr_track_cover .srp_play'  => 'transform:scale({{SIZE}});',
					],
				]
			);
			$this->add_responsive_control(
				'track_artwork_size',
				[
					'label' 						=> esc_html__( 'Thumbnail Width', 'sonaar-music' ) . ' (px)',
					'type'							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 500,
						],
					],
					'size_units' 					=> [ 'px', '%' ],
					'selectors' 					=> [
													//'{{WRAPPER}} .iron-audioplayer .sonaar-grid-2' => 'grid-template-columns: auto {{SIZE}}{{UNIT}};',
													'{{WRAPPER}} .iron-audioplayer .playlist li .sr_track_cover' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					],
					'condition' 					=> [
						'track_artwork_show' 		=> 'yes',
						'trackList_layout!' 		=> 'grid',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 							=> 'grid_track_artwork_format', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
					'default' 						=> 'large',
					'separator' 					=> 'none',
					'exclude' 						=> ['custom'],
					'condition' 					=> [
						'grid_track_artwork_show' 	=> 'yes',
						'trackList_layout' 			=> 'grid',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 							=> 'list_track_artwork_format', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
					'default' 						=> 'thumbnail',
					'separator' 					=> 'none',
					'exclude' 						=> ['custom'],
					'condition' 					=> [
						'track_artwork_show' 		=> 'yes',
						'trackList_layout!' 		=> 'grid',
					],
				]
			);
			$this->add_control(
				'track_artwork_radius',
				[
					'label' 						=> esc_html__( 'Image Radius', 'elementor' ),
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 200,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .playlist li .sr_track_cover, {{WRAPPER}} .srp_tracklist_play_cover .sr_track_cover:after' => 'border-radius: {{SIZE}}px;',
					],
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '!=',
										'value' => 'grid'
									],
									[
										'name' => 'track_artwork_show',
										'operator' => '==',
										'value' => 'yes'
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '==',
										'value' => 'grid'
									],
									[
										'name' => 'grid_track_artwork_show',
										'operator' => '==',
										'value' => 'yes'
									]
								]
							],
						]
					],
				]
			);
			$this->add_control(
				'alignment_options',
				[
					'label' 						=> esc_html__( 'Tracklist Alignments', 'sonaar-music' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist',
					],
				]
			);
			$this->add_responsive_control(
				'playlist_justify',
				[
					'label' 						=> esc_html__( 'Tracklist Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> 'center',
					'selectors' 					=> [
														'{{WRAPPER}} .iron-audioplayer .sonaar-grid' => 'justify-content: {{VALUE}};',
														'{{WRAPPER}}.sr_playlist_below_artwork_auto .iron-audioplayer .sonaar-grid' => 'align-items:{{VALUE}}',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist'
					],
				]
			);
			$this->add_responsive_control(
				'artwork_align',
				[
					'label' 						=> esc_html__( 'Image Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> '',
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .sonaar-Artwort-box' => 'justify-content: {{VALUE}};',
													//'{{WRAPPER}} .iron-audioplayer .sonaar-Artwort-box' => 'justify-self: {{VALUE}}!important; text-align: {{VALUE}};',
					],
					'conditions' 					=> [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'sr_player_on_artwork',
								'operator' => '==',
								'value' => ''
							],
							[
								'name' => 'playlist_hide_artwork',
								'operator' => '==',
								'value' => ''
							],
							[
								'name' => 'playlist_show_playlist',
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'move_playlist_below_artwork',
								'operator' => '!=',
								'value' => ''
							],
							[
								'name' => 'player_layout',
								'operator' => '!=',
								'value' => 'skin_button'
							],
						]
					],
				]
			);
			$this->add_responsive_control(
				'playlist_width',
				[
					'label' 						=> esc_html__( 'Tracklist Width', 'sonaar-music' ) . ' (px)',
					'type'							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 2000,
						],
					],
					'size_units' 					=> [ 'px', 'vw', '%' ],
					'selectors' 					=> [
													//'{{WRAPPER}} .iron-audioplayer .sonaar-grid-2' => 'grid-template-columns: auto {{SIZE}}{{UNIT}};',
													'{{WRAPPER}} .iron-audioplayer .playlist, {{WRAPPER}} .iron-audioplayer .sonaar-Artwort-box, {{WRAPPER}} .iron-audioplayer .buttons-block' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist'
					],
					'render_type'					=> 'template',
				]
			);
			$this->add_control(
				'title_options',
				[
					'label' 						=> sprintf( esc_html__( '%1$s Title', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('playlist/podcast')) ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist',
					],
				]
			);
			$this->add_control(
				'title_html_tag_playlist',
				[
					'label' => esc_html__( 'HTML Title Tag', 'sonaar-music' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
						'span' => 'span',
						'p' => 'p',
					],
					'default' => 'h3',
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist'
					],
				]
			);
			$this->add_control(
				'title_btshow',
				[
					'label' 						=> esc_html__( 'Hide Title', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'none',
					'selectors' 					=> [
						 							'{{WRAPPER}} .playlist .sr_it-playlist-title' => 'display:{{VALUE}};',
					 ],
					 'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist'
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'title_typography',
					'label' 						=> esc_html__( 'Title Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'condition' 					=> [
						'title_btshow' 				=> '',
						'player_layout' 	=> 'skin_float_tracklist'
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .sr_it-playlist-title',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label'                			=> esc_html__( 'Title Color', 'sonaar-music' ),
					'type'                 			=> Controls_Manager::COLOR,
					'default'               		=> '',
					'condition' 					=> [
						'title_btshow'				=> '',
						'player_layout' 	=> 'skin_float_tracklist'
					],
					'selectors'             		=> [
													'{{WRAPPER}} .playlist .sr_it-playlist-title, {{WRAPPER}} .srp_player_meta' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'title_align',
				[
					'label' 						=> esc_html__( 'Title Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'left'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'right' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> '',
					'condition' 					=> [
						'title_btshow'				=> '',
						'player_layout' 	=> 'skin_float_tracklist',
					],
					'selectors' 					=> [
													'{{WRAPPER}} .sr_it-playlist-title, {{WRAPPER}} .sr_it-playlist-artists, {{WRAPPER}} .srp_subtitle' => 'text-align: {{VALUE}}!important;',
													'{{WRAPPER}} .iron-audioplayer .srp_player_meta' => 'justify-content: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'title_indent',
				[
					
					'label' 						=> esc_html__( 'Title Indent', 'sonaar-music' ) . ' (px)',
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'min' 					=> -500,
						],
					],
					'condition' 					=> [
						'title_btshow' 				=> '',
						'player_layout' 	=> 'skin_float_tracklist',
					],
					'selectors' 					=> [
													'{{WRAPPER}} .sr_it-playlist-title' => 'margin-left: {{SIZE}}px;',
													'{{WRAPPER}} .sr_it-playlist-artists' => 'margin-left: {{SIZE}}px;',
													'{{WRAPPER}} .srp_subtitle' => 'margin-left: {{SIZE}}px;',
					],
				]
			);

			$this->add_control(
				'subtitle_options',
				[
					'label' 						=> sprintf( esc_html__( '%1$s Subtitle', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('playlist/podcast')) ),
					'type' 							=> Controls_Manager::HEADING,
					'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist',
					],
					'separator' 					=> 'before',
				]
			);
			$this->add_control(
				'subtitle_btshow',
				[
					'label' 						=> esc_html__( 'Hide Subtitle', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'none',
					'selectors' 					=> [
							 						'{{WRAPPER}} .playlist .srp_subtitle' => 'display:{{VALUE}}!important;',
					 ],
					 'condition' 					=> [
						'player_layout' 	=> 'skin_float_tracklist',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'subtitle_typography',
					'label' 						=> esc_html__( 'Subtitle Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'condition' 					=> [
						'subtitle_btshow' 			=> '',
						'player_layout' 	=> 'skin_float_tracklist',
					],
					'selector' 						=> '{{WRAPPER}} .playlist .srp_subtitle',
					
				]
			);
			$this->add_control(
				'subtitle-color',
				[
					'label'                		 	=> esc_html__( 'Subtitle Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'condition' 					=> [
						'subtitle_btshow' 			=> '',
						'player_layout' 	=> 'skin_float_tracklist',
					],
					'selectors'             		=> [
													'{{WRAPPER}} .playlist .srp_subtitle' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'track_options',
				[
					'label' 						=> esc_html__( 'Tracklist', 'elementor' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'track_title_typography',
					'label' 						=> sprintf( esc_html__( '%1$s Title Typography', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .playlist .audio-track, {{WRAPPER}} .iron-audioplayer .playlist .track-number, {{WRAPPER}} .iron-audioplayer .playlist',
				]
			);
			$this->add_control(
				'tracktitle_white_space',
				[
					'label' 					=> esc_html__( 'Wrap title on multiple lines', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'selectors' 				=> [
												'{{WRAPPER}} .playlist .tracklist-item-title' => 'white-space: normal;overflow:visible;text-overflow:initial;',
						 
				 ],
				]
			);
			$this->start_controls_tabs( 'tabs_tracktitle_style' );
			$this->start_controls_tab(
				'tab_tracktitle_normal',
				[
					'label' 						=> esc_html__( 'Normal', 'elementor' ),
				]
			);
			$this->add_control(
				'track_title_color',
				[
					'label' 						=> sprintf( esc_html__( '%1$s Title Color', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .iron-audioplayer .playlist .audio-track, {{WRAPPER}} .iron-audioplayer .playlist .track-number,  {{WRAPPER}} .iron-audioplayer .player, {{WRAPPER}} .iron-audioplayer .sr-playlist-item .srp_noteButton, {{WRAPPER}} .srp_track_description, {{WRAPPER}} .sr-playlist-cf-container, {{WRAPPER}} .srp_notfound' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'track_bgcolor',
				[
					'label'                			=> esc_html__( 'Tracklist Item Background', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .sr-playlist-item'=> 'background: {{VALUE}}',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist'
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'track_box_shadow_normal',
					'selector' 						=> '{{WRAPPER}} .sr-playlist-item',
				]
			);
			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_tracktitle_hover',
				[
					'label' 						=> esc_html__( 'Hover', 'elementor' ),
				]
			);
				$this->add_control(
					'tracklist_hover_color',
					[
						'label' 						=> sprintf( esc_html__( '%1$s Title Color', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
						'type'                  		=> Controls_Manager::COLOR,
						'default'               		=> '',
						'selectors'             		=> [
														'{{WRAPPER}} .iron-audioplayer .playlist .audio-track:hover, {{WRAPPER}} .iron-audioplayer .playlist .audio-track:hover .track-number, {{WRAPPER}} .iron-audioplayer .playlist a.song-store:not(.sr_store_wc_round_bt):hover, {{WRAPPER}} .iron-audioplayer .playlist .current a.song-store:not(.sr_store_wc_round_bt):hover' => 'color: {{VALUE}}',
													],
					]
				);
				$this->add_control(
					'track_bgcolor_hover',
					[
						'label'                			=> esc_html__( 'Tracklist Item Background', 'sonaar-music' ),
						'type'                 		 	=> Controls_Manager::COLOR,
						'default'               		=> '',
						'selectors'            		 	=> [
														'{{WRAPPER}} .sr-playlist-item:hover'=> 'background-color: {{VALUE}};',
						],
					]
				);
				
				$this->add_control(
					"track_translate_popover_hover",
					[
						'label' => esc_html__( 'Transform FX', 'elementor' ),
						'type' => Controls_Manager::POPOVER_TOGGLE,
					
					]
				);
				$this->start_popover();
				$this->add_responsive_control(
					'track_scale_hover',
					[
						
						'label' 						=> esc_html__( 'Scale', 'sonaar-music' ),
						'type' 							=> \Elementor\Controls_Manager::NUMBER,
						'min' 							=> 0,
						'max' 							=> 10,
						'step' 							=> 0.01,
						'default' 						=> 1,
						'condition' => [
							"track_translate_popover_hover!" => '',
						],
						'selectors' 					=> [
														'{{WRAPPER}} .sr-playlist-item:hover' => '--srp-trackhover-scale:{{SIZE}};',
						],
					]
				);
				$this->add_responsive_control(
					"track_translateX_hover",
					[
						'label' => esc_html__( 'Offset X', 'elementor' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => ['px' ],
						'range' => [
							
							'px' => [
								'min' => -200,
								'max' => 200,
							],
						],
						'condition' => [
							"track_translate_popover_hover!" => '',
						],
						'selectors' => [
							"{{WRAPPER}} .sr-playlist-item:hover" => '--srp-trackhover-translateX: {{SIZE}}{{UNIT}};',
						],
						'frontend_available' => true,
					]
				);
	
				$this->add_responsive_control(
					"track_translateY_hover",
					[
						'label' => esc_html__( 'Offset Y', 'elementor' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => ['px' ],
						'range' => [
							
							'px' => [
								'min' => -20,
								'max' => 20,
							],
						],
						'condition' => [
							"track_translate_popover_hover!" => '',
						],
						'selectors' => [
							"{{WRAPPER}} .sr-playlist-item:hover" => '--srp-trackhover-translateY: {{SIZE}}{{UNIT}};',
						],
						'frontend_available' => true,
					]
				);
				
			$this->end_popover();
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'track_box_shadow_hover',
					'selector' 						=> '{{WRAPPER}}  .sr-playlist-item:hover',
				]
			);
				
			$this->end_controls_tab();
			$this->start_controls_tab(
				'tab_tracktitle_active',
				[
					'label' 						=> esc_html__( 'Active', 'elementor' ),
				]
			);
				$this->add_control(
					'tracklist_active_color',
					[
						'label' 						=> sprintf( esc_html__( '%1$s Title Color', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
						'type'                 			=> Controls_Manager::COLOR,
						'default'              			=> '',
						'selectors'             		=> [
														'{{WRAPPER}} .iron-audioplayer.audio-playing .playlist .current .audio-track, {{WRAPPER}} .iron-audioplayer.audio-playing .playlist .current .audio-track .track-number, {{WRAPPER}} .iron-audioplayer.audio-playing .playlist .current .audio-track .srp_trackartist' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'track_bgcolor_active',
					[
						'label'                			=> esc_html__( 'Tracklist Item Background', 'sonaar-music' ),
						'type'                 		 	=> Controls_Manager::COLOR,
						'default'               		=> '',
						'selectors'            		 	=> [
														'{{WRAPPER}} .audio-playing .current.sr-playlist-item'=> 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					"track_translate_popover_active",
					[
						'label' => esc_html__( 'Transform FX', 'elementor' ),
						'type' => Controls_Manager::POPOVER_TOGGLE,
					
					]
				);
				$this->start_popover();
					$this->add_responsive_control(
						'track_scale_active',
						[
							
							'label' 						=> esc_html__( 'Scale', 'sonaar-music' ),
							'type' 							=> \Elementor\Controls_Manager::NUMBER,
							'min' 							=> 0,
							'max' 							=> 10,
							'step' 							=> 0.01,
							'default' 						=> 1,
							'condition' => [
								"track_translate_popover_active!" => '',
							],
							'selectors' 					=> [
															'{{WRAPPER}} .audio-playing .current.sr-playlist-item' => '--srp-trackactive-scale:{{SIZE}};',
							],
						]
					);
					$this->add_responsive_control(
						"track_translateX_active",
						[
							'label' => esc_html__( 'Offset X', 'elementor' ),
							'type' => Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => -200,
									'max' => 200,
								],
							],
							'condition' => [
								"track_translate_popover_active!" => '',
							],
							'selectors' => [
								"{{WRAPPER}} .audio-playing .current.sr-playlist-item" => '--srp-trackactive-translateX: {{SIZE}}{{UNIT}};',
							],
							'frontend_available' => true,
						]
					);
		
					$this->add_responsive_control(
						"track_translateY_active",
						[
							'label' => esc_html__( 'Offset Y', 'elementor' ),
							'type' => Controls_Manager::SLIDER,
							'size_units' => ['px' ],
							'range' => [
								'px' => [
									'min' => -20,
									'max' => 20,
								],
							],
							'condition' => [
								"track_translate_popover_active!" => '',
							],
							'selectors' => [
								"{{WRAPPER}} .audio-playing .current.sr-playlist-item" => '--srp-trackactive-translateY: {{SIZE}}{{UNIT}};',
							],
							'frontend_available' => true,
						]
					);
				$this->end_popover();
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'track_box_shadow',
						'selector' 						=> '{{WRAPPER}} .audio-playing .current.sr-playlist-item',
					]
				);
				$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->add_control(
				'tracklist_transition',
				[
					'label' => esc_html__( 'Transition Duration (ms)', 'sonaar-music' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 50,
							'max' => 2000,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .iron-audioplayer .playlist li' => 'transition: all {{SIZE}}ms',
					],
				]
			);
			if( Sonaar_Music::get_option('show_artist_name', 'srmp3_settings_general') ){
				$this->add_control(
					'artist_hide',
					[
						'label' 					=> esc_html__( 'Hide Artist Name', 'sonaar-music' ),
						'type' 						=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 					=> esc_html__( 'Hide', 'sonaar-music' ),
						'label_off' 				=> esc_html__( 'Show', 'sonaar-music' ),
						'return_value' 				=> 'true',
						'separator' 				=> 'before',
						'default'					=> 'false',
					]
				);
				$this->add_control(
					'artist_wrap',
					[
						'label' 					=> esc_html__( 'Display Artist Name below Track Title', 'sonaar-music' ),
						'type' 						=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 				=> 'true',
						'default'					=> '',
						'condition' 			=> [
							'artist_hide!' => 'true',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' 							=> 'artist_typography',
						'label' 						=> esc_html__( 'Artist Name Typography', 'sonaar-music' ),
						'global' => [
							'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
						],
						'selector' 						=> '{{WRAPPER}} .iron-audioplayer .srp_trackartist',
						'condition' 			=> [
							'artist_hide!' => 'true',
						],
					]
				);
				$this->add_control(
					'artist_color',
					[
						'label'                			=> esc_html__( 'Artist Name Color', 'sonaar-music' ),
						'type'                 			=> Controls_Manager::COLOR,
						'default'               		=> '',
						'selectors'             		=> [
														'{{WRAPPER}} .iron-audioplayer .srp_trackartist' => 'color: {{VALUE}}',
						],
						'condition' 			=> [
							'artist_hide!' => 'true',
						],
					]
				);
			}
			$this->add_control(
				'track_separator_color',
				[
					'label' 						=> sprintf( esc_html__( '%1$s Separator Color', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::COLOR,
					'separator' 					=> 'before',
					'default' 						=> '',
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .playlist ul.srp_list > li' => 'border-bottom: solid 1px {{VALUE}};',
					],
					'condition' 			=> [
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_control(
				'track_list_linked',
				[
					'label' 						=> sprintf( esc_html__( 'Link title to the %1$s page', 'sonaar-music' ), Sonaar_Music_Admin::sr_GetString('playlist') ),
					'type' 						=> Controls_Manager::SELECT,
					'options' => [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_post_link', 'srmp3_settings_widget_player') ),
						'true' => esc_html__( 'Yes', 'sonaar-music' ),
						'false' => esc_html__( 'No', 'sonaar-music' ),
					],
					'default'					=> 'default',
					'conditions' 					=> [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'playlist_source',
								'operator' => '!=',
								'value' => 'from_elementor'
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'playlist_show_playlist',
												'operator' => '!=',
												'value' => ''
											],
											[
												'name' => 'player_layout',
												'operator' => '!=',
												'value' => 'skin_button'
											]
										]
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'playlist_show_playlist_skin_button',
												'operator' => '!=',
												'value' => ''
											],
											[
												'name' => 'player_layout',
												'operator' => '==',
												'value' => 'skin_button'
											]
										]
									],
								]
							]
						]
					],

				]
			);
			$this->add_responsive_control(
				'tracklist_spacing',
				[
					'label' 						=> sprintf( esc_html__( '%1$s Spacing', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 50,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_float_tracklist"]:not(.srp_tracklist_grid) .playlist .sr-playlist-item' => 'padding-top: {{SIZE}}px;padding-bottom: {{SIZE}}px;',
													'{{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"]:not(.srp_tracklist_grid) .sr-playlist-item + .sr-playlist-item' => 'margin-top: {{SIZE}}px;',
													'{{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .sr-cf-heading' => 'margin-bottom: {{SIZE}}px;',
													'{{WRAPPER}} .srp_tracklist_grid .srp_tracklist > ul' => 'gap: {{SIZE}}px;',
					],
				]
			);
			$this->add_control(
				'track_radius',
				[
					'label' 						=> esc_html__( 'Tracklist Item Radius', 'elementor' ),
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 200,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .playlist li.sr-playlist-item' => 'border-radius: {{SIZE}}px;',
					],
				]
			);
			$this->add_responsive_control(
				'track_padding',
				[
					'label' 						=> esc_html__( 'Tracklist Item Padding', 'sonaar-music' ) . ' (px)', 
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .playlist li.sr-playlist-item' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
													'{{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"].srp_tracklist_grid .sr_track_cover' => 'width:calc(100% + {{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}})!important; margin-left: calc(-1 * {{LEFT}}{{UNIT}});  margin-top: calc(-1 * {{TOP}}{{UNIT}}); margin-bottom: {{TOP}}{{UNIT}};',
												],
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist'
					],
				]
			);
			$this->add_control(
				'play_pause_bt_show',
				[
					'label' 						=> esc_html__( 'Hide Play/Pause Button', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default'						=> '',
					'return_value' 					=> 'yes',
					'separator' 					=> 'before',
					'selectors' => [
						'{{WRAPPER}} .sr-playlist-item .sricon-play' => 'display:none;',
						'{{WRAPPER}} .iron-audioplayer .track-number' => 'padding-left: 0 !important;',
					],
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '!=',
										'value' => 'grid'
									],
									[
										'name' => 'track_artwork_play_button',
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '==',
										'value' => 'grid'
									],
									[
										'name' => 'grid_track_artwork_play_button',
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							],
						]
					],
				]
			);
			$this->add_control(
				'tracklist_controls_color',
				[
					'label'                			=> esc_html__( 'Play/Pause Button Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'              		 	=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .sr-playlist-item .sricon-play' => 'color: {{VALUE}}',
					],
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '!=',
										'value' => 'grid'
									],
									[
										'name' => 'play_pause_bt_show',
										'operator' => '==',
										'value' => ''
									],
									[
										'name' => 'track_artwork_play_button',
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '==',
										'value' => 'grid'
									],
									[
										'name' => 'play_pause_bt_show',
										'operator' => '==',
										'value' => ''
									],
									[
										'name' => 'grid_track_artwork_play_button',
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							],
						]
					],
				]
			);
			$this->add_responsive_control(
				'tracklist_controls_size',
				[
					'label' => esc_html__( 'Play/Pause Button Size', 'sonaar-music' ) . ' (px)',
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 50,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sr-playlist-item .track-number .sricon-play:before' => 'font-size: {{SIZE}}px;',
						'{{WRAPPER}} .iron-audioplayer .track-number' => 'padding-left: calc({{SIZE}}px + 12px);',
						'{{MOBILE}}{{WRAPPER}} .iron-audioplayer .srp_tracklist-item-date' => 'padding-left: calc({{SIZE}}px + 12px);',
					],
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '!=',
										'value' => 'grid'
									],
									[
										'name' => 'play_pause_bt_show',
										'operator' => '==',
										'value' => ''
									],
									[
										'name' => 'track_artwork_play_button',
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'trackList_layout',
										'operator' => '==',
										'value' => 'grid'
									],
									[
										'name' => 'play_pause_bt_show',
										'operator' => '==',
										'value' => ''
									],
									[
										'name' => 'grid_track_artwork_play_button',
										'operator' => '!=',
										'value' => 'yes'
									]
								]
							],
						]
					],
				]
			);
			
			if( Sonaar_Music::get_option('hide_track_number', 'srmp3_settings_widget_player') != 'true') {
				$this->add_control(
					'hide_number_btshow',
					[
						'label' 						=> sprintf( esc_html__( 'Hide %1$s Number', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
						'type' 							=> Controls_Manager::SWITCHER,
						'default' 						=> '',
						'separator' 					=> 'before',
						'return_value' 					=> 'none',
						'selectors' 					=> [
														'{{WRAPPER}} .iron-audioplayer .track-number .number' => 'display:{{VALUE}};',
														'{{WRAPPER}} .iron-audioplayer .track-number' => 'padding-right:0;',
						],
						'condition' 			=> [
							'trackList_layout!' => 'grid',
						],
					]
				);
				$this->add_control(
					'hide_number_btshow_grid',
					[
						'label' 						=> sprintf( esc_html__( 'Hide %1$s Number', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
						'type' 							=> Controls_Manager::SWITCHER,
						'default' 						=> '1',
						'separator' 					=> 'before',
						'return_value' 					=> '1',
						'selectors' 					=> [
														'{{WRAPPER}} .iron-audioplayer .track-number .number' => 'display:none;',
														'{{WRAPPER}} .iron-audioplayer .track-number' => 'padding-right:0;',
						],
						'condition' 			=> [
							'trackList_layout' => 'grid',
						],
					]
				);
			}
			$this->add_control(
					'hide_time_duration',
					[
						'label' 					=> sprintf( esc_html__( 'Hide %1$s Duration', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
						'type' 						=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 				=> 'none',
						'separator' 				=> 'before',
						'default'					=> '',
						'selectors' 				=> [
							 							'{{WRAPPER}} .iron-audioplayer .tracklist-item-time' => 'display:{{VALUE}};'
					 ],
					]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'duration_typography',
					'label' 						=> esc_html__( 'Duration Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'condition' 					=> [
						'hide_time_duration' 		=> '',
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .tracklist-item-time',
				]
			);
			$this->add_control(
				'duration_color',
				[
					'label'                			=> esc_html__( 'Duration Color', 'sonaar-music' ),
					'type'                 			=> Controls_Manager::COLOR,
					'default'               		=> '',
					'condition' 					=> [
						'hide_time_duration' 		=> '',
					],
					'selectors'             		=> [
													'{{WRAPPER}} .tracklist-item-time' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'show_track_publish_date',
				[
					'label' 					=> esc_html__( 'Show Publish Date', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SELECT,
					'options' => [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('player_show_track_publish_date', 'srmp3_settings_widget_player') ),
						'true' => esc_html__( 'Yes', 'sonaar-music' ),
						'false' => esc_html__( 'No', 'sonaar-music' ),
					],
					'separator' 				=> 'before',
					'default'					=> 'default',
					'condition' 					=> [
						'playlist_show_playlist!' 	=> '',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'date_typography',
					'label' 						=> esc_html__( 'Publish Date Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .srp_tracklist-item-date',
					'condition' 					=> [
						'playlist_show_playlist!' 	=> '',
					],
				]
			);
			$this->add_control(
				'date_color',
				[
					'label'                			=> esc_html__( 'Publish Date Color', 'sonaar-music' ),
					'type'                 			=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .srp_tracklist-item-date' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'playlist_show_playlist!' 	=> '',
					],
				]
			);
			$this->add_control(
				'hide_trackdesc',
				[
					'label' 					=> sprintf( esc_html__( 'Hide %1$s Description', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' => esc_html__( 'No', 'sonaar-music' ),
					'return_value' => '1',
					'default' => '0',
					'separator' 				=> 'before',
					
				]
			);
			$this->add_control(
				'track_desc_postcontent',
				[
					'label' 						=> esc_html__( 'Use Post Content', 'sonaar-music' ),
					'description' 					=> esc_html__( 'We will use the post content for the description instead of the track description field', 'sonaar-music' ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> 'true',
					'default'						=> '',
					/*'condition'						=> [
						'hide_trackdesc!' 			=> '1',
					],*/
					
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'track_desc_typography',
					'label' 						=> esc_html__( 'Description Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} div.srp_track_description',
					'condition' => [
						'hide_trackdesc!' => '1',
					],
				]
			);
			$this->add_control(
				'track_desc_color',
				[
					'label'                			=> esc_html__( 'Description Color', 'sonaar-music' ),
					'type'                 			=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
													'{{WRAPPER}} div.srp_track_description' => 'color: {{VALUE}}',
					],
					'condition' => [
						'hide_trackdesc!' => '1',
					],
				]
			);
			$this->add_control(
				'track_desc_lenght',
				[
					'label' => esc_html__( 'Description Lenght', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100000,
					'step' => 1,
					'default' => 55,
					'condition' => [
						'hide_trackdesc!' => '1',
					],
				]
			);
			$this->add_control(
				'strip_html_track_desc',
				[
					'label' => esc_html__( 'Strip HTML', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' => esc_html__( 'No', 'sonaar-music' ),
					'return_value' => '1',
					'default' => '1',
					'condition' => [
						'hide_trackdesc!' => '1',
					],
				]
			);
			$this->add_control(
				'hide_info_icon',
				[
					'label'							=> esc_html__( 'Hide Info Icon', 'sonaar-music'),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value'					=> 'yes',
					'default' 						=> '',
					'selectors' 				=> [
						'{{WRAPPER}} .srp_tracklist .srp_noteButton, {{WRAPPER}} .srp_info_spacer' => 'display:none;'
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'info_title_typography',
					'label' 						=> esc_html__( 'Info Title Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} .playlist .srp_note_title',
					'condition' 					=> [
						'hide_info_icon!' => 'yes',
					],
				]
			);	
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'info_text_typography',
					'label' 						=> esc_html__( 'Info Text Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} .playlist .srp_note',
					'condition' 					=> [
						'hide_info_icon!' => 'yes',
					],
				]
			);	
			$this->add_control(
				'info_font_color',
				[
					'label' 						=> esc_html__( 'Info Fonts Colors', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors' 						=> ['{{WRAPPER}} .playlist .srp_note' => 'color: {{VALUE}}'],
					'condition' 					=> [
						'hide_info_icon!' => 'yes',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' 							=> 'info_background',
					'label' 						=> esc_html__( 'Info Background', 'sonaar-music' ),
					'types' 						=> [ 'classic', 'gradient'],
					'selector' 						=> '{{WRAPPER}} .playlist .srp_note',
					'condition' 					=> [
						'hide_info_icon!' => 'yes',
					],
				]
			);
			$this->add_control(
				'info_background_radius',
				[
					'label' 						=> esc_html__( 'Info Background Radius', 'elementor' ),
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 200,
						],
					],
					'selectors' 						=> ['{{WRAPPER}} .playlist .srp_note' => 'border-radius: {{SIZE}}px;'],
					'condition' 					=> [
						'hide_info_icon!' => 'yes',
					],
				]
			);
			$this->add_control(
				'cta_playlist_options',
				[
					'label' 						=> esc_html__( 'Tracklist Container', 'elementor' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'playlist_bgcolor',
					'label' => esc_html__( 'Background', 'elementor-sonaar' ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_boxed_tracklist"] .playlist, {{WRAPPER}} .iron-audioplayer[data-playertemplate="skin_float_tracklist"] .sonaar-grid',
				]
			);
			
			$this->add_responsive_control(
				'playlist_margin',
				[
					'label' 						=> esc_html__( 'Container Margin', 'sonaar-music' ) . ' (px)', 
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .playlist' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 					=> [
						'player_layout' 	=> ['skin_float_tracklist', 'skin_button']
					],
				]
			);
			$this->add_responsive_control(
				'playlist_padding',
				[
					'label' 						=> esc_html__( 'Container Padding', 'sonaar-music' ) . ' (px)', 
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .playlist' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist'
					],
				]
			);
			$this->add_responsive_control(
				'tracklist_margin',
				[
					'label' 						=> esc_html__( 'Tracklist Margin', 'sonaar-music' ) . ' (px)', 
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .srp_tracklist' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'player_layout' 	=> ['skin_float_tracklist', 'skin_button']
					],
				]
			);
			$this->end_controls_section();

			/**
			* STYLE: TRACKLIST PROGRESS BAR
			* -------------------------------------------------
			*/
			$this->start_controls_section(
	            'playlist_soundwave',
	            [
	                'label'                			=> esc_html__( 'Tracklist: Waveform', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout',
										'operator' => '==',
										'value' => 'skin_button'
									],
									[
										'name' => 'playlist_show_playlist_skin_button',
										'operator' => '!=',
										'value' => ''
									]
								]	
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout',
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'playlist_show_playlist',
										'operator' => '!=',
										'value' => ''
									]
								]	
							],
						]
					],
				]
			);
			$this->add_control(
				'tracklist_soundwave_show',
				[
					'label' 					=> sprintf( esc_html__( 'Enable Waveform on Each %1$s', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track'))),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 				=> 'true',
					'default'					=> '',
				]
			);
			$this->add_control(
				'tracklist_soundwave_style',
				[
					'label' 				=> esc_html__( 'Style', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'separator'						=> 'before',
					'options' 				=> [
						'default' 			=> esc_html__( 'Default', 'sonaar-music' ),
						'mediaElement' 		=> esc_html__( 'Waveform', 'sonaar-music' ),
						'simplebar' 		=> esc_html__( 'Simple Bar', 'sonaar-music' ),
					],
					'default' 				=> 'default',
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
					],
				]
			);
			$this->add_control(
				'tracklist_soundwave_progress_color',
				[
					'label'                 		=> esc_html__( 'Progress Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
						'{{WRAPPER}} .sr-playlist-item .sonaar_wave_cut rect' => 'fill: {{VALUE}}',
						'{{WRAPPER}} .sr_waveform_simplebar .sr-playlist-item .sonaar_wave_cut' => 'background-color: {{VALUE}}',
					],
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
					],
					'render_type' => 'template',
					
				]
			);
			$this->add_control(
				'tracklist_soundwave_color',
				[
					'label'                 		=> esc_html__( 'Progress Background Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
						'{{WRAPPER}} .sr-playlist-item .sonaar_wave_base rect' => 'fill: {{VALUE}}',
						'{{WRAPPER}} .sr_waveform_simplebar .sr-playlist-item .sonaar_wave_base' => 'background-color: {{VALUE}}',
					],		
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
					],
					'render_type' => 'template',
				]
			);
			$this->add_responsive_control(
				'tracklist_soundwave_bar_width',
				[
					
					'label' 						=> esc_html__( 'Bar Width(px)', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SLIDER,
					'render_type'					=> 'template',
					'range' 						=> [
						'px' => [
							'min'					=> 1,
							'max' 					=> 20,
						]
					],
					'size_units' => [ 'px'],
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
						'tracklist_soundwave_style!' => 'simplebar',
					],
				]
			);
			$this->add_responsive_control(
				'tracklist_soundwave_bar_gap',
				[
					
					'label' 						=> esc_html__( 'Bar Gap(px)', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SLIDER,
					'render_type'					=> 'template',
					'range' 						=> [
						'px' 						=> [
							'min'					=> 0,
							'max' 					=> 20,
						]
					],
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
						'tracklist_soundwave_style!' => 'simplebar',
					],
				]
			);
			$this->add_responsive_control(
				'tracklist_simple_bar_radius',
				[
					'label' 						=> esc_html__( 'Bar Radius', 'elementor' ),
					'type' 							=> Controls_Manager::SLIDER,
					
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 20,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' 					=> [
													'{{WRAPPER}} .sr_waveform_simplebar .sr-playlist-item .sonaar_fake_wave .sonaar_wave_base, {{WRAPPER}} .sr_waveform_simplebar .sr-playlist-item .sonaar_fake_wave .sonaar_wave_cut' => 'border-radius: {{SIZE}}px;',
					],
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
						'tracklist_soundwave_style!' => 'mediaElement',
					],
				]
			);
			$this->add_control(
				'tracklist_soundwave_linecap',
				[
					'label' => esc_html__( 'Bar Line Cap', 'sonaar-music' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default' => esc_html__( 'Default (Plugin Settings)', 'sonaar-music' ),
						'square' => esc_html__( 'Square', 'sonaar-music' ),
						'round' => esc_html__( 'Round', 'sonaar-music' ),
						'butt' => esc_html__( 'Butt', 'sonaar-music' ),
					],
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
						'tracklist_soundwave_style!' => 'simplebar',
					],
				]
			);
			$this->add_responsive_control(
				'tracklist_simple_bar_height',
				[
					
					'label' 						=> esc_html__( 'Canvas Height(px)', 'sonaar-music' ),
					'separator'						=> 'before',
					'type' 							=> Controls_Manager::SLIDER,
					'render_type'					=> 'template',
					'range' 						=> [
					'px' 						=> [
						'min'					=> 1,
						'max' 					=> 150,
					],
					],
					'selectors' 					=> [
						'{{WRAPPER}} .sr-playlist-item .wave, {{WRAPPER}} .sr-playlist-item .sonaar_fake_wave, {{WRAPPER}} .sr_waveform_simplebar .sr-playlist-item .sonaar_fake_wave .sonaar_wave_base, {{WRAPPER}} .sr_waveform_simplebar .sr-playlist-item .sonaar_fake_wave .sonaar_wave_cut' => 'height: {{SIZE}}px !important;',
					],
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
					],
				]
			);
			$this->add_responsive_control(
				'tracklist_simple_bar_width',
				[
					'label' 					=> esc_html__( 'Canvas Width(px)', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'render_type'					=> 'template',
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2000,
							'step' => 1,
						],
					],
					'size_units' => ['px'],
					'selectors' 				=> [
								'{{WRAPPER}} .iron-audioplayer .audio-track .srp_soundwave_wrapper' => 'flex: 0 1 {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .iron-audioplayer .audio-track .sonaar_wave_base, {{WRAPPER}} .iron-audioplayer .audio-track .sonaar_fake_wave' => 'max-width: {{SIZE}}{{UNIT}};',
					],
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
					],
				]
			);

			$this->add_responsive_control(
				'tracklist_title_width_with_soundwave',
				[
					'label' 					=> esc_html__( 'Canvas Horizontal Position(px)', 'sonaar-music' ),
					'type' 						=> Controls_Manager::SLIDER,
					'render_type'					=> 'template',
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => ['px'],
					'selectors' 				=> [
								'{{WRAPPER}} .iron-audioplayer .playlist.srp_tracklist_waveform_enabled .tracklist-item-title' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
					],
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
					],
				]
			);
			$this->add_control(
				'tracklist_soundwave_cursor',
				[
					'label' 						=> esc_html__( 'Show Cursor Duration on Hover', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> 'true',
					'return_value' 					=> 'true',
					'separator'						=> 'before',
					'condition' 			=> [
						'tracklist_soundwave_show' => 'true',
					],
				]
			);
			$this->add_control(
				'tracklist_soundwave_cursor_color',
				[
					'label'                 		=> esc_html__( 'Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
						'.sr_tracklenght_tooltip' => 'color: {{VALUE}}',
					],
					'condition' 			=> [
						'tracklist_soundwave_cursor' => 'true',
						'tracklist_soundwave_show' => 'true',
					],
					'render_type' => 'template',
					
				]
			);
			$this->add_control(
				'tracklist_soundwave_cursor_bg_color',
				[
					'label'                 		=> esc_html__( 'Background Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
						'.sr_tracklenght_tooltip' => 'background-color: {{VALUE}}',
					],		
					'condition' 			=> [
						'tracklist_soundwave_cursor' => 'true',
						'tracklist_soundwave_show' => 'true',
					],
					'render_type' => 'template',
				]
			);
			$this->add_control(
				'tracklist_soundwave_cursor_verticalbar_color',
				[
					'label'                 		=> esc_html__( 'Vertical Bar Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'             		=> [
						'.sr_tracklenght_tooltip_vertical' => 'background-color: {{VALUE}}',
					],		
					'condition' 			=> [
						'tracklist_soundwave_cursor' => 'true',
						'tracklist_soundwave_show' => 'true',
					],
					'render_type' => 'template',
				]
			);
			$this->end_controls_section();
			/**
			* STYLE: CUSTOM COLUMNS & SEARCH BAR
			* -------------------------------------------------
			*/
			
			$this->start_controls_section(
				'searchbar',
				[
					'label'                 		=> esc_html__( 'Tracklist: Search Bar', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'playlist_show_playlist', 
										'operator' => '==',
										'value' => 'yes'
									]
								]
							],
						]
					],
				]
			);
			if (get_site_option('SRMP3_ecommerce') != '1'){
				$this->add_control(
					'sonaar_go_pro',
					[
						'type' 							=> \Elementor\Controls_Manager::RAW_HTML,
						'raw' 							=> 	$this->srp_promo_message(),
					]
				);
				$this->end_controls_section();
			}else if (get_site_option('SRMP3_ecommerce') == '1'){
			$this->add_control(
				'searchbar_searchheading',
				[
					'label' 						=> esc_html__( 'Search Bar', 'sonaar-music' ),
					'type' 							=> Controls_Manager::HEADING,
				]
			);
			$this->add_control(
				'enable_searchbar_keyword',
				[
					'label' 							=> esc_html__( 'Enable Tracklist Search', 'sonaar-music' ),
					'type' 								=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 							=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 						=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 						=> 'true',
					'default' 							=> '',
				]
			);
			$this->add_control(
				'search_placeholder',
				[
					'label' 						=> esc_html__( 'Placeholder Text', 'sonaar-music' ),
					'type' 							=> Controls_Manager::TEXT,
					'default' 						=> '',
					'placeholder' 					=> esc_html__( 'Enter any keyword', 'sonaar-music' ),
					'separator' 					=> 'after',
					'dynamic' 						=> [
						'active' 					=> true,
					],
				]
			);
			$this->add_control(
				'searchbar_color',
				[
					'label'                		 	=> esc_html__( 'Keyword Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} .srp_search_container .srp_search, {{WRAPPER}} .srp_search_container .fa-search' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'enable_searchbar_keyword' 	=> 'true',
					],
				]
			);
			$this->add_control(
				'reset_color',
				[
					'label'                		 	=> esc_html__( 'Reset Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} .srp_search_container .srp_reset_search' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'enable_searchbar_keyword' 	=> 'true',
					],
				]
			);
			$this->add_control(
				'searchbar_placeholdercolor',
				[
					'label'                		 	=> esc_html__( 'Placeholder Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} .srp_search_container .srp_search::placeholder' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'enable_searchbar_keyword' 	=> 'true',
					],
				]
			);
			$this->add_control(
				'searchbar_bg',
				[
					'label'                		 	=> esc_html__( 'Background Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} .srp_search_container .srp_search' => 'background: {{VALUE}}',
					],
					'condition' 					=> [
						'enable_searchbar_keyword' 	=> 'true',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'searchbar_typo',
					'label' 						=> esc_html__( 'Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} .srp_search_container .srp_search',
					'separator' 					=> 'after',
					'condition' 					=> [
						'enable_searchbar_keyword' 	=> 'true',
					],
				]
			);
			$this->add_responsive_control(
				'searchbar_padding',
				[
					'label' 						=> esc_html__( 'Search Bar Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_search_container .srp_search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 					=> [
						'enable_searchbar_keyword' 	=> 'true',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' 							=> 'searchbar_container_bg',
					'label' 						=> esc_html__( 'Search Bar Container Background', 'elementor-sonaar' ),
					'types' 						=> [ 'classic', 'gradient'],
					'selector' 						=> '{{WRAPPER}} .srp_search_main',
					'condition' 					=> [
						'enable_searchbar_keyword' 	=> 'true',
					],
				]
			);
			$this->add_responsive_control(
				'searchbar_container_padding',
				[
					'label' 						=> esc_html__( 'Search Bar Container Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_search_main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 					=> [
						'enable_searchbar_keyword' 	=> 'true',
					],
				]
			);
			$this->end_controls_section();
		}
		
			$this->start_controls_section(
				'searchbar_style',
				[
					'label'                 		=> esc_html__( 'Tracklist: Custom Fields Columns', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'playlist_show_playlist', 
										'operator' => '==',
										'value' => 'yes'
									]
								]
							],
						]
					],
				]
			);
		if (get_site_option('SRMP3_ecommerce') != '1'){
			$this->add_control(
				'sonaar_go_pro_cf',
				[
					'type' 							=> \Elementor\Controls_Manager::RAW_HTML,
					'raw' 							=> 	$this->srp_promo_message(),
				]
			);
			$this->end_controls_section();
		}else if (get_site_option('SRMP3_ecommerce') == '1'){
			$this->add_control(
				'searchbar_cf_heading',
				[
					'label' 						=> esc_html__( 'Columns', 'elementor' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
					'condition' 			=> [
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_responsive_control(
				'cf_track_title_width',
				[
					'label' 						=> esc_html__( 'Track Title Column Width', 'sonaar-music' ) . ' (px)',
					'type'							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 600,
						],
					],
					'size_units' 					=> [ 'px' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer.srp_has_customfields .audio-track' => 'flex: 1 1 {{SIZE}}{{UNIT}};',
													'{{WRAPPER}} .iron-audioplayer.srp_has_customfields .sr-playlist-cf-container' => 'flex: 0 1 calc(100% -  {{SIZE}}{{UNIT}});',
													'{{WRAPPER}} .iron-audioplayer.srp_has_customfields .tracklist-item-title' => 'width: unset;',
					],
					'condition' 			=> [
						'trackList_layout!' => 'grid',
						'tracklist_soundwave_show!' => 'true',
					],
				]
			);
			$this->add_responsive_control(
				'cf_cta_width',
				[
					'label' 						=> esc_html__( 'CTA Column Width', 'sonaar-music' ) . ' (px)',
					'type'							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 600,
						],
					],
					'size_units' 					=> [ 'px', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer.srp_has_customfields .sr-playlist-item .store-list' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
													'{{WRAPPER}} .iron-audioplayer.srp_has_customfields .playlist .store-list .song-store-list-menu' => 'width: {{SIZE}}{{UNIT}};',
													'{{WRAPPER}} .iron-audioplayer.srp_has_customfields .sr-playlist-item:not(.srp_extended) .song-store-list-menu' => 'max-width: {{SIZE}}{{UNIT}};',
													'{{WRAPPER}} .iron-audioplayer.srp_has_customfields .srp_responsive .sr-playlist-item .store-list' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
													'{{WRAPPER}} .iron-audioplayer.srp_has_customfields .srp_responsive .playlist .store-list .song-store-list-menu' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' 			=> [
						'trackList_layout!' => 'grid',
					],
				]
			);
			$column_repeater = new \Elementor\Repeater();
			$column_repeater->add_control(
				'column_name',
				[
					'label'     => esc_html__( 'Heading Title', 'sonaar-music' ),
					'type' 		=> Controls_Manager::TEXT,
					'dynamic' 						=> [
						'active' 					=> true,
					],
					'default' 						=> '',
					'label_block' 					=> true,
				]
			);
			$column_repeater->add_control(
				'custom_field_plugin',
				[
					'label'					=> esc_html__( 'Source ', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> false,
					'options'				=> $this->check_column_plugin_activated(),
					'default' 				=> 'object',
				]
			);
			$column_repeater->add_control(
				'column_fields_acf',
				[
					'label'     => esc_html__( 'ACF Field', 'sonaar-music' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'default'   => '',
					'groups'    => $this->get_fields_goups( 'fields' ),
					'condition' => [
						'custom_field_plugin' => 'acf',
					],
				]
			);
			
			if (function_exists('jet_engine')){
				$meta_fields = $this->get_meta_fields_for_post_type();
				if ( ! empty( $meta_fields ) ) {
					$column_repeater->add_control(
						'column_fields_jetengine',
						[
							'label'     => esc_html__( 'Meta Field', 'sonaar-music' ),
							'type'      => \Elementor\Controls_Manager::SELECT,
							'default'   => '',
							'groups'    => $meta_fields,
							'condition' => [
								'custom_field_plugin' => 'jetengine',
							],
						]
					);
				}
			}
			$column_repeater->add_control(
				'column_fields_object',
				[
					'label'     => esc_html__( 'Object Field', 'sonaar-music' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'default'   => '',
					'groups'    => $this->get_object_fields(),
					'condition' => [
						'custom_field_plugin' => 'object',
					],
				]
			);
			$column_repeater->add_control(
				'column_fields_icon',
				[
					'label' => esc_html__( 'Icon', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
			);
			$column_repeater->add_control(
				'column_fields_artwork_size',
				[
					'label' 						=> esc_html__( 'Thumbnail Width', 'sonaar-music' ) . ' (px)',
					'type'							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 50,
						],
					],
					'default' 						=> [
						'unit' => 'px',
						'size' => 50,
						],
				
					'size_units' 					=> [ 'px'],
					'selectors' 					=> [
													'{{WRAPPER}} .sr_cf_track_cover' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition' 					=> [
						'column_fields_object' 		=> 'srmp3_cf_album_img',
					],
				]
			);
			$column_repeater->add_control(
				'custom_field_key', [
					'label'     => esc_html__( 'Custom Meta Key', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'condition' => [
						'custom_field_plugin' => 'customkey',
					],
				]
			);
			$column_repeater->add_control(
				'column_width',
				[
					'label' 						=> esc_html__( 'Column Width', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
					],
					'default' 						=> [
							'unit' => 'px',
							'size' => 100,
							],
				]
			);
			$this->add_control(
				'cf_repeater',
				[
					'label' => esc_html__( 'Add New Column', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'prevent_empty' => false,
					'fields' => $column_repeater->get_controls(),
					'title_field' => '{{{ column_name }}}  <# if ( "object" == custom_field_plugin ) { #> :: {{{ column_fields_object }}} <# } #> <# if ( "acf" == custom_field_plugin ) { #> :: {{{ column_fields_acf }}} <# } #> <# if ( "jetengine" == custom_field_plugin ) { #> :: {{{ column_fields_jetengine }}} <# } #> <# if ( "customkey" == custom_field_plugin ) { #> :: {{{ custom_field_key }}} <# } #>',
					/*'condition' 					=> [
						'show_searchbar' 	=> 'true',
					],*/
				]
			);
			$this->add_control(
				'column_notice',
				[
					'label' => esc_html__( 'Important:', 'sonaar-music' ),
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => esc_html__( 'Make sure tracklist is wide enough to contains all your columns. We automatically hide columns (starting with the last one) if tracklist has not enough space.', 'sonaar-music' ),
				]
			);
			$this->add_responsive_control(
				'column_justify',
				[
				'label' 		=> esc_html_x( 'Justify Content', 'Flex Container Control', 'elementor' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'label_block' 	=> true,
				'separator' 	=> 'before',
				'default' 		=> '',
				'options' 		=> [
					'flex-start' => [
						'title' => esc_html_x( 'Start', 'Flex Container Control', 'elementor' ),
						'icon' => 'eicon-flex eicon-justify-start-h',
					],
					'center' => [
						'title' => esc_html_x( 'Center', 'Flex Container Control', 'elementor' ),
						'icon' => 'eicon-flex eicon-justify-center-h',
					],
					'flex-end' => [
						'title' => esc_html_x( 'End', 'Flex Container Control', 'elementor' ),
						'icon' => 'eicon-flex eicon-justify-end-h',
					],
					'space-between' => [
						'title' => esc_html_x( 'Space Between', 'Flex Container Control', 'elementor' ),
						'icon' => 'eicon-flex eicon-justify-space-between-h',
					],
					'space-around' => [
						'title' => esc_html_x( 'Space Around', 'Flex Container Control', 'elementor' ),
						'icon' => 'eicon-flex eicon-justify-space-around-h',
					],
					'space-evenly' => [
						'title' => esc_html_x( 'Space Evenly', 'Flex Container Control', 'elementor' ),
						'icon' => 'eicon-flex eicon-justify-space-evenly-h',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sr-playlist-cf-container' => 'justify-content: {{VALUE}};',
				],
				]
			);
			$this->add_responsive_control(
				'column_align',
				[
					'label' 						=> esc_html__( 'Text Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'left'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'right' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> '',
					'selectors' 					=> [
													'{{WRAPPER}} .sr-playlist-cf-container .sr-playlist-cf-child' => 'text-align: {{VALUE}};justify-content: {{VALUE}};',
													'{{WRAPPER}} .sr-playlist-heading-child:not(.sr-playlist-cf--title)' => 'text-align: {{VALUE}}!important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'searchbar_cf_typo',
					'label' 						=> esc_html__( 'Column Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} .sr-playlist-cf-container',
				]
			);
			$this->add_control(
				'searchbar_cf_colum_color',
				[
					'label'                		 	=> esc_html__( 'Column Text Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} div.sr-playlist-cf-container' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'cf_white_space',
				[
					'label' 					=> esc_html__( 'White space no wrap', 'sonaar-music' ),
					'type' 						=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 					=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 				=> esc_html__( 'No', 'sonaar-music' ),
					'default' 					=> '',
					'return_value' 				=> 'yes',
					'selectors' 				=> [
												'{{WRAPPER}} .sr-playlist-cf-container .sr-playlist-cf-child' => 'white-space: nowrap;',
						 
				 ],
				]
			);
			$this->add_control(
				'searchbar_cf_colheading_title',
				[
					'label' 						=> esc_html__( 'Column Heading', 'elementor' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
					'condition' 			=> [
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_control(
				'searchbar_cf_heading_show',
				[
					'label' 							=> esc_html__( 'Show Column Headings', 'sonaar-music' ),
					'type' 								=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 							=> esc_html__( 'Show', 'sonaar-music' ),
					'label_off' 						=> esc_html__( 'Hide', 'sonaar-music' ),
					'return_value' 						=> 'true',
					'default' 							=> 'true',
					'condition' 			=> [
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'searchbar_cf_heading_typo',
					'label' 						=> esc_html__( 'Heading Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer.srp_has_customfields .sr-cf-heading .sr-playlist-heading-child',
					'separator' 					=> 'after',
					'condition' 					=> [
						'searchbar_cf_heading_show' 	=> 'true',
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_control(
				'searchbar_cf_heading_color',
				[
					'label'                		 	=> esc_html__( 'Color', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} .sr-playlist-heading-child' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'searchbar_cf_heading_show' 	=> 'true',
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_control(
				'searchbar_cf_heading_color_hover',
				[
					'label'                		 	=> esc_html__( 'Color Hover', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} .sr-playlist-heading-child:hover' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'searchbar_cf_heading_show' 	=> 'true',
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_control(
				'cf_heading_bottom_border',
				[
					'label'                		 	=> esc_html__( 'Heading Separator', 'sonaar-music' ),
					'type'                		 	=> Controls_Manager::COLOR,
					'default'            		    => '',
					'selectors'             		=> [
						'{{WRAPPER}} .sr-cf-heading' => 'border-color: {{VALUE}}',
					],
					'condition' 					=> [
						'searchbar_cf_heading_show' 	=> 'true',
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' 							=> 'searchbar_cf_heading_bg',
					'label' 						=> esc_html__( 'Background', 'elementor-sonaar' ),
					'types' 						=> [ 'classic', 'gradient'],
					'exclude' 						=> [ 'image' ],
					'selector' 						=> '{{WRAPPER}} .sr-cf-heading',
					'condition' 					=> [
						'searchbar_cf_heading_show' 	=> 'true',
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->add_responsive_control(
				'searchbar_cf_heading_pad',
				[
					'label' 						=> esc_html__( 'Heading Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .sr-cf-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' 					=> [
						'searchbar_cf_heading_show' 	=> 'true',
						'trackList_layout!' => 'grid',
					],
				]
			);
			$this->end_controls_section();
		}


			$this->start_controls_section(
				'cta_icon_options',
				[
					'label'                 		 => esc_html__( 'Tracklist: Call-to-Action Buttons', 'elementor' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					    ]
					] 
				]
			);

			$this->add_control(
				'hide_track_market',
				[
					'label'							=> sprintf( esc_html__( 'Hide %1$s\'s Call-to-Action(s)', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value'					=> 'yes',
					'default' 						=> '',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					    ]
					] 
				]
			);
			$this->add_control(
				'force_cta_dl',
				[
					'label'							=> esc_html__( 'DOWNLOAD Buttons display', 'sonaar-music' ), 
					'separator' 					=> 'before',
					'type' 							=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('force_cta_download', 'srmp3_settings_download') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 						=> 'default',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					    ]
					] 
				]
			);
			if (get_site_option('SRMP3_ecommerce') == '1'){
				// START - Dynamic Visibility - DOWNLOAD
				$this->add_control(
					'cta_dl_dv_enable',
					[
						'label'							=> esc_html__( 'Enable Dynamic Visibility', 'sonaar-music' ),
						'type' 							=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 					=> 'yes',
						'default' 						=> 'no',			
					]
				);
				$this->add_control(
					'cta_dl_dv_state',
					[
						'label'							=> esc_html__( 'Visibility State', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> Controls_Manager::SELECT,
						'options' 						=> [
							'' 							=> esc_html__( 'Select a State', 'sonaar-music' ),
							'show' 						=> esc_html__( 'Show Download buttons if', 'sonaar-music' ),
							'hide' 						=> esc_html__( 'Hide Download buttons if', 'sonaar-music' ),
						],
						'default' 						=> '',
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_dl_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);
				$this->add_control(
					'cta_dl_dv_condition',
					[
						'label'							=> esc_html__( 'Visibility Condition', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> Controls_Manager::SELECT,
						'default' 						=> '',
						'options' 						=> [
							'' 							=> esc_html__( 'Select Condition', 'sonaar-music' ),
							'user_logged_in' 			=> esc_html__( 'User logged in', 'sonaar-music' ),
							'user_logged_out' 			=> esc_html__( 'User logged out', 'sonaar-music' ),
							'user_role_is' 				=> esc_html__( 'User Role is', 'sonaar-music' ),
						],
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_dl_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);

				$this->add_control(
					'cta_dl_dv_role',
					[
						'label'							=> esc_html__( 'Role is', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> \Elementor\Controls_Manager::SELECT2,
						'multiple' 						=> true,
						'options' 						=> $this->get_user_roles(),
						'default' 						=> array(),
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_dl_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
								[
									'name' => 'cta_dl_dv_condition',
									'operator' => '==',
									'value' => 'user_role_is'
								],
							]
						] 
					]
				);
				$this->add_control(
					'cta_dl_dv_not_met_action',
					[
						'label'							=> esc_html__( 'Otherwise', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> Controls_Manager::SELECT,
						'default' 						=> '',
						'options' 						=> [
							'' 							=> esc_html__( 'Otherwise, Hide the Download Button', 'sonaar-music' ),
							'redirect' 			=> esc_html__( 'Otherwise, Redirect the Download Button', 'sonaar-music' ),
							'askemail' 			=> esc_html__( 'Otherwise, Ask for an email', 'sonaar-music' ),
						],
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_dl_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);
				$this->add_control(
					'cta_dl_dv_redirect_url',
					[
						'label'							=> esc_html__( 'Redirection URL', 'sonaar-music' ),
						'description' 					=> esc_html__('Enter Page URL. If you are using Elementor Popup, set this to #popup and in your Popup Template, go to Advanced Tab > Open By Selector and set a[href="#popup"]','sonaar-music'),
						'type' 							=> \Elementor\Controls_Manager::TEXT,
						'input_type' => 'url',
						'placeholder'       			=> esc_html__( 'Paste URL or type', 'sonaar-music' ),
						'dynamic'           			=> [ 'active' => true ],
						'label_block' 					=> true,
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_dl_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
								[
									'name' => 'cta_dl_dv_not_met_action',
									'operator' => '==',
									'value' => 'redirect'
								],
							]
						] 
					]
				);
			}
			if (get_site_option('SRMP3_ecommerce') == '1'){
				$this->add_control(
					'force_cta_favorite',
					[
						'label'							=> esc_html__( 'FAVORITE Buttons Display', 'sonaar-music' ), 
						'separator' 					=> 'before',
						'type' 							=> Controls_Manager::SELECT,
						'options' 		=> [
							'default' 	=> esc_html__( $this->get_srmp3_option_label('force_cta_favorite', 'srmp3_settings_favorites') ),
							'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
							'false' 	=> esc_html__( 'No', 'sonaar-music' ),
						],
						'default' 						=> 'default',
						'conditions' => [
							'relation' => 'or',
							'terms' => [
									[
									'terms' => [
											['name' => 'playlist_source', 'operator' => '!in', 'value' => ['from_elementor','from_rss', 'from_text_file']],
											
										]
									],
							],
						]
					]
				);
			
				// START - Dynamic Visibility - FAVORITES
				$this->add_control(
					'cta_favorites_dv_enable',
					[
						'label'							=> esc_html__( 'Enable Dynamic Visibility', 'sonaar-music' ),
						'type' 							=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 					=> 'yes',
						'default' 						=> 'no',			
					]
				);
				$this->add_control(
					'cta_favorites_dv_state',
					[
						'label'							=> esc_html__( 'Visibility State', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> Controls_Manager::SELECT,
						'options' 						=> [
							'' 							=> esc_html__( 'Select a State', 'sonaar-music' ),
							'show' 						=> esc_html__( 'Show Favorite buttons if', 'sonaar-music' ),
							'hide' 						=> esc_html__( 'Hide Favorite buttons if', 'sonaar-music' ),
						],
						'default' 						=> '',
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_favorites_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);
				$this->add_control(
					'cta_favorites_dv_condition',
					[
						'label'							=> esc_html__( 'Visibility Condition', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> Controls_Manager::SELECT,
						'default' 						=> '',
						'options' 						=> [
							'' 							=> esc_html__( 'Select Condition', 'sonaar-music' ),
							'user_logged_in' 			=> esc_html__( 'User logged in', 'sonaar-music' ),
							'user_logged_out' 			=> esc_html__( 'User logged out', 'sonaar-music' ),
							'user_role_is' 				=> esc_html__( 'User Role is', 'sonaar-music' ),
						],
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_favorites_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);

				$this->add_control(
					'cta_favorites_dv_role',
					[
						'label'							=> esc_html__( 'Role is', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> \Elementor\Controls_Manager::SELECT2,
						'multiple' 						=> true,
						'options' 						=> $this->get_user_roles(),
						'default' 						=> array(),
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_favorites_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
								[
									'name' => 'cta_favorites_dv_condition',
									'operator' => '==',
									'value' => 'user_role_is'
								],
							]
						] 
					]
				);
				$this->add_control(
					'cta_favorites_dv_enable_redirect',
					[
						'label'							=> esc_html__( 'Otherwise, show buttons but redirect the user', 'sonaar-music' ),
						'description' 					=> esc_html__( 'If condition not met, display the button but redirect people to a link or popup', 'sonaar-music'),
						'type' 							=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 					=> 'yes',
						'default' 						=> 'no',	
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_favorites_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 	
					]
				);
				$this->add_control(
					'cta_favorites_dv_redirect_url',
					[
						'label'							=> esc_html__( 'Redirection URL', 'sonaar-music' ),
						'description' 					=> esc_html__('Enter Page URL. If you are using Elementor Popup, set this to #popup and in your Popup Template, go to Advanced Tab > Open By Selector and set a[href="#popup"]','sonaar-music'),
						'type' 							=> \Elementor\Controls_Manager::TEXT,
						'input_type' => 'url',
						'placeholder'       			=> esc_html__( 'Paste URL or type', 'sonaar-music' ),
						'dynamic'           			=> [ 'active' => true ],
						'label_block' 					=> true,
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_favorites_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
								[
									'name' => 'cta_favorites_dv_enable_redirect',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);
			}
			$this->add_control(
				'force_cta_share',
				[
					'label'							=> esc_html__( 'SHARE Buttons Display', 'sonaar-music' ), 
					'separator' 					=> 'before',
					'type' 							=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('force_cta_share', 'srmp3_settings_share') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 						=> 'default',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					    ]
					] 
				]
			);
			if (get_site_option('SRMP3_ecommerce') == '1'){
				// START - Dynamic Visibility - SHARE
				$this->add_control(
					'cta_share_dv_enable',
					[
						'label'							=> esc_html__( 'Enable Dynamic Visibility', 'sonaar-music' ),
						'type' 							=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 					=> 'yes',
						'default' 						=> 'no',			
					]
				);
				$this->add_control(
					'cta_share_dv_state',
					[
						'label'							=> esc_html__( 'Visibility State', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> Controls_Manager::SELECT,
						'options' 						=> [
							'' 							=> esc_html__( 'Select a State', 'sonaar-music' ),
							'show' 						=> esc_html__( 'Show Share buttons if', 'sonaar-music' ),
							'hide' 						=> esc_html__( 'Hide Share buttons if', 'sonaar-music' ),
						],
						'default' 						=> '',
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_share_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);
				$this->add_control(
					'cta_share_dv_condition',
					[
						'label'							=> esc_html__( 'Visibility Condition', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> Controls_Manager::SELECT,
						'default' 						=> '',
						'options' 						=> [
							'' 							=> esc_html__( 'Select Condition', 'sonaar-music' ),
							'user_logged_in' 			=> esc_html__( 'User logged in', 'sonaar-music' ),
							'user_logged_out' 			=> esc_html__( 'User logged out', 'sonaar-music' ),
							'user_role_is' 				=> esc_html__( 'User Role is', 'sonaar-music' ),
						],
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_share_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);

				$this->add_control(
					'cta_share_dv_role',
					[
						'label'							=> esc_html__( 'Role is', 'sonaar-music' ),
						'show_label' 					=> false,
						'type' 							=> \Elementor\Controls_Manager::SELECT2,
						'multiple' 						=> true,
						'options' 						=> $this->get_user_roles(),
						'default' 						=> array(),
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_share_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
								[
									'name' => 'cta_share_dv_condition',
									'operator' => '==',
									'value' => 'user_role_is'
								],
							]
						] 
					]
				);
				$this->add_control(
					'cta_share_dv_enable_redirect',
					[
						'label'							=> esc_html__( 'Otherwise, show buttons but redirect the user', 'sonaar-music' ),
						'description' 					=> esc_html__( 'If condition not met, display the button but redirect people to a link or popup', 'sonaar-music'),
						'type' 							=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 					=> 'yes',
						'default' 						=> 'no',	
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_share_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 	
					]
				);
				$this->add_control(
					'cta_share_dv_redirect_url',
					[
						'label'							=> esc_html__( 'Redirection URL', 'sonaar-music' ),
						'description' 					=> esc_html__('Enter Page URL. If you are using Elementor Popup, set this to #popup and in your Popup Template, go to Advanced Tab > Open By Selector and set a[href="#popup"]','sonaar-music'),
						'type' 							=> \Elementor\Controls_Manager::TEXT,
						'input_type' => 'url',
						'placeholder'       			=> esc_html__( 'Paste URL or type', 'sonaar-music' ),
						'dynamic'           			=> [ 'active' => true ],
						'label_block' 					=> true,
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'cta_share_dv_enable',
									'operator' => '==',
									'value' => 'yes'
								],
								[
									'name' => 'cta_share_dv_enable_redirect',
									'operator' => '==',
									'value' => 'yes'
								],
							]
						] 
					]
				);
			}
			$this->add_control(
				'force_cta_singlepost',
				[
					'label'							=> esc_html__( 'Link to Post Buttons Display', 'sonaar-music' ), 
					'separator' 					=> 'before',
					'type' 							=> Controls_Manager::SELECT,
					'options' 		=> [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('force_cta_singlepost', 'srmp3_settings_general') ),
						'true' 		=> esc_html__( 'Yes', 'sonaar-music' ),
						'false' 	=> esc_html__( 'No', 'sonaar-music' ),
					],
					'default' 						=> 'default',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					    ]
					] 
				]
			);

			
			$this->add_control(
				'view_icons_alltime',
				[
					'label' 						=> esc_html__( 'Display Icons without Popover', 'sonaar-music' ),
					'separator' 					=> 'before',
					'description' 					=> 'Turn off if you have a lot of icons',
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> 'yes',
					'default' 						=> 'yes',
					'prefix_class'					=> 'sr_track_inline_cta_bt__',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					       
					    ]
					],
					
				]
			);
			$this->add_control(
				'popover_icons_store',
				[
					'label' 						=> esc_html__( 'Popover Icon Color', 'sonaar-music' ),
					'type'							=> Controls_Manager::COLOR,
					'default' 						=> '',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					        
					    ]
					],
					'selectors'             		=> [
							'{{WRAPPER}} .iron-audioplayer .playlist .song-store-list-menu .fa-ellipsis-v, {{WRAPPER}} .iron-audioplayer .store-list .srp_ellipsis' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'popover_icons_bg_store',
				[
					'label' 						=> esc_html__( 'Popover Background Color', 'sonaar-music' ),
					'type'							=> Controls_Manager::COLOR,
					'default' 						=> '',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					        
					    ]
					],
					'selectors'             		=> [
							'{{WRAPPER}} .store-list .song-store-list-menu .song-store-list-container' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'cta_track_show_label',
				[
					'label' 						=> esc_html__( 'Show Text label', 'sonaar-music' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'default' 	=> esc_html__( $this->get_srmp3_option_label('show_label', 'srmp3_settings_widget_player') ),
						'true' => esc_html__( 'Yes', 'sonaar-music' ),
						'false' => esc_html__( 'No', 'sonaar-music' ),
					],
					'default' => 'default',
				]
			);
			
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'tracklist_label_typography',
					'label' 						=> esc_html__( 'Button Label Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					       
					    ]
					],
					'selector' 						=> '{{WRAPPER}} .iron-audioplayer .song-store-list-container a.song-store',
				]
			);
			$this->add_control(
				'tracklist_icons_color',
				[
					'label'                 		=> esc_html__( 'Icons Color When No Label Present', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					       
					    ]
					],
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer a.song-store:not(.sr_store_wc_round_bt)' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'wc_icons_color',
				[
					'label'                 		=> esc_html__( 'Label Button Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'conditions' 					=> [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'playlist_source',
								'operator' => '!=',
								'value' => 'from_elementor'
							],
							
						]
					],
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .song-store-list-container a.song-store.sr_store_wc_round_bt' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'wc_icons_bg_color',
				[
					'label'                 		=> esc_html__( 'Label Button Background Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'conditions' 					=> [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'playlist_source',
								'operator' => '!=',
								'value' => 'from_elementor'
							],
							
						]
					],
					'selectors'             		=> [
													'{{WRAPPER}} .iron-audioplayer .song-store-list-container a.song-store.sr_store_wc_round_bt' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'cta_padding',
				[
					'label' 						=> esc_html__( 'Button Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .sr_store_wc_round_bt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					      
					    ]
					],
				]
			);
			$this->add_responsive_control(
				'tracklist_icons_spacing',
				[
					'label' 						=> esc_html__( 'Button Spacing', 'elementor' ) . ' (px)',
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 50,
						],
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					       
					    ]
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .song-store-list-container' => 'column-gap: {{SIZE}}px;',
					],
				]
			);
			$this->add_responsive_control(
				'tracklist_icons_size',
				[
					'label' 						=> esc_html__( 'Icon Button Size (when no label is present)', 'sonaar-music' ) . ' (px)', 
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 50,
						],
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					      
					    ]
					],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .song-store-list-container .song-store .fab, {{WRAPPER}} .iron-audioplayer .song-store-list-container .song-store .fas, {{WRAPPER}} .iron-audioplayer .song-store-list-container .song-store.srp-fav-bt i, {{WRAPPER}} .iron-audioplayer .song-store-list-container .song-store.sr_store_force_pl_bt i, {{WRAPPER}} .iron-audioplayer .song-store-list-container .song-store.sr_store_force_share_bt i, {{WRAPPER}} .iron-audioplayer .song-store-list-container .song-store.sr_store_force_dl_bt i' => 'font-size: {{SIZE}}px;',
					],
				]
			);
			$this->add_responsive_control(
				'cta_position',
				[
					'label' => esc_html__( 'Position', 'elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'relative',
					'options' => [
						'relative' => esc_html__( 'Relative', 'elementor' ),
						'absolute' => esc_html__( 'Absolute', 'elementor' ),
					],
					'selectors' => [
						'{{WRAPPER}} .iron-audioplayer .srp_track_cta' => 'position: {{VALUE}};',
						'{{WRAPPER}} .iron-audioplayer .srp_swiper .store-list' => 'position: {{VALUE}};',
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
							
					    ]
					],
				]
			);
			$this->add_responsive_control(
				'cta_hoffset',
				[
					'label' 					=> esc_html__( 'Horizontal Offset', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
						'vw' => [
							'min' => -200,
							'max' => 200,
						],
						'vh' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%', 'vw', 'vh' ],
					'selectors' 				=> [
								'{{WRAPPER}} .iron-audioplayer .srp_track_cta' => 'left: {{SIZE}}px;',
								'{{WRAPPER}} .iron-audioplayer .srp_swiper .store-list' => 'left: {{SIZE}}px;',
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					      
					    ]
					],
				]
			);
			$this->add_responsive_control(
				'cta_voffset',
				[
					'label' 					=> esc_html__( 'Vertical Offset', 'sonaar-music' ) . ' (px)',
					'type' 						=> Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
						'vw' => [
							'min' => -200,
							'max' => 200,
						],
						'vh' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%', 'vw', 'vh' ],
					'selectors' 				=> [
								'{{WRAPPER}} .iron-audioplayer .srp_track_cta' => 'top: {{SIZE}}px;',
								'{{WRAPPER}} .iron-audioplayer .srp_swiper .store-list' => 'top: {{SIZE}}px;',
					],
					'conditions' 					=> [
					    'relation' => 'and',
					    'terms' => [
					        [
					            'name' => 'playlist_source',
					            'operator' => '!=',
					            'value' => 'from_elementor'
					        ],
					       
					    ]
					],
				]
			);
			$this->end_controls_section();

			$this->start_controls_section(
				'pagination_style',
				[
					'label'                 		=> esc_html__( 'Tracklist: Pagination', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'conditions'                    => [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout', 
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[
										'name' => 'playlist_show_playlist', 
										'operator' => '==',
										'value' => 'yes'
									],
									[
										'name' => 'playlist_source', 
										'operator' => '!=',
										'value' => 'recently_played'
									],
								]
							],
						]
					],
				]
			);
			
			$this->add_control(
				'scrollbar_options',
				[
					'label' 						=> esc_html__( 'Scrollbar', 'elementor' ),
					'type' 							=> Controls_Manager::HEADING,
				]
			);
			$this->add_control(
				'scrollbar',
				[
					'label' 						=> esc_html__( 'Enable Scrollbar', 'sonaar-music' ),
					'description' 					=> 'Enable a vertical scrollbar on your tracklist',
					'type' 							=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
					'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
					'return_value' 					=> '1',
					'default' 						=> '',
				]
			);
			$this->add_responsive_control(
				'playlist_height',
				[
					'label' 						=> esc_html__( 'Scrollbar Height', 'sonaar-music' ) . ' (px)',
					'type'							=> Controls_Manager::SLIDER,
					'condition' 					=> [
													'scrollbar' => '1',
					],
					'default'						=> [
						'unit' 						=> 'px',
						'size' 						=> 215,
					],
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 2000,
						],
					],
					'size_units' 					=> [ 'px', 'vh', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer .playlist ul.srp_list' => 'height: {{SIZE}}{{UNIT}}; overflow-y:hidden; overflow-x:hidden;',
					],
				]
			);
				$this->add_control(
					'pagination_heading',
					[
						'label' 						=> esc_html__( 'Pagination', 'elementor' ),
						'type' 							=> Controls_Manager::HEADING,
						'separator' 					=> 'before',
					]
				);
				$this->add_control(
					'pagination',
					[
						'label' 						=> esc_html__( 'Enable Pagination', 'sonaar-music' ),
						'type' 							=> \Elementor\Controls_Manager::SWITCHER,
						'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
						'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
						'return_value' 					=> 'true',
						'default' 						=> '',
					]
				);
				if (get_site_option('SRMP3_ecommerce') != '1'){
					$this->add_control(
						'sonaar_go_pro_pagination',
						[
							'type' 							=> \Elementor\Controls_Manager::RAW_HTML,
							'raw' 							=> 	$this->srp_promo_message(),
							'condition' 					=> [
								'pagination'					 => 'true',
							],
						]
					);
					$this->add_control( //Set Lazy Load to false if not pro to make always condition with lazy_load work
						'lazy_load',
						[
							'type' => \Elementor\Controls_Manager::HIDDEN,
							'default' => false,
						]
					);
					$this->end_controls_section();
				}else if ( get_site_option('SRMP3_ecommerce') == '1'){
					$this->add_control(
						'lazy_load',
						[
							'label' 						=> esc_html__( 'Enable Lazyload (Beta)', 'sonaar-music' ),
							'description' => sprintf(
								esc_html__('Enable this if you have many posts or experience slow page load times. Lazy load has %1$s.', 'sonaar-music'),
								'<a href="https://sonaar.io/docs/lazyload/" target="_blank">' . esc_html__('some limitations', 'sonaar-music') . '</a>'
							),
							'type' 							=> \Elementor\Controls_Manager::SWITCHER,
							'label_on' 						=> esc_html__( 'Yes', 'sonaar-music' ),
							'label_off' 					=> esc_html__( 'No', 'sonaar-music' ),
							'return_value' 					=> 'true',
							//'default' 						=> '0',
							'conditions' => [
								'relation' => 'and',
								'terms' => [
										[
										'terms' => [
												['name' => 'playlist_source', 'operator' => 'in', 'value' => ['from_cat','from_current_term']],
												['name' => 'pagination', 'operator' => '==', 'value' => 'true'],
										],
										]
								]
							]
						]
					);
					$this->add_control(
						'tracks_per_page',
						[
							'label' 						=> esc_html__( 'Tracks/Posts per Page', 'sonaar-music' ),
							'description' 					=> esc_html__( 'This improve the overall performance if you have many tracks. If Lazy Load is enabled, this option set the Posts Per Page rather than Tracks per Page.', 'sonaar-music' ),
							'type' 							=> \Elementor\Controls_Manager::NUMBER,
							'min'							=> 0,
							'max' 							=> 1000,
							'step' 							=> 1,
							'default'						=> 10,
							'condition' 					=> [
								'pagination'					 => 'true',
							],
						]
					);
					$this->add_responsive_control(
						'pagination_scroll_offset',
						[
							'label' 						=> esc_html__( 'Scroll to top offset', 'sonaar-music' ),
							'type' 							=> \Elementor\Controls_Manager::NUMBER,
							//'min'							=> 0,
						//'max' 							=> 1000,
							'step' 							=> 1,
							'default'						=> '',
							'condition' 					=> [
								'pagination'					 => 'true',
							],
						]
					);
					$this->add_control(
						'pagination_numbers_hide',
						[
							'label' 						=> esc_html__( 'Hide Numbers, Keep Arrows', 'sonaar-music' ),
							'type' 							=> Controls_Manager::SWITCHER,
							'default' 						=> '',
							'return_value' 					=> 'none',
							'selectors' 					=> [
								'{{WRAPPER}} .srp_pagination' => 'display:{{VALUE}};',
							],
							'condition' 					=> [
								'pagination'					=> 'true',
							],
						]
					);
			$this->start_controls_tabs( 'pagination_controls' );
			$this->start_controls_tab(
				'tab_page_normal',
				[
					'label' 						=> esc_html__( 'Normal', 'elementor' ),
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'page_color',
				[
					'label' 						=> esc_html__( 'Color', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination span' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'page_bgcolor',
				[
					'label'                			=> esc_html__( 'Background', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination span' => 'background-color: {{VALUE}}',
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'page_arrows_color',
				[
					'label' 						=> esc_html__( 'Arrows Color', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination_arrows' => 'color: {{VALUE}};border-color:{{VALUE}};',
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_page_hover',
				[
					'label' 						=> esc_html__( 'Hover', 'elementor' ),
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'page_color_hover',
				[
					'label' 						=> esc_html__( 'Color', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination span:hover' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'page_bgcolor_hover',
				[
					'label'                			=> esc_html__( 'Background', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination span:hover' => 'background-color: {{VALUE}}',
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'page_arrows_color_hover',
				[
					'label' 						=> esc_html__( 'Arrows Color', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination_arrows:hover' => 'color: {{VALUE}};border-color:{{VALUE}};',
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->end_controls_tab();
			$this->start_controls_tab(
				'tab_page_active',
				[
					'label' 						=> esc_html__( 'Active', 'elementor' ),
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'page_color_active',
				[
					'label' 						=> esc_html__( 'Color', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination .active span' => 'color: {{VALUE}}',
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'page_bgcolor_active',
				[
					'label'                			=> esc_html__( 'Background', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 	=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination .active span' => 'background-color: {{VALUE}}',
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->end_controls_tab();
			$this->end_controls_tabs();		
			$this->add_control(
				'pagination_radius',
				[
					'label' 						=> esc_html__( 'Page Radius', 'elementor' ),
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 40,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .srp_pagination_container .srp_pagination span' => 'border-radius: {{SIZE}}px;',
													'{{WRAPPER}} .srp_pagination_container .srp_pagination_arrows' => 'border-radius: {{SIZE}}px;'
					],
					'condition' 					=> [
						'pagination'					=> 'true',
					],
				]
			);
			$this->add_control(
				'ajax_shimmer_fx',
				[
					'label'					=> esc_html__( 'Preloader / Shimmer  Style', 'sonaar-music' ),
					'type' 					=> Controls_Manager::SELECT,
					'label_block'			=> true,
					'options' 				=> [
						'shimmer'     		=>  esc_html__('Shimmer', 'sonaar-music'),
						'none'   				=>  esc_html__('None', 'sonaar-music'),
					],
					'default' 				=> 'shimmer',
					'condition' 			=> [
						'lazy_load'		=> 'true',
					],
				]
			);
			$this->add_control(
				'ajax_shimmer_color',
				[
					'label' 						=> esc_html__( 'Preloader / Shimmer Color', 'sonaar-music' ),
					'type'                 		 	=> Controls_Manager::COLOR,
					'default'               		=> '',
					'selectors'            		 => [
													'{{WRAPPER}} .srp_shimmer_row_el' => 'background: {{VALUE}};background-image: linear-gradient(89deg, {{VALUE}} 0%, #edeef1 50%, {{VALUE}} 100%);background-size: 1000px 100%;',
					],
					'condition' 				=> [
						'lazy_load'				=> 'true',
					],
				]
			);
			$this->add_responsive_control(
				'ajax_shimmer_opacity',
				[
					'label'							=> esc_html__( 'Preloader / Shimmer Opacity', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' 					=> [
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' 					=> [
						'size' 						=> 10,
						'unit' 						=> '%',
					],
					'selectors'					=> [
													'{{WRAPPER}} .srp_shimmer_row_el' => 'opacity: {{SIZE}}%;', 
					],
					'condition' 				=> [
						'lazy_load'				=> 'true',
					],
				]
			);
			
			$this->end_controls_section();
		}
			

			if (get_site_option('SRMP3_ecommerce') == '1'){
				$this->start_controls_section(
					'favorites_heading',
					[
						'label'                 		=> esc_html__( 'User Favorites', 'sonaar-music' ),
						'tab'                   		=> Controls_Manager::TAB_STYLE,
						'conditions' 					=> [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'playlist_source',
									'operator' => '==',
									'value' => 'from_favorites'
								],
							]
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' 							=> 'fav_no_track_found',
						'label' 						=> esc_html__( 'No Track Found Typography', 'sonaar-music' ),
						'global' => [
							'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
						],
						'selector' 						=> '{{WRAPPER}} .srp-fav-notfound',
					]
				);
				$this->add_control(
					'fav_no_track_color',
					[
						'label'                 		=> esc_html__( 'No Track Found Color', 'sonaar-music' ),
						'type'                  		=> Controls_Manager::COLOR,
						'default'               		=> '',
						'separator' 					=> 'after',
						'selectors'             		=> [
							'{{WRAPPER}} .srp-fav-notfound' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' 							=> 'fav_clearall_typo',
						'label' 						=> esc_html__( 'Remove All Button Typography', 'sonaar-music' ),
						'global' => [
							'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
						],
						'selector' 						=> '{{WRAPPER}} .srp-fav-removeall-bt',
					]
				);
				$this->start_controls_tabs( 'tabs_fav_button_clearall' );

				$this->start_controls_tab(
					'tab_fav_button_clearall_normal',
					[
						'label' 						=> esc_html__( 'Normal', 'elementor' ),
					]
				);

				$this->add_control(
					'fav_button_clearall_color',
					[
						'label' 						=> esc_html__( 'Text Color', 'sonaar-music' ),
						'type' 							=> Controls_Manager::COLOR,
						'default' 						=> '',
						'selectors' 					=> [
														'{{WRAPPER}} .srp-fav-removeall-bt' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'fav_button_clearall_bgcolor',
					[
						'label' 						=> esc_html__( 'Button Color', 'sonaar-music' ),
						'type' 							=> Controls_Manager::COLOR,
						'selectors' 					=> [
														'{{WRAPPER}} .srp-fav-removeall-bt' => 'background: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'fav_button_clearall_btborder_color',
					[
						'label' 						=> esc_html__( 'Button Border Color', 'sonaar-music' ),
						'type' 							=> Controls_Manager::COLOR,
						'condition' 					=> [
							'fav_button_clearall_border!' 			=> '',
						],
						'selectors' 					=> [
														'{{WRAPPER}} .srp-fav-removeall-bt' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_fav_button_clearall_hover',
					[
						'label' 						=> esc_html__( 'Hover', 'elementor' ),
					]
				);

				$this->add_control(
					'fav_button_clearall_color_hover',
					[
						'label' 						=> esc_html__( 'Text Color', 'sonaar-music' ),
						'type' 							=> Controls_Manager::COLOR,
						'selectors' 					=> [
														'{{WRAPPER}} .srp-fav-removeall-bt:hover' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'fav_button_clearall_bgcolor_hover',
					[
						'label' 						=> esc_html__( 'Button Color', 'sonaar-music' ),
						'type' 							=> Controls_Manager::COLOR,
						'selectors'					 	=> [
														'{{WRAPPER}} .srp-fav-removeall-bt:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'fav_button_clearall_border_hover',
					[
						'label' 						=> esc_html__( 'Button Border Color', 'sonaar-music' ),
						'type' 							=> Controls_Manager::COLOR,
						'selectors' 					=> [
														'{{WRAPPER}} .srp-fav-removeall-bt:hover' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->add_control(
					'fav_button_clearall_border_radius',
					[
						'label' 						=> esc_html__( 'Button Radius', 'elementor' ),
						'type' 							=> Controls_Manager::SLIDER,
						'range' 						=> [
							'px' 						=> [
								'max' 					=> 20,
							],
						],
						'selectors' 					=> [
														'{{WRAPPER}} .srp-fav-removeall-bt' => 'border-radius: {{SIZE}}px;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' 							=> 'fav_button_clearall_border',
						'selector' 						=> '{{WRAPPER}} .srp-fav-removeall-bt',
					]
				);
				$this->add_responsive_control(
					'fav_button_clearall_align',
					[
						'label' 						=> esc_html__( 'Remove All Button Alignment', 'sonaar-music' ),
						'type' 							=> Controls_Manager::CHOOSE,
						'options' 						=> [
							'flex-start'    					=> [
								'title' 				=> esc_html__( 'Left', 'elementor' ),
								'icon' 					=> 'eicon-h-align-left',
							],
							'center' 					=> [
								'title' 				=> esc_html__( 'Center', 'elementor' ),
								'icon' 					=> 'eicon-h-align-center',
							],
							'flex-end' 					=> [
								'title' 				=> esc_html__( 'Right', 'elementor' ),
								'icon' 					=> 'eicon-h-align-right',
							],
						],
						'default' 						=> '',
						'selectors' 					=> [
															'{{WRAPPER}} .srp-fav-removeall-wrapper' => 'justify-content: {{VALUE}};align-items:{{VALUE}}',
														//'{{WRAPPER}} .available-now' => 'text-align: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();

				//end if SRMP3_ecommerce
			}




			/**
	         * STYLE: External Links Buttons
	         * -------------------------------------------------
	         */
			
			$this->start_controls_section(
	            'album_stores',
	            [
	                'label'                			=> esc_html__( 'External Links Buttons', 'sonaar-music' ),
					'tab'                   		=> Controls_Manager::TAB_STYLE,
					'conditions' 					=> [
						'relation' => 'or',
						'terms' => [
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout',
										'operator' => '!=',
										'value' => 'skin_button'
									],
									[	
										'name' => 'playlist_show_album_market',
										'operator' => '==',
										'value' => 'yes'
									]
								]
							],
							[
								'relation' => 'and',
								'terms' => [
									[
										'name' => 'player_layout',
										'operator' => '==',
										'value' => 'skin_button'
									],
									[	
										'name' => 'playlist_show_album_market_skin_button',
										'operator' => '==',
										'value' => 'yes'
									]
								]
							],
						]
					]
	            ]
			);
			$this->add_control(
				'album_store_position',
				[
					'label' 						=> esc_html__( 'Move Links below Progress Bar', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'top',
					'condition' 					=> [
						'player_layout' 	=> 'skin_boxed_tracklist',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'storelinks_background',
					'label' => esc_html__( 'Background', 'sonaar-music' ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .iron-audioplayer .album-store',
				]
			);
			$this->add_control(
				'store_heading_options',
				[
					'label' 						=> esc_html__( 'Heading Style', 'elementor' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
				]
			);
			$this->add_control(
				'store_title_btshow',
				[
					'label' 						=> esc_html__( 'Hide Heading', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'none',
					'selectors' 					=> [
							 						'{{WRAPPER}} .available-now' => 'display:{{VALUE}};',
					 ],
				]
			);
			$this->add_control(
				'store_title_text',
				[
					'label' 						=> esc_html__( 'Heading text', 'sonaar-music' ),
					'type' 							=> Controls_Manager::TEXT,
					'dynamic' 						=> [
						'active' 					=> true,
					],
					'default' 						=> '',
					'condition' 					=> [
						'store_title_btshow' 		=> '',
					],
					'label_block' 					=> false,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'store_title_typography',
					'label' 						=> esc_html__( 'Heading Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'condition' 					=> [
						'store_title_btshow' 		=> '',
					],
					'selector' 						=> '{{WRAPPER}} .available-now',
				]
			);
			$this->add_control(
				'store_title_color',
				[
					'label'                 		=> esc_html__( 'Heading Color', 'sonaar-music' ),
					'type'                  		=> Controls_Manager::COLOR,
					'default'               		=> '',
					'condition' 					=> [
						'store_title_btshow' 		=> '',
					],
					'selectors'             		=> [
						'{{WRAPPER}} .available-now' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_responsive_control(
				'store_title_align',
				[
					'label' 						=> esc_html__( 'Heading Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> '',
					'condition' 					=> [
						'store_title_btshow' 		=> '',
					],
					'selectors' 					=> [
														'{{WRAPPER}} .ctnButton-block' => 'justify-content: {{VALUE}};align-items:{{VALUE}}',
													//'{{WRAPPER}} .available-now' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'store_links_options',
				[
					'label' 						=> esc_html__( 'Button Style', 'elementor' ),
					'type' 							=> Controls_Manager::HEADING,
					'separator' 					=> 'before',
				]
			);
			$this->add_responsive_control(
				'album_stores_align',
				[
					'label'						 	=> esc_html__( 'Links Alignment', 'sonaar-music' ),
					'type' 							=> Controls_Manager::CHOOSE,
					'options' 						=> [
						'flex-start'    					=> [
							'title' 				=> esc_html__( 'Left', 'elementor' ),
							'icon' 					=> 'eicon-h-align-left',
						],
						'center' 					=> [
							'title' 				=> esc_html__( 'Center', 'elementor' ),
							'icon' 					=> 'eicon-h-align-center',
						],
						'flex-end' 					=> [
							'title' 				=> esc_html__( 'Right', 'elementor' ),
							'icon' 					=> 'eicon-h-align-right',
						],
					],
					'default' 						=> '',
					'selectors' 					=> [
													'{{WRAPPER}} .buttons-block' => 'justify-content: {{VALUE}};align-items: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 							=> 'store_button_typography',
					'label'						 	=> esc_html__( 'Button Typography', 'sonaar-music' ),
					'global' => [
						'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' 						=> '{{WRAPPER}} a.button',
				]
			);

			$this->start_controls_tabs( 'tabs_button_style' );

			$this->start_controls_tab(
				'tab_button_normal',
				[
					'label' 						=> esc_html__( 'Normal', 'elementor' ),
				]
			);

			$this->add_control(
				'button_text_color',
				[
					'label' 						=> esc_html__( 'Text Color', 'sonaar-music' ),
					'type' 							=> Controls_Manager::COLOR,
					'default' 						=> '',
					'selectors' 					=> [
													'{{WRAPPER}} a.button' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'button_text_background_color',
				[
					'label' 						=> esc_html__( 'Button Color', 'sonaar-music' ),
					'type' 							=> Controls_Manager::COLOR,
					'selectors' 					=> [
													'{{WRAPPER}} a.button' => 'background: {{VALUE}}',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_button_hover',
				[
					'label' 						=> esc_html__( 'Hover', 'elementor' ),
				]
			);

			$this->add_control(
				'button_hover_color',
				[
					'label' 						=> esc_html__( 'Text Color', 'sonaar-music' ),
					'type' 							=> Controls_Manager::COLOR,
					'selectors' 					=> [
													'{{WRAPPER}} a.button:hover' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'button_background_hover_color',
				[
					'label' 						=> esc_html__( 'Button Color', 'sonaar-music' ),
					'type' 							=> Controls_Manager::COLOR,
					'selectors'					 	=> [
													'{{WRAPPER}} a.button:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_hover_border_color',
				[
					'label' 						=> esc_html__( 'Button Border Color', 'sonaar-music' ),
					'type' 							=> Controls_Manager::COLOR,
					'condition' 					=> [
						'border_border!' 			=> '',
					],
					'selectors' 					=> [
													'{{WRAPPER}} a.button:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 							=> 'border',
					'selector' 						=> '{{WRAPPER}} .buttons-block .store-list li .button',
					'separator' 					=> 'before',
				]
			);
			$this->add_control(
				'button_border_radius',
				[
					'label' 						=> esc_html__( 'Button Radius', 'elementor' ),
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 20,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .store-list .button' => 'border-radius: {{SIZE}}px;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 							=> 'button_box_shadow',
					'selector' 						=> '{{WRAPPER}} .store-list .button',
				]
			);
			$this->add_responsive_control(
				'button_text_padding',
				[
					'label' 						=> esc_html__( 'Button Padding', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron_widget_radio .store-list .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' 					=> 'before',
				]
			);
			$this->add_responsive_control(
				'space_between_store_button',
				[
					'label' 						=> esc_html__( 'Buttons Space', 'sonaar-music' ) . ' (px)',
					'type' 							=> Controls_Manager::SLIDER,
					'range' 						=> [
						'px' 						=> [
							'max' 					=> 50,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .buttons-block .store-list' => 'column-gap: {{SIZE}}px;', 
					],
				]
			);
			$this->add_control(
				'hr6',
				[
					'type' 							=> \Elementor\Controls_Manager::DIVIDER,
					'style' 						=> 'thick',
				]
			);
			$this->add_control(
				'store_icon_show',
				[
					'label' 						=> esc_html__( 'Hide Icon', 'sonaar-music' ),
					'type' 							=> Controls_Manager::SWITCHER,
					'default' 						=> '',
					'return_value' 					=> 'none',
					'selectors' 					=> [
							 						'{{WRAPPER}} .store-list .button i' => 'display:{{VALUE}};',
					 ],
				]
			);
			$this->add_responsive_control(
				'icon-font-size',
				[
					'label'							=> esc_html__( 'Icon Font Size', 'sonaar-music' ) . ' (px)',
					'type' 							=> Controls_Manager::SLIDER,
					'condition' 					=> [
						'store_icon_show'			=> '',
					],
					'range' 						=> [
						'px' 						=> [
						'max' 						=> 100,
						],
					],
					'selectors'						=> [
													'{{WRAPPER}} .buttons-block .store-list i' => 'font-size: {{SIZE}}px;', 
					],
				]
			);
			$this->add_responsive_control(
				'icon_indent',
				[
					'label' 						=> esc_html__( 'Icon Spacing', 'elementor' ) . ' (px)',
					'type' 							=> Controls_Manager::SLIDER,
					'condition' 					=> [
						'store_icon_show' 			=> '',
					],
					'range' 						=> [
						'px' 						=> [
						'max' 						=> 50,
						],
					],
					'selectors' 					=> [
													'{{WRAPPER}} .buttons-block .store-list i' => 'margin-right: {{SIZE}}px;',
					],
				]
			);

			$this->add_control(
				'hr11',
				[
					'type' 							=> \Elementor\Controls_Manager::DIVIDER,
					'style' 						=> 'thick',
				]
			);
			$this->add_responsive_control(
				'album_stores_padding',
				[
					'label' 						=> esc_html__( 'Link Buttons Margin', 'sonaar-music' ),
					'type' 							=> Controls_Manager::DIMENSIONS,
					'size_units' 					=> [ 'px', 'em', '%' ],
					'selectors' 					=> [
													'{{WRAPPER}} .iron-audioplayer.show-playlist .ctnButton-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();

		// end if function exist
		}
		//
	
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$playlist_show_album_market = ( ( $settings['player_layout'] != 'skin_button' && $settings['playlist_show_album_market']=="yes" )  || ( $settings['player_layout'] == 'skin_button' && $settings['playlist_show_album_market_skin_button']=="yes" ) ) ? 'true' : 'false';
		$playlist_show_playlist = ( ( $settings['player_layout'] != 'skin_button' && $settings['playlist_show_playlist']=="yes" )  || ( $settings['player_layout'] == 'skin_button' && $settings['playlist_show_playlist_skin_button']=="yes" ) ) ? 'true' : 'false';
		$playlist_show_soundwave = (($settings['playlist_show_soundwave']=="yes") ? 'true' : 'false');
		$playlist_playlist_hide_artwork = (($settings['playlist_hide_artwork']=="yes") ? 'true' : 'false');
		$show_control_on_hover = (isset($settings['show_control_on_hover']) && $settings['show_control_on_hover']=="yes" ? 'true' : 'false');
		$playlist_reverse_tracklist = (function_exists( 'run_sonaar_music_pro' ) && isset($settings['reverse_tracklist']) && $settings['reverse_tracklist'] == "yes") ? true : false;
		$searchbar_show_keyword = '';
		$searchbar_placeholder = '';
		$tracks_per_page = '';
		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$sticky_player = $settings['enable_sticky_player'];
			$shuffle = $settings['enable_shuffle'];
			$wave_color = $settings['soundWave_bg_bar_color'];
			$wave_progress_color = $settings['soundWave_progress_bar_color'];
			$spectro = false;
			if( isset($settings['spectro_animation']) && $settings['spectro_animation'] != 'none'){
				$spectro  = "color1:" . $settings['spectro_color_1'] . "|";
				$spectro .= "color2:" . $settings['spectro_color_2'] . "|";
				$spectro .= "shadow:" . $settings['spectro_shadow'] . "|";
				$spectro .= (isset($settings['spectro_barcount']['size'])) ? "barCount:" . $settings['spectro_barcount']['size'] . "|" : "";
				$spectro .= (isset($settings['spectro_barwidth']['size'])) ? "barWidth:" . $settings['spectro_barwidth']['size'] . "|" : "";
				$spectro .= (isset($settings['spectro_bargap']['size'])) ? "barGap:" . $settings['spectro_bargap']['size'] . "|" : "";
				$spectro .= (isset($settings['spectro_canvasheight']['size'])) ? "canvasHeight:" . $settings['spectro_canvasheight']['size'] . "|" : "";
				$spectro .= ( isset($settings['spectro_alignment']) ) ? "halign:" . $settings['spectro_alignment'] . "|" : "";
				$spectro .= ( isset($settings['spectro_vertical_aligned']) )? "valign:" . $settings['spectro_vertical_aligned'] . "|": "valign:bottom|";
				$spectro .= "spectroStyle:" . $settings['spectro_animation'] . "|";
				$spectro .= ( isset($settings['spectro_pointu']) )? "sharpFx:" . $settings['spectro_pointu'] . "|" : "";
				$spectro .= (isset($settings['spectro_shockwavevibrance']['size'])) ? "shockwaveVibrance:" . $settings['spectro_shockwavevibrance']['size'] . "|" : "";
				$spectro .= (isset($settings['spectro_blockheight']['size'])) ? "blockHeight:" . $settings['spectro_blockheight']['size'] . "|" : "";
				$spectro .= (isset($settings['spectro_blockgap']['size'])) ? "blockGap:" . $settings['spectro_blockgap']['size'] . "|" : "";
				$spectro .= (isset($settings['spectro_reflect'])) ?  "reflectFx:" . $settings['spectro_reflect'] . "|" : "";
				$spectro .= (isset($settings['spectro_gradient_direction'])) ? "gradientDirection:" . $settings['spectro_gradient_direction'] . "|" : "gradientDirection:vertical|";
				$spectro .= (isset($settings['spectro_tracklist_spectrum'])) ? "enableOnTracklist:" . $settings['spectro_tracklist_spectrum'] . "|" : "";
				$spectro .= (isset($settings['spectro_classes'])) ? "bounceClass:" . $settings['spectro_classes'] . "|" : "";
				$spectro .= (isset($settings['spectro_selector_vibrance']['size'])) ? "bounceVibrance:" . $settings['spectro_selector_vibrance']['size'] . "|" : "";
				$spectro .= (isset($settings['spectro_selectorblur'])) ? "bounceBlur:" . $settings['spectro_selectorblur'] : "";
			}
		
			if (get_site_option('SRMP3_ecommerce') == '1'){
				$searchbar_show_keyword = (isset($settings['enable_searchbar_keyword'])) ? $settings['enable_searchbar_keyword'] : $searchbar_show_keyword;
				$searchbar_placeholder = (isset($settings['search_placeholder'])) ? $settings['search_placeholder'] : $searchbar_placeholder;
				$tracks_per_page = ( $settings['pagination'] == 'true' ) ? $settings['tracks_per_page'] : '';
			}
		}else{
			$sticky_player = false;
			$shuffle = false;
			$wave_color = false;
			$wave_progress_color = false;
			$spectro = false;
			$settings['playlist_title_html_tag_soundwave'] = 'div';
			$settings['title_html_tag_playlist'] = 'h3';
		}
		
		$shortcode = '[sonaar_audioplayer elementor="true" tracks_per_page="' . $tracks_per_page . '" titletag_soundwave="'. $settings['playlist_title_html_tag_soundwave'] .'" titletag_playlist="'. $settings['title_html_tag_playlist'] .'" hide_artwork="' . $playlist_playlist_hide_artwork .'" show_control_on_hover="' . $show_control_on_hover .'" show_playlist="' . $playlist_show_playlist .'" reverse_tracklist="' . $playlist_reverse_tracklist .'" show_album_market="' . $playlist_show_album_market .'" hide_timeline="' . $playlist_show_soundwave .'" sticky_player="' . $sticky_player .'" wave_color="' . $wave_color .'" wave_progress_color="' . $wave_progress_color .'" spectro="' . $spectro . '" shuffle="' . $shuffle .'" searchbar="' . $searchbar_show_keyword .'" searchbar_placeholder="' . $searchbar_placeholder .'" ';
		$shortcode .=(isset($settings['spectro_animation_tablet']) && $settings['spectro_animation_tablet'] === 'none') ? 'spectro_hide_tablet="true" ' : '';
		$shortcode .=(isset($settings['spectro_animation_mobile']) && $settings['spectro_animation_mobile'] === 'none') ? 'spectro_hide_mobile="true" ' : '';
		$shortcode .=(isset($settings['rss_items']) && $settings['rss_items'] != '') ? 'rss_items="' . $settings['rss_items'] . '" ' : '';
		$shortcode .=(isset($settings['rss_item_title']) && $settings['rss_item_title'] != '') ? 'rss_item_title="' . $settings['rss_item_title'] . '" ' : '';
		if (isset($settings['show_cat_description'])){
			$shortcode .='show_cat_description="'. $settings['show_cat_description']  .'" ';
		}
		if (isset($settings['adaptive_colors']) && $settings['adaptive_colors'] !== '0'){
			$shortcode .='adaptive_colors="'. $settings['adaptive_colors']  .'" ';
			$shortcode .= ( isset($settings['adaptive_colors_freeze']) ) ? 'adaptive_colors_freeze="'. $settings['adaptive_colors_freeze']  .'" ' : '';
		}
		if (isset($settings['player_layout'])){
			$shortcode .= 'player_layout="' . $settings['player_layout'] . '" ';
		}
		if (isset($settings['soundwave_bar_width']) && $settings['soundwave_bar_width']['size'] != '' ){
			$shortcode .= 'wave_bar_width="' . $settings['soundwave_bar_width']['size'] . '" ';
		}
		if (isset($settings['soundwave_bar_gap']) && $settings['soundwave_bar_gap']['size'] != '' ){
			$shortcode .= 'wave_bar_gap="' . $settings['soundwave_bar_gap']['size'] . '" ';
		}
		if (isset($settings['soundwave_fadein'])  ){
			if($settings['soundwave_fadein'] == 'true' ){
				$shortcode .= 'wave_fadein="false" ';
			}elseif($settings['soundwave_fadein'] == 'false' ){
				$shortcode .= 'wave_fadein="true" ';
			}
		}
		if (isset($settings['soundwave_linecap']) && ($settings['soundwave_linecap'] != 'default') ){
			$shortcode .= 'wave_line_cap="'. $settings['soundwave_linecap'] .'" ';
		}
		if ( isset($settings['player_layout']) && $settings['player_layout'] == 'skin_button'){

			if (isset($settings['show_skip_bt_skin_button']) && $settings['show_skip_bt_skin_button'] == 'yes' ){
				$shortcode .= 'show_skip_bt="true" ';
			}

			if (isset($settings['show_speed_bt_skin_button']) && $settings['show_speed_bt_skin_button'] == 'yes' ){
				$shortcode .= 'show_speed_bt="true" ';
			}

			if (isset($settings['show_volume_bt_skin_button']) && $settings['show_volume_bt_skin_button'] == 'yes' ){
				$shortcode .= 'show_volume_bt="true" ';
			}

			if (isset($settings['show_shuffle_bt_skin_button']) && $settings['show_shuffle_bt_skin_button'] == 'yes' ){
				$shortcode .= 'show_shuffle_bt="true" ';
			}

			if (isset($settings['show_repeat_bt_skin_button']) && $settings['show_repeat_bt_skin_button'] == 'yes' ){
				$shortcode .= 'show_repeat_bt="true" ';
			}

		}else{

			if (isset($settings['show_skip_bt'])){
				$shortcode .= 'show_skip_bt="'. $settings['show_skip_bt'] .'" ';
			}

			if (isset($settings['show_speed_bt'])){
				$shortcode .= 'show_speed_bt="'. $settings['show_speed_bt'] .'" ';
			}

			if (isset($settings['show_volume_bt'])){
				$shortcode .= 'show_volume_bt="'. $settings['show_volume_bt'] .'" ';
			}

			if (isset($settings['show_repeat_bt'])){
				$shortcode .= 'show_repeat_bt="'. $settings['show_repeat_bt'] .'" ';
			}
			
		}

		if( isset($settings['show_miniplayer_note_bt']) ){
			$shortcode .= 'show_miniplayer_note_bt="'. $settings['show_miniplayer_note_bt'] . '" ';
		}

		if( $settings['playlist_title'] ){
			$shortcode .= 'playlist_title="'. $settings['playlist_title'] . '" ';
		}
		
		if( isset($settings['publishdate_btshow']) && $settings['publishdate_btshow'] != ''){
			$shortcode .= 'show_publish_date="'. $settings['publishdate_btshow'] . '" ';
		}

		if( isset($settings['force_cta_dl']) ){
			$shortcode .= 'force_cta_dl="'. $settings['force_cta_dl'] . '" ';
		}
		if( isset($settings['force_cta_singlepost']) ){
			$shortcode .= 'force_cta_singlepost="'. $settings['force_cta_singlepost'] . '" ';
		}
		if( isset($settings['force_cta_share']) ){
			$shortcode .= 'force_cta_share="'. $settings['force_cta_share'] . '" ';
		}
		if( isset($settings['force_cta_favorite']) ){
			$shortcode .= 'force_cta_favorite="'. $settings['force_cta_favorite'] . '" ';
		}
		if( isset($settings['cta_track_show_label']) && $settings['cta_track_show_label'] != ''){
			$shortcode .= 'cta_track_show_label="'. $settings['cta_track_show_label'] . '" ';
		}

		if( isset($settings['playlist_duration_btshow']) && $settings['playlist_duration_btshow'] != ''){
			$shortcode .= 'show_meta_duration="'. $settings['playlist_duration_btshow'] . '" ';
		}
		if( isset($settings['nb_of_track_btshow']) && $settings['nb_of_track_btshow'] != ''){
			$shortcode .= 'show_tracks_count="'. $settings['nb_of_track_btshow'] . '" ';
		}
		
		if ( $settings['playlist_source'] == 'from_text_file' &&  isset($settings['feed_text_source_file']['url']) && $settings['feed_text_source_file']['url'] !== '') {
			$shortcode .= 'import_file="' . $settings['feed_text_source_file']['url'] . '" ';
		}
		if ( $settings['playlist_source'] == 'from_rss' &&  isset($settings['rss_feed']) && $settings['rss_feed'] !== '') {
			$shortcode .= 'import_file="' . $settings['rss_feed'] . '" ';
		}
		if ( $settings['playlist_source'] == 'from_user_meta'){
			$shortcode .= 'feed=from_smg ';
		}
		if ( $settings['playlist_source'] == 'from_elementor' && !$settings['playlist_list']) {	
				
			$shortcode .= 'feed=1 ';
			$shortcode .= 'el_widget_id="' . $this->get_id() .'" ';

			update_post_meta( get_the_ID(), 'srmp3_elementor_tracks', $settings['feed_repeater'] ); // update post meta to retrieve data in json later
			update_post_meta( get_the_ID(), 'alb_store_list', $settings['storelist_repeater'] ); // update post meta store list
		
		}

		if (isset($settings['hide_track_market']) && function_exists( 'run_sonaar_music_pro' )){
			$playlist_hide_track_market = (($settings['hide_track_market']=="yes") ? 'false' : 'true');
			$shortcode .= 'show_track_market="' . $playlist_hide_track_market . '" ';
		}else{
			$shortcode .= 'show_track_market="true" ';
		}

		if (isset($settings['trackList_layout'])  && $settings['trackList_layout']=='grid' ){
			if ( isset( $settings[ 'grid_track_artwork_format_size' ] ) ){
				$shortcode .= 'track_artwork_format="'. $settings['grid_track_artwork_format_size'] .'" ';
			}
			if (isset($settings['grid_track_artwork_show']) && $settings['grid_track_artwork_show'] == 'yes'){
				$shortcode .= 'track_artwork="true" ';
			}
			if (isset($settings['grid_track_artwork_play_button']) && $settings['grid_track_artwork_play_button'] == 'yes'){
				$shortcode .= 'track_artwork_play_button="true" ';
			}
			if (isset($settings['grid_track_artwork_play_on_hover']) && $settings['grid_track_artwork_play_on_hover'] == 'yes'){
				$shortcode .= 'track_artwork_play_on_hover="true" ';
			}
		}else{
			if ( isset( $settings[ 'list_track_artwork_format_size' ] ) ){
				$shortcode .= 'track_artwork_format="'. $settings['list_track_artwork_format_size'] .'" ';
			}
			if (isset($settings['track_artwork_show']) && $settings['track_artwork_show'] == 'yes'){
				$shortcode .= 'track_artwork="true" ';
			}
			if (isset($settings['track_artwork_play_button']) && $settings['track_artwork_play_button'] == 'yes'){
				$shortcode .= 'track_artwork_play_button="true" ';
			}
			if (isset($settings['track_artwork_play_on_hover']) && $settings['track_artwork_play_on_hover'] == 'yes'){
				$shortcode .= 'track_artwork_play_on_hover="true" ';
			}
		}
		
		if(isset($settings['player_layout']) && $settings['player_layout'] == 'skin_button'){
			if (isset($settings['use_play_label_skin_button']) && $settings['use_play_label_skin_button'] == ''){
				$shortcode .= 'use_play_label="false" ';
			}
		}else{
			if (isset($settings['use_play_label'])){
				$shortcode .= 'use_play_label="'. $settings['use_play_label'] .'" ';
			}
		}
		if (isset($settings['soundwave_show_skin_button']) && isset($settings['player_layout']) && $settings['player_layout'] == 'skin_button' && $settings['soundwave_show_skin_button']=='yes'){
			$shortcode .= 'show_progressbar="true" ';
		}
		if (isset($settings['play_btn_align_wave']) && $settings['play_btn_align_wave'] == 'yes' ){
			$shortcode .= 'play_btn_align_wave="true" ';
		}
		if ($settings['sr_player_on_artwork']){
			$shortcode .= 'display_control_artwork="true" ';
		}
		if (isset($settings['hide_trackdesc']) && $settings['hide_trackdesc'] == '1'){
			$shortcode .= 'hide_trackdesc="'. true .'" ';
		}
		if(function_exists( 'run_sonaar_music_pro' ) && get_site_option('SRMP3_ecommerce') == '1'){
			if( isset($settings['pagination_scroll_offset']) && $settings['pagination_scroll_offset'] !== '' ){
				$shortcode .= 'pagination_scroll_offset="' . $settings['pagination_scroll_offset'] . '" ';
			}
			if (isset($settings['lazy_load']) && $settings['lazy_load'] == 'true'){
				$shortcode .= 'lazy_load="true" ';
			}
			if ($settings['cta_dl_dv_enable'] == 'yes'){ //Download Visibility
				if (isset($settings['cta_dl_dv_state']) && $settings['cta_dl_dv_state'] !== '' && isset($settings['cta_dl_dv_condition']) && $settings['cta_dl_dv_condition'] !== ''){
					$cta_dl_dv_role = (isset($settings['cta_dl_dv_role']) && is_array($settings['cta_dl_dv_role'])) ? implode(', ', $settings['cta_dl_dv_role']) : '';
					$shortcode .= 'cta_visibility_download="' . $settings['cta_dl_dv_state'] . '|' . $settings['cta_dl_dv_condition'] . '|' . $cta_dl_dv_role . '|' . $settings['cta_dl_dv_not_met_action'] .'" ';
				}
				if (isset($settings['cta_dl_dv_redirect_url']) && $settings['cta_dl_dv_redirect_url'] !== ''){
					$shortcode .= 'cta_visibility_download_redirect_url="' . $settings['cta_dl_dv_redirect_url'] . '" ';
				}
			}
			if ($settings['cta_share_dv_enable'] == 'yes'){ //Download Visibility
				if (isset($settings['cta_share_dv_state']) && $settings['cta_share_dv_state'] !== '' && isset($settings['cta_share_dv_condition']) && $settings['cta_share_dv_condition'] !== ''){
					$cta_share_dv_role = (isset($settings['cta_share_dv_role']) && is_array($settings['cta_share_dv_role'])) ? implode(', ', $settings['cta_share_dv_role']) : '';
					$shortcode .= 'cta_visibility_share="' . $settings['cta_share_dv_state'] . '|' . $settings['cta_share_dv_condition'] . '|' . $cta_share_dv_role . '" ';
				}
				if (isset($settings['cta_share_dv_redirect_url']) && $settings['cta_share_dv_redirect_url'] !== ''){
					$shortcode .= 'cta_visibility_share_redirect_url="' . $settings['cta_share_dv_redirect_url'] . '" ';
				}
			}
			if ($settings['cta_favorites_dv_enable'] == 'yes'){ //Download Visibility
				if (isset($settings['cta_favorites_dv_state']) && $settings['cta_favorites_dv_state'] !== '' && isset($settings['cta_favorites_dv_condition']) && $settings['cta_favorites_dv_condition'] !== ''){
					$cta_favorites_dv_role = (isset($settings['cta_favorites_dv_role']) && is_array($settings['cta_favorites_dv_role'])) ? implode(', ', $settings['cta_favorites_dv_role']) : '';
					$shortcode .= 'cta_visibility_favorites="' . $settings['cta_favorites_dv_state'] . '|' . $settings['cta_favorites_dv_condition'] . '|' . $cta_favorites_dv_role . '" ';
				}
				if (isset($settings['cta_favorites_dv_redirect_url']) && $settings['cta_favorites_dv_redirect_url'] !== ''){
					$shortcode .= 'cta_visibility_favorites_redirect_url="' . $settings['cta_favorites_dv_redirect_url'] . '" ';
				}
			}
		}
		if(function_exists( 'run_sonaar_music_pro' )){
			if (isset($settings['artwork_set_background']) && $settings['artwork_set_background'] === 'yes'){
				$shortcode .= 'artwork_background="true" ';
			}
			if (isset($settings['artwork_set_background_gradient'])  && $settings['artwork_set_background_gradient'] === 'yes'){
				$shortcode .= 'artwork_background_gradient="true" ';
			}
			/*if (isset($settings['artwork_set_background'])){
				$shortcode .= 'artwork_set_background="true" ';
			}*/
			if (isset($settings['order'])){
				$shortcode .= 'order="' . $settings['order'] . '" ';
			}
			if (isset($settings['orderby'])){
				$shortcode .= 'orderby="' . $settings['orderby'] . '" ';
			}
			if (isset($settings['posts_not_in']) && $settings['posts_not_in'] != ''){
				$shortcode .= 'posts_not_in="' . $settings['posts_not_in'] . '" ';
			}
			if (isset($settings['category_not_in']) && $settings['category_not_in'] != '' && is_array($settings['category_not_in'])){
				$shortcode .= 'category_not_in="' . implode(", ", $settings['category_not_in']) . '" ';
			}
			if (isset($settings['query_by_author']) && $settings['query_by_author'] != '' && is_array($settings['query_by_author'])){
				$shortcode .= 'author="' . implode(", ", $settings['query_by_author']) . '" ';
			}
			if (isset($settings['query_by_author_current']) && $settings['query_by_author_current'] === 'true'){
				$current_author = get_query_var('author');
			    $shortcode .= 'author="' . $current_author . '" ';
			}
			if (isset($settings['track_desc_postcontent']) && $settings['track_desc_postcontent'] == 'true'){
				$shortcode .= 'track_desc_postcontent="true" ';
			}
			if (isset($settings['artist_hide']) && $settings['artist_hide'] === 'true'){
				$shortcode .= 'artist_hide="true" ';
			}
			if (isset($settings['artist_wrap']) && $settings['artist_wrap'] === 'true'){
				$shortcode .= 'artist_wrap="true" ';
			}
			if (isset($settings['scrollbar']) && $settings['scrollbar'] == '1'){
				$shortcode .= 'scrollbar="true" ';
			}
			if (isset($settings['duration_soundwave_show']) && $settings['duration_soundwave_show']=='yes'){
				$shortcode .= 'hide_times="true" ';
			}
			if (isset($settings['use_play_label_with_icon']) && $settings['use_play_label_with_icon']==''){
				$shortcode .= 'use_play_label_with_icon="false" ';
			}
			if (isset($settings['progress_bar_style']) && $settings['progress_bar_style'] !== 'default'){
				$shortcode .= 'progress_bar_style="' . $settings['progress_bar_style'] . '" ';
			}
			if (isset($settings['soundwave_show']) && $settings['soundwave_show']=='yes'){
				$shortcode .= 'hide_progressbar="true" ';
			}
			if (isset($settings['progressbar_inline']) && $settings['progressbar_inline']=='yes'){
				$shortcode .= 'progressbar_inline="true" ';
			}
			if (isset($settings['store_title_text'])){
				$shortcode .= 'store_title_text="' . $settings['store_title_text'] . '" ';
			}
			if (isset($settings['play_text'])){
				$shortcode .= 'play_text="' . $settings['play_text'] . '" ';
			}
			if (isset($settings['pause_text'])){
				$shortcode .= 'pause_text="' . $settings['pause_text'] . '" ';
			}
			if (isset($settings['album_store_position'])){
				$shortcode .= 'album_store_position="' . $settings['album_store_position'] . '" ';
			}
			if (isset($settings['no_track_skip']) && $settings['no_track_skip']=='yes'){
				$shortcode .= 'notrackskip="true" ';
			}
			if (isset($settings['no_loop_tracklist']) && $settings['no_loop_tracklist']=='yes'){
				$shortcode .= 'no_loop_tracklist="true" ';
			}
			if (isset($settings['strip_html_track_desc'])){
				$shortcode .= 'strip_html_track_desc="'. $settings['strip_html_track_desc'] .'" ';
			}
			if (isset($settings['track_desc_lenght'])){
				$shortcode .= 'track_desc_lenght="'. $settings['track_desc_lenght'] .'" ';
			}
			if (isset($settings['show_track_publish_date'])){
				$shortcode .= 'show_track_publish_date="'. $settings['show_track_publish_date'] .'" ';
			}
			if (isset($settings['track_list_linked'])){
				$shortcode .= 'post_link="'. $settings['track_list_linked'] .'" ';
			}
			if (isset($settings['button_hover_animation'])){
				$shortcode .= 'button_animation="'. $settings['button_hover_animation'] .'" ';
			}
			if (isset($settings['show_name_filter'])){
				$shortcode .= 'show_name_filter="'. $settings['show_name_filter'] .'" ';
			}
			if (isset($settings['show_date_filter'])){
				$shortcode .= 'show_date_filter="'. $settings['show_date_filter'] .'" ';
			}
			if (isset($settings['show_duration_filter'])){
				$shortcode .= 'show_duration_filter="'. $settings['show_duration_filter'] .'" ';
			}
			if (isset($settings['track_memory'])){
				$shortcode .= 'track_memory="'. $settings['track_memory'] .'" ';
			}
			if (isset($settings['show_prevnext_bt']) && $settings['show_prevnext_bt']=='true'){
				$shortcode .= 'show_prevnext_bt="true" ';
			}
			if (isset($settings['control_alignment']) && $settings['control_alignment']=='left'){
				$shortcode .= 'control_alignment="left" ';
			}

			if (isset($settings['tracklist_soundwave_show']) && $settings['tracklist_soundwave_show'] == 'true'){
				$shortcode .= 'tracklist_soundwave_show="true" ';

				if (isset($settings['tracklist_soundwave_color']) && $settings['tracklist_soundwave_color'] != ''){
					$shortcode .= 'tracklist_soundwave_color="' . $settings['tracklist_soundwave_color'] . '" ';
				}
				if (isset($settings['tracklist_soundwave_progress_color']) && $settings['tracklist_soundwave_progress_color'] != ''){
					$shortcode .= 'tracklist_soundwave_progress_color="' . $settings['tracklist_soundwave_progress_color'] . '" ';
				}
				if (isset($settings['tracklist_soundwave_style']) && $settings['tracklist_soundwave_style'] !== 'default'){
					$shortcode .= 'tracklist_soundwave_style="' . $settings['tracklist_soundwave_style'] . '" ';
				}
				if (isset($settings['tracklist_soundwave_bar_width']) && $settings['tracklist_soundwave_bar_width']['size'] != '' ){
					$shortcode .= 'tracklist_soundwave_bar_width="' . $settings['tracklist_soundwave_bar_width']['size'] . '" ';
				}
				if (isset($settings['tracklist_soundwave_bar_gap']) && $settings['tracklist_soundwave_bar_gap']['size'] !== '' ){
					$shortcode .= 'tracklist_soundwave_bar_gap="' . $settings['tracklist_soundwave_bar_gap']['size'] . '" ';
				}
				if (isset($settings['tracklist_soundwave_linecap']) && ($settings['tracklist_soundwave_linecap'] != 'default') ){
					$shortcode .= 'tracklist_soundwave_line_cap="'. $settings['tracklist_soundwave_linecap'] .'" ';
				}
				if (isset($settings['tracklist_soundwave_cursor']) && $settings['tracklist_soundwave_cursor'] == 'true'){
					$shortcode .= 'tracklist_soundwave_cursor="true" ';
				}

			}
			if (isset($settings['trackList_layout'])){
				$shortcode .= 'tracklist_layout="'. $settings['trackList_layout'] .'" ';

				if (isset($settings['grid_column_number']) && $settings['trackList_layout']=='grid'){
					$desktopValue = $settings['grid_column_number'];
					$tabetValue = ( isset($settings['grid_column_number_tablet']) && $settings['grid_column_number_tablet'] != '')? $settings['grid_column_number_tablet'] : $desktopValue;
					$mobileValue = ( isset($settings['grid_column_number_mobile']) && $settings['grid_column_number_mobile'] != '')? $settings['grid_column_number_mobile'] : $tabetValue;
					$shortcode .= 'grid_column_number="'. $desktopValue . ',' . $tabetValue . ',' . $mobileValue .'" ';
				}
			}

			if( $playlist_show_soundwave && isset($settings['miniplayer_meta_repeater']) && isset($settings['miniplayer_meta_hide']) && $settings['miniplayer_meta_hide']!='yes' ){ //Mini Player is not hidden
				$miniplayer_meta_order = "";
				$miniplayer_meta_id = "";
				
				foreach ($settings['miniplayer_meta_repeater'] as $index => $value) {
					if(isset($value['miniplayer_order_meta'])){
						$miniplayer_meta_order .= 'meta_' . $value['miniplayer_order_meta'];
					}
					if( isset($value['miniplayer_order_meta']) && $value['miniplayer_order_meta'] == 'custom_heading' && isset($value['miniplayer_custom_heading'])){
						$miniplayer_meta_order .= '::' . $value['miniplayer_custom_heading'];
					}
					
					if( isset($value['miniplayer_order_meta']) && $value['miniplayer_order_meta'] == 'key' && isset($value['custom_field_key'])){
						$miniplayer_meta_order .= '::' . $value['custom_field_key'];
					}
					if( isset($value['miniplayer_order_meta']) && $value['miniplayer_order_meta'] == 'acf_field' && isset($value['miniplayer_meta_acf'])){
						$miniplayer_meta_order .= '::' . $value['miniplayer_meta_acf'];
					}
					if( isset($value['miniplayer_order_meta']) && ($value['miniplayer_prefix'] !== '')){
						$miniplayer_meta_order .= '::prefix_' . $value['miniplayer_prefix'];
					}
					if(isset($value['miniplayer_meta_tag']) && $value['miniplayer_meta_tag'] != 'default'){
						$miniplayer_meta_order .= '::' . $value['miniplayer_meta_tag'];
					}
					
					if($index < count($settings['miniplayer_meta_repeater'])-1){
						$miniplayer_meta_order .= '||';
					}
					if(isset($value['_id'])){
						$miniplayer_meta_id .= $value['_id'] . ',';
					}
				}
	
				$miniplayer_meta_order = htmlspecialchars($miniplayer_meta_order);
				$shortcode .= 'player_metas="'. $miniplayer_meta_order .'" ';

				if($miniplayer_meta_id != ""){
					$shortcode .= 'miniplayer_meta_id="'. $miniplayer_meta_id .'" ';
				}
			}else if(isset($settings['miniplayer_meta_hide']) && $settings['miniplayer_meta_hide']=='yes'){
				$shortcode .= 'player_metas="" ';
			}

			if( $settings['slider'] != ''){
				$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_breakpoints();
				$breakpointMobile = $breakpoints['mobile']->get_value();
				$breakpointTablet = $breakpoints['tablet']->get_value();
				if (isset($settings['slide_source'])){
					$shortcode .= 'slide_source="'. $settings['slide_source'] .'" ';
				}else if( $settings['playlist_source']== 'from_current_post'){
					$shortcode .= 'slide_source="track" ';
				}
				if (isset($settings['slider_play_on_hover']) && $settings['slider_play_on_hover'] == 'yes'){
					$shortcode .= 'slider_play_on_hover="true" ';
				}
				if (isset($settings['slider_content_on_active']) && $settings['slider_content_on_active'] == 'yes'){
					$shortcode .= 'slider_content_on_active="true" ';
				}
				if (isset($settings['slider_content_on_hover']) && $settings['slider_content_on_hover'] == 'yes'){
					$shortcode .= 'slider_content_on_hover="true" ';
				}
				if (isset($settings['slider_hide_album_title']) && $settings['slider_hide_album_title'] == 'yes'){
					$shortcode .= 'slider_hide_album_title="true" ';
				}
				if (isset($settings['slider_hide_track_title']) && $settings['slider_hide_track_title'] == 'yes'){
					$shortcode .= 'slider_hide_track_title="true" ';
				}
				if (isset($settings['slider_arrow_style'])){
					$shortcode .= 'slider_arrow_style="' . $settings['slider_arrow_style'] . '" ';
				}
				if (isset($settings['slider_navigation_placement']) && $settings['slider_navigation_placement'] == 'outside'){
					$shortcode .= 'slider_navigation_placement="outside" ';
				}
				if (isset($settings['slider_pagination_placement']) && $settings['slider_pagination_placement'] == 'outside'){
					$shortcode .= 'slider_pagination_placement="outside" ';
				}
				if (isset($settings['slider_titles_order']) && $settings['slider_titles_order'] == 'yes'){
					$shortcode .= 'slider_titles_order="true" ';
				}
				if (isset($settings['slider_navigation_vertical_alignment'])){
					$shortcode .= 'slider_navigation_vertical_alignment="' . $settings['slider_navigation_vertical_alignment'] . '" ';
				}
				if (isset($settings['slider_move_content_below_image']) && $settings['slider_move_content_below_image'] == 'yes'){
					$shortcode .= 'slider_move_content_below_image="true" ';
				}
				if (isset($settings['slider_hide_artist']) && $settings['slider_hide_artist'] == 'yes'){
					$shortcode .= 'slider_hide_artist="true" ';
				}
				$parameters ="{";
				$parameters .= (isset($settings['slider_loop']) && $settings['slider_loop'] == 'yes') ? "loop:true," : "loop:false,";
				if (isset($settings['hide_slider_navigation']) && $settings['hide_slider_navigation'] != 'yes'){
					$parameters .= "navigation:{nextEl:'.srp_swiper-button-next',prevEl:'.srp_swiper-button-prev',},";
				}
				if (isset($settings['slider_space_between'])){
					$parameters .= "spaceBetween:". $settings['slider_space_between']['size'] .",";
				}
				if (isset($settings['hide_slider_pagination']) && $settings['hide_slider_pagination'] != 'yes'){
					$parameters .= "pagination:{el:'.swiper-pagination',clickable:true,dynamicBullets:true, dynamicMainBullets:5},";
				}
				if (isset($settings['slide_to_show'])){
					$parameters .= "slidesPerView:". $this->get_swiper_responsive_value('slide_to_show') .",";
				}
				if (isset($settings['slide_transition_duration']) && isset($settings['slide_transition_duration']['size'])){
					$parameters .= "speed:". $settings['slide_transition_duration']['size'] .",";
				}
				if (isset($settings['slide_autoplay']) && $settings['slide_autoplay'] == 'yes'){
					$parameters .= "autoplay: { ";
					$parameters .= ( isset($settings['slide_delay']) )? "delay: " . $settings['slide_delay'] . ", " : "3000";
					$parameters .= ( isset($settings['slide_pause_on_hover']) && $settings['slide_pause_on_hover'] == 'yes' )? "pauseOnMouseEnter: true, " : "";
					$parameters .= ( isset($settings['slide_disable_on_interaction']) && $settings['slide_disable_on_interaction'] != 'yes' )? "disableOnInteraction: false, " : "";
					$parameters .= ( isset($settings['slide_direction']) && $settings['slide_direction'] == 'left' )? "reverseDirection: true, " : "";
					$parameters .= "},";
				}
				if (isset($settings['slide_keyboard_ctrl']) && $settings['slide_keyboard_ctrl'] == 'yes'){
					$parameters .= "keyboard: { enabled: true, onlyInViewport: true, },";
				}
				if (isset($settings['slide_mousewheel_ctrl']) && $settings['slide_mousewheel_ctrl'] == 'yes'){
					if (isset($settings['invert_mousewheel_ctrl']) && $settings['invert_mousewheel_ctrl'] == 'yes'){
						$parameters .= "mousewheel: { invert: true,},";
					}else{
						$parameters .= "mousewheel: true,";
					}
				}
				if (isset($settings['slider_effect'])){
					$parameters .= "effect:'". $settings['slider_effect']."',";
					if($settings['slider_effect'] == 'coverflow'){ //Set coverflow params
						$parameters .= "centeredSlides:true,";
						$parameters .= "coverflowEffect:{ scale: 0.9, ";
						if (isset($settings['slide_shadows']) && $settings['slide_shadows'] != 'yes'){
							$parameters .= "slideShadows: false,";
						}
						if (isset($settings['slide_depth'])){
							$parameters .= "depth:". $settings['slide_depth']['size'] .",";
						}
						if (isset($settings['slide_rotate'])){
							$parameters .= "rotate:". $settings['slide_rotate']['size'] .",";
						}
						if (isset($settings['slide_stretch'])){
							$parameters .= "stretch:". $settings['slide_stretch']['size'] .",";
						}
						$parameters .= " },";
					}
				}
				$parameters .= "breakpoints:{ "; 
				if (isset($settings['slide_to_show'])){
					$parameters .= 	$breakpointMobile . ": {slidesPerView: ". $this->get_swiper_responsive_value('slide_to_show', 'mobile') . " }, ";
					$parameters .= 	$breakpointTablet . ": {slidesPerView: ". $this->get_swiper_responsive_value('slide_to_show', 'tablet') . " }, ";
				}
				$parameters .=  "},";
				$parameters .="}";
				$shortcode .= 'slider_param="'. $parameters .'" ';
			}
			/*DEPRECATED OPTION*/
			if ((isset($settings['title_soundwave_show']) && $settings['title_soundwave_show']=='yes') || (isset($settings['playlist_title_soundwave_show']) && $settings['playlist_title_soundwave_show']=='yes')){
				$shortcode .= 'hide_track_title="true" ';
			}
		}	
		
		if ($settings['album_img']){
			//WIP test this.
			$attachImg = wp_get_attachment_image_src( $settings['album_img']['id'], 'large' );
			$album_img = (is_array($attachImg)) ? $attachImg[0] : '';
			$shortcode .= 'artwork="' .  $album_img . '" ';
			update_post_meta( get_the_ID(), 'srmp3_elementor_artwork', $album_img); // update post meta to retrieve data in json later
		}
		if ($settings['play_current_id']=='yes' || $settings['playlist_source']=='from_current_post'){ //If "Play its own Post ID track" option is enable
			$postid = get_the_ID();
			$shortcode .= 'albums="' . $postid . '" ';
			if (isset($settings['audio_meta_field']) && $settings['audio_meta_field'] !=''){ // Use the audio_meta_field field
				$shortcode .= 'audio_meta_field="' . $settings['audio_meta_field'] . '" ';
				if(isset($settings['repeater_meta_field']) && $settings['repeater_meta_field'] !=''){
					$shortcode .= 'repeater_meta_field="' . $settings['repeater_meta_field'] . '" ';	
				}
			}
		}else if($settings['playlist_source']=='from_favorites'){
			$shortcode .= 'albums="favorites" ';
		}else if($settings['playlist_source']=='recently_played'){
			$shortcode .= 'albums="recentlyplayed" ';
			$shortcode .= (isset($settings['posts_per_page'])) ? 'posts_per_page="' . $settings['posts_per_page'] . '" ' : '';
		}else{
			$display_playlist_ar = $settings['playlist_list'];
			$display_playlist_cat_ar = (isset($settings['playlist_list_cat'])) ? $settings['playlist_list_cat'] : null;
			if(is_array($display_playlist_ar)){
				$display_playlist_ar = implode(", ", $display_playlist_ar); 
			}
			if(is_array($display_playlist_cat_ar)){
				$display_playlist_cat_ar = implode(", ", $display_playlist_cat_ar); 
			}
			if(!$display_playlist_cat_ar && $settings['playlist_source'] == 'from_cat'){
				$shortcode .= 'category="all" ';
				$shortcode .= (isset($settings['posts_per_page'])) ? 'posts_per_page="' . $settings['posts_per_page'] . '" ' : '';
			}else if($display_playlist_cat_ar && $settings['playlist_source'] == 'from_cat'){
				$shortcode .= 'category="'. $display_playlist_cat_ar . '" ';
				$shortcode .= (isset($settings['posts_per_page'])) ? 'posts_per_page="' . $settings['posts_per_page'] . '" ' : '';
			}

			if ($settings['playlist_source'] == 'from_current_term'){
				$shortcode .= 'category="current" ';
			}else if (!$display_playlist_ar) { //If no playlist is selected, play the latest playlist
				if($settings['playlist_source'] == 'from_cpt' ){
					$shortcode .= 'play-latest="true" ';
				}
				if ($settings['playlist_source'] == 'from_elementor'){
					if ( isset( \Elementor\Plugin::$instance->documents ) ) {
						$post_id = \Elementor\Plugin::$instance->documents->get_current()->get_main_id();
						$shortcode .= 'albums="' . $post_id . '" ';
					}
					if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
						if (!$settings['feed_repeater']){
							echo esc_html__('Add tracks in the widget settings.', 'sonaar-music');
						}
					}
				}
				
			} else {
				$shortcode .= 'albums="' . $display_playlist_ar . '" ';
			}
		
		}

		if ( function_exists( 'run_sonaar_music_pro' ) &&  get_site_option('SRMP3_ecommerce') == '1' && !empty($settings['cf_repeater'])) {
			$shortcode .=( $settings['searchbar_cf_heading_show'] && $settings['searchbar_cf_heading_show']==true) ? 'custom_fields_heading="true" ' : '';
			$cf_repeater_ar = array();

			foreach ($settings['cf_repeater'] as $key => $value) {
				$value['column_name'] = ($value['column_name']) ? $value['column_name'] : '';
				$value['column_width']['size'] = ($value['column_width']['size']) ? $value['column_width']['size'] : '100';
				$value['column_width']['unit'] = ($value['column_width']['unit']) ? $value['column_width']['unit'] : 'px';
				$fieldKey = '';
				if($value['custom_field_key'] != '' ){
					$value[$fieldKey] = $value['custom_field_key'];
				}else if($value['custom_field_plugin'] == 'customkey' && $value['custom_field_key'] == '' ){
					$value[$fieldKey] = 'null';
				}else{
					$fieldKey = 'column_fields_' .  $value['custom_field_plugin'];
				}

				$icon_val = '';
				if(isset($value['column_fields_icon']['value'])){
					$icon_val = $value['column_fields_icon']['value'];
				}
				if(isset($value['column_fields_icon']['value']['url'])){
					$icon_val = $value['column_fields_icon']['value']['url'];
				}
				if(!empty($icon_val)){
					$icon_val = '::' . $icon_val;
				}
			
				array_push( $cf_repeater_ar, $value['column_name'] . '::' . $value[$fieldKey].'::'.$value['column_width']['size'].$value['column_width']['unit'] . $icon_val );
			}
			
			$cf_repeater_ar = (isset($cf_repeater_ar) && is_array($cf_repeater_ar)) ? implode(";", $cf_repeater_ar):'';
			
			$shortcode .= ($cf_repeater_ar != '') ? 'custom_fields_columns="' . $cf_repeater_ar . '" ':'';
		}
		
		$shortcode .= ']';
		
		//Attention: double brackets are required if using var_dump to display a shortcode otherwise it will render it!
		//print_r("Shortcode = [" . $shortcode . "]");
		echo do_shortcode( $shortcode );



	}
	private function check_column_plugin_activated(){
		$source = array(
			'object' => __( 'Post/Term/User/Object Data', 'sonaar-music' ),
		);
		
		if (function_exists( 'acf_get_fields' )){
			$source['acf'] = 'ACF';
		}
		if (function_exists( 'jet_engine' )){
			$source['jetengine'] = 'Jet Engine';
		}
		$source['customkey'] = 'Custom Meta Key';
		return $source;
	}

	private function metaOptions(){
		$source = array(
			'custom_heading' => esc_html__( 'Custom Heading', 'sonaar-music' ),
			'playlist_title' => sprintf(esc_html__( '%1$s/Post Title', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('album/podcast')) ),
			'track_title' => sprintf(esc_html__( '%1$s Title', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
			'artist_name' => esc_html__( 'Artist Name', 'sonaar-music' ),
			'description' => sprintf(esc_html__( '%1$s Description', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
			'duration' => sprintf(esc_html__( '%1$s Duration', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),
			'categories' => esc_html__( 'Categories', 'sonaar-music' ),
			'tags' => esc_html__( 'Tags', 'sonaar-music' ),
		);	
		if ( Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
			$source['podcast_show'] = esc_html__( 'Podcast Show', 'sonaar-music' );
		}
		if (function_exists( 'acf_get_fields' )){
			$source['acf_field'] = esc_html__( 'ACF', 'sonaar-music' );
		}
		/*if (function_exists( 'jet_engine' )){
			$source['jetengine_field'] = esc_html__( 'Jet Engine', 'sonaar-music' );
		}*/
		$source['key'] = esc_html__( 'Custom Meta Key', 'sonaar-music' );
		return $source;
	}

	private function get_orderby() {
		$groups = array(
			'ID'            => __( 'ID', 'sonaar-music' ),
			'author'        => __( 'Author', 'sonaar-music' ),
			'title'         => __( 'Post Title', 'sonaar-music' ),
			'name'          => __( 'Post Slug', 'sonaar-music' ),
			'date'          => __( 'Published Date', 'sonaar-music' ),
			'modified'      => __( 'Modified', 'sonaar-music' ),
			'menu_order'    => __( 'Menu order', 'sonaar-music' ),
			'srmp3_track_length' => __( 'Duration Length', 'sonaar-music' ),
			//'parent'        => __( 'Parent', 'sonaar-music' ),
			//'comment_count' => __( 'Comment count', 'sonaar-music' ),
			//'relevance'     => __( 'Relevance', 'sonaar-music' ),
			//'type'          => __( 'Post Type', 'sonaar-music' ),
			//'meta_value'    => __( 'Meta value', 'sonaar-music' ),
			//'meta_clause'   => __( 'Meta clause', 'sonaar-music' ),
			//'post__in'      => __( 'Preserve post ID order given in the "Include posts by IDs" option', 'sonaar-music' ),
		);

		if (defined( 'WC_VERSION' )){
			$groups += array(
					'_price'			=> __( 'Price', 'sonaar-music' ),
					'_sale_price'		=> __( 'Sale Price', 'sonaar-music' ),
					'_sku'				=> __( 'SKU', 'sonaar-musice' ),
					'total_sales'		=> __( 'Total Sales', 'sonaar-music' ),
					'_wc_average_rating'=> __( 'Average Rating', 'sonaar-music' ),
					'_stock_status'		=> __( 'Stock Status', 'sonaar-music' ),
			);
		}
	
		return $groups;
	}

	/**
	 * Retuns current object fields array
	 * @return [type] [description]
	 */
	public function get_object_fields( $where = 'elementor', $blocks_values_key = 'values' ) {
		// Get the post types
		$sr_postypes = Sonaar_Music_Admin::get_cpt($all = true);

		// Define groups array with initial common post fields
		$groups = array(
			array(
				'label'  => __( 'Post', 'sonaar-music' ),
				'options' => array(
					'post_id'       => __( 'Post ID', 'sonaar-music' ),
					'post_title'    => __( 'Post Title', 'sonaar-music' ),
					'post_date'     => __( 'Post Date', 'sonaar-music' ),
					'post_modified' => __( 'Post Date Modified', 'sonaar-music' ),
				)
			)
		);
		 // Loop through each post type
		foreach ($sr_postypes as $post_type) {
			$post_type_obj = get_post_type_object($post_type);
			if (!$post_type_obj) {
				// Log the error or handle the case where the post type object is not retrieved
				//error_log('Failed to retrieve post type object for: ' . $post_type);
				continue; // Skip to the next iteration
			}
			
			if (!defined('SR_PLAYLIST_CPT')) {
				define('SR_PLAYLIST_CPT', 'sr_playlist');
			}
			
			if($post_type == SR_PLAYLIST_CPT){
				$groups[] = array(
					'label'  => esc_html__( 'Tracks', 'sonaar-music' ),
					'options' => array(
						''	        	=> __( 'Select...', 'sonaar-music' ),
						'srmp3_cf_album_title'  => sprintf( esc_html__( '%1$s Title', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('album/podcast')) ),//__( 'Audio Image', 'sonaar-music' ),__( 'Album Title', 'sonaar-music' ),
						'srmp3_cf_album_img'  	=> sprintf( esc_html__( '%1$s Image', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),//__( 'Audio Image', 'sonaar-music' ),
						'srmp3_cf_audio_title'  => sprintf( esc_html__( '%1$s Title', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),//__( 'Audio Image', 'sonaar-music' ),
						'srmp3_cf_length'       => sprintf( esc_html__( '%1$s Duration', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),//__( 'Audio Image', 'sonaar-music' ),
						'srmp3_cf_description'  => sprintf( esc_html__( '%1$s Description', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('track')) ),//__( 'Audio Image', 'sonaar-music' ),
						'playlist-category'  => sprintf( esc_html__( '%1$s Category', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('playlist')) ),//__( 'Audio Image', 'sonaar-music' ),
						'playlist-tag'  => sprintf( esc_html__( '%1$s Tag', 'sonaar-music' ), ucfirst(Sonaar_Music_Admin::sr_GetString('playlist')) ),//__( 'Audio Image', 'sonaar-music' ),
						'srmp3_cf_artist'  		=> __( 'Artist Name', 'sonaar-music' ),
						'podcast-show'        	=> __( 'Podcast Show', 'sonaar-music' )
					)
				);
			} else if (defined( 'WC_VERSION' ) && $post_type == 'product'){

				$wcOptions = array(
					'product_cat'       => __('Product Categories', 'sonaar-music'),
					'product_tag'       => __('Product Tags', 'sonaar-music'),
					'_price'            => __('Price', 'sonaar-music'),
					'_sale_price'       => __('Sale Price', 'sonaar-music'),
					'_sku'              => __('SKU', 'sonaar-music'),
					'total_sales'       => __('Total Sales', 'sonaar-music'),
					'_wc_average_rating'=> __('Average Rating', 'sonaar-music'),
					'_stock_status'     => __('Stock Status', 'sonaar-music'),
				);
			
				// Retrieve and format WooCommerce attribute taxonomies
				$wcAttributes = wc_get_attribute_taxonomy_labels();
				if (is_array($wcAttributes)) {
					foreach ($wcAttributes as $key => $label) {
						$wcOptions['pa_' . $key] = 'Attribute: ' . $label;
					}
				}
			
				// Append to the groups array
				$groups[] = array(
					'label'   => esc_html__('WooCommerce', 'sonaar-music'),
					'options' => $wcOptions
				);
			} else {
				// General handling for other post types
				$taxonomies = get_object_taxonomies($post_type, 'objects');
				$taxonomy_options = array();
				foreach ($taxonomies as $taxonomy) {
					$taxonomy_options[$taxonomy->name] = sprintf( esc_html__( '%1$s', 'sonaar-music' ), ucfirst($taxonomy->labels->singular_name) );
				}
	
				$groups[] = array(
					'label' => sprintf( esc_html__( '%1$s Taxonomies', 'sonaar-music' ), $post_type_obj->labels->singular_name ),
					'options' => $taxonomy_options
				);
			}
		}

		
		//$groups[2]['options'] = $my_cat_ar;
		return $groups;

	}
	/**
	 * Get meta fields for post type
	 *
	 * @return array
	 */
	public function get_meta_fields_for_post_type() {

		if ( jet_engine()->meta_boxes ) {
			return jet_engine()->meta_boxes->get_fields_for_select( 'plain' );
		} else {
			return array();
		}

	}
	public function get_fields_goups( $group = 'fields' ) {
		$cb = array(
			'fields'   => 'map_fields',
			/*'images'   => 'map_images',
			'links'    => 'map_links',
			'repeater' => 'map_repeater',*/
		);

		$groups = (null !== $this->get_raw_goups()) ? $this->get_raw_goups() : '';
		
		$result = array(
			''        	=> __( 'Select...', 'sonaar-music' )
		);

		if ( empty( $groups ) ) {
			return $result;
		}

		foreach ( $groups as $data ) {

			$fields = array_filter( array_map( array( $this, $cb[ $group ] ), $data['options'] ) );

			if ( ! empty( $fields ) ) {
				$result[] = array(
					'label'   => $data['label'],
					'options' => $fields,
				);
			}

		}
		return $result;

	}
/**
		 * Map fields callback
		 *
		 * @param  [type] $field [description]
		 * @return [type]        [description]
		 */
		public function map_fields( $field ) {

			$whitelisted = $this->whitelisted_fields();
			$type        = $field['type'];

			if ( ! in_array( 'field', $whitelisted[ $type ] ) ) {
				return false;
			} else {
				return $field['label'];
			}
		}
		/**
		 * Fields groups
		 *
		 * @return array
		 */
		public function get_raw_goups() {
			
			if ( isset($this->fields_groups) && null !== $this->fields_groups ) {
				return $this->fields_groups;
			}

			// ACF >= 5.0.0
			if ( function_exists( 'acf_get_field_groups' ) ) {
				$groups = acf_get_field_groups();
			} else {
				$groups = apply_filters( 'acf/get_field_groups', [] );
			}

			$options_page_groups_ids = array();

			if ( function_exists( 'acf_options_page' ) ) {
				$pages = acf_options_page()->get_pages();

				foreach ( $pages as $slug => $page ) {
					$options_page_groups = acf_get_field_groups( array(
						'options_page' => $slug,
					) );

					foreach ( $options_page_groups as $options_page_group ) {
						$options_page_groups_ids[] = $options_page_group['ID'];
					}
				}
			}

			$result      = array();
			$whitelisted = $this->whitelisted_fields();

			foreach ( $groups as $group ) {

				// ACF >= 5.0.0
				if ( function_exists( 'acf_get_fields' ) ) {
					$fields = acf_get_fields( $group['ID'] );
				} else {
					$fields = apply_filters( 'acf/field_group/get_fields', [], $group['id'] );
				}

				$options = [];

				if ( ! is_array( $fields ) ) {
					continue;
				}

				$has_option_page_location = in_array( $group['ID'], $options_page_groups_ids, true );
				$is_only_options_page = $has_option_page_location && 1 === count( $group['location'] );

				foreach ( $fields as $field ) {

					if ( ! isset( $whitelisted[ $field['type'] ] ) ) {
						continue;
					}

					if ( $has_option_page_location ) {
						$key = 'options::' . $field['name'];

						$options[ $key ] = array(
							'type'  => $field['type'],
							'label' => __( 'Options', 'sonaar-music' ) . ':' . $field['label'],
						);

						if ( $is_only_options_page ) {
							continue;
						}
					}

					$key = $field['name'];
					$options[ $key ] = array(
						'type'  => $field['type'],
						'label' => $field['label']
					);
				}

				if ( empty( $options ) ) {
					continue;
				}

				$result[] = array(
					'label'   => $group['title'],
					'options' => $options,
				);
			}

			$this->fields_groups = $result;
			return $this->fields_groups;

		}
/**
		 * Returns whitelisted fields
		 *
		 * @return [type] [description]
		 */
		public function whitelisted_fields() {

			return array(
				'text'             => array( 'field', 'link' ),
				'textarea'         => array( 'field' ),
				'number'           => array( 'field' ),
				'range'            => array( 'field' ),
				'email'            => array( 'field', 'link' ),
				'url'              => array( 'field', 'link' ),
				'wysiwyg'          => array( 'field' ),
				'image'            => array( 'link', 'image' ),
				'file'             => array( 'field', 'link' ),
				'gallery'          => array( 'field' ),
				'select'           => array( 'field' ),
				'radio'            => array( 'field' ),
				'checkbox'         => array( 'field' ),
				'button_group'     => array( 'field' ),
				'true_false'       => array( 'field' ),
				'page_link'        => array( 'field', 'link' ),
				'post_object'      => array( 'field', 'link' ),
				'relationship'     => array( 'field', 'link' ),
				'taxonomy'         => array( 'field', 'link' ),
				'date_picker'      => array( 'field', 'link' ),
				'date_time_picker' => array( 'field' ),
				'time_picker'      => array( 'field' ),
				'repeater'         => array( 'repeater' ),
				'oembed'           => array( 'field' ),
			);

		}
	public function render_plain_content() {
		$settings = $this->get_settings_for_display();
		$playlist_show_album_market = ( ( $settings['player_layout'] != 'skin_button' && $settings['playlist_show_album_market']=="yes" )  || ( $settings['player_layout'] == 'skin_button' && $settings['playlist_show_album_market_skin_button']=="yes" ) ) ? 'true' : 'false';
		$playlist_reverse_tracklist = (function_exists( 'run_sonaar_music_pro' ) && isset($settings['reverse_tracklist']) && $settings['reverse_tracklist'] == "yes") ? true : false;
		if ( function_exists( 'run_sonaar_music_pro' ) ){
			$sticky_player = $settings['enable_sticky_player'];
			$shuffle = $settings['enable_shuffle'];
			$wave_color = $settings['soundWave_bg_bar_color'];
			$wave_progress_color = $settings['soundWave_progress_bar_color'];
		}else{
			$sticky_player = false;
			$shuffle = false;
			$wave_color = false;
			$wave_progress_color = false;
		}
		
		$shortcode = '[sonaar_audioplayer titletag_playlist="'. isset($settings['title_html_tag_playlist']) .'" store_title_text="' . isset($settings['store_title_text']) .'" hide_artwork="' . isset($playlist_playlist_hide_artwork) .'" show_playlist="' . isset($playlist_show_playlist) .'" reverse_tracklist="' . $playlist_reverse_tracklist .'" show_track_market="' . isset($playlist_hide_track_market) .'" show_album_market="' . isset($playlist_show_album_market) .'" hide_timeline="' . isset($playlist_show_soundwave) .'" sticky_player="' . isset($sticky_player) .'" wave_color="' . isset($wave_color) .'" wave_progress_color="' . isset($wave_progress_color) .'" shuffle="' . isset($shuffle) .'" ';
		
		if ($settings['play_current_id']=='yes' || $settings['playlist_source']=='from_current_posts'){
			$postid = get_the_ID();
			$shortcode .= 'albums="' . $postid . '" ';
		}else{
			$display_playlist_ar = $settings['playlist_list'];

			if(is_array($display_playlist_ar)){
				$display_playlist_ar = implode(", ", $display_playlist_ar); 
			}
			if (!$display_playlist_ar) { //If no playlist is selected, play the latest playlist
				
				if($settings['playlist_source'] == 'from_cpt' ){
					$shortcode .= 'play-latest="true" ';
				}
				if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
					if ($settings['playlist_source'] == 'from_elementor'  && !$settings['feed_repeater'] ){
						echo esc_html__('Add tracks in the widget settings.', 'sonaar-music');
					}
				}
			}else{
				$shortcode .= 'albums="' . $display_playlist_ar . '" ';
			}
		
		}
		$shortcode .= ']';
		echo do_shortcode( $shortcode );
	}
}
Plugin::instance()->widgets_manager->register( new SR_Audio_Player() );