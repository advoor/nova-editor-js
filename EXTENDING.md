# Extending Nova EditorJS

Extending NovaEditorJS is a bit of work, but shouldn't be too hard once you're known with Laravel.

In this demonstration we will be incorporating the [warning component](https://github.com/editor-js/warning) in our
Laravel application.

There are two steps to extending the editor. The first consists of creating a JavaScript file and passing it onto Nova.
The second step allows you to create a blade view file and pass it to the field to allow your block to render in the Nova `show` page.

## Creating the Javascript file

`resources/js/editor-js-plugins/warning.js`

```js
/*
 * The editorConfig variable is used by you to add your tools,
 * or any additional configuration you might want to add to the editor.
 *
 * The fieldConfig variable is the VueJS field exposed to you. You may
 * fetch any value that is contained in your laravel config file from there.
 */
NovaEditorJS.booting(function (editorConfig, fieldConfig) {
    if (fieldConfig.toolSettings.warning.activated === true) {
        editorConfig.tools.warning = {
            class: require('@editorjs/warning'),
            shortcut: fieldConfig.toolSettings.warning.shortcut,
            config: {
                titlePlaceholder: fieldConfig.toolSettings.warning.titlePlaceholder,
                messagePlaceholder: fieldConfig.toolSettings.warning.messagePlaceholder,
            },
        }
    }
});
```

`webpack.mix.js`

```js
const mix = require('laravel-mix');

mix.js('resources/js/editor-js-plugins/warning.js', 'public/js/editor-js-plugins/warning.js');
```

`app/Providers/NovaServiceProvider.php`

```php
// ...
public function boot()
{
    parent::boot();

    Nova::serving(function () {
        Nova::script('editor-js-warning', public_path('js/editor-js-plugins/warning.js'));
    });
}
// ...
```

`config/nova-editor-js.php`

```php
return [
    // ...
    'toolSettings' => [
        'warning' => [
            'activated' => true,
            'titlePlaceholder' => 'Title',
            'messagePlaceholder' => 'Message',
            'shortcut' => 'CMD+SHIFT+L'
        ],
    ]
    // ...
];
```

## Creating the blade view file

`resources/views/editorjs/warning.blade.php`

*CSS classes taken from [here](https://github.com/editor-js/warning/blob/master/src/index.css).*

```html
<div class="editor-js-block">
    <div class="cdx-warning">
        <h3 class="cdx-warning__title">{{ $title }}</h3>
        <p class="cdx-warning__message">{{ $message }}</p>
    </div>
</div>
```

`app/Providers/NovaServiceProvider.php`

```php
use Advoor\NovaEditorJs\NovaEditorJs;

// ...
public function boot()
{
    parent::boot();

    NovaEditorJs::addRender('warning', function($block) {
        return view('editorjs.warning', $block['data'])->render();
    });

    // ...
}
// ...
```

That's it for extending the Nova EditorJS package!

