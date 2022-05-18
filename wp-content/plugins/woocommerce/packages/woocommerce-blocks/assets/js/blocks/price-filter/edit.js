/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { getAdminLink } from '@woocommerce/settings';
import { blocksConfig } from '@woocommerce/block-settings';
import HeadingToolbar from '@woocommerce/editor-components/heading-toolbar';
import BlockTitle from '@woocommerce/editor-components/block-title';
import { Icon, currencyDollar, external } from '@wordpress/icons';
import {
	Placeholder,
	Disabled,
	PanelBody,
	ToggleControl,
	Button,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToggleGroupControl as ToggleGroupControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import Block from './block.js';
import './editor.scss';

export default function ( { attributes, setAttributes } ) {
	const {
		heading,
		headingLevel,
		showInputFields,
		showFilterButton,
	} = attributes;

	const blockProps = useBlockProps();

	const getInspectorControls = () => {
		return (
			<InspectorControls key="inspector">
				<PanelBody
					title={ __(
						'Block Settings',
						'woocommerce'
					) }
				>
					<ToggleGroupControl
						label={ __(
							'Price Range',
							'woocommerce'
						) }
						value={ showInputFields ? 'editable' : 'text' }
						onChange={ ( value ) =>
							setAttributes( {
								showInputFields: value === 'editable',
							} )
						}
					>
						<ToggleGroupControlOption
							value="editable"
							label={ __(
								'Editable',
								'woocommerce'
							) }
						/>
						<ToggleGroupControlOption
							value="text"
							label={ __(
								'Text',
								'woocommerce'
							) }
						/>
					</ToggleGroupControl>
					<ToggleControl
						label={ __(
							'Filter button',
							'woocommerce'
						) }
						help={
							showFilterButton
								? __(
										'Products will only update when the button is pressed.',
										'woocommerce'
								  )
								: __(
										'Products will update when the slider is moved.',
										'woocommerce'
								  )
						}
						checked={ showFilterButton }
						onChange={ () =>
							setAttributes( {
								showFilterButton: ! showFilterButton,
							} )
						}
					/>
					<p>
						{ __(
							'Heading Level',
							'woocommerce'
						) }
					</p>
					<HeadingToolbar
						isCollapsed={ false }
						minLevel={ 2 }
						maxLevel={ 7 }
						selectedLevel={ headingLevel }
						onChange={ ( newLevel ) =>
							setAttributes( { headingLevel: newLevel } )
						}
					/>
				</PanelBody>
			</InspectorControls>
		);
	};

	const noProductsPlaceholder = () => (
		<Placeholder
			className="wc-block-price-slider"
			icon={ <Icon icon={ currencyDollar } /> }
			label={ __(
				'Filter Products by Price',
				'woocommerce'
			) }
			instructions={ __(
				'Display a slider to filter products in your store by price.',
				'woocommerce'
			) }
		>
			<p>
				{ __(
					"Products with prices are needed for filtering by price. You haven't created any products yet.",
					'woocommerce'
				) }
			</p>
			<Button
				className="wc-block-price-slider__add-product-button"
				isSecondary
				href={ getAdminLink( 'post-new.php?post_type=product' ) }
			>
				{ __( 'Add new product', 'woocommerce' ) +
					' ' }
				<Icon icon={ external } />
			</Button>
			<Button
				className="wc-block-price-slider__read_more_button"
				isTertiary
				href="https://docs.woocommerce.com/document/managing-products/"
			>
				{ __( 'Learn more', 'woocommerce' ) }
			</Button>
		</Placeholder>
	);

	return (
		<div { ...blockProps }>
			{ blocksConfig.productCount === 0 ? (
				noProductsPlaceholder()
			) : (
				<>
					{ getInspectorControls() }
					<BlockTitle
						className="wc-block-price-filter__title"
						headingLevel={ headingLevel }
						heading={ heading }
						onChange={ ( value ) =>
							setAttributes( { heading: value } )
						}
					/>
					<Disabled>
						<Block attributes={ attributes } isEditor={ true } />
					</Disabled>
				</>
			) }
		</div>
	);
}
