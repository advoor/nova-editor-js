<?php

declare(strict_types=1);

namespace Laravel\Nova;

class StaticallyUselessClass
{
    public static function __callStatic($method, $args)
    {
        // no-op
    }
}
