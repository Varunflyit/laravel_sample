<?php

namespace App\Services;

use Ecommify\Core\Models\IntegrationInstancesCategories;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class IntegrationInstancesCategoriesService
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
        IntegrationInstancesCategories::insert($category);

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
        $search = $request->get('search', '');
        if(empty($search)){
            $category = IntegrationInstancesCategories::select("content_id", "content_name", "parent_content_id")
                ->withCount('children')
                ->where('parent_content_id', $request->get('parent', '0'))
                ->where('integration_instance_id', $integrationInstanceId)
                ->paginate($request->get('size', 10));

            return $category;
        }
        $category = IntegrationInstancesCategories::select("content_id", "content_name", "parent_content_id")
                ->with('parents')
                ->where('content_name','LIKE',"%{$search}%")
                ->where('integration_instance_id', $integrationInstanceId)
                ->paginate($request->get('size', 10));

        return $category;

    }
    /**
     * Delete Source Instance Caterory from Source integration Instance ID
     *
     * @param string $integrationInstanceId
     * @return boolan
     */
    public static function deleteCategoryAll($integrationInstanceId)
    {
        return IntegrationInstancesCategories::where('integration_instance_id', $integrationInstanceId)->delete();
    }
}
