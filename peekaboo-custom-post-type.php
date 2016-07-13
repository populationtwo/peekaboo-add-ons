<?php
/**
 * Peekaboo Custom Post Type
 *
 * Plugin to create Custom Post Types on Peekaboo theme.
 *
 * @package   Peekaboo_Custom_Post_Type
 * @author    Population2 <info@population-2.com>
 * @license   GPL-2.0+
 * @link      http://population-2.com
 * @copyright Population2
 *
 * @wordpress-plugin
 * Plugin Name:       Peekaboo Custom Post Type
 * Plugin URI:        http://population-2.com
 * Description:       Plugin to create custom post type on Peekaboo theme.
 * Version:           0.0.1
 * Author:            Population2
 * Author URI:        
 * Text Domain:       peekaboo
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-peekaboo-custom-post-type.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Peekaboo_Custom_Post_Type', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Peekaboo_Custom_Post_Type', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Peekaboo_Custom_Post_Type', 'get_instance' ) );