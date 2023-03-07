<?php

namespace Ecommify\Magento;

use Ecommify\Platform\BasePlatform;
use Ecommify\Platform\Contracts\SourcePlatform;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Exception;

class Magento extends BasePlatform implements SourcePlatform
{
    public $name = 'Magento';

    /**
     * Platform identifier
     *
     * @var string
     */
    public $identifier = 'magento';

    /**
     * Platform http instance
     *
     * @return PendingRequest
     */
    private function http($method, $requestUrl = '', $option = array())
    {
        if ($method == 'GET') {
            $optionString = Arr::query($option);
            if (empty($optionString)) {
                $optionString = 'search_criteria';
            }
            $requestUrl .= "?" . $optionString;
        }
        $auth = $this->authHeader($method, $requestUrl);

        $http = Http::acceptJson()
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Oauth ' . $auth,
            ])
            ->baseUrl('https://' . $this->attribute('store_url') . '/index.php/rest');

        $response = array();

        switch ($method) {
            case "GET":
                $response = $http->get(
                    $requestUrl
                )
                    ->json();
                break;
        }

        return $response;
    }

    /**
     * Platform create url string
     *
     * @return string
     */
    private function authHeader($method, $requestUrl = ''): string
    {

        $urlParts = parse_url('https://' . $this->attribute('store_url') . '/index.php/rest' . $requestUrl);

        $oauthParams = [
            'oauth_consumer_key' => $this->attribute('consumer_key'),
            'oauth_nonce' => base64_encode(random_bytes(32)),
            'oauth_signature_method' => 'HMAC-SHA256',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->attribute('access_token')
        ];

        $signingUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];

        $paramString = $this->createParamString($urlParts['query'] ?? '', $oauthParams);

        $baseString = strtoupper($method) . '&' . rawurlencode($signingUrl) . '&' . rawurlencode($paramString);

        $signatureKey = $this->attribute('consumer_secret') . '&' . $this->attribute('access_token_secret');

        $signature = base64_encode(hash_hmac('sha256', $baseString, $signatureKey, true));

        return $this->createOAuthHeader($oauthParams, $signature);
    }

    /**
     * Platform create url string
     *
     * @return string
     */
    private function createParamString(string $query, array $oauthParams): string
    {
        // Create the params string
        $params = array_merge([], $oauthParams);
        if (!empty($query)) {
            foreach (explode('&', $query) as $paramToValue) {
                $paramData = explode('=', $paramToValue);
                if (count($paramData) === 2) {
                    $params[rawurldecode($paramData[0])] = rawurldecode($paramData[1]);
                }
            }
        }
        ksort($params);
        $paramString = '';
        foreach ($params as $param => $value) {
            $paramString .= rawurlencode($param) . '=' . rawurlencode($value) . '&';
        }
        return rtrim($paramString, '&');
    }

    /**
     * Platform create url string
     *
     * @return string
     */
    private function createOAuthHeader(array $oauthParams, string $signature): string
    {
        // Create the OAuth header
        $oauthHeader = "";
        foreach ($oauthParams as $param => $value) {
            $oauthHeader .= "$param=\"$value\",";
        }
        return $oauthHeader . "oauth_signature=\"$signature\"";
    }
    /**
     * Get orders data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getOrders(array $options = []): array
    {
        try {
            $response = $this->http(
                'GET',
                '/V1/orders',
                [
                    "search_criteria" => Arr::merge(
                        [
                            "filter_groups" => [
                                [
                                    "filters" => [
                                        [
                                            "field" => "status",
                                            "value" => "processing",
                                            "condition_type" => "eq"
                                        ]
                                    ]
                                ]
                            ],
                            "page_size" => 1000,
                            "current_page" => 0
                        ],
                        $options
                    )
                ]
            );
            return Arr::get($response, 'items');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
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
    }

    /**
     * Get customers data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getCustomers(array $options = []): array
    {
    }

    /**
     * Add customer data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addCustomer(array $payload = []): array
    {
    }

    /**
     * Get product data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getProducts(array $options = []): array
    {
    }

    /**
     * Add product data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addProduct(array $payload = []): array
    {
    }

    /**
     * Get payments data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getPayments(array $options): array
    {
    }

    /**
     * Add payment data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function addPayment(array $payload = []): array
    {
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
    }

    /**
     * Get shipping method data to the platform
     *
     * @param array $payload
     * @return array
     */
    public function getShippingMethod(array $options = []): array
    {
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
    }

    /**
     * Get order status field.
     *
     * @return string
     */
    public function getOrderStatusField(): string
    {
    }

    /**
     * Get grand total field.
     *
     * @return string
     */
    public function getGrandTotalField(): string
    {
    }

    /**
     * Test connection to platform.
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
     * Get Arribute data from the platform
     *
     * @param array $options
     * @return array
     */
    public function getStores(array $options = []): array
    {
        try {
            $response = $this->http(
                'GET',
                '/V1/store/websites'
            );
            return $this->getCodeLabelValueByIndex($response);
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
            case "STORES":
                $data = $this->getStores();
                break;
            case "PRICE_GROUPS":
                $data = $this->getCustmerGroups();
                break;
            case "CUSTOM_FIELDS":
                $data = $this->getProductAttributes();
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
     * Get Code label array from Platform api response
     *
     * @param array $options
     * @return array
     */
    public function getCodeLabelValueByIndex(array $data = [],string $codeIndex = 'code' , string $labelIndex = 'name' ): array
    {
        $responseArray = array();
        $i = 0;
        foreach ($data as $dlist) {
            if(Arr::exists($dlist, $labelIndex)){
                $responseArray[$i]['code'] = $dlist[$codeIndex];
                $responseArray[$i]['label'] = $dlist[$labelIndex];
                $i++;
            }
        }

        return $responseArray;
    }
    /**
     * Get Categort data from platform
     *
     * @param array $options
     * @return array
     */
    public function getCategories(array $options = []): array
    {
        try {
            $response = $this->http(
                'GET',
                '/V1/categories/list',
                [
                    "search_criteria" => $options,
                ]
            );
            return Arr::get($response, 'items');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Custmer group data from platform
     *
     * @param array $options
     * @return array
     */
    public function getCustmerGroups(array $options = []): array
    {
        try {
            $response = $this->http(
                'GET',
                '/V1/customerGroups/search',
                [
                    "search_criteria" => $options,
                ]
            );
            return $this->getCodeLabelValueByIndex(Arr::get($response, 'items'),'id','code');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
    }

    /**
     * Get Product Attributes data from platform
     *
     * @param array $options
     * @return array
     */
    public function getProductAttributes(array $options = []): array
    {
         try {
            $response = $this->http(
                'GET',
                '/V1/products/attributes',
                [
                    "search_criteria" => Arr::merge(
                        [
                            "page_size" => 1000
                        ],
                        $options
                    ),
                ]
            );
            return $this->getCodeLabelValueByIndex(Arr::get($response, 'items'),'attribute_code','default_frontend_label');
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
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
        try {
            $category = collect($category)->map(function ($item) use ($instanceId) {
                $categoryItem = array();
                $categoryItem['source_instance_id'] = $instanceId;
                $categoryItem['content_id'] = $item['id'];
                $categoryItem['content_name'] = $item['name'];
                $categoryItem['parent_content_id'] = $item['parent_id'];
                return $categoryItem;
            })->toArray();

            return $category;
        } catch (Exception $e) {
            return $this->handleApiRequestException($e);
        }
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
