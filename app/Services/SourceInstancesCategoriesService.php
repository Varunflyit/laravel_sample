<?php

namespace App\Services;

use Ecommify\Core\Models\SourceInstancesCategories;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class SourceInstancesCategoriesService
{

    public function __construct()
    {
    }

    /**
     * Insert New category from Sourece to Database
     *
     * @param array $category
     * @param string $integrationInstanceId
     * @return array
     */
    public static function InsertCategory($category)
    {
        SourceInstancesCategories::insert($category);

        return $category;
    }
    /**
     * Get Source Instance Caterory from Source integration Instance ID
     *
     * @param string $integrationInstanceId
     * @return array
     */
    public static function getCategory($integrationInstanceId, Request $request)
    {
        $integrationInstanceData = IntegrationInstanceService::getIntegrationInstanceById($integrationInstanceId);

        $sourceInstanceData = SourceInstanceService::getSourceInstanceDataById($integrationInstanceData->source_instance_id);
        $search = $request->get('search', '');
        if(empty($search)){
            $category = SourceInstancesCategories::select("content_id", "content_name", "parent_content_id")
                ->withCount('children')
                ->where('parent_content_id', $request->get('parent', '0'))
                ->where('source_instance_id', $sourceInstanceData->source_instance_id)
                ->paginate($request->get('size', 10));

            return $category;
        }

        $category = SourceInstancesCategories::select("content_id", "content_name", "parent_content_id")
                ->with('parents')
                ->where('content_name','LIKE',"%{$search}%")
                ->where('source_instance_id', $sourceInstanceData->source_instance_id)
                ->paginate($request->get('size', 10));

        return $category;
    }
    /**
     * Delete Source Instance Caterory from Source integration Instance ID
     *
     * @param string $integrationInstanceId
     * @return boolan
     */
    public static function deleteCategoryAll($SourceInstanceId)
    {
        return SourceInstancesCategories::where('source_instance_id', $SourceInstanceId)->delete();
    }
}
