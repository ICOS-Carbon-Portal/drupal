const path = require('path');

// Run 'webpack' in a terminal standing in this directory

module.exports = {
	mode: 'development',
	devtool: false,
	entry: './node_modules/icos-cp-backend/lib/index.js',
	module: {
		rules: [
			{
				test: /\.m?js$/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env']
					}
				}
			}
		],
	},
	output: {
		environment: {
			// The environment supports arrow functions ('() => { ... }').
			arrowFunction: true,
			// The environment supports BigInt as literal (123n).
			bigIntLiteral: false,
			// The environment supports const and let for variable declarations.
			const: true,
			// The environment supports destructuring ('{ a, b } = obj').
			destructuring: false,
			// The environment supports an async import() function to import EcmaScript modules.
			dynamicImport: false,
			// The environment supports 'for of' iteration ('for (const x of array) { ... }').
			forOf: false,
			// The environment supports ECMAScript Module syntax to import ECMAScript modules (import ... from '...').
			module: false,
		},
		path: path.resolve(__dirname, 'js'),
		filename: 'icos-cp-backend.js'
  	},
};
