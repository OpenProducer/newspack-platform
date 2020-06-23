/**
 * WordPress dependencies
 */
import { sprintf, _n } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { get, find } from 'lodash';

/**
 * Get interest settings for a Mailchimp campaign.
 * An interest is a subset of a list.
 */
export const getListInterestsSettings = ( {
	campaign,
	interest_categories: interestCategories,
} ) => {
	if (
		! interestCategories ||
		! interestCategories.categories ||
		! interestCategories.categories.length
	) {
		return;
	}
	const options = interestCategories.categories.reduce( ( accumulator, item ) => {
		const { title, interests, id } = item;
		accumulator.push( {
			label: title,
			disabled: true,
		} );
		if ( interests && interests.interests && interests.interests.length ) {
			interests.interests.forEach( interest => {
				const subscriberCount = parseInt( interest.subscriber_count );
				const subscriberCountInfo = sprintf(
					_n( '%d subscriber', '%d subscribers', subscriberCount, 'newspack-newsletters' ),
					subscriberCount
				);

				accumulator.push( {
					label: `- ${ interest.name } (${ subscriberCountInfo })`,
					value: `interests-${ id }:${ interest.id }`,
					disabled: subscriberCount === 0,
					rawInterest: interest,
				} );
			} );
		}
		return accumulator;
	}, [] );
	const field = get( campaign, 'recipients.segment_opts.conditions.[0].field' );
	const interest_id = get( campaign, 'recipients.segment_opts.conditions.[0].value.[0]' );
	const interestValue = field && interest_id ? field + ':' + interest_id : 0;

	return { options, interestValue, setInterest: find( options, [ 'value', interestValue ] ) };
};
