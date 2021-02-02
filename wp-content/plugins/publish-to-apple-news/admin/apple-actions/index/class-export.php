<?php
/**
 * Publish to Apple News: \Apple_Actions\Index\Export class
 *
 * @package Apple_News
 * @subpackage Apple_Actions\Index
 */

namespace Apple_Actions\Index;

require_once plugin_dir_path( __FILE__ ) . '../class-action.php';
require_once plugin_dir_path( __FILE__ ) . '../class-action-exception.php';
require_once plugin_dir_path( __FILE__ ) . '../../../includes/apple-exporter/autoload.php';

use Apple_Actions\Action as Action;
use Apple_Exporter\Exporter as Exporter;
use Apple_Exporter\Exporter_Content as Exporter_Content;
use Apple_Exporter\Exporter_Content_Settings as Exporter_Content_Settings;
use Apple_Exporter\Third_Party\Jetpack_Tiled_Gallery as Jetpack_Tiled_Gallery;
use \Admin_Apple_Sections;

/**
 * A class to handle an export request from the admin.
 *
 * @package Apple_News
 * @subpackage Apple_Actions\Index
 */
class Export extends Action {

	/**
	 * A variable to keep track of whether we are in the middle of an export.
	 *
	 * @var bool
	 * @access private
	 */
	private static $exporting = false;

	/**
	 * ID of the post being exported.
	 *
	 * @var int
	 * @access private
	 */
	private $id;

	/**
	 * Constructor.
	 *
	 * @param \Apple_Exporter\Settings $settings Settings in use during the current run.
	 * @param int|null                 $id       Optional. The ID of the content to export.
	 * @param array|null               $sections Optional. Sections to map for this post.
	 * @access public
	 */
	public function __construct( $settings, $id = null, $sections = null ) {
		parent::__construct( $settings );
		$this->set_theme( $sections );
		$this->id = $id;
		Jetpack_Tiled_Gallery::instance();
	}

	/**
	 * A function to determine whether an export is currently in progress.
	 *
	 * @access public
	 * @return bool
	 */
	public static function is_exporting() {
		return self::$exporting;
	}

	/**
	 * Perform the export and return the results.
	 *
	 * @return string The JSON data
	 * @access public
	 */
	public function perform() {
		self::$exporting = true;
		$exporter        = $this->fetch_exporter();
		$json            = $exporter->export();
		self::$exporting = false;

		return $json;
	}

