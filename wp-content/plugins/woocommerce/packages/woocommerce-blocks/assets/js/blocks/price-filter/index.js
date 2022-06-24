/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { createBlock, registerBlockType } from '@wordpress/blocks';
import classNames from 'classnames';
import { Icon, currencyDollar } from '@wordpress/icons';
import { isFeaturePluginBuild } from '@woocommerce/block-settings';
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import edit from './edit.js';

registerBlockType( 'woocommerce/price-filter', {
	apiVersion: 2,
	title: __( 'Filter Products by Price', 'woocommerce' ),
	icon: {
		src: (
			<Icon
				icon={ currencyDollar }
				className="wc-block-editor-components-block-icon"
			/>
		),
	},
	category: 'woocommerce',
	keywords: [ __( 'WooCommerce', 'woocommerce' ) ],
	description: __(
		'Allow customers to filter the products by choosing a lower or upper price limit. Works in combination with the All Products block.',
		'woocommerce'
	),
	supports: {
		html: false,
		multiple: false,
		color: {
			text: true,
			background: false,
		},
		...( isFeaturePluginBuild() && {
			__experimentalBorder: {
				radius: true,
				color: true,
				width: false,
			},
		} ),
	},
	example: {},
	attributes: {
		showInputFields: {
			type: 'boolean',
			default: true,
		},
		showFilterButton: {
			type: 'boolean',
			default: false,
		},
		heading: {
			type: 'string',
			default: __( 'Filter by price', 'woocommerce' ),
		},
		headingLevel: {
			type: 'number',
			default: 3,
		},
	},
	transforms: {
		from: [
			{
				type: 'block',
				blocks: [ 'core/legacy-widget' ],
				// We can't transform if raw instance isn't shown in the REST API.
				isMatch: ( { idBase, instance } ) =>
					idBase === 'woocommerce_price_filter' && !! instance?.raw,
				transform: ( { instance } ) =>
					createBlock( 'woocommerce/price-filter', {
						showInputFields: false,
						showFilterButton: true,
						heading:
							instance?.raw?.title ||
							__(
								'Filter by price',
								'woocommerce'
							),
						headingLevel: 3,
					} ),
			},
		],
	},
	edit,
	save( { attributes } ) {
		const {
			className,
			showInputFields,
			showFilterButton,
			heading,
			headingLevel,
		} = attributes;
		const data = {
			'data-showinputfields': showInputFields,
			'data-showfilterbutton': showFilterButton,
			'data-heading': heading,
			'data-heading-level': headingLevel,
		};
		return (
			<div
				{ ...useBlockProps.save( {
					className: classNames( 'is-loading', className ),
				} ) }
				{ ...data }
			>
				<span
					aria-hidden
					className="wc-block-product-categories__placeholder"
				/>
			</div>
		);
	},
} );
