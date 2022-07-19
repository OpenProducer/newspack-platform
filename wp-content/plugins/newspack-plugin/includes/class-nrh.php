<?php
/**
 * Newspack News Revenue Hub feature management.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Handles Salesforce functionality.
 */
class NRH {
	/**
	 * Add hooks.
	 */
	public static function init() {
		add_filter( 'newspack_blocks_donate_block_html', [ __CLASS__, 'handle_custom_campaign_id' ], 10, 2 );
		add_filter( 'googlesitekit_gtag_opt', [ __CLASS__, 'googlesitekit_gtag_opt' ], 11 );
		add_filter( 'googlesitekit_amp_gtag_opt', [ __CLASS__, 'googlesitekit_amp_gtag_opt' ], 11 );
		add_filter( 'newspack_blocks_donate_block_html', [ __CLASS__, 'render_nrh_donate_block' ], 10, 2 );
	}

	/**
	 * Add a hidden campaign input when a custom campaign is present in the GET request.
	 *
	 * @param string $html The donate form html.
	 * @param array  $attributes Block attributes.
	 * @return string modified $html.
	 */
	public static function handle_custom_campaign_id( $html, $attributes ) {
		// Don't add a global campaign ID if there is already a campaign ID.
		if ( stripos( $html, "name='campaign'" ) || stripos( $html, 'name="campaign"' ) ) {
			return $html;
		}

		$custom_campaign = filter_input( INPUT_GET, 'campaign', FILTER_SANITIZE_STRING );
		if ( ! $custom_campaign ) {
			return $html;
		}

		$custom_campaign = '<input type="hidden" name="campaign" value="' . esc_attr( $custom_campaign ) . '"/>';
		$html            = str_replace( '</form>', $custom_campaign . '</form>', $html );
		return $html;
	}

	/**
	 * Add cross-domain tracking for News Revenue Hub checkout, on AMP pages.
	 *
	 * @param array $gtag_opt gtag config options.
	 */
	public static function googlesitekit_gtag_opt( $gtag_opt ) {
		if ( ! isset( $gtag_opt['linker'] ) ) {
			$gtag_opt['linker'] = [];
		}
		if ( ! isset( $gtag_opt['linker']['domains'] ) ) {
			$gtag_opt['linker']['domains'] = [];
		}
		$gtag_opt['linker']['domains'][]      = 'checkout.fundjournalism.org';
		$gtag_opt['linker']['decorate_forms'] = true;
		return $gtag_opt;
	}

	/**
	 * Add cross-domain tracking for News Revenue Hub checkout, on AMP pages.
	 *
	 * @param array $gtag_amp_opt gtag config options for AMP.
	 */
	public static function googlesitekit_amp_gtag_opt( $gtag_amp_opt ) {
		$analytics = \Newspack\Google_Services_Connection::get_site_kit_analytics_module();
		if ( $analytics ) {
			$ga_property_code = $analytics->get_settings()->get()['propertyID'];

			if ( ! isset( $gtag_amp_opt['vars']['config'][ $ga_property_code ]['linker'] ) ) {
				$gtag_amp_opt['vars']['config'][ $ga_property_code ]['linker'] = [];
			}
			if ( ! isset( $gtag_amp_opt['vars']['config'][ $ga_property_code ]['linker']['domains'] ) ) {
				$gtag_amp_opt['vars']['config'][ $ga_property_code ]['linker']['domains'] = [];
			}

			$gtag_amp_opt['vars']['config'][ $ga_property_code ]['linker']['domains'][]      = 'checkout.fundjournalism.org';
			$gtag_amp_opt['vars']['config'][ $ga_property_code ]['linker']['decorate_forms'] = true;
			return $gtag_amp_opt;
		} else {
			return $gtag_amp_opt; // If Site Kit isn't installed, bail early.
		}
	}

	/**
	 * Get the site URL with protocol removed.
	 */
	public static function get_clean_site_url() {
		$protocols = [ 'http://', 'https://' ];
		return str_replace( $protocols, '', get_bloginfo( 'wpurl' ) );
	}

	/**
	 * Get NRH config.
	 */
	public static function get_nrh_config() {
		return get_option( NEWSPACK_NRH_CONFIG, [] );
	}

