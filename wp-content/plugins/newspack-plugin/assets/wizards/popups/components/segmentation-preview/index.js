/**
 * Segmentation Preview component.
 * Extension of WebPreview with support for "view-as-segment" functionality.
 */

/**
 * WordPress dependencies
 */
import { addQueryArgs } from '@wordpress/url';

/**
 * Internal dependencies.
 */
import { WebPreview } from '../../../../components/src';

const SegmentationPreview = props => {
	const postPreviewLink = window?.newspack_popups_wizard_data?.preview_post;
	const frontendUrl = window?.newspack_popups_wizard_data?.frontend_url || '/';

	const {
		campaign = false,
		onLoad = () => {},
		segment = '',
		showUnpublished = false,
		url = postPreviewLink || frontendUrl,
	} = props;

	const decorateURL = urlToDecorate => {
		const view_as = segment.length ? [ `segment:${ segment }` ] : [ 'segment:everyone' ];

		if ( showUnpublished ) {
			view_as.push( 'show_unpublished:true' );
		}

		// If passed campaign ID, get only prompts matching that campaign. Otherwise, get all prompts.
		if ( campaign ) {
			view_as.push( `campaign:${ campaign }` );
		} else {
			view_as.push( 'all' );
		}

		return addQueryArgs( urlToDecorate, { view_as: view_as.join( ';' ) } );
	};

	const onWebPreviewLoad = iframeEl => {
		if ( iframeEl ) {
			[ ...iframeEl.contentWindow.document.querySelectorAll( 'a' ) ].forEach( anchor => {
				const href = anchor.getAttribute( 'href' );
				if ( href.indexOf( frontendUrl ) === 0 ) {
					anchor.setAttribute( 'href', decorateURL( href ) );
				}
			} );
			onLoad( iframeEl );
		}
	};

	return <WebPreview { ...props } onLoad={ onWebPreviewLoad } url={ decorateURL( url ) } />;
};

export default SegmentationPreview;
