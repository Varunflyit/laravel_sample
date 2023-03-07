<?php

namespace App\Services;

use Ecommify\Core\Models\SourceInstance;
use Illuminate\Support\Arr;

class SourceInstanceService
{

    public function __construct()
    {

    }

    /**
     * Get Source Instance data from Source Instance ID
     *
     * @param string $sourceId
     * @return array
     */
    public static function getSourceInstanceDataById($sourceId)
    {
        $data = SourceInstance::query()
            ->where('source_instance_id',$sourceId)
            ->first();

        return $data;
    }

     /**
     * Get Source Instance data from Source Instance ID
     *
     * @param string $sourceId
     * @param string $companyId
     * @return array
     */
    public static function getSourceInstanceDataByIdAndCompanyId($sourceId , $companyId)
    {
        $data = SourceInstance::query()
            ->where('source_instance_id',$sourceId)
            ->where('company_id',$companyId)
            ->first();

        return $data;
    }


     /**
     * Get Source Instance data from Source Instance ID
     *
     * @param string $sourceId
     * @return array
     */
    public static function getSourceChannelNameBySourceInstanceID($sourceInstanceId)
    {
        $sourceInstanceData = SourceInstanceService::getSourceInstanceDataById($sourceInstanceId);

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

        $credential_array['source_instance_id'] = $sourceInstanceId;


        $credential_array['sourceOrChannelName'] = $sourceChannelName;

        return [
            'status' => true,
            'data' => $credential_array
        ];
    }

}
