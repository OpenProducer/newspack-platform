/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

type BadgeProps = {
	text: string;
	level?: 'default' | 'info' | 'success' | 'warning' | 'error';
};

/**
 * Badge component
 */
const Badge = ( { text, level = 'default' }: BadgeProps ) => {
	const classes = classnames( 'newspack-badge', `is-${ level }` );
	return <span className={ classes }>{ text }</span>;
};

export default Badge;
