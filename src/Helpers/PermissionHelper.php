<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Helpers;

use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Models\Permission;

class PermissionHelper extends HelperProxy
{
    /**
     * Delimiter for entities string list.
     *
     * @var string
     */
    const ENTITIES_DELIMITER = ',';

    /**
     * Delimiter for permission namespace and rights.
     *
     * @var string
     */
    const NAMESPACE_DELIMITER = '/';

    /**
     * Gets an array from comma-separated values.
     * If $permissions is an array, function returns $permissions itself.
     *
     * @param array|string $permissions Array of permission names or comma-separated string
     *
     * @return array
     */
    public static function getArray($permissions)
    {
        return parent::getArray($permissions);
    }

    /**
     * Gets wildcards for permissions.
     *
     * @return array
     */
    public static function getWildcards()
    {
        return Config::get('trustnosql.permissions.wildcards', ['*', 'all']);
    }

    /**
     * Checks whether given permission is a wildcard permission.
     *
     * @param string $permissionName name of the permission
     *
     * @return array
     */
    public static function isWildcard($permissionName)
    {
        $permissionNameExploded = explode(static::NAMESPACE_DELIMITER, $permissionName);

        return \in_array($permissionNameExploded[1], static::getWildcards(), true);
    }

    /**
     * Returns key (_id) of a permission.
     *
     * @param string $permissionName name of the permission
     *
     * @return string
     */
    protected static function getId($permissionName)
    {
        return static::$model->where('name', $permissionName)->id;
    }

    /**
     * Gets an array of permissions' keys (_ids).
     *
     * @param array|string $permissions Comma-separated values or array
     *
     * @return array
     */
    protected static function getKeys($permissions)
    {
        if (!\is_array($permissions)) {
            $permissions = static::getArray($permissions);
        }

        $wildcards = static::getWildcards();

        $noWildCardPermissions = [];
        $wildCardPermissions = [];

        foreach ($permissions as $permission) {
            if (!static::isWildcard($permission)) {
                $noWildCardPermissions[] = $permission;
            } else {
                $permission = str_replace($wildcards, '*', $permission);
                $wildCardPermissions[] = $permission;
            }
        }

        $permissionKeys = collect(Permission::whereIn('name', $noWildCardPermissions)->get())->map(function ($item, $key) {
            return $item->id;
        })->toArray();

        foreach ($wildCardPermissions as $wildcardPermission) {
            $thisPermissionKeys = collect(Permission::where('name', 'like', static::getNamespace($wildcardPermission).static::NAMESPACE_DELIMITER)->get())->map(function ($item, $key) {
                return $item->id;
            })->toArray();
            $permissionKeys = array_merge($permissionKeys, $thisPermissionKeys);
        }

        return array_unique($permissionKeys);
    }

    /**
     * Gets namespace of a given permission.
     *
     * @param string $permissionName name of the permission
     *
     * @return string
     */
    protected static function getNamespace($permissionName)
    {
        return explode(static::NAMESPACE_DELIMITER, $permissionName)[0];
    }

    /**
     * Checks whether an object is a permission model.
     *
     * @param object $object Object to be checked
     *
     * @return bool
     */
    protected static function isOne($object)
    {
        return is_a(\get_class($object), \get_class(new Permission()), true);
    }

    /**
     * Checks whether given permission name can be used (i.e. if the name
     * doesn't exist and if the name is not a wildcard name).
     *
     * @param string $name Name of the permission
     * @return bool
     */
    public static function checkPermissionName($name)
    {
        if (null !== Permission::where('name', $name)->first()) {
            // Permission with that name already exists
            return false;
        }
        return !static::isWildcard($name);
    }

    public static function getPermissionsInNamespace($namespace)
    {
        return collect(Permission::where('name', 'like', $namespace . '/%')->get())->map(function ($item) {
            return $item->name;
        })->toArray();
    }
}
