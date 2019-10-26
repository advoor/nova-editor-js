<?php

declare(strict_types=1);

namespace Tests\Unit;

use Advoor\NovaEditorJs\NovaEditorJs;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JsonContentTest extends TestCase
{
    /**
     * Path to JSON file with valid contents
     */
    private const TEST_FILE_JSON = __DIR__ . '/../resources/json/editorjs.json';
    private const TEST_FILE_HTML = __DIR__ . '/../resources/html/editorjs.html';

    /**
     * Returns JSON contents from the file
     *
     * @return string[]
     */
    private function getFileContents(): array
    {
        return [
            'json' => file_get_contents(self::TEST_FILE_JSON),
            'html' => file_get_contents(self::TEST_FILE_HTML)
        ];
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStringValue(): void
    {
        // Get contents
        $contents = $this->getFileContents();

        // Verify JSON
        $json = $contents['json'];
        $this->assertIsString($json);

        // Convert to HTML
        $html = NovaEditorJs::generateHtmlOutput($json);

        // Ensure identicality
        $this->assertEquals($contents['html'], $html);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testJsonValue(): void
    {
        // Get contents
        $contents = $this->getFileContents();

        // Verify JSON
        $json = $contents['json'];
        $this->assertIsString($json);

        // Convert to array (just like Laravel would do)
        $json = json_decode($json, true);
        $this->assertIsArray($json);

        // Convert to HTML
        $html = NovaEditorJs::generateHtmlOutput($json);

        // Ensure identicality
        $this->assertEquals($contents['html'], $html);
    }
}
