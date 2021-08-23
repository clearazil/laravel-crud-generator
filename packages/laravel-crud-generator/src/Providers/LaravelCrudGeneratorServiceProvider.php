<?php

namespace Clearazil\LaravelCrudGenerator\Providers;

use Clearazil\LaravelCrudGenerator\Console\Commands\TestCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Undocumented class
 *
 */
class LaravelCrudGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TestCommand::class,
            ]);
        }
    }
}
