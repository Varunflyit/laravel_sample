<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SourceInstance extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'core';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'source_instances';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'source_instance_id';

    /**
     * The primary key data type for the model.
     *
     * @var string
     */
    protected $keyType = 'string';

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id', 'source_id');
    }

    public function instanceAttributes()
    {
        return $this->hasMany(SourceInstanceAttribute::class, 'source_instance_id', 'source_instance_id');
    }
    public function syncOptions()
    {
        return $this->hasMany(SourceInstanceSyncOption::class, 'source_instances_id', 'source_instance_id');
    }

    public function scopeIsActive(Builder $builder)
    {
        return $builder->where('active_status', 'Y');
    }
}
