/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { Fragment, Component } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { decodeEntities } from '@wordpress/html-entities';

/**
 * Internal dependencies.
 */
import AutocompleteTokenField from './autocomplete-tokenfield';

class QueryControls extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			suggestions: [],
		};
	}

	/**
	 * Fetch recent posts to show as suggestions.
	 */
	componentDidMount() {
		this.fetchListingSuggestions().then( suggestions => {
			if ( 0 < suggestions.length ) {
				this.setState( { suggestions } );
			}
		} );
	}

	/**
	 * Fetch posts to show as suggestions.
	 *
	 * @param {string} search An optional search string to use to query posts by title.
	 */
	fetchListingSuggestions = search => {
		const { listingType, listItems } = this.props;
		return apiFetch( {
			path: addQueryArgs( '/newspack-listings/v1/listings', {
				search,
				per_page: 10,
				_fields: 'id,title',
				type: listingType,
			} ),
		} ).then( function( posts ) {
			// Only show suggestions if they aren't already in the list.
			const result = posts.reduce( ( acc, post ) => {
				if ( listItems.indexOf( post.id ) < 0 && listItems.indexOf( post.id.toString() ) < 0 ) {
					acc.push( {
						value: post.id,
						label: decodeEntities( post.title ) || __( '(no title)', 'newspack-listings' ),
					} );
				}

				return acc;
			}, [] );
			return result;
		} );
	};

	/**
	 * If there are saved posts, fetch post info to be tokenized and displayed.
	 *
	 * @param {Array} postIDs Array of post IDs.
	 */
	fetchSavedPosts = postIDs => {
		return apiFetch( {
			path: addQueryArgs( 'newspack-listings/v1/listings', {
				per_page: 100,
				include: postIDs.join( ',' ),
				_fields: 'id,title',
			} ),
		} ).then( function( posts ) {
			return posts.map( post => ( {
				value: post.id,
				label: decodeEntities( post.title ) || __( '(no title)', 'newspack-listings' ),
			} ) );
		} );
	};

	/**
	 * Render a single suggestion object that can be clicked to select it immediately.
	 *
	 * @param {Object} suggestion Suggestion object with value and label keys.
	 * @param {number} index Index of this suggestion in the array.
	 */
	renderSuggestion( suggestion, index ) {
		const { onChange } = this.props;
		return (
			<Button isLink key={ index } onClick={ () => onChange( [ suggestion.value.toString() ] ) }>
				{ suggestion.label }
			</Button>
		);
	}

	/**
	 * Render a list of suggestions that can be clicked to select instead of searching by title.
	 */
	renderSuggestions() {
		const { listingTypeSlug } = this.props;
		const { suggestions } = this.state;

		return (
			<Fragment>
				<p className="newspack-listings__label">
					{ __( 'Or, select a recent ', 'newspack-listings' ) +
						listingTypeSlug +
						__( ' listing:', 'newspack-listings' ) }
				</p>
				<div className="newspack-listings__search-suggestions">
					{ suggestions.map( this.renderSuggestion.bind( this ) ) }
				</div>
			</Fragment>
		);
	}

	render = () => {
		const { label, selectedPost, onChange } = this.props;
		const { suggestions } = this.state;

		return (
			<Fragment>
				<AutocompleteTokenField
					key="listings"
					tokens={ [ selectedPost ] }
					onChange={ onChange }
					fetchSuggestions={ this.fetchListingSuggestions }
					fetchSavedInfo={ postIDs => this.fetchSavedPosts( postIDs ) }
					label={ label }
					help={ __(
						'Begin typing listing title, click autocomplete result to select.',
						'newspack-listings'
					) }
				/>
				{ 0 < suggestions.length && this.renderSuggestions() }
			</Fragment>
		);
	};
}

QueryControls.defaultProps = {
	selectedPost: 0,
};

export default QueryControls;
