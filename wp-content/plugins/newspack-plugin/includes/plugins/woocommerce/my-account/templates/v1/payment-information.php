<?php
/**
 * My Account Payment Information page. Replaces both "Payment Methods" and "Addresses" pages.
 *
 * @package Newspack
 * @version 8.7.0
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

$saved_methods = \wc_get_customer_saved_methods_list( get_current_user_id() );
$has_methods   = (bool) $saved_methods;
$types         = \wc_get_account_payment_methods_types();

\do_action( 'newspack_woocommerce_before_account_payment_methods', $has_methods ); ?>

<section id="payment-methods">
	<h1 class="newspack-ui__font--m"><?php \esc_html_e( 'Payment methods', 'newspack-plugin' ); ?></h1>
	<?php if ( $has_methods ) : ?>

		<div class="newspack-my-account__payment-methods newspack-ui__row newspack-ui__row--no-padding">
			<?php foreach ( $saved_methods as $type => $methods ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
				<?php foreach ( $methods as $method ) : ?>
					<div class="newspack-ui__box newspack-ui__box--border newspack-ui__box--has-dropdown payment-method<?php echo ! empty( $method['is_default'] ) ? ' default-payment-method' : ''; ?>">
						<?php
						$parsed_date = null;
						if ( ! empty( $method['expires'] ) ) :
							$parsed_date = date_parse_from_format( 'n/y', $method['expires'] );
							if (
								empty( $parsed_date['errors'] ) &&
								! empty( $parsed_date['year'] ) &&
								! empty( $parsed_date['month'] ) &&
								(
									(int) $parsed_date['year'] < (int) gmdate( 'Y' ) ||
									( (int) $parsed_date['year'] === (int) gmdate( 'Y' ) && (int) $parsed_date['month'] < (int) gmdate( 'm' ) )
								)
							) :
								?>
								<span class="newspack-ui__badge newspack-ui__badge--secondary"><?php \esc_html_e( 'Expired', 'newspack-plugin' ); ?></span>
								<?php
							endif;
						endif;
						if ( ! empty( $method['is_default'] ) ) :
							?>
							<span class="newspack-ui__badge newspack-ui__badge--secondary"><?php \esc_html_e( 'Default', 'newspack-plugin' ); ?></span>
							<?php
						endif;
						foreach ( \wc_get_account_payment_methods_columns() as $column_id => $column_name ) :
							?>
							<?php
							if ( \has_action( 'newspack_woocommerce_account_payment_methods_column_' . $column_id ) ) {
								\do_action( 'newspack_woocommerce_account_payment_methods_column_' . $column_id, $method );
							} elseif ( 'method' === $column_id ) {
								?>
								<h3 class="newspack-ui__font--s">
									<strong><?php echo \esc_html( \wc_get_credit_card_type_label( $method['method']['brand'] ) ); ?></strong>
								</h3>
								<?php if ( ! empty( $method['method']['last4'] ) ) : ?>
									<p class="newspack-ui__font--s">
										<?php
										printf(
											/* translators: last 4 digits */
											\esc_html__( 'Ending in %s', 'newspack-plugin' ),
											\esc_html( $method['method']['last4'] )
										);
										?>
									</p>
									<?php
								endif;
							} elseif ( 'expires' === $column_id && isset( $parsed_date['error_count'] ) && 0 === $parsed_date['error_count'] ) {
								?>
								<p class="newspack-ui__font--s">
									<?php
									printf(
										/* translators: expiration date */
										\esc_html__( 'Exp. %s', 'newspack-plugin' ),
										\esc_html( $method['expires'] )
									);
									?>
								</p>
								<?php
							} elseif ( 'actions' === $column_id ) {
								ksort( $method['actions'] );
								?>
								<div class="newspack-ui__dropdown">
									<button class="newspack-ui__dropdown__toggle newspack-ui__button newspack-ui__button--icon newspack-ui__button--ghost">
										<?php \Newspack\Newspack_UI_Icons::print_svg( 'more' ); ?>
										<span class="screen-reader-text">More</span>
									</button>
									<div class="newspack-ui__dropdown__content">
										<ul>
										<?php
										foreach ( $method['actions'] as $key => $action ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
											if ( 'delete' === $key || 'wcs_deletion_error' === $key ) {
												$action['name'] = __( 'Delete payment method', 'newspack-plugin' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
											}
											?>
											<li>
												<a href="<?php echo \esc_url( $action['url'] ); ?>" class="newspack-ui__button newspack-ui__button--ghost <?php echo \sanitize_html_class( $key ); ?> <?php echo 'wcs_deletion_error' === $key ? 'disabled' : ''; ?>">
													<?php echo \esc_html( $action['name'] ); ?>
												</a>
											</li>
										<?php endforeach; ?>
										</ul>
									</div>
								</div>
								<?php
							}
							?>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>

	<?php else : ?>
		<p>
			<?php \esc_html_e( 'You don’t have any payment methods saved yet.', 'newspack-plugin' ); ?>
		</p>

	<?php endif; ?>

	<?php do_action( 'newspack_woocommerce_after_account_payment_methods', $has_methods ); ?>

	<?php if ( \WC()->payment_gateways->get_available_payment_gateways() ) : ?>
		<a class="newspack-ui__button newspack-ui__button--primary newspack-my-account__add-payment-method" href="<?php echo \esc_url( \wc_get_endpoint_url( 'add-payment-method' ) ); ?>"><?php \esc_html_e( 'Add payment method', 'newspack-plugin' ); ?></a>
	<?php endif; ?>
</section>
