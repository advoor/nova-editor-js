<?php

namespace Advoor\NovaEditorJs;

use Advoor\NovaEditorJs\NovaEditorJs;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Fluent;
use Stringable;

class NovaEditorJsData extends Fluent implements Htmlable, Stringable
{
    /**
     *
     * Create a new fluent instance.
     *
     * @param iterable<TKey, TValue> $attributes
     * @return void
     */
    public function __construct($attributes = [])
    {
        if (is_string($attributes)) {
            $attributes = json_decode($attributes);
        }

        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * @return \Advoor\NovaEditorJs\HtmlString
     */
    public function toHtml()
    {
        return NovaEditorJs::generateHtmlOutput($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->toHtml();
    }
}
