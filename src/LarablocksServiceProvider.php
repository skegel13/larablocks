<?php

namespace Skegel13\Larablocks;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Skegel13\Larablocks\Commands\LarablocksCommand;

class LarablocksServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('larablocks')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_larablocks_table')
            ->hasCommand(LarablocksCommand::class);
    }
}
