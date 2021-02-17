/**
 * External dependencies
 */
import { debounce, isEqual, isUndefined, pick, pickBy } from 'lodash';

/**
 * External dependencies
 */
import { select as globalSelect } from '@wordpress/data';

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
 * @param {Object} props Edit component props
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
		specificPosts,
		specificMode,
		tagExclusions,
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
					post_type: postType,
			  },
		value => ! isUndefined( value )
	);
	criteria.suppress_password_protected_posts = true;
	criteria.excerpt_length = excerptLength;
	criteria.show_excerpt = showExcerpt;
	return criteria;
};

export const sanitizePostList = postList =>
	postList.map( id => parseInt( id ) ).filter( id => id > 0 );

export const getBlockQueries = ( blocks, blockName ) =>
	blocks.flatMap( block => {
		const homepageArticleBlocks = [];
		if ( block.name === blockName ) {
			const postsQuery = queryCriteriaFromAttributes( block.attributes );
			homepageArticleBlocks.push( { postsQuery, clientId: block.clientId } );
		}
		return homepageArticleBlocks.concat( getBlockQueries( block.innerBlocks, blockName ) );
	} );

export const getEditorBlocksIds = blocks =>
	blocks.flatMap( block => {
		const homepageArticleBlocks = [];
		homepageArticleBlocks.push( block.clientId );
		return homepageArticleBlocks.concat( getEditorBlocksIds( block.innerBlocks ) );
	} );

export const getCoreStorePosts = debounce(
	attributes =>
		globalSelect( 'core' ).getEntityRecords(
			'postType',
			'post',
			queryCriteriaFromAttributes( attributes )
		),
	500
);
