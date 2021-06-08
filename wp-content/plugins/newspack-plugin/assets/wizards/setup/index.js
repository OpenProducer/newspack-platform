import '../../shared/js/public-path';

/**
 * WordPress dependencies.
 */
import { Fragment, render, createElement, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { Welcome, Settings, Services, Integrations, Design } from './views/';
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router';
import './style.scss';

const { HashRouter, Route } = Router;

const ROUTES = [
	{
		path: '/',
		label: __( 'Welcome', 'newspack' ),
		render: Welcome,
	},
	{
		path: '/settings',
		label: __( 'Settings', 'newspack' ),
		subHeaderText: __( 'Set up your site', 'newspack' ),
		render: Settings,
	},
	{
		path: '/integrations',
		label: __( 'Integrations', 'newspack' ),
		subHeaderText: __( 'Configure core plugins', 'newspack' ),
		render: Integrations,
		canProceed: false,
	},
	{
		path: '/services',
		label: __( 'Services', 'newspack' ),
		subHeaderText: __( 'Activate extra features' ),
		render: Services,
	},
	{
		path: '/design',
		label: __( 'Design', 'newspack' ),
		subHeaderText: __( 'Customize your site', 'newspack' ),
		render: Design,
	},
];

const SetupWizard = ( { wizardApiFetch, setError } ) => {
	const [ routes, setRoutes ] = useState( ROUTES );
	const finishSetup = () => {
		const params = {
			path: `/newspack/v1/wizard/newspack-setup-wizard/complete`,
			method: 'POST',
			quiet: true,
		};
		wizardApiFetch( params )
			.then( () => ( window.location = newspack_urls.dashboard ) )
			.catch( setError );
	};

	const sharedProps = {
		wizardApiFetch,
		setError,
		routes,
	};

	return (
		<Fragment>
			<HashRouter hashType="slash">
				{ routes.map( ( route, index ) => {
					const nextRoute = routes[ index + 1 ]?.path;
					const buttonAction = nextRoute
						? {
								href: '#' + nextRoute,
						  }
						: {};
					return (
						<Route
							key={ index }
							path={ route.path }
							exact={ route.path === '/' }
							render={ () =>
								route.render( {
									...sharedProps,
									headerText: route.label,
									subHeaderText: route.subHeaderText,
									buttonText: nextRoute ? route.buttonText || __( 'Continue' ) : __( 'Finish' ),
									buttonAction,
									buttonDisabled: route.canProceed === false,
									onSave: nextRoute ? null : finishSetup,
									updateRoute: update => {
										setRoutes( _routes =>
											_routes.map( ( r, i ) => ( i === index ? { ...r, ...update } : r ) )
										);
									},
								} )
							}
						/>
					);
				} ) }
			</HashRouter>
		</Fragment>
	);
};

render(
	createElement( withWizard( SetupWizard, [] ), { simpleFooter: true } ),
	document.getElementById( 'newspack-setup-wizard' )
);
