const { externals, helpers, plugins, presets } = require( '@humanmade/webpack-helpers' );

const { filePath } = helpers;

module.exports = presets.production( {
    name: 'blocks-plugin',
    externals: {
		...externals,
	},
    entry: {
        'blocks-plugin-editor': filePath( 'src/editor.js' ),
        'blocks-plugin-frontend': filePath( 'src/frontend.js' ),
    },
    plugins: [
        plugins.clean(),
    ],
	cache: {
		type: 'filesystem',
	},
} );
