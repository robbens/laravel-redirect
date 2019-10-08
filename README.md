# Laravel Redirect

[![Latest Version on Packagist](https://img.shields.io/packagist/v/robbens/laravel-redirect.svg?style=flat-square)](https://packagist.org/packages/robbens/laravel-redirect)
[![Build Status](https://img.shields.io/travis/robbens/laravel-redirect/master.svg?style=flat-square)](https://travis-ci.org/robbens/laravel-redirect)
[![Quality Score](https://img.shields.io/scrutinizer/g/robbens/laravel-redirect.svg?style=flat-square)](https://scrutinizer-ci.com/g/robbens/laravel-redirect)
[![Total Downloads](https://img.shields.io/packagist/dt/robbens/laravel-redirect.svg?style=flat-square)](https://packagist.org/packages/robbens/laravel-redirect)

Create redirects with ease from ex. /about to /about-us.

## Installation

Install the package via composer:

```bash
composer require robbens/laravel-redirect
```

Publish migrations

```bash
php artisan vendor:publish --provider="Robbens\LaravelRedirect\LaravelRedirectServiceProvider" --tag="migrations"
```

Run migrations

```bash
php artisan migrate
```

## Usage

``` php
Robbens\LaravelRedirect\Models\Redirect::create([
    'from' => '/foo/bar',
    'to' => '/foo/baz',
]);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email yo@robin.se instead of using the issue tracker.

## Credits

- [Robin Nilsson](https://github.com/robbens)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
