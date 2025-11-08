/* eslint-disable jsx-a11y/click-events-have-key-events */
/* eslint-disable jsx-a11y/no-static-element-interactions */

/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { Icon, chevronRight } from '@wordpress/icons';

/**
 * External dependencies
 */
import classNames from 'classnames';

/**
 * Internal dependencies
 */
import './style.scss';

const Accordion = ( { children, title } ) => {
	const [ isOpen, setIsOpen ] = useState( false );
	return (
		<details className={ classNames( 'newspack-accordion', { 'newspack-accordion--is-open': isOpen } ) }>
			<summary onClick={ () => setIsOpen( ! isOpen ) }>
				{ title }
				<Icon className="newspack-accordion__icon" icon={ chevronRight } size={ 24 } />
			</summary>
			<div className="newspack-accordion__content">{ children }</div>
		</details>
	);
};

export default Accordion;
