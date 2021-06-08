/**
 * WordPress dependencies.
 */
import { ENTER } from '@wordpress/keycodes';

const InteractiveDiv = ( { style = {}, ...props } ) => (
	<div
		tabIndex="0"
		role="button"
		onKeyDown={ event => ENTER === event.keyCode && props.onClick() }
		style={ { cursor: 'pointer', ...style } }
		{ ...props }
	/>
);

export default {
	InteractiveDiv,
};
