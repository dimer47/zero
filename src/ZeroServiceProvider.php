<?php

namespace Dimer47\Zero;

use Illuminate\Support\ServiceProvider;

class ZeroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../runtimes' => $this->app->basePath('docker'),
            ], ['zero', 'zero-docker']);

            $this->publishes([
                __DIR__ . '/../bin/zero' => $this->app->basePath('zero'),
            ], ['zero', 'zero-bin']);
        }
    }
}
