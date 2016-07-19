<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://population-2.com
 * @since      1.0.0
 *
 * @package    Peekaboo_Add_Ons
 * @subpackage Peekaboo_Add_Ons/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Peekaboo_Add_Ons
 * @subpackage Peekaboo_Add_Ons/includes
 * @author     Population2 <info@population-2.com>
 */
class Peekaboo_Add_Ons_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'peekaboo-add-ons',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
