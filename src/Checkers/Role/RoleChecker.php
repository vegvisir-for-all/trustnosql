<?php

namespace Vegvisir\TrustNoSql\Checkers\Role;

use Vegvisir\TrustNoSql\Checkers\BaseChecker;
use Vegvisir\TrustNoSql\Exceptions\Permission\NoWildcardPermissionException;
use Vegvisir\TrustNoSql\Models\Permission;

class RoleChecker extends BaseChecker {

    public function currentRoleHasPermissions($permissions, $requireAll)
    {

        /**
         * Return true if $permissions is empty
         */
        if((is_string($permissions) && $permissions == '') || (is_array($permissions) && empty($permissions))) {
            return true;
        }

        /**
         * Changing string $permissions to array $permissions
         */
        if(!is_array($permissions)) {
            $permissions = explode(',', $permissions);
        }

        foreach($permissions as $permissionName)
        {
            $hasPermission = $this->currentRoleCheckSinglePermission($permissionName);

            if($hasPermission && !$requireAll) {
                return true;
            } elseif(!$hasPermission && $requireAll) {
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

    protected function currentRoleCheckSinglePermission($permission)
    {

        $hasPermission = false;

        foreach($this->model->getRoleCurrentPermissions() as $currentPermission) {

            /**
             * Checking for no-wildcard permission name, like 'city:create'
             */
            if(str_is($permission, $currentPermission['name'])) {
                return true;
            }

            /**
             * Checking for wildcard permissions.
             * If the permission to be checked is 'city:create' and role has permission of 'city:*' or 'city:all',
             * we need to explode permission name using the ':' delimiter
             */
            $permissionExploded = explode(':', $permission);
            $currentPermissionExploded = explode(':', $permissionExploded);

             /**
              * Now, if a role has a permission 'city:*' or 'city:all', we return true
              */
            if($currentPermissionExploded[0] == $permissionExploded[0]) {
                // Both namespaces are 'city'

                if(in_array($currentPermissionExploded[1], $this->wildcards)) {
                    return true;
                }
            }

            /**
             * Another case is when we want to check 'city:*' or 'city:all', and the role has assigned array of no-wildcard
             * permissions. In that case, we need to load all 'city' namespace permissions (excluding those with wildcards)
             * and check, whether a role has all permissions assigned to
             */
            if(in_array($permissionExploded[1], $this->wildcards)) {
                if($this->currentRoleCheckSingleWildcardPermission($permission)) {
                    return true;
                }
            }

        }

        return $hasPermission;
    }

    protected function currentRoleCheckSingleWildcardPermission($permission)
    {
        $permissionExploded = explode(':', $permission);
        $namespace = $permissionExploded[0];

        if(!in_array($permissionExploded[1], $this->wildcards)) {
            throw new NoWildcardPermissionException($permission);
        }

        /**
         * List of all permissions for a namespace ([city:view, city:update, city:create, ...])
         */
        $availablePermissions = Permission::getPermissionsInNamespace($namespace);

        /**
         * Current permissions for a role, with a given namespace
         */
        $rolePermissions = $this->model->getRoleCurrentPermissions($namespace);

        /**
         * Since $availablePermissions and $rolePermissions must have the very same values, we use
         * the array_diff function to check for differences. If there are none, the return array of
         * array_diff should be empty.
         */
        return empty(array_diff($availablePermissions, $rolePermissions));


    }

}
