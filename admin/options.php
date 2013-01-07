<?php
/**
 * @package Options
 */

if ( !defined('DCM_VERSION') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}



class DCM_Options {
	function __construct() {
		add_action('admin_init', array(&$this, 'dcm_init' ));
		add_action('admin_menu', array(&$this, 'dcm_add_menu'));
	}
	// Init plugin options to white list our options
	function dcm_init(){
		register_setting( 'dcm_options', 'dcm_elements', 'dcm_validate' );
	}

	// Add menu page
	function dcm_add_menu() {
		add_options_page('Ozh\'s Sample Options', 'Sample Options', 'manage_options', 'dcm', array($this, 'dcm_do_page'));
	}

	// Draw the menu page itself
	function dcm_do_page() {
		require DCM_PATH . 'admin/settings_page.php';
	}

	// Sanitize and validate input. Accepts an array, return a sanitized array.
	function dcm_validate($input) {
		// Our first value is either 0 or 1
		$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
		
		// Say our second option must be safe text with no HTML tags
		$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
		
		return $input;
	}
}
new DCM_Options;


/*
add_action('admin_init', 'dcm_init' );
add_action('admin_menu', 'dcm_add_menu');

function dcm_init(){
	register_setting( 'dcm_options', 'dcm_elements', 'dcm_validate' );
}

// Add menu page
function dcm_add_menu() {
	add_options_page('Ozh\'s Sample Options', 'Sample Options', 'manage_options', 'dcm', 'dcm_do_page');
}

// Draw the menu page itself
function dcm_do_page() {
	require DCM_PATH . 'admin/settings_page.php';
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function dcm_validate($input) {
	// Our first value is either 0 or 1
	$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
	
	// Say our second option must be safe text with no HTML tags
	$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
	
	return $input;
}
*/