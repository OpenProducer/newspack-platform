/**
 * Textarea Control
 */

/**
 * WordPress dependencies
 */
import { TextareaControl as BaseComponent } from '@wordpress/components';
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

class TextareaControl extends Component {
	/**
	 * Render.
	 */
	render() {
		const { className, ...otherProps } = this.props;
		const classes = classnames( 'newspack-textarea-control', className );
		return <BaseComponent className={ classes } { ...otherProps } />;
	}
}

export default TextareaControl;
