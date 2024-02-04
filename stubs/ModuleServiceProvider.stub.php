<?php

namespace Modules\_Module_\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class _Module_ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../../Database/Migrations');
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', '_module_');

        $this->app->register(_Module_AuthServiceProvider::class);
        $this->app->register(_Module_EventServiceProvider::class);
        $this->app->register(_Module_RouteServiceProvider::class);

        $this->loadViewsFrom(__DIR__.'/../../Views', '_module_');
        Blade::anonymousComponentPath(__DIR__.'/../../Views/Components', '_module_');
        Blade::componentNamespace('Modules\\_Module_\\ViewComponents', '_module_');
    }
}
