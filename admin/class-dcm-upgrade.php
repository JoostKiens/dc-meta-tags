<?php

if ( !defined( 'DCM_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/**
 * Upgrades this installation of DCM
 * An upgrade always updates the options in the database
 */
class DCM_Upgrade {

	public $success;  // whether the upgrade/downgrade was successful
	
	function __construct( $previous_version ) {
		$success = true;
		// test for downgrade
		if( version_compare( $previous_version, DCM_VERSION, '>' ) ) {
			$this->success = $this->downgrade();
		} else {
			// now we test the upgrades, which should be nested in the ifs,
			// from oldest to newest upgrade routine
			if( version_compare( $previous_version, '0.3.0', '<' ) ) {
				$this->success = $this->pre_0_3_0();
			}
			if( version_compare( $previous_version, '0.4.0', '<' ) ) {
				$this->success = $this->pre_0_4_0();
			}
		}
	
		// check for upgrade error
		if ( !$this->success ) {
			// Translators: 1 is previous version number, 2 is new version
			$mask = __( 'Error while upgrading DC Meta Tags %1$s to version %2$s.', 'dc-meta-tags' );
			DCM_Base::settings_message( sprintf( $mask, $previous_version, DCM_VERSION ), 'error' );
			$this->success = false;
		}
		
		// success: say thank you, return true
		// Translators: 1 is previous version number, 2 is new version
		$mask = __( 'Thank you for upgrading DC Meta Tags %1$s to version %2$s.', 'dc-meta-tags' );
		DCM_Base::settings_message( sprintf( $mask, $previous_version, DCM_VERSION ) );
		$this->success = true;
	}
	
	/**
	 * Run in case of a downgrade, i.e. replacing a newer version with an older one
	 * @return  bool              Always false: no error checking is performed
	 */
	static function downgrade( $previous_version ) {
		// we can't do anything, just print an error message
		$mask = __( 'ERROR: You are performing a DOWNGRADE. You had DC Meta Tags version %1$s, and you are now running version %2$s, which is older. Your website may encounter unexpected errors. You are advised to restore this plugin back to version %1$s.', 'dc-meta-tags' );
		DCM_Base::settings_message( sprintf( $mask, $previous_version, DCM_VERSION ), 'error' );
		return false;
	}
	
	/**
	 * Upgrades this installation of DCM from a pre-0.3.0 version
	 * DCM used to store empty meta values, we'll remove these
	 * @return  bool   Always true: no error checking is performed
	 */
	private function pre_0_3_0() {
		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE
			meta_key LIKE '_joost_dcm_%'
			AND (meta_value = '' OR meta_value = 'a:0:{}')" );
		return true;
	}
	
	/**
	 * Upgrades this installation of DCM from a pre-0.4.0 version
	 * Assumes these have been run: pre_0_3_0, DCM_Base::get_pre_0_4_0_options
	 * @return  bool   Always true: no error checking is performed
	 */
	private function pre_0_4_0() {		
		delete_option( '_joost_dcm_options' );
		return true; // DCM_Base::get_pre_0_4_0_options inserted new options already
	}
}
