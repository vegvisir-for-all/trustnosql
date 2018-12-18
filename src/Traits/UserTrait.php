<?php

namespace Vegvisir\TrustNoSql\Traits;

use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Checkers\CheckProxy;
use Vegvisir\TrustNoSql\Traits\Aliases\UserAliasesTrait;
use Vegvisir\TrustNoSql\Exceptions\Role\AttachRolesException;
use Vegvisir\TrustNoSql\Exceptions\Role\DetachRolesException;

trait UserTrait
{

    use UserAliasesTrait;

    /**
     * Returns the right checker for the User model.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\User\UserChecker;
     */
    protected function roleChecker()
    {
        return (new CheckProxy($this))->getChecker();
    }

    public function roles()
    {
        $roles = $this->belongsToMany(\Vegvisir\TrustNoSql\Models\Role::class);

        return $roles;
    }

    public function rolesTeams()
    {

    }

    public function permissions()
    {
        $permissions = $this->belongsToMany(\Vegvisir\TrustNoSql\Models\Permission::class);

        return $permissions;
    }

    /**
     * Retrieves an array of User's permission names
     *
     * @return array
     */
    public function getUserCurrentPermissions($namespace = null) {

        /**
         * If TrustNoSql uses cache, this should be retrieved by userCachedPermissions, provided
         * by UserCacheableTrait
         */
        if(Config::get('trustnosql.cache.use_cache')) {
            return $this->getUserCachedPermissions($namespace);
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

    public function hasPermissions($permissions, $team = null, $requireAll = false)
    {
        return $this->roleChecker()->currentUserHasPermissions($permissions, $requireAll);
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
     * Attaches permission(s) to a User
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
     * Detaches permission(s) from a User
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

    /**
     * Retrieves an array of User's role names
     *
     * @return array
     */
    public function getUserCurrentRoles() {

        /**
         * If TrustNoSql uses cache, this should be retrieved by roleCachedPermissions, provided
         * by RoleCacheableTrait
         */
        if(Config::get('trustnosql.cache.use_cache')) {
            return $this->getUserCachedRoles();
        }

        $rolesCollection = $this->roles();

        return collect($rolesCollection->get())->map(function ($item, $key) {
            return $item->name;
        })->toArray();
    }

    /**
     * Checks whether User has a given role(s).
     *
     * @param string|array $roles Array of roles or comma-separated list.
     * @param bool $requireAll If set to true, role needs to have all of the given roles.
     * @return bool
     */
    public function hasRoles($roles, $team = null, $requireAll = false)
    {
        return $this->roleChecker()->currentUserHasRole($roles, $team, $requireAll);
    }

    /**
     * Syncs role(s) to User.
     *
     * @param string|array $roles Array of roles or comma-separated list.
     * @return Object
     */
    public function syncRoles($roles)
    {
        $rolesKeys = Helper::getRolesKeys($roles);
        $changes = $this->roles()->sync($rolesKeys);

        $this->flushCache();
        $this->fireEvent('roles.synced', [$this, $changes]);

        return $this;
    }

    /**
     * Attaches role(s) to User
     *
     * @param string|array $roles Array of roles or comma-separated list.
     * @return void
     */
    public function attachRoles($roles)
    {

        $rolesKeys = Helper::getRolesKeys($roles);

        try {
            $this->roles()->attach($rolesKeys);
        } catch (\Exception $e) {
            throw new AttachRolesException;
        }

        $this->flushCache();
        $this->fireEvent('roles.attached', [$this, $rolesKeys]);

        return $this;
    }

    /**
     * Detach role(s) from User
     *
     * @param string|array $roles Array of roles or comma-separated list.
     * @return void
     */
    public function detachRoles($roles)
    {

        $rolesKeys = Helper::getRolesKeys($roles);

        try {
            $this->roles()->detach($rolesKeys);
        } catch (\Exception $e) {
            throw new DetachRolesException;
        }

        $this->flushCache();
        $this->fireEvent('roles.detached', [$this, $rolesKeys]);

        return $this;
    }


}
