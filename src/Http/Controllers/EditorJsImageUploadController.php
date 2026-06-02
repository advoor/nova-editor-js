<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs\Http\Controllers;

use Advoor\NovaEditorJs\Events\EditorJsImageUploaded;
use Advoor\NovaEditorJs\Events\EditorJsThumbnailCreated;
use finfo;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Image;

class EditorJsImageUploadController extends Controller
{
    private const VALID_IMAGE_MIMES = [
        'image/jpeg',
        'image/webp',
        'image/gif',
        'image/png',
        'image/svg+xml',
    ];

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

        $path = $request->file('image')->store(
            config('nova-editor-js.toolSettings.image.path'),
            config('nova-editor-js.toolSettings.image.disk')
        );

        if (config('nova-editor-js.toolSettings.image.disk') !== 'local') {
            $tempPath = $request->file('image')->store(
                config('nova-editor-js.toolSettings.image.path'),
                'local'
            );

            $this->applyAlterations(Storage::disk('local')->path($tempPath));
            $thumbnails = $this->applyThumbnails($tempPath);

            $this->deleteThumbnails(Storage::disk('local')->path($tempPath));
            Storage::disk('local')->delete($tempPath);
        } else {
            $this->applyAlterations(Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->path($path));
            $thumbnails = $this->applyThumbnails($path);
        }

        event(new EditorJsImageUploaded(config('nova-editor-js.toolSettings.image.disk'), $path));

        return response()->json([
            'success' => 1,
            'file' => [
                'url' => Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->url($path),
                'thumbnails' => $thumbnails,
            ],
        ]);
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
        } catch (ConnectionException|RequestException) {
            return response()->json([
                'success' => 0,
            ]);
        }

        // Validate mime type
        $mime = (new finfo)->buffer($response->body(), FILEINFO_MIME_TYPE);
        if (! in_array($mime, self::VALID_IMAGE_MIMES, true)) {
            return response()->json([
                'success' => 0,
            ]);
        }

        $urlBasename = basename(parse_url(url($url), PHP_URL_PATH));
        $nameWithPath = config('nova-editor-js.toolSettings.image.path').'/'.uniqid().$urlBasename;
        Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->put($nameWithPath, $response->body());
        event(new EditorJsImageUploaded(config('nova-editor-js.toolSettings.image.disk'), $nameWithPath));

        return response()->json([
            'success' => 1,
            'file' => [
                'url' => Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->url($nameWithPath),
            ],
        ]);
    }

    /**
     * @param  array  $alterations
     */
    private function applyAlterations($path, $alterations = [])
    {
        try {
            if (! empty(config('nova-editor-js.toolSettings.image.imagedriver'))) {
                $image = Image::useImageDriver(config('nova-editor-js.toolSettings.image.imagedriver'));
                $image->loadFile($path);
            } else {
                $image = Image::load($path);
            }

            $imageSettings = config('nova-editor-js.toolSettings.image.alterations');

            if (! empty($alterations)) {
                $imageSettings = $alterations;
            }

            if (empty($imageSettings)) {
                return;
            }

            if (! empty($imageSettings['resize']['width'])) {
                $image->width($imageSettings['resize']['width']);
            }

            if (! empty($imageSettings['resize']['height'])) {
                $image->height($imageSettings['resize']['height']);
            }

            if (! empty($imageSettings['optimize'])) {
                $image->optimize();
            }

            if (! empty($imageSettings['adjustments']['brightness'])) {
                $image->brightness($imageSettings['adjustments']['brightness']);
            }

            if (! empty($imageSettings['adjustments']['contrast'])) {
                $image->contrast($imageSettings['adjustments']['contrast']);
            }

            if (! empty($imageSettings['adjustments']['gamma'])) {
                $image->gamma($imageSettings['adjustments']['gamma']);
            }

            if (! empty($imageSettings['effects']['blur'])) {
                $image->blur($imageSettings['effects']['blur']);
            }

            if (! empty($imageSettings['effects']['pixelate'])) {
                $image->pixelate($imageSettings['effects']['pixelate']);
            }

            if (! empty($imageSettings['effects']['greyscale'])) {
                $image->greyscale();
            }
            if (! empty($imageSettings['effects']['sepia'])) {
                $image->sepia();
            }

            if (! empty($imageSettings['effects']['sharpen'])) {
                $image->sharpen($imageSettings['effects']['sharpen']);
            }

            $image->save();
        } catch (InvalidManipulation $exception) {
            report($exception);
        }
    }

    /**
     * @return array
     */
    private function applyThumbnails($path)
    {
        $thumbnailSettings = config('nova-editor-js.toolSettings.image.thumbnails');

        $generatedThumbnails = [];

        if (! empty($thumbnailSettings)) {
            foreach ($thumbnailSettings as $thumbnailName => $setting) {
                $filename = pathinfo($path, PATHINFO_FILENAME);
                $extension = pathinfo($path, PATHINFO_EXTENSION);

                $newThumbnailName = $filename.$thumbnailName.'.'.$extension;
                $newThumbnailPath = config('nova-editor-js.toolSettings.image.path').'/'.$newThumbnailName;

                Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->copy($path, $newThumbnailPath);

                if (config('nova-editor-js.toolSettings.image.disk') !== 'local') {
                    Storage::disk('local')->copy($path, $newThumbnailPath);
                    $newPath = Storage::disk('local')->path($newThumbnailPath);
                } else {
                    $newPath = Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->path($newThumbnailPath);
                }

                $this->applyAlterations($newPath, $setting);

                event(new EditorJsThumbnailCreated(config('nova-editor-js.toolSettings.image.disk'), $newThumbnailPath));

                $generatedThumbnails[] = Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->url($newThumbnailPath);
            }
        }

        return $generatedThumbnails;
    }

    private function deleteThumbnails($path)
    {
        $thumbnailSettings = config('nova-editor-js.toolSettings.image.thumbnails');

        if (! empty($thumbnailSettings)) {
            foreach ($thumbnailSettings as $thumbnailName => $setting) {
                $filename = pathinfo($path, PATHINFO_FILENAME);
                $extension = pathinfo($path, PATHINFO_EXTENSION);

                $newThumbnailName = $filename.$thumbnailName.'.'.$extension;
                $newThumbnailPath = config('nova-editor-js.toolSettings.image.path').'/'.$newThumbnailName;

                Storage::disk('local')->delete($path, $newThumbnailPath);
            }
        }
    }
}
