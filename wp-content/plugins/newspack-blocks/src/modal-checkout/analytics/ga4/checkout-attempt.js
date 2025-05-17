import { getEventPayload, getProductDetails, sendEvent } from './utils';

/**
 * Event fired when switching between steps of the multi-step checkout flow.
 *
 * @param {string} action Action name for the event: 'continue' or 'back'.
 */

export const manageCheckoutAttempt = () => {
	if ( 'function' !== typeof window.gtag ) {
		return;
	}

	const params = getProductDetails( 'modal-checkout-product-details' );
	const payload = getEventPayload( 'form_submission', params );
	sendEvent( payload );
};
