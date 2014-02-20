<?php 

/**
 * All member functions of DCM_Admin related to rendering the settings page
 */

if ( !defined( 'DCM_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/**
 * Loads the form for the settings page
 * @return void
 */
class DCM_Settings_Page {

	private $options;         // local store for the options
	private $any_input;       // label for "Any text"
	private $nothing;         // label for "Nothing"
	public  $status_values;   // the values for the status drop-down
	public  $status_display;  // the display values for the status drop-down
	
	function __construct( $options ) {
		$this->options = $options;
		$this->status_values = array( 'disabled', 'default', 'editable' );
		$this->status_display = array(
			__( 'disabled', 'dc-meta-tags' ),
			__( 'default only', 'dc-meta-tags' ),
			__( 'editable', 'dc-meta-tags' ),
		);
		// Translators: This is shown when there’s no default value
		$this->nothing = __( 'Nothing', 'dc-meta-tags' );
		$this->any_input = __( 'Any text:', 'dc-meta-tags' );
	}
	
	/* Output the page - this is the function used in add_options_page
	 */
	function render() {
		if( !isset( $_GET['page'] ) || $_GET['page'] != DCM_SETTINGS_SLUG )
			return;	
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Dublin Core Meta Tags', 'dc-meta-tags' ); ?></h2>

	<p><?php printf ( __( 'This plugin adds %sDublin Core Metadata Element Set v1.1%s meta tags to the <code>head</code> of your site. Dublin Core elements are a subset of RDF and help with the discoverability of your site.', 'dc-meta-tags' ), '<a href="http://dublincore.org/documents/dces/" target="_blank">', '</a>' ); ?></p>

	<blockquote cite="http://en.wikipedia.org/wiki/Dublin_Core">
		<p>
			<?php _e( '“Dublin Core Metadata can be used for multiple purposes, from simple resource description, to combining metadata vocabularies of different metadata standards, to providing interoperability for metadata vocabularies in the Linked data cloud and Semantic web implementations.”', 'dc-meta-tags' ); ?>
		</p>
	</blockquote>

	<p><?php _e( 'This plugin automatically adds meta tags on pages and posts, including custom post types. No metadata is added to pages created by WordPress like the home page, category pages, archives, etc.', 'dc-meta-tags' ); ?></p>

	<!-- Beginning of the Plugin Options Form -->
	<form method="post" action="options.php">

		<h3><?php _e( 'Select Dublin Core Meta Tags', 'dc-meta-tags' ); ?></h3>
		<p><?php _e( 'Change an element’s mode and default value below. Modes are as follows:', 'dc-meta-tags' ); ?></p>
		<ul>
		<li><?php printf( __('%sDisabled:%s This field will not be used', 'dc-meta-tags'), '<em>', '</em>' ); ?></li>
		<li><?php printf( __('%sDefault only:%s The value for this field is obtained as indicated in the ‘Default value’ column – it cannot be changed in the edit page', 'dc-meta-tags'), '<em>', '</em>' ); ?></li>
		<li><?php printf( __('%sEditable:%s You will be able to change this field in the edit page. The default value will be suggested unless you enter a value in the edit page.', 'dc-meta-tags'), '<em>', '</em>' ); ?></li>
		</ul>
		<p>
		<?php DCM_Base::any_tooltip( __('Caution', 'dc-meta-tags'), '!' ); ?>
		<?php printf( __('%sCaution:%s Changing a field’s mode from ‘editable’ to ‘default only’ causes the removal of its metadata!', 'dc-meta-tags'), '<strong>', '</strong>' ); ?></p>

		<?php
			settings_fields( DCM_OPTION_GROUP );
		?>
<table class="form-table">
	<tbody><tr>
		<th><?php _e( 'Element', 'dc-meta-tags' ); ?></th>
		<th><?php _e( 'Mode', 'dc-meta-tags' ); ?>
		</th><th>
		<?php _e( 'Default value', 'dc-meta-tags' ); ?>
		</th></tr>
		<?php
		$this->settings_render_element( 'contributor' );
		$this->settings_render_element( 'coverage' );
		$this->settings_render_element( 'creator' );
		$this->settings_render_element( 'date' );
		$this->settings_render_element( 'description' );
		$this->settings_render_element( 'format' );
		$this->settings_render_element( 'identifier' );
		$this->settings_render_element( 'language' );
		$this->settings_render_element( 'publisher' );
		$this->settings_render_element( 'relation' );
		$this->settings_render_element( 'rights' );
		$this->settings_render_element( 'source' );
		$this->settings_render_element( 'subject' );
		$this->settings_render_element( 'title' );
		$this->settings_render_element( 'type' );
		?>
	</tbody>
</table>

		<h3><?php _e( 'Post types', 'dc-meta-tags' ); ?></h3>
		<p><?php _e( 'Select the post types which should have Dublin Core Metadata.', 'dc-meta-tags' ); ?></p>

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Post types', 'dc-meta-tags' ); ?></th>
				<td>
				<?php foreach ( DCM_Base::list_post_types( 'names') as $post_type => $name ) : ?>
				<label><input name="<?php echo DCM_OPTION_NAME; ?>[post_types][]" type="checkbox" value="<?php echo $post_type; ?>" <?php if (isset($this->options['post_types'])) { checked(in_array($post_type, $this->options['post_types'])); } ?> /> <?php echo $name; ?> </label><br />
				<?php endforeach; ?>
				</td>
			</tr>
		</table>

		<h3><?php _e( '(X)HTML version', 'dc-meta-tags' ); ?></h3>
		<p><?php _e( 'Select the (X)HTML version that corresponds to your doctype.', 'dc-meta-tags' ); ?></p>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( '(X)HTML version', 'dc-meta-tags' ); ?></th>
				<td>
					<label><input name="<?php echo DCM_OPTION_NAME; ?>[output_html]" type="radio" value="xhtml" <?php checked('xhtml', $this->options['output_html']); ?> /> XHTML <span style="color:#666666;margin-left:32px;"><?php echo __( 'Meta tag will look like', 'dc-meta-tags' ), ' <code>&lt;meta name="dc.Format" scheme="dcterms.imt" content="text/html" /&gt;</code>'; ?></span></label><br />

					<label><input name="<?php echo DCM_OPTION_NAME; ?>[output_html]" type="radio" value="html5" <?php checked('html5', $this->options['output_html']); ?> /> HTML5 <span style="color:#666666;margin-left:32px;"><?php echo __( 'Meta tag will look like', 'dc-meta-tags' ), ' <code>&lt;meta name="dc.format" content="text/html" /&gt;</code>'; ?></span></label><br />

					<label><input name="<?php echo DCM_OPTION_NAME; ?>[output_html]" type="radio" value="html4" <?php checked('html4', $this->options['output_html']); ?> /> HTML4 <span style="color:#666666;margin-left:32px;"><?php echo __( 'Meta tag will look like', 'dc-meta-tags' ), ' <code>&lt;meta name="dc.Format" scheme="dcterms.imt" content="text/html"&gt;</code>'; ?></span></label><br />
				</td>
			</tr>
		</table>


		<?php submit_button(); ?>
	</form>

</div><!-- class="wrap" -->
<?php
	} // function settings_page


	/**
	 * Outputs a radio button
	 * @param   str  $name     The name attribute
	 * @param   str  $value    The value attribute
	 * @param   mix  $checked_value The string that should be checked, or a bool
	 * @param   str  $label    A label for the radio
	 * @return  void
	 */
	static function the_radio_button( $name, $value = '', $checked = false, $label = '' ) {
		// we check the radio if $checked is a string and it's equal to $value
		// else we interpret $checked as a bool
		$is_checked = ( gettype( $checked ) === 'string' ) ? ( $value === $checked ) : $checked;
		$radio = sprintf( '<input type="radio" name="%s" value="%s"%s/>', $name, $value, $is_checked ? ' checked="checked"' : '' );
		if( $label )
			printf( '<label>%s %s</label>', $radio, $label );
		else
			echo( $radio );
	}
	
	/**
	 * Outputs a drop-down list
	 * @param   str  $name           The name attribute
	 * @param   arr  $values         A list of values
	 * @param   arr  $display        A list of display values (defaults to $values)
	 * @param   str  $selected_value The selected value
	 * @return  void
	 */
	static function the_dropdown( $name, $values, $display = '', $selected_value = '' ) {
		echo( "<select name=\"$name\">\n" );
		if( !$display )
			$display = $values;
		$index = 0;
		foreach( $values as $value ) {
			$selected = ( $value == $selected_value) ? ' selected="selected"' : '';
			echo( "<option value=\"$value\"$selected>$display[$index]</option>\n" );
			$index++;
		}
		echo( "</select>\n" );
	}

	/**
	 * Renders an input box for a default value
	 * @param  string $element        The name of the element, e.g. 'contributor'
	 * @param  string $value          The element’s current default value
	 * @param  string $checked_value  The element’s default type
	 * @param  string $tip            A tooltip text, if any
	 * @return void
	 */
	function the_default_input( $element, $value, $checked_value, $tip = '' ) {
		// Translators: Label for the default value field in the settings page
		echo( '<span class="tight-fit">' );
		self::the_radio_button( DCM_OPTION_NAME.'['.$element.'_default_type]', 'text', $checked_value, $this->any_input );
		printf( '<input type="text" size="30" name="%s[%s_default_value]" value="%s" />', DCM_OPTION_NAME, $element, $value );
		if( $tip ) {
			echo( "&nbsp;" );
			DCM_Base::any_tooltip( $tip );
		}
		echo( '</span>' );
	}

	/**
	 * Renders a Dublin Core metadata element in the settings page
	 * @param  string $element  The name of the element to render, e.g. 'contributor'
	 * @return void
	 */
	function settings_render_element( $element ) {
		// the row
		echo( "    <tr>\n" );
		
		// first cell: enabled checkbox, label, tooltip
		echo( "        <td class=\"tight-fit\">\n" );
		echo( "            <label>" );

		// the label
		DCM_Base::the_label( $element );
		echo( "            " );
		DCM_Base::the_element_tooltip( $element );
		echo( "            </label>" );
		echo( "        </td>\n" );
		
		// middle cell: the status
		echo( "        <td class=\"tight-fit\">\n" );
		$selected = $this->options[$element.'_mode'];
		$name = DCM_OPTION_NAME.'[' . $element . '_mode]';
		self::the_dropdown( $name, $this->status_values, $this->status_display, $selected ); // change 'editable' to the var with the option
		echo( "        </td>\n" );
		
		// last cell: the options
		echo( "        <td class=\"input-box-height\">\n" );
		$this->render_options( $element );
		echo( "        </td>\n" );
		echo( "    </tr>\n" );
	}
	
	/**
	 * Renders the options for a Dublin Core metadata element
	 * @param  string $element  The name of the element, e.g. 'contributor'
	 * @param  string $element  The name of the element, e.g. 'contributor'
	 * @return void
	 */
	function render_options( $element ) {
		$tip = '';
		switch( $element ) {

		case 'contributor':
			break;
			
		case 'coverage':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$mask = __( '‘%s’', 'dc-meta-tags' );
			$label = sprintf( $mask, __( 'World', 'dc-meta-tags' ) );
			self::the_radio_button( $name, 'world', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'creator':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = __( 'The post’s author', 'dc-meta-tags' );
			self::the_radio_button( $name, 'post', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'date':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = __( 'The post’s published date', 'dc-meta-tags' );
			self::the_radio_button( $name, 'post', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'description':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = __( 'The post’s excerpt', 'dc-meta-tags' );
			self::the_radio_button( $name, 'excerpt', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'format':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = __( 'The post’s MIME type (if set)', 'dc-meta-tags' );
			self::the_radio_button( $name, 'post', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'identifier':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = __( 'The post’s permalink', 'dc-meta-tags' );
			self::the_radio_button( $name, 'permalink', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'language':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = sprintf( __( 'The WordPress installation language (‘%s’)', 'dc-meta-tags' ), get_bloginfo( 'language' ) );
			self::the_radio_button( $name, 'wordpress', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'publisher':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = sprintf( __( 'The website’s title (‘%s’)', 'dc-meta-tags' ), get_bloginfo( 'site_name' ) );
			self::the_radio_button( $name, 'website-title', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'relation':
			break;

		case 'rights':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = __( 'Value from Creative Commons Configurator plugin (if active)', 'dc-meta-tags' );
			self::the_radio_button( $name, 'ccc-plugin', $checked_value, $label );
			echo( "<br/>\n" );
			$tip = __( 'Suggestion: the full URL (incl. http://) to your copyrights page', 'dc-meta-tags' );
			break;

		case 'source':
			break;

		case 'subject':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = __( 'List of categories and tags (only for posts)', 'dc-meta-tags' );
			self::the_radio_button( $name, 'categories-tags', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'title':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label =__( 'The post’s title', 'dc-meta-tags' );
			self::the_radio_button( $name, 'post', $checked_value, $label );
			echo( "<br/>\n" );
			break;

		case 'type':
			$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
			$checked_value = $this->options[$element.'_default_type'];
			$label = sprintf( __( '‘%s’', 'dc-meta-tags' ), 'Text' );
			self::the_radio_button( $name, 'post', $checked_value, $label );
			echo( "<br/>\n" );
			break;
		}
		// render "any text"
		$name = DCM_OPTION_NAME.'['.$element.'_default_type]';
		$checked_value = $this->options[$element.'_default_type'];
		$value =  $this->options[$element.'_default_value'];
		$this->the_default_input( $element, $value, $checked_value, $tip );
		// render "nothing"
		echo( "<br/>\n" );
		self::the_radio_button( $name, 'none', $checked_value, $this->nothing );
		echo( "\n" );
	}
}
