<?php
/**
 * Podcast RSS feed template
 *
 * @package Sonaar
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$default_args = array(
	'post_type'           => SR_PLAYLIST_CPT,
	'post_status'         => 'publish',
	'orderby'             => 'date',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true,
);
$termName = (isset($_GET['show'])) ? sanitize_title_with_dashes($_GET['show']) : null;
$termId = (isset($_GET['id'])) ? intval($_GET['id']) : null;

if (isset($termName) && term_exists($termName, 'podcast-show')){
		$default_args['tax_query'] = array(
				array(
					'taxonomy' => 'podcast-show',
					'field'    => 'slug',
					'terms'    => $termName
				)
		);
		
		$terms_data = get_term_by('slug', $termName, 'podcast-show');
		$terms_data = get_term_meta($terms_data->term_id);

}else if (isset($termId) && term_exists($termId, 'podcast-show')) {	
	$default_args['tax_query'] = array(
			array(
				'taxonomy' => 'podcast-show',
				'field'    => 'term_id',
				'terms'    => $termId
			)
	);
	$terms_data = get_term_meta($termId);
}else{
	  // Retrieve the first term_id from the podcast-show taxonomy if it
	  $terms = get_terms(array(
        'taxonomy' => 'podcast-show',
        'number' => 1,  // Limit to one result
        'hide_empty' => false, // Include terms even if they have no posts
    ));

    if (!empty($terms) && !is_wp_error($terms)) {
        $first_term_id = $terms[0]->term_id;
        // Set up the tax query using the first term ID
        $default_args['tax_query'] = array(
            array(
                'taxonomy' => 'podcast-show',
                'field'    => 'term_id',
                'terms'    => $first_term_id
            )
        );

        $terms_data = get_term_meta($first_term_id);
    }else{
		exit;
	}
}
$query_args = apply_filters( 'sonaar_podcast_feed_query_args', $default_args );

$qry = new WP_Query( $query_args );
// If redirect is on, get new feed URL and redirect if setting was changed more than 48 hours ago
$redirect     = ((isset($terms_data['srpodcast_redirect_feed']) && $terms_data['srpodcast_redirect_feed'][0] === "true") && ( isset($terms_data['srpodcast_new_feed_url'][0]) &&  $terms_data['srpodcast_new_feed_url'][0] != "" ) ) ? $terms_data['srpodcast_new_feed_url'][0] : false;

if ( $redirect ) {
	header( 'HTTP/1.1 301 Moved Permanently' );
	header( 'Location: ' . $redirect );
	exit;
}

$title           	= (isset($terms_data['srpodcast_data_title'])) ? $terms_data['srpodcast_data_title'][0] : '';
$subtitle       	= (isset($terms_data['srpodcast_data_subtitle'])) ? $terms_data['srpodcast_data_subtitle'][0] : '';
$author         	= (isset($terms_data['srpodcast_data_author'])) ? $terms_data['srpodcast_data_author'][0] : '';
$description     	= (isset($terms_data['srpodcast_data_description'])) ? $terms_data['srpodcast_data_description'][0] : '';
$language        	= (isset($terms_data['srpodcast_data_language'])) ? $terms_data['srpodcast_data_language'][0] : '';
$copyright       	= (isset($terms_data['srpodcast_data_copyright'])) ? $terms_data['srpodcast_data_copyright'][0] : '';
$owner_name      	= (isset($terms_data['srpodcast_data_owner_name'])) ? $terms_data['srpodcast_data_owner_name'][0] : '';
$owner_email     	= (isset($terms_data['srpodcast_data_owner_email'])) ? $terms_data['srpodcast_data_owner_email'][0] : '';
$explicit_option 	= (isset($terms_data['srpodcast_explicit'])) ? $terms_data['srpodcast_explicit'][0] : '';
$image       	 	= (isset($terms_data['srpodcast_data_image'])) ? $terms_data['srpodcast_data_image'][0] : '';
$itunes_type 	 	= (isset($terms_data['srpodcast_consume_order'])) ? $terms_data['srpodcast_consume_order'][0] : '';
$category_option 	= (isset($terms_data['srpodcast_data_category'])) ? $terms_data['srpodcast_data_category'][0] : '';
$subcategory_option = (isset($terms_data['srpodcast_data_subcategory'])) ? $terms_data['srpodcast_data_subcategory'][0] : '';
$complete_option 	= (isset($terms_data['srpodcast_complete'])) ? $terms_data['srpodcast_complete'][0] : '';

 $latest = new WP_Query(
        array(
            'post_type' => SR_PLAYLIST_CPT,
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'orderby' => 'modified',
            'order' => 'DESC'
        )
);
if($latest->have_posts()){
    $modified_date = $latest->posts[0]->post_modified;
}

$lastbuiltdate = mysql2date( 'D, d M Y H:i:s +0000', $modified_date, false );


if ( $explicit_option && 'true' === $explicit_option ) {
	$itunes_explicit     = 'yes';
	$googleplay_explicit = 'Yes';
} else {
	$itunes_explicit     = 'clean';
	$googleplay_explicit = 'No';
}

if ( $complete_option && 'true' === $complete_option ) {
	$complete = 'yes';
} else {
	$complete = '';
}



// Set RSS content type and charset headers.
header( 'Content-Type: ' . feed_content_type( 'podcast' ) . '; charset=' . get_option( 'blog_charset' ), true );
// Use `echo` for first line to prevent any extra characters at start of document.
echo '<?xml version="1.0" encoding="' . esc_attr( get_option( 'blog_charset' ) ) . '"?>' . "\n";
?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
	xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0"
	<?php do_action( 'rss2_ns' ); ?>
>

	<channel>
		<title><?php echo esc_html( $title ); ?></title>
		<atom:link href="<?php esc_url( self_link() ); ?>" rel="self" type="application/rss+xml" />
		<link><?php echo esc_url( apply_filters( 'sonaar_helper_feed_home_url', trailingslashit( home_url() ) ) ); ?></link>
		<description><?php echo esc_html( $description ); ?></description>
		<lastBuildDate><?php echo esc_html($lastbuiltdate); ?></lastBuildDate>
		<language><?php echo esc_html( $language ); ?></language>
		<copyright><?php echo esc_html( $copyright ); ?></copyright>
		<itunes:subtitle><?php echo esc_html( $subtitle ); ?></itunes:subtitle>
		<itunes:author><?php echo esc_html( $author ); ?></itunes:author>
<?php if ( $itunes_type ) : ?>
		<itunes:type><?php echo esc_html( $itunes_type ); ?></itunes:type>
<?php endif; ?>
		<itunes:owner>
			<itunes:name><?php echo esc_html( $owner_name ); ?></itunes:name>
			<itunes:email><?php echo esc_html( $owner_email ); ?></itunes:email>
		</itunes:owner>
		<googleplay:author><?php echo esc_html( $author ); ?></googleplay:author>
		<googleplay:email><?php echo esc_html( $owner_email ); ?></googleplay:email>
		<itunes:summary><?php echo esc_html( $description ); ?></itunes:summary>
		<googleplay:description><?php echo esc_html( $description ); ?></googleplay:description>
		<itunes:explicit><?php echo esc_html( $itunes_explicit ); ?></itunes:explicit>
		<googleplay:explicit><?php echo esc_html( $googleplay_explicit ); ?></googleplay:explicit>
<?php if ( $complete ) : ?>
		<itunes:complete><?php echo esc_html( $complete ); ?></itunes:complete>
<?php endif; ?>
<?php if ( $image ) : ?>
		<itunes:image href="<?php echo esc_url( $image ); ?>"></itunes:image>
		<googleplay:image href="<?php echo esc_url( $image ); ?>"></googleplay:image>
		<image>
			<url><?php echo esc_url( $image ); ?></url>
			<title><?php echo esc_html( $title ); ?></title>
			<link><?php echo esc_url( apply_filters( 'sonaar_helper_feed_home_url', trailingslashit( home_url() ) ) ); ?></link>
		</image>
<?php endif; ?>

<?php if($subcategory_option == "None"  || $subcategory_option == NULL || $subcategory_option == ""): ?>
		<itunes:category text="<?php echo esc_html( $category_option ); ?>" />
<?php else: ?>

		<itunes:category text="<?php echo esc_html( $category_option ); ?>">	
			<itunes:category text="<?php echo esc_html( $subcategory_option ); ?>"></itunes:category>
		</itunes:category>
<?php endif ?>

<?php
remove_action( 'rss2_head', 'rss2_site_icon' );
remove_action( 'rss2_head', 'the_generator' );

// Add RSS2 headers.
do_action( 'rss2_head' );

if ( $qry->have_posts() ) {
	while ( $qry->have_posts() ) {
		$qry->the_post();
		if ( post_password_required( get_the_ID() ) ) {
			continue;
		}

		// Date recorded.
		$pub_date = esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ) );
		

		// Episode author. For now get author from the podcast settings, not per episode.
		//$author = esc_html( get_the_author() );


		// Episode content (with iframes removed).
		$content = get_the_content_feed( 'rss2' );
		$content = preg_replace( '/<\/?iframe(.|\s)*?>/', '', $content );
		
		$episode_summary = get_post_meta( get_the_ID(), 'episode_summary', true );
		if ( empty( $episode_summary ) ) {
			// iTunes summary is the full episode content, but must be shorter than 4000 characters.
			$itunes_summary = wp_strip_all_tags( strip_shortcodes(  $content ) );
			$itunes_summary = mb_substr( $itunes_summary, 0, 3999 ) ;
		} else {
			//$itunes_summary = wp_strip_all_tags( strip_shortcodes( mb_substr( $episode_summary, 0, 3999 ) ) );
			$itunes_summary = wp_strip_all_tags( strip_shortcodes( $episode_summary ) );
			$itunes_summary = mb_substr( $itunes_summary, 0, 3999 ) ;
		}
		$gp_description = $itunes_summary;



		// Episode description.
		ob_start();
		the_excerpt_rss();
		$description = ob_get_clean();

		// iTunes subtitle does not allow any HTML and must be shorter than 255 characters.
		$episode_subtitle =  get_post_meta( get_the_ID(), 'alb_release_date', true);
		$episode_subtitle = strip_tags( strip_shortcodes( $episode_subtitle ) );
		$episode_subtitle = str_replace( array( '>', '<', '\'', '"', '`', '[andhellip;]', '[&hellip;]', '[&#8230;]' ), array( '', '', '', '', '', '', '', '' ), $episode_subtitle );
		$episode_subtitle = mb_substr( $episode_subtitle, 0, 254 );

		
		$episode_image = '';
		$image_id      = get_post_thumbnail_id( get_the_ID() );
		if ( $image_id ) {
			$image_att = wp_get_attachment_image_src( $image_id, 'full' );
			if ( $image_att ) {
				$episode_image = $image_att[0];
			}
		}
		$audioSrc     = "";
		//$fileOrStream =  get_field('FileOrStreamPodCast', get_the_ID());
		//var_dump(get_the_ID());
		$album_tracks =  get_post_meta( get_the_ID(), 'alb_tracklist', true);
		$fileOrStream =  $album_tracks[0]['FileOrStream'];

		switch ($fileOrStream) {
			case 'mp3':
				if ( isset( $album_tracks[0]["track_mp3"] ) ) {
					$mp3_id = $album_tracks[0]["track_mp3_id"];
					$mp3_metadata = wp_get_attachment_metadata( $mp3_id );
					$track_title = ( isset( $mp3_metadata["title"] ) && $mp3_metadata["title"] !== '' )? $mp3_metadata["title"] : false ;
					$track_title = ( get_the_title($mp3_id) !== '' && $track_title !== get_the_title($mp3_id))? get_the_title($mp3_id): $track_title;
					$track_title = html_entity_decode($track_title, ENT_COMPAT, 'UTF-8');
					$album_filesize = ( isset( $mp3_metadata['filesize'] ) && $mp3_metadata['filesize'] !== '' )? $mp3_metadata['filesize'] : false;
					
					$album_tracks_lenght = ( isset( $mp3_metadata['length_formatted'] ) && $mp3_metadata['length_formatted'] !== '' )? $mp3_metadata['length_formatted'] : false;
					$audioSrc = wp_get_attachment_url($mp3_id);
					//$audioSrc = wp_get_attachment_url($mp3_id['ID']);
							
				}
			break;

			case 'stream':
				$audioSrc = ( array_key_exists ( "stream_link" , $album_tracks[0] ) && $album_tracks[0]["stream_link"] !== '' )? $album_tracks[0]["stream_link"] : false;
				$track_title = (  array_key_exists ( 'stream_title' , $album_tracks[0] ) && $album_tracks[0]["stream_title"] !== '' )? $album_tracks[0]["stream_title"] : false;
				$album_title = ( isset ($album_tracks[0]["stream_album"]) && $album_tracks[0]["stream_album"] !== '' )? $album_tracks[0]["stream_album"] : false;
				$album_tracks_lenght = ( isset( $album_tracks[0]["stream_lenght"] ) && $album_tracks[0]["stream_lenght"] !== '' ) ? $album_tracks[0]["stream_lenght"] : false;
				break;
			
			default:
				$album_tracks[0] = array();
				break;
		}

		// Episode explicit flag.
		$ep_explicit = (isset($album_tracks[0]['podcast_explicit_episode'])) ? $album_tracks[0]['podcast_explicit_episode'] : false;
		if ( $ep_explicit == 'true' ) {
			$itunes_explicit_flag     = 'yes';
			$googleplay_explicit_flag = 'Yes';
		} else {
			$itunes_explicit_flag     = 'clean';
			$googleplay_explicit_flag = 'No';
		}

		// Episode block flag.
		
		$ep_block = (isset($album_tracks[0]['podcast_itunes_notshow'])) ? $album_tracks[0]['podcast_itunes_notshow'] : false;
		
		if ( $ep_block == 'true' ) {
			$block_flag = 'yes';
		} else {
			$block_flag = 'no';
		}


		$audio_file     = $audioSrc;
		// If there is no enclosure then go no further.
		if ( ! isset( $audio_file ) || ! $audio_file ) {
			continue;
		}

		$duration = $album_tracks_lenght;
		if ( ! $duration ) {
			$duration = '0:00';
		}
		$size = (isset($album_filesize)) ? $album_filesize : false;
		if ( ! $size ) {
			$size = 1;
		}

		// Tags/keywords
		$post_tags = get_the_tags( get_the_ID() );
		if ( $post_tags ) {
			$tags = array();
			foreach ( $post_tags as $tag ) {
				$tags[] = $tag->name;
			}
			if ( ! empty( $tags ) ) {
				$keywords = implode( $tags, ',' );
			}
		}else{
			$keywords='';
		}

		// New iTunes WWDC 2017 Tags.
	
		$itunes_episode_type   	= "";
		$itunes_title          	= (isset($album_tracks[0]['podcast_itunes_episode_title'])) ? $album_tracks[0]['podcast_itunes_episode_title'] : the_title_rss();
		$itunes_episode_number 	= (isset($album_tracks[0]['podcast_itunes_episode_number'])) ? $album_tracks[0]['podcast_itunes_episode_number'] : '';
		$itunes_season_number  	= (isset($album_tracks[0]['podcast_itunes_season_number'])) ? $album_tracks[0]['podcast_itunes_season_number'] : '';
		
		
		if ($album_tracks[0]['podcast_itunes_episode_type'] != null){
			$itunes_episode_type   = $album_tracks[0]['podcast_itunes_episode_type'] ;
		}

		?>

		<item>
			<title><?php esc_html( the_title_rss() ); ?></title>
			<link><?php esc_url( the_permalink_rss() ); ?></link>
			<pubDate><?php echo esc_html( $pub_date ); ?></pubDate>
			<dc:creator><?php echo esc_html( $author ); ?></dc:creator>
			<guid isPermaLink="false"><?php esc_html( the_guid() ); ?></guid>
			<description><![CDATA[<?php echo $description; ?>]]></description>
		<?php if ( $episode_subtitle  ) : ?>
			<itunes:subtitle><?php echo esc_html( $episode_subtitle ); ?></itunes:subtitle>
		<?php endif; ?>
		<?php if ( $keywords ) : ?>
			<itunes:keywords><?php echo $keywords; ?></itunes:keywords>
		<?php endif; ?>
		<?php if ( $itunes_episode_type ) : ?>
			<itunes:episodeType><?php echo esc_html( $itunes_episode_type ); ?></itunes:episodeType>
		<?php endif; ?>
		<?php if ( $itunes_title ) : ?>
			<itunes:title><![CDATA[<?php echo esc_html( $itunes_title ); ?>]]></itunes:title>
		<?php endif; ?>
		<?php if ( $itunes_episode_number ) : ?>
			<itunes:episode><?php echo esc_html( $itunes_episode_number ); ?></itunes:episode>
		<?php endif; ?>
		<?php if ( $itunes_season_number ) : ?>
			<itunes:season><?php echo esc_html( $itunes_season_number ); ?></itunes:season>
		<?php endif; ?>
			<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
			<itunes:summary><![CDATA[<?php echo $itunes_summary; ?>]]></itunes:summary>
			<googleplay:description><![CDATA[<?php echo $gp_description; ?>]]></googleplay:description>
		<?php if ( $episode_image ) : ?>
			<itunes:image href="<?php echo esc_url( $episode_image ); ?>"></itunes:image>
			<googleplay:image href="<?php echo esc_url( $episode_image ); ?>"></googleplay:image>
		<?php endif; ?>
			<enclosure url="<?php echo esc_url( $audio_file ); ?>" length="<?php echo esc_attr( $size ); ?>" type="audio/mpeg"></enclosure>
			<itunes:explicit><?php echo esc_html( $itunes_explicit_flag ); ?></itunes:explicit>
			<googleplay:explicit><?php echo esc_html( $googleplay_explicit_flag ); ?></googleplay:explicit>
			<itunes:block><?php echo esc_html( $block_flag ); ?></itunes:block>
			<googleplay:block><?php echo esc_html( $block_flag ); ?></googleplay:block>
			<itunes:duration><?php echo esc_html( $duration ); ?></itunes:duration>
			<itunes:author><?php echo esc_html( $author ); ?></itunes:author>
		</item>
		<?php
	} // end while
} // end if
?>

	</channel>
</rss>
