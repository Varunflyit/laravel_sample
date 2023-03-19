<?php

namespace Ecommify\Platform\Contracts;

interface SourcePlatform
{
    /**
     * Add order data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addOrder(array $payload = []): array;

    /**
     * Get product data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getProducts(array $options = []): array;

    /**
     * Add product data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addProduct(array $payload = []): array;

    /**
     * Get customers data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getCustomers(array $options = []): array;

    /**
     * Add customer data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addCustomer(array $payload = []): array;

    /**
     * Get payments data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getPayments(array $options): array;

    /**
     * Add payment data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addPayment(array $payload = []): array;
}
