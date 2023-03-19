<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationInstancesValueList extends Model
{
   
    public function IntegrationInstances()
    {
        return $this->belongsTo(IntegrationInstances::class, 'integration_instance_id', 'integration_instance_id');
    }
}