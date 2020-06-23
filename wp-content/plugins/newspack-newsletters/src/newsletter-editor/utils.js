/**
 * Internal dependencies
 */
import { getServiceProvider } from '../service-providers';

export const getEditPostPayload = newsletterData => {
	const { validateNewsletter } = getServiceProvider();
	return {
		meta: {
			// These meta fields do not have to be registered on the back end,
			// as they are not used there.
			newsletterValidationErrors: validateNewsletter( newsletterData ),
			newsletterData,
		},
	};
};

/**
 * Test if a string contains valid email addresses.
 *
 * @param  {string}  string String to test.
 * @return {boolean} True if it contains a valid email string.
 */
export const hasValidEmail = string => /\S+@\S+/.test( string );
