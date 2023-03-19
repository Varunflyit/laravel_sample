<?php

namespace App\Services;

use Ecommify\Core\Models\PlatformCategories;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class PlatformCategoriesService
{

    public function __construct()
    {
    }

    /**
     * Insert New category from Sourece to Database
     *
     * @param array $category
     * @return array
     */
    public static function InsertCategory($category)
    {
        PlatformCategories::insert($category);

        return $category;
    }
    /**
     * Get Source Instance Caterory from Source integration Instance ID
     *
     * @param string $platform
     * @return array
     */
    public static function getCategory($platform, Request $request)
    {
        $search = $request->get('search', '');
        if(empty($search)){
            $category = PlatformCategories::select("content_id", "content_name", "parent_content_id")
                ->withCount('children')
                ->with('parents')
                ->where('parent_content_id', $request->get('parent', '0'))
                ->where('platform', $platform)
                ->paginate($request->get('size', 10));

            return $category;
        }

        $category = PlatformCategories::select("content_id", "content_name", "parent_content_id")
                ->with('parents')
                ->withCount('children')
                ->where('content_name','LIKE',"%{$search}%")
                ->where('platform', $platform)
                ->paginate($request->get('size', 10));

        return $category;


    }
    /**
     * Delete Source Instance Caterory from Source integration Instance ID
     *
     * @param string $platform
     * @return boolan
     */
    public static function deleteCategoryAll($platform)
    {
        return PlatformCategories::where('platform', $platform)->delete();
    }
}
