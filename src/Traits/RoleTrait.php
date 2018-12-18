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
use Vegvisir\TrustNoSql\Checkers\CheckProxy;
use Vegvisir\TrustNoSql\Exceptions\Permission\AttachPermissionsException;
use Vegvisir\TrustNoSql\Exceptions\Permission\DetachPermissionsException;

trait RoleTrait {

    /**
     * Returns the right checker for the Role model.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\RoleChecker;
     */
    protected function roleChecker()
    {
        return (new CheckProxy($this))->getChecker();
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
     * Moloquent belongs-to-many relationship with the user model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(get_class(Helper::getUserModel()));
    }

    /**
     * Retrieves an array of Role's permission names
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
            $permissionsCollection = $permissionsCollection->where('name', 'like', $namespace . ':%');
        }

        return collect($permissionsCollection->get())->map(function ($item, $key) {
            return $item->name;
        })->toArray();
    }

    /**
     * Retrieves an array of Role's user emails
     *
     * @return array
     */
    public function getRoleCurrentUsers() {

        /**
         * If TrustNoSql uses cache, this should be retrieved by roleCachedPermissions, provided
         * by RoleCacheableTrait
         */
        if(Config::get('trustnosql.cache.use_cache')) {
            return $this->getRoleCachedUsers($namespace);
        }

        /**
         * Otherwise, retrieve a list of current permissions from the DB
         */
        $usersCollection = $this->users();

        return collect($usersCollection->get())->map(function ($item, $key) {
            return $item->email;
        })->toArray();
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

    /**
     * Syncs permission(s) to a Role.
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @return \Vegvisir\TrustNoSql\Models\Role
     */
    public function syncPermissions($permissions)
    {
        $permissionsKeys = Helper::getPermissionKeys($permissions);
        $changes = $this->permissions()->sync($permissionsKeys);

        $this->flushCache();
        $this->fireEvent('permissions.synced', [$this, $changes]);

        return $this;
    }

    /**
     * Attaches permission(s) to a Role
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @return void
     */
    public function attachPermissions($permissions)
    {

        $permissionsKeys = Helper::getPermissionKeys($permissions);

        try {
            $this->permissions()->attach($permissionsKeys);
        } catch (\Exception $e) {
            throw new AttachPermissionsException;
        }

        $this->flushCache();
        $this->fireEvent('permissions.attached', [$this, $permissionsKeys]);

        return $this;
    }

    /**
     * Detaches permission(s) from a Role
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @return void
     */
    public function detachPermissions($permissions)
    {

        $permissionsKeys = Helper::getPermissionKeys($permissions);

        try {
            $this->permissions()->detach($permissionsKeys);
        } catch (\Exception $e) {
            throw new DetachPermissionsException;
        }

        $this->flushCache();
        $this->fireEvent('permissions.attached', [$this, $permissionsKeys]);

        return $this;
    }


}
