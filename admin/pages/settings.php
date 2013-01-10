<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'Dublin Core Meta Tags', 'dc-meta-tags' ); ?></h2>

	<p><?php _e( 'This plugin adds <a href="http://dublincore.org/documents/2012/06/14/dces/" target="_blank">Dublin Core Metadata Element Set v1.1</a> meta tags to the <code>head</code> of your site. Dublin Core elements are a subset of RDF and help with the discoverability of your site.', 'dc-meta-tags' ); ?></p>

	<blockquote cite="http://en.wikipedia.org/wiki/Dublin_Core">
		<p><em>"
			<?php _e( 'Dublin Core Metadata can be used for multiple purposes, from simple resource description, to combining metadata vocabularies of different metadata standards, to providing interoperability for metadata vocabularies in the Linked data cloud and Semantic web implementations.', 'dc-meta-tags' ); ?>"</em>
		</p>
	</blockquote>

	<p><?php _e( 'The Dublin Core Metadata Element Set describes documents, etc. this plugin automatically adds meta tags on pages and posts (including custom post types). No meta data is added to the home page, category pages, archives, etc.', 'dc-meta-tags' ); ?></p>

	<!-- Beginning of the Plugin Options Form -->
	<form method="post" action="options.php">
		

		<h3><?php _e( 'Select Dublin Core Meta Tags', 'dc-meta-tags' ); ?></h3>
		<p><?php _e( 'This plugin adds elements of Dublin Core Metadata Element Set, Version 1.1 to the <code>head</code> of this WordPress install, you can enable and disable elements below', 'dc-meta-tags' ); ?></p>

		<?php 
			global $dcm_admin;
			settings_fields('joost_dcm_options');
			$options = get_dcm_options(); 
		?>

		<table class="form-table">
			
			<tr valign="top">
				<th scope="row"><?php _e( 'Dublin Core Elements v1.1', 'dc-meta-tags' ); ?></th>
				<td>
					<label><input name="_joost_dcm_options[elem_contributor]" type="checkbox" value="1" <?php if (isset($options['elem_contributor'])) { checked('1', $options['elem_contributor']); } ?> /> <?php _e( 'Contributor', 'dc-meta-tags' ); ?> <em>(<?php _e( 'Need to set per post', 'dc-meta-tags' ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_coverage]" type="checkbox" value="1" <?php if (isset($options['elem_coverage'])) { checked('1', $options['elem_coverage']); } ?> /> <?php _e( 'Coverage', 'dc-meta-tags' ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_creator]" type="checkbox" value="1" <?php if (isset($options['elem_creator'])) { checked('1', $options['elem_creator']); } ?> /> <?php _e( 'Creator', 'dc-meta-tags' ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_date]" type="checkbox" value="1" <?php if (isset($options['elem_date'])) { checked('1', $options['elem_date']); } ?> /> <?php _e( 'Date', 'dc-meta-tags' ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_description]" type="checkbox" value="1" <?php if (isset($options['elem_description'])) { checked('1', $options['elem_description']); } ?> /> <?php _e( 'Description', 'dc-meta-tags' ); ?></label><br />

					<label><input name="_joost_dcm_options[elem_format]" type="checkbox" value="1" <?php if (isset($options['elem_format'])) { checked('1', $options['elem_format']); } ?> /> <?php _e( 'Format', 'dc-meta-tags' ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_identifier]" type="checkbox" value="1" <?php if (isset($options['elem_identifier'])) { checked('1', $options['elem_identifier']); } ?> /> <?php _e( 'Identifier', 'dc-meta-tags' ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_language]" type="checkbox" value="1" <?php if (isset($options['elem_language'])) { checked('1', $options['elem_language']); } ?> /> <?php _e( 'Language', 'dc-meta-tags' ); ?> <em>(<?php _e( 'Redundant with the <code>lang</code> attribute on the <code>html</code> element.', 'dc-meta-tags' ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_publisher]" type="checkbox" value="1" <?php if (isset($options['elem_publisher'])) { checked('1', $options['elem_publisher']); } ?> /> <?php _e( 'Publisher', 'dc-meta-tags' ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_relation]" type="checkbox" value="1" <?php if (isset($options['elem_relation'])) { checked('1', $options['elem_relation']); } ?> /> <?php _e( 'relation', 'dc-meta-tags' ); ?> <em>(<?php _e( 'Need to set per post', 'dc-meta-tags' ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_rights]" type="checkbox" value="1" <?php if (isset($options['elem_rights'])) { checked('1', $options['elem_rights']); } ?> /> <?php _e( 'Rights', 'dc-meta-tags' ); ?> <em>(<?php _e( 'Enter an URL to the copyrights page below.', 'dc-meta-tags' ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_source]" type="checkbox" value="1" <?php if (isset($options['elem_source'])) { checked('1', $options['elem_source']); } ?> /> <?php _e( 'Source', 'dc-meta-tags' ); ?> <em>(<?php _e( 'Need to set per post', 'dc-meta-tags' ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_subject]" type="checkbox" value="1" <?php if (isset($options['elem_subject'])) { checked('1', $options['elem_subject']); } ?> /> <?php _e( 'Subject', 'dc-meta-tags' ); ?> <em>(<?php _e( 'Only on posts: list of categories and tags.', 'dc-meta-tags' ); ?>)</em></label><br />

					<label><input name="_joost_dcm_options[elem_title]" type="checkbox" value="1" <?php if (isset($options['elem_title'])) { checked('1', $options['elem_title']); } ?> /> <?php _e( 'Title', 'dc-meta-tags' ); ?> </label><br />

					<label><input name="_joost_dcm_options[elem_type]" type="checkbox" value="1" <?php if (isset($options['elem_type'])) { checked('1', $options['elem_type']); } ?> /> <?php _e( 'Type', 'dc-meta-tags' ); ?> </label><br />

					<span style="color:#666666;"><?php _e( 'This version of the plugin support only these elements.', 'dc-meta-tags' ); ?></span>
				</td>
			</tr>
		</table>

		<h3><?php _e( 'Post types', 'dc-meta-tags' ); ?></h3>
		<p><?php _e( 'Select the post types which should have Dublin Core meta data.', 'dc-meta-tags' ); ?></p>

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Post types', 'dc-meta-tags' ); ?></th>
				<td>
				<?php foreach ( $dcm_admin->list_post_types( 'names') as $post_type => $name ) : ?>
				<label><input name="_joost_dcm_options[post_types][]" type="checkbox" value="<?php echo $post_type; ?>" <?php if (isset($options['post_types'])) { checked(in_array($post_type, $options['post_types'])); } ?> /> <?php echo $name; ?> </label><br />
				<?php endforeach; ?>
				</td>
			</tr>
		</table>

		<h3><?php _e( 'URL to copyrights page', 'dc-meta-tags' ); ?></h3>
		<p><?php _e( 'Enter the full URL (incl. <code>http://</code>) to your copyrights page below, this will be used for the <code>rights</code> element. (optional)', 'dc-meta-tags' ); ?></p>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('URL to the copyrights page (optional)', 'dc-meta-tags'); ?></th>
				<td>
					<input type="text" size="57" name="_joost_dcm_options[rights_url]" value="<?php echo $options['rights_url']; ?>" />
				</td>
			</tr>
		</table>

		<h3><?php _e( '(X)HTML version', 'dc-meta-tags' ); ?></h3>
		<p><?php _e( 'Select the (X)HTML version that resembles your doctype.', 'dc-meta-tags' ); ?></p>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( '(X)HTML Version', 'dc-meta-tags' ); ?></th>
				<td>
					<label><input name="_joost_dcm_options[output_html]" type="radio" value="xhtml" <?php checked('xhtml', $options['output_html']); ?> /> XHTML <span style="color:#666666;margin-left:32px;"><?php _e( 'Meta tag will look like <code>&lt;meta name="dc.Format" scheme="dcterms.imt" content="text/html" /&gt;</code>', 'dc-meta-tags' ); ?></span></label><br />

					<label><input name="_joost_dcm_options[output_html]" type="radio" value="html5" <?php checked('html5', $options['output_html']); ?> /> HTML5 <span style="color:#666666;margin-left:32px;"><?php _e( 'Meta tag will look like <code>&lt;meta name="dc.format" content="text/html" /&gt;</code>', 'dc-meta-tags' ); ?></span></label><br />

					<label><input name="_joost_dcm_options[output_html]" type="radio" value="html4" <?php checked('html4', $options['output_html']); ?> /> HTML4 <span style="color:#666666;margin-left:32px;"><?php _e( 'Meta tag will look like <code>&lt;meta name="dc.Format" scheme="dcterms.imt" content="text/html"&gt;</code>', 'dc-meta-tags' ); ?></span></label><br />
				</td>
			</tr>
		</table>


		<?php submit_button(); ?>
	</form>

</div>