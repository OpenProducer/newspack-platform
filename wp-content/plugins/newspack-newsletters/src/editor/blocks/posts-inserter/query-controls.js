/**
 * External dependencies
 */
import { includes, debounce } from 'lodash';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { QueryControls, FormTokenField, ToggleControl, Spinner } from '@wordpress/components';
import { addQueryArgs } from '@wordpress/url';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { decodeEntities } from '@wordpress/html-entities';

const fetchPostSuggestions = search =>
	apiFetch( {
		path: addQueryArgs( '/wp/v2/search', {
			search,
			per_page: 20,
			_fields: 'id,title',
			subtype: 'post',
		} ),
	} ).then( posts =>
		posts.map( post => ( {
			id: post.id,
			title: decodeEntities( post.title ) || __( '(no title)', 'newspack-newsletters' ),
		} ) )
	);

const SEPARATOR = '--';
const encodePosts = posts => posts.map( post => [ post.id, post.title ].join( SEPARATOR ) );
const decodePost = encodedPost => {
	const match = encodedPost.match( new RegExp( `^([\\d]*)${ SEPARATOR }(.*)` ) );
	if ( match ) {
		return [ match[ 1 ], match[ 2 ] ];
	}
	return encodedPost;
};

// NOTE: Mostly copied from Gutenberg's Posts Inserter block.
// https://github.com/WordPress/gutenberg/blob/master/packages/block-library/src/posts-inserter/edit.js
const QueryControlsSettings = ( { attributes, setAttributes } ) => {
	const [ categoriesList, setCategoriesList ] = useState( [] );

	useEffect(() => {
		apiFetch( {
			path: addQueryArgs( `/wp/v2/categories`, {
				per_page: -1,
			} ),
		} ).then( setCategoriesList );
	}, []);

	const categorySuggestions = categoriesList.reduce(
		( accumulator, category ) => ( {
			...accumulator,
			[ category.name ]: category,
		} ),
		{}
	);

	const selectCategories = tokens => {
		const hasNoSuggestion = tokens.some(
			token => typeof token === 'string' && ! categorySuggestions[ token ]
		);
		if ( hasNoSuggestion ) {
			return;
		}
		// Categories that are already will be objects, while new additions will be strings (the name).
		// allCategories nomalizes the array so that they are all objects.
		const allCategories = tokens.map( token => {
			return typeof token === 'string' ? categorySuggestions[ token ] : token;
		} );
		// We do nothing if the category is not selected
		// from suggestions.
		if ( includes( allCategories, null ) ) {
			return false;
		}
		setAttributes( { categories: allCategories } );
	};

	const [ isFetchingPosts, setIsFetchingPosts ] = useState( false );
	const [ foundPosts, setFoundPosts ] = useState( [] );
	const handleSpecificPostsInput = search => {
		if ( isFetchingPosts || search.length === 0 ) {
			return;
		}
		setIsFetchingPosts( true );
		fetchPostSuggestions( search ).then( posts => {
			setIsFetchingPosts( false );
			setFoundPosts( posts );
		} );
	};

	const handleSpecificPostsSelection = postTitles => {
		setAttributes( {
			specificPosts: postTitles.map( encodedTitle => {
				const [ id, title ] = decodePost( encodedTitle );
				return { id: parseInt( id ), title };
			} ),
		} );
	};

	return (
		<div className="newspack-newsletters-query-controls">
			<ToggleControl
				label={ __( 'Display specific posts', 'newspack-newsletters' ) }
				checked={ attributes.isDisplayingSpecificPosts }
				onChange={ value => setAttributes( { isDisplayingSpecificPosts: value } ) }
			/>
			{ attributes.isDisplayingSpecificPosts ? (
				<FormTokenField
					label={
						<div>
							{ __( 'Add posts', 'newspack-newsletters' ) }
							{ isFetchingPosts && <Spinner /> }
						</div>
					}
					onChange={ handleSpecificPostsSelection }
					value={ encodePosts( attributes.specificPosts ) }
					suggestions={ encodePosts( foundPosts ) }
					displayTransform={ string => {
						const [ id, title ] = decodePost( string );
						return title || id;
					} }
					onInputChange={ debounce( handleSpecificPostsInput, 400 ) }
				/>
			) : (
				<QueryControls
					{ ...{ order: attributes.order, orderBy: attributes.orderBy } }
					numberOfItems={ attributes.postsToShow }
					onOrderChange={ value => setAttributes( { order: value } ) }
					onOrderByChange={ value => setAttributes( { orderBy: value } ) }
					onNumberOfItemsChange={ value => setAttributes( { postsToShow: value } ) }
					categorySuggestions={ categorySuggestions }
					onCategoryChange={ selectCategories }
					selectedCategories={ attributes.categories }
					// Support for legacy Gutenberg version.
					categoriesList={ [] }
					minItems={ 1 }
					maxItems={ 10 }
				/>
			) }
		</div>
	);
};

export default QueryControlsSettings;
