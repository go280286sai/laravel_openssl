<?php

namespace go280286sai\laravel_openssl\Providers;

use go280286sai\laravel_openssl\Commands\NewOpenSSL;
use go280286sai\laravel_openssl\Commands\ShowPublicKeySSL;
use Illuminate\Support\ServiceProvider;

class OpensslProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
               NewOpenSSL::class,
                ShowPublicKeySSL::class
            ]);
        }
        $this->publishes([
            __DIR__.'/../config/openssl.php' => config_path('openssl.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/../routes/web_json.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../resources/views/openssl' => resource_path('views/vendor/openssl'),
        ]);

    }
}
