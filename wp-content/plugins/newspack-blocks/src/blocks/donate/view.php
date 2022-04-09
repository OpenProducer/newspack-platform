<?php
/**
 * Server-side rendering of the `newspack-blocks/donate` block.
 *
 * @package WordPress
 */

/**
 * Renders the footer of the donation form.
 *
 * @param array $attributes The block attributes.
 *
 * @return string
 */
function newspack_blocks_render_block_donate_footer( $attributes ) {
	$is_streamlined                      = Newspack_Blocks::is_rendering_streamlined_block();
	$is_rendering_newsletter_list_opt_in = false;
	$is_rendering_fee_checkbox           = false;

	if ( $is_streamlined ) {
		$stripe_data               = \Newspack\Stripe_Connection::get_stripe_data();
		$is_rendering_fee_checkbox = 0 < (float) $stripe_data['fee_multiplier'] + (float) $stripe_data['fee_static'];
		if ( class_exists( 'Newspack_Newsletters' ) ) {
			$is_rendering_newsletter_list_opt_in = isset( $stripe_data['newsletter_list_id'] ) && ! empty( $stripe_data['newsletter_list_id'] );
		}
	}

	$campaign  = $attributes['campaign'] ?? false;
	$client_id = '';
	if ( class_exists( 'Newspack_Popups_Segmentation' ) ) {
		$client_id = Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME;
	}

	ob_start();

	?>
		<p class='wp-block-newspack-blocks-donate__thanks thanks'>
			<?php echo wp_kses_post( $attributes['thanksText'] ); ?>
		</p>

		<?php if ( $is_streamlined ) : ?>
			<div class="wp-block-newspack-blocks-donate__stripe stripe-payment stripe-payment--invisible stripe-payment--disabled">
				<div class="stripe-payment__inputs stripe-payment--hidden">
					<div class="stripe-payment__row">
						<div class="stripe-payment__element stripe-payment__card"></div>
					</div>
					<div class="stripe-payment__row stripe-payment__row--flex">
						<input required placeholder="<?php echo esc_html__( 'Email', 'newspack-blocks' ); ?>" type="email" name="email" value="">
						<input required placeholder="<?php echo esc_html__( 'Full Name', 'newspack-blocks' ); ?>" type="text" name="full_name" value="">
					</div>
				</div>
				<?php if ( $is_rendering_fee_checkbox ) : ?>
					<div class="stripe-payment__row stripe-payment__row--small">
						<label class="stripe-payment__checkbox">
							<input type="checkbox" name="agree_to_pay_fees" checked value="true"><?php echo esc_html__( 'Agree to pay fees?', 'newspack-blocks' ); ?>
							<span id="stripe-fees-amount">($0)</span>
						</label>
						<div class="stripe-payment__info"><?php echo esc_html__( 'Paying the transaction fee is not required, but it directs more money in support of our mission.', 'newspack-blocks' ); ?></div>
					</div>
				<?php endif; ?>
				<?php if ( $is_rendering_newsletter_list_opt_in ) : ?>
					<div class="stripe-payment__row stripe-payment__row--small">
						<label class="stripe-payment__checkbox">
							<input type="checkbox" name="newsletter_opt_in" checked value="true"><?php echo esc_html__( 'Sign up for our newsletter', 'newspack-blocks' ); ?>
						</label>
					</div>
				<?php endif; ?>
				<div class="stripe-payment__messages">
					<div class="type-error"></div>
					<div class="type-success"></div>
					<div class="type-info"></div>
				</div>
				<div class="stripe-payment__row stripe-payment__row--flex stripe-payment__footer">
					<div class="stripe-payment__methods">
						<div class="stripe-payment__request-button stripe-payment--hidden stripe-payment__request-button--invisible stripe-payment--transition"></div>
						<button type='submit'>
							<?php echo esc_html__( 'Donate with card', 'newspack-blocks' ); ?>
						</button>
					</div>
					<a target="_blank" rel="noreferrer" class="stripe-payment__branding" href="https://stripe.com">
						<img width="111" height="26" src="<?php echo esc_attr( Newspack_Blocks::streamlined_block_stripe_badge() ); ?>" alt="Stripe">
					</a>
				</div>
			</div>
		<?php else : ?>
			<button type='submit'>
				<?php echo wp_kses_post( $attributes['buttonText'] ); ?>
			</button>
		<?php endif; ?>
		<?php if ( $campaign ) : ?>
			<input type='hidden' name='campaign' value='<?php echo esc_attr( $campaign ); ?>' />
		<?php endif; ?>
		<?php if ( $client_id ) : ?>
			<input
				name="cid"
				type="hidden"
				value="CLIENT_ID(<?php echo esc_attr( $client_id ); ?>)"
				data-amp-replace="CLIENT_ID"
			/>
		<?php endif; ?>
	<?php

	return ob_get_clean();
}

