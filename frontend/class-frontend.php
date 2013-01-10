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
		// Add meta data to wp_head
	
		$this->options = get_dcm_options();
		add_action( 'wp_head', array( $this, 'dcm_add_meta' ) );
	}

	/**
	 * Add Dublin Core Meta Data to head
	 * @return void
	 */
	public function dcm_add_meta() {
		if ( !empty ( $this->options ) && in_array( get_post_type(), $this->options['post_types'] ) && ( is_single() || is_page() ) ) {
			if ( $this->options['output_html'] === 'html5' ) {
				// HTML5
				$output = $this->_get_html5_output();
			} else {
				// XHTML1 or HTML4
				$output = $this->_get_xhtml_output();
			}

			echo $output;
		}
	}

	/**
	 * Prepare xhtml output (also used for html4)
	 * @return str             The final meta tags
	 */
	private function _get_xhtml_output( ) {

		// Elements which have a scheme attribute
		// Elements are the keys, 
		// scheme attribute values are the values
		$schemes = array (
			'date'       => 'dc.w3cdtf',
			'format'     => 'dcterms.imt',
			'identifier' => 'dcterms.uri',
			'language'   => 'dcterms.rfc4646',
			'rights'     => 'dcterms.uri',
			'type'       => 'DCMIType',
		);
		
		// Elements which use dcterms. instead of dc. as prefix
		$dcterms = array (
			'identifier',
			'rights'
		);

		// HTML4 or XHTML style line endings
		$line_ending = ( $this->options['output_html'] === 'html4' ) ? ">\n" : " />\n";

		// The meta values
		$dc_properties = $this->get_dc_properties();

		$output = '<link rel="schema.DC" href="http://purl.org/DC/elements/1.1/"' . $line_ending;
		foreach ( $dc_properties as $name => $value ) {
			$dc     = in_array( $name, $dcterms ) ? 'dcterms' : 'dc';
			$scheme =  array_key_exists( $name, $schemes ) ? ' scheme="' . $schemes[$name] . '"' : '';
			
			if ( is_array($value) ) {
				foreach ( $value as $val ) {
					if ( !empty( $val ) ) {
						$output .= '<meta name="' . $dc . '.' . ucwords( $name ) . '"'. $scheme . ' content="' . $val . '"' . $line_ending;
					}
				}
					
			} else {
				if ( !empty( $value ) ) {
					$output .= '<meta name="' . $dc . '.' . ucwords( $name ) . '"'. $scheme . ' content="' . $value . '"' . $line_ending;
				}
			}
		}

		return $output;
	}

	/**
	 * Prepare html5 output
	 * @return str             The final meta tags
	 */
	private function _get_html5_output() {

		// The meta values
		$dc_properties = $this->get_dc_properties();

		// Line endings: either HTML or XHTML style
		$line_ending = ( defined( 'DCM_HTML5_CLOSING_SLASH') && DCM_HTML5_CLOSING_SLASH === false ) ? ">\n" : " />\n";

		$output = '';

		foreach ( $dc_properties as $name => $value ) {
			if ( is_array($value) ) {
				foreach ( $value as $val ) {
					if ( !empty( $val ) ) {
						$output .= '<meta name="dcterms.' . $name . '" content="' . $val . '"' . $line_ending;
					}
				}
			} else {
				if ( !empty( $value ) ) {
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
		
		$dc_properties = array(
			'contributor'   => !empty( $this->options['elem_contributor'] ) ? $DCM_format->get_the_elem_value( 'elem_contributor' ) : '',
			'coverage'      => !empty( $this->options['elem_coverage'] ) ? $DCM_format->get_the_elem_value( 'elem_coverage' ) : '',
			'creator'       => !empty( $this->options['elem_creator'] ) ? $DCM_format->get_the_elem_value( 'elem_creator' ) : '',
			'date'          => !empty( $this->options['elem_date'] ) ? $DCM_format->get_the_elem_value( 'elem_date' ) : '',
			'description'   => !empty( $this->options['elem_description'] ) ? $DCM_format->get_the_elem_value( 'elem_description' ) : '',
			'format'        => !empty( $this->options['elem_format'] ) ? $DCM_format->get_the_elem_value( 'elem_format' ) : '',
			'identifier'    => !empty( $this->options['elem_identifier'] ) ? $DCM_format->get_the_elem_value( 'elem_identifier' ) : '',
			'language'      => !empty( $this->options['elem_language'] ) ? $DCM_format->get_the_elem_value( 'elem_language' ) : '',
			'publisher'     => !empty( $this->options['elem_publisher'] ) ? $DCM_format->get_the_elem_value( 'elem_publisher' ) : '',
			'relation'      => !empty( $this->options['elem_publisher'] ) ? $DCM_format->get_the_elem_value( 'elem_relation' ) : '',
			'rights'        => !empty( $this->options['elem_rights'] ) ? $DCM_format->get_the_elem_value( 'elem_rights' ) : '',
			'source'        => !empty( $this->options['elem_source'] ) ? $DCM_format->get_the_elem_value( 'elem_source' ) : '',
			'subject'       => !empty( $this->options['elem_subject'] ) ? $DCM_format->get_the_elem_value( 'elem_subject' ) : '',
			'title'         => !empty( $this->options['elem_title'] ) ? $DCM_format->get_the_elem_value( 'elem_title' ) : '',
			'type'          => !empty( $this->options['elem_type'] ) ? $DCM_format->get_the_elem_value( 'elem_type' ) : '',
		);
		return $dc_properties;
	}
}

global $dcm_front;
$dcm_front = new DCM_Frontend;