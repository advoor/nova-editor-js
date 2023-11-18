<?php

declare(strict_types=1);

namespace Tests\Feature\Views;

use Tests\TestCase;

class LinkViewTest extends TestCase
{
    use ViewTestHelpers;

    /**
     * Checks a default render.
     */
    public function testBasic(): void
    {
        $this->view('nova-editor-js::link', [
            'link' => 'https://example.com',
            'meta' => [
                'title' => 'Example Domain',
                'description' => 'This is a description',
            ],
        ])->assertSeeText('Example Domain')
        ->assertSeeText('This is a description')
        ->assertDontSee('<img');
    }

    /**
     * Checks an image render.
     */
    public function testWithImage(): void
    {
        $this->view('nova-editor-js::link', [
            'link' => 'https://example.com',
            'meta' => [
                'title' => 'Example Domain',
                'description' => 'This is a description',
                'imageUrl' => 'https://example.com/image.jpg',
            ],
        ])->assertSeeText('Example Domain')
        ->assertSeeText('This is a description')
        ->assertSee('url(\'https://example.com/image.jpg\')', false);
    }

    /**
     * Checks an old-form image render.
     */
    public function testWithImageInOldFormat(): void
    {
        $this->view('nova-editor-js::link', [
            'link' => 'https://example.com',
            'meta' => [
                'title' => 'Example Domain',
                'description' => 'This is a description',
                'image' => [
                    'url' => 'https://example.com/image.jpg',
                    'caption' => 'This is a caption',
                ],
            ],
        ])->assertSeeText('Example Domain')
        ->assertSeeText('This is a description')
        ->assertSee('url(\'https://example.com/image.jpg\')', false)
        ->assertDontSee('This is a caption');
    }
}
