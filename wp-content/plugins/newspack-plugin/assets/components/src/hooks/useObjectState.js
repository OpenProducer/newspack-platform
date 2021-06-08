/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';

import { mergeWith, isArray } from 'lodash';

const mergeCustomizer = ( objValue, srcValue ) => {
	if ( isArray( objValue ) ) {
		// If it's an array, replace it (instead of concatenating).
		return srcValue;
	}
};

/**
 * A useState for an object.
 * Nested objects will be nested, but arrays replaced.
 */
export default ( initial = {} ) => {
	const [ stateObject, setStateObject ] = useState( initial );

	const runUpdate = update =>
		setStateObject( _stateObject => mergeWith( {}, _stateObject, update, mergeCustomizer ) );

	const updateStateObject = keyOrUpdate => {
		if ( typeof keyOrUpdate === 'string' ) {
			return value => runUpdate( { [ keyOrUpdate ]: value } );
		}
		runUpdate( keyOrUpdate );
	};
	return [ stateObject, updateStateObject ];
};
