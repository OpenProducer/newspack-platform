/**
 * Revenue Main Screen
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Button, Handoff, Notice, withWizardScreen } from '../../../../components/src';

/**
 * Revenue Main Screen Component
 */
class ConfigureLandingPage extends Component {

	/**
	 * Render.
	 */
	render() {
		const { donationPage } = this.props;
		return (
			<Fragment>
				{ donationPage && (
					<Fragment>
						{ 'publish' !== donationPage.status && (
							<Notice isError noticeText={ __( 'Your donations landing page has been created, but is not yet published. You can now edit it and publish when you\'re ready.' ) } />
						) }
						{ 'publish' === donationPage.status && (
							<Notice isSuccess noticeText={ __( 'Your memberships landing page is set up and published.' ) } />
						) }
						<div className="newspack-buttons-card">
							<Handoff
								plugin="woocommerce"
								editLink={ donationPage.editUrl }
								isPrimary
								showOnBlockEditor
							>
								{ __( 'Edit Memberships Page' ) }
							</Handoff>
						</div>
					</Fragment>
				) }
			</Fragment>
		);
	}
}

export default withWizardScreen( ConfigureLandingPage );
