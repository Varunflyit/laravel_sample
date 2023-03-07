<?php

namespace Ecommify\Marketplacer;

use Ecommify\Platform\BasePlatform;
use Ecommify\Platform\Contracts\ChannelPlatform;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

abstract class Marketplacer extends BasePlatform implements ChannelPlatform
{
    /**
     * Platform marketplace
     *
     * @var bool
     */
    public $marketplace = true;

    /**
     * Marketplacer http instance
     *
     * @return PendingRequest
     */
    abstract protected function http();

    /**
     * Get orders data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getOrders(array $options = []): array
    {
        try {
            return $this->http()
                ->get('/client/invoices', $options)
                ->throw()
                ->json();
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get single order data from Myer
     *
     * @param string $orderId
     * @return array
     */
    public function getOrder(string $orderId): array
    {
        try {
            $orderIds = explode(',', $orderId);
            $orderId = Arr::first($orderIds);

            return $this->http()
                ->get("/client/invoices/{$orderId}")
                ->throw()
                ->json();
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Update shipment tracking
     *
     * @param string $orderId
     * @param array $payload
     * @return array
     */
    public function updateShipmentTracking(string $orderId, array $payload = []): array
    {
        $orderIds = explode(',', $orderId);

        $response = [];
        foreach ($orderIds as $id) {
            try {
                $response[$id] = $this->http()
                    ->put("/client/invoices/{$id}/sent", [
                        'data' => [
                            'type' => 'invoices',
                            'attributes' => $payload
                        ]
                    ])
                    ->throw()
                    ->json();

                Log::info($response[$id]);
            } catch (Exception $e) {
                Log::info($e);
                if ($e->getCode() == 422) {
                    $order = $this->getOrder($id);
                    if (Arr::get($order, 'data.attributes.status') == OrderState::SENT) {
                        $response[$id] = $order;
                        continue;
                    }
                }

                return $this->handleApiRequestException($e);
            }
        }

        return $response;
    }

    /**
     * Get shipping method data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function getShippingMethod(array $options = []): array
    {
        return [];
    }

    /**
     * Get order id field
     * 
     * This method is used to get the platform order identifier.
     * @see \Ecommify\Integration\Concerns\PlatformAttributeFinder::getOrderId()
     *
     * @return string
     */
    public function getOrderIdField(): string
    {
        return 'data.id';
    }

    /**
     * Get order line key
     *
     * This method is used to map order line.
     * @see \Ecommify\Integration\Services\Sync\BaseOrderSync::mapOrderAttributes()
     * @return string
     */
    public function getOrderLineKey(): string
    {
        return 'included.line_items';
    }

    /**
     * Get order status field.
     *
     * @return string
     */
    public function getOrderStatusField(): string
    {
        return 'data.attributes.status';
    }

    /**
     * Get grand total field.
     *
     * @return string
     */
    public function getGrandTotalField(): string
    {
        return 'data.attributes.total';
    }

    /**
     * Get Categories data from Platform
     *
     * @param array $options
     * @return array
     */
    public function getCategories(array $options = []): array
    {
        return [];
    }

    /**
     * Check test connection
     *
     * @return boolean
     */
    public function testConnection(): bool
    {
        try {
            $response = $this->getOrders([
                'page' => [
                    'size' => 1,
                ]
            ]);

            return is_array($response);
        } catch (Exception $e) {
        }

        return false;
    }

    /**
     * Get value for code from platform
     *
     * @param string $code
     * @return array
     */
    public function getValues(string $code): array
    {
        $data = array();

        return $this->getCodeLabelValueType($data);
    }

    /**
     * Get Formated Category for database
     *
     * @param array $category
     * @param string $instanceId
     * @return array
     */
    public function categoryObjectFormat(array $category, string $instanceId): array
    {
        $category = collect($category)->map(function ($item) use ($instanceId) {
            $item['integration_instance_id'] = $instanceId;
            $item['content_id'] = $item['code'];
            $item['content_name'] = $item['label'];
            $item['parent_content_id'] = $item['parent_code'] != '' ? $item['parent_code'] : 0;

            unset($item['code']);
            unset($item['label']);
            unset($item['parent_code']);
            unset($item['label_translations']);
            unset($item['level']);
            return $item;
        })->toArray();

        return $category;
    }

    /**
     * Get Formated Value List for database
     *
     * @param array $valueList
     * @param string $type
     * @param string $instanceId
     * @return array
     */
    public function valueListObjectFormat(array $valueList, string $type, string $instanceId): array
    {
        $valueList = collect($valueList)->map(function ($item) use ($type, $instanceId) {
            $item['integration_instance_id'] = $instanceId;
            $item['type'] = $type;
            return $item;
        })->toArray();

        return $valueList;
    }

    /**
     * Get Code label array from Platform api response
     *
     * @param array $category
     * @return array
     */
    public function PlatformCategoryObjectFormat(array $category = []): array
    {
        $category = collect($category)->map(function ($item) {
            $categoryItem = array();
            $categoryItem['platform'] = $this->identifier;
            $categoryItem['content_id'] = $item['code'];
            $categoryItem['content_name'] = $item['label'];
            $categoryItem['parent_content_id'] = $item['parent_code'] != '' ? $item['parent_code'] : 0;
            return $categoryItem;
        })->toArray();

        return $category;
    }

    /**
     * Get Formated Value List for database
     *
     * @param array $valueList
     * @param string $type
     * @return array
     */
    public function PlatformValueListObjectFormat(array $valueList, string $type): array
    {
        $valueList = collect($valueList)->map(function ($item) use ($type) {
            $item['platform'] = $this->identifier;
            $item['type'] = $type;
            return $item;
        })->toArray();

        return $valueList;
    }
}
