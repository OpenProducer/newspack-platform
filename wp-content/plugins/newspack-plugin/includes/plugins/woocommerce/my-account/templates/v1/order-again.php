<?php
/**
 * Order again button.
 *
 * @author   Newspack
 * @category WooCommerce Subscriptions/Templates
 * @package  Newspack
 */

defined( 'ABSPATH' ) || exit;

$wp_button_class = 'newspack-ui__button newspack-ui__button--secondary';
$button_text     = wcs_order_contains_subscription( $order, 'parent' ) ? __( 'Renew subscription', 'newspack-plugin' ) : __( 'Order again', 'newspack-plugin' );
?>

<p class="order-again">
	<a href="<?php echo esc_url( $order_again_url ); ?>" class="<?php echo esc_attr( $wp_button_class ); ?>"><?php echo esc_html( $button_text ); ?></a>
</p>
