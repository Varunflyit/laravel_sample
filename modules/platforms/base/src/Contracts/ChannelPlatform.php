<?php

namespace Ecommify\Platform\Contracts;

interface ChannelPlatform
{
    /**
     * Update shipment tracking
     *
     * @param string $orderId
     * @param array $payload
     * @return array
     */
    public function updateShipmentTracking(string $orderId, array $payload = []): array;
}
