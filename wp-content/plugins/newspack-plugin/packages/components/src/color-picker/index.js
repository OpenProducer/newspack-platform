/**
 * WordPress dependencies.
 */
import { ColorPicker as ColorPickerComponent } from '@wordpress/components';
import { useState, useRef } from '@wordpress/element';

/**
 * External dependencies.
 */
import classnames from 'classnames';
import { colord, extend } from 'colord';
import a11yPlugin from 'colord/plugins/a11y';

/**
 * Internal dependencies.
 */
import hooks from '../hooks';
import utils from '../utils';
import './style.scss';

extend( [ a11yPlugin ] );
const { InteractiveDiv } = utils;

/**
 * ColorPicker component.
 *
 * @param {Object}             props             - Component props.
 * @param {JSX.Element|string} props.label       - Label for the color picker.
 * @param {string}             [props.color]     - Default color.
 * @param {Function}           props.onChange    - Function to call when the color changes.
 * @param {string}             [props.className] - Additional class name.
 * @return {JSX.Element} ColorPicker component.
 */
const ColorPicker = ( { label, color = '#fff', onChange, className } ) => {
	const [ isExpanded, setIsExpanded ] = useState( false );
	const ref = useRef();
	const colordColor = colord( color );
	hooks.useOnClickOutside( ref, () => setIsExpanded( false ) );
	return (
		<div className={ classnames( 'newspack-color-picker', className ) }>
			<div className="newspack-color-picker__label">{ label }</div>

			<InteractiveDiv
				className={ 'newspack-color-picker__expander' }
				onClick={ () => setIsExpanded( ! isExpanded ) }
				style={ {
					backgroundColor: color,
					color: colordColor.contrast() > colordColor.contrast( '#000' ) ? '#fff' : '#000',
				} }
			>
				{ color }
			</InteractiveDiv>

			<div className="newspack-color-picker__main" ref={ ref }>
				{ isExpanded && <ColorPickerComponent color={ color } onChange={ hex => onChange( hex ) } enableAlpha={ false } /> }
			</div>
		</div>
	);
};

export default ColorPicker;
