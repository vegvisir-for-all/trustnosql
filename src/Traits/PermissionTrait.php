<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * (c) Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 *
 * This source file is subject to the GPL-3.0-or-later license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vegvisir\TrustNoSql\Traits;

use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Traits\Cacheable\PermissionCacheableTrait;
use Vegvisir\TrustNoSql\Traits\Events\PermissionEventsTrait;

trait PermissionTrait
{
    use ModelTrait, PermissionCacheableTrait, PermissionEventsTrait;

    /**
     * Moloquent belongs-to-many relationship with the permission model.
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
