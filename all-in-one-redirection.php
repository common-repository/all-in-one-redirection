<?php

/**
 * @link              https://wordpress.org/plugins/all-in-one-redirection/
 * @since             1.0.0
 * @package           All_In_One_Redirection
 *
 * @wordpress-plugin
 * Plugin Name:       All In One Redirection
 * Plugin URI:        https://wordpress.org/plugins/all-in-one-redirection/
 * Description:       All in one redirection provides functionality to redirect the page request to another page of website and its also provide the host/www redirection setting, 404 page detection and bulk import/export.
 * Version:           2.2.0
 * Author:            Vsourz Digital <mehul@vsourz.com>
 * Author URI:        https://www.vsourz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       all-in-one-redirection
 * Domain Path:       /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-all-in-one-redirection-activator.php
 */
function activate_all_in_one_redirection() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-all-in-one-redirection-activator.php';
	All_In_One_Redirection_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-all-in-one-redirection-deactivator.php
 */
function deactivate_all_in_one_redirection() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-all-in-one-redirection-deactivator.php';
	All_In_One_Redirection_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_all_in_one_redirection' );
register_deactivation_hook( __FILE__, 'deactivate_all_in_one_redirection' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-all-in-one-redirection.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_all_in_one_redirection() {
		
	$plugin = new All_In_One_Redirection();
	$plugin->run();

}
run_all_in_one_redirection();