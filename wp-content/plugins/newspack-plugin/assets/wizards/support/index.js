/* global newspack_support_data */

import '../../shared/js/public-path';

/**
 * External dependencies
 */
import { parse } from 'qs';

/**
 * WordPress dependencies.
 */
import { render, createElement, Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import { CreateTicket, ListTickets, Chat, Loading } from './views';
import Router from '../../components/src/proxied-imports/router';
import { getReturnPath } from './utils';

const { HashRouter, Redirect, Route, Switch } = Router;

/**
 * Support view.
 *
 * This is also the location of redirect URI for WPCOM.
 * It means WPCOM will redirect here after authentication - this component
 * will read the auth variables provided by in location hash and pass
 * them to the backend, where they will be saved.
 */
class SupportWizard extends Component {
	hasHashDataWithToken = () => {
		const { hash } = window.location;
		const hashData = parse( hash.substring( 1 ) );
		return hashData.access_token ? hashData : false;
	};

	componentDidMount() {
		const hashData = this.hasHashDataWithToken();
		if ( hashData ) {
			this.props
				.wizardApiFetch( {
					path: '/newspack/v1/oauth/wpcom/token',
					method: 'POST',
					data: hashData,
				} )
				.then( () => {
					const returnPath = getReturnPath();
					if ( returnPath ) {
						// redirect so that Router can take over
						window.location = returnPath;
						window.location.reload();
					}
				} )
				.catch( ( { message: errorMessage } ) => {
					this.setState( { errorMessage } );
				} );
		}
	}

	render() {
		if ( this.hasHashDataWithToken() ) {
			return <Loading />;
		}

		const isPreLaunch = newspack_support_data.IS_PRE_LAUNCH;

		const props = {
			headerText: __( 'Support', 'newspack' ),
			subHeaderText: __( 'Contact customer support', 'newspack' ),
			tabbedNavigation: [
				{
					label: __( 'Submit ticket' ),
					path: '/ticket',
					exact: true,
				},
				{
					label: __( 'Tickets' ),
					path: '/tickets-list',
					exact: true,
				},
				...( isPreLaunch
					? []
					: [
							{
								label: __( 'Chat' ),
								path: '/chat',
								exact: true,
							},
					  ] ),
			],
			...this.props,
		};

		return (
			<HashRouter hashType="slash">
				<Switch>
					<Route path="/ticket" exact render={ () => <CreateTicket { ...props } /> } />
					<Route path="/tickets-list" exact render={ () => <ListTickets { ...props } /> } />
					{ ! isPreLaunch && <Route path="/chat" exact render={ () => <Chat { ...props } /> } /> }
					<Redirect to="/ticket" />
				</Switch>
			</HashRouter>
		);
	}
}

render(
	createElement( withWizard( SupportWizard ) ),
	document.getElementById( 'newspack-support-wizard' )
);
