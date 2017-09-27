<?php
/**
 * Plugin Name: Custom Classes
 * Plugin URI:  https://themehybrid.com/plugins/custom-classes
 * Description: Allows users to add custom post and body classes from the admin.
 * Version:     1.0.0
 * Author:      Justin Tadlock
 * Author URI:  http://justintadlock.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   CustomClasses
 * @version   1.0.0
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2012-2017, Justin Tadlock
 * @link      https://themehybrid.com/plugins/custom-classes
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-1.0.html
 */

namespace Custom_Classes;

/**
 * Singleton class for setting up the plugin.
 *
 * @since  1.0.0
 * @access public
 */
final class Plugin {

	/**
	 * Plugin directory path.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir = '';

	/**
	 * Plugin directory URI.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $uri = '';

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up globals.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup() {

		// Main plugin directory path and URI.
		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->uri = trailingslashit( plugin_dir_url(  __FILE__ ) );
	}

	/**
	 * Loads files needed by the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function includes() {

		// Load filters and functions.
		require_once( $this->dir . 'inc/functions-filters.php' );

		// Load admin files.
		if ( is_admin() ) {

			// Admin classes.
			require_once( $this->dir . 'admin/class-post-edit.php' );
			require_once( $this->dir . 'admin/class-term-edit.php' );

			Post_Edit::get_instance();
			Term_Edit::get_instance();
		}
	}

	/**
	 * Sets up main plugin actions and filters.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Internationalize the text strings used.
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function i18n() {

		load_plugin_textdomain( 'custom-classes', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'lang' );
	}
}

Plugin::get_instance();
