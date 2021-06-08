import apiFetch from '@wordpress/api-fetch';

export const fetchJetpackMailchimpStatus = async () => {
	const jetpackStatus = await apiFetch( { path: `/newspack/v1/plugins/jetpack` } );
	if ( ! jetpackStatus.Configured ) {
		return Promise.resolve( { status: 'inactive', error: { code: 'unavailable_site_id' } } );
	}
	return new Promise( resolve => {
		apiFetch( { path: '/wpcom/v2/mailchimp' } )
			.then( result =>
				resolve( {
					url: result.connect_url,
					status: result.code === 'connected' ? 'active' : 'inactive',
				} )
			)
			.catch( error => resolve( { status: 'inactive', error } ) );
	} );
};
