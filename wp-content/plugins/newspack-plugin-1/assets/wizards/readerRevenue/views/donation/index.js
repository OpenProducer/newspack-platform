/**
 * Donation Settings Screen
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { MoneyInput } from '../../components/';
import { ImageUpload, TextControl, ToggleControl, withWizardScreen } from '../../../../components/src';
import './style.scss';

/**
 * Donation Settings Screen Component
 */
class Donation extends Component {
	/**
	 * Render.
	 */
	render() {
		const { data, onChange } = this.props;
		const {
			name = '',
			suggestedAmounts = [ 0, 0, 0 ],
			suggestedAmountUntiered = 0,
			currencySymbol = '$',
			tiered = false,
			image = '',
		} = data;
		return (
			<div className="newspack-donations-wizard">
				<h2>{ __( 'Suggested donations' ) }</h2>
				<p className="newspack-donations-wizard__help">
					{ __(
						'Set a suggested monthly donation amount. This will provide hints to readers about how much to donate, which will increase the average donation amount.'
					) }
				</p>
				<div className="newspack-donations-wizard__use-tiered">
					<ToggleControl
						label={ __( 'Suggest low, middle, and high tiers for monthly donations' ) }
						checked={ tiered }
						onChange={ tiered => onChange( { ...data, tiered } ) }
					/>
				</div>
				{ tiered && (
					<div className="newspack-donations-wizard__tier-suggested-prices">
						<MoneyInput
							currencySymbol={ currencySymbol }
							label={ __( 'Low-tier' ) }
							value={ suggestedAmounts[ 0 ] }
							onChange={ value =>
								onChange( { ...data, suggestedAmounts: { ...suggestedAmounts, [ 0 ]: value } } )
							}
						/>
						<MoneyInput
							currencySymbol={ currencySymbol }
							label={ __( 'Mid-tier' ) }
							value={ suggestedAmounts[ 1 ] }
							onChange={ value =>
								onChange( { ...data, suggestedAmounts: { ...suggestedAmounts, [ 1 ]: value } } )
							}
						/>
						<MoneyInput
							currencySymbol={ currencySymbol }
							label={ __( 'High-tier' ) }
							value={ suggestedAmounts[ 2 ] }
							onChange={ value =>
								onChange( { ...data, suggestedAmounts: { ...suggestedAmounts, [ 2 ]: value } } )
							}
						/>
					</div>
				) }

				{ ! tiered && (
					<div className="newspack-donations-wizard__suggested-price">
						<MoneyInput
							currencySymbol={ currencySymbol }
							label={ __( 'Suggested donation amount per month' ) }
							value={ suggestedAmountUntiered }
							onChange={ suggestedAmountUntiered =>
								onChange( { ...data, suggestedAmountUntiered } )
							}
						/>
					</div>
				) }
			</div>
		);
	}
}

Donation.defaultProps = {
	data: {},
	onChange: () => null,
};

export default withWizardScreen( Donation );
