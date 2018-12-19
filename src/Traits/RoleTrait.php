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
use Vegvisir\TrustNoSql\Traits\ModelTrait;
use Vegvisir\TrustNoSql\Exceptions\Permission\AttachPermissionsException;
use Vegvisir\TrustNoSql\Exceptions\Permission\DetachPermissionsException;

trait RoleTrait {

    use ModelTrait;

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
        return $this->belongsToMany(get_class(Helper::getUserModel()));
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
