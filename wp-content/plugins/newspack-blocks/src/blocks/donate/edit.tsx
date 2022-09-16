/**
 * External dependencies
 */
import classNames from 'classnames';
import { hooks } from 'newspack-components';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect, useMemo, useRef, Fragment } from '@wordpress/element';
import {
	CheckboxControl,
	PanelBody,
	ExternalLink,
	Placeholder,
	Spinner,
	SelectControl,
	ToggleControl,
	TextControl,
} from '@wordpress/components';
import { InspectorControls, RichText, ColorPaletteControl } from '@wordpress/block-editor';
import { isEmpty } from 'lodash';

/**
 * Internal dependencies
 */
import { getColorForContrast, getMigratedAmount } from './utils';
import type { DonationFrequencySlug } from './types';

const FREQUENCIES: { [ Key in DonationFrequencySlug as string ]: string } = {
	once: __( 'One-time', 'newspack-blocks' ),
	month: __( 'Monthly', 'newspack-blocks' ),
	year: __( 'Annually', 'newspack-blocks' ),
};
const FREQUENCY_SLUGS: DonationFrequencySlug[] = Object.keys(
	FREQUENCIES
) as DonationFrequencySlug[];

type DonationAmounts = {
	[ Key in DonationFrequencySlug as string ]: [ number, number, number, number ];
};

type OverridableConfiguration = {
	amounts: DonationAmounts;
	tiered: boolean;
	disabledFrequencies: {
		[ Key in DonationFrequencySlug as string ]: boolean;
	};
	minimumDonation: number;
};

type DonateBlockAttributes = OverridableConfiguration & {
	buttonText: string;
	buttonWithCCText: string;
	// https://stripe.com/docs/stripe-js/elements/payment-request-button
	paymentRequestType: 'donate' | 'default' | 'book' | 'buy';
	buttonColor: string;
	thanksText: string;
	defaultFrequency: DonationFrequencySlug;
	campaign: string;
	className: string;
	// Manual mode enables block-level overrides of the global Donate settings.
	manual: boolean;
	// Legacy attributes.
	suggestedAmounts?: [ number, number, number ];
	suggestedAmountUntiered?: number;
	minimumDonation: number;
};
type EditProps = {
	attributes: DonateBlockAttributes;
	setAttributes: ( attributes: Partial< DonateBlockAttributes > ) => void;
	className: string;
};
type DonationSettings = OverridableConfiguration & {
	currencySymbol: string;
};

type EditState = DonationSettings;

const TIER_LABELS = [
	__( 'Low-tier', 'newspack' ),
	__( 'Mid-tier', 'newspack' ),
	__( 'High-tier', 'newspack' ),
	__( 'Other', 'newspack' ),
];

const PAYMENT_REQUEST_BUTTON_TYPE_OPTIONS: {
	label: string;
	value: DonateBlockAttributes[ 'paymentRequestType' ];
}[] = [
	{ label: 'Donate', value: 'donate' },
	{ label: 'Pay', value: 'default' },
	{ label: 'Book', value: 'book' },
	{ label: 'Buy', value: 'buy' },
];

