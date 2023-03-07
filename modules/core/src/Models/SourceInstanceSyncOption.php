<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SourceInstanceSyncOption extends Model
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
    protected $table = 'source_instances_sync_options';

    /**
     * Scope a query to only include sync option key based on a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTypeIs(Builder $builder, $type)
    {
        return $builder->where('key', $type);
    }


    /**
     * Scope a query to only include sync option with key equal products
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsProductImport(Builder $builder)
    {
        return $builder->typeIs('product import');
    }

    /**
     * Scope a query to only include active sync option.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsActive(Builder $builder)
    {
        return $builder->where('is_active', 'Y')
            ->where('is_activated', 'Y');
    }
}