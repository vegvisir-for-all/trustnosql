<?php

namespace Vegvisir\TrustNoSql\Checkers\User;

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

class UserChecker extends BaseChecker {

    /**
     * Checks whether User has given Roles.
     *
     * @param string|array $roles Array of role names or comma-separated string
     * @param string|null $team Team name
     * @param bool $requireAll Set to true if role must have all given permissions
     * @return bool
     */
    public function currentUserHasRoles($roles, $team = null, $requireAll = null)
    {
        return $this->currentModelHasRoles($this->model, $permissions, $requireAll);
    }

}
