// Set from https://www.npmjs.com/package/@wordpress/scripts
// Add package.json with the @wordpress/scripts dependency.
// Add a root file inside your theme called webpack.config.js

// Import the original config from the @wordpress/scripts package.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const RemovePlugin = require("remove-files-webpack-plugin");
const styleOutputFolder = "build/css";
const scriptOutputFolder = "build/js";

// Import the helper to find and generate the entry points in the src directory
const path = require( 'node:path' );

// Add any a new entry point by extending the webpack config.
module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		'css/admin': path.resolve( __dirname, './src/scss/admin.scss' ),
		'js/admin': path.resolve( __dirname, './src/js/admin' ),
	},
	plugins: [
		new RemovePlugin( {
			/**
			 * After compilation permanently removes
			 * the extra `.js`, `.php`, and `.js.map` files in the output folders
			 */

			after: {
				log: false,
				test: [
					{
						folder: styleOutputFolder,
						method: ( absoluteItemPath ) => {
							return new RegExp( /\.js/, 'm' ).test( absoluteItemPath );
						},
					},
					{
						folder: styleOutputFolder,
						method: ( absoluteItemPath ) => {
							return new RegExp( /\.php$/, 'm' ).test( absoluteItemPath );
						},
					},
					{
						folder: scriptOutputFolder,
						method: ( absoluteItemPath ) => {
							return new RegExp( /\.php$/, 'm' ).test( absoluteItemPath );
						},
					},
				],
			},
		} ),
		...defaultConfig.plugins,
	],
};
