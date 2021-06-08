/**
 * WordPress dependencies
 */
import { BlockPreview } from '@wordpress/block-editor';
import { Fragment, useMemo } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

const NewsletterPreview = ( { meta = {}, ...props } ) => {
	const ELEMENT_ID = useMemo( () => `preview-${ Math.round( Math.random() * 1000 ) }`, [] );

	return (
		<Fragment>
			<style>{ `${
				meta.font_body
					? `
#${ ELEMENT_ID } *:not( code ) {
  font-family: ${ meta.font_body };
}`
					: ' '
			}${
				meta.font_header
					? `
#${ ELEMENT_ID } h1, #${ ELEMENT_ID } h2, #${ ELEMENT_ID } h3, #${ ELEMENT_ID } h4, #${ ELEMENT_ID } h5, #${ ELEMENT_ID } h6 {
  font-family: ${ meta.font_header };
}`
					: ' '
			}` }</style>
			<div
				id={ ELEMENT_ID }
				className="newspack-newsletters__layout-preview"
				style={ {
					backgroundColor: meta.background_color,
				} }
			>
				<BlockPreview { ...props } />
			</div>
		</Fragment>
	);
};

export default NewsletterPreview;
