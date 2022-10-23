<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs\Contracts;

use Illuminate\Http\File;

/**
 * The UploadHandler takes care of storing images and returning
 * a URL to the image.
 * Can be overridden if you need to handle image upload differently,
 * or if you want to change the URL returned to the client.
 */
interface ImageUploadHandler
{
    /**
     * Called when a controller needs to store a file to a
     * disk, and convert it to a URL.
     *
     * @param File $sourceFile Source file to store
     * @return string URL to the stored image
     */
    public function saveImage(File $sourceFile): string;

    /**
     * Called when a controller needs to store a thumbnail of
     * a source file. The original $sourceFile is provided to use for
     * path computation logic. The $thumbnail is the file that needs to be
     * stored on disk.
     * Should return a URL to the stored thumbnail.
     *
     * @param File $sourceFile Originally uploaded file.
     * @param File $thumbnail The actual created thumbnail. The filename matches the thumbnail name.
     */
    public function saveThumbnail(File $sourceFile, File $thumbnail): string;
}
