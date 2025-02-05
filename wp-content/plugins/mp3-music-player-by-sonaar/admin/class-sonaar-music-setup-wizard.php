<?php

class Sonaar_Music_Setup_Wizard {

    private $config;

    public function __construct() {
        add_action( 'srmp3_cpt_defined', array($this, 'initialize_config'));
        add_action( 'admin_menu', array($this, 'register_wizard_page'));
        //add_action( 'admin_menu', array($this, 'add_setup_wizard_menu'), 100);
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_styles_and_scripts'));
        add_action( 'current_screen', array( $this, 'disable_admin_notices') );
        add_filter( 'admin_body_class', array( $this, 'add_loading_classes' ) );
        // Setup wizard redirect on activation
        add_action( 'admin_init', array($this, 'maybe_redirect_to_wizard'));
    }

    public function register_wizard_page() {
        // Handle the skip setup button click
        add_submenu_page(
            'admin.php', // Parent slug
            'Setup Wizard', // Page title
            'Setup Wizard', // Menu title
            $this->config['capability'], // Capability
            $this->config['wizard_slug'], // Menu slug
            //array($this, 'redirect_to_wizard'), // Callback function
            array($this, 'setup_wizard_page'), // Callback function
            $this->config['position'] // Position to ensure it's the last item
        );
    }
   
