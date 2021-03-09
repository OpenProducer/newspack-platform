<?php
/**
 * Plugin Name: Newspack Supporters
 * Description: Manage and display your site's supporters.
 * Version: 1.0.0
 * Author: Automattic
 * Author URI: https://newspack.blog/
 * License: GPL2
 * Text Domain: newspack-supporters
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manages the whole show.
 */
class Newspack_Supporters {

	/**
	 * @var string The post type of a Supporter.
	 */
	const POST_TYPE = 'newspack_supporter';

	/**
	 * @var string The meta key for the URL info.
	 */
	const URL_META = 'newspack_supporter_url';

	/**
	 * @var string The taxonomy of a Supporter Type.
	 */
	const SUPPORTER_TYPE_TAX = 'newspack_supporter_type';

	/**
	 * Initialize everything.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register' ] );
		add_action( 'init', [ __CLASS__, 'add_supporters_shortcode' ] );
		add_action( 'add_meta_boxes', [ __CLASS__, 'add_meta_boxes' ] );
		add_action( 'save_post', [ __CLASS__, 'save_meta_boxes' ] );
	}

	/**
	 * Register post type and taxonomy.
	 */
	public static function register() {
		register_post_type( 
			self::POST_TYPE,
			[
				'labels'              => [
					'name'             => __( 'Supporters', 'newspack-supporters' ),
					'singular_name'    => __( 'Supporter', 'newspack-supporters' ),
					'edit_item'        => __( 'Edit Supporter', 'newspack-supporters' ),
					'new_item'         => __( 'Add Supporter', 'newspack-supporters' ),
					'add_new_item'     => __( 'Add new Supporter', 'newspack-supporters' ),
					'view_item'        => __( 'View Supporter', 'newspack-supporters' ),
					'view_items'       => __( 'View Supporters', 'newspack-supporters' ),
					'insert_into_item' => __( 'Insert into Supporter', 'newspack-supporters' ),
					'item_published'   => __( 'Supporter published', 'newspack-supporters' ),
					'item_updated'     => __( 'Supporter updated', 'newspack-supporters' ),
				],
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'show_in_rest'        => false,
				'supports'            => [ 'title', 'thumbnail' ],
			]
		);

		register_taxonomy(
			self::SUPPORTER_TYPE_TAX,
			self::POST_TYPE,
			array(
				'hierarchical' => false,
				'labels'       => array(
					'name'                       => _x( 'Supporter Types', 'taxonomy general name' ),
					'singular_name'              => _x( 'Supporter Type', 'taxonomy singular name' ),
					'search_items'               => __( 'Search Supporter Types', 'newspack-supporters' ),
					'all_items'                  => __( 'All Supporter Types', 'newspack-supporters' ),
					'parent_item'                => __( 'Parent Supporter Type', 'newspack-supporters' ),
					'parent_item_colon'          => __( 'Parent Supporter Type:', 'newspack-supporters' ),
					'edit_item'                  => __( 'Edit Supporter Type', 'newspack-supporters' ),
					'view_item'                  => __( 'View Supporter Type', 'newspack-supporters' ),
					'update_item'                => __( 'Update Supporter Type', 'newspack-supporters' ),
					'add_new_item'               => __( 'Add New Supporter Type', 'newspack-supporters' ),
					'new_item_name'              => __( 'New Supporter Type Name', 'newspack-supporters' ),
					'menu_name'                  => __( 'Supporter Types', 'newspack-supporters' ),
					'search_items'               => __( 'Search Supporter Types', 'newspack-supporters' ),
					'choose_from_most_used'      => __( 'Choose from most used Supporter Types', 'newspack-supporters' ),
					'separate_items_with_commas' => __( 'Separate Supporter Types with commas', 'newspack-supporters' ),
					'not_found'                  => __( 'No Supporter Types found', 'newspack-supporters' ),
				),
				'public'            => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => false,
				'query_var'         => false,
				'show_in_rest'      => false,
			)
		);
	}

	/**
	 * Add the URL meta box.
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'newspack_supporter_url',
			__( 'Supporter URL', 'newspack-supporters' ),
			[ __CLASS__, 'render_url_metabox' ],
			self::POST_TYPE
		);
	}

	/**
	 * Render the URL meta box.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function render_url_metabox( $post ) {
		$url = get_post_meta( $post->ID, self::URL_META, true );
		?><input type='url' name='<?php echo self::URL_META; ?>' value='<?php echo $url ? esc_url( $url ) : ""; ?>' /><?php
	}

	/**
	 * Save the URL meta box.
	 *
	 * @param int $post_id The post ID.
	 */
	public static function save_meta_boxes( $post_id ) {
		if ( self::POST_TYPE !== get_post_type() || ! isset( $_POST[ self::URL_META ] ) ) {
			return;
		}

		$url = filter_input( INPUT_POST, self::URL_META, FILTER_SANITIZE_URL );
		update_post_meta( $post_id, self::URL_META, esc_url_raw( $url ) );
	}

