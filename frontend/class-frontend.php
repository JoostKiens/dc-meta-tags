<?php
/**
 * @package Frontend
 */

if ( !defined( 'DCM_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

class DCM_Frontend {

	public function __construct() {
		// Call dcm_add_meta, add to wp_head
		add_action( 'wp_head', array( $this, 'dcm_add_meta' ) );
	}

	/**
	 * Add Dublin Core Meta Data to head
	 * @return void
	 */
	public function dcm_add_meta() {
		if ( is_single() || is_page() ) {
			$options = get_dcm_options();

			if ( !empty ( $options ) ) {
				if ( $options['output_html'] === 'html5' ) {
					// HTML5
					$output = $this->_get_html5_output( $options );
				} else {
					// XHTML1 or HTML4
					$output = $this->_get_xhtml_output( $options );
				}

				echo $output;
			}
		}
	}

	/**
	 * Prepare xhtml output (also used for html4)
	 * @param  arr    $options Array of admin options
	 * @return str             The final meta tags
	 */
	private function _get_xhtml_output( $options ) {
		// HTML4 or XHTML1
		if ( $options['output_html'] === 'html4' ) {
			$line_ending = ">\n";
		} else {
			$line_ending = " />\n";
		}

		$dc_properties = $this->get_dc_properties();
		$output = '';

		$schemes = array (
			'date'       => 'dc.w3cdtf',
			'format'     => 'dcterms.imt',
			'identifier' => 'dcterms.uri',
			'language'   => 'dcterms.rfc4646',
			'rights'     => 'dcterms.uri',
			'type'       => 'DCMIType',
		);
		
		$dcterms = array (
			'identifier',
			'rights'
		);

		$output .= '<link rel="schema.DC" href="http://purl.org/DC/elements/1.1/"' . $line_ending;
		foreach ( $dc_properties as $name => $value ) {
			$dc     = in_array( $name, $dcterms ) ? 'dcterms' : 'dc';
			$scheme =  array_key_exists( $name, $schemes ) ? ' scheme="' . $schemes[$name] . '"' : '';
			if ( !empty( $value ) ) {
				if ( is_array($value) ) {
					foreach ( $value as $val )
						$output .= '<meta name="' . $dc . '.' . ucwords( $name ) . '"'. $scheme . ' content="' . $val . '"' . $line_ending;
				} else {
					$output .= '<meta name="' . $dc . '.' . ucwords( $name ) . '"'. $scheme . ' content="' . $value . '"' . $line_ending;
				}
			}
		}

		return $output;
	}

	/**
	 * Prepare html5 output
	 * @param  arr    $options Array of admin options
	 * @return str             The final meta tags
	 */
	private function _get_html5_output( $options ) {
		$dc_properties = $this->get_dc_properties();
		$output = '';

		if ( defined( 'DCM_HTML5_CLOSING_SLASH') && DCM_HTML5_CLOSING_SLASH === false ) {
			$line_ending = ">\n";	
		} else {
			$line_ending = " />\n";
		}

		foreach ( $dc_properties as $name => $value ) {
			if ( !empty( $value ) ) {
				if ( is_array($value) ) {
					foreach ( $value as $val )
						$output .= '<meta name="dcterms.' . $name . '" content="' . $val . '"' . $line_ending;
				} else {
					$output .= '<meta name="dcterms.' . $name . '" content="' . $value . '"' . $line_ending;
				}
			}
		}

		return $output;
	}

	/**
	 * Get the dublin core elements and their values
	 * @return arr Array of dublin core elements with their values
	 */
	private function get_dc_properties() {
		$DCM_format = new DCM_Format;
		$options    = get_dcm_options();

		$dc_properties = array(
			'contributor'   => !empty( $options['elem_contributor'] ) ? $DCM_format->get_the_elem_value( 'elem_contributor' ) : '',
			'coverage'      => !empty( $options['elem_coverage'] ) ? $DCM_format->get_the_elem_value( 'elem_coverage' ) : '',
			'creator'       => !empty( $options['elem_creator'] ) ? $DCM_format->get_the_elem_value( 'elem_creator' ) : '',
			'date'          => !empty( $options['elem_date'] ) ? $DCM_format->get_the_elem_value( 'elem_date' ) : '',
			'description'   => !empty( $options['elem_description'] ) ? $DCM_format->get_the_elem_value( 'elem_description' ) : '',
			'format'        => !empty( $options['elem_format'] ) ? $DCM_format->get_the_elem_value( 'elem_format' ) : '',
			'identifier'    => !empty( $options['elem_identifier'] ) ? $DCM_format->get_the_elem_value( 'elem_identifier' ) : '',
			'language'      => !empty( $options['elem_language'] ) ? $DCM_format->get_the_elem_value( 'elem_language' ) : '',
			'publisher'     => !empty( $options['elem_publisher'] ) ? $DCM_format->get_the_elem_value( 'elem_publisher' ) : '',
			'relation'      => !empty( $options['elem_publisher'] ) ? $DCM_format->get_the_elem_value( 'elem_relation' ) : '',
			'rights'        => !empty( $options['elem_rights'] ) ? $DCM_format->get_the_elem_value( 'elem_rights' ) : '',
			'source'        => !empty( $options['elem_source'] ) ? $DCM_format->get_the_elem_value( 'elem_source' ) : '',
			'subject'       => !empty( $options['elem_subject'] ) ? $DCM_format->get_the_elem_value( 'elem_subject' ) : '',
			'title'         => !empty( $options['elem_title'] ) ? $DCM_format->get_the_elem_value( 'elem_title' ) : '',
			'type'          => !empty( $options['elem_type'] ) ? $DCM_format->get_the_elem_value( 'elem_type' ) : '',
		);
		return $dc_properties;
	}
}

global $dcm_front;
$dcm_front = new DCM_Frontend;