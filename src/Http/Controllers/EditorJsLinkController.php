<?php

namespace Advoor\NovaEditorJs\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;

class EditorJsLinkController extends Controller
{
    /**
     * Upload file
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fetch(NovaRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|active_url',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 0
            ];
        }

        $contents = file_get_contents($request->input('url'));

        $doc = new \DOMDocument();
        @$doc->loadHTML($contents);
        $nodes = $doc->getElementsByTagName('title');
        $title = $nodes->item(0)->nodeValue;
        $description = '';
        $imageUrl = null;

        $metas = $doc->getElementsByTagName('meta');

        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if ($meta->getAttribute('name') == 'description') {
                $description = $meta->getAttribute('content');
            }

            if ($meta->getAttribute('property') == 'og:image') {
                $imageUrl = $meta->getAttribute('content');
            }
        }

        $results = [
            'success' => 1,
            'meta' => [
                'title' => $title,
                'description' => $description,
            ]
        ];

        if (!empty($imageUrl)){
            $results['meta']['image'] = [
                'url' => $imageUrl,
            ];
        }

        return $results;
    }
}
