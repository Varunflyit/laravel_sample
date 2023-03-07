<?php

namespace Ecommify\MiraklCatch;

use Ecommify\Platform\Contracts\ChannelPlatform;
use Ecommify\Mirakl\Mirakl;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class MiraklCatch extends Mirakl implements ChannelPlatform
{
    /**
     * Platform name
     *
     * @var string
     */
    public $name = 'Mirakl Catch';

    /**
     * Platform identifier
     *
     * @var string
     */
    public $identifier = 'mirakl_catch';

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
            ->baseUrl(Config::get('mirakl_catch.api.base_url'));
    }

    /**
     * Accept order
     *
     * @param string $orderId
     * @param array $payload
     * @return array
     */
    public function acceptOrder(string $orderId, array $payload = []): array
    {
        try {
            $this->http()
                ->throw()
                ->put("/orders/{$orderId}/accept", $payload);

            return [];
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }
}
