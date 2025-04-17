/**
 * Newspack > Settings > Emails
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import WizardsTab from '../../../../wizards-tab';
import { default as EmailsSection } from './emails';
import WizardSection from '../../../../wizards-section';

function Emails() {
	return (
		<WizardsTab title={ __( 'Emails', 'newspack-plugin' ) }>
			<WizardSection>
				<EmailsSection />
			</WizardSection>
		</WizardsTab>
	);
}

export default Emails;
