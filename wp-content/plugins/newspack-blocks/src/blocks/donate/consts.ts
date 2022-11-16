/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import type { DonationFrequencySlug } from './types';

export const FREQUENCIES: { [ Key in DonationFrequencySlug as string ]: string } = {
	once: __( 'One-time', 'newspack-blocks' ),
	month: __( 'Monthly', 'newspack-blocks' ),
	year: __( 'Annually', 'newspack-blocks' ),
};

export const FREQUENCY_SLUGS: DonationFrequencySlug[] = Object.keys(
	FREQUENCIES
) as DonationFrequencySlug[];
