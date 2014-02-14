<?php
/**
 * @package Format
 */

if ( !defined('DCM_VERSION') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}

/**
 * This code handles the formatting
 * An instance is created as a member of DCM_Frontend
 */
class DCM_Format {

	/**
	 * Class constructor
	 */
	function __construct() {
	}

	/**
	 * Get the value of the DC meta elements
	 * @param  str     $elem   DC meta element
	 * @return str/arr         DC meta value(s)
	 */
	public function get_the_elem_value( $elem ) {
		$value = dcm_get_value( $elem, get_the_ID() );
		if ( !$value ) {
			$post  = $this->get_the_post();
			$value = $this->get_alternate_meta_value( $elem, $post );
		}
		return $value;
	}

	/**
	 * Get the current post
	 * @return obj  The post
	 */
	private function get_the_post() {
		global $post;
		if ( $post === null ) {
			$post = get_post( get_the_ID() );
		}
		return $post;
	}

	/**
	 * Get the alternate value if the post meta is empty (doesn't exist or is an empty string)
	 * If the post meta doesn't exist, show default value, else return an empty string
	 * @param  str    $elem DC meta element
	 * @return str          DC meta value
	 */
	private function get_alternate_meta_value( $elem, $post ) {
		if ( !array_key_exists('_joost_dcm_' . $elem, get_post_meta( get_the_ID() ) ) ) {
			return $this->get_default_value( $elem, $post );
		} else {
			return '';
		}
	}

	private function get_default_value( $elem, $post ) {
		switch( $elem ) {
		
		case 'contributor':
			return '';
		
		case 'coverage':
			// Translators: default value for Coverage
			return __( 'World', 'dc-meta-tags' );
		
		case 'creator':
			$lname = get_the_author_meta('last_name', $post->post_author);
			$fname = get_the_author_meta('first_name', $post->post_author);
			// Translators: mask for default Creator; 1 is family name, 2 is first name
			return sprintf( __( '%1$s, %2$s', 'dc-meta-tags' ), $lname, $fname);
		
		case 'date':
			// ISO 8601 formatted time of publication
			return get_the_time( 'c' , $post );
		
		case 'description':
			// default description is based on the content or the excerpt
			$description = $this->dcm_excerpt( $post->post_content, $post->post_excerpt );
			$description = strip_tags( $description );
			$description = str_replace( "\"", "'", $description );
			return $description;
		
		case 'format':
			// the post's MIME type
			return !empty($post->post_mime_type) ? $post->post_mime_type : 'text/html';
		
		case 'identifier':
			return get_permalink();
		
		case 'language':
			return get_bloginfo( 'language' );
		
		case 'publisher':
			return get_bloginfo( 'site_name' );
		
		case 'relation':
			return ''; // used to be empty array, array()
		
		case 'rights':
			// In case the  creative commons configurator plugin is used
			if ( function_exists( 'bccl_get_license_url' ) ) {
				return bccl_get_license_url();
			}
			$rights = $this->get_options( 'elem_rights' );
			$rights_url = $this->get_options( 'rights_url' );
			if ( !empty( $rights ) && !empty( $rights_url ) ) {
				return $rights_url;
			}
			return '';

		case 'source':
			return ''; // used to be empty array, array()
		
		case 'subject':
			// A comma-separated list of categories and tags
			$keywords = strtolower(  $this->dcm_get_categories_string( $post ) );
			$tags     = strtolower( $this->dcm_get_tags_string( $post ) );
			if (!empty($tags))
				$keywords .= ", " . $tags;
			return $keywords;

		case 'title':
			return $post->post_title;

		case 'type':
			// Returns DCMI controlled Type vocabulary identifier of content type
			// TODO: check if image, video, etc need a specific type
			return 'Text';

		default:
			return "ERROR: unknown element '$elem'";
		}

	}

	/**
	 * Return options (admin settings > dc meta tags) for an element
	 * @param  boolean/str $option Option name
	 * @return str                 Option value
	 */
	private function get_options( $option = false )  {
		$options = get_dcm_options();
		return $option ? $options[$option] : $option;
	}

	/**
	 * Create the excerpt of the post
	 * @param  string/bool $content Content of the post
	 * @param  string/bool $excerpt Excerpt of the post
	 * @return string/bool          Custom excerpt
	 */
	private function dcm_excerpt( $content, $excerpt ) {

		if ( $excerpt )
			return $excerpt;

		$content = strip_shortcodes( $content );
		$content = apply_filters('the_content', $content);
		$content = strip_tags($content);

		return wp_trim_words( $content, $num_words = 55, $more = null );
	}

	/**
	 * Get categories of a post, only works for single posts & custom post types
	 * @return string Comma separated categories
	 */
	private function dcm_get_categories_string( $post ) {
		$categories      = array();
		$post_categories = get_the_category( $post->ID );

		if ( $post_categories ) {
			foreach( $post_categories as $category ) {
				$categories[] = $category->cat_name;
			}

			return join( ', ', $categories );
		}
	}

	/**
	 * Get tags of a post, only works for single posts & custom post types
	 * @return string Comma separated tags
	 */
	private function dcm_get_tags_string( $post ) {

		$tags      = array();
		$post_tags = get_the_tags( $post->ID );

		if ( $post_tags ) {
			foreach( $post_tags as $tag ) {
				$tags[] = $tag->name;
			}
			return join( ', ', $tags );
		}
	}
}