/**
 * External dependencies
 */
import { isUndefined, find, pickBy, get } from 'lodash';

/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { RangeControl, Button, ToggleControl, PanelBody, Toolbar } from '@wordpress/components';
import {
	InnerBlocks,
	BlockPreview,
	InspectorControls,
	BlockControls,
} from '@wordpress/block-editor';
import { Fragment, useEffect, useMemo } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';
import './deduplication';
import Icon from './icon';
import { getTemplateBlocks, convertBlockSerializationFormat } from './utils';
import QueryControlsSettings from './query-controls';
import { POSTS_INSERTER_BLOCK_NAME, POSTS_INSERTER_STORE_NAME } from './consts';

const PostsInserterBlock = ( {
	setAttributes,
	attributes,
	postList,
	replaceBlocks,
	setHandledPostsIds,
	setInsertedPostsIds,
	removeBlock,
} ) => {
	const templateBlocks = useMemo( () => getTemplateBlocks( postList, attributes ), [
		postList,
		attributes,
	] );

	const innerBlocksToInsert = templateBlocks.map( convertBlockSerializationFormat );
	useEffect(() => {
		setAttributes( { innerBlocksToInsert } );
	}, [ JSON.stringify( innerBlocksToInsert ) ]);

	const handledPostIds = postList.map( post => post.id );

	useEffect(() => {
		if ( attributes.areBlocksInserted ) {
			replaceBlocks( templateBlocks );
			setInsertedPostsIds( handledPostIds );
		}
	}, [ attributes.areBlocksInserted ]);

	useEffect(() => {
		if ( ! attributes.preventDeduplication ) {
			setHandledPostsIds( handledPostIds );
			return removeBlock;
		}
	}, [ handledPostIds.join() ]);

	const blockControlsImages = [
		{
			icon: 'align-none',
			title: __( 'Show image on top', 'newspack-blocks' ),
			isActive: attributes.featuredImageAlignment === 'top',
			onClick: () => setAttributes( { featuredImageAlignment: 'top' } ),
		},
		{
			icon: 'align-pull-left',
			title: __( 'Show image on left', 'newspack-blocks' ),
			isActive: attributes.featuredImageAlignment === 'left',
			onClick: () => setAttributes( { featuredImageAlignment: 'left' } ),
		},
		{
			icon: 'align-pull-right',
			title: __( 'Show image on right', 'newspack-blocks' ),
			isActive: attributes.featuredImageAlignment === 'right',
			onClick: () => setAttributes( { featuredImageAlignment: 'right' } ),
		},
	];

	return attributes.areBlocksInserted ? null : (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Post content settings', 'newspack-newsletters' ) }>
					<ToggleControl
						label={ __( 'Display post excerpt', 'newspack-newsletters' ) }
						checked={ attributes.displayPostExcerpt }
						onChange={ value => setAttributes( { displayPostExcerpt: value } ) }
					/>
					{ attributes.displayPostExcerpt && (
						<RangeControl
							label={ __( 'Max number of words in excerpt', 'newspack-newsletters' ) }
							value={ attributes.excerptLength }
							onChange={ value => setAttributes( { excerptLength: value } ) }
							min={ 10 }
							max={ 100 }
						/>
					) }
					<ToggleControl
						label={ __( 'Display date', 'newspack-newsletters' ) }
						checked={ attributes.displayPostDate }
						onChange={ value => setAttributes( { displayPostDate: value } ) }
					/>
					<ToggleControl
						label={ __( 'Display featured image', 'newspack-newsletters' ) }
						checked={ attributes.displayFeaturedImage }
						onChange={ value => setAttributes( { displayFeaturedImage: value } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Sorting and filtering', 'newspack-newsletters' ) }>
					<QueryControlsSettings attributes={ attributes } setAttributes={ setAttributes } />
				</PanelBody>
			</InspectorControls>

			<BlockControls>
				{ attributes.displayFeaturedImage && <Toolbar controls={ blockControlsImages } /> }
			</BlockControls>

			<div className="newspack-posts-inserter">
				<div className="newspack-posts-inserter__header">
					{ Icon }
					<span>{ __( 'Posts Inserter', 'newspack-newsletters' ) }</span>
				</div>
				<div className="newspack-posts-inserter__preview">
					<BlockPreview blocks={ templateBlocks } viewportWidth={ 558 } />
				</div>
				<div className="newspack-posts-inserter__footer">
					<Button isPrimary onClick={ () => setAttributes( { areBlocksInserted: true } ) }>
						{ __( 'Insert posts', 'newspack-newsletters' ) }
					</Button>
				</div>
			</div>
		</Fragment>
	);
};

const PostsInserterBlockWithSelect = compose( [
	withSelect( ( select, props ) => {
		const {
			postsToShow,
			order,
			orderBy,
			categories,
			isDisplayingSpecificPosts,
			specificPosts,
			preventDeduplication,
		} = props.attributes;
		const { getEntityRecords, getMedia } = select( 'core' );
		const { getSelectedBlock, getBlocks } = select( 'core/block-editor' );
		const catIds = categories && categories.length > 0 ? categories.map( cat => cat.id ) : [];

		const { getHandledPostIds } = select( POSTS_INSERTER_STORE_NAME );
		const exclude = getHandledPostIds( props.clientId );

		let posts = [];
		const isHandlingSpecificPosts = isDisplayingSpecificPosts && specificPosts.length > 0;

		if ( ! isDisplayingSpecificPosts || isHandlingSpecificPosts ) {
			const postListQuery = isDisplayingSpecificPosts
				? { include: specificPosts.map( post => post.id ) }
				: pickBy(
						{
							categories: catIds,
							order,
							orderby: orderBy,
							per_page: postsToShow,
							exclude: preventDeduplication ? [] : exclude,
						},
						value => ! isUndefined( value )
				  );

			posts = getEntityRecords( 'postType', 'post', postListQuery ) || [];
		}

		// Order posts in the order as they appear in the input
		if ( isHandlingSpecificPosts ) {
			posts = specificPosts.reduce( ( all, { id } ) => {
				const found = find( posts, [ 'id', id ] );
				return found ? [ ...all, found ] : all;
			}, [] );
		}

		return {
			existingBlocks: getBlocks(),
			selectedBlock: getSelectedBlock(),
			postList: posts.map( post => {
				if ( post.featured_media ) {
					const image = getMedia( post.featured_media );
					const fallbackImageURL = get( image, 'source_url', null );
					const featuredImageMediumURL =
						get( image, [ 'media_details', 'sizes', 'medium', 'source_url' ], null ) ||
						fallbackImageURL;
					const featuredImageLargeURL =
						get( image, [ 'media_details', 'sizes', 'large', 'source_url' ], null ) ||
						fallbackImageURL;
					return { ...post, featuredImageMediumURL, featuredImageLargeURL };
				}
				return post;
			} ),
		};
	} ),
	withDispatch( ( dispatch, props ) => {
		const { replaceBlocks } = dispatch( 'core/block-editor' );
		const { setHandledPostsIds, setInsertedPostsIds, removeBlock } = dispatch(
			POSTS_INSERTER_STORE_NAME
		);
		return {
			replaceBlocks: blocks => {
				replaceBlocks( props.selectedBlock.clientId, blocks );
			},
			setHandledPostsIds: ids => setHandledPostsIds( ids, props ),
			setInsertedPostsIds,
			removeBlock: () => removeBlock( props.clientId ),
		};
	} ),
] )( PostsInserterBlock );

export default () => {
	registerBlockType( POSTS_INSERTER_BLOCK_NAME, {
		title: 'Posts Inserter',
		category: 'widgets',
		icon: Icon,
		edit: PostsInserterBlockWithSelect,
		attributes: {
			areBlocksInserted: {
				type: 'boolean',
				default: false,
			},
			postsToShow: {
				type: 'number',
				default: 3,
			},
			displayPostExcerpt: {
				type: 'boolean',
				default: true,
			},
			excerptLength: {
				type: 'number',
				default: 15,
			},
			displayPostDate: {
				type: 'boolean',
				default: false,
			},
			displayFeaturedImage: {
				type: 'boolean',
				default: true,
			},
			innerBlocksToInsert: {
				type: 'array',
				default: '',
			},
			featuredImageAlignment: {
				type: 'string',
				default: 'left',
			},
			isDisplayingSpecificPosts: {
				type: 'boolean',
				default: false,
			},
			specificPosts: {
				type: 'array',
				default: [],
			},
		},
		save: () => <InnerBlocks.Content />,
	} );
};
