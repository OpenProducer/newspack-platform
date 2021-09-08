/**
 * External dependencies
 */
import { times, isEqual, isUndefined, pick, pickBy } from 'lodash';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

import { STORE_NAMESPACE } from './store';

/**
 * Based global WP.com blog_public option, checks whether current blog is
 * private or not.
 *
 * @return {boolean} a private WP.com blog flag
 */
export const isBlogPrivate = () =>
	typeof window === 'object' &&
	window.wpcomGutenberg &&
	Number( window.wpcomGutenberg.blogPublic ) === -1;

/**
 * Block attributes which influence posts query
 */
const POST_QUERY_ATTRIBUTES = [
	'postsToShow',
	'authors',
	'categories',
	'excerptLength',
	'tags',
	'showExcerpt',
	'specificPosts',
	'specificMode',
	'tagExclusions',
	'categoryExclusions',
	'postType',
];

/**
 * Does the props change necessitate a reflow?
 * A reflow should happen if:
 * 1. Query-changing attributes of a block change
 * 2. The top-level blocks order changes. A Homepage Articles
 *    block might be nested somewhere.
 *
 * @param {Object} prevProps Edit component props
 * @param {Object} props     Edit component props
 */
export const shouldReflow = ( prevProps, props ) =>
	! isEqual(
		pick( prevProps.attributes, POST_QUERY_ATTRIBUTES ),
		pick( props.attributes, POST_QUERY_ATTRIBUTES )
	) || ! isEqual( prevProps.topBlocksClientIdsInOrder, props.topBlocksClientIdsInOrder );

/**
 * Builds query criteria from given attributes.
 *
 * @param {Object} attributes block attributes
 * @return {Object} criteria
 */
export const queryCriteriaFromAttributes = attributes => {
	const {
		postsToShow,
		authors,
		categories,
		excerptLength,
		postType,
		showExcerpt,
		tags,
		specificPosts = [],
		specificMode,
		tagExclusions,
		categoryExclusions,
	} = pick( attributes, POST_QUERY_ATTRIBUTES );

	const cleanPosts = sanitizePostList( specificPosts );
	const isSpecificPostModeActive = specificMode && cleanPosts && cleanPosts.length;
	const criteria = pickBy(
		isSpecificPostModeActive
			? {
					include: cleanPosts,
					per_page: specificPosts.length,
					post_type: postType,
			  }
			: {
					per_page: postsToShow,
					categories,
					author: authors,
					tags,
					tags_exclude: tagExclusions,
					categories_exclude: categoryExclusions,
					post_type: postType,
			  },
		value => ! isUndefined( value )
	);
	criteria.excerpt_length = excerptLength;
	criteria.show_excerpt = showExcerpt;
	return criteria;
};

export const sanitizePostList = postList =>
	postList.map( id => parseInt( id ) ).filter( id => id > 0 );

export const getBlockQueries = ( blocks, blockNames ) =>
	blocks.flatMap( block => {
		const homepageArticleBlocks = [];
		if ( blockNames.indexOf( block.name ) >= 0 ) {
			const postsQuery = queryCriteriaFromAttributes( block.attributes );
			homepageArticleBlocks.push( { postsQuery, clientId: block.clientId } );
		}
		return homepageArticleBlocks.concat( getBlockQueries( block.innerBlocks, blockNames ) );
	} );

export const getEditorBlocksIds = blocks =>
	blocks.flatMap( block => {
		const homepageArticleBlocks = [];
		homepageArticleBlocks.push( block.clientId );
		return homepageArticleBlocks.concat( getEditorBlocksIds( block.innerBlocks ) );
	} );

const PREVIEW_IMAGE_BASE = window.newspack_blocks_data.assets_path;
const generatePreviewPost = id => {
	const now = new Date();
	now.setHours( 12, 0, 0, 0 );
	return {
		author: 1,
		content: {
			rendered: '<p>' + __( 'The post content.', 'newspack' ) + '</p>',
		},
		date_gmt: now.toISOString(),
		excerpt: {
			rendered: '<p>' + __( 'The post excerpt.', 'newspack' ) + '</p>',
		},
		featured_media: '1',
		id,
		meta: {
			newspack_post_subtitle: __( 'Post Subtitle', 'newspack' ),
		},
		title: {
			rendered: __( 'Post Title', 'newspack' ),
		},
		newspack_article_classes: 'type-post',
		newspack_author_info: [
			{
				display_name: __( 'Author Name', 'newspack' ),
				avatar: `<div style="background: #36f;width: 40px;height: 40px;display: block;overflow: hidden;border-radius: 50%; max-width: 100%; max-height: 100%;"></div>`,
				id: 1,
				author_link: '/',
			},
		],
		newspack_category_info: __( 'Category', 'newspack' ),
		newspack_featured_image_caption: __( 'Featured image caption', 'newspack' ),
		newspack_featured_image_src: {
			large: `${ PREVIEW_IMAGE_BASE }/newspack-1024x536.jpg`,
			landscape: `${ PREVIEW_IMAGE_BASE }/newspack-800x600.jpg`,
			portrait: `${ PREVIEW_IMAGE_BASE }/newspack-600x800.jpg`,
			square: `${ PREVIEW_IMAGE_BASE }/newspack-800x800.jpg`,
			uncropped: `${ PREVIEW_IMAGE_BASE }/newspack-1024x536.jpg`,
		},
		newspack_has_custom_excerpt: false,
		newspack_post_format: 'standard',
		newspack_post_sponsors: false,
	};
};

const getPreviewPosts = attributes => times( attributes.postsToShow, generatePreviewPost );

export const postsBlockSelector = ( select, { clientId, attributes } ) => {
	const { getPostTypes } = select( 'core' );
	const { getEditorBlocks } = select( 'core/editor' );
	const { getBlocks } = select( 'core/block-editor' );
	const editorBlocksIds = getEditorBlocksIds( getEditorBlocks() );
	// The block might be rendered in the block styles preview, not in the editor.
	const isEditorBlock = editorBlocksIds.indexOf( clientId ) >= 0;

	const { getPosts, getError, isUIDisabled } = select( STORE_NAMESPACE );
	const props = {
		isEditorBlock,
		isUIDisabled: isUIDisabled(),
		error: getError( { clientId } ),
		topBlocksClientIdsInOrder: getBlocks().map( block => block.clientId ),
		availablePostTypes: getPostTypes( { per_page: -1 } )?.filter(
			( { supports: { newspack_blocks: newspackBlocks } } ) => newspackBlocks
		),
	};

	if ( isEditorBlock ) {
		props.latestPosts = getPosts( { clientId } );
	} else {
		// For block preview, display static content.
		props.latestPosts = getPreviewPosts( attributes );
	}

	return props;
};

export const postsBlockDispatch = ( dispatch, { isEditorBlock } ) => {
	return {
		// Only editor blocks can trigger reflows.
		triggerReflow: isEditorBlock ? dispatch( STORE_NAMESPACE ).reflow : () => {},
	};
};
