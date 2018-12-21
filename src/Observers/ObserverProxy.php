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
