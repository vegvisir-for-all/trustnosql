<?php

namespace Vegvisir\TrustNoSql\Helpers;

use Vegvisir\TrustNoSql\Helpers\PermissionHelper;
use Vegvisir\TrustNoSql\Helpers\RoleHelper;

class Helper
{

    public static function getPermissionId($permissionName)
    {
        return PermissionHelper::getId($permissionName);
    }

    public static function getPermissionsArray($permissions)
    {
        return PermissionHelper::getArray($permissions);
    }

    public static function getPermissionKeys($permissions)
    {
        return PermissionHelper::getKeys($permissions);
    }

    public static function getPermissionWildcards()
    {
        return PermissionHelper::getWildcards();
    }

    public static function getRoleId($roleName)
    {
        return RoleHelper::getId($roleName);
    }

    public static function getRolesArray($roles)
    {
        return RoleHelper::getArray($roles);
    }

    protected static function getArray($rolesOrPermissions)
    {
        if(is_array($rolesOrPermissions)) {
            return $rolesOrPermissions;
        }

        return explode(static::DELIMITER, $rolesOrPermissions);
    }

}
