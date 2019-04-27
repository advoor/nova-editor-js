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

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
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

        if (!$this->displayCallback) {

            $this->withMeta(['asHtml' => true]);
            $this->value = $this->generateHtmlOutput($this->value);

        } elseif (is_callable($this->displayCallback)) {
            $value = data_get($resource, str_replace('->', '.', $attribute), $placeholder = new \stdClass());

            if ($value !== $placeholder) {
                $this->value = call_user_func($this->displayCallback, $value);
            }
        }
    }

    /**
     * @param $jsonData
     * @return string
     * @throws \Throwable
     */
    public static function generateHtmlOutput($jsonData): string
    {
        $config = config('nova-editor-js.validationSettings');

        try {
            // Initialize Editor backend and validate structure
            $editor = new EditorJS($jsonData, json_encode($config));

            // Get sanitized blocks (according to the rules from configuration)
            $blocks = $editor->getBlocks();

            $htmlOutput = '<div class="editor-js-content">';

            foreach ($blocks as $block) {
                switch ($block['type']) {
                    case 'header':
                        $htmlOutput .= view('nova-editor-js::heading', $block['data'])->render();
                        break;
                    case 'paragraph':
                        $htmlOutput .= view('nova-editor-js::paragraph', $block['data'])->render();
                        break;
                    case 'list':
                        $htmlOutput .= view('nova-editor-js::list', $block['data'])->render();
                        break;
                    case 'image':
                        $block['data']['classes'] = NovaEditorJs::calculateImageClasses($block['data']);
                        $htmlOutput .= view('nova-editor-js::image', $block['data'])->render();
                        break;
                    case 'code':
                        $htmlOutput .= view('nova-editor-js::code', $block['data'])->render();
                        break;
                    case 'linkTool':
                        $htmlOutput .= view('nova-editor-js::link', $block['data'])->render();
                        break;
                    case 'checklist':
                        $htmlOutput .= view('nova-editor-js::checklist', $block['data'])->render();
                        break;
                }
            }

            $htmlOutput .= '</div>';

            return html_entity_decode($htmlOutput);
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
}
