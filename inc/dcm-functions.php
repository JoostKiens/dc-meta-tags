<?php


/**
 * Get the value from the post custom values
 *
 * @param string $val    name of the value to get
 * @param int    $postid post ID of the post to get the value for
 * @return bool|mixed
 */
function dcm_get_value( $val, $postid = 0 ) {
	$postid = absint( $postid );
	if ( $postid === 0 ) {
		global $post;
		if ( isset( $post ) && isset( $post->post_status ) && $post->post_status != 'auto-draft')
			$postid = $post->ID;
		else
			return false;
	}
	$custom = get_post_custom( $postid );
	if ( !empty( $custom['_joost_dcm_' . $val][0] ) )
		return maybe_unserialize( $custom['_joost_dcm_' . $val][0] );
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

/**
 * Print a question mark tooltip
 * @param  str  $str The title text for the tooltip
 * @return void
 */
function echo_tooltip( $str ) {

	printf( '<span class="icon-question" alt="%1$s" title="%1$s">?</span>', $str );
}
