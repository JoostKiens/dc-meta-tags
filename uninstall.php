<?php
/**
 * @package Internals
 *
 * Code used when the plugin is removed (not just deactivated but actively deleted through the WordPress Admin).
 */

if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

/* remove plugin options */

$plugin_options = array(
		'_joost_dcm_options',
);

foreach ( $plugin_options as $option) {
	delete_option( $option );
}

/* remove meta from posts and pages */
$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_joost_dcm_%'" );
