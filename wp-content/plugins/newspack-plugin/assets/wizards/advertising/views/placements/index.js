/**
 * Ad Services view.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, SectionHeader, withWizardScreen } from '../../../../components/src';
import AdPicker from './ad-picker';

/**
 * Advertising management screen.
 */
class Placements extends Component {
	adUnitsForSelect = adUnits => {
		return [
			{
				label: __( 'Select an ad unit', 'newspack' ),
				value: null,
			},
			...Object.values( adUnits ).map( adUnit => {
				return {
					label: adUnit.name,
					value: adUnit.id,
				};
			} ),
		];
	};

	adServicesForSelect = services => {
		return [
			{
				label: __( 'Select an ad provider', 'newspack' ),
				value: null,
			},
			...Object.keys( services ).map( key => {
				return {
					label: services[ key ].label,
					value: key,
				};
			} ),
		];
	};

	/**
	 * Render.
	 */
	render() {
		const { togglePlacement, placements, adUnits, services, onChange } = this.props;
		const {
			global_above_header,
			global_below_header,
			global_above_footer,
			archives,
			search_results,
			sticky,
		} = placements;

		return (
			<Fragment>
				<SectionHeader
					title={ __( 'Pre-defined ad placements', 'newspack' ) }
					description={ () => (
						<>
							{ __(
								'Define global advertising placements to serve ad units on your site',
								'newspack'
							) }
							<br />
							{ __(
								'Enable the individual pre-defined ad placements to select which ads to serve',
								'newspack'
							) }
						</>
					) }
				/>
				<ActionCard
					isMedium
					title={ __( 'Global: Above Header', 'newspack' ) }
					description={ __( 'Choose an ad unit to display above the header', 'newspack' ) }
					toggleChecked={ global_above_header && global_above_header.enabled }
					hasGreyHeader={ global_above_header && global_above_header.enabled }
					toggleOnChange={ value => togglePlacement( 'global_above_header', value ) }
				>
					{ global_above_header && global_above_header.enabled ? (
						<AdPicker
							adUnits={ adUnits }
							services={ services }
							value={ global_above_header }
							onChange={ value => onChange( 'global_above_header', value ) }
						/>
					) : null }
				</ActionCard>
				<ActionCard
					isMedium
					title={ __( 'Global: Below Header', 'newspack' ) }
					description={ __( 'Choose an ad unit to display below the header', 'newspack' ) }
					toggleChecked={ global_below_header && global_below_header.enabled }
					hasGreyHeader={ global_below_header && global_below_header.enabled }
					toggleOnChange={ value => togglePlacement( 'global_below_header', value ) }
				>
					{ global_below_header && global_below_header.enabled ? (
						<AdPicker
							adUnits={ adUnits }
							services={ services }
							value={ global_below_header }
							onChange={ value => onChange( 'global_below_header', value ) }
						/>
					) : null }
				</ActionCard>
				<ActionCard
					isMedium
					title={ __( 'Global: Above Footer', 'newspack' ) }
					description={ __( 'Choose an ad unit to display above the footer', 'newspack' ) }
					toggleChecked={ global_above_footer && global_above_footer.enabled }
					hasGreyHeader={ global_above_footer && global_above_footer.enabled }
					toggleOnChange={ value => togglePlacement( 'global_above_footer', value ) }
				>
					{ global_above_footer && global_above_footer.enabled ? (
						<AdPicker
							adUnits={ adUnits }
							services={ services }
							value={ global_above_footer }
							onChange={ value => onChange( 'global_above_footer', value ) }
						/>
					) : null }
				</ActionCard>
				<ActionCard
					isMedium
					title={ __( 'Archives', 'newspack' ) }
					description={ __( 'Choose an ad unit to display on your archives', 'newspack' ) }
					toggleChecked={ archives && archives.enabled }
					hasGreyHeader={ archives && archives.enabled }
					toggleOnChange={ value => togglePlacement( 'archives', value ) }
				>
					{ archives && archives.enabled ? (
						<AdPicker
							adUnits={ adUnits }
							services={ services }
							value={ archives }
							onChange={ value => onChange( 'archives', value ) }
						/>
					) : null }
				</ActionCard>
				<ActionCard
					isMedium
					title={ __( 'Search Results', 'newspack' ) }
					description={ __( 'Choose an ad unit to display on your search results', 'newspack' ) }
					toggleChecked={ search_results && search_results.enabled }
					hasGreyHeader={ search_results && search_results.enabled }
					toggleOnChange={ value => togglePlacement( 'search_results', value ) }
				>
					{ search_results && search_results.enabled ? (
						<AdPicker
							adUnits={ adUnits }
							services={ services }
							value={ search_results }
							onChange={ value => onChange( 'search_results', value ) }
						/>
					) : null }
				</ActionCard>
				<ActionCard
					isMedium
					title={ __( 'Sticky', 'newspack' ) }
					description={ __(
						'Choose a sticky ad unit to display at the bottom of the viewport',
						'newspack'
					) }
					toggleChecked={ sticky && sticky.enabled }
					hasGreyHeader={ sticky && sticky.enabled }
					toggleOnChange={ value => togglePlacement( 'sticky', value ) }
				>
					{ sticky && sticky.enabled ? (
						<AdPicker
							adUnits={ adUnits }
							services={ services }
							value={ sticky }
							onChange={ value => onChange( 'sticky', value ) }
						/>
					) : null }
				</ActionCard>
			</Fragment>
		);
	}
}

export default withWizardScreen( Placements );
