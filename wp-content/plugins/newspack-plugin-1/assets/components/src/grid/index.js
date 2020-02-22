/**
 * Grid
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classNames from 'classnames';

class Grid extends Component {
	/**
	 * Render
	 */
	render() {
		const { className, isWide, ...otherProps } = this.props;
		const classes = classNames(
			'newspack-grid',
			isWide && 'newspack-grid__wide',
			className,
		);
		return <div className={ classes } { ...otherProps } />;
	}
}

export default Grid;
