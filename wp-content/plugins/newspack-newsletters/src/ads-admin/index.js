/**
 * WordPress dependencies.
 */
import domReady from '@wordpress/dom-ready';
import { render } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import AdsManager from './AdsManager';

domReady( () => {
	const element = document.getElementById( 'newspack-newsletters-ads-admin' );
	render( <AdsManager />, element );
} );
