/**
 * Stripe Setup Screen
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';
import { ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	Card,
	CheckboxControl,
	Grid,
	TextControl,
	ToggleControl,
	withWizardScreen,
} from '../../../../components/src';

export const StripeKeysSettings = ( { data, onChange } ) => {
	const {
		testMode = false,
		publishableKey = '',
		secretKey = '',
		testPublishableKey = '',
		testSecretKey = '',
	} = data;
	return (
		<Fragment>
			<Card noBorder>
				<p className="newspack-payment-setup-screen__api-keys-instruction">
					{ __( 'Configure Stripe and enter your API keys' ) }
					{ ' – ' }
					<ExternalLink href="https://stripe.com/docs/keys#api-keys">
						{ __( 'learn how' ) }
					</ExternalLink>
				</p>
				<CheckboxControl
					label={ __( 'Use Stripe in test mode' ) }
					checked={ testMode }
					onChange={ _testMode => onChange( { ...data, testMode: _testMode } ) }
					tooltip="Test mode will not capture real payments. Use it for testing your purchase flow."
				/>
			</Card>
			<Grid noMargin>
				{ testMode ? (
					<Fragment>
						<TextControl
							type="password"
							value={ testPublishableKey }
							label={ __( 'Test Publishable Key' ) }
							onChange={ _testPublishableKey =>
								onChange( { ...data, testPublishableKey: _testPublishableKey } )
							}
						/>
						<TextControl
							type="password"
							value={ testSecretKey }
							label={ __( 'Test Secret Key' ) }
							onChange={ _testSecretKey => onChange( { ...data, testSecretKey: _testSecretKey } ) }
						/>
					</Fragment>
				) : (
					<Fragment>
						<TextControl
							type="password"
							value={ publishableKey }
							label={ __( 'Publishable Key' ) }
							onChange={ _publishableKey =>
								onChange( { ...data, publishableKey: _publishableKey } )
							}
						/>
						<TextControl
							type="password"
							value={ secretKey }
							label={ __( 'Secret Key' ) }
							onChange={ _secretKey => onChange( { ...data, secretKey: _secretKey } ) }
						/>
					</Fragment>
				) }
			</Grid>
		</Fragment>
	);
};

/**
 * Stripe Setup Screen Component
 */
class StripeSetup extends Component {
	/**
	 * Render.
	 */
	render() {
		const { data, onChange } = this.props;
		return (
			<Fragment>
				<Grid>
					<ToggleControl
						label={ __( 'Enable Stripe' ) }
						checked={ data.enabled }
						onChange={ _enabled => onChange( { ...data, enabled: _enabled } ) }
					/>
				</Grid>
				{ data.enabled ? (
					<StripeKeysSettings data={ data } onChange={ onChange } />
				) : (
					<Grid>
						<p className="newspack-payment-setup-screen__info">
							{ __( 'Other gateways can be enabled and set up in the ' ) }
							<ExternalLink href="/wp-admin/admin.php?page=wc-settings&tab=checkout">
								{ __( 'WooCommerce payment gateway settings' ) }
							</ExternalLink>
						</p>
					</Grid>
				) }
			</Fragment>
		);
	}
}

StripeSetup.defaultProps = {
	data: {},
	onChange: () => null,
};

export default withWizardScreen( StripeSetup );
