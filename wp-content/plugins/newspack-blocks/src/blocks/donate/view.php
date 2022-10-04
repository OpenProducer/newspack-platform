<?php
/**
 * Server-side rendering of the `newspack-blocks/donate` block.
 *
 * @package WordPress
 */

/**
 * Renders the frequency selection of the donation form.
 *
 * @param string $frequency_slug Frequency slug.
 * @param string $frequency_name Frequency name.
 * @param number $uid Unique ID.
 * @param array  $configuration The donations settings.
 *
 * @return string
 */
function newspack_blocks_render_block_donate_frequency_selection( $frequency_slug, $frequency_name, $uid, $configuration ) {
	ob_start();
	?>
		<input
			type='radio'
			value='<?php echo esc_attr( $frequency_slug ); ?>'
			id='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
			name='donation_frequency'
			<?php
			checked(
				$configuration['defaultFrequency'],
				$frequency_slug
			);
			?>
		/>
		<label
			for='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
			class='donation-frequency-label freq-label'
		>
			<?php echo esc_html( $frequency_name ); ?>
		</label>
	<?php
	return ob_get_clean();
}

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

	$current_user      = wp_get_current_user();
	$user_email        = '';
	$user_display_name = '';
	if ( 0 !== $current_user->ID ) {
		$user_email        = $current_user->user_email;
		$user_display_name = trim( $current_user->first_name . ' ' . $current_user->last_name );
	}

	$button_color      = $attributes['buttonColor'];
	$button_text_color = Newspack_Blocks::get_color_for_contrast( $button_color );
	$button_style_attr = 'style="background-color: ' . esc_attr( $button_color ) . '; color: ' . esc_attr( $button_text_color ) . ';"';

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
						<input
							required
							placeholder="<?php echo esc_html__( 'Email', 'newspack-blocks' ); ?>"
							type="email"
							name="email"
							value="<?php echo esc_attr( $user_email ); ?>"
							<?php if ( '' !== $user_email ) : ?>
								readonly
							<?php endif; ?>
						>
						<input
							required
							placeholder="<?php echo esc_html__( 'Full Name', 'newspack-blocks' ); ?>"
							type="text"
							name="full_name"
							value="<?php echo esc_attr( $user_display_name ); ?>"
						>
					</div>
				</div>
				<?php if ( $is_rendering_fee_checkbox ) : ?>
					<div class="stripe-payment__row stripe-payment__row--small" id="stripe-fees-amount-container">
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
						<button type='submit' <?php echo $button_style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php echo esc_html( $attributes['buttonWithCCText'] ); ?>
						</button>
					</div>
				</div>
			</div>
		<?php else : ?>
			<button type='submit' <?php echo $button_style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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
		$dependencies = [ 'wp-i18n' ];

		if ( method_exists( '\Newspack\Recaptcha', 'can_use_captcha' ) && \Newspack\Recaptcha::can_use_captcha() ) {
			$dependencies[] = \Newspack\Recaptcha::SCRIPT_HANDLE;
		}

		$script_data = Newspack_Blocks::script_enqueue_helper( NEWSPACK_BLOCKS__BLOCKS_DIRECTORY . '/donateStreamlined.js' );
		wp_enqueue_script(
			Newspack_Blocks::DONATE_STREAMLINED_SCRIPT_HANDLE,
			$script_data['script_path'],
			$dependencies,
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

	$configuration = Newspack\Donations::get_donation_settings();
	if ( is_wp_error( $configuration ) ) {
		return '';
	}

	$configuration['defaultFrequency'] = $attributes['defaultFrequency'];

	/* If block has additional CSS class(es)  */
	if ( isset( $attributes['className'] ) ) {
		$classname = $attributes['className'];
	} else {
		$classname = 'is-style-default';
	}

	$frequencies = [
		'once'  => __( 'One-time', 'newspack-blocks' ),
		'month' => __( 'Monthly', 'newspack-blocks' ),
		'year'  => __( 'Annually', 'newspack-blocks' ),
	];

	/* If block is in "manual" mode, override certain state properties with values stored in attributes */
	if ( $attributes['manual'] ?? false ) {
		// Migrate old attributes.
		if ( empty( $attributes['amounts'] ) && isset( $attributes['suggestedAmounts'] ) ) {
			$other_amount = $configuration['amounts']['month'][3];
			if ( isset( $attributes['suggestedAmountUntiered'] ) ) {
				$other_amount = $attributes['suggestedAmountUntiered'];
			}
			$suggested_amounts     = $attributes['suggestedAmounts'];
			$multiplied_amounts    = [
				$suggested_amounts[0] * 12,
				$suggested_amounts[1] * 12,
				$suggested_amounts[2] * 12,
				$other_amount * 12,
			];
			$attributes['amounts'] = [
				'once'  => $multiplied_amounts,
				'month' => [
					$suggested_amounts[0],
					$suggested_amounts[1],
					$suggested_amounts[2],
					$other_amount,
				],
				'year'  => $multiplied_amounts,
			];
		}
		if ( isset( $attributes['tiered'] ) ) {
			$configuration['tiered'] = $attributes['tiered'];
		}
		if ( isset( $attributes['amounts'] ) && ! empty( $attributes['amounts'] ) ) {
			$configuration['amounts'] = $attributes['amounts'];
		}

		if ( isset( $attributes['disabledFrequencies'] ) ) {
			foreach ( $attributes['disabledFrequencies'] as $frequency_slug => $is_disabled ) {
				if ( $is_disabled ) {
					$configuration['disabledFrequencies'][ $frequency_slug ] = true;          }
			}
		}

		if ( isset( $attributes['minimumDonation'] ) ) {
			$configuration['minimumDonation'] = $attributes['minimumDonation'];
		}
	}

	foreach ( array_keys( $frequencies ) as $frequency_slug ) {
		if ( $configuration['disabledFrequencies'][ $frequency_slug ] ) {
			unset( $frequencies[ $frequency_slug ] );
		}
	}

	// Ensure default frequency is valid (not disabled).
	foreach ( array_keys( $configuration['disabledFrequencies'] ) as $frequency_slug ) {
		if ( $configuration['defaultFrequency'] === $frequency_slug && $configuration['disabledFrequencies'][ $frequency_slug ] ) {
			$non_disabled_frequencies          = array_keys(
				array_filter(
					$configuration['disabledFrequencies'],
					function ( $item ) {
						return ! $item;
					}
				)
			);
			$configuration['defaultFrequency'] = $non_disabled_frequencies[0];
		}
	}

	$uid = wp_rand( 10000, 99999 ); // Unique identifier to prevent labels colliding with other instances of Donate block.

	if ( Newspack_Blocks::is_rendering_streamlined_block() ) {
		$stripe_data      = \Newspack\Stripe_Connection::get_stripe_data();
		$currency         = $stripe_data['currency'];
		$captcha_site_key = null;

		if ( method_exists( '\Newspack\Recaptcha', 'can_use_captcha' ) && \Newspack\Recaptcha::can_use_captcha() ) {
			$captcha_site_key = \Newspack\Recaptcha::get_setting( 'site_key' );
		}

		$configuration_for_frontend = [
			$currency,
			$configuration['currencySymbol'],
			get_bloginfo( 'name' ),
			Newspack\Stripe_Connection::is_currency_zero_decimal( $currency ),
			$stripe_data['location_code'],
			$frequencies,
			$stripe_data['fee_multiplier'],
			$stripe_data['fee_static'],
			$stripe_data['usedPublishableKey'],
			$attributes['paymentRequestType'],
			$captcha_site_key,
			$configuration['minimumDonation'],
		];
	} else {
		$configuration_for_frontend = [];
	}

	$frequencies_count    = count( $frequencies );
	$container_classnames = 'wp-block-newspack-blocks-donate wpbnbd ' . $classname . ' wpbnbd-frequencies--' . $frequencies_count;

	ob_start();

	/**
	 * For AMP-compatibility, the donation forms are implemented as pure HTML forms (no JS).
	 * Each frequency and tier option is a radio input, styled to look like a button.
	 * As the radio inputs are checked/unchecked, fields are hidden/displayed using only CSS.
	 */
	if ( ! $configuration['tiered'] ) :
		?>
		<div class="untiered <?php echo esc_html( $container_classnames ); ?>">
			<form data-settings="<?php echo esc_html( htmlspecialchars( wp_json_encode( $configuration_for_frontend ), ENT_QUOTES, 'UTF-8' ) ); ?>">
				<input type='hidden' name='newspack_donate' value='1' />
				<div class='wp-block-newspack-blocks-donate__options'>
					<div class='wp-block-newspack-blocks-donate__frequencies frequencies'>
						<?php foreach ( $frequencies as $frequency_slug => $frequency_name ) : ?>
							<?php
								$formatted_amount = $configuration['amounts'][ $frequency_slug ][3];
							?>

							<div class='wp-block-newspack-blocks-donate__frequency frequency'>
								<?php echo newspack_blocks_render_block_donate_frequency_selection( $frequency_slug, $frequency_name, $uid, $configuration ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<div class='input-container'>
									<label
										class='donate-label'
										for='newspack-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-untiered-input'
									>
										<?php echo esc_html__( 'Donation amount', 'newspack-blocks' ); ?>
									</label>
									<div class='wp-block-newspack-blocks-donate__money-input money-input'>
										<span class='currency'>
											<?php echo esc_html( $configuration['currencySymbol'] ); ?>
										</span>
										<input
											type='number'
											min='<?php echo esc_attr( $configuration['minimumDonation'] ); ?>'
											name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>_untiered'
											value='<?php echo esc_attr( max( $configuration['minimumDonation'], $formatted_amount ) ); ?>'
											id='newspack-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-untiered-input'
										/>
									</div>
								</div>
							</div>

						<?php endforeach; ?>
					</div>
				</div>
				<?php echo newspack_blocks_render_block_donate_footer( $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</form>
		</div>

		<?php
		else :
			$suggested_amounts = $configuration['amounts'];
			?>

		<div class="tiered <?php echo esc_html( $container_classnames ); ?>">
			<form data-settings="<?php echo esc_html( htmlspecialchars( wp_json_encode( $configuration_for_frontend ), ENT_QUOTES, 'UTF-8' ) ); ?>">
				<input type='hidden' name='newspack_donate' value='1' />
				<div class='wp-block-newspack-blocks-donate__options'>
					<div class='wp-block-newspack-blocks-donate__frequencies frequencies'>
						<?php foreach ( $frequencies as $frequency_slug => $frequency_name ) : ?>

							<div class='wp-block-newspack-blocks-donate__frequency frequency'>
								<?php echo newspack_blocks_render_block_donate_frequency_selection( $frequency_slug, $frequency_name, $uid, $configuration ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<div class='wp-block-newspack-blocks-donate__tiers tiers'>
									<?php foreach ( $suggested_amounts[ $frequency_slug ] as $index => $amount ) : ?>
										<div class='wp-block-newspack-blocks-donate__tier'>
											<?php
											if ( 3 === $index ) : // The "other" tier.
												?>
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
													class='odl'
													for='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-other-input'
												>
												<?php echo esc_html__( 'Donation amount', 'newspack-blocks' ); ?>
												</label>
												<div class='wp-block-newspack-blocks-donate__money-input money-input'>
													<span class='currency'>
													<?php echo esc_html( $configuration['currencySymbol'] ); ?>
													</span>
													<input
														type='number'
														min='<?php echo esc_attr( $configuration['minimumDonation'] ); ?>'
														name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>_other'
														value='<?php echo esc_attr( max( $configuration['minimumDonation'], $amount ) ); ?>'
														id='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-other-input'
													/>
												</div>
												<?php
												else :
													?>
												<input
													type='radio'
													name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>'
													value='<?php echo esc_attr( max( $configuration['minimumDonation'], $amount ) ); ?>'
													id='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-<?php echo (int) $index; ?>'
													<?php checked( 1, $index ); ?>
												/>
												<label
													class='tier-select-label tier-label'
													for='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-<?php echo (int) $index; ?>'
												>
													<?php echo esc_html( $configuration['currencySymbol'] . max( $configuration['minimumDonation'], $amount ) ); ?>
												</label>
													<?php
												endif;
												?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

						<?php endforeach; ?>
					</div>
				</div>
				<?php echo newspack_blocks_render_block_donate_footer( $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
	$block_json = json_decode(
		file_get_contents( __DIR__ . '/block.json' ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		true
	);

	register_block_type(
		'newspack-blocks/' . $block_json['name'],
		[
			'attributes'      => $block_json['attributes'],
			'render_callback' => 'newspack_blocks_render_block_donate',
			'supports'        => $block_json['supports'],
		]
	);
}
add_action( 'init', 'newspack_blocks_register_donate' );
