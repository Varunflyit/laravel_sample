<?php

namespace Ecommify\Platform;

use Ecommify\Platform\Contracts\Platform;
use Ecommify\Platform\Exceptions\ApiRequestException;
use Ecommify\Platform\PlatformService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Ecommify\Core\Models\Company;

abstract class BasePlatform implements Platform
{
    /**
     * Platform name
     *
     * @var string
     */
    public $name;

    /**
     * Platform identifier
     *
     * @var string
     */
    public $identifier;

    /**
     * Platform marketplace
     *
     * @var bool
     */
    public $marketplace = false;

    /**
     * Platform attributes
     * 
     * @var array
     */
    protected array $attributes = [];

    /**
     * Set attributes for platform
     *
     * @param array $attributes
     * @return void
     */
    public function attributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get platform attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get attribute value
     *
     * @param string $key
     * @return mixed
     */
    public function attribute(string $key)
    {
        return Arr::get($this->attributes, $key);
    }

    /**
     * Write log
     */
    public function writeLog($message)
    {
        Log::info($message);
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
     * Handle exception
     *
     * @param Exception $e
     * @param mixed $response
     */
    protected function handleApiRequestException(Exception $e, $response = [])
    {
        throw (new ApiRequestException($e->getMessage(), $e->getCode()))
            ->setResponse($response);
    }

    /**
     * Get Value list config object from Platform api response
     *
     * @param array $options
     * @return array
     */
    public function getValueListConfig(array $data = []): array
    {
        $platform = PlatformService::getPlatformDataByPlatform($this->identifier);

        if (!empty($platform)) {
            return $platform;
        }

        return [];
    }

    /**
     * add Value list config object from Platform api response
     *
     * @param array $options
     * @return bool
     */
    public function addValueList(string $data = ''): bool
    {
        return PlatformService::addUpdatePlatformDataByPlatform($this->identifier, $data);
    }

    /**
     * Get Value list config array from Platform api response
     *
     * @param array $options
     * @return array
     */
    public function getValueListConfigArray(array $data = []): array
    {
        $platform = $this->getValueListConfig();
        $value = [];

        if (!empty($platform)) {
            $value = json_decode($platform['value_lists'], true);
        }

        return $value;
    }

    /**
     * Get Value list for code from config array from Platform api response
     *
     * @param array $options
     * @return array
     */
    public function getValueListCode(string $code): array
    {
        $data = array_filter($this->getValueListConfigArray(), function ($list) use ($code) {
            return $list['code'] == $code;
        });

        if (!empty($data)) {
            return end($data);
        }

        return [];
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
            $responseArray[$i]['code'] = $dlist['id'];
            $responseArray[$i]['label'] = $dlist['name'];
            $i++;
        }

        return $responseArray;
    }

    public function productSyncPim(Company $company , array $option){

    }
}
