<?php

namespace App\Services;

use Ecommify\Core\Models\SourceInstancesValueList;
use Illuminate\Support\Arr;

class SourceInstancesValueListService
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
        SourceInstancesValueList::insert($valueList);
        return $valueList;
    }
    /**
     * Get Source Instance valueList from Source integration Instance ID
     *
     * @param string $SourceInstanceId
     * @param string $type
     * @return array
     */
    public static function getValues($type,$SourceInstanceId)
    {

        $valueList = SourceInstancesValueList::select('code','label')->where('source_instance_id',$SourceInstanceId)->where('type',$type)->get()->all();

        return $valueList;
    }
    /**
     * Delete Source Instance valueList from Source integration Instance ID
     *
     * @param string $SourceInstanceId
     * @param string $type
     * @return boolan
     */
    public static function deleteValuesAll($type,$SourceInstanceId)
    {
        return SourceInstancesValueList::where('source_instance_id',$SourceInstanceId)->where('type',$type)->delete();
    }

}
