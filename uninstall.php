<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Peekaboo Custom Post Type
 * @author    Population2 <info@population-2.com>
 * @license   GPL-2.0+
 * @link      http://population-2.com
 * @copyright Population2
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}