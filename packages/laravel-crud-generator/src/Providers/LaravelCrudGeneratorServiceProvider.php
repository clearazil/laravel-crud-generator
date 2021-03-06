<?php

namespace Clearazil\LaravelCrudGenerator\Providers;

use Clearazil\LaravelCrudGenerator\Console\Commands\CreateCommand;
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
                CreateCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/config/crudgenerator.php' =>  config_path('crudgenerator.php'),
         ], 'config');
        $this->publishes([
            __DIR__.'/../resources/crud-generator' => resource_path('crud-generator'),
        ]);
    }
}
