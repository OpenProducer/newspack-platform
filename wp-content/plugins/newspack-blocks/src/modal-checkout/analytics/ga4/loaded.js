import { getEventPayload, getProductDetails, sendEvent } from './utils';
import { domReady } from '../../utils';

/**
 * Event fired when the modal checkout content is loaded.
 */
export const manageLoaded = () => {
	domReady( function() {
		if ( 'function' !== typeof window.gtag ) {
			return;
		}

		const {
			action_type,
			amount,
			currency,
			product_id,
			product_type,
			recurrence,
			referrer,
			variation_id = ''
		} = getProductDetails( 'modal-checkout-product-details' );

		const params = {
			action_type,
			amount,
			currency,
			product_id,
			product_type,
			recurrence,
			referrer,
		};

		// There's only a variation ID for variable products, after you've selected one.
		if ( variation_id ) {
			params.variation_id = variation_id;
		}

		const payload = getEventPayload( 'loaded', params );

		sendEvent( payload );
	} );
};
