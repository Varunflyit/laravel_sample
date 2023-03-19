<?php

namespace Ecommify\Core\Models;

use Ecommify\Integration\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //use HasUuids;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'core';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'company_id';

    /**
     * The primary key data type for the model.
     *
     * @var string
     */
    protected $keyType = 'string';


    public function orders()
    {
        return $this->hasMany(Order::class, 'company_id');
    }

    public function integrationInstances()
    {
        return $this->hasMany(IntegrationInstance::class, 'company_id');
    }
}
