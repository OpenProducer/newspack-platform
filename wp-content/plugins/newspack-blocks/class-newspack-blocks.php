<?php
/**
 * Newspack blocks functionality
 *
 * @package Newspack_Blocks
 */

/**
 * Newspack blocks functionality
 */
class Newspack_Blocks {

	/**
	 * Add hooks and filters.
	 */
	public static function init() {
		add_action( 'after_setup_theme', [ __CLASS__, 'add_image_sizes' ] );
		add_post_type_support( 'post', 'newspack_blocks' );
		add_post_type_support( 'page', 'newspack_blocks' );
	}

	/**
	 * Gather dependencies and paths needed for script enqueuing.
	 *
	 * @param string $script_path Path to the script relative to plugin root.
	 *
	 * @return array Associative array including dependency array, version, and web path to the script. Returns false if script doesn't exist.
	 */
	public static function script_enqueue_helper( $script_path ) {
		$local_path = NEWSPACK_BLOCKS__PLUGIN_DIR . $script_path;
		if ( ! file_exists( $local_path ) ) {
			return false;
		}

		$path_info   = pathinfo( $local_path );
		$asset_path  = $path_info['dirname'] . '/' . $path_info['filename'] . '.asset.php';
		$script_data = file_exists( $asset_path )
			? require $asset_path
			: array(
				'dependencies' => array(),
				'version'      => filemtime( $local_path ),
			);

		$script_data['script_path'] = plugins_url( $script_path, __FILE__ );
		return $script_data;
	}

	/**
	 * Enqueue block scripts and styles for editor.
	 */
	public static function enqueue_block_editor_assets() {
		$script_data = static::script_enqueue_helper( NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . 'editor.js' );

		if ( $script_data ) {
			wp_enqueue_script(
				'newspack-blocks-editor',
				$script_data['script_path'],
				$script_data['dependencies'],
				$script_data['version'],
				true
			);
			wp_localize_script(
				'newspack-blocks-editor',
				'newspack_blocks_data',
				[
					'patterns'                => self::get_patterns_for_post_type( get_post_type() ),
					'posts_rest_url'          => rest_url( 'newspack-blocks/v1/newspack-blocks-posts' ),
					'specific_posts_rest_url' => rest_url( 'newspack-blocks/v1/newspack-blocks-specific-posts' ),
				]
			);

			wp_set_script_translations(
				'newspack-blocks-editor',
				'newspack-blocks',
				plugin_dir_path( __FILE__ ) . 'languages'
			);
		}

		$editor_style = plugins_url( NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . 'editor.css', __FILE__ );

		wp_enqueue_style(
			'newspack-blocks-editor',
			$editor_style,
			array(),
			NEWSPACK_BLOCKS__VERSION
		);
	}

	/**
	 * Enqueue block scripts and styles for view.
	 */
	public static function manage_view_scripts() {
		if ( is_admin() ) {
			// In editor environment, do nothing.
			return;
		}
		$src_directory  = NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/blocks/';
		$dist_directory = NEWSPACK_BLOCKS__PLUGIN_DIR . 'dist/';
		$iterator       = new DirectoryIterator( $src_directory );
		foreach ( $iterator as $block_directory ) {
			if ( ! $block_directory->isDir() || $block_directory->isDot() ) {
				continue;
			}
			$type = $block_directory->getFilename();

			/* If view.php is found, include it and use for block rendering. */
			$view_php_path = $src_directory . $type . '/view.php';
			if ( file_exists( $view_php_path ) ) {
				include_once $view_php_path;
				continue;
			}

			/* If view.php is missing but view Javascript file is found, do generic view asset loading. */
			$view_js_path = $dist_directory . $type . '/view.js';
			if ( file_exists( $view_js_path ) ) {
				register_block_type(
					"newspack-blocks/{$type}",
					array(
						'render_callback' => function( $attributes, $content ) use ( $type ) {
							Newspack_Blocks::enqueue_view_assets( $type );
							return $content;
						},
					)
				);
			}
		}
	}

