dc-meta-tags
============
WordPress plugin for adding Dublin Core meta tags to wp_head on pages, posts & custom post types. Outputs XHTML, HTML4 or HTML5 syntax.

* *Contributors:* Joost Kiens, Júlio Reis
* *Tags:* Dublin Core, meta tags, metadata, RDF
* *Requires at least:* 3.3.0
* *Tested up to:* 3.8.1
* *Stable tag:* 0.2.2
* *License:* GPLv3
* *License URI:* [http://www.gnu.org/licenses/gpl-3.0.html](http://www.gnu.org/licenses/gpl-3.0.html) or see the included `LICENSE.txt` and `LICENSE.md`

Description
---------------------------------

This WordPress plugin adds [Dublin Core Metadata Element Set v1.1](http://dublincore.org/documents/dces/) meta tags to the head of your site. Dublin Core elements are a subset of RDF and help with the discoverability of your site.

### Supported elements
The complete Dublin Core Metadata Element Set v1.1 is supported.

### Settings
On the settings page (Settings > DC Meta Tags) you can configure the meta tags:

* Select which elements should be included
* On which (custom) post types the meta tags should appear
* To which doctype the meta tags should adhere (HTML4, XHTML, HTML5)
* Enter a default text or URL for the rights elements

### Meta box on posts
On the add/edit post screens a meta box is available to override the default meta values. Contributor, Relation & Source, which are not auto-generated, can be entered here.

### Only for documents
Since the Dublin Core Metadata Element Set v1.1 describes documents, it is only added to attachments, pages, posts and custom post types. No metadata is added to non-editable pages like the home page, archives, etc.

Installation
---------------------------------

1. Upload `dc-meta-tags` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the ‘Plugins’ menu in WordPress
1. Go to Settings > DC Meta Tags to configure it if necessary

Frequently Asked Questions
---------------------------------

#### How is the metadata generated?
The metadata is automatically generated based on the post title, author, excerpt, categories, tags, etc. 

#### Can I override the generated metadata for specific posts?
Yes, a meta box is available on the add/edit post screens.

#### Where the hell is that meta box, I don't see it?
Go to ‘Screen options’ and make sure ‘Dublin Core Metadata’ is checked.

#### I don't like the unnessary closing slash in the HTML5 output. Can I remove it?
Add `define ('DCM_HTML5_CLOSING_SLASH', false);` to your `wp-config.php` and you’re good to go.

Changelog
---------------------------------

#### 0.2.3

*(unreleased)*

* Fixed: Was picking wrong value for relation field

#### 0.2.2

*Released 2014-01-31*

* Fixed: Aborts if WordPress version is unsupported
* Minimum WordPress version changed to 3.3.0, according to [Plugin-Datei Doktor](http://de.wpseek.com/pluginfilecheck/)
* Added `LICENSE.txt` and `LICENSE.md` from [TheFox / GPLv3.md](https://github.com/TheFox/GPLv3.md)

#### 0.2.1

*Released 2014-01-25*

* i18n ready
* l10n: Portuguese (pt)
* Tidied up text in the admin page
* Moved help text to tooltips in the meta box, to tidy up the screen and avoid duplication of help text for multivalue fields

#### 0.2.0

*Released 2013-01-10*

* Added meta box on posts to override auto-generated values
* Added support for the following elements: Contributor, Relation, Source
* Added uninstall functions to clean db
* Ability to select on which post types metadata should appear

#### 0.1.0

*Released 2013-01-07*

* Initial commit
