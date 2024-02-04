<?php

namespace Skegel13\Larablocks;

use Illuminate\Support\Facades\File;
use Skegel13\Larablocks\Commands\MakeBlockCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LarablocksServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('larablocks')
            ->hasConfigFile()
            ->hasCommand(MakeBlockCommand::class);
    }

    public function packageBooted(): void
    {
        // Allow publishing the stubs.
        foreach (File::files(__DIR__.'/../stubs') as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $this->publishes([
                $file->getPathname() => base_path("/stubs/{$this->package->shortName()}/{$file->getFilename()}"),
            ], "{$this->package->shortName()}-stubs");
        }
    }
}
