/**
 * Select Control
 */

/**
 * WordPress dependencies
 */
import { SelectControl as BaseComponent } from '@wordpress/components';
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classNames from 'classnames';

class SelectControl extends Component {
	/**
	 * Render.
	 */
	render() {
		const { className, ...otherProps } = this.props;
		const classes = classNames( 'newspack-select-control', className );
		return <BaseComponent className={ classes } { ...otherProps } />;
	}
}

export default SelectControl;
