<?php

namespace App\Services;

use Ecommify\Core\Models\IntegrationInstance;

class IntegrationInstanceService
{
    /**
     * Get Integration Instance data from Integration Instance ID
     *
     * @param array $options
     * @return array
     */
    public static function getIntegrationInstanceById($integrationInstanceId)
    {
        $data = IntegrationInstance::query()
            ->where('integration_instance_id', $integrationInstanceId)
            ->first();

        return $data;
    }

    /**
     * Get Integration Instance data from Integration Instance ID and company Id
     *
     * @param array $options
     * @return array
     */
    public static function getIntegrationInstanceByIdAndCompanyId($integrationInstanceId,$companyId)
    {
        $data = IntegrationInstance::query()
            ->where('integration_instance_id', $integrationInstanceId)
            ->where('company_id', $companyId)
            ->first();

        return $data;
    }

    public static function getSourceChannelNameByIntegrationInstanceID($integrationInstanceId, $type, $userId)
    {
        // get integration instance data by integration instance id.
        $integrationInstanceData = IntegrationInstanceService::getIntegrationInstanceById($integrationInstanceId);

        // check if data found for the integration instance id
        if (empty($integrationInstanceData)) {
            return [
                'status' => false,
                'message' => trans('app.integration_instance_not_found')
            ];
        }

        // check if user has access of given instance
        $hasInstanceAccess = UserService::hasInstanceAccess($userId, $integrationInstanceData);
        if (!$hasInstanceAccess['success']) {
            return array_merge($hasInstanceAccess, [
                'status' => false
            ]);
        }

        $sourceChannelName = '';

        $credential_array = array();

        if ($type == 'source') {
            // get source integration data by source instance id.
            $sourceInstanceData = SourceInstanceService::getSourceInstanceDataById($integrationInstanceData->source_instance_id);

            if (empty($sourceInstanceData)) {
                return [
                    'status' => false,
                    'message' => trans('app.source_instance_not_found')
                ];
            }

            // get source data by source id.
            $sourceData = SourceService::getSourceDataById($sourceInstanceData->source_id);

            if (empty($sourceData)) {
                return [
                    'status' => false,
                    'message' => trans('app.source_not_found')
                ];
            }

            $sourceChannelName = $sourceData->source_platform;

            // get source integration attribute data by source instance id.
            if (!empty($sourceInstanceData)) {
                $sourceInstanceAttributeData = SourceInstanceAttributeService::getSourceInstanceDataBySourceInstanceId($sourceInstanceData->source_instance_id);

                foreach ($sourceInstanceAttributeData as $silist) {
                    $credential_array[$silist->attribute_key] = $silist->attribute_value;
                }
            }

            $credential_array['source_instance_id'] = $integrationInstanceData->source_instance_id;
        } else if ($type == 'channel') {
            // get integration data by instance id.

            $channelData = IntegrationService::getIntegrationDataById($integrationInstanceData->integration_id);

            if (empty($channelData))
                return [
                    'status' => false,
                    'message' => trans('app.source_channel_not_found')
                ];

            $sourceChannelName = $channelData->channel_platform;

            // get integration instance attribute data by integration instance id
            $integrationInstanceAttributeData = IntegrationInstanceAttributeService::getIntegrationInstanceAttributesById($integrationInstanceId);

            foreach ($integrationInstanceAttributeData as $cilist)
                $credential_array[$cilist->attribute_key] = $cilist->attribute_value;
        }

        $credential_array['sourceOrChannelName'] = $sourceChannelName;

        return [
            'status' => true,
            'data' => $credential_array
        ];
    }
}
