<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Observers;

use Illuminate\Support\Facades\Config;

class ObserverProxy
{
    /**
     * List of observer classes for particular model names.
     *
     * @var array
     */
    private static $observers = [
        'Permission' => \Vegvisir\TrustNoSql\Observers\PermissionObserver::class,
        'Role' => \Vegvisir\TrustNoSql\Observers\RoleObserver::class,
        'Team' => \Vegvisir\TrustNoSql\Observers\TeamObserver::class,
        'User' => \Vegvisir\TrustNoSql\Observers\UserObserver::class,
    ];

    /**
     * Get observer for particular model.
     *
     * @param object $object
     *
     * @return BaseObserver
     */
    public static function getModelObserver($object)
    {
        $observers = Config::get('trustnosql.events.observers', static::$observers);

        $observerClass = $observers[class_basename($object)];

        return new $observerClass();
    }
}
