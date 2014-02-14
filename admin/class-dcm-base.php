<?php
/**
 * @package Base
 */

if ( !defined( 'DCM_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/*
 * DCM_Base is the base class for all other DCM classes
 * Performs auxilliary functions
 */
class DCM_Base {

	public function __construct() {
		// TODO: load options here, for all classes
	}
	
	/**
	 * Prints the localized label for the specified metadata
	 * @param  str $meta The metadata
	 * @return nothing
	 */
	static function the_label( $meta ) {
		echo( self::get_the_label( $meta ) );
	}

	/**
	 * Returns the localized label for the specified metadata
	 * @param  str $meta The metadata
	 * @return str The meta label
	 */
	static function get_the_label( $meta ) {
		switch( $meta ) {
		case 'contributor':
			return __("Contributor", 'dc-meta-tags' );
		case 'coverage':
			return __("Coverage", 'dc-meta-tags' );
		case 'creator':
			return __("Creator", 'dc-meta-tags' );
		case 'date':
			return __("Date", 'dc-meta-tags' );
		case 'description':
			return __("Description", 'dc-meta-tags' );
		case 'format':
			return __("Format", 'dc-meta-tags' );
		case 'identifier':
			return __("Identifier", 'dc-meta-tags' );
		case 'language':
			return __("Language", 'dc-meta-tags' );
		case 'publisher':
			return __("Publisher", 'dc-meta-tags' );
		case 'relation':
			return __("Relation", 'dc-meta-tags' );
		case 'rights':
			return __("Rights", 'dc-meta-tags' );
		case 'source':
			return __("Source", 'dc-meta-tags' );
		case 'subject':
			return __("Subject", 'dc-meta-tags' );
		case 'title':
			return __("Title", 'dc-meta-tags' );
		case 'type':
			return __("Type", 'dc-meta-tags' );
		}
	}

	/**
	 * Prints a localized tooltip with the descrition for the specified metadata
	 * @param  str $meta The metadata
	 * @return str The meta tooltip text
	 */
	static function the_tooltip( $meta ) {
		self::any_tooltip( self::get_the_definition( $meta ) );
	}
	
	/**
	 * Print a question mark tooltip
	 * @param  str  $str The title text for the tooltip
	 * @param  str  $tip The tooltip indicator (default: '?')
	 * @return void
	 */
	static function any_tooltip( $str, $tip = '?' ) {
		printf( '<span class="icon-question" alt="%1$s" title="%1$s">%2$s</span>', $str, $tip );
	}

	/**
	 * Returns the localized definition for the specified metadata
	 * @param  str $meta The metadata
	 * @return str The meta tooltip text
	 */
	static function get_the_definition( $meta ) {
		switch( $meta ) {
		case 'contributor':
			// Translators: official definition for the Dublin Core field Contributor
			return __('An entity responsible for making contributions to the resource', 'dc-meta-tags');
		case 'coverage':
			// Translators: official definition for the Dublin Core field Coverage
			return __('The spatial or temporal topic of the resource, the spatial applicability of the resource, or the jurisdiction under which the resource is relevant', 'dc-meta-tags');
		case 'creator':
			// Translators: official definition for the Dublin Core field Creator
			return __('An entity primarily responsible for making the resource', 'dc-meta-tags');
		case 'date':
			// Translators: official definition for the Dublin Core field Date
			return __('A point or period of time associated with an event in the lifecycle of the resource', 'dc-meta-tags');
		case 'description':
			// Translators: official definition for the Dublin Core field Description
			return __('An account of the resource', 'dc-meta-tags');
		case 'format':
			// Translators: official definition for the Dublin Core field Format
			return __('The file format, physical medium, or dimensions of the resource', 'dc-meta-tags');
		case 'identifier':
			// Translators: official definition for the Dublin Core field Identifier
			return __('An unambiguous reference to the resource within a given context', 'dc-meta-tags');
		case 'language':
			// Translators: official definition for the Dublin Core field Language
			return __('A language of the resource', 'dc-meta-tags');
		case 'publisher':
			// Translators: official definition for the Dublin Core field Publisher
			return __('An entity responsible for making the resource available', 'dc-meta-tags');
		case 'relation':
			// Translators: official definition for the Dublin Core field Relation
			return __('A related resource', 'dc-meta-tags');
		case 'rights':
			// Translators: official definition for the Dublin Core field Rights
			return __('Information about rights held in and over the resource', 'dc-meta-tags');
		case 'source':
			// Translators: official definition for the Dublin Core field Source
			return __('A related resource from which the described resource is derived', 'dc-meta-tags');
		case 'subject':
			// Translators: official definition for the Dublin Core field Subject
			return __('The topic of the resource', 'dc-meta-tags');
		case 'title':
			// Translators: official definition for the Dublin Core field Title
			return __('A name given to the resource', 'dc-meta-tags');
		case 'type':
			// Translators: official definition for the Dublin Core field Type
			return __('The nature or genre of the resource', 'dc-meta-tags');
		}
	}
	
	/**
	 * Prints "Add another" for the specified metadata
	 * At the moment, it's only defined for the multivalue fields
	 * @param  str $meta The metadata
	 * @return nothing
	 */
	static function add_another_field( $meta ) {
		switch( $meta ) {
		case 'contributor':
			_e('Add another contributor', 'dc-meta-tags');
			return;
		case 'coverage':
			_e('Add another coverage', 'dc-meta-tags');
			return;
		case 'creator':
			_e('Add another creator', 'dc-meta-tags');
			return;
		case 'date':
			_e('Add another date', 'dc-meta-tags');
			return;
		case 'description':
			_e('Add another description', 'dc-meta-tags');
			return;
		case 'format':
			_e('Add another format', 'dc-meta-tags');
			return;
		case 'identifier':
			_e('Add another identifier', 'dc-meta-tags');
			return;
		case 'language':
			_e('Add another language', 'dc-meta-tags');
			return;
		case 'publisher':
			_e('Add another publisher', 'dc-meta-tags');
			return;
		case 'relation':
			_e('Add another relation', 'dc-meta-tags');
			return;
		case 'rights':
			_e('Add another rights statement', 'dc-meta-tags');
			return;
		case 'source':
			_e('Add another source', 'dc-meta-tags');
			return;
		case 'subject':
			_e('Add another subject', 'dc-meta-tags');
			return;
		case 'title':
			_e('Add another title', 'dc-meta-tags');
			return;
		case 'type':
			_e('Add another type', 'dc-meta-tags');
			return;
		}
	}
	
	/* Tests whether post data has any data values for this key
	 * @param  str    $key       The key to check
	 * @param  array  $postdata  The post data
	 * @return bool              True if there is post data for that key
	 */
	static function has_data( $key, $postdata ) {
		// there is data if the key exists in postdata...
		return array_key_exists( $key, $postdata )
			// and the data is not an empty array...
			&& ( $postdata[ $key ] !== array() )
			// and the data is either not an array, or it has a non-empty first value
			&& ( !is_array( $postdata[ $key ] ) || ( $postdata[ $key ][0] !== '' ) );
	}

	/**
	 * Prints a settings message
	 * @param   str $msg   The text of the message
	 * @param   str $class The type of message: 'updated' (default) or 'error'
	 * @return  void
	 */
	static function settings_message( $msg, $class = 'updated' ) {
		echo( "<div class='$class settings-error'><p><strong>$msg</strong></p></div>\n" );
	}

}
