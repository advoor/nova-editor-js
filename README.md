# Laravel Nova Editor JS Field

[![Latest Version on Github](https://img.shields.io/github/release/advoor/nova-editor-js.svg?style=flat-square)](https://packagist.org/packages/advoor/nova-editor-js)
[![Total Downloads](https://img.shields.io/packagist/dt/advoor/nova-editor-js.svg?style=flat-square)](https://packagist.org/packages/advoor/nova-editor-js)

A Laravel Nova implementation of [Editor.js](https://github.com/codex-team/editor.js) by [@advoor](https://github.com/advoor).

## Installation

Install via composer:

```
composer require advoor/nova-editor-js
```

Publish the config file
```
php artisan vendor:publish --provider="Advoor\NovaEditorJs\FieldServiceProvider"
```

## Version Compatibility

Laravel Nova 4.x isn't backwards compatible with 3.x, so we had to make a version split.
Please use the below table to find which versions are suitable for your installation.

| Package version | Nova Version | Laravel Version | PHP version |
|-----------------|--------------|-----------------|-------------|
| `3.x`           | 4.x          | 8.x - 9.x       | 8.1+        |
| `2.x`           | 2.x - 3.x    | 5.x - 8.x       | 5.6 - 7.4   |

Note that we really pushed the PHP version up. If you're staying on
new versions of Laravel and Nova, we're expecting your PHP version to match that behaviour.

## Upgrade

See [the upgrade guide](./UPGRADING.md).

## Usage:

Add this `use` statement to the top of the your Laravel Nova Resources:

```php
use Advoor\NovaEditorJs\NovaEditorJs;
```

Use the field as below:

```php
NovaEditorJs::make('FieldName');
```

And boom!

You can configure what tools the Editor should use in the config 
file along with some other settings so make sure to have a look :)

You can use the built in function to generate the response for the frontend:

```php
NovaEditorJs::generateHtmlOutput($user->about);
```

Each 'block' has it's own view which can be overwritten in `resources/views/vendor/nova-editor-js/`

## Tools included
* https://github.com/editor-js/header
* https://github.com/editor-js/image
* https://github.com/editor-js/code
* https://github.com/editor-js/link
* https://github.com/editor-js/list
* https://github.com/editor-js/inline-code
* https://github.com/editor-js/checklist
* https://github.com/editor-js/marker
* https://github.com/editor-js/embed
* https://github.com/editor-js/delimiter
* https://github.com/editor-js/table
* https://github.com/editor-js/raw

## Extending

Please refer to the [extending Nova EditorJS](./EXTENDING.md) guide on instructions on how to register custom components.
