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
	CheckboxControl,
	TextControl,
	ToggleControl,
	withWizardScreen,
} from '../../../../components/src';
import './style.scss';

/**
 * Stripe Setup Screen Component
 */
class StripeSetup extends Component {
	/**
	 * Render.
	 */
	render() {
		const { data, onChange } = this.props;
		const {
			enabled = false,
			testMode = false,
			publishableKey = '',
			secretKey = '',
			testPublishableKey = '',
			testSecretKey = '',
		} = data;
		return (
			<div className="newspack-payment-setup-screen">
				<ToggleControl
					label={ __( 'Enable Stripe' ) }
					checked={ enabled }
					onChange={ _enabled => onChange( { ...data, enabled: _enabled } ) }
				/>
				{ enabled && (
					<Fragment>
						<h2 className="newspack-payment-setup-screen__settings-heading">
							{ __( 'Stripe settings' ) }
						</h2>
						<CheckboxControl
							label={ __( 'Use Stripe in test mode' ) }
							checked={ testMode }
							onChange={ _testMode => onChange( { ...data, testMode: _testMode } ) }
							tooltip="Test mode will not capture real payments. Use it for testing your purchase flow."
						/>
						<div className="newspack-payment-setup-screen__api-keys-heading">
							<p className="newspack-payment-setup-screen__api-keys-instruction">
								{ __( 'Get your API keys from your Stripe account.' ) }{' '}
								<ExternalLink href="https://stripe.com/docs/keys#api-keys">
									{ __( 'Learn how' ) }
								</ExternalLink>
							</p>

							{ testMode && (
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
										onChange={ _testSecretKey =>
											onChange( { ...data, testSecretKey: _testSecretKey } )
										}
									/>
								</Fragment>
							) }
							{ ! testMode && (
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
						</div>
					</Fragment>
				) }
				{ ! enabled && (
					<p className="newspack-payment-setup-screen__info">
						{ __( 'Other gateways can be enabled and set up in the ' ) }
						<ExternalLink href="/wp-admin/admin.php?page=wc-settings&tab=checkout">
							{ __( 'WooCommerce payment gateway settings' ) }
						</ExternalLink>
					</p>
				) }
			</div>
		);
	}
}

StripeSetup.defaultProps = {
	data: {},
	onChange: () => null,
};

export default withWizardScreen( StripeSetup );
