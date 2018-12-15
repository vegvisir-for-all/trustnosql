<?php

namespace Vegvisir\TrustNoSql\Traits;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Checkers\CheckManager;

trait RoleTrait {

    /**
     * Returns the right checker for the Role model.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\BaseChecker;
     */
    protected function roleChecker()
    {
        return (new CheckManager($this))->getChecker();
    }

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
     * Retrieves an array of Role's permission names, excluding wildcard permissions
     *
     * @return array
     */
    public function getRoleCurrentPermissions($namespace = null) {

        /**
         * If TrustNoSql uses cache, this should be retrieved by roleCachedPermissions, provided
         * by RoleCacheableTrait
         */
        if(Config::get('trustnosql.cache.use_cache')) {
            return $this->getRoleCachedPermissions($namespace);
        }

        /**
         * Otherwise, retrieve a list of current permissions from the DB
         */
        $permissionsCollection = $this->permissions();

        if($namespace !== null) {
            $permissionsCollection = $permissionsCollection->where('name', 'like', $namespace . ':');
        }

        return collect($permissionsCollection->get())->map(function ($item, $key) {
            return $item->name;
        });
    }

    /**
     * Checkes whether Role has a given permission(s).
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @param bool $requireAll If set to true, role needs to have all of the given permissions.
     * @return bool
     */
    public function hasPermissions($permissions, $requireAll = true)
    {
        return $this->roleChecker()->currentRoleHasPermissions($permissions, $requireAll);
    }

}
