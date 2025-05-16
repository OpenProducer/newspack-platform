<?php
/**
 * Newspack Newsletter Ads
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Newsletters Ads Class.
 */
final class Newspack_Newsletters_Ads {

	const CPT = 'newspack_nl_ads_cpt';

	const ADVERTISER_TAX = 'newspack_nl_advertiser';

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Newsletters
	 */
	protected static $instance = null;

	/**
	 * Ads already inserted in the newsletter.
	 *
	 * @var array[] Ad ids mapped by newsletter id.
	 */
	protected static $inserted_ads = [];

	/**
	 * Main Newspack Newsletter Ads Instance.
	 * Ensures only one instance of Newspack Ads Instance is loaded or can be loaded.
	 *
	 * @return Newspack Ads Instance - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'register_ads_cpt' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'init', [ __CLASS__, 'register_newsletter_meta' ] );
		add_action( 'save_post_' . self::CPT, [ __CLASS__, 'ad_default_fields' ], 10, 3 );
		add_action( 'rest_api_init', [ __CLASS__, 'rest_api_init' ] );
		add_action( 'admin_menu', [ __CLASS__, 'add_ads_page' ] );
		add_filter( 'get_post_metadata', [ __CLASS__, 'migrate_diable_ads' ], 10, 4 );
		add_action( 'newspack_newsletters_tracking_pixel_seen', [ __CLASS__, 'track_ad_impression' ], 10, 2 );
		add_filter( 'newspack_newsletters_newsletter_content', [ __CLASS__, 'filter_newsletter_content' ], 10, 2 );

		// Columns.
		add_action( 'manage_' . self::CPT . '_posts_columns', [ __CLASS__, 'manage_columns' ] );
		add_action( 'manage_' . self::CPT . '_posts_custom_column', [ __CLASS__, 'custom_column' ], 10, 2 );
		add_action( 'manage_edit-' . self::CPT . '_sortable_columns', [ __CLASS__, 'sortable_columns' ] );
		// Sorting.
		add_action( 'pre_get_posts', [ __CLASS__, 'handle_sorting' ] );
	}

	/**
	 * API endpoints.
	 */
	public static function rest_api_init() {
		\register_rest_route(
			'wp/v2/' . self::CPT,
			'config',
			[
				'callback'            => [ __CLASS__, 'get_ads_config' ],
				'methods'             => 'GET',
				'permission_callback' => [ 'Newspack_Newsletters', 'api_authoring_permissions_check' ],
			]
		);
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'start_date',
			[
				'object_subtype' => self::CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'expiry_date',
			[
				'object_subtype' => self::CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'price',
			[
				'object_subtype' => self::CPT,
				'show_in_rest'   => true,
				'type'           => 'number',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'insertion_strategy',
			[
				'object_subtype' => self::CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
				'default'        => 'percentage',
			]
		);
		\register_meta(
			'post',
			'position_in_content',
			[
				'object_subtype' => self::CPT,
				'show_in_rest'   => true,
				'type'           => 'integer',
				'single'         => true,
				'auth_callback'  => '__return_true',
				'default'        => 0,
			]
		);
		\register_meta(
			'post',
			'position_block_count',
			[
				'object_subtype' => self::CPT,
				'show_in_rest'   => true,
				'type'           => 'integer',
				'single'         => true,
				'auth_callback'  => '__return_true',
				'default'        => 0,
			]
		);
	}

	/**
	 * Register custom fields for newsletters.
	 */
	public static function register_newsletter_meta() {
		\register_meta(
			'post',
			'disable_auto_ads',
			[
				'object_subtype' => Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => true,
				'type'           => 'boolean',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
	}

	/**
	 * Add ads page link.
	 */
	public static function add_ads_page() {
		add_submenu_page(
			'edit.php?post_type=' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
			__( 'Newsletters Ads', 'newspack-newsletters' ),
			__( 'Ads', 'newspack-newsletters' ),
			'edit_others_posts',
			'/edit.php?post_type=' . self::CPT,
			null,
			2
		);
	}

	/**
	 * Register the custom post type for layouts.
	 */
	public static function register_ads_cpt() {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}

		$labels = [
			'name'                     => _x( 'Newsletter Ads', 'post type general name', 'newspack-newsletters' ),
			'singular_name'            => _x( 'Newsletter Ad', 'post type singular name', 'newspack-newsletters' ),
			'menu_name'                => _x( 'Newsletter Ads', 'admin menu', 'newspack-newsletters' ),
			'name_admin_bar'           => _x( 'Newsletter Ad', 'add new on admin bar', 'newspack-newsletters' ),
			'add_new'                  => _x( 'Add New', 'popup', 'newspack-newsletters' ),
			'add_new_item'             => __( 'Add New Newsletter Ad', 'newspack-newsletters' ),
			'new_item'                 => __( 'New Newsletter Ad', 'newspack-newsletters' ),
			'edit_item'                => __( 'Edit Newsletter Ad', 'newspack-newsletters' ),
			'view_item'                => __( 'View Newsletter Ad', 'newspack-newsletters' ),
			'all_items'                => __( 'All Newsletter Ads', 'newspack-newsletters' ),
			'search_items'             => __( 'Search Newsletter Ads', 'newspack-newsletters' ),
			'parent_item_colon'        => __( 'Parent Newsletter Ads:', 'newspack-newsletters' ),
			'not_found'                => __( 'No Newsletter Ads found.', 'newspack-newsletters' ),
			'not_found_in_trash'       => __( 'No Newsletter Ads found in Trash.', 'newspack-newsletters' ),
			'items_list'               => __( 'Newsletter Ads list', 'newspack-newsletters' ),
			'item_published'           => __( 'Newsletter Ad published', 'newspack-newsletters' ),
			'item_published_privately' => __( 'Newsletter Ad published privately', 'newspack-newsletters' ),
			'item_reverted_to_draft'   => __( 'Newsletter Ad reverted to draft', 'newspack-newsletters' ),
			'item_scheduled'           => __( 'Newsletter Ad scheduled', 'newspack-newsletters' ),
			'item_updated'             => __( 'Newsletter Ad updated', 'newspack-newsletters' ),
		];

		$cpt_args = [
			'public'       => false,
			'labels'       => $labels,
			'show_ui'      => true,
			'show_in_menu' => false,
			'show_in_rest' => true,
			'supports'     => [ 'editor', 'title', 'custom-fields' ],
			'taxonomies'   => [ 'category' ],
		];
		register_post_type( self::CPT, $cpt_args );

		register_taxonomy(
			self::ADVERTISER_TAX,
			[ self::CPT, Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT ],
			[
				'labels'            => [
					'name'                     => __( 'Advertisers', 'newspack-newsletters' ),
					'singular_name'            => __( 'Advertiser', 'newspack-newsletters' ),
					'search_items'             => __( 'Search Advertisers', 'newspack-newsletters' ),
					'popular_items'            => __( 'Popular Advertisers', 'newspack-newsletters' ),
					'all_items'                => __( 'All Advertisers', 'newspack-newsletters' ),
					'parent_items'             => __( 'Parent Advertisers', 'newspack-newsletters' ),
					'parent_item'              => __( 'Parent Advertiser', 'newspack-newsletters' ),
					'name_field_description'   => __( 'The advertiser name', 'newspack-newsletters' ),
					'slug_field_description'   => '', // There's no advertiser URL so let's skip slug field description.
					'parent_field_description' => __( 'Assign a parent advertiser', 'newspack-newsletters' ),
					'desc_field_description'   => __( 'Optional description for this advertiser', 'newspack-newsletters' ),
					'edit_item'                => __( 'Edit Advertiser', 'newspack-newsletters' ),
					'view_item'                => __( 'View Advertiser', 'newspack-newsletters' ),
					'update_item'              => __( 'Update Advertiser', 'newspack-newsletters' ),
					'add_new_item'             => __( 'Add New Advertiser', 'newspack-newsletters' ),
					'new_item_name'            => __( 'New Advertiser Name', 'newspack-newsletters' ),
					'not_found'                => __( 'No advertisers found', 'newspack-newsletters' ),
					'no_terms'                 => __( 'No advertisers', 'newspack-newsletters' ),
					'filter_by_item'           => __( 'Filter by advertiser', 'newspack-newsletters' ),
				],
				'description'       => __( 'Newspack Newsletters Ads Advertisers', 'newspack-newsletters' ),
				'public'            => true,
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
			]
		);
	}

	/**
	 * Set default fields when Ad is created.
	 *
	 * @param int     $post_id ID of post being saved.
	 * @param WP_POST $post The post being saved.
	 * @param bool    $update True if this is an update, false if a newly created post.
	 */
	public static function ad_default_fields( $post_id, $post, $update ) {
		// Set meta only if this is a newly created post.
		if ( $update ) {
			return;
		}
		update_post_meta( $post_id, 'position_in_content', 100 );
	}

	/**
	 * Migrate 'diable_ads' meta.
	 *
	 * @param mixed  $value   The value get_metadata() should return - a single
	 *                        metadata value, or an array of values. Default null.
	 * @param int    $post_id Post ID.
	 * @param string $key     Meta key.
	 * @param bool   $single  Whether to return only the first value of the specified $key.
	 */
	public static function migrate_diable_ads( $value, $post_id, $key, $single ) {
		if ( 'disable_auto_ads' !== $key ) {
			return $value;
		}
		remove_filter( 'get_post_metadata', [ __CLASS__, 'migrate_diable_ads' ], 10, 4 );
		if ( get_post_meta( $post_id, 'diable_ads', true ) ) {
			delete_post_meta( $post_id, 'diable_ads' );
			update_post_meta( $post_id, 'disable_auto_ads', true );
			$value = true;
			if ( ! $single ) {
				$value = [ $value ];
			}
		}
		add_filter( 'get_post_metadata', [ __CLASS__, 'migrate_diable_ads' ], 10, 4 );
		return $value;
	}

	/**
	 * Whether to render ads in the newsletter
	 *
	 * @param int $post_id ID of the newsletter post.
	 */
	public static function should_render_ads( $post_id ) {
		$should_render_ads = true;

		/**
		 * Disable automated ads insertion meta.
		 */
		if ( get_post_meta( $post_id, 'disable_auto_ads', true ) ) {
			$should_render_ads = false;
		}

		/**
		 * Disable automated ads insertion if the newsletter contains a manual ad block.
		 */
		if ( has_block( 'newspack-newsletters/ad', $post_id ) ) {
			$should_render_ads = false;
		}

		/**
		 * Filters whether to render ads in the newsletter.
		 *
		 * @param bool $should_render_ads Whether to render ads in the newsletter.
		 * @param int  $post_id           ID of the newsletter post.
		 */
		return apply_filters( 'newspack_newsletters_should_render_ads', $should_render_ads, $post_id );
	}

	/**
	 * Get properties required to render a useful modal in the editor that alerts
	 * users of ads they're sending.
	 *
	 * @param WP_REST_REQUEST $request The WP Request Object.
	 * @return array
	 */
	public static function get_ads_config( $request ) {
		$letterhead                 = new Newspack_Newsletters_Letterhead();
		$has_letterhead_credentials = $letterhead->has_api_credentials();
		$post_id                    = $request->get_param( 'postId' );
		$newspack_ad_type           = self::CPT;

		$url_to_manage_promotions   = 'https://app.tryletterhead.com/promotions';
		$url_to_manage_newspack_ads = "/wp-admin/edit.php?post_type={$newspack_ad_type}";

		$ads                   = self::get_newsletter_ads( $post_id, true );
		$ads_label             = $has_letterhead_credentials ? __( 'promotion', 'newspack-newsletters' ) : __( 'ad', 'newspack-newsletters' );
		$ads_manage_url        = $has_letterhead_credentials ? $url_to_manage_promotions : $url_to_manage_newspack_ads;
		$ads_manage_url_rel    = $has_letterhead_credentials ? 'noreferrer' : '';
		$ads_manage_url_target = $has_letterhead_credentials ? '_blank' : '_self';

		return [
			'count'           => count( $ads ),
			'label'           => $ads_label,
			'manageUrl'       => $ads_manage_url,
			'manageUrlRel'    => $ads_manage_url_rel,
			'manageUrlTarget' => $ads_manage_url_target,
			'ads'             => array_map(
				function ( $ad ) {
					return [
						'id'    => $ad->ID,
						'title' => $ad->post_title,
					];
				},
				$ads
			),
		];
	}

	/**
	 * Whether the ad is active.
	 *
	 * @param int $ad_id   ID of the Ad post.
	 * @param int $post_id Optional ID of the Newsletter post to check against.
	 *
	 * @return bool
	 */
	public static function is_ad_active( $ad_id, $post_id = null ) {

		$start_date  = get_post_meta( $ad_id, 'start_date', true );
		$expiry_date = get_post_meta( $ad_id, 'expiry_date', true );

		if ( ! $start_date && ! $expiry_date ) {
			return true;
		}

		$date_format = 'Y-m-d';
		$date        = gmdate( $date_format );
		if ( $post_id ) {
			$date = get_the_date( $date_format, $post_id );
		}

		$started = true;
		if ( $start_date ) {
			$formatted_start_date = ( new DateTime( $start_date ) )->format( $date_format );
			$started              = $formatted_start_date <= $date;
		}

		$ended = false;
		if ( $expiry_date ) {
			$formatted_expiry_date = ( new DateTime( $expiry_date ) )->format( $date_format );
			$ended                 = $formatted_expiry_date < $date;
		}

		return $started && ! $ended;
	}

	/**
	 * Get available ads for a newsletter.
	 *
	 * @param int  $newsletter_id   Newsletter post ID.
	 * @param bool $skip_validation Whether to skip validation of ad categories and advertisers.
	 *
	 * @return WP_Post[] Array of ad posts.
	 */
	public static function get_newsletter_ads( $newsletter_id, $skip_validation = false ) {
		$all_ads = get_posts(
			[
				'post_type'      => self::CPT,
				'posts_per_page' => -1,
			]
		);
		$ads     = [];
		foreach ( $all_ads as $ad ) {
			// Include ad if validation is skipped.
			if ( $skip_validation ) {
				$ads[] = $ad;
				continue;
			}
			// Skip if ad is not active.
			if ( ! self::is_ad_active( $ad->ID, $newsletter_id ) ) {
				continue;
			}
			$ad_categories = wp_get_post_terms( $ad->ID, 'category' );
			// Skip if the ad is not in the same category as the post.
			if ( ! empty( $ad_categories ) ) {
				$newsletter_categories = wp_get_post_terms( $newsletter_id, 'category' );
				if ( empty( array_intersect( wp_list_pluck( $ad_categories, 'term_id' ), wp_list_pluck( $newsletter_categories, 'term_id' ) ) ) ) {
					continue;
				}
			}
			$newsletter_advertisers = wp_get_post_terms( $newsletter_id, self::ADVERTISER_TAX );
			// Skip if the post has an advertiser and the ad is not from the same advertiser.
			if ( ! empty( $newsletter_advertisers ) ) {
				$ad_advertisers = wp_get_post_terms( $ad->ID, self::ADVERTISER_TAX );
				if ( empty( array_intersect( wp_list_pluck( $newsletter_advertisers, 'term_id' ), wp_list_pluck( $ad_advertisers, 'term_id' ) ) ) ) {
					continue;
				}
			}
			$ads[] = $ad;
		}

		// Return early if there's only one ad. No need to sort.
		if ( count( $ads ) <= 1 ) {
			return $ads;
		}

		// Sort ads by position.
		$ads_by_position = [];
		foreach ( $ads as $ad ) {
			$insertion_strategy = get_post_meta( $ad->ID, 'insertion_strategy', true );
			if ( empty( $insertion_strategy ) ) {
				$insertion_strategy = 'percentage';
			}
			/**
			 * Rough position calculation for ads inserted by percentage. Should be
			 * good enough for sorting and normalizing priority against the block
			 * count strategy.
			 */
			if ( 'percentage' === $insertion_strategy ) {
				$percentage = intval( get_post_meta( $ad->ID, 'position_in_content', true ) ) / 100;
				$post       = get_post( $newsletter_id );
				$blocks     = parse_blocks( $post->post_content );
				$position   = intval( count( $blocks ) * $percentage );
			} else {
				$position = intval( get_post_meta( $ad->ID, 'position_block_count', true ) );
			}

			if ( ! isset( $ads_by_position[ $position ] ) ) {
				$ads_by_position[ $position ] = [];
			}
			$ads_by_position[ $position ][] = $ad;
		}

		$flattened_ads = [];
		foreach ( $ads_by_position as $position_ads ) {
			foreach ( $position_ads as $ad ) {
				$flattened_ads[] = $ad;
			}
		}

		sort( $flattened_ads );
		return $flattened_ads;
	}

	/**
	 * Track ad impression.
	 *
	 * @param int    $newsletter_id Newsletter ID.
	 * @param string $email_address Email address.
	 */
	public static function track_ad_impression( $newsletter_id, $email_address ) {
		$inserted_ads = get_post_meta( $newsletter_id, 'inserted_ads', true );
		if ( empty( $inserted_ads ) ) {
			return;
		}
		foreach ( $inserted_ads as $ad_id ) {
			$impressions = get_post_meta( $ad_id, 'tracking_impressions', true );
			if ( ! $impressions ) {
				$impressions = 0;
			}
			$impressions++;
			update_post_meta( $ad_id, 'tracking_impressions', $impressions );

			/**
			 * Fires when an ad impression is tracked.
			 *
			 * @param int    $ad_id         Ad ID.
			 * @param int    $newsletter_id Newsletter ID.
			 * @param string $email_address Email address.
			 */
			do_action( 'newspack_newsletters_tracking_ad_impression', $ad_id, $newsletter_id, $email_address );
		}
	}

	/**
	 * Manage ads columns.
	 *
	 * @param array $columns Columns.
	 */
	public static function manage_columns( $columns ) {
		$columns['start_date']  = __( 'Start Date', 'newspack-newsletters' );
		$columns['expiry_date'] = __( 'Expiration Date', 'newspack-newsletters' );
		$columns['price']       = __( 'Price', 'newspack-newsletters' );
		unset( $columns['date'] );
		unset( $columns['stats'] );
		return $columns;
	}

	/**
	 * Custom ads column content.
	 *
	 * @param array $column_name Column name.
	 * @param int   $post_id     Post ID.
	 */
	public static function custom_column( $column_name, $post_id ) {
		if ( 'start_date' === $column_name ) {
			$start_date = get_post_meta( $post_id, 'start_date', true );
			if ( ! empty( $start_date ) ) {
				echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $start_date ) ) );
			} else {
				echo '—';
			}
		} elseif ( 'expiry_date' === $column_name ) {
			$expiry_date = get_post_meta( $post_id, 'expiry_date', true );
			if ( ! empty( $expiry_date ) ) {
				echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $expiry_date ) ) );
			} else {
				echo '—';
			}
		} elseif ( 'price' === $column_name ) {
			$price = get_post_meta( $post_id, 'price', true );
			if ( ! empty( $price ) ) {
				echo esc_html( $price );
			} else {
				echo '—';
			}
		}
	}

	/**
	 * Sortable columns.
	 *
	 * @param array $columns Columns.
	 */
	public static function sortable_columns( $columns ) {
		$columns['start_date']  = 'start_date';
		$columns['expiry_date'] = 'expiry_date';
		$columns['price']       = 'price';
		return $columns;
	}

	/**
	 * Handle sorting.
	 *
	 * @param \WP_Query $query Query.
	 */
	public static function handle_sorting( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( self::CPT === $query->get( 'post_type' ) ) {
			$orderby = $query->get( 'orderby' );
			if ( 'price' === $orderby ) {
				$query->set( 'meta_key', 'price' );
				$query->set( 'orderby', 'meta_value_num' );
			} elseif ( 'start_date' === $orderby ) {
				$query->set( 'meta_key', 'start_date' );
				$query->set( 'orderby', 'meta_value' );
			} elseif ( 'expiry_date' === $orderby ) {
				$query->set( 'meta_key', 'expiry_date' );
				$query->set( 'orderby', 'meta_value' );
			}
		}
	}

	/**
	 * Filter newsletter content to insert automated ads.
	 *
	 * @param string  $content The newsletter content.
	 * @param WP_Post $post    The newsletter post.
	 *
	 * @return string Transformed newsletter content with ads inserted.
	 */
	public static function filter_newsletter_content( $content, $post ) {
		if ( Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT !== $post->post_type ) {
			return $content;
		}
		if ( ! self::should_render_ads( $post->ID ) ) {
			return $content;
		}
		$ads = self::get_newsletter_ads( $post->ID );
		if ( empty( $ads ) ) {
			return $content;
		}
		return self::insert_auto_ads( $post->ID, $content, $ads );
	}

	/**
	 * Some blocks should never have an ad right after them. For example, an ad right after a subheading
	 * (header block) would not look good.
	 *
	 * @param object $block A block.
	 */
	private static function can_block_be_followed_by_ad( $block ) {
		if (
			in_array(
				$block['blockName'],
				[
					// An ad may not appear right after a heading block.
					'core/heading',
				]
			) ) {
			return false;
		}
		if (
			// An ad may not appear after a floated image block, because it
			// will mess up the layout then.
			'core/image' === $block['blockName']
			&& isset( $block['attrs']['align'] )
			&& in_array( $block['attrs']['align'], [ 'left', 'right' ] )
		) {
			return false;
		}
		return true;
	}

	/**
	 * Get content from a given block's inner blocks, and recursively from those blocks' inner blocks.
	 *
	 * @param object $block A block.
	 *
	 * @return string The block's inner content.
	 */
	private static function get_inner_block_content( $block ) {
		$inner_block_content = '';

		if ( 0 < count( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $inner_block ) {
				$inner_block_content .= $inner_block['innerHTML'];

				// Recursively get content from nested inner blocks.
				if ( 0 < count( $inner_block['innerBlocks'] ) ) {
					$inner_block_content .= self::get_inner_block_content( $inner_block );
				}
			}
		}

		return $inner_block_content;
	}

	/**
	 * Get content from given block, including content from the block's inner blocks, if any.
	 *
	 * @param object $block A block.
	 *
	 * @return string The block's content.
	 */
	private static function get_block_content( $block ) {
		$is_classic_block = null === $block['blockName'] || 'core/freeform' === $block['blockName']; // Classic block doesn't have a block name.
		$block_content    = $is_classic_block ? force_balance_tags( wpautop( $block['innerHTML'] ) ) : $block['innerHTML'];
		$block_content   .= self::get_inner_block_content( $block );

		return $block_content;
	}

	/**
	 * Whether the ad was inserted
	 *
	 * @param int $newsletter_id Newsletter ID.
	 * @param int $ad_id         Ad ID.
	 *
	 * @return boolean
	 */
	public static function is_ad_inserted( $newsletter_id, $ad_id ) {
		return ! empty( self::$inserted_ads[ $newsletter_id ] ) && in_array( $ad_id, self::$inserted_ads[ $newsletter_id ], true );
	}

	/**
	 * Mark the ad as inserted
	 *
	 * @param int $newsletter_id Newsletter ID.
	 * @param int $ad_id         Ad ID.
	 */
	public static function mark_ad_inserted( $newsletter_id, $ad_id ) {
		if ( empty( self::$inserted_ads[ $newsletter_id ] ) ) {
			self::$inserted_ads[ $newsletter_id ] = [];
		}
		// Avoid duplicate.
		if ( in_array( $ad_id, self::$inserted_ads[ $newsletter_id ], true ) ) {
			return;
		}
		self::$inserted_ads[ $newsletter_id ][] = $ad_id;
		update_post_meta( $newsletter_id, 'inserted_ads', self::$inserted_ads[ $newsletter_id ] );
	}

	/**
	 * Insert ads in newsletter content.
	 *
	 * @param int       $newsletter_id The newsletter post ID.
	 * @param string    $content       The newsletter content.
	 * @param WP_Post[] $ads           Array of ad posts.
	 *
	 * @return string Transformed newsletter content with ads inserted.
	 */
	private static function insert_auto_ads( $newsletter_id, $content, $ads ) {
		if ( empty( $ads ) ) {
			return $content;
		}

		$parsed_blocks = parse_blocks( $content );

		// List of blocks that require innerHTML to render content.
		$blocks_to_skip_empty = [
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/quote',
			'core/html',
		];
		$parsed_blocks        = array_values( // array_values will reindex the array.
			// Filter out empty blocks.
			array_filter(
				$parsed_blocks,
				function ( $block ) use ( $blocks_to_skip_empty ) {
					$null_block_name     = null === $block['blockName'];
					$is_skip_empty_block = in_array( $block['blockName'], $blocks_to_skip_empty, true );
					$is_empty            = empty( trim( $block['innerHTML'] ) );
					return ! ( $is_empty && ( $null_block_name || $is_skip_empty_block ) );
				}
			)
		);

		$block_index            = 0;
		$grouped_blocks_indexes = [];
		$max_index              = count( $parsed_blocks );

		$parsed_blocks_groups = array_reduce(
			$parsed_blocks,
			function ( $block_groups, $block ) use ( &$block_index, $parsed_blocks, $max_index, &$grouped_blocks_indexes ) {
				$next_index = $block_index;

				// If we've already included this block in a previous group, bail early to avoid content duplication.
				if ( in_array( $next_index, $grouped_blocks_indexes, true ) ) {
					$block_index++;
					return $block_groups;
				}

				// Create a group of blocks that can be followed by an ad.
				$next_block     = $block;
				$group_blocks   = [];
				$index_in_group = 0;

				// Insert any following blocks, which can't be followed by an ad.
				while ( $next_index < $max_index && ! self::can_block_be_followed_by_ad( $next_block ) ) {
					$next_block               = $parsed_blocks[ $next_index ];
					$group_blocks[]           = $next_block;
					$grouped_blocks_indexes[] = $next_index;
					$next_index++;
					$index_in_group++;
				}
				// Always insert the initial block in the group (if the index in group was not incremented, this is the initial block).
				if ( 0 === $index_in_group ) {
					$group_blocks[]           = $next_block;
					$grouped_blocks_indexes[] = $next_index;
				}

				$block_groups[] = $group_blocks;

				$block_index++;
				return $block_groups;
			},
			[]
		);

		$total_length = 0;
		// Compute the total length of the content.
		foreach ( $parsed_blocks as $block ) {
			$block_content = self::get_block_content( $block );
			$total_length += strlen( wp_strip_all_tags( $block_content ) );
		}

		// Prepare ads configuration for insertion.
		$ads_config = [];
		foreach ( $ads as $ad ) {
			$insertion_strategy = get_post_meta( $ad->ID, 'insertion_strategy', true );
			// Default insertion strategy is percentage.
			if ( empty( $insertion_strategy ) ) {
				$insertion_strategy = 'percentage';
			}
			$percentage   = intval( get_post_meta( $ad->ID, 'position_in_content', true ) ) / 100;
			$block_count  = intval( get_post_meta( $ad->ID, 'position_block_count', true ) );
			$ads_config[] = [
				'id'                 => $ad->ID,
				'insertion_strategy' => $insertion_strategy,
				'precise_position'   => 'percentage' === $insertion_strategy ? $percentage * $total_length : $block_count,
				'is_inserted'        => false,
			];
		}

		// Iterate over all blocks and insert configured ads.
		$pos    = 0;
		$output = '';

		foreach ( $parsed_blocks_groups as $block_index => $block_group ) {
			// Compute the length of the blocks in the group.
			foreach ( $block_group as $block ) {
				$pos += strlen( wp_strip_all_tags( self::get_block_content( $block ) ) );
			}

			// Inject ads before the group.
			foreach ( $ads_config as &$ad_config ) {
				if ( self::is_ad_inserted( $newsletter_id, $ad_config['id'] ) ) {
					// Skip if already inserted.
					continue;
				}

				$position           = $ad_config['precise_position'];
				$insertion_strategy = $ad_config['insertion_strategy'];
				$insert_at_zero     = 0 === $position; // If the position is 0, the ad should always appear first.
				$insert_for_scroll  = 'block_count' !== $insertion_strategy && $pos > $position;
				$insert_for_blocks  = 'block_count' === $insertion_strategy && $block_index >= $position;

				if ( $insert_at_zero || $insert_for_scroll || $insert_for_blocks ) {
					$output .= '<!-- wp:newspack-newsletters/ad {"adId":"' . $ad_config['id'] . '"} /-->';
					self::mark_ad_inserted( $newsletter_id, $ad_config['id'] );
				}
			}

			// Render blocks from the block group.
			foreach ( $block_group as $block ) {
				$output .= serialize_block( $block );
			}
		}

		// Insert any remaining ads at the end.
		foreach ( $ads_config as &$ad_config ) {
			if ( ! self::is_ad_inserted( $newsletter_id, $ad_config['id'] ) ) {
				$output .= '<!-- wp:newspack-newsletters/ad {"adId":"' . $ad_config['id'] . '"} /-->';
				self::mark_ad_inserted( $newsletter_id, $ad_config['id'] );
			}
		}

		return $output;
	}
}
Newspack_Newsletters_Ads::instance();
