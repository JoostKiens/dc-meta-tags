<?php

/**
 * All of the upgrade functions
 * Included in DCM_Admin, if necessary (i.e. if it's an upgrade)
 * This is NOT a child of DCM_Base, as it's never run on its own,
 * but always from within DCM_Admin
 * no singleton necessary, either
 */

if ( !defined( 'DCM_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

class DCM_Upgrade {

	/**
	 * Upgrades this installation of DCM
	 * @param   str $old_version  The existing version
	 * @param   arr $options      The existing options
	 * @return  bool              True if upgrade was successful
	 */
	static function upgrade( $old_version, $old_options ) {

		$success = true;
		// test for downgrade
		if( version_compare( $old_version, DCM_VERSION, '>' ) ) {
			$success = self::downgrade( $old_version );
		} else {
			// now we test the upgrades, which should be nested in the ifs,
			// from oldest to newest upgrade routine
			// test for pre-0.3.0
			if( version_compare( $old_version, '0.3.0', '<' ) ) {
				$success = self::pre_0_3_0();
			}
		}
		
		// update version, even if there was an error
		$old_options[ 'version' ] = DCM_VERSION;
		update_option( '_joost_dcm_options', $old_options );

		// check for upgrade error
		if ( !$success ) {
			// Translators: 1 is previous version number, 2 is new version
			$mask = __( 'Error while upgrading DC Meta Tags %1$s to version %2$s.', 'dc-meta-tags' );
			DCM_Base::settings_message( sprintf( $mask, $old_version, DCM_VERSION ), 'error' );
			return false;
		}
		
		// success: say thank you, return true
		// Translators: 1 is previous version number, 2 is new version
		$mask = __( 'Thank you for upgrading DC Meta Tags %1$s to version %2$s.', 'dc-meta-tags' );
		DCM_Base::settings_message( sprintf( $mask, $old_version, DCM_VERSION ) );
		return true;
	}
	
	/**
	 * Upgrades this installation of DCM from a pre-0.3.0 version
	 * DCM used to store empty meta values, we'll remove these
	 * @return  bool   Always true: no error checking is performed
	 */
	static function pre_0_3_0() {
		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE
			meta_key LIKE '_joost_dcm_%'
			AND (meta_value = '' OR meta_value = 'a:0:{}')" );
		return true;
	}

	/**
	 * Run in case of a downgrade, i.e. replacing a newer version with an older one
	 * @param   str $old_version  The existing version
	 * @return  bool              Always false: no error checking is performed
	 */
	static function downgrade( $old_version ) {
		// we can't do anything, just print an error message
		$mask = __( 'ERROR: You are performing a DOWNGRADE. You had DC Meta Tags version %1$s, and you are now running version %2$s, which is older. Your website may encounter unexpected errors. You are advised to restore this plugin back to version %1$s.', 'dc-meta-tags' );
		DCM_Base::settings_message( sprintf( $mask, $old_version, DCM_VERSION ), 'error' );
		return false;
	}
}
