/**
 * External dependencies
 */
import { omit } from 'lodash';

/**
 * WordPress dependencies
 */
import { createBlock, getBlockContent } from '@wordpress/blocks';
import { dateI18n, __experimentalGetSettings } from '@wordpress/date';

/**
 * Internal dependencies
 */
import { POSTS_INSERTER_BLOCK_NAME } from './consts';

const getHeadingBlockTemplate = post => [
	'core/heading',
	{ content: `<a href="${ post.link }">${ post.title.rendered }</a>`, level: 3 },
];

const getDateBlockTemplate = post => {
	const dateFormat = __experimentalGetSettings().formats.date;
	return [
		'core/paragraph',
		{
			content: dateI18n( dateFormat, post.date_gmt ),
			fontSize: 'normal',
			customTextColor: '#444444',
		},
	];
};

const getExcerptBlockTemplate = ( post, { excerptLength } ) => {
	let excerpt = post.content.rendered;
	const excerptElement = document.createElement( 'div' );
	excerptElement.innerHTML = excerpt;
	excerpt = excerptElement.textContent || excerptElement.innerText || '';

	const needsEllipsis = excerptLength < excerpt.trim().split( ' ' ).length;

	const postExcerpt = needsEllipsis
		? `${ excerpt.split( ' ', excerptLength ).join( ' ' ) } […]`
		: excerpt;
	return [ 'core/paragraph', { content: postExcerpt.trim() } ];
};

const createBlockTemplatesForSinglePost = (
	post,
	{
		displayPostDate,
		displayPostExcerpt,
		excerptLength,
		displayFeaturedImage,
		featuredImageAlignment,
	}
) => {
	const postContentBlocks = [ getHeadingBlockTemplate( post ) ];

	if ( displayPostDate && post.date_gmt ) {
		postContentBlocks.push( getDateBlockTemplate( post ) );
	}
	if ( displayPostExcerpt ) {
		postContentBlocks.push( getExcerptBlockTemplate( post, { excerptLength } ) );
	}

	const hasFeaturedImage = post.featuredImageLargeURL || post.featuredImageMediumURL;

	if ( displayFeaturedImage && hasFeaturedImage ) {
		const getImageBlock = ( alignCenter = false ) => [
			'core/image',
			{
				url: alignCenter ? post.featuredImageLargeURL : post.featuredImageMediumURL,
				linkDestination: post.link,
				...( alignCenter ? { align: 'center' } : {} ),
			},
		];
		const imageColumnBlock = [ 'core/column', {}, [ getImageBlock() ] ];
		const postContentColumnBlock = [ 'core/column', {}, postContentBlocks ];
		switch ( featuredImageAlignment ) {
			case 'left':
				return [ [ 'core/columns', {}, [ imageColumnBlock, postContentColumnBlock ] ] ];
			case 'right':
				return [ [ 'core/columns', {}, [ postContentColumnBlock, imageColumnBlock ] ] ];
			case 'top':
				return [ getImageBlock( true ), ...postContentBlocks ];
		}
	}
	return postContentBlocks;
};

const createBlockFromTemplate = ( [ name, blockAttributes, innerBlocks = [] ] ) =>
	createBlock( name, blockAttributes, innerBlocks.map( createBlockFromTemplate ) );

const createBlockTemplatesForPosts = ( posts, attributes ) =>
	posts.reduce( ( blocks, post ) => {
		return [ ...blocks, ...createBlockTemplatesForSinglePost( post, attributes ) ];
	}, [] );

export const getTemplateBlocks = ( postList, attributes ) =>
	createBlockTemplatesForPosts( postList, attributes ).map( createBlockFromTemplate );

/**
 * Converts a block object to a shape processable by the backend,
 * which contains block's HTML.
 *
 * @param {Object} block block, as understood by the block editor
 * @return {Object} block with innerHTML, processable by the backend
 */
export const convertBlockSerializationFormat = block => ( {
	attrs: omit( block.attributes, 'content' ),
	blockName: block.name,
	innerHTML: getBlockContent( block ),
	innerBlocks: block.innerBlocks.map( convertBlockSerializationFormat ),
} );

// In some cases, the Posts Inserter block should not handle deduplication.
// Previews might be displayed next to each other or next to a post, which results in multiple block lists.
// The deduplication store relies on the assumption that a post has a single blocks list, which
// is not true when there are block previews used.
export const setPreventDeduplicationForPostsInserter = blocks =>
	blocks.map( block => {
		if ( block.name === POSTS_INSERTER_BLOCK_NAME ) {
			block.attributes.preventDeduplication = true;
		}
		if ( block.innerBlocks ) {
			block.innerBlocks = setPreventDeduplicationForPostsInserter( block.innerBlocks );
		}
		return block;
	} );
