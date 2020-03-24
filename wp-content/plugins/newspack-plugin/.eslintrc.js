module.exports = {
	extends: [
		'plugin:@wordpress/eslint-plugin/recommended',
		'plugin:prettier/recommended',
		'plugin:react/recommended',
		'plugin:jsdoc/recommended',
		'plugin:import/errors',
		'plugin:import/warnings',
	],
	env: {
		browser: true,
	},
	globals: {
		newspack_urls: 'readonly',
		newspack_aux_data: 'readonly',
	},
	ignorePatterns: [ 'dist/', 'node_modules/', 'assets/components/node_modules' ],
	rules: {
		'no-console': 'off',
		camelcase: 'off',
		// Disallow importing or requiring packages that are not listed in package.json
		// This prevents us from depending on transitive dependencies, which could break in unexpected ways.
		'import/no-extraneous-dependencies': [ 'error', { packageDir: '.' } ],
		// There's a conflict with prettier here:
		'react/jsx-curly-spacing': 'off',
		// Skip prop types validation for now
		'react/prop-types': 'off',
		'react/react-in-jsx-scope': 'off',
		// JSDoc rules overrides
		'jsdoc/require-returns': 'off',
		'jsdoc/require-param': 'off',
	},
};
