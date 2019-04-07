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



## Todo

* Index and details views
* Integrate the image upload plugin
* Add configuration options to enable / disable some tools
