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

	// these are filled in init_vars()
	protected $fields = false;           // the list of DCM fields
	protected $default_options = false;  // the default options
	protected $default_types = false;    // the type of default values accepted per field
	protected $options = false;          // the actual options
	protected $is_install;               // is this an install?
	protected $is_upgrade;               // is this an upgrade?
	protected $previous_version;         // the previous version number (if known)
	
	public function __construct() {
	}
	
	/**
	 * Gets options and default options, tests for install and upgrade
	 * This should be called whenever you need the options
	 * @param  nothing
	 * @return nothing
	 */
	function init_vars() {
		if( $this->fields !== false ) {
			return; // init_vars has already been run
		}
		// list of Dublin Core fields
		$this->fields = array(
			'contributor',
			'coverage',
			'creator',
			'date',
			'description',
			'format',
			'identifier',
			'language',
			'publisher',
			'relation',
			'rights',
			'source',
			'subject',
			'title',
			'type',
		);
		
		// *_mode can be: disabled, default, editable
		// *_default_type can be always be none, text, or:
		//   contributor_default_type: --
		//   coverage_default_type: world
		//   creator_default_type: post
		//   date_default_type: post
		//   description_default_type: excerpt
		//   format_default_type: post
		//   identifier_default_type: permalink
		//   language_default_type: wordpress
		//   publisher_default_type: website-title
		//   relation_default_type: --
		//   rights_default_type: ccc-plugin
		//   source_default_type: --
		//   subject_default_type: categories-tags
		//   title_default_type: post
		//   type_default_type: post
		//
			
		$this->default_types = array(
			'contributor' => array( 'none', 'text' ),
			'coverage'    => array( 'none', 'text', 'world' ),
			'creator'     => array( 'none', 'text', 'post' ),
			'date'        => array( 'none', 'text', 'post' ),
			'description' => array( 'none', 'text', 'excerpt' ),
			'format'      => array( 'none', 'text', 'mime-type' ),
			'identifier'  => array( 'none', 'text', 'permalink' ),
			'language'    => array( 'none', 'text', 'wordpress' ),
			'publisher'   => array( 'none', 'text', 'website-title' ),
			'relation'    => array( 'none', 'text' ),
			'rights'      => array( 'none', 'text', 'ccc-plugin' ),
			'source'      => array( 'none', 'text' ),
			'subject'     => array( 'none', 'text', 'categories-tags' ),
			'title'       => array( 'none', 'text', 'post' ),
			'type'        => array( 'none', 'text', 'post' ),
		);
		$this->default_options = array(
			'version' => DCM_VERSION,
			
			'contributor_mode' => 'editable',
			'coverage_mode' => 'editable',
			'creator_mode' => 'editable',
			'date_mode' => 'editable',
			'description_mode' => 'editable',
			'format_mode' => 'editable',
			'identifier_mode' => 'editable',
			'language_mode' => 'disabled',
			'publisher_mode' => 'editable',
			'relation_mode' => 'editable',
			'rights_mode' => 'editable',
			'source_mode' => 'editable',
			'subject_mode' => 'editable',
			'title_mode' => 'editable',
			'type_mode' => 'editable',
			
			'contributor_default_type' => 'none',
			'coverage_default_type' => 'world',
			'creator_default_type' => 'post',
			'date_default_type' => 'post',
			'description_default_type' => 'excerpt',
			'format_default_type' => 'mime-type',
			'identifier_default_type' => 'permalink',
			'language_default_type' => 'wordpress',
			'publisher_default_type' => 'website-title',
			'relation_default_type' => 'none',
			'rights_default_type' => 'ccc-plugin',
			'source_default_type' => 'none',
			'subject_default_type' => 'categories-tags',
			'title_default_type' => 'post',
			'type_default_type' => 'post',

			'contributor_default_value' => '',
			'coverage_default_value' => '',
			'creator_default_value' => '',
			'date_default_value' => '',
			'description_default_value' => '',
			'format_default_value' => '',
			'identifier_default_value' => '',
			'language_default_value' => '',
			'publisher_default_value' => '',
			'relation_default_value' => '',
			'rights_default_value' => '',
			'source_default_value' => '',
			'subject_default_value' => '',
			'title_default_value' => '',
			'type_default_value' => '',
			
			'output_html' => 'xhtml',
			'post_types' => self::list_post_types( false ),
		);

		// look for _joost_dcm_options (pre-0.4.0)
		$old_options = get_option( '_joost_dcm_options' );
		if( $old_options ) {
			$this->is_upgrade = true;
			$this->is_install = false;
			$this->previous_version = array_key_exists( 'version', $old_options ) ? $old_options['version'] : '';
			$this->get_pre_0_4_0_options( $old_options );
			return;
		}

		// try inserting default options; if success, this is a first run
		// (add_option doesn't update existing data, only inserts new)
		$this->is_install = add_option( DCM_OPTION_NAME, $this->default_options, "", "yes" );
		if( $this->is_install) {
			// on install, use default options
			$this->options = $this->default_options;
			$this->is_upgrade = false;
			$this->previous_version = '';
		}
		else {
			// not an install; get options
			$this->options = get_option( DCM_OPTION_NAME );
			$this->previous_version = array_key_exists( 'version', $this->options ) ? $this->options['version'] : '';
			// it's an upgrade if previous version number wasn't set,
			// or if version number is different (version numbers are set since 0.3.0)
			// note: using != operator instead of < to cover for possible downgrades
			$this->is_upgrade = !$this->previous_version
				|| version_compare( $this->previous_version, DCM_VERSION, '!=' );
			
			if( $this->is_upgrade )
				$this->options['version'] = DCM_VERSION; // set the current option
		}
	}
	
	/* Convert options from _joost_dcm_options to DCM_OPTION_NAME
	 * does not delete old options - see DCM_Upgrade::pre_0_4_0
	 * @param array $old The old set of _joost_dcm_options
	 * @return void
	 */
	function get_pre_0_4_0_options( $old ) {
		// start with the default options
		$new = $this->default_options;
		
		// override with the old mode: 0 -> disabled, 1-> editable
		foreach( $this->fields as $field ) {
			$new[$field.'_mode'] =
				(array_key_exists( 'elem_'.$field, $old ) && $old['elem_'.$field]=='1') ?
				'editable' : 'disabled';
		}
		// fill in old default values
		$new['rights_default_value'] = $old['rights_url'];
		// fill other options
		$new['output_html'] = $old['output_html'];
		$new['post_types'] = $old['post_types'];
		
		$this->options = $new;
		update_option( DCM_OPTION_NAME, $new, '', 'yes' );
	}

	/**
	 * Returns the value for an option
	 * @param  str $name  The name of the option
	 * @return mixed      The value for that option
	 */
	function get_option( $name ) {
		$this->init_vars();
		return isset( $this->options[$name] ) ? $this->options[$name] : '';
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
	 * Returns the localized label for the metadata field
	 * @param  str $field The field
	 * @return str The label
	 */
	static function get_the_label( $field ) {
		switch( $field ) {
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
	static function the_element_tooltip( $meta ) {
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
	 * Returns an array with the current post types as key and 
	 * either the name of the post type as value OR a 1
	 * @param   str $output_val If set to 'names' the returned array valus will the post type's name
	 * @return  arr           Custom post types
	 */
	public static function list_post_types( $output ) {
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
	 * Prints a settings message
	 * @param   str $msg   The text of the message
	 * @param   str $class The type of message: 'updated' (default) or 'error'
	 * @return  void
	 */
	static function settings_message( $msg, $class = 'updated' ) {
		echo( "<div class='$class settings-error'><p><strong>$msg</strong></p></div>\n" );
	}
	
	/**
	 * Gets the database value for a field
	 * @param  string $field         The name of the field
	 * @param  int    $postid        The post's ID
	 * @return mixed  The field's value
	 */
	function get_field_db_value( $field ) {
		return get_post_meta( get_the_ID(), DCM_FIELD_PREFIX.$field, true );
	}	
	
	/**
	 * Get the default value for a field
	 * Only works inside The Loop
	 * @param  str   $field  DC meta element
	 * @return str           A default value
	 */
	protected function get_field_default_value( $field ) {
		
		// check for default_types 'none' and 'text'
		if ( $this->options[$field.'_default_type'] == 'none' )
			return '';
		if ( $this->options[$field.'_default_type'] == 'text' )
			return $this->options[$field.'_default_value'];

		// now check every other default type, per element
		switch( $field ) {
		
		case 'contributor':
			return '';
		
		case 'coverage':
			if ( $this->options[$field.'_default_type'] == 'world' ) {
				// Translators: predefined default value for Coverage
				return __( 'World', 'dc-meta-tags' );
			}
			else return '';
		
		case 'creator':
			if ( $this->options[$field.'_default_type'] == 'post' ) {
				$lname = get_the_author_meta( 'last_name' );
				$fname = get_the_author_meta( 'first_name' );
				// Translators: 1 is family name, 2 is first name
				return sprintf( __( '%1$s, %2$s', 'dc-meta-tags' ), $lname, $fname);
			}
			else return '';
		
		case 'date':
			if ( $this->options[$field.'_default_type'] == 'post' ) {
				// ISO 8601 formatted time of publication
				return get_the_time( 'c' );
			}
			else return '';
		
		case 'description':
			if ( $this->options[$field.'_default_type'] == 'excerpt' ) {
				return $this->generate_excerpt();
			}
			else return '';
		
		case 'format':
			if ( $this->options[$field.'_default_type'] == 'mime-type' ) {
				return get_post_mime_type();
			}
			else return '';
		
		case 'identifier':
			if ( $this->options[$field.'_default_type'] == 'permalink' ) {
				return get_permalink();
			}
			else return '';
		
		case 'language':
			if ( $this->options[$field.'_default_type'] == 'wordpress' ) {
			return get_bloginfo( 'language' );
			}
			else return '';
		
		case 'publisher':
			if ( $this->options[$field.'_default_type'] == 'website-title' ) {
				return get_bloginfo( 'site_name' );
			}
			else return '';
		
		case 'relation':
			return '';
		
		case 'rights':
			if ( $this->options[$field.'_default_type'] == 'ccc-plugin' ) {
				// In case the Creative Commons Configurator plugin is used
				if( function_exists( 'bccl_get_license_url' ) )
					return bccl_get_license_url();
				else return '';
			}
			return '';

		case 'source':
			return '';
		
		case 'subject':
			if ( $this->options[$field.'_default_type'] == 'categories-tags' ) {
				// A comma-separated list of categories and tags
				// Translators: a separator between items on a list
				$sep = __( ', ', 'dc-meta-tags' );
				$cats = $this->get_categories_string( $sep );
				$tags = $this->get_tags_string( $sep );
				return $cats . ( $cats && $tags ? $sep : '' ) . $tags;
			}
			else return '';

		case 'title':
			if ( $this->options[$field.'_default_type'] == 'post' ) {
				return get_the_title();
			}
			else return '';

		case 'type':
			if ( $this->options[$field.'_default_type'] == 'post' ) {
				// Returns DCMI controlled Type vocabulary identifier of content type
				// TODO: check if image, video, etc need a specific type
				return 'Text';
			}
			else return '';

		default:
			return "ERROR: unknown element '$field'";
		}
	}

	/**
	 * Get categories of a post; only works for single posts & custom post types
	 * @param  string $sep  The separator
	 * @return string       The tags separated by $sep
	 */
	private function get_categories_string( $sep ) {
		$cats = '';
		$post_cats = get_the_category();
		if( $post_cats )
			foreach( $post_cats as $cat )
				$cats .= ( $cats ? $sep : '' ) . $cat->name;
		return $cats;
	}

	/**
	 * Get tags of a post; only works for single posts & custom post types
	 * @param  string $sep  The separator
	 * @return string       The tags separated by $sep
	 */
	private function get_tags_string( $sep ) {
		$tags = '';
		$post_tags = get_the_tags();
		if( $post_tags )
			foreach( $post_tags as $tag )
				$tags .= ( $tags ? $sep : '' ) . $tag->name;
		return $tags;
	}

	/**
	 * Create the excerpt of the post
	 * @param  string/bool $content Content of the post
	 * @param  string/bool $excerpt Excerpt of the post
	 * @return string/bool          Custom excerpt
	 */
	public function generate_excerpt() {
		$excerpt = get_the_excerpt();
		if( $excerpt )
			return $excerpt;
		
		// no excerpt, we’ll generate our own
		$content = get_the_content();
		$content = strip_shortcodes( $content );
		$content = apply_filters('the_content', $content);
		$content = strip_tags($content);
		return wp_trim_words( $content, $num_words = 55, $more = null );
	}
	
	/**
	 * Saves a DC field to the database
	 * @param int    $postid the ID of the post to change the meta for
	 * @param string $field  the metadata field to change
	 * @param mixed  $val    the value to set the meta to
	 */
	static function write_meta( $postid, $field, $val ) {
		update_post_meta( $postid, DCM_FIELD_PREFIX.$field, $val );
	}
	
	/**
	 * Deletes the value of a DC field from the database
	 * @param string $field  the metadata field to change
	 * @param int    $postid the ID of the post to change the meta for
	 */
	static function delete_meta( $postid, $field ) {
		delete_post_meta( $postid, DCM_FIELD_PREFIX.$field );
	}
}
