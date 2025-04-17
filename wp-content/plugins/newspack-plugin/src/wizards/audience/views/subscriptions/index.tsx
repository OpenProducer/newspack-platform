/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { forwardRef } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import { Button, Card, Wizard, withWizard } from '../../../../components/src';
import WizardsTab from '../../../wizards-tab';
import WizardSection from '../../../wizards-section';

const subscriptionTabs = window.newspackAudienceSubscriptions.tabs;

function AudienceSubscriptions( props: Record<string, any>, ref: React.ForwardedRef<HTMLDivElement> ) {
	const tabs = subscriptionTabs.map( tab => {
		const render = () => (
			<WizardsTab title={ tab.title }>
				<WizardSection>
					<Card isNarrow>
						<h2>{ tab.header }</h2>
						<p>{ tab.description }</p>
						<Button variant="primary" href={ tab.href }>
							{ tab.btn_text }
						</Button>
					</Card>
				</WizardSection>
			</WizardsTab>
		);
		return {
			label: tab.title,
			path: tab.path,
			render,
		};
	} );

	return (
		<Wizard
			headerText={ __(
				'Audience Management / Subscriptions',
				'newspack-plugin'
			) }
			sections={ tabs }
			requiredPlugins={ [ 'woocommerce', 'woocommerce-memberships' ] }
			ref={ ref }
		/>
	);
}

export default withWizard( forwardRef( AudienceSubscriptions ) );
