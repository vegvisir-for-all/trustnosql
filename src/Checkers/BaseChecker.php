<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Checkers;

use Illuminate\Support\Facades\Config;
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Exceptions\Model\ModelTypeMismatchException;
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
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->wildcards = Helper::getPermissionWildcards();
    }

    /**
     * Calls appropriate proxy method when currentModelHasRoles (or similar) method
     * was called.
     *
     * @param mixed $name
     * @param mixed $arguments
     */
    public function __call($name, $arguments)
    {
        $parsed = explode('_', snake_case($name));

        $modelName = ucfirst($parsed[1]);
        $entityModelName = ucfirst(str_singular($parsed[3]));

        if (!Helper::{"is${modelName}"}($this->model)) {
            throw new ModelTypeMismatchException();
        }

        $functionName = 'currentModel'.ucfirst($parsed[2]).'Entities';

        return $this->{$functionName}($entityModelName, $arguments[0], $arguments[1]);
    }

    /**
     * Checks if current model has entities attached to.
     *
     * @param object|string $entitiesModel Name of model (or model itself)
     * @param array|string  $entitiesList  Array of entity names or comma-separated list
     * @param bool          $requireAll    Set to true if model must have all entities
     *
     * @return bool
     */
    protected function currentModelHasEntities($entitiesModel, $entitiesList, $requireAll)
    {
        if (!\is_object($entitiesModel)) {
            if ('User' === $entitiesModel) {
                $className = '\\'.Config::get('trustnosql.user_models.users');
            } else {
                $className = "\\Vegvisir\\TrustNoSql\\Models\\${entitiesModel}";
            }

            $entitiesModel = new $className();
        }

        $entitiesList = Helper::getArray($entitiesList);

        if (empty($entitiesList)) {
            return true;
        }

        $hasEntity = false;

        foreach ($entitiesList as $entityName) {
            $hasEntity = $this->currentModelCheckSingleEntity($entitiesModel, $entityName);

            if ($hasEntity && !$requireAll) {
                return true;
            }
            if (!$hasEntity && $requireAll) {
                return false;
            }

            return $requireAll;
        }

        return false;
    }

    /**
     * Checks if current model has a signle entity attached to.
     *
     * @param object|string $entitiesModel Name of model (or model itself)
     * @param string        $entitiyName   Entity name
     * @param mixed         $entityModel
     * @param mixed         $entityName
     *
     * @return bool
     */
    protected function currentModelCheckSingleEntity($entityModel, $entityName)
    {
        $hasEntity = false;

        $modelFunctionName = 'get'
            .ucfirst(class_basename($this->model))
            .'Current'
            .ucfirst(str_plural(class_basename($entityModel)));

        if (Helper::isPermission($entityModel) && Helper::isPermissionWildcard($entityName)) {
            return $this->currentModelCheckSingleWildcardPermission($entityName);
        }

        foreach ($this->model->{$modelFunctionName}() as $currentEntity) {
            // Checking for no-wildcard permission name, like 'city/create'
            if (str_is($entityName, $currentEntity)) {
                return true;
            }
        }

        if (Helper::isUser($this->model) && Helper::isPermission($entityModel)) {
            // Checking for permissions for user roles
            foreach ($this->model->roles() as $role) {
                if ($role->hasPermission($entityName)) {
                    return true;
                }
            }
        }

        return $hasEntity;
    }

    /**
     * Checks whether a model has appropriate permissions, pointed by a wildcard permission.
     *
     * @param string $permission Wildcard permission name
     *
     * @return bool
     */
    protected function currentModelCheckSingleWildcardPermission($permission)
    {
        if (!Helper::isPermissionWildcard($permission)) {
            throw new NoWildcardPermissionException($permission);
        }

        $namespace = Helper::getPermissionNamespace($permission);

        /**
         * List of all permissions for a namespace ([city:view, city:update, city:create, ...]).
         */
        $availablePermissions = Helper::getPermissionsInNamespace($namespace);

        /**
         * Current permissions for a role, with a given namespace.
         */
        $modelName = \get_class($this->model);

        if (is_a($modelName, Config::get('trustnosql.user_models.users'), true)) {
            $modelName = 'User';
        }

        $modelPermissions = $this->model->getModelCurrentEntities('permission', $namespace, true);

        /*
         * Since $availablePermissions and $rolePermissions must have the very same values, we use
         * the array_diff function to check for differences. If there are none, the return array of
         * array_diff should be empty.
         */
        if ((bool) empty(array_diff($availablePermissions, $modelPermissions))) {
            return true;
        }

        if (Helper::isUser($this->model)) {
            foreach ($this->model->roles() as $role) {
                if ($role->hasPermission($permission)) {
                    return true;
                }
            }
        }

        return false;
    }
}