	/**
	 * Rewrite Donate block for News Revenue Hub submission.
	 *
	 * @param string $html The donate form html.
	 * @param array  $attributes Block attributes.
	 *
	 * @return string modified $html.
	 */
	public static function render_nrh_donate_block( $html, $attributes ) {
		$settings = Donations::get_donation_settings();
		if ( isset( $attributes['manual'] ) && true === $attributes['manual'] ) {
			$settings['suggestedAmounts']        = $attributes['suggestedAmounts'];
			$settings['suggestedAmountUntiered'] = $attributes['suggestedAmountUntiered'];
			$settings['tiered']                  = $attributes['tiered'];
		}

			$frequencies = [
				'once'  => __( 'One-time', 'newspack-blocks' ),
				'month' => __( 'Monthly', 'newspack-blocks' ),
				'year'  => __( 'Annually', 'newspack-blocks' ),
			];

			$nrh_frequency_keys = [
				'once'  => 'once',
				'month' => 'monthly',
				'year'  => 'yearly',
			];

			$selected_frequency = isset( $attributes['defaultFrequency'] ) ? $attributes['defaultFrequency'] : 'month';
			$suggested_amounts  = $settings['suggestedAmounts'];

			$campaign = isset( $attributes['campaign'] ) ? $attributes['campaign'] : false;

			$uid = wp_rand( 10000, 99999 ); // Unique identifier to prevent labels colliding with other instances of Donate block.

			$button_text = $attributes['buttonText'];

			$nrh_config = self::get_nrh_config();
			if ( ! isset( $nrh_config['nrh_organization_id'] ) ) {
				// The Organisation ID is crucial.
				return '';
			}

			$organization_id = wp_strip_all_tags( $nrh_config['nrh_organization_id'] );
			$campaign_global = wp_strip_all_tags( isset( $nrh_config['nrh_salesforce_campaign_id'] ) ? $nrh_config['nrh_salesforce_campaign_id'] : '' );

			if ( ! $campaign && $campaign_global ) {
				$campaign = $campaign_global;
			}

			$allowed_tags = [
				'form'  => [
					'method' => [],
					'action' => [],
				],
				'input' => [
					'type'  => [],
					'name'  => [],
					'value' => [],
				],
			];

			$is_amp        = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
			$block_id      = 'wpbdbd_' . uniqid();
			$initial_value = $suggested_amounts[1];
			if ( ! $settings['tiered'] ) {
				$initial_value = $settings['suggestedAmountUntiered'];
				if ( in_array( $selected_frequency, [ 'year', 'once' ] ) ) {
					$initial_value *= 12;
				}
			}

			$form_open = sprintf(
				'<form method="GET" action="https://checkout.fundjournalism.org/memberform"><input type="hidden" name="org_id" value="%s" />',
				esc_attr( $organization_id )
			);

			ob_start();

		if ( ! $is_amp ) : ?>
			<script type="text/javascript">
				function updateAmount( id ) {
					var container = document.querySelector( 'div.wpbnbd#' + id );
					var frequencies = {
						'once': 'once',
						'monthly': 'month',
						'yearly': 'year'
					};
					var selectedTab = container.querySelector( 'input[name="installmentPeriod"]:checked' ).value;
					var inputId = 'donation_value_' + frequencies[selectedTab];
					var amount;
					var untieredInput = container.querySelector( 'input[name="' + inputId + '_untiered"]' );
					if ( untieredInput ) {
						amount = untieredInput.value;
					} else {
						amount = container.querySelector( 'input[name="' + inputId + '"]:checked' ).value;
						if ( 'other' === amount ) {
							amount = container.querySelector( 'input[name="' + inputId + '_other"]' ).value;
						}
					}
					container.querySelector( 'input[name=amount]' ).value = amount;
				}
			</script>
				<?php
			endif;

			/**
			 * For AMP-compatibility, the donation forms are implemented as pure HTML forms (no JS).
			 * Each frequency and tier option is a radio input, styled to look like a button.
			 * As the radio inputs are checked/unchecked, fields are hidden/displayed using only CSS.
			 */
		if ( ! $settings['tiered'] ) :

			?>
				<?php if ( $is_amp ) : ?>
				<amp-state id="<?php echo esc_attr( $block_id ); ?>">
					<script type="application/json">
						{
							"amount": <?php echo esc_attr( $initial_value ); ?>,
							"amount_once": <?php echo esc_attr( 12 * intval( $settings['suggestedAmountUntiered'] ) ); ?>,
							"amount_month": <?php echo esc_attr( $settings['suggestedAmountUntiered'] ); ?>,
							"amount_year": <?php echo esc_attr( 12 * intval( $settings['suggestedAmountUntiered'] ) ); ?>
						}
					</script>
				</amp-state>
			<?php endif; ?>
				<div class='wp-block-newspack-blocks-donate wpbnbd untiered' id='<?php echo esc_attr( $block_id ); ?>'>
					<?php echo wp_kses( $form_open, $allowed_tags ); ?>
						<input type="hidden" <?php if ( $is_amp ) : ?>
						[value]="<?php echo esc_attr( $block_id ); ?>.amount"<?php endif; ?> value="<?php echo esc_attr( $initial_value ); ?>" name="amount" id="amount" />
						<div class='wp-block-newspack-blocks-donate__options'>
							<?php foreach ( $frequencies as $frequency_slug => $frequency_name ) : ?>
								<?php
									$formatted_amount = 'year' === $frequency_slug || 'once' === $frequency_slug ? 12 * $settings['suggestedAmountUntiered'] : $settings['suggestedAmountUntiered'];
								?>

								<div class='wp-block-newspack-blocks-donate__frequency frequency'>
									<input
										type='radio'
										value='<?php echo esc_attr( $nrh_frequency_keys[ $frequency_slug ] ); ?>'
										id='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
										name='installmentPeriod'
										<?php checked( $selected_frequency, $frequency_slug ); ?>
										<?php if ( $is_amp ) : ?>
										on="tap:AMP.setState(
											{
												<?php echo esc_attr( $block_id ); ?>: {
													amount: <?php echo esc_attr( $block_id ); ?>.amount_<?php echo esc_attr( $frequency_slug ); ?>
												}
											}
										)"
										<?php else : ?>
											onclick="updateAmount( '<?php echo esc_attr( $block_id ); ?>' );"
										<?php endif; ?>
									/>
									<label
										for='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
										class='donation-frequency-label freq-label'
									>
										<?php echo esc_html( $frequency_name ); ?>
									</label>
									<div class='input-container'>
										<label
											class='donate-label'
											for='newspack-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-untiered-input'
										>
											<?php echo esc_html__( 'Donation amount', 'newspack-blocks' ); ?>
										</label>
										<div class='wp-block-newspack-blocks-donate__money-input money-input'>
											<span class='currency'>
												<?php echo esc_html( $settings['currencySymbol'] ); ?>
											</span>
											<input
												type='number'
												name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>_untiered'
												value='<?php echo esc_attr( $formatted_amount ); ?>'
												id='newspack-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-untiered-input'
												<?php if ( $is_amp ) : ?>
												on="input-throttled:AMP.setState(
													{
														<?php echo esc_attr( $block_id ); ?>: {
															amount:event.value,
															amount_<?php echo esc_attr( $frequency_slug ); ?>:event.value
														}
													}
												)"
												<?php else : ?>
													oninput="updateAmount( '<?php echo esc_attr( $block_id ); ?>' );"
												<?php endif; ?>
											/>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<p class='wp-block-newspack-blocks-donate__thanks thanks'>
							<?php echo esc_html__( 'Your contribution is appreciated.', 'newspack-blocks' ); ?>
						</p>
						<button type='submit'>
							<?php echo wp_kses_post( $button_text ); ?>
						</button>
						<?php if ( $campaign ) : ?>
							<input type='hidden' name='campaign' value='<?php echo esc_attr( $campaign ); ?>' />
						<?php endif; ?>
					</form>
				</div>
				<?php

		else :
			?>
			<?php if ( $is_amp ) : ?>
			<amp-state id="<?php echo esc_attr( $block_id ); ?>">
				<script type="application/json">
					{
						"amount": <?php echo esc_attr( $initial_value ); ?>,
						"amount_once": <?php echo esc_attr( 12 * intval( $suggested_amounts[1] ) ); ?>,
						"amount_month": <?php echo esc_attr( $suggested_amounts[1] ); ?>,
						"amount_year": <?php echo esc_attr( 12 * intval( $suggested_amounts[1] ) ); ?>,
						"amount_other_once": <?php echo esc_attr( 12 * intval( $suggested_amounts[1] ) ); ?>,
						"amount_other_month": <?php echo esc_attr( $suggested_amounts[1] ); ?>,
						"amount_other_year": <?php echo esc_attr( 12 * intval( $suggested_amounts[1] ) ); ?>
					}
				</script>
			</amp-state>
		<?php endif; ?>
			<div class='wp-block-newspack-blocks-donate wpbnbd tiered' id='<?php echo esc_attr( $block_id ); ?>'>
				<?php echo wp_kses( $form_open, $allowed_tags ); ?>
					<input type="hidden" <?php if ( $is_amp ) : ?>
					[value]="<?php echo esc_attr( $block_id ); ?>.amount"<?php endif; ?> value="<?php echo esc_attr( $initial_value ); ?>" name="amount" id="amount" />
					<div class='wp-block-newspack-blocks-donate__options'>
						<div class='wp-block-newspack-blocks-donate__frequencies frequencies'>
							<?php foreach ( $frequencies as $frequency_slug => $frequency_name ) : ?>

								<div class='wp-block-newspack-blocks-donate__frequency frequency'>
									<input
										type='radio'
										value='<?php echo esc_attr( $nrh_frequency_keys[ $frequency_slug ] ); ?>'
										id='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
										name='installmentPeriod'
										<?php checked( $selected_frequency, $frequency_slug ); ?>

										<?php if ( $is_amp ) : ?>
										on="tap:AMP.setState(
											{
												<?php echo esc_attr( $block_id ); ?>: {
													amount: <?php echo esc_attr( $block_id ); ?>.amount_<?php echo esc_attr( $frequency_slug ); ?>
												}
											}
										)"
										<?php else : ?>
											onclick="updateAmount( '<?php echo esc_attr( $block_id ); ?>' );"
										<?php endif; ?>
									/>
									<label
										for='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
										class='donation-frequency-label freq-label'
									>
										<?php echo esc_html( $frequency_name ); ?>
									</label>

									<div class='wp-block-newspack-blocks-donate__tiers tiers'>
										<?php foreach ( $suggested_amounts as $index => $suggested_amount ) : ?>
											<div class='wp-block-newspack-blocks-donate__tier'>
												<?php
													$amount           = 'year' === $frequency_slug || 'once' === $frequency_slug ? 12 * $suggested_amount : $suggested_amount;
													$formatted_amount = $settings['currencySymbol'] . $amount;
												?>
												<input
													type='radio'
													name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>'
													value='<?php echo esc_attr( $amount ); ?>'
													id='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-<?php echo (int) $index; ?>'
													<?php checked( 1, $index ); ?>

													<?php if ( $is_amp ) : ?>
													on="tap:AMP.setState(
														{
															<?php echo esc_attr( $block_id ); ?>: {
																amount:<?php echo esc_attr( $amount ); ?>,
																amount_<?php echo esc_attr( $frequency_slug ); ?>:<?php echo esc_attr( $amount ); ?>
															}
														}
													)"
													<?php else : ?>
														onclick="updateAmount( '<?php echo esc_attr( $block_id ); ?>' );"
													<?php endif; ?>
												/>
												<label
													class='tier-select-label tier-label'
													for='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-<?php echo (int) $index; ?>'
												>
													<?php echo esc_html( $formatted_amount ); ?>
												</label>
											</div>
										<?php endforeach; ?>

										<div class='wp-block-newspack-blocks-donate__tier'>
											<?php $amount = 'year' === $frequency_slug || 'once' === $frequency_slug ? 12 * $suggested_amounts[1] : $suggested_amounts[1]; ?>
											<input
												type='radio'
												class='other-input'
												name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>'
												value='other'
												id='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-other'

												<?php if ( $is_amp ) : ?>
												on="tap:AMP.setState(
													{
														<?php echo esc_attr( $block_id ); ?>: {
															amount: <?php echo esc_attr( $block_id ); ?>.amount_other_<?php echo esc_attr( $frequency_slug ); ?>
														}
													}
												)"
												<?php else : ?>
														onclick="updateAmount( '<?php echo esc_attr( $block_id ); ?>' );"
												<?php endif; ?>
											/>
											<label
												class='tier-select-label tier-label'
												for='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-other'
											>
												<?php echo esc_html__( 'Other', 'newspack-blocks' ); ?>
											</label>
											<label
												class='other-donate-label odl'
												for='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-other-input'
											>
												<?php echo esc_html__( 'Donation amount', 'newspack-blocks' ); ?>
											</label>
											<div class='wp-block-newspack-blocks-donate__money-input money-input'>
												<span class='currency'>
													<?php echo esc_html( $settings['currencySymbol'] ); ?>
												</span>
												<input
													type='number'
													name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>_other'
													value='<?php echo esc_attr( $amount ); ?>'
													id='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-other-input'

													<?php if ( $is_amp ) : ?>
													on="input-throttled:AMP.setState(
														{
															<?php echo esc_attr( $block_id ); ?>: {
																amount:event.value,
																amount_other_<?php echo esc_attr( $frequency_slug ); ?>:event.value
															}
														}
													)"
													<?php else : ?>
														oninput="updateAmount( '<?php echo esc_attr( $block_id ); ?>' );"
													<?php endif; ?>
												/>
											</div>
										</div>

									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<p class='wp-block-newspack-blocks-donate__thanks thanks'>
						<?php echo esc_html__( 'Your contribution is appreciated.', 'newspack-blocks' ); ?>
					</p>
					<button type='submit'>
						<?php echo wp_kses_post( $button_text ); ?>
					</button>
					<?php if ( $campaign ) : ?>
						<input type='hidden' name='campaign' value='<?php echo esc_attr( $campaign ); ?>' />
					<?php endif; ?>
				</form>
			</div>
			<?php
		endif;

		return ob_get_clean();
	}
}
NRH::init();
