<?php
/**
 * @package Admin
 */

if ( !defined( 'DCM_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/**
 * Class that holds the DC meta box on the post/page/cpt screens
 */
class DCM_Meta_box extends DCM_Base {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();

		// TODO remove values when uninstall
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_postdata' ) );
		$this->DCM_format = new DCM_format;
		$this->options = get_dcm_options();

		// elements to save
		// TODO remove all these multiple lists of elements
		$this->meta_box_elem = array(
			'elem_contributor',
			'elem_coverage',
			'elem_creator',
			'elem_date',
			'elem_description',
			'elem_format',
			'elem_identifier',
			'elem_language',
			'elem_publisher',
			'elem_relation',
			'elem_rights',
			'elem_source',
			'elem_subject',
			'elem_title',
			'elem_type',
		);
	}

	/**
	 * Add the meta box
	 * @return  void
	 */
	public function add_meta_box() {
		foreach ( $this->options['post_types'] as $post_type ) {
			add_meta_box(
				'dcm',
				__( 'Dublin Core Metadata', 'dc-meta-tags' ),
				array( $this, 'render_meta_box_content' ),
				$post_type
			);
		}
	}

	/**
	 * Render the meta box content
	 * @param  obj    $post The main post object
	 * @return void
	 */
	public function render_meta_box_content( $post ) {
		// Use nonce for verification
		wp_nonce_field( DCM_BASENAME, 'dcm_nonce' );
		// Translators: link to the Dublin Core Element Set definition
		$link = __( 'http://dublincore.org/documents/dces/', 'dc-meta-tags' );
		$text = __( 'Dublin Core specification and information about recommended values', 'dc-meta-tags' );
		$neww = __( '(opens in a new window)', 'dc-meta-tags');
		echo( "<p><a href=\"$link\" target=\"_blank\">$text</a> $neww<p>\n" );
		echo( "<table class=\"form-table\"><tbody>\n" );

		$this->render_field( 'contributor' );
		$this->render_field( 'coverage' );
		$this->render_field( 'creator' );
		$this->render_field( 'date' );
		$this->render_field( 'description' );
		$this->render_field( 'format' );
		$this->render_field( 'identifier' );
		$this->render_field( 'language' );
		$this->render_field( 'publisher' );
		$this->render_field( 'relation' );
		$this->render_field( 'rights' );
		$this->render_field( 'source' );
		$this->render_field( 'subject' );
		$this->render_field( 'title' );
		$this->render_field( 'type' );

		echo( "</tbody></table>\n" );
	}

	/**
	 * Render one field of the meta box
	 * @param  str    $field The name of the field, e.g. 'contributor'
	 * @return void
	 */
	public function render_field( $field ) {
		$key = 'elem_'.$field;
		$values = $this->DCM_format->get_the_elem_value( $field );
		if( $this->options[ $key ] == 1 ) {
		
			printf( "<tr>\n\t<th scope=\"row\">\t\t<label for=\"elem_%s[0]\">", $field );
			DCM_Base::the_label( $field );
			echo( "</label>\n" );
			DCM_Base::the_tooltip( $field );
			echo( "\n</th>\n" );
			echo( "<td valign=\"top\">" );
			echo( "<ul id=\"elem_$field-repeatable\" class=\"custom_repeatable\">" );
			$i = 0;

			// convert single value to array, if necessary (always array since 0.3.0)
			if( !is_array( $values ) ) {
				$values = array( $values );
			}

			foreach( $values as $value ) {
				echo( "<li>" );
				printf( '<input type="text" name="dcm_elem_%1$s[%2$d]" id="elem_%1$s[%2$d]" value="%3$s" size="25" /> ', $field, $i, $value );
				printf( '<a class="repeatable-remove button" href="#">%s</a>', __('Remove', 'dc-meta-tags') );
				echo( "</li>" );
				$i++;
			}
	
			echo( "</ul>" );
			echo( "<a class=\"repeatable-add button\" href=\"#\">" );
			DCM_Base::add_another_field( $field );
			echo( "</a>\n" );
			echo( "</td>" );
			echo( "</tr>" );
		}
	}

	/**
	 * When the post is saved, saves our data
	 * @param  int    $post_id The ID of the post
	 * @return void
	 */
	public function save_postdata( $post_id ) {
	
		// verify if this is an auto save routine. 
		// If it is, our form has not been submitted, 
		// so we don't want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['dcm_nonce'], DCM_BASENAME ) )
			return;

		// Check permissions
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
				return;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
		}

		// Still here? Find and save the data
		foreach ( $this->meta_box_elem as $elem ) {
			$key = 'dcm_' . $elem;
			if( !DCM_Base::has_data( $key, $_POST ) ) {
				// there's no data, remove meta
				dcm_delete_meta( $elem, $_POST['post_ID'] );
				continue;
			}
			// we have meta, sanitize and update
			$mydata = array();
			foreach( $_POST[ $key ] as $val ) {
				$mydata[] = sanitize_text_field( $val );
			}
			dcm_set_value( $elem, $mydata, $_POST['post_ID'] );
		}
	}
	
}

// Globalize the var first as it's needed globally.
global $dcm_meta_box;
$dcm_meta_box = new DCM_Meta_box();
