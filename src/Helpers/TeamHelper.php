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
     * @param array|string $teams Comma-separated values or array
     *
     * @return array
     */
    protected static function getKeys($teams)
    {
        if (!\is_array($teams)) {
            $teams = static::getArray($teams);
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

    /**
     * Checks whether team functionality is on.
     *
     * @return bool
     */
    public static function isFunctionalityOn()
    {
        return config('trustnosql.teams.use_teams', false);
    }

    /**
     * Checks whether given team name can be used (i.e. if the name
     * doesn't exist.
     *
     * @param string $name Name of the team
     * @return bool
     */
    public static function checkTeamName($name)
    {
        if (!self::isFunctionalityOn()) {
            return false;
        }
        if (null !== Team::where('name', $name)->first()) {
            // Team with that name already exists
            return false;
        }
        return true;
    }
}
