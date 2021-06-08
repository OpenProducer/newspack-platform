/**
 * Wizard Pagination
 */

/**
 * WordPress dependencies.
 */
import { useEffect, useRef, useState, Fragment } from '@wordpress/element';
import { arrowRight, moreHorizontal, moreVertical } from '@wordpress/icons';

/**
 * Internal dependencies.
 */
import { Button } from '../';
import Router from '../proxied-imports/router';
import './style.scss';

const { withRouter } = Router;

const WizardPagination = props => {
	const [ showSteps, setShowSteps ] = useState( false );
	const stepper = useRef( null );
	const { location, routes } = props;
	const routeList = Object.keys( routes || {} );
	const currentRoute = routeList.find( route => location.pathname === routes[ route ].path );

	useEffect( () => {
		window.addEventListener( 'click', hideSteps );
		return () => window.removeEventListener( 'click', hideSteps );
	}, [] );

	useEffect( () => {
		setShowSteps( false );
	}, [ currentRoute ] );

	const hideSteps = e => {
		// If clicking outside the expanded stepper, hide it.
		if (
			stepper.current &&
			e.target !== stepper.current &&
			! e.target.classList.contains( 'newspack-wizard-pagination__show-steps' )
		) {
			setShowSteps( false );
		}
	};

	return (
		<div className="newspack-wizard-pagination newspack-wizard__header__inner">
			{ routeList.length && (
				<>
					<Button
						className="newspack-wizard-pagination__show-steps"
						onClick={ () => setShowSteps( ! showSteps ) }
						icon={ showSteps ? moreVertical : moreHorizontal }
					>
						{ routes[ currentRoute ].label }
					</Button>
					<ul
						className={ `newspack-wizard-pagination__steps ${ ! showSteps ? 'hidden' : '' }` }
						ref={ stepper }
					>
						{ routeList.map( ( route, index ) => {
							if ( '0' === route ) {
								return null;
							}

							const currentIndex = routeList.indexOf( currentRoute );
							const classes = [];

							if ( route === currentRoute ) {
								classes.push( 'active' );
							}

							if ( index < currentIndex ) {
								classes.push( 'complete' );
							}

							return (
								<Fragment key={ index }>
									<li className="newspack-wizard-pagination__step">
										<Button
											className={ classes.join( ' ' ) }
											href={ '#' + routes[ route ].path }
											isLink
											icon={ route === currentRoute ? arrowRight : null }
											disabled={ index > currentIndex }
										>
											{ routes[ route ].label }
										</Button>
									</li>
								</Fragment>
							);
						} ) }
					</ul>
				</>
			) }
		</div>
	);
};

export default withRouter( WizardPagination );
