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
	const { labels, nonce, rest_url, should_rate_limit } = newspack_my_account || {};

	// Show a confirmation dialog before cancelling a subscription.
	if ( cancelButton ) {
		const confirmCancel = event => {
			const message =
				labels?.cancel_subscription_message ||
				'Are you sure you want to cancel this subscription?';

			// eslint-disable-next-line no-alert
			if ( ! confirm( message ) ) {
				event.preventDefault();
			}
		};
		cancelButton.addEventListener( 'click', confirmCancel );
	}

	// Rate limit the add payment method form.
	const addPaymentForm = document.getElementById( 'add_payment_method' );
	if ( addPaymentForm && Boolean( should_rate_limit ) ) {
		const errorContainer = document.querySelector( '.woocommerce-notices-wrapper' );
		const submitButton = addPaymentForm.querySelector( 'input[type="submit"], button[type="submit"]' );
		const rateLimit = function( e ) {
			if ( addPaymentForm.hasAttribute( 'data-check-rate-limit' ) ) {
				errorContainer.textContent = '';
				submitButton.setAttribute( 'disabled', '' );
				e.preventDefault();
				const xhr = new XMLHttpRequest();
				xhr.onreadystatechange = function() {
					// Return if the request is completed.
					if ( xhr.readyState !== 4 ) {
						return;
					}

					// Call onSuccess with parsed JSON if the request is successful.
					if ( xhr.status >= 200 && xhr.status < 300 ) {
						submitButton.removeAttribute( 'disabled' );
						const data = JSON.parse( xhr.responseText );
						if ( data?.success ) {
							addPaymentForm.removeAttribute( 'data-check-rate-limit' );
							addPaymentForm.requestSubmit( submitButton );
							addPaymentForm.setAttribute( 'data-check-rate-limit', '1' );
						}
						if ( data?.error ) {
							const error = document.createElement( 'div' );
							const errorUl = document.createElement( 'ul' );
							const errorLi = document.createElement( 'li' );
							errorUl.classList.add( 'woocommerce-error' );
							errorLi.textContent = data.error;
							error.appendChild( errorUl );
							errorUl.appendChild( errorLi );
							errorContainer.appendChild( error );
							errorContainer.scrollIntoView( { behavior: 'smooth' } );
						}
					}
				};

				xhr.open( 'GET', rest_url + 'newspack/v1/check-rate' );
				xhr.setRequestHeader( 'X-WP-Nonce', nonce );
				xhr.send();
			}
		};
		addPaymentForm.setAttribute( 'data-check-rate-limit', '1' );
		addPaymentForm.addEventListener( 'submit' , rateLimit, true );
		submitButton.addEventListener( 'click', rateLimit, true );
	}

	// Fire a newsletter_signup event when the user subscribes to a newsletter via My Account.
	window.newspackRAS = window.newspackRAS || [];
	window.newspackRAS.push( readerActivation => {
		const reader = readerActivation.getReader();
		const params = new URLSearchParams( window.location.search );
		const subscribed = params.get( 'newspack_newsletters_subscription_subscribed' );
		if ( subscribed && reader?.email && reader?.authenticated ) {
			readerActivation.dispatchActivity( 'newsletter_signup', {
				email: reader.email,
				lists: subscribed.split( ',' ),
				newsletters_subscription_method: 'my-account',
			} );
		}
		params.delete( 'newspack_newsletters_subscription_subscribed' );
		const newQueryString = params.toString() ? '?' + params.toString() : '';
		window.history.replaceState( {}, '', window.location.pathname + newQueryString );
	} );
} );
