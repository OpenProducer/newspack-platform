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
		const { className, isDark, checked, ...otherProps } = this.props;
		const classes = classNames(
			'newspack-toggle-control',
			isDark && 'newspack-toggle-control--is-dark',
			// NOTE: disabled prop is handled in more recent Gutenberg versions. This special handling can be removed once that's in Core.
			otherProps.disabled && 'newspack-toggle-control--is-disabled',
			className
		);
		return <BaseComponent className={ classes } checked={ Boolean( checked ) } { ...otherProps } />;
	}
}

export default ToggleControl;
