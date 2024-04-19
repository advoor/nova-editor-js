<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs;

use JsonException;
use Laravel\Nova\Fields\Field;

class NovaEditorJsField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-editor-js';

    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        // DEPRECATED: Simplify to `nova-editor-js.editorSettings.defaultBlock` in v4.0.0
        $defaultBlock = config('nova-editor-js.editorSettings.initialBlock', config('nova-editor-js.editorSettings.defaultBlock', 'paragraph'));

        $this->withMeta([
            'editorSettings' => [
                'placeholder' => config('nova-editor-js.editorSettings.placeholder', ''),
                'defaultBlock' => $defaultBlock,
                'autofocus' => config('nova-editor-js.editorSettings.autofocus', false),
                'rtl' => config('nova-editor-js.editorSettings.rtl', false),
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
     * @param  string|null  $attribute
     *
     * @throws \Throwable
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        $attribute = $attribute ?? $this->attribute;
        if ($attribute === 'ComputedField') {
            return;
        }

        $value = data_get($resource, str_replace('->', '.', $attribute), $placeholder = new \stdClass());

        if (is_callable($this->resolveCallback)) {
            $value = call_user_func($this->resolveCallback, $value, $resource, $attribute);
        }

        if (! $this->displayCallback) {
            $this->withMeta(['asHtml' => true]);
            $this->value = (string) NovaEditorJs::generateHtmlOutput($value);

            return;
        }

        if (! is_callable($this->displayCallback) || $value === $placeholder) {
            return;
        }

        // Convert from JSON
        if (is_string($value)) {
            try {
                $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                //
            }
        }

        if ($value !== null) {
            $value = new NovaEditorJsData($value);
        }

        $this->value = call_user_func($this->displayCallback, $value);
    }
}
