<?php

namespace Osem\Commenist\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Osem\Commenist\Contracts\ICommentable;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends LaravelServiceProvider
{

    public function boot()
    {
        $this->app->bind(
            ICommentable::class
        );

        /**
         * Publishers
         */
        $this->publishes([
            __DIR__ . '/../../config/comments.php' => config_path('comments.php'),
        ], 'config');

        /**
         * Load some stuff
         */
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');        
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');        
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/comments.php',
            'comments'
        );
    }
}