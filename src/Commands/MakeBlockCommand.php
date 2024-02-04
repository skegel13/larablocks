<?php

declare(strict_types=1);

namespace Skegel13\Larablocks\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use JsonException;

class MakeBlockCommand extends Command
{
    public $signature = 'make:block {name} {--F|force} {--D|delete}';

    public $description = 'Create a new block (module).';

    /**
     * @throws JsonException
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $force = $this->option('force');
        $delete = $this->option('delete');

        if ($delete) {
            return $this->deleteModule($name);
        }

        if ($force) {
            $this->info('Forcing module creation');
            File::deleteDirectory(base_path("Modules/{$name}"));
        }

        // Add the new module to the composer.json file
        $this->info('Adding module to composer.json file');
        $composer = json_decode(file_get_contents(base_path('composer.json')), true, 512, JSON_THROW_ON_ERROR);
        $composer['autoload']['psr-4']["Modules\\{$name}\\"] = "Modules/{$name}/src";
        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );

        // Make directories
        $this->info('Creating module structure');
        File::makeDirectory(base_path("Modules/{$name}/Config"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/Database/Factories"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/Database/Migrations"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/Database/Seeders"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/src/Exceptions"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/src/Http/Controllers"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/src/Models"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/src/Providers"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/src/Views/Components"), 0755, true, true);
        File::makeDirectory(base_path("Modules/{$name}/tests"), 0755, true, true);

        // Create files
        $this->info('Creating service providers');
        File::put(
            base_path(
                "Modules/{$name}/src/Providers/{$name}ServiceProvider.php"
            ),
            $this->compileFileStub('ModuleServiceProvider', $name),
        );
        File::put(
            base_path(
                "Modules/{$name}/src/Providers/${name}AuthServiceProvider.php"
            ),
            $this->compileFileStub('ModuleAuthServiceProvider', $name),
        );
        File::put(
            base_path(
                "Modules/{$name}/src/Providers/${name}EventServiceProvider.php"
            ),
            $this->compileFileStub('ModuleEventServiceProvider', $name),
        );
        File::put(
            base_path(
                "Modules/{$name}/src/Providers/{$name}RouteServiceProvider.php"
            ),
            $this->compileFileStub('ModuleRouteServiceProvider', $name),
        );
        File::put(
            base_path(
                "Modules/{$name}/routes.php"
            ),
            $this->compileFileStub('ModuleRoutes', $name),
        );

        // Run composer optimize
        $this->info('Running composer optimize');
        Process::run(['composer', 'dump-autoload']);

        return static::SUCCESS;
    }

    private function compileFileStub(string $stub, string $name): string
    {
        if (File::isFile(base_path("stubs/larablocks/{$stub}.stub.php"))) {
            $fileContents = file_get_contents(base_path("stubs/larablocks/{$stub}.stub.php"));
        } else {
            $fileContents = file_get_contents(__DIR__."/../../stubs/{$stub}.stub.php");
        }

        return str_replace(
            ['_Module_', '_module_'],
            [$name, strtolower($name)],
            $fileContents
        );
    }

    /**
     * @throws JsonException
     */
    public function deleteModule(bool|array|string|null $name): int
    {
        $this->info('Deleting module');
        File::deleteDirectory(base_path("Modules/{$name}"));

        // Remove from composer.json
        $this->info('Removing module from composer.json');
        $composer = json_decode(file_get_contents(base_path('composer.json')), true, 512, JSON_THROW_ON_ERROR);
        unset($composer['autoload']['psr-4']["Modules\\{$name}\\"]);
        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );

        // Run composer optimize
        $this->info('Running composer optimize');
        Process::run(['composer', 'dump-autoload']);

        return static::SUCCESS;
    }
}
