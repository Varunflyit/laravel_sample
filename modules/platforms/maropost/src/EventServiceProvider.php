<?php

namespace Ecommify\Maropost;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \Ecommify\Maropost\Socialite\MaropostExtendSocialite::class . '@handle'
        ]
    ];
}
