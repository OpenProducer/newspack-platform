/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import {
	BaseControl,
	ExternalLink,
	RadioControl,
	SelectControl,
	Spinner,
	TextControl,
	Notice,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * Validation utility.
 *
 * @param  {Object} object data fetched using getFetchDataConfig
 * @return {string[]} Array of validation messages. If empty, newsletter is valid.
 */
export const validateNewsletter = ( { from_email, from_name, list_id, segment_id, send_mode } ) => {
	const messages = [];
	if ( ! from_email || ! from_name ) {
		messages.push( __( 'Missing required sender info.', 'newspack-newsletters' ) );
	}

	if ( ! send_mode ) {
		messages.push( __( 'Must select a send mode', 'newspack-newsletters' ) );
	}

	if ( 'list' === send_mode && ! list_id ) {
		messages.push( __( 'Must select a list when sending in list mode', 'newspack-newsletters' ) );
	}

	if ( 'segment' === send_mode && ! segment_id ) {
		messages.push(
			__( 'Must select a segment when sending in segment mode', 'newspack-newsletters' )
		);
	}

	return messages;
};

/**
 * Component to be rendered in the sidebar panel.
 * Has full control over the panel contents rendering,
 * so that it's possible to render e.g. a loader while
 * the data is not yet available.
 *
 * @param {Object} props props
 */
const ProviderSidebarComponent = ( {
	/**
	 * ID of the edited newsletter post.
	 */
	postId,

	/**
	 * Function that renders email subject input.
	 */
	renderSubject,

	/**
	 * Whether or not the post is in a loading state.
	 */
	inFlight,

	/**
	 * Campaign Monitor data.
	 */
	cmData,

	/**
	 * Dispatcher to update post meta.
	 */
	updateMetaValue,

	/**
	 * Newsletter data from the parent components
	 */
	newsletterData,

	/**
	 * Dispatcher to display an error message in the editor.
	 */
	createErrorNotice,

	/**
	 * Current post status.
	 */
	status,
} ) => {
	const [ isLoading, setIsLoading ] = useState( false );
	const [ lists, setLists ] = useState( [] );
	const [ segments, setSegments ] = useState( [] );
	const { listId, segmentId, sendMode, senderName, senderEmail } = cmData;

	useEffect(() => {
		fetchListsAndSegments();
	}, []);

	const fetchListsAndSegments = async () => {
		setIsLoading( true );
		try {
			const response = await apiFetch( {
				path: `/newspack-newsletters/v1/campaign_monitor/${ postId }/retrieve`,
			} );

			setLists( response.lists );
			setSegments( response.segments );
		} catch ( e ) {
			createErrorNotice(
				e.message || __( 'Error retrieving campaign information.', 'newspack-newsletters' )
			);
		}
		setIsLoading( false );
	};

	useEffect(() => {
		const updatedData = {
			...newsletterData,
			lists,
			segments,
			send_mode: sendMode,
			list_id: listId,
			segment_id: segmentId,
			from_email: senderEmail,
			from_name: senderName,
			campaign: true,
		};

		const messages = validateNewsletter( updatedData );

		// Send info to parent components, for send button/validation management.
		updateMetaValue( 'newsletterValidationErrors', messages );
		updateMetaValue( 'newsletterData', updatedData );
	}, [ JSON.stringify( cmData ), lists, segments, status ]);

	if ( ! inFlight && 'publish' === status ) {
		return (
			<Notice status="success" isDismissible={ false }>
				{ __( 'Campaign has been sent.', 'newspack-newsletters' ) }
			</Notice>
		);
	}

	return (
		<div className="newspack-newsletters__campaign-monitor-sidebar">
			{ renderSubject() }
			<BaseControl className="newspack-newsletters__send-mode">
				<RadioControl
					className={
						'newspack-newsletters__sendmode-radiocontrol' + ( inFlight ? ' inFlight' : '' )
					}
					label={ __( 'Send Mode', 'newspack-newsletters' ) }
					selected={ sendMode }
					onChange={ value => updateMetaValue( 'cm_send_mode', value ) }
					options={ [
						{ label: __( 'List', 'newspack-newsletters' ), value: 'list' },
						{ label: __( 'Segment', 'newspack-newsletters' ), value: 'segment' },
					] }
					disabled={ inFlight }
				/>
				{ inFlight && <Spinner /> }
			</BaseControl>
			{ 'list' === sendMode && lists && (
				<BaseControl className="newspack-newsletters__list-select">
					<SelectControl
						className="newspack-newsletters__campaign-monitor-send-to"
						label={ __( 'To', 'newspack-newsletters' ) }
						value={ listId }
						options={ [
							{
								value: '',
								label: __( '-- Select a subscriber list --', 'newspack-newsletters' ),
							},
							...lists.map( ( { ListID, Name } ) => ( {
								value: ListID,
								label: Name,
							} ) ),
						] }
						onChange={ value => updateMetaValue( 'cm_list_id', value ) }
						disabled={ isLoading }
					/>
					{ isLoading && <Spinner /> }
				</BaseControl>
			) }
			{ 'segment' === sendMode && segments && (
				<BaseControl className="newspack-newsletters__list-select">
					<SelectControl
						label={ __( 'To', 'newspack-newsletters' ) }
						className="newspack-newsletters__to-selectcontrol"
						value={ segmentId }
						options={ [
							{
								value: '',
								label: __( '-- Select a subscriber segment --', 'newspack-newsletters' ),
							},
							...segments.map( ( { SegmentID, Title } ) => ( {
								value: SegmentID,
								label: Title,
							} ) ),
						] }
						onChange={ value => updateMetaValue( 'cm_segment_id', value ) }
						disabled={ isLoading }
					/>
					{ isLoading && <Spinner /> }
				</BaseControl>
			) }
			{ sendMode && (
				<p>
					{ 'segment' === sendMode ? (
						<ExternalLink href={ 'https://help.campaignmonitor.com/list-segmentation' }>
							{ __( 'Manage segments on Campaign Monitor', 'newspack-newsletters' ) }
						</ExternalLink>
					) : (
						<ExternalLink href={ 'https://help.campaignmonitor.com/create-a-subscriber-list' }>
							{ __( 'Manage lists on Campaign Monitor', 'newspack-newsletters' ) }
						</ExternalLink>
					) }
				</p>
			) }
			<strong>{ __( 'From', 'newspack-newsletters' ) }</strong>
			<TextControl
				label={ __( 'Name', 'newspack-newsletters' ) }
				className="newspack-newsletters__name-textcontrol"
				value={ senderName }
				disabled={ inFlight }
				onChange={ value => updateMetaValue( 'cm_from_name', value ) }
			/>
			<TextControl
				label={ __( 'Email', 'newspack-newsletters' ) }
				className="newspack-newsletters__email-textcontrol"
				value={ senderEmail }
				type="email"
				disabled={ inFlight }
				onChange={ value => updateMetaValue( 'cm_from_email', value ) }
			/>
		</div>
	);
};

const mapStateToProps = select => {
	const { getCurrentPostAttribute, getEditedPostAttribute } = select( 'core/editor' );
	const meta = getEditedPostAttribute( 'meta' );

	return {
		cmData: {
			listId: meta.cm_list_id,
			segmentId: meta.cm_segment_id,
			sendMode: meta.cm_send_mode,
			senderName: meta.cm_from_name,
			senderEmail: meta.cm_from_email,
		},
		status: getCurrentPostAttribute( 'status' ),
	};
};

const mapDispatchToProps = dispatch => {
	const { editPost } = dispatch( 'core/editor' );
	const { createErrorNotice } = dispatch( 'core/notices' );

	return {
		updateMetaValue: ( key, value ) => editPost( { meta: { [ key ]: value } } ),
		createErrorNotice,
	};
};

export const ProviderSidebar = compose( [
	withSelect( mapStateToProps ),
	withDispatch( mapDispatchToProps ),
] )( ProviderSidebarComponent );
