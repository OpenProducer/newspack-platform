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
	 * Script handle for the streamlined donate block script.
	 */
	const DONATE_STREAMLINED_SCRIPT_HANDLE = 'newspack-blocks-donate-streamlined';

	/**
	 * Regex pattern we can use to search for and remove custom SQL statements.
	 * Custom statements added by this class are wrapped by `newspack-blocks` comments.
	 */
	const SQL_PATTERN = '/\/\* newspack-blocks \*\/(.|\n)*\/\* \/newspack-blocks \*\//';

	/**
	 * Class property to store user IDs and CAP guest author names for building
	 * custom SQL statements. In order to allow a single WP_Query to filter by
	 * both WP users and CAP guest authors (a taxonomy), we need to directly
	 * modify the JOIN and WHERE clauses in the SQL query.
	 *
	 * If this property is false, then the custom statements will be stripped
	 * from all SQL clauses. If it's an array with `authors` and `coauthors`
	 * keys, the custom statements will be added to the SQL query.
	 *
	 * Example array:
	 * [
	 *     'authors'   => [], // Array of numeric WP user IDs.
	 *     'coauthors' => [], // Array of CAP guest author name slugs.
	 * ]
	 *
	 * @var boolean|array
	 */
	protected static $filter_clauses = false;

	/**
	 * Add hooks and filters.
	 */
	public static function init() {
		add_action( 'after_setup_theme', [ __CLASS__, 'add_image_sizes' ] );
		add_post_type_support( 'post', 'newspack_blocks' );
		add_post_type_support( 'page', 'newspack_blocks' );
		add_filter( 'script_loader_tag', [ __CLASS__, 'mark_view_script_as_amp_plus_allowed' ], 10, 2 );
		add_action( 'jetpack_register_gutenberg_extensions', [ __CLASS__, 'disable_jetpack_donate' ], 99 );
		add_filter( 'the_content', [ __CLASS__, 'hide_post_content_when_iframe_block_is_fullscreen' ] );
		add_filter( 'posts_clauses', [ __CLASS__, 'filter_posts_clauses_when_co_authors' ], 999, 2 );
		add_filter( 'posts_groupby', [ __CLASS__, 'group_by_post_id_filter' ], 999 );

		/**
		 * Disable NextGEN's `C_NextGen_Shortcode_Manager`.
		 *
		 * The way it currently parses `the_content` conflicts with the REST API
		 * request to save a post containing a Homepage Posts block. This is due to
		 * how it uses output buffering through `ob_start()` on REST requests.
		 *
		 * @link https://plugins.trac.wordpress.org/browser/nextgen-gallery/tags/3.23/non_pope/class.nextgen_shortcode_manager.php#L193.
		 */
		if ( ! defined( 'NGG_DISABLE_SHORTCODE_MANAGER' ) ) {
			define( 'NGG_DISABLE_SHORTCODE_MANAGER', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		}
	}

	/**
	 * Modify the Donate block script to allow it as an "AMP Plus" script.
	 *
	 * @param string $tag HTML of the script tag.
	 * @param string $handle The script handle.
	 */
	public static function mark_view_script_as_amp_plus_allowed( $tag, $handle ) {
		if ( self::DONATE_STREAMLINED_SCRIPT_HANDLE === $handle ) {
			return str_replace( '<script', '<script data-amp-plus-allowed', $tag );
		}
		return $tag;
	}

	/**
	 * Hide the post content when it contains an iframe block that is set to fullscreen mode.
	 *
	 * @param string $content post content from the_content hook.
	 * @return string the post content.
	 */
	public static function hide_post_content_when_iframe_block_is_fullscreen( $content ) {
		if ( has_block( 'newspack-blocks/iframe' ) ) {
			$blocks = parse_blocks( get_post()->post_content );

			foreach ( $blocks as $block ) {
				if ( 'newspack-blocks/iframe' === $block['blockName']
					&& array_key_exists( 'isFullScreen', $block['attrs'] )
					&& $block['attrs']['isFullScreen']
					) {
					// we don't need the post content since the iframe will be fullscreen.
					$content = render_block( $block );

					add_filter(
						'body_class',
						function( $classes ) {
							$classes[] = 'newspack-post-with-fullscreen-iframe';
							return $classes;
						}
					);

					// we don't need to show Newspack popups since the iframe will take over them.
					add_filter( 'newspack_popups_assess_has_disabled_popups', '__return_true' );
				}
			}
		}

		return $content;
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
				'dependencies' => [ 'wp-a11y', 'wp-escape-html', 'wp-i18n', 'wp-polyfill' ],
				'version'      => filemtime( $local_path ),
			);

		$script_data['script_path'] = plugins_url( $script_path, NEWSPACK_BLOCKS__PLUGIN_FILE );
		return $script_data;
	}

	/**
	 * Path of the Stripe badge file.
	 */
	public static function streamlined_block_stripe_badge() {
		return plugins_url( '/src/assets', NEWSPACK_BLOCKS__PLUGIN_FILE ) . '/stripe-badge.svg';
	}

	/**
	 * Possible mimes for iframe archive source file.
	 */
	public static function iframe_archive_accepted_file_mimes() {
		return [ 'application/zip' => 'zip' ];
	}

	/**
	 * Possible mimes for iframe document source file.
	 */
	public static function iframe_document_accepted_file_mimes() {
		$mimes = get_allowed_mime_types();
		return [
			$mimes['pdf']             => 'pdf',
			$mimes['doc']             => 'doc',
			$mimes['docx']            => 'docx',
			$mimes['xla|xls|xlt|xlw'] => 'xls',
			$mimes['xlsx']            => 'xlsx',
			$mimes['pot|pps|ppt']     => 'ppt',
			$mimes['pptx']            => 'pptx',
		];
	}

	/**
	 * Possible mimes for iframe source file.
	 */
	public static function iframe_accepted_file_mimes() {
		return array_merge(
			array_values( self::iframe_archive_accepted_file_mimes() ),
			array_values( self::iframe_document_accepted_file_mimes() )
		);
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

			$localized_data = [
				'patterns'                       => self::get_patterns_for_post_type( get_post_type() ),
				'posts_rest_url'                 => rest_url( 'newspack-blocks/v1/newspack-blocks-posts' ),
				'specific_posts_rest_url'        => rest_url( 'newspack-blocks/v1/newspack-blocks-specific-posts' ),
				'authors_rest_url'               => rest_url( 'newspack-blocks/v1/authors' ),
				'assets_path'                    => plugins_url( '/src/assets', NEWSPACK_BLOCKS__PLUGIN_FILE ),
				'post_subtitle'                  => get_theme_support( 'post-subtitle' ),
				'is_rendering_streamlined_block' => self::is_rendering_streamlined_block(),
				'streamlined_block_stripe_badge' => self::streamlined_block_stripe_badge(),
				'iframe_accepted_file_mimes'     => self::iframe_accepted_file_mimes(),
			];

			if ( class_exists( 'WP_REST_Newspack_Author_List_Controller' ) ) {
				$localized_data['can_use_cap']    = class_exists( 'CoAuthors_Guest_Authors' );
				$author_list_controller           = new WP_REST_Newspack_Author_List_Controller();
				$localized_data['editable_roles'] = $author_list_controller->get_editable_roles();
			}

			wp_localize_script(
				'newspack-blocks-editor',
				'newspack_blocks_data',
				$localized_data
			);

			wp_set_script_translations(
				'newspack-blocks-editor',
				'newspack-blocks',
				plugin_dir_path( NEWSPACK_BLOCKS__PLUGIN_FILE ) . 'languages'
			);
		}

		$editor_style = plugins_url( NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . 'editor.css', NEWSPACK_BLOCKS__PLUGIN_FILE );

		wp_enqueue_style(
			'newspack-blocks-editor',
			$editor_style,
			array(),
			NEWSPACK_BLOCKS__VERSION
		);
	}

	/**
	 * Should the Donate block be a "streamlined" block?
	 *
	 * @return bool True if it can.
	 */
	public static function is_rendering_streamlined_block() {
		if (
			class_exists( 'Newspack\Donations' )
			&& method_exists( 'Newspack\Donations', 'can_use_streamlined_donate_block' )
			&& method_exists( 'Newspack\Donations', 'is_platform_stripe' )
		) {
			return \Newspack\Donations::can_use_streamlined_donate_block() && \Newspack\Donations::is_platform_stripe();
		}
		return false;
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
							self::enqueue_view_assets( $type );
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
				plugins_url( $style_path, NEWSPACK_BLOCKS__PLUGIN_FILE ),
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
				plugins_url( $style_path, NEWSPACK_BLOCKS__PLUGIN_FILE ),
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
		if ( ! empty( $attributes['hideControls'] ) ) {
			$classes[] = 'hide-controls';
		}

		return implode( ' ', $classes );
	}

	/**
	 * Checks whether the current view is served in AMP context.
	 *
	 * @return bool True if AMP, false otherwise.
	 */
	public static function is_amp() {
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return true;
		}
		return false;
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
			if ( ! empty( $attachment ) && $dimensions[0] === $attachment[1] && $dimensions[1] === $attachment[2] ) {
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
	 * @param array $block_name Name of the block requesting the query.
	 *
	 * @return array
	 */
	public static function build_articles_query( $attributes, $block_name ) {
		// Reset author/CAP guest author SQL statements by default.
		self::$filter_clauses = false;

		global $newspack_blocks_post_id;
		if ( ! $newspack_blocks_post_id ) {
			$newspack_blocks_post_id = array();
		}

		// Get all blocks and gather specificPosts ids of all eligible blocks.
		global $newspack_blocks_all_specific_posts_ids;
		if ( ! is_array( $newspack_blocks_all_specific_posts_ids ) ) {
			$blocks                                 = parse_blocks( get_the_content() );
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

		$post_type           = isset( $attributes['postType'] ) ? $attributes['postType'] : [ 'post' ];
		$authors             = isset( $attributes['authors'] ) ? $attributes['authors'] : array();
		$categories          = isset( $attributes['categories'] ) ? $attributes['categories'] : array();
		$tags                = isset( $attributes['tags'] ) ? $attributes['tags'] : array();
		$tag_exclusions      = isset( $attributes['tagExclusions'] ) ? $attributes['tagExclusions'] : array();
		$category_exclusions = isset( $attributes['categoryExclusions'] ) ? $attributes['categoryExclusions'] : array();
		$specific_posts      = isset( $attributes['specificPosts'] ) ? $attributes['specificPosts'] : array();
		$posts_to_show       = intval( $attributes['postsToShow'] );
		$specific_mode       = isset( $attributes['specificMode'] ) ? intval( $attributes['specificMode'] ) : false;
		$args                = array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'suppress_filters'    => false,
			'ignore_sticky_posts' => true,
			'has_password'        => false,
			'is_newspack_query'   => true,
		);
		if ( $specific_mode && $specific_posts ) {
			$args['nopaging'] = true;
			$args['post__in'] = $specific_posts;
			$args['orderby']  = 'post__in';
		} else {
			$args['posts_per_page'] = $posts_to_show;

			$show_rendered_posts = apply_filters( 'newspack_blocks_homepage_shown_rendered_posts', false );
			if ( $show_rendered_posts ) {
				$args['post__not_in'] = [ get_the_ID() ];
			} else {
				if ( count( $newspack_blocks_all_specific_posts_ids ) ) {
					$args['post__not_in'] = $newspack_blocks_all_specific_posts_ids;
				}
				$args['post__not_in'] = array_merge(
					$args['post__not_in'] ?? [],
					array_keys( $newspack_blocks_post_id ),
					get_the_ID() ? [ get_the_ID() ] : []
				);
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
			if ( $category_exclusions && count( $category_exclusions ) ) {
				$args['category__not_in'] = $category_exclusions;
			}

			if ( $authors && count( $authors ) ) {
				$co_authors_names = [];
				$author_names     = [];

				if ( class_exists( 'CoAuthors_Guest_Authors' ) ) {
					$co_authors_guest_authors = new CoAuthors_Guest_Authors();

					foreach ( $authors as $index => $author_id ) {
						// If the given ID is a guest author.
						$co_author = $co_authors_guest_authors->get_guest_author_by( 'id', $author_id );
						if ( $co_author ) {
							if ( ! empty( $co_author->linked_account ) ) {
								$linked_account = get_user_by( 'login', $co_author->linked_account );
								if ( $linked_account ) {
									$authors[] = $linked_account->ID;
								}
							}
							$co_authors_names[] = $co_author->user_nicename;
							unset( $authors[ $index ] );
						} else {
							// If the given ID is linked to a guest author.
							$authors_controller = new WP_REST_Newspack_Authors_Controller();
							$author_data        = get_userdata( $author_id );
							if ( $author_data ) {
								$linked_guest_author = $authors_controller->get_linked_guest_author( $author_data->user_login );
								if ( $linked_guest_author ) {
									$guest_author_name = sanitize_title( $linked_guest_author->post_title );
									if ( ! in_array( $guest_author_name, $co_authors_names, true ) ) {
										$co_authors_names[] = $guest_author_name;
										unset( $authors[ $index ] );
									}
								} else {
									$author_names[] = $author_data->user_login;
								}
							}
						}
					}
				}

				// Reset numeric indexes.
				$authors = array_values( $authors );
				if ( empty( $authors ) && count( $co_authors_names ) ) {
					// Look for co-authors posts.
					$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						[
							'field'    => 'name',
							'taxonomy' => 'author',
							'terms'    => $co_authors_names,
						],
					];
				} elseif ( empty( $co_authors_names ) && count( $authors ) ) {
					$args['author__in'] = $authors;

					// Don't get any posts that are attributed to other CAP guest authors.
					$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						[
							'relation' => 'OR',
							[
								'taxonomy' => 'author',
								'operator' => 'NOT EXISTS',
							],
							[
								'field'    => 'name',
								'taxonomy' => 'author',
								'terms'    => $author_names,
							],
						],
					];
				} else {
					// The query contains both WP users and CAP guest authors. We need to filter the SQL query.
					self::$filter_clauses = [
						'authors'   => $authors,
						'coauthors' => $co_authors_names,
					];
				}
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

		/**
		 * Filter the array of class names before applying them to the HTML.
		 *
		 * @param array $classes Array of term class names.
		 *
		 * @return array Filtered array of term class names.
		 */
		$classes = apply_filters( 'newspack_blocks_term_classes', $classes );

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
						'alt'    => esc_attr( $sponsor['sponsor_name'] ),
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
	 * @var Closure
	 */
	public static $newspack_blocks_excerpt_closure = null;

	/**
	 * Closure for excerpt length filtering that can be added and removed.
	 *
	 * @var Closure
	 * @deprecated
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
	public static function filter_excerpt( $attributes ) {
		if ( empty( $attributes['excerptLength'] ) || ! $attributes['showExcerpt'] ) {
			return;
		}

		self::$newspack_blocks_excerpt_closure = function( $text = '', $post = null ) use ( $attributes ) {
			$post        = get_post( $post );
			$text        = $post->post_excerpt;
			$raw_excerpt = $text;

			if ( empty( $text ) ) {
				$text = get_the_content( '', false, $post );
				$text = strip_shortcodes( $text );
				$text = excerpt_remove_blocks( $text );
			}

			/** This filter is documented in wp-includes/post-template.php */
			$text = str_replace( ']]>', ']]&gt;', $text );
			$text = wp_trim_words( $text, $attributes['excerptLength'], static::more_excerpt() );

			/** This filter is documented in wp-includes/post-template.php */
			return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt ); // phpcs:ignore
		};

		add_filter( 'get_the_excerpt', self::$newspack_blocks_excerpt_closure, 11, 2 );
	}

	/**
	 * Remove excerpt filter after Homepage Posts block loop.
	 */
	public static function remove_excerpt_filter() {
		if ( static::$newspack_blocks_excerpt_closure ) {
			remove_filter( 'get_the_excerpt', static::$newspack_blocks_excerpt_closure, 11 );
		}
	}

	/**
	 * Filter for excerpt length.
	 *
	 * @deprecated
	 * @param array $attributes The block's attributes.
	 */
	public static function filter_excerpt_length( $attributes ) {
		// If showing excerpt, filter the length using the block attribute.
		if ( isset( $attributes['excerptLength'] ) && $attributes['showExcerpt'] ) {
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
	 *
	 * @deprecated
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
	 * @deprecated
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
	 *
	 * @deprecated
	 */
	public static function remove_excerpt_more_filter() {
		remove_filter( 'excerpt_more', [ __CLASS__, 'more_excerpt' ], 999 );
	}

	/**
	 * Filter posts by authors and co-authors. If the query is filtering posts
	 * by both WP users and CAP guest authors, the SQL clauses must be modified
	 * directly so that the filtering can happen with a single SQL query.
	 *
	 * @param string[] $clauses Associative array of the clauses for the query.
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 */
	public static function filter_posts_clauses_when_co_authors( $clauses, $query ) {
		// Remove any lingering custom SQL statements.
		$clauses['join']   = preg_replace( self::SQL_PATTERN, '', $clauses['join'] );
		$clauses['where']  = preg_replace( self::SQL_PATTERN, '', $clauses['where'] );
		$is_newspack_query = isset( $query->query_vars['is_newspack_query'] ) && $query->query_vars['is_newspack_query'];

		// If the query isn't coming from this plugin, or $filter_clauses lacks expected data.
		if (
			! $is_newspack_query ||
			! self::$filter_clauses ||
			! isset( self::$filter_clauses['authors'] ) ||
			! isset( self::$filter_clauses['coauthors'] )
		) {
			return $clauses;
		}

		global $wpdb;

		$authors_ids      = self::$filter_clauses['authors'];
		$co_authors_names = self::$filter_clauses['coauthors'];

		// co-author tax query.
		$tax_query = [
			[
				'taxonomy' => 'author',
				'field'    => 'name',
				'terms'    => $co_authors_names,
			],
		];

		// Generate the tax query SQL.
		$tax_query = new WP_Tax_Query( $tax_query );
		$tax_query = $tax_query->get_sql( $wpdb->posts, 'ID' );

		// Generate the author query SQL.
		$csv          = implode( ',', wp_parse_id_list( (array) $authors_ids ) );
		$author_names = array_reduce(
			$authors_ids,
			function( $acc, $author_id ) {
				$author_data = get_userdata( $author_id );
				if ( $author_data ) {
					$acc[] = $author_data->user_login;
				}
				return $acc;
			},
			[]
		);

		// If getting only WP users, we don't want to get posts attributed to CAP guest authors not linked to the given WP users.
		$exclude = new WP_Tax_Query(
			[
				'relation' => 'OR',
				[
					'taxonomy' => 'author',
					'operator' => 'NOT EXISTS',
				],
				[
					'field'    => 'name',
					'taxonomy' => 'author',
					'terms'    => $author_names,
				],
			]
		);
		$exclude = $exclude->get_sql( $wpdb->posts, 'ID' );
		$exclude = $exclude['where'];
		$authors = " ( {$wpdb->posts}.post_author IN ( $csv ) $exclude ) ";

		// Make sure the authors are set, the tax query is valid (doesn't contain 0 = 1).
		if ( false === strpos( $tax_query['where'], ' 0 = 1' ) ) {
			// Append to the current join parts. The JOIN statment only needs to exist in the clause once.
			if ( false === strpos( $clauses['join'], $tax_query['join'] ) ) {
				$clauses['join'] .= '/* newspack-blocks */ ' . $tax_query['join'] . ' /* /newspack-blocks */';
			}

			$clauses['where'] .= sprintf(
			// The tax query SQL comes prepended with AND.
				'%s AND ( %s ( 1=1 %s ) ) %s',
				'/* newspack-blocks */',
				empty( $authors_ids ) ? '' : $authors . ' OR',
				$tax_query['where'],
				'/* /newspack-blocks */'
			);
		}
		return $clauses;
	}

	/**
	 * Group by post ID filter, used when we join taxonomies while getting posts.
	 *
	 * @param string $groupby The GROUP BY clause of the query.
	 * @return string The filtered GROUP BY clause.
	 */
	public static function group_by_post_id_filter( $groupby ) {
		global $wpdb;

		if ( self::$filter_clauses ) {
			return "{$wpdb->posts}.ID ";
		}

		return $groupby;
	}

	/**
	 * Utility to get the link for the given post ID. If the post has an external URL meta value, use that.
	 * Otherwise, use the permalink. But if the post type doesn't have a public singular view, don't link.
	 *
	 * @param int $post_id Post ID for which to get the link. Will default to current post if none given.
	 * @return string|boolean The URL for the post, or false if it can't be linked to.
	 */
	public static function get_post_link( $post_id = null ) {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		$post_type        = get_post_type( $post_id );
		$sponsor_url      = get_post_meta( $post_id, 'newspack_sponsor_url', true );
		$supporter_url    = get_post_meta( $post_id, 'newspack_supporter_url', true );
		$external_url     = ! empty( $sponsor_url ) ? $sponsor_url : $supporter_url;
		$post_type_info   = get_post_type_object( $post_type );
		$link             = ! empty( $external_url ) ? $external_url : get_permalink();
		$should_have_link = ! empty( $post_type_info->public ) || ! empty( $external_url ); // False if a sponsor or supporter without an external URL.

		return $should_have_link ? $link : false;
	}

	/**
	 * Sanitize SVG markup for front-end display.
	 *
	 * @param string $svg SVG markup to sanitize.
	 * @return string Sanitized markup.
	 */
	public static function sanitize_svg( $svg = '' ) {
		$allowed_html = [
			'svg'  => [
				'xmlns'       => [],
				'fill'        => [],
				'viewbox'     => [],
				'role'        => [],
				'aria-hidden' => [],
				'focusable'   => [],
				'height'      => [],
				'width'       => [],
			],
			'path' => [
				'd'    => [],
				'fill' => [],
			],
		];

		return wp_kses( $svg, $allowed_html );
	}

	/**
	 * Disable Jetpack's donate block when using Newspack donations.
	 */
	public static function disable_jetpack_donate() {
		// Do nothing if Jetpack's blocks or Newspack aren't being used.
		if ( ! class_exists( 'Jetpack_Gutenberg' ) || ! class_exists( 'Newspack' ) ) {
			return;
		}

		// Allow Jetpack donations if Newspack donations isn't set up.
		$donate_settings = Newspack\Donations::get_donation_settings();
		if ( is_wp_error( $donate_settings ) || ! $donate_settings['created'] ) {
			return;
		}

		// Tell Jetpack to mark the donations feature as unavailable.
		Jetpack_Gutenberg::set_extension_unavailable(
			'jetpack/donations',
			esc_html__( 'Jetpack donations is disabled in favour of Newspack donations.', 'newspack-blocks' )
		);
	}

	/**
	 * Loads a template with given data in scope.
	 *
	 * @param string $template Name of the template to be included.
	 * @param array  $data     Data to be passed into the template to be included.
	 * @param string $path     (Optional) Path to the folder containing the template.
	 * @return string
	 */
	public static function template_include( $template, $data = [], $path = NEWSPACK_BLOCKS__PLUGIN_DIR . 'src/templates/' ) {
		if ( ! strpos( $template, '.php' ) ) {
			$template = $template . '.php';
		}
		$path .= $template;
		if ( ! is_file( $path ) ) {
			return '';
		}
		ob_start();
		include $path;
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}
Newspack_Blocks::init();
