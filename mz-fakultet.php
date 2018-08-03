<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.linkedin.com/in/milos-zivic-2586a174
 * @since             1.0.0
 * @package           Mz_Fakultet
 *
 * @wordpress-plugin
 * Plugin Name:       Fakultet
 * Plugin URI:        https://github.com/milosaiki
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Milos Zivic
 * Author URI:        www.linkedin.com/in/milos-zivic-2586a174
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mz-fakultet
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mz-fakultet-activator.php
 */
function activate_mz_fakultet() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mz-fakultet-activator.php';
	Mz_Fakultet_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mz-fakultet-deactivator.php
 */
function deactivate_mz_fakultet() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mz-fakultet-deactivator.php';
	Mz_Fakultet_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mz_fakultet' );
register_deactivation_hook( __FILE__, 'deactivate_mz_fakultet' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mz-fakultet.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mz_fakultet() {

	$plugin = new Mz_Fakultet();
	$plugin->run();

}
run_mz_fakultet();
