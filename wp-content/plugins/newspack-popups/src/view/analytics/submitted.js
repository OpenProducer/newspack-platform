import { getEventPayload, getRawId, sendEvent } from '../utils';

/**
 * Execute a callback function to send a GA event when a prompt is dismissed.
 *
 * @param {Array} prompts Array of prompts loaded in the DOM.
 */

export const manageFormSubmissions = prompts => {
	prompts.forEach( prompt => {
		const forms = [
			...prompt.querySelectorAll( '.newspack-popup__content form, .newspack-inline-popup form' ),
		];
		const handleEvent = () => {
			const payload = getEventPayload( 'form_submission', getRawId( prompt.getAttribute( 'id' ) ) );
			sendEvent( payload );
		};

		forms.forEach( form => form.addEventListener( 'submit', handleEvent ) );
	} );
};
