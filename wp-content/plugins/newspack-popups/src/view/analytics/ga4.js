/* global gtag */

import { manageLoadedEvents } from './loaded';
import { manageSeenEvents } from './seen';
import { manageDismissals } from './dismissed';
import { manageClickedEvents } from './clicked';
import { manageFormSubmissions } from './submitted';

import { getPrompts } from '../utils';

export const manageAnalytics = () => {
	// Must have a gtag instance to proceed.
	if ( 'function' === typeof gtag ) {
		// Fetch all prompts on the page just once.
		const prompts = getPrompts();

		manageLoadedEvents( prompts );
		manageSeenEvents( prompts );
		manageDismissals( prompts );
		manageClickedEvents( prompts );
		manageFormSubmissions( prompts );
	}
};
