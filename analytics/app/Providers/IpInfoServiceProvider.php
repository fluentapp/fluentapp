<?php

namespace App\Providers;

use \Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use App\Support\IpInformation;

class IpInfoServiceProvider extends ServiceProvider {

    /**
     * Register services.
     */
    public function register(): void {
        $this->app->singleton(IpInformation::class, function (Application $app) {
            return new IpInformation();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        //
    }
}
