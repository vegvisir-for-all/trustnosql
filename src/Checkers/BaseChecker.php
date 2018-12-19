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

    public function __call($name, $arguments)
    {

        /**
         * 1. First, we need to parse called function name (like 'currentRoleHasPermissions')
         *    - splitting into 'Role', 'Has', 'Permissions'
         * 2. Then, we check whether $this->model is of type specified in 'Role'
         * 3. If 'Has', we call internal function, if 'Cached' - still to be disclosed
         * 4. 'Permissions' indicate entity model type
         * 5. $arguments[0] is an entities' list
         * 6. $arguments[1] is a $requireAll
         */

        /**
         * 1. Parsing
         * We'll be using Laravel helpers for convenience
         */

        $parsed = explode('_', snake_case($name));

        $modelName = ucfirst($parsed[1]);
        $entityModelName = ucfirst(str_singular($parsed[3]));

        /**
         * 2. Checking if models are compatible
         */
        if(!Helper::{"is$modelName"}($this->model)) {
            throw new \Exception; // todo
        }

        $functionName = 'currentModel' . ucfirst($parsed[2]) . 'Entities';
        return $this->$functionName($entityModelName, $arguments[0], $arguments[1]);
    }

    protected function currentModelHasEntities($entitiesModel, $entitiesList, $requireAll)
    {

        if(!is_object($entitiesModel)) {
            $className = "\\Vegvisir\\TrustNoSql\\Models\\$entitiesModel";
            $entitiesModel = new $className;
        }

        $entitiesList = Helper::getArray($entitiesList);

        if(empty($entitiesList)) {
            return true;
        }

        $hasEntity = false;

        foreach($entitiesList as $entityName) {

            $hasEntity = $this->currentModelCheckSingleEntity($entitiesModel, $entityName);

            if($hasEntity && !$requireAll) {
                return true;
            } elseif(!$hasEntity && $requireAll) {
                return false;
            }

            return $requireAll;

        }

        return false;

    }

    protected function currentModelCheckSingleEntity($entityModel, $entityName)
    {

        $hasEntity = false;

        /**
         * Check whether $this->model is instance of Role or User
         */

        if(Helper::isRole($this->model)) {
            // role
        } elseif(Helper::isUser($this->model)) {
            // user
        }

        $modelFunctionName = 'get'
            . ucfirst(class_basename($this->model))
            . 'Current'
            . ucfirst(str_plural(class_basename($entityModel)));

        foreach($this->model->$modelFunctionName() as $currentEntity) {

            /**
             * Checking for no-wildcard permission name, like 'city:create'
             */
            if(str_is($entityName, $currentEntity)) {
                return true;
            }

            /**
             * If entity is not a permission, we should continue, omitting wildcard permissions.
             * We also continue is entity is not a wildcard permission
             */
            if(!Helper::isPermission($entityModel)
                || (Helper::isPermission($entityModel) && !Helper::isPermissionWildcard($entityName))) {
                continue;
            }

            /**
             * Another case is when we want to check 'city:*' or 'city:all', and the role has assigned array of no-wildcard
             * permissions. In that case, we need to load all 'city' namespace permissions (excluding those with wildcards)
             * and check, whether a role has all permissions assigned to
             */
            if($this->currentModelCheckSingleWildcardPermission($entityName)) {
                return true;
            }

        }

        return $hasEntity;
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
