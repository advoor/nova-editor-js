<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs\Events;

use Illuminate\Foundation\Events\Dispatchable;

class EditorJsThumbnailCreated
{
    use Dispatchable;

    public function __construct(public string $disk, public string $path) {}
}