	/**
	 * Enqueue block styles stylesheet.
	 */
	public static function enqueue_block_styles_assets() {
		$style_path = NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . 'block_styles' . ( is_rtl() ? '.rtl' : '' ) . '.css';
		if ( file_exists( NEWSPACK_BLOCKS__PLUGIN_DIR . $style_path ) ) {
			wp_enqueue_style(
				'newspack-blocks-block-styles-stylesheet',
				plugins_url( $style_path, __FILE__ ),
				array(),
				NEWSPACK_BLOCKS__VERSION
			);
		}
	}

	/**
	 * Enqueue view scripts and styles for a single block.
	 *
	 * @param string $type The block's type.
	 */
	public static function enqueue_view_assets( $type ) {
		$style_path = apply_filters(
			'newspack_blocks_enqueue_view_assets',
			NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . $type . '/view' . ( is_rtl() ? '.rtl' : '' ) . '.css',
			$type,
			is_rtl()
		);

		if ( file_exists( NEWSPACK_BLOCKS__PLUGIN_DIR . $style_path ) ) {
			wp_enqueue_style(
				"newspack-blocks-{$type}",
				plugins_url( $style_path, __FILE__ ),
				array(),
				NEWSPACK_BLOCKS__VERSION
			);
		}
		if ( static::is_amp() ) {
			return;
		}
		$script_data = static::script_enqueue_helper( NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . $type . '/view.js' );
		if ( $script_data ) {
			wp_enqueue_script(
				"newspack-blocks-{$type}",
				$script_data['script_path'],
				$script_data['dependencies'],
				$script_data['version'],
				true
			);
		}
	}

	/**
	 * Utility to assemble the class for a server-side rendered block.
	 *
	 * @param string $type The block type.
	 * @param array  $attributes Block attributes.
	 * @param array  $extra Additional classes to be added to the class list.
	 *
	 * @return string Class list separated by spaces.
	 */
	public static function block_classes( $type, $attributes = array(), $extra = array() ) {
		$classes = [ "wp-block-newspack-blocks-{$type}" ];

		if ( ! empty( $attributes['align'] ) ) {
			$classes[] = 'align' . $attributes['align'];
		}
		if ( isset( $attributes['className'] ) ) {
			array_push( $classes, $attributes['className'] );
		}
		if ( is_array( $extra ) && ! empty( $extra ) ) {
			$classes = array_merge( $classes, $extra );
		}

		return implode( ' ', $classes );
	}

	/**
	 * Checks whether the current view is served in AMP context.
	 *
	 * @return bool True if AMP, false otherwise.
	 */
	public static function is_amp() {
		return ! is_admin() && function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
	}

	/**
	 * Return the most appropriate thumbnail size to display.
	 *
	 * @param string $orientation The block's orientation settings: landscape|portrait|square.
	 *
	 * @return string Returns the thumbnail key to use.
	 */
	public static function image_size_for_orientation( $orientation = 'landscape' ) {
		$sizes = array(
			'landscape' => array(
				'large'  => array(
					1200,
					900,
				),
				'medium' => array(
					800,
					600,
				),
				'small'  => array(
					400,
					300,
				),
				'tiny'   => array(
					200,
					150,
				),
			),
			'portrait'  => array(
				'large'  => array(
					900,
					1200,
				),
				'medium' => array(
					600,
					800,
				),
				'small'  => array(
					300,
					400,
				),
				'tiny'   => array(
					150,
					200,
				),
			),
			'square'    => array(
				'large'  => array(
					1200,
					1200,
				),
				'medium' => array(
					800,
					800,
				),
				'small'  => array(
					400,
					400,
				),
				'tiny'   => array(
					200,
					200,
				),
			),
		);

		foreach ( $sizes[ $orientation ] as $key => $dimensions ) {
			$attachment = wp_get_attachment_image_src(
				get_post_thumbnail_id( get_the_ID() ),
				'newspack-article-block-' . $orientation . '-' . $key
			);
			if ( $dimensions[0] === $attachment[1] && $dimensions[1] === $attachment[2] ) {
				return 'newspack-article-block-' . $orientation . '-' . $key;
			}
		}

		return 'large';
	}

