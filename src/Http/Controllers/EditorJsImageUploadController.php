<?php

namespace Advoor\NovaEditorJs\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;

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
            config('nova-editor-js.image.path'),
            config('nova-editor-js.image.disk')
        );

        return [
            'success' => 1,
            'file' => [
                'url' => Storage::disk(config('nova-editor-js.image.disk'))->url($path)
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
        $nameWithPath = config('nova-editor-js.image.path') . '/' . uniqid() . $name;

        Storage::disk(config('nova-editor-js.image.disk'))->put($nameWithPath, $imageContents);

        return [
            'success' => 1,
            'file' => [
                'url' => Storage::disk(config('nova-editor-js.image.disk'))->url($nameWithPath)
            ]
        ];
    }
}