/* eslint-disable @wordpress/no-unsafe-wp-apis */
/* globals newspack_blocks_data */

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
	CheckboxControl,
	TextControl,
	SelectControl,
	FormTokenField,
	Button,
	ButtonGroup,
	Spinner,
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import './edit.scss';
import RedirectAfterSuccess from '../../components/redirect-after-success';

function getVariationName( variation ) {
	const attributes = [];
	for ( const attribute of variation.attributes ) {
		attributes.push( attribute.name + ': ' + attribute.option );
	}
	return attributes.join( ', ' );
}

function getNYP( product ) {
	return {
		isNYP:
			newspack_blocks_data?.can_use_name_your_price &&
			product?.meta_data?.some( meta => meta.key === '_nyp' && meta.value === 'yes' ),
		suggestedPrice: product?.meta_data?.find( meta => meta.key === '_suggested_price' )?.value,
		minPrice: product?.meta_data?.find( meta => meta.key === '_min_price' )?.value,
		maxPrice: product?.meta_data?.find( meta => meta.key === '_maximum_price' )?.value,
	};
}

function WidthPanel( { selectedWidth, setAttributes } ) {
	function handleChange( newWidth ) {
		// Check if we are toggling the width off
		const width = selectedWidth === newWidth ? undefined : newWidth;

		// Update attributes.
		setAttributes( { width } );
	}

	return (
		<PanelBody title={ __( 'Width settings', 'newspack-blocks' ) }>
			<ButtonGroup aria-label={ __( 'Button width', 'newspack-blocks' ) }>
				{ [ 25, 50, 75, 100 ].map( widthValue => {
					return (
						<Button
							key={ widthValue }
							size="small"
							variant={ widthValue === selectedWidth ? 'primary' : undefined }
							onClick={ () => handleChange( widthValue ) }
						>
							{ widthValue }%
						</Button>
					);
				} ) }
			</ButtonGroup>
		</PanelBody>
	);
}

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
					{ props.children }
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
	const { placeholder, style, text, product, price, variation, width } = attributes;

	const [ productData, setProductData ] = useState( {} );
	const [ variations, setVariations ] = useState( [] );
	const [ nyp, setNYP ] = useState( false );

	function handleProduct( data ) {
		setProductData( data );

		// Handle product variation data.
		if ( data?.variations?.length ) {
			setAttributes( { is_variable: true } );
			apiFetch( { path: `/wc/v2/products/${ data.id }/variations?per_page=100` } )
				.then( res => setVariations( res ) )
				.catch( () => setVariations( [] ) );
		} else {
			setAttributes( { is_variable: false } );
			setVariations( [] );
		}

		// Handle NYP data.
		if ( ! variation ) {
			setNYP( getNYP( data ) );
		}
	}

	useEffect( () => {
		if ( variation ) {
			apiFetch( { path: `/wc/v2/products/${ product }/variations/${ variation }` } )
				.then( res => setNYP( getNYP( res ) ) )
				.catch( () => setNYP( {} ) );
		} else {
			setNYP( getNYP( productData ) );
		}
	}, [ variation ] );

	useEffect( () => {
		if ( ! price && nyp?.suggestedPrice ) {
			setAttributes( { price: nyp.suggestedPrice } );
		}
	}, [ nyp ] );

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
					[ `has-custom-width wp-block-button__width-${ width }` ]: width,
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
						onChange={ value => setAttributes( { product: value, variation: '', price: '' } ) }
						onProduct={ handleProduct }
					>
						{ productData?.variations?.length > 0 && (
							<>
								<CheckboxControl
									label={ __(
										'Allow the reader to select the variation before checkout.',
										'newspack-blocks'
									) }
									checked={ ! variation }
									onChange={ value =>
										setAttributes( {
											variation: value ? '' : variations[ 0 ].id.toString(),
											price: '',
										} )
									}
								/>
								{ variations.length ? (
									<SelectControl
										label={ __( 'Variation', 'newspack-blocks' ) }
										help={ __(
											'Select the product variation to be added to cart.',
											'newspack-blocks'
										) }
										value={ variation }
										disabled={ ! variation }
										options={ [
											{ label: '--', value: '' },
											...variations.map( item => ( {
												label: getVariationName( item ),
												value: item.id,
											} ) ),
										] }
										onChange={ value =>
											setAttributes( { variation: value.toString(), price: '' } )
										}
									/>
								) : (
									<Spinner />
								) }
							</>
						) }
					</ProductControl>
				</PanelBody>
				<PanelBody title={ __( 'After purchase', 'newspack-blocks' ) }>
					<RedirectAfterSuccess setAttributes={ setAttributes } attributes={ attributes } />
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
			<InspectorControls>
				<WidthPanel selectedWidth={ width } setAttributes={ setAttributes } />
			</InspectorControls>
		</>
	);
}

export default CheckoutButtonEdit;
