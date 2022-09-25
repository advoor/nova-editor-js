<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        'src',
        'tests',
        'routes',
    ]);

return (new Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setCacheFile('.php-cs-fixer.cache')
    ->setRules([
        '@PSR12' => true,

        // Consistent naming of PHPUnit methods
        'php_unit_method_casing' => ['case' => 'camel_case'],
        'php_unit_test_annotation' => ['style' => 'prefix'],

        // Remove duplicate entries from phpdoc (like `@param string` on a typed method, without further info)
        'no_superfluous_phpdoc_tags' => true,

        // Ensure strict types are used
        'declare_strict_types' => true,
        'strict_param' => true,
    ]);
