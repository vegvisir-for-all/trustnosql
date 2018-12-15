<?php

namespace Vegvisir\TrustNoSql\Traits\Aliases;

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
