/**
 * Services Screen
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, withWizardScreen } from '../../../../components/src';

/**
 * Services Screen Component
 */
class Services extends Component {
	/**
	 * Render.
	 */
	render() {
		return (
			<Fragment>
				<ActionCard
					title={ __( 'Donations', 'newspack' ) }
					description={ __(
						'Set up a donations page and accept one-time or recurring payments from your readers.',
						'newspack'
					) }
					actionText={ __( 'Configure', 'newspack' ) }
					href="/wp-admin/admin.php?page=newspack-reader-revenue-wizard#/donations"
				/>
				<ActionCard
					title={ __( 'Salesforce', 'newspack' ) }
					description={ __(
						'Integrate Salesforce to capture contact information when readers donate to your organization.',
						'newspack'
					) }
					actionText={ __( 'Configure', 'newspack' ) }
					href="/wp-admin/admin.php?page=newspack-reader-revenue-wizard#/salesforce"
				/>
			</Fragment>
		);
	}
}

export default withWizardScreen( Services );
