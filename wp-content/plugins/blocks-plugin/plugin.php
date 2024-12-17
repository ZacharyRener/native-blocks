<?php
/**
 * Plugin Name: Native Blocks
 * Description: Blocks
 */

function enqueue_editor_scripts() {
	// we need to load in [plugin]/build/production-asset-manifest.json
	// the output of that, for example, could be this:
		/*
		{
			"blocks-plugin-editor.css": "blocks-plugin-editor.83a0d46488aef8a0610c.css",
			"blocks-plugin-editor.js": "blocks-plugin-editor.19a533c64aab01b4cd5c.js",
			"blocks-plugin-frontend.css": "blocks-plugin-frontend.83a0d46488aef8a0610c.css",
			"blocks-plugin-frontend.js": "blocks-plugin-frontend.ac43ab61b9dde1aa8540.js"
		}
		*/
	// from that, we need to enqueue the blocks-plugin-editor files to the block editor
	// the frontend ones will be handled in a different function

	// so first, load in the manifest:
	$plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
	$manifest = null;
	$editor_script_url = null;
	if( wp_get_environment_type() === 'local' ) {
		$manifest = json_decode(file_get_contents($plugin_path . 'build/development-asset-manifest.json'), true);
		$editor_script_url = $manifest['blocks-plugin-editor.js'];
	} else {
		$manifest = json_decode(file_get_contents($plugin_path . 'build/production-asset-manifest.json'), true);
		$editor_script_url = plugin_dir_url(__FILE__) . 'build/' . $manifest['blocks-plugin-editor.js'];
	}
	// then, enqueue the editor assets:
	wp_enqueue_script(
		'blocks-plugin-editor',
		$editor_script_url,
		array(
			'wp-blocks',
			'wp-components',
			'wp-edit-post',
			'wp-element',
			'wp-i18n',
		),
		null,
		true
	);

	wp_localize_script( 'blocks-plugin-editor', 'blocksPluginData', array(
        'pluginUrl' => plugins_url( '/', __FILE__ ),
    ));
	
}
add_action('enqueue_block_editor_assets', 'enqueue_editor_scripts');

function enqueue_frontend_scripts() {
	$plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
	$manifest = null;
	$frontend_script_url = null;
	if( wp_get_environment_type() === 'local' ) {
		$manifest = json_decode(file_get_contents($plugin_path . 'build/development-asset-manifest.json'), true);
		$frontend_script_url = $manifest['blocks-plugin-frontend.js'];
	} else {
		$manifest = json_decode(file_get_contents($plugin_path . 'build/production-asset-manifest.json'), true);
		$frontend_script_url = plugin_dir_url(__FILE__) . 'build/' . $manifest['blocks-plugin-frontend.js'];
	}
	// then, enqueue the editor assets:
	wp_enqueue_script(
		'blocks-plugin',
		$frontend_script_url,
		array(
			'wp-blocks',
		),
		null,
		true
	);

	wp_localize_script( 'blocks-plugin', 'blocksPluginData', array(
        'pluginUrl' => plugins_url( '/', __FILE__ ),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_frontend_scripts');
