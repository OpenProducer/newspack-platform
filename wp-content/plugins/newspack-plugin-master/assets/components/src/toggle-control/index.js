/**
 * Toggle Control
 */

/**
 * WordPress dependencies
 */
import { ToggleControl as BaseComponent } from '@wordpress/components';
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classNames from 'classnames';

class ToggleControl extends Component {
	/**
	 * Render.
	 */
	render() {
		const { className, isDark, ...otherProps } = this.props;
		const classes = classNames(
			'newspack-toggle-control',
			isDark && 'newspack-toggle-control__is-dark',
			className
		);
		return <BaseComponent className={ classes } { ...otherProps } />;
	}
}

export default ToggleControl;
