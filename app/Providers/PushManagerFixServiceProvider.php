<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PushManagerFixServiceProvider extends ServiceProvider
{
    /**
     * Register services - register the joynala/maker commands safely
     *
     * @return void
     */
    public function register()
    {
        // Register the commands from joynala/maker without triggering PushManager
        $this->registerMakerCommands();
    }

    /**
     * Bootstrap services - safely boot without triggering PushManager errors
     *
     * @return void
     */
    public function boot()
    {
        // Safely load joynala/maker views
        $this->loadViewsFromMaker();
    }

    /**
     * Register make:model and make:repository commands
     */
    protected function registerMakerCommands()
    {
        if (class_exists('Abedin\Maker\Commands\MakeModel')) {
            $this->app->bind('command.make:model', 'Abedin\Maker\Commands\MakeModel');
            $this->commands(['command.make:model']);
        }

        if (class_exists('Abedin\Maker\Commands\MakeRepository')) {
            $this->app->bind('command.make:repository', 'Abedin\Maker\Commands\MakeRepository');
            $this->commands(['command.make:repository']);
        }
    }

    /**
     * Load views from joynala/maker
     */
    protected function loadViewsFromMaker()
    {
        $viewsPath = __DIR__ . '/../../vendor/joynala/maker/resources/views';
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'joynala.maker');
        }
    }
}

