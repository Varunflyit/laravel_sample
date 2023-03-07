<?php

namespace Ecommify\Magento;

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
        Platform::extend('magento', function ($container) {
            return new Magento;
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
            __DIR__ . '/../config/magento.php',
            'magento'
        );
    }
}
