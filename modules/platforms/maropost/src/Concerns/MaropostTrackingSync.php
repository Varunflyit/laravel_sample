<?php

namespace Ecommify\Maropost\Concerns;

use Ecommify\Platform\Contracts\Platform;
use Ecommify\Platform\Contracts\SourcePlatform;
use Ecommify\Integration\Exceptions\InvalidMappingException;
use Ecommify\Integration\Exceptions\SyncLifecycleFailed;
use Ecommify\Integration\Mappings\SyncOptionMapper;
use Ecommify\Integration\SyncLifecycle;
use Ecommify\Maropost\OrderState;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

/**
 * @property Platform|SourcePlatform $sourcePlatform
 */
trait MaropostTrackingSync
{
    /**
     * Get dispatched order from source platform
     *
     * @param string $orderId
     * @return array
     */
    protected function getDispatchedOrder(string $orderId)
    {
        $payload = [
            'Filter' => [
                'OrderID' => $orderId,
                'OrderStatus' => OrderState::DISPATCHED,
                'OutputSelector' => Config::get('maropost.output_selector.shipment')
            ]
        ];

        $response = $this->sourcePlatform->getOrders($payload);

        $tracking = [];
        $order = Arr::first(Arr::get($response, 'Order'));
        if ($order) {
            $mappingOptions = $this->getMappingOptions();
            if ($carrierFallback = Arr::get($mappingOptions, 'source_carrier_invalid_mapping')) {
                $tracking = array_merge($tracking, [
                    'ShippingServiceName' => $carrierFallback,
                    'ShippingServiceID' => $carrierFallback
                ]);
            }

            if ($trackingFallback = Arr::get($mappingOptions, 'source_tracking_invalid_mapping')) {
                $tracking = array_merge($tracking, [
                    'ShippingTracking' => $trackingFallback
                ]);
            }
        }

        foreach (Arr::get($order, 'OrderLine', []) as $orderLine) {
            if ($orderLine['ShippingTracking']) {
                $tracking = array_merge($tracking, $orderLine);
                break;
            }
        }

        return [
            'payload' => $payload,
            'response' => $response,
            'tracking' => ['OrderLine' => $tracking], // Added OrderLine for formula variable
        ];
    }
}
