<?php
/*
Plugin Name: Admin Columns - Toggle Columns Add-on
Version: 1.0
Author: Jesper van Engelen
Author URI: http://jespervanengelen.com
Text Domain: acti
License: GPLv2

Copyright 2014	Jesper van Engelen	contact@jepps.nl

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit of accessed directly

define( 'ACTI_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ACTI_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main plugin class
 *
 * @since 1.0
 */
class ACTI {

	/**
	 * Main Admin Columns class instance
	 *
	 * @var CPAC
	 * @since 1.0
	 */
	public $cpac;

	/**
	 * Admin class instance
	 *
	 * @var ACTI_Admin
	 * @since 1.0
	 */
	protected $admin;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		if ( is_admin() ) {
			require_once ACTI_PLUGIN_DIR_PATH . 'includes/class-admin.php';

			$this->admin();
		}

		// Hooks
		add_action( 'plugins_loaded', array( $this, 'after_setup' ) );
		add_action( 'cac/loaded', array( $this, 'cac_setup' ) );
	}

	/**
	 * Get admin class instance
	 *
	 * @since 1.0
	 *
	 * @return ACTI_Admin Admin class instance
	 */
	public function admin() {
		if ( ! isset( $this->admin ) ) {
			$this->admin = new ACTI_Admin( $this );
		}

		return $this->admin;
	}

	/**
	 * Allow other plugins to hook into this plugin
	 * Should be called on the plugins_loaded action
	 *
	 * @see action:plugins_loaded
	 * @since 1.0
	 */
	public function after_setup() {
		/**
		 * Fires after this plugin is setup, which should be on plugins_loaded
		 * Should be used to access the main plugin class instance, possibly store a reference to it for later use
		 * and remove any plugin action and filter hooks
		 *
		 * @since 1.0
		 *
		 * @param ACTI Main plugin class instance
		 */
		do_action( 'acti/after_setup', $this );
	}

	/**
	 * @see action:cac/loaded
	 */
	public function cac_setup( $cpac ) {
		$this->cpac = $cpac;
	}

}

new ACTI();
