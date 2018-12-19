<?php

namespace Vegvisir\TrustNoSql\Traits\Events;

trait ModelEventsTrait
{

    public static function trustNoSqlObserve($observer = null)
    {
        $observerName = is_string($observer) ? $observer : get_class($observer);

        foreach(self::$trustNoSqlObservables as $event) {
            static::registerTrustNoSqlEvent(snake_case($event, '.'), $observerName.'@'.$event);
        }
    }

    protected static function registerTrustNoSqlEvent($eventName, $callback)
    {
    }

}
