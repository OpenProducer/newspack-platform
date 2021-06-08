/**
 * Campaign management screen.
 */

/**
 * WordPress dependencies.
 */
import { useContext, useEffect, useRef, useState, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ENTER } from '@wordpress/keycodes';
import { moreVertical } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import {
	withWizardScreen,
	Button,
	Card,
	CustomSelectControl,
	Modal,
	Router,
	TextControl,
} from '../../../../components/src';
import CampaignManagementPopover from '../../components/campaign-management-popover';
import SegmentGroup from '../../components/segment-group';
import { dataForCampaignId } from '../../utils';
import { CampaignsContext } from '../../contexts';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

const { useHistory } = Router;

const MODAL_TYPE_DUPLICATE = 1;
const MODAL_TYPE_RENAME = 2;
const MODAL_TYPE_NEW = 3;

const modalTitle = modalType => {
	if ( MODAL_TYPE_RENAME === modalType ) {
		return __( 'Rename Campaign', 'newspack' );
	} else if ( MODAL_TYPE_DUPLICATE === modalType ) {
		return __( 'Duplicate Campaign', 'newspack' );
	}
	return __( 'New Campaign', 'newspack' );
};

const modalButton = modalType => {
	if ( MODAL_TYPE_RENAME === modalType ) {
		return __( 'Rename', 'newspack' );
	} else if ( MODAL_TYPE_DUPLICATE === modalType ) {
		return __( 'Duplicate', 'newspack' );
	}
	return __( 'Add', 'newspack' );
};

const filterByCampaign = ( prompts, campaignId ) => {
	if ( 'active' === campaignId || ! campaignId ) {
		return prompts.filter( ( { status } ) => 'publish' === status );
	}
	if ( 'unassigned' === campaignId ) {
		return prompts.filter(
			( { campaign_groups: campaigns } ) => ! campaigns || ! campaigns.length
		);
	}
	return prompts.filter(
		( { campaign_groups: campaigns } ) =>
			campaigns && campaigns.find( term => +term.term_id === +campaignId )
	);
};

const groupBySegment = ( segments, prompts ) => {
	const grouped = [];
	grouped.push(
		...segments.map( ( { name: label, id, configuration } ) => ( {
			label,
			id,
			configuration,
			prompts: prompts.filter( ( { options: { selected_segment_id: _segments } } ) => {
				return _segments ? -1 < _segments.split( ',' ).indexOf( id ) : false;
			} ),
		} ) )
	);
	grouped.push( {
		label: __( 'Everyone', 'newspack' ),
		id: '',
		prompts: prompts.filter( ( { options: { selected_segment_id: segment } } ) => ! segment ),
		configuration: {},
	} );
	return grouped;
};

/**
 * Campaign management screen.
 */
