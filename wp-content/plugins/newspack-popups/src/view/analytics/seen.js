import { getEventPayload, getRawId, sendEvent } from '../utils';

// The minimum continuous amount of time the prompt must be in the viewport before being considered visible.
const MINIMUM_VISIBLE_TIME = 250;

// The minimum percentage of the prompt that must be in the viewport before being considered visible.
const MINIMUM_VISIBLE_PERCENTAGE = 0.5;

/**
 * Execute a callback function to send a GA event when a prompt becomes visible.
 *
 * @param {Function} handleEvent Callback function to execute when the prompt becomes eligible for display.
 * @return {IntersectionObserver} Observer instance.
 */
const getObserver = handleEvent => {
	let timer;
	const observer = new IntersectionObserver(
		entries => {
			entries.forEach( observerEntry => {
				if ( observerEntry.isIntersecting ) {
					if ( ! timer ) {
						timer = setTimeout( () => {
							handleEvent();
							observer.unobserve( observerEntry.target );
						}, MINIMUM_VISIBLE_TIME || 0 );
					}
				} else if ( timer ) {
					clearTimeout( timer );
					timer = false;
				}
			} );
		},
		{
			threshold: MINIMUM_VISIBLE_PERCENTAGE,
		}
	);

	return observer;
};

/**
 * Event fired when a prompt becomes visible in the viewport.
 *
 * @param {Array} prompts Array of prompts.
 */
export const manageSeenEvents = prompts => {
	prompts.forEach( prompt => {
		const handleEvent = () => {
			const payload = getEventPayload( 'seen', getRawId( prompt.getAttribute( 'id' ) ) );
			sendEvent( payload );
		};

		getObserver( handleEvent ).observe( prompt, { attributes: true } );
	} );
};
