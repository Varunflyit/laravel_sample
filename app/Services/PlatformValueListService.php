<?php

namespace App\Services;

use Ecommify\Core\Models\PlatformValueList;
use Illuminate\Support\Arr;

class PlatformValueListService
{

    public function __construct()
    {

    }

    /**
     * Insert New valueList from Sourece to Database
     *
     * @param array $valueList
     * @return array
     */
    public static function InsertValues($valueList)
    { 
        PlatformValueList::insert($valueList);
        return $valueList;
    }
    /**
     * Get Source Instance valueList from Source integration Instance ID
     *
     * @param string $platform
     * @param string $type
     * @return array
     */
    public static function getValues($type,$platform)
    {

        $valueList = PlatformValueList::select('code','label')->where('platform',$platform)->where('type',$type)->get()->all();

        return $valueList;
    }
    /**
     * Delete Source Instance valueList from Source integration Instance ID
     *
     * @param string $platform
     * @param string $type
     * @return boolan
     */
    public static function deleteValuesAll($type,$platform)
    {
        return PlatformValueList::where('platform',$platform)->where('type',$type)->delete();
    }

}
