<?php

namespace Advoor\NovaEditorJs\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Image;

class EditorJsImageUploadController extends Controller
{
    /**
     * Upload file
     *
     * @param NovaRequest $request
     * @return array
     */
    public function file(NovaRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 0
            ];
        }

        $path = $request->file('image')->store(
            config('nova-editor-js.toolSettings.image.path'),
            config('nova-editor-js.toolSettings.image.disk')
        );


        $this->applyAlterations(Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->path($path));
       // $this->applyThumbnails(Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->path($path));

        return [
            'success' => 1,
            'file' => [
                'url' => Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->url($path)
            ]
        ];
    }

    /**
     * @param NovaRequest $request
     * @return array
     */
    public function url(NovaRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => [
                'required',
                'active_url',
                function ($attribute, $value, $fail) {
                    $imageDetails = getimagesize($value);

                    if (!in_array($imageDetails['mime'] ?? '', [
                        'image/jpeg',
                        'image/webp',
                        'image/gif',
                        'image/png',
                        'image/svg+xml',
                    ])) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return [
                'success' => 0
            ];
        }

        $url = $request->input('url');
        $imageContents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        $nameWithPath = config('nova-editor-js.toolSettings.image.path') . '/' . uniqid() . $name;

        Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->put($nameWithPath, $imageContents);

        return [
            'success' => 1,
            'file' => [
                'url' => Storage::disk(config('nova-editor-js.toolSettings.image.disk'))->url($nameWithPath)
            ]
        ];
    }

    /**
     * @param $path
     */
    private function applyAlterations($path)
    {
        try {
            $image = Image::load($path);

            $imageSettings = config('nova-editor-js.toolSettings.image.alterations');

            if (!empty($imageSettings['resize']['width'])) {
                $image->width($imageSettings['resize']['width']);
            }

            if (!empty($imageSettings['resize']['height'])) {
                $image->height($imageSettings['resize']['height']);
            }

            if (!empty($imageSettings['optimize'])) {
                $image->optimize();
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

    private function applyThumbnails($path)
    {
        $image = Image::load($path);

        $imageSettings = config('nova-editor-js.toolSettings.image');

        dd($imageSettings);
    }
}