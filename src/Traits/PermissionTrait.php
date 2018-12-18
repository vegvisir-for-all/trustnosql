<?php

namespace Vegvisir\TrustNoSql\Traits;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Helper;

trait PermissionTrait {

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
        return $this->belongsToMany(get_class(Helper::getUserModel()));
    }

    /**
     * Retrieves an array of Permission's role names
     *
     * @return array
     */
    public function getPermissionCurrentRoles() {

        /**
         * If TrustNoSql uses cache, this should be retrieved by roleCachedPermissions, provided
         * by RoleCacheableTrait
         */
        if(Config::get('trustnosql.cache.use_cache')) {
            return $this->getPermissionCachedRoles($namespace);
        }

        /**
         * Otherwise, retrieve a list of current permissions from the DB
         */
        $rolesCollection = $this->roles();

        return collect($rolesCollection->get())->map(function ($item, $key) {
            return $item->name;
        })->toArray();
    }

}
