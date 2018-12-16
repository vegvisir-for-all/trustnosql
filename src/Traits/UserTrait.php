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

    public function hasPermissions($permission, $team = null, $requireAll = false)
    {
        $userChecker = CheckProxy::getChecker();
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
     * Detach role(s) to User
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
