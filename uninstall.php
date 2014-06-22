<?php
/**
 * @package Internals
 *
 * Code used when the plugin is removed (not just deactivated but actively deleted through the WordPress Admin).
 */

if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

delete_option( DCM_OPTION_NAME ); /* remove plugin options */

/* maybe I'm paranoid... but if DCM_FIELD_PREFIX is empty, the query below would delete all the metadata from WordPress! So, better make sure that's not the case */
if( !DCM_FIELD_PREFIX ) {
	wpdie( "<p>DCM_FIELD_PREFIX = '{DCM_FIELD_PREFIX}'!</p>" );
}

/* remove meta from posts and pages */
global $wpdb;

$wpdb->query( $wpdb->prepare(
	"DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '%s'",
	DCM_FIELD_PREFIX.'%' )
);
