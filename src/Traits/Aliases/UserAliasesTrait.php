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

}
