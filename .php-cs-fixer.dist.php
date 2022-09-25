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

        // Ensure strict types are used
        'declare_strict_types' => true,
        'strict_param' => true,
    ]);
