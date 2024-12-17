# Setting up local dev server

 1. Use Local by WPEngine https://wpengine.com/local/
 2. Create a new site, use default settings
 3. Browse to the site's folder
 4. Replace the public folder with this entire repository. Make sure its still named public
 5. Open the public folder in VSCode, Cursor, or whatever you use
 6. Browse to the sites url in your browser. make sure it's https://. Login to wordpress by adding /wp-admin to the url


# Getting Started
Open the terminal in VSCode with Ctrl + ~

Change to the plugin folder with `cd wp-content/plugins/blocks-plugin`

then, install the dependencies: `npm install --legacy-peer-deps`

then start the build process: `npm run start`

  

# Next

 1. Make sure you're on the https:// version of the site
 2. In another tab, go to https://localhost:9090/ and press 'Proceed' under advanced settings
 3. Back in WordPress, Enable the plugin
 4. Create or edit a page
 5. Look under the 'Widgets' category in the block editor
 6. Confirm that the 'Boilerplate' block is available

