<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Advoor\NovaEditorJs\Events\EditorJsImageUploaded;
use Advoor\NovaEditorJs\Events\EditorJsThumbnailCreated;
use Advoor\NovaEditorJs\Http\Controllers\EditorJsImageUploadController;
use finfo;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class EditorJsImageUploadControllerTest extends TestCase
{
    /**
     * @before
     */
    public function registerTestRoutes(): void
    {
        $this->afterApplicationCreated(function () {
            Route::post('/test/image/file', [EditorJsImageUploadController::class, 'file']);
            Route::post('/test/image/url', [EditorJsImageUploadController::class, 'url']);
        });
    }

    /**
     * Test an image upload.
     *
     * @param  string  $path  Path to the image file
     *
     * @dataProvider provideValidFilesForImageUpload
     */
    public function testImageUpload(string $path): void
    {
        Storage::fake();
        Storage::fake('public');
        $fake = Event::fake();
        DB::setEventDispatcher($fake);

        $uploadedFile = UploadedFile::fake()->create('file', 1024, (new finfo)->file($path, FILEINFO_MIME_TYPE));
        if ($fp = $uploadedFile->openFile('w')) {
            $fp->fwrite(file_get_contents($path));
        }

        $response = $this->post('/test/image/file', [
            'image' => $uploadedFile,
        ])->assertOk()->assertJson(['success' => 1]);

        $responseUrl = $response->json('file.url');
        $this->assertNotEmpty($responseUrl, 'Response file URL is empty');

        $storageBaseUrl = Storage::disk('public')->url('');
        $this->assertStringStartsWith($storageBaseUrl, $responseUrl, 'Response URL seems to not be in a public folder');

        $createdFiles = Storage::disk()->allFiles();
        $this->assertCount(2, $createdFiles, 'Storage seems to not contain exactly two files (one upload, one saved)');

        $filesThatLookLikeTheUpload = array_filter(
            $createdFiles,
            fn ($file) => Str::endsWith($file, basename($responseUrl)),
        );

        Event::assertDispatched(EditorJsImageUploaded::class);
        Event::assertDispatched(EditorJsThumbnailCreated::class);
        $this->assertCount(1, $filesThatLookLikeTheUpload, 'Storage doesn\'t seem to contain a file with the same name as the returned URL');
    }

    /**
     * Test uploading a non-image.
     */
    public function testNonImageUpload(): void
    {
        Storage::fake();
        Storage::fake('public');

        $uploadedFile = UploadedFile::fake()->createWithContent('upload', 'Hello World!');

        $response = $this->post('/test/image/file', [
            'image' => $uploadedFile,
        ])->assertOk()->assertJson(['success' => 0]);
    }

    /**
     * Test submitting an image URL causes the file to be stored to disk and returned.
     *
     * @param  string  $file  path to the file returned by the URL
     *
     * @dataProvider provideValidFiles
     */
    public function testValidImageUrlSubmission(string $file): void
    {
        Storage::fake();
        Storage::fake('public');
        $fake = Event::fake();
        DB::setEventDispatcher($fake);

        Http::fake([
            'https://example.com/image.bin' => Http::response(file_get_contents($file)),
        ])->preventStrayRequests();

        $response = $this->post('/test/image/url', [
            'url' => 'https://example.com/image.bin',
        ])->assertOk()->assertJson(['success' => 1]);

        $responseUrl = $response->json('file.url');
        $this->assertNotEmpty($responseUrl, 'Response file URL is empty');

        $storageBaseUrl = Storage::disk('public')->url('');
        $this->assertStringStartsWith($storageBaseUrl, $responseUrl, 'Response URL seems to not be in a public folder');

        $createdFiles = Storage::disk()->allFiles();
        $this->assertCount(1, $createdFiles, 'Storage seems to not contain exactly one file');

        Event::assertDispatched(EditorJsImageUploaded::class);
        $this->assertEquals(basename($createdFiles[0]), basename($responseUrl), 'Response URL filename doesn\'t match created file basename');
    }

    /**
     * Test submitting a non-image URL causes the request to fail.
     */
    public function testInvalidImageUrlSubmission(): void
    {
        Http::fake([
            'https://example.com/image.bin' => Http::response('Hello World!'),
        ])->preventStrayRequests();

        $this->post('/test/image/url', [
            'url' => 'https://example.com/image.bin',
        ])->assertOk()->assertJson(['success' => 0]);
    }

    /**
     * Test submitting a URL that's not valid, but is a properly formed HTTP
     * URL, still sends out a ping (but fails, eventually).
     */
    public function testSubmittingADeadUrl(): void
    {
        Http::fake([
            'https://example.invalid/image.bin' => Http::response('Hello World!'),
        ])->preventStrayRequests();

        $this->post('/test/image/url', [
            'url' => 'https://example.invalid/image.bin',
        ])->assertOk()->assertJson(['success' => 0]);

        Http::assertSentCount(1);
    }

    /**
     * Test submitting a URL which the server won't or cannot provide returns an error.
     * Also implicitly handles timeouts, since that's the same block.
     */
    public function testSubmittingImageUrlWithErrors(): void
    {
        Http::fake([
            'https://example.com/client/image.bin' => Http::response(test_resource('responses/image.png'), Response::HTTP_BAD_GATEWAY),
            'https://example.com/server/image.bin' => Http::response(test_resource('responses/image.png'), Response::HTTP_GONE),
        ])->preventStrayRequests();

        $this->post('/test/image/url', [
            'url' => 'https://example.com/client/image.bin',
        ])->assertOk()->assertJson(['success' => 0]);

        $this->post('/test/image/url', [
            'url' => 'https://example.com/server/image.bin',
        ])->assertOk()->assertJson(['success' => 0]);

        Http::assertSentCount(2);
    }

    /**
     * Provides a list of valid image files to test.
     *
     * @return string[][]
     */
    public static function provideValidFiles(): array
    {
        return [
            'gif' => [test_resource('responses/image.gif')],
            'jpg' => [test_resource('responses/image.jpg')],
            'png' => [test_resource('responses/image.png')],
            'svg' => [test_resource('responses/image.svg')],
            'svg' => [test_resource('responses/image.svg')],
        ];
    }

    /**
     * Provides a subset of the available image formats, since svg isn't supported by the GD library.
     *
     * @return string[][]
     */
    public static function provideValidFilesForImageUpload(): array
    {
        return Arr::except(self::provideValidFiles(), [
            'svg',
        ]);
    }
}
