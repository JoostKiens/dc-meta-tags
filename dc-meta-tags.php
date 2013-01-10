<?php 
/*
Plugin Name: Dublin Core Meta Tags
Version: 0.1.0
Plugin URI: http://joostkiens.com
Description: Add Dublin Core meta tags to pages, posts & custom post types
Author: Joost Kiens
Author URI: http://joostkiens.com/
License: GPL v3

Dublin Core Meta Tags Plugin
Copyright (C) 2012-2013, Joost Kiens - me@joostkiens.com

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
 * @package Main
 */
if ( !defined('DB_NAME') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}


if ( !defined('DCM_URL') )
	define( 'DCM_URL', plugin_dir_url( __FILE__ ) );
if ( !defined('DCM_PATH') )
	define( 'DCM_PATH', plugin_dir_path( __FILE__ ) );
if ( !defined('DCM_BASENAME') )
	define( 'DCM_BASENAME', plugin_basename( __FILE__ ) );

define( 'DCM_VERSION', '0.1.0' );

load_plugin_textdomain('dc-meta-tags', false, basename( dirname( __FILE__ ) ) . '/languages' );


require DCM_PATH.'inc/dcm-functions.php';
// Used by admin & frontend
require DCM_PATH . 'frontend/class-format.php';

/**
 * Initialize DCM admin functionality
 * @return void
 */
function dcm_admin_init() {
	require DCM_PATH.'admin/class-admin.php';
	require DCM_PATH.'admin/class-meta-box.php';
	
	add_action( 'admin_enqueue_scripts', 'dcm_load_admin_scripts' );
}

function dcm_load_admin_scripts() {
	wp_enqueue_script( 'dcm-admin', DCM_URL . 'js/dcm-admin.js', 'jquery', '0.1.0' );
	wp_enqueue_style( 'dcm-admin', DCM_URL . 'css/dcm-admin.css' );
}

/**
 * Initialize DCM frontend functionality
 * @return void
 */
function dcm_frontend_init() {
	require DCM_PATH . 'frontend/class-frontend.php';
	
}

if ( is_admin() ) {
	add_action( 'plugins_loaded', 'dcm_admin_init', 0 );
} else {	
	add_action( 'plugins_loaded', 'dcm_frontend_init', 0 );
}

