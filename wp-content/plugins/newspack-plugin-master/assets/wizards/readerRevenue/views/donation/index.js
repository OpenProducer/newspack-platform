/**
 * Donation Settings Screen
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';

/**
 * Material UI dependencies.
 */
import EditIcon from '@material-ui/icons/Edit';

/**
 * Internal dependencies.
 */
import { MoneyInput } from '../../components/';
import { Handoff, Notice, ToggleControl, withWizardScreen } from '../../../../components/src';
import './style.scss';

/**
 * Donation Settings Screen Component
 */
class Donation extends Component {
	/**
	 * Render.
	 */
	render() {
		const { data, onChange, donationPage } = this.props;
		const {
			suggestedAmounts = [ 0, 0, 0 ],
			suggestedAmountUntiered = 0,
			currencySymbol = '$',
			tiered = false,
		} = data;
		return (
			<div className="newspack-donations-wizard">
				{ donationPage && (
					<Fragment>
						<h2>{ __( 'Donations landing page' ) }</h2>
						{ 'publish' !== donationPage.status && (
							<Notice
								isError
								noticeText={ __(
									"Your donations landing page has been created, but is not yet published. You can now edit it and publish when you're ready."
								) }
							/>
						) }
						{ 'publish' === donationPage.status && (
							<Notice
								isSuccess
								noticeText={ __( 'Your donations landing page is set up and published.' ) }
							/>
						) }
						<div className="newspack-donations-wizard__edit-page">
							<Handoff
								plugin="woocommerce"
								editLink={ donationPage.editUrl }
								isTertiary
								isSmall
								icon={ <EditIcon /> }
								showOnBlockEditor
							>
								{ __( 'Edit Page' ) }
							</Handoff>
						</div>
						<hr />
					</Fragment>
				) }
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
						onChange={ _tiered => onChange( { ...data, tiered: _tiered } ) }
					/>
				</div>
				{ tiered && (
					<div className="newspack-donations-wizard__tier-suggested-prices">
						<MoneyInput
							currencySymbol={ currencySymbol }
							label={ __( 'Low-tier' ) }
							value={ suggestedAmounts[ 0 ] }
							onChange={ value =>
								onChange( { ...data, suggestedAmounts: { ...suggestedAmounts, 0: value } } )
							}
						/>
						<MoneyInput
							currencySymbol={ currencySymbol }
							label={ __( 'Mid-tier' ) }
							value={ suggestedAmounts[ 1 ] }
							onChange={ value =>
								onChange( { ...data, suggestedAmounts: { ...suggestedAmounts, 1: value } } )
							}
						/>
						<MoneyInput
							currencySymbol={ currencySymbol }
							label={ __( 'High-tier' ) }
							value={ suggestedAmounts[ 2 ] }
							onChange={ value =>
								onChange( { ...data, suggestedAmounts: { ...suggestedAmounts, 2: value } } )
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
							onChange={ _suggestedAmountUntiered =>
								onChange( { ...data, suggestedAmountUntiered: _suggestedAmountUntiered } )
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
