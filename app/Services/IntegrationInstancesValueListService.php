<?php

namespace App\Services;

use Ecommify\Core\Models\IntegrationInstancesValueList;
use Illuminate\Support\Arr;

class IntegrationInstancesValueListService
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
        IntegrationInstancesValueList::insert($valueList);
        return $valueList;
    }
    /**
     * Get Source Instance valueList from Source integration Instance ID
     *
     * @param string $integrationInstanceId
     * @param string $type
     * @return array
     */
    public static function getValues($type,$integrationInstanceId)
    {

        $valueList = IntegrationInstancesValueList::select('code','label')->where('integration_instance_id',$integrationInstanceId)->where('type',$type)->get()->all();

        return $valueList;
    }
    /**
     * Delete Source Instance valueList from Source integration Instance ID
     *
     * @param string $integrationInstanceId
     * @param string $type
     * @return boolan
     */
    public static function deleteValuesAll($type,$integrationInstanceId)
    {
        return IntegrationInstancesValueList::where('integration_instance_id',$integrationInstanceId)->where('type',$type)->delete();
    }


}