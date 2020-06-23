/**
 * WordPress dependencies
 */
import { __, sprintf, _n } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';

/**
 * External dependencies
 */
import { find } from 'lodash';

/**
 * Internal dependencies
 */
import ProviderSidebar from './ProviderSidebar';
import { getListInterestsSettings } from './utils';

const validateNewsletter = ( { campaign } ) => {
	const { recipients, settings, status } = campaign || {};
	const { list_id: listId } = recipients || {};
	const { from_name: senderName, reply_to: senderEmail } = settings || {};

	const messages = [];
	if ( 'sent' === status || 'sending' === status ) {
		messages.push( __( 'Newsletter has already been sent.', 'newspack-newsletters' ) );
	}
	if ( ! listId ) {
		messages.push(
			__( 'A Mailchimp list must be selected before publishing.', 'newspack-newsletters' )
		);
	}
	if ( ! senderName || senderName.length < 1 ) {
		messages.push( __( 'Sender name must be set.', 'newspack-newsletters' ) );
	}
	if ( ! senderEmail || senderEmail.length < 1 ) {
		messages.push( __( 'Sender email must be set.', 'newspack-newsletters' ) );
	}

	return messages;
};

const getFetchDataConfig = ( { postId } ) => ( {
	path: `/newspack-newsletters/v1/mailchimp/${ postId }`,
} );

const renderPreSendInfo = newsletterData => {
	if ( ! newsletterData.campaign ) {
		return null;
	}
	let listData;
	if ( newsletterData.campaign && newsletterData.lists ) {
		const list = find( newsletterData.lists.lists, [
			'id',
			newsletterData.campaign.recipients.list_id,
		] );
		const interestSettings = getListInterestsSettings( newsletterData );

		if ( list ) {
			listData = { name: list.name, subscribers: parseInt( list.stats.member_count ) };
			if ( interestSettings && interestSettings.setInterest ) {
				listData.groupName = interestSettings.setInterest.rawInterest.name;
				listData.subscribers = parseInt(
					interestSettings.setInterest.rawInterest.subscriber_count
				);
			}
		}
	}

	return (
		<p>
			{ __( "You're about to send a newsletter to:", 'newspack-newsletters' ) }
			<br />
			<strong>{ listData.name }</strong>
			<br />
			{ listData.groupName && (
				<Fragment>
					{ __( 'Group:', 'newspack-newsletters' ) } <strong>{ listData.groupName }</strong>
					<br />
				</Fragment>
			) }
			<strong>
				{ sprintf(
					_n( '%d subscriber', '%d subscribers', listData.subscribers, 'newspack-newsletters' ),
					listData.subscribers
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
