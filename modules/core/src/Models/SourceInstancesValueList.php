<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Model;

class SourceInstancesValueList extends Model
{
   
    public function SourceInstance()
    {
        return $this->belongsTo(SourceInstance::class, 'source_instance_id', 'source_instance_id');
    }
}