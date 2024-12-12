import { getEventPayload, sendEvent } from './utils';

/**
 * Execute a callback function to send a GA event when a prompt is dismissed.
 *
 * @param {Object} data Information about the purchase being made.
 */
export const manageOpened = ( data ) => {
	if ( 'function' !== typeof window.gtag ) {
		return;
	}

	let action = 'opened';

	const {
		action_type,
		amount = '',
		currency,
		is_variable = '',
		price = '',
		product_id,
		product_type,
		recurrence,
		referrer,
		variation_id = '',
	} = data;

	const params = {
		action_type,
		currency,
		product_id,
		product_type,
		referrer,
	};

	// On the first variable screen, there may not be a price so we want to check for it.
	if ( amount || price ) {
		params.amount = amount ? amount : price;
	}

	// Only pass is_variable if available -- it only is for variable products.
	if ( is_variable ) {
		params.is_variable = is_variable;
	}

	// Only pass the variation_id if available -- it only is when a variation is picked.
	if ( variation_id ) {
		params.variation_id = variation_id;
	}

	// Only pass the recurrence if available -- for variable products, it won't be until a variation is picked.
	if ( recurrence ) {
		params.recurrence = recurrence;
	}

	// Change the action when opening the initial variation modal.
	if ( is_variable && ! variation_id ) {
		action = 'opened_variations';
	}

	const payload = getEventPayload( action, params );

	sendEvent( payload );
};
