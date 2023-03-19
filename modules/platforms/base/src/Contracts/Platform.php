<?php

namespace Ecommify\Platform\Contracts;

interface Platform
{
    /**
     * Get orders data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getOrders(array $options = []): array;

    /**
     * Get single order data from the platform
     *
     * @param string $orderId
     * @return array
     */
    public function getOrder(string $orderId): array;

    /**
     * Get shipping method data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function getShippingMethod(array $options = []): array;

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
    public function getOrderKey(): ?string;

    /**
     * Get order id field
     * 
     * This method is used to get the platform order identifier.
     * @see \Ecommify\Integration\Services\BaseService::syncOrders()
     *
     * @return string
     */
    public function getOrderIdField(): string;

    /**
     * Get order line key
     *
     * This method is used to map order line.
     * @see \Ecommify\Integration\Mappings\Orders\Pipes\MapCustomAttribute::handle()
     * @return string
     */
    public function getOrderLineKey(): string;

    /**
     * Get order status field.
     *
     * @return string
     */
    public function getOrderStatusField(): string;

    /**
     * Get grand total field.
     *
     * @return string
     */
    public function getGrandTotalField(): string;

    /**
     * Test connection to platform.
     *
     * @return boolean
     */
    public function testConnection(): bool;

    /**
     * Get value for code from platform
     *
     * @param string $code
     * @return array
     */
    public function getValues(string $code): array;

    /**
     * Get Categories data from Platform
     *
     * @param array $options
     * @return array
     */
    public function getCategories(array $options = []): array;

    /**
     * Get Formated Category for database
     *
     * @param array $category
     * @param string $instanceId
     * @return array
     */
    public function categoryObjectFormat(array $category, string $instanceId): array;

    /**
     * Get Value list for code from config array from Platform api response
     *
     * @param array $options
     * @return array
     */
    public function getValueListCode(string $code): array;

    /**
     * Get Value list config array from Platform api response
     *
     * @param array $options
     * @return array
     */
    public function getValueListConfigArray(array $data = []): array;

    /**
     * add Value list config object from Platform api response
     *
     * @param array $options
     * @return bool
     */
    public function addValueList(string $data = ''): bool;

    /**
     * Get Value list config object from Platform api response
     *
     * @param array $options
     * @return array
     */
    public function getValueListConfig(array $data = []): array;

    /**
     * Get Formated Value List for database
     *
     * @param array $valueList
     * @param string $type
     * @param string $instanceId
     * @return array
     */
    public function valueListObjectFormat(array $valueList, string $type, string $instanceId): array;
}
