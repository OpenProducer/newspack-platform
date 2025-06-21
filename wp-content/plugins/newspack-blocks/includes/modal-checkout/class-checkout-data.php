<?php
/**
 * Newspack Blocks Modal Checkout Data.
 *
 * @package Newspack
 */

namespace Newspack_Blocks\Modal_Checkout;

/**
 * Checkout Data Class.
 */
final class Checkout_Data {
	/**
	 * Get price string for the price summary card to render in auth flow.
	 *
	 * @param string $name      The name.
	 * @param string $price     The price. Optional. If not provided, the price string will contain 0.
	 * @param string $frequency The frequency. Optional. If not provided, the price will be treated as a one-time payment.
	 *
	 * @return string The price string.
	 */
	public static function get_price_summary( $name, $price = '', $frequency = '' ) {
		if ( ! $price ) {
			$price = '0';
		}

		if ( function_exists( 'wcs_price_string' ) && function_exists( 'wc_price' ) ) {
			if ( $frequency && $frequency !== 'once' ) {
				$price = wp_strip_all_tags(
					wcs_price_string(
						[
							'recurring_amount'    => $price,
							'subscription_period' => $frequency,
							'use_per_slash'       => true,
						]
					)
				);
			} else {
				$price = wp_strip_all_tags( wc_price( $price ) );
			}
		}

		// translators: 1 is the name of the item. 2 is the price of the item.
		return sprintf( __( '%1$s: %2$s', 'newspack-blocks' ), $name, $price );
	}

	/**
	 * Returns whether a product is a one time purchase, or recurring and when.
	 *
	 * @param string $product_id Product's ID.
	 */
	public static function get_purchase_recurrence( $product_id ) {
		$recurrence = get_post_meta( $product_id, '_subscription_period', true );
		if ( empty( $recurrence ) ) {
			$recurrence = 'once';
		}
		return $recurrence;
	}

