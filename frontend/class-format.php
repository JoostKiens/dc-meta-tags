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
	 * @param  boolean $single Is the result single (string) or multiple (array)
	 * @return str/arr         DC meta value(s)
	 */
	public function get_the_elem_value( $elem, $single = true ) {
		$post = $this->get_the_post();
		$value = get_post_meta( get_the_ID() , '_joost_dcm_' . $elem , $single );

		if ( !$value ) {
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
			$default_val = 'get_default_' . $elem;
			return $this->$default_val( $post );
		} else {
			return '';
		}
	}

	/**
	 * Returns default contributor (empty string)
	 * @return string Empty
	 */
	private function get_default_elem_contributor( $post ) {
		return '';
	}

	/**
	 * Returns the default coverage (World)
	 * @return string 'World'
	 */
	private function get_default_elem_coverage( $post ) {
		return 'World';
	}

	/**
	 * Returns the author name
	 * @return string Author name (last name, first name)
	 */
	private function get_default_elem_creator( $post ) {
		return get_the_author_meta('last_name', $post->post_author) . ', ' . get_the_author_meta('first_name', $post->post_author);
	}

	/**
	 * Returns the publication date
	 * @return string ISO 8601 formatted time of publication
	 */
	private function get_default_elem_date( $post ) {
		return get_the_time( 'c' , $post );
	}

	/**
	 * Get the description of a post or page, based on the content or the excerpt
	 * @param  object $post The global post object
	 * @return string The description
	 */
	private function get_default_elem_description( $post ) {
		$description = $this->dcm_excerpt( $post->post_content, $post->post_excerpt );
		$description = strip_tags( $description );
		$description = str_replace( "\"", "'", $description );
		return $description;
	}

	/**
	 * Returns the site's format
	 * @return string format
	 */
	private function get_default_elem_format( $post ) {
		return !empty($post->post_mime_type) ? $post->post_mime_type : 'text/html';
	}

	/**
	 * Returns the current pretty permalink
	 * @return string current URL
	 */
	private function get_default_elem_identifier( $post ) {
		return get_permalink();
	}

	/**
	 * Returns the site's default language
	 * @return string RFC4646 formatted site language
	 */
	private function get_default_elem_language( $post ) {
		return get_bloginfo( 'language' );
	}

	/**
	 * Returns the site's name
	 * @return string Site name
	 */
	private function get_default_elem_publisher( $post ) {
		return get_bloginfo( 'site_name' );
	}

	/**
	 * Returns default relation (empty array)
	 * @return string Empty
	 */
	private function get_default_elem_relation( $post ) {
		return array();
	}

	/**
	 * Returns the rights statement of the content
	 * @return string Rights statement
	 */
	private function get_default_elem_rights( $post ) {

		// In case the  creative commons configurator plugin is used
		if ( function_exists( 'bccl_get_license_url' ) ) {
			return bccl_get_license_url();
		}
		$rights = $this->get_options( 'elem_rights' );
		$rights_url = $this->get_options( 'rights_url' );
		if ( !empty( $rights ) && !empty( $rights_url ) ) {
			return $rights_url;
		}
	}

	/**
	 * Returns default source (empty array)
	 * @return string Empty
	 */
	private function get_default_elem_source( $post ) {
		return array();
	}

	/**
	 * Returns keywords for the post, only works for single posts & custom post types
	 * @return string Comma separated categories & tags
	 */
	private function get_default_elem_subject( $post ) {
		$keywords = '';
		$keywords .= strtolower(  $this->dcm_get_categories_string( $post ) );
		$tags      = strtolower( $this->dcm_get_tags_string( $post ) );

		if (!empty($tags))
			$keywords .= ", " . $tags;

		return $keywords;
	}

	/**
	 * Returns the post title
	 * @param  object $post The global post object
	 * @return string       The current post's title
	 */
	public function get_default_elem_title( $post ) {
		return $post->post_title;
	}

	/**
	 * Returns DCMI controlled Type vocabulary identifier of content type
	 * TODO: check if image, video, etc need a specific type
	 * 
	 * @return string DCMI controlled Type vocabulary
	 */
	public function get_default_elem_type( $post ) {
		return 'Text';
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

	/* Create the excerpt of the post
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