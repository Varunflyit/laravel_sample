<?php

namespace Ecommify\Core\Models;

use Illuminate\Database\Eloquent\Model;

class AuthAssignment extends Model
{
    public const SUPER_ADMIN = 'superAdmin';

    public const ADMIN = 'admin';

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
    protected $table = 'auth_assignment';
}
