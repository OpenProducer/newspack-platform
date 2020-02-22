/**
 * Performance Wizard Intro screen
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
import { Card, withWizardScreen, Grid, ImageUpload, Notice } from '../../../../components/src';

/**
 * Intro screen for Performnance Wizard
 */
class Intro extends Component {
	/**
	 * Render.
	 */
	render() {
		const { updateSiteIcon, settings } = this.props;
		return (
			<Card noBackground>
				<h2>{ __( 'Increase user engagement' ) }</h2>
				<p>
					{ __(
						'Engage with your audience more by letting them add the site to their home screen and use it offline.'
					) }
				</p>
				<hr />
				<h2>{ __( 'Automatic performance enhancements' ) }</h2>
				<p>
					{ __(
						'Newspack utilizes Progressive Web App (PWA) to automatically optimize and configure your news site to perform better. It automatically improves:'
					) }
				</p>
				<p>
					<strong>{ __( 'Speed: ' ) }</strong>
					{ __( 'Will work reliably, no matter the network conditions.' ) }
					<br />
					<strong>{ __( 'Security: ' ) }</strong>
					{ __( 'Served through HTTPS to protect the integrity of your news site.' ) }
					<br />
					<strong>{ __( 'User experience:  ' ) }</strong>
					{ __( 'Feels like a native app on the device.' ) }
				</p>
				<p><em>{ __( 'PWA options have been automatically set up for you.' ) }</em></p>
				{ settings.configured && (
					<Notice noticeText={ __( 'PWA is configured and working.' ) } isSuccess />
				) }
				{ ! settings.configured && settings.error && (
					<Notice noticeText={ settings.error } isError />
				) }
				<hr />
				<h2>{ __( 'Site icon' ) }</h2>
				<p>{ __( 'Your site icon is the icon your site has when installed as an app.' ) }</p>
				<ImageUpload
					image={ settings.site_icon }
					onChange={ image => updateSiteIcon( image ) }
					addText={ __( 'Add icon' ) }
					removeText={ __( 'Remove icon' ) }
				/>
				<Notice noticeText={ __( 'Site icons should be square and at least 144 × 144 pixels.' ) } isPrimary />
			</Card>
		);
	}
}

export default withWizardScreen( Intro, {} );
