/**
 * External dependencies
 */
import classNames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useMemo, useEffect, useRef, useState } from '@wordpress/element';
import { RichText } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import { AmountValueInput } from './components';
import { getColorForContrast, getFrequencyLabel } from '../utils';
import { FREQUENCIES } from '../consts';
import type { ComponentProps, DonationFrequencySlug } from '../types';

const FrequencyBasedLayout = ( props: { isTiered: boolean } & ComponentProps ) => {
	// Unique identifier to prevent collisions with other Donate blocks' labels.
	const uid = useMemo( () => Math.random().toString( 16 ).slice( 2 ), [] );

	const { attributes, settings, amounts, availableFrequencies, setAttributes } = props;

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

	const [ selectedFrequency, setSelectedFrequency ] = useState( attributes.defaultFrequency );
	const canUseNameYourPrice = window.newspack_blocks_data?.can_use_name_your_price;
	const renderFrequencySelect = ( frequencySlug: DonationFrequencySlug ) => (
		<>
			<input
				type="radio"
				value={ frequencySlug }
				id={ `newspack-donate-${ frequencySlug }-${ uid }` }
				name="donation_frequency"
				checked={ frequencySlug === selectedFrequency }
				onChange={ evt => setSelectedFrequency( evt.target.value as 'once' | 'month' | 'year' ) }
			/>
			<label htmlFor={ 'newspack-donate-' + frequencySlug + '-' + uid }>
				{ FREQUENCIES[ frequencySlug ] }
			</label>
		</>
	);

	const renderTab = ( frequencySlug: DonationFrequencySlug ) => (
		<button
			key={ frequencySlug }
			role="tab"
			className={ classNames( 'wpbnbd__button freq-label', {
				'wpbnbd__button--active': frequencySlug === selectedFrequency,
			} ) }
			id={ `tab-newspack-donate-${ frequencySlug }-${ uid }` }
			onClick={ evt => {
				evt.preventDefault();
				setSelectedFrequency( frequencySlug );
			} }
		>
			{ FREQUENCIES[ frequencySlug ] }
		</button>
	);

	// This code is fired on tab select and updates aria elements, tabindex states, and radio buttons
	const displayAmount = ( amount: number ) => amount.toFixed( 2 ).replace( /\.?0*$/, '' );

	const renderUntieredForm = () => (
		<div className="wp-block-newspack-blocks-donate__options">
			<div className="wp-block-newspack-blocks-donate__frequencies frequencies">
				<div className="tab-container">{ availableFrequencies.map( renderTab ) }</div>
				{ availableFrequencies.map( frequencySlug => {
					const untieredAmount = amounts[ frequencySlug ][ 3 ];
					return (
						<div
							className="wp-block-newspack-blocks-donate__frequency frequency"
							key={ frequencySlug }
						>
							{ renderFrequencySelect( frequencySlug ) }
							<div className="input-container">
								{ canUseNameYourPrice ? (
									<>
										<label
											className="donate-label"
											htmlFor={ 'newspack-' + frequencySlug + '-' + uid + '-untiered-input' }
										>
											{ __( 'Donation amount', 'newspack-blocks' ) }
										</label>
										<div className="wp-block-newspack-blocks-donate__money-input money-input">
											<span className="currency">{ settings.currencySymbol }</span>
											<AmountValueInput
												{ ...props }
												frequencySlug={ frequencySlug }
												tierIndex={ 3 }
												id={ `newspack-${ frequencySlug }-${ uid }-untiered-input` }
											/>
										</div>
									</>
								) : (
									<>
										<input
											type="radio"
											value={ untieredAmount }
											className={ 'frequency-input' }
											id={ `newspack-${ frequencySlug }-${ uid }-untiered-input` }
											name={ `donation_value_${ frequencySlug }` }
											defaultChecked={ true }
										/>
										<label
											className="tier-select-label tier-label"
											htmlFor={ `newspack-${ frequencySlug }-${ uid }-untiered-input` }
										>
											<div
												dangerouslySetInnerHTML={ {
													__html: getFrequencyLabel( untieredAmount, frequencySlug ),
												} }
											/>
										</label>
									</>
								) }
							</div>
						</div>
					);
				} ) }
			</div>
		</div>
	);

	const renderTieredForm = () => (
		<div className="wp-block-newspack-blocks-donate__options">
			<div className="wp-block-newspack-blocks-donate__frequencies frequencies">
				<div className="tab-container">{ availableFrequencies.map( renderTab ) }</div>
				{ availableFrequencies.map( frequencySlug => (
					<div
						className="wp-block-newspack-blocks-donate__frequency frequency"
						key={ frequencySlug }
					>
						{ renderFrequencySelect( frequencySlug ) }

						<div className="wp-block-newspack-blocks-donate__tiers tiers">
							{ amounts[ frequencySlug ].map( ( suggestedAmount, index: number ) => {
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
													<input type="number" readOnly />
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
		<div
			className="submit-button"
			style={ {
				backgroundColor: attributes.buttonColor,
				color: getColorForContrast( attributes.buttonColor ),
			} }
		>
			<RichText
				onChange={ ( value: string ) => setAttributes( { buttonText: value } ) }
				placeholder={ __( 'Button text…', 'newspack-blocks' ) }
				value={ attributes.buttonText }
				tagName="span"
			/>
		</div>
	);

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
			{ renderButton() }
		</>
	);

	return (
		<form ref={ formRef }>
			{ props.isTiered ? renderTieredForm() : renderUntieredForm() }
			{ renderFooter() }
		</form>
	);
};
export default FrequencyBasedLayout;
