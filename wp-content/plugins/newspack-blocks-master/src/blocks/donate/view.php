<?php
/**
 * Server-side rendering of the `newspack-blocks/donate` block.
 *
 * @package WordPress
 */

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

	Newspack_Blocks::enqueue_view_assets( 'donate' );

	$settings = Newspack\Donations::get_donation_settings();
	if ( is_wp_error( $settings ) || ! $settings['created'] ) {
		return '';
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

	$selected_frequency = 'month';
	$suggested_amounts  = $settings['suggestedAmounts'];

	$campaign = $attributes['campaign'] ?? false;

	$uid = rand(); // Unique identifier to prevent labels colliding with other instances of Donate block.

	ob_start();

	/**
	 * For AMP-compatibility, the donation forms are implemented as pure HTML forms (no JS).
	 * Each frequency and tier option is a radio input, styled to look like a button.
	 * As the radio inputs are checked/unchecked, fields are hidden/displayed using only CSS.
	 */
	if ( ! $settings['tiered'] ) :

		?>
		<div class='wp-block-newspack-blocks-donate wpbnbd untiered'>
			<form>
				<input type='hidden' name='newspack_donate' value='1' />
				<div class='wp-block-newspack-blocks-donate__options'>
					<?php foreach ( $frequencies as $frequency_slug => $frequency_name ) : ?>
						<?php
							$amount           = 'year' === $frequency_slug || 'once' === $frequency_slug ? 12 * $settings['suggestedAmountUntiered'] : $settings['suggestedAmountUntiered'];
							$formatted_amount = number_format( $amount, floatval( $amount ) - intval( $amount ) ? 2 : 0 );
						?>

						<div class='wp-block-newspack-blocks-donate__frequency'>
							<input
								type='radio'
								value='<?php echo esc_attr( $frequency_slug ); ?>'
								id='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
								name='donation_frequency'
								<?php checked( $selected_frequency, $frequency_slug ); ?>
							/>
							<label
								for='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
								class='donation-frequency-label'
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
								<div class='wp-block-newspack-blocks-donate__money-input'>
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
				<p class='wp-block-newspack-blocks-donate__thanks'>
					<?php echo esc_html__( 'Your contribution is appreciated.', 'newspack-blocks' ); ?>
				</p>
				<button type='submit'>
					<?php echo esc_html__( 'Donate now!', 'newspack-blocks' ); ?>
				</button>
				<?php if ( $campaign ) : ?>
					<input type='hidden' name='campaign' value='<?php echo esc_attr( $campaign ); ?>' />
				<?php endif; ?>
			</form>
		</div>
		<?php

	else :

		?>
		<div class='wp-block-newspack-blocks-donate wpbnbd tiered'>
			<form>
				<input type='hidden' name='newspack_donate' value='1' />
				<div class='wp-block-newspack-blocks-donate__options'>
					<div class='wp-block-newspack-blocks-donate__frequencies'>
						<?php foreach ( $frequencies as $frequency_slug => $frequency_name ) : ?>

							<div class='wp-block-newspack-blocks-donate__frequency'>
								<input
									type='radio'
									value='<?php echo esc_attr( $frequency_slug ); ?>'
									id='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
									name='donation_frequency'
									<?php checked( $selected_frequency, $frequency_slug ); ?>
								/>
								<label
									for='newspack-donate-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>'
									class='donation-frequency-label'
								>
									<?php echo esc_html( $frequency_name ); ?>
								</label>

								<div class='wp-block-newspack-blocks-donate__tiers'>
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
												class='tier-select-label'
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
											class='tier-select-label'
											for='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-other'
										>
											<?php echo esc_html__( 'Other', 'newspack-blocks' ); ?>
										</label>
										<label
											class='other-donate-label'
											for='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid ); ?>-other-input'
										>
											<?php echo esc_html__( 'Donation amount', 'newspack-blocks' ); ?>
										</label>
										<div class='wp-block-newspack-blocks-donate__money-input'>
											<span class='currency'>
												<?php echo esc_html( $settings['currencySymbol'] ); ?>
											</span>
											<input
												type='number'
												name='donation_value_<?php echo esc_attr( $frequency_slug ); ?>_other'
												value='<?php echo esc_attr( $amount ); ?>'
												id='newspack-tier-<?php echo esc_attr( $frequency_slug . '-' . $uid  ); ?>-other-input'
											/>
										</div>
									</div>

								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<p class='wp-block-newspack-blocks-donate__thanks'>
					<?php echo esc_html__( 'Your contribution is appreciated.', 'newspack-blocks' ); ?>
				</p>
				<button type='submit'>
					<?php echo esc_html__( 'Donate now!', 'newspack-blocks' ); ?>
				</button>
				<?php if ( $campaign ) : ?>
					<input type='hidden' name='campaign' value='<?php echo esc_attr( $campaign ); ?>' />
				<?php endif; ?>
			</form>
		</div>
		<?php

	endif;

	return apply_filters( 'newspack_blocks_donate_block_html', ob_get_clean() );
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
					'type'    => 'string',
				]
			),
			'render_callback' => 'newspack_blocks_render_block_donate',
		)
	);
}
add_action( 'init', 'newspack_blocks_register_donate' );
