<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'platform_name',
        'value_lists'
    ];
}
