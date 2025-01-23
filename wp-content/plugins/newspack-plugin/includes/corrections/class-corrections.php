<?php
/**
 * Newspack Corrections and Clarifications
 *
 * @package Newspack
 */

namespace Newspack;

/**
 * Class to handle Corrections and Clarifications.
 */
class Corrections {
	/**
	 * Post type for corrections.
	 */
	const POST_TYPE = 'newspack_correction';

	/**
	 * Meta key for correction post ID meta.
	 */
	const CORRECTION_POST_ID_META = 'newspack_correction-post-id';

	/**
	 * Meta key for post corrections active meta.
	 */
	const CORRECTIONS_ACTIVE_META = 'newspack_corrections_active';

	/**
	 * Meta key for post corrections location meta.
	 */
	const CORRECTIONS_LOCATION_META = 'newspack_corrections_location';

	/**
	 * Initializes the class.
	 */
	public static function init() {
		if ( ! self::is_enabled() ) {
			return;
		}
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'init', [ __CLASS__, 'add_corrections_shortcode' ] );
		add_action( 'add_meta_boxes', [ __CLASS__, 'add_corrections_metabox' ] );
		add_action( 'save_post', [ __CLASS__, 'save_corrections_metabox' ] );
		add_filter( 'the_content', [ __CLASS__, 'output_corrections_on_post' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );
	}

	/**
	 * Checks if the feature is enabled.
	 *
	 * True when:
	 * - NEWSPACK_CORRECTIONS_ENABLED is defined and true.
	 *
	 * @return bool True if the feature is enabled, false otherwise.
	 */
	public static function is_enabled() {
		return defined( 'NEWSPACK_CORRECTIONS_ENABLED' ) && NEWSPACK_CORRECTIONS_ENABLED;
	}


	/**
	 * Enqueue scripts and styles.
	 */
	public static function wp_enqueue_scripts() {
		if ( ! is_admin() || ! filter_input( INPUT_GET, 'post', FILTER_VALIDATE_INT ) ) {
			return;
		}

		\wp_enqueue_script(
			'newspack-corrections',
			Newspack::plugin_url() . '/dist/other-scripts/corrections.js',
			[],
			NEWSPACK_PLUGIN_VERSION,
			true
		);
		\wp_enqueue_style(
			'newspack-corrections',
			Newspack::plugin_url() . '/dist/other-scripts/corrections.css',
			[],
			NEWSPACK_PLUGIN_VERSION
		);
	}

