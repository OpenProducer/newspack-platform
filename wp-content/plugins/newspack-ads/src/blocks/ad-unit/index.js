/**
 * WordPress dependencies
 */
import { ExternalLink } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { getCategories } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { Icon, stretchWide } from '@wordpress/icons';

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

export const settings = {
	title,
	icon: {
		src: <Icon icon={ stretchWide } />,
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
		align: [ 'left', 'center', 'right' ],
	},
	edit,
	save: () => null, // to use view.php
};
