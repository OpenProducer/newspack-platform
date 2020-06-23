/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { dispatch, select } from '@wordpress/data';
import { createHigherOrderComponent } from '@wordpress/compose';
import { useState } from '@wordpress/element';

export default () =>
	createHigherOrderComponent(
		OriginalComponent => props => {
			const [ inFlight, setInFlight ] = useState( false );
			const [ errors, setErrors ] = useState( {} );
			const { createSuccessNotice, createErrorNotice, removeNotice } = dispatch( 'core/notices' );
			const { getNotices } = select( 'core/notices' );
			const setInFlightForAsync = () => {
				setInFlight( true );
			};
			const apiFetchWithErrorHandling = apiRequest => {
				setInFlight( true );
				return new Promise( ( resolve, reject ) => {
					apiFetch( apiRequest )
						.then( response => {
							const { message } = response;
							getNotices().forEach( notice => removeNotice( notice.id ) );
							if ( message ) {
								createSuccessNotice( message );
							}
							setInFlight( false );
							setErrors( {} );
							resolve( response );
						} )
						.catch( error => {
							const { message } = error;
							getNotices().forEach( notice => removeNotice( notice.id ) );
							createErrorNotice( message );
							setInFlight( false );
							setErrors( { [ error.code ]: true } );
							reject( error );
						} );
				} );
			};
			return (
				<OriginalComponent
					{ ...props }
					apiFetchWithErrorHandling={ apiFetchWithErrorHandling }
					errors={ errors }
					setInFlightForAsync={ setInFlightForAsync }
					inFlight={ inFlight }
				/>
			);
		},
		'with-api-handler'
	);
