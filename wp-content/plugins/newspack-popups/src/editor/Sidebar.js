/**
 * Prompt display settings.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import {
	RadioControl,
	RangeControl,
	SelectControl,
	ToggleControl,
	CheckboxControl,
} from '@wordpress/components';

/**
 * External dependencies
 */
import { without } from 'lodash';

/**
 * Internal dependencies
 */
import { isCustomPlacement, getPlacementHelpMessage } from '../editor/utils';

const Sidebar = ( {
	display_title,
	hide_border,
	frequency,
	onMetaFieldChange,
	placement,
	trigger_scroll_progress,
	archive_insertion_posts_count,
	archive_insertion_is_repeating,
	trigger_delay,
	trigger_type,
	isOverlay,
	isInlinePlacement,
	archive_page_types = [],
} ) => {
	const updatePlacement = value => {
		onMetaFieldChange( 'placement', value );
		if ( ! isInlinePlacement( value ) && frequency === 'always' ) {
			onMetaFieldChange( 'frequency', 'once' );
		}
	};
	const customPlacements = window.newspack_popups_data?.custom_placements || {};
	const availableArchivePageTypes = window.newspack_popups_data?.available_archive_page_types || [];

	return (
		<Fragment>
			<RadioControl
				className="newspack-popups__prompt-type-control"
				label={ __( 'Prompt type', 'newspack-popups' ) }
				selected={ isOverlay ? 'center' : 'inline' }
				options={ [
					{ label: __( 'Inline', 'newspack-popups' ), value: 'inline' },
					{ label: __( 'Overlay', 'newspack-popups' ), value: 'center' },
				] }
				onChange={ value => updatePlacement( value ) }
			/>
			<SelectControl
				label={ isOverlay ? __( 'Position' ) : __( 'Placement' ) }
				help={ getPlacementHelpMessage(
					placement,
					trigger_scroll_progress,
					archive_insertion_posts_count,
					archive_insertion_is_repeating
				) }
				value={ placement }
				onChange={ updatePlacement }
				options={
					isOverlay
						? [
								{ value: 'center', label: __( 'Center' ) },
								{ value: 'top', label: __( 'Top' ) },
								{ value: 'bottom', label: __( 'Bottom' ) },
						  ]
						: [
								{ value: 'inline', label: __( 'In article content' ) },
								{ value: 'archives', label: __( 'In archive pages' ) },
								{ value: 'above_header', label: __( 'Above site header' ) },
								{ value: 'manual', label: __( 'Manual only', 'newspack-popups' ) },
						  ].concat(
								Object.keys( customPlacements ).map( key => ( {
									value: key,
									label: customPlacements[ key ],
								} ) )
						  )
				}
			/>
			{ isOverlay && (
				<Fragment>
					<SelectControl
						label={ __( 'Trigger' ) }
						help={ __( 'The event to trigger the prompt.', 'newspack-popups' ) }
						selected={ trigger_type }
						options={ [
							{ label: __( 'Timer' ), value: 'time' },
							{ label: __( 'Scroll Progress' ), value: 'scroll' },
						] }
						onChange={ value => onMetaFieldChange( 'trigger_type', value ) }
					/>
					{ 'time' === trigger_type && (
						<RangeControl
							label={ __( 'Delay (seconds)' ) }
							value={ trigger_delay }
							onChange={ value => onMetaFieldChange( 'trigger_delay', value ) }
							min={ 0 }
							max={ 60 }
						/>
					) }
					{ 'scroll' === trigger_type && (
						<RangeControl
							label={ __( 'Scroll Progress (percent)', 'newspack-popups' ) }
							value={ trigger_scroll_progress }
							onChange={ value => onMetaFieldChange( 'trigger_scroll_progress', value ) }
							min={ 1 }
							max={ 100 }
						/>
					) }
				</Fragment>
			) }
			{ placement === 'inline' && (
				<RangeControl
					label={ __( 'Approximate Position (in percent)', 'newspack-popups' ) }
					value={ trigger_scroll_progress }
					onChange={ value => onMetaFieldChange( 'trigger_scroll_progress', value ) }
					min={ 0 }
					max={ 100 }
				/>
			) }
			{ placement === 'archives' && (
				<Fragment>
					<RangeControl
						label={ __( 'Number of articles before prompt', 'newspack-popups' ) }
						value={ archive_insertion_posts_count }
						onChange={ value => onMetaFieldChange( 'archive_insertion_posts_count', value ) }
						min={ 1 }
						max={ 20 }
					/>

					<div className="newspack-popups__prompt-type-control">
						<legend className="components-base-control__legend">
							{ __( 'Archive Page Types', 'newspack-popups' ) }
						</legend>
						{ availableArchivePageTypes.map( ( { name, label } ) => (
							<CheckboxControl
								key={ name }
								label={ label }
								checked={ archive_page_types.indexOf( name ) > -1 }
								onChange={ isIncluded => {
									onMetaFieldChange(
										'archive_page_types',
										isIncluded
											? [ ...archive_page_types, name ]
											: without( archive_page_types, name )
									);
								} }
							/>
						) ) }
					</div>

					<ToggleControl
						label={ __( 'Repeat prompt', 'newspack-popups' ) }
						checked={ archive_insertion_is_repeating }
						onChange={ value => onMetaFieldChange( 'archive_insertion_is_repeating', value ) }
					/>
				</Fragment>
			) }
			<ToggleControl
				label={ __( 'Display Prompt Title', 'newspack-popups' ) }
				checked={ display_title }
				onChange={ value => onMetaFieldChange( 'display_title', value ) }
			/>
			{ ( placement === 'inline' || placement === 'manual' || isCustomPlacement( placement ) ) && (
				<ToggleControl
					label={ __( 'Hide Prompt Border', 'newspack-popups' ) }
					checked={ hide_border }
					onChange={ value => onMetaFieldChange( 'hide_border', value ) }
				/>
			) }
		</Fragment>
	);
};

export default Sidebar;