const Edit = ( { attributes, setAttributes, className }: EditProps ) => {
	const [ isLoading, setIsLoading ] = useState( true );
	const [ error, setError ] = useState( '' );

	// Unique identifier to prevent collisions with other Donate blocks' labels.
	const uid = useMemo( () => Math.random().toString( 16 ).slice( 2 ), [] );

	const [ settings, setSettings ] = hooks.useObjectState< EditState >( {
		amounts: {},
		currencySymbol: '$',
		tiered: false,
		disabledFrequencies: {},
		minimumDonation: 5,
	} );

	useEffect( () => {
		apiFetch< DonationSettings >( {
			path: '/newspack/v1/wizard/newspack-reader-revenue-wizard/donations',
		} )
			.then( ( donationSettings: DonationSettings ) => {
				setSettings( {
					amounts: donationSettings.amounts,
					currencySymbol: donationSettings.currencySymbol,
					tiered: donationSettings.tiered,
					disabledFrequencies: donationSettings.disabledFrequencies,
					minimumDonation: donationSettings.minimumDonation,
				} );

				if ( isEmpty( attributes.disabledFrequencies ) ) {
					setAttributes( { disabledFrequencies: donationSettings.disabledFrequencies } );
				}

				if ( isEmpty( attributes.minimumDonation ) ) {
					setAttributes( { minimumDonation: donationSettings.minimumDonation } );
				}

				// Migrate old attributes.
				if (
					isEmpty( attributes.amounts ) &&
					attributes.suggestedAmounts &&
					attributes.suggestedAmounts.length
				) {
					const untieredAmount =
						attributes.suggestedAmountUntiered || donationSettings.amounts.month[ 3 ];
					setAttributes( {
						suggestedAmounts: undefined,
						suggestedAmountUntiered: undefined,
						amounts: {
							once: getMigratedAmount( 'once', attributes.suggestedAmounts, untieredAmount ),
							month: getMigratedAmount( 'month', attributes.suggestedAmounts, untieredAmount ),
							year: getMigratedAmount( 'year', attributes.suggestedAmounts, untieredAmount ),
						},
					} );
				} else {
					setAttributes( { amounts: { ...donationSettings.amounts, ...attributes.amounts } } );
				}
			} )
			.catch( setError )
			.finally( () => setIsLoading( false ) );
	}, [] );

	const isRenderingStreamlinedBlock = () =>
		window.newspack_blocks_data?.is_rendering_streamlined_block;

	const amounts = attributes.manual ? attributes.amounts : settings.amounts;
	const availableFrequencies = FREQUENCY_SLUGS.filter( slug =>
		attributes.manual
			? ! attributes.disabledFrequencies[ slug ]
			: ! settings.disabledFrequencies[ slug ]
	);

	const formRef = useRef< HTMLFormElement >( null );

	// Update selected frequency when available frequencies change.
	useEffect( () => {
		if ( formRef.current ) {
			const formValues = Object.fromEntries( new FormData( formRef.current ) );
			if ( ! formValues.donation_frequency && formRef.current.elements ) {
				const frequencyRadioInput = formRef.current.elements[ 0 ];
				if ( frequencyRadioInput instanceof HTMLInputElement ) {
					frequencyRadioInput.click();
				}
			}
		}
		if ( availableFrequencies.indexOf( attributes.defaultFrequency ) === -1 ) {
			setAttributes( { defaultFrequency: availableFrequencies[ 0 ] } );
		}
	}, [ attributes.disabledFrequencies ] );

	// Update selected frequency when the default frequency attribute is updated.
	useEffect( () => {
		if ( formRef.current ) {
			const defaultFrequencyInput = formRef.current.querySelector(
				`[name="donation_frequency"][value="${ attributes.defaultFrequency }"]`
			);
			if ( defaultFrequencyInput instanceof HTMLInputElement ) {
				defaultFrequencyInput.click();
			}
		}
	}, [ attributes.defaultFrequency ] );

	const handleCustomDonationChange = ( {
		value,
		frequency,
		tierIndex,
	}: {
		value: string;
		frequency: DonationFrequencySlug;
		tierIndex: number;
	} ) => {
		const subject = attributes.manual ? attributes : settings;
		subject.amounts[ frequency ][ tierIndex ] = parseFloat( value );
		const update: Partial< DonateBlockAttributes > = {
			amounts: {
				[ frequency ]: subject.amounts[ frequency ],
				...subject.amounts,
			},
		};

		if ( attributes.manual ) {
			setAttributes( update );
		} else {
			setSettings( update );
		}
	};

	const renderFrequencySelect = ( frequencySlug: DonationFrequencySlug ) => (
		<>
			<input
				type="radio"
				value={ frequencySlug }
				id={ `newspack-donate-${ frequencySlug }-${ uid }` }
				name="donation_frequency"
				defaultChecked={ frequencySlug === attributes.defaultFrequency }
			/>
			<label
				htmlFor={ 'newspack-donate-' + frequencySlug + '-' + uid }
				className="donation-frequency-label freq-label"
			>
				{ FREQUENCIES[ frequencySlug ] }
			</label>
		</>
	);

	const displayAmount = ( amount: number ) => amount.toFixed( 2 ).replace( /\.?0*$/, '' );
	const getClassNames = ( classes: string ) =>
		classNames(
			classes,
			className,
			'wpbnbd',
			`wpbnbd-frequencies--${ availableFrequencies.length }`
		);

	const renderAmountValueInput = ( {
		frequencySlug,
		tierIndex,
		id,
		label,
	}: {
		frequencySlug: DonationFrequencySlug;
		tierIndex: number;
		id: string;
		label?: string;
	} ) => (
		<span key={ `${ frequencySlug }-${ tierIndex }` }>
			{ label && <label htmlFor={ id }>{ label }</label> }
			<input
				type="number"
				min={ attributes.minimumDonation }
				onChange={ evt =>
					handleCustomDonationChange( {
						value: evt.target.value,
						frequency: frequencySlug,
						tierIndex,
					} )
				}
				value={ amounts[ frequencySlug ][ tierIndex ] }
				id={ id }
			/>
		</span>
	);

	const renderUntieredForm = () => (
		<div className="wp-block-newspack-blocks-donate__options">
			<div className="wp-block-newspack-blocks-donate__frequencies frequencies">
				{ availableFrequencies.map( frequencySlug => (
					<div
						className="wp-block-newspack-blocks-donate__frequency frequency"
						key={ frequencySlug }
					>
						{ renderFrequencySelect( frequencySlug ) }
						<div className="input-container">
							<label
								className="donate-label"
								htmlFor={ 'newspack-' + frequencySlug + '-' + uid + '-untiered-input' }
							>
								{ __( 'Donation amount', 'newspack-blocks' ) }
							</label>
							<div className="wp-block-newspack-blocks-donate__money-input money-input">
								<span className="currency">{ settings.currencySymbol }</span>
								{ renderAmountValueInput( {
									frequencySlug,
									tierIndex: 3,
									id: `newspack-${ frequencySlug }-${ uid }-untiered-input`,
								} ) }
							</div>
						</div>
					</div>
				) ) }
			</div>
		</div>
	);

	const renderTieredForm = () => (
		<div className="wp-block-newspack-blocks-donate__options">
			<div className="wp-block-newspack-blocks-donate__frequencies frequencies">
				{ availableFrequencies.map( frequencySlug => (
					<div
						className="wp-block-newspack-blocks-donate__frequency frequency"
						key={ frequencySlug }
					>
						{ renderFrequencySelect( frequencySlug ) }

						<div className="wp-block-newspack-blocks-donate__tiers tiers">
							{ amounts[ frequencySlug ].map( ( suggestedAmount, index ) => {
								const isOtherTier = index === 3;
								const id = `newspack-tier-${ frequencySlug }-${ uid }-${
									isOtherTier ? 'other' : index
								}`;
								return (
									<div
										className={ classNames(
											'wp-block-newspack-blocks-donate__tier',
											`wp-block-newspack-blocks-donate__tier--${
												isOtherTier ? 'other' : 'frequency'
											}`
										) }
										key={ index }
									>
										<input
											type="radio"
											value={ isOtherTier ? 'other' : suggestedAmount }
											className={ isOtherTier ? 'other-input' : 'frequency-input' }
											id={ id }
											name={ `donation_value_${ frequencySlug }` }
											defaultChecked={ index === 1 }
										/>
										<label className="tier-select-label tier-label" htmlFor={ id }>
											{ isOtherTier
												? __( 'Other', 'newspack-blocks' )
												: settings.currencySymbol + displayAmount( suggestedAmount ) }
										</label>
										{ isOtherTier ? (
											<>
												<label className="odl" htmlFor={ id + '-other-input' }>
													{ __( 'Donation amount', 'newspack-blocks' ) }
												</label>
												<div className="wp-block-newspack-blocks-donate__money-input money-input">
													<span className="currency">{ settings.currencySymbol }</span>
													{ renderAmountValueInput( {
														frequencySlug,
														tierIndex: index,
														id: `${ id }-other-input`,
													} ) }
												</div>
											</>
										) : null }
									</div>
								);
							} ) }
						</div>
					</div>
				) ) }
			</div>
		</div>
	);

	const renderButton = () => (
		<button
			type="submit"
			onClick={ evt => evt.preventDefault() }
			style={ {
				backgroundColor: attributes.buttonColor,
				color: getColorForContrast( attributes.buttonColor ),
			} }
		>
			{ isRenderingStreamlinedBlock() ? (
				<RichText
					onChange={ ( value: string ) => setAttributes( { buttonWithCCText: value } ) }
					placeholder={ __( 'Button text…', 'newspack-blocks' ) }
					value={ attributes.buttonWithCCText }
					tagName="span"
				/>
			) : (
				<RichText
					onChange={ ( value: string ) => setAttributes( { buttonText: value } ) }
					placeholder={ __( 'Button text…', 'newspack-blocks' ) }
					value={ attributes.buttonText }
					tagName="span"
				/>
			) }
		</button>
	);

	const selectedPaymentRequestTypeOption = PAYMENT_REQUEST_BUTTON_TYPE_OPTIONS.find(
		option => option.value === attributes.paymentRequestType
	);
	const selectedPaymentRequestType = selectedPaymentRequestTypeOption
		? selectedPaymentRequestTypeOption.value
		: 'donate';

	const renderFooter = () => (
		<>
			<p className="wp-block-newspack-blocks-donate__thanks thanks">
				<RichText
					onChange={ ( value: string ) => setAttributes( { thanksText: value } ) }
					placeholder={ __( 'Thank you text…', 'newspack-blocks' ) }
					value={ attributes.thanksText }
					tagName="span"
				/>
			</p>
			{ isRenderingStreamlinedBlock() ? (
				<div className="wp-block-newspack-blocks-donate__stripe stripe-payment">
					<div className="stripe-payment__row stripe-payment__row--flex stripe-payment__footer">
						<div className="stripe-payment__methods">
							<div className="stripe-payment__request-button">
								<SelectControl
									options={ PAYMENT_REQUEST_BUTTON_TYPE_OPTIONS }
									value={ selectedPaymentRequestType }
									onChange={ (
										paymentRequestType: DonateBlockAttributes[ 'paymentRequestType' ]
									) => setAttributes( { paymentRequestType } ) }
								/>
								{ __( 'with Apple/Google Pay', 'newspack-blocks' ) }
							</div>
							{ renderButton() }
						</div>
					</div>
				</div>
			) : (
				renderButton()
			) }
		</>
	);

	if ( error.length ) {
		return (
			<Placeholder icon="warning" label={ __( 'Error', 'newspack-blocks' ) } instructions={ error }>
				<ExternalLink href="/wp-admin/admin.php?page=newspack-reader-revenue-wizard#/donations">
					{ __( 'Go to donation settings to troubleshoot.', 'newspack-blocks' ) }
				</ExternalLink>
			</Placeholder>
		);
	}

	if ( isLoading ) {
		return <Placeholder icon={ <Spinner /> } className="component-placeholder__align-center" />;
	}

	const isTiered = attributes.manual ? attributes.tiered : settings.tiered;

	return (
		<>
			<div className={ getClassNames( isTiered ? 'tiered' : 'untiered' ) }>
				<form ref={ formRef }>
					{ isTiered ? renderTieredForm() : renderUntieredForm() }
					{ renderFooter() }
				</form>
			</div>

			<InspectorControls>
				<PanelBody title={ __( 'Suggested Donations', 'newspack-blocks' ) }>
					<SelectControl
						label={ __( 'Default Tab', 'newspack' ) }
						value={ attributes.defaultFrequency }
						options={ availableFrequencies.map( key => ( {
							label: FREQUENCIES[ key ],
							value: key,
						} ) ) }
						onChange={ ( defaultFrequency: DonationFrequencySlug ) =>
							setAttributes( { defaultFrequency } )
						}
					/>
					<ToggleControl
						checked={ Boolean( attributes.manual ) }
						onChange={ () => setAttributes( { manual: ! attributes.manual } ) }
						label={ __( 'Configure manually', 'newspack-blocks' ) }
					/>
					{ attributes.manual ? (
						<>
							<ToggleControl
								checked={ Boolean( attributes.tiered ) }
								onChange={ () => setAttributes( { tiered: ! attributes.tiered } ) }
								label={ __( 'Tiered', 'newspack-blocks' ) }
							/>
							{ attributes.tiered ? (
								<>
									<div className="components-frequency-donations">
										{ FREQUENCY_SLUGS.map( ( frequency: DonationFrequencySlug ) => {
											const isFrequencyDisabled = attributes.disabledFrequencies[ frequency ];
											const isOneFrequencyActive =
												Object.values( attributes.disabledFrequencies ).filter( Boolean ).length ===
												FREQUENCY_SLUGS.length - 1;
											return (
												<Fragment key={ frequency }>
													<CheckboxControl
														label={ FREQUENCIES[ frequency ] }
														checked={ ! isFrequencyDisabled }
														disabled={ ! isFrequencyDisabled && isOneFrequencyActive }
														onChange={ () => {
															setAttributes( {
																disabledFrequencies: {
																	...attributes.disabledFrequencies,
																	[ frequency ]: ! isFrequencyDisabled,
																},
															} );
														} }
													/>
													{ ! isFrequencyDisabled && (
														<div className="wp-block-newspack-blocks-donate__panel-inputs">
															{ amounts[ frequency ].reduce(
																( acc: boolean, suggestedAmount: number ) => {
																	if ( suggestedAmount < attributes.minimumDonation ) {
																		return true;
																	}
																	return acc;
																},
																false
															) && (
																<p className="components-frequency-donations__error">
																	{ __(
																		'Warning: suggested donations should be at least the minimum donation amount.',
																		'newspack-blocks'
																	) }
																</p>
															) }
															{ amounts[ frequency ].map( ( suggestedAmount, tierIndex ) =>
																renderAmountValueInput( {
																	frequencySlug: frequency,
																	tierIndex,
																	label: TIER_LABELS[ tierIndex ],
																	id: `${ frequency }-${ tierIndex }-amount`,
																} )
															) }
														</div>
													) }
												</Fragment>
											);
										} ) }
									</div>
								</>
							) : (
								<div className="components-frequency-donations">
									<div className="wp-block-newspack-blocks-donate__panel-inputs">
										{ FREQUENCY_SLUGS.reduce(
											( acc: boolean, frequencySlug: DonationFrequencySlug ) => {
												if ( amounts[ frequencySlug ][ 3 ] < attributes.minimumDonation ) {
													return true;
												}
												return acc;
											},
											false
										) && (
											<p className="components-frequency-donations__error">
												{ __(
													'Warning: suggested donations should be at least the minimum donation amount.',
													'newspack-blocks'
												) }
											</p>
										) }
										{ FREQUENCY_SLUGS.map( ( frequencySlug: DonationFrequencySlug ) =>
											renderAmountValueInput( {
												frequencySlug,
												tierIndex: 3,
												label: FREQUENCIES[ frequencySlug ],
												id: `${ frequencySlug }-${ 3 }-amount`,
											} )
										) }
									</div>
								</div>
							) }
							<TextControl
								className="components-frequency-donations__minimum-donation"
								type="number"
								label={ __( 'Minimum donation', 'newspack-blocks' ) }
								min={ 1 }
								onChange={ ( value: number ) => setAttributes( { minimumDonation: value } ) }
								value={ attributes.minimumDonation }
							/>
						</>
					) : (
						<p>
							{ __(
								'The Donate Block allows you to collect donations from readers. The fields are automatically defined based on your donation settings.',
								'newspack-blocks'
							) }
							<br />
							<br />
							<ExternalLink href="/wp-admin/admin.php?page=newspack-reader-revenue-wizard#/donations">
								{ __( 'Edit donation settings', 'newspack-blocks' ) }
							</ExternalLink>
						</p>
					) }
				</PanelBody>
				<PanelBody title={ __( 'Styling', 'newspack-blocks' ) } initialOpen={ false }>
					<ColorPaletteControl
						value={ attributes.buttonColor }
						onChange={ ( buttonColor: string ) => setAttributes( { buttonColor } ) }
						label={ __( 'Button Color', 'newspack-blocks' ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Campaign', 'newspack-blocks' ) } initialOpen={ false }>
					<TextControl
						label={ __( 'Campaign ID', 'newspack-blocks' ) }
						value={ attributes.campaign || '' }
						onChange={ ( value: string ) =>
							setAttributes( {
								campaign: value,
							} )
						}
					/>
				</PanelBody>
			</InspectorControls>
		</>
	);
};

export default Edit;
