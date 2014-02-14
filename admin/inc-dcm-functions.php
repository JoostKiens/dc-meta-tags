<?php


/**
 * Get the value from the post custom values
 *
 * @param string $val    name of the value to get
 * @param int    $postid post ID of the post to get the value for
 * @return bool|mixed
 */
function dcm_get_value( $key, $postid = 0 ) {
	$postid = absint( $postid );
	if ( $postid === 0 ) {
		global $post;
		if ( isset( $post ) && isset( $post->post_status ) && $post->post_status != 'auto-draft')
			$postid = $post->ID;
		else
			return false;
	}
	$value = get_post_meta( $postid, '_joost_dcm_elem_' . $key, true );
	if ( !empty( $value ) )
		return $value;
	else
		return false;
}

/**
 * @param string $meta   the meta to change
 * @param mixed  $val    the value to set the meta to
 * @param int    $postid the ID of the post to change the meta for.
 */
function dcm_set_value( $meta, $val, $postid ) {
	update_post_meta( $postid, '_joost_dcm_' . $meta, $val );
}

/**
 * @param string $meta   the meta to change
 * @param mixed  $val    the value to set the meta to
 * @param int    $postid the ID of the post to change the meta for.
 */
function dcm_delete_meta( $meta, $postid ) {
	delete_post_meta( $postid, '_joost_dcm_' . $meta );
}

/**
 * Retrieve an array of all the options the plugin uses.
 *
 * @return array of options.
 */
function get_dcm_options_arr() {
	$options_arr = array( 
		'_joost_dcm_options', 
	);
	return apply_filters( 'dcm_options', $options_arr );
}

/**
 * Retrieve all the options for the plugin
 *
 * @return array of options
 */
function get_dcm_options() {
	$options = array();
	foreach ( get_dcm_options_arr() as $opt ) {
		$options = array_merge( $options, (array) get_option( $opt ) );
	}
	return $options;
}
