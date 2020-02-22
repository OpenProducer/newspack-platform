/**
 * Collects and connects with Mailchimp API key.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { Card, Notice, TextControl, PluginInstaller, withWizardScreen } from '../../../../components/src';

/**
 * Initial connection to Mailchimp.
 */
class Newsletters extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			pluginRequirementsMet: false,
		};
	}
	/**
	 * Render.
	 */
	render() {
		const { apiKey, connected, connectURL, wcConnected, onChange } = this.props;
		const { pluginRequirementsMet } = this.state;
		return [
			<Card key="mailchimp-block">
				{ ! connected && (
					<p>
						{ __(
							'This feature connects your site to Mailchimp and sets up a Mailchimp block you can use to get new subscribers for your newsletter. The Mailchimp connection to your site for this feature is managed through Jetpack and WordPress.com.'
						) }
					</p>
				) }
				{ !! connected && (
					<Notice noticeText={ __( 'You can insert newsletter sign up forms in your content using the Mailchimp block.' ) } isSuccess />
				) }
				<p className="wpcom-link">
					<ExternalLink href={ connectURL }>
						{ ! connected
							? __( 'Set up Mailchimp on WordPress.com' )
							: __( 'Manage your Mailchimp connection' ) }
					</ExternalLink>
				</p>
			</Card>,
			wcConnected && pluginRequirementsMet && (
				<Card key="wc-mailchimp-plugin">
					<p>
						{ __(
							'Integrate the Donation feature with Mailchimp by pasting in your API key below. To find your Mailchimp API key, log into your Mailchimp account and go to Account settings > Extras > API keys. From there, either grab an existing key or generate a new one.'
						) }
					</p>
					<TextControl
						label={ __( 'Enter your Mailchimp API key' ) }
						value={ apiKey }
						onChange={ value => onChange( value ) }
					/>
				</Card>
			),
			wcConnected && ! pluginRequirementsMet && (
				<PluginInstaller
					key="wc-mailchimp-plugin-installer"
					plugins={ [ 'mailchimp-for-woocommerce' ] }
					onStatus={ ( { complete } ) => this.setState( { pluginRequirementsMet: complete } ) }
				/>
			),
		];
	}
}

export default withWizardScreen( Newsletters );
