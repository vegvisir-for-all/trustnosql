<?php

namespace Vegvisir\TrustNoSql\Observers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
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
     * @param Object $object
     * @return BaseObserver
     */
    public static function getModelObserver($object)
    {
        $observers = Config::get('trustnosql.events.observers', static::$observers);

        $observerClass = $observers[class_basename($object)];
        return new $observerClass;
    }

}
