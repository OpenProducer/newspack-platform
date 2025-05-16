import { getEventPayload, getProductDetails, sendEvent } from './utils';

/**
 * Event fired when a checkout modal is dismissed (not when closed automatically due to a completed checkout).
 *
 * @param {Object} data The data to send with the event.
 */
export const manageDismissed = ( data ) => {
	if ( 'function' !== typeof window.gtag ) {
		return;
	}

	data = data || getProductDetails( 'newspack_modal_checkout' );

	const payload = getEventPayload( 'dismissed', data );
	sendEvent( payload );
};
