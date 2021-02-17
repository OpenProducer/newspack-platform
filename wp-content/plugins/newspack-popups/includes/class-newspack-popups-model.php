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
	 * Possible placements of overlay popups.
	 *
	 * @var array
	 */
	protected static $overlay_placements = [ 'top', 'bottom', 'center' ];

	/**
	 * Possible placements of inline popups.
	 *
	 * @var array
	 */
	protected static $inline_placements = [ 'inline', 'above_header' ];

	/**
	 * Retrieve all Popups (first 100).
	 *
	 * @param  boolean $include_unpublished Whether to include unpublished posts.
	 * @return array Array of Popup objects.
	 */
	public static function retrieve_popups( $include_unpublished = false ) {
		$args = [
			'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
			'post_status'    => $include_unpublished ? [ 'draft', 'pending', 'future', 'publish' ] : 'publish',
			'posts_per_page' => 100,
		];

		$popups = self::retrieve_popups_with_query( new WP_Query( $args ), true );
		return $popups;
	}

	/**
	 * Set terms for a Popup.
	 *
	 * @param integer $id ID of Popup.
	 * @param array   $terms Array of terms to be set.
	 * @param string  $taxonomy Taxonomy slug.
	 */
	public static function set_popup_terms( $id, $terms, $taxonomy ) {
		$popup = self::retrieve_popup_by_id( $id, true );
		if ( ! $popup ) {
			return new \WP_Error(
				'newspack_popups_popup_doesnt_exist',
				esc_html__( 'The prompt specified does not exist.', 'newspack-popups' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}
		if ( ! in_array( $taxonomy, [ 'category', Newspack_Popups::NEWSPACK_POPUPS_TAXONOMY ] ) ) {
			return new \WP_Error(
				'newspack_popups_invalid_taxonomy',
				esc_html__( 'Invalid taxonomy.', 'newspack-popups' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}
		$term_ids = array_map(
			function( $term ) {
				return $term['id'];
			},
			$terms
		);
		return wp_set_post_terms( $id, $term_ids, $taxonomy );
	}

	/**
	 * Set options for a Popup. Can be used by other plugins to set popup's options.
	 *
	 * @param integer $id ID of Popup.
	 * @param array   $options Array of options to update.
	 */
	public static function set_popup_options( $id, $options ) {
		$popup = self::retrieve_popup_by_id( $id, true );
		if ( ! $popup ) {
			return new \WP_Error(
				'newspack_popups_popup_doesnt_exist',
				esc_html__( 'The prompt specified does not exist.', 'newspack-popups' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}
		foreach ( $options as $key => $value ) {
			switch ( $key ) {
				case 'frequency':
					if ( ! in_array( $value, [ 'once', 'daily', 'always', 'manual' ] ) ) {
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
					if ( ! in_array( $value, array_merge( self::$overlay_placements, self::$inline_placements ) ) ) {
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
				case 'trigger_type':
				case 'trigger_scroll_progress':
				case 'utm_suppression':
				case 'selected_segment_id':
				case 'dismiss_text':
					update_post_meta( $id, $key, esc_attr( $value ) );
					break;
				default:
					return new \WP_Error(
						'newspack_popups_invalid_option',
						esc_html__( 'Invalid prompt option.', 'newspack-popups' ),
						[
							'status' => 400,
							'level'  => 'fatal',
						]
					);
			}
		}
	}

	/**
	 * Retrieve all overlay popups.
	 *
	 * @param  boolean     $include_unpublished Whether to include unpublished prompts.
	 * @param  int|boolean $campaign_id Campaign term ID, or false to ignore campaign.
	 * @return array Overlay popup objects.
	 */
	public static function retrieve_overlay_popups( $include_unpublished = false, $campaign_id = false ) {
		$args = [
			'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
			'post_status'    => $include_unpublished ? [ 'draft', 'pending', 'future', 'publish' ] : 'publish',
			'meta_key'       => 'placement',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_value'     => self::$overlay_placements,
			'meta_compare'   => 'IN',
			'posts_per_page' => 100,
		];

		$tax_query = [
			'taxonomy' => 'category',
			'operator' => 'NOT EXISTS',
		];

		$args['tax_query'] = [ $tax_query ]; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

		// If previewing specific campaign.
		if ( ! empty( $campaign_id ) ) {
			$campaign_tax_query = [ 'taxonomy' => Newspack_Popups::NEWSPACK_POPUPS_TAXONOMY ];

			if ( -1 === (int) $campaign_id ) {
				$campaign_tax_query['operator'] = 'NOT EXISTS';
			} else {
				$campaign_tax_query['field'] = 'term_id';
				$campaign_tax_query['terms'] = [ $campaign_id ];
			}

			$args['tax_query'][] = $campaign_tax_query;
		}

		if ( ! empty( $campaign_id ) ) {
			$args['tax_query']['relation'] = 'AND';
		}

		return self::retrieve_popups_with_query( new WP_Query( $args ) );
	}

	/**
	 * Retrieve all inline prompts.
	 *
	 * @param  boolean     $include_unpublished Whether to include unpublished prompts.
	 * @param  int|boolean $campaign_id Campaign term ID, or false to ignore campaign.
	 * @return array Inline popup objects.
	 */
	public static function retrieve_inline_popups( $include_unpublished = false, $campaign_id = false ) {
		$args = [
			'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
			'post_status'    => $include_unpublished ? [ 'draft', 'pending', 'future', 'publish' ] : 'publish',
			'meta_key'       => 'placement',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_value'     => self::$inline_placements,
			'meta_compare'   => 'IN',
			'posts_per_page' => 100,
		];

		// If previewing specific campaign.
		if ( ! empty( $campaign_id ) ) {
			$tax_query = [ 'taxonomy' => Newspack_Popups::NEWSPACK_POPUPS_TAXONOMY ];

			if ( -1 === (int) $campaign_id ) {
				$tax_query['operator'] = 'NOT EXISTS';
			} else {
				$tax_query['field'] = 'term_id';
				$tax_query['terms'] = [ $campaign_id ];
			}

			$args['tax_query'] = [ $tax_query ]; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		return self::retrieve_popups_with_query( new WP_Query( $args ) );
	}

	/**
	 * Retrieve overlay popups matching post categories.
	 *
	 * @param  boolean     $include_unpublished Whether to include unpublished prompts.
	 * @param  int|boolean $campaign_id Campaign term ID, or false to ignore campaign.
	 * @return array|null Array of popup objects.
	 */
	public static function retrieve_category_overlay_popups( $include_unpublished = false, $campaign_id = false ) {
		$post_categories = get_the_category();

		if ( empty( $post_categories ) ) {
			return null;
		}

		$args = [
			'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
			'posts_per_page' => 1,
			'post_status'    => $include_unpublished ? [ 'draft', 'pending', 'future', 'publish' ] : 'publish',
			'category__in'   => array_column( $post_categories, 'term_id' ),
			'meta_key'       => 'placement',
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'meta_value'     => self::$overlay_placements,
			'meta_compare'   => 'IN',
			'posts_per_page' => 100,
		];

		// If previewing specific campaign.
		if ( ! empty( $campaign_id ) ) {
			$tax_query = [ 'taxonomy' => Newspack_Popups::NEWSPACK_POPUPS_TAXONOMY ];

			if ( -1 === (int) $campaign_id ) {
				$tax_query['operator'] = 'NOT EXISTS';
			} else {
				$tax_query['field'] = 'term_id';
				$tax_query['terms'] = [ $campaign_id ];
			}

			$args['tax_query'] = [ $tax_query ]; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		$popups = self::retrieve_popups_with_query( new WP_Query( $args ) );
		return count( $popups ) > 0 ? $popups : null;
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
				'dismiss_text_alignment'  => filter_input( INPUT_GET, 'dismiss_text_alignment', FILTER_SANITIZE_STRING ),
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
	 * @param bool   $include_drafts Include drafts.
	 * @return object Popup object.
	 */
	public static function retrieve_popup_by_id( $post_id, $include_drafts = false ) {
		$args = [
			'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
			'posts_per_page' => 1,
			'p'              => $post_id,
		];
		if ( false === $include_drafts ) {
			$args['post_status'] = 'publish';
		}

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
		$popups = [];
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$popup = self::create_popup_object(
					get_post( get_the_ID() ),
					$include_categories
				);
				$popup = self::deprecate_test_never( $popup, 'publish' === $query->get( 'post_status', null ) );

				if ( $popup ) {
					$popups[] = $popup;
				}
			}
			wp_reset_postdata();
		}
		return $popups;
	}

	/**
	 * Deprecate Test Mode and Never frequency.
	 *
	 * @param object $popup The popup.
	 * @param bool   $published_only Whether the result must be a published post.
	 * @return object|null Popup object or null.
	 */
	protected static function deprecate_test_never( $popup, $published_only ) {
		$frequency = $popup['options']['frequency'];
		$placement = $popup['options']['placement'];
		if ( in_array( $frequency, [ 'never', 'test' ] ) ) {
			if ( in_array( $placement, [ 'inline', 'above_header' ] ) ) {
				$popup['options']['frequency'] = 'always';
			} else {
				$popup['options']['frequency'] = 'daily';
			}
			update_post_meta( $popup['id'], 'frequency', $popup['options']['frequency'] );
			$popup['status'] = 'draft';

			$post = get_post( $popup['id'] );

			$post->post_status = 'draft';
			wp_update_post( $post );

			if ( $published_only ) {
				$popup = null;
			}
		}
		return $popup;
	}

	/**
	 * Create the popup object.
	 *
	 * @param WP_Post $campaign_post The prompt post object.
	 * @param boolean $include_categories If true, returned objects will include assigned categories.
	 * @param object  $options Popup options to use instead of the options retrieved from the post. Used for popup previews.
	 * @return object Popup object
	 */
	public static function create_popup_object( $campaign_post, $include_categories = false, $options = null ) {
		$id = $campaign_post->ID;

		$post_options = isset( $options ) ? $options : [
			'background_color'        => get_post_meta( $id, 'background_color', true ),
			'dismiss_text'            => get_post_meta( $id, 'dismiss_text', true ),
			'dismiss_text_alignment'  => get_post_meta( $id, 'dismiss_text_alignment', true ),
			'display_title'           => get_post_meta( $id, 'display_title', true ),
			'frequency'               => get_post_meta( $id, 'frequency', true ),
			'overlay_color'           => get_post_meta( $id, 'overlay_color', true ),
			'overlay_opacity'         => get_post_meta( $id, 'overlay_opacity', true ),
			'placement'               => get_post_meta( $id, 'placement', true ),
			'trigger_type'            => get_post_meta( $id, 'trigger_type', true ),
			'trigger_delay'           => get_post_meta( $id, 'trigger_delay', true ),
			'trigger_scroll_progress' => get_post_meta( $id, 'trigger_scroll_progress', true ),
			'utm_suppression'         => get_post_meta( $id, 'utm_suppression', true ),
			'selected_segment_id'     => get_post_meta( $id, 'selected_segment_id', true ),
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
					'dismiss_text_alignment'  => 'center',
					'frequency'               => 'always',
					'overlay_color'           => '#000000',
					'overlay_opacity'         => 30,
					'placement'               => 'inline',
					'trigger_type'            => 'time',
					'trigger_delay'           => 0,
					'trigger_scroll_progress' => 0,
					'utm_suppression'         => null,
					'selected_segment_id'     => '',
				]
			),
		];
		if ( $popup['options']['selected_segment_id'] && ! in_array( $popup['options']['selected_segment_id'], Newspack_Popups_Segmentation::get_segment_ids() ) ) {
			$popup['options']['selected_segment_id'] = null;
		}
		if ( $include_categories ) {
			$popup['categories']      = get_the_category( $id );
			$popup['campaign_groups'] = get_the_terms( $id, Newspack_Popups::NEWSPACK_POPUPS_TAXONOMY );
		}

		if ( self::is_inline( $popup ) ) {
			return $popup;
		}

		if ( self::is_overlay( $popup ) ) {
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
	 * Get the popup dismiss button alignment. Default/empty === center alignment.
	 *
	 * @param object $popup The popup object.
	 * @return string|null Dismiss button alignment.
	 */
	protected static function get_dismiss_text_alignment( $popup ) {
		return ! empty( $popup['options']['dismiss_text_alignment'] ) ? $popup['options']['dismiss_text_alignment'] : 'center';
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
	public static function is_inline( $popup ) {
		if ( ! isset( $popup['options'], $popup['options']['placement'] ) ) {
			return false;
		}
		return in_array( $popup['options']['placement'], self::$inline_placements );
	}

	/**
	 * Get popups which should be inserted above page header.
	 *
	 * @param object $popup The popup object.
	 * @return boolean True if the popup should be inserted above page header.
	 */
	public static function should_be_inserted_above_page_header( $popup ) {
		if ( self::is_inline( $popup ) ) {
			return 'above_header' === $popup['options']['placement'];
		} else {
			// Insert time-triggered overlay popups above the header, this way they will be
			// visible before scrolling below the fold.
			return 'time' === $popup['options']['trigger_type'];
		}
	}

	/**
	 * Get popups which should be inserted in page content.
	 *
	 * @param object $popup The popup object.
	 * @return boolean True if the popup should be inserted in page content.
	 */
	public static function should_be_inserted_in_page_content( $popup ) {
		return self::should_be_inserted_above_page_header( $popup ) === false;
	}

	/**
	 * Is it an overlay popup or not.
	 *
	 * @param object $popup The popup object.
	 * @return boolean True if it is an overlay popup.
	 */
	public static function is_overlay( $popup ) {
		if ( ! isset( $popup['options'], $popup['options']['placement'] ) ) {
			return false;
		}
		return in_array( $popup['options']['placement'], self::$overlay_placements, true );
	}

	/**
	 * Is it an above-header popup?
	 *
	 * @param object $popup The popup object.
	 * @return boolean True if it is an above-header popup.
	 */
	public static function is_above_header( $popup ) {
		if ( ! isset( $popup['options'], $popup['options']['placement'] ) ) {
			return false;
		}
		return 'above_header' === $popup['options']['placement'];
	}

	/**
	 * Does the popup have newsletter prompt?
	 *
	 * @param object $popup The popup object.
	 * @return boolean True if popup has a newsletter prompt.
	 */
	public static function has_newsletter_prompt( $popup ) {
		return (
			false !== strpos( $popup['content'], 'wp:jetpack/mailchimp' ) ||
			false !== strpos( $popup['content'], 'wp:mailchimp-for-wp/form' )
		);
	}

	/**
	 * Does the popup have a donation block?
	 *
	 * @param object $popup The popup object.
	 * @return boolean True if popup has a donation block.
	 */
	public static function has_donation_block( $popup ) {
		return false !== strpos( $popup['content'], 'wp:newspack-blocks/donate' );
	}

	/**
	 * Insert amp-analytics tracking code.
	 *
	 * @param object $popup The popup object.
	 * @param string $body Post body.
	 * @param string $element_id The id of the popup element.
	 * @return string Prints the generated amp-analytics element.
	 */
	protected static function insert_event_tracking( $popup, $body, $element_id ) {
		if (
			Newspack_Popups::is_preview_request() ||
			Newspack_Popups_Settings::is_non_interactive()
		) {
			return '';
		}
		global $wp;

		$endpoint = self::get_reader_endpoint();

		// Mailchimp.
		$mailchimp_form_selector = '';
		$email_form_field_name   = 'email';
		if ( preg_match( '/wp-block-jetpack-mailchimp/', $body ) !== 0 ) {
			$mailchimp_form_selector = '.wp-block-jetpack-mailchimp form';
		}
		if ( preg_match( '/mc4wp-form/', $body ) !== 0 ) {
			$mailchimp_form_selector = '.mc4wp-form';
			$email_form_field_name   = 'EMAIL';
		}

		$amp_analytics_config = [
			'requests' => [
				'event' => esc_url( $endpoint ),
			],
			'triggers' => [
				'trackPageview' => [
					'on'             => 'visible',
					'request'        => 'event',
					'visibilitySpec' => [
						'selector'             => '#' . esc_attr( $element_id ),
						'visiblePercentageMin' => 90,
						'totalTimeMin'         => 500,
						'continuousTimeMin'    => 200,
					],
					'extraUrlParams' => [
						'popup_id' => esc_attr( self::canonize_popup_id( $popup['id'] ) ),
						'cid'      => 'CLIENT_ID(' . esc_attr( Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME ) . ')',
					],
				],
			],
		];
		if ( $mailchimp_form_selector ) {
			$amp_analytics_config['triggers']['formSubmitSuccess'] = [
				'on'             => 'amp-form-submit-success',
				'request'        => 'event',
				'selector'       => '#' . esc_attr( $element_id ) . ' ' . esc_attr( $mailchimp_form_selector ),
				'extraUrlParams' => [
					'popup_id'            => esc_attr( self::canonize_popup_id( $popup['id'] ) ),
					'cid'                 => 'CLIENT_ID(' . esc_attr( Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME ) . ')',
					'mailing_list_status' => 'subscribed',
					'email'               => '$[formFields[' . esc_attr( $email_form_field_name ) . ']}',
				],
			];
		}

		?>
		<amp-analytics>
			<script type="application/json">
				<?php echo wp_json_encode( $amp_analytics_config ); ?>
			</script>
		</amp-analytics>
		<?php
	}

	/**
	 * Add tracked analytics events to use in Newspack Plugin's newspack_analytics_events filter.
	 *
	 * @param object $popup The popup object.
	 * @param string $body Post body.
	 * @param string $element_id The id of the popup element.
	 */
	protected static function get_analytics_events( $popup, $body, $element_id ) {
		if ( Newspack_Popups::is_preview_request() ) {
			return [];
		}

		$popup_id       = $popup['id'];
		$event_category = 'Newspack Announcement';
		$event_label    = 'Newspack Announcement: ' . $popup['title'] . ' (' . $popup_id . ')';

		$has_link                = preg_match( '/<a\s/', $body ) !== 0;
		$has_form                = preg_match( '/<form\s/', $body ) !== 0;
		$has_dismiss_form        = self::is_overlay( $popup );
		$has_not_interested_form = self::get_dismiss_text( $popup );

		$analytics_events = [
			[
				'id'              => 'popupPageLoaded-' . $popup_id,
				'on'              => 'ini-load',
				'element'         => '#' . esc_attr( $element_id ),
				'event_name'      => esc_html__( 'Load', 'newspack-popups' ),
				'event_label'     => esc_attr( $event_label ),
				'event_category'  => esc_attr( $event_category ),
				'non_interaction' => true,
			],
			[
				'id'              => 'popupSeen-' . $popup_id,
				'on'              => 'visible',
				'element'         => '#' . esc_attr( $element_id ),
				'event_name'      => esc_html__( 'Seen', 'newspack-popups' ),
				'event_label'     => esc_attr( $event_label ),
				'event_category'  => esc_attr( $event_category ),
				'non_interaction' => true,
				'visibilitySpec'  => [
					'totalTimeMin' => 500,
				],
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
				'id'              => 'popupDismissed-' . $popup_id,
				'amp_on'          => 'amp-form-submit-success',
				'on'              => 'submit',
				'element'         => '#' . esc_attr( $element_id ) . ' form.popup-dismiss-form',
				'event_name'      => esc_html__( 'Dismissal', 'newspack-popups' ),
				'event_label'     => esc_attr( $event_label ),
				'event_category'  => esc_attr( $event_category ),
				'non_interaction' => true,
			];
		}
		if ( $has_not_interested_form ) {
			$analytics_events[] = [
				'id'              => 'popupNotInterested-' . $popup_id,
				'amp_on'          => 'amp-form-submit-success',
				'on'              => 'submit',
				'element'         => '#' . esc_attr( $element_id ) . ' form.popup-not-interested-form',
				'event_name'      => esc_html__( 'Permanent Dismissal', 'newspack-popups' ),
				'event_label'     => esc_attr( $event_label ),
				'event_category'  => esc_attr( $event_category ),
				'non_interaction' => true,
			];
		}

		return $analytics_events;
	}

	/**
	 * Canonise popups id. The id from WP will be an integer, but AMP does not play well with that and needs a string.
	 *
	 * @param int $popup_id Popup id.
	 */
	public static function canonize_popup_id( $popup_id ) {
		return 'id_' . $popup_id;
	}

	/**
	 * Get amp-access attributes for a popup-enclosing amp-layout tag.
	 *
	 * @param object $popup Popup.
	 */
	public static function get_access_attrs( $popup ) {
		if ( Newspack_Popups_Settings::is_non_interactive() ) {
			return '';
		}
		if ( Newspack_Popups::previewed_popup_id() && Newspack_Popups::is_user_admin() ) {
			return '';
		}
		return 'amp-access="popups.' . esc_attr( self::canonize_popup_id( $popup['id'] ) ) . '" amp-access-hide ';
	}

	/**
	 * Get data-popup-status attribute for use in previews, if viewing as an admin.
	 *
	 * @param object $popup Popup.
	 */
	public static function get_data_status_preview_attrs( $popup ) {
		if ( ! Newspack_Popups::is_user_admin() ) {
			return '';
		}
		$status = 'future' === $popup['status'] ? __( 'scheduled', 'newspack-popups' ) : $popup['status'];
		return 'data-popup-status="' . esc_attr( $status ) . '" ';
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

		$element_id             = 'lightbox' . rand(); // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
		$endpoint               = self::get_reader_endpoint();
		$display_title          = $popup['options']['display_title'];
		$hidden_fields          = self::get_hidden_fields( $popup );
		$dismiss_text           = self::get_dismiss_text( $popup );
		$dismiss_text_alignment = self::get_dismiss_text_alignment( $popup );
		$is_newsletter_prompt   = self::has_newsletter_prompt( $popup );
		$classes                = [];
		$classes[]              = 'above_header' === $popup['options']['placement'] ? 'newspack-above-header-popup' : null;
		$classes[]              = 'inline' === $popup['options']['placement'] ? 'newspack-inline-popup' : null;
		$classes[]              = 'publish' !== $popup['status'] ? 'newspack-inactive-popup-status' : null;
		$classes[]              = ( ! empty( $popup['title'] ) && $display_title ) ? 'newspack-lightbox-has-title' : null;
		$classes[]              = $is_newsletter_prompt ? 'newspack-newsletter-prompt-inline' : null;

		$analytics_events = self::get_analytics_events( $popup, $body, $element_id );
		if ( ! empty( $analytics_events ) ) {
			add_filter(
				'newspack_analytics_events',
				function ( $evts ) use ( $analytics_events ) {
					return array_merge( $evts, $analytics_events );
				}
			);
		}

		ob_start();
		?>
			<?php self::insert_event_tracking( $popup, $body, $element_id ); ?>
			<amp-layout
				<?php echo self::get_access_attrs( $popup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo self::get_data_status_preview_attrs( $popup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
				role="button"
				tabindex="0"
				style="<?php echo esc_attr( self::container_style( $popup ) ); ?>"
				id="<?php echo esc_attr( $element_id ); ?>"
			>
						<?php if ( ! empty( $popup['title'] ) && $display_title ) : ?>
					<h1 class="newspack-popup-title"><?php echo esc_html( $popup['title'] ); ?></h1>
				<?php endif; ?>
						<?php echo ( $body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( $dismiss_text && ! Newspack_Popups_Settings::is_non_interactive() ) : ?>
					<form class="popup-not-interested-form popup-action-form align-<?php echo esc_attr( $dismiss_text_alignment ); ?>"
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
		if ( ! self::is_overlay( $popup ) ) {
			return self::generate_inline_popup( $popup );
		}

		do_action( 'newspack_campaigns_before_campaign_render', $popup );
		$blocks = parse_blocks( $popup['content'] );
		$body   = '';
		foreach ( $blocks as $block ) {
			$body .= render_block( $block );
		}
		do_action( 'newspack_campaigns_after_campaign_render', $popup );

		$element_id             = 'lightbox' . rand(); // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
		$endpoint               = self::get_reader_endpoint();
		$dismiss_text           = self::get_dismiss_text( $popup );
		$dismiss_text_alignment = self::get_dismiss_text_alignment( $popup );
		$display_title          = $popup['options']['display_title'];
		$overlay_opacity        = absint( $popup['options']['overlay_opacity'] ) / 100;
		$overlay_color          = $popup['options']['overlay_color'];
		$hidden_fields          = self::get_hidden_fields( $popup );
		$is_newsletter_prompt   = self::has_newsletter_prompt( $popup );
		$classes                = array( 'newspack-lightbox', 'newspack-lightbox-placement-' . $popup['options']['placement'] );
		$classes[]              = ( ! empty( $popup['title'] ) && $display_title ) ? 'newspack-lightbox-has-title' : null;
		$classes[]              = $is_newsletter_prompt ? 'newspack-newsletter-prompt-overlay' : null;
		$wrapper_classes        = [ 'newspack-popup-wrapper' ];
		$wrapper_classes[]      = 'publish' !== $popup['status'] ? 'newspack-inactive-popup-status' : null;
		$is_scroll_triggered    = 'scroll' === $popup['options']['trigger_type'];

		add_filter(
			'newspack_analytics_events',
			function ( $evts ) use ( $popup, $body, $element_id ) {
				return array_merge( $evts, self::get_analytics_events( $popup, $body, $element_id ) );
			}
		);

		ob_start();
		?>
		<amp-layout
			<?php echo self::get_access_attrs( $popup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo self::get_data_status_preview_attrs( $popup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			role="button"
			tabindex="0"
			id="<?php echo esc_attr( $element_id ); ?>"
		>
			<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-popup-status="<?php echo esc_attr( $popup['status'] ); ?>" style="<?php echo esc_attr( self::container_style( $popup ) ); ?>">
				<div class="newspack-popup">
					<?php if ( ! empty( $popup['title'] ) && $display_title ) : ?>
						<h1 class="newspack-popup-title"><?php echo esc_html( $popup['title'] ); ?></h1>
					<?php endif; ?>
					<?php echo ( $body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $dismiss_text ) : ?>
					<form class="popup-not-interested-form popup-action-form align-<?php echo esc_attr( $dismiss_text_alignment ); ?>"
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
					<form class="popup-dismiss-form popup-action-form align-<?php echo esc_attr( $dismiss_text_alignment ); ?>"
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
		<?php if ( $is_scroll_triggered ) : ?>
			<div id="page-position-marker" style="position: absolute; top: <?php echo esc_attr( $popup['options']['trigger_scroll_progress'] ); ?>%"></div>
			<amp-position-observer target="page-position-marker" on="enter:showAnim.start;" once layout="nodisplay"></amp-position-observer>
		<?php endif; ?>
		<amp-animation id="showAnim" layout="nodisplay" <?php echo $is_scroll_triggered ? '' : 'trigger="visibility"'; ?>>
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
	 * Endpoint to handle Pop-up data.
	 *
	 * @return string Endpoint URL.
	 */
	public static function get_reader_endpoint() {
		return plugins_url( '../api/campaigns/index.php', __FILE__ );
	}

	/**
	 * Generate hidden fields to be used in all forms.
	 *
	 * @param  object $popup A Pop-up object.
	 * @return string Hidden fields markup.
	 */
	public static function get_hidden_fields( $popup ) {
		ob_start();
		?>
		<input
			name="popup_id"
			type="hidden"
			value="<?php echo esc_attr( self::canonize_popup_id( $popup['id'] ) ); ?>"
		/>
		<input
			name="cid"
			type="hidden"
			value="CLIENT_ID(<?php echo esc_attr( Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME ); ?>)"
			data-amp-replace="CLIENT_ID"
		/>
		<input
			name="mailing_list_status"
			type="hidden"
			[value]="mailing_list_status"
		/>
		<input
			name="is_newsletter_popup"
			type="hidden"
			value="<?php echo esc_attr( self::has_newsletter_prompt( $popup ) ); ?>"
		/>
		<?php
		return ob_get_clean();
	}
}
