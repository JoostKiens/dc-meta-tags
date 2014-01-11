<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Dublin Core Meta Tags', PLUGIN_NAME ); ?></h2>

	<p><?php _e( 'This plugin adds <a href="http://dublincore.org/documents/2012/06/14/dces/" target="_blank">Dublin Core Metadata Element Set v1.1</a> meta tags to the <code>head</code> of your site. Dublin Core elements are a subset of RDF and help with the discoverability of your site.', PLUGIN_NAME ); ?></p>

	<blockquote cite="http://en.wikipedia.org/wiki/Dublin_Core">
		<p><em>"
			<?php _e( 'Dublin Core Metadata can be used for multiple purposes, from simple resource description, to combining metadata vocabularies of different metadata standards, to providing interoperability for metadata vocabularies in the Linked data cloud and Semantic web implementations.', PLUGIN_NAME ); ?>"</em>
		</p>
	</blockquote>

	<p><?php _e( 'The Dublin Core Metadata Element Set describes documents, etc. this plugin automatically adds meta tags on pages and posts (including custom post types). No meta data is added to the home page, category pages, archives, etc.', PLUGIN_NAME ); ?></p>

	<!-- Beginning of the Plugin Options Form -->
	<form method="post" action="options.php">
		

		<h3><?php _e( 'Select Dublin Core Meta Tags', PLUGIN_NAME ); ?></h3>
		<p><?php _e( 'This plugin adds elements of Dublin Core Metadata Element Set, Version 1.1 to the <code>head</code> of this WordPress install, you can enable and disable elements below', PLUGIN_NAME ); ?></p>

		<?php 
			global $dcm_admin;
			settings_fields('joost_dcm_options');
			$options = get_dcm_options(); 
		?>

		<table class="form-table">
			
			<tr valign="top">
				<th scope="row"><?php _e( 'Dublin Core Elements v1.1', PLUGIN_NAME ); ?></th>
				<td>
					<label><input name="_joost_dcm_options[elem_contributor]" type="checkbox" value="1" <?php if (isset($options['elem_contributor'])) { checked('1', $options['elem_contributor']); } ?> /> <?php _e( 'Contributor', PLUGIN_NAME ); ?> <em>(<?php _e( 'Need to set per post', PLUGIN_NAME ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_coverage]" type="checkbox" value="1" <?php if (isset($options['elem_coverage'])) { checked('1', $options['elem_coverage']); } ?> /> <?php _e( 'Coverage', PLUGIN_NAME ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_creator]" type="checkbox" value="1" <?php if (isset($options['elem_creator'])) { checked('1', $options['elem_creator']); } ?> /> <?php _e( 'Creator', PLUGIN_NAME ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_date]" type="checkbox" value="1" <?php if (isset($options['elem_date'])) { checked('1', $options['elem_date']); } ?> /> <?php _e( 'Date', PLUGIN_NAME ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_description]" type="checkbox" value="1" <?php if (isset($options['elem_description'])) { checked('1', $options['elem_description']); } ?> /> <?php _e( 'Description', PLUGIN_NAME ); ?></label><br />

					<label><input name="_joost_dcm_options[elem_format]" type="checkbox" value="1" <?php if (isset($options['elem_format'])) { checked('1', $options['elem_format']); } ?> /> <?php _e( 'Format', PLUGIN_NAME ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_identifier]" type="checkbox" value="1" <?php if (isset($options['elem_identifier'])) { checked('1', $options['elem_identifier']); } ?> /> <?php _e( 'Identifier', PLUGIN_NAME ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_language]" type="checkbox" value="1" <?php if (isset($options['elem_language'])) { checked('1', $options['elem_language']); } ?> /> <?php _e( 'Language', PLUGIN_NAME ); ?> <em>(<?php _e( 'Redundant with the <code>lang</code> attribute on the <code>html</code> element.', PLUGIN_NAME ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_publisher]" type="checkbox" value="1" <?php if (isset($options['elem_publisher'])) { checked('1', $options['elem_publisher']); } ?> /> <?php _e( 'Publisher', PLUGIN_NAME ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_relation]" type="checkbox" value="1" <?php if (isset($options['elem_relation'])) { checked('1', $options['elem_relation']); } ?> /> <?php _e( 'relation', PLUGIN_NAME ); ?> <em>(<?php _e( 'Need to set per post', PLUGIN_NAME ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_rights]" type="checkbox" value="1" <?php if (isset($options['elem_rights'])) { checked('1', $options['elem_rights']); } ?> /> <?php _e( 'Rights', PLUGIN_NAME ); ?> <em>(<?php _e( 'Enter an URL to the copyrights page below.', PLUGIN_NAME ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_source]" type="checkbox" value="1" <?php if (isset($options['elem_source'])) { checked('1', $options['elem_source']); } ?> /> <?php _e( 'Source', PLUGIN_NAME ); ?> <em>(<?php _e( 'Need to set per post', PLUGIN_NAME ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_subject]" type="checkbox" value="1" <?php if (isset($options['elem_subject'])) { checked('1', $options['elem_subject']); } ?> /> <?php _e( 'Subject', PLUGIN_NAME ); ?> <em>(<?php _e( 'Only on posts: list of categories and tags.', PLUGIN_NAME ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_title]" type="checkbox" value="1" <?php if (isset($options['elem_title'])) { checked('1', $options['elem_title']); } ?> /> <?php _e( 'Title', PLUGIN_NAME ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_type]" type="checkbox" value="1" <?php if (isset($options['elem_type'])) { checked('1', $options['elem_type']); } ?> /> <?php _e( 'Type', PLUGIN_NAME ); ?> </label><br />

					<span style="color:#666666;"><?php _e( 'This version of the plugin support only these elements.', PLUGIN_NAME ); ?></span>
				</td>
			</tr>
		</table>

		<h3><?php _e( 'Post types', PLUGIN_NAME ); ?></h3>
		<p><?php _e( 'Select the post types which should have Dublin Core meta data.', PLUGIN_NAME ); ?></p>

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Post types', PLUGIN_NAME ); ?></th>
				<td>
				<?php foreach ( $dcm_admin->list_post_types( 'names') as $post_type => $name ) : ?>
				<label><input name="_joost_dcm_options[post_types][]" type="checkbox" value="<?php echo $post_type; ?>" <?php if (isset($options['post_types'])) { checked(in_array($post_type, $options['post_types'])); } ?> /> <?php echo $name; ?> </label><br />
				<?php endforeach; ?>
				</td>
			</tr>
		</table>

		<h3><?php _e( 'URL to copyrights page', PLUGIN_NAME ); ?></h3>
		<p><?php _e( 'Enter the full URL (incl. <code>http://</code>) to your copyrights page below, this will be used for the <code>rights</code> element. (optional)', PLUGIN_NAME ); ?></p>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('URL to the copyrights page (optional)', PLUGIN_NAME); ?></th>
				<td>
					<input type="text" size="57" name="_joost_dcm_options[rights_url]" value="<?php echo $options['rights_url']; ?>" />
				</td>
			</tr>
		</table>

		<h3><?php _e( '(X)HTML version', PLUGIN_NAME ); ?></h3>
		<p><?php _e( 'Select the (X)HTML version that resembles your doctype.', PLUGIN_NAME ); ?></p>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( '(X)HTML Version', PLUGIN_NAME ); ?></th>
				<td>
					<label><input name="_joost_dcm_options[output_html]" type="radio" value="xhtml" <?php checked('xhtml', $options['output_html']); ?> /> XHTML <span style="color:#666666;margin-left:32px;"><?php _e( 'Meta tag will look like <code>&lt;meta name="dc.Format" scheme="dcterms.imt" content="text/html" /&gt;</code>', PLUGIN_NAME ); ?></span></label><br />

					<label><input name="_joost_dcm_options[output_html]" type="radio" value="html5" <?php checked('html5', $options['output_html']); ?> /> HTML5 <span style="color:#666666;margin-left:32px;"><?php _e( 'Meta tag will look like <code>&lt;meta name="dc.format" content="text/html" /&gt;</code>', PLUGIN_NAME ); ?></span></label><br />

					<label><input name="_joost_dcm_options[output_html]" type="radio" value="html4" <?php checked('html4', $options['output_html']); ?> /> HTML4 <span style="color:#666666;margin-left:32px;"><?php _e( 'Meta tag will look like <code>&lt;meta name="dc.Format" scheme="dcterms.imt" content="text/html"&gt;</code>', PLUGIN_NAME ); ?></span></label><br />
				</td>
			</tr>
		</table>


		<?php submit_button(); ?>
	</form>

</div>