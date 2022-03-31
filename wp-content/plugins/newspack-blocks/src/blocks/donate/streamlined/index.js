/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { loadStripe } from '@stripe/stripe-js/pure';
import 'regenerator-runtime'; // Required in WP >=5.8.

/**
 * Internal dependencies
 */
import * as utils from './utils';
import './style.scss';

export const processStreamlinedElements = ( parentElement = document ) =>
	[ ...parentElement.querySelectorAll( '.stripe-payment' ) ].forEach( async el => {
		let stripe, cardElement, paymentRequest, paymentMethod, paymentRequestToken;

		const formElement = el.closest( 'form' );
		const messagesEl = el.querySelector( '.stripe-payment__messages' );

		const settings = utils.getSettings( formElement );

		const disableForm = () => el.classList.add( 'stripe-payment--disabled' );
		const enableForm = () => el.classList.remove( 'stripe-payment--disabled' );
		enableForm();

		// Universal payment handling, for both card and payment request button flows.
		// In card flow, this will happen after user submits their card data in the HTML form.
		// In payment request flow, this will happen after the user validates the payment in
		// the browser/OS UI.
		const payWithToken = async (
			token,
			/**
			 * Overrides for sent donation data. In a card flow the data is
			 * provided explicitly by the user (via the form), but in payment request flow
			 * the data is from the payment request event.
			 */
			requestPayloadOverrides = {}
		) => {
			const formValues = utils.getFormValues( formElement );
			const apiRequestPayload = {
				tokenData: token,
				amount: utils.getTotalAmount( formElement ),
				email: formValues.email,
				full_name: formValues.full_name,
				frequency: formValues.donation_frequency,
				newsletter_opt_in: Boolean( formValues.newsletter_opt_in ),
				clientId: formValues.cid,
				...requestPayloadOverrides,
			};
			const chargeResultData = await utils.sendAPIRequest( '/donate', apiRequestPayload );

			// Error handling.
			if ( chargeResultData.data?.status !== 200 && chargeResultData.message ) {
				utils.renderMessages( [ chargeResultData.message ], messagesEl );
				return { error: true };
			}
			if ( chargeResultData.error ) {
				utils.renderMessages( [ chargeResultData.error ], messagesEl );
				return { error: true };
			}

			const exitWithError = errorMessage => {
				utils.renderMessages( [ errorMessage ], messagesEl );
				enableForm();
				return { error: true };
			};

			// Additional steps handling.
			if ( chargeResultData.client_secret && paymentMethod ) {
				const confirmationResult = await stripe.confirmCardPayment(
					chargeResultData.client_secret,
					{
						payment_method: paymentMethod,
					}
				);

				if ( confirmationResult.error ) {
					return exitWithError( confirmationResult.error.message );
				} else if ( confirmationResult.paymentIntent.status === 'succeeded' ) {
					utils.renderSuccessMessageWithEmail( apiRequestPayload.email, messagesEl );
				} else if ( confirmationResult.paymentIntent.status === 'requires_action' ) {
					// Could not test this flow (with 3D Secure test cards), but it's listed in the docs:
					// https://stripe.com/docs/stripe-js/elements/payment-request-button#html-js-complete-payment
					const { error: confirmationError } = await stripe.confirmCardPayment(
						chargeResultData.client_secret
					);
					if ( confirmationError ) {
						return exitWithError( confirmationError.message );
					}
					utils.renderSuccessMessageWithEmail( apiRequestPayload.email, messagesEl );
				} else {
					return exitWithError(
						__(
							'Something went wrong with the payment. Please try again later.',
							'newspack-blocks'
						)
					);
				}
			}

			if ( chargeResultData.status === 'success' ) {
				utils.renderSuccessMessageWithEmail( apiRequestPayload.email, messagesEl );
			}
			return {};
		};

		const initStripe = async () => {
			stripe = await loadStripe( settings.stripePublishableKey );

			const elements = stripe.elements();

			// Handle card element.
			cardElement = elements.create( 'card' );
			cardElement.mount( el.querySelector( '.stripe-payment__card' ) );

			// Handle payment request button (Apple/Google Pay). This has to be initialised to see if
			// such payments are available in the browser (canMakePayment method).
			paymentRequest = stripe.paymentRequest( {
				country: settings.countryCode,
				currency: settings.currency,
				total: utils.getPaymentRequestTotal( formElement ),
				// Docs: Use the requestPayerName parameter to collect the payer’s billing address for Apple Pay.
				requestPayerName: true,
				requestPayerEmail: true,
			} );

			const rendersPaymentRequestButton = await paymentRequest.canMakePayment();
			if ( rendersPaymentRequestButton ) {
				paymentRequest.on( 'token', async event => {
					paymentRequestToken = event.token;
				} );
				paymentRequest.on( 'paymentmethod', async event => {
					// Save payment method ID to use it in payWithToken if a client secret is returned.
					paymentMethod = event.paymentMethod.id;
					const result = await payWithToken( paymentRequestToken, {
						email: event.payerEmail,
						full_name: event.payerName,
						payment_method_id: paymentMethod,
					} );
					// The UI messages are handled in payWithToken, this event listener only
					// has to notify the browser that the payment is done.
					event.complete( result.error ? 'fail' : 'success' );
				} );
				// Update payment request when the form values are updated.
				formElement.addEventListener( 'change', () => {
					paymentRequest.update( {
						total: utils.getPaymentRequestTotal( formElement ),
					} );
				} );
				// Create and mount the payment request button.
				const prButton = elements.create( 'paymentRequestButton', {
					paymentRequest,
					style: {
						paymentRequestButton: {
							type: 'donate',
							height: '46px',
						},
					},
				} );
				const prButtonElement = el.querySelector( '.stripe-payment__request-button' );
				prButton.mount( prButtonElement );
				prButtonElement.classList.remove( 'stripe-payment--hidden' );
				setTimeout( () => {
					prButtonElement.classList.remove( 'stripe-payment__request-button--invisible' );
				}, 0 );
			}

			el.classList.remove( 'stripe-payment--invisible' );
		};

		initStripe();

		// Card form unravelling.
		const submitButtonEl = el.querySelector( 'button[type="submit"]' );
		submitButtonEl.onclick = e => {
			const inputsHiddenEl = el.querySelector( '.stripe-payment__inputs.stripe-payment--hidden' );
			if ( inputsHiddenEl ) {
				e.preventDefault();
				inputsHiddenEl.classList.remove( 'stripe-payment--hidden' );
			}
		};

		const updateFeesAmount = () => {
			const feesAmountEl = el.querySelector( '#stripe-fees-amount' );
			if ( feesAmountEl ) {
				const formValues = Object.fromEntries( new FormData( formElement ) );
				const feeAmount = utils.getFeeAmount( formElement );
				feesAmountEl.innerHTML = `(${ settings.currencySymbol }${ feeAmount.toFixed(
					2
				) } ${ settings.frequencies[ formValues.donation_frequency ].toLowerCase() })`;
			}
		};

		updateFeesAmount();
		formElement.addEventListener( 'change', updateFeesAmount );

		// Card payment flow – on form submission.
		formElement.addEventListener( 'submit', async e => {
			e.preventDefault();
			disableForm();
			utils.renderMessages(
				[ __( 'Processing payment…', 'newspack-blocks' ) ],
				messagesEl,
				'info'
			);

			const formValues = utils.getFormValues( formElement );
			const validationErrors = Object.values( utils.validateFormData( formValues ) );
			if ( validationErrors.length > 0 ) {
				utils.renderMessages( validationErrors, messagesEl );
				enableForm();
				return;
			}

			const handleStripeSDKError = error => {
				validationErrors.push( error.message );
				utils.renderMessages( validationErrors, messagesEl );
				enableForm();
			};

			const stripeTokenCreationResult = await stripe.createToken( cardElement );
			if ( stripeTokenCreationResult.error ) {
				handleStripeSDKError( stripeTokenCreationResult.error );
				return;
			}
			const paymentMethodCreationResult = await stripe.createPaymentMethod( {
				type: 'card',
				card: cardElement,
				billing_details: { name: formValues.full_name, email: formValues.email },
			} );
			if ( paymentMethodCreationResult.error ) {
				handleStripeSDKError( paymentMethodCreationResult.error );
				return;
			}
			// Save payment method ID to use it in payWithToken if a client secret is returned.
			paymentMethod = { card: cardElement };
			await payWithToken( stripeTokenCreationResult.token, {
				payment_method_id: paymentMethodCreationResult.paymentMethod.id,
			} );
		} );
	} );

processStreamlinedElements();