	/**
	 * Registers image sizes required for Newspack Blocks.
	 */
	public static function add_image_sizes() {
		add_image_size( 'newspack-article-block-landscape-large', 1200, 900, true );
		add_image_size( 'newspack-article-block-portrait-large', 900, 1200, true );
		add_image_size( 'newspack-article-block-square-large', 1200, 1200, true );

		add_image_size( 'newspack-article-block-landscape-medium', 800, 600, true );
		add_image_size( 'newspack-article-block-portrait-medium', 600, 800, true );
		add_image_size( 'newspack-article-block-square-medium', 800, 800, true );

		add_image_size( 'newspack-article-block-landscape-small', 400, 300, true );
		add_image_size( 'newspack-article-block-portrait-small', 300, 400, true );
		add_image_size( 'newspack-article-block-square-small', 400, 400, true );

		add_image_size( 'newspack-article-block-landscape-tiny', 200, 150, true );
		add_image_size( 'newspack-article-block-portrait-tiny', 150, 200, true );
		add_image_size( 'newspack-article-block-square-tiny', 200, 200, true );

		add_image_size( 'newspack-article-block-uncropped', 1200, 9999, false );
	}

	/**
	 * Builds and returns query args based on block attributes.
	 *
	 * @param array $attributes An array of block attributes.
	 *
	 * @return array
	 */
	public static function build_articles_query( $attributes ) {
		global $newspack_blocks_post_id;
		if ( ! $newspack_blocks_post_id ) {
			$newspack_blocks_post_id = array();
		}

		// Get all blocks and gather specificPosts ids of all Homepage Articles blocks.
		global $newspack_blocks_all_specific_posts_ids;
		if ( ! is_array( $newspack_blocks_all_specific_posts_ids ) ) {
			$blocks                                 = parse_blocks( get_the_content() );
			$block_name                             = apply_filters( 'newspack_blocks_block_name', 'newspack-blocks/homepage-articles' );
			$newspack_blocks_all_specific_posts_ids = array_reduce(
				$blocks,
				function ( $acc, $block ) use ( $block_name ) {
					if (
						$block_name === $block['blockName'] &&
						isset( $block['attrs']['specificMode'], $block['attrs']['specificPosts'] ) &&
						count( $block['attrs']['specificPosts'] )
					) {
						return array_merge(
							$block['attrs']['specificPosts'],
							$acc
						);
					}
					return $acc;
				},
				[]
			);
		}

		$post_type      = isset( $attributes['postType'] ) ? $attributes['postType'] : [ 'post' ];
		$authors        = isset( $attributes['authors'] ) ? $attributes['authors'] : array();
		$categories     = isset( $attributes['categories'] ) ? $attributes['categories'] : array();
		$tags           = isset( $attributes['tags'] ) ? $attributes['tags'] : array();
		$tag_exclusions = isset( $attributes['tagExclusions'] ) ? $attributes['tagExclusions'] : array();
		$specific_posts = isset( $attributes['specificPosts'] ) ? $attributes['specificPosts'] : array();
		$posts_to_show  = intval( $attributes['postsToShow'] );
		$specific_mode  = intval( $attributes['specificMode'] );
		$args           = array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'suppress_filters'    => false,
			'ignore_sticky_posts' => true,
			'has_password'        => false,
		);
		if ( $specific_mode && $specific_posts ) {
			$args['nopaging'] = true;
			$args['post__in'] = $specific_posts;
			$args['orderby']  = 'post__in';
		} else {
			$args['posts_per_page'] = $posts_to_show;
			if ( count( $newspack_blocks_all_specific_posts_ids ) ) {
				$args['post__not_in'] = $newspack_blocks_all_specific_posts_ids;
			}
			$args['post__not_in'] = array_merge(
				$args['post__not_in'] ?? [],
				array_keys( $newspack_blocks_post_id ),
				get_the_ID() ? [ get_the_ID() ] : []
			);
			if ( $authors && count( $authors ) ) {
				$args['author__in'] = $authors;
			}
			if ( $categories && count( $categories ) ) {
				$args['category__in'] = $categories;
			}
			if ( $tags && count( $tags ) ) {
				$args['tag__in'] = $tags;
			}
			if ( $tag_exclusions && count( $tag_exclusions ) ) {
				$args['tag__not_in'] = $tag_exclusions;
			}
		}
		return $args;
	}

	/**
	 * Loads a template with given data in scope.
	 *
	 * @param string $template full Path to the template to be included.
	 * @param array  $data          Data to be passed into the template to be included.
	 * @return string
	 */
	public static function template_inc( $template, $data = array() ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		if ( ! strpos( $template, '.php' ) ) {
			$template = $template . '.php';
		}
		if ( ! is_file( $template ) ) {
			return '';
		}
		ob_start();
		include $template;
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	/**
	 * Prepare an array of authors, taking presence of CoAuthors Plus into account.
	 *
	 * @return array Array of WP_User objects.
	 */
	public static function prepare_authors() {
		if ( function_exists( 'coauthors_posts_links' ) && ! empty( get_coauthors() ) ) {
			$authors = get_coauthors();
			foreach ( $authors as $author ) {
				// Check if this is a guest author post type.
				if ( 'guest-author' === get_post_type( $author->ID ) ) {
					// If yes, make sure the author actually has an avatar set; otherwise, coauthors_get_avatar returns a featured image.
					if ( get_post_thumbnail_id( $author->ID ) ) {
						$author->avatar = coauthors_get_avatar( $author, 48 );
					} else {
						// If there is no avatar, force it to return the current fallback image.
						$author->avatar = get_avatar( ' ' );
					}
				} else {
					$author->avatar = coauthors_get_avatar( $author, 48 );
				}
				$author->url = get_author_posts_url( $author->ID, $author->user_nicename );
			}
			return $authors;
		}
		$id = get_the_author_meta( 'ID' );
		return array(
			(object) array(
				'ID'            => $id,
				'avatar'        => get_avatar( $id, 48 ),
				'url'           => get_author_posts_url( $id ),
				'user_nicename' => get_the_author(),
				'display_name'  => get_the_author_meta( 'display_name' ),
			),
		);
	}

	/**
	 * Prepare a list of classes based on assigned tags, categories, post formats and types.
	 *
	 * @param string $post_id Post ID.
	 * @return string CSS classes.
	 */
	public static function get_term_classes( $post_id ) {
		$classes = [];

		$tags = get_the_terms( $post_id, 'post_tag' );
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				$classes[] = 'tag-' . $tag->slug;
			}
		}

		$categories = get_the_terms( $post_id, 'category' );
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $cat ) {
				$classes[] = 'category-' . $cat->slug;
			}
		}

		$post_format = get_post_format( $post_id );
		if ( false !== $post_format ) {
			$classes[] = 'format-' . $post_format;
		}

		$post_type = get_post_type( $post_id );
		if ( false !== $post_type ) {
			$classes[] = 'type-' . $post_type;
		}

		return implode( ' ', $classes );
	}

	/**
	 * Get patterns for post type.
	 *
	 * @param string $post_type Post type.
	 * @return array Array of patterns.
	 */
	public static function get_patterns_for_post_type( $post_type = null ) {
		$patterns    = apply_filters( 'newspack_blocks_patterns', [], $post_type );
		$categorized = [];
		$clean       = [];
		foreach ( $patterns as $pattern ) {
			if ( ! isset( $pattern['image'] ) || ! $pattern['image'] ) {
				continue;
			}
			$category = isset( $pattern['category'] ) ? $pattern['category'] : __( 'Common', 'newspack-blocks' );
			if ( ! isset( $categorized[ $category ] ) ) {
				$categorized[ $category ] = [];
			}
			$categorized[ $category ][] = $pattern;
		}
		$categories = array_keys( $categorized );
		sort( $categories );
		foreach ( $categories as $category ) {
			$clean[] = [
				'title' => $category,
				'items' => $categorized[ $category ],
			];
		}
		return $clean;
	}

	/**
	 * Function to check if plugin is enabled, and if there are sponsors.
	 *
	 * @see https://github.com/Automattic/newspack-sponsors/blob/8ebf72ec4fe744bca405a1f6fe8cd5bce3a29e6a/includes/newspack-sponsors-theme-helpers.php#L35
	 *
	 * @param int|null    $id    ID of the post or archive term to get sponsors for.
	 *                           If not provided, we will try to guess based on context.
	 * @param string|null $scope Scope of the sponsors to get. Can be 'native' or
	 *                           'underwritten'. If provided, only sponsors with the
	 *                           matching scope will be returned. If not, all sponsors
	 *                           will be returned regardless of scope.
	 * @param string|null $type  Type of the $id given: 'post' or 'archive'. If not
	 *                           provided, we will try to guess based on context.
	 * @param array       $logo_options Optional array of logo options. Valid options:
	 *                                  maxwidth: max width of the logo image, in pixels.
	 *                                  maxheight: max height of the logo image, in pixels.
	 * @return array Array of sponsors.
	 */
	public static function get_all_sponsors( $id = null, $scope = 'native', $type = 'post', $logo_options = array(
		'maxwidth'  => 80,
		'maxheight' => 40,
	) ) {
		if ( function_exists( '\Newspack_Sponsors\get_sponsors_for_post' ) ) {
			return \Newspack_Sponsors\get_all_sponsors( $id, $scope, $type, $logo_options ); // phpcs:ignore PHPCompatibility.LanguageConstructs.NewLanguageConstructs.t_ns_separatorFound
		}

		return false;
	}

	/**
	 * Function to return sponsor 'flag' from first sponsor.
	 *
	 * @param array  $sponsors Array of sponsors.
	 * @param string $id Post ID.
	 * @return string|boolean Sponsor flag label, or false if none found.
	 */
	public static function get_sponsor_label( $sponsors = null, $id = null ) {
		if ( null === $sponsors && ! empty( $id ) ) {
			$sponsors = self::get_all_sponsors( $id );
		}

		if ( ! empty( $sponsors ) ) {
			$sponsor_flag = $sponsors[0]['sponsor_flag'];
			return $sponsor_flag;
		}

		return false;
	}

	/**
	 * Outputs the sponsor byline markup for the theme.
	 *
	 * @param array  $sponsors Array of sponsors.
	 * @param string $id Post ID.
	 * @return array|boolean Array of Sponsor byline information, or false if none found.
	 */
	public static function get_sponsor_byline( $sponsors = null, $id = null ) {
		if ( null === $sponsors & ! empty( $id ) ) {
			$sponsors = self::get_all_sponsors( $id );
		}

		if ( ! empty( $sponsors ) ) {
			$sponsor_count = count( $sponsors );
			$i             = 1;
			$sponsor_list  = [];

			foreach ( $sponsors as $sponsor ) {
				$i++;
				if ( $sponsor_count === $i ) :
					/* translators: separates last two sponsor names; needs a space on either side. */
					$sep = esc_html__( ' and ', 'newspack-blocks' );
				elseif ( $sponsor_count > $i ) :
					/* translators: separates all but the last two sponsor names; needs a space at the end. */
					$sep = esc_html__( ', ', 'newspack-blocks' );
				else :
					$sep = '';
				endif;

				$sponsor_list[] = array(
					'byline' => $sponsor['sponsor_byline'],
					'url'    => $sponsor['sponsor_url'],
					'name'   => $sponsor['sponsor_name'],
					'sep'    => $sep,
				);
			}
			return $sponsor_list;
		}

		return false;
	}

	/**
	 * Outputs set of sponsor logos with links.
	 *
	 * @param array  $sponsors Array of sponsors.
	 * @param string $id Post ID.
	 * @return array Array of sponsor logo images, or false if none found.
	 */
	public static function get_sponsor_logos( $sponsors = null, $id = null ) {
		if ( null === $sponsors && ! empty( $id ) ) {
			$sponsors = self::get_all_sponsors(
				$id,
				'native',
				'post',
				array(
					'maxwidth'  => 80,
					'maxheight' => 40,
				)
			);
		}

		if ( ! empty( $sponsors ) ) {
			$sponsor_logos = [];
			foreach ( $sponsors as $sponsor ) {
				if ( ! empty( $sponsor['sponsor_logo'] ) ) :
					$sponsor_logos[] = array(
						'url'    => $sponsor['sponsor_url'],
						'src'    => esc_url( $sponsor['sponsor_logo']['src'] ),
						'width'  => esc_attr( $sponsor['sponsor_logo']['img_width'] ),
						'height' => esc_attr( $sponsor['sponsor_logo']['img_height'] ),
					);
				endif;
			}

			return $sponsor_logos;
		}

		return false;
	}

	/**
	 * Closure for excerpt filtering that can be added and removed.
	 *
	 * @var newspack_blocks_excerpt_length_closure
	 */
	public static $newspack_blocks_excerpt_length_closure = null;

	/**
	 * Function to override WooCommerce Membership's Excerpt Length filter.
	 *
	 * @return string Current post's original excerpt.
	 */
	public static function remove_wc_memberships_excerpt_limit() {
		$excerpt = get_the_excerpt( get_the_id() );
		return $excerpt;
	}

	/**
	 * Filter for excerpt length.
	 *
	 * @param array $attributes The block's attributes.
	 */
	public static function filter_excerpt_length( $attributes ) {
		// If showing excerpt, filter the length using the block attribute.
		if ( $attributes['showExcerpt'] ) {
			self::$newspack_blocks_excerpt_length_closure = add_filter(
				'excerpt_length',
				function() use ( $attributes ) {
					if ( $attributes['excerptLength'] ) {
						return $attributes['excerptLength'];
					}
					return 55;
				},
				999
			);
			add_filter( 'wc_memberships_trimmed_restricted_excerpt', [ 'Newspack_Blocks', 'remove_wc_memberships_excerpt_limit' ], 999 );
		}
	}

	/**
	 * Remove excerpt length filter after Homepage Posts block loop.
	 */
	public static function remove_excerpt_length_filter() {
		if ( self::$newspack_blocks_excerpt_length_closure ) {
			remove_filter(
				'excerpt_length',
				self::$newspack_blocks_excerpt_length_closure,
				999
			);
			remove_filter( 'wc_memberships_trimmed_restricted_excerpt', [ 'Newspack_Blocks', 'remove_wc_memberships_excerpt_limit' ] );
		}
	}

	/**
	 * Return a excerpt more replacement when using the 'Read More' link.
	 */
	public static function more_excerpt() {
		return '…';
	}

	/**
	 * Filter for excerpt ellipsis.
	 *
	 * @param array $attributes The block's attributes.
	 */
	public static function filter_excerpt_more( $attributes ) {
		// If showing the 'Read More' link, modify the ellipsis.
		if ( $attributes['showReadMore'] ) {
			add_filter( 'excerpt_more', [ __CLASS__, 'more_excerpt' ], 999 );
		}
	}

	/**
	 * Remove excerpt ellipsis filter after Homepage Posts block loop.
	 */
	public static function remove_excerpt_more_filter() {
		remove_filter( 'excerpt_more', [ __CLASS__, 'more_excerpt' ], 999 );
	}
}
Newspack_Blocks::init();
