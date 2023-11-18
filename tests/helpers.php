<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (!function_exists('test_path')) {
    /**
     * Returns the absolute path to the given test file.
     */
    function test_path(string $path = ''): string
    {
        return __DIR__ . Str::start($path, '/');
    }
}

if (!function_exists('test_resource')) {
    /**
     * Returns the absolute path to the given test resource.
     */
    function test_resource(string $path = ''): string
    {
        return test_path('resources/' . ltrim($path, '/'));
    }
}
