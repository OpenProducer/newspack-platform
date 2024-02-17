/* globals newspack_my_account */

/**
 * Internal dependencies.
 */
import './style.scss';

/**
 * Specify a function to execute when the DOM is fully loaded.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/packages/dom-ready/
 *
 * @param {Function} callback A function to execute after the DOM is ready.
 * @return {void}
 */
function domReady( callback ) {
	if ( typeof document === 'undefined' ) {
		return;
	}
	if (
		document.readyState === 'complete' || // DOMContentLoaded + Images/Styles/etc loaded, so we call directly.
		document.readyState === 'interactive' // DOMContentLoaded fires at this point, so we call directly.
	) {
		return void callback();
	}
	// DOMContentLoaded has not fired yet, delay callback until then.
	document.addEventListener( 'DOMContentLoaded', callback );
}

domReady( function () {
	const cancelButton = document.querySelector( '.subscription_details .button.cancel' );

	if ( cancelButton ) {
		const confirmCancel = event => {
			const message =
				newspack_my_account?.labels?.cancel_subscription_message ||
				'Are you sure you want to cancel this subscription?';

			// eslint-disable-next-line no-alert
			if ( ! confirm( message ) ) {
				event.preventDefault();
			}
		};
		cancelButton.addEventListener( 'click', confirmCancel );
	}
} );
