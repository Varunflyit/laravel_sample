<?php

namespace App\Services;

use Ecommify\Core\Models\Source;
use Illuminate\Support\Arr;

class SourceService
{

    public function __construct()
    {

    }

    /**
     * Get Source data from Source ID
     *
     * @param array $options
     * @return array
     */
    public static function getSourceDataById($sourceId)
    {
        $data = Source::query()
            ->where('source_id',$sourceId)
            ->first();

        return $data;
    }

}
