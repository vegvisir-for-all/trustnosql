<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits\Aliases;

trait ModelAliasesTrait
{

    /**
     * Alias for hasPermission
     *
     * @param string $permissionName Permission name.
     *
     * @return bool
     */
    public function does($permissionName)
    {
        return $this->hasPermission($permissionName);
    }

    /**
     * Alias for hasRole
     *
     * @param string $roleName Role name.
     *
     * @return bool
     */
    public function isA($roleName)
    {
        return $this->hasEntities('role', $roleName, false);
    }

    /**
     * Alias for hasRole
     *
     * @param string $roleName Role name.
     *
     * @return bool
     */
    public function isAn($roleName)
    {
        return $this->hasEntities('role', $roleName, false);
    }

    /**
     * Alias for hasTeam
     *
     * @param string $teamName Team name.
     *
     * @return bool
     */
    public function memberOf($teamName)
    {
        return $this->hasEntities('team', $teamName, false);
    }
}
