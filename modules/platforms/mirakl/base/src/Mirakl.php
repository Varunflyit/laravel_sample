<?php

namespace Ecommify\Mirakl;

use Ecommify\Platform\BasePlatform;
use Exception;

abstract class Mirakl extends BasePlatform
{
    /**
     * Platform name
     *
     * @var string
     */
    public $name = 'Bunnings Marketlink';

    /**
     * Platform identifier
     *
     * @var string
     */
    public $identifier = 'mirakl';

    /**
     * Platform marketplace
     *
     * @var bool
     */
    public $marketplace = true;

    /**
     * Mirakl http instance
     *
     * @return PendingRequest
     */
    abstract protected function http();

    /**
     * Get orders data from Mirakl
     *
     * @param array $options
     * @return array
     */
    public function getOrders(array $options = []): array
    {
        try {
            $response = $this->http()
                ->get('/orders', $options)
                ->throw()
                ->json();

            return $response;
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $response);
        }
    }

    /**
     * Get single order data from Mirakl
     *
     * @param string $orderId
     * @return array
     */
    public function getOrder(string $orderId): array
    {
        return $this->getOrders(['order_ids' => $orderId]);
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
        try {
            $this->http()
                ->throw()
                ->put("/orders/{$orderId}/tracking", $payload);

            return [];
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Valid the shipment of the order which is in "SHIPPING" state
     * 
     * @param string $orderId
     * @return array
     */
    public function validateShipment(string $orderId): array
    {
        try {
            $this->http()
                ->throw()
                ->put("/orders/{$orderId}/ship");

            return [];
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get order key
     *
     * This method is used to get the platform order identifier.
     * @see \Ecommify\Integration\Services\BaseService::syncOrders()
     * 
     * @return string|null
     */
    public function getOrderKey(): ?string
    {
        return 'orders';
    }

    /**
     * Get order id field
     * 
     * This method is used to get the platform order identifier.
     * @see \Ecommify\Integration\Services\BaseService::syncOrders()
     *
     * @return string
     */
    public function getOrderIdField(): string
    {
        return 'order_id';
    }

    /**
     * Get order line key
     *
     * @return string
     */
    public function getOrderLineKey(): string
    {
        return 'order_lines';
    }

    /**
     * Get order status field.
     *
     * @return string
     */
    public function getOrderStatusField(): string
    {
        return 'order_state';
    }

    /**
     * Get grand total field.
     *
     * @return string
     */
    public function getGrandTotalField(): string
    {
        return 'total_price';
    }

    /*
     * Get Value List
     *
     * @return string
     */
    public function getValueList()
    {
        try {
            $response = $this->http()
                ->get("/values_lists")
                ->throw()
                ->json('values_lists');

            return $response;
        } catch (Exception $e) {
            $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Carriers List
     *
     * @return string
     */
    public function getCarriers()
    {
        try {
            $response = $this->http()
                ->get('/shipping/carriers')
                ->throw()
                ->json('carriers');

            return $response ?? [];
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Shipping method
     *
     * @return string
     */
    public function getShippingMethod(array $options = []): array
    {
        try {
            $response = $this->http()
                ->get('/shipping/types')
                ->throw()
                ->json('shipping_types');

            return $response ?? [];
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Categories
     *
     * @return string
     */
    public function getCategories(array $options = []): array
    {
        try {
            $response = $this->http()
                ->get('/hierarchies')
                ->throw()
                ->json('hierarchies');

            return $response ?? [];
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Check test connection
     *
     * @return boolean
     */
    public function testConnection(): bool
    {
        try {
            $response = $this->getCategories();
            if (is_array($response)) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
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
        switch ($code) {
            case 'CARRIERS':
                $data = $this->getCarriers();
                break;
            case 'SHIPPING_METHODS':
                $data = $this->getShippingMethod();
                break;
            case 'CATEGORIES':
                $data = $this->getCategories();
                break;
        }

        return $this->getCodeLabelValueType($data);
    }

    /**
     * Get Code label array from Platform api response
     *
     * @param array $options
     * @return array
     */
    public function getCodeLabelValueType(array $data = []): array
    {
        $responseArray = array();
        $i = 0;
        foreach ($data as $dlist) {
            $responseArray[$i]['code'] = $dlist['code'];
            $responseArray[$i]['label'] = $dlist['label'];
            $i++;
        }

        return $responseArray;
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
