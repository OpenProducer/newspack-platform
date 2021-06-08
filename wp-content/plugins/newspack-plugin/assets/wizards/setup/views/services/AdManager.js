/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import { TextControl, PluginInstaller } from '../../../../components/src';

const AdManager = ( { configuration, onUpdate, className } ) => {
	useEffect( () => {
		apiFetch( { path: '/newspack/v1/wizard/advertising' } ).then( res =>
			onUpdate( { network_code: res.services?.google_ad_manager?.network_code } )
		);
	}, [] );
	if ( configuration.network_code?.errors?.newspack_missing_required_plugin ) {
		return <PluginInstaller plugins={ [ 'newspack-ads' ] } withoutFooterButton />;
	}
	const hasNetworkCode =
		typeof configuration.network_code === 'number' ||
		typeof configuration.network_code === 'string';
	return (
		<div className={ className }>
			<TextControl
				label={ __( 'Network Code', 'newspack' ) }
				placeholder={ __( '0123456789' ) }
				value={ hasNetworkCode ? configuration.network_code : '' }
				disabled={ hasNetworkCode === undefined }
				onChange={ network_code => onUpdate( { network_code } ) }
			/>
		</div>
	);
};

export default AdManager;
