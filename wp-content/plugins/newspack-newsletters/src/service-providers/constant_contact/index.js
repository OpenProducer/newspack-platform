/**
 * WordPress dependencies
 */
import { __, sprintf, _n } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import ProviderSidebar from './ProviderSidebar';

const validateNewsletter = ( { campaign } ) => {
	const messages = [];

	if ( 'DRAFT' !== campaign.status ) {
		messages.push( __( 'Newsletter has already been sent.', 'newspack-newsletters' ) );
	}
	if ( ! campaign.sent_to_contact_lists.length ) {
		messages.push(
			__(
				'At least one Constant Contact list must be selected before publishing.',
				'newspack-newsletters'
			)
		);
	}
	return messages;
};

const getFetchDataConfig = ( { postId } ) => ( {
	path: `/newspack-newsletters/v1/constant_contact/${ postId }`,
} );

const renderPreSendInfo = newsletterData => {
	if ( ! newsletterData.campaign ) {
		return null;
	}
	let subscriberCount = 0;
	const listNames = [];
	newsletterData.lists.forEach( ( { id, name, contact_count } ) => {
		if (
			newsletterData.campaign.sent_to_contact_lists.some(
				( { id: usedListId } ) => usedListId === id
			)
		) {
			listNames.push( name );
			subscriberCount += contact_count;
		}
	} );

	return (
		<p>
			{ __( "You're about to send a newsletter to:", 'newspack-newsletters' ) }
			<br />
			<strong>{ listNames.join( ', ' ) }</strong>
			<br />
			<strong>
				{ sprintf(
					_n( '%d subscriber', '%d subscribers', subscriberCount, 'newspack-newsletters' ),
					subscriberCount
				) }
			</strong>
		</p>
	);
};

export default {
	validateNewsletter,
	getFetchDataConfig,
	ProviderSidebar,
	renderPreSendInfo,
};
