<?php

namespace Advoor\NovaEditorJs;

use EditorJS\EditorJS;
use EditorJS\EditorJSException;
use Laravel\Nova\Fields\Field;

class NovaEditorJs extends Field
{
    private $editorConfig = [
        'tools' => [
            'header' => [
                'text' => [
                    'type' => 'string',

                ],
                'level' => [
                    'type' => 'int',
                    'canBeOnly' => [1, 2, 3, 4, 5]
                ]
            ],
            'paragraph' => [
                'text' => [
                    'type' => 'string',
                    'allowedTags' => 'i,b,u,a[href]'
                ]
            ],
        ]
    ];
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
            $this->value = $this->generateHtmlOutput();

        } elseif (is_callable($this->displayCallback)) {
            $value = data_get($resource, str_replace('->', '.', $attribute), $placeholder = new \stdClass());

            if ($value !== $placeholder) {
                $this->value = call_user_func($this->displayCallback, $value);
            }
        }
    }

    protected function generateHtmlOutput(): string
    {
        try {
            // Initialize Editor backend and validate structure
            $editor = new EditorJS($this->value, json_encode($this->editorConfig));

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

            return $htmlOutput;
        } catch (EditorJSException $e) {
            // process exception
            return 'Something went wrong: ' . $e->getMessage();
        }
    }
}
