<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs;

use Illuminate\Support\Str;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JsonException;

class NovaEditorJsCast implements CastsAttributes
{
    /**
     * Magic number to describe "this field is broken" in the version
     */
    public const BROKEN_VERSION = '2.12.9999';

    /**
     * Returns an instance of the EditorJsData field warning the user
     * the data is corrupted.
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
     */
    public function get($model, string $key, $value, array $attributes): ?NovaEditorJsData
    {
        try {
            // Recursively decode JSON, to solve a bug where the JSON is double-encoded.
            while (is_string($value) && !empty($value)) {
                $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            }

            // Return null if the new value is null
            return $value === null ? null : new NovaEditorJsData($value);
        } catch (JsonException $exception) {
            return self::getErrorObject($exception->getMessage());
        }
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function set($model, string $key, $value, array $attributes): array
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
            $key => is_string($value) ? $value : json_encode($value),
        ];
    }
}
