/**
 * Button
 */

/**
 * WordPress dependencies.
 */
import { Button as BaseComponent } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import Router from '../proxied-imports/router';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

const { useHistory } = Router;

const Button = ( { className, isQuaternary, href, onClick, ...otherProps } ) => {
	const history = useHistory();
	const [ isAwaitingOnClick, setIsAwaitingOnClick ] = useState( false );

	const classes = classnames( 'newspack-button', isQuaternary && 'is-quaternary', className );

	// If both onClick and href are present, await the onClick action an then redirect.
	if ( href && onClick ) {
		otherProps.onClick = async () => {
			setIsAwaitingOnClick( true );
			await onClick();
			setIsAwaitingOnClick( false );
			history.push( href.replace( '#', '' ) );
		};
	} else {
		otherProps.href = href;
		otherProps.onClick = onClick;
	}
	if ( isAwaitingOnClick ) {
		otherProps.disabled = true;
	}
	return <BaseComponent className={ classes } { ...otherProps } />;
};

export default Button;
