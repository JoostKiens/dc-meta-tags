<?php
/**
 * @package Internals
 *
 * Code used when the plugin is removed (not just deactivated but actively deleted through the WordPress Admin).
 */

if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

$plugin_options = array(
		'_joost_dcm_options',
);

foreach ( $plugin_options as $option) {
	delete_option( $option );
}


$args = array(
	'public' => true,
);

$post_types = get_post_types( $args );


$args = array (
	'numberposts' => -1,
	'post_type'   => $post_types,
	'post_status' => 'any',
);

$allposts = get_posts( $args );

foreach( $allposts as $postinfo) {
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_contributor');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_coverage');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_creator');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_date');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_description');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_format');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_identifier');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_language');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_publisher');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_relation');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_rights');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_source');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_subject');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_title');
	delete_post_meta($postinfo->ID, '_joost_dcm_elem_type');
	delete_post_meta($postinfo->ID, '_joost_dcm_rights_url');
	delete_post_meta($postinfo->ID, '_joost_dcm_output_html');
	delete_post_meta($postinfo->ID, '_joost_dcm_post_types');
}