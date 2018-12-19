<?php

namespace Vegvisir\TrustNoSql\Traits;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Traits\ModelTrait;
use Vegvisir\TrustNoSql\Traits\Cacheable\UserCacheableTrait;
use Vegvisir\TrustNoSql\Traits\Events\UserEventsTrait;

trait UserTrait
{

    use ModelTrait, UserCacheableTrait, UserEventsTrait;

    /**
     * Moloquent belongs-to-many relationship with the Role model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function roles()
    {
        $roles = $this->belongsToMany(\Vegvisir\TrustNoSql\Models\Role::class);

        return $roles;
    }

    /**
     * Moloquent belongs-to-many relationship with the permission model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function permissions()
    {
        $permissions = $this->belongsToMany(\Vegvisir\TrustNoSql\Models\Permission::class);

        return $permissions;
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


}
