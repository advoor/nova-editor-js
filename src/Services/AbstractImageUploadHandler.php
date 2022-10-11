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
 * Upload handler that saves files to the confgured disk, but asks a
 * further implementation to determine the URL.
 */
abstract class AbstractImageUploadHandler implements ImageUploadHandler
{
    private string $imageDisk;
    private string $imagePath;

    public function __construct()
    {
        $this->imageDisk = (string) config('nova-editor-js.toolSettings.image.disk', 'public');
        $this->imagePath = (string) trim(config('nova-editor-js.toolSettings.image.path', 'public/images'), '/');
    }

    /**
     * Returns the disk used for image storage
     */
    protected function getImageDisk(): string
    {
        return $this->imageDisk;
    }

    /**
     * Returns the base path to store images in
     */
    protected function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function saveImage(File $sourceFile): string
    {
        $uploadHashedName = basename($sourceFile->hashName());
        $path = Storage::disk($this->imageDisk)->putFileAs($this->imagePath, $sourceFile, $uploadHashedName);

        if (! $path) {
            throw new RuntimeException('Could not store image');
        }

        return $this->determineImageUrl($path);
    }

    public function saveThumbnail(File $sourceFile, File $thumbnail): string
    {
        $uploadHashedName = basename($sourceFile->hashName());
        $uploadHashedSelfName = Str::beforeLast($uploadHashedName, ".{$sourceFile->guessExtension()}");

        $thumbnailName = $thumbnail->getBasename();
        $actualFileName = "{$uploadHashedSelfName}_{$thumbnailName}";

        $path = Storage::disk($this->imageDisk)->putFileAs($this->imagePath, $thumbnail, $actualFileName);

        if (! $path) {
            throw new RuntimeException('Could not save thumbnail');
        }

        return $this->determineImageUrl($path);
    }

    /**
     * Determines the URL to a given image path. Usually just uses Storage::url,
     * but you can use your own logic here.
     */
    abstract protected function determineImageUrl(string $path): string;
}
