/* eslint-disable @wordpress/no-unsafe-wp-apis */
/**
 * External dependencies
 */
import classnames from 'classnames';
import { debounce, invert } from 'lodash';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	RichText,
	useBlockProps,
	__experimentalUseBorderProps as useBorderProps,
	__experimentalUseColorProps as useColorProps,
	__experimentalGetSpacingClassesAndStyles as useSpacingProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	BaseControl,
	TextControl,
	FormTokenField,
	Button,
	Spinner,
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import './edit.scss';

function ProductControl( props ) {
	const [ inFlight, setInFlight ] = useState( false );
	const [ suggestions, setSuggestions ] = useState( {} );
	const [ selected, setSelected ] = useState( false );
	const [ isChanging, setIsChanging ] = useState( false );
	function fetchSuggestions( search ) {
		setInFlight( true );
		return apiFetch( {
			path: `/wc/v2/products?search=${ encodeURIComponent( search ) }`,
		} )
			.then( products => {
				const _suggestions = {};
				products.forEach( product => {
					_suggestions[ product.id ] = `${ product.id }: ${ product.name }`;
				} );
				setSuggestions( _suggestions );
			} )
			.finally( () => setInFlight( false ) );
	}
	function fetchSaved() {
		setInFlight( true );
		return apiFetch( {
			path: `/wc/v2/products/${ props.value }`,
		} )
			.then( product => {
				setSuggestions( { [ product.id ]: `${ product.id }: ${ product.name }` } );
				setSelected( product );
				props.onProduct( product );
			} )
			.finally( () => setInFlight( false ) );
	}
	useEffect( () => {
		setIsChanging( false );
		if ( props.value ) {
			fetchSaved();
		} else {
			setSelected( false );
		}
	}, [ props.value ] );
	function onChange( tokens ) {
		const productName = tokens[ 0 ];
		const productId = invert( suggestions )[ productName ];
		setIsChanging( false );
		props.onChange( productId );
	}
	const debouncedFetchProductSuggestions = debounce( fetchSuggestions, 200 );
	const handleInputChange = value => {
		if ( value.length > 2 ) {
			setInFlight( true );
			debouncedFetchProductSuggestions( value );
		} else {
			setInFlight( false );
		}
	};
	if ( props.value && ! selected && inFlight ) {
		return <Spinner />;
	}
	return (
		<div className="newspack-checkout-button__product-field">
			{ selected && ! isChanging ? (
				<>
					<BaseControl
						className="newspack-checkout-button__product-field__selected"
						help={ __( 'Click to change the selected product', 'newspack-blocks' ) }
					>
						<Button
							isSecondary
							onClick={ () => setIsChanging( true ) }
							aria-label={ __( 'Change the selected product', 'newspack-blocks' ) }
						>
							{ selected.name }
						</Button>
					</BaseControl>
				</>
			) : (
				<>
					<div className="newspack-checkout-button__product-field__tokenfield">
						<FormTokenField
							placeholder={
								props.placeholder || __( 'Type to search for a product…', 'newspack-blocks' )
							}
							label={ __( 'Select a product', 'newspack-blocks' ) }
							maxLength={ 1 }
							onChange={ onChange }
							onInputChange={ handleInputChange }
							suggestions={ Object.values( suggestions ) }
						/>
						{ inFlight && <Spinner /> }
					</div>
					{ selected && (
						<Button isSecondary onClick={ () => setIsChanging( false ) }>
							{ __( 'Cancel', 'newspack-blocks' ) }
						</Button>
					) }
				</>
			) }
		</div>
	);
}

function CheckoutButtonEdit( props ) {
	const { attributes, setAttributes, className } = props;
	const { placeholder, style, text, product, price } = attributes;

	const [ nyp, setNYP ] = useState( false );
	function handleProduct( data ) {
		const _nyp = {
			isNYP: data?.meta_data?.some( meta => meta.key === '_nyp' && meta.value === 'yes' ),
			suggestedPrice: data?.meta_data?.find( meta => meta.key === '_suggested_price' )?.value,
			minPrice: data?.meta_data?.find( meta => meta.key === '_min_price' )?.value,
			maxPrice: data?.meta_data?.find( meta => meta.key === '_maximum_price' )?.value,
		};
		setNYP( _nyp );
		if ( ! price ) {
			setAttributes( { price: _nyp?.suggestedPrice } );
		}
	}

	function setButtonText( newText ) {
		// Remove anchor tags from button text content.
		setAttributes( { text: newText.replace( /<\/?a[^>]*>/g, '' ) } );
	}

	const borderProps = useBorderProps( attributes );
	const colorProps = useColorProps( attributes );
	const spacingProps = useSpacingProps( attributes );
	const blockProps = useBlockProps();
	return (
		<>
			<div
				{ ...blockProps }
				className={ classnames( blockProps.className, {
					[ `wp-block-button` ]: true,
					[ `has-custom-font-size` ]: blockProps.style.fontSize,
				} ) }
			>
				<RichText
					aria-label={ __( 'Button text' ) }
					placeholder={ placeholder || __( 'Add text…' ) }
					value={ text }
					onChange={ value => setButtonText( value ) }
					withoutInteractiveFormatting
					className={ classnames(
						className,
						'wp-block-button__link',
						'wp-block-newspack-blocks-checkout-button__button',
						colorProps.className,
						borderProps.className,
						{
							// For backwards compatibility add style that isn't
							// provided via block support.
							'no-border-radius': style?.border?.radius === 0,
						}
					) }
					style={ {
						...borderProps.style,
						...colorProps.style,
						...spacingProps.style,
					} }
					identifier="text"
				/>
			</div>
			<InspectorControls>
				<PanelBody title={ __( 'Product', 'newspack-blocks' ) }>
					<ProductControl
						value={ product }
						price={ price }
						onChange={ value => setAttributes( { product: value } ) }
						onProduct={ handleProduct }
					/>
				</PanelBody>
				{ nyp?.isNYP && (
					<PanelBody title={ __( 'Name Your Price', 'newspack-blocks' ) }>
						<p>
							{ __(
								'This product has "Name Your Price" toggled on. You can set the custom price for this checkout.',
								'newspack-blocks'
							) }
						</p>
						<p>
							<strong>{ __( 'Suggested price:', 'newspack-blocks' ) }</strong>{ ' ' }
							{ nyp.suggestedPrice || 0 }
							{ nyp.minPrice && (
								<>
									<br />
									<strong>{ __( 'Minimum price:', 'newspack-blocks' ) }</strong> { nyp.minPrice }
								</>
							) }
							{ nyp.maxPrice && (
								<>
									<br />
									<strong>{ __( 'Maximum price:', 'newspack-blocks' ) }</strong> { nyp.maxPrice }
								</>
							) }
						</p>
						<TextControl
							type="number"
							label={ __( 'Custom Price', 'newspack-blocks' ) }
							placeholder={ nyp.suggestedPrice }
							value={ price }
							min={ parseFloat( nyp.minPrice ) || null }
							max={ parseFloat( nyp.maxPrice ) || null }
							onChange={ value => setAttributes( { price: value } ) }
						/>
					</PanelBody>
				) }
			</InspectorControls>
		</>
	);
}

export default CheckoutButtonEdit;
