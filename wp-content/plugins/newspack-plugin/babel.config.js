module.exports = ( { env } ) => {
	const isTest = env( 'test' );
	return {
		plugins: [ ...( isTest ? [] : [ 'babel-plugin-jsx-remove-data-test-id' ] ) ],
		presets: [
			...( isTest ? [ '@babel/preset-env' ] : [] ),
			'@automattic/calypso-build/babel/default',
			'@automattic/calypso-build/babel/wordpress-element',
		],
	};
};
