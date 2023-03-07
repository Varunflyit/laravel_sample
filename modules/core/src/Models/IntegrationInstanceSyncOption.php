<?php

namespace Ecommify\Core\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class IntegrationInstanceSyncOption extends Model
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
    protected $table = 'integrations_instances_sync_options';

    protected $casts = [
        'attributes' => 'array',
    ];

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
     * Scope a query to only include sync option with key equal orders
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsOrder(Builder $builder)
    {
        return $builder->typeIs('orders');
    }

    /**
     * Scope a query to only include sync option with key equal inventory
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsInventory(Builder $builder)
    {
        return $builder->typeIs('inventory');
    }

    /**
     * Scope a query to only include sync option with key equal products
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsProduct(Builder $builder)
    {
        return $builder->typeIs('products');
    }

    /**
     * Scope a query to only include sync option with key equal tracking
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsTracking(Builder $builder)
    {
        return $builder->typeIs('tracking');
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
