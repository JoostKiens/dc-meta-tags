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
class DCM_Meta_box {

	/**
	 * Class constructor
	 */
	public function __construct() {
		// TODO remove values when uninstall
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_postdata' ) );
		$this->DCM_format = new DCM_format;
		$this->options = get_dcm_options();

		$this->meta_box_elem = array(
			'elem_contributor'  => false,
			'elem_coverage'     => true,
			'elem_creator'      => true,
			'elem_description'  => true,
			'elem_language'     => true,
			'elem_publisher'    => true,
			'elem_relation'     => false,
			'elem_rights'       => true,
			'elem_source'       => false,
			'elem_subject'      => true,
			'elem_title'        => true,
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
		wp_nonce_field( plugin_basename( __FILE__ ), 'dcm_nonce' );

		$values = array();
		foreach ( $this->meta_box_elem as $elem => $single ) {
			$values[$elem] = $this->DCM_format->get_the_elem_value( $elem );
		}
		include( DCM_PATH . '/admin/meta/dcm-meta-box.php' );
	}

	/**
	 * When the post is saved, saves our data
	 * @param  int    $post_id The ID of the post
	 * @return void
	 */
	public function save_postdata( $post_id ) {
		// verify if this is an auto save routine. 
		// If it is, our form has not been submitted, 
		// so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['dcm_nonce'], plugin_basename( __FILE__ ) ) )
			return;

		// Check permissions
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
				return;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
		}

		// Still here? Find to find and save the data
		foreach ( $this->meta_box_elem as $elem => $single ) {
			if ( $single ) {
				$mydata = sanitize_text_field( $_POST['dcm_' . $elem] );
			} else {
				$mydata = array();
				foreach( $_POST['dcm_' . $elem] as $val ) {
					$mydata[] = sanitize_text_field( $val );
				}
			}
			
			dcm_set_value( $elem, $mydata, $_POST['post_ID'] );
		}
	}

}

// Globalize the var first as it's needed globally.
global $dcm_meta_box;
$dcm_meta_box = new DCM_Meta_box();