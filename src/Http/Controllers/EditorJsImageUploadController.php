<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs\Http\Controllers;

use Advoor\NovaEditorJs\Contracts\ImageUploadHandler;
use finfo;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use RuntimeException;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Image;
use Symfony\Component\Filesystem\Filesystem;

class EditorJsImageUploadController extends Controller
{
    private const VALID_IMAGE_MIMES = [
        'image/jpeg',
        'image/webp',
        'image/gif',
        'image/png',
        'image/svg+xml',
    ];

    private const TEMP_LOCATION = 'temp/editorjs';

    protected ImageUploadHandler $uploadHandler;

    /**
     * Auto-provision the upload handler.
     */
    public function __construct(ImageUploadHandler $uploadHandler)
    {
        $this->uploadHandler = $uploadHandler;
    }

    /**
     * Upload file.
     */
    public function file(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
            ]);
        }

        // Get handle to uploaded file
        $uploadedFile = $request->file('image');

        try {
            // Send to image handler, return outcome
            return response()->json([
                'success' => 1,
                'file' => $this->storeImage($uploadedFile),
            ]);
        } catch (RuntimeException $e) {
            // Return failure
            return response()->json([
                'success' => 0,
            ]);
        }
    }

    /**
     * "Upload" a URL.
     */
    public function url(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
            ]);
        }

        $url = $request->input('url');

        // Fetch URL
        try {
            $response = Http::timeout(5)->get($url)->throw();
        } catch (ConnectionException | RequestException) {
            return response()->json([
                'success' => 0,
            ]);
        }

        // Validate mime type
        $mime = (new finfo())->buffer($response->body(), FILEINFO_MIME_TYPE);
        if (! in_array($mime, self::VALID_IMAGE_MIMES, true)) {
            return response()->json([
                'success' => 0,
            ]);
        }

        // Write temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'editorjs');
        file_put_contents($tempFile, $response->body());

        // Convert to uploadedfile
        $urlBasename = basename(parse_url(url($url), PHP_URL_PATH));
        $downloadedFile = new UploadedFile($tempFile, $urlBasename, $mime);

        try {
            // Send to image handler, return outcome
            return response()->json([
                'success' => 1,
                'file' => $this->storeImage($downloadedFile),
            ]);
        } catch (RuntimeException $e) {
            // Return failure
            return response()->json([
                'success' => 0,
            ]);
        } finally {
            // Try to delete the temp file
            @unlink($tempFile);
        }
    }

    /**
     * Saves the given file, creating thumbnails if specified
     * and using the upload handler to save the file.
     *
     * @return array Array of file and thumbnail URLs
     */
    protected function storeImage(UploadedFile $file): array
    {
        // Cleanup values
        $fileExtension = $file->guessExtension();
        $filename = (string) Str::of($file->getClientOriginalName())
            ->beforeLast($file->getClientOriginalExtension())
            ->ascii()
            ->finish(".{$fileExtension}")
            ->lower();

        // Determine temp folder
        $tempLocation = self::TEMP_LOCATION . '/' . Str::random(16);
        $tempThumbsLocation = "{$tempLocation}/thumbs";

        // Store the (uploaded) file in a temp location, using the clean name
        $sourcePath = Storage::disk('local')->putFileAs($tempLocation, $file, $filename);
        if (! $sourcePath) {
            throw new RuntimeException('Failed to copy initial file.');
        }

        // Convert to File instance
        $sourceFile = new File(Storage::disk('local')->path($sourcePath));

        // Prep variables
        $createdFiles = [$sourcePath];
        $fileUrl = null;
        $thumbnailUrls = [];

        try {
            // Apply initial alterations
            $this->applyAlterations($sourceFile, null);

            // Ask the image handler to save the image
            $fileUrl = $this->uploadHandler->saveImage($sourceFile);

            // Fetch thumbnail settings
            $thumbnailSettings = config('nova-editor-js.toolSettings.image.thumbnails') ?? [];

            // Output if the image is a vector
            if ($fileExtension === 'svg') {
                return [
                    'url' => $fileUrl,
                ];
            }


            // Create thumbnails
            foreach ($thumbnailSettings as $thumbnailName => $setting) {
                // Make a copy of the original file
                $thumbnailPath = "{$tempThumbsLocation}/{$thumbnailName}.{$fileExtension}";

                // Check if copy worked, abort loop if it failed.
                if (! Storage::disk('local')->copy($sourcePath, $thumbnailPath)) {
                    throw new RuntimeException(
                        "Failed to copy {$sourcePath} to create thumbnail {$thumbnailName}"
                    );
                }

                $createdFiles[] = $thumbnailPath;

                // Convert to File instance
                $thumbnailFile = new File(Storage::disk('local')->path($thumbnailPath));

                // Apply the changes
                $this->applyAlterations($thumbnailFile, $setting);

                // Ask the image handler to save the thumbnail
                $thumbnailUrls[] = $this->uploadHandler->saveThumbnail($sourceFile, $thumbnailFile);
            }
        } catch (RuntimeException $exception) {
            report($exception);
        } finally {
            // Delete the temp files
            try {
                Storage::disk('local')->delete($createdFiles);
            } catch (FileNotFoundException $exception) {
                report(new RuntimeException("Failed to delete created temp files after handling upload", 0, $exception));
            }
        }

        return [
            'url' => $fileUrl,
            'thumbnails' => $thumbnailUrls,
        ];
    }

    /**
     * Applies the alterations in $alterations or in the config to the given image file.
     * Input file is modified
     */
    private function applyAlterations(File $file, ?array $alterations): void
    {
        // Set settings from alterations or default settings
        $imageSettings = $alterations ?? config('nova-editor-js.toolSettings.image.alterations');

        if (empty($imageSettings)) {
            return;
        }

        if ($image = $file->guessExtension() === 'svg') {
            return;
        }

        try {
            $image = Image::load($file->getPathname());

            if (!empty($imageSettings['resize']['width'])) {
                $image->width($imageSettings['resize']['width']);
            }

            if (!empty($imageSettings['resize']['height'])) {
                $image->height($imageSettings['resize']['height']);
            }

            if (!empty($imageSettings['optimize'])) {
                $image->optimize();
            }

            if (!empty($imageSettings['adjustments']['brightness'])) {
                $image->brightness($imageSettings['adjustments']['brightness']);
            }

            if (!empty($imageSettings['adjustments']['contrast'])) {
                $image->contrast($imageSettings['adjustments']['contrast']);
            }

            if (!empty($imageSettings['adjustments']['gamma'])) {
                $image->gamma($imageSettings['adjustments']['gamma']);
            }

            if (!empty($imageSettings['effects']['blur'])) {
                $image->blur($imageSettings['effects']['blur']);
            }

            if (!empty($imageSettings['effects']['pixelate'])) {
                $image->pixelate($imageSettings['effects']['pixelate']);
            }

            if (!empty($imageSettings['effects']['greyscale'])) {
                $image->greyscale();
            }
            if (!empty($imageSettings['effects']['sepia'])) {
                $image->sepia();
            }

            if (!empty($imageSettings['effects']['sharpen'])) {
                $image->sharpen($imageSettings['effects']['sharpen']);
            }

            $image->save();
        } catch (InvalidManipulation $exception) {
            report($exception);
        }
    }
}
