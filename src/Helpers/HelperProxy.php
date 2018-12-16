<?php

namespace Vegvisir\TrustNoSql\Helpers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Helpers\PermissionHelper;
use Vegvisir\TrustNoSql\Helpers\RoleHelper;
use Vegvisir\TrustNoSql\Helpers\UserHelper;

class HelperProxy
{

    /**
     * Gets _id for a single permission
     *
     * @param string $permissionName Name of the permission
     * @return string
     */
    public static function getPermissionId($permissionName)
    {
        return PermissionHelper::getId($permissionName);
    }

    /**
     * Gets an array of permissions
     *
     * @param string|array $permissions Comma-separated permission names or array of them
     * @return array
     */
    public static function getPermissionsArray($permissions)
    {
        return PermissionHelper::getArray($permissions);
    }

    /**
     * Gets an array of permission keys (_ids)
     *
     * @param string|array $permissions Comma-separated permission names or array of them
     * @return array
     */
    public static function getPermissionKeys($permissions)
    {
        return PermissionHelper::getKeys($permissions);
    }

    /**
     * Gets permission wildcards from config
     *
     * @return array
     */
    public static function getPermissionWildcards()
    {
        return PermissionHelper::getWildcards();
    }

    /**
     * Gets _id for a single role
     *
     * @param string $roleName Name of the role
     * @return string
     */
    public static function getRoleId($roleName)
    {
        return RoleHelper::getId($roleName);
    }
    /**
     * Gets an array of roles keys (_ids)
     *
     * @param string|array $roles Comma-separated roles names or array of them
     * @return array
     */
    public static function getRolesArray($roles)
    {
        return RoleHelper::getArray($roles);
    }

    /**
     * Gets an array of role keys (_ids)
     *
     * @param string|array $roles Comma-separated role names or array of them
     * @return array
     */
    public static function getRolesKeys($roles)
    {
        return RoleHelper::getKeys($roles);
    }

    /**
     * Gets a user model used by application
     *
     * @return Jenssegers\Mongodb\Eloquent\Model
     */
    public static function getUserModel()
    {
        return UserHelper::getModel();
    }

    /**
     * Gets an array from comma-separated values.
     * If $rolesOrPermissions is an array, function returns $rolesOrPermissions itself
     *
     * @param string|array $rolesOrPermissions Comma-separated values or array
     * @return array
     */
    protected static function getArray($rolesOrPermissions)
    {
        if(is_array($rolesOrPermissions)) {
            return $rolesOrPermissions;
        }

        return explode(static::DELIMITER, $rolesOrPermissions);
    }

}
