<?php

namespace Ecommify\Maropost;

use Ecommify\Maropost\OAuth\OAuth;
use Ecommify\Platform\BasePlatform;
use Ecommify\Platform\Contracts\SourcePlatform;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Maropost extends BasePlatform implements SourcePlatform
{
    /**
     * Platform name
     *
     * @var string
     */
    public $name = 'Maropost';

    /**
     * Platform identifier
     *
     * @var string
     */
    public $identifier = 'maropost';

    /**
     * Store maropost API response
     *
     * @var array
     */
    public $response;

    /**
     * Maropost http instance
     *
     * @return PendingRequest
     */
    private function http($version = 'v1')
    {
        $oAuthOptions = array_merge(
            [
                'client_id' => Config::get('services.maropost.client_id'),
                'client_secret' => Config::get('services.maropost.client_secret')
            ],
            Arr::only($this->getAttributes(), ['refresh_token'])
        );

        $oAuth = (new OAuth($oAuthOptions))->refreshAccessToken();

        $baseUrl = sprintf(
            Config::get("maropost.api.{$version}_base_url"),
            $this->attribute('api_id')
        );

        return Http::acceptJson()
            ->withToken($oAuth['access_token'])
            ->baseUrl($baseUrl);
    }

    /**
     * Send V1 API request to maropost
     *
     * @param string $action
     * @param array $options
     * @return array
     */
    private function sendRequest(string $action = null, array $options = [])
    {
        $this->response = $this->http()
            ->withHeaders([
                'NETOAPI_ACTION' => $action
            ])
            ->post('/', $options)
            ->throw()
            ->json();

        // Maropost flag error on response instead of HTTP code.
        if (Arr::get($this->response, 'Ack') == 'Error') {
            $error = Arr::get($this->response, 'Messages.Error.Message');
            if ($error === null) {
                $errors = Arr::pluck(
                    Arr::get($this->response, 'Messages.Error', []),
                    'Message'
                );

                $error = implode("<br/>", $errors);
            }

            throw new Exception(
                Str::limit($error, 250)
            );
        }

        return $this->response;
    }

    /**
     * Send V2 API request to maropost
     *
     * @param string $action
     * @param array $options
     * @return array
     */
    private function sendV2Request(string $action, array $options = [])
    {
        $method = strtolower(Arr::get($options, 'method', 'get'));

        return $this->http('v2')
            ->{$method}($action, Arr::except($options, ['method']))
            ->throw()
            ->json();
    }

    /**
     * Get orders data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getOrders(array $options = []): array
    {
        try {
            return $this->sendRequest(
                'GetOrder',
                Arr::merge(
                    [
                        'Filter' => [
                            'OrderStatus' => 'New',
                            'OutputSelector' => Config::get('maropost.output_selector.order')
                        ]
                    ],
                    $options
                )
            );
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $this->response);
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
        $payload = [
            'Filter' => [
                'OrderID' => $orderId,
                'OrderStatus' => null,
                'OutputSelector' => Config::get('maropost.output_selector.order')
            ]
        ];

        return $this->getOrders($payload);
    }

    /**
     * Add order data to Maropost
     *
     * @param array $payload
     * @return array
     */
    public function addOrder(array $payload = []): array
    {
        try {
            return $this->sendRequest('AddOrder', [
                'Order' => $payload
            ]);
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $this->response);
        }
    }

    /**
     * Get customers data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getCustomers(array $options = []): array
    {
        try {
            return $this->sendRequest(
                'GetCustomer',
                Arr::mergeRecursive(
                    [
                        'Filter' => [
                            'Active' => true,
                            'OutputSelector' => Config::get('maropost.output_selector.customer')
                        ]
                    ],
                    $options
                )
            );
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $this->response);
        }
    }

    /**
     * Add customer data to Maropost
     *
     * @param array $payload
     * @return array
     */
    public function addCustomer(array $payload = []): array
    {
        try {
            return $this->sendRequest('AddCustomer', [
                'Customer' => $payload
            ]);
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $this->response);
        }
    }

    /**
     * Get payments data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getPayments(array $options): array
    {
        try {
            return $this->sendRequest('GetPayment', $options);
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $this->response);
        }
    }

    /**
     * Add payment data to Maropost
     *
     * @param array $payload
     * @return array
     */
    public function addPayment(array $payload = []): array
    {
        try {
            return $this->sendRequest('AddPayment', [
                'Payment' => $payload
            ]);
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $this->response);
        }
    }

    /**
     * Get product data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getProducts(array $options = []): array
    {
        try {
            return $this->sendRequest(
                'GetItem',
                Arr::merge(
                    [
                        'Filter' => [
                            'IsActive' => false, // Test
                            'OutputSelector' => Config::get('maropost.output_selector.product')
                        ]
                    ],
                    $options
                )
            );
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $this->response);
        }
    }

    /**
     * Add product data to Maropost
     *
     * @param array $payload
     * @return array
     */
    public function addProduct(array $payload = []): array
    {
        try {
            return $this->sendRequest(
                'AddItem',
                ['Item' => $payload]
            );
        } catch (Exception $e) {
            return $this->handleApiRequestException($e, $this->response);
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
        return 'Order';
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
        return 'OrderID';
    }

    /**
     * Get order line key
     *
     * @return string
     */
    public function getOrderLineKey(): string
    {
        return 'OrderLine';
    }

    /**
     * Get order status field.
     *
     * @return string
     */
    public function getOrderStatusField(): string
    {
        return 'OrderStatus';
    }

    /**
     * Get grand total field.
     *
     * @return string
     */
    public function getGrandTotalField(): string
    {
        return 'GrandTotal';
    }

    /*
     * Get Shipping Service data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getShippingService(array $options = []): array
    {
        try {
            $response = $this->sendV2Request('/shipping-services', $options);

            return Arr::get($response, 'result');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Price Group data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getPriceGroup(array $options = []): array
    {
        try {
            $response = $this->sendV2Request('/customer-groups', $options);

            return Arr::get($response, 'result');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Shipping Method data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getShippingMethod(array $options = []): array
    {
        try {
            $response = $this->sendRequest('GetShippingMethods', $options);

            return Arr::get($response, 'ShippingMethods.ShippingMethod');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Payment Method data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getPaymentMethod(array $options = []): array
    {
        try {
            $response = $this->sendRequest('GetPaymentMethods', $options);

            return Arr::get($response, 'PaymentMethods.PaymentMethod');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Warehouse data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getWarehouse(array $options = []): array
    {
        try {
            $response = $this->sendRequest('GetWarehouse', $options);

            return Arr::get($response, 'Warehouse');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Custom Field data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getCustomField(array $options = []): array
    {
        try {
            $response = $this->sendV2Request('/product-custom-fields', $options);

            return Arr::get($response, 'result');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Content Type data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getContentType(array $options = []): array
    {
        try {
            $response = $this->sendV2Request('/content-types', $options);

            return Arr::get($response, 'result');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Categort data from Maropost
     *
     * @param array $options
     * @return array
     */
    public function getCategories(array $options = []): array
    {
        try {
            $response = $this->sendRequest(
                'GetContent',
                Arr::merge(
                    [
                        'Filter' => [
                            'ContentType' => "category",
                            'OutputSelector' => Config::get('maropost.output_selector.category')
                        ]
                    ],
                    $options
                )
            );

            return Arr::get($response, 'Content');
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
            $response = $this->getContentType();
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
            case "PAYMENT_METHODS":
                $data = $this->getCodeLabelValueType($this->getPaymentMethod());
                break;
            case "SHIPPING_METHODS":
                $data = $this->getCodeLabelValueType($this->getShippingMethod());
                break;
            case "SHIPPING_SERVICES":
                $data = $this->getCodeLabelValueType($this->getShippingService());
                break;
            case "CUSTOM_FIELDS":
                $data = array_map(function ($item) {
                    return [
                        'code' => $item['netoapi_name'],
                        'label' => $item['name']
                    ];
                }, $this->getCustomField());
                break;
            case "PRICE_GROUPS":
                $data = $this->getCodeLabelValueType($this->getPriceGroup());
                break;
            case "CONTENT_TYPES":
                $data = $this->getCodeLabelValueType($this->getContentType());
                break;
            default:
                $dataList = $this->getValueListCode($code);
                if (isset($dataList['values'])) {
                    $data = $dataList['values'];
                }
                break;
        }

        return $data;
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
            $categoryItem = array();
            $categoryItem['source_instance_id'] = $instanceId;
            $categoryItem['content_id'] = $item['ContentID'];
            $categoryItem['content_name'] = $item['ContentName'];
            $categoryItem['parent_content_id'] = $item['ParentContentID'];
            return $categoryItem;
        })->toArray();

        return $category;
    }

    /**
     * Get Formated Value List for database
     *
     * @param array $valueList
     * @param string $type
     * @param string $instance_id
     * @return array
     */
    public function valueListObjectFormat(array $valueList, string $type, string $instance_id): array
    {
        $valueList = collect($valueList)->map(function ($item) use ($type, $instance_id) {
            $item['source_instance_id'] = $instance_id;
            $item['type'] = $type;
            return $item;
        })->toArray();

        return $valueList;
    }
}
