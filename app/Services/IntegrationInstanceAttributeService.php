<?php

namespace App\Services;

use Ecommify\Core\Models\IntegrationInstanceAttribute;
use Illuminate\Support\Arr;

class IntegrationInstanceAttributeService
{

    public function __construct()
    {

    }

    /**
     * Get Integration Instance Attributes By Integration Instance ID
     *
     * @param array $options
     * @return array
     */
    public static function getIntegrationInstanceAttributesById($integrationInstanceId)
    {
        $data = IntegrationInstanceAttribute::query()
            ->where('integration_instance_id',$integrationInstanceId)
            ->get();

        return $data;
    }

}
