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
		global $post;
		$this->post = $post;
	}

	/**
	 * Get the description of a post or page, based on the content or the excerpt
	 * @param  object $post The global post object
	 * @return string The description
	 */
	public function get_the_description() {
		$description = $this->_dcm_excerpt( $this->post->post_content, $this->post->post_excerpt );
		$description = strip_tags( $description );
		$description = str_replace( "\"", "'", $description );
		return $description;
	}

	/**
	 * Returns the current pretty permalink
	 * @return string current URL
	 */
	public function get_permalink() {
		return get_permalink();
	}

	/**
	 * Returns the post title
	 * @param  object $post The global post object
	 * @return string       The current post's title
	 */
	public function get_the_title() {
		return $this->post->post_title;
	}

	/**
	 * Returns the author name
	 * @return string Author name (last name, first name)
	 */
	public function get_the_author() {
		return get_the_author_meta('last_name', $this->post->post_author) . ', ' . get_the_author_meta('first_name', $this->post->post_author);
	}

	/**
	 * Returns the site's name
	 * @return string Site name
	 */
	public function get_the_sitename() {
		return get_bloginfo( 'site_name' );
	}

	/**
	 * Returns the publication date
	 * @return string ISO 8601 formatted time of publication
	 */
	public function get_the_date() {
		return get_the_time( 'c' , $this->post);
	}

	/**
	 * Returns the site's default language
	 * @return string RFC4646 formatted site language
	 */
	public function get_the_language() {
		return get_bloginfo( 'language' );
	}

	/**
	 * Returns keywords
	 * @return string Comma separated categories & tags
	 */
	public function get_the_subject() {
		return $this->_dcm_get_content_keywords();
	}

	/**
	 * Returns the site's format
	 * TODO: Check if attachments need a separate format: image, video, etc.
	 * 
	 * @return string format
	 */
	public function get_the_format() {
		return get_bloginfo( 'html_type' );
	}

	/**
	 * Returns the rights statement of the content
	 * @return string Rights statement
	 */
	public function get_the_rights() {

		// In case the  creative commons configurator plugin is used
		if ( function_exists( 'bccl_get_license_url' ) ) {
			return bccl_get_license_url();
		}
		$rights = $this->_get_options( 'elem_rights' );
		$rights_url = $this->_get_options( 'rights_url' );
		if ( !empty( $rights ) && !empty( $rights_url ) ) {
			return $rights_url;
		}
	}

	/**
	 * Returns DCMI controlled Type vocabulary identifier of content type
	 * TODO: check if image, video, etc need a specific type
	 * 
	 * @return string DCMI controlled Type vocabulary
	 */
	public function get_the_type() {
		return 'Text';
	}
	private function _get_options( $option = false )  {
		$options = get_dcm_options();
		return $option ? $options[$option] : $option;
	}

	/**
	 * Create the excerpt of the post
	 * @param  string/bool $content Content of the post
	 * @param  string/bool $excerpt Excerpt of the post
	 * @return string/bool          Custom excerpt
	 */
	private function _dcm_excerpt( $content, $excerpt ) {

		if ( $excerpt )
			return $excerpt;

    	$content = strip_shortcodes( $content );
    	$content = apply_filters('the_content', $content);
    	$content = strip_tags($content);

    	return wp_trim_words( $content, $num_words = 55, $more = null );
	}

	/**
	 * Get keywords for the post, only works for single posts & custom post types
	 * @return string Comma separated categories & tags
	 */
	private function _dcm_get_content_keywords() {

	    $keywords = '';
	   
	    if ( is_single() ) {  // pages do not support categories and tags
			$keywords .= strtolower(  $this->_dcm_get_categories_string() );
			
			$tags = strtolower( $this->_dcm_get_tags_string() );

			if (!empty($tags)) {
				$keywords .= ", " . $tags;
			}
		}
		return $keywords;
	}

	/**
	 * Get categories of a post, only works for single posts & custom post types
	 * @return string Comma separated categories
	 */
	private function _dcm_get_categories_string() {
		$categories      = array();
		$post_categories = get_the_category( $this->post->ID );

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
	private function _dcm_get_tags_string() {

		$tags      = array();
		$post_tags = get_the_tags( $this->post->ID );

		if ( $post_tags ) {
			foreach( $post_tags as $tag ) {
				$tags[] = $tag->name;
			}

			return join( ', ', $tags );
		}
	}
}