/**
 * Settings Collections: Global settings for Collections module.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import WizardsActionCard from '../../../../wizards-action-card';
import useWizardApiFetchToggle from '../../../../hooks/use-wizard-api-fetch-toggle';

/**
 * Collections settings component.
 */
function Collections() {
	const {
		description,
		apiData,
		isFetching,
		actionText,
		apiFetchToggle,
		errorMessage,
	} = useWizardApiFetchToggle< { module_enabled_collections: boolean } >( {
		path: '/newspack/v1/wizard/newspack-settings/collections',
		apiNamespace: 'newspack-settings/collections',
		refreshOn: [ 'POST' ],
		data: {
			module_enabled_collections: false,
		},
		description: __(
			'Manage print editions and other collections of content with custom ordering and organization.',
			'newspack-plugin'
		),
	} );

	return (
		<div className="newspack-wizard__sections">
			<h1>{ __( 'Collections Settings', 'newspack-plugin' ) }</h1>
			<WizardsActionCard
				title={ __( 'Collections Module', 'newspack-plugin' ) }
				description={ description }
				disabled={ isFetching }
				actionText={ actionText }
				error={ errorMessage }
				toggleChecked={ apiData.module_enabled_collections }
				toggleOnChange={ ( value: boolean ) =>
					apiFetchToggle(
						{ ...apiData, module_enabled_collections: value },
						true
					)
				}
			/>
		</div>
	);
}

export default Collections;
