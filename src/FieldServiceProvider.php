<?php

declare(strict_types=1);

namespace Advoor\NovaEditorJs;

use Advoor\NovaEditorJs\Contracts\ImageUploadHandler;
use Advoor\NovaEditorJs\Services\DefaultImageUploadHandler;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * @property-read \Illuminate\Foundation\Application $app
 */
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

        if (!$this->app->configurationIsCached() && !$this->app->isProduction()) {
            $this->checkForConfigDeprecations();
        }
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

        // Register the image handler service
        $this->app->bind(
            ImageUploadHandler::class,
            config('nova-editor-js.services.image_upload', DefaultImageUploadHandler::class)
        );
    }

    /**
     * Check for deprecated config keys.
     *
     */
    protected function checkForConfigDeprecations(): void
    {
        /** @var ConfigRepository $config */
        $config = $this->app->get('config');
        if ($config->has('nova-editor-js.editorSettings.initialBlock')) {
            trigger_deprecation('advoor/nova-editor-js', '3.1.0', 'The config key "editorSettings.initialBlock" is deprecated. Use "editorSettings.defaultBlock" instead.');
        }
    }
}
