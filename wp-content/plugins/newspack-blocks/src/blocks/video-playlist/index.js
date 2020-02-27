/**
 * External dependencies
 */
import { Path, SVG } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import edit from './edit';

/**
 * Style dependencies - will load in editor
 */
import './editor.scss';

export const name = 'youtube-video-playlist';
export const title = __( 'YouTube Video Playlist', 'newspack-blocks' );

/* From https://material.io/tools/icons */
export const icon = (
	<SVG xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
		<Path d="M20 8H4V6h16v2zm-2-6H6v2h12V2zm4 10v8c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2v-8c0-1.1.9-2 2-2h16c1.1 0 2 .9 2 2zm-6 4l-6-3.27v6.53L16 16z" />
		<Path d="M0 0h24v24H0z" fill="none" />
	</SVG>
);

export const settings = {
	title,
	icon,
	category: 'newspack',
	keywords: [
		__( 'video', 'newspack-blocks' ),
		__( 'playlist', 'newspack-blocks' ),
		__( 'youtube', 'newspack-blocks' ),
	],
	description: __( 'Embed a playlist of latest YouTube videos.', 'newspack-blocks' ),
	attributes: {
		className: {
			type: 'string',
		},
		categories: {
			type: 'array',
			default: [],
		},
		videosToShow: {
			type: 'integer',
			default: 5,
		},
	},
	supports: {
		html: false,
		align: true,
	},
	edit,
	save: () => null, // to use view.php
};
