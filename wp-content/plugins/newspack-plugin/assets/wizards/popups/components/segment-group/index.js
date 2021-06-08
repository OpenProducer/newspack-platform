/**
 * Segment group component.
 */

import cookies from 'js-cookie';

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useState, Fragment } from '@wordpress/element';
import { Icon, plus } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import { Button, Card, Modal } from '../../../../components/src';
import SegmentationPreview from '../segmentation-preview';
import PromptActionCard from '../prompt-action-card';
import {
	descriptionForPopup,
	descriptionForSegment,
	getCardClassName,
	getFavoriteCategoryNames,
	warningForPopup,
} from '../../utils';

import {
	iconInline,
	iconCenterOverlay,
	iconTopOverlay,
	iconBottomOverlay,
	iconAboveHeader,
	iconManualPlacement,
	iconPreview,
} from './icons';
import './style.scss';

const addNewURL = ( placement, campaignId, segmentId ) => {
	const base = '/wp-admin/post-new.php?post_type=newspack_popups_cpt&';
	const params = [];
	if ( placement ) {
		params.push( `placement=${ placement }` );
	}
	if ( +campaignId > 0 ) {
		params.push( `group=${ campaignId }` );
	}
	if ( segmentId ) {
		params.push( `segment=${ segmentId }` );
	}
	return base + params.join( '&' );
};

const removeCIDCookie = () => {
	if ( newspack_aux_data.popups_cookie_name ) {
		// Remove cookies for all possible domains.
		window.location.host
			.split( '.' )
			.reduce( ( acc, _, i, arr ) => {
				acc.push( arr.slice( -( i + 1 ) ).join( '.' ) );
				return acc;
			}, [] )
			.map( domain =>
				cookies.remove( newspack_aux_data.popups_cookie_name, {
					domain: `.${ domain }`,
				} )
			);
	}
};

const SegmentGroup = props => {
	const { campaignData, campaignId, segment } = props;
	const [ modalVisible, setModalVisible ] = useState( false );
	const [ categories, setCategories ] = useState( [] );
	const { label, id, prompts } = segment;
	const campaignToPreview = 'unassigned' !== campaignId ? parseInt( campaignId ) : -1;

	useEffect( () => {
		updateCategories();
	}, [ segment ] );

	const updateCategories = async () => {
		if ( 0 < segment.configuration?.favorite_categories?.length ) {
			setCategories( await getFavoriteCategoryNames( segment.configuration.favorite_categories ) );
		}
	};

	let emptySegmentText;
	if ( 'unassigned' === campaignId ) {
		emptySegmentText = __( 'No unassigned prompts in this segment.', 'newspack' );
	} else if ( campaignData ) {
		emptySegmentText =
			__( 'No prompts in this segment for', 'newspack' ) + ' ' + campaignData.name + '.';
	} else {
		emptySegmentText = __( 'No active prompts in this segment.', 'newspack' );
	}
	return (
		<Card isSmall className="newspack-campaigns__segment-group__card">
			<div className="newspack-campaigns__segment-group__card__segment">
				<div className="newspack-campaigns__segment-group__card__segment-title">
					<h3>
						{ id ? (
							<Button
								href={ `#/segments/${ id }` }
								label={ __( 'Edit Segment ', 'newspack' ) }
								isLink
								showTooltip
								tooltipPosition="bottom center"
							>
								{ __( 'Segment: ', 'newspack' ) }
								{ label }
							</Button>
						) : (
							label
						) }
					</h3>
					<span className="newspack-campaigns__segment-group__description">
						{ id
							? descriptionForSegment( segment, categories )
							: __( 'All readers, regardless of segment', 'newspack' ) }
					</span>
				</div>
				<div className="newspack-campaigns__segment-group__card__segment-actions">
					<SegmentationPreview
						campaign={ campaignId ? campaignToPreview : false }
						segment={ id }
						showUnpublished={ !! campaignId } // Only if previewing a specific campaign/group.
						onClose={ removeCIDCookie }
						renderButton={ ( { showPreview } ) => (
							<Button
								isQuaternary
								isSmall
								onClick={ () => {
									removeCIDCookie();
									if ( newspack_aux_data.popups_cookie_name ) {
										cookies.set( newspack_aux_data.popups_cookie_name, `preview-${ Date.now() }`, {
											domain: '.' + window.location.host,
										} );
									}

									showPreview();
								} }
								icon={ iconPreview }
								label={ __( 'Preview Segment', 'newspack' ) }
								tooltipPosition="bottom center"
							/>
						) }
					/>
					{ 'unassigned' !== campaignId && (
						<Fragment>
							<Button
								isSmall
								isQuaternary
								onClick={ () => setModalVisible( ! modalVisible ) }
								icon={ plus }
								label={ __( 'Add New Prompt', 'newspack' ) }
								tooltipPosition="bottom center"
							/>
							{ modalVisible && (
								<Modal
									title={ __( 'Add New Prompt', 'newspack' ) }
									className="newspack-campaigns__segment-group__add-new-button__modal"
									onRequestClose={ () => setModalVisible( false ) }
									shouldCloseOnEsc={ false }
									shouldCloseOnClickOutside={ false }
								>
									<Card buttonsCard noBorder className="newspack-card__buttons-prompt">
										<Button href={ addNewURL( null, campaignId, id ) }>
											<Icon icon={ iconInline } height={ 48 } width={ 48 } />
											{ __( 'Inline', 'newspack' ) }
										</Button>
										<Button href={ addNewURL( 'overlay-center', campaignId, id ) }>
											<Icon icon={ iconCenterOverlay } height={ 48 } width={ 48 } />
											{ __( 'Center Overlay', 'newspack' ) }
										</Button>
										<Button href={ addNewURL( 'overlay-top', campaignId, id ) }>
											<Icon icon={ iconTopOverlay } height={ 48 } width={ 48 } />
											{ __( 'Top Overlay', 'newspack' ) }
										</Button>
										<Button href={ addNewURL( 'overlay-bottom', campaignId, id ) }>
											<Icon icon={ iconBottomOverlay } height={ 48 } width={ 48 } />
											{ __( 'Bottom Overlay', 'newspack' ) }
										</Button>
										<Button href={ addNewURL( 'above-header', campaignId, id ) }>
											<Icon icon={ iconAboveHeader } height={ 48 } width={ 48 } />
											{ __( 'Above Header', 'newspack' ) }
										</Button>
										<Button href={ addNewURL( 'custom', campaignId, id ) }>
											<Icon icon={ iconManualPlacement } height={ 48 } width={ 48 } />
											{ __( 'Custom Placement', 'newspack' ) }
										</Button>
									</Card>
								</Modal>
							) }
						</Fragment>
					) }
				</div>
			</div>
			<Card noBorder className="newspack-campaigns__segment-group__action-cards">
				{ prompts.map( item => (
					<PromptActionCard
						className={ getCardClassName( item ) }
						description={ descriptionForPopup( item ) }
						warning={ warningForPopup( prompts, item ) }
						key={ item.id }
						prompt={ item }
						{ ...props }
					/>
				) ) }
			</Card>
			{ prompts.length < 1 ? <p>{ emptySegmentText }</p> : '' }
		</Card>
	);
};
export default SegmentGroup;