const Campaigns = props => {
	const {
		campaignId,
		campaigns = [],
		manageCampaignGroup,
		segments,
		createCampaignGroup,
		duplicateCampaignGroup,
		deleteCampaignGroup,
		archiveCampaignGroup,
		renameCampaignGroup,
	} = props;

	const modalTextRef = useRef( null );

	const [ popoverVisible, setPopoverVisible ] = useState();
	const [ modalVisible, setModalVisible ] = useState();
	const [ modalType, setModalType ] = useState();
	const [ campaignName, setCampaignName ] = useState();

	const allPrompts = useContext( CampaignsContext );
	const prompts = filterByCampaign( allPrompts, campaignId );
	const hasUnassigned = filterByCampaign( allPrompts, 'unassigned' ).length;

	useEffect( () => {
		if ( modalVisible ) {
			modalTextRef.current.querySelector( 'input' ).focus();
		}
	}, [ modalVisible ] );

	const history = useHistory();

	const submitModal = modalText => {
		if ( MODAL_TYPE_NEW === modalType ) {
			createCampaignGroup( modalText );
		} else if ( MODAL_TYPE_RENAME === modalType ) {
			renameCampaignGroup( campaignId, modalText );
		} else if ( MODAL_TYPE_DUPLICATE === modalType ) {
			duplicateCampaignGroup( campaignId, modalText );
		}
		setModalVisible( false );
	};

	const activeCampaigns = campaigns.filter( ( { status } ) => 'archive' !== status );
	const archivedCampaigns = campaigns.filter( ( { status } ) => 'archive' === status );
	const campaignData = dataForCampaignId( campaignId, campaigns );

	const valueForCampaignId = id => {
		if ( 'unassigned' === id ) {
			return {
				key: 'unassigned',
				name: __( 'Unassigned Prompts', 'newspack' ),
			};
		}

		const data = dataForCampaignId( id, campaigns );

		if ( data ) {
			return {
				key: data.term_id,
				name:
					'archive' === data.status ? data.name + ' ' + __( '(archived)', 'newspack' ) : data.name,
			};
		}
		return {
			key: 'active',
			name: __( 'Active Prompts', 'newspack' ),
		};
	};
	const selectValue = valueForCampaignId( campaignId );
	return (
		<Fragment>
			<Card headerActions noBorder>
				<div className="newspack-campaigns__campaign-group__filter-group-actions">
					<CustomSelectControl
						label={ __( 'Campaigns', 'newspack' ) }
						options={ [
							{
								key: 'active',
								name: __( 'Active Prompts', 'newspack' ),
								className: selectValue.key === 'active' && 'is-selected',
							},
							...( hasUnassigned
								? [
										{
											key: 'unassigned',
											name: __( 'Unassigned Prompts', 'newspack' ),
											className: selectValue.key === 'unassigned' && 'is-selected',
										},
								  ]
								: [] ),
							...( activeCampaigns.length
								? [
										{
											key: 'header-campaigns',
											name: __( 'Campaigns', 'newspack' ),
											className: 'is-header',
										},
								  ]
								: [] ),
							...activeCampaigns.map( ( { term_id: id, name } ) => ( {
								key: id,
								name,
								className: classnames(
									'newspack-campaigns__campaign-group__select-control-group-item',
									+selectValue.key === +id && 'is-selected'
								),
							} ) ),
							...( archivedCampaigns.length
								? [
										{
											key: 'header-archived-campaigns',
											name: __( 'Archived Campaigns', 'newspack' ),
											className: 'is-header',
										},
								  ]
								: [] ),
							...archivedCampaigns.map( ( { term_id: id, name } ) => ( {
								key: id,
								name,
								className: classnames(
									'newspack-campaigns__campaign-group__select-control-group-item',
									+selectValue.key === +id && 'is-selected'
								),
							} ) ),
						] }
						onChange={ ( { selectedItem: { key } } ) =>
							'active' === key
								? history.push( '/campaigns' )
								: history.push( `/campaigns/${ key }` )
						}
						value={ selectValue }
						hideLabelFromVision={ true }
					/>
					{ campaignData && (
						<div className="newspack-campaigns__campaign-group__filter-group-actions__button">
							<Button
								isQuaternary
								isSmall
								className={ popoverVisible && 'popover-active' }
								onClick={ () => setPopoverVisible( ! popoverVisible ) }
								icon={ moreVertical }
								label={ __( 'Actions', 'newspack' ) }
								tooltipPosition="bottom center"
							/>
							{ popoverVisible && (
								<CampaignManagementPopover
									dismiss={ () => setPopoverVisible( false ) }
									isArchive={ campaignData && 'archive' === campaignData.status }
									onActivate={ () => manageCampaignGroup( prompts ) }
									onArchive={ () => archiveCampaignGroup( campaignId, true ) }
									onDeactivate={ () => manageCampaignGroup( prompts, 'DELETE' ) }
									onDelete={ () => deleteCampaignGroup( campaignId ) }
									onDuplicate={ () => {
										setModalVisible( true );
										setCampaignName( '' );
										setModalType( MODAL_TYPE_DUPLICATE );
									} }
									onRename={ () => {
										setCampaignName( campaignData.name );
										setModalVisible( true );
										setModalType( MODAL_TYPE_RENAME );
									} }
									onUnarchive={ () => archiveCampaignGroup( campaignId, false ) }
									hasPrompts={ prompts.length > 0 }
									hasPublished={ prompts.some( ( { status } ) => 'publish' === status ) }
								/>
							) }
						</div>
					) }
				</div>
				<div className="newspack-campaigns__campaign-group__add-new-button">
					<Button
						isPrimary
						isSmall
						onClick={ () => {
							setModalVisible( ! modalVisible );
							setCampaignName( '' );
							setModalType( MODAL_TYPE_NEW );
						} }
					>
						{ __( 'Add New Campaign', 'newspack' ) }
					</Button>
					{ modalVisible && (
						<Modal
							title={ modalTitle( modalType ) }
							isDismissible={ false }
							className="newspack-campaigns__campaign-group__add-new-button__modal"
						>
							<div ref={ modalTextRef }>
								<TextControl
									placeholder={ __( 'Campaign Name', 'newspack' ) }
									onChange={ setCampaignName }
									label={ __( 'Campaign Name', 'newspack' ) }
									hideLabelFromVision={ true }
									value={ campaignName }
									onKeyDown={ event => {
										if ( ENTER === event.keyCode && '' !== campaignName ) {
											event.preventDefault();
											submitModal( campaignName );
										}
									} }
								/>
							</div>
							<Card buttonsCard noBorder>
								<Button
									isSecondary
									onClick={ () => {
										setModalVisible( false );
									} }
								>
									{ __( 'Cancel', 'newspack' ) }
								</Button>
								<Button
									isPrimary
									disabled={ ! campaignName }
									onClick={ () => submitModal( campaignName ) }
								>
									{ modalButton( modalType ) }
								</Button>
							</Card>
						</Modal>
					) }
				</div>
			</Card>
			{ groupBySegment( segments, prompts ).map( ( segment, index ) => (
				<SegmentGroup
					key={ index }
					segment={ segment }
					campaignId={ campaignId }
					campaignData={ campaignData }
					{ ...props }
				/>
			) ) }
		</Fragment>
	);
};
export default withWizardScreen( Campaigns );
