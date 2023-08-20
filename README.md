# Laravel: release protection

![Packagist License](https://img.shields.io/packagist/l/think.studio/laravel-release-protection?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/laravel-release-protection)](https://packagist.org/packages/think.studio/laravel-release-protection)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/laravel-release-protection)](https://packagist.org/packages/think.studio/laravel-release-protection)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/laravel-release-protection/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-release-protection/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/laravel-release-protection/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-release-protection/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/laravel-release-protection/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-release-protection/?branch=main)

Protect your routes for not allowed users.

## Installation

Install the package via composer:

```bash
composer require think.studio/laravel-release-protection
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="ReleaseProtection\ServiceProvider" --tag="config"
```

Configuration in *.env* (optional)

```dotenv
RPROTECT_TESTERS_EMAILS="myemail@test.com,otheremail@test.com"
RPROTECT_FIRST_PARTY_IPS="123.4.5.6,123.4.5.7"
```

## Usage

Add new middlewares

```php
# app/Http/Kernel.php
protected $routeMiddleware = [
    // ...
    'first-party-ip'          => \ReleaseProtection\Http\Middleware\VerifyFirstPartyClientIp::class,
    'testers-email'           => \ReleaseProtection\Http\Middleware\TestersEmailMiddleware::class,
];
```

Add middleware to routes

```php
Route::middleware([ 'auth', 'testers-email:auth' ])
     ->group(function () {
     // 
     });
Route::get( 'my/events', \App\Http\Controllers\Events::class )
             ->middleware( [ 'first-party-ip:events' ] );
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
