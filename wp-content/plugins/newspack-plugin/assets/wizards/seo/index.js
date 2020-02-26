/**
 * SEO
 */

/**
 * WordPress dependencies.
 */
import { Component, render, Fragment, createElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Material UI dependencies.
 */
import HeaderIcon from '@material-ui/icons/Search';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router';
import { Environment, Separator, Social, Tools } from './views';

/**
 * External dependencies.
 */
import deepMapKeys from 'deep-map-keys';
import { camelCase, snakeCase } from 'lodash';

const { HashRouter, Redirect, Route, Switch } = Router;

class SEOWizard extends Component {
	state = {
		titleSeparator: '',
		underConstruction: false,
		urls: {
			facebook: '',
			twitter: '',
			instagram: '',
			youtube: '',
			linkedin: '',
			pinterest: '',
		},
		verification: {
			bing: '',
			google: '',
		},
	};

	onWizardReady = () => this.fetch();

	/**
	 * Get settings for the wizard.
	 */
	fetch() {
		const { setError, wizardApiFetch } = this.props;
		return wizardApiFetch( {
			path: '/newspack/v1/wizard/newspack-seo-wizard/settings',
		} )
			.then( response => this.setState( this.sanitizeResponse( response ) ) )
			.catch( error => setError( error ) );
	}
	/**
	 * Update settings.
	 */
	update() {
		const { setError, wizardApiFetch } = this.props;
		return wizardApiFetch( {
			path: '/newspack/v1/wizard/newspack-seo-wizard/settings',
			method: 'POST',
			data: deepMapKeys( this.state, key => snakeCase( key ) ),
		} )
			.then( response => this.setState( this.sanitizeResponse( response ) ) )
			.catch( error => setError( error ) );
	}

	/**
	 * Sanitize API response.
	 */
	sanitizeResponse = response => {
		return deepMapKeys( response, key => camelCase( key ) );
	};

	/**
	 * Render
	 */
	render() {
		const { pluginRequirements } = this.props;
		const headerIcon = <HeaderIcon />;
		const headerText = __( 'SEO', 'newspack' );
		const subHeaderText = __( 'Search engine and social optimization', 'newspack' );
		const tabbedNavigation = [
			{
				label: __( 'Environment', 'newspack' ),
				path: '/',
				exact: true,
			},
			{
				label: __( 'Separator', 'newspack' ),
				path: '/separator',
				exact: true,
			},
			{
				label: __( 'Tools', 'newspack' ),
				path: '/tools',
				exact: true,
			},
			{
				label: __( 'Social', 'newspack' ),
				path: '/social',
			},
		];
		const buttonText = __( 'Save settings', 'newspack' );
		const secondaryButtonText = __( 'Advanced settings', 'newspack' );
		const screenParams = {
			data: this.state,
			headerIcon,
			headerText,
			subHeaderText,
			tabbedNavigation,
		};
		return (
			<Fragment>
				<HashRouter hashType="slash">
					<Switch>
						{ pluginRequirements }
						<Route
							exact
							path="/"
							render={ () => (
								<Environment
									{ ...screenParams }
									buttonAction={ () => this.update() }
									buttonText={ buttonText }
									onChange={ settings => this.setState( settings ) }
									secondaryButtonAction={ {
										handoff: 'wordpress-seo',
									} }
									secondaryButtonText={ secondaryButtonText }
								/>
							) }
						/>
						<Route
							exact
							path="/separator"
							render={ () => (
								<Separator
									{ ...screenParams }
									buttonAction={ () => this.update() }
									buttonText={ buttonText }
									onChange={ settings => this.setState( settings ) }
									secondaryButtonAction={ {
										editLink: 'admin.php?page=wpseo_titles',
										handoff: 'wordpress-seo',
									} }
									secondaryButtonText={ secondaryButtonText }
								/>
							) }
						/>
						<Route
							exact
							path="/social"
							render={ () => (
								<Social
									{ ...screenParams }
									buttonAction={ () => this.update() }
									buttonText={ buttonText }
									onChange={ settings => this.setState( settings ) }
									secondaryButtonAction={ {
										editLink: 'admin.php?page=wpseo_social',
										handoff: 'wordpress-seo',
									} }
									secondaryButtonText={ secondaryButtonText }
								/>
							) }
						/>
						<Route
							exact
							path="/tools"
							render={ () => (
								<Tools
									{ ...screenParams }
									buttonAction={ () => this.update() }
									buttonText={ buttonText }
									onChange={ settings => this.setState( settings ) }
									secondaryButtonAction={ {
										editLink: 'admin.php?page=wpseo_dashboard#top#webmaster-tools',
										handoff: 'wordpress-seo',
									} }
									secondaryButtonText={ secondaryButtonText }
								/>
							) }
						/>
						<Redirect to="/" />
					</Switch>
				</HashRouter>
			</Fragment>
		);
	}
}

render(
	createElement( withWizard( SEOWizard, [ 'wordpress-seo', 'jetpack' ] ) ),
	document.getElementById( 'newspack-seo-wizard' )
);
