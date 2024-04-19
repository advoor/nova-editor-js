<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->useDatabasePath(__DIR__.'/database/');
    }
}
