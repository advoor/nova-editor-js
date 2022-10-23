<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use Advoor\NovaEditorJs\Contracts\ImageUploadHandler;
use Advoor\NovaEditorJs\Services\DefaultImageUploadHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase;

class DefaultImageUploadHandlerTest extends TestCase
{
    private array $createdImages = [];

    /**
     * Set disk and path before tests
     * @before
     */
    public function setImagePaths()
    {
        $this->afterApplicationCreated(function () {
            Config::set([
            'nova-editor-js.toolSettings.image.disk' => 'local',
            'nova-editor-js.toolSettings.image.path' => 'public/images',
        ]);

            Storage::fake('local');
        });
    }

    /**
     * @after
     */
    public function deleteTempImages(): void
    {
        foreach ($this->createdImages as $image) {
            @unlink($image);
        }
    }

    /**
     * A basic feature test example.
     *
     */
    public function testUploadHandling(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'editorjs-test-');
        file_put_contents($tempFile, file_get_contents(test_resource('responses/image.jpg')));

        /** @var ImageUploadHandler $service */
        $service = App::make(DefaultImageUploadHandler::class);

        $url = $service->saveImage(new File($tempFile));

        $this->assertStringContainsString('storage/images', $url);

        $localPath = str_replace($url, "storage/images", "public/images");
        Storage::disk('local')->assertExists($localPath);
    }
}
