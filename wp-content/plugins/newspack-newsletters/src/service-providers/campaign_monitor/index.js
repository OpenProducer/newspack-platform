/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Spinner } from '@wordpress/components';
import { Fragment } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { ProviderSidebar, validateNewsletter } from './ProviderSidebar';

/**
 * Get config used to fetch newsletter data.
 * Should return apiFetch utility config:
 * https://www.npmjs.com/package/@wordpress/api-fetch
 *
 * @param {Object} object data to contruct the config.
 * @return {Object} Config fetching.
 */
const getFetchDataConfig = ( { postId } ) => ( {
	path: `/newspack-newsletters/v1/campaign_monitor/${ postId }/retrieve`,
} );

/**
 * A function to render additional info in the pre-send confirmation modal.
 * Can return null if no additional info is to be presented.
 *
 * @param {Object} newsletterData the data returned by getFetchDataConfig handler
 * @return {any} A React component
 */
const renderPreSendInfo = ( newsletterData = {} ) => {
	const { list_id, lists, segment_id, segments, send_mode } = newsletterData;
	let sendToName = null;

	if ( ! send_mode ) {
		return <Spinner />;
	}

	// Get the list name if in list mode.
	if ( 'list' === send_mode && list_id ) {
		const list = lists.find( thisList => list_id === thisList.ListID );

		if ( list ) {
			sendToName = list.Name;
		}
	}

	// Get the segment name if in segment mode.
	if ( 'segment' === send_mode && segment_id ) {
		const segment = segments.find( thisSegment => segment_id === thisSegment.SegmentID );

		if ( segment ) {
			sendToName = segment.Title;
		}
	}

	if ( ! sendToName ) {
		return (
			<p>
				{ __(
					'You’re about to send a Campaign Monitor newsletter. Are you sure you want to proceed?',
					'newspack-newsletters'
				) }
			</p>
		);
	}

	return (
		<Fragment>
			<p>
				{ __(
					'You’re about to send a Campaign Monitor newsletter to the following ',
					'newspack-newsletters'
				) }
				{ send_mode + ': ' }
				<strong>{ sendToName }</strong>
			</p>
			<p>{ __( 'Are you sure you want to proceed?', 'newspack-newsletters' ) }</p>
		</Fragment>
	);
};

export default {
	validateNewsletter,
	getFetchDataConfig,
	ProviderSidebar,
	renderPreSendInfo,
};
