<?php

namespace App\Services;

use Ecommify\Core\Models\SourceInstanceAttribute;
use Illuminate\Support\Arr;

class SourceInstanceAttributeService
{

    public function __construct()
    {

    }

    /**
     * Get Source Integration Attribute data from Source Instance ID
     *
     * @param array $options
     * @return array
     */
    public static function getSourceInstanceDataBySourceInstanceId($sourceInstanceId)
    {   
        $data = SourceInstanceAttribute::query()
            ->where('source_instance_id',$sourceInstanceId)
            ->get();

        return $data;
    }

}
