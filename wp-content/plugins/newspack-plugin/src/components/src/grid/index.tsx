/**
 * Grid
 */

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

const Grid = ( {
	className = '',
	columns = 2,
	gutter = 32,
	noMargin = false,
	rowGap = 0,
	...otherProps
} ) => {
	const classes = classnames(
		'newspack-grid',
		noMargin && 'newspack-grid--no-margin',
		columns && 'newspack-grid__columns-' + columns,
		gutter && 'newspack-grid__gutter-' + gutter,
		rowGap && 'newspack-grid__row-gap-' + rowGap,
		className
	);
	return <div className={ classes } { ...otherProps } />;
};

export default Grid;
