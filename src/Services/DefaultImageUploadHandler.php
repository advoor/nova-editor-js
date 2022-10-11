<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs\Services;

use Advoor\NovaEditorJs\Contracts\ImageUploadHandler;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Default upload handler implementation, using the disk configured in the
 * image toolSettings, and using the Laravel Storage API to return a URL
 * to the resulting image.
 */
class DefaultImageUploadHandler extends AbstractImageUploadHandler
{
    protected function determineImageUrl(string $path): string
    {
        return Storage::disk($this->getImageDisk())->url($path);
    }
}
