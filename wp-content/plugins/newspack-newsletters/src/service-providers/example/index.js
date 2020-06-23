/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';

/**
 * Validation utility.
 *
 * @param  {Object} object data fetched using getFetchDataConfig
 * @return {string[]} Array of validation messages. If empty, newsletter is valid.
 */
const validateNewsletter = ( { status } ) => {
	const messages = [];
	if ( 'sent' === status || 'sending' === status ) {
		messages.push( __( 'Newsletter has already been sent.', 'newspack-newsletters' ) );
	}

	return messages;
};

/**
 * Get config used to fetch newsletter data.
 * Should return apiFetch utility config:
 * https://www.npmjs.com/package/@wordpress/api-fetch
 *
 * @param {Object} object data to contruct the config.
 * @return {Object} Config fetching.
 */
const getFetchDataConfig = ( { postId } ) => ( {
	path: `/newspack-newsletters/v1/example/${ postId }`,
} );

/**
 * Component to be rendered in the sidebar panel.
 * Has full control over the panel contents rendering,
 * so that it's possible to render e.g. a loader while
 * the data is not yet available.
 *
 * @param {Object} props props
 */
const ProviderSidebar = ( {
	/**
	 * ID of the edited newsletter post.
	 */
	postId,
	/**
	 * Fetching handler. Receives config for @wordpress/api-fetch as argument.
	 */
	apiFetch,
	/**
	 * Function that renders email subject input.
	 */
	renderSubject,
	/**
	 * Function that renders from inputs - sender name and email.
	 * Has to receive an object with `handleSenderUpdate` function,
	 * which will receive a `{senderName, senderEmail}` object – so that
	 * the data can be sent to the backend.
	 */
	renderFrom,
} ) => {
	const handleSenderUpdate = ( { senderName, senderEmail } ) =>
		apiFetch( {
			path: `/newspack-newsletters/v1/example/${ postId }/settings`,
			data: {
				from_name: senderName,
				reply_to: senderEmail,
			},
			method: 'POST',
		} );

	return (
		<Fragment>
			{ renderSubject() }
			{ renderFrom( { handleSenderUpdate } ) }
		</Fragment>
	);
};

/**
 * A function to render additional info in the pre-send confirmation modal.
 * Can return null if no additional info is to be presented.
 *
 * @param {Object} newsletterData the data returned by getFetchDataConfig handler
 * @return {any} A React component
 */
const renderPreSendInfo = ( newsletterData = {} ) => (
	<p>
		{ __( 'Sending newsletter to:', 'newspack-newsletters' ) } { newsletterData.listName }
	</p>
);

export default {
	validateNewsletter,
	getFetchDataConfig,
	ProviderSidebar,
	renderPreSendInfo,
};
