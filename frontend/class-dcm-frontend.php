<?php
/**
 * @package Frontend
 */

if ( !defined( 'DCM_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

class DCM_Frontend extends DCM_Base {

	public function __construct() {
		parent::__construct();
		// Add metadata to wp_head
		add_action( 'wp_head', array( $this, 'dcm_add_meta' ) );
	}

	/**
	 * Add Dublin Core Metadata to head
	 * @return void
	 */
	public function dcm_add_meta() {
		$this->init_vars();
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
		$dc_properties = $this->get_head_properties();

		$output = '<link rel="schema.DC" href="http://purl.org/DC/elements/1.1/"' . $line_ending;
		foreach ( $dc_properties as $name => $value ) {
			$dc     = in_array( $name, $dcterms ) ? 'dcterms' : 'dc';
			$scheme = array_key_exists( $name, $schemes ) ? ' scheme="' . $schemes[$name] . '"' : '';
			
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
		$dc_properties = $this->get_head_properties();

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
	 * Get the Dublin Core elements and their values for the <head> section
	 * @return arr Array of Dublin Core elements with their values
	 */
	private function get_head_properties() {
		$this->init_vars();
		$head = array();
		$head['my-excerpt'] = $this->generate_excerpt();
		foreach( $this->fields as $field ) {
			$mode = $this->options[ $field.'_mode' ];
			if( $mode === 'disabled' )
				continue;
			if( $mode === 'editable' ) {
				$values = $this->get_field_db_value( $field );
				if( $values ) {
					$head[ $field ] = $values;
					continue;
				}
			}
			// at this point, we rely on the default value:
			// the field is either default only, or editable but without a value
			$value = $this->get_field_default_value( $field );
			if( $value )
				$head[ $field ] = $value;
		}
		return $head;
	}
}

global $dcm_front;
$dcm_front = new DCM_Frontend;