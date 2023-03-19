<?php

namespace Ecommify\Maropost;

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
        Platform::extend('maropost', function ($container) {
            return new Maropost;
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
            __DIR__ . '/../config/maropost.php',
            'maropost'
        );

        $this->app->register(EventServiceProvider::class);
    }
}
