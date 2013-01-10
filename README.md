dc-meta-tags
============
WordPress plugin for adding Dublin Core meta tags to wp_head on pages, posts & custom post types. Outputs XHTML, HTML4 or HTML5 syntax.

Description
--------------------------------------

This WordPress plugin adds Dublin Core Metadata Element Set v1.1 meta tags to the head of your site. Dublin Core elements are a subset of RDF and help with the discoverability of your site.

### Supported elements
The complete Dublin Core Metadata Element Set v1.1 is supported.

### Settings
On the settings page (Settings > DC Meta Tags) you can configure the meta tags.
* Select which elements should be included.
* On which (custom) post types the meta tags should appear.
* To which doctype the meta tags should adhere (HTML4, XHTML, HTML5)
* Enter a default text or URL for the rights elements

### Meta box on posts
On the add/edit post screens a meta box is available to override the default meta values. Contributor, Relation & Source, which are not auto-generated can be entered here.

### Only for documents
Since the Dublin Core Metadata Element Set v1.1 describes documents, it is only added to pages, posts and custom post types. No meta data is added to the home page, archives, etc.

Installation
--------------------------------------

1. Upload `dc-meta-tags` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings > DC Meta Tags to configure if necessary

Frequently Asked Questions
--------------------------------------

#### How is the meta data generated?
The meta data is automatically generated based on the post title, author, excerpt, categories, tags, etc. 

#### Can I over ride the generated meta data for specific posts?
Yes, a meta box is available on the add/edit post screens.

#### Where the hell is that meta box, I don't see it?
Go to "Screen options" and make sure 'Dublin Core Meta Data' is checked.

#### I don't like the unnessary closing slash in the HTML5 output. Can I remove it?
Add `define ('DCM_HTML5_CLOSING_SLASH', false);` to you wp-config.php and you're good to go.
.
Changelog
--------------------------------------

#### 0.2.0
* Added meta box on posts to override auto-generated values
* Added support for the following elements: Contributor, Relation, Source
* Added uninstall functions to clean db
* Ability to select on which post types meta data should appear

#### 0.1.0
* Initial commit.