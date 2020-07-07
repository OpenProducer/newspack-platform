<?php
/**
 * Newspack Popups Model
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * API endpoints
 */
final class Newspack_Popups_Model {

	/**
	 * Retrieve all Popups (first 100).
	 *
	 * @param  boolean $include_unpublished Whether to include unpublished posts.
	 * @return array Array of Popup objects.
	 */
	public static function retrieve_popups( $include_unpublished = false ) {
		$args = [
			'post_type'      => Newspack_Popups::NEWSPACK_PLUGINS_CPT,
			'post_status'    => $include_unpublished ? [ 'publish', 'draft' ] : 'publish',
			'posts_per_page' => 100,
		];

		$sitewide_default_id = get_option( Newspack_Popups::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, null );

		$popups = self::retrieve_popups_with_query( new WP_Query( $args ), true );
		foreach ( $popups as &$popup ) {
			// UI will not allow for setting inline as sitewide default, but there may be
			// legacy popups from before this update.
			if ( 'inline' !== $popup['options']['placement'] ) {
				$popup['sitewide_default'] = absint( $sitewide_default_id ) === absint( $popup['id'] );
			}
		}
		return $popups;
	}

	/**
	 * Set post time to now, making it the sitewide popup.
	 *
	 * @param integer $id ID of the Popup to make sitewide default.
	 */
	public static function set_sitewide_popup( $id ) {
		$popup = self::retrieve_popup_by_id( $id );
		if ( ! $popup ) {
			return new \WP_Error(
				'newspack_popups_popup_doesnt_exist',
				esc_html__( 'The Campaign specified does not exist.', 'newspack-popups' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}

		// Such update will not be permitted by the UI, but it's handled just to be explicit about it.
		if ( 'inline' === $popup['options']['placement'] ) {
			return new \WP_Error(
				'newspack_popups_inline_sitewide',
				esc_html__( 'An inline Campaign cannot be a sitewide default.', 'newspack-popups' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}
		return update_option( Newspack_Popups::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, $id );
	}

	/**
	 * If a certain post is sitewide default, clear it.
	 *
	 * @param integer $id ID of the Popup to unset as sitewide default.
	 */
	public static function unset_sitewide_popup( $id ) {
		$popup = self::retrieve_popup_by_id( $id );
		if ( ! $popup ) {
			return new \WP_Error(
				'newspack_popups_popup_doesnt_exist',
				esc_html__( 'The Campaign specified does not exist.', 'newspack-popups' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}
		if ( absint( get_option( Newspack_Popups::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, null ) ) === absint( $id ) ) {
			return update_option( Newspack_Popups::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, null );
		}
	}

	/**
	 * Set categories for a Popup.
	 *
	 * @param integer $id ID of Popup.
	 * @param array   $categories Array of categories to be set.
	 */
	public static function set_popup_categories( $id, $categories ) {
		$popup = self::retrieve_popup_by_id( $id );
		if ( ! $popup ) {
			return new \WP_Error(
				'newspack_popups_popup_doesnt_exist',
				esc_html__( 'The Campaign specified does not exist.', 'newspack-popups' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}
		$category_ids = array_map(
			function( $category ) {
				return $category['id'];
			},
			$categories
		);
		return wp_set_post_categories( $id, $category_ids );
	}

	/**
	 * Set options for a Popup.
	 *
	 * @param integer $id ID of Popup.
	 * @param array   $options Array of options to update.
	 */
	public static function set_popup_options( $id, $options ) {
		$popup = self::retrieve_popup_by_id( $id );
		if ( ! $popup ) {
			return new \WP_Error(
				'newspack_popups_popup_doesnt_exist',
				esc_html__( 'The Campaign specified does not exist.', 'newspack-popups' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}
		foreach ( $options as $key => $value ) {
			switch ( $key ) {
				case 'frequency':
					if ( ! in_array( $value, [ 'test', 'never', 'once', 'daily', 'always' ] ) ) {
						return new \WP_Error(
							'newspack_popups_invalid_option_value',
							esc_html__( 'Invalid frequency value.', 'newspack-popups' ),
							[
								'status' => 400,
								'level'  => 'fatal',
							]
						);
					}
					update_post_meta( $id, $key, $value );
					break;
				case 'placement':
					if ( ! in_array( $value, [ 'center', 'top', 'bottom', 'inline' ] ) ) {
						return new \WP_Error(
							'newspack_popups_invalid_option_value',
							esc_html__( 'Invalid placement value.', 'newspack-popups' ),
							[
								'status' => 400,
								'level'  => 'fatal',
							]
						);
					}
					update_post_meta( $id, $key, $value );
					break;
				default:
					return new \WP_Error(
						'newspack_popups_invalid_option',
						esc_html__( 'Invalid Campaign option.', 'newspack-popups' ),
						[
							'status' => 400,
							'level'  => 'fatal',
						]
					);
			}
		}
	}

	/**
	 * Retrieve all inline popups.
	 *
	 * @return array Inline popup objects.
	 */
	public static function retrieve_inline_popups() {
		$args = [
			'post_type'   => Newspack_Popups::NEWSPACK_PLUGINS_CPT,
			'post_status' => 'publish',
			'meta_key'    => 'placement',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_value'  => 'inline',
		];

		return self::retrieve_popups_with_query( new WP_Query( $args ) );
	}

	/**
	 * Retrieve first overlay popup matching post categries.
	 *
	 * @return object|null Popup object.
	 */
	public static function retrieve_category_overlay_popup() {
		$post_categories = get_the_category();

		if ( empty( $post_categories ) ) {
			return null;
		}

		$args = [
			'post_type'      => Newspack_Popups::NEWSPACK_PLUGINS_CPT,
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'category__in'   => array_column( $post_categories, 'term_id' ),
			'meta_key'       => 'placement',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_value'     => 'inline',
			'meta_compare'   => '!=',
		];

		$popups = self::retrieve_popups_with_query( new WP_Query( $args ) );
		return count( $popups ) > 0 ? $popups[0] : null;
	}

	/**
	 * Retrieve popup preview CPT post.
	 *
	 * @param string $post_id Post id.
	 * @return object Popup object.
	 */
	public static function retrieve_preview_popup( $post_id ) {
		// Up-to-date post data is stored in an autosave.
		$autosave    = wp_get_post_autosave( $post_id );
		$post_object = $autosave ? $autosave : get_post( $post_id );
		// Setting proper id for correct API calls.
		$post_object->ID = $post_id;

		return self::create_popup_object(
			$post_object,
			false,
			[
				'background_color'        => filter_input( INPUT_GET, 'background_color', FILTER_SANITIZE_STRING ),
				'display_title'           => filter_input( INPUT_GET, 'display_title', FILTER_VALIDATE_BOOLEAN ),
				'dismiss_text'            => filter_input( INPUT_GET, 'dismiss_text', FILTER_SANITIZE_STRING ),
				'frequency'               => filter_input( INPUT_GET, 'frequency', FILTER_SANITIZE_STRING ),
				'overlay_color'           => filter_input( INPUT_GET, 'overlay_color', FILTER_SANITIZE_STRING ),
				'overlay_opacity'         => filter_input( INPUT_GET, 'overlay_opacity', FILTER_SANITIZE_STRING ),
				'placement'               => filter_input( INPUT_GET, 'placement', FILTER_SANITIZE_STRING ),
				'trigger_type'            => filter_input( INPUT_GET, 'trigger_type', FILTER_SANITIZE_STRING ),
				'trigger_delay'           => filter_input( INPUT_GET, 'trigger_delay', FILTER_SANITIZE_STRING ),
				'trigger_scroll_progress' => filter_input( INPUT_GET, 'trigger_scroll_progress', FILTER_SANITIZE_STRING ),
				'utm_suppression'         => filter_input( INPUT_GET, 'utm_suppression', FILTER_SANITIZE_STRING ),
			]
		);
	}

	/**
	 * Retrieve popup CPT post by ID.
	 *
	 * @param string $post_id Post id.
	 * @return object Popup object.
	 */
	public static function retrieve_popup_by_id( $post_id ) {
		$args = [
			'post_type'      => Newspack_Popups::NEWSPACK_PLUGINS_CPT,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'p'              => $post_id,
		];

		$popups = self::retrieve_popups_with_query( new WP_Query( $args ) );
		return count( $popups ) > 0 ? $popups[0] : null;
	}

	/**
	 * Retrieve popup CPT posts.
	 *
	 * @param WP_Query $query The query to use.
	 * @param boolean  $include_categories If true, returned objects will include assigned categories.
	 * @return array Popup objects array
	 */
	protected static function retrieve_popups_with_query( WP_Query $query, $include_categories = false ) {
		$popups             = [];
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$popups[] = self::create_popup_object(
					get_post( get_the_ID() ),
					$include_categories
				);
			}
			wp_reset_postdata();
		}
		return $popups;
	}

	/**
	 * Create the popup object.
	 *
	 * @param WP_Post $campaign_post The campaign post object.
	 * @param boolean $include_categories If true, returned objects will include assigned categories.
	 * @param object  $options Popup options to use instead of the options retrieved from the post. Used for popup previews.
	 * @return object Popup object
	 */
	protected static function create_popup_object( $campaign_post, $include_categories = false, $options = null ) {
		$id = $campaign_post->ID;

		$post_options = isset( $options ) ? $options : [
			'background_color'        => get_post_meta( $id, 'background_color', true ),
			'dismiss_text'            => get_post_meta( $id, 'dismiss_text', true ),
			'display_title'           => get_post_meta( $id, 'display_title', true ),
			'frequency'               => get_post_meta( $id, 'frequency', true ),
			'overlay_color'           => get_post_meta( $id, 'overlay_color', true ),
			'overlay_opacity'         => get_post_meta( $id, 'overlay_opacity', true ),
			'placement'               => get_post_meta( $id, 'placement', true ),
			'trigger_type'            => get_post_meta( $id, 'trigger_type', true ),
			'trigger_delay'           => get_post_meta( $id, 'trigger_delay', true ),
			'trigger_scroll_progress' => get_post_meta( $id, 'trigger_scroll_progress', true ),
			'utm_suppression'         => get_post_meta( $id, 'utm_suppression', true ),
		];

		$popup = [
			'id'      => $id,
			'status'  => $campaign_post->post_status,
			'title'   => $campaign_post->post_title,
			'content' => $campaign_post->post_content,
			'options' => wp_parse_args(
				array_filter( $post_options ),
				[
					'background_color'        => '#FFFFFF',
					'display_title'           => false,
					'dismiss_text'            => '',
					'frequency'               => 'test',
					'overlay_color'           => '#000000',
					'overlay_opacity'         => 30,
					'placement'               => 'center',
					'trigger_type'            => 'time',
					'trigger_delay'           => 0,
					'trigger_scroll_progress' => 0,
					'utm_suppression'         => null,
				]
			),
		];
		if ( $include_categories ) {
			$popup['categories'] = get_the_category( $id );
		}

		if ( self::is_inline( $popup ) ) {
			return $popup;
		}

		switch ( $popup['options']['trigger_type'] ) {
			case 'scroll':
				$popup['options']['trigger_delay'] = 0;
				break;
			case 'time':
			default:
				$popup['options']['trigger_scroll_progress'] = 0;
				break;
		};
		if ( ! in_array( $popup['options']['placement'], [ 'top', 'bottom' ], true ) ) {
			$popup['options']['placement'] = 'center';
		}
		return $popup;
	}

	/**
	 * Get the popup dismissal text.
	 *
	 * @param object $popup The popup object.
	 * @return string|null Dismiss popup text.
	 */
	protected static function get_dismiss_text( $popup ) {
		return ! empty( $popup['options']['dismiss_text'] ) && strlen( trim( $popup['options']['dismiss_text'] ) ) > 0 ? $popup['options']['dismiss_text'] : null;
	}

	/**
	 * Get the popup delay in milliseconds.
	 *
	 * @param object $popup The popup object.
	 * @return number Delay in milliseconds.
	 */
	protected static function get_delay( $popup ) {
		return intval( $popup['options']['trigger_delay'] ) * 1000 + 500;
	}

	/**
	 * Is it an inline popup or not.
	 *
	 * @param object $popup The popup object.
	 * @return boolean True if it is an inline popup.
	 */
	protected static function is_inline( $popup ) {
		return 'inline' === $popup['options']['placement'];
	}

	/**
	 * Insert amp-analytics tracking code.
	 *
	 * @param object $popup The popup object.
	 * @param string $element_id The id of the popup element.
	 * @return string Prints the generated amp-analytics element.
	 */
	protected static function insert_event_tracking( $popup, $body, $element_id ) {
		if ( Newspack_Popups::previewed_popup_id() ) {
			return '';
		}
		global $wp;

		$is_inline = self::is_inline( $popup );
		$endpoint  = self::get_dismiss_endpoint();

		// Mailchimp.
		$mailchimp_form_selector = '';
		if ( preg_match( '/wp-block-jetpack-mailchimp/', $body ) !== 0 ) {
			$mailchimp_form_selector = '.wp-block-jetpack-mailchimp form';
		}
		if ( preg_match( '/mc4wp-form/', $body ) !== 0 ) {
			$mailchimp_form_selector = '.mc4wp-form';
		}

		?>
		<?php if ( $mailchimp_form_selector ) : ?>
			<amp-analytics>
				<script type="application/json">
					{
						"requests": {
							"event": "<?php echo esc_url( $endpoint ); ?>"
						},
						"triggers": {
							"formSubmitSuccess": {
								"on": "amp-form-submit-success",
								"request": "event",
								"selector": "#<?php echo esc_attr( $element_id ); ?> <?php echo esc_attr( $mailchimp_form_selector ); ?>",
								"extraUrlParams": {
									"popup_id": "<?php echo ( esc_attr( $popup['id'] ) ); ?>",
									"url": "<?php echo esc_url( home_url( $wp->request ) ); ?>",
									"mailing_list_status": "subscribed"
								}
							}
						},
						"transport": {
							"beacon": true,
							"xhrpost": true,
							"useBody": true,
							"image": false
						}
					}
				</script>
			</amp-analytics>
		<?php endif; ?>

		<?php if ( $is_inline ) : ?>
			<amp-analytics>
				<script type="application/json">
					{
						"requests": {
							"event": "<?php echo esc_url( $endpoint ); ?>"
						},
						"triggers": {
							"trackPageview": {
								"on": "visible",
								"request": "event",
								"visibilitySpec": {
									"selector": "#<?php echo esc_attr( $element_id ); ?>",
									"visiblePercentageMin": 90,
									"totalTimeMin": 500,
									"continuousTimeMin": 200
								},
								"extraUrlParams": {
									"popup_id": "<?php echo ( esc_attr( $popup['id'] ) ); ?>",
									"url": "<?php echo esc_url( home_url( $wp->request ) ); ?>"
								}
							}
						},
						"transport": {
							"beacon": true,
							"xhrpost": true,
							"useBody": true,
							"image": false
						}
					}
				</script>
			</amp-analytics>
		<?php endif; ?>
		<?php

		// GA events which are not supported by Newspack plugin filter.
		if ( class_exists( '\Google\Site_Kit\Context', '\Google\Site_Kit\Modules\Analytics' ) ) {
			$analytics           = new \Google\Site_Kit\Modules\Analytics( new \Google\Site_Kit\Context( GOOGLESITEKIT_PLUGIN_MAIN_FILE ) );
			$google_analytics_id = $analytics->get_settings()->get()['propertyID'];
		} else {
			return '';
		}

		$event_category = 'Newspack Announcement';
		$event_label    = 'Newspack Announcement: ' . $popup['title'] . ' (' . $popup['id'] . ')';

		?>
		<amp-analytics type="gtag" data-credentials="include">
			<script type="application/json">
				{
					"vars" : {
						"gtag_id": "<?php echo esc_attr( $google_analytics_id ); ?>",
						"config" : {
							"<?php echo esc_attr( $google_analytics_id ); ?>": { "groups": "default", "send_page_view": false }
						}
					},
					"triggers": {
						"popupVisible": {
							"on": "visible",
							"request": "event",
							"selector": "#<?php echo esc_attr( $element_id ); ?>",
							"visibilitySpec": {
								"totalTimeMin": "500"
							},
							"vars": {
								"event_name": "<?php echo esc_html__( 'Seen', 'newspack-popups' ); ?>",
								"event_label": "<?php echo esc_attr( $event_label ); ?>",
								"event_category": "<?php echo esc_attr( $event_category ); ?>"
							}
						}
					}
				}
			</script>
		</amp-analytics>
		<?php
	}

	/**
	 * Add tracked analytics events to use in Newspack Plugin's newspack_analytics_events filter.
	 *
	 * @param object $popup The popup object.
	 * @param string $element_id The id of the popup element.
	 */
	protected static function get_analytics_events( $popup, $body, $element_id ) {
		if ( Newspack_Popups::previewed_popup_id() ) {
			return '';
		}

		$popup_id       = $popup['id'];
		$event_category = 'Newspack Announcement';
		$event_label    = 'Newspack Announcement: ' . $popup['title'] . ' (' . $popup_id . ')';

		$has_link                = preg_match( '/<a\s/', $body ) !== 0;
		$has_form                = preg_match( '/<form\s/', $body ) !== 0;
		$has_dismiss_form        = 'inline' !== $popup['options']['placement'];
		$has_not_interested_form = self::get_dismiss_text( $popup );

		$analytics_events = [
			[
				'id'             => 'popupPageLoaded-' . $popup_id,
				'on'             => 'ini-load',
				'element'        => '#' . esc_attr( $element_id ),
				'event_name'     => esc_html__( 'Load', 'newspack-popups' ),
				'event_label'    => esc_attr( $event_label ),
				'event_category' => esc_attr( $event_category ),
			],
		];

		if ( $has_link ) {
			$analytics_events[] = [
				'id'             => 'popupAnchorClicks-' . $popup_id,
				'on'             => 'click',
				'element'        => '#' . esc_attr( $element_id ) . ' a',
				'amp_element'    => '#' . esc_attr( $element_id ) . ' a',
				'event_name'     => esc_html__( 'Link Click', 'newspack-popups' ),
				'event_label'    => esc_attr( $event_label ),
				'event_category' => esc_attr( $event_category ),
			];
		}

		if ( $has_form ) {
			$analytics_events[] = [
				'id'             => 'popupFormSubmitSuccess-' . $popup_id,
				'amp_on'         => 'amp-form-submit-success',
				'on'             => 'submit',
				'element'        => '#' . esc_attr( $element_id ) . ' form:not(.popup-action-form)',
				'event_name'     => esc_html__( 'Form Submission', 'newspack-popups' ),
				'event_label'    => esc_attr( $event_label ),
				'event_category' => esc_attr( $event_category ),
			];
		}
		if ( $has_dismiss_form ) {
			$analytics_events[] = [
				'id'             => 'popupDismissed-' . $popup_id,
				'amp_on'         => 'amp-form-submit-success',
				'on'             => 'submit',
				'element'        => '#' . esc_attr( $element_id ) . ' form.popup-dismiss-form',
				'event_name'     => esc_html__( 'Dismissal', 'newspack-popups' ),
				'event_label'    => esc_attr( $event_label ),
				'event_category' => esc_attr( $event_category ),
			];
		}
		if ( $has_not_interested_form ) {
			$analytics_events[] = [
				'id'             => 'popupNotInterested-' . $popup_id,
				'amp_on'         => 'amp-form-submit-success',
				'on'             => 'submit',
				'element'        => '#' . esc_attr( $element_id ) . ' form.popup-not-interested-form',
				'event_name'     => esc_html__( 'Permanent Dismissal', 'newspack-popups' ),
				'event_label'    => esc_attr( $event_label ),
				'event_category' => esc_attr( $event_category ),
			];
		}

		return $analytics_events;
	}

	/**
	 * Generate markup inline popup.
	 *
	 * @param string $popup The popup object.
	 * @return string The generated markup.
	 */
	public static function generate_inline_popup( $popup ) {
		global $wp;

		do_action( 'newspack_campaigns_before_campaign_render', $popup );
		$blocks = parse_blocks( $popup['content'] );
		$body   = '';
		foreach ( $blocks as $block ) {
			$body .= render_block( $block );
		}
		do_action( 'newspack_campaigns_after_campaign_render', $popup );

		$element_id           = 'lightbox' . rand(); // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
		$endpoint             = self::get_dismiss_endpoint();
		$display_title        = $popup['options']['display_title'];
		$hidden_fields        = self::get_hidden_fields( $popup );
		$dismiss_text         = self::get_dismiss_text( $popup );
		$is_newsletter_prompt = false !== strpos( $body, 'wp-block-jetpack-mailchimp' ); // Is this a newsletter prompt? Add a class so we can target for analytics.
		$classes              = array( 'newspack-inline-popup' );
		$classes[]            = ( ! empty( $popup['title'] ) && $display_title ) ? 'newspack-lightbox-has-title' : null;
		$classes[]            = $is_newsletter_prompt ? 'newspack-newsletter-prompt-inline' : null;

		add_filter(
			'newspack_analytics_events',
			function ( $evts ) use ( $popup, $body, $element_id ) {
					return array_merge( $evts, self::get_analytics_events( $popup, $body, $element_id ) );
			}
		);

		ob_start();
		?>
			<?php self::insert_event_tracking( $popup, $body, $element_id ); ?>
			<amp-layout amp-access="popup_<?php echo esc_attr( $popup['id'] ); ?>.displayPopup" amp-access-hide class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" role="button" tabindex="0" style="<?php echo esc_attr( self::container_style( $popup ) ); ?>" id="<?php echo esc_attr( $element_id ); ?>">
						<?php if ( ! empty( $popup['title'] ) && $display_title ) : ?>
					<h1 class="newspack-popup-title"><?php echo esc_html( $popup['title'] ); ?></h1>
				<?php endif; ?>
						<?php echo ( $body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( $dismiss_text ) : ?>
					<form class="popup-not-interested-form popup-action-form"
						method="POST"
						action-xhr="<?php echo esc_url( $endpoint ); ?>"
						target="_top">
							<?php echo $hidden_fields; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<input
							name="suppress_forever"
							type="hidden"
							value="1"
						/>
						<button on="tap:<?php echo esc_attr( $element_id ); ?>.hide" aria-label="<?php esc_attr( $dismiss_text ); ?>" style="<?php echo esc_attr( self::container_style( $popup ) ); ?>"><?php echo esc_attr( $dismiss_text ); ?></button>
					</form>
				<?php endif; ?>
			</amp-layout>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate markup and styles for popup.
	 *
	 * @param string $popup The popup object.
	 * @return string The generated markup.
	 */
	public static function generate_popup( $popup ) {

		if ( isset( $popup['options'], $popup['options']['placement'] ) && 'inline' === $popup['options']['placement'] ) {
			return self::generate_inline_popup( $popup );
		}

		do_action( 'newspack_campaigns_before_campaign_render', $popup );
		$blocks = parse_blocks( $popup['content'] );
		$body   = '';
		foreach ( $blocks as $block ) {
			$body .= render_block( $block );
		}
		do_action( 'newspack_campaigns_after_campaign_render', $popup );

		$element_id           = 'lightbox' . rand(); // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
		$endpoint             = self::get_dismiss_endpoint();
		$dismiss_text         = self::get_dismiss_text( $popup );
		$display_title        = $popup['options']['display_title'];
		$overlay_opacity      = absint( $popup['options']['overlay_opacity'] ) / 100;
		$overlay_color        = $popup['options']['overlay_color'];
		$hidden_fields        = self::get_hidden_fields( $popup );
		$is_newsletter_prompt = false !== strpos( $body, 'wp-block-jetpack-mailchimp' ); // Is this a newsletter prompt? Add a class so we can target for analytics.
		$classes              = array( 'newspack-lightbox', 'newspack-lightbox-placement-' . $popup['options']['placement'] );
		$classes[]            = ( ! empty( $popup['title'] ) && $display_title ) ? 'newspack-lightbox-has-title' : null;
		$classes[]            = $is_newsletter_prompt ? 'newspack-newsletter-prompt-overlay' : null;

		add_filter(
			'newspack_analytics_events',
			function ( $evts ) use ( $popup, $body, $element_id ) {
				return array_merge( $evts, self::get_analytics_events( $popup, $body, $element_id ) );
			}
		);

		ob_start();
		?>
		<amp-layout amp-access="popup_<?php echo esc_attr( $popup['id'] ); ?>.displayPopup" amp-access-hide class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" role="button" tabindex="0" id="<?php echo esc_attr( $element_id ); ?>">
			<div class="newspack-popup-wrapper" style="<?php echo esc_attr( self::container_style( $popup ) ); ?>">
				<div class="newspack-popup">
					<?php if ( ! empty( $popup['title'] ) && $display_title ) : ?>
						<h1 class="newspack-popup-title"><?php echo esc_html( $popup['title'] ); ?></h1>
					<?php endif; ?>
					<?php echo ( $body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $dismiss_text ) : ?>
					<form class="popup-not-interested-form popup-action-form"
						method="POST"
						action-xhr="<?php echo esc_url( $endpoint ); ?>"
						target="_top">
							<?php echo $hidden_fields; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<input
							name="suppress_forever"
							type="hidden"
							value="1"
						/>
						<button on="tap:<?php echo esc_attr( $element_id ); ?>.hide" aria-label="<?php esc_attr( $dismiss_text ); ?>" style="<?php echo esc_attr( self::container_style( $popup ) ); ?>"><?php echo esc_attr( $dismiss_text ); ?></button>
					</form>
					<?php endif; ?>
					<form class="popup-dismiss-form popup-action-form"
						method="POST"
						action-xhr="<?php echo esc_url( $endpoint ); ?>"
						target="_top">
						<?php echo $hidden_fields; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<button on="tap:<?php echo esc_attr( $element_id ); ?>.hide" class="newspack-lightbox__close" aria-label="<?php esc_html_e( 'Close Pop-up', 'newspack-popups' ); ?>" style="<?php echo esc_attr( self::container_style( $popup ) ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
						</button>
					</form>
				</div>
			</div>
			<form class="popup-dismiss-form popup-action-form"
				method="POST"
				action-xhr="<?php echo esc_url( $endpoint ); ?>"
				target="_top">
				<?php echo $hidden_fields; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<button style="opacity: <?php echo floatval( $overlay_opacity ); ?>;background-color:<?php echo esc_attr( $overlay_color ); ?>;" class="newspack-lightbox-shim" on="tap:<?php echo esc_attr( $element_id ); ?>.hide"></button>
			</form>
		</amp-layout>
		<div id="newspack-lightbox-marker">
			<amp-position-observer on="enter:showAnim.start;" once layout="nodisplay" />
		</div>
		<amp-animation id="showAnim" layout="nodisplay">
			<script type="application/json">
				{
					"duration": "125ms",
					"fill": "both",
					"iterations": "1",
					"direction": "alternate",
					"animations": [
						{
							"selector": ".newspack-lightbox",
							"delay": "<?php echo esc_html( self::get_delay( $popup ) ); ?>",
							"keyframes": {
								"opacity": ["0", "1"],
								"visibility": ["hidden", "visible"]
							}
						},
						{
							"selector": ".newspack-lightbox",
							"delay": "<?php echo esc_html( self::get_delay( $popup ) - 500 ); ?>",
							"keyframes": {
								"transform": ["translateY(100vh)", "translateY(0vh)"]
							}
						},
						{
								"selector": ".newspack-popup-wrapper",
								"delay": "<?php echo intval( $popup['options']['trigger_delay'] ) * 1000 + 625; ?>",
								"keyframes": {
									<?php if ( 'top' === $popup['options']['placement'] ) : ?>
										"transform": ["translateY(-100%)", "translateY(0)"]
									<?php elseif ( 'bottom' === $popup['options']['placement'] ) : ?>
										"transform": ["translateY(100%)", "translateY(0)"]
									<?php else : ?>
										"opacity": ["0", "1"]
									<?php endif; ?>
								}
						}
					]
				}
			</script>
		</amp-animation>
		<?php self::insert_event_tracking( $popup, $body, $element_id ); ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * Pick either white or black, whatever has sufficient contrast with the color being passed to it.
	 * Copied from https://github.com/Automattic/newspack-theme/blob/master/newspack-theme/inc/template-functions.php#L401-L431
	 *
	 * @param  string $background_color Hexidecimal value of the background color.
	 * @return string Either black or white hexidecimal values.
	 *
	 * @ref https://stackoverflow.com/questions/1331591/given-a-background-color-black-or-white-text
	 */
	public static function foreground_color_for_background( $background_color ) {
		// hex RGB.
		$r1 = hexdec( substr( $background_color, 1, 2 ) );
		$g1 = hexdec( substr( $background_color, 3, 2 ) );
		$b1 = hexdec( substr( $background_color, 5, 2 ) );
		// Black RGB.
		$black_color    = '#000';
		$r2_black_color = hexdec( substr( $black_color, 1, 2 ) );
		$g2_black_color = hexdec( substr( $black_color, 3, 2 ) );
		$b2_black_color = hexdec( substr( $black_color, 5, 2 ) );
		// Calc contrast ratio.
		$l1             = 0.2126 * pow( $r1 / 255, 2.2 ) +
			0.7152 * pow( $g1 / 255, 2.2 ) +
			0.0722 * pow( $b1 / 255, 2.2 );
		$l2             = 0.2126 * pow( $r2_black_color / 255, 2.2 ) +
			0.7152 * pow( $g2_black_color / 255, 2.2 ) +
			0.0722 * pow( $b2_black_color / 255, 2.2 );
		$contrast_ratio = 0;
		if ( $l1 > $l2 ) {
			$contrast_ratio = (int) ( ( $l1 + 0.05 ) / ( $l2 + 0.05 ) );
		} else {
			$contrast_ratio = (int) ( ( $l2 + 0.05 ) / ( $l1 + 0.05 ) );
		}
		if ( $contrast_ratio > 5 ) {
			// If contrast is more than 5, return black color.
			return '#000';
		} else {
			// if not, return white color.
			return '#fff';
		}
	}

	/**
	 * Generate inline styles for Popup element.
	 *
	 * @param  object $popup A Pop-up object.
	 * @return string Inline styles attribute.
	 */
	public static function container_style( $popup ) {
		$background_color = $popup['options']['background_color'];
		$foreground_color = self::foreground_color_for_background( $background_color );
		return 'background-color:' . $background_color . ';color:' . $foreground_color;
	}

	/**
	 * Endpoint to dismiss Pop-up.
	 *
	 * @return string Endpoint URL.
	 */
	public static function get_dismiss_endpoint() {
		return str_replace( 'http://', '//', get_rest_url( null, 'newspack-popups/v1/reader' ) );
	}

	/**
	 * Generate hidden fields to be used in all dismiss FORMs.
	 *
	 * @param  object $popup A Pop-up object.
	 * @return string Hidden fields markup.
	 */
	public static function get_hidden_fields( $popup ) {
		ob_start();
		?>
		<input
			name="url"
			type="hidden"
			value="CANONICAL_URL"
			data-amp-replace="CANONICAL_URL"
		/>
		<input
			name="popup_id"
			type="hidden"
			value="<?php echo ( esc_attr( $popup['id'] ) ); ?>"
		/>
		<input
			name="mailing_list_status"
			type="hidden"
			[value]="mailing_list_status"
		/>
		<?php
		return ob_get_clean();
	}
}
