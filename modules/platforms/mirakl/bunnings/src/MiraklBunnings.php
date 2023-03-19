<?php

namespace Ecommify\MiraklBunnings;

use Ecommify\Platform\Contracts\ChannelPlatform;
use Ecommify\Mirakl\Mirakl;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class MiraklBunnings extends Mirakl implements ChannelPlatform
{
    /**
     * Platform name
     *
     * @var string
     */
    public $name = 'Mirakl Bunnings';

    /**
     * Platform identifier
     *
     * @var string
     */
    public $identifier = 'mirakl_bunnings';

    /**
     * Mirakl http instance
     *
     * @return PendingRequest
     */
    protected function http()
    {
        return Http::acceptJson()
            ->withHeaders([
                'Authorization' => $this->attribute('shop_key'),
                'Content-Type' => 'application/json'
            ])
            ->baseUrl(Config::get('mirakl_bunnings.api.base_url'));
    }
}
