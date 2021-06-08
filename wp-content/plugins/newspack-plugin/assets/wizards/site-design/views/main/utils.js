/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

const processFontOptions = ( headingsOnly, options ) =>
	options.reduce( ( acc, option ) => {
		const isHeadingsOnly = option.label.indexOf( '(*)' ) > 0;
		const label = option.label.replace( ' (*)', '' );
		const selectOption = {
			label,
			value: label,
		};
		if ( isHeadingsOnly ? headingsOnly : true ) {
			acc.push( selectOption );
		}
		return acc;
	}, [] );

export const getFontsList = headingsOnly =>
	[
		{
			label: __( 'Serif', 'newspack' ),
			fallback: 'serif',
			options: [
				{ label: 'Alegreya' },
				{ label: 'Arvo' },
				{ label: 'BioRhyme' },
				{ label: 'BioRhyme Expanded (*)' },
				{ label: 'Bodoni Moda' },
				{ label: 'Cormorant' },
				{ label: 'Crimson Text' },
				{ label: 'EB Garamond' },
				{ label: 'Eczar' },
				{ label: 'Frank Ruhl Libre' },
				{ label: 'Georgia' },
				{ label: 'IBM Plex Serif' },
				{ label: 'Josefin Slab' },
				{ label: 'Inknut Antiqua' },
				{ label: 'Libre Baskerville' },
				{ label: 'Lora' },
				{ label: 'Merriweather' },
				{ label: 'Neuton' },
				{ label: 'Newsreader' },
				{ label: 'Noto Serif' },
				{ label: 'Old Standard TT (*)' },
				{ label: 'Playfair Display' },
				{ label: 'PT Serif' },
				{ label: 'Roboto Slab' },
				{ label: 'Source Serif Pro' },
				{ label: 'Spectral' },
			],
		},
		{
			label: __( 'Sans Serif', 'newspack' ),
			fallback: 'sans_serif',
			options: [
				{ label: 'Alegreya Sans' },
				{ label: 'Archivo' },
				{ label: 'Archivo Black (*)' },
				{ label: 'Archivo Narrow (*)' },
				{ label: 'Chivo' },
				{ label: 'DM Sans' },
				{ label: 'Fira Sans' },
				{ label: 'Fira Sans Condensed (*)' },
				{ label: 'Fira Sans Extra Condensed (*)' },
				{ label: 'IBM Plex Sans' },
				{ label: 'IBM Plex Sans Condensed (*)' },
				{ label: 'Inter' },
				{ label: 'Josefin Sans' },
				{ label: 'Jost' },
				{ label: 'Karla' },
				{ label: 'Lato' },
				{ label: 'Libre Franklin' },
				{ label: 'Montserrat' },
				{ label: 'Noto Sans' },
				{ label: 'Open Sans' },
				{ label: 'Oswald (*)' },
				{ label: 'Overpass' },
				{ label: 'Poppins' },
				{ label: 'PT Sans' },
				{ label: 'Raleway' },
				{ label: 'Roboto' },
				{ label: 'Roboto Condensed (*)' },
				{ label: 'Rubik' },
				{ label: 'Source Sans Pro' },
				{ label: 'Work Sans' },
			],
		},
		{
			label: __( 'Display', 'newspack' ),
			fallback: 'display',
			options: [
				{ label: 'Abril Fatface (*)' },
				{ label: 'Bangers (*)' },
				{ label: 'Bebas Neue (*)' },
				{ label: 'Concert One (*)' },
				{ label: 'Fredoka One (*)' },
				{ label: 'Unica One (*)' },
			],
		},
		{
			label: __( 'Monospace', 'newspack' ),
			fallback: 'monospace',
			options: [
				{ label: 'Anonymous Pro' },
				{ label: 'IBM Plex Mono' },
				{ label: 'JetBrains Mono' },
				{ label: 'Inconsolata' },
				{ label: 'Overpass Mono' },
				{ label: 'PT Mono' },
				{ label: 'Source Code Pro' },
				{ label: 'Space Mono' },
				{ label: 'Roboto Mono' },
			],
		},
	]
		.map( group => ( { ...group, options: processFontOptions( headingsOnly, group.options ) } ) )
		.filter( group => group.options.length );

export const getFontImportURL = value =>
	`//fonts.googleapis.com/css2?family=${ value.replace(
		/\s/g,
		'+'
	) }:ital,wght@0,400;0,700;1,400;1,700&display=swap`;

export const LOGO_SIZE_OPTIONS = [
	{ value: 0, label: __( 'XS', 'newspack' ) },
	{ value: 19, label: __( 'S', 'newspack' ) },
	{ value: 48, label: __( 'M', 'newspack' ) },
	{ value: 72, label: __( 'L', 'newspack' ) },
	{ value: 91, label: __( 'XL', 'newspack' ) },
];

/**
 * Map a logo size to an option value.
 * The size might have been set in the Customizer, where it is a slider input.
 */
export const parseLogoSize = ( size, options = LOGO_SIZE_OPTIONS ) =>
	options.reduce(
		( foundSize, { value } ) => ( size >= value ? value : foundSize ),
		options[ 0 ].value
	);
