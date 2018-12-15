<?php

namespace Vegvisir\TrustNoSql\Traits\Aliases;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */

trait RoleAliasesTrait {

    /**
     * Alias for hasPermissions.
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @param bool $requireAll If set to true, role needs to have all of the given permissions.
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->hasPermissions($permission, false);
    }

    /**
     * Alias for attachPermissions.
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @return void
     */
    public function attachPermission($permission)
    {
        return $this->attachPermissions($permission);
    }

    /**
     * Alias for detachPermissions.
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @return void
     */
    public function detachPermission($permission)
    {
        return $this->detachPermission($permission);
    }

}
