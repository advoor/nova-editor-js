<?php

namespace Advoor\NovaEditorJs;

use EditorJS\EditorJS;
use EditorJS\EditorJSException;
use Laravel\Nova\Fields\Field;

class NovaEditorJs extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-editor-js';

    /**
     * HTML callbacks, used for extending render functionality.
     */
    protected static $hasBootedHtmlCallbacks = false;
    protected static $htmlRenderCallbacks = [];

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'editorSettings' => [
                'placeholder' => config('nova-editor-js.editorSettings.placeholder', ''),
                'initialBlock' => config('nova-editor-js.editorSettings.initialBlock', 'paragraph'),
                'autofocus' => config('nova-editor-js.editorSettings.autofocus', false),
            ],
            'toolSettings' => config('nova-editor-js.toolSettings'),
            'uploadImageByFileEndpoint' => route('editor-js-upload-image-by-file'),
            'uploadImageByUrlEndpoint' => route('editor-js-upload-image-by-url'),
            'fetchUrlEndpoint' => route('editor-js-fetch-url'),
        ]);
    }

    /**
     * Resolve the field's value for display.
     *
     * @param mixed $resource
     * @param string|null $attribute
     * @throws \Throwable
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        $attribute = $attribute ?? $this->attribute;

        if ($attribute === 'ComputedField') {
            return;
        }

        $value = data_get($resource, str_replace('->', '.', $attribute), $placeholder = new \stdClass());

        if (!$this->displayCallback) {

            $this->withMeta(['asHtml' => true]);
            $this->value = $this->generateHtmlOutput($value);

        } elseif (is_callable($this->displayCallback)) {

            if ($value !== $placeholder) {
                $this->value = call_user_func($this->displayCallback, $value);
            }
        }
    }

    /**
     * @param string|mixed $jsonData
     * @return string
     * @throws \Throwable
     */
    public static function generateHtmlOutput($jsonData): string
    {
        if (empty($jsonData)) {
            return '';
        }

        // Clean non-string data
        if (!is_string($jsonData)) {
            $newData = json_encode($jsonData);
            if (json_last_error() === \JSON_ERROR_NONE) {
                $jsonData = $newData;
            }
        }

        $config = config('nova-editor-js.validationSettings');

        static::bootHtmlCallbacks();

        try {
            // Initialize Editor backend and validate structure
            $editor = new EditorJS($jsonData, json_encode($config));

            // Get sanitized blocks (according to the rules from configuration)
            $blocks = $editor->getBlocks();

            $htmlOutput = '';

            foreach ($blocks as $block) {
                if (isset(static::$htmlRenderCallbacks[$block['type']])) {
                    $htmlOutput .= (static::$htmlRenderCallbacks[$block['type']])($block);
                }
            }

            return html_entity_decode(
                view('vendor.nova-editor-js.content', ['content' => $htmlOutput])->render()
            );
        } catch (EditorJSException $e) {
            // process exception
            return 'Something went wrong: ' . $e->getMessage();
        }
    }

    /**
     * @param $blockData
     * @return string
     */
    public static function calculateImageClasses($blockData)
    {
        $classes = [];
        foreach ($blockData as $key => $data) {
            if (is_bool($data) && $data === true) {
                $classes[] = $key;
            }
        }

        return implode(' ', $classes);
    }

    /**
     * Add a custom render callback for the given block.
     *
     * @param          $block
     * @param callable $callback
     */
    public static function addRender($block, callable $callback)
    {
        static::$htmlRenderCallbacks[$block] = $callback;
    }

    /**
     * Boots the HTML callbacks, as to allow extension
     * of HTML output for custom blocks
     *
     * @return void
     */
    protected static function bootHtmlCallbacks()
    {
        if (static::$hasBootedHtmlCallbacks) return;

        static::$htmlRenderCallbacks['header'] = function($block) {
            return view('nova-editor-js::heading', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['paragraph'] = function($block) {
            return view('nova-editor-js::paragraph', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['list'] = function($block) {
            return view('nova-editor-js::list', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['image'] = function($block) {
            $block['data']['classes'] = NovaEditorJs::calculateImageClasses($block['data']);
            return view('nova-editor-js::image', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['code'] = function($block) {
            return view('nova-editor-js::code', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['linkTool'] = function($block) {
            return view('nova-editor-js::link', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['checklist'] = function($block) {
            return view('nova-editor-js::checklist', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['delimiter'] = function($block) {
            return view('nova-editor-js::delimiter', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['table'] = function($block) {
            return view('nova-editor-js::table', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['raw'] = function($block) {
            return view('nova-editor-js::raw', $block['data'])->render();
        };

        static::$htmlRenderCallbacks['embed'] = function($block) {
            return view('nova-editor-js::embed', $block['data'])->render();
        };

        static::$hasBootedHtmlCallbacks = true;
    }
}
