<?php

namespace Ecommify\Auth;

use Illuminate\Support\Facades\Http;

abstract class OAuth
{
    /**
     * OAuth constructor
     * 
     * @param array $options
     */
    public function __construct(public array $options)
    {
    }

    /**
     * Return OAuth URL
     * 
     * @return string
     */
    abstract protected function getAuthUrl(): string;

    /**
     * Refresh access token
     * 
     * @return array;
     */
    public function refreshAccessToken()
    {
        $response = Http::asForm()
            ->post($this->getAuthUrl(), [
                'client_id' => $this->options['client_id'],
                'client_secret' => $this->options['client_secret'],
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->options['refresh_token'],
            ])
            ->json();

        return $response;
    }
}
