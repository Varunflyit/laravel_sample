<?php

namespace Ecommify\Core\Models;

use Ecommify\Integration\Models\Order;
use Ecommify\Integration\Models\OrderTracking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class IntegrationInstance extends Model
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
    protected $table = 'integrations_instances';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'integration_instance_id';

    /**
     * The primary key data type for the model.
     *
     * @var string
     */
    protected $keyType = 'string';
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'integration',
        'instanceAttributes',
        'syncOptions',
        'source'
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        // Fix for has many relation with cross database.
        $this->table = "{$this->getConnection()->getDatabaseName()}.integrations_instances";

        parent::__construct($attributes);
    }

    public function sourceInstance()
    {
        return $this->belongsTo(SourceInstance::class, 'source_instance_id', 'source_instance_id');
    }

    public function source()
    {
        return $this->hasOneThrough(Source::class, SourceInstance::class, 'source_instance_id', 'source_id', 'source_instance_id', 'source_id');
    }

    public function integration()
    {
        return $this->belongsTo(Integration::class, 'integration_id', 'integration_id');
    }

    public function instanceAttributes()
    {
        return $this->hasMany(IntegrationInstanceAttribute::class, 'integration_instance_id', 'integration_instance_id');
    }

    public function syncOptions()
    {
        return $this->hasMany(IntegrationInstanceSyncOption::class, 'integration_instance_id', 'integration_instance_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'integration_instance_id', 'integration_instance_id');
    }

    public function trackings()
    {
        // return $this->hasMany(OrderTracking::class, 'integration_instance_id', 'integration_instance_id');
        return $this->hasManyThrough(OrderTracking::class, Order::class, 'integration_instance_id');
    }

    public function scopeIsActive(Builder $builder)
    {
        return $builder->where('active_status', 'Y');
    }

    /**
     * Instance driver name for Integration Manager
     * @see \Ecommify\Integration\IntegrationManager
     *
     * @return string
     */
    public function getInstanceDriverAttribute()
    {
        return $this->source->source_platform . '-' . $this->integration->channel_platform;
    }

    // TODO: Get from DB
    public function getSyncStatusAttribute()
    {
        return 'Active';
    }

    /**
     * Merge user sync options and custom sync options
     *
     * @param array $attributes
     * @return array
     */
    private function formatSyncOptions($attributes): array
    {
        return Arr::mergeRecursive(
            Arr::get($attributes, 'user', []),
            Arr::get($attributes, 'custom', [])
        );
    }

    public function getOrderSyncOptions(): array
    {
        $options = $this->syncOptions
            ->where('key', 'orders')
            ->where('is_active', 'Y')
            ->value('attributes') ?? [];

        return collect($options)
            ->keyBy('code')
            ->toArray();
    }

    public function getTrackingSyncOptions(): array
    {
        $options = $this->syncOptions
            ->where('key', 'tracking')
            ->where('is_active', 'Y')
            ->value('attributes') ?? [];

        return collect($options)
            ->keyBy('code')
            ->toArray();
    }
}
