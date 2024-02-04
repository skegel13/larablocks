# Larablocks

A simple package for creating Laravel modules compatible with the Laravel IDEA plugin

This package was inspired by the [Modular Laravel](https://laracasts.com/series/modular-laravel) series by [Mateus Guimarães](https://mateusguimaraes.com/) on [Laracasts](https://laracasts.com/referral/skegel13).

## Installation

You can install the package via composer:

```bash
composer require skegel13/larablocks
```

You can publish the stubs with:

```bash
php artisan vendor:publish --tag="larablocks-stubs"
```

## Usage

Create a block with:

```bash
# Create block
php artisan make:block Blog

# Delete block
php artisan make:block Blog --delete
```

If you are using the Laravel IDEA plugin, update your module settings to use "Directory modules".

Then, set the "Sources path" to "src" and the "Root directory path" to "Modules".

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Sean Kegel](https://github.com/skegel13)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
