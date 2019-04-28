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

## Usage:

Add this `use` statement to the top of the your nova resource file:

```
use Advoor\NovaEditorJs\NovaEditorJs;
```

Use the field as below:

```
NovaEditorJs::make('FieldName')
```

And boom!

You can use the built in function to generate the response for the frontend:

```
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