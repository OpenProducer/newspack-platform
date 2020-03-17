/**
 **** WARNING: No ES6 modules here. Not transpiled! ****
 */
/* eslint-disable import/no-nodejs-modules */

/**
 * External dependencies
 */
const fs = require( 'fs' );
const getBaseWebpackConfig = require( '@automattic/calypso-build/webpack.config.js' );
const path = require( 'path' );

/**
 * Internal dependencies
 */
// const { workerCount } = require( './webpack.common' ); // todo: shard...

/**
 * Internal variables
 */
const editorSetup = path.join( __dirname, 'src', 'setup', 'editor' );
const viewSetup = path.join( __dirname, 'src', 'setup', 'view' );

function blockScripts( type, inputDir, blocks ) {
	return blocks
		.map( block => path.join( inputDir, 'blocks', block, `${ type }.js` ) )
		.filter( fs.existsSync );
}

const blocksDir = path.join( __dirname, 'src', 'blocks' );
const blocks = fs
  .readdirSync( blocksDir )
  .filter( block => fs.existsSync( path.join( __dirname, 'src', 'blocks', block, 'editor.js' ) ) );

// Helps split up each block into its own folder view script
const viewBlocksScripts = blocks.reduce( ( viewBlocks, block ) => {
	const viewScriptPath = path.join( __dirname, 'src', 'blocks', block, 'view.js' );
	if ( fs.existsSync( viewScriptPath ) ) {
		viewBlocks[ block + '/view' ] = [ viewSetup, ...[ viewScriptPath ] ];
	}
	return viewBlocks;
}, {} );

// Combines all the different blocks into one editor.js script
const editorScript = [
	editorSetup,
	...blockScripts( 'editor', path.join( __dirname, 'src' ), blocks ),
];

const suppressAdsScript = path.join( __dirname, 'src', 'suppress-ads' );

const webpackConfig = getBaseWebpackConfig(
	{ WP: true },
	{
		entry: {
			editor: editorScript,
			...viewBlocksScripts,
			'suppress-ads': suppressAdsScript,
		},
		'output-path': path.join( __dirname, 'dist' ),
	}
);

module.exports = webpackConfig;
