<?php

namespace Advoor\NovaEditorJs;

use Illuminate\Support\Facades\Facade;

/**
 * @method static HtmlString generateHtmlOutput(iterable|string $data)
 * @method static void addRender(string $block, callable $callback)
 * @see NovaEditorJsConverter
 */
class NovaEditorJs extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'nova-editor-js';
    }

    /**
     * @deprecated since 3.0, use \Advoor\NovaEditorJs\NovaEditorJsField::make
     */
    public static function make(mixed $name, $attribute = null, $resolveCallback = null)
    {
        trigger_deprecation('advoor/nova-editor-js', '4.0', "NovaEditorJs has been converted to a Facade, use NovaEditorJsField::make instead");

        return NovaEditorJsField::make($name, $attribute, $resolveCallback);
    }
}
