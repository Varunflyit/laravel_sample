<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationInstanceAttribute extends Model
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
    protected $table = 'integrations_instances_attributes';
}
