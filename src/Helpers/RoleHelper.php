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
     * If $roles is an array, function returns $roles itself.
     *
     * @param array|string $roles Array of role names or comma-separated string
     *
     * @return array
     */
    public static function getArray($roles)
    {
        return parent::getArray($roles);
    }

    /**
     * Checks whether given role name can be used (i.e. if the name
     * doesn't exist.
     *
     * @param string $name Name of the role
     *
     * @return bool
     */
    public static function checkRoleName($name)
    {
        if (null !== Role::where('name', $name)->first()) {
            // Role with that name already exists
            return false;
        }
        $pattern = '/(^[\p{L}0-9-_]*+)$/mu';
        if (1 !== preg_match($pattern, $name)) {
            // Wrong pattern for name
            return false;
        }

        return true;
    }

    /**
     * Gets an array of roles' keys (_ids).
     *
     * @param array|string $roles Comma-separated values or array
     *
     * @return array
     */
    protected static function getKeys($roles)
    {
        if (!\is_array($roles)) {
            $roles = static::getArray($roles);
        }

        return collect(Role::whereIn('name', $roles)->get())->map(function ($item, $key) {
            return $item->id;
        })->toArray();
    }

    /**
     * Checks whether an object is a role model.
     *
     * @param object $object Object to be checked
     *
     * @return bool
     */
    protected static function isOne($object)
    {
        return is_a(\get_class($object), \get_class(new Role()), true);
    }
}
