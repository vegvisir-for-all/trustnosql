<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * (c) Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 *
 * This source file is subject to the GPL-3.0-or-later license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vegvisir\TrustNoSql\Helpers;

use Vegvisir\TrustNoSql\Models\Team;

class TeamHelper extends HelperProxy
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
     * @param mixed        $teams
     *
     * @return array
     */
    public static function getArray($teams)
    {
        return parent::getArray($teams);
    }

    /**
     * Gets an array of roles' keys (_ids).
     *
     * @param array|string $roles Comma-separated values or array
     * @param mixed        $teams
     *
     * @return array
     */
    protected static function getKeys($teams)
    {
        if (!\is_array($teams)) {
            $roles = static::getArray($teams);
        }

        return collect(Team::whereIn('name', (array) $teams)->get())->map(function ($item, $key) {
            return $item->id;
        })->toArray();
    }

    /**
     * Checks whether an object is a team model.
     *
     * @param object $object Object to be checked
     *
     * @return bool
     */
    protected static function isOne($object)
    {
        return is_a(\get_class($object), \get_class(new Team()), true);
    }
}
