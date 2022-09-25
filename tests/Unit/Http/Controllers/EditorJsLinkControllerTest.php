<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use Advoor\NovaEditorJs\Http\Controllers\EditorJsLinkController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EditorJsLinkControllerTest extends TestCase
{
    /**
     * @before
     */
    public function registerTestRoutes(): void
    {
        $this->afterApplicationCreated(function () {
            Route::post('/test/url', [EditorJsLinkController::class, 'fetch']);
        });
    }

    /**
     * Checks simple URL fetch.
     */
    public function testFetchValidUrl(): void {
        Http::fake([
            'https://example.com' => Http::response(file_get_contents(test_resource('responses/simple.html'))),
        ])->preventStrayRequests();

        $this->post('/test/url', [
            'url' => 'https://example.com',
        ])->assertOk()->assertJson([
            'success' => 1,
            'meta' => [
                'title' => 'Example Domain',
                'description' => 'This is a description',
            ],
        ]);
    }

    /**
     * Checks simple URL fetch.
     */
    public function testImageDetermination(): void {
        Http::fake([
            'https://example.com' => Http::response(file_get_contents(test_resource('responses/with-image.html'))),
        ])->preventStrayRequests();

        $this->post('/test/url', [
            'url' => 'https://example.com',
        ])->assertOk()->assertJson([
            'success' => 1,
            'meta' => [
                'title' => 'Example Domain with an image',
                'description' => 'This is a description',
                'imageUrl' => 'https://example.com/image.jpg',
            ],
        ]);
    }
}
