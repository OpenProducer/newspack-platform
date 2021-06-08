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
import {
	RangeControl,
	Button,
	ToggleControl,
	FontSizePicker,
	ColorPicker,
	PanelBody,
	Spinner,
	Toolbar,
} from '@wordpress/components';
import {
	InnerBlocks,
	BlockPreview,
	InspectorControls,
	BlockControls,
} from '@wordpress/block-editor';
import { Fragment, useEffect, useMemo, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';
import './deduplication';
import blockDefinition from './block.json';
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
	blockEditorSettings,
} ) => {
	const [ isReady, setIsReady ] = useState( ! attributes.displayFeaturedImage );
	const stringifiedPostList = JSON.stringify( postList );

	// Stringify added to minimize flicker.
	const templateBlocks = useMemo( () => getTemplateBlocks( postList, attributes ), [
		stringifiedPostList,
		attributes,
	] );

	const stringifiedTemplateBlocks = JSON.stringify( templateBlocks );

	useEffect(() => {
		const { isDisplayingSpecificPosts, specificPosts } = attributes;

		// No spinner if we're not dealing with images.
		if ( ! attributes.displayFeaturedImage ) {
			return setIsReady( true );
		}

		// No spinner if we're in the middle of selecting a specific post.
		if ( isDisplayingSpecificPosts && 0 === specificPosts.length ) {
			return setIsReady( true );
		}

		// Reset ready state.
		setIsReady( false );

		// If we have a post to show, check for featured image blocks.
		if ( 0 < postList.length ) {
			// Find all the featured images.
			const images = [];
			postList.map( post => post.featured_media && images.push( post.featured_media ) );

			// If no posts have featured media, skip loading state.
			if ( 0 === images.length ) {
				return setIsReady( true );
			}

			// Wait for image blocks to be added to the BlockPreview.
			const imageBlocks = stringifiedTemplateBlocks.match( /\"name\":\"core\/image\"/g ) || [];

			// Preview is ready once all image blocks are accounted for.
			if ( imageBlocks.length === images.length ) {
				setIsReady( true );
			}
		}
	}, [ stringifiedPostList, stringifiedTemplateBlocks ]);

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
			title: __( 'Show image on top', 'newspack-newsletters' ),
			isActive: attributes.featuredImageAlignment === 'top',
			onClick: () => setAttributes( { featuredImageAlignment: 'top' } ),
		},
		{
			icon: 'align-pull-left',
			title: __( 'Show image on left', 'newspack-newsletters' ),
			isActive: attributes.featuredImageAlignment === 'left',
			onClick: () => setAttributes( { featuredImageAlignment: 'left' } ),
		},
		{
			icon: 'align-pull-right',
			title: __( 'Show image on right', 'newspack-newsletters' ),
			isActive: attributes.featuredImageAlignment === 'right',
			onClick: () => setAttributes( { featuredImageAlignment: 'right' } ),
		},
	];

	return attributes.areBlocksInserted ? null : (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Post content settings', 'newspack-newsletters' ) }>
					<ToggleControl
						label={ __( 'Post excerpt', 'newspack-newsletters' ) }
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
						label={ __( 'Date', 'newspack-newsletters' ) }
						checked={ attributes.displayPostDate }
						onChange={ value => setAttributes( { displayPostDate: value } ) }
					/>
					<ToggleControl
						label={ __( 'Featured image', 'newspack-newsletters' ) }
						checked={ attributes.displayFeaturedImage }
						onChange={ value => setAttributes( { displayFeaturedImage: value } ) }
					/>
					<ToggleControl
						label={ __( "Author's name", 'newspack-newsletters' ) }
						checked={ attributes.displayAuthor }
						onChange={ value => setAttributes( { displayAuthor: value } ) }
					/>
					<ToggleControl
						label={ __( '"Continue reading…" link', 'newspack-newsletters' ) }
						checked={ attributes.displayContinueReading }
						onChange={ value => setAttributes( { displayContinueReading: value } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Sorting and filtering', 'newspack-newsletters' ) }>
					<QueryControlsSettings attributes={ attributes } setAttributes={ setAttributes } />
				</PanelBody>
				<PanelBody title={ __( 'Text style', 'newspack-newsletters' ) }>
					<FontSizePicker
						fontSizes={ blockEditorSettings.fontSizes }
						value={ attributes.textFontSize }
						fallbackFontSize={ 16 }
						onChange={ value => setAttributes( { textFontSize: isNaN( value ) ? null : value } ) }
					/>
					<ColorPicker
						color={ attributes.textColor }
						onChangeComplete={ value => setAttributes( { textColor: value.hex } ) }
						disableAlpha
					/>
				</PanelBody>
				<PanelBody title={ __( 'Heading style', 'newspack-newsletters' ) }>
					<FontSizePicker
						fontSizes={ blockEditorSettings.fontSizes }
						value={ attributes.headingFontSize }
						fallbackFontSize={ 25 }
						onChange={ value =>
							setAttributes( { headingFontSize: isNaN( value ) ? null : value } )
						}
					/>
					<ColorPicker
						color={ attributes.headingColor }
						onChangeComplete={ value => setAttributes( { headingColor: value.hex } ) }
						disableAlpha
					/>
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
					{ isReady ? (
						<BlockPreview blocks={ templateBlocks } viewportWidth={ 558 } />
					) : (
						<Spinner />
					) }
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
			tags,
			tagExclusions,
			categoryExclusions,
			excerptLength,
		} = props.attributes;
		const { getEntityRecords, getMedia } = select( 'core' );
		const { getSelectedBlock, getBlocks, getSettings } = select( 'core/block-editor' );
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
							tags,
							order,
							orderby: orderBy,
							per_page: postsToShow,
							exclude: preventDeduplication ? [] : exclude,
							categories_exclude: categoryExclusions,
							tags_exclude: tagExclusions,
							excerpt_length: excerptLength,
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
			// Not used by the component, but needed in deduplication.
			existingBlocks: getBlocks(),
			blockEditorSettings: getSettings(),
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
		...blockDefinition,
		title: 'Posts Inserter',
		icon: Icon,
		edit: PostsInserterBlockWithSelect,
		save: () => <InnerBlocks.Content />,
	} );
};
