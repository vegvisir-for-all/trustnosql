<?php

namespace Vegvisir\TrustNoSql\Observers;

use Illuminate\Support\Facades\Config;

class ObserverProxy
{

    private static $observers = [
        'Permission' => \Vegvisir\TrustNoSql\Observers\PermissionObserver::class,
        'Role' => \Vegvisir\TrustNoSql\Observers\RoleObserver::class,
        'Team' => \Vegvisir\TrustNoSql\Observers\TeamObserver::class,
        'User' => \Vegvisir\TrustNoSql\Observers\UserObserver::class,
    ];

    public static function getModelObserver($object)
    {
        $observers = Config::get('trustnosql.events.observers', static::$observers);

        $observerClass = $observers[class_basename($object)];
        return new $observerClass;
    }

}
