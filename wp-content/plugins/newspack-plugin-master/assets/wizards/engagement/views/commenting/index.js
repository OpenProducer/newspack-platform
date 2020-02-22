/**
 * Commenting screen.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { Card, Notice, withWizardScreen } from '../../../../components/src';

/**
 * Commenting Screen
 */
class Commenting extends Component {
	/**
	 * Render.
	 */
	render() {
		const { connected, connectURL } = this.props;

		return (
			<div className="newspack-engagement-wizard__mailchimp-connect-screen">
				<Card className="newspack-newsletter-block-wizard__jetpack-info">
					{ ! connected && (
						<p>
							{ __(
								'This feature connects your site to Mailchimp and sets up a Mailchimp block you can use to get new subscribers for your newsletter. The Mailchimp connection to your site for this feature is managed through Jetpack and WordPress.com.'
							) }
						</p>
					) }
					{ !! connected && (
						<Notice
							noticeText={ __(
								'You can insert newsletter sign up forms in your content using the Mailchimp block.'
							) }
							isSuccess
						/>
					) }
					<p>
						{ __(
							"Click the link below to set up and manage your site's Mailchimp connection on WordPress.com."
						) }
					</p>
				</Card>
				<ExternalLink href={ connectURL }>
					{ ! connected
						? __( 'Set up Mailchimp on WordPress.com' )
						: __( 'Manage your Mailchimp connection' ) }
				</ExternalLink>
			</div>
		);
	}
}

export default withWizardScreen( Commenting );
