<?php

namespace Vegvisir\TrustNoSql\Helpers;

use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Models\Permission;

class PermissionHelper
{

    const DELIMITER = ',';
    const NAMESPACE_DELIMITER = ':';

    protected static function getId($permissionName)
    {

    }

    protected static function getArray($permissions)
    {
        parent::getArray($permissions);
    }

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

        $permissionKeys = collect(Permissions::whereIn('name', $noWildCardPermissions)->get())->map(function ($item, $key) {
            return $item->id;
        });

        foreach($wildCardPermissions as $wildcardPermission) {
            $thisPermissionKeys = collect(Permissions::where('name', 'like', static::getNamespace($wildcardPermission) . static::NAMESPACE_DELIMITER)->get())->map(function ($item, $key) {
                return $item->id;
            });
            $permissionKeys = array_merge($permissionKeys, $thisPermissionKeys);
        }

        return array_unique($permissionKeys);

    }

    protected static function getNamespace($permissionName)
    {
        return explode(static::NAMESPACE_DELIMITER, $permissionName)[0];
    }

    protected static function getWildcards()
    {
        return Config::get('trustnosql.permissions.wildcards', ['*', 'all']);
    }

    protected static function isWildcard($permissionName)
    {
        $permissionNameExploded = explode(static::DELIMITER, $permissionName);

        return in_array($permissionNameExploded[1], static::getWildcards());
    }

}
