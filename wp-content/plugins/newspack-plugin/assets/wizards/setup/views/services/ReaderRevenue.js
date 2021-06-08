/**
 * External dependencies
 */
import classnames from 'classnames';
import { pick } from 'lodash';

/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { DontationAmounts } from '../../../readerRevenue/views/donation';
import { StripeKeysSettings } from '../../../readerRevenue/views/stripe-setup';

const ReaderRevenue = ( { configuration, onUpdate, className } ) => {
	useEffect( () => {
		apiFetch( { path: 'newspack/v1/wizard/newspack-reader-revenue-wizard' } ).then( response =>
			onUpdate( {
				...pick( response, [ 'donation_data', 'stripe_data', 'platform_data' ] ),
				hasLoaded: true,
			} )
		);
	}, [] );
	return (
		<div className={ classnames( className, { 'o-50': ! configuration.hasLoaded } ) }>
			{ configuration.platform_data?.platform === 'nrh' ? (
				<p>
					{ __(
						'Looks like this Newspack instance is already configured to use News Revenue Hub as the Reader Revenue platform. To edit these settings, visit the Reader Revenue section from the Newspack dashboard.',
						'newspack'
					) }
				</p>
			) : (
				<>
					<DontationAmounts
						data={ configuration.donation_data || {} }
						onChange={ donation_data => onUpdate( { donation_data } ) }
					/>
					<h2>{ __( 'Payment gateway', 'newspack' ) }</h2>
					<StripeKeysSettings
						data={ configuration.stripe_data || {} }
						onChange={ stripe_data => onUpdate( { stripe_data } ) }
					/>
				</>
			) }
		</div>
	);
};

export default ReaderRevenue;
