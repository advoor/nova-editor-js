<?php

namespace Advoor\NovaEditorJs;

use Advoor\NovaEditorJs\NovaEditorJs;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Fluent;
use Stringable;

class NovaEditorJsData extends Fluent implements Htmlable, Stringable
{
    public function toHtml()
    {
        return NovaEditorJs::generateHtmlOutput($this);
    }

    public function __toString()
    {
        return (string) $this->toHtml();
    }
}
