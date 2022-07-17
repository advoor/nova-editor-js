<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs;

use Advoor\NovaEditorJs\NovaEditorJs;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
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

        if (! is_iterable($attributes)) {
            $attributes = Arr::wrap($attributes);
        }

        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * @return \Illuminate\Support\HtmlString
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
