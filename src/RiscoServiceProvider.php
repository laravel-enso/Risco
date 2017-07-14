<?php

namespace LaravelEnso\Risco;

use Illuminate\Support\ServiceProvider;

class RiscoServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishesAll();
        $this->loadDependencies();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function publishesAll()
    {
        $this->publishes([
            __DIR__.'/config/risco.php' => config_path('risco.php'),
        ], 'risco-config');

        $this->publishes([
            __DIR__.'/config' => config_path(),
        ], 'enso-config');

    }

    private function loadDependencies()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravel-enso/risco');
    }
}
