import { getEventPayload, getProductDetails, sendEvent } from './utils';

/**
 * Event fired when switching between steps of the multi-step checkout flow.
 *
 * @param {string} action Action name for the event: 'continue' or 'back'.
 */

export const managePagination = ( action = 'continue' ) => {
	if ( 'function' !== typeof window.gtag ) {
		return;
	}

	const { action_type, amount, currency, product_id, product_type, recurrence, referrer, variation_id = '' } = getProductDetails( 'modal-checkout-product-details' );

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

	const payload = getEventPayload( action, params );
	sendEvent( payload );
};
