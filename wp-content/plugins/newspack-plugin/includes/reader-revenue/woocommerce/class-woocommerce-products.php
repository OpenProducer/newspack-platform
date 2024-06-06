<?php
/**
 * Extensions for WooCommerce products.
 *
 * @package Newspack
 */

namespace Newspack;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Connection with WooCommerce's features.
 */
class WooCommerce_Products {
	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		\add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ] );
		\add_filter( 'product_type_options', [ __CLASS__, 'show_custom_product_options' ] );
		\add_action( 'woocommerce_variation_options', [ __CLASS__, 'show_custom_variation_options' ], 10, 3 );
		\add_action( 'woocommerce_process_product_meta', [ __CLASS__, 'save_custom_product_options' ] );
		\add_action( 'woocommerce_admin_process_variation_object', [ __CLASS__, 'save_custom_variation_options' ], 30, 2 );
		\add_filter( 'woocommerce_order_item_needs_processing', [ __CLASS__, 'require_order_processing' ], 10, 2 );
	}

	/**
	 * Enqueue admin scripts.
	 */
	public static function admin_enqueue_scripts() {
		wp_enqueue_script(
			'newspack-products-custom-options',
			Newspack::plugin_url() . '/dist/other-scripts/custom-product-options.js',
			[],
			NEWSPACK_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Get custom product options. Product option values are always booleans but represented by string.
	 * See: https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/includes/wc-formatting-functions.php#L37
	 *
	 * @return array Keyed array of custom product options.
	 */
	public static function get_custom_options() {
		return [
			'newspack_autocomplete_orders' => [
				'id'            => '_newspack_autocomplete_orders',
				'wrapper_class' => 'show_if_virtual',
				'label'         => __( 'Auto-complete orders', 'newspack-plugin' ),
				'description'   => __( 'Allow orders containing this product to automatically complete upon successful payment.', 'newspack-plugin' ),
				'default'       => 'yes',
			],
		];
	}

	/**
	 * Get the value of a custom product option, taking defaults into account.
	 *
	 * @param \WC_Product|int $product The product object or ID.
	 * @param string          $option_name The name of the option.
	 *
	 * @return bool|null The value of the option as converted by wc_string_to_bool,
	 *                   or null if the product or option isn't valid.
	 */
	public static function get_custom_option_value( $product, $option_name ) {
		$custom_options = self::get_custom_options();
		if ( ! isset( $custom_options[ $option_name ] ) ) {
			return null;
		}

		$product = is_a( $product, 'WC_Product' ) ? $product : \wc_get_product( $product );
		if ( ! $product ) {
			return null;
		}

		$custom_option = $custom_options[ $option_name ];
		$meta_key      = $custom_option['id'];
		$option_value  = $product->meta_exists( $meta_key ) ? $product->get_meta( $meta_key ) : $custom_option['default'];
		return \wc_string_to_bool( $option_value );
	}

	/**
	 * Add custom options to the Product Data panel.
	 * See: https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/includes/admin/wc-admin-functions.php#L574
	 *
	 * @param array $options Keyed array of product type options.
	 *
	 * @return array
	 */
	public static function show_custom_product_options( $options ) {
		$custom_options = self::get_custom_options();
		foreach ( $custom_options as $option_key => $option_config ) {
			if ( ! isset( $options[ $option_key ] ) ) {
				$options[ $option_key ] = $option_config;
			}
		}
		return $options;
	}

	/**
	 * Add custom options to product variations.
	 *
	 * @param int     $loop The index of the variation within its parent product.
	 * @param array   $variation_data The variation data.
	 * @param WP_Post $variation The variation's post object.
	 */
	public static function show_custom_variation_options( $loop, $variation_data, $variation ) {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return;
		}

		$custom_options = self::get_custom_options();
		$variation      = \wc_get_product( $variation->ID );

		foreach ( $custom_options as $option_key => $option_config ) {
			$meta_key = $option_config['id'];
			?>
			<label class="tips show_if_variation_virtual" data-tip="<?php echo esc_attr( $option_config['description'] ); ?>">
				<?php echo esc_html( $option_config['label'] ); ?>
				<input
					type="checkbox" class="checkbox show_if_variation_virtual variable_<?php echo esc_attr( $option_key ); ?>"
					name="<?php echo esc_attr( $meta_key . '[' . $loop . ']' ); ?>"
					<?php \checked( self::get_custom_option_value( $variation, $option_key ), true ); ?>
				/>
			</label>
			<?php
		}
	}

	/**
	 * Save custom product option values when a product is saved.
	 *
	 * @param int $post_id The product post ID being saved.
	 */
	public static function save_custom_product_options( $post_id ) {
		if ( ! function_exists( 'wc_get_product' ) || ! function_exists( 'wc_bool_to_string' ) ) {
			return;
		}

		$product = \wc_get_product( $post_id );
		if ( ! $product ) {
			return;
		}

		$custom_options = self::get_custom_options();
		foreach ( $custom_options as $option_key => $option_config ) {
			$meta_key = $option_config['id'];
			$option_value = isset( $_POST[ $meta_key ] ) ? \wc_bool_to_string( true ) : \wc_bool_to_string( false ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$product->update_meta_data( $option_config['id'], $option_value );
		}

		$product->save();
	}

	/**
	 * Save custom variation option values when a variation is saved.
	 *
	 * @param WC_Product_Variation $variation The variation object being saved.
	 * @param int                  $i The index of the variation within its parent product.
	 */
	public static function save_custom_variation_options( $variation, $i ) {
		$is_legacy = is_numeric( $variation );

		// Need to instantiate the product object on WC<3.8.
		if ( $is_legacy ) {
			$variation = \wc_get_product( $variation );
		}
		if ( ! $variation ) {
			return;
		}

		$custom_options = self::get_custom_options();
		foreach ( $custom_options as $option_key => $option_config ) {
			$meta_key = $option_config['id'];
			$option_value = isset( $_POST[ $meta_key ] ) ? \wc_bool_to_string( true ) : \wc_bool_to_string( false ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$variation->update_meta_data( $option_config['id'], $option_value );
		}

		// Save the meta on WC<3.8.
		if ( $is_legacy ) {
			$variation->save();
		}
	}

	/**
	 * If the order item is tied to a product that's set to autocomplete, then allow the order to autocomplete.
	 * By default, Woo will require processing for any order containing items that aren't both downloadable and virtual.
	 *
	 * @param boolean    $needs_proccessing If true, the item needs processing. If not, allow the order to autocomplete.
	 * @param WC_Product $product The product associated with this order item.
	 */
	public static function require_order_processing( $needs_proccessing, $product ) {
		if ( $product->is_virtual() ) {
			return self::get_custom_option_value( $product, 'newspack_autocomplete_orders' ) ? false : $needs_proccessing;
		}

		return $needs_proccessing;
	}
}

WooCommerce_Products::init();
