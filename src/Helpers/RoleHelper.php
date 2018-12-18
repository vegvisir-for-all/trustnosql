<?php

namespace Vegvisir\TrustNoSql\Helpers;

use Vegvisir\TrustNoSql\Models\Role;

class RoleHelper extends HelperProxy
{

    const ENTITIES_DELIMITER = ',';

    protected static function getId($roleName)
    {

    }

    public static function getArray($roles)
    {
        return parent::getArray($roles);
    }

    /**
     * Gets an array of roles' keys (_ids)
     *
     * @param string|array $roles Comma-separated values or array
     * @return array
     */
    protected static function getKeys($roles)
    {
        if(!is_array($roles)) {
            $roles = static::getArray($roles);
        }

        return collect(Role::whereIn('name', $roles)->get())->map(function ($item, $key) {
            return $item->id;
        })->toArray();

    }

}
