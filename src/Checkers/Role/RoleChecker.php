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

    public function currentRoleHasPermissions($permissions, $requireAll)
    {
        return $this->currentModelHasPermissions($this->model, $permissions, $requireAll);
    }

}
