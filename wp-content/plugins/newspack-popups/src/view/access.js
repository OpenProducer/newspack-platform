/**
 * Internal dependencies
 */
import { shouldPolyfillAMPModule, parseDynamicURL } from './utils';
import './access.scss';

export const manageAccess = () => {
	if ( ! shouldPolyfillAMPModule( 'access' ) ) {
		return;
	}
	const ampAccessScript = document.getElementById( 'amp-access' );
	if ( ! ampAccessScript ) {
		return;
	}
	let ampAccessConfig;
	try {
		ampAccessConfig = JSON.parse( ampAccessScript.innerText );
	} catch ( error ) {}
	if ( ! ampAccessConfig ) {
		return;
	}
	const { authorization, namespace } = ampAccessConfig;

	fetch( parseDynamicURL( authorization ) )
		.then( response => response.json() )
		.then( data => {
			Object.keys( data ).forEach( key => {
				const element = document.querySelectorAll( `[amp-access='${ namespace }.${ key }']` );
				if ( element ) {
					const shouldDisplay = data[ key ];
					if ( shouldDisplay ) {
						element.forEach( el => el.removeAttribute( 'amp-access-hide' ) );
					}
				}
			} );
		} );
};
