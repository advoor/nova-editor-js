<?php

declare(strict_types=1);

namespace Tests;

use Advoor\NovaEditorJs\FieldServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tests\Fixtures\TestServiceProvider;

/**
 * Defines basic system to test with
 */
class TestCase extends OrchestraTestCase
{
    use DatabaseMigrations;

    /**
     * Ensure the HTTP Client requests have a method to prevent stray requests (without logic)
     *
     * @before
     */
    protected function repairLaravel8Compatibiliy()
    {
        if (! \Composer\InstalledVersions::satisfies(new \Composer\Semver\VersionParser, 'illuminate/support', '^8.0')) {
            return;
        }

        $this->afterApplicationCreated(function () {
            \Illuminate\Http\Client\PendingRequest::macro('preventStrayRequests', function () {
                return $this;
            });
        });
    }

    /**
     * Path to config file from here
     */
    private const CONFIG_PATH = __DIR__.'/../src/config/nova-editor-js.php';

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            FieldServiceProvider::class,
            TestServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('nova-editor-js', require self::CONFIG_PATH);
        $app['config']->set('database.default', 'testing');
    }
}
