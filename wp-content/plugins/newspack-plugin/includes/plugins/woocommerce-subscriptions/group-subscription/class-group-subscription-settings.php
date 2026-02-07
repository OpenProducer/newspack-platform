<?php
/**
 * Settings for Newspack Group Subscriptions.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Settings class.
 */
class Group_Subscription_Settings {
	/**
	 * Default group subscription settings.
	 */
	const DEFAULT_SETTINGS = [
		'enabled' => false,
		'limit'   => 0,
	];

	/**
	 * Prefix for group subscription meta keys.
	 */
	const GROUP_SUBSCRIPTION_META_PREFIX = '_newspack_group_subscription_';

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		// Add Group Subscription options to subscription and variable subscription product admin pages.
		add_filter( 'newspack_custom_product_options', [ __CLASS__, 'add_custom_product_options' ] );
		add_filter( 'newspack_custom_product_pricing_options', [ __CLASS__, 'add_custom_product_pricing_options' ] );

		// Add Group Subscription options to subscription admin pages.
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ] );
		add_action( 'add_meta_boxes', [ __CLASS__, 'add_group_subscription_meta_box' ], 26, 2 );
		add_action( 'woocommerce_process_shop_order_meta', [ __CLASS__, 'save_group_subscription_meta' ], 10, 2 );
		add_action( 'wp_ajax_newspack_group_subscription_search_users', [ __CLASS__, 'ajax_search_users' ] );
	}

	/**
	 * Enqueue admin scripts.
	 */
	public static function admin_enqueue_scripts() {
		if ( ! function_exists( 'wcs_get_page_screen_id' ) ) {
			return;
		}
		$screen = get_current_screen();
		$is_subscription_screen = in_array(
			$screen->id,
			[
				'edit-shop_subscription',
				'shop_subscription',
				wcs_get_page_screen_id( 'shop_subscription' ),
			],
			true
		);
		if ( ! $is_subscription_screen ) {
			return;
		}
		\wp_enqueue_script(
			'newspack-group-subscription-admin',
			Newspack::plugin_url() . '/dist/group-subscription-admin.js',
			[],
			NEWSPACK_PLUGIN_VERSION,
			true
		);
		\wp_enqueue_style(
			'newspack-group-subscription-admin',
			Newspack::plugin_url() . '/dist/group-subscription-admin.css',
			[],
			NEWSPACK_PLUGIN_VERSION
		);
		\wp_localize_script(
			'newspack-group-subscription-admin',
			'newspackGroupSubscriptions',
			[
				'apiUrl'      => \rest_url( Group_Subscription_API::NAMESPACE ),
				'apiNonce'    => \wp_create_nonce( 'wp_rest' ),
				'placeholder' => __( 'Search for a reader...', 'newspack-plugin' ),
			]
		);
	}

	/**
	 * Add custom product options.
	 *
	 * @param array $custom_options Keyed array of custom product options.
	 *
	 * @return array Keyed array of custom product options.
	 */
	public static function add_custom_product_options( $custom_options ) {
		if ( ! Content_Gate::is_newspack_feature_enabled() ) {
			return $custom_options;
		}
		$custom_options['newspack_group_subscription_enabled'] = [
			'id'            => self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled',
			'label'         => __( 'Group subscription', 'newspack-plugin' ),
			'description'   => __( 'Enable group subscriptions for this product.', 'newspack-plugin' ),
			'default'       => self::DEFAULT_SETTINGS['enabled'],
			'product_types' => [ 'subscription', 'subscription_variation' ],
			'type'          => 'boolean',
			'wrapper_class' => 'show_if_subscription',
		];
		return $custom_options;
	}

	/**
	 * Add custom product pricing options.
	 *
	 * @param array $custom_product_pricing_options Keyed array of custom product pricing options.
	 *
	 * @return array Keyed array of custom product pricing options.
	 */
	public static function add_custom_product_pricing_options( $custom_product_pricing_options ) {
		if ( ! Content_Gate::is_newspack_feature_enabled() ) {
			return $custom_product_pricing_options;
		}
		$custom_product_pricing_options['newspack_group_subscription_limit'] = [
			'id'                => self::GROUP_SUBSCRIPTION_META_PREFIX . 'limit',
			'wrapper_class'     => 'show_if_newspack_group_subscription_enabled',
			'label'             => __( 'Group subscription member limit', 'newspack-plugin' ),
			'desc_tip'          => true,
			'description'       => __( 'Set the maximum number of members for group subscriptions. Set to 0 to allow an unlimited number of group members.', 'newspack-plugin' ),
			'default'           => self::DEFAULT_SETTINGS['limit'],
			'product_types'     => [ 'subscription', 'subscription_variation' ],
			'type'              => 'number',
			'custom_attributes' => [
				'step' => 1,
				'min'  => 0,
			],
		];
		return $custom_product_pricing_options;
	}

	/**
	 * Get the group subscription settings for a product.
	 *
	 * @param WC_Product|int $product The product object or ID.
	 *
	 * @return array The group subscription settings.
	 */
	public static function get_product_settings( $product ) {
		$settings = self::DEFAULT_SETTINGS;
		if ( ! function_exists( 'wc_get_product' ) ) {
			return $settings;
		}
		if ( ! is_a( $product, 'WC_Product' ) ) {
			$product = \wc_get_product( $product );
		}
		if ( ! $product ) {
			return $settings;
		}
		$settings['enabled'] = $product->get_meta( self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled', true ) ? \wc_string_to_bool( $product->get_meta( self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled', true ) ) : self::DEFAULT_SETTINGS['enabled'];
		$settings['limit']   = (int) $product->get_meta( self::GROUP_SUBSCRIPTION_META_PREFIX . 'limit', true ) ?: self::DEFAULT_SETTINGS['limit']; // phpcs:ignore Universal.Operators.DisallowShortTernary.Found

		/**
		 * Filter the group subscription settings for a product.
		 *
		 * @param array $settings The group subscription settings.
		 * @param WC_Product $product The product object.
		 */
		return apply_filters( 'newspack_group_subscription_product_settings', $settings, $product );
	}

	/**
	 * Get the product ID for a subscription.
	 *
	 * @param WC_Subscription|int $subscription The subscription object or ID.
	 *
	 * @return int|false The product ID, or false if no product is found.
	 */
	public static function get_subscription_product_id( $subscription ) {
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			$subscription = \wcs_get_subscription( $subscription );
		}
		if ( ! $subscription ) {
			return false;
		}
		$product_id = false;
		foreach ( $subscription->get_items() as $item ) {
			$product_id = \wcs_get_canonical_product_id( $item );
			if ( $product_id ) {
				break;
			}
		}
		return $product_id;
	}

	/**
	 * Get the group subscription settings for a subscription.
	 *
	 * @param WC_Subscription|int $subscription The subscription object or ID.
	 *
	 * @return array The group subscription settings.
	 */
	public static function get_subscription_settings( $subscription ) {
		if ( ! function_exists( 'wcs_get_subscription' ) || ! function_exists( 'wcs_get_canonical_product_id' ) ) {
			return self::DEFAULT_SETTINGS;
		}
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			$subscription = \wcs_get_subscription( $subscription );
		}
		if ( ! $subscription ) {
			return self::DEFAULT_SETTINGS;
		}
		$product_id          = self::get_subscription_product_id( $subscription );
		$settings            = self::get_product_settings( $product_id );
		$settings['enabled'] = $subscription->get_meta( self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled', true ) ? \wc_string_to_bool( $subscription->get_meta( self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled', true ) ) : $settings['enabled'];
		$settings['limit']   = (int) $subscription->get_meta( self::GROUP_SUBSCRIPTION_META_PREFIX . 'limit', true ) ?: $settings['limit']; // phpcs:ignore Universal.Operators.DisallowShortTernary.Found

		/**
		 * Filter the group subscription settings for a subscription.
		 *
		 * @param array $settings The group subscription settings.
		 * @param WC_Subscription $subscription The subscription object.
		 */
		return apply_filters( 'newspack_group_subscription_settings', $settings, $subscription );
	}

	/**
	 * Update group subscription settings for a subscription.
	 *
	 * @param WC_Subscription|int $subscription The subscription object or ID.
	 * @param array               $settings The group subscription settings.
	 */
	public static function update_subscription_settings( $subscription, $settings ) {
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			$subscription = \wcs_get_subscription( $subscription );
		}
		if ( ! $subscription ) {
			return;
		}
		$previous_settings = self::get_subscription_settings( $subscription );
		$should_save       = false;
		foreach ( $settings as $key => $value ) {
			if ( ! isset( self::DEFAULT_SETTINGS[ $key ] ) ) {
				continue;
			}
			if ( is_bool( self::DEFAULT_SETTINGS[ $key ] ) ) {
				$value = \wc_bool_to_string( $value );
			}
			if ( is_int( self::DEFAULT_SETTINGS[ $key ] ) ) {
				$value = absint( $value );
			}
			if ( $value !== $previous_settings[ $key ] ) {
				$subscription->update_meta_data( self::GROUP_SUBSCRIPTION_META_PREFIX . $key, $value );
				$should_save = true;
			}
		}
		if ( $should_save ) {
			$subscription->save();
		}
	}

	/**
	 * Add Group Subscription meta box to subscription admin pages.
	 *
	 * @param string                  $post_type The post type of the current post being edited.
	 * @param WP_Post|WC_Subscription $post_or_subscription The post or subscription currently being edited.
	 */
	public static function add_group_subscription_meta_box( $post_type, $post_or_subscription ) {
		if ( ! Content_Gate::is_newspack_feature_enabled() || ! function_exists( 'wcs_is_subscription' ) || ! \wcs_is_subscription( $post_or_subscription ) ) {
			return;
		}
		\add_meta_box(
			'newspack-group-subscription',
			__( 'Group subscription', 'newspack-plugin' ),
			[ __CLASS__, 'add_group_subscription_options' ],
			$post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Add Group Subscription options to subscription admin pages.
	 *
	 * @param WC_Subscription $subscription The subscription object.
	 */
	public static function add_group_subscription_options( $subscription ) {
		if ( ! $subscription || ! Content_Gate::is_newspack_feature_enabled() || ! function_exists( 'wcs_is_subscription' ) || ! wcs_is_subscription( $subscription ) ) {
			return;
		}
		$settings = self::get_subscription_settings( $subscription );
		$product  = \wc_get_product( self::get_subscription_product_id( $subscription ) );
		$members  = Group_Subscription::get_members( $subscription );
		?>
		<div class="newspack-group-subscription--container" data-subscription-id="<?php echo \esc_attr( $subscription->get_id() ); ?>">
			<div class="newspack-group-subscription--settings">
				<h3><?php \esc_html_e( 'Settings', 'newspack-plugin' ); ?></h3>
				<p>
					<em>
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: %s: The product edit link or 'the product' if no product is found. */
							__( 'Changing these settings will override settings inherited from %s.', 'newspack-plugin' ),
							$product ? '<a href="' . \admin_url( 'post.php?post=' . ( $product->get_parent_id() ?: $product->get_id() ) . '&action=edit' ) . '">' . $product->get_name() . '</a>' : __( 'the product', 'newspack-plugin' ) // phpcs:ignore Universal.Operators.DisallowShortTernary.Found
						)
					);
					?>
					</em>
				</p>
				<p>
					<label for="<?php echo \esc_attr( self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled' ); ?>">
						<input
							type="checkbox"
							id="<?php echo \esc_attr( self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled' ); ?>"
							name="<?php echo \esc_attr( self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled' ); ?>"
							value="yes"
							<?php checked( $settings['enabled'], true ); ?>
						/>
						<?php \esc_html_e( 'Group subscription enabled', 'newspack-plugin' ); ?>
					</label>
				</p>
				<div class="form-row">
					<?php
					$pricing_options = self::add_custom_product_pricing_options( [] );
					foreach ( $pricing_options as $option_key => $option_config ) {
						if ( $option_key === 'newspack_group_subscription_limit' ) {
							$option_config['value'] = $settings['limit'];
							echo wp_kses_post( \woocommerce_wp_text_input( $option_config ) );
							break;
						}
					}
					?>
				</div>
			</div>
			<div class="newspack-group-subscription--members show_if_newspack_group_subscription_enabled" >
				<h3>
					<?php
					echo wp_kses_post(
						sprintf(
							// translators: %d: The number of group members.
							__( 'Group members (<span class="newspack-group-subscription--members-count">%d</span>)', 'newspack-plugin' ),
							count( $members )
						)
					);
					?>
				</h3>
				<ul class="newspack-group-subscription--members-list">
					<?php
					foreach ( $members as $member_id ) :
						$user = get_user_by( 'id', $member_id );
						if ( ! $user || ! Reader_Activation::is_user_reader( $user ) ) {
							continue;
						}
						?>
						<li>
							<a class="newspack-group-subscription--member-user-link" href="<?php echo \esc_url( \get_edit_user_link( $user->ID ) ); ?>"><?php echo \esc_html( $user->user_email ); ?></a>
							<a title="<?php \esc_attr_e( 'Remove', 'newspack-plugin' ); ?>" href="#" class="newspack-group-subscription--remove-member" data-user-id="<?php echo \esc_attr( $user->ID ); ?>">
								&#215;
								<span class="screen-reader-text"><?php \esc_html_e( 'Remove', 'newspack-plugin' ); ?></span>
						</a>
						</li>
						<?php
					endforeach;
					?>
				</ul>
			</div>
			<div class="newspack-group-subscription--add-member show_if_newspack_group_subscription_enabled form-row">
				<h3><?php \esc_html_e( 'Add new group members', 'newspack-plugin' ); ?></h3>
				<select id="_newspack_group_subscription_member_ids" name="_newspack_group_subscription_member_ids[]">
					<option value="">
						<?php echo \esc_html( 'Select a reader...' ); ?>
					</option>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * Save Group Subscription meta to a subscription.
	 *
	 * @param int             $subscription_id Subscription ID.
	 * @param WC_Subscription $subscription Optional. Subscription object. Default null - will be loaded from the ID.
	 */
	public static function save_group_subscription_meta( $subscription_id, $subscription = null ) {
		if ( ! function_exists( 'wcs_is_subscription' ) || ! function_exists( 'wcs_get_subscription' ) || ! function_exists( 'wc_clean' ) || ! \wcs_is_subscription( $subscription_id ) ) {
			return;
		}

		// Verify save nonce. See: WCS_Meta_Box_Subscription_Data::save().
		if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! \wp_verify_nonce( \wc_clean( \wp_unslash( $_POST['woocommerce_meta_nonce'] ) ), 'woocommerce_save_data' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		// Get subscription object.
		$subscription = is_a( $subscription, 'WC_Subscription' ) ? $subscription : \wcs_get_subscription( $subscription_id );
		$is_enabled   = isset( $_POST[ self::GROUP_SUBSCRIPTION_META_PREFIX . 'enabled' ] );
		$limit        = isset( $_POST[ self::GROUP_SUBSCRIPTION_META_PREFIX . 'limit' ] )
			? absint( wp_unslash( $_POST[ self::GROUP_SUBSCRIPTION_META_PREFIX . 'limit' ] ) )
			: 0;
		self::update_subscription_settings(
			$subscription,
			[
				'enabled' => $is_enabled,
				'limit'   => $limit,
			]
		);
	}
}
Group_Subscription_Settings::init();
