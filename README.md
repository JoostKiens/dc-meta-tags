dc-meta-tags
============
WordPress plugin for adding Dublin Core meta tags to wp_head pages, posts & custom post types. Outputs XHTML, HTML4 or HTML5 syntax.

Description
--------------------------------------

This WordPress plugin adds Dublin Core Metadata Element Set v1.1 meta tags to the head of your site. Dublin Core elements are a subset of RDF and help with the discoverability of your site.

# Supported elements
The following elements are supported:
* Coverage
* Creator
* Date
* Description
* Format
* Indentifier
* Language
* Publisher
* Rights
* Subject
* Title
* Type

# Only for documents
Since the Dublin Core Metadata Element Set v1.1 describes documents, it is only added to pages, posts and custom post types. No meta data is added to the home page, archives, etc.

# Settings
On the settings page (Settings > DC Meta Tags), you can select which elements should be included, to which doctype the meta tags need to adhere to and optionally add a URL to the copyright page

Installation
--------------------------------------

1. Upload `dc-meta-tags` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Grab a cup of coffee, you're done!

Frequently Asked Questions
--------------------------------------

# How is the meta data generated?

The meta data is automatically generated based on the post title, author, excerpt, categories, tags, etc.

# Can I over ride the generated meta data for specific posts?

Nope. Perhaps in a future version.

# I don't like the unnessary closing slash in the HTML5 output. Can I fix it?

Neither do I care for those slashes. Add `define ('DCM_HTML5_CLOSING_SLASH', false);` to you wp-config.php and you're good to go
.
Changelog
--------------------------------------

= 0.1.0 =
* A Initial commit.
