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
class DCM_Admin extends DCM_Base {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_init', array( $this, 'requires_wordpress_version') );
		add_action( 'admin_init', array( $this, 'options_init' ) );
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_action_link' ), 10, 2 );
	}

	/**
	 * Register all the options needed for config pages
	 * @return void
	 */
	public function options_init() {
		// '1' in elem_* means it’s enabled
		$options = array(
			'version'          => DCM_VERSION,
			'elem_contributor' => '1',
			'elem_coverage'    => '1',
			'elem_creator'     => '1',
			'elem_date'        => '1',
			'elem_description' => '1',
			'elem_format'      => '1',
			'elem_identifier'  => '1',
			'elem_language'    => '0',
			'elem_publisher'   => '1',
			'elem_relation'    => '1',
			'elem_rights'      => '1',
			'elem_source'      => '1',
			'elem_subject'     => '1',
			'elem_title'       => '1',
			'elem_type'        => '1',
			'rights_url'       => '',
			'output_html'      => 'xhtml',
			'post_types'       => $this->list_post_types( false ),
		);
		
		// first time? set default options, say thank you
		// (add_option doesn't update existing data, only inserts new data)
		$is_install = add_option( "_joost_dcm_options", $options, "", "yes" );
		if( $is_install ) {
			$mask = __( 'Thank you for installing DC Meta Tags.', 'dc-meta-tags' );
			DCM_Base::settings_message( $mask );
		}
		else {
			// is this an upgrade?
			$previous_version = DCM_Admin::get_options_version();
			// it's an upgrade if previous version number wasn't set,
			// or if version number is different
			// (version numbers are set since 0.3.0)
			// note: using != operator instead of < to cover for possible downgrades
			$is_upgrade = !$previous_version
				|| version_compare( $previous_version, DCM_VERSION, '!=' );
			if( $is_upgrade ) {
				// include upgrade code and run it
				include( DCM_PATH . '/admin/class-dcm-upgrade.php' );
				$success = DCM_Upgrade::upgrade( $previous_version, $options );
			}
		}
		
		register_setting( 'joost_dcm_options', '_joost_dcm_options', array( $this, 'dcm_validate') );
	}
	
	/**
	 * Returns an array with the current post types as key and 
	 * either the name of the post type as value OR a 1
	 * @param   str $output_val If set to 'names' the returned array valus will the post type's name
	 * @return  arr           Custom post types
	 */
	public function list_post_types( $output ) {
		$args = array(
			'public' => true,
		);
		$output = array();
		if ($output === 'names') {
			foreach ( get_post_types( $args, 'objects' ) as $post_type => $vars) {
				$output[$post_type] = $vars->labels->name;
			} 
			return $output;
		} else {
			return get_post_types( $args );
		}
		
	}

	/**
	 * Register the menu item & page
	 * @return void
	 */
	public function register_settings_page() {
		add_options_page(
			__( 'Dublin Core Meta Tags', 'dc-meta-tags' ),
			__( 'DC Meta Tags', 'dc-meta-tags' ),
			'manage_options',
			'dcm_settings',
			array( $this, 'config_page' )
		);
	}

	/**
	 * Loads the form for the settings page
	 * @return void
	 */
	public function config_page() {
		if ( isset( $_GET['page'] ) && 'dcm_settings' == $_GET['page'] )
			include( DCM_PATH . '/admin/inc-dcm-settings.php' );
	}

	/**
	 * Sanitize and validate input
	 * @param  arr $options    Admin options with values
	 * @return arr             Sanitized admin options with values
	 */
	public function dcm_validate( $options ) {
		// if the enable box is unchecked, $options comes with no value
		$options['elem_contributor'] = DCM_Admin::is_enabled( 'elem_contributor', $options );
		$options['elem_coverage']    = DCM_Admin::is_enabled( 'elem_coverage', $options );
		$options['elem_creator']     = DCM_Admin::is_enabled( 'elem_creator', $options );
		$options['elem_date']        = DCM_Admin::is_enabled( 'elem_date', $options );
		$options['elem_description'] = DCM_Admin::is_enabled( 'elem_description', $options );
		$options['elem_format']      = DCM_Admin::is_enabled( 'elem_format', $options );
		$options['elem_identifier']  = DCM_Admin::is_enabled( 'elem_identifier', $options );
		$options['elem_language']    = DCM_Admin::is_enabled( 'elem_language', $options );
		$options['elem_publisher']   = DCM_Admin::is_enabled( 'elem_publisher', $options );
		$options['elem_relation']    = DCM_Admin::is_enabled( 'elem_relation', $options );
		$options['elem_rights']      = DCM_Admin::is_enabled( 'elem_rights', $options );
		$options['elem_source']      = DCM_Admin::is_enabled( 'elem_source', $options );
		$options['elem_subject']     = DCM_Admin::is_enabled( 'elem_subject', $options );
		$options['elem_title']       = DCM_Admin::is_enabled( 'elem_title', $options );
		$options['elem_type']        = DCM_Admin::is_enabled( 'elem_type', $options );
		$options['output_html']      = wp_filter_nohtml_kses( $options['output_html'] );
		$options['rights_url']       = wp_filter_nohtml_kses( $options['rights_url'] );
		foreach ($options['post_types'] as $key => $val)
			$options['post_types'][$key] = wp_filter_nohtml_kses( $val );

		$options[ 'version' ] = DCM_VERSION;
		return $options;
	}
	
	/* Test if an option should be enabled
	 * @param  arr $elem       The element we're seaching for
	 * @param  arr $options    Admin options with values
	 * @return num             1 if the option should be enabled
	 *
	 * TODO: convert 1's to true and false
	 */
	private static function is_enabled( $elem, $options ) {
		$enabled = ( array_key_exists( $elem, $options ) )
			&& ( $options[ $elem ] == 1 );
		return $enabled ? 1 : 0;
	}

	/**
	 * Checks if the current WP install is newer than $wp_version
	 * @return void
	 */
	public function requires_wordpress_version() {
		global $wp_version;
		$plugin = DCM_BASENAME;
		$plugin_data = get_plugin_data( DCM_MAINFILE, false );

		if ( version_compare($wp_version, DCM_MIN_WP_VERSION, "<" ) ) {
			if( is_plugin_active( $plugin ) ) {
				// deactivate plugin, print error message
				deactivate_plugins( $plugin );
				// Translators: 1 is plugin name, 2 is version number
				$msg_title = sprintf( __( '%1$s %2$s not activated', 'licence-picker' ), $plugin_data['Name'], $plugin_data['Version'] );
				// Translators: 1 is current WordPress version, 2 is required version
				$msg_para = sprintf( __( 'You are running WordPress version %1$s. This plugin requires version %2$s or higher, and has been deactivated! Please upgrade WordPress and try again.', 'licence-picker' ), $wp_version, DCM_MIN_WP_VERSION );
				$msg_back = __( 'Back to WordPress admin', 'licence-picker' );
				wp_die(  sprintf( '<h1>%s</h1><p>%s</p><p><a href="%s">%s</a></p>' , $msg_title, $msg_para, admin_url(), $msg_back ) );
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
	public function add_action_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) 
			$this_plugin = 'dc-meta-tags/dc-meta-tags.php';
		if ( $file == $this_plugin ) {
			$settings_link = '<a href="' . admin_url( 'admin.php?page=dcm_settings' ) . '">' . __( 'Settings', 'dc-meta-tags' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}
	
	/**
	 * Returns the plugin version, as recorded in the saved options
	 */
	static function get_options_version() {
		$options = get_option( '_joost_dcm_options' );
		return
			array_key_exists( 'version', $options ) ? $options['version'] : '';
	}
	
}

// Globalize the var first as it's needed globally.
global $dcm_admin;
$dcm_admin = new DCM_Admin();
