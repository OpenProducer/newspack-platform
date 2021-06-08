/**
 * Section Header
 */

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

const SectionHeader = ( { className, description, noMargin, title } ) => {
	const classes = classnames(
		'newspack-section-header',
		noMargin && 'newspack-section-header--no-margin',
		className
	);
	return (
		<div className={ classes }>
			<h2>{ title }</h2>
			{ typeof description === 'string' && <span>{ description }</span> }
			{ typeof description === 'function' && <span>{ description() }</span> }
		</div>
	);
};

export default SectionHeader;
