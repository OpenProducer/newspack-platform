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
export const icon = (
	<SVG xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
		<Path d="M7 5.5h10V4H7zM17 20H7v-1.5h10zM17.75 9.75l-11.5 4.5v-4.5z" />
		<Path
			fillRule="evenodd"
			clipRule="evenodd"
			d="M20 9.5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2h12a2 2 0 002-2zM18 9H6a.5.5 0 00-.5.5v5a.5.5 0 00.5.5h12a.5.5 0 00.5-.5v-5A.5.5 0 0018 9z"
		/>
	</SVG>
);

export const settings = {
	title,
	icon: {
		src: icon,
		foreground: '#36f',
	},
	category: getCategories().some( ( { slug } ) => slug === 'newspack' ) ? 'newspack' : 'common',
	keywords: [ __( 'ad' ), __( 'advert' ), __( 'ads' ) ],
	description: (
		<Fragment>
			<p>{ __( 'A block for displaying ad inventory.', 'newspack' ) }</p>
			<ExternalLink href="/wp-admin/admin.php?page=newspack-advertising-wizard#/google_ad_manager">
				{ __( 'Manage ad units', 'newspack' ) }
			</ExternalLink>
		</Fragment>
	),
	attributes: {
		activeAd: {
			type: 'string',
		},
	},
	supports: {
		html: false,
		align: [ 'left', 'center', 'right', 'wide', 'full' ],
	},
	edit,
	save: () => null, // to use view.php
};
