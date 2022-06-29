# Upgrade Guide

This guide describes how to upgrade this application.

## From 2.x to 3.x (Laravel Nova 4.x)

To be more in line with the separation of concerns, a bunch of code has moved.
The changes are somewhat backwards compatible, but you're advices to quickly fix these deprecations.

### High impact changes

- The HTML rendering has been split from the field, `NovaEditorJs::make` is deprecated.
  - Update your Nova resources to use the `NovaEditorJsField` in the `fields()`
  - `NovaEditorJs` is now a facade, containing the `generateHtmlOutput` and `addRender` methods
- PHP requirement is now 8.1+
- Laravel requirement is now 8.0+

### Medium impact changes

- `NovaEditorJsField::displayUsing` now recieves a `NovaEditorJsData` instance, instead of a `string|array`.
  - `NovaEditorJsData` is a Fluent type, can be treated as an `iterable`.

### Low impact changes

- The Table component has been updated. While this shouldn't affect the data model, you're best off checking it.
- Using the `NovaEditorJsCast` on your Eloquent models is now recommended over casting fields to an array.

## From 1.x to 2.x

*No significant changes written down.*

## From 0.4 to 1.x

If upgrading from v0.4.0, re-publish the config file!
