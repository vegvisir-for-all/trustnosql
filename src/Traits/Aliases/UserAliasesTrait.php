<?php

namespace Vegvisir\TrustNoSql\Traits\Aliases;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */

trait UserAliasesTrait {

    /**
     * Alias for hasPermissions.
     *
     * @param string|array $permission Array of permissions or comma-separated list.
     * @return bool
     */
    public function hasPermission($role)
    {
        return $this->hasPermissions($role, false);
    }

    /**
     * Alias for attachPermissions.
     *
     * @param string|array $permission Array of permissions or comma-separated list.
     * @return void
     */
    public function attachPermission($permission)
    {
        return $this->attachPermissions($permission);
    }

    /**
     * Alias for detachPermissions
     *
     * @param string|array $permission Array of permissions or comma-separated list.
     * @return void
     */
    public function detachPermission($permission)
    {
        return $this->detachPermissions($permission);
    }

    /**
     * Alias for hasRole.
     *
     * @param string|array $role Array of roles or comma-separated list.
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->hasRoles($role, false);
    }

    /**
     * Alias for attachRoles.
     *
     * @param string|array $role Array of roles or comma-separated list.
     * @return void
     */
    public function attachRole($role)
    {
        return $this->attachRoles($role);
    }

    /**
     * Alias for detachRoles
     *
     * @param string|array $roles Array of roles or comma-separated list.
     * @return void
     */
    public function detachRole($roles)
    {
        return $this->detachRoles($roles);
    }

}
