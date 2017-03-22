<?php
/*
Plugin Name: Admin Columns - Toggle Columns Add-on
Version: 1.0
Author: Jesper van Engelen, Tobias Schutter
Author URI: http://admincolumns.com
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

defined( 'ABSPATH' ) or die();

if ( ! is_admin() ) {
	return;
}

class ACA_Toggle_Admin {

	public function __construct() {

		add_action( 'ac/settings_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'ac/column/settings', array( $this, 'column_settings' ) );
		add_filter( 'ac/headings', array( $this, 'remove_table_headings' ), 10, 2 );
		add_action( 'ac/table/list_screen', array( $this, 'remove_table_columns' ) );
	}

	public function admin_scripts() {
		wp_enqueue_style( 'acti/admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css' );
	}

	/**
	 * @param AC_Column $column
	 */
	public function column_settings( $column ) {
		require_once plugin_dir_path( __FILE__ ) . 'classes/class-settings-column.php';

		$column->add_setting( new ACA_Toggle_Settings_Column( $column ) );
	}

	/**
	 * @param AC_ListScreen $list_screen
	 */
	public function remove_table_columns( $list_screen ) {
		foreach ( $this->get_disabled_columns( $list_screen ) as $name ) {
			$list_screen->deregister_column( $name );
		}
	}

	/**
	 * @param array         $headings
	 * @param AC_ListScreen $list_screen
	 *
	 * @return array
	 */
	public function remove_table_headings( $headings, $list_screen ) {
		foreach ( $this->get_disabled_columns( $list_screen ) as $name ) {
			unset( $headings[ $name ] );
		}

		return $headings;
	}

	/**
	 * @param AC_ListScreen $list_screen
	 */
	private function get_disabled_columns( $list_screen ) {
		$column_names = array();

		foreach ( $list_screen->get_columns() as $column ) {

			/* @var ACA_Toggle_Settings_Column $setting */
			$setting = $column->get_setting( 'active' );

			if ( ! $setting->is_active() ) {
				$column_names[] = $column->get_name();
			}
		}

		return $column_names;
	}

}

new ACA_Toggle_Admin;