	/**
	 * Fetches an instance of Exporter.
	 *
	 * @return Exporter
	 * @access public
	 */
	public function fetch_exporter() {

		global $post;

		do_action( 'apple_news_do_fetch_exporter', $this->id );

		/**
		 * Fetch WP_Post object, and all required post information to fill up the
		 * Exporter_Content instance.
		 */
		$post = get_post( $this->id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		// Only include excerpt if exists.
		$excerpt = has_excerpt( $post ) ? wp_strip_all_tags( $post->post_excerpt ) : '';

		// Get the cover configuration.
		$post_thumb    = null;
		$cover_meta_id = get_post_meta( $this->id, 'apple_news_coverimage', true );
		$cover_caption = get_post_meta( $this->id, 'apple_news_coverimage_caption', true );
		if ( ! empty( $cover_meta_id ) ) {
			if ( empty( $cover_caption ) ) {
				$cover_caption = wp_get_attachment_caption( $cover_meta_id );
			}
			$post_thumb = [
				'caption' => ! empty( $cover_caption ) ? $cover_caption : '',
				'url'     => wp_get_attachment_url( $cover_meta_id ),
			];
		} else {
			$thumb_id       = get_post_thumbnail_id( $this->id );
			$post_thumb_url = wp_get_attachment_url( $thumb_id );
			if ( empty( $cover_caption ) ) {
				$cover_caption = wp_get_attachment_caption( $thumb_id );
			}
			if ( ! empty( $post_thumb_url ) ) {
				$post_thumb = [
					'caption' => ! empty( $cover_caption ) ? $cover_caption : '',
					'url'     => $post_thumb_url,
				];
			}
		}

		// If there is a cover caption but not a cover image URL, preserve it, so it can take precedence later.
		if ( empty( $post_thumb ) && ! empty( $cover_caption ) ) {
			$post_thumb = [
				'caption' => $cover_caption,
				'url'     => '',
			];
		}

		// Build the byline.
		$byline = $this->format_byline( $post );

		// Get the content.
		$content = $this->get_content( $post );

		/*
		 * If the excerpt looks too similar to the content, remove it.
		 * We do this before the filter, to allow overrides for the final value.
		 * This essentially prevents the case where someone intentionally copies
		 * the first paragraph of content into the `post_excerpt` field and
		 * unintentionally introduces a duplicate content issue.
		 */
		if ( ! empty( $excerpt ) ) {
			$content_normalized = strtolower( str_replace( ' ', '', wp_strip_all_tags( $content ) ) );
			$excerpt_normalized = strtolower( str_replace( ' ', '', wp_strip_all_tags( $excerpt ) ) );
			if ( false !== strpos( $content_normalized, $excerpt_normalized ) ) {
				$excerpt = '';
			}
		}

		// Filter each of our items before passing into the exporter class.
		$title     = apply_filters( 'apple_news_exporter_title', $post->post_title, $post->ID );
		$excerpt   = apply_filters( 'apple_news_exporter_excerpt', $excerpt, $post->ID );
		$cover_url = apply_filters( 'apple_news_exporter_post_thumb', ! empty( $post_thumb['url'] ) ? $post_thumb['url'] : null, $post->ID );
		$byline    = apply_filters( 'apple_news_exporter_byline', $byline, $post->ID );
		$content   = apply_filters( 'apple_news_exporter_content', $content, $post->ID );

		// Re-apply the cover URL after filtering.
		if ( ! empty( $cover_url ) ) {
			$cover_caption = ! empty( $post_thumb['caption'] ) ? $post_thumb['caption'] : '';

			/**
			 * Filters the cover caption.
			 *
			 * @param string $caption The caption to use for the cover image.
			 * @param int    $post_id The post ID.
			 *
			 * @since 2.1.0
			 */
			$cover_caption = apply_filters( 'apple_news_exporter_cover_caption', $cover_caption, $post->ID );

			$post_thumb = [
				'caption' => $cover_caption,
				'url'     => $cover_url,
			];
		} else {
			$post_thumb = null;
		}

		// Now pass all the variables into the Exporter_Content array.
		$base_content = new Exporter_Content(
			$post->ID,
			$title,
			$content,
			$excerpt,
			$post_thumb,
			$byline,
			$this->fetch_content_settings()
		);

		return new Exporter( $base_content, null, $this->settings );
	}

	/**
	 * Formats the byline
	 *
	 * @since 1.2.0
	 *
	 * @param \WP_Post $post   The post to use.
	 * @param string   $author Optional. Overrides author information. Defaults to author of the post.
	 * @param string   $date   Optional. Overrides the date. Defaults to the date of the post.
	 * @access public
	 * @return string
	 */
	public function format_byline( $post, $author = '', $date = '' ) {

		// Get information about the currently used theme.
		$theme = \Apple_Exporter\Theme::get_used();

		// Get the author.
		if ( empty( $author ) ) {

			// Try to get the author information from Co-Authors Plus.
			if ( function_exists( 'coauthors' ) ) {
				$author = coauthors( null, null, null, null, false );
			} else {
				$author = ucfirst( get_the_author_meta( 'display_name', $post->post_author ) );
			}
		}

		// Get the date.
		if ( empty( $date ) && ! empty( $post->post_date ) ) {
			$date = $post->post_date;
		}

		// Set the default date format.
		$date_format = 'M j, Y | g:i A';

		// Check for a custom byline format.
		$byline_format = $theme->get_value( 'byline_format' );
		if ( ! empty( $byline_format ) ) {
			/**
			 * Find and replace the author format placeholder name with a temporary placeholder.
			 * This is because some bylines could contain hashtags!
			 */
			$temp_byline_placeholder = 'AUTHOR' . time();
			$byline                  = str_replace( '#author#', $temp_byline_placeholder, $byline_format );

			// Attempt to parse the date format from the remaining string.
			$matches = array();
			preg_match( '/#(.*?)#/', $byline, $matches );
			if ( ! empty( $matches[1] ) && ! empty( $date ) ) {
				// Set the date using the custom format.
				$byline = str_replace( $matches[0], apple_news_date( $matches[1], strtotime( $date ) ), $byline );
			}

			// Replace the temporary placeholder with the actual byline.
			$byline = str_replace( $temp_byline_placeholder, $author, $byline );

		} else {
			// Use the default format.
			$byline = sprintf(
				'by %1$s | %2$s',
				$author,
				apple_news_date( $date_format, strtotime( $date ) )
			);
		}

		return $byline;
	}

	/**
	 * Converts Brightcove Gutenberg blocks and shortcodes to video tags that
	 * can be handled by Apple News. Requires that Apple connect the Brightcove
	 * account to the Apple News channel.
	 *
	 * @since 2.1.0
	 *
	 * @param string $content The post content to filter.
	 *
	 * @return string The modified content.
	 */
	private function format_brightcove( $content ) {

		// Replace Brightcove Gutenberg blocks with Gutenberg video blocks with a specially-formatted Brightcove source URL.
		if ( preg_match_all( '/<!-- wp:bc\/brightcove ({.+?}) \/-->/', $content, $matches ) ) {
			foreach ( $matches[0] as $index => $match ) {
				$atts = json_decode( $matches[1][ $index ], true );
				if ( ! empty( $atts['account_id'] ) && ! empty( $atts['video_id'] ) ) {
					$content = str_replace(
						$match,
						sprintf(
							'<!-- wp:video -->' . "\n" . '<figure class="wp-block-video"><video controls src="https://edge.api.brightcove.com/playback/v1/accounts/%s/videos/%s" poster="%s"></video></figure>' . "\n" . '<!-- /wp:video -->',
							$atts['account_id'],
							$atts['video_id'],
							$this->get_brightcove_stillurl( $atts['account_id'], $atts['video_id'] )
						),
						$content
					);
				}
			}
		}

		// Replace Brightcove shortcodes with plain video tags with a specially-formatted Brightcove source URL.
		$bc_video_regex = '/' . get_shortcode_regex( [ 'bc_video' ] ) . '/';
		if ( preg_match_all( $bc_video_regex, $content, $matches ) ) {
			foreach ( $matches[0] as $match ) {
				$atts = shortcode_parse_atts( $match );
				if ( ! empty( $atts['account_id'] ) && ! empty( $atts['video_id'] ) ) {
					$content = str_replace(
						$match,
						sprintf(
							'<video controls src="https://edge.api.brightcove.com/playback/v1/accounts/%s/videos/%s" poster="%s"></video>',
							$atts['account_id'],
							$atts['video_id'],
							$this->get_brightcove_stillurl( $atts['account_id'], $atts['video_id'] )
						),
						$content
					);
				}
			}
		}

		return $content;
	}

	/**
	 * Given an account ID and video ID, gets the Brightcove still image URL.
	 *
	 * @param string $account_id The Brightcove account ID to use.
	 * @param string $video_id   The Brightcove video ID to use.
	 *
	 * @return string The URL to the still image. Empty string on failure.
	 */
	private function get_brightcove_stillurl( $account_id, $video_id ) {
		global $bc_accounts;

		// If the $bc_accounts global doesn't exist, bail.
		if ( empty( $bc_accounts ) ) {
			return '';
		}

		/*
		 * BC_Setup only runs if is_admin returns true, which won't be if the
		 * publish was triggered from a REST request (which it will be if the
		 * user is using Gutenberg to publish manually, or on publish of the
		 * post with auto-publish turned on). Therefore, we need to bootstrap
		 * the functionality ourselves by mimicing the behavior of the init
		 * hook.
		 */
		if ( ! class_exists( '\BC_CMS_API' ) && class_exists( '\BC_Setup' ) ) {
			\BC_Setup::action_init();
		}

		// Ensure the account ID and video IDs are strings.
		$account_id = (string) $account_id;
		$video_id   = (string) $video_id;

		// Get the current account ID and switch accounts if necessary.
		$old_account_id = (string) $bc_accounts->get_account_id();
		if ( $old_account_id !== $account_id ) {
			$bc_accounts->set_current_account_by_id( $account_id );
		}

		// Initialize a new BC_CMS_API instance and fetch the video images.
		$bc_cms_api = new \BC_CMS_API();
		$response   = $bc_cms_api->video_get_images( $video_id );
		$image      = ! empty( $response['poster']['src'] ) ? $response['poster']['src'] : '';

		// Switch accounts back, if necessary.
		if ( $old_account_id !== $account_id ) {
			$bc_accounts->set_current_account_by_id( $old_account_id );
		}

		return $image;
	}

	/**
	 * Gets the content
	 *
	 * @since 1.4.0
	 *
	 * @param \WP_Post $post The post object to extract content from.
	 * @access private
	 * @return string
	 */
	private function get_content( $post ) {
		/**
		 * The post_content is not raw HTML, as WordPress editor cleans up
		 * paragraphs and new lines, so we need to transform the content to
		 * HTML. We use 'the_content' filter for that.
		 */
		$content = apply_filters( 'apple_news_exporter_content_pre', $post->post_content, $post->ID );
		$content = $this->format_brightcove( $content );
		$content = apply_filters( 'the_content', $content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$content = $this->remove_tags( $content );
		$content = $this->remove_entities( $content );
		return $content;
	}

	/**
	 * Remove tags incompatible with Apple News format.
	 *
	 * @since 1.4.0
	 *
	 * @param string $html The HTML to be filtered.
	 * @access private
	 * @return string
	 */
	private function remove_tags( $html ) {
		$html = preg_replace( '/<style[^>]*>.*?<\/style>/i', '', $html );
		return $html;
	}

	/**
	 * Filter the content for markdown format.
	 *
	 * @param string $content The content to be filtered.
	 * @access private
	 * @return string
	 */
	private function remove_entities( $content ) {
		if ( 'yes' === $this->get_setting( 'html_support' ) ) {
			return $content;
		}

		// Correct ampersand output.
		return str_replace( '&amp;', '&', $content );
	}

	/**
	 * Loads settings for the Exporter_Content from the WordPress post metadata.
	 *
	 * @since 0.4.0
	 * @return Settings
	 * @access private
	 */
	private function fetch_content_settings() {
		$settings = new Exporter_Content_Settings();
		foreach ( get_post_meta( $this->id ) as $name => $value ) {
			if ( 0 === strpos( $name, 'apple_news_' ) ) {
				$name  = str_replace( 'apple_news_', '', $name );
				$value = $value[0];
				$settings->set( $name, $value );
			}
		}
		return apply_filters( 'apple_news_content_settings', $settings );
	}

	/**
	 * Sets the active theme for this session if explicitly set or mapped.
	 *
	 * @since 1.2.3
	 *
	 * @param array $sections Explicit sections mapped for this post.
	 *
	 * @access private
	 */
	private function set_theme( $sections ) {

		// If there are no sections, bail.
		if ( empty( $sections ) || ! is_array( $sections ) ) {
			return;
		}

		/*
		 * Get a list of priorities from the options table. If no priorities have
		 * been set, then this will return an empty array, and the priority of
		 * each section will default to 1.
		 */
		$priorities = get_option(
			Admin_Apple_Sections::PRIORITY_MAPPING_KEY,
			[]
		);

		/*
		 * Priorities are stored as section_id => priority, so we can just run
		 * arsort here to preserve keys and sort by values in reverse order, so
		 * sections with highest priority are sorted to the top.
		 */
		arsort( $priorities );

		// Default to the first section in the list.
		$assigned_section = basename( $sections[0] );

		// Loop over the priority map and find the priority of the default section.
		$section_priority = 1;
		foreach ( $priorities as $section_id => $priority ) {
			if ( $assigned_section === $section_id ) {
				$section_priority = $priority;
				break;
			}
		}

		/*
		 * Sections are stored as URLs, but we really need the section ID, which is
		 * the last segment of the URL. Loop over the section list and extract the
		 * last segment using basename() into a new array that we can use for easy
		 * comparison.
		 */
		$section_keys = array_map( 'basename', $sections );

		/*
		 * Loop over the priority list and try to find a section that is assigned
		 * to the post that has a higher priority than the default section. If
		 * found, swap the active section.
		 */
		foreach ( $priorities as $section_id => $priority ) {
			if ( in_array( $section_id, $section_keys, true )
				&& $priority >= $section_priority
			) {
				$assigned_section = $section_id;
				break;
			}
		}

		// Check if there is a custom theme mapping.
		$theme_name = Admin_Apple_Sections::get_theme_for_section( $assigned_section );
		if ( empty( $theme_name ) ) {
			return;
		}

		// Try to get theme settings.
		$theme = new \Apple_Exporter\Theme();
		$theme->set_name( $theme_name );
		if ( ! $theme->load() ) {

			// Fall back to the active theme.
			$theme->set_name( \Apple_Exporter\Theme::get_active_theme_name() );
			$theme->load();
		}

		// Set theme as active for this session.
		$theme->use_this();
	}
}
