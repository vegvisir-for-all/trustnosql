<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits;

use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Traits\Cacheable\RoleCacheableTrait;
use Vegvisir\TrustNoSql\Traits\Events\RoleEventsTrait;

trait RoleTrait
{
    use ModelTrait, RoleCacheableTrait, RoleEventsTrait;

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
     * Moloquent belongs-to-many relationship with the team model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(\Vegvisir\TrustNoSql\Models\Team::class);
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
