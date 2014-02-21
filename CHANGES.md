Change log for Dublin Core Meta Tags
------------------------------------

#### 0.4.1

*Released 2014-02-21*

* Added: Integration with [Transifex](https://www.transifex.com/projects/p/dc-meta-tags/) for community translation
* Added: Complete translation: pt_PT
* Added: 43 very incomplete translations (>10% done)

*Released 2014-02-20*

* Added: three modes for fields: disabled, default only, and editable
* Added: flexible default values for all fields: none, any text, or special values obtained from WP or the current post
* Improved: much code rewrite: fully object-oriented; higher cohesion of some classes; common services moved to a base class; options retrieved only once

#### 0.3.0

*Released 2014-02-14*

* Added: Coverage for Date, Format, Identifier and Type (complete coverage of Dublin Core fields 1.1)
* Added: Multi-value input for all fields
* Added: l10n for default meta values
* Improved: Detects install, upgrade and downgrade
* Improved: Empty meta no longer saved as empty, but deleted; deletes empty meta written by previous versions
* Improved: More efficient uninstall
* Improved: More efficient metadata retrieval
* Fixed: Bad l10n filenames for pt_PT

#### 0.2.3

*Released 2014-02-09*

* Added: Upgrades via `github-updater`
* Fixed: Was picking wrong value for `relation` field

#### 0.2.2

*Released 2014-01-31*

* Added: `LICENSE.txt` and `LICENSE.md` from [TheFox / GPLv3.md](https://github.com/TheFox/GPLv3.md)
* Improved: Minimum WordPress version changed to 3.3.0, according to [Plugin-Datei Doktor](http://de.wpseek.com/pluginfilecheck/)
* Fixed: Abort if WordPress version is unsupported

#### 0.2.1

*Released 2014-01-25*

* Added: i18n ready
* Added: l10n: Portuguese (pt)
* Improved: Tidied up text in the admin page
* Improved: Moved help text to tooltips in the meta box, to tidy up the screen and avoid duplication of help text for multivalue fields

#### 0.2.0

*Released 2013-01-10*

* Added meta box on posts to override auto-generated values
* Added support for the following elements: Contributor, Relation, Source
* Added uninstall functions to clean db
* Ability to select on which post types metadata should appear

#### 0.1.0

*Released 2013-01-07*

* Initial commit
