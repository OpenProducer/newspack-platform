/**
 * Internal dependencies
 */
import { isFunction, isString, isObject } from 'lodash';

export default function buttonProps( action ) {
	const props = {};
	if ( isFunction( action ) ) {
		props.onClick = action;
	}
	if ( isString( action ) ) {
		props.href = action;
	}
	if ( isObject( action ) ) {
		if ( action.handoff ) {
			props.plugin = action.handoff;
		}
		if ( action.onClick ) {
			props.onClick = action.onClick;
		}
		if ( action.href ) {
			props.href = action.href;
		}
	}
	return props;
}