	/**
	 * Registers the corrections post type.
	 *
	 * @return void
	 */
	public static function register_post_type() {
		$supports = [
			'author',
			'editor',
			'title',
			'revisions',
			'custom-fields',
		];
		$labels = [
			'name'                     => _x( 'Corrections', 'post type general name', 'newspack-plugin' ),
			'singular_name'            => _x( 'Correction', 'post type singular name', 'newspack-plugin' ),
			'menu_name'                => _x( 'Corrections', 'admin menu', 'newspack-plugin' ),
			'name_admin_bar'           => _x( 'Correction', 'add new on admin bar', 'newspack-plugin' ),
			'add_new'                  => _x( 'Add New', 'correction', 'newspack-plugin' ),
			'add_new_item'             => __( 'Add New Correction', 'newspack-plugin' ),
			'new_item'                 => __( 'New Correction', 'newspack-plugin' ),
			'edit_item'                => __( 'Edit Correction', 'newspack-plugin' ),
			'view_item'                => __( 'View Correction', 'newspack-plugin' ),
			'view_items'               => __( 'View Correction', 'newspack-plugin' ),
			'all_items'                => __( 'All Corrections', 'newspack-plugin' ),
			'search_items'             => __( 'Search Corrections', 'newspack-plugin' ),
			'parent_item_colon'        => __( 'Parent Correction:', 'newspack-plugin' ),
			'not_found'                => __( 'No corrections found.', 'newspack-plugin' ),
			'not_found_in_trash'       => __( 'No corrections found in Trash.', 'newspack-plugin' ),
			'archives'                 => __( 'Correction Archives', 'newspack-plugin' ),
			'attributes'               => __( 'Correction Attributes', 'newspack-plugin' ),
			'insert_into_item'         => __( 'Insert into correction', 'newspack-plugin' ),
			'uploaded_to_this_item'    => __( 'Uploaded to this correction', 'newspack-plugin' ),
			'filter_items_list'        => __( 'Filter corrections list', 'newspack-plugin' ),
			'items_list_navigation'    => __( 'Corrections list navigation', 'newspack-plugin' ),
			'items_list'               => __( 'Corrections list', 'newspack-plugin' ),
			'item_published'           => __( 'Correction published.', 'newspack-plugin' ),
			'item_published_privately' => __( 'Correction published privately.', 'newspack-plugin' ),
			'item_reverted_to_draft'   => __( 'Correction reverted to draft.', 'newspack-plugin' ),
			'item_scheduled'           => __( 'Correction scheduled.', 'newspack-plugin' ),
			'item_updated'             => __( 'Correction updated.', 'newspack-plugin' ),
			'item_link'                => __( 'Correction Link', 'newspack-plugin' ),
			'item_link_description'    => __( 'A link to a correction.', 'newspack-plugin' ),
		];
		$args = array(
			'labels'           => $labels,
			'description'      => 'Post type used to store corrections and clarifications.',
			'has_archive'      => true,
			'public'           => true,
			'public_queryable' => true,
			'query_var'        => true,
			'rewrite'          => [ 'slug' => 'correction' ],
			'show_ui'          => false,
			'show_in_rest'     => true,
			'supports'         => $supports,
			'taxonomies'       => [],
			'menu_icon'        => 'dashicons-edit',
		);
		\register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Save corrections for post.
	 *
	 * @param int   $post_id     The post ID.
	 * @param array $corrections The corrections.
	 */
	public static function add_corrections( $post_id, $corrections ) {
		foreach ( $corrections as $correction ) {
			$id = wp_insert_post(
				[
					'post_title'   => 'Correction for ' . get_the_title( $post_id ),
					'post_content' => $correction['content'],
					'post_date'    => $correction['date'],
					'post_type'    => self::POST_TYPE,
					'post_status'  => 'publish',
					'meta_input'   => [
						self::CORRECTION_POST_ID_META => $post_id,
					],
				]
			);
			if ( ! \is_wp_error( $id ) ) {
				$correction_ids[] = $id;
			}
		}
	}

	/**
	 * Get corrections for post.
	 *
	 * @param int $post_id The post ID.
	 *
	 * @return array The corrections.
	 */
	public static function get_corrections( $post_id ) {
		return get_posts(
			[
				'posts_per_page' => -1,
				'post_type'      => self::POST_TYPE,
				'meta_key'       => self::CORRECTION_POST_ID_META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $post_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);
	}

	/**
	 * Update correction.
	 *
	 * @param int   $correction_id the post id.
	 * @param array $correction    the correction.
	 */
	public static function update_correction( $correction_id, $correction ) {
		wp_update_post(
			[
				'ID'           => $correction_id,
				'post_content' => sanitize_textarea_field( $correction['content'] ),
				'post_date'    => sanitize_text_field( $correction['date'] ),
			]
		);
	}

	/**
	 * Delete corrections for post.
	 *
	 * @param int   $post_id        the post id.
	 * @param array $correction_ids correction ids.
	 */
	public static function delete_corrections( $post_id, $correction_ids ) {
		foreach ( $correction_ids as $id ) {
			wp_delete_post( $id, true );
		}
	}

	/**
	 * Adds the corrections shortcode.
	 */
	public static function add_corrections_shortcode() {
		add_shortcode( 'corrections', [ __CLASS__, 'handle_corrections_shortcode' ] );
	}

	/**
	 * Handles the corrections shortcode.
	 *
	 * @return string the shortcode output.
	 */
	public static function handle_corrections_shortcode() {
		global $wpdb;

		$post_ids = get_posts(
			[
				'posts_per_page' => -1,
				'meta_key'       => self::CORRECTIONS_ACTIVE_META,
				'meta_value'     => 1, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'fields'         => 'ids',
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);

		ob_start();
		foreach ( $post_ids as $post_id ) :
			$corrections = self::get_corrections( $post_id );
			if ( empty( $corrections ) ) {
				continue;
			}

			?>
			<!-- wp:group {"className":"is-style-default correction-shortcode-item"} -->
			<div class="wp-block-group is-style-default correction-shortcode-item">
				<div class="wp-block-group__inner-container">
					<!-- wp:newspack-blocks/homepage-articles {"showExcerpt":false,"showDate":false,"showAuthor":false,"mediaPosition":"left","specificPosts":["<?php echo intval( $post_id ); ?>"],"imageScale":2,"specificMode":true} /-->

					<div class="correction-list">
						<?php
						foreach ( $corrections as $correction ) :
							$correction_content = $correction->post_content;
							$correction_date    = \get_the_date( 'M j, Y', $correction->ID );
							$correction_heading = sprintf(
								// translators: %s: correction date.
								__( 'Correction on %s', 'newspack-plugin' ),
								$correction_date
							);
							?>
							<p>
								<span class="correction-date"><?php echo esc_html( $correction_heading ); ?><span>:</span></span>
								<?php echo esc_html( $correction_content ); ?>
							</p>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<!-- /wp:group -->
			<?php
		endforeach;
		return do_blocks( ob_get_clean() );
	}

	/**
	 * Adds the corrections metabox.
	 *
	 * @param string $post_type the post type.
	 */
	public static function add_corrections_metabox( $post_type ) {
		$valid_post_types = [ 'article_legacy', 'content_type_blog', 'post', 'press_release' ];
		if ( in_array( $post_type, $valid_post_types, true ) ) {
			add_meta_box(
				'corrections',
				'Corrections',
				[ __CLASS__, 'render_corrections_metabox' ],
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Renders the corrections metabox.
	 *
	 * @param \WP_Post $post the post object.
	 */
	public static function render_corrections_metabox( $post ) {
		$is_active   = get_post_meta( $post->ID, self::CORRECTIONS_ACTIVE_META, true );
		$location    = get_post_meta( $post->ID, self::CORRECTIONS_LOCATION_META, true );
		$corrections = self::get_corrections( $post->ID );
		?>
		<div class="corrections-metabox-container">
			<div class="activate-corrections">
				<input type="hidden" value="0" name="<?php echo esc_attr( self::CORRECTIONS_ACTIVE_META ); ?>" />
				<input type="checkbox" class="activate-corrections-checkbox" value="1" name="<?php echo esc_attr( self::CORRECTIONS_ACTIVE_META ); ?>" <?php checked( 0 != $is_active ); ?> />
				<?php echo esc_html( __( 'activate corrections', 'newspack-plugin' ) ); ?>
			</div>
			<div class="display-corrections">
				<select name="<?php echo esc_attr( self::CORRECTIONS_LOCATION_META ); ?>" />
					<option value=""><?php echo esc_html( __( 'Select Location', 'newspack-plugin' ) ); ?></option>
					<option value="bottom" <?php selected( $location, 'bottom' ); ?>><?php echo esc_html( __( 'Bottom', 'newspack-plugin' ) ); ?></option>
					<option value="top" <?php selected( $location, 'top' ); ?>><?php echo esc_html( __( 'Top', 'newspack-plugin' ) ); ?></option>
				</select>
			</div>
			<div class="manage-corrections">
				<fieldset name="existing-corrections[]" class="existing-corrections">
					<?php
					foreach ( $corrections as $correction ) :
						$correction_content = $correction->post_content;
						$correction_date    = \get_the_date( 'Y-m-d', $correction->ID );
						?>
						<fieldset name="existing-corrections[<?php echo esc_attr( $correction->ID ); ?>]" class="correction">
							<p><?php echo esc_html( __( 'Article Correction', 'newspack-plugin' ) ); ?></p>
							<textarea name="existing-corrections[<?php echo esc_attr( $correction->ID ); ?>][content]" rows="3" cols="60"><?php echo esc_html( $correction_content ); ?></textarea>
							<br/>
							<p>
								<?php echo esc_html( __( 'Date:', 'newspack_plugin' ) ); ?>
								<input name="existing-corrections[<?php echo esc_attr( $correction->ID ); ?>][date]" type="date" value="<?php echo esc_attr( sanitize_text_field( $correction_date ) ); ?>">
							</p>
							<button class="delete-correction">X</button>
						</fieldset>
					<?php endforeach; ?>
				</fieldset>
				<fieldset name="new-corrections[]" class="new-corrections"></fieldset>
				<fieldset name="deleted-corrections[]" class="deleted-corrections"></fieldset>
				<button type="button" class="add-correction"><?php echo esc_html( __( 'Add new correction', 'newspack-plugin' ) ); ?></button>
			</div>
		</div>
		<?php
	}

	/**
	 * Saves the corrections metabox.
	 *
	 * @param int $post_id the post id.
	 */
	public static function save_corrections_metabox( $post_id ) {
		// return early if we are saving a correction.
		if ( self::POST_TYPE === get_post_type( $post_id ) ) {
			return;
		}

		$corrections_active   = filter_input( INPUT_POST, self::CORRECTIONS_ACTIVE_META, FILTER_SANITIZE_NUMBER_INT );
		$corrections_location = filter_input( INPUT_POST, self::CORRECTIONS_LOCATION_META, FILTER_SANITIZE_STRING );
		$corrections_data     = filter_input_array(
			INPUT_POST,
			[
				'existing-corrections' => [
					'flags'  => FILTER_REQUIRE_ARRAY,
					'filter' => FILTER_DEFAULT,
				],
				'new-corrections'      => [
					'flags'  => FILTER_REQUIRE_ARRAY,
					'filter' => FILTER_DEFAULT,
				],
				'deleted-corrections'  => [
					'flags'  => FILTER_REQUIRE_ARRAY,
					'filter' => FILTER_SANITIZE_NUMBER_INT,
				],
			]
		);
		// return early if there is no corrections data.
		if ( false === $corrections_active && false === $corrections_location && empty( $corrections_data ) ) {
			return;
		}
		// update active flag if present.
		if ( $corrections_active != get_post_meta( $post_id, self::CORRECTIONS_ACTIVE_META, true ) ) {
			update_post_meta( $post_id, self::CORRECTIONS_ACTIVE_META, $corrections_active );
		}
		// update location flag if present.
		if ( $corrections_location !== get_post_meta( $post_id, self::CORRECTIONS_LOCATION_META, true ) ) {
			update_post_meta( $post_id, self::CORRECTIONS_LOCATION_META, sanitize_text_field( $corrections_location ) );
		}
		// update existing corrections if present.
		if ( ! empty( $corrections_data['existing-corrections'] ) ) {
			foreach ( $corrections_data['existing-corrections'] as $correction_id => $correction ) {
				// don't save empty corrections.
				if ( empty( trim( $correction['content'] ) ) ) {
					continue;
				}
				self::update_correction( $correction_id, $correction );
			}
		}
		// save new corrections if present.
		if ( ! empty( $corrections_data['new-corrections'] ) ) {
			$corrections = [];
			foreach ( $corrections_data['new-corrections'] as $correction ) {
				// don't save empty corrections.
				if ( empty( trim( $correction['content'] ) ) ) {
					continue;
				}
				$corrections[] = [
					'content' => sanitize_textarea_field( $correction['content'] ),
					'date'    => ! empty( $correction['date'] ) ? sanitize_text_field( $correction['date'] ) : gmdate( 'Y-m-d' ),
				];
			}
			self::add_corrections( $post_id, $corrections );
		}
		// delete corrections if present.
		if ( ! empty( $corrections_data['deleted-corrections'] ) ) {
			$correction_ids = array_map( 'intval', $corrections_data['deleted-corrections'] );
			self::delete_corrections( $post_id, $correction_ids );
		}
	}

	/**
	 * Outputs corrections on the post content.
	 *
	 * @param string $content the post content.
	 *
	 * @return string the post content with corrections.
	 */
	public static function output_corrections_on_post( $content ) {
		if ( is_admin() || ! is_single() ) {
			return $content;
		}

		if ( 0 == get_post_meta( get_the_ID(), self::CORRECTIONS_ACTIVE_META, true ) ) {
			return $content;
		}

		$corrections = self::get_corrections( get_the_ID() );
		if ( empty( $corrections ) ) {
			return $content;
		}

		ob_start();
		?>
		<!-- wp:group {"className":"correction-module","backgroundColor":"light-gray"} -->
		<div class="wp-block-group correction-module has-light-gray-background-color has-background">
			<div class="wp-block-group__inner-container">
			<?php
			foreach ( $corrections as $correction ) :
				$correction_content = $correction->post_content;
				$correction_date    = \get_the_date( 'M j, Y', $correction->ID );
				$correction_heading = sprintf(
					// translators: %s: correction date.
					__( 'Correction on %s', 'newspack-plugin' ),
					$correction_date
				);
				?>
				<!-- wp:paragraph {"fontSize":"small"} -->
				<p class="has-small-font-size correction-heading"><?php echo esc_html( $correction_heading ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"fontSize":"normal"} -->
				<p class="has-normal-font-size correction-body"><?php echo esc_html( $correction_content ); ?></p>
				<!-- /wp:paragraph -->
			<?php endforeach; ?>
			</div>
		</div>
		<!-- /wp:group -->
		<?php
		$markup = do_blocks( ob_get_clean() );
		return 'top' === get_post_meta( get_the_ID(), self::CORRECTIONS_LOCATION_META, true ) ? $markup . $content : $content . $markup;
	}
}
Corrections::init();
