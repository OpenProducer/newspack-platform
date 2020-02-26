/**
 * Category Autocomplete
 */

/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';
import { Component } from '@wordpress/element';
import { addQueryArgs } from '@wordpress/url';

/**
 * Internal dependencies.
 */
import { FormTokenField } from '../';

/**
 * External dependencies
 */
import { debounce } from 'lodash';

/**
 * Category autocomplete field component.
 */
class CategoryAutocomplete extends Component {
	state = {
		suggestions: {},
	};

	/**
	 * Class constructor.
	 */
	constructor( props ) {
		super( props );
		this.debouncedUpdateSuggestions = debounce( this.updateSuggestions, 100 );
	}

	/**
	 * Clean up debounced suggestions method.
	 */
	componentWillUnmount() {
		this.debouncedUpdateSuggestions.cancel();
	}

	/**
	 * Refresh the autocomplete UI based on text that was typed.
	 *
	 * @param {string} search The typed text to search for.
	 */
	updateSuggestions( search ) {
		apiFetch( {
			path: addQueryArgs( '/wp/v2/categories', {
				search,
				per_page: 20,
				_fields: 'id,name',
				orderby: 'count',
				order: 'desc',
			} ),
		} ).then( categories => {
			this.setState( {
				suggestions: categories.reduce(
					( accumulator, category ) => ( { ...accumulator, [ category.name ]: category } ),
					{}
				),
			} );
		} );
	}

	/**
	 * Prepare categories data for the API endpoint, call the change handler.
	 *
	 * @param {Array} tokens An array of category tokens.
	 */
	handleOnChange = tokens => {
		const { onChange } = this.props;
		const { suggestions } = this.state;
		// Categories that are already will be objects, while new additions will be strings (the name).
		// allValues nomalizes the array so that they are all objects.
		const allValues = tokens.map( token =>
			typeof token === 'string' ? suggestions[ token ] : token
		);
		onChange( allValues );
	};

	/**
	 * Render the component.
	 */
	render() {
		const { value, label } = this.props;
		const { suggestions } = this.state;
		return (
			<FormTokenField
				onInputChange={ input => this.debouncedUpdateSuggestions( input ) }
				value={ value.map( item => ( { id: item.term_id, value: item.name } ) ) }
				suggestions={ Object.keys( suggestions ) }
				onChange={ this.handleOnChange }
				label={ label }
			/>
		);
	}
}
export default CategoryAutocomplete;
