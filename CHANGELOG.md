# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [UNRELEASED]

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

[unreleased]: https://github.com/advoor/nova-editor-js/compare/v2.0.3..master
[2.0.3]: https://github.com/advoor/nova-editor-js/releases/v2.0.3
[2.0.2]: https://github.com/advoor/nova-editor-js/releases/v2.0.2
[2.0.0]: https://github.com/advoor/nova-editor-js/releases/v2.0.0
