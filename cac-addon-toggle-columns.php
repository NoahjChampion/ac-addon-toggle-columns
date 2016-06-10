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

defined( 'ABSPATH' ) or die();

define( 'ACTI_FILE', __FILE__ );

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin.php';
	new ACTI_Admin();
}
