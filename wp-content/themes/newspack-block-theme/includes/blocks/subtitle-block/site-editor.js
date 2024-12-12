/* globals newspack_block_theme_subtitle_block */

/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { Icon, listView } from '@wordpress/icons';
import { useEntityProp } from '@wordpress/core-data';

import blockData from './block.json';

const EditComponent = ( { context: { postType, postId } } ) => {
	const [ postMeta = {} ] = useEntityProp( 'postType', postType, 'meta', postId );
	return (
		postMeta[ newspack_block_theme_subtitle_block.post_meta_name ] ||
		__( 'Article subtitle', 'newspack-block-theme' )
	);
};

blockData = {
	title: __( 'Article Subtitle', 'newspack-block-theme' ),
	icon: {
		src: <Icon icon={ listView } />,
		foreground: '#36f',
	},
	edit: EditComponent,
	usesContext: [ 'postId', 'postType' ],
	...blockData,
};

registerBlockType( blockData.name, blockData );
