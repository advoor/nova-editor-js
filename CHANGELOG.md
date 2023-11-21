# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [UNRELEASED]

## [3.3.0]

- Fixed invalid image reference `image.url` in `views/link.blade.php`, via #101 by @roelofr.

### Deprecated

- Deprecated support for Laravel 8.x. It might still work, but we're not testing it anymore.

## [3.2.1]

### Changed

- Improve GitHub Actions and test results, via #95 by @roelofr.

## [3.2.0]

### Added

- Suport for Laravel 10, via #93 by @Woeler.

### Fixed

- Fixed ignoring the resolveCallbacks on a field, via #83, by @Woeler.

## [3.1.0]

### Added

- Guzzle is now a dependency of this project.
- Added `php-cs-fixer` for code standards.
- Added `php-parallel-lint` to ensure all files are actually valid PHP code.
- Added `pretttier` for consistent Markdown files.
- Added RTL support (`editorSettings.rtl`)

### Changed

- Improved image upload handling, using Laravel-native libraries
- Improved link metadata retrieval, using Laravel-native libraries

### Deprecated

- Deprecated `editorSettings.initialBlock` in favor of `editorSettings.defaultBlock` to match EditorJS

### Fixed

- Fixed HTML escaping on list and raw HTML fields. (#80 by @Harrk)

## [3.0.5]

### Fixed

- When no changes are made to the editor, the value is left as-is, instead of double-encoding it (thanks @waelelsawy)
- Templates for `list`', `paragraph` and `table` to use raw-html statements on cleaned fields.

## [3.0.4]

### Fixed

- NovaEditorJsCast now properly handles JSON, not double-encoding stuff and decoding double-encoded properties.

## [3.0.3]

### Fixed

- Constructor of `NovaEditorJsData` now accepts null values and non-iterables.
- PHPDoc return type of `NovaEditorJsData::toHtml()`.

## [3.0.2]

### Added

- Support for `spatie/image` version 2.x.

## [3.0.1]

### Fixed

- `composer.json` didn't require PHP 8.1+, but the codebase did.

## [3.0.0]

### Added

- Nova 4 support
- `NovaEditorJsConverter` to split HTML conversion from the Nova Field
- `NovaEditorJsData` model to store JSON data and allow easy HTML conversion
- `NovaEditorJsCast` to easily convert between raw data and the `NovaEditorJsData` model
- JS linter, EditorConfig and other tools for better development

### Changed

- **PHP version requirements changed**, now requires PHP 8.1 or higher
- `NovaEditorJs` facade for better separation of concerns
- Improved README and separated extending docs to separate file
- Updated Laravel Mix to new version
- Updated Vue to version 3
- The `NovaEditorJsField::displayUsing` now recieves a `NovaEditorJsData` model
- More robust conversion between the model data and the Nova editor field

## Deprecated

- `NovaEditorJs::make`, use `NovaEditorJsField::make` instead

## [2.0.3] - 2020-12-07

### Fixed

- Fix for Amazon S3 file support (#49)

## [2.0.2] - 2020-11-29

### Changed

- Reduced minimum height of editor (#47)

### Fixed

- Fix for when using an S3 disk (#46)

## [2.0.0] - 2020-08-03

### Added

- Added support for extending the EditorJS field with custom plugins

---

For older changes before v2.0.0, please see the [releases page](https://github.com/advoor/nova-editor-js/releases).

[unreleased]: https://github.com/advoor/nova-editor-js/compare/v3.3.0..master
[3.3.0]: https://github.com/advoor/nova-editor-js/releases/v3.3.0
[3.2.1]: https://github.com/advoor/nova-editor-js/releases/v3.2.1
[3.2.0]: https://github.com/advoor/nova-editor-js/releases/v3.2.0
[3.1.0]: https://github.com/advoor/nova-editor-js/releases/v3.1.0
[3.0.5]: https://github.com/advoor/nova-editor-js/releases/v3.0.5
[3.0.4]: https://github.com/advoor/nova-editor-js/releases/v3.0.4
[3.0.3]: https://github.com/advoor/nova-editor-js/releases/v3.0.3
[3.0.2]: https://github.com/advoor/nova-editor-js/releases/v3.0.2
[3.0.1]: https://github.com/advoor/nova-editor-js/releases/v3.0.1
[3.0.0]: https://github.com/advoor/nova-editor-js/releases/v3.0.0
[2.0.3]: https://github.com/advoor/nova-editor-js/releases/v2.0.3
[2.0.2]: https://github.com/advoor/nova-editor-js/releases/v2.0.2
[2.0.0]: https://github.com/advoor/nova-editor-js/releases/v2.0.0
