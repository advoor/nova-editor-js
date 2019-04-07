<?php

namespace Advoor\NovaEditorJs;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/nova-editor-js.php' => base_path('config/nova-editor-js.php'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-editor-js');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-editor-js', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-editor-js', __DIR__ . '/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
