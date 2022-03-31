/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { image, Icon } from '@woocommerce/icons';

export const BLOCK_TITLE = __(
	'Product Image',
	'woocommerce'
);
export const BLOCK_ICON = (
	<Icon
		srcElement={ image }
		className="wc-block-editor-components-block-icon"
	/>
);
export const BLOCK_DESCRIPTION = __(
	'Display the main product image',
	'woocommerce'
);
