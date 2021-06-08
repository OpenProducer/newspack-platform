import '../../shared/js/public-path';

/**
 * Engagement
 */

/**
 * WordPress dependencies.
 */
import { Component, render, Fragment, createElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router';
import { Commenting, Newsletters, Social, RelatedContent, UGC } from './views';

const { HashRouter, Redirect, Route, Switch } = Router;

class EngagementWizard extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			relatedPostsEnabled: false,
			relatedPostsMaxAge: 0,
			relatedPostsUpdated: false,
			relatedPostsError: null,
		};
	}

	/**
	 * Figure out whether to use the WooCommerce or Jetpack Mailchimp wizards and get appropriate settings.
	 */
	onWizardReady = () => {
		const { setError, wizardApiFetch } = this.props;
		return wizardApiFetch( {
			path: '/newspack/v1/wizard/newspack-engagement-wizard/related-content',
		} )
			.then( data => this.setState( data ) )
			.catch( error => setError( error ) );
	};

	/**
	 * Update Related Content settings.
	 */
	updatedRelatedContentSettings = async () => {
		const { wizardApiFetch } = this.props;
		const { relatedPostsMaxAge } = this.state;

		try {
			await wizardApiFetch( {
				path: '/newspack/v1/wizard/newspack-engagement-wizard/related-posts-max-age',
				method: 'POST',
				data: { relatedPostsMaxAge },
			} );
			this.setState( { relatedPostsError: null, relatedPostsUpdated: false } );
		} catch ( e ) {
			this.setState( {
				relatedPostsError:
					e.message || __( 'There was an error saving settings. Please try again.', 'newspack' ),
			} );
		}
	};

	/**
	 * Render
	 */
	render() {
		const { pluginRequirements } = this.props;
		const {
			relatedPostsEnabled,
			relatedPostsError,
			relatedPostsMaxAge,
			relatedPostsUpdated,
		} = this.state;

		const tabbed_navigation = [
			{
				label: __( 'Newsletters' ),
				path: '/newsletters',
				exact: true,
			},
			{
				label: __( 'Social' ),
				path: '/social',
				exact: true,
			},
			{
				label: __( 'Commenting' ),
				path: '/commenting',
			},
			{
				label: __( 'Recirculation' ),
				path: '/recirculation',
			},
			{
				label: __( 'UGC' ),
				path: '/user-generated-content',
			},
		];
		const subheader = __(
			'Newsletters, social, commenting, recirculation, user-generated content'
		);
		const props = {
			headerText: __( 'Engagement', 'newspack' ),
			subHeaderText: subheader,
			tabbedNavigation: tabbed_navigation,
		};
		return (
			<Fragment>
				<HashRouter hashType="slash">
					<Switch>
						{ pluginRequirements }
						<Route path="/newsletters" render={ () => <Newsletters { ...props } /> } />
						<Route path="/social" exact render={ () => <Social { ...props } /> } />
						<Route path="/commenting" exact render={ () => <Commenting { ...props } /> } />
						<Route
							path="/recirculation"
							exact
							render={ () => (
								<RelatedContent
									{ ...props }
									relatedPostsEnabled={ relatedPostsEnabled }
									relatedPostsError={ relatedPostsError }
									buttonAction={ () => this.updatedRelatedContentSettings() }
									buttonText={ __( 'Save', 'newspack' ) }
									buttonDisabled={ ! relatedPostsEnabled || ! relatedPostsUpdated }
									relatedPostsMaxAge={ relatedPostsMaxAge }
									onChange={ value => {
										this.setState( { relatedPostsMaxAge: value, relatedPostsUpdated: true } );
									} }
								/>
							) }
						/>
						<Route path="/user-generated-content" exact render={ () => <UGC { ...props } /> } />
						<Redirect to="/newsletters" />
					</Switch>
				</HashRouter>
			</Fragment>
		);
	}
}

render(
	createElement( withWizard( EngagementWizard, [ 'jetpack' ] ) ),
	document.getElementById( 'newspack-engagement-wizard' )
);
