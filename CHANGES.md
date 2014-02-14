Change log for Dublin Core Meta Tags
------------------------------------

#### 0.3.0

*Released 2014-02-14*

Enhancements:

* Accepts values for Date, Format, Identifier and Type (complete coverage of Dublin Core fields 1.1)
* All fields accept multiple values
* Empty meta no longer saved as empty, but deleted; deletes empty meta written by previous versions
* l10n for default meta values
* Tooltips on settings page
* Detects install, upgrade and downgrade
* More efficient uninstall
* More efficient metadata retrieval

Bug fixes:

* l10n: Bad filenames for pt_PT

#### 0.2.3

*Released 2014-02-09*

* Fix: Was picking wrong value for `relation` field
* Upgrades via `github-updater`

#### 0.2.2

*Released 2014-01-31*

* Fix to Abort if WordPress version is unsupported
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
