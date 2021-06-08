/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';

export const OFFSETS = [
	{ label: __( 'Last 7 days', 'newspack' ), value: '7' },
	{ label: __( 'Last 14 days', 'newspack' ), value: '14' },
	{ label: __( 'Last 28 days', 'newspack' ), value: '28' },
	{ label: __( 'Last 90 days', 'newspack' ), value: '90' },
];
export const DEFAULT_OFFSET = OFFSETS[ 0 ];
