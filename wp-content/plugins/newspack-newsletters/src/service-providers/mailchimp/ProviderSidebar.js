/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment, useEffect, useState } from '@wordpress/element';
import {
	ExternalLink,
	SelectControl,
	Spinner,
	Notice,
	FormTokenField,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import { getListInterestsSettings, getListTags, getTagNames, getTagIds } from './utils';

const SegmentsSelection = ( {
	onUpdate,
	inFlight,
	chosenInterestId,
	availableInterests,
	chosenTags,
	availableTags,
} ) => {
	const [ segmentsData, setSegmentsData ] = useState( {
		interest_id: chosenInterestId,
		tag_ids: chosenTags,
	} );
	const updateSegmentsData = data => setSegmentsData( { ...segmentsData, ...data } );

	// Update with real data after local (optimistic) update.
	useEffect(() => {
		setSegmentsData( {
			interest_id: chosenInterestId,
			tag_ids: chosenTags,
		} );
	}, [ chosenInterestId, chosenTags.join() ]);

	const [ isInitial, setIsInitial ] = useState( true );
	useEffect(() => {
		if ( ! isInitial ) {
			onUpdate( segmentsData );
		}
		setIsInitial( false );
	}, [ segmentsData.interest_id, segmentsData.tag_ids.join() ]);

	return (
		<Fragment>
			{ availableInterests.length ? (
				<SelectControl
					label={ __( 'Groups', 'newspack-newsletters' ) }
					value={ segmentsData.interest_id }
					options={ [
						{
							label: __( '-- Select a group --', 'newspack-newsletters' ),
							value: 'no_interests',
						},
						...availableInterests,
					] }
					onChange={ interest_id => updateSegmentsData( { interest_id } ) }
					disabled={ inFlight }
				/>
			) : null }
			{ availableTags.length ? (
				<FormTokenField
					className="newspack-newsletters__mailchimp-tags"
					label={ __( 'Tags', 'newspack-newsletters' ) }
					value={ getTagNames( segmentsData.tag_ids, availableTags ) }
					suggestions={ availableTags.map( tag => tag.name ) }
					onChange={ updatedTagsNames =>
						updateSegmentsData( { tag_ids: getTagIds( updatedTagsNames, availableTags ) } )
					}
					disabled={ inFlight }
				/>
			) : null }
		</Fragment>
	);
};

const ProviderSidebar = ( {
	renderSubject,
	renderFrom,
	inFlight,
	newsletterData,
	apiFetch,
	postId,
	updateMeta,
} ) => {
	const campaign = newsletterData.campaign;
	const lists = newsletterData.lists ? newsletterData.lists.lists : [];

	const setList = listId =>
		apiFetch( {
			path: `/newspack-newsletters/v1/mailchimp/${ postId }/list/${ listId }`,
			method: 'POST',
		} );

	const updateSegments = updatedData =>
		apiFetch( {
			path: `/newspack-newsletters/v1/mailchimp/${ postId }/segments`,
			method: 'POST',
			data: updatedData,
		} );

	const setSender = ( { senderName, senderEmail } ) =>
		apiFetch( {
			path: `/newspack-newsletters/v1/mailchimp/${ postId }/sender`,
			data: {
				from_name: senderName,
				reply_to: senderEmail,
			},
			method: 'POST',
		} );

	useEffect(() => {
		if ( campaign && campaign.settings ) {
			updateMeta( {
				senderName: campaign.settings.from_name,
				senderEmail: campaign.settings.reply_to,
			} );
		}
	}, [ campaign ]);

	if ( ! campaign ) {
		return (
			<div className="newspack-newsletters__loading-data">
				{ __( 'Retrieving Mailchimp data...', 'newspack-newsletters' ) }
				<Spinner />
			</div>
		);
	}

	const { status } = campaign || {};

	if ( 'sent' === status || 'sending' === status ) {
		return (
			<Notice status="success" isDismissible={ false }>
				{ __( 'Campaign has been sent.', 'newspack-newsletters' ) }
			</Notice>
		);
	}

	const { list_id } = campaign.recipients || {};
	const list = list_id && lists.find( ( { id } ) => list_id === id );
	const { web_id: listWebId } = list || {};

	const interestSettings = getListInterestsSettings( newsletterData );
	const chosenTags = getListTags( newsletterData );

	return (
		<Fragment>
			{ renderSubject() }

			<SelectControl
				label={ __( 'To', 'newspack-newsletters' ) }
				className="newspack-newsletters__to-selectcontrol"
				value={ list_id }
				options={ [
					{
						value: null,
						label: __( '-- Select an audience --', 'newspack-newsletters' ),
					},
					...lists.map( ( { id, name } ) => ( {
						value: id,
						label: name,
					} ) ),
				] }
				onChange={ setList }
				disabled={ inFlight }
			/>
			{ listWebId && (
				<p>
					<ExternalLink href={ `https://admin.mailchimp.com/lists/members/?id=${ listWebId }` }>
						{ __( 'Manage audience', 'newspack-newsletters' ) }
					</ExternalLink>
				</p>
			) }

			<SegmentsSelection
				chosenInterestId={ interestSettings.interestValue }
				availableInterests={ interestSettings.options }
				chosenTags={ chosenTags }
				availableTags={ newsletterData.tags.filter( tag => tag.member_count > 0 ) }
				apiFetch={ apiFetch }
				inFlight={ inFlight }
				onUpdate={ updateSegments }
			/>

			{ renderFrom( { handleSenderUpdate: setSender } ) }
		</Fragment>
	);
};

export default ProviderSidebar;
