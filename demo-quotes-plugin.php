<?php
/*
Plugin Name: Demo Quotes Plugin
Plugin URI: https://github.com/jrfnl/wp-plugin-best-practices-demo
Description: Demo plugin for WordPress Plugins Best Practices Tutorial
Version: 1.0
Author: Juliette Reinders Folmer
Author URI: http://adviesenzo.nl/
Text Domain: demo-quotes-plugin
Domain Path: /languages/
License: GPL v3

Copyright (C) 2013, Juliette Reinders Folmer - wp-best-practices@adviesenzo.nl

GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/3.0/>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * POTENTIAL ROAD MAP:
 *
 *
 */


if ( !class_exists( 'DemoQuotesPlugin' ) ) {
	/**
	 * @package WordPress\Plugins\DemoQuotesPlugin
	 * @version 1.0
	 * @link https://github.com/jrfnl/wp-plugin-best-practices-demo WP Plugin Best Practices Demo
	 *
	 * @copyright 2013 Juliette Reinders Folmer
	 * @license http://creativecommons.org/licenses/GPL/3.0/ GNU General Public License, version 3
	 */
	class DemoQuotesPlugin {


		/* *** DEFINE CLASS CONSTANTS *** */

		/**
		 * @const string	Plugin version number
		 * @usedby upgrade_options(), __construct()
		 */
		const VERSION = '1.0';


		/**
		 * @const	string	Minimum required capability to change the plugin options
		 */
		const REQUIRED_CAP = 'manage_options';

		/**
		 * @const	string	Page underneath which the settings page will be hooked
		 */
		const PARENT_PAGE = 'options-general.php';





		/* *** DEFINE STATIC CLASS PROPERTIES *** */

		/**
		 * These static properties will be initialized - *before* class instantiation -
		 * by the static init() function
		 */

		/**
		 * @staticvar	string	$basename	Plugin Basename = 'dir/file.php'
		 */
		public static $basename;

		/**
		 * @staticvar	string	$name		Plugin name	  = dirname of the plugin
		 *									Also used as text domain for translation
		 */
		public static $name;

		/**
		 * @staticvar	string	$url		Full url to the plugin directory, has trailing slash
		 */
		public static $url;

		/**
		 * @staticvar	string	$path		Full server path to the plugin directory, has trailing slash
		 */
		public static $path;

		/**
		 * @staticvar	string	$suffix		Suffix to use if scripts/styles are in debug mode
		 */
		public static $suffix;



		/* *** DEFINE CLASS PROPERTIES *** */

		/* *** Semi Static Properties *** */


		/**
		 * @var array	Default option values
		 */
		var $defaults = array(
		);




		/* *** Properties Holding Various Parts of the Class' State *** */





		/* *** PLUGIN INITIALIZATION METHODS *** */

		/**
		 * Object constructor for plugin
		 */
		function __construct() {

			/* Load plugin text strings */
			load_plugin_textdomain( self::$name, false, self::$name . '/languages/' );
			
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );


			// Register the plugin initialization actions
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}


		/**
		 * Set the static path and directory variables for this class
		 * Is called from the global space *before* instantiating the class to make
		 * sure the correct values are available to the object
		 *
		 * @return void
		 */
		public static function init_statics() {

			self::$basename = plugin_basename( __FILE__ );
			self::$name     = dirname( self::$basename );
			self::$url      = plugin_dir_url( __FILE__ );
			self::$path     = plugin_dir_path( __FILE__ );
			self::$suffix   = ( ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min' );
		}






		/** ******************* ADMINISTRATIVE METHODS ******************* **/


		/**
		 * Add the actions for the front-end functionality
		 * Add actions which are needed for both front-end and back-end functionality
		 */
		public function init() {
			
			// Register the Quotes Custom Post Type
			include_once( self::$path . 'class.demo-quotes-cpt.php' );
			DemoQuotesPluginCpt::register_post_types();


		}


		/**
		 * Add the actions for the back-end functionality
		 */
		function admin_init() {
			/* Don't do anything if user does not have the required capability */
			if ( false === is_admin() || false === current_user_can( self::REQUIRED_CAP ) ) {
				return;
			}

		}




		/* *** PLUGIN ACTIVATION, UPGRADING AND DEACTIVATION *** */


		function activate() {
			// Register the Quotes Custom Post Type so WP knows how to adjust the rewrite rules
			include_once( self::$path . 'class.demo-quotes-cpt.php' );
			DemoQuotesPluginCpt::register_post_types();

			// Make sure our post type slugs will be recognized
			flush_rewrite_rules();
		}


		function deactivate() {
			// Make sure our post type slugs will be removed
			flush_rewrite_rules();
		}





		/* *** FRONT-END: DISPLAY METHODS *** */







		/* *** BACK-END: CUSTOM POST TYPE METHODS *** */








	} /* End of class */


	/* Instantiate our class */
	add_action( 'plugins_loaded', 'demo_quotes_plugin_init' );

	if ( !function_exists( 'demo_quotes_plugin_init' ) ) {
		/**
		 * Initialize the class
		 *
		 * @return void
		 */
		function demo_quotes_plugin_init() {
			/* Initialize the static variables */
			DemoQuotesPlugin::init_statics();

			$GLOBALS['demo_quotes_plugin'] = new DemoQuotesPlugin();
		}
	}
} /* End of class-exists wrapper */