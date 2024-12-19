// Set from https://www.npmjs.com/package/@wordpress/scripts
// Add package.json with the @wordpress/scripts dependency.
// Add a root file inside your theme called webpack.config.js

// Import the original config from the @wordpress/scripts package.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const RemovePlugin = require("remove-files-webpack-plugin");
const styleOutputFolder = "build/css";

// Import the helper to find and generate the entry points in the src directory
const path = require( 'node:path' );

// Add any a new entry point by extending the webpack config.
module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		'css/admin': path.resolve( __dirname, './src/scss/admin.scss' ),
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
						folder: './src/scss/utils',
						method: ( absoluteItemPath ) => {
							return new RegExp( /\.php$/, 'm' ).test( absoluteItemPath );
						},
					},
					{
						folder: './src/scss/utils',
						method: ( absoluteItemPath ) => {
							return new RegExp( /(\.js)(\.map)*$/, 'm' ).test( absoluteItemPath );
						},
					},
					{
						folder: '.',
						method: ( absoluteItemPath ) => {
							return new RegExp( /(json\.js)(\.map)*$/, 'm' ).test(
								absoluteItemPath
							);
						},
					},
					{
						folder: '.',
						method: ( absoluteItemPath ) => {
							return new RegExp( /theme\.json\.asset\.php$/, 'm' ).test(
								absoluteItemPath
							);
						},
					},
				],
			},
		} ),
		...defaultConfig.plugins,
	],
};
