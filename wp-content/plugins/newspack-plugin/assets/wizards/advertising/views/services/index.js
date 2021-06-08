/**
 * Ad Services view.
 */

/**
 * WordPress dependencies
 */
import { ExternalLink } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, withWizardScreen } from '../../../../components/src';

/**
 * Advertising management screen.
 */
const Services = ( { services, toggleService } ) => {
	const { wordads, google_adsense, google_ad_manager } = services;

	return (
		<>
			<p>
				{ __( 'Please enable and configure the ad providers you’d like to use to get started.' ) }
			</p>
			<ActionCard
				title={ __( 'WordAds from WordPress.com' ) }
				badge={ __( 'Jetpack Premium' ) }
				description={ __(
					'A managed ad optimization platform where the top 50 ad networks (DSPs and exchanges) compete for your traffic, with flexible placement options, and support from WordPress.com.'
				) }
				actionText={ wordads && wordads.enabled && __( 'Configure' ) }
				toggle
				toggleChecked={ wordads && wordads.enabled }
				toggleOnChange={ value => toggleService( 'wordads', value ) }
				href={ wordads && '#/ad-placements' }
				notification={
					wordads.upgrade_required && [
						__( 'Upgrade Jetpack to enable WordAds.' ) + '\u00A0',
						<ExternalLink href="/wp-admin/admin.php?page=jetpack#/plans" key="jetpack-link">
							{ __( 'Click to upgrade' ) }
						</ExternalLink>,
					]
				}
				notificationLevel={ 'info' }
			/>
			<ActionCard
				title={ __( 'Google AdSense' ) }
				description={ __(
					'A simple way to place adverts on your news site automatically based on where they best perform.'
				) }
				actionText={ google_adsense && google_adsense.enabled && __( 'Configure' ) }
				toggle
				toggleChecked={ google_adsense && google_adsense.enabled }
				toggleOnChange={ value => toggleService( 'google_adsense', value ) }
				handoff="google-site-kit"
				editLink="admin.php?page=googlesitekit-module-adsense"
			/>
			<ActionCard
				title={ __( 'Google Ad Manager' ) }
				description={ __(
					'An advanced ad inventory creation and management platform, allowing you to be specific about ad placements.'
				) }
				actionText={ google_ad_manager && google_ad_manager.enabled && __( 'Configure' ) }
				toggle
				toggleChecked={ google_ad_manager && google_ad_manager.enabled }
				toggleOnChange={ value => toggleService( 'google_ad_manager', value ) }
				titleLink={ google_ad_manager ? '#/google_ad_manager' : null }
				href={ google_ad_manager && '#/google_ad_manager' }
			/>
		</>
	);
};

export default withWizardScreen( Services );
