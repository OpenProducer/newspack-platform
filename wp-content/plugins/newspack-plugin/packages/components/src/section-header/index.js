/**
 * Section Header
 */

/**
 * WordPress dependencies
 */
import { useEffect, useRef } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { Grid } from '..';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Represents a section header component.
 *
 * @typedef {Object} SectionHeaderProps
 * @property {boolean}           [centered=false] - Indicates if the header is centered.
 * @property {?string}           [className=null] - Additional CSS class name.
 * @property {string|Function|*} [description]    - Description of the section.
 * @property {number}            [heading=2]      - HTML heading level, e.g., 1 for h1, 2 for h2, etc.
 * @property {boolean}           [isWhite=false]  - Indicates if the header should use a white theme.
 * @property {boolean}           [noMargin=false] - Indicates if the header should have no margin.
 * @property {string}            title            - The title of the section.
 * @property {?string}           [id=null]        - Optional ID for the header element.
 */

/**
 * Creates a section header.
 *
 * @param {SectionHeaderProps} props - The properties for the section header.
 */
const SectionHeader = ( {
	centered = false,
	className = null,
	description = '',
	heading = 2,
	isWhite = false,
	noMargin = false,
	title,
	id = null,
} ) => {
	// If id is in the URL as a scrollTo param, scroll to it on render.
	const ref = useRef();
	useEffect( () => {
		const params = new Proxy( new URLSearchParams( window.location.search ), {
			get: ( searchParams, prop ) => searchParams.get( prop ),
		} );
		const scrollToId = params.scrollTo;
		if ( scrollToId && scrollToId === id ) {
			// Let parent scroll action run before running this.
			window.setTimeout( () => ref.current.scrollIntoView( { behavior: 'smooth' } ), 250 );
		}
	}, [] );

	const classes = classnames(
		'newspack-section-header',
		centered && 'newspack-section-header--is-centered',
		isWhite && 'newspack-section-header--is-white',
		noMargin && 'newspack-section-header--no-margin',
		className
	);

	const HeadingTag = `h${ heading }`;

	return (
		<div id={ id } className="newspack-section-header__container" ref={ ref }>
			<Grid columns={ 1 } gutter={ 8 } className={ classes }>
				{ typeof title === 'string' && <HeadingTag>{ title }</HeadingTag> }
				{ typeof title === 'function' && <HeadingTag>{ title() }</HeadingTag> }
				{ description && typeof description === 'string' && <p>{ description }</p> }
				{ typeof description === 'function' && <p>{ description() }</p> }
				{ description && typeof description !== 'string' && typeof description !== 'function' && <p>{ description }</p> }
			</Grid>
		</div>
	);
};

export default SectionHeader;
