<?php

namespace Vegvisir\TrustNoSql\Checkers\Role;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Checkers\BaseChecker;
use Vegvisir\TrustNoSql\Exceptions\Permission\NoWildcardPermissionException;
use Vegvisir\TrustNoSql\Models\Permission;

class RoleChecker extends BaseChecker {

    /**
     * Checks whether Role has given Permissions.
     *
     * @param string|array $permissions Array of permission names or comma-separated string
     * @param bool $requireAll Set to true if role must have all given permissions
     * @return bool
     */
    public function currentRoleHasPermissions($permissions, $requireAll)
    {
        return $this->currentModelHasPermissions($this->model, $permissions, $requireAll);
    }

}
