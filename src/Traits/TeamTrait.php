<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits;

use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Traits\Cacheable\TeamCacheableTrait;
use Vegvisir\TrustNoSql\Traits\Events\TeamEventsTrait;

trait TeamTrait
{
    use ModelTrait, TeamCacheableTrait, TeamEventsTrait;

    /**
     * Fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Moloquent belongs-to-many relationship with the permission model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(\Vegvisir\TrustNoSql\Models\Permission::class);
    }

    /**
     * Moloquent belongs-to-many relationship with the role model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(\Vegvisir\TrustNoSql\Models\Role::class);
    }

    /**
     * Moloquent belongs-to-many relationship with the user model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(\get_class(Helper::getUserModel()));
    }
}
