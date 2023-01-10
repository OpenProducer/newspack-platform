<?php
/**
 * Newspack Newsletters Subscription Lists
 *
 * @package Newspack
 */

namespace Newspack\Newsletters;

use Newspack_Newsletters;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Newsletters Subscription Lists class.
 *
 * Subscriptions Lists are Lists which readers can subscribe to. AKA Newsletters.
 *
 * Each List is associated with a Audience/List in the Provider and can be associated to one or more tags in the provider
 */
class Subscription_Lists {

	/**
	 * CPT for Newsletter Lists.
	 */
	const CPT = 'newspack_nl_list';

	/**
	 * Initialize this class and register hooks
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! self::should_initialize_lists() ) {
			return;
		}
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'admin_menu', [ __CLASS__, 'add_submenu_item' ] );
		add_filter( 'wp_editor_settings', [ __CLASS__, 'filter_editor_settings' ], 10, 2 );
		add_action( 'save_post', [ __CLASS__, 'save_post' ] );
		add_filter( 'manage_' . self::CPT . '_posts_columns', [ __CLASS__, 'posts_columns' ] );
		add_action( 'manage_' . self::CPT . '_posts_custom_column', [ __CLASS__, 'posts_columns_values' ], 10, 2 );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ] );
	}

	/**
	 * Add custom CSS to the List post type edit screen
	 */
	public static function admin_enqueue_scripts() {
		if ( get_current_screen()->post_type === self::CPT ) {
			wp_enqueue_style(
				'newspack-newsletters-subscription-list-editor',
				plugins_url( '../css/subscription-list-editor.css', __FILE__ ),
				[],
				filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'css/subscription-list-editor.css' )
			);
		}
	}

	/**
	 * Check if we should initialize the Subscription lists
	 *
	 * @return boolean
	 */
	public static function should_initialize_lists() {
		// We only need this on admin.
		if ( ! is_admin() ) {
			return false;
		}
		
		// If Service Provider is not configured yet.
		if ( 'manual' === Newspack_Newsletters::service_provider() || ! Newspack_Newsletters::is_service_provider_configured() ) {
			return false;
		}

		$provider = Newspack_Newsletters::get_service_provider();

		// Only init if current provider supports tags.
		return $provider::$support_tags;

	}

	/**
	 * Disable Rich text editing from the editor
	 *
	 * @param array  $settings The settings to be filtered.
	 * @param string $editor_id The editor identifier.
	 * @return array
	 */
	public static function filter_editor_settings( $settings, $editor_id ) {
		if ( 'content' === $editor_id && get_current_screen()->post_type === self::CPT ) {
			$settings['tinymce']       = false;
			$settings['quicktags']     = false;
			$settings['media_buttons'] = false;
		}
	
		return $settings;
	}

	/**
	 * Add Submenu item for Lists
	 */
	public static function add_submenu_item() {
		add_submenu_page(
			'edit.php?post_type=' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
			__( 'Lists', 'newspack-newsletters' ),
			__( 'Lists', 'newspack-newsletters' ),
			'edit_others_posts',
			'/edit.php?post_type=' . self::CPT,
			null,
			2
		);
	}

	/**
	 * Register the custom post type
	 *
	 * @return void
	 */
	public static function register_post_type() {

		$labels = array(
			'name'                  => _x( 'Lists', 'Post Type General Name', 'newspack' ),
			'singular_name'         => _x( 'List', 'Post Type Singular Name', 'newspack' ),
			'menu_name'             => __( 'Lists', 'newspack' ),
			'name_admin_bar'        => __( 'Lists', 'newspack' ),
			'archives'              => __( 'LIsts', 'newspack' ),
			'attributes'            => __( 'Lists', 'newspack' ),
			'parent_item_colon'     => __( 'Parent List', 'newspack' ),
			'all_items'             => __( 'All Lists', 'newspack' ),
			'add_new_item'          => __( 'Add new list', 'newspack' ),
			'add_new'               => __( 'Add New', 'newspack' ),
			'new_item'              => __( 'New List', 'newspack' ),
			'edit_item'             => __( 'Edit list', 'newspack' ),
			'update_item'           => __( 'Update list', 'newspack' ),
			'view_item'             => __( 'View list', 'newspack' ),
			'view_items'            => __( 'View Lists', 'newspack' ),
			'search_items'          => __( 'Search List', 'newspack' ),
			'not_found'             => __( 'Not found', 'newspack' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'newspack' ),
			'featured_image'        => __( 'Featured Image', 'newspack' ),
			'set_featured_image'    => __( 'Set featured image', 'newspack' ),
			'remove_featured_image' => __( 'Remove featured image', 'newspack' ),
			'use_featured_image'    => __( 'Use as featured image', 'newspack' ),
			'insert_into_item'      => __( 'Insert into item', 'newspack' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'newspack' ),
			'items_list'            => __( 'Items list', 'newspack' ),
			'items_list_navigation' => __( 'Items list navigation', 'newspack' ),
			'filter_items_list'     => __( 'Filter items list', 'newspack' ),
		);
		$args   = array(
			'label'                => __( 'List', 'newspack' ),
			'description'          => __( 'Newsletter list', 'newspack' ),
			'labels'               => $labels,
			'supports'             => array( 'title', 'editor' ),
			'hierarchical'         => false,
			'public'               => false,
			'show_ui'              => true,
			'show_in_menu'         => false,
			'can_export'           => false,
			'capability_type'      => 'page',
			'show_in_rest'         => false,
			'delete_with_user'     => false,
			'register_meta_box_cb' => [ __CLASS__, 'add_metabox' ],
		);
		register_post_type( self::CPT, $args );
	}

	/**
	 * Adds post type metaboxes
	 *
	 * @param WP_Post $post The current post.
	 * @return void
	 */
	public static function add_metabox( $post ) {
		add_meta_box(
			'newspack-newsletters-list-metabox',
			__( 'Provider settings' ),
			[ __CLASS__, 'metabox_content' ],
			self::CPT,
			'side',
			'high'
		);
	}

	/**
	 * Modify columns on post type table
	 *
	 * @param array $columns Registered columns.
	 * @return array
	 */
	public static function posts_columns( $columns ) {  
		unset( $columns['date'] );
		unset( $columns['stats'] );
		$columns['active_providers'] = __( 'Service Providers', 'newspack-newsletters' );
		return $columns;
		
	}

	/**
	 * Add content to the custom column
	 *
	 * @param string $column The current column.
	 * @param int    $post_id The current post ID.
	 * @return void
	 */
	public static function posts_columns_values( $column, $post_id ) {
		if ( 'active_providers' === $column ) {
			$list = new Subscription_List( $post_id );
			foreach ( $list->get_configured_providers() as $provider ) {
				$settings     = $list->get_provider_settings( $provider );
				$provider_obj = Newspack_Newsletters::get_service_provider_instance( $provider );
				?>
				<p>
					<?php echo esc_html( $provider_obj::label( 'name' ) ); ?>:
					<span class="subscription-list-tag">
						<?php echo esc_html( $settings['tag_name'] ); ?>
					</span>
				</p>
				<?php

			}
		}
	}

	/**
	 * Outputs metabox content
	 *
	 * @param WP_Post $post The current post.
	 * @return void
	 */
	public static function metabox_content( $post ) {
		$subscription_list = new Subscription_List( $post );
		$current_provider  = Newspack_Newsletters::get_service_provider();
		$empty_message     = '';
		$current_settings  = array_merge(
			[
				'list'     => null,
				'tag_id'   => null,
				'tag_name' => null,
				'error'    => null,
			],
			(array) $subscription_list->get_current_provider_settings()
		);

		if ( empty( $current_settings ) ) {

			$empty_message = sprintf(
				// translators: %s is the provider name. Ex: Mailchimp.
				__( 'This list is not yet configured for %s. Please use the fields below to configure where readers should be added to.' ),
				'<b>' . esc_html( $current_provider::label( 'name' ) ) . '</b>'
			);

		}

		$lists = $current_provider->get_lists();

		wp_nonce_field( 'newspack_newsletters_save_list', 'newspack_newsletters_save_list_nonce' );

		?>
		<div class="misc-pub-section">
			<?php if ( ! empty( $empty_message ) ) : ?>
				<p>
					<?php echo wp_kses( $empty_message, 'data' ); ?>
				</p>
			<?php endif; ?>
			<?php if ( ! empty( $current_settings['error'] ) ) : ?>
				<div class="notice notice-error">
					<p>
						<?php echo esc_html( $current_settings['error'] ); ?>
					</p>
				</div>
			<?php endif; ?>
			<label for="newspack_newsletters_list">
				<?php echo esc_html( $current_provider::label( 'List' ) ); ?>:
			</label>
			<select name="newspack_newsletters_list" id="newspack_newsletters_list" style="width: 100%">
				<?php foreach ( $lists as $list ) : ?>

					<option value="<?php echo esc_attr( $list['id'] ); ?>" <?php selected( $current_settings['list'], $list['id'] ); ?> >
						<?php echo esc_html( $list['name'] ); ?>
					</option>

				<?php endforeach; ?>
			</select>
		</div>

		<div class="misc-pub-section">
			<?php if ( ! empty( $current_settings['tag_name'] ) ) : ?>
				<p>
					<?php esc_html_e( 'Tag created for this list', 'newspack-newsletters' ); ?>:
				</p>
				<p class="subscription-list-tag">
					<?php echo esc_html( $current_settings['tag_name'] ); ?>
				</p>
			<?php else : ?>
				<p>
					<?php esc_html_e( 'Once this list is saved, a tag will be created for it.', 'newspack-newsletters' ); ?>
				</p>
			<?php endif; ?>
			<?php 
			/**
			 * Fires after the tag field in the list metabox.
			 *
			 * @param array $current_settings The current list settings.
			 */
			do_action( 'newspack_newsletters_subscription_lists_metabox_after_tag', $current_settings );
			?>
		</div>

		<?php if ( $subscription_list->has_other_configured_providers() ) : ?>
			<div class="misc-pub-section">
				<p>
					<?php esc_html_e( 'Other providers this list is already configured for:', 'newspack-newsletters' ); ?>
					<?php echo esc_html( implode( ', ', $subscription_list->get_other_configured_providers_names() ) ); ?>
				</p>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Save post callback
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @return void
	 */
	public static function save_post( $post_id ) {

		$post_type = sanitize_text_field( $_POST['post_type'] ?? '' );

		if ( self::CPT !== $post_type ) {
			return;
		}

		if ( ! isset( $_POST['newspack_newsletters_save_list_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( $_POST['newspack_newsletters_save_list_nonce'] ), 'newspack_newsletters_save_list' )
		) {
			return;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$post_type_object = get_post_type_object( $post_type );

		if ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) {
			return;
		}

		$list = sanitize_text_field( $_POST['newspack_newsletters_list'] ?? '' );

		if ( empty( $list ) ) {
			return;
		}

		$provider          = Newspack_Newsletters::get_service_provider();
		$subscription_list = new Subscription_List( $post_id );
		$current_settings  = $subscription_list->get_current_provider_settings();
		$tag_id            = $current_settings['tag_id'] ?? false;
		$tag_name          = $current_settings['tag_name'] ?? $subscription_list->generate_tag_name();
		$error             = '';

		if ( $tag_id ) {
			// Check if tag still exists on the ESP. Also, update tag_name if it was changed on the ESP.
			$tag_name = $provider->get_tag_by_id( $current_settings['tag_id'], $list );
			
			if ( is_wp_error( $tag_name ) ) {
				// Tag was not found. We need to create a new one. In Mailchimp, this can happen if you changed the list.
				$tag_id   = false;
				$tag_name = $subscription_list->generate_tag_name();
			}       
		}

		if ( ! $tag_id ) {
			$tag_id = $provider->get_tag_id( $tag_name, true, $list );
			if ( is_wp_error( $tag_id ) ) {
				$error = $tag_id->get_error_message();
			}
		}

		$subscription_list->update_current_provider_settings( $list, $tag_id, $tag_name, $error );

	}

	/**
	 * Methods for fetching Subscription Lists
	 * 
	 * Note: This was built under the assumption that there will never be too many (hundreds) of Lists, so these methods will not scale for large lists.
	 *
	 * If we see that the number of lists grows too much, we might need to refactor these methods and how we store Lists metadata in order to be able to perform more performatic queries using Meta_Queries.
	 */

	/**
	 * Get all Subscription Lists
	 *
	 * @return Subscription_List[]
	 */
	public static function get_all() {
		$posts   = get_posts(
			[
				'post_type'      => self::CPT,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			]
		);
		$objects = [];
		foreach ( $posts as $post ) {
			$objects[] = new Subscription_List( $post );
		}
		return $objects;
	}

	/**
	 * Get Subscription Lists based on a callback to filter them
	 *
	 * @param callable $callback The callback used to filter Lists. It must be a function that takes a Subscription_List instance as argument and returns a boolean whether to include the list to the results or not.
	 * @return Subscription_List[]
	 */
	public static function get_filtered( $callback ) {
		$lists = self::get_all();
		return array_values(
			array_filter(
				$lists,
				function( $list ) use ( $callback ) {
					return call_user_func( $callback, $list );
				}
			)
		);
	}

	/**
	 * Get Lists that are configured to a given provider
	 *
	 * @param string $provider_slug The provider slug to get lists configured for.
	 * @return Subscription_List[]
	 */
	public static function get_configured_for_provider( $provider_slug ) {
		return self::get_filtered(
			function( $list ) use ( $provider_slug ) {
				return $list->is_configured_for_provider( $provider_slug );
			}
		);
	}

	/**
	 * Get Lists that are configured for the current provider
	 *
	 * @return Subscription_List[]
	 */
	public static function get_configured_for_current_provider() {
		return self::get_filtered(
			function( $list ) {
				return $list->is_configured_for_current_provider();
			}
		);
	}
}
