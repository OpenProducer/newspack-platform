<?php
/**
 * Collections Template Helper.
 *
 * @package Newspack\Collections
 */

namespace Newspack\Collections;

defined( 'ABSPATH' ) || exit;

/**
 * Template helper class for Collections rendering and hooks.
 */
class Template_Helper {

	/**
	 * The directory where the collection template parts are located.
	 *
	 * @var string
	 */
	public const TEMPLATE_PARTS_DIR = 'collections/parts/';

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_filter( 'template_include', [ __CLASS__, 'template_include' ] );
		add_action( 'get_template_part', [ __CLASS__, 'load_template_part' ], 10, 4 );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ], 5 );
		add_action( 'pre_get_posts', [ __CLASS__, 'archive_filters' ] );
		add_filter( 'redirect_canonical', [ __CLASS__, 'prevent_year_redirect' ] );
		add_filter( 'jetpack_relatedposts_filter_enabled_for_request', [ __CLASS__, 'disable_jetpack_related_posts' ] );
		add_filter( 'document_title_parts', [ __CLASS__, 'update_document_title' ] );
	}

	/**
	 * Override template loading for Collections pages to use plugin templates as fallback.
	 * Follows WordPress template hierarchy - theme templates take precedence.
	 *
	 * @param string $template The path of the template to include.
	 * @return string The path of the template to include.
	 */
	public static function template_include( $template ) {
		// Determine the template name based on the post type.
		$template_name = match ( true ) {
			is_post_type_archive( Post_Type::get_post_type() ) => 'archive-newspack-collection.php',
			is_singular( Post_Type::get_post_type() )          => 'single-newspack-collection.php',
			default                                            => '',
		};

		if ( $template_name ) {
			$resolved_template = self::resolve_template( $template_name );
			$template          = $resolved_template ? $resolved_template : $template;
		}

		/**
		 * Filters the template path for Collections pages.
		 *
		 * @param string $template      The template path.
		 * @param string $template_name The name of the template file.
		 */
		return apply_filters( 'newspack_collections_template_include', $template, $template_name );
	}

	/**
	 * Resolve template by checking theme and plugin locations.
	 *
	 * @param string $template_name The name of the template file.
	 * @return string The resolved template path or empty string.
	 */
	private static function resolve_template( $template_name ) {
		$theme_template = locate_template( $template_name );
		if ( $theme_template ) {
			return $theme_template;
		}

		$plugin_template = plugin_dir_path( __DIR__ ) . 'templates/collections/' . $template_name;

		return file_exists( $plugin_template ) ? $plugin_template : '';
	}

	/**
	 * Override template part loading for Collections pages to use plugin templates as fallback.
	 * Follows WordPress template hierarchy - theme template parts take precedence.
	 *
	 * @param string   $slug      The slug name for the generic template.
	 * @param string   $name      The name of the specialized template or an empty string if there is none.
	 * @param string[] $templates Array of template names.
	 * @param array    $args      Additional arguments passed to the template.
	 */
	public static function load_template_part( $slug, $name, $templates, $args ) {
		if ( ! str_starts_with( $slug, self::TEMPLATE_PARTS_DIR ) ) {
			return;
		}

		// Build the template file path.
		$template_file = ( $name ? "{$slug}-{$name}" : $slug ) . '.php';

		// Look in theme first.
		$theme_template = locate_template( $template_file );
		if ( $theme_template ) {
			return;
		}

		/**
		 * Filters the fallback plugin template path before attempting to load it.
		 *
		 * @param string $plugin_template Path to the plugin template.
		 * @param string $template_file   Relative template file name.
		 * @param string $slug            The slug name for the generic template.
		 * @param string $name            The name of the specialized template or an empty string if there is none.
		 * @param array  $args            Additional arguments passed to the template.
		 */
		$plugin_template = apply_filters(
			'newspack_collections_plugin_template_part',
			plugin_dir_path( __DIR__ ) . "templates/{$template_file}",
			$template_file,
			$slug,
			$name,
			$args
		);

		if ( file_exists( $plugin_template ) ) {
			load_template( $plugin_template, false, $args );
		}
	}

	/**
	 * Trigger frontend asset loading for Collections pages.
	 */
	public static function enqueue_assets() {
		if (
			is_post_type_archive( Post_Type::get_post_type() ) ||
			is_singular( Post_Type::get_post_type() )
		) {
			Enqueuer::add_data( 'is_collection', true ); // Any data will trigger the enqueuing.
		}
	}

	/**
	 * Modify Collections archive query to apply filters.
	 *
	 * @param WP_Query $query The WP_Query instance.
	 */
	public static function archive_filters( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! is_post_type_archive( Post_Type::get_post_type() ) ) {
			return;
		}

		// Set posts per page.
		$posts_per_page = Settings::get_setting( 'posts_per_page' );
		$query->set( 'posts_per_page', $posts_per_page );

		// Handle category filtering.
		$category = isset( $_GET['np_collections_category'] ) ? sanitize_text_field( $_GET['np_collections_category'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $category ) ) {
			$tax_query = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				[
					'taxonomy' => Collection_Category_Taxonomy::get_taxonomy(),
					'field'    => 'slug',
					'terms'    => $category,
				],
			];
			$query->set( 'tax_query', $tax_query );
		}

		// Handle year filtering.
		$year = isset( $_GET['np_collections_year'] ) ? sanitize_text_field( $_GET['np_collections_year'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $year ) ) {
			$query->set(
				'date_query',
				[
					[
						'year' => intval( $year ),
					],
				]
			);
		}
	}

	/**
	 * Prevent year archive redirects on collection archive pages.
	 *
	 * @param string $redirect_url The redirect URL.
	 * @return string|false The redirect URL or false to prevent redirect.
	 */
	public static function prevent_year_redirect( $redirect_url ) {
		// Check if we're on a collection archive page with year parameter.
		if ( is_post_type_archive( Post_Type::get_post_type() ) && isset( $_GET['year'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return false;
		}

		return $redirect_url;
	}

	/**
	 * Disable Jetpack related posts for collections.
	 *
	 * @param bool $enabled Should Related Posts be enabled on the current page.
	 * @return bool Whether Related Posts should be enabled on the current page.
	 */
	public static function disable_jetpack_related_posts( $enabled ) {
		if ( is_singular( Post_Type::get_post_type() ) ) {
			return false;
		}

		return $enabled;
	}

	/**
	 * Render a collection image.
	 *
	 * @param int|WP_Post  $post      The post object or ID.
	 * @param bool|string  $permalink Whether to wrap the image in a permalink. If a string, it will be used as the URL. Otherwise, the permalink will be generated from the post.
	 * @param string|int[] $size      Optional. Image size. Accepts any registered image size name, or an array of width and height values in pixels (in that order). Default 'post-thumbnail'.
	 * @param string|array $attr      Optional. Query string or array of attributes to add to the image. Default empty.
	 * @return string The rendered image HTML.
	 */
	public static function render_image( $post, $permalink = true, $size = 'post-thumbnail', $attr = '' ) {
		$image = has_post_thumbnail( $post )
			? get_the_post_thumbnail( $post, $size, $attr )
			: '<div class="collection-placeholder has-light-gray-background-color" aria-hidden="true"></div>';

		if ( $permalink ) {
			$image = sprintf(
				'<a href="%s">%s</a>',
				is_string( $permalink ) ? $permalink : esc_url( get_permalink( $post ) ),
				$image
			);
		}

		$html = sprintf( '<figure class="collection-image">%s</figure>', $image );

		/**
		 * Filter the rendered collection image HTML.
		 *
		 * @param string       $html      The complete image HTML.
		 * @param int|WP_Post  $post      The post.
		 * @param bool         $permalink Whether the image is wrapped in a permalink.
		 * @param string|int[] $size      The image size.
		 * @param string|array $attr      The image attributes.
		 */
		return apply_filters( 'newspack_collections_render_image', $html, $post, $permalink, $size, $attr );
	}

	/**
	 * Get formatted collection metadata text.
	 *
	 * @param int|WP_Post $post  Post ID or post object.
	 * @param int         $lines Number of lines to output (1 = inline, 2 = stacked). Default 2.
	 * @return string Formatted metadata text.
	 */
	public static function render_meta_text( $post, $lines = 2 ) {
		$post_id    = $post instanceof \WP_Post ? $post->ID : $post;
		$meta_parts = [];
		$volume     = Collection_Meta::get( $post_id, 'volume' );
		$number     = Collection_Meta::get( $post_id, 'number' );
		$period     = Collection_Meta::get( $post_id, 'period' );

		if ( $period ) {
			$meta_parts[] = esc_html( $period );
		}

		$vol_number = [];

		if ( $volume ) {
			/* translators: %s is the volume number of a collection */
			$vol_number[] = sprintf( _x( 'Vol. %s', 'collection volume number', 'newspack-plugin' ), esc_html( $volume ) );
		}

		if ( $number ) {
			/* translators: %s is the issue number of a collection */
			$vol_number[] = sprintf( _x( 'No. %s', 'collection issue number', 'newspack-plugin' ), esc_html( $number ) );
		}

		if ( $vol_number ) {
			$meta_parts[] = implode( ' / ', $vol_number );
		}

		$separator = 1 === $lines ? ' / ' : '<br>';
		$meta_text = implode( $separator, $meta_parts );

		/**
		 * Filters the meta text for a collection.
		 *
		 * @param string $meta_text The meta text.
		 * @param int    $post_id   Post ID.
		 * @param int    $lines     Number of lines to output.
		 */
		$meta_text = apply_filters( 'newspack_collections_render_meta_text', $meta_text, $post_id, $lines );

		if ( ! empty( $meta_text ) ) {
			return sprintf(
				'<p class="has-medium-gray-color has-text-color has-link-color has-small-font-size">%s</p>',
				wp_kses_post( $meta_text )
			);
		}

		return '';
	}

	/**
	 * Render a CTA button.
	 *
	 * @param array $cta {
	 *     The CTA data.
	 *
	 *     @type string $url   The URL of the CTA.
	 *     @type string $label The label of the CTA.
	 *     @type string $class The class of the CTA.
	 * }
	 * @return string The rendered CTA button.
	 */
	public static function render_cta( $cta ) {
		/**
		 * Filters the CTA button data before rendering.
		 *
		 * @param array $cta {
		 *     The CTA data.
		 *
		 *     @type string $url   The URL of the CTA.
		 *     @type string $label The label of the CTA.
		 *     @type string $class The class of the CTA.
		 * }
		 */
		$cta = apply_filters( 'newspack_collections_render_cta', $cta );

		if ( ! $cta || ! $cta['url'] || ! $cta['label'] ) {
			return '';
		}

		$html = sprintf(
			'<div class="collection-cta %1$s">
				<a class="wp-block-button__link has-dark-gray-color has-light-gray-background-color has-text-color has-background has-link-color wp-element-button" href="%2$s">%3$s</a>
			</div>',
			esc_attr( $cta['class'] ?? '' ),
			esc_url( $cta['url'] ?? '' ),
			esc_html( $cta['label'] ?? '' )
		);

		/**
		 * Filters the CTA button HTML.
		 *
		 * @param string $html The CTA button HTML.
		 * @param array  $cta  The CTA data.
		 */
		return apply_filters( 'newspack_collections_cta_html', $html, $cta );
	}

	/**
	 * Render articles block.
	 * Reuses the Content Loop block.
	 *
	 * @uses newspack-blocks/homepage-articles Content Loop block from newspack-blocks plugin.
	 *
	 * @param array  $post_ids       Array of post IDs.
	 * @param string $section_header The header of the section.
	 * @param bool   $show_image     Whether to show the image.
	 * @param int    $columns        The number of columns.
	 * @param int    $type_scale     The type scale.
	 * @return string The rendered articles block.
	 */
	public static function render_articles( $post_ids, $section_header = '', $show_image = true, $columns = 2, $type_scale = 3 ) {
		if ( empty( $post_ids ) ) {
			return '';
		}

		$block_name = 'newspack-blocks/homepage-articles';

		// Check if the required block is registered.
		if ( ! \WP_Block_Type_Registry::get_instance()->is_registered( $block_name ) ) {
			// Surface dependency for logged-in editors.
			return ( current_user_can( 'edit_posts' ) )
				/* translators: %s is the block name */
				? sprintf( esc_html__( 'The %s block is required but not available. Please install and activate the Newspack Blocks plugin.', 'newspack-plugin' ), '<code>' . esc_html( $block_name ) . '</code>' )
				: '';
		}

		$attrs = [
			'sectionHeader' => $section_header,
			'showImage'     => $show_image,
			'className'     => 'is-style-default',
			'showDate'      => false,
			'showAvatar'    => false,
			'postLayout'    => 'grid',
			'columns'       => $columns,
			'mediaPosition' => 'left',
			'specificPosts' => $post_ids,
			'typeScale'     => $type_scale,
			'imageScale'    => 1,
			'specificMode'  => true,
		];

		// Override with global settings if set.
		$global_attrs = Settings::get_setting( 'articles_block_attrs', [] );
		if ( is_array( $global_attrs ) && ! empty( $global_attrs ) ) {
			$attrs = array_merge( $attrs, $global_attrs );
		}

		/**
		 * Filters the attributes before rendering the content loop block for posts in a section.
		 *
		 * @param array $attrs The attributes for the articles block.
		 */
		$attrs = apply_filters( 'newspack_collections_render_articles_attrs', $attrs );

		return render_block(
			[
				'blockName' => $block_name,
				'attrs'     => $attrs,
			]
		);
	}

	/**
	 * Render a see all Collections link.
	 *
	 * @return string The rendered see all link.
	 */
	public static function render_see_all_link() {
		$link       = get_post_type_archive_link( Post_Type::get_post_type() );
		$label      = _x( 'See all', 'see all collections link', 'newspack-plugin' );
		$aria_label = sprintf(
			/* translators: %s is the collection name (e.g., "Collections", "Issues") */
			_x( 'See all %s', 'see all collections link aria-label', 'newspack-plugin' ),
			strtolower( Settings::get_collection_label() )
		);

		$html = sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			esc_url( $link ),
			esc_attr( $aria_label ),
			esc_html( $label )
		);

		/**
		 * Filters the see all link HTML.
		 *
		 * @param string $html The see all link HTML.
		 */
		return apply_filters( 'newspack_collections_see_all_link_html', $html );
	}

	/**
	 * Render a separator.
	 *
	 * @param string $class_name Optional class for the separator.
	 * @return string The separator HTML.
	 */
	public static function render_separator( $class_name = '' ) {
		return '<hr class="has-light-gray-background-color has-background is-style-wide ' . esc_attr( $class_name ) . '"/>';
	}

	/**
	 * Filter document title for collections pages to use custom label.
	 *
	 * @param array $title_parts The document title parts.
	 * @return array Modified title parts.
	 */
	public static function update_document_title( $title_parts ) {
		if ( is_post_type_archive( Post_Type::get_post_type() ) ) {
			$title_parts['title'] = Settings::get_collection_label();
		}

		return $title_parts;
	}

	/**
	 * Determine whether to show featured image for cover stories.
	 * Can be overridden by collection-specific setting.
	 *
	 * @param int $collection_id The collection post ID.
	 * @return bool Whether to show the featured image for cover stories.
	 */
	public static function should_show_cover_story_image( $collection_id ) {
		$show_image = match ( Collection_Meta::get( $collection_id, 'cover_story_img_visibility' ) ) {
			'show' => true,
			'hide' => false,
			default => (bool) Settings::get_setting( 'show_cover_story_img', false ), // Fallback to global setting.
		};

		/**
		 * Filters whether to show the featured image for cover stories.
		 *
		 * @param bool $show_image    Whether to show the featured image for cover stories.
		 * @param int  $collection_id The collection post ID.
		 */
		return apply_filters( 'newspack_should_show_cover_story_image', $show_image, $collection_id );
	}
}
