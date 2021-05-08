const apiFetch = ( { path } ) =>
	new Promise( resolve => {
		if ( path.indexOf( '/newspack/v1/wizard/newspack-popups-wizard/segmentation-reach' ) === 0 ) {
			resolve( { total: 10, in_segment: 10 } );
		}
		resolve();
	} );

module.exports = apiFetch;
