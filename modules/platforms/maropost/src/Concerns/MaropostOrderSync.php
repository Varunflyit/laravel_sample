<?php

namespace Ecommify\Maropost\Concerns;

use Ecommify\Integration\Mappings\SyncOptions\Customer;
use Ecommify\Integration\SyncLifecycle;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

trait MaropostOrderSync
{
    /**
     * Check if order already synced to source platform
     *
     * @param string $channelOrderId
     * @return array
     */
    protected function isOrderAlreadySynced($channelOrderId): array
    {
        $payload = [
            'Filter' => [
                'OrderStatus' => null, // Pick any status
                'ExternalOrderReference' => $channelOrderId,
                'OutputSelector' => ['OrderID']
            ]
        ];

        $order = $this->sourcePlatform->getOrders($payload);

        $syncedOrder = Arr::first(
            Arr::get($order, $this->sourcePlatform->getOrderKey(), [])
        );

        return [
            'payload' => $payload,
            'response' => $order,
            'order_id' => Arr::get($syncedOrder, $this->sourcePlatform->getOrderIdField())
        ];
    }

    /**
     * callback after order mapped.
     *
     * @param array $order
     * @return array
     */
    protected function afterOrderMapped(array $order)
    {
        // TODO: Find a way to set customer order username on platform, 
        // so we don't need to add this method for each integration
        // consider adding getCustomerField() on platform?
        if ($this->getCustomerOption() == Customer::CREATE_UNIQUE_CUSTOMER) {
            $order['Username'] = $this->getWorkflowMetadata(SyncLifecycle::SYNCING_CUSTOMER . '.username');
        }

        return $order;
    }

    /**
     * Create unique customer
     *
     * @param array $payload
     * @return array
     */
    protected function createUniqueCustomer(array $payload): array
    {
        $response = [];
        if ($email = Arr::get($payload, 'EmailAddress')) {
            $response = $this->sourcePlatform->getCustomers([
                'Filter' => [
                    'Email' => $email
                ]
            ]);
        }

        if (empty(Arr::get($response, 'Customer', []))) {
            $response = $this->sourcePlatform->addCustomer($payload);
        }

        $data = Arr::get($response, 'Customer', []);
        if (count($data) != count($data, COUNT_RECURSIVE)) {
            $data = Arr::first($data);
        }

        return [
            'payload' => $payload,
            'response' => $response,
            'username' => Arr::get($data, 'Username')
        ];
    }

    /**
     * Get products from source platform based on channel order.
     *
     * Response must be in this format:
     *
     * ```php
     * [
     *      'payload' => [],
     *      'response' => [],
     *      'missing_skus' => []
     * ]
     * ```
     *
     * @param array $channelOrder
     * @return array
     */
    protected function getMissingProducts(array $channelOrder): array
    {
        $products = $this->getChannelOrderSkus($channelOrder);

        $payload = [
            'Filter' => [
                'SKU' => $products,
                'OutputSelector' => ['SKU']
            ]
        ];

        $sourceProductResponses = $this->sourcePlatform->getProducts($payload);

        $sourceProducts = Arr::get($sourceProductResponses, 'Item', []);

        // List of missing products in source compared to channel
        $missingProducts = array_diff($products, Arr::pluck($sourceProducts, 'SKU'));

        return [
            'payload' => $payload,
            'response' => $sourceProductResponses,
            'missing_skus' => $missingProducts,
        ];
    }

    /**
     * Add missing products from channel order
     *
     * @param array $missingProducts
     * @return array
     */
    protected function addMissingProduct(array $missingProducts): array
    {
        if (!empty($missingProducts)) {
            $payload = array_map(function ($product) {
                return [
                    'SKU' => $product
                ];
            }, $missingProducts);

            $response = $this->sourcePlatform->addProduct($payload);
        }

        return [
            'payload' => $payload ?? [],
            'response' => $response ?? []
        ];
    }

    /**
     * Get error message from Maropost API response.
     *
     * @param array $response
     * @param string|null $default
     * @return string|null
     */
    private function getErrorMessage(array $response, $default = null)
    {
        $ack = Arr::get($response, 'Ack');

        return Arr::get($response, "Messages.{$ack}.Message", $default);
    }

    /**
     * Add order payment
     *
     * @param array $payload
     * @param array $order
     * @return array
     */
    protected function addPayment(array $payload, array $order): array
    {
        $orderId = Arr::get($order, 'Order.OrderID');

        // Get order total
        $orders = $this->sourcePlatform->getOrders([
            'Filter' => [
                'OrderStatus' => null, // Pick any status
                'OrderID' => $orderId,
                'OutputSelector' => ['OrderID', 'GrandTotal', 'DateInvoiced']
            ]
        ]);

        $platformOrder = Arr::first(
            Arr::get($orders, 'Order')
        );

        $paymentPayload = array_merge([
            'OrderID' => $platformOrder['OrderID'],
            'AmountPaid' => $platformOrder['GrandTotal'],
            'DatePaid' => $platformOrder['DateInvoiced']
        ], $payload);

        $response = $this->sourcePlatform->addPayment($paymentPayload);

        $errorMessage = null;
        if (in_array(Arr::get($response, 'Ack'), ['Warning', 'Error'])) {
            $errorMessage = $this->getErrorMessage($response, trans("integration::messages.errors.payment.unable_to_sync_payment"));
        }

        return [
            'payload' => $paymentPayload,
            'response' => $response,
            'payment_id' => Arr::get($response, 'Payment.PaymentID'),
            'error_message' => $errorMessage,
        ];
    }

    /**
     * Format order response
     *
     * @param array $response
     * @return array
     */
    protected function formatOrderResponse(array $response)
    {
        $orderId = Arr::get($response, 'Order.OrderID');

        // Get order total
        $response = $this->sourcePlatform->getOrders([
            'Filter' => [
                'OrderStatus' => null, // Pick any status
                'OrderID' => $orderId,
                'OutputSelector' => Config::get('maropost.output_selector.order')
            ]
        ]);

        $response['Order'] = Arr::first($response['Order']);

        return $response;
    }
}
