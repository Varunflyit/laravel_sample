<?php

namespace Ecommify\MiraklCatch;

use Ecommify\Platform\Facades\Platform;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Platform::extend('mirakl_catch', function ($container) {
            return new MiraklCatch;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/mirakl.php',
            'mirakl_catch'
        );
    }
}
