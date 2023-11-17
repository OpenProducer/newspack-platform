/**
 * Internal dependencies
 */
import './modal.scss';

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

const triggers =
	'.wpbnbd.wpbnbd--platform-wc,.wp-block-newspack-blocks-checkout-button,.newspack-blocks-variation-modal';

let iframeResizeObserver;

function closeCheckout() {
	const iframe = document.querySelector( 'iframe[name="newspack_modal_checkout"]' );
	if ( iframe ) {
		iframe.src = 'about:blank';
	}
	document.body.classList.remove( 'newspack-modal-checkout-open' );
	if ( iframeResizeObserver ) {
		iframeResizeObserver.disconnect();
	}
	Array.from( document.querySelectorAll( '.newspack-blocks-modal' ) ).forEach( el => {
		el.style.display = 'none';
		if ( el.overlayId && window.newspackReaderActivation?.overlays ) {
			window.newspackReaderActivation?.overlays.remove( el.overlayId );
		}
	} );
}

window.newspackCloseModalCheckout = closeCheckout;

const MODAL_CLASSNAME_BASE = '.newspack-blocks-modal';

domReady( () => {
	/**
	 * Initialize modal checkout.
	 */
	const modalCheckout = document.querySelector( '.newspack-blocks-checkout-modal' );
	if ( ! modalCheckout ) {
		return;
	}
	const spinner = document.querySelector( `${ MODAL_CLASSNAME_BASE }__spinner` );
	const iframeName = 'newspack_modal_checkout';
	const modalCheckoutInput = document.createElement( 'input' );
	modalCheckoutInput.type = 'hidden';
	modalCheckoutInput.name = 'modal_checkout';
	modalCheckoutInput.value = '1';
	const modalContent = modalCheckout.querySelector( `${ MODAL_CLASSNAME_BASE }__content` );
	const initialHeight = modalContent.clientHeight + 'px';
	const iframe = document.createElement( 'iframe' );
	iframe.name = iframeName;
	modalContent.appendChild( iframe );
	modalCheckout.addEventListener( 'click', ev => {
		if ( ev.target === modalCheckout ) {
			closeCheckout();
		}
	} );
	const closeButtons = modalCheckout.querySelectorAll( `${ MODAL_CLASSNAME_BASE }__close` );
	closeButtons.forEach( button => {
		button.addEventListener( 'click', ev => {
			ev.preventDefault();
			modalContent.style.height = initialHeight;
			spinner.style.display = 'flex';
			closeCheckout();
		} );
	} );

	/**
	 * Variation modals.
	 */
	const variationModals = document.querySelectorAll( '.newspack-blocks-variation-modal' );
	variationModals.forEach( variationModal => {
		variationModal.addEventListener( 'click', ev => {
			if ( ev.target === variationModal ) {
				closeCheckout();
			}
		} );
		variationModal.querySelectorAll( '.newspack-blocks-modal__close' ).forEach( button => {
			button.addEventListener( 'click', ev => {
				ev.preventDefault();
				closeCheckout();
			} );
		} );
	} );

	/**
	 * Handle triggers.
	 */
	const elements = document.querySelectorAll( triggers );
	elements.forEach( element => {
		const forms = element.querySelectorAll( 'form' );
		forms.forEach( form => {
			form.appendChild( modalCheckoutInput.cloneNode() );
			form.target = iframeName;

			// Fill in the referrer field.
			const afterSuccessUrlInput = form.querySelector( 'input[name="after_success_url"]' );
			const afterSuccessBehaviorInput = form.querySelector(
				'input[name="after_success_behavior"]'
			);
			if (
				afterSuccessBehaviorInput &&
				afterSuccessUrlInput &&
				'referrer' === afterSuccessBehaviorInput.getAttribute( 'value' )
			) {
				afterSuccessUrlInput.setAttribute( 'value', document.referrer || window.location.href );
			}

			form.addEventListener( 'submit', ev => {
				const formData = new FormData( form );
				// Clear any open variation modal.
				variationModals.forEach( variationModal => ( variationModal.style.display = 'none' ) );
				// Trigger variation modal if variation is not selected.
				if ( formData.get( 'is_variable' ) && ! formData.get( 'variation_id' ) ) {
					const variationModal = [ ...variationModals ].find(
						modal => modal.dataset.productId === formData.get( 'product_id' )
					);
					if ( variationModal ) {
						// Fill in the after success variables in the variation modal.
						variationModal
							.querySelectorAll( 'form[target="newspack_modal_checkout"]' )
							.forEach( singleVariationForm => {
								[
									'after_success_behavior',
									'after_success_url',
									'after_success_button_label',
								].forEach( afterSuccessParam => {
									const input = document.createElement( 'input' );
									input.type = 'hidden';
									input.name = afterSuccessParam;
									input.value = formData.get( afterSuccessParam );
									singleVariationForm.appendChild( input );
								} );
							} );
						// Open the variations modal.
						ev.preventDefault();
						document.body.classList.add( 'newspack-modal-checkout-open' );
						variationModal.style.display = 'block';
						return;
					}
				}
				// Continue with checkout modal.
				spinner.style.display = 'flex';
				modalCheckout.style.display = 'block';
				document.body.classList.add( 'newspack-modal-checkout-open' );
				if ( window.newspackReaderActivation?.overlays ) {
					modalCheckout.overlayId = window.newspackReaderActivation?.overlays.add();
				}

				iframeResizeObserver = new ResizeObserver( entries => {
					if ( ! entries || ! entries.length ) {
						return;
					}
					const contentRect = entries[ 0 ].contentRect;
					if ( contentRect ) {
						modalContent.style.height = contentRect.top + contentRect.bottom + 'px';
						spinner.style.display = 'none';
					}
				} );
				iframe.addEventListener( 'load', () => {
					const location = iframe.contentWindow.location;
					// If RAS is available, set the front-end authentication.
					if ( window.newspackReaderActivation && location.href.indexOf( 'order-received' ) > -1 ) {
						const ras = window.newspackReaderActivation;
						const params = new Proxy( new URLSearchParams( location.search ), {
							get: ( searchParams, prop ) => searchParams.get( prop ),
						} );
						if ( params.email ) {
							ras.setReaderEmail( params.email );
							ras.setAuthenticated( true );
						}
					}
					const container = iframe.contentDocument.querySelector( '#newspack_modal_checkout' );
					if ( container ) {
						iframeResizeObserver.observe( container );
					}
					const innerButtons = [
						...iframe.contentDocument.querySelectorAll( '.modal-continue, .edit-billing-link' ),
					];
					innerButtons.forEach( innerButton => {
						innerButton.addEventListener( 'click', () => ( spinner.style.display = 'flex' ) );
					} );
					const innerForm = iframe.contentDocument.querySelector( '.checkout' );
					if ( innerForm ) {
						const innerBillingFields = [
							...innerForm.querySelectorAll( '.woocommerce-billing-fields input' ),
						];
						innerBillingFields.forEach( innerField => {
							innerField.addEventListener( 'keyup', e => {
								if ( 'Enter' === e.key ) {
									spinner.style.display = 'flex';
									innerForm.submit();
								}
							} );
						} );
					}
				} );
			} );
		} );
	} );
} );
