'use strict';

import { getBlockType, unregisterBlockType } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

const removeBlocks = [ 'jetpack/donations' ];

domReady( function () {
	removeBlocks.forEach( function ( blockName ) {
		if ( getBlockType( blockName ) ) {
			unregisterBlockType( blockName );
		}
	} );
} );
