/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';

const isValidEmail = string => /\S+@\S+/.test( string );
export const validateFormData = values => {
	const errors = {};
	if ( ! isValidEmail( values.email ) ) {
		errors.email = __( 'Email address is invalid.', 'newspack-blocks' );
	}
	if ( values.amount <= 0 ) {
		errors.amount = __( 'Amount must be greater than zero.', 'newspack-blocks' );
	}
	if ( values.full_name.length === 0 ) {
		errors.amount = __( 'Full name should be provided.', 'newspack-blocks' );
	}
	return errors;
};

/**
 * Renders UI messages in a given DOM element.
 */
export const renderMessages = ( messages, el, type = 'error' ) => {
	el.innerHTML = '';
	messages.forEach( message => {
		const messageEl = document.createElement( 'div' );
		messageEl.classList.add( `type-${ type }` );
		messageEl.innerHTML = message;
		el.appendChild( messageEl );
	} );
};

const getCookies = () =>
	document.cookie.split( '; ' ).reduce( ( acc, cookieStr ) => {
		const cookie = cookieStr.split( '=' );
		acc[ cookie[ 0 ] ] = cookie[ 1 ];
		return acc;
	}, {} );

/**
 * Retrieves donation settings passed via a `data-settings` HTML attribute
 * on a `form` element.
 */
export const getSettings = formElement => {
	const [
		currency,
		currencySymbol,
		siteName,
		isCurrencyZeroDecimal,
		countryCode,
		frequencies,
		feeMultiplier,
		feeStatic,
		stripePublishableKey,
	] = JSON.parse( formElement.getAttribute( 'data-settings' ) );
	return {
		currency: currency.toLowerCase(),
		currencySymbol,
		siteName,
		isCurrencyZeroDecimal,
		countryCode,
		frequencies,
		feeMultiplier: parseFloat( feeMultiplier ),
		feeStatic: parseFloat( feeStatic ),
		stripePublishableKey,
	};
};

/**
 * Retrieves form values from the donation form HTML element.
 */
export const getFormValues = formElement => {
	const formValues = Object.fromEntries( new FormData( formElement ) );
	const valueKey = `donation_value_${ formValues.donation_frequency }`;
	formValues.amount = formValues[ valueKey ];
	if ( formValues.amount === 'other' ) {
		formValues.amount = formValues[ `${ valueKey }_other` ];
	}
	if ( ! formValues.amount ) {
		formValues.amount = formValues[ `${ valueKey }_untiered` ];
	}
	if ( formValues.cid && formValues.cid.indexOf( 'CLIENT_ID' ) === 0 ) {
		// In non-AMP environment, the value will not be dynamically substituted by AMP runtime.
		formValues.cid = getCookies()[ 'newspack-cid' ];
	}
	return formValues;
};

/**
 * Computes the total amount of the donation, taking into account if the
 * donor has opted to cover the processing fee.
 */
export const getTotalAmount = (
	formElement,
	// For the payment request button (Apple/Google Pay), the amount has to be
	// delivered in subunits.
	{ convertToSubunit } = { convertToSubunit: false }
) => {
	const settings = getSettings( formElement );
	let { amount, agree_to_pay_fees: paysFees } = getFormValues( formElement );

	const processAmount = amountToProcess =>
		parseFloat(
			convertToSubunit
				? amountToProcess * ( settings.isCurrencyZeroDecimal ? 1 : 100 )
				: amountToProcess
		);

	amount = processAmount( amount );
	if ( paysFees ) {
		const feesAmount = processAmount( getFeeAmount( formElement ) );
		amount = amount + feesAmount;
	}
	return amount;
};

/**
 * Creates a `total` value for Stripe's `paymentRequest` creation and updating.
 */
export const getPaymentRequestTotal = formElement => {
	const settings = getSettings( formElement );
	const { donation_frequency: frequency } = getFormValues( formElement );
	const frequencyLabel = settings.frequencies[ frequency ];
	return {
		label: `${ settings.siteName } (${ frequencyLabel })`,
		amount: getTotalAmount( formElement, { convertToSubunit: true } ),
	};
};

/**
 * Computes the fee amount.
 */
export const computeFeeAmount = ( amount, feeMultiplier, feeStatic ) => {
	return parseFloat(
		( ( ( amount + feeStatic ) / ( 100 - feeMultiplier ) ) * 100 - amount ).toFixed( 2 )
	);
};

/**
 * Given the donation form HTML element, computes the fee amount.
 */
export const getFeeAmount = formElement => {
	const { amount } = getFormValues( formElement );
	const { feeMultiplier, feeStatic } = getSettings( formElement );
	return computeFeeAmount( parseFloat( amount ), feeMultiplier, feeStatic );
};

export const sendAPIRequest = async ( endpoint, data, method = 'POST' ) =>
	fetch( `/wp-json/newspack-blocks/v1${ endpoint }`, {
		method,
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify( data ),
	} ).then( async res => {
		const responseData = await res.json();
		if ( res.ok ) {
			return responseData;
		}
		return { error: responseData };
	} );

export const renderSuccessMessageWithEmail = ( emailAddress, messagesEl ) => {
	const successMessge = sprintf(
		/* Translators: %s is the email address of the current user. */
		__(
			'Your payment has been processed. Thank you for your contribution! You will receive a confirmation email at %s.',
			'newspack-blocks'
		),
		emailAddress
	);
	renderMessages( [ successMessge ], messagesEl, 'success' );
};
