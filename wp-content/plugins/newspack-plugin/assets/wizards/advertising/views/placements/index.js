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
import { ToggleGroup, withWizardScreen } from '../../../../components/src';
import AdPicker from './ad-picker';

/**
 * Advertising management screen.
 */
class Placements extends Component {
	adUnitsForSelect = adUnits => {
		return [
			{
				label: __( 'Select an ad unit' ),
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
				label: __( 'Select an ad provider' ),
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
		} = placements;
		return (
			<Fragment>
				<h2>{ __( 'Pre-defined ad placements' ) }</h2>
				<p>
					{ __(
						'Define global advertising placements to serve ad units on your site. Enable the individual pre-defined ad placements to select which ads to serve.'
					) }
				</p>
				<ToggleGroup
					title={ __( 'Global: Above Header' ) }
					description={ __( 'Choose an ad unit to display above the header' ) }
					checked={ global_above_header && global_above_header.enabled }
					onChange={ value => togglePlacement( 'global_above_header', value ) }
				>
					<AdPicker
						adUnits={ adUnits }
						services={ services }
						value={ global_above_header }
						onChange={ value => onChange( 'global_above_header', value ) }
					/>
				</ToggleGroup>
				<ToggleGroup
					title={ __( 'Global: Below Header' ) }
					description={ __( 'Choose an ad unit to display above the header' ) }
					checked={ global_below_header && global_below_header.enabled }
					onChange={ value => togglePlacement( 'global_below_header', value ) }
				>
					<AdPicker
						adUnits={ adUnits }
						services={ services }
						value={ global_below_header }
						onChange={ value => onChange( 'global_below_header', value ) }
					/>
				</ToggleGroup>
				<ToggleGroup
					title={ __( 'Global: Above Footer' ) }
					description={ __( 'Choose an ad unit to display above the header' ) }
					checked={ global_above_footer && global_above_footer.enabled }
					onChange={ value => togglePlacement( 'global_above_footer', value ) }
				>
					<AdPicker
						adUnits={ adUnits }
						services={ services }
						value={ global_above_footer }
						onChange={ value => onChange( 'global_above_footer', value ) }
					/>
				</ToggleGroup>
				<ToggleGroup
					title={ __( 'Archives' ) }
					description={ __( 'Choose an ad unit to display above the header' ) }
					checked={ archives && archives.enabled }
					onChange={ value => togglePlacement( 'archives', value ) }
				>
					<AdPicker
						adUnits={ adUnits }
						services={ services }
						value={ archives }
						onChange={ value => onChange( 'archives', value ) }
					/>
				</ToggleGroup>
				<ToggleGroup
					title={ __( 'Search Results' ) }
					description={ __( 'Choose an ad unit to display above the header' ) }
					checked={ search_results && search_results.enabled }
					onChange={ value => togglePlacement( 'search_results', value ) }
				>
					<AdPicker
						adUnits={ adUnits }
						services={ services }
						value={ search_results }
						onChange={ value => onChange( 'search_results', value ) }
					/>
				</ToggleGroup>
			</Fragment>
		);
	}
}

export default withWizardScreen( Placements );
