<?php

namespace ReleaseProtection;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/release-protection.php' => config_path('release-protection.php'),
            ], 'config');


            $this->commands([]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/release-protection.php', 'release-protection');
    }
}
