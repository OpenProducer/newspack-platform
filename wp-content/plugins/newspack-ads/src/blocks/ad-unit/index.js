/**
 * WordPress dependencies
 */
import { ExternalLink, Path, SVG } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { getCategories } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import edit from './edit';

/**
 * Style dependencies - will load in editor
 */
import './editor.scss';

export const name = 'ad-unit';
export const title = __( 'Ad Unit' );

/* From https://material.io/tools/icons */
export const icon = (
	<SVG xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
		<Path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 9H3V5h9v7z"/>
	</SVG>
);
export const settings = {
	title,
	icon,
	category: getCategories().some( ( { slug } ) => slug === 'newspack' ) ? 'newspack' : 'common',
	keywords: [ __( 'ad' ), __( 'advert' ), __( 'ads' ) ],
	description: (
		<Fragment>
			<p>{ __( 'A block for displaying ad inventory.', 'newspack' ) }</p>
			<ExternalLink href="/wp-admin/admin.php?page=newspack-advertising-wizard#/google_ad_manager">{ __( 'Manage ad units', 'newspack' ) }</ExternalLink>
		</Fragment>
	),
	attributes: {
		activeAd: {
			type: 'string',
		}
	},
	supports: {
		html: false,
		align: [
			'left',
			'center',
			'right',
		],
	},
	edit,
	save: () => null, // to use view.php
};
