<?php

namespace Ecommify\MarketplacerMyer;

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
        Platform::extend('marketplacer_myer', function ($container) {
            return new MarketplacerMyer;
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
            __DIR__ . '/../config/marketplacer-myer.php',
            'marketplacer_myer'
        );
    }
}
