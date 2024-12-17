const { externals, helpers, presets } = require( '@humanmade/webpack-helpers' );

const { choosePort, cleanOnExit, filePath } = helpers;

cleanOnExit( [
    filePath( 'build', 'development-asset-manifest.json' ),
] );

module.exports = choosePort( 9090 ).then( ( port ) => presets.development( {
    name: 'blocks-plugin-editor',
    devServer: {
        server: 'https',
        port,
    },
    externals: {
        ...externals,
    },
    entry: {
        'blocks-plugin-editor': filePath( 'src/editor.js' ),
        'blocks-plugin-frontend': filePath( 'src/frontend.js' ),
    },
} ) );
