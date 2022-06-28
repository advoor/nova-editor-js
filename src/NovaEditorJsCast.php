<?php

namespace Advoor\NovaEditorJs;

use Illuminate\Support\Str;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JsonException;

class NovaEditorJsCast implements CastsAttributes
{
    /**
     * Magic number to describe "this field is broken" in the version
     */
    private const BROKEN_VERSION = '2.12.9999';

    /**
     * Returns an instance of the EditorJsData field warning the user
     * the data is corrupted.
     * @return NovaEditorJsData
     */
    private static function getErrorObject(string $exceptionMessage): NovaEditorJsData
    {
        return new NovaEditorJsData([
            'time' => now(),
            'version' => self::BROKEN_VERSION,
            'blocks' => [
                [
                    "id" => Str::random(10),
                    'type' => 'paragraph',
                    'data' => [
                        'text' => sprintf(
                            "<b>Oh no!</b><br>It looks like this component failed to load.<br>Please contact your system administrator.<br>Error code: %s",
                            e($exceptionMessage)
                        ),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return NovaEditorJsData|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        try {
            return new NovaEditorJsData(json_decode($value, true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $exception) {
            return self::getErrorObject($exception->getMessage());
        }
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return [
                $key => null,
            ];
        }

        // Refuse to write if the value is marked as broken
        if ($value instanceof NovaEditorJsData && $value->version === self::BROKEN_VERSION) {
            return [];
        }

        return [
            $key => json_encode($value),
        ];
    }
}
