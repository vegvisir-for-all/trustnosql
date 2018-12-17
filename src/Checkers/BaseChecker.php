<?php

namespace Vegvisir\TrustNoSql\Checkers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Role;

class BaseChecker
{
    /**
     * Permission namespace delimiter.
     *
     * @var string
     */
    const NAMESPACE_DELIMITER = '/';

    /**
     * Role model used for checking.
     *
     * @var \Jenssegers\Mongodb\Eloquent\Model
     */
    protected $model;

    /**
     * Available permissions wildcards array.
     *
     * @var array
     */
    protected $wildcards = [];

    /**
     * Protected function names
     */
    protected $functionNames = [];

    /**
     * Creates new instance.
     *
     * @param \Jenssegers\Mongodb\Eloquent\Model $model Role model used for checking
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->wildcards = Helper::getPermissionWildcards();
    }

    /**
     * Checks whether current model has attached permissions.
     *
     * @param string|array $permissions Array of permission names or comma-separated string
     * @param bool $requireAll If set to true, role must have all of the given permissions
     */
    public function currentModelHasPermissions($model, $permissions, $requireAll)
    {

        /**
         * Return true if $permissions is empty
         */
        if((is_string($permissions) && $permissions == '') || (is_array($permissions) && empty($permissions))) {
            return true;
        }

        /**
         * Get array of permissions
         */
        $permissions = Helper::getPermissionsArray($permissions);

        foreach($permissions as $permissionName)
        {
            $hasPermission = $this->currentModelCheckSinglePermission($permissionName);

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

    /**
     * Checks whether model has a single permission, by its name.
     *
     * @param string $permission Permission name.
     * @return bool
     */
    protected function currentModelCheckSinglePermission($permission)
    {

        $hasPermission = false;

        /**
         * Check whether $this->model is instance of Role or User
         */

        if(is_a($this->model, Role::class, true)) {
            $this->functionNames['getModelPermissions'] = 'getRoleCurrentPermissions';
        } elseif(is_a($this->model, get_class(Helper::getUserModel()), true)) {
            $this->functionNames['getModelPermissions'] = 'getUserCurrentPermissions';
        }

        foreach($this->model->{$this->functionNames['getModelPermissions']}() as $currentPermission) {

            /**
             * Checking for no-wildcard permission name, like 'city:create'
             */
            if(str_is($permission, $currentPermission)) {
                return true;
            }

            /**
             * Checking for wildcard permissions.
             * If the permission to be checked is 'city:create' and role has permission of 'city:*' or 'city:all',
             * we need to explode permission name using the ':' delimiter
             */
            $permissionExploded = explode(static::NAMESPACE_DELIMITER, $permission);
            $currentPermissionExploded = explode(static::NAMESPACE_DELIMITER, $currentPermission);

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
                if($this->currentModelCheckSingleWildcardPermission($permission)) {
                    return true;
                }
            }

        }

        return $hasPermission;
    }

    /**
     * Checks whether a model has appropriate permissions, pointed by a wildcard permission.
     *
     * @param string $permission Wildcard permission name
     * @return bool
     */
    protected function currentModelCheckSingleWildcardPermission($permission)
    {

        if(!Helper::isPermissionWildcard($permission)) {
            throw new NoWildcardPermissionException($permission);
        }

        /**
         * List of all permissions for a namespace ([city:view, city:update, city:create, ...])
         */
        $availablePermissions = Permission::getPermissionsInNamespace($namespace);

        /**
         * Current permissions for a role, with a given namespace
         */
        $rolePermissions = $this->model->{$this->functionNames['getModelPermissions']}($namespace);

        /**
         * Since $availablePermissions and $rolePermissions must have the very same values, we use
         * the array_diff function to check for differences. If there are none, the return array of
         * array_diff should be empty.
         */
        return empty(array_diff($availablePermissions, $rolePermissions));

    }

}
