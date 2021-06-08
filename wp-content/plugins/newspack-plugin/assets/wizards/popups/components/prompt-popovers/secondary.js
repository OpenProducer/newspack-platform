/**
 * Secondary PromptActionCard Popover.
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { MenuItem } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { ESCAPE } from '@wordpress/keycodes';

/**
 * Internal dependencies.
 */
import {
	CategoryAutocomplete,
	FormTokenField,
	Popover,
	SelectControl,
} from '../../../../components/src';
import { frequenciesForPopup, isOverlay, placementsForPopups } from '../../utils';
import './style.scss';

const SecondaryPromptPopover = ( {
	prompt,
	onFocusOutside,
	segments,
	setTermsForPopup,
	updatePopup,
} ) => {
	const { campaign_groups: campaignGroups, categories, id, options } = prompt;
	const { frequency, placement, selected_segment_id: selectedSegmentId } = options;
	const [ assignedSegments, setAssignedSegments ] = useState( [] );

	useEffect( () => {
		if ( selectedSegmentId ) {
			setAssignedSegments( selectedSegmentId.split( ',' ) );
		} else {
			setAssignedSegments( [] );
		}
	}, [ selectedSegmentId ] );

	return (
		<Popover
			position="bottom left"
			onFocusOutside={ onFocusOutside }
			onKeyDown={ event => ESCAPE === event.keyCode && onFocusOutside() }
			padding={ 8 }
			className="newspack-popover__campaigns__secondary-popover"
		>
			<MenuItem onClick={ () => onFocusOutside() } className="screen-reader-text">
				{ __( 'Close Popover', 'newspack' ) }
			</MenuItem>
			{ 'test' !== frequency && (
				<SelectControl
					onChange={ value => {
						updatePopup( id, { frequency: value } );
						onFocusOutside();
					} }
					options={ frequenciesForPopup( prompt ) }
					value={ frequency }
					label={ __( 'Frequency', 'newspack' ) }
				/>
			) }
			<SelectControl
				onChange={ value => {
					updatePopup( id, { placement: value } );
					onFocusOutside();
				} }
				options={ placementsForPopups( prompt ) }
				value={ placement }
				label={ isOverlay( prompt ) ? __( 'Position' ) : __( 'Placement' ) }
			/>
			<FormTokenField
				value={ segments
					.filter( segment => -1 < assignedSegments.indexOf( segment.id ) )
					.map( segment => segment.name ) }
				onChange={ _segments => {
					const segmentsToAssign = segments
						.filter( segment => -1 < _segments.indexOf( segment.name ) )
						.map( segment => segment.id );
					updatePopup( id, { selected_segment_id: segmentsToAssign.join( ',' ) } );
					onFocusOutside();
				} }
				suggestions={ segments
					.filter( segment => -1 === assignedSegments.indexOf( segment.id ) )
					.map( segment => segment.name ) }
				label={ __( 'Segment', 'newspack-popups' ) }
			/>

			<CategoryAutocomplete
				value={ campaignGroups || [] }
				onChange={ tokens => setTermsForPopup( id, tokens, 'newspack_popups_taxonomy' ) }
				label={ __( 'Campaigns', 'newspack' ) }
				taxonomy="newspack_popups_taxonomy"
			/>
			<CategoryAutocomplete
				value={ categories || [] }
				onChange={ tokens => setTermsForPopup( id, tokens, 'category' ) }
				label={ __( 'Category filtering', 'newspack ' ) }
			/>
		</Popover>
	);
};
export default SecondaryPromptPopover;
