/**
 * Internal dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { name, settings } from '.';

if ( window.newspack_blocks_data?.can_use_video_playlist ) {
	registerBlockType( `newspack-blocks/${ name }`, settings );
}