    public function maybe_redirect_to_wizard() {
        if (get_option('srmp3_free_wizard_redirect', false)) {

            if ( wp_doing_ajax() ) {
                return;
            }

            delete_option('srmp3_free_wizard_redirect');

            //check if wizard has been executed before
            if (get_option('srmp3_free_wizard_shown', false)) {
                return;
            }

            //We check if the peaks dir has been previously created. There is high chance people already used the plugin and thus, we don't show the wizard on activation.
            if (file_exists(Sonaar_Music::get_peak_dir())) {
                return;
            }

            // Check if the user has the capability to manage options
            if ( ! current_user_can( 'manage_options' ) ) {
                return;
            }

            // Check if the user is a network admin or if the plugin is being activated in bulk
            if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
                return;
            }

            // Use the config array to build the redirect URL
            $wizard_url = admin_url($this->config['menu_slug'] . '?page=' . $this->config['wizard_slug']);
            wp_safe_redirect($wizard_url);
            exit;
        }
    }

    private function is_setup_wizard() {
		return isset( $_GET['page'] ) &&
			'mp3-player-setup-wizard' === $_GET['page'];
	}
   
    
   
    public function add_loading_classes( $classes ) {
		if ( $this->is_setup_wizard() ) {
			$classes .= ' srmp3-admin-full-screen';
		}

		return $classes;
	}
 
    public function disable_admin_notices() {
        if ($this->is_setup_wizard()) {
            remove_all_actions('admin_notices');
            add_action('admin_print_styles', array($this, 'srmp3_hide_admin_notices_with_css'));
        }
    }

    public function srmp3_hide_admin_notices_with_css() {
        echo '<style>
            .notice:not(.notice-success) {
                display: none !important;
            }
        </style>';
    }
    public function enqueue_youtube_api_and_seek_script() {
        // Enqueue the YouTube Iframe API
        wp_enqueue_script( 'youtube-api', 'https://www.youtube.com/iframe_api', array(), null, true );
    
        // Inline script to control YouTube iframe and seeking
        $inline_script = "
        var player;
        var playerReady = false; // Track if the player is ready
        var retries = 0;
        
        // Polling function to check if the YouTube API is ready
        function checkYouTubeAPIReady() {
            if (typeof YT !== 'undefined' && typeof YT.Player !== 'undefined') {
                //console.log('YouTube API is ready');
                createPlayer();
            } else if (retries < 10) {
                retries++;
                //console.log('Retrying to create player, attempt ' + retries);
                setTimeout(checkYouTubeAPIReady, 500);  // Retry every 500ms, up to 10 times
            } else {
                //console.error('YouTube API not loaded after multiple attempts.');
            }
        }
        
        // Create the YouTube player
        function createPlayer() {
            player = new YT.Player('youtubeVideo', {
                videoId: 'YXVHGj3ZA1c',  // Your video ID
                events: {
                    'onReady': onPlayerReady
                }
            });
        }
        
        // This function will be called when the player is ready
        function onPlayerReady(event) {
            //console.log('Player is ready');
            playerReady = true;  // Set player as ready
        }
        
        // Set current time based on array of timestamps
        function setCurrentTime(slideNum) {
            var timestamps = [25, 155, 232, 378, 442, 528, 633, 672, 715, 747, 819]; // Array of times in seconds
            
            if (playerReady && player && typeof player.seekTo === 'function') {
                //console.log('Seeking to timestamp: ' + timestamps[slideNum]);
                player.seekTo(timestamps[slideNum]);
                player.playVideo();  // Ensure it starts playing
            } else {
                //console.error('Player is not ready yet. Please wait.');
            }
        }
    
        // Start the polling to create the player
        checkYouTubeAPIReady();
        ";
        wp_add_inline_script( 'youtube-api', $inline_script );
    }
    
    
    
    
    public function initialize_config() {
        $post_type = SR_PLAYLIST_CPT;
        $this->config = array(
            'post_type' => $post_type,
            'menu_slug' => 'admin.php',
            'wizard_slug' => 'mp3-player-setup-wizard',
            'capability' => 'manage_options',
            'position' => 100,
            'logo_url' => plugins_url('/img/sonaar_music_logo_opt.svg', __FILE__)
        );

        if (!$this->is_setup_wizard()) {
          return;
        }
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_youtube_api_and_seek_script') );

        $base_url = admin_url($this->config['menu_slug'] . '?page=' . $this->config['wizard_slug']);

        // Define settings array outside the callbacks so it can be used in both content and form_handler
        $general_options = get_option('srmp3_settings_general', array());
        $widget_options = get_option('srmp3_settings_widget_player', array());
        $download_options = get_option('srmp3_settings_download', array());
        $sticky_options = get_option('srmp3_settings_sticky_player', array());
        $share_options = get_option('srmp3_settings_share', array());
        $favorite_options = get_option('srmp3_settings_favorites', array());
        $preview_options = get_option('srmp3_settings_audiopreview', array());
    
        $website_type = isset($general_options['player_type']) ? $general_options['player_type'] : 'classic';
    
        // Define labels and descriptions based on website type
        $labels_and_descriptions = [
            'classic' => [
                'track' => __('audio track', 'sonaar-music'),
                'tracklist' => __('playlist', 'sonaar-music'),
            ],
            'podcast' => [
                'track' => __('episode', 'sonaar-music'),
                'tracklist' => __('podcast or series', 'sonaar-music'),
            ],
            'radio' => [
                'track' => __('stream', 'sonaar-music'),
                'tracklist' => __('streamlist', 'sonaar-music'),
            ],
        ];
    
        $current_labels = $labels_and_descriptions[$website_type];
    
        // Initialize the settings array
        $settings = [];
        $settings['general'] = [];
        $settings['download'] = [];
        $settings['audiopreview'] = [];
        $settings['sticky'] = [];
        $settings['widget'] = [];
        $settings['favorites'] = [];
        $settings['share'] = [];
    
        // Add general and audiopreview settings if the website type is not 'radio'
        if ($website_type !== 'radio') {
            $settings['download'] = [
                'force_cta_download' => [
                    'icon' => 'sricon-filedownload',
                    'title' => __('Enable Download Buttons?', 'sonaar-music'),
                    'description' => __('Allow users to download the ' . $current_labels['track'] . ' file.', 'sonaar-music'),
                    'default' => 'false',
                    'options' => $download_options,
                ],
            ];
            $settings['audiopreview'] = [
                'force_audio_preview' => [
                    'icon' => 'sricon-svg-video',
                    'title' => __('Enable ' . ucfirst($current_labels['track']) . ' Preview', 'sonaar-music'),
                    'description' => __('Generate previews, audio watermarks, fade-in/fade-out, pre-roll and post-roll Ads of your ' . $current_labels['track'] . ' automatically in 1 click!', 'sonaar-music'),
                    'default' => 'false',
                    'options' => $preview_options,
                ],
            ];
        }
    
        // Add sticky settings
        $settings['sticky'] = [
            'enable_continuous_player' => [
                'icon' => 'sricon-audiostream',
                'title' => __('Enable Continuous Playback', 'sonaar-music'),
                'description' => __('When user loads a new page, everything is reloaded but the audio player resumes where it left.', 'sonaar-music'),
                'default' => 'false',
                'options' => $sticky_options,
            ],
            'use_sticky_cpt' => [
                'icon' => 'sricon-deezer',
                'title' => __('Enable Sticky Player in the single post template?', 'sonaar-music'),
                'description' => __('Launch the sticky player when user clicks play from the single ' . $current_labels['track'] . ' post template.', 'sonaar-music'),
                'default' => 'false',
                'options' => $sticky_options,
            ],
        ];
    
        if ($website_type !== 'radio') {
            $settings['widget'] += [
                'player_show_repeat_bt' => [
                    'icon' => 'sricon-repeat-track',
                    'title' => __('Display Repeat Control', 'sonaar-music'),
                    'description' => __('Allow users to repeat the ' . $current_labels['track'] . '/' . $current_labels['tracklist'], 'sonaar-music'),
                    'default' => 'false',
                    'options' => $widget_options,
                ],
                'player_show_speed_bt' => [
                    'icon' => 'sricon-dj',
                    'title' => __('Display Playback Speed Control', 'sonaar-music'),
                    'description' => __('Allow users to change the playback speed from 0.5x, 1x, 1.2x, 1.5x, and 2x', 'sonaar-music'),
                    'default' => 'false',
                    'options' => $widget_options,
                ],
            ];
        }
    
        // Add widget settings
        $settings['widget'] += [
            'player_show_shuffle_bt' => [
                'icon' => 'sricon-shuffle',
                'title' => __('Display Shuffle Control', 'sonaar-music'),
                'description' => __('Allow users to shuffle ' . $current_labels['track'] . ' within the ' . $current_labels['tracklist'] . ' randomly.', 'sonaar-music'),
                'default' => 'false',
                'options' => $widget_options,
            ],
        ];
    
        // Add favorites settings
        $settings['favorites'] = [
            'force_cta_favorite' => [
                'icon' => 'sricon-heart-fill',
                'title' => __('Display Add to Favorites Button', 'sonaar-music'),
                'description' => __('Allow users to add ' . $current_labels['track'] . 's to the favorites list.', 'sonaar-music'),
                'default' => 'false',
                'options' => $favorite_options,
            ],
        ];
    
        // Add share settings
        $settings['share'] = [
            'force_cta_share' => [
                'icon' => 'sricon-share',
                'title' => __('Display Share Link Button', 'sonaar-music'),
                'description' => __('Allow users to distribute the ' . $current_labels['track'] . ' via a link or on social media platforms.', 'sonaar-music'),
                'default' => 'false',
                'options' => $share_options,
            ],
        ];
    
        if ($website_type === 'podcast') {
            $settings['widget'] += [
                'player_show_skip_bt' => [
                    'icon' => 'sricon-30s',
                    'title' => __('Display Skip 15/30 Seconds Control', 'sonaar-music'),
                    'description' => __('Allow users to skip forward or backward.', 'sonaar-music'),
                    'default' => 'false',
                    'options' => $widget_options,
                ],
            ];
        }



        $this->config['steps'] = array(
            'welcome' => array(
                'title' => __('Welcome', 'sonaar-music'),
                'content' => function() use ($base_url) {
                    echo '<div class="sr-wizard-welcome">';
                        echo '<div>';
                            echo '<h2>' . esc_html__('Hello, maestro!', 'sonaar-music') . '</h2>';
                            echo '<p class="sr-wizard-sub-heading">' . esc_html__('It\'s great to have you here with us! We\'re here to tune your setup perfectly. Answer a few questions to get started on your audio journey.', 'sonaar-music') . '</p>';
                            echo '<a href="' . esc_url($base_url . '&step=player_setup') . '" class="button-primary">' . esc_html__('Setup my Player', 'sonaar-music') . '</a>';
                        echo '</div>';
                        echo '<div>';
                            echo '<img src="' . esc_url(plugins_url('/img/logo-sonaar-galaxy-opt.jpg', __FILE__)) . '" alt="' . esc_attr__('Welcome to Sonaar', 'sonaar-music') . '">';
                        echo '</div>';
                    echo '</div>';
                },
                'actions' => array()
            ),
            'player_setup' => array(
                'title' => __('Player Setup', 'sonaar-music'),
                'content' => function() {                       
                    $options = get_option('srmp3_settings_general', array());
                    $player_type = isset($options['player_type']) ? $options['player_type'] : 'classic';
                    $waveformType = isset($options['waveformType']) ? $options['waveformType'] : 'mediaElement';
                    $hasRssFeed = isset($options['wizard_hasRssFeed']) ? $options['wizard_hasRssFeed'] : 'true';
                    $hasEcommerceProfile = isset($options['wizard_has_ecommerce_profile']) ? $options['wizard_has_ecommerce_profile'] : 'false';
    
                    echo '<form method="post" id="player_setup_form" action="">';
                    wp_nonce_field('player_setup_action', 'player_setup_nonce');
                    echo '<div class="sr-wizard-item sr-wizard-website-type">';
                    echo '<h2>' . esc_html__('What is your website type?', 'sonaar-music') . '</h2>';
                    echo '<div class="sr-wizard-label-wrapper">';
                    
                    $types = [
                        'classic' => [
                            'icon' => 'sricon-musiccreation',
                            'label' => __('Music', 'sonaar-music'),
                            'description' => __('For Musician, Artist & Beatstore', 'sonaar-music')
                        ],
                        'podcast' => [
                            'icon' => 'sricon-micro',
                            'label' => __('Podcast', 'sonaar-music'),
                            'description' => __('For Podcaster, Audio Books or Story-teller', 'sonaar-music')
                        ],
                        'radio' => [
                            'icon' => 'sricon-dj',
                            'label' => __('Radio Streams', 'sonaar-music'),
                            'description' => __('For Online Radio, Live Streaming & Icecast', 'sonaar-music')
                        ]
                    ];
    
                    foreach ($types as $value => $data) {
                        echo '<label class="sr-wizard-radio-option">';
                        echo '<input type="radio" name="player_type" value="' . esc_attr($value) . '" ' . checked($value, $player_type, false) . '>';
                        echo '<div class="sr-wizard-radio-content">';
                        echo '<div class="sr-wizard-radio-icon sricons ' . esc_attr($data['icon']) . '"></div>';
                        echo '<span class="sr-wizard-radio-label">' . esc_html($data['label']) . '</span>';
                        echo '<span class="sr-wizard-item-description">' . esc_html($data['description']) . '</span>';
                        echo '</div></label>';
                    }
    
                    echo '</div></div>';




                    echo '<div class="sr-wizard-item has_rss_feed">';
                    echo '<h2>' . esc_html__('Do you have a RSS Podcast Feed you\'d like to import?', 'sonaar-music') . '</h2>';
                    echo '<div class="sr-wizard-label-wrapper">';

                    $rss_feed = [
                        'true' => [
                            'icon' => 'sricon-rss-feed',
                            'label' => __('Yes', 'sonaar-music'),
                            'description' => __('I have a RSS Feed and I\'d love to import my existing Episodes.', 'sonaar-music')
                        ],
                        'false' => [
                            'icon' => 'sricon-close',
                            'label' => __('No', 'sonaar-music'),
                            'description' => __('I\'m not interested', 'sonaar-music')
                        ]
                    ];

                    foreach ($rss_feed as $value => $data) {
                        echo '<label class="sr-wizard-radio-option">';
                        echo '<input type="radio" name="hasRssFeed" value="' . esc_attr($value) . '" ' . checked($value, $hasRssFeed, false) . '>';
                        echo '<div class="sr-wizard-radio-content">';
                        echo '<div class="sr-wizard-radio-icon sricons ' . esc_attr($data['icon']) . '"></div>';
                       /* echo '<span class="sr-wizard-form-item-icon"><img src="' . esc_url(plugins_url($data['icon'], __FILE__)) . '" alt="' . esc_attr($data['label']) . '"></span>';*/
                        echo '<span class="sr-wizard-radio-label">' . esc_html($data['label']) . '</span>';
                        echo '<span class="sr-wizard-item-description">' . esc_html($data['description']) . '</span>';
                        echo '</div></label>';
                    }
                    echo '</div></div>';


    
                    echo '<div class="sr-wizard-item">';
                    echo '<h2>' . esc_html__('What kind of progress bar would you like?', 'sonaar-music') . '</h2>';
                    echo '<div class="sr-wizard-label-wrapper">';
    
                    $waveform_types = [
                        'mediaElement' => [
                            'icon' => '/img/wizard_waveform.svg',
                            'label' => __('Real Waveforms', 'sonaar-music'),
                            'description' => __('Slick & Dynamic visual soundwave', 'sonaar-music')
                        ],
                        'simplebar' => [
                            'icon' => '/img/wizard_simplebar.svg',
                            'label' => __('Simple Bar', 'sonaar-music'),
                            'description' => __('Simple Progress line', 'sonaar-music')
                        ]
                    ];
    
                    foreach ($waveform_types as $value => $data) {
                        echo '<label class="sr-wizard-radio-option">';
                        echo '<input type="radio" name="waveformType" value="' . esc_attr($value) . '" ' . checked($value, $waveformType, false) . '>';
                        echo '<div class="sr-wizard-radio-content">';
                        echo '<span class="sr-wizard-form-item-icon"><img src="' . esc_url(plugins_url($data['icon'], __FILE__)) . '" alt="' . esc_attr($data['label']) . '"></span>';
                        echo '<span class="sr-wizard-radio-label">' . esc_html($data['label']) . '</span>';
                        echo '<span class="sr-wizard-item-description">' . esc_html($data['description']) . '</span>';
                        echo '</div></label>';
                    }
    
                    echo '</div></div>';





                    echo '<div class="sr-wizard-item hasEcommerce_profile">';
                    echo '<h2>' . esc_html__('Are you planning to sell audio files?', 'sonaar-music') . '</h2>';
                    echo '<div class="sr-wizard-label-wrapper">';

                    $has_ecommerce_profile = [
                        'true' => [
                            'icon' => 'sricon-cash',
                            'label' => __('Yes', 'sonaar-music'),
                            'description' => __('I will be using WooCommerce plugin to sell audio or audio licensing. Ka-ching!', 'sonaar-music')
                        ],
                        'false' => [
                            'icon' => 'sricon-nocash',
                            'label' => __('No', 'sonaar-music'),
                            'description' => __('I\'m not interested', 'sonaar-music')
                        ]
                    ];

                    foreach ($has_ecommerce_profile as $value => $data) {
                        echo '<label class="sr-wizard-radio-option">';
                        echo '<input type="radio" name="hasEcommerceProfile" value="' . esc_attr($value) . '" ' . checked($value, $hasEcommerceProfile, false) . '>';
                        echo '<div class="sr-wizard-radio-content">';
                        echo '<div class="sr-wizard-radio-icon sricons ' . esc_attr($data['icon']) . '"></div>';
                        echo '<span class="sr-wizard-radio-label">' . esc_html($data['label']) . '</span>';
                        echo '<span class="sr-wizard-item-description">' . esc_html($data['description']) . '</span>';
                        echo '</div></label>';
                    }
                    echo '</div></div>';

                    echo '</form>';
                },
                'actions' => array(
                    'submit_form' => 'player_setup_form',
                    'form_handler' => function() {
                        if (!isset($_POST['player_setup_nonce']) || !wp_verify_nonce($_POST['player_setup_nonce'], 'player_setup_action')) {
                            return;
                        }

                        $options = get_option('srmp3_settings_general', array());

                        $post_data = array_map('sanitize_text_field', $_POST);
                        unset($post_data['player_setup_nonce'], $post_data['_wp_http_referer']);

                        if (!is_array($options)) {
                            $options = array();
                        }
                        if (isset($post_data['player_type'])) {
                            $options['player_type'] = sanitize_text_field($post_data['player_type']);
                        }
                        if (isset($post_data['waveformType'])) {
                            $options['waveformType'] = sanitize_text_field($post_data['waveformType']);
                        }
                        if (isset($post_data['hasRssFeed'])) {
                            $options['wizard_hasRssFeed'] = sanitize_text_field($post_data['hasRssFeed']);
                        }
                        if (isset($post_data['hasEcommerceProfile'])) {
                            $options['wizard_has_ecommerce_profile'] = sanitize_text_field($post_data['hasEcommerceProfile']);
                        }

                        update_option('srmp3_free_wizard_shown', true);
                        update_option('srmp3_settings_general', $options);
                    }
                )
            ),
        );
    
        $this->config['steps'] += array(
            'pro_setup' => array(
                'title' => __('Pro Features', 'sonaar-music'),
                'content' => function() use ($settings, $current_labels) {
                     // Output Headings
                    if (function_exists('run_sonaar_music_pro')) {
                        echo '<h2>' . esc_html__('Pro Features', 'sonaar-music') . '</h2>';
                        echo '<p class="sr-wizard-sub-heading">' . esc_html__('Explore our top pro features. Customize these options and discover more in the plugin settings.', 'sonaar-music') . '</p>';
                    } else {
                        echo '<h2>' . esc_html__('Meet MP3 Audio Player PRO', 'sonaar-music') . '</h2>';
                        echo '<p class="sr-wizard-sub-heading">' . esc_html__('The Pro addon is not installed yet. Customize these options now, and they’ll be ready if you go Pro later.', 'sonaar-music') . '</p>';
                    }
        
                       // Start the form
                    echo '<form method="post" id="pro_setup_form" action="">';
                    wp_nonce_field('pro_setup_action', 'pro_setup_nonce');
        
                    // Display the settings
                    echo '<div class="sr-wizard-item-wrapper">';
                        foreach ($settings as $section => $options) {
                            foreach ($options as $name => $data) {
                                $value = isset($data['options'][$name]) ? $data['options'][$name] : $data['default'];
                                echo '<div class="sr-wizard-item">';
                                echo '<div class="sr-wizard-item-title-wrapper">';
                                echo '<div class="sr-wizard-radio-icon sricons ' . esc_attr($data['icon']) . '"></div>';
                                echo '<div class="sr-wizard-item-title">' . esc_html($data['title']) . '</div>';
                                echo '<div class="sr-wizard-item-desc">' . esc_html($data['description']) . '</div>';
                                echo '</div>';
                                echo '<label class="sr-wizard-toggle-option">';
                                echo '<input type="hidden" name="' . esc_attr($name) . '" value="false">';
                                echo '<input type="checkbox" name="' . esc_attr($name) . '" value="true" ' . checked('true', $value, false) . '>';
                                echo '<span class="sr-wizard-toggle-slider"></span>';
                                echo '</label></div>';
                            }
                        }
                    echo '</div>';
        
                    echo '</form>';

                    // Display Pro Features Information
                    if (!function_exists('run_sonaar_music_pro')) {
                        echo '<div class="sr-wizard-pro-features-wrapper">';
                            echo '<div class="sr-wizard-pro-features">';
                                echo '<span class="sricons sricon-Sonaar-symbol"></span><h2>' . esc_html__('We Are Not Done Yet.', 'sonaar-music') . '</h2>';
                                echo '<p class="sr-wizard-sub-heading">' . esc_html__('Explore the community\'s most-loved features exclusive to the Pro Addon.', 'sonaar-music') . '</p>';
                                echo '<a class="pro-upgrade-link button button-primary" href="https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin" target="_blank">' . esc_html__('Upgrade to Pro', 'sonaar-music') . '</a>';
                            echo '</div>';
                            echo '<ul class="pro-feature-list">';
                            $pro_features = [
                                __('Sticky Player with Soundwave', 'sonaar-music'),
                                __('Continuous / Persistent Playback', 'sonaar-music'),
                                __('WooCommerce Support Addon', 'sonaar-music'),
                                __('Sell Music Licenses & Contracts', 'sonaar-music'),
                                __('Animated Audio Spectrum', 'sonaar-music'),
                                __('Sliders & 3D Coverflow', 'sonaar-music'),
                                __('Soundwave & Waveform', 'sonaar-music'),
                                __('70+ Elementor Style Options', 'sonaar-music'),
                                __('Shortcode Player Builder', 'sonaar-music'),
                                __('Add Track to Favorites (Like)', 'sonaar-music'),
                                __('Share your ' . ucfirst($current_labels['track']) . 's', 'sonaar-music'),
                                __('Most Recently Played Tracks', 'sonaar-music'),
                                __('Live Lyrics | Karaoke Mode', 'sonaar-music'),
                                __('Chapters & Timestamp', 'sonaar-music'),
                                __('User Recently Played ' . ucfirst($current_labels['track']) . 's','sonaar-music'),
                                __('Column Custom Fields Support', 'sonaar-music'),
                                __('Search Bar Widget', 'sonaar-music'),
                                __('Filters, Search, Chips Widgets', 'sonaar-music'),
                                __('Tracklist & Scrollbar Pagination', 'sonaar-music'),
                                __('Lazyload AJAX Tracklist', 'sonaar-music'),
                                __('Support for ACF, JetEngine, etc.', 'sonaar-music'),
                                __('Audio Preview, Watermarks & Ads', 'sonaar-music'),
                                __('Adaptive Colors in real-time', 'sonaar-music'),
                                __('Dynamic Buttons Visibility', 'sonaar-music'),
                                __('Bulk Import & Import CSV File', 'sonaar-music'),
                                __('Analytics & Reports', 'sonaar-music'),
                            ];
                            
                            foreach ($pro_features as $feature) {
                                echo '<li><span class="feature-icon dashicons dashicons-yes"></span>' . esc_html($feature) . '</li>';
                            }
                            echo '</ul>';
                        echo '</div>';
                        
                    }
        
                  
                },
                'actions' => array(
                    'submit_form' => 'pro_setup_form',
                    'form_handler' => function() use ($settings) {
                        if (!isset($_POST['pro_setup_nonce']) || !wp_verify_nonce($_POST['pro_setup_nonce'], 'pro_setup_action')) {
                            return;
                        }
                
                        $options_sections = [
                            'general' => 'srmp3_settings_general',
                            'download' => 'srmp3_settings_download',
                            'widget' => 'srmp3_settings_widget_player',
                            'sticky' => 'srmp3_settings_sticky_player',
                            'share' => 'srmp3_settings_share',
                            'audiopreview' => 'srmp3_settings_audiopreview',
                            'favorites' => 'srmp3_settings_favorites'
                        ];
                
                        $post_data = array_map('sanitize_text_field', $_POST);
                        unset($post_data['pro_setup_nonce'], $post_data['_wp_http_referer']);
                                
                        foreach ($options_sections as $section => $option_name) {
                            if (isset($settings[$section]) && is_array($settings[$section])) {
                
                                $options = get_option($option_name, array());
                                if (!is_array($options)) {
                                    $options = array();
                                }
                
                                foreach ($settings[$section] as $key => $data) {
                                    if (isset($post_data[$key])) {
                                        $options[$key] = filter_var($post_data[$key], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
                                    } else {
                                        $options[$key] = 'false';
                                    }
                                }
                
                                update_option($option_name, $options);
                            } else {
                                error_log("No settings defined for section: $section");
                            }
                        }
                    }
                )
                
            ),
        );
        
        
        
        
        
    
        $this->config['steps'] += array(  
            'templates' => array(
                'title' => __('Import Player Templates', 'sonaar-music'),
                'content' => function() {
                    echo '<h2>' . esc_html__('Player Templates Addon', 'sonaar-music') . '</h2>';
                    echo '<p class="sr-wizard-sub-heading">' . esc_html__('Select from a variety of premium templates to customize the look and feel of your audio player.', 'sonaar-music') . '</p>';
    
                    echo '<div class="sr-wizard-image-container">';
                    echo '<img src="' . esc_url(plugins_url('/img/wizard_templates_library.jpg', __FILE__)) . '" alt="' . esc_attr__('Templates Library', 'sonaar-music') . '">';
                    echo '<div class="sr-wizard-image-gradient"></div>';
                    echo '</div>';
    
                    if (class_exists('Elementor\Plugin')) {
                        echo '<a href="' . esc_url(admin_url('edit.php?post_type=' . $this->config['post_type'] . '&page=srmp3-import-templates')) . '" target="_blank" class="button-primary">' . esc_html__('Explore Player Templates for Elementor', 'sonaar-music') . '</a>';
                    } else {
                        echo '<a href="' . esc_url(admin_url('edit.php?post_type=' . $this->config['post_type'] . '&page=srmp3-import-shortcode-templates')) . '" target="_blank" class="button-primary">' . esc_html__('Explore Player Templates', 'sonaar-music') . '</a>';
                    }
                },
                'actions' => array()
            ),
            'video_tutorial' => array(
                'title' => __('Video Tutorial', 'sonaar-music'),
                'content' => function() {
                    echo '<h2>' . esc_html__('Get started', 'sonaar-music') . '</h2>';
                    echo '<p class="sr-wizard-sub-heading">' . esc_html__('Learn how to use our player by watching this video tutorial.', 'sonaar-music') . '</p>';
                    echo '<div id="youtubeVideo"></div>';
                    echo '<p>This video will teach you how to use the plugin in 11 quick chapters:</p>
                    <ul>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(0)">Installing MP3 Audio Player Plugin: 00:25</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(1)">Installing MP3 Audio Player PRO Addon: 02:35</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(2)">Adding Audio Track to WordPress 03:53</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(3)">Adding Audio Track in Elementor 06:20</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(4)">Using Podcast RSS Feed 07:23</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(5)">Display Audio Player in a Page using Shortcode 08:48</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(6)">Display Audio Player in a Page using Elementor 10:33</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(7)">Display Audio Player in a Page using Guternberg 11:12</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(8)">Global Settings Overview 11:55</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(9)">Enabling Audio Custom Fields for Custom Post Types 12:27</a></li>
                      <li><a href="javascript:void(0);" onclick="setCurrentTime(10)">Enabling the Sticky Player 13:40</a></li>
                    </ul>';
                },
                'actions' => array()
            ),
            
            'finalize' => array(
                'title' => __('Finalize', 'sonaar-music'),
                'content' => function() {
                    $options = get_option('srmp3_settings_general', array());
                    $website_type = isset($options['player_type']) ? $options['player_type'] : 'classic';
                    $hasRssFeed = isset($options['wizard_hasRssFeed']) ? $options['wizard_hasRssFeed'] : 'false';
                    $hasEcommerceProfile = isset($options['wizard_has_ecommerce_profile']) ? $options['wizard_has_ecommerce_profile'] : 'false';

                    if($website_type == 'podcast' && $hasRssFeed == 'true'){
                        $website_type = 'podcast_import_rss';
                    }

                    if (function_exists('is_woocommerce') && $hasEcommerceProfile == 'true' && $website_type != 'podcast_import_rss') {
                        $website_type = 'create_product';
                    }

                   

    
                    echo '<span class="sr-wizard-form-item-icon sr-wizard-icon-large"><img src="' . esc_url(plugins_url('/img/wizard_confetti.svg', __FILE__)) . '" alt="waveform"></span>';
                    echo '<h2>' . esc_html__('Congratulations!', 'sonaar-music') . '</h2>';
                    echo '<p>' . esc_html__('Your player is now ready for use.', 'sonaar-music') . '</p>';
    
                    $button_links = [
                        'classic' => [
                            'link' => 'edit.php?post_type=' . $this->config['post_type'] . '',
                            'text' => __('Finish and Add your First Track', 'sonaar-music')
                        ],
                        'podcast' => [
                            'link' => 'edit.php?post_type=' . $this->config['post_type']. '',
                            'text' => __('Finish and Add your First Episode', 'sonaar-music')
                        ],
                        'podcast_import_rss' => [
                            'link' => 'admin.php?import=podcast-rss',
                            'text' => __('Finish and Import RSS Feed', 'sonaar-music')
                        ],
                        'radio' => [
                            'link' => 'edit.php?post_type=' . $this->config['post_type'] . '',
                            'text' => __('Finish and Add your Stream Feed', 'sonaar-music')
                        ],
                        'create_product' => [
                            'link' => 'edit.php?post_type=product',
                            'text' => __('Finish and Create your First Product', 'sonaar-music')
                        ],
                    ];
    
                    if (isset($button_links[$website_type])) {
                        // Wrap the button in a form
                        echo '<form method="post" action="">';
                        wp_nonce_field('finalize_action', 'finalize_nonce');
                        echo '<input type="hidden" name="finalize_step" value="1">';
                        echo '<input type="hidden" name="redirect_url" value="' . esc_url(admin_url($button_links[$website_type]['link'])) . '">';
                        echo '<button type="submit" class="button-primary">' . esc_html($button_links[$website_type]['text']) . '</button>';
                        echo '</form>';
                    }

                },
                'actions' => array(
                    'form_handler' => function() {
                        if (isset($_POST['finalize_step'])) {    
                            update_option('srmp3_free_wizard_shown', true); 
                            // Redirect to the specified URL
                            if (isset($_POST['redirect_url'])) {
                                wp_redirect(esc_url_raw($_POST['redirect_url']));
                                exit;
                            }
                        }
                    }
                )
            ),
        );
    }
    
    
    
    public function enqueue_styles_and_scripts() {
        if (!$this->is_setup_wizard()) {
            return;
        }
        wp_enqueue_style('sonaar-wizard-style', plugins_url('/css/sonaar-admin-setup-wizard.css', __FILE__));
        //enqueue script
        wp_enqueue_script('sonaar-wizard-script', plugins_url('/js/sonaar-admin-setup-wizard.js', __FILE__), array('jquery'), null, true);
        
    }
    

    public function setup_wizard_page() {
        $current_step = isset($_GET['step']) ? sanitize_text_field($_GET['step']) : 'welcome';
        $base_url = admin_url($this->config['menu_slug'] . '?page=' . $this->config['wizard_slug']);
        $steps_keys = array_keys($this->config['steps']);
        $current_index = array_search($current_step, $steps_keys);
    
        // Handle the skip setup button click
        if (isset($_GET['action']) && $_GET['action'] === 'skip_setup') {
            // Set the option in the database
            update_option('srmp3_free_wizard_shown', true);
    
            // Redirect to the settings page
            wp_redirect(admin_url('edit.php?post_type=' . $this->config['post_type'] . '&page=srmp3_settings_general'));
            exit;
        }
    
        // Check if the current step has a form to handle
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($this->config['steps'][$current_step]['actions']['form_handler'])) {
            $nonce_action = $steps_keys[$current_index] . '_action';
            $nonce_field = $steps_keys[$current_index] . '_nonce';
    
            if (check_admin_referer($nonce_action, $nonce_field)) {
                call_user_func($this->config['steps'][$current_step]['actions']['form_handler']);
                $next_step = $steps_keys[$current_index + 1];
                wp_redirect(add_query_arg('step', $next_step, $base_url));
                exit;
            }
        }
      


        echo '
        <div class="sr-admin-header">
        <div class="srlogo"><img src="' . esc_url($this->config['logo_url']) . '" alt="Sonaar Music Logo"></div>
        <a href="' . esc_url(add_query_arg('action', 'skip_setup', $base_url)) . '" class="sr-wizard-skip-setup">' . esc_html__('Skip Setup & Go to Settings', 'sonaar-music') . '</a>
        </div>';
        // Begin output
        echo '<div class="sonaar-wizard-wrapper">';
        echo '<div class="wrap sonaar-wizard">';
    
        echo '<div class="sonaar-steps">';
        foreach ($this->config['steps'] as $key => $step) {
            $completed = $current_index > array_search($key, $steps_keys) ? ' completed' : '';
            $active = ($key === $current_step) ? ' active' : '';
            $step_number = array_search($key, $steps_keys) + 1; // Getting the step number
            echo '<a href="' . esc_url(add_query_arg('step', $key, $base_url)) . '" class="sonaar-step' . esc_attr($completed . $active) . '">';
            if ($completed) {
                echo '<span class="step-icon"><i class="dashicons dashicons-yes"></i></span>'; // Completed icon
            } else {
                echo '<span class="step-icon">' . esc_html($step_number) . '</span>'; // Show step number if not completed
            }
            echo '<span class="step-label">' . esc_html($step['title']) . '</span>';
            echo '</a>';
        }
        echo '</div>';
    
        echo '<div class="sonaar-step-content">';
        if (isset($this->config['steps'][$current_step])) {
            $step_content = $this->config['steps'][$current_step]['content'];
            if (is_callable($step_content)) {
                $step_content();
            }
        }
        echo '</div>';
    
        $this->render_footer_navigation($base_url, $current_index, $steps_keys);
    
        echo '</div>';
    
        echo '</div>';
    }
    
    
    
    
    
    private function render_footer_navigation($base_url, $current_index, $steps_keys) {


        echo '<div class="sonaar-setup-footer">';
            // Skip step
            if ($current_index < count($steps_keys) - 1) {
                echo '<span></span>';
            }

            if ($current_index > 0 && $current_index < count($steps_keys) - 1) {
                $next_step = $steps_keys[$current_index + 1];

                echo '<a href="' . esc_url($base_url . '&step=' . $next_step) . '" class="sr-wizard-skip">' . esc_html__('Skip this Step', 'sonaar-music') . '</a>';

            }

            if ($current_index > 0) {
                $previous_step = $steps_keys[$current_index - 1];
                echo '<a href="' . esc_url($base_url . '&step=' . $previous_step) . '" class="button-secondary">' . esc_html__('Previous', 'sonaar-music') . '</a>';
            }

            if ($current_index !== 0 && $current_index < count($steps_keys) - 1) {
                $step_actions = $this->config['steps'][$steps_keys[$current_index]]['actions'];
                if (isset($step_actions['submit_form'])) {
                    echo '<button type="submit" form="' . esc_attr($step_actions['submit_form']) . '" class="button-primary">' . esc_html__('Next', 'sonaar-music') . '</button>';
                } else {
                    $next_step = $steps_keys[$current_index + 1];
                    echo '<a href="' . esc_url($base_url . '&step=' . $next_step) . '" class="button-primary">' . esc_html__('Next', 'sonaar-music') . '</a>';
                }
            }    
    
        echo '</div>';
       
    }
    
    
    
}
new Sonaar_Music_Setup_Wizard();
