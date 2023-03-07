<?php

namespace Ecommify\PLATFORM_NAMESPACE;

use Ecommify\Platform\BasePlatform;

class Platform extends BasePlatform
{
    /**
     * Platform name
     *
     * @var string
     */
    public $name = 'Platform Name';

    /**
     * Platform identifier
     *
     * @var string
     */
    public $identifier = 'platform_identifier';

    /**
     * Get orders data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getOrders(array $options = []): array
    {
        return [];
    }

    /**
     * Get single order data from the platform
     *
     * @param string $orderId
     * @return array
     */
    public function getOrder(string $orderId): array
    {
        return [];
    }

    /**
     * Add order data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addOrder(array $payload = []): array
    {
        return [];
    }

    /**
     * Get customers data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getCustomers(array $options = []): array
    {
        return [];
    }

    /**
     * Add customer data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addCustomer(array $payload = []): array
    {
        return [];
    }

    /**
     * Get product data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getProducts(array $options = []): array
    {
        return [];
    }

    /**
     * Add product data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addProduct(array $payload = []): array
    {
        return [];
    }

    /**
     * Get payments data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getPayments(array $options): array
    {
        return [];
    }

    /**
     * Add payment data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addPayment(array $payload = []): array
    {
        return [];
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
        return [];
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
     * Get order key
     * 
     * Some of platform show the orders data wrapped in a key.
     * e.g Maropost
     * "Order": [
     *      {"data"}
     * ]
     *
     * This method is used to get the platform order identifier.
     * @see \Ecommify\Integration\Services\BaseService::syncOrders()
     * 
     * @return string|null
     */
    public function getOrderKey(): ?string
    {
        return null;
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
        return '';
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
        return '';
    }

    /**
     * Get order status field.
     *
     * @return string
     */
    public function getOrderStatusField(): string
    {
        return '';
    }

    /**
     * Get grand total field.
     *
     * @return string
     */
    public function getGrandTotalField(): string
    {
        return '';
    }

    /**
     * Test connection to platform.
     *
     * @return boolean
     */
    public function testConnection(): bool
    {
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
        return [];
    }

    /**
     * Get Categort data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getCategories(array $options = []): array
    {
        return [];
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
        return [];
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
        return [];
    }
}
