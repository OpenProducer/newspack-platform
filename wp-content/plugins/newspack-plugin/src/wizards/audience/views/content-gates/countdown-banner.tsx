/**
 * Metered Countdown settings page.
 */

/**
 * WordPress dependencies.
 */
import { useDispatch } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { Notice } from '../../../../../packages/components/src';
import { useWizardData } from '../../../../../packages/components/src/wizard/store/utils';
import { WIZARD_STORE_NAMESPACE } from '../../../../../packages/components/src/wizard/store';
import { useWizardApiFetch } from '../../../hooks/use-wizard-api-fetch';
import CountdownBanner from '../setup/countdown-banner';
import { AUDIENCE_CONTENT_GATES_WIZARD_SLUG } from './consts';
import './style.scss';

const CountdownBannerSettings = () => {
	const wizardData = useWizardData( AUDIENCE_CONTENT_GATES_WIZARD_SLUG ) as WizardData;
	const { updateWizardSettings } = useDispatch( WIZARD_STORE_NAMESPACE );
	const { wizardApiFetch, errorMessage, resetError } = useWizardApiFetch( AUDIENCE_CONTENT_GATES_WIZARD_SLUG );

	const onChange = ( newConfig: GateSettings ) => {
		updateWizardSettings( {
			slug: AUDIENCE_CONTENT_GATES_WIZARD_SLUG,
			path: [ 'config' ],
			value: newConfig,
		} );
	};

	const updateConfig = ( newConfig: GateSettings ) => {
		resetError();
		wizardApiFetch(
			{
				path: '/newspack/v1/wizard/newspack-audience-content-gates/countdown-banner',
				method: 'POST',
				quiet: true,
				data: newConfig.countdown_banner,
			},
			{
				onSuccess( data ) {
					onChange( { ...wizardData?.config, countdown_banner: data } );
				},
			}
		);
	};

	return (
		<>
			{ errorMessage && <Notice isError noticeText={ errorMessage } /> }
			<CountdownBanner config={ wizardData?.config || {} } setConfig={ onChange } updateConfig={ updateConfig } noBorder />
		</>
	);
};

export default CountdownBannerSettings;
