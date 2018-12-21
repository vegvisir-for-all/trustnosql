<?php

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Models;

use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Vegvisir\TrustNoSql\Traits\UserTrait as TrustNoSqlUserTrait;

class User extends Authenticatable
{
    use TrustNoSqlUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
