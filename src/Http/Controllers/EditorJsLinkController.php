<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs\Http\Controllers;

use DOMDocument;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class EditorJsLinkController extends Controller
{
    /**
     * Determine microdata for the given file.
     */
    public function fetch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
            ]);
        }

        // Contents
        try {
            $url = $request->input('url');
            $response = Http::timeout(5)->get($url)->throw();
        } catch (ConnectionException|RequestException) {
            return response()->json([
                'success' => 0,
            ]);
        }

        $doc = new DOMDocument;
        @$doc->loadHTML((string) $response->getBody());
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

        return response()->json([
            'success' => 1,
            'meta' => array_filter([
                'title' => $title ?? $url,
                'description' => $description,
                'imageUrl' => $imageUrl,
            ]),
        ]);
    }
}