/**
 * Enqueue frontend scripts and styles for the streamlined version of the donate block.
 */
function newspack_blocks_enqueue_streamlined_donate_block_scripts() {
	if ( Newspack_Blocks::is_rendering_streamlined_block() ) {
		$script_data = Newspack_Blocks::script_enqueue_helper( NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . '/donateStreamlined.js' );
		wp_enqueue_script(
			Newspack_Blocks::DONATE_STREAMLINED_SCRIPT_HANDLE,
			$script_data['script_path'],
			[ 'wp-i18n' ],
			$script_data['version'],
			true
		);
		$style_path = NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . 'donateStreamlined' . ( is_rtl() ? '.rtl' : '' ) . '.css';
		wp_enqueue_style(
			Newspack_Blocks::DONATE_STREAMLINED_SCRIPT_HANDLE,
			plugins_url( $style_path, NEWSPACK_BLOCKS__PLUGIN_FILE ),
			[],
			NEWSPACK_BLOCKS__VERSION
		);
	}
}

/**
 * Renders the `newspack-blocks/donate` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string
 */
function newspack_blocks_render_block_donate( $attributes ) {
	if ( ! class_exists( 'Newspack\Donations' ) ) {
		return '';
	}

	if ( Newspack_Blocks::is_rendering_streamlined_block() ) {
		newspack_blocks_enqueue_streamlined_donate_block_scripts();
	}

	Newspack_Blocks::enqueue_view_assets( 'donate' );

	$settings = Newspack\Donations::get_donation_settings();
	if ( is_wp_error( $settings ) || ! $settings['created'] ) {
		return '';
	}

	/* If block has additional CSS class(es)  */
	if ( isset( $attributes['className'] ) ) {
		$classname = $attributes['className'];
	} else {
		$classname = 'is-style-default';
	}

	/* If block is in "manual" mode, override certain state properties with values stored in attributes */
	if ( $attributes['manual'] ?? false ) {
		$settings = array_merge( $settings, $attributes );
	}

	$frequencies = [
		'once'  => __( 'One-time', 'newspack-blocks' ),
		'month' => __( 'Monthly', 'newspack-blocks' ),
		'year'  => __( 'Annually', 'newspack-blocks' ),
	];

	$selected_frequency = $attributes['defaultFrequency'] ?? 'month';
	$suggested_amounts  = $settings['suggestedAmounts'];

	$uid = wp_rand( 10000, 99999 ); // Unique identifier to prevent labels colliding with other instances of Donate block.

	$form_footer = newspack_blocks_render_block_donate_footer( $attributes );

	if ( Newspack_Blocks::is_rendering_streamlined_block() ) {
		$stripe_data           = \Newspack\Stripe_Connection::get_stripe_data();
		$currency              = $stripe_data['currency'];
		$settings_for_frontend = [
			$currency,
			$settings['currencySymbol'],
			get_bloginfo( 'name' ),
			Newspack\Stripe_Connection::is_currency_zero_decimal( $currency ),
			$stripe_data['location_code'],
			$frequencies,
			$stripe_data['fee_multiplier'],
			$stripe_data['fee_static'],
			$stripe_data['usedPublishableKey'],
		];
	} else {
		$settings_for_frontend = [];
	}

	ob_start();

	/**
	 * For AMP-compatibility, the donation forms are implemented as pure HTML forms (no JS).
	 * Each frequency and tier option is a radio input, styled to look like a button.
	 * As the radio inputs are checked/unchecked, fields are hidden/displayed using only CSS.
	 */
	if ( ! $settings['tiered'] ) :

		?>
		<div class="wp-block-newspack-blocks-donate wpbnbd untiered <?php echo esc_html( $classname ); ?>">
			<form data-settings="<?php echo esc_html( htmlspecialchars( wp_json_encode( $settings_for_frontend ), ENT_QUOTES, 'UTF-8' ) ); ?>">
				<input type='hidden' name='newspack_donate' value='1' />
				<div class='wp-block-newspack-blocks-donate__options'>
					<?php foreach ( $frequencies as $frequency_slug => $frequency_name ) : ?>
						<?php
							$amount           = 'year' === $frequency_slug || 'once' === $frequency_slug ? 12 * $settings['suggestedAmountUntiered'] : $settings['suggestedAmountUntiered'];
							$formatted_amount = number_format( $amount, floatval( $amount ) - intval( $amount ) ? 2 : 0 );
						?>

						<div class='wp-block-newspack-blocks-donate__frequency frequency'>
							<input
								type='radio'
								value='<?php echo esc_attr( $frequency_slug ); ?>'
								id='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
								name='donation_frequency'
								<?php checked( $selected_frequency, $frequency_slug ); ?>
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
									/>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php echo $form_footer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</form>
		</div>
		<?php
	else :

		?>
		<div class="wp-block-newspack-blocks-donate wpbnbd tiered <?php echo esc_html( $classname ); ?>">
			<form data-settings="<?php echo esc_html( htmlspecialchars( wp_json_encode( $settings_for_frontend ), ENT_QUOTES, 'UTF-8' ) ); ?>">
				<input type='hidden' name='newspack_donate' value='1' />
				<div class='wp-block-newspack-blocks-donate__options'>
					<div class='wp-block-newspack-blocks-donate__frequencies frequencies'>
						<?php foreach ( $frequencies as $frequency_slug => $frequency_name ) : ?>

							<div class='wp-block-newspack-blocks-donate__frequency frequency'>
								<input
									type='radio'
									value='<?php echo esc_attr( $frequency_slug ); ?>'
									id='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
									name='donation_frequency'
									<?php checked( $selected_frequency, $frequency_slug ); ?>
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
												$formatted_amount = $settings['currencySymbol'] . number_format( $amount, floatval( $amount ) - intval( $amount ) ? 2 : 0 );
											?>
											<input
												type='radio'
												name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>'
												value='<?php echo esc_attr( $amount ); ?>'
												id='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-<?php echo (int) $index; ?>'
												<?php checked( 1, $index ); ?>
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
											/>
										</div>
									</div>

								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php echo $form_footer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</form>
		</div>
		<?php
	endif;

	return apply_filters( 'newspack_blocks_donate_block_html', ob_get_clean(), $attributes );
}

/**
 * Registers the `newspack-blocks/donate` block on server.
 */
function newspack_blocks_register_donate() {
	register_block_type(
		'newspack-blocks/donate',
		array(
			'attributes'      => array(
				'className'               => [
					'type' => 'string',
				],
				'manual'                  => [
					'type' => 'boolean',
				],
				'suggestedAmounts'        => [
					'type'    => 'array',
					'items'   => [
						'type' => 'number',
					],
					'default' => [ 0, 0, 0 ],
				],
				'suggestedAmountUntiered' => [
					'type' => 'number',
				],
				'tiered'                  => [
					'type'    => 'boolean',
					'default' => true,
				],
				'campaign'                => [
					'type' => 'string',
				],
				'thanksText'              => [
					'type'    => 'string',
					'default' => __( 'Your contribution is appreciated.', 'newspack-blocks' ),
				],
				'buttonText'              => [
					'type'    => 'string',
					'default' => __( 'Donate Now', 'newspack-blocks' ),
				],
				'defaultFrequency'        => [
					'type'    => 'string',
					'default' => 'month',
				],
			),
			'render_callback' => 'newspack_blocks_render_block_donate',
			'supports'        => [],
		)
	);
}
add_action( 'init', 'newspack_blocks_register_donate' );
