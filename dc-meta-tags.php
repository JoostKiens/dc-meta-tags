<?php 
/**
 * @package   Dublin Core Meta Tags
 * @author    Joost Kiens, Júlio Reis
 * @license   GPL-3.0
 * @link      http://arocha.org
 * @copyright 2014 A Rocha International
 *
 * @wordpress-plugin
 * Plugin Name:       Dublin Core Meta Tags
 * Plugin URI:        http://joostkiens.com/project/wp-dublin-core-metadata-plugin/
 * Description:       Add Dublin Core meta tags to pages, posts & custom post types
 * Version:           0.4.1
 * Author:            Joost Kiens
 * Author URI:        http://joostkiens.com/
 * Text Domain:       dc-meta-tags
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/JoostKiens/dc-meta-tags
 * Requires at least: 3.3.0
 */
/*
Dublin Core Meta Tags Plugin
Copyright (C) 2012-2014, Joost Kiens - me@joostkiens.com

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

// error_reporting( E_STRICT | E_ALL ); // error reporting, comment out after testing

define( 'DCM_MAINFILE', __FILE__ );
define( 'DCM_BASENAME', plugin_basename( __FILE__ ) );
define( 'DCM_PATH', plugin_dir_path( __FILE__ ) );
define( 'DCM_URL', plugin_dir_url( __FILE__ ) );
define( 'DCM_VERSION', '0.4.2' );
define( 'DCM_MIN_WP_VERSION', '3.3.0');
define( 'DCM_OPTION_NAME', 'dc_meta_tags_options' );
define( 'DCM_OPTION_GROUP', 'dc_meta_tags_options_group' );
define( 'DCM_SETTINGS_SLUG', 'dcm_settings' );
define( 'DCM_FIELD_PREFIX', '_joost_dcm_elem_' );

load_plugin_textdomain( 'dc-meta-tags', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Required by admin & frontend
 */
require DCM_PATH . 'admin/class-dcm-base.php';

/**
 * Initialize DCM admin functionality
 * @return void
 */
function dcm_admin_init() {
	require DCM_PATH . 'admin/class-dcm-admin.php';
	require DCM_PATH . 'admin/class-dcm-meta-box.php';
	
	add_action( 'admin_enqueue_scripts', 'dcm_load_admin_scripts' );
}

/**
 * Load scripts & stylesheets for admin
 * @return void
 */
function dcm_load_admin_scripts() {
	wp_enqueue_script( 'dcm-admin', DCM_URL . 'js/dcm-admin.js', 'jquery', '0.1.0' );
	wp_enqueue_style( 'dcm-admin', DCM_URL . 'css/dcm-admin.css' );
}

/**
 * Initialize DCM frontend functionality
 * @return void
 */
function dcm_frontend_init() {
	require DCM_PATH . 'frontend/class-dcm-frontend.php';
}

if ( is_admin() ) {
	if ( defined('DOING_AJAX') && DOING_AJAX ) {  // if we had ajax, we’d load it here
	}
	else add_action( 'plugins_loaded', 'dcm_admin_init', 0 );
} else {	
	add_action( 'plugins_loaded', 'dcm_frontend_init', 0 );
}
