<?php

namespace Ecommify\PLATFORM_NAMESPACE;

use Ecommify\Platform\Facades\Platform;
use Ecommify\PLATFORM_NAMESPACE\Platform as PlatformClass;
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
        Platform::extend('platform-identifier', function ($container) {
            return new PlatformClass;
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
            __DIR__ . '/../config/platform.php',
            'platform-identifier'
        );
    }
}
