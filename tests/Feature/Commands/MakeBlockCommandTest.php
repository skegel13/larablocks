<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;
use Skegel13\Larablocks\Commands\MakeBlockCommand;

beforeEach(function () {
    $this->processSpy = Process::spy();
});

it('creates a block', function () {
    // Arrange
    $testModule = 'TestModule';
    $basePath = base_path("Modules/{$testModule}");

    // Act
    Artisan::call(MakeBlockCommand::class, [
        'name' => $testModule,
    ]);

    // Assert
    expect("$basePath/Config")->toBeDirectory()
        ->and("$basePath/Database/Factories")->toBeDirectory()
        ->and("$basePath/Database/Migrations")->toBeDirectory()
        ->and("$basePath/Database/Seeders")->toBeDirectory()
        ->and("$basePath/src/Exceptions")->toBeDirectory()
        ->and("$basePath/src/Http/Controllers")->toBeDirectory()
        ->and("$basePath/src/Models")->toBeDirectory()
        ->and("$basePath/src/Providers")->toBeDirectory()
        ->and("$basePath/src/Views/Components")->toBeDirectory()
        ->and("$basePath/tests")->toBeDirectory()
        ->and("$basePath/routes.php")->toBeFile()
        ->and("$basePath/src/Providers/{$testModule}ServiceProvider.php")->toBeFile()
        ->and("$basePath/src/Providers/{$testModule}AuthServiceProvider.php")->toBeFile()
        ->and("$basePath/src/Providers/{$testModule}EventServiceProvider.php")->toBeFile()
        ->and("$basePath/src/Providers/{$testModule}RouteServiceProvider.php")->toBeFile();
})->group('blocks');

it('deletes a block', function () {
    // Arrange
    $testModule = 'TestModule';
    $basePath = base_path("Modules/{$testModule}");
    Artisan::call(MakeBlockCommand::class, [
        'name' => $testModule,
    ]);

    // Act
    Artisan::call(MakeBlockCommand::class, [
        'name' => $testModule,
        '--delete' => true,
    ]);

    // Assert
    expect("$basePath")->not()->toBeDirectory();
})->group('blocks');

it('refreshes composer', function () {
    // Arrange
    $testModule = 'TestModule';

    // Act
    Artisan::call(MakeBlockCommand::class, [
        'name' => $testModule,
    ]);

    // Assert
    // Make sure composer optimize was called.
    expect(Artisan::output())->toContain('Running composer optimize');
    $this->processSpy->shouldHaveReceived()->run(['composer', 'dump-autoload']);
})->group('blocks');

it('refreshes composer on delete', function () {
    // Arrange
    $testModule = 'TestModule';

    // Act
    Artisan::call(MakeBlockCommand::class, [
        'name' => $testModule,
        '--delete' => true,
    ]);

    // Assert
    // Make sure composer optimize was called.
    expect(Artisan::output())->toContain('Running composer optimize');
    $this->processSpy->shouldHaveReceived()->run(['composer', 'dump-autoload']);
})->group('blocks');
