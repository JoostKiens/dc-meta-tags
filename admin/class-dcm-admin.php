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

	protected $settings; // this will have a DCM_Settings_page object
		
	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->init_vars();
		// include the settings page code and initialize it
		include( DCM_PATH . '/admin/class-dcm-settings-page.php' );
		$this->settings = new DCM_Settings_page( $this->options );

		add_action( 'admin_menu', array( $this, 'on_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'on_admin_init' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_action_link' ), 10, 2 );
	}

	/**
	 * Register the menu item & page
	 * @return void
	 */
	public function on_admin_menu() {
		add_options_page(
			__( 'Dublin Core Meta Tags', 'dc-meta-tags' ),
			__( 'DC Meta Tags', 'dc-meta-tags' ),
			'manage_options',
			'dcm_settings',
			array( $this->settings, 'render' )
		);
	}

	/**
	 * Register all the options needed for config pages
	 * @return void
	 */
	public function on_admin_init() {
		$this->requires_wordpress_version();
		
		// first run? set default options, say thank you
		if( $this->is_install ) {
			$mask = __( 'Thank you for installing DC Meta Tags.', 'dc-meta-tags' );
			DCM_Base::settings_message( $mask );
		}
		else {
			// is this an upgrade?
			if( $this->is_upgrade ) {
				// include the upgrade code and run it
				include( DCM_PATH . '/admin/class-dcm-upgrade.php' );
				$upgrade = new DCM_Upgrade( $this->previous_version );
				// success recorded in $upgrade->success
			}
		}
		register_setting( DCM_OPTION_GROUP, DCM_OPTION_NAME, array( $this, 'sanitize_options') );
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
	 * Sanitize and validate input
	 * @param  arr $o    Admin options with values
	 * @return arr       Sanitized admin options with values
	 */
	public function sanitize_options( $o ) {
		
		// Translators: a separator between items on a list
		$sep = __( ', ', 'dc-meta-tags' );
		$list = '';                   // string of deleted metadata fields
		$nrows = 0;                   // total rows deleted
		
		foreach( $this->fields as $field ) {
		
			// *_mode must be set to off, default, or editable
			if( !isset( $o[$field.'_mode'] ) || !in_array( $o[$field.'_mode'], $this->settings->status_values ) ) {
				$o[$field.'_mode'] = 'editable';
			}
			// we remove the metadata of fields that go from editable to default,
			// and collect their names
			$n = $this->remove_default_field_metadata( $field, $o[$field.'_mode'] );
			if( $n ) {
				$list .= ( $list ? $sep : '') . DCM_Base::get_the_label( $field );
				$nrows += $n;
			}
			
			// this default type
			$default_type = isset( $o[ $field.'_default_type' ] ) ?
				$o[ $field.'_default_type' ] : '';
			// acceptable values for this default type
			$default_types = $this->default_types[ $field ];
			// if not in acceptable list, we revert to none
			if( !in_array( $default_type, $default_types ) ) {
				$o[$field.'_default_type'] = 'none';
			}
			
			// clean *_default_value, set it if missing (shouldn't happen)
			if( isset( $o[$field.'_default_value'] ) )
				$o[$field.'_default_value'] =
					wp_filter_nohtml_kses($o[$field.'_default_value'] );
			else
				$o[$field.'_default_value'] = '';
		}
		foreach ($o['post_types'] as $key => $val)
			$o['post_types'][$key] = wp_filter_nohtml_kses( $val );
		$o['version'] = DCM_VERSION;
		
		// affix message if any rows have been deleted
		if( $nrows ) {
			$mask = _n( 'Settings updated. %s metadata record deleted for %s.', 'Settings updated. %s metadata records deleted from %s.', $nrows, 'dc-meta-tags' );
			add_settings_error( DCM_SETTINGS_SLUG, 'metadata_deleted', sprintf( $mask, $nrows, $list ), 'error' );
		}
		return $o;
	}
	
	/**
	 * Remove the metadata of fields that go from editable to default
	 * @param  str $field     The name of the field
	 * @param  str $new_mode  The field’s new mode
	 * @return int            Number of rows deleted (false if SQL gave an error)
	 */
	private function remove_default_field_metadata( $field, $new_mode ) {
		if( $new_mode === 'default' && $this->options[$field.'_mode'] === 'editable' ) {
			global $wpdb;
			return $wpdb->query( $wpdb->prepare(
				"DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '%s'",
				DCM_FIELD_PREFIX.$field )
			);
		} else return 0;
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
}

global $dcm_admin; // Globalize the var first as it's needed globally
$dcm_admin = new DCM_Admin();
