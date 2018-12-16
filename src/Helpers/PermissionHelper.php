<?php

namespace Vegvisir\TrustNoSql\Helpers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
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
     * Returns key (_id) of a permission
     *
     * @param string $permissionName Name of the permission.
     * @return string
     */
    protected static function getId($permissionName)
    {
        return static::$model->where('name', $permissionName)->id;
    }

    /**
     * Gets an array from comma-separated values.
     * If $permissions is an array, function returns $permissions itself
     *
     * @param string|array $permissions
     * @return array
     */
    public static function getArray($permissions)
    {
        return parent::getArray($permissions);
    }

    /**
     * Gets an array of permissions' keys (_ids)
     *
     * @param string|array $permissions Comma-separated values or array
     * @return array
     */
    protected static function getKeys($permissions)
    {
        if(!is_array($permissions)) {
            $permissions = static::getArray($permissions);
        }

        $wildcards = static::getWildcards();

        $noWildCardPermissions = [];
        $wildCardPermissions = [];

        foreach($permissions as $permission) {
            if(!static::isWildcard($permission)) {
                $noWildCardPermissions[] = $permission;
            } else {
                $permission = str_replace($wildcards, '*', $permission);
                $wildCardPermissions[] = $permission;
            }
        }

        $permissionKeys = collect(Permission::whereIn('name', $noWildCardPermissions)->get())->map(function ($item, $key) {
            return $item->id;
        })->toArray();

        foreach($wildCardPermissions as $wildcardPermission) {
            $thisPermissionKeys = collect(Permission::where('name', 'like', static::getNamespace($wildcardPermission) . static::NAMESPACE_DELIMITER)->get())->map(function ($item, $key) {
                return $item->id;
            })->toArray();
            $permissionKeys = array_merge($permissionKeys, $thisPermissionKeys);
        }

        return array_unique($permissionKeys);

    }

    /**
     * Gets namespace of a given permission.
     *
     * @param string $permissionName Name of the permission.
     * @return string
     */
    protected static function getNamespace($permissionName)
    {
        return explode(static::NAMESPACE_DELIMITER, $permissionName)[0];
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
     * @param string $permissionName Name of the permission.
     * @return array
     */
    public static function isWildcard($permissionName)
    {
        $permissionNameExploded = explode(static::NAMESPACE_DELIMITER, $permissionName);

        return in_array($permissionNameExploded[1], static::getWildcards());
    }

}
