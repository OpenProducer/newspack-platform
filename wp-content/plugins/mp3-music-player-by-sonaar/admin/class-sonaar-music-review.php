<?php
/*
** To test the notice and display it in the dashboard: delete the option sonaar_music_playlists_add AND sonaar_music_hide_review_box in table wp_options
*/

class Sonaar_Music_Review {

    public $plugin_slug = "sonaar-music";

	public function __construct() {
		
        add_action("wp_ajax_sonaar_music_review_box", array($this, "sonaar_music_review_box"));
		
		if ( !function_exists( 'run_sonaar_music_pro' ) ){
			add_action('admin_notices', array($this, 'sonaar_music_review_notices'));
		}
    }

	public function sonaar_music_review_box() {
		check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');

		if (!current_user_can('manage_options')) {
			exit;
		}
        $days = filter_input(INPUT_POST, 'days', FILTER_SANITIZE_STRING);

		if($days == -1) {
			update_option( 'sonaar_music_hide_review_box', '1' );
		} else {
			$count_review_box = get_option('sonaar_music_show_review_box_count');
			if ( $count_review_box == '' ) {
				$count_review_box = 0;
			}
			$count_review_box = $count_review_box+1;			
			
			update_option( 'sonaar_music_show_review_box_count', ( $count_review_box ));
			/* User hide this box third time then need to hide box permantly. */
			if ( $count_review_box == 3 ) {
				update_option( 'sonaar_music_hide_review_box', '1' );
			}
			/* User want to hind second time then hide box for 90 days */
			if ( $count_review_box == 2) {
				$days = 90;
			}
			$date = date("Y-m-d", strtotime("+".$days." days"));
			update_option( 'sonaar_music_show_review_box_after', $date);
		}

        wp_die();
	}

	public function sonaar_music_review_notices() {
		
		$is_hidden = get_option( 'sonaar_music_hide_review_box' );

        if( $is_hidden !== false) {
           return;
        }


        $current_count = get_option( 'sonaar_music_show_review_box_after');
        if($current_count === false ) {
            $date = date("Y-m-d", strtotime("+3 days"));
            update_option( 'sonaar_music_show_review_box_after', $date );
            return;
        }

		$current_user = wp_get_current_user();
		$first_name = ucfirst(strtolower(!empty($current_user->user_firstname) ? $current_user->user_firstname : $current_user->display_name));

		$count = wp_count_posts( SR_PLAYLIST_CPT );
		$published = $count->publish;

        $date_to_show = get_option( 'sonaar_music_show_review_box_after' );
        if( $date_to_show !== false ) {
            $current_date = date("Y-m-d");
            if($current_date < $date_to_show) {
				$is_hidden = get_option( 'sonaar_music_playlists_add' );
				if ( $published != 0 && $is_hidden === false ) {
					update_option( 'sonaar_music_show_review_box_after', date("Y-m-d") );
					update_option( 'sonaar_music_playlists_add', true );
				} else {
					return;
				}
            }
        }

        ?>
        <div class="notice notice-info sonaar-music-review-box sonaar-music-review-box">
            <div class="sr-notice-logo"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ) . '/img/sonaar-icon-white.png')?>"></div>
            <div class="sr-notice-content">
	            <h3>MP3 Player by Sonaar</h3>
	            <p>
					<?php echo sprintf( esc_html__( "Hey %s, I've noticed you've created %d playlists with the MP3 Player - That's awesome!", 'sonaar-music'), $first_name, $published  );?> 
					<br>
					<?php echo sprintf( esc_html__( "Could you please do me a BIG favor in return and give it a 5-star rating ★★★★★ on WordPress ? That would make my day!", 'sonaar-music' ) , $published  );?>
	                <a href="javascript:;" class="dismiss-btn sonaar-music-review-dismiss-btn"><span class="dashicons dashicons-no-alt"></span></a>
	            </p>
	            <div class="sr-notice-cta">
		            <a class="button button-primary sonaar-music-review-box-hide-btn" href="https://wordpress.org/support/plugin/mp3-music-player-by-sonaar/reviews/?filter=5#new-post" data-days="-1" target="_blank"><?php esc_html_e( '★ Ok, you deserve it', 'sonaar-music' );?></a>
					<a class="button button-primary sonaar-music-review-box-hide-btn" data-days="-1" href="javascript:;"><?php esc_html_e( 'I already did', 	'sonaar-music');?></a>
					<a class="button button-primary sonaar-music-review-box-hide-btn" href="javascript:;"><?php esc_html_e( 'Nope, not good enough', 	'sonaar-music');?></a>
        		</div>
        	</div>

        </div>        
		<script>
		(function( $ ) {
			'use strict';

			$(document).ready(function(){
				$("body").addClass("has-box");		
				$(document).on("click", ".sonaar-music-review-dismiss-btn, .sonaar-music-review-box-hide-btn", function(){
					var dataDays = $(this).attr("data-days");
					if ( typeof dataDays === 'undefined') {
						dataDays = 30;
					}					
					$(".sonaar-music-review-box").remove();
					$("body").removeClass("has-box");
					$.ajax({
						url: sonaar_admin_ajax.ajax.ajax_url,
						data: {
							action: "sonaar_music_review_box",
							days: dataDays,
							nonce: sonaar_admin_ajax.ajax.ajax_nonce
						},
						type: "post",
						success: function() {							
							$(".sonaar-music-review-box").remove();
						}
					});
				});
			});
		})( jQuery );
		</script>

        <?php
	}

}

$sonaar_music_review = new Sonaar_Music_Review();