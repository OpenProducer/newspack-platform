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
import classnames from 'classnames';

class Grid extends Component {
	/**
	 * Render
	 */
	render() {
		const { className, columns, gutter, noMargin, rowGap, ...otherProps } = this.props;
		const classes = classnames(
			'newspack-grid',
			noMargin && 'newspack-grid--no-margin',
			columns && 'newspack-grid__columns-' + columns,
			gutter && 'newspack-grid__gutter-' + gutter,
			rowGap && 'newspack-grid__row-gap-' + rowGap,
			className
		);
		return <div className={ classes } { ...otherProps } />;
	}
}

Grid.defaultProps = {
	columns: 2,
	gutter: 64,
	rowGap: null,
};

export default Grid;
