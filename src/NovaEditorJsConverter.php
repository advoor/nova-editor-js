<?php

namespace Advoor\NovaEditorJs;

use Closure;
use EditorJS\EditorJS;
use EditorJS\EditorJSException;
use Illuminate\Support\HtmlString;
use JsonException;

class NovaEditorJsConverter
{
    /**
     * List of callbacks that can render blocks
     * @var array<Closure>
     */
    protected array $renderCallbacks = [];

    public function __construct()
    {
        $this->registerDefaultCallbacks();
    }

    /**
     * Add a custom render callback for the given block.
     *
     * @param string   $block  Name of the block, as defined in the JSON
     * @param callable $callback  Closure that returns a string (or a Stringable)
     * @return void
     */
    public function addRender(string $block, callable $callback): void
    {
        $this->renderCallbacks[$block] = $callback;
    }

    /**
     * Renders the given EditorJS data to safe HTML.
     *
     * @param iterable|string $data Data to render
     * @return HtmlString           Safe, directly returnable string.
     */
    public function generateHtmlOutput(iterable|string $data): HtmlString
    {
        if (empty($data)) {
            return new HtmlString('');
        }

        // Clean non-string data
        if (!is_string($data)) {
            try {
                $data = json_encode($data, JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                // noop
            }
        }

        $config = config('nova-editor-js.validationSettings');

        try {
            // Initialize Editor backend and validate structure
            $editor = new EditorJS($data, json_encode($config));

            // Get sanitized blocks (according to the rules from configuration)
            $blocks = $editor->getBlocks();

            $htmlOutput = '';

            foreach ($blocks as $block) {
                if (array_key_exists($block['type'], $this->renderCallbacks)) {
                    $htmlOutput .= $this->renderCallbacks[$block['type']]($block);
                }
            }

            return new HtmlString(
                view('nova-editor-js::content', ['content' => $htmlOutput])->render()
            );
        } catch (EditorJSException $exception) {
            // process exception
            return new HtmlString(
                "Something went wrong: {$exception->getMessage()}"
            );
        }
    }

    /**
     * Registers all default render helpers
     */
    protected function registerDefaultCallbacks(): void
    {
        $this->addRender(
            'header',
            fn ($block) => view('nova-editor-js::heading', $block['data'])->render()
        );

        $this->addRender(
            'paragraph',
            fn ($block) => view('nova-editor-js::paragraph', $block['data'])->render()
        );

        $this->addRender(
            'list',
            fn ($block) => view('nova-editor-js::list', $block['data'])->render()
        );

        $this->addRender(
            'image',
            fn ($block) => view('nova-editor-js::image', array_merge($block['data'], [
                'classes' => $this->calculateImageClasses($block['data'])
            ]))->render()
        );

        $this->addRender(
            'code',
            fn ($block) => view('nova-editor-js::code', $block['data'])->render()
        );

        $this->addRender(
            'linkTool',
            fn ($block) => view('nova-editor-js::link', $block['data'])->render()
        );

        $this->addRender(
            'checklist',
            fn ($block) => view('nova-editor-js::checklist', $block['data'])->render()
        );

        $this->addRender(
            'delimiter',
            fn ($block) => view('nova-editor-js::delimiter', $block['data'])->render()
        );

        $this->addRender(
            'table',
            fn ($block) => view('nova-editor-js::table', $block['data'])->render()
        );

        $this->addRender(
            'raw',
            fn ($block) => view('nova-editor-js::raw', $block['data'])->render()
        );

        $this->addRender(
            'embed',
            fn ($block) => view('nova-editor-js::embed', $block['data'])->render()
        );
    }

    /**
     * @param $blockData
     * @return string
     */
    protected function calculateImageClasses($blockData)
    {
        $classes = [];
        foreach ($blockData as $key => $data) {
            if (is_bool($data) && $data === true) {
                $classes[] = $key;
            }
        }

        return implode(' ', $classes);
    }
}