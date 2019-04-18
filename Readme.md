# Laravel Nova Editor JS Field

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

## Tools included
* https://github.com/editor-js/header
* https://github.com/editor-js/image
* https://github.com/editor-js/code
* https://github.com/editor-js/link
* https://github.com/editor-js/list

## Todo

* Implement more tools
* Add configuration options to enable / disable some tools