	/**
	 * Returns whether a product ID is associated with a membership.
	 *
	 * @param string $product_id Product's ID.
	 */
	public static function is_membership_product( $product_id ) {
		if ( ! function_exists( 'wc_memberships_get_membership_plans' ) ) {
			return false;
		}
		$membership_plans = wc_memberships_get_membership_plans();
		$plans            = [];

		foreach ( $membership_plans as $plan ) {
			$subscription_plan  = new \WC_Memberships_Integration_Subscriptions_Membership_Plan( $plan->get_id() );
			$required_products = $subscription_plan->get_subscription_product_ids();
			if ( in_array( $product_id, $required_products ) ) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Returns the product type: product, subscription, donation, or membership.
	 *
	 * @param string $product_id Product's ID.
	 */
	public static function get_product_type( $product_id ) {
		$product_type = 'product';
		$recurrence   = self::get_purchase_recurrence( $product_id );

		// Check if it's a subscription product.
		if ( 'once' !== $recurrence ) {
			$product_type = 'subscription';
		}

		// Check if it's a membership product.
		if ( self::is_membership_product( $product_id ) ) {
			$product_type = 'membership';
		}

		// Check if it's a donation product.
		if ( method_exists( 'Newspack\Donations', 'is_donation_product' ) ) {
			if ( \Newspack\Donations::is_donation_product( $product_id ) ) {
				$product_type = 'donation';
			}
		}

		return $product_type;
	}

	/**
	 * Returns the action type: checkout_button or donation.
	 *
	 * @param string $product_id Product's ID.
	 */
	public static function get_action_type( $product_id ) {
		$action_type = 'checkout_button';
		// Check if it's a donation product, and update action_type, product_type.
		if ( method_exists( 'Newspack\Donations', 'is_donation_product' ) ) {
			if ( \Newspack\Donations::is_donation_product( $product_id ) ) {
				$action_type = 'donation';
			}
		}
		return $action_type;
	}

	/**
	 * Get donate product checkout data.
	 *
	 * @param string $frequency The frequency.
	 * @param int    $amount    The amount.
	 *
	 * @return array
	 */
	public static function get_donation_checkout_data( $frequency, $amount = null ) {
		if ( ! method_exists( 'Newspack\Donations', 'get_donation_product' ) ) {
			return [];
		}
		$product_id = \Newspack\Donations::get_donation_product( $frequency );
		if ( ! $product_id ) {
			return [];
		}
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return [];
		}
		$data = self::get_checkout_data( $product );
		if ( $amount ) {
			$data['amount'] = $amount;
		}
		return $data;
	}

	/**
	 * Returns checkout data given a product, product variation, cart or order object.
	 *
	 * @param \WC_Product|\WC_Product_Variation|\WC_Cart|\WC_Order $source Product, product variation, cart or order object.
	 *
	 * @return array
	 */
	public static function get_checkout_data( $source ) {
		$data = [];
		if ( empty( $source ) ) {
			return $data;
		}

		$cart_item    = null;
		$order        = null;
		$referrer     = '';
		$variation_id = null;

		if ( $source instanceof \WC_Product_Variation ) {
			$product_id = $source->get_parent_id();
			$variation_id = $source->get_id();
			$amount = $source->get_price();
		} elseif ( $source instanceof \WC_Product ) {
			$product_id = $source->get_id();
			if ( $source->get_parent_id() ) {
				$product_id   = $source->get_parent_id();
				$variation_id = $source->get_id();
			}
			$amount = $source->get_price();
		} elseif ( $source instanceof \WC_Cart ) {
			$cart_items   = $source->get_cart();
			$cart_item    = reset( $cart_items ); // Use only the first item in the cart.
			$product_id   = $cart_item['product_id'];
			$variation_id = $cart_item['variation_id'];
			$amount       = $cart_item['data']->get_price();
			$referrer     = $cart_item['referer'] ?? '';
		} elseif ( $source instanceof \WC_Order ) {
			$order        = $source;
			$order_items  = $order->get_items();
			$order_item   = reset( $order_items ); // Use only the first item in the order.
			$product_id   = $order_item->get_product_id();
			$variation_id = $order_item->get_variation_id();
			$amount       = $order_item->get_subtotal();
			$referrer     = $order->get_meta( '_newspack_referer' );
		}

		// If we have no referrer, set it to the current path.
		if ( ! $referrer ) {
			global $wp;
			$referrer = $wp->request;
		}

		$product_type = self::get_product_type( $product_id );
		$recurrence   = self::get_purchase_recurrence( $product_id );

		/**
		 * Price summary name.
		 */
		if ( 'donation' === $product_type ) {
			$name = __( 'Donate', 'newspack-blocks' );
		} elseif ( $variation_id ) {
			$variation = wc_get_product( $variation_id );
			$name = $variation->get_name();
		} else {
			$product = wc_get_product( $product_id );
			$name = $product->get_name();
		}

		$data = [
			'amount'           => $amount,
			'action_type'      => self::get_action_type( $product_id ),
			'currency'         => function_exists( 'get_woocommerce_currency' ) ? \get_woocommerce_currency() : 'USD',
			'product_id'       => strval( $product_id ? $product_id : '' ),
			'product_type'     => $product_type,
			'price_summary'    => self::get_price_summary( $name, $amount, $recurrence ),
			'summary_template' => self::get_price_summary( $name, '{{PRICE}}', $recurrence ),
			'referrer'         => $referrer ? str_replace( home_url(), '', $referrer ) : '', // Keeps format consistent for Homepage with Donate and Checkout Button blocks.
			'recurrence'       => $recurrence,
			'variation_id'     => strval( $variation_id ? $variation_id : '' ),
		];

		/**
		 * Order specific data.
		 */
		if ( $order ) {
			$data['order_id'] = $order->get_id();
			if ( in_array( $product_type, [ 'subscription', 'membership' ], true ) ) {
				$subscription_renewal = $order->get_meta( '_subscription_renewal' );
				if ( $subscription_renewal ) {
					$data['subscription_renewal'] = $subscription_renewal;
				}
				if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
					$subscriptions = wcs_get_subscriptions_for_order( $order );
					if ( ! empty( $subscriptions ) ) {
						$data['subscription_ids'] = array_values(
							array_map(
								function( $subscription ) {
									return $subscription->get_id();
								},
								$subscriptions
							)
						);
					}
				}
			}
		}

		/**
		 * Gate and popup data.
		 */
		$gate_post_id = null;
		$newspack_popup_id = null;
		if ( $order ) {
			$gate_post_id = $order->get_meta( '_memberships_content_gate' );
			$newspack_popup_id = $order->get_meta( '_newspack_popup_id' );
		} elseif ( $cart_item ) {
			$gate_post_id = $cart_item['memberships_content_gate'] ?? null;
			$newspack_popup_id = $cart_item['newspack_popup_id'] ?? null;
		} else {
			$gate_post_id = filter_input( INPUT_GET, 'memberships_content_gate', FILTER_SANITIZE_NUMBER_INT );
			$newspack_popup_id = filter_input( INPUT_GET, 'newspack_popup_id', FILTER_SANITIZE_NUMBER_INT );
		}
		if ( $gate_post_id ) {
			$data['gate_post_id'] = $gate_post_id;
		}
		if ( $newspack_popup_id ) {
			$data['newspack_popup_id'] = $newspack_popup_id;
		}

		return $data;
	}
}
