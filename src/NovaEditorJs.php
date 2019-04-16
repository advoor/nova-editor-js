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
     * Resolve the field's value for display.
     *
     * @param mixed $resource
     * @param string|null $attribute
     * @return void
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

    public static function generateHtmlOutput($jsonData): string
    {
        $config = config('nova-editor-js');

        try {
            // Initialize Editor backend and validate structure
            $editor = new EditorJS($jsonData, json_encode($config));

            // Get sanitized blocks (according to the rules from configuration)
            $blocks = $editor->getBlocks();

            $htmlOutput = '';

            foreach ($blocks as $block) {
                switch ($block['type']) {
                    case 'header':
                        $htmlOutput .= view('nova-editor-js::heading', $block['data'])->render();
                        break;
                    case 'paragraph':
                        $htmlOutput .= view('nova-editor-js::paragraph', $block['data'])->render();
                        break;
                }
            }

            return html_entity_decode($htmlOutput);
        } catch (EditorJSException $e) {
            // process exception
            return 'Something went wrong: ' . $e->getMessage();
        }
    }
}