	/**
	 * Register the 'supporters' shortcode.
	 */
	public static function add_supporters_shortcode() {
		add_shortcode( 'supporters', [ __CLASS__, 'render_supporters_shortcode' ] );
	}

	/**
	 * Process the 'supporters' shortcode.
	 *
	 * @param array $atts The shortcode supports a 'type' param for limiting to a specific Supporter Type.
	 * @return string HTML shortcode output.
	 */
	public static function render_supporters_shortcode( $atts ) {
		$type        = ! empty( $atts['type'] ) ? sanitize_text_field( $atts['type'] ) : '';
		$num_columns = ! empty( $atts['columns'] ) ? intval( $atts['columns'] ) : 3;
		$show_links  = isset( $atts['show_links'] ) && 'false' === strtolower( $atts['show_links'] ) ? false : true;

		$query_args = [
			'post_type'      => self::POST_TYPE,
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		];

		if ( $type ) {
			$query_args['tax_query'] = [
				[
					'taxonomy' => self::SUPPORTER_TYPE_TAX,
					'field'    => 'slug',
					'terms'    => $type
				]
			];
		}

		$supporters = get_posts( $query_args );
		if ( empty( $supporters ) ) {
			return '';
		}

		ob_start();

		?>
		<style>
			@media only screen and (min-width: 600px) {
				.newspack-supporter-info {
					display: flex;
					flex-direction: column;
					justify-content: center;
				}
			}

			.wp-block-columns .newspack-supporter-info > a {
				margin-bottom: 0;
			}

			.wp-block-columns .newspack-supporter-info > p {
				margin-top: 0;
				font-size: .75em;
			}

			.wp-block-image.supporter-image img {
				max-height: 120px;
				width: auto;
			}
		</style>
		<?php

		$elements = [];
		foreach ( $supporters as $supporter ) {
			$supporter_html = '';
			$supporter_logo = get_post_thumbnail_id( $supporter->ID );
			$supporter_url  = get_post_meta( $supporter->ID, self::URL_META, true );

			$supporter_html .= '';
			if ( $supporter_logo ) {
				$logo_html = '';
		 		$logo_atts = wp_get_attachment_image_src( $supporter_logo, 'full' );
		 		if ( $logo_atts ) {
		 			$logo_html = '<figure class="wp-block-image supporter-image"><img class="aligncenter" src="' . esc_attr( $logo_atts[0] ) . '" /></figure>';
		 		}

		 		if ( $logo_html && $supporter_url ) {
		 			$logo_html = '<a href="' . esc_url( $supporter_url ) . '">' . $logo_html . '</a>';
		 		}

		 		$supporter_html .= $logo_html;
			}

			if ( $show_links ) {
				$supporter_name = $supporter->post_title;
				if ( $supporter_url ) {
					$supporter_name = '<a href="' . esc_url( $supporter_url ) . '">' . $supporter_name . '</a>';
				}
				$supporter_html .= '<p class="has-text-align-center">' . $supporter_name . '</p>';
			}

			if ( ! empty( $supporter_html ) ) {
				$elements[] = $supporter_html;
			}
		}

		$current          = 0;
		$container_closed = true;
		foreach ( $elements as $element ) {
			if ( 0 == $current ) {
				echo '<div class="wp-block-columns is-style-borders">';
				$container_closed = false;
			}

			echo '<div class="wp-block-column newspack-supporter-info">';
			echo wp_kses_post( $element );
			echo '</div>';

			++$current;

			if ( $num_columns == $current ) {
				echo '</div><hr class="wp-block-separator is-style-wide">';
				$current          = 0;
				$container_closed = true;
			}
		}

		// Close last div if needed.
		if ( ! $container_closed ) {
			while ( $num_columns !== $current ) {
				echo '<div class="wp-block-column newspack-supporter-info"></div>';
				++$current;
			}

			echo '</div><hr class="wp-block-separator is-style-wide">';
		}

		return ob_get_clean();
	}
}
Newspack_Supporters::init();