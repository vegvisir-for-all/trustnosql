<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Helpers;

class HelperProxy
{
    /**
     * Delimiter for comma-separated entity string.
     *
     * @var string
     */
    const ENTITIES_DELIMITER = ',';

    /**
     * Checks whether $object is a Permission.
     *
     * @param object $object object to be checked against
     *
     * @return bool
     */
    public static function isPermission($object)
    {
        return PermissionHelper::isOne($object);
    }

    /**
     * Gets _id for a single permission.
     *
     * @param string $permissionName Name of the permission
     *
     * @return string
     */
    public static function getPermissionId($permissionName)
    {
        return PermissionHelper::getId($permissionName);
    }

    /**
     * Gets an array of permissions.
     *
     * @param array|string $permissions Comma-separated permission names or array of them
     *
     * @return array
     */
    public static function getPermissionsArray($permissions)
    {
        return PermissionHelper::getArray($permissions);
    }

    /**
     * Gets an array of permission keys (_ids).
     *
     * @param array|string $permissions Comma-separated permission names or array of them
     *
     * @return array
     */
    public static function getPermissionKeys($permissions)
    {
        return PermissionHelper::getKeys($permissions);
    }

    /**
     * Alias for getPermissionKeys.
     *
     * @param array|string $permissions Comma-separated permission names or array of them
     *
     * @return array
     */
    public static function getPermissionsKeys($permissions)
    {
        return PermissionHelper::getKeys($permissions);
    }

    /**
     * Returns array of permissions in given namespace.
     *
     * @param string $namespace Namespace name
     *
     * @return array
     */
    public static function getPermissionsInNamespace($namespace)
    {
        return PermissionHelper::getPermissionsInNamespace($namespace);
    }

    /**
     * Return extracted namespace of permission name.
     *
     * @param string $permission Permission name
     *
     * @return string
     */
    public static function getPermissionNamespace($permission)
    {
        return PermissionHelper::getNamespace($permission);
    }

    /**
     * Gets permission wildcards from config.
     *
     * @return array
     */
    public static function getPermissionWildcards()
    {
        return PermissionHelper::getWildcards();
    }

    /**
     * Checks whether permission is a wildcard permission ('namespace/*').
     *
     * @param string $permissionName name of the permission
     *
     * @return bool
     */
    public static function isPermissionWildcard($permissionName)
    {
        return PermissionHelper::isWildcard($permissionName);
    }

    /**
     * Checks whether $object is a Role.
     *
     * @param object $object object to be checked against
     *
     * @return bool
     */
    public static function isRole($object)
    {
        return RoleHelper::isOne($object);
    }

    /**
     * Gets _id for a single role.
     *
     * @param string $roleName Name of the role
     *
     * @return string
     */
    public static function getRoleId($roleName)
    {
        return RoleHelper::getId($roleName);
    }

    /**
     * Gets an array of roles keys (_ids).
     *
     * @param array|string $roles Comma-separated roles names or array of them
     *
     * @return array
     */
    public static function getRolesArray($roles)
    {
        return RoleHelper::getArray($roles);
    }

    /**
     * Gets an array of role keys (_ids).
     *
     * @param array|string $roles Comma-separated role names or array of them
     *
     * @return array
     */
    public static function getRoleKeys($roles)
    {
        return RoleHelper::getKeys($roles);
    }

    /**
     * Alias for getRoleKeys.
     *
     * @param array|string $roles Comma-separated role names or array of them
     *
     * @return array
     */
    public static function getRolesKeys($roles)
    {
        return RoleHelper::getKeys($roles);
    }

    /**
     * Checks whether $object is a Team.
     *
     * @param object $object object to be checked against
     *
     * @return bool
     */
    public static function isTeam($object)
    {
        return TeamHelper::isOne($object);
    }

    /**
     * Checks whether team functionality is on.
     *
     * @return bool
     */
    public static function isTeamFunctionalityOn()
    {
        return TeamHelper::isFunctionalityOn();
    }

    /**
     * Gets an array of team keys (_ids).
     *
     * @param array|string $teams Comma-separated team names or array of them
     *
     * @return array
     */
    public static function getTeamKeys($teams)
    {
        return TeamHelper::getKeys($teams);
    }

    /**
     * Alias for getTeamKeys.
     *
     * @param array|string $teams Comma-separated team names or array of them
     *
     * @return array
     */
    public static function getTeamsKeys($teams)
    {
        return self::getTeamKeys($teams);
    }

    /**
     * Checks whether $object is a User.
     *
     * @param object $object object to be checked against
     *
     * @return bool
     */
    public static function isUser($object)
    {
        return UserHelper::isOne($object);
    }

    /**
     * Gets an array of user keys (emails).
     *
     * @param array|string $users Comma-separated user names or array of them
     *
     * @return array
     */
    public static function getUserKeys($users)
    {
        return UserHelper::getKeys($users);
    }

    /**
     * Alias for getUserKeys.
     *
     * @param array|string $users Comma-separated user names or array of them
     *
     * @return array
     */
    public static function getUsersKeys($users)
    {
        return UserHelper::getKeys($users);
    }

    /**
     * Gets a user model used by application.
     *
     * @return Jenssegers\Mongodb\Eloquent\Model
     */
    public static function getUserModel()
    {
        return UserHelper::getModel();
    }

    /**
     * Provide a user logic proxy for middleware checking.
     *
     * @param User $user
     *
     * @return Closure
     */
    public static function getUserLogicProxy($user)
    {
        return UserHelper::logicProxy($user);
    }

    /**
     * Gets an array from comma-separated values.
     * If $rolesOrPermissions is an array, function returns $rolesOrPermissions itself.
     *
     * @param array|string $rolesOrPermissions Comma-separated values or array
     *
     * @return array
     */
    public static function getArray($rolesOrPermissions)
    {
        if (\is_array($rolesOrPermissions)) {
            return $rolesOrPermissions;
        }

        return explode(static::ENTITIES_DELIMITER, $rolesOrPermissions);
    }

    /**
     * Checks whether name of the model is valid. Always returns true for models
     * other than role, permission and team. You can specify model name and name to check
     * or just a model being created.
     *
     * @param object|string $model Model being created or its name
     * @param null|string   $name  (Optional) name of the entity being created
     *
     * @return bool
     */
    public static function checkName($model, $name = null)
    {
        if (!\is_object($model)) {
            $model = new $model();
        } else {
            $name = $model->name;
        }

        if (self::isUser($model)) {
            return true;
        }

        switch (class_basename($model)) {
            case 'Permission':
                return PermissionHelper::checkPermissionName($name);

                break;
            case 'Role':
                return RoleHelper::checkRoleName($name);

                break;
            case 'Team':
                return TeamHelper::checkTeamName($name);

                break;
            default:
                return true;

                break;
        }
    }
}
