<?php

namespace App\Services;

use Ecommify\Core\Models\Integration;
use Ecommify\Platform\Facades\Platform;
use Illuminate\Support\Arr;

class IntegrationService
{

    public function __construct()
    {
    }

    /**
     * Get Integration data from Integration ID
     *
     * @param array $options
     * @return array
     */
    public static function getIntegrationDataById($integrationId)
    {
        $data = Integration::query()
            ->where('integration_id', $integrationId)
            ->first();

        return $data;
    }

    public static function IntegrationValueName($integrationId)
    {
        $channelValue = array();
        $sourceValue = array();
        // get Instance Detail
        $integrationData = IntegrationService::getIntegrationDataById($integrationId);
        // check the response and give proper error message
        if (empty($integrationData)) {
            return [
                'status' => false,
                'error' => [
                    'message' => trans('app.integration_not_found'),
                    'code' => 'integration_not_found'
                ]
            ];
        }
        $data = array();
        $ChannelName = $integrationData->channel_platform;
        $platformChannel = Platform::driver($ChannelName);
        //get channel value list
        $channelValue = $platformChannel->getValueListConfigArray();
        //Update collection for only code.
        $channelValue = collect($channelValue)->map(function ($item) {
            unset($item['label']);
            unset($item['dynamic']);
            unset($item['values']);
            return $item['code'];
        })->toArray();
        $sourceData = SourceService::getSourceDataById($integrationData->source_id);
        if (!empty($sourceData)) {
            $sourceChannelName = $sourceData->source_platform;
            $platformSource = Platform::driver($sourceChannelName);
            //get source value list
            $sourceValue = $platformSource->getValueListConfigArray();
            //Update collection for only code.
            $sourceValue = collect($sourceValue)->map(function ($item) {
                unset($item['label']);
                unset($item['dynamic']);
                unset($item['values']);
                return $item['code'];
            })->toArray();
        }
        return [
            'status' => true,
            'message' => trans('app.successfully_list_code'),
            'result' => [
                'source' => $sourceValue,
                "channel" => $channelValue
            ]
        ];
    }
    /**
     * Get Integration Source detail from Integration ID
     *
     * @param array $options
     * @return array
     */
    public static function getSourceChannelNameByIntegrationID($integrationId, $origin)
    {
        $channelValue = array();
        $sourceValue = array();
        // get Instance Detail
        $integrationData = IntegrationService::getIntegrationDataById($integrationId);
        // check the response and give proper error message
        if (empty($integrationData)) {
            return [
                'status' => false,
                'error' => [
                    'message' => trans('app.integration_not_found'),
                    'code' => 'integration_not_found'
                ]
            ];
        }
        $data = array();
        $ChannelName = $integrationData->channel_platform;
        $platformChannel = Platform::driver($ChannelName);
        //get channel value list
        $channelValue = $platformChannel->getValueListConfigArray();
        //Update collection for only code.
        $channelValue = collect($channelValue)->map(function ($item) {
            unset($item['label']);
            unset($item['dynamic']);
            unset($item['values']);
            return $item['code'];
        })->toArray();
        $sourceData = SourceService::getSourceDataById($integrationData->source_id);
        if (!empty($sourceData)) {
            $sourceChannelName = $sourceData->source_platform;
            $platformSource = Platform::driver($sourceChannelName);
            //get source value list
            $sourceValue = $platformSource->getValueListConfigArray();
            //Update collection for only code.
            $sourceValue = collect($sourceValue)->map(function ($item) {
                unset($item['label']);
                unset($item['dynamic']);
                unset($item['values']);
                return $item['code'];
            })->toArray();
        }
        return [
            'status' => true,
            'message' => trans('app.successfully_list_code'),
            'result' => [
                'source' => $sourceValue,
                "channel" => $channelValue
            ]
        ];
    }
}
