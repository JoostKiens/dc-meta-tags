<?php
/**
 * @package Admin
 */

if ( !defined( 'DCM_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/**
 * Class that holds most of the admin functionality.
 */
class DCM_Admin {

	/**
	 * Class constructor
	 */
	function __construct() {

		$options = get_dcm_options();
		add_action( 'admin_init', array( $this, 'requires_wordpress_version') );
		add_action( 'admin_init', array( $this, 'options_init' ) );
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_action_link' ), 10, 2 );
	}

	/**
	 * Register all the options needed for config pages
	 * @return [type] [description]
	 */
	function options_init() {
		 
		$options = array(
			'elem_coverage'     => '1',
			'elem_creator'     => '1',
			'elem_date'        => '1',
			'elem_description' => '1',
			'elem_format'      => '1',
			'elem_identifier'  => '1',
			'elem_language'    => '0',
			'elem_publisher'   => '1',
			'elem_rights'      => '1',
			'elem_subject'     => '1',
			'elem_title'       => '1',
			'elem_type'        => '1',
			'rights_url'       => '',
			'output_html'      => 'xhtml',
		);
    	add_option("dcm_options", $options, "", "yes");
		register_setting( 'joost_dcm_options', 'dcm_options' );
	}

	/**
	 * Register the menu item & page
	 */
	function register_settings_page() {
		add_options_page( __( 'Dublin Core Meta Tags', 'dc-meta-tags' ), __( 'DC Meta Tags', 'dc-meta-tags' ), 'manage_options', 'dcm_settings', array( $this, 'config_page' ) );
	}

	/**
	 * Loads the form for the Settings page.
	 */
	function config_page() {
		if ( isset( $_GET['page'] ) && 'dcm_settings' == $_GET['page'] )
			include( DCM_PATH . '/admin/pages/settings.php' );
	}

	/**
	 * Checks if the current WP install is newer than $wp_version
	 * @return [type] [description]
	 */
	function requires_wordpress_version() {
		global $wp_version;
		$plugin = plugin_basename( DCM_PATH );
		$plugin_data = get_plugin_data( DCM_PATH, false );

		if ( version_compare($wp_version, "3.3", "<" ) ) {
			if( is_plugin_active($plugin) ) {
				deactivate_plugins( $plugin );
				wp_die( "'".$plugin_data['Name']."' requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
			}
		}
	}

	/**
	 * Add a link to the settings page to the plugins list
	 *
	 * @staticvar string $this_plugin holds the directory & filename for the plugin
	 * @param array  $links array of links for the plugins, adapted when the current plugin is found.
	 * @param string $file  the filename for the current plugin, which the filter loops through.
	 * @return array $links
	 */
	function add_action_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) 
			$this_plugin = 'dc-meta-tags/dc-meta-tags.php';
		if ( $file == $this_plugin ) {
			$settings_link = '<a href="' . admin_url( 'admin.php?page=dcm_settings' ) . '">' . __( 'Settings', 'dc-meta-tags' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}
}

// Globalize the var first as it's needed globally.
global $dcm_admin;
$dcm_admin = new DCM_Admin();	