<?php
/**
 * Busbee Trucks Parts
 *
 * @since             1.0.0
 * @package           WP_Cleanify
 * @author            PhoolChand <wp.pcprajapat@gmail.com>
 * @link              https://github.com/wppcprajapat
 *
 * @wordpress-plugin
 * Plugin Name:       WP Cleanify
 * Plugin URI:        https://github.com/wppcprajapat
 * Description:       This Plugin is used to disable some unwanted or extra stuff.
 * Version:           1.0.1
 * Author:            Phool Chand Prajapat
 * Author URI:        https://github.com/wppcprajapat
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-cleanify
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently active plugin file.
 */
define( 'WP_CLEANIFY_FILE', __FILE__ );

/**
 * The core plugin class that is used to define admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'classes/class-wp-cleanify-loader.php';
