<?php

namespace Ecommify\Maropost\OAuth;

use Ecommify\Auth\OAuth as BaseOAuth;

class OAuth extends BaseOAuth
{
    /**
     * Return Maropost Auth URL
     * 
     * @return string
     */
    protected function getAuthUrl(): string
    {
        return "https://api.netodev.com/oauth/v2/token?version=2";
    }
}
