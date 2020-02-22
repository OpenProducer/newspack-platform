/**
 * Text Control
 */

/**
 * WordPress dependencies
 */
import { TextControl as BaseComponent } from '@wordpress/components';
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classNames from 'classnames';

class TextControl extends Component {
	/**
	 * Render.
	 */
	render( props ) {
		const { className, ...otherProps } = this.props;
		const classes = classNames( 'newspack-text-control', className );
		return <BaseComponent className={ classes } { ...otherProps } />;
	}
}

export default TextControl;
