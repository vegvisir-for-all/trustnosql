<?php

namespace Vegvisir\TrustNoSql\Helpers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Models\Role;

class RoleHelper extends HelperProxy
{

    /**
     * Entities delimiter.
     *
     * @var string
     */
    const ENTITIES_DELIMITER = ',';

    /**
     * Gets an array from comma-separated values.
     * If $roles is an array, function returns $roles itself
     *
     * @param string|array $roles Array of role names or comma-separated string
     * @return array
     */
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

    /**
     * Checks whether an object is a role model
     *
     * @param Object $object Object to be checked
     * @return bool
     */
    protected static function isOne($object)
    {
        return is_a(get_class($object), get_class(new Role), true);
    }

}
