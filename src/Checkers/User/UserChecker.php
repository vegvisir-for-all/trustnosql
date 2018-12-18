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

    public function currentUserHasRole($roles, $team = null, $requireAll = null)
    {

        /**
         * Return true if $roles is empty
         */
        if((is_string($roles) && $roles == '') || (is_array($roles) && empty($roles))) {
            return true;
        }

        /**
         * Get array of roles
         */
        $roles = Helper::getRolesArray($roles);

        foreach($roles as $roleName)
        {
            $hasRole = $this->currentUserCheckSingleRole($roleName);

            if($hasRole && !$requireAll) {
                return true;
            } elseif(!$hasRole && $requireAll) {
                return false;
            }

            /**
             * If we've made it this far, and $requireAll is FALSE, then none of the permissions were found
             * If we've made it this far, and $requireAll is TRUE, then all of the permissions were found
             * Thus, we need to return value of $requireAll
             */
            return $requireAll;
        }

        return false;
    }

    protected function currentUserCheckSingleRole($roleName)
    {

        $hasRole = false;

        foreach($this->model->getUserCurrentRoles() as $currentRole) {

            if(str_is($roleName, $currentRole)) {
                return true;
            }

        }

        return $hasRole;
    }

    public function currentUserHasPermissions($permissions, $requireAll)
    {
        return $this->currentModelHasPermissions($this->model, $permissions, $requireAll);
    }

}
