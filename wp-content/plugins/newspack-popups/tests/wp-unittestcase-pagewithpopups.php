<?php
/**
 * Class for test
 *
 * @package Newspack_Popups
 */

/**
 * WP_UnitTestCase which renders a page with popups.
 */
class WP_UnitTestCase_PageWithPopups extends WP_UnitTestCase {
	protected static $popup_content       = 'The popup content.'; // phpcs:ignore Squiz.Commenting.VariableComment.Missing
	protected static $popup_id            = false; // phpcs:ignore Squiz.Commenting.VariableComment.Missing
	protected static $raw_post_content    = 'The post content.'; // phpcs:ignore Squiz.Commenting.VariableComment.Missing
	protected static $post_content        = false; // phpcs:ignore Squiz.Commenting.VariableComment.Missing
	protected static $post_head           = false; // phpcs:ignore Squiz.Commenting.VariableComment.Missing
	protected static $dom_xpath           = false; // phpcs:ignore Squiz.Commenting.VariableComment.Missing
	protected static $post_head_dom_xpath = false; // phpcs:ignore Squiz.Commenting.VariableComment.Missing
	protected static $segments            = []; // phpcs:ignore Squiz.Commenting.VariableComment.Missing

	public function setUp() { // phpcs:ignore Squiz.Commenting.FunctionComment.Missing
		// Reset segments.
		update_option( Newspack_Popups_Segmentation::SEGMENTS_OPTION_NAME, [] );
		self::$segments = Newspack_Popups_Segmentation::create_segment(
			[
				'name'          => 'segment1',
				'configuration' => [],
			]
		);

		// Remove any popups (from previous tests).
		foreach ( Newspack_Popups_Model::retrieve_popups() as $popup ) {
			wp_delete_post( $popup['id'] );
		}

		self::$popup_id = self::createPopup();

		Newspack_Popups_Model::set_popup_options(
			self::$popup_id,
			[
				'frequency'    => 'daily',
				'dismiss_text' => Newspack_Popups::get_default_dismiss_text(),
			]
		);
	}

	/**
	 * Create a popup in the database.
	 *
	 * @param string $popup_content String to render as popup content.
	 * @param object $options Options for the popup.
	 * @return int Popup ID.
	 */
	protected function createPopup( $popup_content = null, $options = null ) {
		if ( null === $popup_content ) {
			$popup_content = self::$popup_content;
		}
		$popup_id = self::factory()->post->create(
			[
				'post_type'    => Newspack_Popups::NEWSPACK_POPUPS_CPT,
				'post_title'   => 'Popup title',
				'post_content' => $popup_content,
			]
		);
		if ( null !== $options ) {
			Newspack_Popups_Model::set_popup_options( $popup_id, $options );
		}
		return $popup_id;
	}

	/**
	 * Trigger post rendering with popups in it.
	 *
	 * @param string $url_query Query to append to URL.
	 * @param string $post_content_override String to render as post content.
	 * @param array  $category_ids Ids of categories of the post.
	 * @param array  $tag_ids Ids of tags of the post.
	 */
	protected function renderPost( $url_query = '', $post_content_override = null, $category_ids = [], $tag_ids = [] ) {
		$post_id = self::factory()->post->create(
			[
				'post_content' => $post_content_override ? $post_content_override : self::$raw_post_content,
			]
		);

		if ( ! empty( $category_ids ) ) {
			wp_set_post_terms( $post_id, $category_ids, 'category' );
		}
		if ( ! empty( $tag_ids ) ) {
			wp_set_post_terms( $post_id, $tag_ids, 'post_tag' );
		}

		// Navigate to post.
		self::go_to( get_permalink( $post_id ) . '&' . $url_query );
		global $wp_query, $post;
		$wp_query->in_the_loop = true;
		setup_postdata( $post );

		// Reset internal duplicate-prevention.
		Newspack_Popups_Inserter::$the_content_has_rendered = false;

		$content = get_post( $post_id )->post_content;

		self::$post_content = apply_filters( 'the_content', $content );
		$dom                = new DomDocument();
		@$dom->loadHTML( self::$post_content ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		self::$dom_xpath = new DOMXpath( $dom );

		// Save page head.
		ob_start();
		wp_head();
		self::$post_head = ob_get_clean();
		$post_head_dom   = new DomDocument();
		@$post_head_dom->loadHTML( self::$post_head ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		self::$post_head_dom_xpath = new DOMXpath( $post_head_dom );
	}

	/**
	 * Get the amp-access config.
	 *
	 * @param bool $decode If true, response will be JSON-decoded.
	 * @return object amp-access config.
	 */
	protected function getAMPAccessConfig( $decode = true ) {
		$amp_access_content = json_decode( self::$post_head_dom_xpath->query( '//*[@id="amp-access"]' )->item( 0 )->textContent );
		parse_str( wp_parse_url( $amp_access_content->authorization )['query'], $amp_access_config );
		if ( $decode ) {
			$amp_access_config['popups']   = json_decode( $amp_access_config['popups'] );
			$amp_access_config['settings'] = json_decode( $amp_access_config['settings'] );
			$amp_access_config['visit']    = json_decode( $amp_access_config['visit'] );
		}
		return $amp_access_config;
	}

	/**
	 * Get the API response.
	 *
	 * @return object API response.
	 */
	protected function getAPIResponse() {
		$amp_access_config   = self::getAMPAccessConfig( false );
		$_REQUEST            = $amp_access_config;
		$maybe_show_campaign = new Maybe_Show_Campaign();
		return $maybe_show_campaign->response;
	}
}
