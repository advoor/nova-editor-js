<?php

namespace Advoor\NovaEditorJs;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        $this->publishes([
            __DIR__ . '/config/nova-editor-js.php' => base_path('config/nova-editor-js.php'),
        ], 'editorjs-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/nova-editor-js'),
        ], 'views');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-editor-js');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-editor-js', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-editor-js', __DIR__ . '/../dist/css/field.css');
        });
    }

    /**
     * Register the fields's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/editor-js-field')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the converter
        $this->app->singleton(NovaEditorJsConverter::class);
        $this->app->alias(NovaEditorJsConverter::class, 'nova-editor-js');
    }
}
