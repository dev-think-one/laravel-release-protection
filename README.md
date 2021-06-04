# Laravel: release protection

Protect your routes for not allowed users.

## Installation

Install the package via composer:

```bash
composer require yaroslawww/laravel-release-protection
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

```injectablephp
# app/Http/Kernel.php
protected $routeMiddleware = [
    // ...
    'first-party-ip'          => \ReleaseProtection\Http\Middleware\VerifyFirstPartyClientIp::class,
    'testers-email'           => \ReleaseProtection\Http\Middleware\TestersEmailMiddleware::class,
];
```

Add middleware to routes

```injectablephp
Route::middleware([ 'auth', 'testers-email:auth' ])
     ->group(function () {
     // 
     });
Route::get( 'my/events', \App\Http\Controllers\Events::class )
             ->middleware( [ 'first-party-ip:events' ] );
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
