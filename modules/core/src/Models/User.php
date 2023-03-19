<?php

namespace Ecommify\Core\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

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
    protected $table = 'user';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'userCompanies:user_id,company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'password_reset_token',
        'access_token',
        'auth_key'
    ];

    /**
     * The primary key data type for the model.
     *
     * @var string
     */
    protected $keyType = 'string';
    
    public function tokens()
    {
        return $this->hasMany(UserToken::class);
    }

    public function userCompanies()
    {
        return $this->hasMany(UserCompanyMap::class);
    }

    public function companies()
    {
        return $this->hasManyThrough(Company::class, UserCompanyMap::class, 'user_id', 'company_id', 'id', 'company_id');
    }

    public function authAssignments()
    {
        return $this->hasMany(AuthAssignment::class);
    }

    public function hasRole(string $role)
    {
        return in_array(
            $role,
            $this->authAssignments->pluck('item_name')->toArray()
        );
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(AuthAssignment::SUPER_ADMIN);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(AuthAssignment::ADMIN);
    }

    public function isCompanyMember(Company $company)
    {
        return in_array($company->company_id, $this->userCompanies->pluck('company_id')->toArray());
    }
}
