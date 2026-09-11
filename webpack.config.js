const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		admin: path.resolve( __dirname, 'assets/src/js/admin/index.js' ),
		public: path.resolve( __dirname, 'assets/src/js/public/index.js' ),
	},
};
