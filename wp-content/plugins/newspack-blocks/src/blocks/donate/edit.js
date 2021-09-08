/**
 * External dependencies
 */
import classNames from 'classnames';

/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { Component, Fragment } from '@wordpress/element';
import {
	PanelBody,
	ExternalLink,
	Placeholder,
	Spinner,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { InspectorControls, RichText } from '@wordpress/block-editor';

class Edit extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			suggestedAmounts: [ 0, 0, 0 ],
			suggestedAmountUntiered: 0,
			currencySymbol: '$',
			tiered: false,
			created: false,

			isLoading: false,
			activeTier: 1,
			customDonationAmounts: {
				once: 0,
				month: 0,
				year: 0,
			},
			error: '',
			uid: Math.random().toString( 16 ).slice( 2 ), // Unique identifier to prevent collisions with other Donate blocks' labels.
		};
	}
	componentDidMount() {
		this.getSettings();
	}

	componentDidUpdate( prevProps ) {
		const { selectedFrequency } = this.state;
		const { attributes } = this.props;
		const { attributes: prevAttributes } = prevProps;
		if ( ! selectedFrequency || attributes.defaultFrequency !== prevAttributes.defaultFrequency ) {
			this.setState( { selectedFrequency: attributes.defaultFrequency } );
		}
	}

	/* If block is in "manual" mode, override certain state properties with values stored in attributes */
	blockData() {
		const { attributes } = this.props;
		const { defaultFrequency, manual, campaign } = attributes;
		const data = { ...this.state, ...( manual ? attributes : { defaultFrequency } ) };
		if ( manual ) {
			data.customDonationAmounts = {
				once: data.tiered ? 12 * data.suggestedAmounts[ 1 ] : 12 * data.suggestedAmountUntiered,
				month: data.tiered ? data.suggestedAmounts[ 1 ] : data.suggestedAmountUntiered,
				year: data.tiered ? 12 * data.suggestedAmounts[ 1 ] : 12 * data.suggestedAmountUntiered,
			};
		}
		data.campaign = campaign;
		return data;
	}

	isRenderingStreamlinedBlock() {
		return window.newspack_blocks_data?.is_rendering_streamlined_block;
	}

	sanitizeCurrencyInput = amount => Math.max( 0, parseFloat( amount ).toFixed( 2 ) );

	formatCurrencyWithoutSymbol = amount => {
		const decimalPlaces = parseFloat( amount ) - parseInt( amount ) ? 2 : 0;
		return parseFloat( amount ).toFixed( decimalPlaces );
	};

	formatCurrency = amount => {
		const { currencySymbol } = this.blockData();
		return currencySymbol + this.formatCurrencyWithoutSymbol( amount );
	};

	manualChanged = value => {
		const { attributes, setAttributes } = this.props;
		const { suggestedAmounts, suggestedAmountUntiered } = attributes;
		setAttributes( { manual: value } );
		if (
			value &&
			0 === suggestedAmountUntiered &&
			0 === suggestedAmounts[ 0 ] &&
			0 === suggestedAmounts[ 1 ] &&
			0 === suggestedAmounts[ 2 ]
		) {
			setAttributes( {
				suggestedAmounts: [
					this.sanitizeCurrencyInput( this.state.suggestedAmounts[ 0 ] ),
					this.sanitizeCurrencyInput( this.state.suggestedAmounts[ 1 ] ),
					this.sanitizeCurrencyInput( this.state.suggestedAmounts[ 2 ] ),
				],
				suggestedAmountUntiered: this.sanitizeCurrencyInput( this.state.suggestedAmountUntiered ),
			} );
		}
	};

	getSettings() {
		const path = '/newspack/v1/wizard/newspack-donations-wizard/donation';

		this.setState( { isLoading: true }, () => {
			apiFetch( { path } )
				.then( settings => {
					const {
						suggestedAmounts,
						suggestedAmountUntiered,
						currencySymbol,
						tiered,
						created,
					} = settings;
					this.setState( {
						suggestedAmounts,
						suggestedAmountUntiered,
						currencySymbol,
						tiered,
						created,
						isLoading: false,
						customDonationAmounts: {
							once: tiered ? 12 * suggestedAmounts[ 1 ] : 12 * suggestedAmountUntiered,
							month: tiered ? suggestedAmounts[ 1 ] : suggestedAmountUntiered,
							year: tiered ? 12 * suggestedAmounts[ 1 ] : 12 * suggestedAmountUntiered,
						},
						activeTier: 1,
					} );
				} )
				.catch( error => {
					this.setState( {
						isLoading: false,
						error: error.message,
					} );
				} );
		} );
	}

	handleCustomDonationChange( evt, frequency ) {
		const { customDonationAmounts } = this.state;
		customDonationAmounts[ frequency ] = evt.target.value;
		this.setState( { customDonationAmounts } );
	}

	renderUntieredForm() {
		const { className } = this.props;
		const { uid } = this.state;
		const { currencySymbol, customDonationAmounts, selectedFrequency } = this.blockData();

		const frequencies = {
			once: __( 'One-time', 'newspack-blocks' ),
			month: __( 'Monthly', 'newspack-blocks' ),
			year: __( 'Annually', 'newspack-blocks' ),
		};

		return (
			<div className={ classNames( className, 'untiered wpbnbd' ) }>
				<form>
					<div className="wp-block-newspack-blocks-donate__options">
						{ Object.keys( frequencies ).map( frequencySlug => (
							<div
								className="wp-block-newspack-blocks-donate__frequency frequency"
								key={ frequencySlug }
							>
								<input
									type="radio"
									onClick={ () => this.setState( { selectedFrequency: frequencySlug } ) }
									id={ 'newspack-donate-' + frequencySlug + '-' + uid }
									name="donation_frequency"
									checked={ frequencySlug === selectedFrequency }
								/>
								<label
									htmlFor={ 'newspack-donate-' + frequencySlug + '-' + uid }
									className="donation-frequency-label freq-label"
								>
									{ frequencies[ frequencySlug ] }
								</label>
								<div className="input-container">
									<label
										className="donate-label"
										htmlFor={ 'newspack-' + frequencySlug + '-' + uid + '-untiered-input' }
									>
										{ __( 'Donation amount', 'newspack-blocks' ) }
									</label>
									<div className="wp-block-newspack-blocks-donate__money-input money-input">
										<span className="currency">{ currencySymbol }</span>
										<input
											type="number"
											onChange={ evt => this.handleCustomDonationChange( evt, frequencySlug ) }
											value={ this.formatCurrencyWithoutSymbol(
												customDonationAmounts[ frequencySlug ]
											) }
											id={ 'newspack-' + frequencySlug + '-' + uid + '-untiered-input' }
										/>
									</div>
								</div>
							</div>
						) ) }
					</div>
					{ this.renderFooter() }
				</form>
			</div>
		);
	}

	renderTieredForm() {
		const { className } = this.props;
		const { uid } = this.state;
		const {
			activeTier,
			currencySymbol,
			customDonationAmounts,
			selectedFrequency,
			suggestedAmounts,
		} = this.blockData();

		const frequencies = {
			once: __( 'One-time', 'newspack-blocks' ),
			month: __( 'Monthly', 'newspack-blocks' ),
			year: __( 'Annually', 'newspack-blocks' ),
		};
		return (
			<div className={ classNames( className, 'tiered wpbnbd' ) }>
				<form>
					<div className="wp-block-newspack-blocks-donate__options">
						<div className="wp-block-newspack-blocks-donate__frequencies frequencies">
							{ Object.keys( frequencies ).map( frequencySlug => (
								<div
									className="wp-block-newspack-blocks-donate__frequency frequency"
									key={ frequencySlug }
								>
									<input
										type="radio"
										onClick={ () => this.setState( { selectedFrequency: frequencySlug } ) }
										id={ 'newspack-donate-' + frequencySlug + '-' + uid }
										name="donation_frequency"
										checked={ frequencySlug === selectedFrequency }
									/>
									<label
										htmlFor={ 'newspack-donate-' + frequencySlug + '-' + uid }
										className="donation-frequency-label freq-label"
									>
										{ frequencies[ frequencySlug ] }
									</label>

									<div className="wp-block-newspack-blocks-donate__tiers tiers">
										{ suggestedAmounts.map( ( suggestedAmount, index ) => (
											<div className="wp-block-newspack-blocks-donate__tier" key={ index }>
												<input
													type="radio"
													onClick={ () => this.setState( { activeTier: index } ) }
													id={ 'newspack-tier-' + frequencySlug + '-' + uid + '-' + index }
													checked={ index === activeTier }
												/>
												<label
													className="tier-select-label tier-label"
													htmlFor={ 'newspack-tier-' + frequencySlug + '-' + uid + '-' + index }
												>
													{ this.formatCurrency(
														'year' === frequencySlug || 'once' === frequencySlug
															? 12 * suggestedAmount
															: suggestedAmount
													) }
												</label>
											</div>
										) ) }

										<div className="wp-block-newspack-blocks-donate__tier">
											<input
												type="radio"
												onClick={ () => this.setState( { activeTier: 'other' } ) }
												className="other-input"
												id={ 'newspack-tier-' + frequencySlug + '-' + uid + '-other' }
												checked={ 'other' === activeTier }
											/>
											<label
												className="tier-select-label tier-label"
												htmlFor={ 'newspack-tier-' + frequencySlug + '-' + uid + '-other' }
											>
												{ __( 'Other', 'newspack-blocks' ) }
											</label>
											<label
												className="other-donate-label odl"
												htmlFor={ 'newspack-tier-' + frequencySlug + '-' + uid + '-other-input' }
											>
												{ __( 'Donation amount', 'newspack-blocks' ) }
											</label>
											<div className="wp-block-newspack-blocks-donate__money-input money-input">
												<span className="currency">{ currencySymbol }</span>
												<input
													type="number"
													onChange={ evt => this.handleCustomDonationChange( evt, frequencySlug ) }
													value={ customDonationAmounts[ frequencySlug ] }
													id={ 'newspack-tier-' + frequencySlug + '-' + uid + '-other-input' }
												/>
											</div>
										</div>
									</div>
								</div>
							) ) }
						</div>
					</div>
					{ this.renderFooter() }
				</form>
			</div>
		);
	}

	renderButton() {
		const { attributes, setAttributes } = this.props;
		const { buttonText } = attributes;
		return (
			<button type="submit" onClick={ evt => evt.preventDefault() }>
				<RichText
					onChange={ value => setAttributes( { buttonText: value } ) }
					placeholder={ __( 'Button text…', 'newspack-blocks' ) }
					value={ buttonText }
					tagName="span"
				/>
			</button>
		);
	}

	renderFooter() {
		const { attributes, setAttributes } = this.props;
		const { thanksText } = attributes;
		return (
			<>
				<p className="wp-block-newspack-blocks-donate__thanks thanks">
					<RichText
						onChange={ value => setAttributes( { thanksText: value } ) }
						placeholder={ __( 'Thank you text…', 'newspack-blocks' ) }
						value={ thanksText }
						tagName="span"
					/>
				</p>
				{ this.isRenderingStreamlinedBlock() ? (
					<div className="wp-block-newspack-blocks-donate__stripe stripe-payment">
						<div className="stripe-payment__row stripe-payment__row--flex stripe-payment__footer">
							{ this.renderButton() }
							<a
								target="_blank"
								rel="noreferrer"
								className="stripe-payment__branding"
								href="https://stripe.com"
							>
								<img
									width="111"
									height="26"
									src={ window.newspack_blocks_data?.streamlined_block_stripe_badge }
									alt="Stripe"
								/>
							</a>
						</div>
					</div>
				) : (
					this.renderButton()
				) }
			</>
		);
	}

	renderPlaceholder() {
		const { created, error, isLoading, manual } = this.blockData();

		if ( manual ) {
			return null;
		}
		if ( isLoading ) {
			return <Placeholder icon={ <Spinner /> } className="component-placeholder__align-center" />;
		}
		if ( error.length ) {
			return (
				<Placeholder
					icon="warning"
					label={ __( 'Error', 'newspack-blocks' ) }
					instructions={ error }
				>
					<ExternalLink href="/wp-admin/admin.php?page=newspack-reader-revenue-wizard#/donations">
						{ __( 'Go to donation settings to troubleshoot.', 'newspack-blocks' ) }
					</ExternalLink>
				</Placeholder>
			);
		}
		if ( ! created ) {
			return (
				<Placeholder
					icon="warning"
					label={ __( 'Not ready', 'newspack-blocks' ) }
					instructions={ __(
						'You have not set up your donation settings yet. You need to do that before you can use the Donate Block.',
						'newspack-blocks'
					) }
				>
					<ExternalLink href="/wp-admin/admin.php?page=newspack-reader-revenue-wizard#/donations">
						{ __( 'Set up donation settings.', 'newspack-blocks' ) }
					</ExternalLink>
				</Placeholder>
			);
		}
	}

	renderForm() {
		const { created, error, manual, tiered } = this.blockData();
		if ( ! manual && ( error.length || ! created ) ) {
			return null;
		}
		return tiered ? this.renderTieredForm() : this.renderUntieredForm();
	}

	renderManualControls() {
		const { setAttributes } = this.props;
		const { currencySymbol, suggestedAmounts, suggestedAmountUntiered, tiered } = this.blockData();
		return (
			<Fragment>
				<ToggleControl
					key="tiered"
					checked={ tiered }
					onChange={ value => setAttributes( { tiered: value } ) }
					label={ __( 'Tiered', 'newspack-blocks' ) }
				/>
				{ tiered && (
					<Fragment>
						<TextControl
							key="low-tier"
							/* Translators: %s: the symbol for the current currency */
							label={ sprintf( __( 'Low-tier (%s)' ), currencySymbol ) }
							type="number"
							step="0.01"
							value={ suggestedAmounts[ 0 ] }
							onChange={ value =>
								setAttributes( {
									suggestedAmounts: [
										this.sanitizeCurrencyInput( value ),
										suggestedAmounts[ 1 ],
										suggestedAmounts[ 2 ],
									],
								} )
							}
						/>
						<TextControl
							key="mid-tier"
							/* Translators: %s: the symbol for the current currency */
							label={ sprintf( __( 'Mid-tier (%s)' ), currencySymbol ) }
							type="number"
							step="0.01"
							value={ suggestedAmounts[ 1 ] }
							onChange={ value =>
								setAttributes( {
									suggestedAmounts: [
										suggestedAmounts[ 0 ],
										this.sanitizeCurrencyInput( value ),
										suggestedAmounts[ 2 ],
									],
								} )
							}
						/>
						<TextControl
							key="hi-tier"
							label={ __( 'Hi-tier' ) + ' (' + currencySymbol + ')' }
							type="number"
							step="0.01"
							value={ suggestedAmounts[ 2 ] }
							onChange={ value =>
								setAttributes( {
									suggestedAmounts: [
										suggestedAmounts[ 0 ],
										suggestedAmounts[ 1 ],
										this.sanitizeCurrencyInput( value ),
									],
								} )
							}
						/>
					</Fragment>
				) }
				{ ! tiered && (
					<TextControl
						key="suggestedAmountUntiered"
						label={ __( 'Suggested donation amount per month' ) + ' (' + currencySymbol + ')' }
						type="number"
						step="0.01"
						value={ suggestedAmountUntiered }
						onChange={ value =>
							setAttributes( {
								suggestedAmountUntiered: this.sanitizeCurrencyInput( value ),
							} )
						}
					/>
				) }
			</Fragment>
		);
	}

	render() {
		const { setAttributes } = this.props;
		const { manual, campaign, defaultFrequency } = this.blockData();
		return (
			<Fragment>
				{ this.renderPlaceholder() }
				{ this.renderForm() }
				<InspectorControls>
					<PanelBody>
						<ToggleControl
							key="manual"
							checked={ manual }
							onChange={ this.manualChanged }
							label={ __( 'Configure manually', 'newspack-blocks' ) }
						/>
						{ ! manual && (
							<Fragment>
								<p>
									{ __(
										'The Donate Block allows you to collect donations from readers. The fields are automatically defined based on your donation settings.',
										'newspack-blocks'
									) }
								</p>

								<ExternalLink href="/wp-admin/admin.php?page=newspack-reader-revenue-wizard#/donations">
									{ __( 'Edit donation settings.', 'newspack-blocks' ) }
								</ExternalLink>
							</Fragment>
						) }
					</PanelBody>
					{ manual && (
						<PanelBody title={ __( 'Manual Settings', 'newspack-blocks' ) }>
							{ this.renderManualControls() }
						</PanelBody>
					) }
					<PanelBody>
						<SelectControl
							label={ __( 'Default Tab', 'newspack' ) }
							value={ defaultFrequency }
							options={ [
								{
									label: __( 'One-time', 'newspack' ),
									value: 'once',
								},
								{
									label: __( 'Monthly', 'newspack' ),
									value: 'month',
								},
								{
									label: __( 'Annually', 'newspack' ),
									value: 'year',
								},
							] }
							onChange={ _defaultFrequency => {
								setAttributes( {
									defaultFrequency: _defaultFrequency,
								} );
							} }
						/>
					</PanelBody>
					<PanelBody title={ __( 'Campaign', 'newspack-blocks' ) } initialOpen={ false }>
						<TextControl
							label={ __( 'Campaign ID', 'newspack-blocks' ) }
							value={ campaign ? campaign : '' }
							onChange={ value =>
								setAttributes( {
									campaign: value,
								} )
							}
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	}
}

export default Edit;
