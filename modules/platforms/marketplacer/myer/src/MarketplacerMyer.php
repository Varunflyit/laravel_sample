<?php

namespace Ecommify\MarketplacerMyer;

use Ecommify\Platform\Contracts\ChannelPlatform;
use Ecommify\Marketplacer\Marketplacer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class MarketplacerMyer extends Marketplacer implements ChannelPlatform
{
    /**
     * Platform name
     *
     * @var string
     */
    public $name = 'Myer Market';

    /**
     * Platform identifier
     *
     * @var string
     */
    public $identifier = 'marketplacer_myer';

    /**
     * Marketplacer http instance
     *
     * @return PendingRequest
     */
    protected function http()
    {
        return Http::acceptJson()
            ->withHeaders([
                'MARKETPLACER-API-KEY' => $this->attribute('MARKETPLACER-API-KEY'),
                'Content-Type' => 'application/vnd.api+json'
            ])
            ->baseUrl(Config::get('marketplacer_myer.api.base_url'));
    }
}
