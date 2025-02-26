<?php

declare(strict_types=1);

namespace Tests\Feature\Views;

use Illuminate\Support\Facades\View;
use Illuminate\Testing\TestView;

trait ViewTestHelpers
{
    protected function view(string $view, $args = []): TestView
    {
        return new TestView(View::make($view, $args));
    }
}
