<?php

namespace Ecommify\Maropost\Socialite;

use SocialiteProviders\Manager\SocialiteWasCalled;

class MaropostExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('maropost', Provider::class);
    }
}
